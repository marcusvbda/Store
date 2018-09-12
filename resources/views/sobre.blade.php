@extends('templates.admin')
@section('title', 'Sobre')
@section('content')
<ul class="breadcrumb" style="margin-bottom:20px;">
  <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
  <li class="active">Sobre</li>
</ul>



<div class="row">
    <div class="col-md-2">
        <img src="{{asset('public/img/logo.png')}}" width="100%;">
        <br>
        <h1 style="color:#272C30;margin: 0;" class="text-center" ><small>ezCore</small>{{env('APP_NAME')}}</h1>
        <h3 style="margin: 0;" class="text-center"><small>{{env("VERSAO")}}</small></h3>
    </div>
    <div class="col-md-10">
        <div id="exTab2" class="col-md-12"> 
            <ul class="nav nav-tabs">
                <li class="active">
                    <a  href="#versoes" data-toggle="tab">Versões</a>
                </li>
                <li>
                    <a href="#desenvolvedores" data-toggle="tab">Desenvolvedores</a>
                </li>
            </ul>

            <div class="tab-content ">
                <div class="tab-pane active" id="versoes" style="padding:15px;">
                    @for($i=0;$i<count($versoes);$i++)
                        <div class="row">
                            <div class="col-md-3" @if($i%2==0)style="background-color:#b7cee8;padding:15px;"@else style="background-color: #c4c4c4;padding:15px;" @endif>
                                Versão : <strong>{{$versoes[$i]->nome}}</strong>
                                @if(env('VERSAO')==$versoes[$i]->nome)
                                    <span class="label label-success">Versão atual</span>
                                @endif
                            </div>
                            <div class="col-md-9" @if($i%2==0)style="background-color:#b7cee8;padding:15px;"@else style="background-color: #c4c4c4;padding:15px;" @endif>
                                <div style="padding:20px;">
                                    {!!$versoes[$i]->descricao!!}
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
                <div class="tab-pane" id="desenvolvedores">
                    <div class="row">
                        <div class="col-md-12" style="padding: 15px;">
                            <p style="margin:0"><strong>Analista desenvolvedor : </strong>Marcus Vincius Bassalobre de Assis</p>
                            <p style="margin:0"><strong>Website/Portifólio : </strong><a target="_blank" href="https://marcusvbda.github.io">https://marcusvbda.github.io</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>




@stop