@extends('templates.admin')
@section('title', 'SKUs do produto')
@section('content')
<ul class="breadcrumb" style="margin-bottom:0px;">
  <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
  <li class="active">Cadastros <span class="divider"></span></li>
  <li class="active">Principais <span class="divider"></span></li>
  <li class="active"><a href="{{route('cadastros.principais.produtos')}}">Produtos</a><span class="divider"></span></li>
  <li class="active"><a href="{{route('cadastros.principais.produtos.show',['id'=>$produto->id])}}">{{$produto->nome}}</a><span class="divider"></span></li>
  <li class="active">SKUs do produto<span class="divider"></span></li>
</ul>

<br>
<form onsubmit="loadingElement($('.btnFiltrar'))">

  <div class="row" id="app">
      <div class="col-md-12">
          <div class="panel panel-default">
              <div class="panel-heading">
                  SKUs do produto <strong>{{$produto->nome}}</strong>
              </div>
              <div class="panel-body">
                  <table id="table" class="table table-striped table-bordered" style="width:100%;display:none;">
                      <thead>
                        <tr>
                            <th style="width:1%;" class="no-sort">
                              @can('post_produtos')
                                  <a onclick="loadingElement(this);openRoute('{{route('cadastros.principais.produtos.skus.create',['produtoId'=>$produto->id])}}')"class="btn btn-default btn-sm"><span class="glyphicon glyphicon-plus"></span> Cadastrar SKU</a>
                              @endcan
                            </th>
                            <th style="width:1%;" class="no-sort text-center"></th>
                            <th style="width:10%;">id</th>
                            <th style="width:20%;">Nome</th>
                            <th>EAN</th>
                            <th class="text-center" width="1%">Status</th>
                            <th style="width:1%;" class="no-sort"></th>
                        </tr>
                          <thead>
                              <tr>
                                  <th></th>
                                  <th style="width:1%;"></th>
                                  <th style="width:10%;">
                                      <input type="text" class="form form-control input-sm" style="width:100%" name="id" value="{{$id}}">
                                  </th>
                                  <th>
                                      <input type="text" class="form form-control input-sm" style="width:100%" name="nome" value="{{$nome}}">
                                  </th>
                                  <th>
                                      <input type="text" class="form form-control input-sm" style="width:100%" name="ean" value="{{$ean}}">
                                  </th>
                                  <th>
                                    <select  class="form form-control input-sm" style="width:100%" name="ativo" >
                                      <option @if($ativo=="") selected @endif ></option>
                                      <option @if($ativo=='1') selected @endif value="1">Ativo</option>
                                      <option @if($ativo=='0') selected @endif value="0">Inativo</option>
                                    </select>
                                  </th>
                                  <th style="width:1%">
                                      <button type="submit" class="btn btn-default btn-sm btnFiltrar" style="width:100%"><i class="glyphicon glyphicon-search"></i> Filtrar</button>
                                  </th>
                              </tr>
                          </thead>
                      <tbody>
                      @foreach($data as $d)
                        <tr>
                            <td class="text-center" style="padding-top: 20px;">
                              <div class="dropdown">
                                <a class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" style="font-size:11px;" aria-haspopup="true" aria-expanded="true">
                                  <span class="glyphicon glyphicon-cog"></span> Ações <span class="caret"></span>
                                </a>    
                                <ul class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenu1">
                                <li>
                                    @can("get_produtos")
                                        <a class="BtnView" onclick="openRoute('{{route('cadastros.principais.produtos.skus.show',['skuId'=>$d->id,'produtoId'=>$produto->id])}}')">Visualizar</a>
                                    @endcan
                                </li>
                               	<li>
                               	    @can("put_produtos")
                                    <a class="BtnView" onclick="openRoute('{{route('cadastros.principais.produtos.skus.edit',['produtoId'=>$produto->id, 'skuId'=>$d->id])}}')">Editar</a>
                               	    @endcan
                               	</li>
                               	<li role="separator" class="divider"></li>
                               	<li>
                               	    @can("delete_produtos")
                               	        <a class="BtnDestroy"  v-on:click="excluir('{{route('cadastros.principais.produtos.skus.delete',['produtoId'=>$produto->id, 'skuId'=>$d->id])}}')" style="color:red;">Excluir</a>
                               	    @endcan
                               	</li>
                                </ul>
                              </div>
                            </td>
                            
                            <td class="text-center">
                              <img src="{{$self->getPrincipalImg($d->id)}}" style="height: 50px;">
                            </td>
                            <td style="padding-top: 20px;width:10%;">{{$d->id}}</td>
                            <td style="padding-top: 20px;">{{$d->nome}}</td>
                            <td style="padding-top: 20px;">{{$d->ean}}</td>
                            <td style="padding-top: 20px;"  class="text-center" width="1%">
                              @if($d->ativo)
                                <span class="label label-success" >Ativo</span>
                              @else
                                <span class="label label-danger" >Inativo</span>
                              @endif
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
    excluir: function(url)
    {
        messageBox.confirm("Confirmação","Deseja excluir este SKU ?",function()
        {
          	
          vform.ajax("delete",url,{},function(response)
          {
              if(!response.success)
                return toastr.error(response.message);
              toastr.success("SKU excluido com sucesso");
              window.location.reload();
          });

        });
    }
  }
});
var dtable = new dataTableCrud({
    order : 2,
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