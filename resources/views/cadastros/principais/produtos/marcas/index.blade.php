@extends('templates.admin')
@section('title', 'Cadastro de marcas')
@section('content')
<ul class="breadcrumb" style="margin-bottom:0px;">
  <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
  <li class="active">Cadastros <span class="divider"></span></li>
  <li class="active">Principais <span class="divider"></span></li>
  <li class="active"><a href="{{route('cadastros.principais.produtos')}}">Produtos</a><span class="divider"></span></li>
  <li class="active"><strong>Marcas</strong></li>  
</ul>

<br>


<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Cadastro de marcas
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
        <div class="row">
            <div class="col-md-12">
                <label><span class="text-danger">*</span> Nome</label>
                <input class="form form-control" type="text" name="nome" id="nome" required maxlength="100">
            </div>
        </div>
    </form>
</div>


<script>
var dtable = new dataTableCrud({
    titulo : "de marcas",
    order : 1,
    table : "#table",
    perpage : 10,
    size : "40%",
    crud  : true,
    formCrud : true,
    routePost : "{{route('cadastros.principais.produtos.marcas.store')}}",
    routeGet  : "{{route('cadastros.principais.produtos.marcas.get')}}",
    routePut  : "{{route('cadastros.principais.produtos.marcas.put')}}",
    routeDelete : "{{route('cadastros.principais.produtos.marcas.delete')}}",
    onload : function()
    {
        @cannot("get_marcas")
            $(".BtnView").remove();
        @endcannot
        @cannot("put_marcas")
            $(".BtnEdit").remove();
        @endcannot
        @cannot("delete_marcas")
            $(".BtnDestroy").remove();
        @endcannot
        @cannot("post_marcas")
            $(".BtnStore").remove();
        @endcannot
    }
});

</script>
@stop