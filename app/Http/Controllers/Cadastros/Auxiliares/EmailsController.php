<?php
namespace App\Http\Controllers\Cadastros\Auxiliares;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\DefaultCrudController;

class EmailsController extends DefaultCrudController
{
    public function __construct()
    {
        $this->table        = "modeloEmail";
        $this->primaryKey   = "id";
        $this->route        = "cadastros.auxiliares.emails";
        $this->principalView   = "cadastros.auxiliares.emails";
    }

    public function index(Request $request)
    {
        $nome = "";
        $assunto = "";
        try 
        {
            $request = $request->all();
            $data  = DB::table($this->table)->where("tenantId","=",Auth::user()->tenantId);
            if(isset($request["nome"]))
            {
                $nome = strtoupper($request["nome"]);
                if($nome!="")
                    $data = $data->where("modeloEmail.nome","like","%{$nome}%");
            }
            if(isset($request["assunto"]))
            {
                $assunto = strtoupper($request["assunto"]);
                if($assunto!="")
                    $data = $data->where("modeloEmail.assunto","like","%{$assunto}%");
            }
            $data = $data->get();
            
            return view($this->route,compact('data','nome','assunto'));
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            return view("errors.500",compact("message"));
        }
    }

    

}