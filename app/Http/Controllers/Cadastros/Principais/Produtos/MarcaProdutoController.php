<?php
namespace App\Http\Controllers\Cadastros\Principais\Produtos;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\DefaultCrudController;
use Illuminate\Http\Request;


class MarcaProdutoController extends DefaultCrudController
{
    public function __construct()
    {
        $this->table        = "produtoMarca";
        $this->primaryKey   = "id";
        $this->route        = "cadastros.principais.produtos.marcas";
        $this->principalView   = "cadastros.principais.produtos.marcas.index";
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
                {
                    $data = $data->where("nome","like","%{$nome}%");
                }
            }
            $data = $data->get();
            
            return view($this->principalView,compact('data','nome'));
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            return view("errors.500",compact("message"));
        }
    }


}