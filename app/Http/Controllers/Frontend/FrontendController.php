<?php
namespace App\Http\Controllers\Frontend;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;

class FrontendController extends Controller
{
    public function index()
    {
        $self = $this;
        return view('frontend.index',compact('self'));
    }

    public function subCategoria($subCategoriaNome)
    {
        $subCategoria = DB::table("produtoSubCategoria")->where("nome","=",strtoupper(str_replace("_", " ", $subCategoriaNome)))->first();
        $self = $this;
        return view('frontend.subCategoria',compact('subCategoria','self'));
    }

    public function paginaSku($skuNome)
    {
        $sku = DB::table("skus")->where("nome","=",strtoupper(str_replace("_", " ", $skuNome)))->first();
        $produto = DB::table("produtos")->where("id","=",$sku->produtoId)->first();
        $self = $this;
        return view('frontend.sku',compact('sku','self','produto'));
    }
    
    public function getCategorias()
    {
    	return  DB::table("produtoCategoria")->get();
    }

    public function getSubCategorias($id)
    {
    	return  DB::table("produtoSubCategoria")->where("categoriaId","=",$id)->get();
    }

    public function getCampoPersonalizado($nome,$valorSubstituto="campo inexistente")
    {
        $campo = DB::table("camposPersonalizados")->where("nome","=",$nome)->get();
        return ((count($campo)>0) ? $campo[0]->conteudo : $valorSubstituto) ;
    }

    public function getSkus($subCategoriaId = null)
    {
        if(!$subCategoriaId)
            return  DB::table("skus")
                        ->select("skus.id as skuId","skus.nome as skuNome","produtos.nome as produtoNome")
                        ->join("produtos","produtos.id","skus.produtoId")
                        ->where("skus.ativo","=", 1)
                        ->get();
        else
        {
            return  DB::table("produtoProdutoSubCategoria")
                        ->select("skus.id as skuId","skus.nome as skuNome","produtos.nome as produtoNome")
                        ->where("produtoProdutoSubCategoria.produtoSubCategoriaId","=", $subCategoriaId)
                        ->join("produtos","produtos.id","=","produtoProdutoSubCategoria.produtoId")
                        ->join("skus","skus.produtoId","=","produtos.id")
                        ->where("skus.ativo","=", 1)
                        ->get();
        }
    }

    public function getImagemPrincipalSku($skuId)
    {
        $imagens = DB::table("skuImagens")->where("skuId","=",$skuId)->where("principal","=",1)->get();
        return ((count($imagens)>0) ? $imagens[0]->url : asset('public/img/sem-foto.png')) ;
    }

    public function getImagensSku($skuId)
    {
        return DB::table("skuImagens")->where("skuId","=",$skuId)->orderBy("principal","desc")->get();
    }
}