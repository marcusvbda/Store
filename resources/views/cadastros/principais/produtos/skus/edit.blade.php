@extends('templates.admin')
@section('title', 'Edição de Sku')
@section('content')
<ul class="breadcrumb" style="margin-bottom:0px;">
  <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
  <li class="active">Cadastros <span class="divider"></span></li>
  <li class="active">Principais <span class="divider"></span></li>
  <li class="active"><a href="{{route('cadastros.principais.produtos')}}">Produtos</a><span class="divider"></span></li>
  <li class="active"><a href="{{route('cadastros.principais.produtos.show',['id'=>$produto->id])}}">{{$produto->nome}}</a><span class="divider"></span></li>
  <li class="active">Edição de Sku<span class="divider"></span></li>
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
                    <form id="frm" v-on:submit.prevent="salvar()">
                      <div class="row" style="padding-bottom: 20px;">
                          <div class="col-md-3" style="padding-top: 20px;">
                            <p style="margin-bottom: 0;"><strong>Produto : </strong>{{$produto->nome}}</p>
                            <p style="margin-bottom: 0;"><strong>Cadastrado em : </strong>{{date_format(date_create($produto->dataCadastro), 'd/m/Y')}} as {{$produto->horaCadastro}}</p>
                          </div>
                          <div class="col-md-2">
                            <label><span class="text-danger" v-show="((frm.ean=='')||(frm.ean==null))">*</span> Codigo de Referência</label>
                            <input type="" class="form form-control" v-model="frm.codRef" :required="((frm.ean=='')||(frm.ean==null))" required>
                          </div>
                          <div class="col-md-3">
                            <label><span class="text-danger">*</span> Nome</label>
                            <input type="" class="form form-control" v-model="frm.nome" required required >
                          </div>
                          <div class="col-md-3">
                            <label><span class="text-danger"  v-show="((frm.codRef=='')||(frm.codRef==null))">*</span> EAN</label>
                            <input type="" class="form form-control" v-model="frm.ean" :required="((frm.codRef=='')||(frm.codRef==null))" required >
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
                      <hr>
                      <div class="row" style="padding-bottom: 20px;">
                        <div class="col-md-12">  
                          <label>Produtos sugeridos</label>
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
                      <div class="row" style="padding-bottom: 20px;">
                        <div class="col-md-12">  
                          <label>Semelhantes</label>
                          <select class="form form-control selectpicker" data-show-subtext="true" data-live-search="true" multiple v-model="frm.semelhantes">
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
                      

                      <div class="row">
                        <div class="col-md-12 text-right">
                            <button class="btn btn-primary btn-sm" v-on:click="modalUploadImagem()">
                              <span class="glyphicon glyphicon-plus"></span> Adicionar nova imagem
                            </button>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <table class="table">
                            <thead>
                              <tr>
                                <th>Preview</th>
                                <th>Legenda/Link</th>
                                <th>Editar</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>teste</td>
                                <td>teste</td>
                                <td>teste</td>
                              </tr>
                            </tbody>
                          </table>
                      </div>
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

  
  <div style="display:none;" id="formUpload">
      <form v-on:submit.prevent="salvarImagem()">
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-md-12">
                <label><span class="text-danger">*</span> Nome</label>
                <input type="text" class="form form-control" name="nome" v-model="novaImagem.nome" required>
            </div>
          </div>
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-md-12">
                <label><span class="text-danger">*</span> Legenda</label>
                <input type="text" class="form form-control" name="legenda" v-model="novaImagem.legenda" required>
            </div>
        </div>
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-md-12">
                <label><span class="text-danger">*</span> Imagem</label>
                <input type="file" id="novaImagemFile" v-model="novaImagem.imagem" required>
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
        nome : null,
        legenda : null,
        imagem : null
      },
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
          ],
          semelhantes : [
            @foreach($semelhantes as $row)
              "{{$row->id}}",
            @endforeach
          ]
      }
  },
  methods: 
  {
      salvarImagem : function()
      {
        console.log(this.novaImagem);
      },
      modalUploadImagem : function()
      {
          $("#formUpload").dialog(
          {
              modal: true, title: "Importação de imagens", zIndex: 10000, autoOpen: true,
              width: "30%", resizable: false, draggable: true,
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