<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger( 'invoice_id' )->unsigned();
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->date('invoice_Date')->nullable();
            $table->date('Due_date')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('product', 50);
            $table->bigInteger( 'section_id' )->unsigned();
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            $table->decimal('Amount_collection',8,2)->nullable();;
            $table->decimal('Amount_Commission',8,2);
            $table->decimal('Discount',8,2);
            $table->decimal('Value_VAT',8,2);
            $table->string('Rate_VAT', 999);
            $table->decimal('Total',8,2);
            $table->tinyInteger('Status');
            $table->text('note')->nullable();
            $table->string('user',150);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices_details');
    }
};
