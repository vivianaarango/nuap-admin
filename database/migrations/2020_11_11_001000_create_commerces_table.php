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
            $table->string('nit');
            $table->string('second_phone');
            $table->float('commission')->default(0.0);
            $table->enum('type', [
                'Cigarreria',
                'Drogueria',
                'Ferreteria',
                'Licorera',
                'Miscelanea',
                'Mini mercado',
                'Prestador de servicios',
                'Profesional independiente',
                'Supermercado'
            ]);
            $table->string('name_legal_representative');
            $table->string('cc_legal_representative');
            $table->string('contact_legal_representative');
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
        Schema::dropIfExists('commerces');
    }
}