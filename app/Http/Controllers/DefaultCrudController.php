<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class DefaultCrudController extends Controller
{
    public $table           = "";
    public $primaryKey      = "";
    public $route           = "";
    public $principalView   = "";

    public function index()
    {
        try 
        {
            $data  = DB::table($this->table)->get();
            return view($this->principalView,compact('data'));
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            return view("errors.500",compact("message"));
        }
    }

    public function rawQuery(Request $request)
    {
        try 
        {
            $data = $request->all();
            $query = $data["query"];
            $data =  DB::select($query);
            $header = [];
            foreach($data as $value)
            {
                foreach($value as $key => $k)
                {
                    array_push($header,$key);
                }
                break;
            }
            return response()->json(["code"=>202,"success"=>true,"data"=>$data, "header"=>$header]);
        } 
        catch (\Exception $e) 
        {
            return response()->json(["code"=>505,"success"=>false,"message" => $e->getMessage()]);
        }
    }

     public function checkMasterPass(Request $request)
    {
        try 
        {
            $data = $request->all();
            $pass = $data["pass"];
            if($pass!=env("MASTER_PASS"))
                return response()->json(["code"=>403,"success"=>false, "message"=>"Senha master incorreta"]);
            else
                return response()->json(["code"=>202,"success"=>true]);
        } 
        catch (\Exception $e) 
        {
            return response()->json(["code"=>505,"success"=>false,"message" => $e->getMessage()]);
        }
    }

    public function filter()
    { 
        try 
        {
            $data = DB::table($this->table);

            foreach ($_GET as $key => $value) 
            {
                if($key!=$this->primaryKey)
                {
                    $data = $data->where($this->table.".".$key,"like","%{$value}%");
                }
                else
                {
                    $data = $data->where($this->table.".".$key,"=","$value");
                }
            }
            return response()->json(["code"=>202,"success"=>true,"data"=>$data->get()]);
        } 
        catch (\Exception $e) 
        {
            return response()->json(["code"=>202,"success"=>false,"message" => $e->getMessage()]);
        }
    }

    public function get()
    { 
        try 
        {
            $primaryKey = $_GET[$this->primaryKey];
            $data = DB::table($this->table)->find($primaryKey);

            $data = DB::table($this->table)->where($this->primaryKey,"=",$primaryKey);
            if(Schema::hasColumn($this->table,"tenantId"))
            {
                $data = $data->where("tenantId", "=", Auth::user()->tenantId);
            }  
            $data = $data->first();

            return response()->json(["code"=>202,"success"=>true,"data"=>$data]);
        } 
        catch (\Exception $e) 
        {
            return response()->json(["code"=>202,"success"=>false,"message" => $e->getMessage()]);
        }
    }

    public function store()
    { 
        try 
        {
            unset($_POST["_token"],$_POST["_method"]);
            $_POST["id"] = uniqid();
            if(Schema::hasColumn($this->table,"tenantId"))
            {
                $_POST["tenantId"] = Auth::user()->tenantId;
            }           
            $data = DB::table($this->table)->insert($_POST);
            return redirect()->route($this->route);
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            return view("errors.500",compact("message"));
        }
    }

    public function put()
    { 
        try 
        {
            unset($_POST["_token"],$_POST["_method"]);
            $data = DB::table($this->table)->where($this->primaryKey,"=",$_POST[$this->primaryKey]);
            if(Schema::hasColumn($this->table,"tenantId"))
            {
                $data = $data->where("tenantId", "=", Auth::user()->tenantId);
            }  

            $data = $data->update($_POST);

            return redirect()->route($this->route);
        } 
        catch (\Exception $e) 
        {
            $message = $e->getMessage();
            return view("errors.500",compact("message"));
        }
    }

    public function delete(Request $request)
    { 
        try 
        {
            $data = DB::table($this->table)->where($this->primaryKey,"=",$request->only($this->primaryKey));
            if(Schema::hasColumn($this->table,"tenantId"))
            {
                $data = $data->where("tenantId", "=", Auth::user()->tenantId);
            }  
            $data->delete();
            return response()->json(["code"=>202,"success"=>true]);
        } 
        catch (\Exception $e) 
        {
            return response()->json(["code"=>202,"success"=>false,"message" => $e->getMessage()]);
        }
    }

}