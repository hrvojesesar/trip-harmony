<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->string('name');
            $table->string('surname');
            $table->date('birth_date');
            $table->string('OIB');
            $table->string('serial_number_identity_card');
            $table->string('URL_identity_card_front')->nullable();
            $table->string('URL_identity_card_back')->nullable();
            $table->string('serial_number_driver_license');
            $table->string('URL_driver_license_front')->nullable();
            $table->string('URL_driver_license_back')->nullable();
            $table->string('serial_number_health_card');
            $table->string('URL_health_card_front')->nullable();
            $table->string('URL_health_card_back')->nullable();
            $table->string('Car_name');
            $table->string('Car_model');
            $table->string('Car_color');
            $table->string('registration_mark');
            $table->string('URL_registration_certificate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
