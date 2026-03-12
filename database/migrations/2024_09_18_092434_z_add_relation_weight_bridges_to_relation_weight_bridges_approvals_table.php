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
        Schema::table('weight_bridge_approvals', function (Blueprint $table) {

            $table->foreign('weight_bridge_uuid')->references('uuid')->on('weight_bridges');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('weight_bridge_approvals', function (Blueprint $table) {

            $table->foreign('weight_bridge_uuid')->references('uuid')->on('weight_bridges');
        });
    }
};
