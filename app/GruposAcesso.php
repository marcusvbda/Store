<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class GruposAcesso extends Model
{
    public $timestamps   = false;
    public $incrementing = false;

    public function __construct()
    {
        $this->table  = "gruposAcesso";
    }

    public function usuarios()
    {
        return $this->hasMany(\App\User::class,"id","grupoAcessoId");
    }

    public function empresa()
    {
        return $this->belongsTo(\App\Empresa::class,"empresa_id","id");
    }

    public function permissoes()
    {
        return $this->hasManyThrough(
            \App\Permissoes::class,
            \App\GruposAcesso_permissoes::class,
            'permissao_id', 
            'id', 
            'id', 
            'permissao_id' 
        );
    }

}