<?php
namespace App\Http\Controllers\Cadastros\Principais\Usuarios;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DefaultCrudController;
use App\User;
use App\Permissoes;
use Illuminate\Support\Facades\DB;


class GruposAcessoController extends DefaultCrudController
{
    public function __construct()
    {
        $this->table        = "gruposAcesso";
        $this->primaryKey   = "id";
        $this->route        = "cadastros.principais.usuarios.gruposAcesso";
        $this->principalView   = "cadastros.principais.usuarios.gruposAcesso";
    }

    public function index(Request $request)
    {
        try 
        {
            $request = $request->all();
            $nome = "";
            $data  = DB::table($this->table);
            if(isset($request["nome"]))
            {
                $nome = strtoupper($request["nome"]);
                if($nome!="")
                    $data = $data->where("nome","like","%{$nome}%");
            }
            $data = $data->get();
            $gruposPermissao = DB::table("grupoPermissao" )->get();
            return view($this->principalView,compact('data','nome','gruposPermissao'));
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            return view("errors.500",compact("message"));
        }
    }

    public function get(Request $request)
    { 
        try 
        {
            $request = $request->all();
            $primaryKey = $request[$this->primaryKey];
            $data = [];
            $grupoAcesso = DB::table("gruposAcesso")->find($primaryKey);
            foreach(Permissoes::get() as $permissao)
            {
                $temPermissao = count(DB::table("grupoAcessoPermissoes")->where("grupoAcessoId","=",$grupoAcesso->id)->where("permissaoId","=",$permissao->id)->get())>0;
                array_push($data,[
                    "id" => $grupoAcesso->id,
                    "nome" => $grupoAcesso->nome,
                    $permissao->nome => ( $temPermissao  ? true : false )
                ]);
            }
            return response()->json(["code"=>202,"success"=>true,"data"=>$data]);
        } 
        catch (\Exception $e) 
        {
            return response()->json(["code"=>202,"success"=>false,"message" => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try 
        {
            $request = $request->all();
            unset($request["_token"],$request["_method"],$request[$this->primaryKey]);
            $grupoAcessoId = uniqid();
            DB::table("gruposAcesso")->insert(["id"=>$grupoAcessoId,"nome"=>$request["nome"]]);
            foreach( Permissoes::get() as $permissao)
            {
                if(isset($request[$permissao->nome]))
                {
                    DB::table("grupoAcessoPermissoes")->insert(["permissaoId"=>$permissao->id,"grupoAcessoId"=>$grupoAcessoId]);
                }
            }
            return redirect()->route($this->route);
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            return view("errors.500",compact("message"));
        }
    }


    public function put(Request $request)
    { 
        try 
        {
            $request = $request->all();
            unset($request["_token"],$request["_method"]);
            $grupoAcesso = DB::table("gruposAcesso")->find($request[$this->primaryKey]);
            DB::table("gruposAcesso")->where("id","=",$request[$this->primaryKey])->update(["nome" => $request["nome"]]);
            DB::table("grupoAcessoPermissoes")->where("grupoAcessoId","=",$grupoAcesso->id)->delete();
            foreach( Permissoes::get() as $permissao)
            {
                if(isset($request[$permissao->nome]))
                {
                    DB::table("grupoAcessoPermissoes")->insert(["permissaoId" => $permissao->id , "grupoAcessoId" => $grupoAcesso->id]);
                }
            }
            return redirect()->route($this->route);            
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            return view("errors.500",compact("message"));
        }
    }

}