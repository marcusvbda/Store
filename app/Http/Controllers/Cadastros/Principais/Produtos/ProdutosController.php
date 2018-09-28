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
        $this->diretorioUpload  =  __DIR__."/../../../../../../public/upload";
        $this->diretorioTemp    =  __DIR__."/../../../../../../public/upload/temp";
    }

    private function limparPastaTemp()
    {
        $arquivos = glob($this->diretorioTemp . '/*');
        foreach ($arquivos as $arquivo) 
        {
            unlink($arquivo);
        }
    }


    public function index(Request $request)
    {
        try 
        {
            $this->limparPastaTemp();
            
            $request = $request->all();
            $nome = "";
            $id = "";
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
            if(!empty($request["id"]))
            {
                $id = $request["id"];
                $data = $data->where("produtos.id","=",$id);                
            }
            $data = $data->get();
            $marcas = DB::table("produtoMarca")->get();
            $self = $this;
            return view($this->principalView,compact('self','data','nome','marcaId','marcas','id'));
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            return view("errors.500",compact("message"));
        }
    }

    public function upload(Request $request)
    {
        try 
        {
            $arquivos = $request->file('file');
            if (!is_array($arquivos)) 
            {
                $arquivos = [$arquivos];
            }
    
            if (!is_dir($this->diretorioUpload)) 
            {
                mkdir($this->diretorioUpload, 0777);
                mkdir($this->diretorioTemp, 0777);
            }
            for ($i = 0; $i < count($arquivos); $i++) 
            {
                $arquivo = $arquivos[$i];
                $arquivo->move($this->diretorioTemp, $arquivo->getClientOriginalName() );
            }
            return response()->json(["code"=>202,"success"=>true]);
        } 
        catch (\Exception $e) 
        {
            return response()->json(["code"=>202,"success"=>false,"message" => $e->getMessage()]);
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

    public function confirmarUpload(Request $request)
    {
        date_default_timezone_set(session('timezone'));
        try 
        {
            $request = $request->all();
            $arquivos = glob($this->diretorioTemp . '/*');
            foreach ($arquivos as $arquivo) 
            {
                $nomeArquivo = basename($arquivo);
                $array = Excel::read($arquivo);
                $resultado = false;
                if($request["tipo"]=="imagens")
                {
                    $resultado = $this->importar_xls_imagens($array);
                }
                elseif($request["tipo"]=="produtos")
                {
                    $resultado = $this->importar_xls_produtos($array);
                }
            }
            if($resultado["success"])
                return response()->json(["code"=>202,"success"=>true]);
            else
                return response()->json(["code"=>505,"success"=>false, "request"=>$request, "message"=>$resultado["message"] ]);
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();
            return response()->json(["code"=>500,"success"=>false,"message" => $e->getMessage()]);
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

    private function importar_xls_imagens( $planilha )
    {
        foreach($planilha as $row)
        {   
            try
            {
                DB::beginTransaction();
                $skuId = (int)$row["ID_DO_SKU"];
                $produtoId = (int)$row["ID_DO_PRODUTO"];
                $sku = DB::table("skus")->where("id","=",$skuId)->where("produtoId","=",$produtoId);
                if($sku->count()>0)
                {   $sku = $sku->first();
                    $produtoId = $sku->produtoId;
                    $destinationPath = public_path("/upload/imgs/produtos/{$produtoId}/skus/{$skuId}");
                    if(!is_dir(public_path("/upload/imgs/produtos")))
                        mkdir(public_path("/upload/imgs/produtos"),0777, true);
                    if(!is_dir(public_path("/upload/imgs/produtos/{$produtoId}")))
                        mkdir(public_path("/upload/imgs/produtos/{$produtoId}"),0777, true);
                    if(!is_dir(public_path("/upload/imgs/produtos/{$produtoId}/skus")))
                        mkdir(public_path("/upload/imgs/produtos/{$produtoId}/skus"),0777, true);
                    if(!is_dir(public_path("/upload/imgs/produtos/{$produtoId}/skus/{$skuId}")))
                        mkdir(public_path("/upload/imgs/produtos/{$produtoId}/skus/{$skuId}"),0777, true);
                    $name = time().'.png';

                    $imagem = file($row["URL_DA_IMAGEM"]);
                    file_put_contents($destinationPath."/".$name, $imagem);
                    $row["URL_DA_IMAGEM"]=asset("public/upload/imgs/produtos/{$produtoId}/skus/{$skuId}/{$name}");

                    $principal = 0;
                    if(DB::table("skuImagens")->where("skuId","=",$skuId)->count()<=0)
                    {
                        $principal=1;
                    }
                    $idImangem = uniqid();
                    $novaImagem = [
                        "id"       => $idImangem,
                        "nome"     => $row["NOME_DA_IMAGEM"],
                        "legenda"  => $row["LEGENDA_(ALT)"],
                        "url"      => $row["URL_DA_IMAGEM"],
                        "skuId"    => $skuId,
                        "principal"=> $principal
                    ];
                    DB::table("skuImagens")->insert($novaImagem);

                    if($row["PRINCIPAL_(SIM_OU_NAO)"]=="SIM")
                    {

                        DB::table("skuImagens")->where("skuId","=",$skuId)->update(["principal"=>0]);
                        DB::table("skuImagens")->where("id","=",$idImangem)->update(["principal"=>1]);
                    }
                }
                DB::commit();
                return ["success"=>true];
            }
            catch(\Exception $e) 
            {
                DB::rollBack();
                return ["success"=>false,"message"=>$e->getMessage()];
            }
        }
        return $planilha;
    }

    private function importar_xls_produtos( $planilha )
    {
        try
        {
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
                $id = (int)$row["ID_DO_PRODUTO"];            
                $novoProduto = [
                    "id"                   => $id,
                    "nome"                 => $row["NOME_DO_PRODUTO"],
                    "palavrasSubstitutas"  => $row["PALAVRAS_CHAVE_(SEPARADAS_POR_VIRGULA)"],
                    "tituloPagina"         => $row["TITULO_DA_PAGINA"],
                    "textLink"             => $row["TEXTOLINK_(SEM_ESPACOS)"],
                    "descricaoProduto"     => $row["DESCRICAO_DO_PRODUTO"],
                    "descricaoMeta"        => $row["DESCRICAO_META_(PARA_SEO)"],
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

            $cadastrarSku = function( $row,$produto, $dataAtual,$horaAtual)
            {
                $id = (int)$row["ID_DO_SKU"];
                $novoSku = [
                    "id"                   => $id,
                    "nome"                 => $row["NOME_DO_SKU"],
                    "ean"                  => $row["EAN"],
                    "codRef"               => $row["CODIGO_REFERENCIA_DO_SKU"],
                    "ncm"                  => null,
                    "produtoId"            => $produto,
                    "altura"               => $row["ALTURA"],
                    "largura"              => $row["LARGURA"],
                    "comprimento"          => $row["COMPRIMENTO"],
                    "peso"                 => $row["PESO"],
                    "ativo"                => (( $row["ATIVO_(SIM_OU_NAO)"]=="SIM"  ) ? 1 : 0 ),
                    "dataCadastro"         => $dataAtual,
                    "horaCadastro"         => $horaAtual,
                    "estoqueReal"          => 0,
                    "estoqueAtual"         => 0,
                    "multiplicadorUnd"     => $row["MULTIPLICADOR_UNIDADE"],
                    "codigoFabricante"     => $row["CODIGO_DO_FABRICANTE"]
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
                $id = (int)$row["ID_DA_SUBCATEGORIA"];            
                $novaSubCategoria = [
                    "id"           => $id,
                    "nome"         => $row["NOME_DA_SUBCATEGORIA"],
                    "categoriaId"  => $categoria
                ];

                $produtoSubCategoria = DB::table('produtoSubCategoria')->where("id","=",$id);
                if( $produtoSubCategoria->count()<=0 )
                    DB::table("produtoSubCategoria")->insert($novaSubCategoria);
                else
                    DB::table("produtoSubCategoria")->where("id","=",$id)->update($novaSubCategoria);

                DB::table("produtoProdutoSubCategoria")->where("produtoId","=",$produto)->delete();
                DB::table("produtoProdutoSubCategoria")->insert(["produtoSubCategoriaId"=>$id,"produtoId"=>$produto]);
                return $id;
            };

            date_default_timezone_set(session('timezone'));
            $dataAtual = date('Y-m-d');
            $horaAtual = date("H:i:s");
            foreach($planilha as $row)
            {   
                $marca = $cadastrarMarca($row['ID_DA_MARCA'],$row["MARCA_(NOME)"]);
                $categoria = $cadastrarCategoria($row['ID_DA_CATEGORIA'],$row["CATEGORIA_(NOME)"]);
                $produto = $cadastrarProduto($row,$marca,$categoria,$dataAtual,$horaAtual);
                $subCategoria = $cadastrarSubCategoria($row,$produto,$categoria);
                $sku = $cadastrarSku($row,$produto,$dataAtual,$horaAtual,$subCategoria);
            }
            DB::commit();
            return ["success"=>true];
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();
            $message = $e->getMessage();
            return ["success"=>false,"message"=>$message];
        }
    }

}