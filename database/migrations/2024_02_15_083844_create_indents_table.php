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
        Schema::create('indents', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('company_name');
            $table->string('number_1')->nullable();
            $table->string('number_2')->nullable();
            $table->string('source_of_lead');
            $table->unsignedBigInteger('pickup_location_id')->nullable();
            $table->unsignedBigInteger('drop_location_id')->nullable();
            $table->foreign('pickup_location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->foreign('drop_location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->string('body_type');
            $table->string('weight')->nullable();
            $table->string('weight_unit');
            $table->string('pod_soft_hard_copy');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); 
            $table->unsignedBigInteger('material_type_id');
            $table->foreign('material_type_id')->references('id')->on('material_types')->onDelete('cascade');
            $table->unsignedBigInteger('truck_type_id');
            $table->foreign('truck_type_id')->references('id')->on('truck_types')->onDelete('cascade');
            $table->string('status')->default('0');
            $table->decimal('customer_rate', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indents');
    }
};
