<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateOrdersTable
 */
class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('user_type');
            $table->enum('status',
                ['Iniciado', 'Aceptado', 'Alistamiento', 'Circulacion', 'Entregado', 'Cancelado']
            );
            $table->string('cancel_reason')->nullable();
            $table->integer('client_id');
            $table->string('client_type');
            $table->integer('total_products');
            $table->float('total_amount');
            $table->float('delivery_amount');
            $table->float('total_discount');
            $table->float('total');
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
        Schema::dropIfExists('orders');
    }
}