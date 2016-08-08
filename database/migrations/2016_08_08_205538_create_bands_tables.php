<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBandsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bands', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('domain');
            $table->timestamps();
        });
        Schema::create('roles', function($table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('description');
            $table->timestamps();
        });
        Schema::create('permissions', function($table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('description');
            $table->timestamps();
        });
        Schema::create('role_permissions', function($table) {
            $table->increments('id');
            $table->integer('role_id')->unsigned();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->integer('permission_id')->unsigned();
            $table->foreign('permission_id')->references('id')->on('permissions');
            $table->timestamps();
        });
        Schema::create('user_permissions', function($table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('permission_id')->unsigned();
            $table->foreign('permission_id')->references('id')->on('permissions');
            $table->timestamps();
        });
        Schema::create('user_roles', function($table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('role_id')->unsigned();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->integer('band_id')->unsigned();
            $table->foreign('band_id')->references('id')->on('bands');
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
        Schema::drop('user_roles');
        Schema::drop('user_permissions');
        Schema::drop('role_permissions');
        Schema::drop('permissions');
        Schema::drop('roles');
        Schema::drop('bands');
    }
}
