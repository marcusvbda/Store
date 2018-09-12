class dataTableCrud 
{
    constructor(_param)
    {
        this.parametros = _param;
        this.table = $(this.parametros.table);
        if(!this.parametros.order)
            this.parametros.order = 0;

        if(!this.parametros.crud)
            this.parametros.crud = false;
        else
        {
            this.montarCrud();
            this.parametros.crud = true;
        }
        var dt = this.init();
        $(this.table).toggle(250);
        return dt;
    }

    init()
    {
        var self = this;
        var dt = $(this.table).DataTable(
        {
            "destroy" : true,
            "responsive": true,
            "order": [[self.parametros.order , "desc" ]],
            "searching": false,
            "pageLength": self.parametros.perpage,
            "lengthChange": false,
            columnDefs: [
                { targets: 'no-sort', orderable: false }
            ],
            "oLanguage":
            {
                "sEmptyTable": "Nenhum registro encontrado",
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "_MENU_ resultados",
                "sZeroRecords": "Nenhum registro encontrado",
                "oPaginate": {
                    "sNext": "Próximo",
                    "sPrevious": "Anterior",
                    "sFirst": "Primeiro",
                    "sLast": "Último"
                }
            }
        });
        if(this.parametros.onload)
            this.parametros.onload();
        return dt;
    }

    montarCrud()
    {
        var thead = $(this.table).find("thead");
        var tr = $(thead).find("tr");
        var td = $(this.table).find("tbody").find("tr");

        var btnStore = "<a class='btnCrud btn BtnStore btn-default btn-sm'><span class='glyphicon glyphicon-plus'></span> Cadastrar</a>";
        // $(this.table).before("<div class='row'><div class='col-md-12 text-right "+this.parametros.table.replace("#", "")+"__btnStore__'>"+btnStore+"</div></div>")
        $(tr).prepend("<th width='1%' class='btnStorePlace no-sort'></th>");
        $($(".btnStorePlace")[0]).html("<div class='"+this.parametros.table.replace("#", "")+"__btnStore__'>"+btnStore+"</div>");
        // console.log($(".btnStorePlace")[1]);
        var self = this;
        td.each(function()
        {
            var btnAcoes = '<div class="dropdown btnCrud">'+ 
            '    <a class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" style="width="100%" aria-haspopup="true" aria-expanded="true">'+ 
            '<span class="glyphicon glyphicon-cog"></span> Ações <span class="caret"></span>'+ 
            '    </a>'+ 
            '    <ul class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenu1">'+ 
            '        <li><a class="'+self.parametros.table.replace("#", "")+" btnCrud BtnView "+self.parametros.table.replace("#", "")+"__btnView__"+'">Visualizar</a></li>'+ 
            '        <li><a class="'+self.parametros.table.replace("#", "")+"__btnCrud__ btnCrud BtnEdit "+self.parametros.table.replace("#", "")+"__btnEdit__"+'">Editar</a></li>'+ 
            '        <li role="separator" class="divider"></li>'+ 
            '        <li><a class="'+self.parametros.table.replace("#", "")+"__btnCrud__ btnCrud BtnDestroy "+self.parametros.table.replace("#", "")+"__btnDestroy__"+'" style="color:red;">Excluir</a></li>'+ 
            '    </ul>'+ 
            '</div>';
            $(this).addClass("table_row");
            $(this).prepend("<th width='1%' class='no-sort'>"+btnAcoes+"</th>");
        });

        if(!this.parametros.formCrud)
        {
            var form = "<crud style='display:none;' id='"+this.parametros.table.replace("#","")+"__CRUD__'><div class='row' style='padding-left:20px;padding-right:20px;'>";
            form += "<form id='"+this.parametros.table.replace("#", "")+"__formCrud__" +"' method='POST' action=''>"+
                            "<input type='hidden' style='display:none;' name='_method' id='"+this.parametros.table.replace("#", "")+"__formCrud__"+"_method' value='POST'/>"+
                            "<input type='hidden' style='display:none;' class='"+this.parametros.table.replace("#", "")+"_primarykey_' value=''/>"+
                            "<input type='hidden' style='display:none;' name='_token' id='_token' value='"+$('meta[name="csrf-token"]').attr('content') +"'/>";
            form+="<input type='submit' id='"+this.parametros.table.replace("#", "")+"__formCrud__SUBMIT"+"' style='display:none;'>";
            
            for(var i = 0; i<this.parametros.campos.length;i++)
            {
                if((this.parametros.campos[i].type=="text")||(this.parametros.campos[i].type=="number")||(this.parametros.campos[i].type=="email")||(this.parametros.campos[i].type=="date"))
                {
                    form+="<div class='"+this.parametros.campos[i].divClass+"' style='margin-bottom: 7px;'>";
                    form+="<label>";
                    if(this.parametros.campos[i].required)
                        form+="<i class='text-danger'>*</i> ";
                    
                    form+=this.parametros.campos[i].label+"</label>"+
                        "<input type='"+this.parametros.campos[i].type+"'"+
                        "maxlength='"+this.parametros.campos[i].maxlength+"'"+
                        "max='"+this.parametros.campos[i].max+"'"+
                        "min='"+this.parametros.campos[i].min+"'";
                        if(this.parametros.campos[i].readonly)
                            form+=" readonly ";
                        if(this.parametros.campos[i].value)
                            form+="value='"+this.parametros.campos[i].value+"'";
                        if(this.parametros.campos[i].uppercase)
                            form += " style='text-transform:uppercase' ";
                        if(this.parametros.campos[i].required)
                            form += "required ";
                        if(this.parametros.campos[i].visible == false)
                            form += " style='display:none;' ";
                        form += "id='"+this.parametros.table.replace("#", "")+"__formCrud__"+this.parametros.campos[i].collumn+"'"+" name='"+this.parametros.campos[i].collumn+"'"+
                        "class='form form-control form-control-xs'/>";
                    form+="</div>";
                }
                else if(this.parametros.campos[i].type=="select")
                {
                    form+="<div class='"+this.parametros.campos[i].divClass+"'>";
                    form+="<label>";
                    if(this.parametros.campos[i].required)
                        form+="<i class='text-danger'>*</i> ";
                    
                    form+=this.parametros.campos[i].label+"</label>"+
                        "<select required='"+this.parametros.campos[i].required+"'"+
                        "id='"+this.parametros.table.replace("#", "")+"__formCrud__"+this.parametros.campos[i].collumn+"'"+" name='"+this.parametros.campos[i].collumn+"'"+
                        "class='form form-control form-control-xs'>";
                    for(var y =0; y<this.parametros.campos[i].options.length;y++)
                    {
                        form+="<option ";
                        if(this.parametros.campos[i].value)
                        {
                            if(this.parametros.campos[i].value==this.parametros.campos[i].options[y].value)
                                form += " selected ";
                        }
                        form += "value='"+this.parametros.campos[i].options[y].value+"'>"+this.parametros.campos[i].options[y].text+"</option>";
                    }
                    form+="</select>";   
                    form+="</div>";   
                }
                else if(this.parametros.campos[i].type=="checkbox")
                {
                    form+="<div class='"+this.parametros.campos[i].divClass+"' style='margin-bottom: 7px;'>";
                    form+="<label>"+
                        "<input type='"+this.parametros.campos[i].type+"'"+
                        "maxlength='"+this.parametros.campos[i].maxlength+"'"+
                        "max='"+this.parametros.campos[i].max+"'"+
                        "min='"+this.parametros.campos[i].min+"'";
                        if(this.parametros.campos[i].readonly)
                            form+=" readonly ";
                        if(this.parametros.campos[i].checked)
                            form+=" checked ";
                        if(this.parametros.campos[i].visible == false)
                            form += " style='display:none;' ";
                        form += "id='"+this.parametros.table.replace("#", "")+"__formCrud__"+this.parametros.campos[i].collumn+"'"+" name='"+this.parametros.campos[i].collumn+"'/>";
                    form+=" "+this.parametros.campos[i].label+"</label></div>";
                }
            }
            form+="</div>";
            form+="</div></form>";
            form+="</crud>";
        }
        $(this.table).after(form);

        $(self.parametros.table+"__formCrud__").submit(function(event)
        {
            vloading.start();
            $(".btnConfirm__formCrud__").data('loading-text', "<span class='glyphicon glyphicon-repeat normal-right-spinner'></span> Processando...");
            $(".btnConfirm__formCrud__").button("loading");
        });


        $(document).on("click",this.parametros.table.replace("#", ".")+"__btnView__",function()
        {   
            var data = {};
            $(self.parametros.table+"__formCrud__").trigger("reset");            
            data[$(this).closest("tr").attr("primarykey")]=$(this).closest("tr").attr("value");
            vform.ajax("get",self.parametros.routeGet,data,function(response)
            {
                var formInputs = $(self.parametros.table+"__formCrud__").find("input , select, textarea");
                $(self.parametros.table+"__formCrud___method").val("");
                $(self.parametros.table+"__formCrud__").attr("method","");
                $(self.parametros.table+"__formCrud__").attr("action","");
                if(response.data.length)
                {
                    for(var y = 0; y < response.data.length; y++)
                    {                            
                        for(var i = 2; i<formInputs.length;i++)
                        {
                            var name = $(formInputs[i]).attr("name");   
                            if(( name !="_token" ) && ( name != "_method" ))
                            {
                                $(formInputs[i]).attr("disabled",true); 

                                if($(formInputs[i]).is("select"))
                                {
                                    $("#"+$(formInputs[i]).attr("id")+" select").val(response.data[y][ $(formInputs[i]).attr("id").replace(self.parametros.table.replace("#", "")+"__formCrud__","")]).change();
                                }
                                else if($(formInputs[i]).attr('type')=="checkbox")   
                                {              
                                    var checked = response.data[y][ $(formInputs[i]).attr("id").replace(self.parametros.table.replace("#", "")+"__formCrud__","")];
                                    $(formInputs[i]).attr("checked",checked);
                                }
                                else
                                {
                                    $(formInputs[i]).val(response.data[y][ $(formInputs[i]).attr("id").replace(self.parametros.table.replace("#", "")+"__formCrud__","")]);
                                }
                            }
                        }
                    }
                }
                else
                {
                    for(var i = 2; i<formInputs.length;i++)
                    {
                        var name = $(formInputs[i]).attr("name");                      
                        if(( name !="_token" ) && ( name != "_method" ))
                        {
                            $(formInputs[i]).attr("disabled",true); 
                            if($(formInputs[i]).is("select"))
                            {
                                $("#"+$(formInputs[i]).attr("id")+" select").val(response.data[ $(formInputs[i]).attr("id").replace(self.parametros.table.replace("#", "")+"__formCrud__","")]).change();
                            }
                            else if($(formInputs[i]).attr('type')=="checkbox")   
                            {              
                                var checked = response.data[ $(formInputs[i]).attr("id").replace(self.parametros.table.replace("#", "")+"__formCrud__","")];
                                $(formInputs[i]).attr("checked",checked);
                            }
                            else
                            {
                                var attrId = $(formInputs[i]).attr("id");
                                if (typeof attrId !== typeof undefined && attrId !== false)
                                    $(formInputs[i]).val(response.data[ attrId.replace(self.parametros.table.replace("#", "")+"__formCrud__","")]);
                            }
                        }
                    }
                }
                
                $(self.parametros.table+"__CRUD__").dialog(
                {
                    modal: true, title: "Visualização", zIndex: 10000, autoOpen: true,
                    width: self.parametros.size, resizable: false, draggable: true,
                    open: function (event, ui) 
                    {
                        if(self.parametros.summernote)
                        {
                            for(var i = 0; i<self.parametros.summernote.length;i++)
                            {
                                $(self.parametros.summernote[i].id).summernote('code',$(self.parametros.summernote[i].id).val());
                                $(self.parametros.summernote[i].id).summernote('disable');
                            }
                        }
                        $(".ui-dialog-titlebar-close", ui.dialog | ui).html("<span class='glyphicon glyphicon-remove'></span>");
                        $(".ui-dialog-titlebar-close", ui.dialog | ui).show();
                    },
                    buttons:{}
                });
            });
        });

        $(document).on("click",this.parametros.table.replace("#", ".")+"__btnEdit__",function()
        {
            var data = {};
            $(self.parametros.table+"__formCrud__").trigger("reset"); 
            var primarykey = $(this).closest("tr").attr("primarykey");           
            var primarykeyVal = $(this).closest("tr").attr("value");  
            data[primarykey] = primarykeyVal;
            vform.ajax("get",self.parametros.routeGet,data,function(response)
            {
                var formInputs = $(self.parametros.table+"__formCrud__").find("input , select, textarea");
                $(self.parametros.table+"__formCrud___method").val("PUT");
                $(self.parametros.table.replace("#",".")+"_primarykey_").attr("name",primarykey);
                $(self.parametros.table.replace("#",".")+"_primarykey_").val(primarykeyVal);
                $(self.parametros.table+"__formCrud__").attr("method","POST");
                $(self.parametros.table+"__formCrud__").attr("action",self.parametros.routePut);
                
                if(response.data.length)
                {
                    for(var y = 0; y < response.data.length; y++)
                    {                            

                        for(var i = 2; i<formInputs.length;i++)
                        {
                            var name = $(formInputs[i]).attr("name");                      
                            if(( name !="_token" ) && ( name != "_method" ))
                            {
                                $(formInputs[i]).attr("disabled",false);
                                if($(formInputs[i]).is("select"))
                                {
                                    $("#"+$(formInputs[i]).attr("id")+" select").val(response.data[y][ $(formInputs[i]).attr("id").replace(self.parametros.table.replace("#", "")+"__formCrud__","")]).change();
                                } 
                                if($(formInputs[i]).attr('type')=="checkbox")   
                                {              
                                    var checked = response.data[y][ $(formInputs[i]).attr("id").replace(self.parametros.table.replace("#", "")+"__formCrud__","")];
                                    $(formInputs[i]).attr("checked",checked);
                                }
                                else
                                {
                                    $(formInputs[i]).val(response.data[y][ $(formInputs[i]).attr("id").replace(self.parametros.table.replace("#", "")+"__formCrud__","")]);
                                }
                            }
                        }
                    }
                }
                else
                {
                    for(var i = 2; i<formInputs.length;i++)
                    {
                        var name = $(formInputs[i]).attr("name");                      
                        if(( name !="_token" ) && ( name != "_method" ))
                        {
                            $(formInputs[i]).attr("disabled",false); 
                            if($(formInputs[i]).attr('type')=="checkbox")   
                            {              
                                var checked = response.data[ $(formInputs[i]).attr("id").replace(self.parametros.table.replace("#", "")+"__formCrud__","")];
                                $(formInputs[i]).attr("checked",checked);
                            }
                            else
                            {
                                var attrId = $(formInputs[i]).attr("id");
                                if (typeof attrId !== typeof undefined && attrId !== false)
                                    $(formInputs[i]).val(response.data[ attrId.replace(self.parametros.table.replace("#", "")+"__formCrud__","")]);
                            }
                        }
                    }
                }
                $(self.parametros.table+"__CRUD__").dialog(
                {
                    modal: true, title: "Edição", zIndex: 10000, autoOpen: true,
                    width: self.parametros.size, resizable: false, draggable: true,
                    open: function (event, ui) {
                        if(self.parametros.summernote)
                        {
                            for(var i = 0; i<self.parametros.summernote.length;i++)
                            {
                                $(self.parametros.summernote[i].id).summernote('code',$(self.parametros.summernote[i].id).val());
                                $(self.parametros.summernote[i].id).summernote('enable');
                            }
                        }
                        $(".ui-dialog-titlebar-close", ui.dialog | ui).html("<span class='glyphicon glyphicon-remove'></span>");
                        $(".ui-dialog-titlebar-close", ui.dialog | ui).show();
                    },
                    buttons:{
                        cancel : 
                        {
                            click: function () {
                                // param.confirm(vCrud.lang[param.lang].confirmCancel,function(callback){
                                    $(self.parametros.table+"__CRUD__").dialog('close');
                                // });
                            },
                            class: 'btn btn-danger',
                            text: "Cancelar"
                        },
                        store : 
                        {
                            click: function () 
                            {
                                messageBox.confirm("Confirmação","Confirma cadastro ?",function(callback)
                                {
                                    $(self.parametros.table+"__formCrud__SUBMIT").trigger( "click" );
                                });
                            },
                            class: 'btnConfirm__formCrud__ btn btn-primary',
                            text:  "Salvar"
                        }
                    }
                });
            });
        });
        
        $(document).on("click",this.parametros.table.replace("#", ".")+"__btnDestroy__",function()
        {
            var data = {};
            var primarykey = $(this).closest("tr").attr("primarykey");           
            var primarykeyVal = $(this).closest("tr").attr("value");  
            data[primarykey] = primarykeyVal;
            messageBox.confirm("Confirmação","Confirma exclusão ?",function()
            {
                vform.ajax("delete",self.parametros.routeDelete,data,function(response){
                    console.log(response);
                    if(!response.success)
                        return messageBox.simple("Ooops",response.message,"error");
                    return location.reload();   
                });
            });
        });

        var self = this;
        $(document).on("click",this.parametros.table.replace("#", ".")+"__btnStore__",function()
        {
            $(self.parametros.table+"__formCrud__").trigger("reset");            
            $(self.parametros.table+"__formCrud__").find("input,select").each(function()
            {
                $(this).attr("disabled",false);
            });      
            $(self.parametros.table+"__formCrud___method").val("POST");
            $(self.parametros.table.replace("#",".")+"_primarykey_").removeAttr("name");
            $(self.parametros.table.replace("#",".")+"_primarykey_").val("");
            $(self.parametros.table+"__formCrud__").attr("method","POST");
            $(self.parametros.table+"__formCrud__").attr("action",self.parametros.routePost);
            if(self.parametros.summernote)
            {
                for(var i = 0; i<self.parametros.summernote.length;i++)
                {
                    $(self.parametros.summernote[i].id).summernote('code',null);
                }
            }
            $(self.parametros.table+"__CRUD__").dialog(
            {
                modal: true, title: "Cadastro", zIndex: 10000, autoOpen: true,
                width: self.parametros.size, resizable: false, draggable: false,
                open: function (event, ui) 
                {
                    $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
                },
                buttons:{
                    cancel : 
                    {
                        click: function () 
                        {
                            messageBox.confirm("Confirmação","Deseja mesmo cancelar ?",function(callback)
                            {
                                $(self.parametros.table+"__CRUD__").dialog('close');
                            });
                        },
                        class: 'btn btn btn-danger',
                        text: "Cancelar"
                    },
                    store : 
                    {
                        click: function () 
                        {
                            $(self.parametros.table+"__formCrud__SUBMIT").trigger( "click" );
                        },
                        class: 'btnConfirm__formCrud__ btn btn btn-primary',
                        text: "Salvar"
                    }
                }
            });
        });

    }
}