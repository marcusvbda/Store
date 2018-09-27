@extends('templates.admin')
@section('title', 'Campos personalizados')
@section('content')
<ul class="breadcrumb" style="margin-bottom:0px;">
  <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
  <li class="active">Utilitários <span class="divider"></span></li>
  <li class="active">CMS <span class="divider"></span></li>
  <li class="active"><strong>Campos personalizados</strong></li>  
</ul>

<br>


<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Campos personalizados
            </div>
            <div class="panel-body">
                <table id="table" class="table table-striped table-bordered" style="width:100%;display:none;">
                    <thead>
                        <tr>
                            <th style="width:30%">Nome</th>
                            <th class="no-sort" style="width:1%"></th>
                        </tr>
                    </thead>
                    <form onsubmit="loadingElement($('#btnFiltrar'))">
                        <thead>
                            <tr>
                                <th style="width:30%">
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
    <form id="table__formCrud__">
        <input style="display:none;" id='table__formCrud___method' name="_method">
        <input style="display:none;" class='table_primarykey_' name="id">
        <input style="display:none;"  name="_token" value="{{csrf_token()}}">
        <input type='submit' id='table__formCrud__SUBMIT' style='display:none;'>
        <div class="row">
            <div class="col-md-12">
                <label><span class="text-danger">*</span> Nome</label>
                <input class="form form-control" type="text" maxlength="200" name="nome" id="nome" required>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <label>Conteudo</label>
                <textarea class="form form-control" id="conteudo" style="resize: none;" name="conteudo"></textarea>
            </div>
    </form>
</div>


<script>
$('#conteudo').summernote({
    height: 250,
    callbacks: {
                onImageUpload: function(image) {
                    uploadImage(image[0]);
                }
            }
});

function uploadImage(image)
{
    var data = new FormData();
    data.append("image", image);
    $.ajax({
        url: "{{route('default.uploadImagem')}}",
        cache: false,
        contentType: false,
        processData: false,
        data: data,
        type: "POST",
        success: function(response) 
        {
            console.log(response);
            var image = $('<img>').attr('src', response.data);
            $('#conteudo').summernote("insertNode", image[0]);
        },
        error: function(data) {
            console.log(data);
        }
    });
}

var dtable = new dataTableCrud({
    titulo : "de campos personalizados",
    order : 1,
    table : "#table",
    perpage : 10,
    size : "80%",
    crud  : true,
    formCrud : true,
    summernote : 
    [
        { id : "#conteudo" }
    ],
    routePost : "{{route('utilitarios.cms.camposPersonalizados.store')}}",
    routeGet  : "{{route('utilitarios.cms.camposPersonalizados.get')}}",
    routePut  : "{{route('utilitarios.cms.camposPersonalizados.put')}}",
    routeDelete : "{{route('utilitarios.cms.camposPersonalizados.delete')}}",
    onload : function()
    {
        @cannot("get_campoPersonalizado")
            $(".BtnView").remove();
        @endcannot
        @cannot("put_campoPersonalizado")
            $(".BtnEdit").remove();
        @endcannot
        @cannot("delete_campoPersonalizado")
            $(".BtnDestroy").remove();
        @endcannot
        @cannot("post_campoPersonalizado")
            $(".BtnStore").remove();
        @endcannot
    }
});

</script>
@stop