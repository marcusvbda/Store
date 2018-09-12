//<div class="col-md-4">
//    <label>TIPO LOGRADOURO</label>  
//    <div class="input-group mb-3">
//        <input type="number" class="form-control form-control-sm col-md-3" id="txtTipoCodigo" tabIndex=1>
//        <input type="text" class="form-control form-control-sm" id="txtTipoDescricao" disabled>
//        <div class="input-group-append">
//            <button type="button" class="btn btn-primary" id="btnTipoSelect">
//                <i class="icon mdi mdi-search"></i>
//            </button>
//        </div>
//    </div>
//</div>
// fkSelect.init({
//     title : "Seleção de PAÍS",
//     fields : [{
//         field : "CODIGO",
//         label : "Código",
//     },{
//         field : "NOME",
//         label : "Nome",
//     },{
//         field : "SIGLA",
//         label : "Sigla",
//     }],
//     btn   : "#btnPaisSelect",
//     id    : "#txtPaisCodigo",
//     desc  : "#txtPaisDescricao",
//     query : "select * from PAIS",
//     route : "@Url.RouteUrl("helper.query")",
//     field_id   : "CODIGO",
//     field_desc : "NOME",
// });
class fkSelect{
    static init(parameters)
    {   
        if (!parameters.btn)
            parameters.btn = "btn";
        if (!parameters.title)
            parameters.title = "";
        if (!parameters.desc)
            parameters.desc = "desc";
        if (!parameters.size)
            parameters.size = "50%";
        if (!parameters.query)
            parameters.query = "";
        if (!parameters.key)
            parameters.key = "CODIGO";
        if (!parameters.route)
            parameters.route = "";
        if (!parameters.fields)
            parameters.fields = [];
        if (!parameters.field_id)
            parameters.field_id = "CODIGO";
        if (!parameters.field_desc)
            parameters.field_desc = "DESCRICAO";

        $(parameters.btn).click(function()
        {
            fkSelect.createDiv(parameters);
        });
        $(parameters.id).blur(function()
        {
            fkSelect.query(parameters);
        });
        $(parameters.id).on('keydown', function (event) {
            if (event.which == 13) 
            {
                fkSelect.query(parameters);
                $(this).next("input").focus();
                var nextElement = $('[tabindex="' + (this.tabIndex+1) + '"]');
                if(!nextElement[0])
                    return $(this).blur();
                if (nextElement.length)
                    nextElement.focus()
                else
                    $('[tabindex="1"]').focus();
            }
        });
    }
    static query(parameters)
    {
        var id = $(parameters.id).val().trim();
        if(id!="")
        {
            var query = parameters.query+" where "+parameters.key+"="+id;
            // console.log(query);
            vform.ajax("get",parameters.route,{query,format:"JSON"},function(response)
            {
                if(!response.success)
                {
                    messageBox.simple("Oops","Não encontrado, verifique...","error");   
                    return fkSelect.clean(parameters);
                }
                var countResult = response.data.length;
                if(countResult==1)
                {
                    $(parameters.id).val(response.data[0][parameters.field_id]);
                    $(parameters.desc).val(response.data[0][parameters.field_desc]);
                }
                else
                {
                    messageBox.simple("Oops","Não encontrado, verifique...","error");
                    return fkSelect.clean(parameters);
                }
            });
        }
        else
            return fkSelect.clean(parameters);
    }
    static createDiv(parameters)
    {
        vform.ajax("get",parameters.route,{query:parameters.query,format:"JSON"},function(response)
        {
            if(!response.success)
                return messageBox.simple("Oops","Não encontrado, verifique...","error");
            var countResult = response.data.length;
            if(countResult>0)
            {
                var html  = '<div id="__foreignSelectContent__" style="display:none;">'+
                                '<div class="row">'+
                                    '<div class="col-md-12">'+
                                        '<table>'+
                                            '<thead>'+
                                                '<tr>';
                
                if(parameters.fields.length>0)
                {
                    for(var i=0;i<parameters.fields.length;i++)
                    {
                        if(i==0)
                            html+= '<th width="1%">'+parameters.fields[i].label+'</th>';
                        else 
                            html+= '<th>'+parameters.fields[i].label+'</th>';
                    }              
                }
                else
                {
                    var keys = Object.keys(response.data[0]);
                    for(var i=0;i<keys.length;i++)
                    {
                        if(i==0)
                            html+= '<th width="1%">'+keys[i]+'</th>';
                        else 
                            html+= '<th>'+keys[i]+'</th>';
                    }   
                }
                                         

                html +=                          '</tr>'+
                                            '</thead>'+
                                            '<tbody></tbody>'+
                                        '</table>'+
                                    '</div>'+
                                '</div>'+
                            '</div>';
                $('body').append(html); 

                var table = ezCrud.init({
                    div : "#__foreignSelectContent__",
                    lang : "PTBR",
                    perpage : 5,
                    defaultSort : 1,
                    doubleClick : function(data)
                    {
                        $(parameters.id).val(data[0]);
                        $(parameters.desc).val(data[1]);
                        $("#__foreignSelectContent__").dialog('close');
                    }
                });
                for(i = 0; i< response.data.length;i++)
                {
                    ezCrud.addRow(table,response.data[i]);
                    console.log(response.data[i]);
                }      
                fkSelect.openDialog(parameters);
            }
            else
                return messageBox.simple("Oops","Não encontrado, verifique...","error");
        });
        
        
    }
    static openDialog(parameters)
    {
        $("#__foreignSelectContent__").dialog(
        {
            modal: true, title: parameters.title, zIndex: 10000, autoOpen: true,
            width: parameters.size, resizable: false, draggable: false,
            open: function (event, ui) {
                $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
            },
            buttons:
            {
                Cancelar:{
                    click : function()
                    {
                        $("#__foreignSelectContent__").dialog('close');
                        fkSelect.clean(parameters);
                    },
                    text:'Cancelar',
                    class:'btn btn-danger'
                }
            }
        });
        $('#__foreignSelectContent__').on('dialogclose', function(event) {
            $('#__foreignSelectContent__').remove();
        });
    }
    static clean(parameters)
    {
        $(parameters.id).val("");
        $(parameters.desc).val("");
    }
}