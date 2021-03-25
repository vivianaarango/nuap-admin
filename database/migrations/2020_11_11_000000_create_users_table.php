<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateUsersTable
 */
class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('phone', 10);
            $table->boolean('phone_validated')->default(false);
            $table->dateTime('phone_validated_date')->nullable();
            $table->string('password');
            $table->boolean('status')->default(false);
            $table->enum('role', ['Administrador', 'Distribuidor', 'Comercio', 'Usuario']);
            $table->dateTime('last_logged_in');
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
        Schema::dropIfExists('users');
    }
}