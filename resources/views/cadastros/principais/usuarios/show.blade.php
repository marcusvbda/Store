@extends('templates.admin')
@section('title', 'Visualização de Usuário')
@section('content')
<ul class="breadcrumb" style="margin-bottom:0px;">
  <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
  <li class="active">Cadastros <span class="divider"></span></li>
  <li class="active">Principais <span class="divider"></span></li>
  <li class="active"><a href="{{route('cadastros.principais.usuarios')}}">Usuários</a><span class="divider"></span></li>
  <li class="active"><strong>Profile do usuário ( #{{$usuario->id}} - {{$usuario->nome}} )</strong><span class="divider"></span></li>
</ul>

<div  style="margin-top:15px;" id="app">


    <div class="panel panel-default">
        <div class="panel-heading">
            Profile do usuário ( #{{$usuario->id}} - {{$usuario->nome}} )
        </div>
        <div class="panel-body">


              <form id="frm" data-parsley-validate="" novalidate="" v-on:submit.prevent="editar()">

                <div class="row">
                  <div class="col-md-4" style="margin-bottom:15px;">
                    <label><span class="text-danger">*</span> Nome</label>
                    <input type="text" class="form-control form-control-sm" readonly value="{{$usuario->nome}}">
                  </div>
                  <div class="col-md-2" style="margin-bottom:15px;">
                      <label>Data nascimento</label>
                      <input type="date" class="form-control form-control-sm"  readonly value="{{$usuario->dtNascimento}}">
                  </div>
                  @can("put_emailSenha")
                    <div class="col-md-6" style="margin-bottom:15px;">
                        <label><span class="text-danger">*</span> Email</label>
                        <input type="email" class="form-control form-control-sm" readonly value="{{$usuario->email}}">
                    </div>
                  @endcan
                  <div class="col-md-4" style="margin-bottom:15px;">
                    <label><span class="text-danger">*</span> Grupo de acesso</label>
                    <input type="email" class="form-control form-control-sm" readonly value="{{$usuario->grupoAcesso->nome}}">
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4" style="margin-bottom:15px;">
                      <label><span class="text-danger">*</span> Polo</label>
                      <select class="form form-control selectpicker" multiple required parsley-trigger="change" disabled readonly
                        data-parsley-required-message="Este é um campo obrigatório">
                          @foreach($tenants as $tenant)
                            <?php $selected = (count(DB::table("tenantsUsuarios")->where("usuarioId","=",$usuario->id)->where("tenantId","=",$tenant->id)->get())>0);  ?>
                            <option @if($selected) selected @endif>{{$tenant->nome}}</option>
                          @endforeach
                      </select>
                  </div>
                  <div class="col-md-4" style="margin-bottom:15px;">
                        <label><span class="text-danger">*</span> Operador Follow</label>
                        <select class="form form-control"  required parsley-trigger="change" disabled readonly
                          data-parsley-required-message="Este é um campo obrigatório">
                              <option  @if($usuario->operadorFollow) selected @endif value="1">Sim</option>
                              <option  @if(!$usuario->operadorFollow1) selected @endif value="0">Não</option>
                        </select>
                    </div>
                    <div class="col-md-4" style="margin-bottom:15px;">
                        <label><span class="text-danger">*</span> Captador</label>
                        <select class="form form-control"  required parsley-trigger="change" disabled readonly
                          data-parsley-required-message="Este é um campo obrigatório">
                              <option @if($usuario->captador) selected @endif value="1">Sim</option>
                              <option @if(!$usuario->captador) selected @endif value="0">Não</option>
                        </select>
                    </div>
                </div>
                @if(!$usuario->root)
                <hr>
                
                <div class="row">
                  @can("put_usuarios")
                    <div class="col-md-12 text-right">
                      <a onclick="loadingElement(this);openRoute('{{route('cadastros.principais.usuarios.edit',['id'=>$usuario->id])}}')" class="btn btn-default"><i class="glyphicon glyphicon-edit"></i> Editar</a>
                    </div>
                  @endcan
                </div>
                @endif

              </form>

        </div>
    </div>

 


</div>


@stop