<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryTable extends Migration
{
    public function up()
    {
        Schema::create('category', function (Blueprint $table) {
            $table->increments('id_category');
            $table->integer('parent_id')->nullable();
            $table->string('name', 100);
            $table->string('desc_category', 100)->nullable();
            $table->string('hexcolor_category', 45)->nullable();
            $table->string('icone', 100)->nullable();
            $table->text('descricao_detalhada')->nullable();
            $table->enum('tipo', ['gasto', 'receita', 'ambos'])->default('ambos');
            $table->decimal('limite_orcamento', 10, 2)->nullable();
            $table->boolean('compartilhavel')->default(0);
            $table->string('tags', 255)->nullable();
            $table->json('regras_auto_categorizacao')->nullable();
            $table->integer('id_bank')->nullable();
            $table->integer('id_clients')->nullable();
            $table->integer('id_produtos_clientes')->nullable();
            $table->text('historico_alteracoes')->nullable();
            $table->integer('is_active')->default(1);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->enum('type', ['product', 'transaction'])->default('product');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->primary('id_category');
            $table->index('user_id', 'idx_category_user_id');
            $table->index('id_bank', 'fk_category_banks');
            $table->index('id_clients', 'fk_category_clients');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('id_bank')->references('id_bank')->on('banks')->onDelete('set null');
            $table->foreign('id_clients')->references('id')->on('clients')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('category');
    }
}
