@extends('templates.admin')
@section('title', 'Empresas')
@section('content')
<ul class="breadcrumb" style="margin-bottom:0px;">
  <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
  <li class="active">Cadastros <span class="divider"></span></li>
  <li class="active">Principais <span class="divider"></span></li>
  <li class="active"><strong>Empresas</strong></li>  
</ul>

<br>

<form id="app" onsubmit="loadingElement($('#btnFiltrar'))">

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Cadastro de empresas
                </div>
                <div class="panel-body">
                    <table id="table" class="table table-striped table-bordered" style="width:100%;display:none;">
                        <thead>
                            <tr>
                                <th style="width:1%;" class="no-sort">
                                  @can('post_tenants')
                                      <a onclick="loadingElement(this);openRoute('{{route('cadastros.principais.empresas.create')}}')" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-plus"></span> Cadastrar</a>
                                  @endcan
                                </th>
                                <th style="width:15%">CNPJ</th>
                                <th>Nome</th>
                                <th>Razão</th>
                                <th style="width: 10%">Principal</th>
                                <th class="no-sort" style="width:1%"></th>
                            </tr>
                        </thead>
                        <thead>
                            <tr>
                                <th></th>
                                <th style="width:15%">
                                    <input type="text" class="form form-control input-sm" style="width:100%" name="cnpj"  value="{{$cnpj}}" >
                                </th>
                                <th>
                                    <input type="text" class="form form-control input-sm" style="width:100%" name="nome" value="{{$nome}}">
                                </th>
                                <th>
                                    <input type="text" class="form form-control input-sm" style="width:100%" name="razao" value="{{$razao}}">
                                </th>
                                <th>
                                    <select class="form form-control input-sm" style="width:100%" name="principal" >
                                        <option @if($principal==-1) selected @endif value="-1"></option>
                                        <option @if($principal==1) selected @endif value="1">SIM</option>
                                        <option @if($principal==0) selected @endif value="0">NÃO</option>
                                    </select>
                                </th>
                                <th style="width:1%">
                                    <button class="btn btn-default btn-sm" id="btnFiltrar" style="width:100%"><i class="glyphicon glyphicon-search"></i> Filtrar</button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($tenants as $tenant)
                            <tr primarykey="id" value="{{$tenant->id}}">
                                <td class="text-center" width="1%">
                                  <div class="dropdown">
                                    <a class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" style="font-size:11px;" aria-haspopup="true" aria-expanded="true">
                                      <span class="glyphicon glyphicon-cog"></span> Ações <span class="caret"></span>
                                    </a>    
                                    <ul class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenu1">
                                    <li>
                                        @can("get_tenants")
                                            <a class="BtnView" onclick="openRoute('{{route('cadastros.principais.empresas.show',['id'=>$tenant->id])}}')">Visualizar</a>
                                        @endcan
                                        </li>
                                        <li>
                                            @can("put_tenants")
                                                <a class="BtnEdit"  onclick="openRoute('{{route('cadastros.principais.empresas.edit',['id'=>$tenant->id])}}')">Editar</a>
                                            @endcan
                                        </li>
                                        @if(!$tenant->principal)
                                            <li role="separator" class="divider"></li>
                                            <li>
                                              @can("delete_tenants")
                                                  <a class="BtnDestroy"  v-on:click="excluir('{{$tenant->id}}')" style="color:red;">Excluir</a>
                                              @endcan
                                           </li>
                                        @endif
                                    </ul>
                                  </div>
                                </td>
                                <td>{{$tenant->cnpj}}</td>
                                <td>{{$tenant->nome}}</td>
                                <td>{{$tenant->razao}}</td>
                                <td class="text-center">@if($tenant->principal) <span class="label label-success">Principal</span>  @endif</td>
                                <td></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>
<script>



var app = new Vue( 
{
  el: '#app',
  data:
  {
  },
  methods: 
  {
    excluir: function(id)
    {
        messageBox.confirm("Confirmação","Deseja excluir esta empresa?",function()
        {
          vform.ajax('delete', '{{route("cadastros.principais.empresas.delete")}}',{id},function(response)
          {
              if(!response.success)
                  return toastr.error(response.message);
              openRoute("{{route('cadastros.principais.empresas')}}");
          });
        });
    }
  }
});



var dtable = new dataTableCrud({
    order : 2,
    table : "#table",
    crud  : false
});

</script>
@stop