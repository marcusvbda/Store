<?php
namespace App\Http\Controllers\Cadastros\Principais;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\DefaultCrudController;
use Illuminate\Support\Facades\Schema;
use App\Utils\excel;
use App\User;
use App\Context;

class EmpresasController extends DefaultCrudController
{
    public function __construct()
    {
        $this->table          = "tenants";
        $this->primaryKey     = "id";
        $this->route          = "cadastros.principais.empresas";
        $this->principalView  = "cadastros.principais.empresas.index";
        $this->createView     = "cadastros.principais.empresas.create";
        $this->showView       = "cadastros.principais.empresas.show";
        $this->editView       = "cadastros.principais.empresas.edit";
    }

    public function index()
    {
        try 
        {
            $principal = "";
            $cnpj = "";
            $razao = "";
            $nome = "";
            $principal = -1;
            $data = DB::table($this->table);
            if(!empty($_GET["cnpj"]))
            {
                $cnpj = $_GET["cnpj"];
                $data = $data->where("cnpj","like","%{$cnpj}%");                
            }
            if(!empty($_GET["nome"]))
            {
                $nome = $_GET["nome"];
                $data = $data->where("nome","like","%{$nome}%");                
            }
            if(!empty($_GET["razao"]))
            {
                $razao = $_GET["razao"];
                $data = $data->where("razao","like","%{$razao}%");                
            }
            if(isset($_GET["principal"]))
            {
                $principal = $_GET["principal"];
                if($principal!=-1)
                {
                    $data = $data->where("principal","=",$principal);   
                }             
            }
            $tenants = $data->get();
            return view($this->principalView,compact('tenants','cnpj','nome','principal','razao'));
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            return view("errors.500",compact("message"));
        }
    }

    public function create()
    {
        return view($this->createView);
    }

    public function edit($id)
    {
        $tenant = DB::table("tenants")->find($id);
        return view($this->editView,compact('tenant'));
    }

    public function show($id)
    {
        $tenant = DB::table("tenants")->find($id);
        return view($this->showView,compact('tenant'));
    }

    public function store()
    { 
        try 
        {
            unset($_POST["_token"],$_POST["_method"]);
            $_POST["id"] = uniqid();
            if(Schema::hasColumn($this->table,"tenantId"))
            {
                $_POST["tenantId"] = Auth::user()->tenantId;
            }           
            $data = DB::table($this->table)->insert($_POST);
            DB::table('TenantParametros')->insert(
            [
                "parametroId"  =>  "casasDecimais",
                "valor"        =>  "2",
                "tenantId"     =>  $_POST["id"] 
            ]);
            DB::table('TenantParametros')->insert(
            [
                "parametroId"  =>  "distribuirFollowsAutomaticamente",
                "valor"        =>  "1",
                "tenantId"     =>  $_POST["id"] 
            ]);
            DB::table('TenantParametros')->insert(
            [
                "parametroId"  =>  "diasParaAgendamentoPadrao",
                "valor"        =>  "2",
                "tenantId"     =>  $_POST["id"] 
            ]);
            return redirect()->route($this->route);
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
            $tenant = DB::table("tenants")->find($request->only($this->primaryKey));
            if($tenant->principal)
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