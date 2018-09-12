<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Relatorio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relatorios', function (Blueprint $table) 
        {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->string('id',50)->unique()->index()->primary();
            $table->string('nome',150);
            $table->string('permissao',100);
            $table->string('categoriaId',50)->index();
            $table->foreign('categoriaId')
                ->references('id')
                ->on('categoriasRelatorio')
                ->onDelete('cascade');
            $table->longText('query');            
            $table->longText('campos');            
            $table->string("modoPDF",50)->default("portrait");        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relatorios');
    }
}
