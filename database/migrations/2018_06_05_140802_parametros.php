<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Parametros extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parametros', function (Blueprint $table) 
        {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->string('id',50)->unique()->index()->primary();
            $table->string('label',100);
            $table->string('descricao',100)->nullable();
            $table->string('type',30)->default("text");
            $table->string('maxlength',150)->nullable();
            $table->integer('max')->nullable();
            $table->integer('min')->nullable();
            $table->integer('bootstrapCol');
            $table->boolean('required')->default(false);
            $table->float('step',8, 2)->nullable();
            $table->string('placeholder',50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parametros');
    }
}
