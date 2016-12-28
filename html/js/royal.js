$(document).ready(function(){
    $('.toggler-menu').on('click', function(){
        console.log('qui');
        $('.menu-mobile-inner').toggleClass('visible');
        $('.toggler-bg').toggleClass('visible');
    })
    $('.immobili_vendite').on('click', function(){
        $(this).addClass('active');
        $('.menu_vendite').addClass('visible');
        $('.immobili_affitti').removeClass('active');
        $('.menu_affitti').removeClass('visible');
    });
    $('.immobili_affitti').on('click', function(){
        $(this).addClass('active');
        $('.menu_affitti').addClass('visible');
        $('.immobili_vendite').removeClass('active');
        $('.menu_vendite').removeClass('visible');
    });

    $('.controls').on('click', function() {
        var wrapper = $('.annuncio-slideshow-inner');
        var children = $(wrapper).children();
        var count = children.length;
        if ($(this).hasClass('slide-prev')) {
            var selected = children[count - 1];
            $(selected).prependTo($(wrapper));
        } else {
            var selected = children[0];
            $(selected).appendTo($(wrapper));
        }
    })

    $('.toggler-flats').on('click', function(){
        console.log('qui');
        $('.immobili_menu_container').toggleClass('open');
    })
    $('.immobili_menu_inner').on('click', function(e){
        e.stopPropagation();
    })
})

$(window).on('scroll', function(){
    var scrolly = window.scrollY;
    if (scrolly > 150) {
        $('#header').addClass('fixed');
    } else {
        $('#header').removeClass('fixed');
    }
})
