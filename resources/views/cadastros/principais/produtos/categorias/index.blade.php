@extends('templates.admin')
@section('title', 'Cadastro de categorias de produto')
@section('content')
<ul class="breadcrumb" style="margin-bottom:0px;">
  <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
  <li class="active">Cadastros <span class="divider"></span></li>
  <li class="active">Principais <span class="divider"></span></li>
  <li class="active"><a href="{{route('cadastros.principais.produtos')}}">Produtos</a><span class="divider"></span></li>
  <li class="active"><strong>Categorias de produto</strong></li>  
</ul>

<br>


<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Cadastro de categorias de produto
            </div>
            <div class="panel-body">
                <table id="table" class="table table-striped table-bordered" style="width:100%;display:none;">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>subCategorias</th>
                            <th class="no-sort" style="width:1%"></th>
                        </tr>
                    </thead>
                    <form onsubmit="loadingElement($('#btnFiltrar'))">
                        <thead>
                            <tr>
                                <th>
                                    <input type="text" class="form form-control input-sm" style="width:100%" name="nome" value="{{$nome}}">
                                </th>
                                <th></th>
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
                            <td>
                                @foreach($d->subCategorias as $sub)
                                    <a href="#"><span class="glyphicon glyphicon-triangle-right"></span> {{$sub->nome}}</a>
                                @endforeach
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
<div id="table__CRUD__" style="display:none;" >
    <form id="table__formCrud__" style="overflow: hidden;">
        <input style="display:none;" id='table__formCrud___method' name="_method">
        <input style="display:none;" class='table_primarykey_' name="id">
        <input style="display:none;"  name="_token" value="{{csrf_token()}}">
        <input type='submit' id='table__formCrud__SUBMIT' style='display:none;'>
        <div class="row">
            <div class="col-md-5">
                <label><span class="text-danger">*</span> Categoria</label>
                <input class="form form-control" type="text"  name="nome" id="nome" required maxlength="100">
            </div>
            <div class="col-md-7">
                <label><span class="text-danger">*</span> Subcategorias <small class="text-danger">digite cada sub categoria separada por virgula ( , )</small></label>
                <input class="form form-control" type="text"  name="sub" id="sub" required">
            </div>
        </div>
    </form>
</div>


<script>
var dtable = new dataTableCrud({
    order : 1,
    table : "#table",
    perpage : 10,
    size : "60%",
    crud  : true,
    formCrud : true,
    routePost : "{{route('cadastros.principais.produtos.categorias.store')}}",
    routeGet  : "{{route('cadastros.principais.produtos.categorias.get')}}",
    routePut  : "{{route('cadastros.principais.produtos.categorias.put')}}",
    routeDelete : "{{route('cadastros.principais.produtos.categorias.delete')}}",
    onload : function()
    {
        @cannot("get_categoriaProduto____")
            $(".BtnView").remove();
        @endcannot
        @cannot("get_categoriaProduto____")
            $(".BtnEdit").remove();
        @endcannot
        @cannot("get_categoriaProduto____")
            $(".BtnDestroy").remove();
        @endcannot
        @cannot("post_categoriaProduto")
            $(".BtnStore").remove();
        @endcannot
    }
});

</script>
@stop