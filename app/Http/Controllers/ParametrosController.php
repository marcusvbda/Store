<?php
namespace App\Http\Controllers;
use App\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Utils;
use App\Utils\Email;

class ParametrosController extends Controller
{
    private $route = "parametros";

    public function email()
    {
        $tenantId = Auth::user()->tenantId;
        $email = DB::table("emailTenant")->where("tenantId","=",$tenantId);
        if($email->Count()<=0)
        {
            $email = 
            [
                "id"          => uniqid(),
                "tenantId"    => $tenantId,
                "email"       => "",
                "senha"       => "",
                "servidor"    => "",
                "porta"       => "",
                "criptografia"=> "",
                "driver"      => "",
                "testado"     => 0
            ];
            DB::table("emailTenant")->insert($email);
            $email = (object) $email;
        }
        else
        {
            $email = $email->first();
        }
        return view("parametros.email",compact('email'));
    }

    public function emailTestar(Request $request)
    {
        try 
        {
            $data = $request->all();
            $envio = Email::send($data["email"],"teste de configuração de email","teste de configuração de email",
            [
                "name"         => Auth::user()->nome,
                "mail"         => $data["email"],
                "smtp"         => $data["servidor"],
                "port"         => $data["porta"],
                "password"     => $data["senha"],
                "encryption"   => $data["criptografia"],
                "driver"       => $data["driver"]
            ]);
            if($envio["success"])
                return response()->json(["code"=>202,"success"=>true]);
            return response()->json(["code"=>505,"success"=>false, "message"=>$envio["message"]  ]);
        } 
        catch (\Exception $e) 
        {
            return response()->json(["code"=>202,"success"=>false,"message" => $e->getMessage()]);
        }
    }

    public function emailEdit(request $request)
    {
        try 
        {
            $data = $request->except(["_token","_method"]);
            DB::table("emailTenant")
                ->where("id","=",$data["id"])
                ->where("tenantId","=",Auth::user()->tenantId)
                ->update( $data );
            return redirect()->route("parametros.email");;
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            return view("errors.500",compact("message"));
        }
        $data = $request->all();
    }

    public static function getEmail($parametro)
    {
        $parametros = DB::table("emailTenant")
            ->where("tenantId","=",Auth::user()->tenantId);
        if($parametros->Count()>0)
            return $parametros->first()->{$parametro};
        else
            return null;
    }

    public static function get($parametro)
    {
        $parametros = DB::table("parametros")
            ->leftjoin("TenantParametros","TenantParametros.parametroId","=","parametros.id")
            ->where("TenantParametros.tenantId","=",Auth::user()->tenantId)
            ->where("parametros.id","=",$parametro);
        if($parametros->Count()>0)
            return $parametros->first()->valor;
        else
            return null;
    }

    public function setSession()
    {
        try 
        {
            session([ $_POST["nome"] => $_POST["valor"] ]);
            return response()->json(["code"=>202,"success"=>true]);
        } 
        catch (\Exception $e) 
        {
            return response()->json(["code"=>202,"success"=>false,"message" => $e->getMessage()]);
        }
    }

    public function index()
    {
        try 
        {
            $parametros = DB::table("parametros")
                        ->leftjoin("TenantParametros","TenantParametros.parametroId","=","parametros.id")
                        ->where("TenantParametros.tenantId","=",Auth::user()->tenantId)
                        ->get();
            return view("parametros.index",compact('parametros'));
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            return view("errors.500",compact("message"));
        }
    }

    public function put(Request $request)
    {
        try 
        {
            $data = $request->except(["_token","_method"]);
            foreach(DB::table("parametros")->get() as $parametro)
            {
                $parametros = DB::table("TenantParametros")
                    ->where("parametroId","=",$parametro->id)
                    ->where("tenantId","=",Auth::user()->tenantId)
                    ->update([ "valor" => $data[$parametro->id] ]);
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