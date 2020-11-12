<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateUsersTable
 */
class CreateCommercesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commerces', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('business_name');
            $table->string('city');
            $table->string('location');
            $table->string('neighborhood');
            $table->string('address');
            $table->string('latitude');
            $table->string('longitude');
            $table->enum('type', [
                'Cigarreria',
                'Drogueria',
                'Ferreteria',
                'Licorera',
                'Miscelanea',
                'Mini mercado',
                'Prestador de servicios',
                'Supermercado'
            ]);
            $table->string('name_legal_representative');
            $table->string('cc_legal_representative');
            $table->string('contact_legal_representative');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commerces');
    }
}