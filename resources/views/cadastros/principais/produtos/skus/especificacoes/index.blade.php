@extends('templates.admin')
@section('title', 'Cadastro de especificações de produto')
@section('content')
<ul class="breadcrumb" style="margin-bottom:0px;">
  <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
  <li class="active">Cadastros <span class="divider"></span></li>
  <li class="active">Principais <span class="divider"></span></li>
  <li class="active"><a href="{{route('cadastros.principais.produtos')}}">Produtos</a><span class="divider"></span></li>
  <li class="active"><strong>Especificações de produto</strong></li>  
</ul>

<br>


<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Cadastro de especificações de produto
            </div>
            <div class="panel-body">
                <table id="table" class="table table-striped table-bordered" style="width:100%;display:none;">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th class="no-sort" style="width:1%"></th>
                        </tr>
                    </thead>
                    <form onsubmit="loadingElement($('#btnFiltrar'))">
                        <thead>
                            <tr>
                                <th>
                                    <input type="text" class="form form-control input-sm" style="width:100%" name="nome" value="{{$nome}}">
                                </th>
                                <th style="width:1%">
                                    <button type="submit" id="btnFiltrar" class="btn btn-default btn-sm" style="width:100%"><i class="glyphicon glyphicon-search"></i> Filtrar</button>
                                </th>
                            </tr>
                        </thead>
                    </form>
                    <tbody>
                    @foreach($data as $d)
                        <tr primarykey="id" value="{{$d->id}}">
                            <td>{{$d->nome}}</td>
                            <td></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div id="table__CRUD__" style="display:none;" >
    <form id="table__formCrud__" style="overflow: hidden;">
        <input style="display:none;" id='table__formCrud___method' name="_method">
        <input style="display:none;" class='table_primarykey_' name="id">
        <input style="display:none;"  name="_token" value="{{csrf_token()}}">
        <input type='submit' id='table__formCrud__SUBMIT' style='display:none;'>
        <div class="row" style="margin-bottom: 15px;">
            <div class="col-md-4">
                <label><span class="text-danger">*</span> Nome</label>
                <input class="form form-control" type="text" name="nome" id="nome" required maxlength="100">
            </div>
            <div class="col-md-4">
                <label><span class="text-danger">*</span> Tipo</label>
                <select class="form form-control" name="tipoCampo" id="tipoCampo" required v-model="tipoCampo"> 
                    <option value="TEXT">Texto</option>
                    <option value="NUMBER">Numérico</option>
                    <option value="SELECT">Seleção</option>
                    <option value="CHECKBOX">Checkbox</option>
                </select>
            </div>
            <div class="col-md-4">
                <label><span class="text-danger">*</span> Opções <small style="color:red;">Opções do campo separados por virgula caso o tipo seja SELEÇÃO</small></label>
                <input class="form form-control" type="text" name="opcoes" id="opcoes" :required="tipoCampo=='SELECAO'">
            </div>
        </div>
    </form>
</div>


<script>
var dtable = new dataTableCrud({
    order : 1,
    table : "#table",
    perpage : 10,
    size : "75%",
    crud  : true,
    formCrud : true,
    routePost : "{{route('cadastros.principais.produtos.sku.especificacoes.store')}}",
    routeGet  : "{{route('cadastros.principais.produtos.sku.especificacoes.get')}}",
    routePut  : "{{route('cadastros.principais.produtos.sku.especificacoes.put')}}",
    routeDelete : "{{route('cadastros.principais.produtos.sku.especificacoes.delete')}}",
    onload : function()
    {
        @cannot("get_especificacaoProduto")
            $(".BtnView").remove();
        @endcannot
        @cannot("put_especificacaoProduto")
            $(".BtnEdit").remove();
        @endcannot
        @cannot("delete_especificacaoProduto")
            $(".BtnDestroy").remove();
        @endcannot
        @cannot("post_especificacaoProduto")
            $(".BtnStore").remove();
        @endcannot
    }
});

var crud = new Vue( 
{
    el: '#table__CRUD__',
      data:
      {
          tipoCampo : "TEXT",
      },
      methods: 
      {
        
      }
});  

</script>
@stop