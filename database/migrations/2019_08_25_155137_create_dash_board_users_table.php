<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDashBoardUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dash_board_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('displayId');
            $table->string('displayName');
            $table->integer('status');
            $table->string('firstName');
            $table->string('lastName');
            $table->integer('rankNo');
            $table->string('rankName');
            $table->string('phoneNo');
            $table->integer('belongsId');
            $table->string('belongsName');
            $table->string('mail');
            $table->integer('locationId');
            $table->string('location');
            $table->integer('comentNum');
            $table->boolean('seisouFlag');
            $table->boolean('soujiFlag');
            $table->boolean('garbageFlag');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dash_board_users');
    }
}
