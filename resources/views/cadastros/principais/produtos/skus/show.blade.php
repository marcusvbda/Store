@extends('templates.admin')
@section('title', 'Visualização de Sku')
@section('content')
<ul class="breadcrumb" style="margin-bottom:0px;">
  <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
  <li class="active">Cadastros <span class="divider"></span></li>
  <li class="active">Principais <span class="divider"></span></li>
  <li class="active"><a href="{{route('cadastros.principais.produtos')}}">Produtos</a><span class="divider"></span></li>
  <li class="active"><a href="{{route('cadastros.principais.produtos.show',['id'=>$produto->id])}}">{{$produto->nome}}</a><span class="divider"></span></li>
  <li class="active"><a href="{{route('cadastros.principais.produtos.skus',['id'=>$produto->id])}}">Skus do produto</a><span class="divider"></span></li>
  <li class="active">Visualização de SKU <span class="divider"></span></li>
  <li class="active"><strong>{{$sku->nome}}</strong><span class="divider"></span></li>
</ul>

<div style="margin-top:15px;" id="app">
  <div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
       Você está em modo de  <strong>visualização,</strong> para poder efetuar alterações clique no botão editar abaixo.
  </div> 
  <div class="row">
      <div class="col-md-12" style="padding: 0;">
          <div id="exTab2" class="col-md-12"> 
              <ul class="nav nav-tabs">
                  <li class="active">
                      <a  href="#produto" data-toggle="tab">Sku</a>
                  </li>
                  <li>
                      <a href="#imagens" data-toggle="tab">Imagens</a>
                  </li>
                  
              </ul>
              <div class="tab-content ">
                  <div class="tab-pane active" id="produto" style="padding:15px;">
    
                    <div class="row" style="padding-bottom: 20px;">
                        <div class="col-md-3" style="padding-top: 20px;">
                          <p style="margin-bottom: 0;"><strong>Produto : </strong>{{$produto->nome}}</p>
                          <p style="margin-bottom: 0;"><strong>Cadastrado em : </strong>{{date_format(date_create($produto->dataCadastro), 'd/m/Y')}} as {{$produto->horaCadastro}}</p>
                        </div>
                        <div class="col-md-2">
                          <label><span class="text-danger" v-show="((frm.ean=='')||(frm.ean==null))">*</span> Codigo de Referência</label>
                          <input type="" class="form form-control" v-model="frm.codRef" :required="((frm.ean=='')||(frm.ean==null))" disabled readonly>
                        </div>
                        <div class="col-md-3">
                          <label><span class="text-danger">*</span> Nome</label>
                          <input type="" class="form form-control" v-model="frm.nome" required disabled readonly>
                        </div>
                        <div class="col-md-3">
                          <label><span class="text-danger"  v-show="((frm.codRef=='')||(frm.codRef==null))">*</span> EAN</label>
                          <input type="" class="form form-control" v-model="frm.ean" :required="((frm.codRef=='')||(frm.codRef==null))" disabled readonly>
                        </div>
                        <div class="col-md-1">
                          <label><span class="text-danger">*</span> Ativo</label>
                          <select class="form form-control" v-model="frm.ativo" required disabled readonly>
                              <option value="1">SIM</option>
                              <option value="0">NÃO</option>
                          </select>
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
                        <input type="number" class="form form-control" v-model="frm.peso" required disabled readonly>
                      </div>
                      <div class="col-md-3">
                        <label><span class="glyphicon glyphicon-circle-arrow-right"></span> Altura em centimentros <span class="text-danger">*</span></label>
                        <input type="number" class="form form-control" v-model="frm.altura" required disabled readonly>
                      </div>
                      <div class="col-md-3">
                        <label><span class="glyphicon glyphicon-circle-arrow-right"></span> Largura em centimentros <span class="text-danger">*</span></label>
                        <input type="number" class="form form-control" v-model="frm.largura" required disabled readonly>
                      </div>
                      <div class="col-md-3">
                        <label><span class="glyphicon glyphicon-circle-arrow-right"></span> Comprimento em centimentros <span class="text-danger">*</span></label>
                        <input type="number" class="form form-control" v-model="frm.comprimento" required disabled readonly>
                      </div>
                    </div>
                    <div class="row" style="padding-bottom: 20px;">
                      <div class="col-md-12">  
                        <label>Produtos recomendados</label>
                        <input class="form form-control" v-model="frm.sugestoes" required disabled readonly>
                      </div>  
                    </div>
                    <div class="row" style="padding-bottom: 20px;"> 
                      <div class="col-md-12">  
                        <label>Acessórios</label>
                        <input class="form form-control" v-model="frm.acessorios" required disabled readonly>
                      </div>    
                    </div>
                    
                  </div>
                  <div class="tab-pane" id="imagens"  style="padding:15px;">
                        
                        <div class="row">
                          <div class="col-md-12">
                            <table class="table table-bordered">
                              <thead>
                                <tr>
                                  <th class="text-center" width="1%">Preview</th>
                                  <th>Nome/Legenda</th>
                                  <th width="1%"></th>
                                </tr>
                              </thead>
                              <tbody>
                                @foreach($imagens as $imagem)
                                <tr>
                                  <td class="text-center">
                                    <img style="height: 100px;" src="{{$imagem->url}}">
                                  </td>
                                  <td>
                                    <p><strong>Nome : </strong>{{$imagem->nome}}</p>
                                    <p>
                                      <div class="form-group input-group">
                                          <span class="input-group-addon">Legenda</span>
                                          <input type="text" class="form-control" readonly disabled value="{{$imagem->legenda}}">
                                      </div>
                                    </p>
                                </td>
                                  <td class="text-center" style="padding-top: 45px">
                                    @if($imagem->principal)
                                      <span class="label label-success">Principal</span></button>
                                    @endif
                                  </td>
                                </tr>
                                @endforeach
                              </tbody>
                            </table>
                        </div>
                  </div>

             
              </div>
              <hr>
              <div class="row" style="padding: 15px;">
                <div class="col-md-12 text-right">
                  <a  href="#" onclick="openRoute('{{route('cadastros.principais.produtos.skus.edit',['produtoId'=>$produto->id, 'skuId'=>$sku->id])}}')" type="button" class="btn btn-default"><i class="  glyphicon glyphicon-edit"></i> Editar</a>
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
      imagens : [
        @foreach($imagens as $imagem)
          {
            url : "{{$imagem->url}}",
            nome : "{{$imagem->nome}}",
            legenda : "{{$imagem->legenda}}",
            principal : {{$imagem->principal}},
          },
        @endforeach
      ],
      frm: 
      {
          produtoId : "{{$produto->id}}",
          nome : "{{$sku->nome}}",
          codRef : "{{$sku->codRef}}",
          ean : "{{$sku->ean}}",
          peso : "{{$sku->peso}}",
          altura : "{{$sku->altura}}",
          largura : "{{$sku->largura}}",
          comprimento : "{{$sku->comprimento}}",
          ativo : "{{$sku->ativo}}",
          sugestoes : "{{$sugestoes}}",
          acessorios : "{{$acessorios}}"
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