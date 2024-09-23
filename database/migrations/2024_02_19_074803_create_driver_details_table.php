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
        Schema::create('driver_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('indent_id');
            $table->foreign('indent_id')->references('id')->on('indents')->onDelete('cascade');
            $table->string('driver_name');
            $table->string('driver_number');
            $table->string('vehicle_number');
            $table->string('driver_base_location');
            $table->string('vehicle_photo')->nullable();
            $table->string('driver_license')->nullable();
            $table->string('rc_book')->nullable();
            $table->string('insurance')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_details');
    }
};
