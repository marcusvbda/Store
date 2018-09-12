function openRoute(rota) {
    vloading.start();
    window.location.href = rota;
}

function dataTable(tabela, param) {
    if (!param.sort)
        param.sort = false;

    if (!param.search)
        param.search = false;

    if (!param.perpage)
        param.perpage = 10;

    if (!param.perpagemenu)
        param.perpagemenu = false;

    if (!param.orderColl)
        param.orderColl = 0;

    if (!param.orderType)
        param.orderType = "asc";


    var table = $(tabela).DataTable({
        "bSort": param.sort,
        "searching": param.search,
        "pageLength": param.perpage,
        "lengthChange": param.perpagemenu,
        responsive: true,
        destroy: true,
        "order": [param.orderColl, param.orderType],
        "oLanguage": {
            "sEmptyTable": "Nenhum registro encontrado",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "_MENU_ resultados",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum registro encontrado",
            "sSearch": "Filtro",
            "oPaginate": {
                "sNext": "Próximo",
                "sPrevious": "Anterior",
                "sFirst": "Primeiro",
                "sLast": "Último"
            },
            "oAria": {
                "sSortAscending": ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            }
        }
    }, {
        "ordering": true
    });

    if (typeof param.dblclick === "function") {
        $(tabela + ' tbody').on('dblclick', 'tr', function() {
            param.dblclick(table.row(this).data());
        });
    }
    if (typeof param.click === "function") {
        $(tabela + ' tbody').on('click', 'tr', function() {
            param.click(table.row(this).data());
        });
    }

    if (param.detail) {
        $(tabela + ' tbody').on('click', 'td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = table.row(tr);

            if (row.child.isShown()) {
                $(this).css("background", "url('/assets/img/plus-flat.png') no-repeat center center");
                $(this).css("background-size", "30px 30px");
                row.child.hide();
                tr.removeClass('shown');
            } else {
                $(this).css("background", "url('/assets/img/minus-flat.png') no-repeat center center");
                $(this).css("background-size", "30px 30px");
                var detail = table.row(this).data()[0];
                row.child(detail).show();
                tr.addClass('shown');
            }
        });
    }

    $(tabela).show();
    return table;
}

function modal(form, title, size, buttons) 
{
    $(form).dialog({
        modal: true,
        title: title,
        zIndex: 10000,
        autoOpen: true,
        width: size,
        resizable: false,
        draggable: true,
        open: function(event, ui) {
            $(".ui-dialog-titlebar-close", ui.dialog | ui).html("X");
        },
        buttons
    });
}

class messageBox {
    static confirm(title, msg, callback) {
        swal({
                title: title,
                text: msg,
                icon: "warning",
                buttons: true,
                focusConfirm: true,
                buttons: {
                    cancel: {
                        text: "Cancelar",
                        value: false,
                        visible: true,
                        className: "btn btn-danger",
                        closeModal: true,
                    },
                    confirm: {
                        text: "Confirmar",
                        value: true,
                        visible: true,
                        className: "btn btn-primary",
                        closeModal: true,
                    }
                }
            })
            .then((value) => {
                if (value) {
                    callback();
                }
            });
    }

    static simple(title, msg, icon) {
        swal({
            title: title,
            text: msg,
            icon: icon
        });
    }

    static alert(title, msg, type, time, callback) {
        swal({
            position: 'top-end',
            icon: type,
            text: msg,
            title: title,
            showConfirmButton: false,
            timer: time
        }).then((result) => {
            callback();
        });
    }

    static noty(title, msg, type, time) {
        swal({
            position: 'top-end',
            icon: type,
            title: title,
            text: msg,
            button: false,
            timer: time
        });
    }
}

function gera_cor()
{
    var hexadecimais = '0123456789ABCDEF';
    var cor = '#';
    for (var i = 0; i < 6; i++ ) 
    {
        cor += hexadecimais[Math.floor(Math.random() * 16)];
    }
    return cor;
}


function formatJsonDate(date, locale) {
    date = parseInt(date.replace("/Date(", "").replace(")/", ""));
    date = new Date(date);
    return date.toLocaleDateString(locale);
}

function strToCNPJ(text) {
    return text.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/g, "\$1.\$2.\$3\/\$4\-\$5");
}

function strToTelefone(text, tamanho) {
    if (tamanho == 11)
        return text.replace(/(\d{2})(\d{5})(\d{4})/, "($1) $2-$3");
    else if (tamanho == 10)
        return text.replace(/(\d{2})(\d{4})(\d{4})/, "($1) $2-$3");
}

function strToCPF(text) {
    return text.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/g, "\$1.\$2.\$3\-\$4");
}

function aspas(text) {
    return '"' + text + '"';
}

function partialViewDialog(titulo, url, width) {
    bloquearTela("Aguarde...");
    $("#dialog").load(url, function() {
        $(this).dialog({
            modal: true,
            title: titulo,
            zIndex: 10000,
            autoOpen: true,
            width: width,
            resizable: false,
            draggable: false,
            open: function(event, ui) {
                DesbloquearTela();
                $(".ui-widget-overlay").attr("z-index", "9999");
            },
        });
    });
}

Number.prototype.pad = function(size) {
    var s = String(this);
    while (s.length < (size || 2)) {
        s = "0" + s;
    }
    return s;
}

function movepage(section)
{
    var position =  $('#'+section).first().offset().top-70;
    $('html, body').animate({
        scrollTop: (position)
    }, 450);
}



function addError(field, msg) {
    $(field).parsley().removeError('forcederror');
    $(field).parsley().addError('forcederror', {
        message: msg,
        updateClass: true
    });
}

String.prototype.replaceAll = function(find, replace) {
    var str = this;
    return str.replace(new RegExp(find, 'g'), replace);
};

function insertParamNewTab(key, value) {
    key = escape(key);
    value = escape(value);
    var kvp = document.location.search.substr(1).split('&');
    var i = kvp.length;
    var x;
    while (i--) {
        x = kvp[i].split('=');
        if (x[0] == key) {
            x[1] = value;
            kvp[i] = x.join('=');
            break;
        }
    }
    if (i < 0) {
        kvp[kvp.length] = [key, value].join('=');
    }
    var url = "";
    if (document.location.search == "")
        url = document.location.href + kvp.join('?');
    else
        url = document.location.href + kvp.join('&');
    window.open(url);
}


function insertParam(key, value) {
    vloading.start();
    key = escape(key);
    value = escape(value);
    var kvp = document.location.search.substr(1).split('&');
    var i = kvp.length;
    var x;
    while (i--) {
        x = kvp[i].split('=');

        if (x[0] == key) {
            x[1] = value;
            kvp[i] = x.join('=');
            break;
        }
    }
    if (i < 0) {
        kvp[kvp.length] = [key, value].join('=');
    }
    document.location.search = kvp.join('&');
}

function removeParam(key, sourceURL) {
    var rtn = sourceURL.split("?")[0],
        param,
        params_arr = [],
        queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
    if (queryString !== "") {
        params_arr = queryString.split("&");
        for (var i = params_arr.length - 1; i >= 0; i -= 1) {
            param = params_arr[i].split("=")[0];
            if (param === key) {
                params_arr.splice(i, 1);
            }
        }
        rtn = rtn + "?" + params_arr.join("&");
    }
    return rtn;
}

function initFiltroAvancado(div) {
    var camposFiltro = $(div + "_campos").find(".filtroAvancado");
    var label = "";
    camposFiltro.each(function() {
        if ($(this).val()) {
            var valor = "";
            label = $(this).attr("label");
            if( $(this).hasClass("select") )
            {
                value = $('option:selected',this);
                valor =  value[0].innerHTML;
            }
            else
                valor = $(this).val();
            var name = $(this).attr("name");
            var id = "labelAvancado_" + name;
            $(div).append('<span id="' + id + '" label="' + label + '" name="' + name + '" class="label label-primary" style="margin-left:5px;cursor: default;">' + 
                label + " [" + valor + "]"+
                '<a onclick=excluirFiltroAvancado("' + div + '","' + id + '")> <span class="badge">X</span></a></span>');
        }
    });
}



function excluirFiltroAvancado(div, id) {
    var _label = $("#" + id).attr("label");
    var rota =  window.location.href;
    $("#" + id).remove();
    var camposFiltro = $(div + "_campos").find(".filtroAvancado");
    camposFiltro.each(function() 
    {
        if ($(this).attr("label") == _label)
        {
            $(this).val("");
            rota = removeParam($(this).attr("name"), rota)
        }
    });
    return openRoute(rota);
}

function insertParam(key, value) {
    vloading.start();
    key = escape(key);
    value = escape(value);
    var kvp = document.location.search.substr(1).split('&');
    var i = kvp.length;
    var x;
    while (i--) {
        x = kvp[i].split('=');
        if (x[0] == key) {
            x[1] = value;
            kvp[i] = x.join('=');
            break;
        }
    }
    if (i < 0) {
        kvp[kvp.length] = [key, value].join('=');
    }
    return kvp.join('&');
}

function removeParam(key, sourceURL) {
    var rtn = sourceURL.split("?")[0],
        param,
        params_arr = [],
        queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
    if (queryString !== "") {
        params_arr = queryString.split("&");
        for (var i = params_arr.length - 1; i >= 0; i -= 1) {
            param = params_arr[i].split("=")[0];
            if (param === key) {
                params_arr.splice(i, 1);
            }
        }
        rtn = rtn + "?" + params_arr.join("&");
    }
    return rtn;
}

Date.prototype.addDays = function(days) {
    var date = new Date(this.valueOf());
    date.setDate(date.getDate() + days);
    return date;
}

function loadingElement(element)
{
    // vloading.start();
    $(element).data('loading-text', "<span class='glyphicon glyphicon-repeat normal-right-spinner'></span> Processando...");
    $(element).button("loading");
}

var stringCor = function(str) {
  var hash = 0;
  for (var i = 0; i < str.length; i++) {
    hash = str.charCodeAt(i) + ((hash << 5) - hash);
  }
  var colour = '#';
  for (var i = 0; i < 3; i++) {
    var value = (hash >> (i * 8)) & 0xFF;
    colour += ('00' + value.toString(16)).substr(-2);
  }
  return colour;
} 

