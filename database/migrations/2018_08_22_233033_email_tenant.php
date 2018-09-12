<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmailTenant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emailTenant', function (Blueprint $table) 
        {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->string('id',50)->unique()->index()->primary();
            $table->string('email',450);
            $table->string('servidor',450);
            $table->string('porta',450);
            $table->string('criptografia',450);
            $table->string('senha',450);
            $table->string('driver',450);
            $table->integer('testado')->default(0);
            $table->string('tenantId',50)->index();
            $table->foreign('tenantId')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');                
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emailTenant');
    }
}
