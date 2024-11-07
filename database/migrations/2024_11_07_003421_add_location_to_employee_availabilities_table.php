<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationToEmployeeAvailabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_availabilities', function (Blueprint $table) {
            $table->string('location')->nullable(); // Add the location column, allowing it to be nullable
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_availabilities', function (Blueprint $table) {
            $table->dropColumn('location'); // Drop the location column if the migration is rolled back
        });
    }
}
