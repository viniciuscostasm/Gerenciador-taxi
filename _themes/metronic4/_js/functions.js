var tinymceConfig = {
    theme: "advanced",
    plugins: "autolink,lists,style,layer,table,advhr,advimage,advlink,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",
    theme_advanced_buttons1: "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
    theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,cleanup,code,|,forecolor,backcolor",
    theme_advanced_buttons3: "tablecontrols,|,hr,removeformat,|,sub,sup,|,charmap,iespell,advhr",
    theme_advanced_toolbar_location: "top",
    theme_advanced_toolbar_align: "left",
    theme_advanced_statusbar_location: "none"
};
var tinymceConfigSmall = {
    theme: "advanced",
    plugins: "autolink,lists,style,layer,advhr,advlink,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",
    theme_advanced_buttons1: "bold,italic,underline,|,fontsizeselect,|,forecolor,backcolor,|,bullist,numlist,|,outdent,indent",
    theme_advanced_toolbar_location: "top",
    theme_advanced_toolbar_align: "left",
    theme_advanced_statusbar_location: "none"
};

$(function() {
    setInterval(ping, 5 * 60 * 1000);
    Metronic.init(); // init metronic core components
    Layout.init(); // init current layout
});

/**
 *
 * @param {type} div
 * @param {type} acao
 * @param {type} acaoTitulo
 * @param {type} tab
 * @returns {undefined}
 */
function showForm(div, acao, acaoTitulo, tab) {
    $('#divTable').hide();
    $('.divForm').hide();
    $('#' + div).show();

    if (tab !== undefined)
        $('#' + div + ' a[href="#' + tab + '"]').tab('show');

    if (acao === 'ins') {
        $('#f__btn_excluir').hide();

        var filtro = $('#filter').serializeObject();

        $.each(filtro, function(k, v) {
            if (v.length > 0) {
                var i = k.replace('p__', '');
                $('#' + div + ' #' + i).val(v);
                $('#' + div + ' #' + i + '_group button[rel="' + v + '"]').trigger('click');
            }
        });

        $(':input:visible:enabled:not([readonly="readonly"]):first').focus();
    } else {
        $('#f__btn_excluir').show();
    }

    if (Object.keys($.uniform.elements).length > 0) {
        $.uniform.update('input:checkbox');
    }

    $('#' + div + ' .acao').val(acao);
    $('#' + div + ' .acaoTitulo').html(acaoTitulo);
}
function showList(reload) {
    scrollTop();
    $('.divForm').hide();
    $('#divTable').show();
    clearForm('.form');

//    if (Object.keys($.uniform.elements).length > 0) {
//        $.uniform.update('input:checkbox');
//    }

    if (reload === true) {
        filtrar(1);
    }
}
function showView() {
    $('.divForm').hide();
    $('#divTable').hide();
    $('#divView').show();
}
/**
 * Carrega via ajax os dados e coloca no form
 *
 * @param string pag
 * @param array param
 * @param function callback
 */
function loadForm(pag, param, callback) {
    $.ajax({
        type: "POST",
        url: pag,
        data: param,
        dataType: 'json',
        async: false,
        beforeSend: function() {
            $.gDisplay.loadStart('html');
        },
        error: function() {
            $.gDisplay.loadError('html', "Erro ao carregar a página...");
        },
        success: function(json) {
            $.gDisplay.loadStop('html');
            if (json.status === undefined) {
                $.each(json, function(k, v) {
                    if (isNaN(k)) {
                        if (v !== null) {
                            $('#' + k).val(v);
                            $('#' + k + '_group button[rel="' + v + '"]').click();
                        }
                    }
                });
                $('.combobox').trigger("liszt:updated");
            } else {
                $.gDisplay.showError(json.msg);
            }

            if (typeof callback === 'function') {
                callback.call(this, json);
            }

            return true;
        }
    });
}
function loadView(pag, param, div) {
    $.ajax({
        type: "POST",
        url: pag,
        data: param,
        dataType: 'html',
        beforeSend: function() {
            $.gDisplay.loadStart('html');
        },
        error: function() {
            $.gDisplay.loadError('html', "Erro ao carregar a página...");
        },
        success: function(html) {
            $.gDisplay.loadStop('html');
            $(div).html(html);
        }
    });
}
function selectLine(codigo) {
    unselectLines();
    $('tr[id="' + codigo + '"]').addClass('selectedLine');
}
function unselectLines() {
    $('tr').removeClass('selectedLine');
}

function ping() {
    //$.gAjax.execCallback(URL_SYS + 'feedback/ping.php', {}, false, false, true, false);
}


function validaData(value, brasil) {
    if (value.length != 0) {
        if (value.length != 10) {
            return false;
        }
        if (brasil) {
            var data = value;
            var dia = data.substr(0, 2);
            var barra1 = data.substr(2, 1);
            var mes = data.substr(3, 2);
            var barra2 = data.substr(5, 1);
            var ano = data.substr(6, 4);
            if ((data.length != 10) || barra1 != "/" || barra2 != "/" || isNaN(dia) || isNaN(mes) || isNaN(ano) || dia > 31 || mes > 12 || dia === '00' || mes === '00' || ano === '0000') {
                return false;
            }
            if ((mes == 4 || mes == 6 || mes == 9 || mes == 11) && dia == 31) {
                return false;
            }
            if (mes == 2 && (dia > 29 || (dia == 29 && ano % 4 != 0))) {
                return false;
            }
        } else {
            var dataA = value;
            var anoA = dataA.substr(0, 4);
            var barra1A = dataA.substr(4, 1);
            var mesA = dataA.substr(5, 2);
            var barra2A = dataA.substr(7, 1);
            var diaA = dataA.substr(8, 2);
            if ((dataA.length != 10) || barra1A != "-" || barra2A != "-" || isNaN(diaA) || isNaN(mesA) || isNaN(anoA) || diaA > 31 || mesA > 12) {
                return false;
            }
            if ((mesA == 4 || mesA == 6 || mesA == 9 || mesA == 11) && diaA == 31) {
                return false;
            }
            if (mesA == 2 && (diaA > 29 || (diaA == 29 && anoA % 4 != 0))) {
                return false;
            }

        }
    }
    return true;
}

function maskTel(obj) {
    $(obj).each(function() {
        var numero = $(this).val().replace(/\D/g, '');
        if (numero.length === 11) {
            $(this).mask('(99) 99999-9999');
        } else {
            $(this).mask('(99) 9999-9999?9');
        }
    });

    $(document).on('keyup', obj, function() {
        var val = $(this).val().replace(/\D/g, '');
        if (val.length === 11) {
            $(this).unmask();
            $(this).mask('(99) 99999-9999');
        } else if (val.length === 10) {
            $(this).unmask();
            $(this).mask('(99) 9999-9999?9');
        }
    });
}

function validaEmail(email) {
    email = trim(email);
    if (trim(email) === '') {
        return true;
    }
    if (email.substr(0, 1) === '.' || email.substr(email.length - 1, 1) === '.') {
        return false;
    }
    var er = new RegExp(/^[A-Za-z0-9_\-\.]+@[A-Za-z0-9_\-\.]{2,}\.[A-Za-z0-9]{2,}(\.[A-Za-z0-9])?/);
    if (er.test(email)) {
        return true;
    }
    else {
        return false;
    }
}

function formataValor(valor) {
    if (valor !== null)
        return (valor.replace('.', ',')).replace(',00', '');
    else
        return valor;
}