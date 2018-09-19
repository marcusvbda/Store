@extends('templates.admin')
@section('title', 'Parametros')
@section('content')
<ul class="breadcrumb" style="margin-bottom:0px;">
  <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
  <li class="active"><strong>Parametros de sistema</strong></li>  
</ul>
<br>
<div id="app">

  <div class="alert alert-warning alert-dismissable" v-show="!editando">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
   Você está em modo de  <strong>visualização,</strong> para fazer alterações clique em <strong>Editar</strong> abaixo.
 </div> 
 <div class="alert alert-info alert-dismissable" v-show="editando">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
   Você está em modo de  <strong>edição,</strong> após concluir clique em <strong>salvar</strong> abaixo.
 </div> 

  <div class="panel panel-default">
      <div class="panel-heading">
          Parametros de sistema
      </div>
      <div class="panel-body">


                    
        <form id="frmPrincipal" data-parsley-validate="" novalidate="" v-on:submit.prevent="submeter()">
          <div class="row">
            @foreach($parametros as $parametro)
              @if(($parametro->type=="text")||($parametro->type=="number"))
                <div class="col-md-{{$parametro->bootstrapCol}}" style="margin-bottom: 10px;">
                  <label>@if($parametro->required)<span class="text-danger">*</span>@endif {{$parametro->label}}</label>
                  <p><small>{{$parametro->descricao}}</small></p>
                  <input :disabled="!editando" type="{{$parametro->type}}" class="form form-control" title="{{$parametro->descricao}}" 
                    @if($parametro->maxlength) maxlength="{{$parametro->maxlength}}" @endif 
                    required="{{$parametro->required}}" 
                    @if($parametro->max) max="{{$parametro->max}}" @endif
                    v-model="parametros.{{$parametro->id}}" 
                    @if($parametro->min) min="{{$parametro->min}}" @endif 
                    @if($parametro->placeholder) placeholder="{{$parametro->placeholder}}" @endif 
                    name="{{$parametro->id}}" id="{{$parametro->id}}" 
                    step="{{$parametro->step}}" parsley-trigger="change"
                    data-parsley-required-message="Este é um campo obrigatório"
                    data-parsley-max-message="Valor máximo excedido"
                    data-parsley-min-message="Valor mínimo excedido"
                    data-parsley-maxlength-message="Quantidade de caracteres máximo excedido"
                    data-parsley-type-message="Este campo deve ser um email válido">
                </div>
              @elseif($parametro->type=="boolean")
                <div class="col-md-{{$parametro->bootstrapCol}}" style="margin-bottom: 10px;">
                  <label>@if($parametro->required)<span class="text-danger">*</span>@endif {{$parametro->label}}</label>
                  <p><small>{{$parametro->descricao}}</small></p>
                  <select class="form form-control" :disabled="!editando" title="{{$parametro->descricao}}" 
                    required="{{$parametro->required}}" @if($parametro->max) max="{{$parametro->max}}" @endif
                    v-model="parametros.{{$parametro->id}}" 
                    @if($parametro->min) min="{{$parametro->min}}" @endif 
                    name="{{$parametro->id}}" id="{{$parametro->id}}" 
                    step="{{$parametro->step}}" parsley-trigger="change"
                    data-parsley-required-message="Este é um campo obrigatório"
                    data-parsley-maxlength-message="Quantidade de caracteres máximo excedido"
                    data-parsley-max-message="Valor máximo excedido"
                    data-parsley-min-message="Valor mínimo excedido"
                    data-parsley-type-message="Este campo deve ser um email válido">
                    <option value="1">SIM</option>
                    <option value="0">NÃO</option>
                  </select>
                </div>
              @endif        
            @endforeach
          </div>
          <hr>
          <div class="row">
            @can("put_parametros")
              <div class="col-md-12 text-right">
                <a onclick="javascript:location.reload()" v-show="editando" class="btn btn-danger"><i class=" glyphicon glyphicon-remove"></i> Cancelar</a>

                <button type="submit" v-on:click="testar()" v-show="editando" class="btn btn-default" 
                    id="btnSubmit" data-loading-text="<span class='glyphicon glyphicon-repeat normal-right-spinner'></span> Processando..."> 
                    <i class="glyphicon glyphicon-floppy-disk"></i> Salvar
                </button>


                <a v-on:click="editando = !editando" v-show="!editando" class="btn btn-default"><i class="glyphicon glyphicon-pencil"></i> Editar</a>
              </div>
            @endcan
          </div>
        </form>

      </div>
  </div>

</div><!-- app -->


<script>
var app = new Vue( 
{
    el: '#app',
    data:
    {
        editando: false,
        parametros: 
        {
          @foreach($parametros as $parametro)
            {{$parametro->id}}: "{{$parametro->valor}}" ,
          @endforeach
        }
    },
    methods: 
    {
      submeter : function()
      {
          if (!$('#frmPrincipal').parsley().isValid())
              return false;
          $("#btnSubmit").button("loading");
          vloading.start();
          vform.submit("put", "{{route('parametros.put')}}", this.parametros);
      }
    }
}); 
</script>
@stop