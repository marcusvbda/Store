@extends('templates.admin')
@section('title', 'Configuração de email')
@section('content')
<ul class="breadcrumb" style="margin-bottom:0px;">
  <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
  <li class="active"><strong>Configuração de email</strong></li>  
</ul>
<br>
<div id="app">

     <div class="panel panel-default">
      <div class="panel-heading">
          Configuração de email
      </div>
      <div class="panel-body">
          <form id="frmPrincipal" data-parsley-validate="" novalidate="" v-on:submit.prevent="submeter()">
            <div class="row">
              <div class="col-md-4" style="margin-bottom: 10px;">
                <label><span class="text-danger">*</span> Email</label>
                <input :disabled="!editando" type="text" maxlength="450" class="form form-control" v-on:change="solicitarTeste()"
                  required v-model="parametro.email" placeholder="email@email.com" parsley-trigger="change"
                  data-parsley-required-message="Este é um campo obrigatório"
                  data-parsley-maxlength-message="Quantidade de caracteres máximo excedido"
                  data-parsley-type-message="Este campo deve ser um email válido">
              </div>
              <div class="col-md-4" style="margin-bottom: 10px;">
                <label><span class="text-danger">*</span> Servidor</label>
                <input :disabled="!editando" type="text" maxlength="450" class="form form-control"  v-on:change="solicitarTeste()"
                  required v-model="parametro.servidor" placeholder="smpt.seu_email.com" parsley-trigger="change"
                  data-parsley-required-message="Este é um campo obrigatório"
                  data-parsley-maxlength-message="Quantidade de caracteres máximo excedido">
              </div>
              <div class="col-md-4" style="margin-bottom: 10px;">
                <label><span class="text-danger">*</span> Senha</label>
                <input :disabled="!editando" type="password" maxlength="450" class="form form-control"  v-on:change="solicitarTeste()"
                  required v-model="parametro.senha" placeholder="************" parsley-trigger="change"
                  data-parsley-required-message="Este é um campo obrigatório"
                  data-parsley-maxlength-message="Quantidade de caracteres máximo excedido">
              </div>
            </div>
            <div class="row">
              <div class="col-md-2" style="margin-bottom: 10px;">
                <label><span class="text-danger">*</span> Porta</label>
                <input :disabled="!editando" type="text" maxlength="450" class="form form-control" v-on:change="solicitarTeste()" 
                  required v-model="parametro.porta" placeholder="000" parsley-trigger="change"
                  data-parsley-required-message="Este é um campo obrigatório"
                  data-parsley-maxlength-message="Quantidade de caracteres máximo excedido">
              </div>
              <div class="col-md-3" style="margin-bottom: 10px;">
                <label><span class="text-danger">*</span> Criptografia</label>
                <select :disabled="!editando" class="form form-control"  v-on:change="solicitarTeste()"
                  required v-model="parametro.criptografia" placeholder="000" parsley-trigger="change"
                  data-parsley-required-message="Este é um campo obrigatório">
                    <option value="ssl">SSL</option>
                    <option value="tls">TLS</option>
                    <option value="starttls">STARTTLS</option>
                </select>
              </div>
              <div class="col-md-3" style="margin-bottom: 10px;">
                <label><span class="text-danger">*</span> Driver</label>
                <select :disabled="!editando" class="form form-control"  v-on:change="solicitarTeste()"
                  required v-model="parametro.driver" placeholder="000" parsley-trigger="change"
                  data-parsley-required-message="Este é um campo obrigatório">
                    <option value="smtp">SMTP</option>
                    <option value="imap">IMAP</option>
                </select>
              </div>
            </div>
            <hr>
            <div class="row">
              @can("get_config_email")
                <div class="col-md-12 text-right">
                  <a onclick="javascript:location.reload()" v-show="editando" class="btn btn-danger"><i class=" glyphicon glyphicon-remove"></i> Cancelar</a>
                  
                  <button type="submit" hidden v-show="((editando) && (parametro.testado==1))" class="btn btn-default"
                      id="btnSubmit" data-loading-text="<span class='glyphicon glyphicon-repeat normal-right-spinner'></span> Processando..."> 
                      <i class="glyphicon glyphicon-exclamation-sign"></i> Salvar
                  </button>

                  <button type="button" v-on:click="testar('#btnTestar')" v-show="((editando) && (parametro.testado==0))" class="btn btn-default" 
                      id="btnTestar" data-loading-text="<span class='glyphicon glyphicon-repeat normal-right-spinner'></span> Processando..."> 
                      <i class="glyphicon glyphicon-exclamation-sign"></i> Testar
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
        parametro :
        {
          id : "{{$email->id}}",
          email : "{{$email->email}}",
          servidor : "{{$email->servidor}}",
          senha : "{{$email->senha}}",
          porta : "{{$email->porta}}",
          criptografia : "{{$email->criptografia}}",
          driver : "{{$email->driver}}",
          testado : "{{$email->testado}}",
        },
        editando: false,
    },
    methods: 
    {
      testar :function(button)
      {
        var self = this;
        var $button = $(button);
        $button.button('loading');
        vform.ajax("put",'{{route("parametros.email.testar")}}',this.parametro,function(response)
        {
            if(!response.success)
            {
                self.parametro.testado = 0;
                $button.button('reset');
                return toastr.error(response.message);
            }
            self.parametro.testado = 1;
            toastr.success("Teste efetuado com sucesso !!");
            return $button.button('reset');
        });
      },
      solicitarTeste : function()
      {
        this.parametro.testado = 0;
      },
      submeter : function()
      {
          if (!$('#frmPrincipal').parsley().isValid())
              return false;
          if(this.parametro.testado == 0)
              return this.testar("#btnTestar");
          $("#btnSubmit").button("loading");
          vloading.start();
          vform.submit("put", "{{route('parametros.email.put')}}", this.parametro);
      }
    }
}); 
</script>
@stop