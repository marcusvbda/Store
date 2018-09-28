@extends('templates.admin')
@section('title', 'Produtos')
@section('content')
<ul class="breadcrumb" style="margin-bottom:0px;">
   <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
   <li class="active">Cadastros <span class="divider"></span></li>
   <li class="active">Principais <span class="divider"></span></li>
   <li class="active"><strong>Produtos e SKUs</strong><span class="divider"></span></li>
</ul>
<br>
<div class="row" id="app">
   <div class="col-md-12">
      <div class="panel panel-default">
         <div class="panel-heading">
            Listagem de produtos e SKUs
         </div>
         <div class="panel-body">
            <form onsubmit="loadingElement($('.btnFiltrar'))">
               <table id="table" class="table table-striped table-bordered" style="width:100%;display:none;">
                  <thead>
                     <tr>
                        <th style="width:1%;" class="no-sort text-center">
                           <div class="dropdown">
                              <a class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" style="font-size:11px;" aria-haspopup="true" aria-expanded="true">
                              <span class="glyphicon glyphicon-plus"></span> Cadastrar produto <span class="caret"></span>
                              </a>    
                              <ul class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenu1">
                                 @can('post_produtos')
                                 <li><a class="BtnView" onclick="openRoute('{{route('cadastros.principais.produtos.create')}}')">Cadastrar produto</a></li>
                                 @endif
                                 <li role="separator" class="divider"></li>
                                 <li><a v-on:click="dialogImportacao('produtos')">Importar planilha de produtos e SKU</a></li>
                                 <li><a v-on:click="dialogImportacao('imagens')">Importar planilha de imagens</a></li>
                              </ul>
                           </div>
                        </th>
                        <th style="width:10%;" class="text-center no-sort"></th>
                        <th>id</th>
                        <th style="width:5%;">Marca</th>
                        <th style="width:20%;">Nome</th>
                        <th style="width:5%;" class="no-sort">SKUs</th>
                        <th style="width:1%;" class="no-sort"></th>
                     </tr>
                  <thead>
                     <tr>
                        <th style="width:5%;"></th>
                        <th style="width:1%;"></th>
                        <th style="width:10%;">
                           <input type="text" class="form form-control input-sm" style="width:100%" name="id" value="{{$id}}">
                        </th>
                        <th>
                           <select class="form form-control input-sm"  style="width:100%" name="marcaId">
                              <option></option>
                              @foreach($marcas as $m)
                              <option value="{{$m->id}}" @if($m->id == $marcaId) selected @endif>{{$m->nome}}</option>
                              @endforeach
                           </select>
                        </th>
                        <th>
                           <input type="text" class="form form-control input-sm" style="width:100%" name="nome" value="{{$nome}}">
                        </th>
                        <th></th>
                        <th style="width:1%">
                           <button type="submit" class="btn btn-default btn-sm btnFiltrar" style="width:100%"><i class="glyphicon glyphicon-search"></i> Filtrar</button>
                        </th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($data as $d)
                     <tr>
                        <td class="text-center" style="padding-top: 20px;">
                           <div class="dropdown">
                              <a class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" style="font-size:11px;" aria-haspopup="true" aria-expanded="true">
                              <span class="glyphicon glyphicon-cog"></span> Ações <span class="caret"></span>
                              </a>    
                              <ul class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenu1">
                                 <li>
                                    @can("get_produtos")
                                    <a class="BtnView" onclick="openRoute('{{route('cadastros.principais.produtos.show',['id'=>$d->id])}}')">Visualizar</a>
                                    @endcan
                                 </li>
                                 <li>
                                    @can("get_produtos")
                                    <a class="BtnView" onclick='openRoute("{{route('cadastros.principais.produtos.skus',['id'=>$d->id])}}")'>SKUs</a>
                                    @endcan
                                 </li>
                                 <li>
                                    @can("put_produtos")
                                    <a class="BtnEdit"  onclick="openRoute('{{route('cadastros.principais.produtos.edit',['id'=>$d->id])}}')">Editar</a>
                                    @endcan
                                 </li>
                                 <li role="separator" class="divider"></li>
                                 <li>
                                    @can("delete_produtos")
                                    <a class="BtnDestroy"  v-on:click="excluir('{{route('cadastros.principais.produtos.delete',['id'=>$d->id])}}')" style="color:red;">Excluir</a>
                                    @endcan
                                 </li>
                              </ul>
                           </div>
                        </td>
                        <td class="text-center">
                           <img src="{{$self->getFirstSkuImg($d->id)}}" style="height: 50px;">
                        </td>
                        <td style="padding-top: 20px;width: 10%;">{{$d->id}}</td>
                        <td style="padding-top: 20px;">{{$d->marca}}</td>
                        <td style="padding-top: 20px;">{{$d->nome}}</td>
                        <td style="padding-top: 20px;">
                           <div class="row">
                              <div class="col-md-12">
                                 <?php 
                                    $skus = DB::table("skus")->where("produtoId","=",$d->id)->get();
                                    ?>
                                 <small>
                                 @foreach($skus as $s)
                                 <a href="#"  onclick="openRoute('{{route('cadastros.principais.produtos.skus.show',['skuId'=>$s->id,'produtoId'=>$d->id])}}')">
                                 <span class="glyphicon glyphicon-triangle-right"></span>{{$s->nome}}
                                 </a>
                                 @endforeach
                                 </small>
                              </div>
                           </div>
                        </td>
                        <td></td>
                     </tr>
                     @endforeach
                  </tbody>
               </table>
            </form>
         </div>
      </div>
   </div>
   <div style="display:none;" id="formUpload">
      <div class="row">
         <div class="col-md-6 text-left">
            <h4>[[tituloUpload]]</h4>
         </div>
         <div class="col-md-6 text-right">
            <h5 class="text-danger">* Extensões aceitas [ xls, xlsx e csv ]</h5>
         </div>
         <div class="col-md-12 text-center">
            <h5 v-show="tipoImportacao=='produtos'" class="text-danger">
               Para importação ser efetuada corretamente, deve-se utilizar um modelo padrão de importação de produtos e SKU<br>Se não possui o modelo, 
               <a href="{{asset('public/MODELO_IMPORTACAO_PRODUTOS_SKU.xls')}}" target="_blank">clique aqui para baixa-lo</a>
            </h5>
            <h5 v-show="tipoImportacao!='produtos'" class="text-danger">
               Para a importação ser efetuada corretamente, deve-se utilizar um modelo padrão de importação de imagens<br>Se não possui o modelo, 
               <a href="{{asset('public/MODELO_IMPORTACAO_IMAGENS.xls')}}" target="_blank">clique aqui para baixa-lo</a>
            </h5>
         </div>
         <div class="col-md-12">
            <form action="{{ route('cadastros.principais.produtos.upload') }}" enctype="multipart/form-data" class="dropzone" multiple id="my-dropzone">
               {{ csrf_field() }}
            </form>
         </div>
      </div>
      <br>
      <div class="row">
         <div class="col-md-12 text-right">
            <button v-show="total_files_counter>0" type="button" v-on:click="confirmUploadPlanilha('#btnImportar')" class="btn btn-primary btn-sm" 
               id="btnImportar" data-loading-text="<span class='glyphicon glyphicon-repeat normal-right-spinner'></span> Processando..."> 
            <span class="glyphicon glyphicon-cloud-upload"></span> Confirmar importação
            </button>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12" id="AreaDeMensagens">
            <!--mensagens aqui -->
         </div>
      </div>
   </div>
</div>
<script>
   initFiltroAvancado("#filtrosAvancados");
   
   Dropzone.options.myDropzone = 
   {
       uploadMultiple: true,
       parallelUploads: 2,
       maxFilesize: 20, //mb
       addRemoveLinks: true,
       dictDefaultMessage: "Arraste seus arquivos para cá ou clique para seleciona-los",
       dictCancelUpload : "Cancelar upload",
       acceptedFiles: ".xls,.xlsx,.csv,.ods",
       dictRemoveFile: 'Excluir arquivo',
       dictFileTooBig: 'Arquivo não pode ser maior que 20mb',
       timeout: 10000,
       init: function () 
       {
           this.on("removedfile", function (file) 
           {
               toastr.success("Arquivo removido !!");
               location.reload();
           });
           this.on("addedfile", function(file) 
           {
               if (this.files.length) 
               {
                   var i, len, pre;
                   for (i = 0, len = this.files.length; i < len - 1; i++) 
                   {
                       if (this.files[i].name == file.name) 
                       {
                           toastr.error("Não é permitido upload de arquivos duplicados !!");
   
                           return (pre = file.previewElement) != null ? pre.parentNode.removeChild(file.previewElement) : void 0;
                       }
                   }
               }
           });
       },
       success: function (file, response) 
       {
           app._data.total_files_counter++;
       }
   };
   var app = new Vue( 
   {
     el: '#app',
     delimiters: ["[[","]]"],
     data:
     {
       total_files_counter  :  0,
       tituloUpload : null,
       tipoImportacao : null,
     },
     methods: 
     {
       confirmUploadPlanilha : function(button)
       {
           var self = this;
           var tipo = this.tipoImportacao;
           messageBox.confirm("Confirmação","Confirma importação desta planilha ?",function()
           {
               var $button = $(button);
               $button.button('loading');
               vform.ajax("post",'{{route("cadastros.principais.produtos.confirmarUpload")}}',{tipo}, function(response)
               {
                   if(!response.success)
                   {
                       $button.button('reset');
                       return toastr.error(response.message);
                   }
                   toastr.success("Planilha importada com sucesso !!");
                   return location.reload();
               });
           });
       },
       excluir: function(url)
       {
           messageBox.confirm("Confirmação","Deseja excluir este SKU ?",function()
           {
               
             vform.ajax("delete",url,{},function(response)
             {
                 if(!response.success)
                   return toastr.error(response.message);
                 toastr.success("SKU excluido com sucesso");
                 window.location.reload();
             });
   
           });
       },
       dialogImportacao : function(tipoImportacao)
       {
           this.tipoImportacao = tipoImportacao;
           if(this.tipoImportacao == "produtos")
           {
             this.tituloUpload = "Importação de planilha de produtos e sku";
           }
           else
           {
             this.tituloUpload = "Importação de planilha de imagens";
           }
           var self = this;
           $("#formUpload").dialog(
           {
               modal: true, title: "Importação de planilha" , zIndex: 10000, autoOpen: true,
               width: "60%", resizable: false, draggable: true,
               open: function (event, ui) 
               {
                   $(".ui-dialog-titlebar-close", ui.dialog | ui).html("<span class='glyphicon glyphicon-remove'></span>");
                   $(".ui-dialog-titlebar-close", ui.dialog | ui).show();
               },
               buttons:{}
           });
       }
     }
   });
   var dtable = new dataTableCrud({
       order : 2,
       table : "#table",
       onload : function()
       {
           @cannot("get_produtos")
               $(".BtnView").remove();
           @endcannot
           @cannot("put_produtos")
               $(".BtnEdit").remove();
           @endcannot
           @cannot("delete_produtos")
               $(".BtnDestroy").remove();
           @endcannot
       }
   });
</script>
@stop