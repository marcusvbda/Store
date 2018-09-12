@extends('templates.default')
@section('title', 'Login')
@section('body')

<body>
<link href="{{asset('public/css/sigin.css')}}" rel="stylesheet">

    <div class="row" id="app" style="margin-right: 10px;margin-left: 10px;">

        <div class="col-md-9"></div>
        <div class="col-md-3 sombraLogin" style="background-color: white;height: 450px;padding-top: 45px;border-radius: 10px;">
            <form class="form-signin" id="frmLogin" v-on:submit.prevent="login()">
                <div class="text-center" style="margin-bottom:10px;">
                    <p style="margin-bottom: 0;">
                        <img src="{{asset('public/img/logo.png')}}" width="100px;" style="margin-bottom: 0;">
                        <h1 style="margin:0;" class="text-center"><small>ezCore</small>{{env('APP_NAME')}}</h1>
                        <h3 style="margin:0;" class="text-center"><small>{{env('VERSAO')}}</small></h3>
                    </p>
                </div>
                <label class="sr-only">Email</label>
                <input type="email" maxlength="250" placeholder="Email" class="form-control" placeholder="Email"  autofocus=""
                    name="email" required="" v-model="formLogin.email">
                <label  class="sr-only">Senha</label>
                <input type="password" required="" maxlength="30" v-model="formLogin.senha" placeholder="Senha"  class="form-control">
                <div class="row" style="margin-bottom: 15px;">
                    <div class="col-md-6 text-left" style="padding:0;padding-left:15px;">
                        <label>
                            <input type="checkbox" name="remember" v-model="formLogin.manter_logado"> Manter logado
                        </label>
                    </div>
                    <div class="col-md-6 text-right" style="padding:0;padding-right:15px;"> 
                        <a v-on:click="esqueciSenha()">Esqueci a senha</a>
                    </div>
                </div>
                <button type="submit" class="btn btn-lg btn-primary btn-block" id="btnLogin" 
                    data-loading-text="<span class='glyphicon glyphicon-repeat normal-right-spinner'></span> Processando..."> 
                    Logar
                </button>

            </form>
        </div>


        <form  id="frmMudarSenha" v-on:submit.prevent="mudarSenha()" style="display: none;">
            <label class="sr-only">Nova Senha</label>
            <input type="password" maxlength="30"  class="form-control" placeholder="Senha"  autofocus=""
                name="novaSenha" required="" v-model="formLogin.novaSenha">
            <label  class="sr-only">Repita a senha</label>
            <input type="password" required="" maxlength="30" v-model="formLogin.repitaSenha" id="txtSenha" placeholder="Repita a senha"  class="form-control">
            <br>
            <button type="submit" class="btn btn-lg btn-primary btn-block" id="btnMudarSenha" 
                data-loading-text="<span class='glyphicon glyphicon-repeat normal-right-spinner'></span> Processando..."> 
                Mudar a senha
            </button>
        </form>


    </div> <!-- /container -->

</body>




<div id="frm_selecaoEmpresa"  style="display:none">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-table">
                <div class="card-body">
                    <div>
                      <table id="tb_selecao_empresa" class="table table-striped table-bordered hoverable" style="display:none;width:100%;">
                          <thead>
                            <tr>
                                <th class="text-center" width="1%">#</th>
                                <th collumn="razao">Razão</th>
                                <th collumn="nome">Nome</th>
                                <th collumn="cnpj">CNPJ</th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                      </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

  


</div>



<script>

var dtable = new dataTableCrud({
    table : "#tb_selecao_empresa",
    perpage : 10,
    size : "70%"
});
$(document).on("click","#tb_selecao_empresa tbody tr",function()
{
    return app.entrar($(this)[0].cells[0].innerText);
});
var app = new Vue( 
{
    el: '#app',
    data:
    {
        formLogin: 
        {
            email: @if(isset($_GET['email'])) "{{$_GET['email']}}" @else null @endif,
            senha: null,
            manter_logado: true,
            tenantId: null,
            novaSenha : null,
            repitaSenha : null,
        }
    },
    methods: 
    {
        esqueciSenha : function()
        {
            if((this.formLogin.email==null)||(this.formLogin.email==""))
                return toastr.error("Digite um email válido");
            vform.ajax('put', '{{route("auth.enviarEmailSenha")}}', this.formLogin ,function(response)
            {
                if(!response.success)
                    return toastr.error(response.message);
                return toastr.success("Email com a nova senha foi enviado em seu email, verifique ...");
            });
        },
        entrar: function(codigo)
        {
            $("#btnLogin").button('loading');
            var dados = { ReturnUrl: "@ViewBag.ReturnUrl", tenantId: codigo, email: this.email, senha: this.senha, manter_logado: this.manter_logado };
            this.formLogin.tenantId = codigo;
            vform.ajax('post', '{{route("logar")}}', this.formLogin,function(response)
            {
                if(!response.success)
                {
                    $("#btnLogin").button('reset');
                    return toastr.error(response.message);
                }
                openRoute("{{route('dashboard')}}");
            });
        },
        mudarSenha : function()
        {
            $("#btnMudarSenha").button('loading');
            if(this.formLogin.novaSenha!=this.formLogin.repitaSenha)
            {
                $("#btnMudarSenha").button('reset');
                return toastr.error("Senhas não conferem !!!");
            }
            var self = this;
            // console.log(self.formLogin);
            vform.ajax("put", "{{route('auth.resetPassword')}}", self.formLogin, function (response) 
            {
                if(!response.success)
                {
                    $("#btnMudarSenha").button('reset');
                    return toastr.error(response.message);
                }
                $("#frmMudarSenha").dialog('close');
                self.formLogin.senha = null;
                self.formLogin.novaSenha = null;
                self.formLogin.repitaSenha = null;
                toastr.success("Senha alterada com sucesso, efetue o login para começar.");
                return $("#txtSenha").focus();
            });
        },
        login: function()
        {
            var self = this;
            $("#btnLogin").button('loading');
            vform.ajax("post", "{{route('getTenants')}}", this.formLogin , function (response) 
            {
                if(!response.success)
                {
                    $("#btnLogin").button('reset');
                    return toastr.error(response.message);
                }
                else
                {
                    if(response.mudarSenha)
                    {
                        $("#btnLogin").button('reset');
                        self.formLogin.novaSenha = null;
                        self.formLogin.repitaSenha = null;
                        return  modal("#frmMudarSenha", "Mudar a senha", "180px", {});
                    }
                    var tenants = response.data;
                    dtable.rows().clear();
                    if(tenants.length > 1)
                    {
                        for (var i = 0; i < tenants.length; i++) 
                        {
                            dtable.row.add([
                                tenants[i].id,
                                tenants[i].razao,
                                tenants[i].nome,
                                strToCNPJ(tenants[i].cnpj)
                            ]).draw( false );
                        }
                        $("#btnLogin").button('reset');
                        modal("#frm_selecaoEmpresa", "Selecione o polo", "90%", {
                            cancelar:
                            {
                                click: function () 
                                {
                                    $("#frm_selecaoEmpresa").dialog('close');
                                    self.formLogin.tenantId = null;
                                    dtable.rows().clear();
                                },
                                class: 'btn btn-danger',
                                text: 'Cancelar'
                            }
                        });
                    }
                    else
                    {
                        self.entrar(tenants[0].id);
                    }
                }
            });
        }
    }
}); 
</script>
@stop
