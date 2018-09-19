<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProdutoSubCategoria extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtoSubCategoria', function (Blueprint $table) 
        {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->string('id',50)->unique()->index();
            $table->string('nome',100);
            $table->string('categoriaId',50)->index();
            $table->foreign('categoriaId')
                ->references('id')
                ->on('produtoCategoria')
                ->onDelete('cascade');   
            $table->primary(['id', 'categoriaId','nome']);       
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('produtoSubCategoria');
    }
}
