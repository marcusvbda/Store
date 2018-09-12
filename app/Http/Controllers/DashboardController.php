<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;

class DashboardController extends Controller
{
    public function sobre()
    {
        $versoes = DB::table("versoes")->orderBy("id","desc")->get();
        return view('sobre',compact('versoes'));
    }

    public function dashboard(request $request)
    {
        if(!$request->session()->has('timezone'))
            return redirect()->route("login");
        return view('dashboard');
    }
}