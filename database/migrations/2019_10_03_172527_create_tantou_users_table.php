<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTantouUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tantou_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('userName');
            $table->integer('gomiFlag');
            $table->integer('souziFlag');
            $table->integer('seisouFlag');
            $table->integer('hinomotoFlag');
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
        Schema::dropIfExists('tantou_users');
    }
}
