@extends('templates.admin')
@section('title', "Visualização de produto")
@section('content')
<ul class="breadcrumb" style="margin-bottom:0px;">
  <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
  <li class="active">Cadastros <span class="divider"></span></li>
  <li class="active">Principais <span class="divider"></span></li>
  <li class="active"><a href="{{route('cadastros.principais.produtos')}}">Produtos</a><span class="divider"></span></li>
  <li class="active"><strong>Visualização de produto {{$produto->nome}}</strong><span class="divider"></span></li>
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
                <div class="tab-content ">
                    <div class="tab-pane active" id="produto" style="padding:15px;">
                      <div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                          Você está em modo de  <strong>visualização,</strong> para poder efetuar alterações clique no botão editar abaixo.
                      </div> 
                      <div class="row" style="padding-bottom: 10px;">
                          <div class="col-md-8">
                            <label><span class="text-danger">*</span> Nome</label>
                            <input type="" class="form form-control" v-model="frm.nome"  readonly disabled>
                          </div>
                          <div class="col-md-4">
                            <label><span class="text-danger">*</span> TextLink</label>
                            <input type="" class="form form-control" v-model="frm.textLink" readonly disabled onkeyup="this.value = String(this.value).trim()">
                            <small>(Utilizado para montar a Url do produto) Não utilizar acentuação</small>
                          </div>
                      </div>
                      <div class="row"  style="padding-bottom: 10px;">
                          <div class="col-md-6">
                            <label>Palavras Substitutas <span class="text-danger">(utilizar palavras que tenham a mesma semântica)</span></label>
                            <textarea class="form form-control" cols="50" rows="2" v-model="frm.palavrasSubstitutas"  readonly disabled></textarea>
                            <small>Palavras relacionadas ao produto, serão utilizadas para a busca, separe as palavras com virgula ","</small>
                          </div>
                          <div class="col-md-6">
                            <label>Título da página (Tag Title)</label>
                            <input type="" class="form form-control" v-model="frm.tituloPagina"  readonly disabled>
                          </div>
                      </div>
                      <div class="row"  style="padding-bottom: 10px;">
                          <div class="col-md-6">
                            <label>Descrição do produto</label>
                            <textarea class="summernote form form-control" cols="50" rows="2" v-model="frm.descricaoProduto" id="summernoteDescricao"  readonly disabled></textarea>
                          </div>
                          <div class="col-md-6">
                            <label>Descrição (Meta Tag Description)</label>
                            <textarea class="form form-control" cols="50" rows="2"  v-model="frm.descricaoMeta"  readonly disabled></textarea>
                          </div>
                      </div>
                      <div class="row" style="padding-bottom: 10px;">
                          <div class="col-md-4">
                            <label><span class="text-danger">*</span> Marca</label>
                            <select class="form form-control"  v-model="frm.marcaId"  readonly disabled>
                              @foreach($marcas as $marca)
                                <option value="{{$marca->id}}">{{$marca->nome}}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-md-4">
                            <label><span class="text-danger">*</span> Categorias</label>
                            <select class="form form-control"   readonly disabled v-model="frm.categoriaId" v-on:change="changeCategoria()">
                              @foreach($categorias as $categoria)
                                <option value="{{$categoria->id}}">{{$categoria->nome}}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-md-4">
                            <label><span class="text-danger">*</span> Sub Categorias</label>
                            <select class="form form-control selectpicker" multiple  readonly disabled id="selectSubCategoria" v-model="frm.subCategorias">
                                @foreach($subCategorias as $sub)
                                  <option class="catOption {{$sub->categoriaId}}_subOption" @if(in_array($sub->id, $subsSelecionados)) selected @endif style="display: none;" value="{{$sub->id}}">{{$sub->nome}}</option>
                                @endforeach
                            </select>
                          </div>
                      </div>
                      
                    </div>
                    @can('put_produtos')
                      <hr>
                      <div class="row" style="padding-right: 15px;margin-bottom: 150px;">
                        <div class="col-md-12 text-right">
                          <a  href="#" onclick="openRoute('{{route('cadastros.principais.produtos.edit',['id'=>$produto->id])}}')" type="button" class="btn btn-default"><i class="  glyphicon glyphicon-edit"></i> Editar</a>
                        </div>
                      </div>
                    @endcan
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
  $('#summernoteDescricao').summernote('disable');
  app.changeCategoria(false);

</script>

@stop