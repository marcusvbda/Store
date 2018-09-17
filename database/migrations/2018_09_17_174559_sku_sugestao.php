<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SkuSugestao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skuSugestao', function (Blueprint $table) 
        {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->string('skuId',50)->index();
            $table->foreign('skuId')
                ->references('id')
                ->on('skus')
                ->onDelete('restrict');   
            $table->string('sugestaoId',50)->index();
            $table->foreign('sugestaoId')
                ->references('id')
                ->on('skus')
                ->onDelete('restrict');   
            $table->primary(['sugestaoId', 'skuId']);       
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('skuSugestao');
    }
}
