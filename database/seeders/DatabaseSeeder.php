<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Rian Admin',
            'email' => 'rianjaidi@fic16.com',
            'password' => Hash::make('12345678'),
        ]);

        //data dummy for company
        Company::create([
            'name' => 'PT Artek Sinergi Multimedia',
            'email' => 'artek@arteksinergi.co.id',
            'address' => '18 OFFICE PARK, LT. 25, SUITE A 2, JL. TB SIMATUPANG KAV. 18, JAKARTA SELATAN.',
            'latitude' => '-6.266667',
            'longitude' => '106.833333',
            'radius_km' => '0.5',
            'time_in' => '08:00',
            'time_out' => '17:00',
        ]);
    }
}
