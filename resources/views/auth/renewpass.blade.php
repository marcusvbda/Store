@extends('templates.default')
@section('title', 'Nova senha')
@section('body')

<body>
<link href="{{asset('public/css/sigin.css')}}" rel="stylesheet">

    <div class="row" id="app" style="margin-right: 10px;margin-left: 10px;">

        <div class="col-md-9">
        	<div class="alert alert-success alert-dismissable">
			    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			    Sua senha foi temporariamente alterada para <strong>{{$novaSenhaTemp}}</strong>.
			</div>
        	<div class="alert alert-warning alert-dismissable">
			    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			    Copie esta nova senha e faça <a target="_blank" href="{{asset('admin/auth/login')}}">login</a> no sistema, antes de entrar será solicitado a mudança para uma nova senha de sua preferencia.
			</div>
        </div>
        <div class="col-md-3 sombraLogin" style="background-color: white;height: 450px;padding-top: 45px;border-radius: 10px;">
                <div class="text-center" style="margin-bottom:10px;">
                    <p style="margin-bottom: 0;">
                        <img src="{{asset('public/img/logo.png')}}" width="100px;" style="margin-bottom: 0;">
                        <h1 style="margin:0;" class="text-center"><small>ezCore</small>Leads</h1>
                        <h3 style="margin:0;" class="text-center"><small>{{env('VERSAO')}}</small></h3>
                    </p>
                </div>
                <label>Email</label>
                <input type="text" disabled class="form-control"  value="{{$usuario->email}}">
                <label>Senha temporária</label>
                <input type="text" disabled class="form-control"  value="{{$novaSenhaTemp}}">

                <br>
                <a target="_blank" href="{{asset('admin/auth/login')}}" class="btn btn-lg btn-primary btn-block"> 
                    Ir para a tela de login
                </a>
        </div>

    </div> <!-- /container -->

</body>


@stop