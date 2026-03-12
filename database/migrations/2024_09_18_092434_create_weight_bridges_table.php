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
        Schema::create('weight_bridges', function (Blueprint $table) {

            $table->string('uuid')->primary();
            $table->string('slip_no')->nullable();
            $table->date('arrival_date')->nullable();
            $table->string('weight_type')->nullable();
            $table->string('vehicle_uuid')->nullable();
            $table->string('weight_in')->nullable();
            $table->dateTime('weight_in_date')->nullable();
            $table->string('weight_out')->nullable();
            $table->dateTime('weight_out_date')->nullable();
            $table->string('weight_netto')->nullable();
            $table->string('weight_standart')->nullable();
            $table->string('po_do')->nullable();
            $table->string('difference')->nullable();
            $table->string('weight_in_by')->nullable();
            $table->string('weight_out_by')->nullable();
            $table->string('remark')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('weight_bridges');
    }
};
