@extends('templates.admin')
@section('title', 'Cadastro de modelos de email')
@section('content')
<ul class="breadcrumb" style="margin-bottom:0px;">
  <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
  <li class="active">Cadastros <span class="divider"></span></li>
  <li class="active">Auxiliáres <span class="divider"></span></li>
  <li class="active">Modelos <span class="divider"></span></li>
  <li class="active"><strong>Emails</strong></li>  
</ul>

<br>


<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Cadastro de modelos de email
            </div>
            <div class="panel-body">
                <table id="table" class="table table-striped table-bordered" style="width:100%;display:none;">
                    <thead>
                        <tr>
                            <th style="width:20%">Assunto</th>
                            <th style="width:30%">Nome</th>
                            <th class="no-sort" style="width:1%"></th>
                        </tr>
                    </thead>
                    <form onsubmit="loadingElement($('#btnFiltrar'))">
                        <thead>
                            <tr>
                                <th style="width:30%">
                                    <input type="text" class="form form-control input-sm" style="width:100%" name="assunto" value="{{$assunto}}">
                                </th>
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
                            <td>{{$d->assunto}}</td>
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
            <div class="col-md-6">
                <label><span class="text-danger">*</span> Nome</label>
                <input class="form form-control" type="text" maxlength="200" name="nome" id="nome" required>
            </div>
             <div class="col-md-6">
                <label><span class="text-danger">*</span>  Assunto</label>
                <input class="form form-control" type="text" maxlength="200" name="assunto" id="assunto" required>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <label>Modelo</label>
                <textarea class="form form-control" id="modelo" style="resize: none;" name="modelo"></textarea>
            </div>
    </form>
</div>


<script>
$('#modelo').summernote({
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
        url: "{{route('cadastros.auxiliares.emails.uploadImagem')}}",
        cache: false,
        contentType: false,
        processData: false,
        data: data,
        type: "POST",
        success: function(response) 
        {
            console.log(response);
            var image = $('<img>').attr('src', response.data);
            $('#modelo').summernote("insertNode", image[0]);
        },
        error: function(data) {
            console.log(data);
        }
    });
}

var dtable = new dataTableCrud({
    titulo : " de modelo email",
    order : 1,
    table : "#table",
    perpage : 10,
    size : "80%",
    crud  : true,
    formCrud : true,
    summernote : 
    [
        { id : "#modelo" }
    ],
    routePost : "{{route('cadastros.auxiliares.emails.store')}}",
    routeGet  : "{{route('cadastros.auxiliares.emails.get')}}",
    routePut  : "{{route('cadastros.auxiliares.emails.put')}}",
    routeDelete : "{{route('cadastros.auxiliares.emails.delete')}}",
    onload : function()
    {
        @cannot("get_modelos_email")
            $(".BtnView").remove();
        @endcannot
        @cannot("put_modelos_email")
            $(".BtnEdit").remove();
        @endcannot
        @cannot("delete_modelos_email")
            $(".BtnDestroy").remove();
        @endcannot
        @cannot("post_modelos_email")
            $(".BtnStore").remove();
        @endcannot
    }
});

</script>
@stop