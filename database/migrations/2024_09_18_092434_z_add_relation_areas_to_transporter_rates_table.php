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
        Schema::table('transporter_rates', function (Blueprint $table) {
            
$table->foreign('area_uuid')->references('uuid')->on('areas');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transporter_rates', function (Blueprint $table) {
            
$table->foreign('area_uuid')->references('uuid')->on('areas');

        });
    }
};
