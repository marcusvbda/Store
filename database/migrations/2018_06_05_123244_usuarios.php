<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Usuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) 
        {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->string('id',50)->unique()->index()->primary();
            $table->string('renewToken',50)->nullable();
            $table->string('email',250)->unique();
            $table->date('dtNascimento')->nullable();
            $table->string('senha',250);
            $table->string('nome',250);
            $table->boolean('root')->default(0);
            $table->boolean('mudarSenha')->default(0);
            $table->rememberToken();
            $table->string('grupoAcessoId',50)->index();
            $table->foreign('grupoAcessoId')
                ->references('id')
                ->on('gruposAcesso')
                ->onDelete('restrict');
            $table->string('tenantId',50)->nullable()->index();
            $table->foreign('tenantId')
                ->references('id')
                ->on('tenants')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
}
