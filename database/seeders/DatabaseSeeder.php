<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Str;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeder Users (Insecure: password plaintext)
        DB::table('users')->insert([
            [
                'id' => Str::uuid(),
                'name' => 'Petugas Approver',
                'email' => 'approver@example.com',
                'password' => 'password123', // INSECURE
                'role' => 'approver',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Petugas Viewer',
                'email' => 'viewer@example.com',
                'password' => 'password123', // INSECURE
                'role' => 'viewer',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // Seeder Arrivals (5 dummy data)
        for ($i = 1; $i <= 5; $i++) {
            DB::table('arrivals')->insert([
                'id' => Str::uuid(),
                'full_name' => "Wisatawan $i",
                'passport_number' => "P123456$i",
                'nationality' => "Country $i",
                'gender' => $i % 2 === 0 ? 'male' : 'female',
                'birth_date' => now()->subYears(20 + $i)->toDateString(),
                'photo_path' => "/uploads/photo/sample$i.jpg",
                'phone_number' => "+62081234567$i",
                'email' => "wisatawan$i@example.com",
                'stay_address' => "Hotel Example $i, Jakarta",
                'flight_number' => "GA$i$i$i",
                'arrival_date' => now()->addDays($i)->toDateTimeString(),
                'origin_city' => "City $i",
                'destination_city' => $i % 2 === 0 ? "Jakarta" : "Bali",
                'health_history' => "none",
                'emergency_contact_name' => "Kontak Darurat $i",
                'emergency_contact_phone' => "+62081987654$i",
                'vaccine_certificate_path' => "/uploads/vaccine/sample$i.jpg",
                'status' => 'pending',
                'approved_by_user_id' => null,
                'rejected_by_user_id' => null,
                'reject_reason' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
