<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashbookTable extends Migration
{
    public function up()
    {
        Schema::create('cashbook', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('value', 10, 2);
            $table->string('description', 255)->nullable();
            $table->date('date');
            $table->boolean('is_pending')->default(0);
            $table->string('attachment', 255)->nullable();
            $table->dateTime('inc_datetime')->nullable()->comment('insert date');
            $table->dateTime('edit_datetime')->nullable()->comment('edit date');
            $table->unsignedBigInteger('user_id');
            $table->integer('category_id');
            $table->integer('type_id');
            $table->string('note', 255)->nullable();
            $table->integer('segment_id')->nullable();
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate();
            $table->integer('id_bank')->nullable();
            $table->integer('client_id')->nullable();

            $table->primary('id');
            $table->index('category_id');
            $table->index('type_id');
            $table->index('segment_id');
            $table->index('date');
            $table->index('user_id', 'idx_cashbook_user_id');
            $table->index('id_bank', 'cashbook_fk_id_bank');
            $table->index('client_id', 'fk_cashbook_clients');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id_category')->on('category');
            $table->foreign('type_id')->references('id_type')->on('type');
            $table->foreign('segment_id')->references('id')->on('segment');
            $table->foreign('id_bank')->references('id_bank')->on('banks')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cashbook');
    }
}
