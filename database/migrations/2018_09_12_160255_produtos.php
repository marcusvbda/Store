<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Produtos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) 
        {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->string('id',50)->unique()->index()->primary();
            $table->string('nome',100);
            $table->longtext('palavrasSubstitutas')->nullable();
            $table->string('tituloPagina',100);
            $table->string('textLink',100);
            $table->longtext('descricaoProduto')->nullable();
            $table->longtext('descricaoMeta')->nullable();
            $table->string('codRef',100)->nullable();
            $table->date('dataCadastro');
            $table->time('horaCadastro');
            $table->string('categoriaId',50)->index();
            $table->foreign('categoriaId')
                ->references('id')
                ->on('produtoCategoria')
                ->onDelete('restrict');   
            $table->string('marcaId',50)->index();
            $table->foreign('marcaId')
                ->references('id')
                ->on('produtoMarca')
                ->onDelete('restrict');                
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produtos');
    }
}
