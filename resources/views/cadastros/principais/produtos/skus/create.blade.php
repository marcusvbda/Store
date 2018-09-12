@extends('templates.admin')
@section('title', 'SKU')
@section('content')
<ul class="breadcrumb" style="margin-bottom:0px;">
  <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
  <li class="active">Cadastros <span class="divider"></span></li>
  <li class="active">Principais <span class="divider"></span></li>
  <li class="active"><a href="{{route('cadastros.principais.produtos')}}">Produtos</a><span class="divider"></span></li>
  <li class="active"><a href="{{route('cadastros.principais.produtos')}}">#{{$produto->id}} - {{$produto->nome}}</a><span class="divider"></span></li>
  <li class="active"><strong>Cadastro de SKU</strong><span class="divider"></span></li>
</ul>

<div style="margin-top:15px;" id="app">

  <div class="row">
      <div class="col-md-12" style="padding: 0;">
          <div id="exTab2" class="col-md-12"> 
              <ul class="nav nav-tabs">
                  <li class="active">
                      <a  href="#produto" data-toggle="tab">SKU</a>
                  </li>
                  <li>
                      <a href="#imagens" data-toggle="tab">Imagens</a>
                  </li>
                  <li>
                      <a href="#especificacoes" data-toggle="tab">Especificações</a>
                  </li>
              </ul>
              <div class="tab-content ">
                  <div class="tab-pane active" id="produto" style="padding:15px;">
                    <form id="frm" v-on:submit.prevent="cadastrar()">
                      <div class="row" style="padding-bottom: 10px;">
                          <div class="col-md-4" style="padding-top: 20px;">
                            <p style="margin-bottom: 0;"><strong>Produto : </strong>#{{$produto->id}} - {{$produto->nome}}</p>
                            <p style="margin-bottom: 0;"><strong>Cadastrado em : </strong>{{date_format(date_create($produto->dataCadastro), 'd/m/Y')}} as {{$produto->horaCadastro}}</p>
                          </div>
                          <div class="col-md-3">
                            <label>Codigo de Referência <span class="text-danger" v-show="((frm.ean=='')||(frm.ean==null))">*</span></label>
                            <input type="" class="form form-control" v-model="frm.codRef" :required="((frm.ean=='')||(frm.ean==null))">
                          </div>
                          <div class="col-md-3">
                            <label>EAN <span class="text-danger"  v-show="((frm.codRef=='')||(frm.codRef==null))">*</span></label>
                            <input type="" class="form form-control" v-model="frm.ean" :required="((frm.codRef=='')||(frm.codRef==null))">
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
                  <div class="tab-pane" id="imagens"  style="padding:15px;">
                        <div class="alert alert-warning alert-dismissable">
                          Primeiro salve as informações da aba <strong>SKU</strong> para poder cadastrar imagens.
                        </div>                  
                  </div>
                  <div class="tab-pane" id="especificacoes"  style="padding:15px;">
                    <div class="alert alert-warning alert-dismissable">
                        Primeiro salve as informações da aba <strong>SKU</strong> para poder cadastrar especificações.
                    </div>   
                  </div>
                </div>
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
      frm: 
      {
          codRef : null,
          ean : null,
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
            // vform.submit('post', '{{route("cadastros.principais.produtos.store")}}', self.frm);
        });
      }
  }
});   
</script>

@stop