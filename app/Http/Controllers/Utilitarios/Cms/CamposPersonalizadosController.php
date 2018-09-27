<?php
namespace App\Http\Controllers\Utilitarios\Cms;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\DefaultCrudController;

class CamposPersonalizadosController extends DefaultCrudController
{
    public function __construct()
    {
        $this->table        = "camposPersonalizados";
        $this->primaryKey   = "id";
        $this->route        = "utilitarios.cms.camposPersonalizados";
        $this->principalView   = "utilitarios.cms.camposPersonalizados";
    }

    public function index(Request $request)
    {
        $nome = "";
        try 
        {
            $request = $request->all();
            $data  = DB::table($this->table);
            if(isset($request["nome"]))
            {
                $nome = strtoupper($request["nome"]);
                if($nome!="")
                    $data = $data->where("nome","like","%{$nome}%");
            }
            $data = $data->get();
            
            return view($this->route,compact('data','nome'));
            return view('utilitarios.cms.camposCustomizacos');
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            return view("errors.500",compact("message"));
        }
    }
    

}