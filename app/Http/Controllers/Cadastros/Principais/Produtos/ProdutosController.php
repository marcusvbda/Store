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
            $self = $this;
            return view($this->principalView,compact('self','data','nome','marcaId','marcas'));
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
        $imagens = DB::table("skuImagens")->where("skuId","=",$skuId)->get();
        return view($this->showSkuView,compact('sku','produto','sugestoes','acessorios','semelhantes','imagens'));
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
        $imagens = DB::table("skuImagens")->where("skuId","=",$skuId)->get();
        return view($this->editSkuView,compact('sku','produto','outrosSkus','sugestoes','acessorios','semelhantes','imagens'));
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

    public function setPrincipal($produtoId,$skuId,Request $request)
    {
        try 
        {
            $data = $request->all();
            DB::table("skuImagens")->where("skuId","=",$skuId)->update(["principal"=>0]);
            DB::table("skuImagens")->where("id","=",$data["id"])->update(["principal"=>1]);
            $imagens = DB::table("skuImagens")->where("skuId","=",$skuId)->get();
            return response()->json(["code"=>202,"success"=>true,"data"=>$imagens ]);
        } 
        catch (\Exception $e) 
        {
            return response()->json(["code"=>202,"success"=>false,"message" => $e->getMessage()]);
        }
    }

    public function getFirstSkuImg($produtoId)
    {
        try 
        {
            $skus = DB::table("skus")->where("produtoId","=",$produtoId)->get();
            if(Count($skus)<=0)
                return asset('public/img/sem-foto.png');

            $skus = explode(",",Helper::implodeEloquent("id",$skus));
            $imagens = DB::table("skuImagens")->whereIn("skuId",$skus)->where("principal","=",1)->get();
            if(Count($imagens)<=0)
                return asset('public/img/sem-foto.png');
            else
            {
                return $imagens[rand(0,(count($imagens)-1))]->url;
            }
        } 
        catch (\Exception $e) 
        {
            return asset('public/img/sem-foto.png');
        }
    }

    public function imagemEdit($produtoId,$skuId,Request $request)
    {
        try 
        {
            $data = $request->all();
            $imagens = DB::table("skuImagens")->where("id","=",$data["id"])->update(["legenda"=>$data["legenda"]]);
            return response()->json(["code"=>202,"success"=>true]);
        } 
        catch (\Exception $e) 
        {
            return response()->json(["code"=>202,"success"=>false,"message" => $e->getMessage()]);
        }

    }

    public function deleteImagem($produtoId,$skuId,Request $request)
    {
        try 
        {
            $data = $request->all();
            $imagem = DB::table("skuImagens")->find($data["id"]);
            $arquivo = public_path("/upload/imgs/produtos/{$produtoId}/skus/{$skuId}/".$imagem->nome);
            if(file_exists($arquivo))
                unlink($arquivo);
            if($imagem->principal)
            {
                $_data = DB::table("skuImagens")->where("skuId","=",$skuId);
                if($_data->count()>0)
                {
                    $_data = $_data->first();
                    DB::table("skuImagens")->where("id","=",$_data->id)->update(["principal" => 1]);
                }
            }
            $imagem = DB::table("skuImagens")->where("id","=",$imagem->id)->delete();
            $imagens = DB::table("skuImagens")->where("skuId","=",$skuId)->get();
            return response()->json(["code"=>202,"success"=>true,"data"=>$imagens ]);
        } 
        catch (\Exception $e) 
        {
            return response()->json(["code"=>202,"success"=>false,"message" => $e->getMessage()]);
        }
    }

    public function uploadImagem($produtoId,$skuId,Request $request)
    {
        try 
        {
            $data = $request->all();
            $imagemId = uniqid();
            $data["nome"]= "url_{$imagemId}";
            if( trim($data["url"])!="" )
            {
                if($request->hasFile('imagem'))
                {
                    $image = $request->file('imagem');
                    $name = time().'.'.$image->getClientOriginalExtension();
                    if(!is_dir(public_path("/upload/imgs/produtos")))
                        mkdir(public_path("/upload/imgs/produtos"),0777, true);
                    if(!is_dir(public_path("/upload/imgs/produtos/{$produtoId}")))
                        mkdir(public_path("/upload/imgs/produtos/{$produtoId}"),0777, true);
                    if(!is_dir(public_path("/upload/imgs/produtos/{$produtoId}/skus")))
                        mkdir(public_path("/upload/imgs/produtos/{$produtoId}/skus"),0777, true);
                    if(!is_dir(public_path("/upload/imgs/produtos/{$produtoId}/skus/{$skuId}")))
                        mkdir(public_path("/upload/imgs/produtos/{$produtoId}/skus/{$skuId}"),0777, true);
                    $destinationPath = public_path("/upload/imgs/produtos/{$produtoId}/skus/{$skuId}");
                    $image->move($destinationPath, $name);
                    $data["url"] = asset("public/upload/imgs/produtos/{$produtoId}/skus/{$skuId}/{$name}");
                    $data["nome"]=$name;
                }
            }

            if(DB::table("skuImagens")->where("skuId","=",$skuId)->count()<=0)
            {
                $data["principal"]=1;
            }
            DB::table("skuImagens")->insert([
                    "id"=>$imagemId,"skuId"=>$skuId,"nome"=>$data["nome"],"legenda"=>$data["legenda"],"url"=>$data["url"],"principal"=>$data["principal"] 
            ]);
            $imagens = DB::table("skuImagens")->where("skuId","=",$skuId)->get();
            return response()->json(["code"=>202,"success"=>true,"data"=> $imagens ]);
        } 
        catch (\Exception $e) 
        {
            return response()->json(["code"=>202,"success"=>false,"message" => $e->getMessage()]);
        }
    }

}