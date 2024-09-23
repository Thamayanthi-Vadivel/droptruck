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
        Schema::create('indent_cancel_reason', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('indent_id');
            $table->foreign('indent_id')->references('id')->on('indents')->onDelete('cascade');
            $table->unsignedBigInteger('cancel_reason_id');
            $table->foreign('cancel_reason_id')->references('id')->on('cancel_reasons')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indent_cancel_reason');
    }
};
