<?php
namespace App\Http\Controllers\Cadastros\Auxiliares;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public function get()
    {
        try 
        {
            $data = DB::table($this->table)->where("id","=",$_GET["id"])->first();
            return response()->json(["code"=>202,"success"=>true, "data" => $data]);
        } 
        catch (\Exception $e) 
        {
            return response()->json(["code"=>202,"success"=>false,"message" => $e->getMessage()]);
        }
    }

    public function store()
    {   
        unset($_POST[$this->primaryKey]);
        unset($_POST["files"]);
        return parent::store();
    }

    public function put()
    {  
        unset($_POST["files"]);
        return parent::put();
    }

    public function index()
    {
        $nome = "";
        $assunto = "";
        try 
        {
            $data  = DB::table($this->table)->where("tenantId","=",Auth::user()->tenantId);
            if(isset($_GET["nome"]))
            {
                $nome = strtoupper($_GET["nome"]);
                if($nome!="")
                    $data = $data->where("modeloEmail.nome","like","%{$nome}%");
            }
            if(isset($_GET["assunto"]))
            {
                $assunto = strtoupper($_GET["assunto"]);
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