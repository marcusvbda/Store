@extends('templates.admin')
@section('title', 'Produtos')
@section('content')
<ul class="breadcrumb" style="margin-bottom:0px;">
  <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
  <li class="active">Cadastros <span class="divider"></span></li>
  <li class="active">Principais <span class="divider"></span></li>
  <li class="active"><strong>Produtos e SKUs</strong><span class="divider"></span></li>
</ul>
<br>
<form onsubmit="loadingElement($('.btnFiltrar'))">

  <div class="row" id="app">
      <div class="col-md-12">
          <div class="panel panel-default">
              <div class="panel-heading">
                  Listagem de produtos e SKUs
              </div>
              <div class="panel-body">
                  <table id="table" class="table table-striped table-bordered" style="width:100%;display:none;">
                      <thead>
                        <tr>
                            <th style="width:1%;" class="no-sort">
                              @can('post_produtos')
                                  <a onclick="loadingElement(this);openRoute('{{route('cadastros.principais.produtos.create')}}')"class="btn btn-default btn-sm"><span class="glyphicon glyphicon-plus"></span> Cadastrar</a>
                              @endcan
                            </th>
                            <th style="width:5%;">Marca</th>
                            <th style="width:20%;">Nome</th>
                            <th style="width:5%;">Skus</th>
                            <th style="width:1%;" class="no-sort"></th>
                        </tr>
                          <thead>
                              <tr>
                                  <th style="width:1%;"></th>
                                  <th>
                                      <select class="form form-control input-sm"  style="width:100%" name="marcaId">
                                      	<option></option>
                                      	@foreach($marcas as $m)
                                      		<option value="{{$m->id}}" @if($m->id == $marcaId) selected @endif>{{$m->nome}}</option>
                                      	@endforeach
                                      </select>
                                  </th>
                                  <th>
                                      <input type="text" class="form form-control input-sm" style="width:100%" name="nome" value="{{$nome}}">
                                  </th>
                                  <th></th>
                                  <th style="width:1%">
                                      <button type="submit" class="btn btn-default btn-sm btnFiltrar" style="width:100%"><i class="glyphicon glyphicon-search"></i> Filtrar</button>
                                  </th>
                              </tr>
                          </thead>
                      <tbody>
                      @foreach($data as $d)
                        <tr>
                            <td class="text-center">
                              <div class="dropdown">
                                <a class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" style="font-size:11px;" aria-haspopup="true" aria-expanded="true">
                                  <span class="glyphicon glyphicon-cog"></span> Ações <span class="caret"></span>
                                </a>    
                                <ul class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenu1">
                                <li>
                                    @can("get_produtos")
                                        <a class="BtnView" onclick="openRoute('{{route('cadastros.principais.produtos.show',['id'=>$d->id])}}')">Visualizar</a>
                                    @endcan
                                </li>
                               	<li>
                               	    @can("put_produtos")
                               	        <a class="BtnEdit"  onclick="openRoute('{{route('cadastros.principais.produtos.edit',['id'=>$d->id])}}')">Editar</a>
                               	    @endcan
                               	</li>
                               	<li role="separator" class="divider"></li>
                               	<li>
                               	    @can("delete_produtos")
                               	        <a class="BtnDestroy"  v-on:click="excluir('{{$d->id}}')" style="color:red;">Excluir</a>
                               	    @endcan
                               	</li>
                                </ul>
                              </div>
                            </td>
                            <td>{{$d->marca}}</td>
                            <td>{{$d->nome}}</td>
                            <td>
                            	<div class="row">
                            		<div class="col-md-3">
                            			<a  onclick="openRoute('{{route('cadastros.principais.produtos.sku.create',['produtoId'=>$d->id])}}')"><span class="glyphicon glyphicon-plus"></span> Novo SKU</a>
                            		</div>
                            		<div class="col-md-9">
                            			<a href="#"><span class="glyphicon glyphicon-triangle-right"></span>#2133143412
                            		</div>
                            	</div>
                            </td>
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

initFiltroAvancado("#filtrosAvancados");

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
        messageBox.confirm("Confirmação","Deseja excluir este produto?",function()
        {
          	alert("em desenvolvimento ...");
        });
    }
  }
});
var dtable = new dataTableCrud({
    order : 1,
    table : "#table",
    onload : function()
    {
        @cannot("get_produtos")
            $(".BtnView").remove();
        @endcannot
        @cannot("put_produtos")
            $(".BtnEdit").remove();
        @endcannot
        @cannot("delete_produtos")
            $(".BtnDestroy").remove();
        @endcannot
    }
});
</script>
@stop