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


class SkuController extends Controller
{
    public function __construct()
    {
        $this->table          = "skus";
        $this->primaryKey     = "id";
        $this->route          = "cadastros.principais.produtos.skus";
        $this->principalView  = "cadastros.principais.produtos.skus.index";
        $this->showRoute      = "cadastros.principais.produtos.skus.show";
        $this->editRoute      = "cadastros.principais.produtos.skus.edit";
        $this->createRoute    = "cadastros.principais.produtos.skus.create";
    }

    public function index($produtoId,Request $request)
    {
        try 
        {
            $request = $request->all();
            $nome = "";
            $ean  = "";
            $ativo  = null;
            $data = DB::table("skus")
                ->select("skus.*");
            if(!empty($request["nome"]))
            {
                $nome = $request["nome"];
                $data = $data->where("skus.nome","like","%{$nome}%");                
            }
            if(!empty($request["ean"]))
            {
                $ean = $request["ean"];
                $data = $data->where("skus.ean","like","%{$ean}%");                
            }
            if(isset($request["ativo"]))
            {
                $ativo = $request["ativo"];
                $data = $data->where("skus.ativo","=",$ativo);                
            }
            $data = $data->get();
            $produto = DB::table("produtos")->find($produtoId);
            $self = $this;
            return view($this->principalView,compact('self','produto','data','nome','ean','ativo'));
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            return view("errors.500",compact("message"));
        }
    }

    public function getPrincipalImg($skuId)
    {
        try
        {
            $imagens = DB::table("skuImagens")->where("principal","=",1)->where("skuId","=",$skuId);
            if($imagens->count()<=0)
                return asset('public/img/sem-foto.png');
            return $imagens->first()->url;
        } 
        catch (\Exception $e) 
        {
            return asset('public/img/sem-foto.png');
        }
    }

    public function show($produtoId,$skuId)
    {
        $produto = DB::table("produtos")->find($produtoId);
        $sku = DB::table($this->table)->where("id","=",$skuId)->first();
        $sugestoes=Helper::implodeEloquent("nome",DB::table("skuSugestao")
            ->join("skus","skus.id","skuSugestao.sugestaoId")
            ->where("skuSugestao.skuId","=",$skuId)
            ->select("skus.nome as nome")->get());
        $acessorios=Helper::implodeEloquent("nome",DB::table("skuAcessorios")
            ->join("skus","skus.id","skuAcessorios.acessorioId")
            ->where("skuAcessorios.skuId","=",$skuId)
            ->select("skus.nome as nome")->get());
        $imagens = DB::table("skuImagens")->where("skuId","=",$skuId)->get();
        return view($this->showRoute,compact('sku','produto','sugestoes','acessorios','imagens'));
    }


    public function create($produtoId)
    {
        $produto = DB::table("produtos")->find($produtoId);
        $outrosSkus = DB::table("skus")->get();
        return view($this->createRoute,compact('produto','outrosSkus'));
    }

    public function edit($produtoId,$skuId)
    {
        $produto = DB::table("produtos")->find($produtoId);
        $outrosSkus = DB::table("skus")->join("produtos","produtos.id","skus.produtoId")->get();
        $sku = DB::table("skus")->where("id","=",$skuId)->first();
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
        return view($this->editRoute,compact('sku','produto','outrosSkus','sugestoes','acessorios','imagens'));
    }


    public function store($produtoId,Request $request)
    {
        try 
        {
            $request = $request->all();
            date_default_timezone_set(session('timezone'));
            DB::beginTransaction();
            $sugestoes   =  [];
            $acessorios  =  [];
            if(isset($request["sugestoes"]))
                $sugestoes   =  array_map('trim',explode(",", $request["sugestoes"]));
            if(isset($request["acessorios"]))
                $acessorios  =  array_map('trim',explode(",", $request["acessorios"]));
            unset($request["_token"],$request["_method"],$request["sugestoes"],$request["acessorios"]);
            $request["id"] = uniqid();
            $request["dataCadastro"] = date('Y-m-d');
            $request["horaCadastro"] = date("H:i:s");
            $data = DB::table("skus")->insert($request);
            foreach($acessorios as $acessorio)
            {
                DB::table("skuAcessorios")->insert(["acessorioId"=>$acessorio,"skuId"=>$request["id"] ]);
            }
            foreach($sugestoes as $sugestao)
            {
                DB::table("skuSugestao")->insert(["sugestaoId"=>$sugestao,"skuId"=>$request["id"] ]);
            }
            DB::commit();
            return redirect()->route($this->showRoute,["produtoId"=>$request["produtoId"],"skuId"=>$request["id"]  ]);
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            DB::rollBack();
            return view("errors.500",compact("message"));
        }
    }

    public function put($produtoId,$skuId,Request $request)
    {
        try 
        {
            $request = $request->all();
            DB::beginTransaction();
            $sugestoes = array_map('trim',explode(",",$request["sugestoes"] ));
            $acessorios = array_map('trim',explode(",",$request["acessorios"] ));
            unset($request["_token"],$request["_method"],$request["sugestoes"],$request["acessorios"]);
            $data = DB::table($this->table)->where("id","=",$skuId)->update($request);
            DB::table("skuAcessorios")->where("skuId","=",$skuId)->delete();
            DB::table("skuSugestao")->where("skuId","=",$skuId)->delete();
            foreach($acessorios as $acessorio)
            {
                DB::table("skuAcessorios")->insert(["acessorioId"=>$acessorio,"skuId"=>$skuId ]);
            }
            foreach($sugestoes as $sugestao)
            {
                DB::table("skuSugestao")->insert(["sugestaoId"=>$sugestao,"skuId"=>$skuId ]);
            }
            DB::commit();
            return redirect()->route($this->showRoute,["skuId"=>$skuId, "produtoId"=>$produtoId ]);
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
            $destinationPath = public_path("/upload/imgs/produtos/{$produtoId}/skus/{$skuId}");
            
            if(!is_dir(public_path("/upload/imgs/produtos")))
                mkdir(public_path("/upload/imgs/produtos"),0777, true);
            if(!is_dir(public_path("/upload/imgs/produtos/{$produtoId}")))
                mkdir(public_path("/upload/imgs/produtos/{$produtoId}"),0777, true);
            if(!is_dir(public_path("/upload/imgs/produtos/{$produtoId}/skus")))
                mkdir(public_path("/upload/imgs/produtos/{$produtoId}/skus"),0777, true);
            if(!is_dir(public_path("/upload/imgs/produtos/{$produtoId}/skus/{$skuId}")))
                mkdir(public_path("/upload/imgs/produtos/{$produtoId}/skus/{$skuId}"),0777, true);

            if( $data["tipo"]=="UPLOAD" )
            {   
                $image = $request->file('imagem');
                $name = time().'.'.$image->getClientOriginalExtension();
                $image->move($destinationPath, $name);
                $data["nome"]=$name;
            }
            else
            {
                $name = time().'.png';
                $data["nome"]=$name;
                copy("http://copysupply.vteximg.com.br/arquivos/ids/155393/molde-limpo--1---1-.jpg",$destinationPath."/".$name);
            }
            $data["url"] = asset("public/upload/imgs/produtos/{$produtoId}/skus/{$skuId}/{$name}");

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

    public function delete($produtoId,$skuId)
    {
        try 
        {
            DB::table($this->table)->where("id","=",$skuId)->delete();
            return response()->json(["code"=>202,"success"=>true]);
        } 
        catch (\Exception $e) 
        {
            return response()->json(["code"=>202,"success"=>false,"message" => $e->getMessage()]);
        }

    }

}