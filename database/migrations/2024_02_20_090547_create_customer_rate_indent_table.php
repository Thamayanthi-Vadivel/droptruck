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
        Schema::create('customer_rate_indent', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_rate_id');
            $table->foreign('customer_rate_id')->references('id')->on('customer_rates')->onDelete('cascade');
            $table->unsignedBigInteger('indent_id');
            $table->foreign('indent_id')->references('id')->on('indents')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_rate_indent');
    }
};
