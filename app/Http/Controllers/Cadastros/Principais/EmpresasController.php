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

    public function index(Request $request)
    {
        try 
        {
            $request = $request->all();
            $principal = "";
            $cnpj = "";
            $razao = "";
            $nome = "";
            $principal = -1;
            $data = DB::table($this->table);
            if(!empty($request["cnpj"]))
            {
                $cnpj = $request["cnpj"];
                $data = $data->where("cnpj","like","%{$cnpj}%");                
            }
            if(!empty($request["nome"]))
            {
                $nome = $request["nome"];
                $data = $data->where("nome","like","%{$nome}%");                
            }
            if(!empty($request["razao"]))
            {
                $razao = $request["razao"];
                $data = $data->where("razao","like","%{$razao}%");                
            }
            if(isset($request["principal"]))
            {
                $principal = $request["principal"];
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

    public function store(Request $request)
    { 
        try 
        {
            $request = $request->all();
            DB::beginTransaction();
            unset($request["_token"],$request["_method"]);
            $request["id"] = uniqid();
            if(Schema::hasColumn($this->table,"tenantId"))
            {
                $request["tenantId"] = Auth::user()->tenantId;
            }           
            $data = DB::table($this->table)->insert($request);
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