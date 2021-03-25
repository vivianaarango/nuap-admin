<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateDistributorsTable
 */
class CreateDistributorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distributors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('business_name');
            $table->string('nit');
            $table->string('second_phone');
            $table->float('commission')->default(0.0);
            $table->enum('type', ['Mayorista', 'Distribuidor']);
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
        Schema::dropIfExists('distributors');
    }
}