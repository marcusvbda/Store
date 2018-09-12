<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SkuSkuEspecificacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skuSkuEspecificacao', function (Blueprint $table) 
        {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->string('skuEspecificacaoId',50)->index();
            $table->foreign('skuEspecificacaoId')
                ->references('id')
                ->on('skuEspecificacao')
                ->onDelete('cascade');      
            $table->string('skuId',50)->index();
            $table->foreign('skuId')
                ->references('id')
                ->on('skus')
                ->onDelete('cascade');
            $table->longtext('valor')->nullable();

            $table->primary(['skuId', 'skuEspecificacaoId']);            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('skuSkuEspecificacao');
    }
}
