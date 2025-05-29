<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceTable extends Migration
{
    public function up()
    {
        Schema::create('invoice', function (Blueprint $table) {
            $table->increments('id_invoice');
            $table->integer('id_bank');
            $table->string('description', 255);
            $table->string('installments', 255);
            $table->decimal('value', 10, 2);
            $table->date('invoice_date');
            $table->unsignedBigInteger('user_id');
            $table->integer('category_id');
            $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate();
            $table->integer('client_id')->nullable();
            $table->boolean('dividida')->default(0);

            $table->index('id_bank');
            $table->index('category_id');
            $table->index('user_id', 'idx_invoice_user_id');
            $table->index('value', 'idx_invoice_value');
            $table->index('client_id', 'fk_invoice_clients');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('id_bank')->references('id_bank')->on('banks');
            $table->foreign('category_id')->references('id_category')->on('category');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice');
    }
}
