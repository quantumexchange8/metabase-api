<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         User::create([
             'title' => 'Mr',
             'name' => 'Admin',
             'email' => 'admin@admin.com',
             'email_verified_at' => now(),
             'password' =>  Hash::make('password'),
             'remember_token' => Str::random(10),
             'country' => 132,
             'state' => 1949,
             'city' => 76497,
             'phone' => '+60123456789',
             'trade_min_experience' => '10',
             'trade_max_experience' => '15',
             'source_of_fund' => 'investment',
             'gender' => 'male',
             'dob' => '1990-09-12',
             'nationality' => 'Malaysian',
             'us_citizen' => 0,
             'identification_type' => 'nric',
             'identification_number' => '900912-14-1243',
         ]);

    }
}
