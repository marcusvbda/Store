<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class GruposAcessoPermissoes extends Model
{
    public $timestamps   = false;
    public $incrementing = false;
    
    public function __construct()
    {
        $this->table  = "grupoAcessoPermissoes";
    }

    public function permissao()
    {
        return $this->belongsTo(\App\Permissoes::class,"permissaoId","id");
    }

    public function grupoAcesso()
    {
        return $this->belongsTo(\App\GruposAcesso::class,"grupoAcessoId","id");
    }
}
