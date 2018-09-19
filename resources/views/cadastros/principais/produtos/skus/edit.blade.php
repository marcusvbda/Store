@extends('templates.admin')
@section('title', 'Edição de SKU')
@section('content')
<ul class="breadcrumb" style="margin-bottom:0px;">
  <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
  <li class="active">Cadastros <span class="divider"></span></li>
  <li class="active">Principais <span class="divider"></span></li>
  <li class="active"><a href="{{route('cadastros.principais.produtos')}}">Produtos</a><span class="divider"></span></li>
  <li class="active"><a href="{{route('cadastros.principais.produtos.show',['id'=>$produto->id])}}">{{$produto->nome}}</a><span class="divider"></span></li>
  <li class="active"><a href="{{route('cadastros.principais.produtos.skus',['id'=>$produto->id])}}">Skus do produto</a><span class="divider"></span></li>
  <li class="active">Edição de SKU <span class="divider"></span></li>
  <li class="active"><strong>{{$sku->nome}}</strong><span class="divider"></span></li>
</ul>

<div style="margin-top:15px;" id="app">

  <div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
   Você está em modo de  <strong>edição,</strong> após efetuar as alterações clique em salvar a abaixo.
 </div> 
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
              </ul>
              <div class="tab-content ">
                  <div class="tab-pane active" id="produto" style="padding:15px;">
                  
                    <form id="frm" v-on:submit.prevent="salvar()">
                      <div class="row" style="padding-bottom: 20px;">
                          <div class="col-md-3" style="padding-top: 20px;">
                            <p style="margin-bottom: 0;"><strong>Produto : </strong>{{$produto->nome}}</p>
                            <p style="margin-bottom: 0;"><strong>Cadastrado em : </strong>{{date_format(date_create($produto->dataCadastro), 'd/m/Y')}} as {{$produto->horaCadastro}}</p>
                          </div>
                          <div class="col-md-2">
                            <label><span class="text-danger" v-show="((frm.ean=='')||(frm.ean==null))">*</span> Codigo de Referência</label>
                            <input type="" class="form form-control" v-model="frm.codRef" :required="((frm.ean=='')||(frm.ean==null))">
                          </div>
                          <div class="col-md-3">
                            <label><span class="text-danger">*</span> Nome</label>
                            <input type="" class="form form-control" v-model="frm.nome" required required >
                          </div>
                          <div class="col-md-3">
                            <label><span class="text-danger"  v-show="((frm.codRef=='')||(frm.codRef==null))">*</span> EAN</label>
                            <input type="" class="form form-control" v-model="frm.ean" :required="((frm.codRef=='')||(frm.codRef==null))" >
                          </div>
                          <div class="col-md-1">
                            <label><span class="text-danger">*</span> Ativo</label>
                            <select class="form form-control" v-model="frm.ativo" required required >
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
                          <input type="number" class="form form-control" v-model="frm.peso" required >
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
                          <label>Produtos Recomendados</label>
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
                  <div class="tab-pane" id="imagens"  style="padding:15px;">
                      

                      <div class="row" style="margin-bottom: 5px;">
                        <div class="col-md-12 text-right">
                            <button class="btn btn-primary btn-sm" v-on:click="modalUploadImagem()">
                              <span class="glyphicon glyphicon-plus"></span> Adicionar imagem
                            </button>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th class="text-center" width="1%">Preview</th>
                                <th>Nome/Legenda</th>
                                <th width="1%"></th>
                                <th width="1%"></th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr  v-for="val in imagens">
                                <td class="text-center">
                                  <img style="height: 80px;" v-bind:src="val.url">
                                </td>
                                <td>
                                  <form v-on:submit.prevent="alterarImagem(val.id)" v-bind:id="val.id">
                                    <p><strong>Nome : </strong>[[val.nome]]</p>
                                    <p>
                                      <div class="form-group input-group">
                                          <span class="input-group-addon"><span class="text-danger">*</span> Legenda</span>
                                          <input type="text" class="form-control" placeholder="Legenda" name="legenda" required maxlength="100" :value="[[val.legenda]]">
                                          <span class="input-group-btn">
                                            <button type="submit" class="btn btn-default" type="button">Alterar</button>
                                          </span>
                                      </div>
                                    </p>
                                  </form>
                                </td>
                                <td class="text-center" style="padding-top: 45px">
                                  <span v-show="val.principal" class="label label-success" >Principal</span>
                                  <button v-show="!(val.principal)" class="btn btn-default btn-xs" v-on:click="setPrincipal(val.id)">Definir principal</button>
                                </td>
                                <td class="text-center" style="padding-top: 45px">
                                  <button class="btn btn-danger btn-xs" v-on:click="excluirImagem(val.id)"><span class="glyphicon glyphicon-trash"></span> Excluir</button>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                      </div>
                    </div>


                </div>
          </div>

      </div>
  </div>

  
  <div style="display:none; overflow: hidden;" id="formUpload">
      <form v-on:submit.prevent="salvarImagem()">
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-md-12">
                <label><span class="text-danger">*</span> Legenda</label>
                <input type="text" class="form form-control" name="legenda" maxlength="100" v-model="novaImagem.legenda" required>
            </div>
        </div>
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-md-12">
              <p class="text-danger">A url de imagem será desconsiderado caso o campo seja feito o upload de uma imagem.</p>
            </div>
            <div class="col-md-4">
                <label><span class="text-danger">*</span> Tipo</label>
                <select  v-model="novaImagem.tipo" required="" class="form form-control" v-on:change="mudarTipoUpload()">
                  <option disabled>Selecione uma opção</option>
                  <option value="UPLOAD">Upload de imagem</option>
                  <option value="URL">Url da imagem</option>
                </select>
            </div>
            <div class="col-md-8" v-show="(novaImagem.tipo=='UPLOAD')">
                <label><span class="text-danger">*</span> Imagem</label>
                <input type="file" id="novaImagemFile" v-model="novaImagem.imagem" :required="(novaImagem.tipo=='UPLOAD')">
            </div>
            <div class="col-md-8" v-show="(novaImagem.tipo=='URL')">
                <label><span class="text-danger">*</span> Url</label>
                <input type="text" class="form form-control" name="url" v-model="novaImagem.url" :required="(novaImagem.tipo=='URL')">
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12 text-right">
                <button class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-floppy-disk"></i> Salvar</button>
            </div>
        </div>
    </form>
  </div>

</div>


<script>
var app = new Vue( {
el: '#app',
delimiters: ["[[","]]"],
  data:{
      novaImagem : 
      {
        tipo : null,
        url : null,
        legenda : null,
        imagem : null,
        principal : 0,
      },
      imagens : [
        @foreach($imagens as $imagem)
          {
            id : "{{$imagem->id}}",
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
          sugestoes : [
            @foreach($sugestoes as $row)
              "{{$row->id}}",
            @endforeach
          ],
          acessorios : [
            @foreach($acessorios as $row)
              "{{$row->id}}",
            @endforeach
          ]
      }
  },
  methods: 
  {
      mudarTipoUpload : function()
      {
          this.novaImagem.url = null;
          this.novaImagem.imagem = null;
      },
      excluirImagem : function(id)
      {
        var self = this;
        messageBox.confirm("Confirmação","Deseja mesmo excluir esta imagem ?",function()
        {

          vform.ajax("delete","{{route( 'cadastros.principais.produtos.skus.deleteImagem',['produtoId'=>$produto->id,'skuId'=>$sku->id] )}}",{id},function(response)
          {
            console.log(response);
              if(!response.success)
                return toastr.error(response.message);
              self.imagens = response.data;
              return toastr.success("Imagem excluida com sucesso");
          });

        });
      },
      alterarImagem : function(id)
      {
        var self = this;
        var data = vform.getData("#"+id);
        data["id"] = id;
        messageBox.confirm("Confirmação","Deseja mesmo alterar esta imagem ?",function()
        {
          vform.ajax("put","{{route( 'cadastros.principais.produtos.skus.imagemEdit',['produtoId'=>$produto->id,'skuId'=>$sku->id] )}}",data,function(response)
          {
              console.log(response);
              if(!response.success)
                return toastr.error(response.message);
              return toastr.success("Imagem Alterada com sucesso");
          });

        });
      },
      setPrincipal : function(id)
      {   
          var self = this;
          vform.ajax("put","{{route( 'cadastros.principais.produtos.skus.setPrincipal',['produtoId'=>$produto->id,'skuId'=>$sku->id] )}}",{id},function(response)
          {
              if(!response.success)
                return toastr.error(response.message);
              self.imagens = response.data;
              return toastr.success("Imagem principal alterada");
          });
      },
      salvarImagem : function()
      {
          // if(this.novaImagem.tipo=="URL")
          // {
          //   if(!checkImagemUrl(this.novaImagem.url))
          //     return toastr.error("Url de imagem inválida");
          // }
          var self = this;
          var data = new FormData();
          data.append("imagem", $("#novaImagemFile")[0].files[0]);
          data.append("url", self.novaImagem.url);
          data.append("tipo", self.novaImagem.tipo);
          data.append("legenda", self.novaImagem.legenda);
          data.append("principal", self.novaImagem.principal);
          $.ajax(
          {
              url: "{{route( 'cadastros.principais.produtos.skus.uploadImagem',['produtoId'=>$produto->id,'skuId'=>$sku->id] )}}",
              cache: false,
              contentType: false,
              processData: false,
              data: data,
              type: "POST",
              success: function(response) 
              {   
                  if(!response.success)
                    return toastr.error(response.message);
                  toastr.success("Imagem importada com sucesso!!");
                  self.imagens = response.data;
                  self.novaImagem.imagem = null;
                  self.novaImagem.url = null;
                  self.novaImagem.legenda = null;
                  self.novaImagem.tipo = null;
                  self.novaImagem.nome = null;
                  return $("#formUpload").dialog('close');
              },
              error: function(data) 
              {
                  toastr.error("Erro ao importar imagem");
                  return console.log(data);
              }
          });
      },
      modalUploadImagem : function()
      {
          $("#formUpload").dialog(
          {
              modal: true, title: "Importação de imagens", zIndex: 10000, autoOpen: true,
              width: "50%", resizable: false, draggable: true,
              open: function (event, ui) 
              {
                  $(".ui-dialog-titlebar-close", ui.dialog | ui).html("X");
                  $(".ui-dialog-titlebar-close", ui.dialog | ui).show();
              },
              buttons:{}
          });
      },
      salvar: function()
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