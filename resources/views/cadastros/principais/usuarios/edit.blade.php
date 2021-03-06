@extends('templates.admin')
@section('title', 'Edição de usuário')
@section('content')
<ul class="breadcrumb" style="margin-bottom:0px;">
  <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
  <li class="active">Cadastros <span class="divider"></span></li>
  <li class="active">Principais <span class="divider"></span></li>
  <li class="active"><a href="{{route('cadastros.principais.usuarios')}}">Usuários</a><span class="divider"></span></li>
  <li class="active"><strong>Edição de usuário ( #{{$usuario->id}} )</strong><span class="divider"></span></li>
</ul>

<div style="margin-top:15px;" id="app">
<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  Você está em modo de  <strong>edição,</strong> após efetuar as alterações clique em salvar a abaixo.
              </div> 

    <div class="panel panel-default">
        <div class="panel-heading">
            Edição de usuário ( #{{$usuario->id}} )
        </div>
        <div class="panel-body">

              
             <form id="frm" data-parsley-validate="" novalidate="" v-on:submit.prevent="editar()">

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
                  @can("put_emailSenha")
                    <div class="col-md-6" style="margin-bottom:15px;">
                        <label><span class="text-danger">*</span> Email</label>
                        <input type="email" class="form-control form-control-sm"
                        maxlength="250" placeholder="Email"
                        required v-model="frm.email" parsley-trigger="change"
                        data-parsley-required-message="Este é um campo obrigatório"
                        data-parsley-maxlength-message="Quantidade de caracteres máximo excedida"
                        data-parsley-type-message="Este campo deve ser um email válido">
                    </div>
                  @endcan
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
                  @can("put_emailSenha")
                    <div class="col-md-4" style="margin-bottom:15px;">
                        <label>Senha</label>
                        <input type="password" class="form-control form-control-sm"
                        maxlength="20" id="senha" placeholder="Senha"
                        v-model="frm.senha" parsley-trigger="change"
                        data-parsley-required-message="Este é um campo obrigatório"
                        data-parsley-maxlength-message="Quantidade de caracteres máximo excedida">
                    </div>
                    <div class="col-md-4" style="margin-bottom:15px;">
                        <label>Confirme a senha</label>      
                        <input type="password" class="form-control form-control-sm" id="_senha"
                        maxlength="20" placeholder="Confirme a senha"
                        v-model="senha" parsley-trigger="change"
                        data-parsley-required-message="Este é um campo obrigatório"
                        data-parsley-maxlength-message="Quantidade de caracteres máximo excedida">
                    </div>
                  @endcan
                </div>
                <div class="row">
                  <div class="col-md-4" style="margin-bottom:15px;">
                    <label><span class="text-danger">*</span> Empresa</label>
                    <select class="form form-control selectpicker" multiple required parsley-trigger="change" id="polos"
                      data-parsley-required-message="Este é um campo obrigatório">
                        @foreach($tenants as $tenant)
                          <?php $selected = (count(DB::table("tenantsUsuarios")->where("usuarioId","=",$usuario->id)->where("tenantId","=",$tenant->id)->get())>0);  ?>
                          <option @if($selected) selected @endif value="{{$tenant->id}}">{{$tenant->nome}}</option>
                        @endforeach
                    </select>
                  </div>
                </div>

                @if(!$usuario->root)
                  <hr>
                  <div class="row">
                    <div class="col-md-12 text-right">
                      <button type="submit" id="btnSubmit" class="btn btn-default"><i class="glyphicon glyphicon-floppy-disk"></i> Salvar</button>
                    </div>
                  </div>
                @endif
                

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
          nome: "{{$usuario->nome}}",
          email: "{{$usuario->email}}",
          grupoAcessoId : "{{$usuario->grupoAcessoId}}",
          dtNascimento : "{{$usuario->dtNascimento}}",
          senha : "",
          tenantId : ""
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
      editar: function()
      {
        if (!$('#frm').parsley().isValid())
            return false;
          loadingElement($("#btnSubmit"));
          if(!this.confirmaSenha())
          {
            addError('#_senha',"Senhas não conferem");
            $("#btnSubmit").button("reset");
            return addError('#senha',"Senhas não conferem");
          }
          this.frm.tenantid = $("#polos").selectpicker('val');
          vform.submit('put', '{{route("cadastros.principais.usuarios.edit",["id"=>$usuario->id])}}', this.frm);
      }
  }
});  
</script>

@stop