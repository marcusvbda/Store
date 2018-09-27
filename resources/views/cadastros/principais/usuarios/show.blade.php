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
  
  <div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  Você está em modo de  <strong>visualização,</strong> para poder efetuar alterações clique no botão editar abaixo.
              </div> 

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
                      <label><span class="text-danger">*</span> Empresa</label>
                      <select class="form form-control selectpicker" multiple required parsley-trigger="change" disabled readonly
                        data-parsley-required-message="Este é um campo obrigatório">
                          @foreach($tenants as $tenant)
                            <?php $selected = (count(DB::table("tenantsUsuarios")->where("usuarioId","=",$usuario->id)->where("tenantId","=",$tenant->id)->get())>0);  ?>
                            <option @if($selected) selected @endif>{{$tenant->nome}}</option>
                          @endforeach
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