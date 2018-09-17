<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SkuAcessorios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skuAcessorios', function (Blueprint $table) 
        {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->string('skuId',50)->index();
            $table->foreign('skuId')
                ->references('id')
                ->on('skus')
                ->onDelete('restrict');   
            $table->string('acessorioId',50)->index();
            $table->foreign('acessorioId')
                ->references('id')
                ->on('skus')
                ->onDelete('restrict');   
            $table->primary(['acessorioId', 'skuId']);       
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('skuAcessorios');
    }
}
