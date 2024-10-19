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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();  // Primary key
            $table->datetime('start_time');  // Start time of the appointment
            $table->datetime('finish_time');  // End time of the appointment
            $table->longText('comments')->nullable();  // Optional comments
            $table->foreignId('client_id')->constrained();  // Foreign key to clients table
            $table->foreignId('employee_id')->constrained();  // Foreign key to employees table
            $table->timestamps();  // Created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
