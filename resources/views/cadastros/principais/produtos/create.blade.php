@extends('templates.admin')
@section('title', 'Produto')
@section('content')
<ul class="breadcrumb" style="margin-bottom:0px;">
  <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
  <li class="active">Cadastros <span class="divider"></span></li>
  <li class="active">Principais <span class="divider"></span></li>
  <li class="active"><a href="{{route('cadastros.principais.produtos')}}">Produtos</a><span class="divider"></span></li>
  <li class="active"><strong>Cadastro de produto</strong><span class="divider"></span></li>
</ul>

<div style="margin-top:15px;" id="app">

  <div class="row">
      <div class="col-md-12" style="padding: 0;">
          <div id="exTab2" class="col-md-12"> 
              <ul class="nav nav-tabs">
                  <li class="active">
                      <a  href="#produto" data-toggle="tab">Produto</a>
                  </li>
              </ul>
              <form id="frm" v-on:submit.prevent="cadastrar()">
                <div class="tab-content ">
                  <div class="tab-pane active" id="produto" style="padding:15px;">


                      <div class="row" style="padding-bottom: 10px;">
                          <div class="col-md-2">
                            <label>Código de referência</label>
                            <input type="" class="form form-control" v-model="frm.codRef">
                          </div>
                          <div class="col-md-6">
                            <label>Nome <span class="text-danger">*</span></label>
                            <input type="" class="form form-control" v-model="frm.nome" required v-on:blur="exitNome()">
                          </div>
                          <div class="col-md-4">
                            <label>TextLink <span class="text-danger">*</span></label>
                            <input type="" class="form form-control" v-model="frm.textLink" required onkeyup="this.value = String(this.value).trim()">
                            <small>(Utilizado para montar a Url do produto) Não utilizar acentuação</small>
                          </div>
                      </div>
                      <div class="row"  style="padding-bottom: 10px;">
                          <div class="col-md-6">
                            <label>Palavras Substitutas <span class="text-danger">(utilizar palavras que tenham a mesma semântica)</span></label>
                            <textarea class="form form-control" cols="50" rows="2" v-model="frm.palavrasSubstitutas"></textarea>
                            <small>Palavras relacionadas ao produto, serão utilizadas para a busca, separe as palavras com virgula ","</small>
                          </div>
                          <div class="col-md-6">
                            <label>Título da página (Tag Title)</label>
                            <input type="" class="form form-control" v-model="frm.tituloPagina">
                          </div>
                      </div>
                      <div class="row"  style="padding-bottom: 10px;">
                          <div class="col-md-6">
                            <label>Descrição do produto</label>
                            <textarea class="form form-control" cols="50" rows="2" v-model="frm.descricaoProduto"></textarea>
                          </div>
                          <div class="col-md-6">
                            <label>Descrição (Meta Tag Description)</label>
                            <textarea class="form form-control" cols="50" rows="2"  v-model="frm.descricaoMeta"></textarea>
                          </div>
                      </div>
                      <div class="row" style="padding-bottom: 10px;">
                          <div class="col-md-4">
                            <label>Marca <span class="text-danger">*</span></label>
                            <select class="form form-control" required v-model="frm.marcaId" required>
                              @foreach($marcas as $marca)
                                <option value="{{$marca->id}}">{{$marca->nome}}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-md-4">
                            <label>Categorias <span class="text-danger">*</span></label>
                            <select class="form form-control"  required v-model="categoria">
                              @foreach($categorias as $categoria)
                                <option value="{{$categoria->id}}">{{$categoria->nome}}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-md-4">
                            <label>Sub Categorias <span class="text-danger">*</span></label>
                            <select class="form form-control"   required v-model="frm.subCategoriaId">
                              @foreach($subCategorias as $subCategoria)
                                <option value="{{$subCategoria->id}}" v-show="(categoria == '{{$subCategoria->categoriaId}}' )" :disabled="(categoria != '{{$subCategoria->categoriaId}}' )" >{{$subCategoria->nome}}</option>
                              @endforeach
                            </select>
                          </div>
                      </div>
                      
                    </div>
                    <hr>
                    <div class="row" style="padding-right: 15px;margin-bottom: 150px;">
                      <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-default"><i class="  glyphicon glyphicon-floppy-disk"></i> Salvar</button>
                      </div>
                    </div>
                </div>
              </form>
          </div>

      </div>
  </div>

  


</div>


<script>

var app = new Vue( {
el: '#app',
delimiters: ["[[","]]"],
  data:{
      categoria : null,
      frm: {
          codRef : null,
          nome: null,
          textLink : null,
          palavrasSubstitutas : null,
          tituloPagina : null,
          descricaoProduto : null,
          descricaoMeta : null,
          subCategorias : null,
          marcaId : null
      }
  },
  methods: 
  {
      exitNome : function()
      {
        if((this.frm.textLink == null) || (String(this.frm.textLink).trim() == ""))
        {
            this.frm.textLink = String(this.frm.nome).trim().replaceAll(" ","-");
        }
        if((this.frm.tituloPagina == null) || (String(this.frm.tituloPagina).trim() == ""))
        {
            this.frm.tituloPagina = this.frm.nome;
        }
      },
      cadastrar: function()
      {
        var self=this;
        self.frm.especificacoes = JSON.stringify(self.frm.especificacoes);
        messageBox.confirm("Confirmação","Confirma o cadastro deste produto ?",function()
        {
            vform.submit('post', '{{route("cadastros.principais.produtos.store")}}', self.frm);
        });
      }
  }
});   
</script>

@stop