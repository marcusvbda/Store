<?php
namespace App\Http\Controllers\Cadastros\Principais\Usuarios;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\DefaultCrudController;
use Illuminate\Support\Facades\Schema;
use App\Utils\excel;
use App\User;
use App\Context;

class UsuariosController extends Controller
{
    public function __construct()
    {
        $this->table          = "usuarios";
        $this->primaryKey     = "id";
        $this->route          = "cadastros.principais.usuarios";
        $this->principalView  = "cadastros.principais.usuarios.index";
        $this->createView     = "cadastros.principais.usuarios.create";
        $this->editView       = "cadastros.principais.usuarios.edit";
        $this->showView       = "cadastros.principais.usuarios.show";
    }

    public function index()
    {
        try 
        {
            $nome = "";
            $email = "";
            $dtNascimentoDe = "";
            $dtNascimentoAte = "";
            $data = DB::table("usuarios");
            
            if(!empty($_GET["nome"]))
            {
                $nome = $_GET["nome"];
                $data = $data->where("usuarios.nome","like","%{$nome}%");                
            }
            if(!empty($_GET["email"]))
            {
                $email = $_GET["email"];
                $data = $data->where("usuarios.email","like","%{$email}%");                
            }

            if(!empty($_GET["dtNascimentoDe"]))
            {
                $dtNascimentoDe = $_GET["dtNascimentoDe"];
                $data = $data->where("dtNascimento",">=",$dtNascimentoDe);                
            }

            if(!empty($_GET["dtNascimentoAte"]))
            {
                $dtNascimentoAte = $_GET["dtNascimentoAte"];
                $data = $data->where("usuarios.dtNascimento","<=",$dtNascimentoAte);                
            }

            $data = $data->get();
            return view($this->principalView,compact('data','nome','email','dtNascimentoDe','dtNascimentoAte'));
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            return view("errors.500",compact("message"));
        }
    }

    public function create()
    {
        try 
        {
            $tenants = DB::table("tenants")->get();
            $gruposAcesso = DB::table("gruposAcesso")->get();
            return view($this->createView,compact('gruposAcesso','tenants'));
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            return view("errors.500",compact("message"));
        }
    }

    public function edit($id)
    {
        try 
        {
            $gruposAcesso = DB::table("gruposAcesso")->get();
            $usuario = User::find($id);
            if($usuario->root)
                abort(403);
            $tenants = DB::table("tenants")->get();            
            return view($this->editView,compact('gruposAcesso','usuario','tenants'));
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            return view("errors.500",compact("message"));
        }
    }

    public function show($id)
    {
        try 
        {
            $usuario = User::find($id);
            $tenants = DB::table("tenants")->get();            
            return view($this->showView,compact('usuario','tenants'));
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            return view("errors.500",compact("message"));
        }
    }

    public function store()
    {
        try 
        {
            
            unset($_POST["_token"],$_POST["_method"],$_POST[$this->primaryKey]);
            $userId = uniqid();           
            $_POST["id"]       = $userId;       
            $_POST["senha"]    = md5($_POST["senha"]);    
            $polos = explode(",",$_POST["tenantId"]);
            unset($_POST["tenantId"]);   
            DB::table($this->table)->insert($_POST);
            foreach($polos as $polo)
            {
                DB::table("tenantsUsuarios")->insert(["usuarioId"=>$userId,"tenantId"=>$polo]);
            }
            return redirect()->route($this->showView,["id"=>$userId]);
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            return view("errors.500",compact("message"));
        }
    }

    public function put($id)
    {
        try 
        {
            $usuario = User::find($id);
            if($usuario->root)
                abort(403);

            unset($_POST["_token"],$_POST["_method"],$_POST[$this->primaryKey]);
            
            if(!empty($_POST["senha"]))
                $_POST["senha"]  = md5($_POST["senha"]);    
            else
                unset($_POST["senha"]);
            
            $polos = explode(",",$_POST["tenantid"]);
            $tenantId = $_POST["tenantId"];
            unset($_POST["tenantId"]);   
            DB::table("tenantsUsuarios")->where("usuarioId","=",$id)->delete();
            foreach($polos as $polo)
            {
                DB::table("tenantsUsuarios")->insert(["usuarioId"=>$id,"tenantId"=>$polo]);
            }
            unset($_POST["tenantid"]);
            $_POST["tenantId"] = null;
            DB::table($this->table)->where("id","=",$id)->update($_POST);   

            return redirect()->route($this->showView,["id"=>$id]);
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            return view("errors.500",compact("message"));
        }
    }

    public function delete(Request $request)
    { 
        try 
        {
            $usuario = DB::table("usuarios")->find($request->only($this->primaryKey));
            if($usuario->root)
                abort(403);
            $data = DB::table($this->table)->where($this->primaryKey,"=",$request->only($this->primaryKey))->delete();
            return response()->json(["code"=>202,"success"=>true]);
        } 
        catch (\Exception $e) 
        {
            return response()->json(["code"=>202,"success"=>false,"message" => $e->getMessage()]);
        }
    }


}