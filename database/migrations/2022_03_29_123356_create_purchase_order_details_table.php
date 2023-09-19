<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_details', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('id', true);
            $table->integer('sale_unit_id')->nullable()->index('sale_unit_id');
            $table->foreign('sale_unit_id', 'sale_unit_id')->references('id')->on('units')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->float('price', 10, 0);
            $table->float('TaxNet', 10, 0)->nullable()->default(0);
            $table->string('tax_method', 192)->nullable()->default('1');
            $table->float('discount', 10, 0)->nullable()->default(0);
            $table->string('discount_method', 192)->nullable()->default('1');
            $table->float('total', 10, 0);
            $table->float('quantity', 10, 0);
            $table->integer('product_id')->index('product_id_purchase_details');
            $table->integer('product_variant_id')->nullable()->index('quote_product_variant_id');
            $table->integer('purchase_order_id')->index('purchase_order_id');
            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_order_details');
    }
}
