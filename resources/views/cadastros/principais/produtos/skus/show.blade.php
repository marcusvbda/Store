@extends('templates.admin')
@section('title', 'Visualização de Sku')
@section('content')
<ul class="breadcrumb" style="margin-bottom:0px;">
  <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
  <li class="active">Cadastros <span class="divider"></span></li>
  <li class="active">Principais <span class="divider"></span></li>
  <li class="active"><a href="{{route('cadastros.principais.produtos')}}">Produtos</a><span class="divider"></span></li>
  <li class="active"><a href="{{route('cadastros.principais.produtos.show',['id'=>$produto->id])}}">{{$produto->nome}}</a><span class="divider"></span></li>
  <li class="active">Visualização de Sku<span class="divider"></span></li>
  <li class="active"><strong>{{$sku->nome}}</strong><span class="divider"></span></li>
</ul>

<div style="margin-top:15px;" id="app">

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
                  <li>
                      <a href="#especificacoes" data-toggle="tab">Especificações</a>
                  </li>
                  <li>
                      <a href="#configs" data-toggle="tab">Configurações avançadas</a>
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
                          <label>Codigo de Referência <span class="text-danger" v-show="((frm.ean=='')||(frm.ean==null))">*</span></label>
                          <input type="" class="form form-control" v-model="frm.codRef" :required="((frm.ean=='')||(frm.ean==null))" disabled readonly>
                        </div>
                        <div class="col-md-3">
                          <label>Nome <span class="text-danger">*</span></label>
                          <input type="" class="form form-control" v-model="frm.nome" required disabled readonly>
                        </div>
                        <div class="col-md-3">
                          <label>EAN <span class="text-danger"  v-show="((frm.codRef=='')||(frm.codRef==null))">*</span></label>
                          <input type="" class="form form-control" v-model="frm.ean" :required="((frm.codRef=='')||(frm.codRef==null))" disabled readonly>
                        </div>
                        <div class="col-md-1">
                          <label>Ativo <span class="text-danger">*</span></label>
                          <select class="form form-control" v-model="frm.ativo" required disabled readonly>
                              <option value="1">SIM</option>
                              <option value="0">NÃO</option>
                          </select>
                        </div>
                    </div>
                    <div class="row" style="padding-bottom: 20px;">
                      <div class="col-md-12">
                        <p style="margin:0;">
                          <small class="text-danger">
                            <span class="glyphicon glyphicon-circle-arrow-right"></span>
                            Utilize gramas como unidade de massa e centimentos para unidade de tamanho.
                          </small>
                        </p>
                        <p style="margin: 0;">
                          <small class="text-danger">
                            <span class="glyphicon glyphicon-circle-arrow-right"></span>
                            O peso e as medidas devem ser cadastradas considerando a ambelagem de envio.
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
                    <hr>
                    <div class="row" style="padding-bottom: 20px;">
                      <div class="col-md-12">  
                        <label>Produtos sugeridos</label>
                        <input class="form form-control" v-model="frm.sugestoes" required disabled readonly>
                      </div>  
                    </div>
                    <div class="row" style="padding-bottom: 20px;"> 
                      <div class="col-md-12">  
                        <label>Acessórios</label>
                        <input class="form form-control" v-model="frm.acessorios" required disabled readonly>
                      </div>    
                    </div>
                    <div class="row" style="padding-bottom: 20px;">
                      <div class="col-md-12">  
                        <label>Semelhantes</label>
                        <input class="form form-control" v-model="frm.semelhantes" required disabled readonly>
                      </div>    
                    </div>
                    <hr>
                    <div class="row">
                      <div class="col-md-12 text-right">
                        <a  href="#" onclick="openRoute('{{route('cadastros.principais.produtos.skus.edit',['produtoId'=>$produto->id, 'skuId'=>$sku->id])}}')" type="button" class="btn btn-default"><i class="  glyphicon glyphicon-edit"></i> Editar</a>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane" id="imagens"  style="padding:15px;">
                        <div class="alert alert-warning alert-dismissable">
                          Primeiro salve as informações da aba <strong>Sku</strong> para poder cadastrar imagens.
                        </div>                  
                  </div>
                  <div class="tab-pane" id="especificacoes"  style="padding:15px;">
                    <div class="alert alert-warning alert-dismissable">
                        Primeiro salve as informações da aba <strong>Sku</strong> para poder cadastrar especificações.
                    </div>   
                  </div>
                  <div class="tab-pane" id="configs"  style="padding:15px;">
                    <div class="alert alert-warning alert-dismissable">
                        Primeiro salve as informações da aba <strong>Sku</strong> para poder alterar as configurações avançadas.
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
          acessorios : "{{$acessorios}}",
          semelhantes : "{{$semelhantes}}"
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