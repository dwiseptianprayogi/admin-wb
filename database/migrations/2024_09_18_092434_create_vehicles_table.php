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
        Schema::create('vehicles', function (Blueprint $table) {

            $table->string('uuid')->primary();
            $table->string('register_number');
            $table->string('code');
            $table->string('status');
            $table->string('type');
            $table->string('vehicle_type_uuid');
            $table->string('description');
            $table->string('transporter_rate_uuid');
            $table->string('transporter_uuid');
            $table->string('ownership');
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
        Schema::dropIfExists('vehicles');
    }
};
