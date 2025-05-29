<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSegmentTable extends Migration
{
    public function up()
    {
        Schema::create('segment', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->integer('category_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate();

            $table->index('category_id', 'idx_segment_category_id');
            $table->index('user_id', 'idx_segment_user_id');
            $table->foreign('category_id')->references('id_category')->on('category');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('segment');
    }
}
