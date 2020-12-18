<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreatePaymentsTable
 */
class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('user_type');
            $table->integer('account_id');
            $table->float('value');
            $table->dateTime('request_date');
            $table->dateTime('payment_date')->nullable();
            $table->enum('status',
                ['Pendiente', 'Aprobado', 'Cancelado']
            );
            $table->string('voucher')->nullable();
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
        Schema::dropIfExists('payments');
    }
}