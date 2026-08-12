/* *****************************/
/* CONFIGURAÇÕES DO DATEPICKER */
/* *****************************/
dateCustomOption = {
    view: {showOn: "none", isInputIsReadonly: false, position: [0, -45]},
    daysSimple: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"],
    monthsFull: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
    dateFormat: {separator: "/", format: ["DD", "MM", "YYYY"]},
    modules: {header: true, footer: false, icon: false, clear: false}
}
/* *****************************/

/* ********************************/
/* FUNÇÃO PARA TOGGLE DE CONTEUDO */
/* ********************************/

function goToggle(target, img) {
    var obj = eval($(target));

    if (obj.is(':visible')) {
        var src = $(img).attr("src").replace("up.png", "down.png");
        $(img).attr("src", src);
    } else {
        var src = $(img).attr("src").replace("down.png", "up.png");
        $(img).attr("src", src);
    }
    obj.toggle();
}


/* ********************************************/
/* FUNÇÃO PARA CARREGAMENTO DE IMAGENS SUITES */
/* ********************************************/
function callGaleriaAcomodacoes(id, object) {

    $(".thumbs-acomodacoes div a").removeClass("active");
    $(object).addClass("active");

    $.ajax({
        url: 'ajax-galeria-acomodacoes.html',
        cache: false,
        dataType: 'html',
        beforeSend: function () {
            // Handle para beforeSend event
        },
        complete: function () {
            // Handle para complete event
        },
        error: function () {
            // Handle para error event
        },
        success: function (data) {
            $('#bxslider-container').html(data);
            $('.bxslider-galeria-updated').bxSlider(); // re aplicando o bxslider ao conteudo carregado
        }
    });

    $.ajax({
        url: 'ajax-galeria-textos.html',
        cache: false,
        dataType: 'html',
        beforeSend: function () {
            // Handle para beforeSend event
        },
        complete: function () {
            // Handle para complete event
        },
        error: function () {
            // Handle para error event
       },
        success: function (data) {
            $('#container-texto-hospedagem').html(data);
        }
    });



}

/* ********************************/
/* ANCHOR SCROLL FUNCTION		 */
/* ********************************/

$(function () {

    /* ************************************/
    /*	BLOCO DE REPEAT DOS QUARTOS		*/
    /* ************************************/
    $("#quartos").change(function () {

        var numberOfCopies = $(this).val();
        // limpando todos os objetos, mantendo apenas o primeiro
        $('#repeatQuartos ul li').not(':first').remove();
        for (x = 1; x < numberOfCopies; x++) {
            $("#repeatQuartos ul li:first-child").each(function () {
                $(this)
                        .clone()
                        .appendTo("#repeatQuartos ul")
                        .find('div.titulo').html('Quarto ' + (x + 1));
            });

        }
    });
    /* ************************************/

    $(window).scroll(function () {
        if ($(this).scrollTop() != 0) {
            $('#toTop').fadeIn();
        } else {
            $('#toTop').fadeOut();
        }
    });

    $('#toTop').click(function () {
        $('body,html').animate({scrollTop: 0}, 800);
    });



    if (document.location.hash) {
        setTimeout(function () {
            window.scrollTo(window.scrollX, window.scrollY - 87);
        }, 10);
    }

    $('a[href*=#]:not([href=#])').click(function () {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {

                $('html,body').animate({
                    scrollTop: (-84) + target.offset().top
                }, 1000);
                return false;
            }
        }
    });
});

/* ********************************/
/* FUNÇÃO PARA RESETAR O MENU	  */
/* ********************************/
function resetMenu() {

    $('#busca-ativa').css('display', 'none');
    $('#busca-click').css('display', 'inline');
    $('#area-logada-click').css('display', 'inline');
    $('#area-logada-click-ativa').css('display', 'none');
}
/* ********************************/
/* ********************************/

/* ****************************************/
/* BLOCO DE SUPORTE DAS ABAS DE GALERIA	  */
/* ****************************************/

var onSelectTab = function (event, item) {
//    console.log("********************************************");
//    console.log("Aba Selecionada: " + item.tab.index());
//    console.log("********************************************");

    $('#bxslider-galeria_' + item.tab.index()).bxSlider({
        pagerCustom: '#bxslider-pager_' + item.tab.index(),
        captions: true,
        mode: 'fade',
        preloadImages: 'all',
        options: 'all',
        onSliderLoad: function () {
            /* Call Back Reservado */
            
        },
    });


    $('#bxslider-pager_' + item.tab.index()).bxSlider({
        minSlides: 5,
        maxSlides: 5,
        slideWidth: 125,
        slideMargin: 0,
        moveSlides: 1,
        auto: false,
        pager: false,
        infiniteLoop: false,
        hideControlOnEnd: true,
        adaptiveHeight: true,
        preloadImages: 'all',
        onSliderLoad: function () {
            // retorna o alpha da DIV container da galeria
            // *******************************************
            $('#galeria-block_' + item.tab.index()).css("opacity", "1");
            //alert('Chamando o Método do Refresh');
            $("#tabbed-nav-fotos").data('zozoTabs').refresh();
            // *******************************************
        },
    });
};
/* ********************************/
/* ********************************/

/* ****************************************/
/* BLOCO PARA OS INITS GLOBAIS			  */
/* ****************************************/

$(function () {
//    console.log("ready! :: Commons Elements");



    $('#busca-toggle').click(function () {
        $('#busca-ativa').toggle();
        $('#busca-click').toggle();
    })

    $('#busca-click').click(function () {
        resetMenu();
        $('#busca-ativa').toggle();
        $('#busca-click').toggle();

    })

    $('#area-logada-toggle').click(function () {
        $('#area-logada-click').toggle();
        $('#area-logada-click-ativa').toggle();
    })


    $('#area-logada-click').click(function () {
        resetMenu();
        $('#area-logada-click').toggle();
        $('#area-logada-click-ativa').toggle();
    })


    /* INIT DO MONSORY COM AS NOTICIAS DO RODAPE */
    $('#masonryContainer').masonry({
        itemSelector: '.masonry-brick',
        //columnWidth: 100
    });

    $('#sac').autohide_timeout({
        buttons_events: 'click', // default is click
        content: $('.bubble_sac'),
        //hide_on_start: true, // hides target element on load, default is false
        timeout: 1000
    });

    $('#carrinho').autohide_timeout({
        buttons_events: 'click', // default is click
        content: $('.bubble_carrinho'),
        //hide_on_start: true, // hides target element on load, default is false
        timeout: 1000
    });

});



$(document).ready(function () {
    /**
     * EXIBE MAIS INTENS
     * example: elements/footer/prefooter.ctp
     */
    $(".carregar_mais").click(function () {
        carregar = $(this).attr('rel_carrega_mais');
        $('.' + carregar).removeAttr('style');
        $('.' + carregar).fadeIn("slow");
        $(this).parent().fadeOut("slow");
    });

    /**
     * LOAD GALERY AUTOMÁTICO E COM TEMPO DE 10S
     */
    $('.bxslider').bxSlider({
        auto: true,
        pause: 10000
    });

    /**
     * Fixar menu geral no topo
     * @type @call;$@call;offset@pro;top
     */
    var offset = $('#supportMenu').offset().top;
    var $meuMenu = $('#supportMenu'); // guardar o elemento na memoria para melhorar performance
    $(document).on('scroll', function () {
        if (offset <= $(window).scrollTop()) {
            $meuMenu.addClass('fixar_menu');
            $('.hotsite-reserva').attr('style','margin-top:70px !important;');
        } else {
            $meuMenu.removeClass('fixar_menu');
            $('.hotsite-reserva').removeAttr('style');
        }
    });


});

