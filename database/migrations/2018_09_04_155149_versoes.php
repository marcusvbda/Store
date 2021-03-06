<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Versoes extends Migration
{
    
    public function up()
    {
        Schema::create('versoes', function (Blueprint $table) 
        {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->string('id',50)->unique()->index()->primary();
            $table->string('nome',30);
            $table->longtext('descricao');         
        });
    }

    public function down()
    {
        Schema::dropIfExists('versos');
    }
}
