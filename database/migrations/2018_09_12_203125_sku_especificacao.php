<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SkuEspecificacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skuEspecificacao', function (Blueprint $table) 
        {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->string('id',50)->unique()->index()->primary();
            $table->string('nome',100)->unique();
            $table->string('tipoCampo',20);
            $table->longtext('opcoes')->nullable();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('skuEspecificacao');
    }
}
