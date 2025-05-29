<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleTable extends Migration
{
    public function up()
    {
        Schema::create('role', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->timestamp('create_time')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('update_time')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate();
            $table->smallInteger('can_admin')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('role');
    }
}
