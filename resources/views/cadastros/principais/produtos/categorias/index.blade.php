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

<div id="app">
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
                                <th>subcategorias</th>
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
                                    <div class="row">
                                        <div class="col-md-3">
                                            <small>
                                                <a onclick="cadastrarSubCategoria('{{$d->id}}')" href="#">
                                                    <span class="glyphicon glyphicon-plus"></span> Nova subcategoria
                                                </a>
                                            </small>
                                        </div>
                                        <div class="col-md-9">
                                            @foreach($d->subCategorias as $sub)
                                                <span>
                                                    <small>
                                                        <a href="#" @can('put_categoriaProduto') onclick="editSubCategoria('{{$sub->id}}','{{$d->id}}')" @endcan>
                                                            <span class="glyphicon glyphicon-triangle-right"></span>{{$sub->nome}}
                                                        </a>
                                                    </small>
                                                    @can('delete_categoriaProduto')
                                                    <button href="#" class="btn btn-danger btn-xs" style="margin-right: 20px;" onclick="excluirSubCate('{{$sub->id}}')" >
                                                        <span class="glyphicon glyphicon-remove-sign"></span>
                                                    </button>
                                                    @endcan
                                                </span>
                                            @endforeach
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
    <div id="table__CRUD__" style="display:none;" >
        <form id="table__formCrud__" style="overflow: hidden;">
            <input style="display:none;" id='table__formCrud___method' name="_method">
            <input style="display:none;" class='table_primarykey_' name="id">
            <input style="display:none;"  name="_token" value="{{csrf_token()}}">
            <input type='submit' id='table__formCrud__SUBMIT' style='display:none;'>
            <div class="row">
                <div class="col-md-12">
                    <label><span class="text-danger">*</span> Categoria</label>
                    <input class="form form-control" type="text"  name="nome" id="nome" required maxlength="100" style="text-transform: uppercase;">
                </div>
            </div>
        </form>
    </div>

    <div id="table__CRUD__sub" style="display:none;" >
        <form id="table__formCrud__sub" style="overflow: hidden;">
            <input style="display:none;" id='table__formCrud___method__sub' name="_method">
            <input style="display:none;" id='table_primarykey_sub'>
            <input style="display:none;"  name="_token" value="{{csrf_token()}}">
            <input type='submit' id='table__formCrud__SUBMIT_sub' style='display:none;'>
            <div class="row">
                <div class="col-md-12">
                    <label><span class="text-danger">*</span> Subcategoria</label>
                    <input class="form form-control" type="text" id="nome_sub"  name="nome"  required maxlength="100" style="text-transform: uppercase;">
                    <input style="display:none;" id='table_primarykey_sub_categoriaId' name="categoriaId">
                    <input style="display:none;" id='table_primarykey_sub_id' name="id">
                </div>
            </div>
        </form>
    </div>
</div>


<script>
var dtable = new dataTableCrud({
    titulo : " de categoria de produtos",
    order : 1,
    table : "#table",
    perpage : 10,
    size : "30%",
    crud  : true,
    formCrud : true,
    routePost : "{{route('cadastros.principais.produtos.categorias.store')}}",
    routeGet  : "{{route('cadastros.principais.produtos.categorias.get')}}",
    routePut  : "{{route('cadastros.principais.produtos.categorias.put')}}",
    routeDelete : "{{route('cadastros.principais.produtos.categorias.delete')}}",
    onload : function()
    {
        @cannot("get_categoriaProduto")
            $(".BtnView").remove();
        @endcannot
        @cannot("get_categoriaProduto")
            $(".BtnEdit").remove();
        @endcannot
        @cannot("delete_categoriaProduto")
            $(".BtnDestroy").remove();
        @endcannot
        @cannot("post_categoriaProduto")
            $(".BtnStore").remove();
        @endcannot
    }
});
 

function cadastrarSubCategoria(categoriaId)
{
    $("#table__formCrud__sub").trigger("reset");            
    $("#table__formCrud__sub").find("input,select").each(function()
    {
        $(this).attr("disabled",false);
    });      
    $("#table__formCrud___method__sub").val("POST");
    $("#table_primarykey_sub").removeAttr("name");
    $("#table_primarykey_sub").val("");
    $("#table_primarykey_sub_categoriaId").val(categoriaId);
    $("#table__formCrud__sub").attr("method","POST");
    $("#table__formCrud__sub").attr("action","{{route('cadastros.principais.produtos.categorias.subcategoria.store')}}");
    $("#table__CRUD__sub").dialog(
    {
        modal: true, title: "Cadastro de subCategoria", zIndex: 10000, autoOpen: true,
        width: "30%", resizable: false, draggable: true,
        open: function (event, ui) 
        {
            $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
        },
        buttons:{
            cancel : 
            {
                click: function () 
                {
                    messageBox.confirm("Confirmação","Deseja mesmo cancelar ?",function(callback)
                    {
                        $("#table__CRUD__sub").dialog('close');
                    });
                },
                class: 'btn btn btn-danger',
                text: "Cancelar"
            },
            store : 
            {
                click: function () 
                {
                    $("#table__formCrud__SUBMIT_sub").trigger( "click" );
                },
                class: 'btnConfirm__formCrud__ btn btn btn-primary',
                text: "Salvar"
            }
        }
    });

} 

function excluirSubCate(id)
{
    messageBox.confirm("Confirmação","Confirma exclusão ?",function()
    {
        vform.ajax("delete","{{route('cadastros.principais.produtos.categorias.subcategoria.delete')}}",{id},function(response)
        {
            if(!response.success)
                return messageBox.simple("Ooops",response.message,"error");
            return location.reload();   
        });
    });
}


function editSubCategoria(id,categoriaId)
{
    vform.ajax("get","{{route('cadastros.principais.produtos.categorias.subcategoria.get')}}",{id},function(response)
    {   
        $("#table__formCrud__sub").trigger("reset");            
        $("#table__formCrud__sub").find("input,select").each(function()
        {
            $(this).attr("disabled",false);
        });      
        $("#table__formCrud___method__sub").val("PUT");
        $("#table_primarykey_sub").removeAttr("name");
        $("#table_primarykey_sub").val("");
        $("#table_primarykey_sub_id").val(id);
        $("#table_primarykey_sub_categoriaId").val(categoriaId);
        $("#nome_sub").val(response.data.nome);
        $("#table__formCrud__sub").attr("method","POST");
        $("#table__formCrud__sub").attr("action","{{route('cadastros.principais.produtos.categorias.subcategoria.put')}}");
        $("#table__CRUD__sub").dialog(
        {
            modal: true, title: "Edição de subCategoria", zIndex: 10000, autoOpen: true,
            width: "30%", resizable: false, draggable: true,
            open: function (event, ui) 
            {
                $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
            },
            buttons:
            {
                cancel : 
                {
                    click: function () 
                    {
                        messageBox.confirm("Confirmação","Deseja mesmo cancelar ?",function(callback)
                        {
                            $("#table__CRUD__sub").dialog('close');
                        });
                    },
                    class: 'btn btn btn-danger',
                    text: "Cancelar"
                },
                store : 
                {
                    click: function () 
                    {
                        $("#table__formCrud__SUBMIT_sub").trigger( "click" );
                    },
                    class: 'btnConfirm__formCrud__ btn btn btn-primary',
                    text: "Salvar"
                }
            }
        });


    });
}
</script>
@stop