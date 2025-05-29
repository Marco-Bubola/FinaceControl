<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeTable extends Migration
{
    public function up()
    {
        Schema::create('type', function (Blueprint $table) {
            $table->increments('id_type');
            $table->string('desc_type', 45);
            $table->string('hexcolor_type', 45)->nullable();
            $table->string('icon_type', 45)->nullable();

            $table->index('desc_type', 'idx_type_desc_type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('type');
    }
}
