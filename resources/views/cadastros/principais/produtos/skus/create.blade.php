@extends('templates.admin')
@section('title', 'Cadastro de Sku')
@section('content')
<ul class="breadcrumb" style="margin-bottom:0px;">
  <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
  <li class="active">Cadastros <span class="divider"></span></li>
  <li class="active">Principais <span class="divider"></span></li>
  <li class="active"><a href="{{route('cadastros.principais.produtos')}}">Produtos</a><span class="divider"></span></li>
  <li class="active"><a href="{{route('cadastros.principais.produtos.show',['id'=>$produto->id])}}">{{$produto->nome}}</a><span class="divider"></span></li>
  <li class="active"><a href="{{route('cadastros.principais.produtos.skus',['id'=>$produto->id])}}">Skus do produto</a><span class="divider"></span></li>
  <li class="active">Cadastro de SKU <span class="divider"></span></li>
</ul>

<div style="margin-top:15px;" id="app">

  <div class="row">
      <div class="col-md-12" style="padding: 0;">
          <div id="exTab2" class="col-md-12"> 
              <ul class="nav nav-tabs">
                  <li class="active">
                      <a  href="#produto" data-toggle="tab">Sku</a>
                  </li>
              </ul>
              <div class="tab-content ">
                  <div class="tab-pane active" id="produto" style="padding:15px;">

                    <div class="alert alert-info alert-dismissable">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                          Preencha o formulário <strong>corretamente</strong> e clique em salvar a abaixo.
                    </div> 

                    <form id="frm" v-on:submit.prevent="cadastrar()">
                      <div class="row" style="padding-bottom: 20px;">
                          <div class="col-md-4" style="padding-top: 20px;">
                            <p style="margin-bottom: 0;"><strong>Produto : </strong>{{$produto->nome}}</p>
                            <p style="margin-bottom: 0;"><strong>Cadastrado em : </strong>{{date_format(date_create($produto->dataCadastro), 'd/m/Y')}} as {{$produto->horaCadastro}}</p>
                          </div>
                          <div class="col-md-7">
                            <label><span class="text-danger">*</span> Nome</label>
                            <input type="" class="form form-control" v-model="frm.nome" required>
                          </div>
                          <div class="col-md-1">
                            <label><span class="text-danger">*</span> Ativo</label>
                            <select class="form form-control" v-model="frm.ativo" required>
                                <option value="1">SIM</option>
                                <option value="0">NÃO</option>
                            </select>
                          </div>
                      </div>
                      <div class="row" style="padding-bottom: 20px;">
                        <div class="col-md-4">
                          <label> <span class="text-danger" v-show="((frm.ean=='')||(frm.ean==null))">*</span> Codigo de Referência</label>
                          <input type="" class="form form-control" v-model="frm.codRef" :required="((frm.ean=='')||(frm.ean==null))">
                        </div>
                        <div class="col-md-4">
                            <label><span class="text-danger"  v-show="((frm.codRef=='')||(frm.codRef==null))">*</span> EAN</label>
                            <input type="" class="form form-control" v-model="frm.ean" :required="((frm.codRef=='')||(frm.codRef==null))">
                        </div>
                        <div class="col-md-4">
                            <label>NCM</label>
                            <input type="" class="form form-control" v-model="frm.ncm" >
                        </div>
                      </div>
                      <div class="row" style="padding-bottom: 20px;">
                        <div class="col-md-12">
                          <p style="margin: 0;">
                            <small class="text-danger">
                              <span class="glyphicon glyphicon-circle-arrow-right"></span>
                              Medidas e pesos devem ser cadastradas considerando a embalagem.
                            </small>
                          </p>
                        </div>
                        <div class="col-md-3">
                          <label><span class="glyphicon glyphicon-circle-arrow-right"></span> Peso em gramas <span class="text-danger">*</span></label>
                          <input type="number" class="form form-control" v-model="frm.peso" required>
                        </div>
                        <div class="col-md-3">
                          <label><span class="glyphicon glyphicon-circle-arrow-right"></span> Altura em centimentros <span class="text-danger">*</span></label>
                          <input type="number" class="form form-control" v-model="frm.altura" required>
                        </div>
                        <div class="col-md-3">
                          <label><span class="glyphicon glyphicon-circle-arrow-right"></span> Largura em centimentros <span class="text-danger">*</span></label>
                          <input type="number" class="form form-control" v-model="frm.largura" required>
                        </div>
                        <div class="col-md-3">
                          <label><span class="glyphicon glyphicon-circle-arrow-right"></span> Comprimento em centimentros <span class="text-danger">*</span></label>
                          <input type="number" class="form form-control" v-model="frm.comprimento" required>
                        </div>
                      </div>
                      <div class="row" style="padding-bottom: 20px;">
                        <div class="col-md-12">  
                          <label>Produtos recomendados</label>
                          <select class="form form-control selectpicker" data-show-subtext="true" data-live-search="true" multiple v-model="frm.sugestoes">
                              <option disabled>Selecione uma opção</option>
                              @foreach($outrosSkus as $row)
                                <option value="{{$row->id}}">{{$row->nome}}</option>
                              @endforeach
                          </select>
                        </div>   
                      </div> 
                      <div class="row" style="padding-bottom: 20px;">  
                        <div class="col-md-12">  
                          <label>Acessórios</label>
                          <select class="form form-control selectpicker" data-show-subtext="true" data-live-search="true" multiple v-model="frm.acessorios">
                              <option disabled>Selecione uma opção</option>
                              @foreach($outrosSkus as $row)
                                <option value="{{$row->id}}">{{$row->nome}}</option>
                              @endforeach
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

      </div>
  </div>

  


</div>


<script>

var app = new Vue( {
el: '#app',
delimiters: ["[[","]]"],
  data:{
      frm: 
      {
          produtoId : "{{$produto->id}}",
          nome : null,
          codRef : null,
          ean : null,
          peso : 0,
          altura : 0,
          largura : 0,
          comprimento : 0,
          ativo : 1,
          ncm : 1,
          sugestoes : null,
          acessorios : null,
      }
  },
  methods: 
  {
      cadastrar: function()
      {
        var self=this;
        messageBox.confirm("Confirmação","Confirma o cadastro deste Sku ?",function()
        {
            vform.submit('post', '{{route("cadastros.principais.produtos.skus.store",["produtoId"=>$produto->id])}}', self.frm);
        });
      }
  }
});   
</script>

@stop