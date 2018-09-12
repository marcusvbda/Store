@extends('templates.admin')
@section('title', 'Cadastro de usuários')
@section('content')
<ul class="breadcrumb" style="margin-bottom:0px;">
  <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
  <li class="active">Cadastros <span class="divider"></span></li>
  <li class="active">Principais <span class="divider"></span></li>
  <li class="active"><a href="{{route('cadastros.principais.usuarios')}}">Usuários</a><span class="divider"></span></li>
  <li class="active"><strong>Cadastro de usuário</strong><span class="divider"></span></li>
</ul>

<div style="margin-top:15px;" id="app">


  <div class="panel panel-default">
      <div class="panel-heading">
          Cadastro de usuários
      </div>
      <div class="panel-body">


          <form id="frm" data-parsley-validate="" novalidate="" v-on:submit.prevent="cadastrar()">

            <div class="row">
              <div class="col-md-4" style="margin-bottom:15px;">
                <label><span class="text-danger">*</span> Nome</label>
                <input type="text" class="form-control form-control-sm" autofocus=""
                  maxlength="250" placeholder="Nome"
                  required v-model="frm.nome" parsley-trigger="change"
                  data-parsley-required-message="Este é um campo obrigatório"
                  data-parsley-maxlength-message="Quantidade de caracteres máximo excedida">
              </div>
              <div class="col-md-2" style="margin-bottom:15px;">
                <label>Data nascimento</label>
                <input type="date" min="00001-01-01" max="9999-12-31" class="form-control form-control-sm"  placeholder="Data Nascimento" v-model="frm.dtNascimento">
              </div>
              <div class="col-md-6" style="margin-bottom:15px;">
                <label><span class="text-danger">*</span> Email</label>
                <input type="email" class="form-control form-control-sm"
                  maxlength="250" placeholder="Email"
                  required v-model="frm.email" parsley-trigger="change"
                  data-parsley-required-message="Este é um campo obrigatório"
                  data-parsley-maxlength-message="Quantidade de caracteres máximo excedida"
                  data-parsley-type-message="Este campo deve ser um email válido">
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-4" style="margin-bottom:15px;">
                <label><span class="text-danger">*</span> Grupo de acesso</label>
                <select class="form-control form-control-sm"
                  required v-model="frm.grupoAcessoId" parsley-trigger="change"
                  data-parsley-required-message="Este é um campo obrigatório">
                    @foreach($gruposAcesso as $grupo)
                      <option value="{{$grupo->id}}">{{$grupo->nome}}</option>
                    @endforeach
                </select>
              </div>
              <div class="col-md-4" style="margin-bottom:15px;">
                <label><span class="text-danger">*</span> Senha</label>
                <input type="password" class="form-control form-control-sm"
                  maxlength="20" id="senha" placeholder="Senha"
                  required v-model="frm.senha" parsley-trigger="change"
                  data-parsley-required-message="Este é um campo obrigatório"
                  data-parsley-maxlength-message="Quantidade de caracteres máximo excedida">
              </div>
              <div class="col-md-4" style="margin-bottom:15px;">
                <label><span class="text-danger">*</span> Confirme a senha</label>      
                <input type="password" class="form-control form-control-sm" id="_senha"
                  maxlength="20" placeholder="Confirme a senha"
                  required v-model="senha" parsley-trigger="change"
                  data-parsley-required-message="Este é um campo obrigatório"
                  data-parsley-maxlength-message="Quantidade de caracteres máximo excedida">
              </div>
            </div>
            <div class="row">
              <div class="col-md-3" style="margin-bottom:15px;">
                  <label><span class="text-danger">*</span> Polo</label>
                  <select class="form form-control selectpicker" multiple required v-model="frm.tenantId" parsley-trigger="change"
                    data-parsley-required-message="Este é um campo obrigatório">
                      @foreach($tenants as $tenant)
                        <option value="{{$tenant->id}}">{{$tenant->nome}}</option>
                      @endforeach
                  </select>
              </div>
              <div class="col-md-3" style="margin-bottom:15px;">
                  <label><span class="text-danger">*</span> Operador Follow</label>
                  <select class="form form-control"  required v-model="frm.operadorFollow" parsley-trigger="change"
                    data-parsley-required-message="Este é um campo obrigatório">
                        <option value="1">Sim</option>
                        <option value="0">Não</option>
                  </select>
              </div>
              <div class="col-md-3" style="margin-bottom:15px;">
                  <label><span class="text-danger">*</span> Captador</label>
                  <select class="form form-control"  required v-model="frm.captador" parsley-trigger="change"
                    data-parsley-required-message="Este é um campo obrigatório">
                        <option value="1">Sim</option>
                        <option value="0">Não</option>
                  </select>
              </div>
              <div class="col-md-3" style="margin-bottom:15px;">
                  <label><span class="text-danger">*</span> Trocar senha no primeiro login</label>
                  <select class="form form-control"  required v-model="frm.mudarSenha" parsley-trigger="change"
                    data-parsley-required-message="Este é um campo obrigatório">
                        <option value="1">Sim</option>
                        <option value="0">Não</option>
                  </select>
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

var app = new Vue( {
el: '#app',
  data:{
      senha: "",
      frm: {
          nome: "",
          email: "",
          grupoAcessoId : "",
          senha : "",
          dtNascimento : "",
          tenantId : "",
          operadorFollow : 1,
          captador : 0,
          mudarSenha : 0
      }
  },
  methods: {
      confirmaSenha : function()
      {
        if(this.frm.senha == this.senha)
          return true;
        else
          return false;
      },
      cadastrar: function()
      {
        if (!$('#frm').parsley().isValid())
            return false;
        if(!this.confirmaSenha())
        {
          addError('#_senha',"Senhas não conferem");
          return addError('#senha',"Senhas não conferem");
        }
        vform.submit('post', '{{route("cadastros.principais.usuarios.store")}}', this.frm);
      }
  }
});   
</script>

@stop