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
        Schema::create('weight_bridge_approvals', function (Blueprint $table) {

            $table->string('uuid')->primary();
            $table->string('weight_bridge_uuid')->nullable();
            $table->dateTime('action_date')->nullable();
            $table->string('action_by')->nullable();
            $table->boolean('is_approve')->nullable();
            $table->boolean('is_reject')->nullable();
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('weight_bridge_approvals');
    }
};
