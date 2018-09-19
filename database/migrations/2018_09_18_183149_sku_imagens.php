<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SkuImagens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skuImagens', function (Blueprint $table) 
        {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->string('id',50)->unique()->index()->primary();
            $table->string('nome',100);
            $table->string('legenda',100);
            $table->boolean('principal')->default(0);
            $table->longtext('url');
            $table->string('skuId',50)->index();
            $table->foreign('skuId')
                ->references('id')
                ->on('skus')
                ->onDelete('cascade'); 
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('skuImagens');
    }
}
