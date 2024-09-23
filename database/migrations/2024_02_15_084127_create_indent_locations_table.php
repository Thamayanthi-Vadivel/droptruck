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
        Schema::create('indent_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('indent_id');
            $table->unsignedBigInteger('location_id');
            $table->string('type'); 
            $table->foreign('indent_id')->references('id')->on('indents')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indent_locations');
    }
};
