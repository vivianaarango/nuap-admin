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
            $table->string('name');
            $table->string('lastname');
            $table->enum('identity_type', ['Cédula', 'Nit', 'Cédula de extranjería']);
            $table->string('identity_number');
            $table->string('phone', 10);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('image_url')->nullable();
            $table->enum('role', ['Administrador', 'Mayorista', 'Comercio', 'Usuario']);
            $table->dateTime('last_logged_in');
            $table->boolean('status')->default(true);
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