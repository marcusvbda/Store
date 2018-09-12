<?php
namespace App\Http\Controllers\Relatorios;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ParametrosController as Parametros;
use DB;

class RelatorioController extends Controller
{
    public function index($id)
    {
        $relatorio = DB::table('relatorios')
                        ->where("relatorios.id", "=",$id);
        if($relatorio->count()<=0)
            return abort(404);

        $relatorio = $relatorio->first();
        if(Auth::user()->cannot($relatorio->permissao))
            return abort(403);

        $dados = $this->processaQueryRelatorio($relatorio->query);
        $camposTabela = explode("|", $relatorio->campos);

        return view('relatorios.padrao.index',compact('dados','relatorio','camposTabela'));
    }

    public function executeQuery()
    {
        try 
        {   
            $relatorioId = $_GET["relatorioId"];
            // $relatorioId = 1;
            $filtro = $_GET["filtro"];
            // $filtro = [
            //     "Nome"=> "Teste",
            //     "Grupo_de_acesso"=>null,
            //     "Email" =>null,
            //     "Id" => null
            // ];
            $relatorio = DB::table('relatorios')
                        ->where("relatorios.id", "=",$relatorioId);
            if($relatorio->count()<=0)
                return response()->json(["code"=>404,"success"=>true,"message" => "Relatório não encontrado !!!"]);
            
            $relatorio = $relatorio->first();
            if(Auth::user()->cannot($relatorio->permissao))
                return response()->json(["code"=>403,"success"=>false,"message" => "Acesso negado !!!"]);

            $dados = $this->processaQueryRelatorio($relatorio->query);

            $query = $dados["query"];
            foreach($dados["parametros"] as $p)
            {
                $valor =  $filtro[trim($p["nome"])];
                $auxAnterior = strpos($query,"#")+1;
                $queryAux = substr($query,$auxAnterior,strlen($query));
                $queryAux =  "#".substr($queryAux,0,strpos($queryAux,"#"))."#";
                if(($valor == null) || (trim($valor) == ""))
                {
                    $query = str_replace($queryAux,"",$query);
                }
                else
                {
                    $queryAux2 = str_replace($p["parametro"],$valor,$queryAux);
                    $queryAux2 = str_replace("#","",$queryAux2);
                    $query = str_replace($queryAux,$queryAux2,$query);
                }
            }
            return response()->json([
                "code"=>202,"success"=>true, "data" => DB::select($query)
            ]);
        } 
        catch (\Exception $e) 
        {
            return response()->json(["code"=>505,"success"=>false,"message" => $e->getMessage(), "query" => $query]);
        }
    }

    

    public function executeQueryPaginado()
    {
        try 
        {   
            $relatorioId = $_GET["relatorioId"];
            $page = $_GET["page"];
            $filtro = $_GET["filtro"];
            $relatorio = DB::table('relatorios')
                        ->where("relatorios.id", "=",$relatorioId);
            if($relatorio->count()<=0)
                return response()->json(["code"=>404,"success"=>true,"message" => "Relatório não encontrado !!!"]);
            
            $relatorio = $relatorio->first();
            if(Auth::user()->cannot($relatorio->permissao))
                return response()->json(["code"=>403,"success"=>false,"message" => "Acesso negado !!!"]);

            $dados = $this->processaQueryRelatorio($relatorio->query);
            $query = $dados["query"];
            foreach($dados["parametros"] as $p)
            {
                $valor =  $filtro[trim($p["nome"])];
                $auxAnterior = strpos($query,"#")+1;
                $queryAux = substr($query,$auxAnterior,strlen($query));
                $queryAux =  "#".substr($queryAux,0,strpos($queryAux,"#"))."#";
                if(($valor == null) || (trim($valor) == ""))
                {
                    $query = str_replace($queryAux,"",$query);
                }
                else
                {
                    $queryAux2 = str_replace($p["parametro"],$valor,$queryAux);
                    $queryAux2 = str_replace("#","",$queryAux2);
                    $query = str_replace($queryAux,$queryAux2,$query);
                }
            }
            $query = str_replace("#","",$query);
            $qtde = count(DB::select($query));
            $query .=" LIMIT ".($page*100).",100";
            return response()->json([
                "code"=>202,"success"=>true, "data" =>  DB::select($query), "qtde" => $qtde, "filtro" => $filtro , "query" => $query, "filtro" => $filtro
            ]);
        } 
        catch (\Exception $e) 
        {
            return response()->json(["code"=>505,"success"=>false,"message" => $e->getMessage(),"query" => $query ]);
        }
    }

    private function processaQueryRelatorio($queryOriginal)
    {
        $parametros = [];
        $parametros["parametros"] = [];
        $cont = 0;
        $query = $queryOriginal;
        while(strpos($query,"{")!=null)
        {            
            $parametro_original =  substr($query,strpos($query,"{"), (strpos($query,"}"))-(strpos($query,"{")-1)  );
            $parametro =  $parametro_original;
            if((strpos($parametro,",")!=null))
            {
                $selectCombo =  substr($parametro,strpos($parametro,",")+1,(  ( (strpos($parametro,"}")-1) - strpos($parametro,",")) ));
                $camposCombo = [];
                if((strpos($parametro,"=>")!=null))
                {
                    foreach(explode("|", $selectCombo) as $campo)
                    {
                        array_push($camposCombo,[
                            "id" => substr($campo,strpos($campo,"=>")+2, strlen($campo)),
                            "nome" => substr($campo,0, strpos($campo,"=>"))
                        ]);
                    }
                }
                else
                {                    
                    foreach(DB::select($selectCombo) as $campo)
                    {
                        array_push($camposCombo,[
                            "id" => $campo->id,
                            "nome" => $campo->nome
                        ]);
                    }
                }
                array_push($parametros["parametros"],[
                    "label" => substr(str_replace("_"," ",str_replace("}","",str_replace("{","",$parametro_original))),0,strpos($parametro_original,",")-1),
                    "nome" => trim(substr(str_replace("}","",str_replace("{","",$parametro_original)),0,strpos($parametro_original,",")-1)),
                    "original" => $parametro_original,
                    "combo"  => $camposCombo,
                    "parametro" => "[".$cont."]"
                ]);
            }
            else
            {
                $tipo = "text";
                $nome = str_replace("}","",str_replace("{","",$parametro));
                if((strpos($parametro,"@")!=null))
                {
                    $tipo = substr($nome,strpos($parametro,"@"),strlen($nome));
                    $nome = str_replace("@".$tipo, "", $nome);
                }

                array_push($parametros["parametros"],[
                    "label" => trim(str_replace("_"," ",str_replace("}","",str_replace("{","",$nome)))),
                    "nome"  => $nome,
                    "original" => $parametro_original,
                    "tipo" => $tipo,
                    "parametro" => "[".$cont."]"
                ]);
            }
            $query = str_replace($parametro_original,"[".$cont++."]",$query);
        }
        $parametros["query"]=$query;
        // dD($parametros);

        return $parametros;
    }

}
