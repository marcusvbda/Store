<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GrupoAcessoPermissoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupoAcessoPermissoes', function (Blueprint $table) 
        {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->string('grupoAcessoId',50)->index();
            $table->foreign('grupoAcessoId')
                ->references('id')
                ->on('gruposAcesso')
                ->onDelete('cascade');
            $table->string('permissaoId',50)->index();
            $table->foreign('permissaoId')
                ->references('id')
                ->on('permissoes')
                ->onDelete('restrict');
            
            $table->primary(['grupoAcessoId', 'permissaoId'],'grupoAcessoPermissoes_pk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grupoAcessoPermissoes');
    }
}
