@extends('templates.admin')
@section('title', 'Usuários')
@section('content')
<ul class="breadcrumb" style="margin-bottom:0px;">
  <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
  <li class="active">Cadastros <span class="divider"></span></li>
  <li class="active">Principais <span class="divider"></span></li>
  <li class="active"><strong>Usuários</strong><span class="divider"></span></li>
</ul>
<br>
<form onsubmit="loadingElement($('.btnFiltrar'))">

  <div class="row">
    <div class="col-md-12">
        <div id="filtrosAvancados" class="text-right"></div>

          <div class="panel panel-default">
              <div class="panel-heading" style="padding-top: 5px;padding-bottom: 5px;padding-right: 10px;padding-left: 10px;background-color: rgb(81, 83, 104);">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" class="collapsed" style="width:100%;color:white;text-decoration:none;">
                      <h5 class="panel-title"><span class="glyphicon glyphicon-search"></span> Filtro avançado</h5>
                  </a>
              </div>
              <div id="collapseOne" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                  <div class="panel-body">
                      <div class="row" id="filtrosAvancados_campos">
                        <div class="col-md-2">
                            <label>Data Nascimento</label><br>
                            de: <input type="date" min="00001-01-01" max="9999-12-31" class="form form-control input-sm filtroAvancado" label="Nascimento de" style="width:100%" name="dtNascimentoDe" value="{{$dtNascimentoDe}}">
                            Até: <input type="date" min="00001-01-01" max="9999-12-31" class="form form-control input-sm filtroAvancado" label="Nascimento até" style="width:100%" name="dtNascimentoAte" value="{{$dtNascimentoAte}}">
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-md-12 text-right">
                          <button type="submit" class="btn btn-default btn-sm btnFiltrar"><i class="glyphicon glyphicon-search"></i> Filtrar</button>
                        </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <div class="row" id="app">
      <div class="col-md-12">
          <div class="panel panel-default">
              <div class="panel-heading">
                  Listagem de usuários
              </div>
              <div class="panel-body">
                  <table id="tbUsuarios" class="table table-striped table-bordered" style="width:100%;display:none;">
                      <thead>
                        <tr>
                            <th style="width:1%;" class="no-sort">
                              @can('post_usuarios')
                                  <a onclick="loadingElement(this);openRoute('{{route('cadastros.principais.usuarios.create')}}')"class="btn btn-default btn-sm"><span class="glyphicon glyphicon-plus"></span> Cadastrar</a>
                              @endcan
                            </th>
                            <th style="width:20%;">Nome</th>
                            <th style="width:20%;">Email</th>
                            <th style="width:5%;">Nascimento</th>
                            <th style="width:1%;" class="no-sort"></th>
                        </tr>
                          <thead>
                              <tr>
                                  <td style="width:1%;"></td>
                                  <th  style="width:20%">
                                      <input type="text" class="form form-control input-sm" style="width:100%" name="nome" value="{{$nome}}">
                                  </th>
                                  <th style="width:20%">
                                      <input type="text" class="form form-control input-sm" style="width:100%" name="email" value="{{$email}}">
                                  </th>
                                  <th style="width:5%"></th>
                                  <th style="width:1%">
                                      <button type="submit" class="btn btn-default btn-sm btnFiltrar" style="width:100%"><i class="glyphicon glyphicon-search"></i> Filtrar</button>
                                  </th>
                              </tr>
                          </thead>
                      <tbody>
                      @foreach($data as $d)
                        <tr>
                            <td class="text-center" width="1%">
                              <div class="dropdown">
                                <a class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" style="font-size:11px;" aria-haspopup="true" aria-expanded="true">
                                  <span class="glyphicon glyphicon-cog"></span> Ações <span class="caret"></span>
                                </a>    
                                <ul class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenu1">
                                <li>
                                    @can("get_usuarios")
                                            <a class="BtnView" onclick="openRoute('{{route('cadastros.principais.usuarios.show',['id'=>$d->id])}}')">Visualizar</a>
                                        @endcan
                                    </li>
                                    @if(!$d->root)
                                      <li>
                                          @can("put_usuarios")
                                              <a class="BtnEdit"  onclick="openRoute('{{route('cadastros.principais.usuarios.edit',['id'=>$d->id])}}')">Editar</a>
                                          @endcan
                                      </li>
                                      <li role="separator" class="divider"></li>
                                      <li>
                                          @can("delete_usuarios")
                                              <a class="BtnDestroy"  v-on:click="excluir('{{$d->id}}')" style="color:red;">Excluir</a>
                                          @endcan
                                      </li>
                                    @endif
                                </ul>
                              </div>
                            </td>
                            <td>{{$d->nome}} @if($d->root==1) <span class="label label-success">root</span>@endif</td>
                            <td>{{$d->email}}</td>
                            <td class="text-center">{{date('d/m/Y', strtotime($d->dtNascimento))}}</td>
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
        messageBox.confirm("Confirmação","Deseja excluir este usuário?",function()
        {
          vform.ajax('delete', '{{route("cadastros.principais.usuarios.delete")}}',{id},function(response)
          {
              if(!response.success)
                  return toastr.error(response.message);
              openRoute("{{route('cadastros.principais.usuarios')}}");
          });
        });
    }
  }
});
var dtable = new dataTableCrud({
    order : 1,
    table : "#tbUsuarios",
    onload : function()
    {
        @cannot("get_usuarios")
            $(".BtnView").remove();
        @endcannot
        @cannot("put_usuarios")
            $(".BtnEdit").remove();
        @endcannot
        @cannot("delete_usuarios")
            $(".BtnDestroy").remove();
        @endcannot
    }
});
</script>
@stop