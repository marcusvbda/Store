@extends('templates.admin')
@section('title',  "Edição de produto")
@section('content')
<ul class="breadcrumb" style="margin-bottom:0px;">
  <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
  <li class="active">Cadastros <span class="divider"></span></li>
  <li class="active">Principais <span class="divider"></span></li>
  <li class="active"><a href="{{route('cadastros.principais.produtos')}}">Produtos</a><span class="divider"></span></li>
  <li class="active"><strong>Edição de produto #{{$produto->id}}</strong><span class="divider"></span></li>
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
              <form id="frm" v-on:submit.prevent="salvar()">
                <div class="tab-content ">
                  <div class="tab-pane active" id="produto" style="padding:15px;">

                      <div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                          Você está em modo de  <strong>edição,</strong> após efetuar as alterações clique em salvar a abaixo.
                      </div> 
                      <div class="row" style="padding-bottom: 10px;">
                          <div class="col-md-8">
                            <label><span class="text-danger">*</span> Nome</label>
                            <input type="" class="form form-control" v-model="frm.nome" required v-on:blur="exitNome()">
                          </div>
                          <div class="col-md-4">
                            <label><span class="text-danger">*</span> TextLink</label>
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
                            <textarea class="summernote form form-control" cols="50" rows="2" v-model="frm.descricaoProduto" id="summernoteDescricao"></textarea>
                          </div>
                          <div class="col-md-6">
                            <label>Descrição (Meta Tag Description)</label>
                            <textarea class="form form-control" cols="50" rows="2"  v-model="frm.descricaoMeta"></textarea>
                          </div>
                      </div>
                      <div class="row" style="padding-bottom: 10px;">
                          <div class="col-md-4">
                            <label><span class="text-danger">*</span> Marca</label>
                            <select class="form form-control selectpicker" required v-model="frm.marcaId" required data-live-search="true">
                              <option disabled>Selecione uma opção</option>
                              @foreach($marcas as $marca)
                                <option value="{{$marca->id}}">{{$marca->nome}}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-md-4">
                            <label><span class="text-danger">*</span> Categorias</label>
                            <select class="form form-control selectpicker"  required v-model="frm.categoriaId" v-on:change="changeCategoria()" data-live-search="true">
                              <option disabled>Selecione uma opção</option>
                              @foreach($categorias as $categoria)
                                <option value="{{$categoria->id}}">{{$categoria->nome}}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-md-4">
                            <label><span class="text-danger">*</span> Sub Categorias</label>
                            <select class="form form-control selectpicker" data-show-subtext="true" data-live-search="true" multiple required id="selectSubCategoria" v-model="frm.subCategorias">
                                <option disabled >Selecione uma opção</option>
                                @foreach($subCategorias as $sub)
                                  <option data-subtext="{{$sub->categoria}}"  class="catOption {{$sub->categoriaId}}_subOption" @if(in_array($sub->id, $subsSelecionados)) selected @endif style="display: none;" value="{{$sub->id}}">{{$sub->nome}}</option>
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
$(function() 
{
  var availableTags = [];
  @foreach($subCategorias as $sub)
      availableTags.push("{{$sub->nome}}");
  @endforeach 
    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
 
    $( "#tags" )
      .on( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) 
        {
          event.preventDefault();
        }
      })
      .autocomplete({
        minLength: 0,
        source: function( request, response ) 
        {
          response( $.ui.autocomplete.filter(
            availableTags, extractLast( request.term ) ) );
        },
        focus: function() 
        {
          return false;
        },
        select: function( event, ui ) 
        {
          var terms = split( this.value );
          terms.pop();
          terms.push( ui.item.value );
          terms.push( "" );
          this.value = terms.join( ", " );
          return false;
        }
      });
  } );



  var app = new Vue( {
  el: '#app',
  delimiters: ["[[","]]"],
    data:{
        frm: {
            id : "{{$produto->id}}",
            nome: "{{$produto->nome}}",
            textLink : "{{$produto->textLink}}",
            palavrasSubstitutas : "{{$produto->palavrasSubstitutas}}",
            tituloPagina : "{{$produto->tituloPagina}}",
            descricaoProduto : "{{$produto->descricaoProduto}}",
            descricaoMeta : "{{$produto->descricaoMeta}}",
            subCategorias : "{{implode(',',$subsSelecionados)}}",
            categoriaId : "{{$produto->categoriaId}}",
            marcaId : "{{$produto->marcaId}}"
        }
    },
    methods: 
    {
        changeCategoria : function(refresh=true)
        {
          $(".catOption").css("display","none");
          $("."+this.frm.categoriaId+"_subOption").css("display","block");
          if(refresh)
          {
	          this.frm.subCategorias = null;
	          $("#selectSubCategoria").val('default').selectpicker("refresh");
	      }
        },
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
        salvar: function()
        {
          var self=this;
          self.frm.especificacoes = JSON.stringify(self.frm.especificacoes);
          self.frm.descricaoProduto = $("#summernoteDescricao").summernote("code");
          messageBox.confirm("Confirmação","Confirma o alteração deste produto ?",function()
          {
              vform.submit('put', '{{route("cadastros.principais.produtos.put")}}', self.frm);
          });
        }
    }
  }); 

  $('#summernoteDescricao').summernote({
    toolbar: 
    [
      ['style', ['bold', 'italic', 'underline', 'clear']],
      ['font', ['strikethrough', 'superscript', 'subscript']],
      ['fontsize', ['fontsize']],
      ['color', ['color']],
      ['para', ['ul', 'ol', 'paragraph']]
    ],
  });

  $('#summernoteDescricao').summernote("code",'{!! $produto->descricaoProduto !!}');
  app.changeCategoria(false);

</script>

@stop