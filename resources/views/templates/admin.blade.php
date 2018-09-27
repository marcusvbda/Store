@extends('templates.default')
@section('body')
<body style="margin-top:40px;" z-index: 0;>
  <nav class="navbar navbar-inverse navbar-fixed-top" style="background-color: #272C30;border-color: #272C30;">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" onclick="openRoute('{{route('dashboard')}}')" style="padding-top: 5px;" >
              <img src="{{asset('public/img/logo.png')}}" width="40px;">
          </a>
        </div>
        <div id="navbar" class="col-md-6 navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Cadastros <span class="caret"></span></a>
              <ul class="dropdown-menu preventClick'">
                <li class="dropdown-submenu preventClick">
                  <a style="cursor:default;">Auxiliares</a>
                  <ul class="dropdown-menu preventClick">
                    <li class="dropdown-submenu preventClick">
                      <a style="cursor:default;">Modelos</a>
                      <ul class="dropdown-menu preventClick">
                        @can("get_modelos_email")
                          <li><a  onclick="openRoute('{{route('cadastros.auxiliares.emails')}}')" style="cursor:default;">Emails</a></li>
                        @endcan
                        <!-- <li role="separator" class="divider"></li> -->
                      </ul>
                    </li>
                  </ul>
                </li>
                <li class="dropdown-submenu preventClick">
                  <a>Principais</a>
                  <ul class="dropdown-menu preventClick">
                    @can("get_tenants")
                      <li><a onclick="openRoute('{{route('cadastros.principais.empresas')}}')" style="cursor:default;">Empresas</a></li>
                    @endcan
                    <li class="dropdown-submenu preventClick ">
                      <a style="cursor:default;">Usuários</a>
                      <ul class="dropdown-menu preventClick">
                        @can("get_gruposAcesso")
                          <li><a onclick="openRoute('{{route('cadastros.principais.usuarios.gruposAcesso')}}')" style="cursor:default;">Grupos de Acesso</a></li>
                        @endcan
                        @can("get_usuarios")
                          <li><a onclick="openRoute('{{route('cadastros.principais.usuarios')}}')" style="cursor:default;">Usuários</a></li>
                        @endcan
                        <!-- <li role="separator" class="divider"></li> -->
                      </ul>
                    </li>
                    <li class="dropdown-submenu preventClick ">
                      <a style="cursor:default;">Produtos</a>
                      <ul class="dropdown-menu preventClick">
                        @can("get_produtos")
                          <li><a onclick="openRoute('{{route('cadastros.principais.produtos')}}')" style="cursor:default;">Produtos e SKUs</a></li>
                        @endcan
                        @can("get_especificacaoProduto")
                          <li><a onclick="openRoute('{{route('cadastros.principais.produtos.sku.especificacoes')}}')" style="cursor:default;">Especificações de SKU</a></li>
                        @endcan
                        @can("get_marcas")
                          <li><a onclick="openRoute('{{route('cadastros.principais.produtos.marcas')}}')" style="cursor:default;">Marcas</a></li>
                        @endcan
                        @can("get_categoriaProduto")
                          <li><a onclick="openRoute('{{route('cadastros.principais.produtos.categorias')}}')" style="cursor:default;">Categorias</a></li>
                        @endcan
                      </ul>
                    </li>
                  </ul>
                </li>
              </ul>
            </li>

            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Utilitários <span class="caret"></span></a>
              <ul class="dropdown-menu preventClick'">
                <li class="dropdown-submenu preventClick">
                  <a style="cursor:default;">CMS</a>
                  <ul class="dropdown-menu preventClick">
                    <li class="dropdown-submenu preventClick">
                      @can("get_campoPersonalizado")
                        <li><a  onclick="openRoute('{{route('utilitarios.cms.camposPersonalizados')}}')" style="cursor:default;">Campos personalizados</a></li>
                      @endcan
                    </li>
                  </ul>
                </li>
              </ul>
            </li>

             <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="cursor:default;">Relatórios <span class="caret"></span></a>
              <ul class="dropdown-menu preventClick'">
                <?php  $relatoriosCustomizados = []; ?>
                @if(count($relatoriosCustomizados)>0)
                  <li class="dropdown-submenu preventClick">
                    <a>Customizados</a>
                    <ul class="dropdown-menu preventClick">
                        @foreach($relatoriosCustomizados as $relatorio)
                          @can($relatorio->permissao)
                            <li><a onclick="openRoute('{{asset('admin/relatorios/padrao')}}/{{$relatorio->id}}')" style="cursor:default;">{{$relatorio->nome}}</a></li>
                          @endcan
                        @endforeach
                    </ul>
                  </li>
                @endif
                <?php  $categoriasRelatorio = DB::table('categoriasRelatorio')->get(); ?>
                @foreach($categoriasRelatorio as $categoria)
                  <li class="dropdown-submenu preventClick">
                    <a>{{$categoria->nome}}</a>
                    <ul class="dropdown-menu preventClick">
                      <?php  $relatorios = DB::table('relatorios')->where("categoriaId","=",$categoria->id)->get(); ?>
                      @foreach($relatorios as $relatorio)
                        @can($relatorio->permissao)
                          <li><a onclick="openRoute('{{asset('admin/relatorios/padrao')}}/{{$relatorio->id}}')" style="cursor:default;">{{$relatorio->nome}}</a></li>
                        @endcan
                      @endforeach
                    </ul>
                  </li>
                @endforeach
              </ul>
            </li>

            <li class="dropdown" style="display: none;">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="cursor:default;">Gráficos<span class="caret"></span></a>
              <ul class="dropdown-menu preventClick'">
                  <!--  -->
              </ul>
            </li>

          </ul>
        </div><!--/.nav-collapse -->
        <div id="navbar" class="col-md-6 navbar-collapse collapse pull-right">
          <ul class="nav navbar-nav">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> {{Auth::user()->email}} ({{Auth::user()->tenant->nome}}) <span class="caret"></span></a>
              <ul class="dropdown-menu preventClick">
                @can("get_parametros")
                  <li><a onclick="openRoute('{{route('parametros')}}')" style="cursor:default;">Parametros</a></li>        
                @endcan   
                @can("get_config_email")
                  <li><a onclick="openRoute('{{route('parametros.email')}}')" style="cursor:default;">Configuração de Email</a></li>        
                @endcan 
                <li><a onclick="openRoute('{{route('sobre')}}')" style="cursor:default;">Sobre</a></li>
                @if(Auth::user()->root==1)
                  <li role="separator" class="divider"></li> 
                   <li><a onclick="sqlEditor.masterPass()" style="cursor:default;">SQL editor</a></li>
                  <li role="separator" class="divider"></li> 
                @endif
                @can("get_usuarios")
                  <li><a onclick="openRoute('{{route('cadastros.principais.usuarios.show',['id'=>Auth::user()->id])}}')" style="cursor:default;">Profile</a></li>
                @endcan
                <li role="separator" class="divider"></li> 
                <li><a onclick="logout()">Sair</a></li>
              </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
    </nav>
    <div class="row" style="padding-left:10px;padding-right:10px;">
      <div class="col-md-12">
        @yield('content')
      </div>
    </div>
    <footer class="footer">
      <div class="container text-right" style="margin-right:0px;">
        <p class="text-muted"><strong><small>ezCore</small>{{env('APP_NAME')}}</strong><small> - Versão:{{env('VERSAO')}}</small></p>
      </div>
    </footer>
</body>

<div style="overflow: none;display: none;" id="frmSQLEditor">
  <div class="row" style="padding: 5px;">
    <div class="col-12">
      <textarea class="form form-control" rows=3 style="resize: none;" id="__sqlEditorQuery"></textarea>
    </div>
  </div>
  <div class="row" style="padding: 5px;">
    <div class="col-12 text-right">
      <button class="btn btn-primary" onclick="sqlEditor.executar()">Executar</button>
    </div>
  </div>
  <div class="row" style="padding: 5px;">
    <div class="col-12" style="height: 200px;min-height: 200px;max-height: 200px;overflow: scroll;">
        <table class="table table-striped" style="width: 100%">
          <thead id="__sqlEditorQueryResultHeader">
          </thead>
          <tbody id="__sqlEditorResultBody"></tbody>
        </table>
    </div>
  </div>

</div>


<div id="modalMasterPassSqlEditor" style="display: none;overflow: none;" >
  <div class="row" style="padding: 5px;">
    <div class="col-12" style="margin-bottom: 10px;">
      <input id="senhaMasterTxt" type="password" class="form form-control" autofocus="">
    </div>
  </div>
  <div class="row" style="padding: 5px;">
    <div class="col-12">
      <button class="btn btn-primary btn-block" onclick="sqlEditor.abrir()">Validar</button>
    </div>
  </div>
</div>


<script>
  $('.preventClick').click(function(event){
      event.stopPropagation();
  });
  function logout()
  {
      messageBox.confirm("Confirmação","Confirma saida do sistema ?",function()
      {
        openRoute("{{route('login')}}")
      });
  }

  @if(Auth::user()->root==1)
    var sqlEditor = 
    {
        masterPass : function()
        {
            $("#modalMasterPassSqlEditor").dialog({
              modal: true,
              title: "Senha mestre",
              zIndex: 10000,
              autoOpen: true,
              width: "180px",
              resizable: false,
              draggable: true,
              open: function(event, ui) 
              {
                  $(".ui-dialog-titlebar-close", ui.dialog | ui).html("X");
              },
              buttons : {
              }
            });
        },
        abrir : function()
        {
            var pass = $("#senhaMasterTxt").val();
            $("#senhaMasterTxt").val(null);
            $("#__sqlEditorQueryResultHeader").html(null);
            $("#__sqlEditorResultBody").html(null);
            $("#__sqlEditorQuery").val(null);
            $(".ui-dialog-titlebar-close").click();
            vform.ajax("post", "{{route('default.checkMasterPass')}}", {pass} , function (response) 
            {
              if(!response.success)
                return toastr.error(response.message);
                $("#frmSQLEditor").dialog({
                  modal: true,
                  title: "SQL Editor",
                  zIndex: 10000,
                  autoOpen: true,
                  width: "800px",
                  resizable: false,
                  draggable: true,
                  open: function(event, ui) 
                  {
                      $(".ui-dialog-titlebar-close", ui.dialog | ui).html("X");
                  },
                  buttons : {

                  }
                });
            });
        },
        executar : function()
        {
            var query = $("#__sqlEditorQuery").val();
            vform.ajax("put", "{{route('default.rawQuery')}}", {query} , function (response) 
            {
                if(!response.success)
                {
                  $("#__sqlEditorQueryResultHeader").html("<tr><th></th></tr>");
                  $("#__sqlEditorResultBody").html("<tr><td>Erro ao executar</td></tr>");
                  return false;
                }
                if((response.header.length<=0)&&(response.data.length<=0))
                {
                  $("#__sqlEditorQueryResultHeader").html("<tr><th></th></tr>");
                  $("#__sqlEditorResultBody").html("<tr><td>Executado com sucesso</td></tr>");
                }
                else
                {
                  var header ="<tr>";
                  for(var i=0;i<response.header.length;i++)
                  {
                      header+="<th>"+response.header[i]+"</th>";
                  }
                  header+="</tr>";
                  $("#__sqlEditorQueryResultHeader").html(header);

                  var body = "";
                  for(var i=0;i<response.data.length;i++)
                  {
                      body +="<tr>"
                      $.each(response.data[i],function(key,value)
                      {
                          body += "<td>"+value+"</td>";
                      });
                      body+="</tr>";
                  }
                  $("#__sqlEditorResultBody").html(body);
                }
            });
        }
    };
  @endif

</script>

@stop