<?php
namespace App\Http\Controllers\Cadastros\Principais\Produtos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\DefaultCrudController;
use Illuminate\Support\Facades\Schema;
use App\Utils\excel;
use App\User;
use App\Context;

class ProdutosController extends Controller
{
    public function __construct()
    {
        $this->table          = "produtos";
        $this->primaryKey     = "id";
        $this->route          = "cadastros.principais.produtos";
        $this->principalView  = "cadastros.principais.produtos.index";
        $this->createView     = "cadastros.principais.produtos.create";
        $this->editView       = "cadastros.principais.produtos.edit";
        $this->showView       = "cadastros.principais.produtos.show";
        $this->createSkuView  = "cadastros.principais.produtos.skus.create";
    }

    public function index()
    {
        try 
        {
            $nome = "";
            $marcaId = "";
            $data = DB::table("produtos")
            ->select("produtos.*","produtoMarca.nome as marca")
            ->join("produtoMarca","produtoMarca.id","=","produtos.marcaId");
            
            if(!empty($_GET["nome"]))
            {
                $nome = $_GET["nome"];
                $data = $data->where("produtos.nome","like","%{$nome}%");                
            }
            if(!empty($_GET["marcaId"]))
            {
                $marcaId = $_GET["marcaId"];
                $data = $data->where("produtos.marcaId","=","{$marcaId}");                
            }
            $data = $data->get();
            $marcas = DB::table("produtoMarca")->get();
            return view($this->principalView,compact('data','nome','marcaId','marcas'));
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            return view("errors.500",compact("message"));
        }
    }

    public function create()
    {
        $marcas = DB::table("produtoMarca")->get();
        $categorias = DB::table("produtoCategoria")->get();
        $subCategorias = DB::table("produtoSubCategoria")->get();
        return view($this->createView,compact('marcas','categorias','subCategorias'));
    }

    public function show($id)
    {
        echo $id;
    }
    public function edit($id)
    {
        echo $id;
    }

    public function skuCreate($produtoId)
    {
        $produto = DB::table("produtos")->find($produtoId);
        return view($this->createSkuView,compact('produto'));
    }

    public function store()
    {
        try 
        {
            date_default_timezone_set(session('timezone'));
            DB::beginTransaction();
            // $especificacoes = json_decode($_POST["especificacoes"]);
            unset($_POST["_token"],$_POST["_method"],$_POST["especificacoes"]);
            $_POST["dataCadastro"] = date('Y-m-d');
            $_POST["horaCadastro"] = date("H:i:s");
            $_POST["id"] = uniqid();        
            $data = DB::table($this->table)->insert($_POST);
            // foreach($especificacoes as $key => $value)
            // {
            //     DB::table("produtoProdutoEspecificacao")->insert(["especificacaoId"=>str_replace("_", "",$key), "valor"=>$value, "produtoId" => $_POST["id"] ]);
            // }
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