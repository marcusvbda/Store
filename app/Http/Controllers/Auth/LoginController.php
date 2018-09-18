<?php
namespace App\Http\Controllers\Auth;
use App;
use App\User;
use App\GruposAcesso;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Context;
use DB;
use App\Utils\Email;


class LoginController extends Controller
{
    public function authResetPassword(Request $request)
    {
        try 
        {
            $data = $request->all();
            $email = $data["email"];
            $senha = md5($data["novaSenha"]);
            DB::table("usuarios")->where("email","=", $email)->update(["senha"=>$senha,"mudarSenha"=>0,"renewToken"=>null ]);
            return response()->json(["code"=>202,"success"=>true]);
        } 
        catch (\Exception $e) 
        {
            return response()->json(["code"=>202,"success"=>false,"message" => $e->getMessage()]);
        }
    }

    public function renewpass($codigo)
    {
        date_default_timezone_set(session('timezone'));
        $codigo = base64_decode($codigo);
        $codigos = explode("|", $codigo);
        $novaSenhaTemp = $codigos[0];
        $usuarioId = $codigos[1];
        $data = $codigos[2];
        $renewToken = $codigos[3];

        $usuario = DB::table("usuarios")->where("id", "=",$usuarioId)->where("renewToken","=",$renewToken);
        if(($usuario->count()<=0) || ($data!=md5(date("Y-m-d"))))
            return abort(404);
        $usuario->update(["mudarSenha"=>1,"senha"=>md5($novaSenhaTemp)]);
        $usuario = $usuario->first();
        return view('auth.renewpass',compact("novaSenhaTemp","usuario"));
    }

    public function enviarEmailSenha(Request $request)
    {
        try 
        {
            date_default_timezone_set(session('timezone'));
            $data = $request->all();
            $email = $data["email"];
            $usuario = DB::table("usuarios")->where("email","=", $email);
            if($usuario->count()<=0)
                return response()->json(["code"=>202,"success"=>false,"message"=>"Email digitado não consta na base de dados, verifique..."]);

            $usuario = $usuario->first();
            $novaSenhaTemp = uniqid();
            $renewToken = uniqid();
            DB::table("usuarios")->where("id","=",$usuario->id)->update(["renewToken"=>$renewToken]);
            $url = asset("")."admin/auth/renewpass/".base64_encode($novaSenhaTemp."|".$usuario->id."|".md5(date('Y-m-d'))."|".$renewToken);
            $corpoEmail = "
                <p>Foi solicitado uma renovação de senha para seu usuario</p>          
                <p>Acesse o link abaixo para receber sua nova senha <strong>temporária</strong></p>
                <p><a target='_blank'  href='".$url."'></a>".$url."</p>
                <p>Este link tem validade de apenas 24 horas</p>";
            $envio = Email::send($email,"Renovação de senha ezCore",$corpoEmail,
            [
                "name"         => "Root ezCore",
                "mail"         => "root.ezcore@gmail.com",
                "smtp"         => "smtp.gmail.com",
                "port"         => "465",
                "password"     => "roottoor",
                "encryption"   => "SSL",
                "driver"       => "SMTP"
            ]);
            return response()->json(["code"=>202,"success"=>$envio]);
        } 
        catch (\Exception $e) 
        {
            return response()->json(["code"=>202,"success"=>false,"message" => $e->getMessage()]);
        }
    }

    public function login()
    {
        if(Auth::check())
        {     
            User::where("id","=",Auth::user()->id)->update(["tenantId"=>null ]);            
            Auth::logout();
        } 
        return view('auth.login');
    }

    public function logar(Request $request)
    {
        $request = $request->all();
        $manter_logado =  $request["manter_logado"];
        $usuario = User::where( "email","=",$request["email"] )
            ->where("senha","=", md5($request["senha"]) )
            ->get();
        
        if(Count($usuario)>0)
        {
            Auth::loginUsingId($usuario[0]->id, $manter_logado);   
            User::where("id","=",$usuario[0]->id)->update(["tenantId"=>$request["tenantId"]]);            
            return response()->json(["code"=>202,"success"=>true]);            
        }
        else
        {
            return response()->json(["code"=>404,"success"=>false,"message"=>"Usuário ou senha incorretos"]);
        }
    }

    public function getTenants(Request $request)
    {
        $request = $request->all();
        $usuario = User::where( "email","=",$request["email"] )
            ->where("senha","=", md5($request["senha"]) );
        if($usuario->count()>0)
        {
            $usuario = $usuario->first();
            $tenants = DB::table("tenantsUsuarios")
                ->join("tenants","tenants.id","=","tenantsUsuarios.tenantId")
                ->select("tenants.*")
                ->where("tenantsUsuarios.usuarioId","=",$usuario->id)
                ->get();
            $mudarSenha = $usuario->mudarSenha;
            return response()->json(["code"=>202,"success"=>true, "data"=>$tenants, "mudarSenha" => $mudarSenha]);            
        }
        else
        {
            return response()->json(["code"=>404,"success"=>false,"message"=>"Usuário ou senha incorretos"]);
        }
    }
}