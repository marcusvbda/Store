<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Skus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skus', function (Blueprint $table) 
        {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->string('id',50)->unique()->index()->primary();
            $table->string('nome',100)->nullable();
            $table->string('ean',100)->nullable();
            $table->string('codRef',100)->nullable();
            $table->string('ncm',30)->nullable();
            $table->string('produtoId',50)->index();
            $table->foreign('produtoId')
                ->references('id')
                ->on('produtos')
                ->onDelete('restrict');   
            $table->double('altura', 15, 8)->default(0);
            $table->double('comprimento', 15, 8)->default(0);
            $table->double('largura', 15, 8)->default(0);
            $table->double('peso', 15, 8)->default(0);
            $table->boolean('ativo')->default(0);
            $table->date('dataCadastro');
            $table->time('horaCadastro');
            $table->double('estoqueReal', 15, 8)->default(0);
            $table->double('estoqueAtual', 15, 8)->default(0);
            $table->double('multiplicadorUnd', 15, 8)->default(1);
            $table->string('codigoFabricante',50)->nullable();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('skus');
    }
}
