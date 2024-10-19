<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client; // Add this line

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create 10 sample clients
        Client::insert([
            ['name' => 'John Doe'],
            ['name' => 'Jane Doe'],
            ['name' => 'Michael Johnson'],
            ['name' => 'Emma Watson'],
            ['name' => 'Olivia Brown'],
            ['name' => 'Ava Jones'],
            ['name' => 'Sophia Miller'],
            ['name' => 'Isabella Garcia'],
            ['name' => 'Mia Martinez'],
            ['name' => 'James Anderson'],
        ]);
    }
}
