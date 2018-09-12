<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    public $incrementing = false;
    public $timestamps   = false;

    public function __construct()
    {
        $this->table  = "usuarios";
    }

    public function tenant()
    {
        return $this->hasOne(\App\Tenants::class,"id","tenantId");
    }
    
    public function grupoAcesso()
    {
        return $this->hasOne(\App\GruposAcesso::class,"id","grupoAcessoId");
    }

    public function tenants()
    {
        return $this->hasMany(\App\Tenants::class,"usuarioId","id");
    }

    public function hasPermission(Permissoes $permissao)
    {   
        return $permissao->grupoAcessoPermissao->contains("grupoAcessoId",$this->grupoAcesso->id);      
    }

}