
/**
 * Faz uma busca
 * @param {type} param1
 * @param {type} param2
 */
$(document).ready(function() {


//    $('.link_image').parent().attr('target','_blank');

    $('.link_image').click(function() {
        url = $(this).attr('rel');
        var maskHeight = $(document).height();
        var maskWidth = $(window).width();

        $('#dialog2').html('<div align="right"><div align="right"><input type="button" value="Fechar" class="close"/><img src="' + url + '"/></div>');


        $('#mask').css({'width': '100%', 'height': maskHeight});
        $('#mask').fadeIn(1000);
        $('#mask').fadeTo("slow", 0.6);

        //Get the window height and width
        var winH = $(window).height();
        var winW = $(window).width();
        $('#dialog2').css('top', (winH / 2 - $('#dialog2').height() / 2) - 50);
        $('#dialog2').css('left', (winW / 2 - $('#dialog2').width() / 2) - 50);

        $('#dialog2').fadeIn(2000);

        $('.window .close').click(function(e) {
            e.preventDefault();
            $('#mask').hide();
            $('.window').hide();
        });

        $('#mask').click(function() {
            $(this).hide();
            $('.window').hide();
        });
    });


    /**
     * USADO NO BREADCRUMP
     * Atualização de página
     */
    $(document).on('click', '.atualizar_pagina', function() {
        location.reload();
    });



});




/**
 * @author SÂndler A. Matos
 * @description Utilizado para exclusão de vários itens da lista
 * #exemplo: FuncoesHelper cria o "Excluir Selecionados" e nos checks existe uma classe chamada "ck_delete"
 *           que ao click dela é adicionado no "href" do "ck_delete_all" que é o link "Excluir Selecionados"
 *           e passa como parametro do get o que é para ser excluído.
 * @type: HTML CLASS DELETE
 */
$('.ck_delete').click(function() {
    var checkeds = '';
    $('.ck_delete').each(function() {
        if ($(this).is(':checked')) {
            checkeds += $(this).val() + '_';
        }
    });



    link = $('.ck_delete_all').attr('rel');
    link = link + '/ids:' + checkeds;
    $('.ck_delete_all').attr('href', link);

    for (i = 1; i <= 5; i++) {
        link = $('.ck_classifica_all' + i).attr('rel');
        link = link + '/ids:' + checkeds;
        $('.ck_classifica_all' + i).attr('href', link);
    }
});



/**
 * FUNÇÃO PARA SELECIONAR TODOS OS CHECKS DE UMA LISTAGEM
 */
/**
 * @author SÂndler A. Matos
 * @description Utilizado para seleção de todas as linhas que estão visíveis na listagem
 * #exemplo: Utiliza uma classe no ckeckbox "ck_select_all" para selecionar todos os checks da tela
 * @type: HTML CLASS SELECT ALL
 */
$('.ck_select_all').click(function() {
    if ($(this).is(':checked')) {
        $('.ck_delete').each(function() {
            if (!$(this).is(':checked')) {
                $(this).click();
            }
        });
    } else {
        $('.ck_delete').each(function() {
            if ($(this).is(':checked')) {
                $(this).click();
            }
        });
    }
})



/**
 * CONTA CARACTERES READY
 * Existe um document.ready no ajax.js que chama esta função também
 */
function conta_caracteres(obj) {
    var valor = $(obj).val();
    var id = $(obj).attr('rel');
    var content_local = $(obj).attr('content_local');
    var caracteres = 2000;
    var limit = $(obj).attr('maxlength');

    if (limit == undefined) {
        caracteres = caracteres - valor.length;
    } else {
        caracteres = limit - valor.length;
    }

    if (content_local == undefined) {
        content_local = '';
    }
    if (caracteres < 0) {
        caracteres = '<span style="color:red;">' + caracteres + '</span>';
    }
    $(content_local + ' ' + id).html('Total de caracteres restantes ' + caracteres);
}

$(document).on('keyup', '.conta_caracteres', function() {
    conta_caracteres(this);
});

/**
 * CONTA CARACTERES READY
 * Função se encontra no ajax_init.js
 */
$(document).ready(function() {
    $('.conta_caracteres').each(function() {
        conta_caracteres(this);
    });
});




/**
 * VERIFICA SE É NUMÉRICO
 * @param {type} n
 * @returns {unresolved}
 */
function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}



/**
 * PISCA ELEMENTO
 * @param {type} selector
 * @param {type} infinity
 * @returns {undefined}
 */
function blink(selector, infinity) {
    $(selector).fadeOut('slow', function() {
        $(this).fadeIn('slow', function() {
            if (infinity == true) {
                blink(this);
            }
        });
    });
}

/**
 * PISCA O ALERT E RETIRA ELE DA TELA!
 */
$(document).ready(function() {
    var segundos = 1000;
    if ($(".alert").hasClass("alert-success")) {
        setTimeout(function() {
            blink($('.alert-success'), false);
        }, 2 * segundos);

        setTimeout(function() {
            $('.alert-success').fadeOut('slow', 'swing');
        }, 10 * segundos);
    }

});



/**
 * MÁSCARAS DE CAMPO
 * UTILIZA O ARQUIVO jquery.maskedinput.js no layout default.ctp
 */
$(document).ready(function() {
    $('.peso_mask').mask('9?99,9', {reverse: true});
    //$('.peso_mask2').mask("#.##0,00", {reverse: true, maxlength: false});

//    $('.peso_mask').mask('9!99,99');
    $('.altura_mask').mask('9,99');
    $(".money_mask").maskMoney();
    $('.num_mask').mask('9?9999999');
    $('.cep_mask').mask('99999-999');
    $('.date_mask').mask('99/99/9999');
    $('.rg_mask').mask('99.999.999-*');
    $('.cpf_mask').mask('999.999.999-**');
    $('.cnpj_mask').mask('99.999.999/9999-99');
    $('.tel_mask').mask("(99) 9999-9999?9").ready(function(event) {
        var target, phone, element;
        target = (event.currentTarget) ? event.currentTarget : event.srcElement;

        if (target == undefined) {
            phone = '';
        } else {
            phone = target.value.replace(/\D/g, '');
        }

        element = $(target);
        element.unmask();
        if (phone.length > 10) {
            element.mask("(99) 99999-999?9");
        } else {
            element.mask("(99) 9999-9999?9");
        }
    });
});


/**
 * MÁSCARA - INPUT NUMÉRICO
 * Este serve para buscar montar o campo com opção de aceitar somente números.
 * Exemplo: 'class' => 'input_login input_numerico', 'data-number' => '99'
 */
$(document).on('focus', '.input_numerico', function() {
    var numero = $(this).attr('data-number');
    if (numero == undefined) {
        numero = '99';
    }
    $('.input_numerico').mask(numero);
});






/**
 *  CONFIRMAÇÃO NAS EXCLUSÕES DE REGISTROS
 */
$('.ajaxMsg').click(function() {
    msg = $(this).attr('ajaxMsg');
    if (confirm(msg)) {
        return true;
    }
    return false;
});





/**
 * ESTRUTURA DE CRIAÇÃO DE CONTEÚDO DINÂMICO (PROCESSOS_ID)
 */

/**
 * BOTÃO DE AÇÃO DINÂMICA
 * @example DEFAULT EXAMPLE PROCESSOS
 */
$(document).on('click', '.btnAcaoD', function() {
    var content_example = $(this).attr('content_example');
    var content_local = $(this).attr('content_local');
    var type = $(this).attr('content_action');
    var id = $(this).attr('id_linha');
    var id_master = $(this).attr('id_linha_master');
    var sub_count_rows = $(this).attr('sub_count_rows');

    if (sub_count_rows == undefined) {
        sub_count_rows = 'count_rows';
    }
    changeRow(content_example, content_local, type, id, id_master, sub_count_rows);
});

function changeRow(content_example, content_local, type, id, id_master, sub_count_rows) {
//    if(id_master == undefined){ id_master = "";}
    if (type == 'add') {
        var qtd = 0;
        var content = $(content_example).html();
        $(content_local + ' .' + sub_count_rows).each(function() {
            qtd++;
        });
        content = content.replace(/_N_/g, qtd + 1);
        if (isNumber(id_master)) {//ADICIONA O ID DA MASTER PARA FAZER OS SUB-DINAMICOS
            content = content.replace(/_NM_/g, id_master);
        }

        $(content_local).append(content);
    } else {
        var title = $(content_local + ' .oculta_' + id + ' .title_default').val().trim();
        var msg_conf = 'Tem certeza que deseja excluir o conteúdo do "' + title + '": ' + id + ' ?';
        if (title == undefined || title == '') {
            msg_conf = 'Tem certeza que deseja excluir o Registro: ' + id + ' ?';
        }

        if (confirm(msg_conf)) {
            $(content_local + ' .status' + id).val('2');
            $(content_local + ' .oculta_' + id).hide();
        }
    }
}

/**
 * CRIADO MODO DE PEGAR TARGET DA IMAGEM E PASSAR PARA O LINK QUANDO UTILIZADO IMAGE URL DO CAKE
 */
$(document).ready(function() {
    $('img').each(function() {
        if ($(this).attr('target') !== undefined) {
            target = $(this).attr('target');
            $(this).removeAttr('target');
            $(this).parent().attr('target', '_blank')
        }
    });
});







/**
 * CHANGE PARA EXIBIR E OCULTAR ESCOLHAS
 */

//$(document).on('change', '.exibir_hidden', function() {
//    value = $(this).val();
//    idObj = $(this).attr('id');
//
//    if((idObj == 'AlunoAprovado' && $(this).val() != '') || (idObj != 'AlunoAprovado' && $(this).val() == 1)){
//        $($(this).attr('data_hidden')).show();
//    }else{
//        $($(this).attr('data_hidden')).hide();
//    }
//
//});




function exibir_hidden(idObj) {
    valObj = $('#' + idObj).val();
    dataHidden = $('#' + idObj).attr('data_hidden');
    dataClean = $('#' + idObj).attr('data_clean');

    if ((idObj == 'AlunoAprovado' && valObj != '') || (idObj != 'AlunoAprovado' && valObj == 1)) {
        $(dataHidden).show();
    } else {
        $(dataHidden).hide();
        $(dataClean).val('');
    }
}

$(document).ready(function() {

    $(".exibir_hidden").each(function(index) {
        exibir_hidden($(this).attr('id'));
    });

    $(document).on('change', '.exibir_hidden', function() {
        exibir_hidden($(this).attr('id'));
    });

});


$(document).ready(function() {
    //SAVE SESSION CONTA|CLIENTE
    $('.select_grupo_empresarial_id').change(function() {
        var valor = $(this).val();
        atualiza_session_cliente('grupo_empresarial_id', valor);
    });
    $('.select_cliente_id').change(function() {
        var valor = $(this).val();
        atualiza_session_cliente('cliente_id', valor);
    });


    //SAVE BUTTOM MENU
    $('.minifyme').click(function() {
        setTimeout(function() {
            atualiza_session_menu();
        }, 500);
    });
    //BUTTOM TOPO
    $('#hide-menu').click(function() {
        setTimeout(function() {
            atualiza_session_menu();
        }, 500);
    });
});

function atualiza_session_cliente(tipo, valor) {
    var url = $('body').attr('rel_url');
    var controller = $('body').attr('rel_controller');
    var action = $('body').attr('rel_action');
    
    $.ajax({
        url: url + "admin/usuario/atualiza_session_cliente",
        data: {valor: valor, tipo: tipo},
        dataType: "json",
        type: 'POST',
        async: true,
        cache: false,
        statusCode: {
            404: function() {
                alert(' Desculpe, a página solicitada não foi encontrada!');
            },
            500: function() {
                alert(' Desculpe, ocorreu um erro no servidor! Tente novamente mais tarde!');
            }
        },
        success: function(data) {
            if(tipo == 'cliente_id'){
                if(controller == 'grupo_empresarial' && action == 'admin_selecione'){
                    window.location = url+'/admin';
                }else{
                    window.location = location.href;
                }
            }else{
                window.location = location.href;
            }
        },
        error: function(result) {
            alert('Desculpe, ocorreu um erro no servidor! Tente novamente mais tarde!');
        }
    });
}


function atualiza_session_menu() {
    var url = $('body').attr('rel_url');
    var value = $('body').attr('class');
    var key = 'menu_geral';
    $.ajax({
        url: url + "admin/usuario/atualiza_session_menu",
        data: {key: key, value: value},
        dataType: "json",
        type: 'POST',
        async: true,
        cache: false,
        statusCode: {
        },
        success: function(data) {
        },
        error: function(result) {
        }
    });
}









/**
 * DATAS DE INICIO E TÉRMINO E CONTINUA (AUTOMATIZA PARA OCULTAR E LIMPAR)
 */

//$(document).on('change', '.data_continua', function() {
//    var id = $(this).attr('rel');
//    var valor = $(this).val();
//    var content_local   = $(this).attr('content_local');
//    var sub_content_local   = $(this).attr('sub_content_local');
//
//    if(sub_content_local == undefined){
//            data_termino = 'data_termino'+id;
//            sub_content_local = '';
//    }else{
//            data_termino = 'sub_data_termino'+id;
//    }
//
//    if(valor == 1){
//        $(content_local+' '+sub_content_local+' .'+data_termino+' input').val('');
//        $(content_local+' '+sub_content_local+' .'+data_termino).hide();
//    }else{
//        $(content_local+' '+sub_content_local+' .'+data_termino).show();
//    }
//
//});




/**
 * @description Aviso caso tente sair ou fechar!
 * @param {type} evt
 * @returns {Window.onbeforeunload.message|String|Function.message}
 */
//não funciona no logout
//window.onbeforeunload = function(evt) {
//    var message = 'Caso esteja fechando a Aba ou o navegador, recomendamos que bloqueie ou deslogue-se para maior segurança!';
//    if (typeof evt == 'undefined') {
//        evt = window.event;
//    }
//    if (evt) {
//        evt.returnValue = message;
//    }
//    return message;
//}

