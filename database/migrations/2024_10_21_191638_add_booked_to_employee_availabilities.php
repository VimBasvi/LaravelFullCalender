<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employee_availabilities', function (Blueprint $table) {
            
            $table->boolean('booked')->default(false); // adding the booked column 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_availabilities', function (Blueprint $table) {
            $table->dropColumn('booked'); // dropping the booked column if necessary
        });
    }
};
