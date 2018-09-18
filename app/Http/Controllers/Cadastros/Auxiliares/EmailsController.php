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

    public function uploadImagem(Request $request)
    {
        try 
        {
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/upload/imgs');
            $image->move($destinationPath, $name);
            $url = asset("public/upload/imgs/{$name}");
            return response()->json(["code"=>202,"success"=>true,"data"=>$url]);
        } 
        catch (\Exception $e) 
        {
            return response()->json(["code"=>202,"success"=>false,"message" => $e->getMessage()]);
        }
    }

    

}