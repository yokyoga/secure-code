<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('arrivals', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->string('full_name', 255);
            $table->text('passport_number');
            $table->string('nationality', 100);
            $table->enum('gender', ['male', 'female']);
            $table->date('birth_date');
            $table->text('photo_path');
            $table->text('phone_number');
            $table->text('email');
            $table->text('stay_address');
            $table->string('flight_number', 50);
            $table->timestamp('arrival_date');
            $table->string('origin_city', 100);
            $table->string('destination_city', 100);
            $table->text('health_history')->nullable();
            $table->text('emergency_contact_name');
            $table->text('emergency_contact_phone');
            $table->text('vaccine_certificate_path');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->uuid('approved_by_user_id')->nullable();
            $table->uuid('rejected_by_user_id')->nullable();
            $table->text('reject_reason')->nullable();
            $table->timestamps();
            $table->index('approved_by_user_id');
            $table->index('rejected_by_user_id');

            $table->foreign('approved_by_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('rejected_by_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arrivals');
    }
};
