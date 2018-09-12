<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantParametros extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TenantParametros', function (Blueprint $table) 
        {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->string('parametroId',50);
            $table->foreign('parametroId')
                ->references('id')
                ->on('parametros')
                ->onDelete('restrict');
            $table->string('valor',250)->nullable();
            $table->string('tenantId',50)->index();
            $table->foreign('tenantId')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');

            $table->primary(['parametroId','tenantId']);            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('TenantParametros');
    }
}
