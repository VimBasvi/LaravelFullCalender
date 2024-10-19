<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee; // Add this line   else error will come

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create 10 sample employees
        Employee::insert([
            ['name' => 'Alice Johnson'],
            ['name' => 'Bob Smith'],
            ['name' => 'Charlie Davis'],
            ['name' => 'David Evans'],
            ['name' => 'Eve White'],
            ['name' => 'Frank Brown'],
            ['name' => 'Grace Taylor'],
            ['name' => 'Henry Wilson'],
            ['name' => 'Ivy Scott'],
            ['name' => 'Jack Lewis'],
        ]);
    }
}
