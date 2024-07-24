<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;
use OpenApi\Annotations as OA;
use Illuminate\Support\Facades\Log;
use thiagoalessio\TesseractOCR\TesseractOCR;
use App\Models\Driver;

class UploadImagesController extends Controller
{
    /**
 * @OA\Post(
 *     path="/api/upload-identity-card",
 *     summary="Upload two images of identity card (front and back side) to Azure CDN",
 *     tags={"Upload"},
 *     security={{"bearerAuth": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="file1",
 *                     type="string",
 *                     format="binary",
 *                 ),
 *                 @OA\Property(
 *                     property="file2",
 *                     type="string",
 *                     format="binary",
 *                 ),
 *             ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Success",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Obje slike su prepoznate kao valjane i uspješno su spremljene."),
 *             @OA\Property(property="file_url1", type="string", example="http://example.com/path_to_file1"),
 *             @OA\Property(property="file_url2", type="string", example="http://example.com/path_to_file2"),
 *             @OA\Property(property="extracted_text1", type="string"),
 *             @OA\Property(property="extracted_text2", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad Request",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Prva slika nije prepoznata kao osobna iskaznica.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="User not authenticated")
 *         )
 *     )
 * )
 */

 public function uploadIdentityCard(Request $request)
{
    $request->validate([
        'file1' => 'required|mimes:jpeg,png,jpg,gif,svg|max:10000',
        'file2' => 'required|mimes:jpeg,png,jpg,gif,svg|max:10000',
    ]);

    // Fetch the authenticated user
    $user = auth()->user(); // Use Laravel's auth helper to get the current authenticated user
    if (!$user) {
        return response()->json(['error' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
    }

    // Check if the user is a driver
    $driver = Driver::where('user_id', $user->id)->first();
    if (!$driver) {
        return response()->json(['error' => 'User is not a driver'], Response::HTTP_FORBIDDEN);
    }

    $file1 = $request->file('file1');
    $filePath1 = $file1->getPathName();
    $fileName1 = time() . '_file1_' . $file1->getClientOriginalName();

    $file2 = $request->file('file2');
    $filePath2 = $file2->getPathName();
    $fileName2 = time() . '_file2_' . $file2->getClientOriginalName();

    // Validate first image contains the required text
    $text1 = $this->extractTextWithOCR1($filePath1);
    Log::info('Extracted text from file1: ' . $text1);

    if (!$this->containsRequiredText1($text1, $driver)) {
        return response()->json(['error' => 'Prva slika nije prepoznata kao osobna iskaznica.'], Response::HTTP_BAD_REQUEST);
    }

    // Validate second image contains the required text
    $text2 = $this->extractTextWithOCR1($filePath2);
    Log::info('Extracted text from file2: ' . $text2);

    // Check if the second image contains the required OIB and additional phrases
    if (!$this->containsRequiredText2($text2, $driver->OIB)) {
        return response()->json(['error' => 'Druga slika nije prepoznata kao valjana.'], Response::HTTP_BAD_REQUEST);
    }

    // Azure Storage configuration using SAS token
    $accountName = env('AZURE_STORAGE_ACCOUNT_NAME');
    $sasToken = env('AZURE_STORAGE_SAS_TOKEN');
    $blobEndpoint = env('AZURE_STORAGE_BLOB_ENDPOINT');
    $containerName = env('AZURE_STORAGE_CONTAINER_NAME');

    $connectionString = "BlobEndpoint=$blobEndpoint;SharedAccessSignature=$sasToken";
    $blobClient = BlobRestProxy::createBlobService($connectionString);

    $content1 = fopen($filePath1, "r");
    $blobOptions1 = new CreateBlockBlobOptions();
    $blobOptions1->setContentType($file1->getClientMimeType());

    $content2 = fopen($filePath2, "r");
    $blobOptions2 = new CreateBlockBlobOptions();
    $blobOptions2->setContentType($file2->getClientMimeType());

    try {
        $blobClient->createBlockBlob($containerName, $fileName1, $content1, $blobOptions1);
        $blobClient->createBlockBlob($containerName, $fileName2, $content2, $blobOptions2);

        // Generate file URLs
        $fileUrl1 = "$blobEndpoint/$containerName/$fileName1";
        $fileUrl2 = "$blobEndpoint/$containerName/$fileName2";

        // Update driver with the URLs
        $driver->update([
            'URL_identity_card_front' => $fileUrl1,
            'URL_identity_card_back' => $fileUrl2,
        ]);

        return response()->json([
            'message' => 'Obje slike su prepoznate kao valjane i uspješno su spremljene.',
            'file_url1' => $fileUrl1,
            'file_url2' => $fileUrl2,
            'extracted_text1' => $text1,
            'extracted_text2' => $text2
        ], Response::HTTP_OK);
    } catch (\Exception $e) {
        Log::error('Error uploading files: ', ['exception' => $e]);
        return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
    }
}

private function extractTextWithOCR1($imagePath)
{
    // Use Tesseract OCR to extract text from the image
    return (new TesseractOCR($imagePath))->lang('eng', 'hrv', 'bos', 'srp')->run();
}

private function containsRequiredText1($text, $driver)
{
    $formattedBirthDate = \Carbon\Carbon::createFromFormat('Y-m-d', $driver->birth_date)->format('d.m.Y');
    $formattedName = strtoupper($driver->name);
    $formattedSurname = strtoupper($driver->surname);
    // Check if the key phrases exist in the extracted text for the first image
    $phrasesToCheck = [
        "OSOBNA ISKAZNICA",
        "IDENTITY CARD",
        "BOSNIA AND HERZEGOVINA",
        "SERIAL NUMBER",
        "LIČNA KARTA",
        $formattedName,
        $formattedSurname,
        $formattedBirthDate,
        $driver->serial_number_identity_card
    ];

    foreach ($phrasesToCheck as $phrase) {
        if (strpos($text, $phrase) === false) {
            Log::info('Missing phrase slika 1 osobna: ' . $phrase);
            return false;
        }
    }
    return true;
}

private function containsRequiredText2($text, $OIB)
{
    // Check if the driver's OIB exists in the extracted text for the second image
    $additionalPhrases = ["DATUM IZDAVANJA", "ENTITY CITIZENSHIP", "<<<", "<<<<<<", "<<"];
    if (strpos($text, $OIB) === false) {
        return false;
    }
    foreach ($additionalPhrases as $phrase) {
        if (strpos($text, $phrase) === false) {
            return false;
        }
    }
    return true;
}
}
