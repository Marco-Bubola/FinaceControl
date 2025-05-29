<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileTable extends Migration
{
    public function up()
    {
        Schema::create('profile', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->timestamp('create_time')->nullable();
            $table->timestamp('update_time')->nullable();
            $table->string('full_name', 255)->nullable();
            $table->string('language', 100)->default('en');
            $table->string('startpage', 100)->default('cashbook/index');
            $table->string('currencycode', 100)->default('USD');
            $table->string('decimalseparator', 1)->default('.');

            // Não há índices ou chaves estrangeiras explícitas no dump para user_id
        });
    }

    public function down()
    {
        Schema::dropIfExists('profile');
    }
}
