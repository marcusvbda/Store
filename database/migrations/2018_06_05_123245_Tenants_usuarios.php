<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantsUsuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenantsUsuarios', function (Blueprint $table) 
        {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->string('usuarioId',50)->index();
            $table->foreign('usuarioId')
                ->references('id')
                ->on('usuarios')
                ->onDelete('cascade');
            $table->string('tenantId',50)->index();
            $table->foreign('tenantId')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');

            $table->primary(['usuarioId', 'tenantId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenantsUsuarios');
    }
}
