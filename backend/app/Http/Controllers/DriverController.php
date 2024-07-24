<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DriverController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/driver",
     *     summary="Get the user and associated driver model",
     *     tags={"Driver"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="The user and associated driver model",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="email_verified_at", type="string", nullable=true),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time"),
     *             @OA\Property(property="driver", type="object", nullable=true,
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="user_id", type="integer"),
     *                 @OA\Property(property="birth_date", type="string", format="date"),
     *                 @OA\Property(property="OIB", type="string"),
     *                 @OA\Property(property="serial_number_identity_card", type="string"),
     *                 @OA\Property(property="URL_identity_card_front", type="string", format="url"),
     *                 @OA\Property(property="URL_identity_card_back", type="string", format="url"),
     *                 @OA\Property(property="serial_number_driver_license", type="string"),
     *                 @OA\Property(property="URL_driver_license_front", type="string", format="url"),
     *                 @OA\Property(property="URL_driver_license_back", type="string", format="url"),
     *                 @OA\Property(property="serial_number_health_card", type="string"),
     *                 @OA\Property(property="URL_health_card_front", type="string", format="url"),
     *                 @OA\Property(property="URL_health_card_back", type="string", format="url"),
     *                 @OA\Property(property="Car_name", type="string"),
     *                 @OA\Property(property="Car_model", type="string"),
     *                 @OA\Property(property="Car_color", type="string"),
     *                 @OA\Property(property="registration_mark", type="string"),
     *                 @OA\Property(property="URL_registration_certificate", type="string", format="url"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Driver not found.")
     *         )
     *     )
     * )
     */
    public function show(Request $request)
    {
        // return back the user and associated driver model
        $user = $request->user();
        $user->load('driver');

        return $user;
    }

    /**
     * @OA\Post(
     *    path="/api/driver",
     *    summary="Update the user and associated driver model",
     *    tags={"Driver"},
     *    security={{"bearerAuth": {}}},
     *    @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *            required={"name", "surname", "birth_date", "OIB", "serial_number_identity_card", "serial_number_driver_license", "serial_number_health_card", "Car_name", "Car_model", "Car_color", "registration_mark"},
     *            @OA\Property(property="name", type="string", example="John"),
     *            @OA\Property(property="surname", type="string", example="Doe"),
     *            @OA\Property(property="birth_date", type="string", format="date", example="1980-01-01"),
     *            @OA\Property(property="OIB", type="string", example="1234567890123"),
     *            @OA\Property(property="serial_number_identity_card", type="string", example="12345678A"),
     *            @OA\Property(property="URL_identity_card_front", type="string", format="url", example="http://example.com/identity_card_front.jpg"),
     *            @OA\Property(property="URL_identity_card_back", type="string", format="url", example="http://example.com/identity_card_back.jpg"),
     *            @OA\Property(property="serial_number_driver_license", type="string", example="123456789"),
     *            @OA\Property(property="URL_driver_license_front", type="string", format="url", example="http://example.com/driver_license_front.jpg"),
     *            @OA\Property(property="URL_driver_license_back", type="string", format="url", example="http://example.com/driver_license_back.jpg"),
     *            @OA\Property(property="serial_number_health_card", type="string", example="1-23-4567"),
     *            @OA\Property(property="URL_health_card_front", type="string", format="url", example="http://example.com/health_card_front.jpg"),
     *            @OA\Property(property="URL_health_card_back", type="string", format="url", example="http://example.com/health_card_back.jpg"),
     *            @OA\Property(property="Car_name", type="string", example="TOYOTA"),
     *            @OA\Property(property="Car_model", type="string", example="COROLLA"),
     *            @OA\Property(property="Car_color", type="string", example="RED"),
     *            @OA\Property(property="registration_mark", type="string", example="A12-B-345"),
     *            @OA\Property(property="URL_registration_certificate", type="string", format="url", example="http://example.com/registration_certificate.jpg"),
     *        )
     *    ),
     *    @OA\Response(
     *        response=200,
     *        description="The user and associated driver model",
     *        @OA\JsonContent(
     *            @OA\Property(property="id", type="integer"),
     *            @OA\Property(property="name", type="string"),
     *            @OA\Property(property="surname", type="string"),
     *            @OA\Property(property="email", type="string"),
     *            @OA\Property(property="email_verified_at", type="string"),
     *            @OA\Property(property="created_at", type="string"),
     *            @OA\Property(property="updated_at", type="string"),
     *            @OA\Property(property="driver", type="object",
     *                @OA\Property(property="id", type="integer"),
     *                @OA\Property(property="user_id", type="integer"),
     *                @OA\Property(property="birth_date", type="string", format="date"),
     *                @OA\Property(property="OIB", type="string"),
     *                @OA\Property(property="serial_number_identity_card", type="string"),
     *                @OA\Property(property="URL_identity_card_front", type="string"),
     *                @OA\Property(property="URL_identity_card_back", type="string"),
     *                @OA\Property(property="serial_number_driver_license", type="string"),
     *                @OA\Property(property="URL_driver_license_front", type="string"),
     *                @OA\Property(property="URL_driver_license_back", type="string"),
     *                @OA\Property(property="serial_number_health_card", type="string"),
     *                @OA\Property(property="URL_health_card_front", type="string"),
     *                @OA\Property(property="URL_health_card_back", type="string"),
     *                @OA\Property(property="Car_name", type="string"),
     *                @OA\Property(property="Car_model", type="string"),
     *                @OA\Property(property="Car_color", type="string"),
     *                @OA\Property(property="registration_mark", type="string"),
     *                @OA\Property(property="URL_registration_certificate", type="string"),
     *                @OA\Property(property="created_at", type="string"),
     *                @OA\Property(property="updated_at", type="string")
     *            )
     *        )
     *    ),
     *    @OA\Response(
     *        response=400,
     *        description="Bad Request"
     *    )
     * )
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'birth_date' => 'required|date|before:today',
            'OIB' => 'required|digits:13',
            'serial_number_identity_card' => 'required',
            'URL_identity_card_front' => 'url',
            'URL_identity_card_back' => 'url',
            'serial_number_driver_license' => 'required',
            'URL_driver_license_front' => 'url',
            'URL_driver_license_back' => 'url',
            'serial_number_health_card' => 'required',
            'URL_health_card_front' => 'url',
            'URL_health_card_back' => 'url',
            'Car_name' => 'required',
            'Car_model' => 'required',
            'Car_color' => 'required',
            'registration_mark' => 'required',
            'URL_registration_certificate' => 'url',
        ]);

        $user = $request->user();

        $user->update($request->only('name', 'surname'));

        // create or update a driver associated with the user
        $user->driver()->updateOrCreate([
            'user_id' => $user->id,
        ], $request->only([
            'name',
            'surname',
            'birth_date',
            'OIB',
            'serial_number_identity_card',
            'URL_identity_card_front',
            'URL_identity_card_back',
            'serial_number_driver_license',
            'URL_driver_license_front',
            'URL_driver_license_back',
            'serial_number_health_card',
            'URL_health_card_front',
            'URL_health_card_back',
            'Car_name',
            'Car_model',
            'Car_color',
            'registration_mark',
            'URL_registration_certificate',
        ]));

        $user->load('driver');

        return $user;
    }
}
