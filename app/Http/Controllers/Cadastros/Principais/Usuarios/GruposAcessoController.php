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

    public function index()
    {
        $nome = "";
        try 
        {
            $data  = DB::table($this->table);
            if(isset($_GET["nome"]))
            {
                $nome = strtoupper($_GET["nome"]);
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

    public function get()
    { 
        try 
        {
            $primaryKey = $_GET[$this->primaryKey];
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

    public function store()
    {
        try 
        {
            DB::beginTransaction();
            unset($_POST["_token"],$_POST["_method"],$_POST[$this->primaryKey]);
            $grupoAcessoId = uniqid();
            DB::table("gruposAcesso")->insert(["id"=>$grupoAcessoId,"nome"=>$_POST["nome"]]);
            foreach( Permissoes::get() as $permissao)
            {
                if(isset($_POST[$permissao->nome]))
                {
                    DB::table("grupoAcessoPermissoes")->insert(["permissaoId"=>$permissao->id,"grupoAcessoId"=>$grupoAcessoId]);
                }
            }
            DB::commit();
            return redirect()->route($this->route);
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            DB::rollBack();
            return view("errors.500",compact("message"));
        }
    }


    public function put()
    { 
        try 
        {
            DB::beginTransaction();
            unset($_POST["_token"],$_POST["_method"]);
            $grupoAcesso = DB::table("gruposAcesso")->find($_POST[$this->primaryKey]);
            DB::table("gruposAcesso")->where("id","=",$_POST[$this->primaryKey])->update(["nome" => $_POST["nome"]]);
            DB::table("grupoAcessoPermissoes")->where("grupoAcessoId","=",$grupoAcesso->id)->delete();
            foreach( Permissoes::get() as $permissao)
            {
                if(isset($_POST[$permissao->nome]))
                {
                    DB::table("grupoAcessoPermissoes")->insert(["permissaoId" => $permissao->id , "grupoAcessoId" => $grupoAcesso->id]);
                }
            }
            DB::commit();
            return redirect()->route($this->route);            
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            DB::rollBack();
            return view("errors.500",compact("message"));
        }
    }

}