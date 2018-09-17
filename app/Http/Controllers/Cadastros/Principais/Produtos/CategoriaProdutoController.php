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

    public function store()
    {
        $_POST["nome"] = strtoupper($_POST["nome"]);
        return parent::store();
    }

    public function put()
    {
        $_POST["nome"] = strtoupper($_POST["nome"]);
        return parent::put();
    }

    public function substore()
    {
        try 
        {
            unset($_POST["_token"],$_POST["_method"]);
            $_POST["id"] = uniqid();    
            $_POST["nome"] = strtoupper($_POST["nome"]);      
            $data = DB::table("produtoSubCategoria")->insert($_POST);
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

    public function putsub()
    { 
        try 
        {
            unset($_POST["_token"],$_POST["_method"]);
            $_POST["nome"] = strtoupper($_POST["nome"]);
            $data = DB::table("produtoSubCategoria")->where("id","=",$_POST["id"]);
            $data = $data->update($_POST);
            return redirect()->route($this->route);
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            return view("errors.500",compact("message"));
        }
    }

    public function getsub()
    { 
        try 
        {
            $data = DB::table("produtoSubCategoria")->where("id","=",$_GET["id"]);
            $data = $data->first();

            return response()->json(["code"=>202,"success"=>true,"data"=>$data]);
        } 
        catch (\Exception $e) 
        {
            return response()->json(["code"=>202,"success"=>false,"message" => $e->getMessage()]);
        }
    }




}