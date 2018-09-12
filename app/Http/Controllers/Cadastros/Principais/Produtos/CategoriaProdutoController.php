<?php
namespace App\Http\Controllers\Cadastros\Principais\Produtos;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\DefaultCrudController;

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
        try 
        {
            $subCategorias = explode(",",$_POST["sub"]);
            unset($_POST["_token"],$_POST["_method"],$_POST["sub"]);
            $_POST["id"] = uniqid();        
            $data = DB::table($this->table)->insert($_POST);
            foreach($subCategorias as $sub)
            {
                DB::table("produtoSubCategoria")->insert(["id" => uniqid(), "categoriaId" => $_POST["id"] , "nome" => trim($sub) ]);
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