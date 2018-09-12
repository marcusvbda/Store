<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Tenants extends Model
{
    public $timestamps   = false;
    public $incrementing = false;

    public function __construct()
    {
        $this->table  = "tenants";
    }

}