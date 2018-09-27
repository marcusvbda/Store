<?php use App\Http\Controllers\ParametrosController as Parametros;?>
@extends('templates.admin')
@section('title', $relatorio->nome)
@section('content')
<script>
    vloading.start();
</script>
<section id="conteudo">
    <ul class="breadcrumb" style="margin-bottom:20px;">
    <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
    <li class="active">Relatórios padrão</li>
    <li class="active">
        <strong>{{$relatorio->nome}}</strong>
    </li>
    </ul>

    <div id="app">
        <div class="row">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            Preencha o formulário de filtros a <strong>esquerda</strong> e clique em <strong>filtrar</strong>, para gerar um PDF ou excel , clique em ações e selecione a opção desejada.
                </div> 
            </div>
            @include('relatorios.padrao.filtro') 
            <div class="col-md-9" style="width:78%;">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        
                        <div class="dropdown">
                            <button class="btn btn-default btn-xs dropdown-toggle" :disabled="(DATATABLE.RECORDSFILTERED<=0)" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <span class="glyphicon glyphicon-list-alt"></span> Ações
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                <li><a v-on:click="gerarARQ('{{trim(str_replace(" ","_",$relatorio->nome))}}','xls');" style="cursor:default;">Exportar para XLS</a></li>
                                <li><a v-on:click="gerarARQ('{{trim(str_replace(" ","_",$relatorio->nome))}}','xlsx');" style="cursor:default;">Exportar para XLSX</a></li>
                                <li><a v-on:click="gerarPDF('{{trim(str_replace(" ","_",$relatorio->nome))}}','{{$relatorio->modoPDF}}');" style="cursor:default;">Gerar PDF</a></li>
                            </ul>
                        </div>

                    </div>
                    <div class="panel-body" id="conteudoRelatorio">

                        <table id="table" class="table table-striped table-bordered hoverable" style="width:100%;">
                            <thead>
                                <tr>
                                    @foreach($camposTabela as $campo)
                                        <th>{{$campo}}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

    </div> <!-- app -->
</section>

<script>
var app = new Vue( 
{
    el: '#app',
    delimiters: ["[[","]]"],
    data:
    {
        DATATABLE : 
        {
            TABLE : null,
            DATA : [],
            ALLDATA : [],
            RECORDSFILTERED : 0,
            PAGE : 1
        },
        @foreach($dados["parametros"] as $parametro)
            {{$parametro["nome"]}} : null,
        @endforeach
    },
    methods: 
    {   
        gerarPDF : function(titulo,modoPDF)
        {
            var filtro = {};
            @foreach($dados["parametros"] as $parametro)
                filtro.{{$parametro["nome"]}}  = this.{{$parametro["nome"]}};
            @endforeach
            var relatorioId = "{{$relatorio->id}}";
            return vform.ajax("get", '{{route("relatorios.padrao.executeQuery")}}',{filtro,relatorioId},function(response)
            {
                var pdf = new jsPDF(modoPDF,'pt','a4');
                var columns = [
                    @foreach($camposTabela as $campo)
                        "{{$campo}}",
                    @endforeach
                ];
                var dados = [];
                for ( var i=0; i < response.data.length ; i++ )
                {
                    dados.push( 
                    [ 
                        @foreach($camposTabela as $campo)
                            response.data[i].{{trim(str_replace(" ","_",$campo))}},
                        @endforeach
                    ] );
                }
                pdf.autoTable(columns, dados);
                var qtdePagina = pdf.internal.getNumberOfPages();
                for(i = 0; i < qtdePagina; i++) 
                { 
                    pdf.setPage(i); 
                    pdf.setFontSize(8);
                    pdf.text(42,25,pdf.internal.getCurrentPageInfo().pageNumber + "/" + qtdePagina);
                }
                pdf.save(titulo+".pdf");
                return toastr.info("PDF ["+titulo+".pdf] gerado com sucesso !!!");    
            });             
        },
        gerarARQ : function(titulo,formato)
        {
            var relatorioId = "{{$relatorio->id}}";
            var filtro = {};
            @foreach($dados["parametros"] as $parametro)
                filtro.{{$parametro["nome"]}}  = this.{{$parametro["nome"]}};
            @endforeach
            vform.ajax("get", '{{route("relatorios.padrao.executeQuery")}}',{filtro,relatorioId},function(response)
            {
                dados = "<table><tr>";
                @foreach($camposTabela as $campo)
                    dados+= "<th>{{$campo}}</th>",
                @endforeach
                dados += "</tr>";
                for ( var i=0; i < response.data.length ; i++ )
                {
                    dados+="<tr>";
                    @foreach($camposTabela as $campo)
                        dados+="<td>"+response.data[i].{{trim(str_replace(" ","_",$campo))}}+"</td>";
                    @endforeach
                    dados+="</tr>";
                }
                dados += "</table>";

                var dt = new Date();
                var a = document.createElement('a');
                var data_type = 'data:application/vnd.ms-excel';
                a.href = data_type + ', ' + dados;
                a.download = titulo+'.'+formato;
                a.click();
                return toastr.info("Arquivo ["+titulo+"."+formato+"] gerado com sucesso !!!");  
            }); 
        },
        filtrar : function()
        {
            $("#table").find("tbody").remove();
            if ( $.fn.DataTable.isDataTable( '#table' ) )
                this.DATATABLE.TABLE.destroy();
            this.tableInit();
        },
        tableInit : function()
        {
            var self = this;
            this.DATATABLE.RECORDSFILTERED = 0;
            this.DATATABLE.DATA = [];
            this.DATATABLE.ALLDATA = [];
            this.DATATABLE.TABLE = $("#table").removeAttr('width').DataTable(
            {
                serverSide: true,
                ordering: false,
                searching: false,
                infoFiltered : false,
                keys: 
                {
                    blurable : false
                },
                responsive: false,
                scrollX:    true,
                lengthChange: false,
                columnDefs: 
                [
                    { targets: 'no-sort', orderable: false }
                ],
                oLanguage:
                {
                    "sEmptyTable": "Nenhum registro encontrado",
                    "sInfo": "Mostrando de _START_ até _END_",
                    "sInfoEmpty": "Mostrando 0 até 0",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "_MENU_ resultados",
                    "sInfoFiltered": "(Total : _MAX_)"
                },
                ajax: function ( data, callback, settings ) 
                {
                    var page = data.draw - 1 ;
                    var relatorioId = "{{$relatorio->id}}";
                    var filtro = {};
                    @foreach($dados["parametros"] as $parametro)
                        filtro.{{$parametro["nome"]}}  = self.{{$parametro["nome"]}};
                    @endforeach
                    vform.ajax("get", '{{route("relatorios.padrao.executeQueryPaginado")}}',{page,filtro,relatorioId},function(response)
                    {
                        console.log(response);
                        var recordsTotal = response.qtde;
                        self.DATATABLE.RECORDSFILTERED += response.data.length;
                        if(page==0)
                        {
                            if(recordsTotal>0)
                                toastr.success(recordsTotal + " resultados encontrados !!");
                            else
                                toastr.info("Nenhum resultado encontrado !!");
                        }
                        for ( var i=0; i < response.data.length ; i++ )
                        {
                            // return false;
                            self.DATATABLE.DATA.push( 
                            [ 
                                @foreach($camposTabela as $campo)
                                    response.data[i].{{trim(str_replace(" ","_",$campo))}},
                                @endforeach
                            ] );
                        }
                        callback( 
                        {
                            draw:data.draw,
                            data: self.DATATABLE.DATA,
                            recordsTotal: recordsTotal,
                            recordsFiltered: self.DATATABLE.RECORDSFILTERED
                        });
                    },false); //ajax
                },
                scrollY: 300,
                scroller: 
                {
                    loadingIndicator: true
                }
            });
            movepage("conteudo");
        }
    }
});

vloading.stop();
</script>
@stop