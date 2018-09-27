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
        $this->showView       = "cadastros.principais.produtos.show";
    }


    private function importar_xls_vtex_produtos( $diretorio )
    {
        // try
        // {
            DB::beginTransaction();
            $cadastrarMarca = function( $id,$marcaNome )
            {
                $id = (int)$id;
                $marca = DB::table('produtoMarca')->where("id","=",$id)->orwhere("nome","=",$marcaNome);
                if( $marca->count()<=0 )
                {
                    $novaMarca = [
                        "id"    =>  $id,
                        "nome"  =>  $marcaNome
                    ];
                    DB::table("produtoMarca")->insert($novaMarca);
                }
                else
                {
                    $id = $marca->first()->id;
                }
                return $id;
            };

            $cadastrarCategoria = function( $id,$categoriaNome )
            {
                $id = (int)$id;
                $categoria = DB::table('produtoCategoria')->where("id","=",$id)->orwhere("nome","=",$categoriaNome);
                if( $categoria->count()<=0 )
                {
                    $novaCategoria = [
                        "id"    =>  $id,
                        "nome"  =>  $categoriaNome
                    ];
                    DB::table("produtoCategoria")->insert($novaCategoria);
                }
                else
                {
                    $id = $categoria->first()->id;
                }
                return $id;
            };

            $cadastrarProduto = function( $row,$marca,$categoria,$dataAtual,$horaAtual )
            {
                $id = (int)$row["_IDPRODUTO_(NAO_ALTERAVEL)"];            
                $novoProduto = [
                    "id"                   => $id,
                    "nome"                 => $row["_NOMEPRODUTO_(OBRIGATORIO)"]." ".$row["_NOMECOMPLEMENTO"],
                    "palavrasSubstitutas"  => $row["_PALAVRASCHAVE"],
                    "tituloPagina"         => $row["_TITULOSITE"],
                    "textLink"             => $row["_TEXTOLINK_(NAO_ALTERAVEL)"],
                    "descricaoProduto"     => $row["_DESCRICAOPRODUTO"],
                    "descricaoMeta"        => $row["_METATAGDESCRIPTION"],
                    "dataCadastro"         => $dataAtual,
                    "horaCadastro"         => $horaAtual,
                    "categoriaId"          => $categoria,
                    "marcaId"              => $marca
                ];

                $produto = DB::table('produtos')->where("id","=",$id);
                if( $produto->count()<=0 )
                    DB::table("produtos")->insert($novoProduto);
                else
                    DB::table("produtos")->where("id","=",$id)->update($novoProduto);
                return $id;
            };

            $cadastrarSku = function( $row,$produto, $dataAtual,$horaAtual )
            {
                $id = (int)$row["_SKUID_(NAO_ALTERAVEL)"];
                $novoSku = [
                    "id"                   => $id,
                    "nome"                 => $row["_NOMESKU"],
                    "ean"                  => $row["_SKUEAN"],
                    "codRef"               => $row["_CODIGOREFERENCIASKU"],
                    "ncm"                  => null,
                    "produtoId"            => $produto,
                    "altura"               => $row["_ALTURA"],
                    "largura"              => $row["_LARGURA"],
                    "comprimento"          => $row["_COMPRIMENTO"],
                    "peso"                 => $row["_PESO"],
                    "ativo"                => (( $row["_SKUATIVO_(NAO_ALTERAVEL)"]=="NÃO"  ) ? 1 : 0 ),
                    "dataCadastro"         => $dataAtual,
                    "horaCadastro"         => $horaAtual,
                    "estoqueReal"          => 0,
                    "estoqueAtual"         => 0,
                    "multiplicadorUnd"     => $row["_MULTIPLICADORUNIDADE"],
                    "fornecedorId"         => $row["_IDFORNECEDOR"]
                ];

                $sku = DB::table('skus')->where("id","=",$id);
                if( $sku->count()<=0 )
                    DB::table("skus")->insert($novoSku);
                else
                    DB::table("skus")->where("id","=",$id)->update($novoSku);
                return $id;
            };

            $cadastrarSubCategoria = function($row,$produto,$categoria)
            {
                $id = (int)$row["_IDDEPARTAMENTO_(NAO_ALTERAVEL)"];            
                $novaSubCategoria = [
                    "id"           => $id,
                    "nome"         => $row["_NOMEDEPARTAMENTO"],
                    "categoriaId"  => $categoria
                ];

                $produtoSubCategoria = DB::table('produtoSubCategoria')->where("id","=",$id);
                if( $produtoSubCategoria->count()<=0 )
                    DB::table("produtoSubCategoria")->insert($novaSubCategoria);
                else
                    DB::table("produtoSubCategoria")->where("id","=",$id)->update($novaSubCategoria);

                DB::table("produtoProdutoSubCategoria")->where("produtoId","=",$produto)->delete();
                DB::table("produtoProdutoSubCategoria")->insert(["produtoId"=>$produto,"produtoSubCategoriaId"=>$id]);
                return $id;
            };

            date_default_timezone_set(session('timezone'));
            $dataAtual = date('Y-m-d');
            $horaAtual = date("H:i:s");
            $planilha = Excel::read( $diretorio );
            foreach($planilha as $row)
            {
                dd($row);
                
                $marca = $cadastrarMarca($row['_IDMARCA'],$row["_MARCA"]);
                $categoria = $cadastrarCategoria($row['_IDCATEGORIA'],$row["_NOMECATEGORIA"]);
                $produto = $cadastrarProduto($row,$marca,$categoria,$dataAtual,$horaAtual);
                $sku = $cadastrarSku($row,$produto,$dataAtual,$horaAtual);
                $subCategoria = $cadastrarSubCategoria($row,$produto,$categoria);
                // subCategorias
                // sugestoes
                // acessorios
                // especificacoes
                dd($row);
            }
            DB::rollBack();

            // DB::commit();
            return true;
        // } 
        // catch (\Exception $e) 
        // {
        //     DB::rollBack();
        //     $message = $e->getMessage();
        //     return false;
        // }
    }

    public function teste()
    {
        $planilha = $this->importar_xls_vtex_produtos(__DIR__."/../../../../../../public/planilha_vtex_produtos.xls");
        dd($planilha);
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

    public function delete($id)
    {
        try 
        {
            DB::table($this->table)->where("id","=",$id)->delete();
            return response()->json(["code"=>202,"success"=>true]);
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

}