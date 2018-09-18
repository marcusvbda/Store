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
use App\Utils\Helper;


class ProdutosController extends DefaultCrudController
{
    public function __construct()
    {
        $this->table          = "produtos";
        $this->primaryKey     = "id";
        $this->route          = "cadastros.principais.produtos";
        $this->showRoute      = "cadastros.principais.produtos.show";
        $this->principalView  = "cadastros.principais.produtos.index";
        $this->createView     = "cadastros.principais.produtos.create";
        $this->editView       = "cadastros.principais.produtos.edit";
        $this->routeShowSku   = "cadastros.principais.produtos.skus.show";
        $this->showSkuView    = "cadastros.principais.produtos.skus.show";
        $this->editSkuView    = "cadastros.principais.produtos.skus.edit";
        $this->showView       = "cadastros.principais.produtos.show";
        $this->createSkuView  = "cadastros.principais.produtos.skus.create";
    }

    public function index(Request $request)
    {
        try 
        {
            $request = $request->all();
            $nome = "";
            $marcaId = "";
            $data = DB::table("produtos")
            ->select("produtos.*","produtoMarca.nome as marca")
            ->join("produtoMarca","produtoMarca.id","=","produtos.marcaId");
            
            if(!empty($request["nome"]))
            {
                $nome = $request["nome"];
                $data = $data->where("produtos.nome","like","%{$nome}%");                
            }
            if(!empty($request["marcaId"]))
            {
                $marcaId = $request["marcaId"];
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
        $subCategorias = DB::table("produtoSubCategoria")
            ->join("produtoCategoria","produtoCategoria.id","=","produtoSubCategoria.categoriaId")
            ->select("produtoSubCategoria.*","produtoCategoria.nome as categoria")
            ->get();
        return view($this->createView,compact('marcas','categorias','subCategorias'));
    }

    public function show($id)
    {
        $marcas = DB::table("produtoMarca")->get();
        $categorias = DB::table("produtoCategoria")->get();
        $subCategorias = DB::table("produtoSubCategoria")->get();
        $_subCategoriasSelecionadas = DB::table("produtoProdutoSubCategoria")->where("produtoId","=",$id)->get();
        $subsSelecionados = [];
        foreach ($_subCategoriasSelecionadas as $sub ) 
        {
            array_push($subsSelecionados,$sub->produtoSubCategoriaId);
        }
        $produto = DB::table("produtos")->find($id);
        return view($this->showView,compact('produto','marcas','categorias','subCategorias','subsSelecionados'));
    }
    public function edit($id)
    {
        $marcas = DB::table("produtoMarca")->get();
        $categorias = DB::table("produtoCategoria")->get();
         $subCategorias = DB::table("produtoSubCategoria")
            ->join("produtoCategoria","produtoCategoria.id","=","produtoSubCategoria.categoriaId")
            ->select("produtoSubCategoria.*","produtoCategoria.nome as categoria")
            ->get();
        $_subCategoriasSelecionadas = DB::table("produtoProdutoSubCategoria")->where("produtoId","=",$id)->get();
        $subsSelecionados = [];
        foreach ($_subCategoriasSelecionadas as $sub ) 
        {
            array_push($subsSelecionados,$sub->produtoSubCategoriaId);
        }
        $produto = DB::table("produtos")->find($id);
        return view($this->editView,compact('produto','marcas','categorias','subCategorias','subsSelecionados'));
    }

    public function skuCreate($produtoId)
    {
        $produto = DB::table("produtos")->find($produtoId);
        $outrosSkus = DB::table("skus")->get();
        return view($this->createSkuView,compact('produto','outrosSkus'));
    }

    public function showSku($produtoId,$skuId)
    {
        $produto = DB::table("produtos")->find($produtoId);
        $sku = DB::table("skus")->where("id","=",$skuId)->first();
        $semelhantes=Helper::implodeEloquent("nome",DB::table("skuSemelhantes")
                ->join("skus","skus.id","skuSemelhantes.semelhanteId")
                ->select("skus.nome as nome")
                ->where("skuSemelhantes.skuId","=",$skuId)
                ->get());
        $sugestoes=Helper::implodeEloquent("nome",DB::table("skuSugestao")
            ->join("skus","skus.id","skuSugestao.sugestaoId")
            ->where("skuSugestao.skuId","=",$skuId)
            ->select("skus.nome as nome")->get());
        $acessorios=Helper::implodeEloquent("nome",DB::table("skuAcessorios")
            ->join("skus","skus.id","skuAcessorios.acessorioId")
            ->where("skuAcessorios.skuId","=",$skuId)
            ->select("skus.nome as nome")->get());
        return view($this->showSkuView,compact('sku','produto','sugestoes','acessorios','semelhantes'));
    }

    public function editSku($produtoId,$skuId)
    {
        $produto = DB::table("produtos")->find($produtoId);
        $outrosSkus = DB::table("skus")->join("produtos","produtos.id","skus.produtoId")->get();
        $sku = DB::table("skus")->where("id","=",$skuId)->first();
        $semelhantes=DB::table("skuSemelhantes")
            ->join("skus","skus.id","skuSemelhantes.semelhanteId")
            ->where("skuSemelhantes.skuId","=",$skuId)
            ->get();
        $sugestoes=DB::table("skuSugestao")
            ->join("skus","skus.id","skuSugestao.sugestaoId")
            ->where("skuSugestao.skuId","=",$skuId)
            ->get();
        $acessorios=DB::table("skuAcessorios")
            ->join("skus","skus.id","skuAcessorios.acessorioId")
            ->where("skuAcessorios.skuId","=",$skuId)
            ->get();
        $outrosSkus = DB::table("skus")->get();
        return view($this->editSkuView,compact('sku','produto','outrosSkus','sugestoes','acessorios','semelhantes'));
    }


    public function skuStore($produtoId,Request $request)
    {
        try 
        {
            $request = $request->all();
            date_default_timezone_set(session('timezone'));
            DB::beginTransaction();
            $sugestoes   =  [];
            $semelhantes =  [];
            $acessorios  =  [];
            if(isset($request["sugestoes"]))
                $sugestoes   =  array_map('trim',explode(",", $request["sugestoes"]));
            if(isset($request["semelhantes"]))
                $semelhantes =  array_map('trim',explode(",", $request["semelhantes"]));
            if(isset($request["acessorios"]))
                $acessorios  =  array_map('trim',explode(",", $request["acessorios"]));
            unset($request["_token"],$request["_method"],$request["sugestoes"],$request["semelhantes"],$request["acessorios"]);
            $request["id"] = uniqid();
            $request["dataCadastro"] = date('Y-m-d');
            $request["horaCadastro"] = date("H:i:s");
            $data = DB::table("skus")->insert($request);

            foreach($semelhantes as $semelhante)
            {
                DB::table("skuSemelhantes")->insert(["semelhanteId"=>$semelhante,"skuId"=>$request["id"] ]);
            }
            foreach($acessorios as $acessorio)
            {
                DB::table("skuAcessorios")->insert(["acessorioId"=>$acessorio,"skuId"=>$request["id"] ]);
            }
            foreach($sugestoes as $sugestao)
            {
                DB::table("skuSugestao")->insert(["sugestaoId"=>$sugestao,"skuId"=>$request["id"] ]);
            }
            DB::commit();
            return redirect()->route($this->routeShowSku,["produtoId"=>$request["produtoId"],"skuId"=>$request["id"]  ]);
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            DB::rollBack();
            return view("errors.500",compact("message"));
        }
    }

    public function store(Request $request)
    {
        try 
        {
            $request = $request->all();
            date_default_timezone_set(session('timezone'));
            DB::beginTransaction();
            $subCategorias = array_map('trim',explode(",",$request["subCategorias"] ));
            unset($request["_token"],$request["_method"],$request["especificacoes"],$request["subCategorias"]);
            $request["dataCadastro"] = date('Y-m-d');
            $request["horaCadastro"] = date("H:i:s");
            $request["id"] = uniqid();        
            $data = DB::table($this->table)->insert($request);
            foreach($subCategorias as $sub)
            {
                DB::table("produtoProdutoSubCategoria")->insert(["produtoId"=>$request["id"],"produtoSubCategoriaId"=>$sub ]);
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

    public function put(Request $request)
    {
        try 
        {
            $request = $request->all();
            DB::beginTransaction();
            $subCategorias = array_map('trim',explode(",",$request["subCategorias"] ));
            unset($request["_token"],$request["_method"],$request["especificacoes"],$request["subCategorias"]);
            $data = DB::table($this->table)->where("id","=",$request["id"])->update($request);
            DB::table("produtoProdutoSubCategoria")->where("produtoId","=",$request["id"])->delete();
            foreach($subCategorias as $sub)
            {
                DB::table("produtoProdutoSubCategoria")->insert(["produtoId"=>$request["id"],"produtoSubCategoriaId"=>$sub ]);
            }
            DB::commit();
            return redirect()->route($this->showRoute,["id"=>$request["id"]]);
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            DB::rollBack();
            return view("errors.500",compact("message"));
        }
    }

}