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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_name');
            $table->string('supplier_type');
            $table->string('company_name');
            $table->string('contact_number');
            $table->string('pan_card_number');
            $table->string('pan_card')->nullable();
            $table->string('business_card')->nullable();
            $table->string('memo')->nullable();
            $table->text('remarks')->nullable();
            $table->string('bank_name'); // Add unique constraint to bank_name field
            $table->string('ifsc_code')->unique(); // Add unique constraint to ifsc_code field
            $table->string('account_number')->unique();
            $table->string('re_account_number')->nullable();
            $table->unsignedBigInteger('indent_id')->nullable();
            $table->foreign('indent_id')->references('id')->on('indents')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
