<?php
namespace App\Http\Controllers\Cadastros\Principais\Produtos;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\DefaultCrudController;
use Illuminate\Http\Request;


class CategoriaProdutoController extends DefaultCrudController
{
    public function __construct()
    {
        $this->table        = "produtoCategoria";
        $this->primaryKey   = "id";
        $this->route        = "cadastros.principais.produtos.categorias";
        $this->principalView   = "cadastros.principais.produtos.categorias.index";
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
            $cat = [];
            foreach($data as $_d)
            {
                array_push($cat,(object)[
                    "id"=>$_d->id,
                    "nome"=>$_d->nome,
                    "subCategorias" => DB::table("produtoSubCategoria")->where("categoriaId","=",$_d->id)->get()
                ]);
            }
            $data=$cat;
            return view($this->principalView,compact('data','nome'));
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            return view("errors.500",compact("message"));
        }
    }

    public function store(Request $request)
    {
        $request["nome"] = strtoupper($request["nome"]);
        return parent::store($request);
    }

    public function put(Request $request)
    {
        $request["nome"] = strtoupper($request["nome"]);
        return parent::put($request);
    }

    public function substore(Request $request)
    {
        try 
        {
            $request = $request->all();
            unset($request["_token"],$request["_method"]);
            $request["id"] = uniqid();    
            $request["nome"] = strtoupper($request["nome"]);      
            $data = DB::table("produtoSubCategoria")->insert($request);
            return redirect()->route($this->route);
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            return view("errors.500",compact("message"));
        }
    }

    public function deletesub(Request $request)
    {
        try 
        {
            $data = DB::table("produtoSubCategoria")->where("id","=",$request->only("id"));
            $data->delete();
            return response()->json(["code"=>202,"success"=>true]);
        } 
        catch (\Exception $e) 
        {
            return response()->json(["code"=>202,"success"=>false,"message" => $e->getMessage()]);
        }
    }

    public function putsub(Request $request)
    { 
        try 
        {
            $request = $request->all();
            unset($request["_token"],$request["_method"]);
            $request["nome"] = strtoupper($request["nome"]);
            $data = DB::table("produtoSubCategoria")->where("id","=",$request["id"]);
            $data = $data->update($request);
            return redirect()->route($this->route);
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            return view("errors.500",compact("message"));
        }
    }

    public function getsub(Request $request)
    { 
        try 
        {
            $request = $request->all();
            $data = DB::table("produtoSubCategoria")->where("id","=",$request["id"]);
            $data = $data->first();

            return response()->json(["code"=>202,"success"=>true,"data"=>$data]);
        } 
        catch (\Exception $e) 
        {
            return response()->json(["code"=>202,"success"=>false,"message" => $e->getMessage()]);
        }
    }




}