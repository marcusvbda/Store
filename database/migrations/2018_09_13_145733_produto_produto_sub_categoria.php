<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProdutoProdutoSubCategoria extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtoProdutoSubCategoria', function (Blueprint $table) 
        {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->string('produtoSubCategoriaId',50)->index();
            $table->foreign('produtoSubCategoriaId','fk_produtoSubCat')
                ->references('id')
                ->on('produtoSubCategoria')
                ->onDelete('cascade');      
            $table->string('produtoId',50)->index();
            $table->foreign('produtoId','fk_produtoSubProd')
                ->references('id')
                ->on('produtos');

            $table->primary(['produtoSubCategoriaId', 'produtoId'],'pksub');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produtoProdutoSubCategoria');
    }
}
