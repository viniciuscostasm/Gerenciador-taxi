function removeSustenido(url) {
    return url.replace(/^.*#/, '');
}

function trim(str) {
    if (typeof str !== 'undefined')
        return str.replace(/^\s+|\s+$/g, "");
}

function ltrim(str) {
    return str.replace(/^\s+/, "");
}

function rtrim(str) {
    return str.replace(/\s+$/, "");
}

function formatMoney(value) {
    var ret = '';
    ret = value.replace('R$ ', '');
    ret = ret.replace(',', '.');
    return ret;
}

function closeColorbox() {
    parent.jQuery.fn.colorbox.close();
}

function Money(e) {
    var tecla = (window.event) ? event.keyCode : e.which;

    if ((tecla > 47 && tecla < 58))
        return true;
    else {
        if (tecla == 46 || tecla == 13 || tecla == 0)
            return true;
        if (tecla != 8)
            return false;
        else
            return true;
    }
}

function roundNumber(num, dec) {
    var result = Math.round(num * Math.pow(10, dec)) / Math.pow(10, dec);
    return result;
}

function quantidadeCaracteres(e, elemento, quant) {
    var tecla = (window.event) ? event.keyCode : e.which;
    if (tecla == 0)
        return true;
    if (tecla == 8)
        return true;

    var valor = jQuery(elemento).val();
    var total = valor.length;
    if (total < quant)
        return true;
    else {
        jQuery(elemento).val(valor.substr(0, quant));
        jQuery.gDisplay.showError("Quantidade de caracteres máximo (" + quant + ") atingido", "jQuery('#" + jQuery(elemento).attr("id") + "').focus();");
        return false;
    }
}

function criarPermalink(str) {
    str = retiraAcentos(str);
    return str.replace(/[^a-z0-9]+/gi, '-').replace(/^-*|-*$/g, '').toLowerCase();
}

function retiraAcentos(Campo) {
    var Acentos = "áàãââÁÀÃÂéêÉÊíÍóõôÓÔÕúüÚÜçÇabcdefghijklmnopqrstuvxwyz";
    var Traducao = "AAAAAAAAAEEEEIIOOOOOOUUUUCCABCDEFGHIJKLMNOPQRSTUVXWYZ";
    var Posic, Carac;
    var TempLog = "";
    for (var i = 0; i < Campo.length; i++)
    {
        Carac = Campo.charAt(i);
        Posic = Acentos.indexOf(Carac);
        if (Posic > -1)
            TempLog += Traducao.charAt(Posic);
        else
            TempLog += Campo.charAt(i);
    }
    return (TempLog);
}

function clearForm(form) {
    $(form).find(':input').each(function() {
        switch (this.type) {
            case 'select-multiple':
            case 'select-one':
            case 'password':
            case 'text':
            case 'textarea':
            case 'hidden':
                $(this).val('');
                break;
            case 'checkbox':
            case 'radio':
                $(this).prop('checked', false);
                this.checked = false;
                break;
        }
    });
    $(form + ' .control-group').removeClass('error');
    $(form).find(form + ' .btn-group.btnChave').each(function() {
        $(this).find('button:first').trigger('click');
    });
}

function __jqPaginate(id, post, target, rp, sortname, sortorder, filters, pagina) {
    $.gDisplay.loadStart("#" + id);
    jQuery.ajax({
        type: "POST",
        url: post,
        data: jsonConcat({
            type: 'C',
            rp: rp
        }, filters),
        dataType: 'json',
        success: function(json) {
            $.gDisplay.loadStop("#" + id);

            if (json.count > 1) {
                $('#' + id + ' .jqpagination').show();
            } else {
                $('#' + id + ' .jqpagination').hide();
            }

            pagina = (json.count < pagina) ? json.count : pagina;

            jQuery("#" + id).jqPagination({
                max_page: json.count,
                paged: function(page) {
//                    if (page !== pagina) {
                    jQuery('#pag_atual_' + id).val(page);
                    jQuery.gAjax.load(post, jsonConcat({
                        type: 'R',
                        count: json.count,
                        rp: rp,
                        page: page,
                        sortname: sortname,
                        sortorder: sortorder
                    }, filters), target);
//                    }
                }
            });


            pagina = (pagina === undefined) ? 1 : pagina;
            jQuery('#pag_atual_' + id).val(pagina);
            jQuery("#" + id).jqPagination('option', 'current_page', pagina);
            jQuery.gAjax.load(post, jsonConcat({
                type: 'R',
                count: json.count,
                rp: rp,
                page: pagina,
                sortname: sortname,
                sortorder: sortorder
            }, filters), target);

        }
    });
}

function pressEnter(obj, acao) {
    if (jQuery.browser.mozilla) {
        jQuery(obj).keypress(function(e) {
            if (e.keyCode == 13)
                eval(acao);
        });
    } else {
        jQuery(obj).keydown(function(e) {
            if (e.keyCode == 13)
                eval(acao);
        });
    }
    return false;
}

function pressEnterDelegate(obj, acao) {
    if (jQuery.browser.mozilla) {
        jQuery(document).delegate(obj, 'keypress', function(e) {
            if (e.keyCode == 13)
                eval(acao);
        });
    } else {
        jQuery(document).delegate(obj, 'keydown', function(e) {
            if (e.keyCode == 13)
                eval(acao);
        });
    }
}

function retirarMask(text) {
    var proc = ".-/";
    for (var i = 0; i < text.length; i++) {
        if (proc.indexOf(text.charAt(i)) > -1)
            text = text.replace(text.charAt(i), "");
    }
    return text;
}

function formatarData(string, brasil) {
    var retorno = string;
    var dataHora = '';
    var data = '';
    if (brasil) {
        dataHora = string.split(" ");
        data = dataHora[0].split("/");
        if (data.length > 1) {
            retorno = data[2] + "-" + data[1] + "-" + data[0] + ' ' + dataHora[1];
        }
    } else {
        dataHora = string.split(" ");
        data = dataHora[0].split("/");
        if (data.length > 1) {
            retorno = data[2] + "/" + data[1] + "/" + data[0] + ' ' + dataHora[1];
        }
    }
    return retorno;
}

function trocarPonto(valor, tipo) {
    if (tipo == 'V')
        return valor.replace(',', '.');
    else
        return valor.replace('.', ',');
}

//function formatarValor(valor) {
//    var ret = valor;
//    var io = valor.indexOf(',');
//    if (io != -1) {
//        var arr = valor.split(',');
//        if (arr[1].length == 1) {
//            ret = valor + '0';
//        }
//    } else {
//        ret = valor + ',00';
//    }
//
//    return ret;
//}

function verificaNumero(e) {
    if ((e.ctrlKey && e.which == 99) || (e.ctrlKey && e.which == 118))
        return true;
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57))
        return false;
    return true;
}

function jsonConcat(o1, o2) {
    for (var key in o2) {
        o1[key] = o2[key];
    }
    return o1;
}

function updateConteudoFrame(alturaConteudo, alturaColorbox) {
    var heightContent = (alturaConteudo === undefined) ? 150 : alturaConteudo;
    var heightColorbox = (alturaColorbox === undefined) ? parent.$("#cboxLoadedContent").height() : alturaColorbox;
    $('.__conteudoFrame').css('height', heightColorbox - heightContent + 'px');

    $('.__conteudoFrame').slimScroll({
        height: heightColorbox - heightContent
    });
}

function numberFormat(valor) {
    if (valor === null) {
        return null;
    } else {
        return number_format(valor, 2, ',', '.');
    }
}

function numberUnformat(valor) {
    if (valor === null) {
        return null;
    } else {
        valor = valor.replace('.', '');
        valor = valor.replace(',', '.');
        return valor;
    }
}

function utf8_encode(argString) {
  //  discuss at: http://phpjs.org/functions/utf8_encode/
  // original by: Webtoolkit.info (http://www.webtoolkit.info/)
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: sowberry
  // improved by: Jack
  // improved by: Yves Sucaet
  // improved by: kirilloid
  // bugfixed by: Onno Marsman
  // bugfixed by: Onno Marsman
  // bugfixed by: Ulrich
  // bugfixed by: Rafal Kukawski
  // bugfixed by: kirilloid
  //   example 1: utf8_encode('Kevin van Zonneveld');
  //   returns 1: 'Kevin van Zonneveld'

  if (argString === null || typeof argString === 'undefined') {
    return '';
  }

  var string = (argString + ''); // .replace(/\r\n/g, "\n").replace(/\r/g, "\n");
  var utftext = '',
    start, end, stringl = 0;

  start = end = 0;
  stringl = string.length;
  for (var n = 0; n < stringl; n++) {
    var c1 = string.charCodeAt(n);
    var enc = null;

    if (c1 < 128) {
      end++;
    } else if (c1 > 127 && c1 < 2048) {
      enc = String.fromCharCode(
        (c1 >> 6) | 192, (c1 & 63) | 128
      );
    } else if (c1 & 0xF800 != 0xD800) {
      enc = String.fromCharCode(
        (c1 >> 12) | 224, ((c1 >> 6) & 63) | 128, (c1 & 63) | 128
      );
    } else { // surrogate pairs
      if (c1 & 0xFC00 != 0xD800) {
        throw new RangeError('Unmatched trail surrogate at ' + n);
      }
      var c2 = string.charCodeAt(++n);
      if (c2 & 0xFC00 != 0xDC00) {
        throw new RangeError('Unmatched lead surrogate at ' + (n - 1));
      }
      c1 = ((c1 & 0x3FF) << 10) + (c2 & 0x3FF) + 0x10000;
      enc = String.fromCharCode(
        (c1 >> 18) | 240, ((c1 >> 12) & 63) | 128, ((c1 >> 6) & 63) | 128, (c1 & 63) | 128
      );
    }
    if (enc !== null) {
      if (end > start) {
        utftext += string.slice(start, end);
      }
      utftext += enc;
      start = end = n + 1;
    }
  }

  if (end > start) {
    utftext += string.slice(start, stringl);
  }

  return utftext;
}

function sha1(str) {
  //  discuss at: http://phpjs.org/functions/sha1/
  // original by: Webtoolkit.info (http://www.webtoolkit.info/)
  // improved by: Michael White (http://getsprink.com)
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  //    input by: Brett Zamir (http://brett-zamir.me)
  //  depends on: utf8_encode
  //   example 1: sha1('Kevin van Zonneveld');
  //   returns 1: '54916d2e62f65b3afa6e192e6a601cdbe5cb5897'

  var rotate_left = function(n, s) {
    var t4 = (n << s) | (n >>> (32 - s));
    return t4;
  };

  var cvt_hex = function(val) {
    var str = '';
    var i;
    var v;

    for (i = 7; i >= 0; i--) {
      v = (val >>> (i * 4)) & 0x0f;
      str += v.toString(16);
    }
    return str;
  };

  var blockstart;
  var i, j;
  var W = new Array(80);
  var H0 = 0x67452301;
  var H1 = 0xEFCDAB89;
  var H2 = 0x98BADCFE;
  var H3 = 0x10325476;
  var H4 = 0xC3D2E1F0;
  var A, B, C, D, E;
  var temp;

  str = this.utf8_encode(str);
  var str_len = str.length;

  var word_array = [];
  for (i = 0; i < str_len - 3; i += 4) {
    j = str.charCodeAt(i) << 24 | str.charCodeAt(i + 1) << 16 | str.charCodeAt(i + 2) << 8 | str.charCodeAt(i + 3);
    word_array.push(j);
  }

  switch (str_len % 4) {
    case 0:
      i = 0x080000000;
      break;
    case 1:
      i = str.charCodeAt(str_len - 1) << 24 | 0x0800000;
      break;
    case 2:
      i = str.charCodeAt(str_len - 2) << 24 | str.charCodeAt(str_len - 1) << 16 | 0x08000;
      break;
    case 3:
      i = str.charCodeAt(str_len - 3) << 24 | str.charCodeAt(str_len - 2) << 16 | str.charCodeAt(str_len - 1) <<
        8 | 0x80;
      break;
  }

  word_array.push(i);

  while ((word_array.length % 16) != 14) {
    word_array.push(0);
  }

  word_array.push(str_len >>> 29);
  word_array.push((str_len << 3) & 0x0ffffffff);

  for (blockstart = 0; blockstart < word_array.length; blockstart += 16) {
    for (i = 0; i < 16; i++) {
      W[i] = word_array[blockstart + i];
    }
    for (i = 16; i <= 79; i++) {
      W[i] = rotate_left(W[i - 3] ^ W[i - 8] ^ W[i - 14] ^ W[i - 16], 1);
    }

    A = H0;
    B = H1;
    C = H2;
    D = H3;
    E = H4;

    for (i = 0; i <= 19; i++) {
      temp = (rotate_left(A, 5) + ((B & C) | (~B & D)) + E + W[i] + 0x5A827999) & 0x0ffffffff;
      E = D;
      D = C;
      C = rotate_left(B, 30);
      B = A;
      A = temp;
    }

    for (i = 20; i <= 39; i++) {
      temp = (rotate_left(A, 5) + (B ^ C ^ D) + E + W[i] + 0x6ED9EBA1) & 0x0ffffffff;
      E = D;
      D = C;
      C = rotate_left(B, 30);
      B = A;
      A = temp;
    }

    for (i = 40; i <= 59; i++) {
      temp = (rotate_left(A, 5) + ((B & C) | (B & D) | (C & D)) + E + W[i] + 0x8F1BBCDC) & 0x0ffffffff;
      E = D;
      D = C;
      C = rotate_left(B, 30);
      B = A;
      A = temp;
    }

    for (i = 60; i <= 79; i++) {
      temp = (rotate_left(A, 5) + (B ^ C ^ D) + E + W[i] + 0xCA62C1D6) & 0x0ffffffff;
      E = D;
      D = C;
      C = rotate_left(B, 30);
      B = A;
      A = temp;
    }

    H0 = (H0 + A) & 0x0ffffffff;
    H1 = (H1 + B) & 0x0ffffffff;
    H2 = (H2 + C) & 0x0ffffffff;
    H3 = (H3 + D) & 0x0ffffffff;
    H4 = (H4 + E) & 0x0ffffffff;
  }

  temp = cvt_hex(H0) + cvt_hex(H1) + cvt_hex(H2) + cvt_hex(H3) + cvt_hex(H4);
  return temp.toLowerCase();
}

function scrollTop() {
    $('body,html').animate({
        scrollTop: 0
    }, 200);
    return false;
}

$.fn.serializeObject = function() {
    limparPlaceHolder();
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    carregarPlaceHolder();
    return o;
};

/*********************** Compatibilidade com IE 8 *****************************/
// coloca placeholder nos campos que o browser não puder colocar nativo
function carregarPlaceHolder() {
    if (!$.support.placeholder) {
        $('[placeholder]').each(function() {
            var input = $(this);
            $(input).val(input.attr('placeholder'));
            $(input).focus(function() {
                if (input.val() == input.attr('placeholder')) {
                    input.val('');
                }
            });
            $(input).blur(function() {
                if (input.val() == '' || input.val() == input.attr('placeholder')) {
                    input.val(input.attr('placeholder'));
                }
            });
        });
    }
}

// retira o placeholder dos campos para poder pegar os valores dos inputs e não os placeholders dos mesmos.
function limparPlaceHolder() {
    if (!$.support.placeholder) {
        $('[placeholder]').each(function() {
            var input = $(this);
            if (input.val() == input.attr('placeholder'))
                input.val('');
        });
    }
}

// configura $.support.placeholder como compatível a placeholder ou não
(function($) {
    $.support.placeholder = ('placeholder' in document.createElement('input'));
})(jQuery);

// define função para substituir a função nativa Object.keys que não funciona no IE8 
Object.keys = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key))
            size++;
    }
    return size;
};