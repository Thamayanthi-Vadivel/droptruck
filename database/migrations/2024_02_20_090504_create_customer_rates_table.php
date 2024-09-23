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
        Schema::create('customer_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('indent_id'); 
            $table->decimal('rate', 8, 2);
            $table->timestamps();
            $table->foreign('indent_id')->references('id')->on('indents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_rates');
    }
};
