@extends('templates.admin')
@section('title', 'Cadastro de grupos de acesso')
@section('content')
<ul class="breadcrumb" style="margin-bottom:0px;">
  <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
  <li class="active">Cadastros <span class="divider"></span></li>
  <li class="active">Principais <span class="divider"></span></li>
  <li class="active"><a href="{{route('cadastros.principais.usuarios')}}">Usuários</a><span class="divider"></span></li>
  <li class="active"><strong>Grupos de acesso</strong></li>
</ul>

<br>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Cadastro de grupos de acesso
            </div>
            <div class="panel-body">
                <table id="tbgrupos" class="table table-striped table-bordered" style="width:100%;display:none;">
                    <thead>
                        <tr>
                            <th>NOME</th>
                            <th style="width:1%" class="no-sort"></th>
                        </tr>
                    </thead>
                    <form onsubmit="loadingElement($('#btnFiltrar'))">
                        <thead>
                            <tr>
                                <th>
                                    <input type="text" class="form form-control input-sm" style="width:100%" name="nome" value="{{$nome}}">
                                </th>
                                <th style="width:1%">
                                    <button class="btn btn-default btn-sm" style="width:100%" id="btnFiltrar"><i class="glyphicon glyphicon-search"></i> Filtrar</button>
                                </th>
                            </tr>
                        </thead>
                    </form>
                    <tbody>
                    @foreach($data as $grupo)
                        <tr primarykey="id" value="{{$grupo->id}}">
                            <td>{{$grupo->nome}}</td>
                            <td></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="tbgrupos__CRUD__" style="display:none;" >
    <form id="tbgrupos__formCrud__">
        <input style="display:none;" id='tbgrupos__formCrud___method' name="_method">
        <input style="display:none;" class='tbgrupos_primarykey_' name="id">
        <input style="display:none;"  name="_token" value="{{csrf_token()}}">
        <input type='submit' id='tbgrupos__formCrud__SUBMIT' style='display:none;'>
        <div class="row">
            <div class="col-md-12">
                <label>Nome</label>
                <input class="form form-control" type="text" maxlength="200" name="nome" id="nome">
            </div>
        </div>
        <br>
        <div class="row">
            @foreach($gruposPermissao as $grupo)
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">{{$grupo->descricao}}</div>
                        <div class="panel-body">
                            <?php $permissoes  = DB::table("permissoes")->where("grupoPermissaoId","=",$grupo->id)->get(); ?>
                            @foreach($permissoes as $permissao)
                                <p><label><input type="checkbox" name="{{$permissao->nome}}" id="{{$permissao->nome}}"> {{$permissao->descricao}}</label></p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
    </form>
</div>

<script>
var dtable = new dataTableCrud({
    titulo : " de grupos de acesso",
    order: 1,
    table : "#tbgrupos",
    crud  : true,
    perpage : 10,
    size : "90%",
    routePost : "{{route('cadastros.principais.usuarios.gruposAcesso.store')}}",
    routeDelete : "{{route('cadastros.principais.usuarios.gruposAcesso.delete')}}",
    routePut : "{{route('cadastros.principais.usuarios.gruposAcesso.put')}}",
    routeGet : "{{route('cadastros.principais.usuarios.gruposAcesso.get')}}",
    onload : function()
    {
        @cannot("get_gruposAcesso")
            $('.BtnView').remove();
        @endcan
        @cannot("put_gruposAcesso")
            $('.BtnEdit').remove();
        @endcan
        @cannot("delete_gruposAcesso")
            $('.BtnDestroy').remove();
        @endcan
        @cannot("post_gruposAcesso")
            $('.BtnStore').remove();
        @endcan
    },
    formCrud : "#formCrud"
    // campos:[
    //     {
    //       divClass : "col-md-12",
    //       label : "Nome",
    //       collumn : "nome",
    //       type : "text",
    //       maxlength : "100",
    //       required : true
    //     },
    //     @foreach($permissoes as $permissao)
    //         {
    //             divClass : "col-md-3",
    //             label : "{{$permissao->descricao}}",
    //             collumn : "{{$permissao->nome}}",
    //             type : "checkbox",
    //             checked : true
    //         }, 
    //     @endforeach
    // ]
});

</script>
@stop