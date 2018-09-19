@extends('templates.admin')
@section('title', 'Cadastro de empresas')
@section('content')
<ul class="breadcrumb" style="margin-bottom:0px;">
  <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
  <li class="active">Cadastros <span class="divider"></span></li>
  <li class="active">Principais <span class="divider"></span></li>
  <li class="active"><a href="{{route('cadastros.principais.empresas')}}">Empresas</a><span class="divider"></span></li>
  <li class="active"><strong>Cadastro de empresa</strong><span class="divider"></span></li>
</ul>

<div style="margin-top:15px;" id="app">

  <div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        Preencha o formulário <strong>corretamente</strong> e clique em salvar a abaixo.
  </div> 

  <div class="panel panel-default">
      <div class="panel-heading">
          Cadastro de empresa
      </div>
      <div class="panel-body">


            <form id="frm" data-parsley-validate="" novalidate="" v-on:submit.prevent="cadastrar()">
              <div class="row">
                <div class="col-md-2" style="margin-bottom:15px;">
                  <label><span class="text-danger">*</span> CNPJ</label>
                  <input type="text" class="form-control form-control-sm" autofocus="" v-mask="'##.###.###/####-##'"
                    maxlength="50" placeholder="CNPJ"
                    required v-model="frm.cnpj" parsley-trigger="change"
                    data-parsley-required-message="Este é um campo obrigatório"
                    data-parsley-maxlength-message="Quantidade de caracteres máximo excedida">
                </div>
                <div class="col-md-5" style="margin-bottom:15px;">
                  <label><span class="text-danger">*</span> Nome</label>
                  <input type="text" class="form-control form-control-sm" autofocus=""
                    maxlength="250" placeholder="Nome"
                    required v-model="frm.nome" parsley-trigger="change"
                    data-parsley-required-message="Este é um campo obrigatório"
                    data-parsley-maxlength-message="Quantidade de caracteres máximo excedida">
                </div>
                <div class="col-md-5" style="margin-bottom:15px;">
                  <label><span class="text-danger">*</span> Razão Social</label>
                  <input type="text" class="form-control form-control-sm" autofocus=""
                    maxlength="250" placeholder="Razão"
                    required v-model="frm.razao" parsley-trigger="change"
                    data-parsley-required-message="Este é um campo obrigatório"
                    data-parsley-maxlength-message="Quantidade de caracteres máximo excedida">
                </div>
              </div>
              <div class="row">
                <div class="col-md-5" style="margin-bottom:15px;">
                  <label><span class="text-danger">*</span> Cidade</label>
                  <input type="text" class="form-control form-control-sm" autofocus=""
                    maxlength="100" placeholder="Cidade"
                    required v-model="frm.cidade" parsley-trigger="change"
                    data-parsley-required-message="Este é um campo obrigatório"
                    data-parsley-maxlength-message="Quantidade de caracteres máximo excedida">
                </div>
              </div>

              <hr>
          
              <div class="row">
                <div class="col-md-12 text-right">
                  <button type="submit" class="btn btn-default"><i class="  glyphicon glyphicon-floppy-disk"></i> Salvar</button>
                </div>
              </div>

            </form>

      </div>
  </div>
  
</div>


<script>

var app = new Vue( 
{
  el: '#app',
  data:{
      frm: 
      {
          cnpj: "",
          nome: "",
          razao: "",
          cidade: ""
      }
  },
  methods: 
  {
      cadastrar: function()
      {
        if (!$('#frm').parsley().isValid())
            return false;
        vform.submit('post', '{{route("cadastros.principais.empresas.store")}}', this.frm);
      }
  }
});   
</script>

@stop