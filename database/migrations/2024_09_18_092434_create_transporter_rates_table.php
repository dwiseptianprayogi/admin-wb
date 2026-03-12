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
        Schema::create('transporter_rates', function (Blueprint $table) {

            $table->string('uuid')->primary();
            $table->string('name');
            $table->string('area_uuid');
            $table->string('rate');
            $table->string('charge');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->auditableWithDeletes();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transporter_rates');
    }
};
