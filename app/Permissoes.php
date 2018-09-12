<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Permissoes extends Model
{
    public $timestamps   = false;
    public $incrementing = false;
    
    public function __construct()
    {
        $this->table  = "permissoes";
    }


    public function grupoAcessoPermissao()
    {
        return $this->HasMany(\App\GruposAcessoPermissoes::class,"permissaoId","id");
    }

}