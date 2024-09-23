<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtraCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
        public function up(): void
        {
            Schema::create('extra_costs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('indent_id');
                $table->foreign('indent_id')->references('id')->on('indents')->onDelete('cascade');
                $table->string('extra_cost_type');
                $table->decimal('amount', 10, 2);
                $table->string('bill_copy')->nullable();
                $table->string('unloading_photo')->nullable();
                $table->string('bill_copies')->nullable();
                $table->timestamps();
            });
        }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('extra_costs');
    }
}
