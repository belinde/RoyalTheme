jQuery(function ($) {
    royalGallerySlider('photos');
    $('.toggler-menu').on('click', function () {
        $('.menu-mobile-inner').toggleClass('visible');
        $('.toggler-bg').toggleClass('visible');
    });

    $('.immobili_contratto.immobili_vendita').on('click', function () {
        $('.immobili_contratto').removeClass('active');
        $(this).addClass('active');
        $('.immobili_menu .immobili_menu').removeClass('visible');
        $('.immobili_menu .menu_vendita').addClass('visible');
    });
    $('.immobili_contratto.immobili_affitto').on('click', function () {
        $('.immobili_contratto').removeClass('active');
        $(this).addClass('active');
        $('.immobili_menu .immobili_menu').removeClass('visible');
        $('.immobili_menu .menu_affitto').addClass('visible');
    });
    $('.immobili_contratto.immobili_nuda-proprieta').on('click', function () {
        $('.immobili_contratto').removeClass('active');
        $(this).addClass('active');
        $('.immobili_menu .immobili_menu').removeClass('visible');
        $('.immobili_menu .menu_nuda-proprieta').addClass('visible');
    });

    // $('.controls').on('click', function () {
    //     var wrapper = $(this).siblings('.annuncio-slideshow-inner');
    //     var children = $(wrapper).children();
    //     var count = children.length;
    //     var selected;
    //     if ($(this).hasClass('slide-prev')) {
    //         selected = children[count - 1];
    //         $(selected).prependTo($(wrapper));
    //     } else {
    //         selected = children[0];
    //         $(selected).appendTo($(wrapper));
    //     }
    // });

    $('.controls').on('click', function () {
        var wrapper = $(this).siblings('.annuncio-slideshow-inner');
        var type = $(wrapper).parents('.annuncio-slideshow').attr('class').split(" ")[1];
        var children = $(wrapper).children();
        var count = children.length;
        var selected = 0;
        for (var i = 0; i < count; i++) {
            selected = $(children[i]).hasClass('selected') ? i : selected;
        }
        if ($(this).hasClass('slide-prev')) {
            selected--;
        } else {
            selected++;
        }
        moveGallery(selected, type);
    });

    $('.toggler-flats').on('click', function () {
        // console.log('qui');
        $('.immobili_menu_container').toggleClass('open');
    });
    $('.immobili_menu_inner').on('click', function (e) {
        e.stopPropagation();
    });

    $('.annuncio-tab').on('click', function () {
        $('.annuncio-tab').removeClass('active');
        $(this).addClass('active');
        var tab = ($(this).data('tab'));
        $('.annuncio-tab-content').removeClass('active');
        $('.annuncio-tab-content.' + tab).addClass('active');
        royalGallerySlider();
    })

    $('.royalFormInfo').submit(function () {
        var form = $(this);
        var dati = {
            action: 'royalmail',
            nome: form.find('.royalFormNome').val(),
            testo: form.find('.royalFormTesto').val(),
            email: form.find('.royalFormEmail').val(),
            annuncio: form.find('.royalFormAnnuncio').val(),
        };
        $.post(royalconf.ajax, dati, function (res) {
            var errori = form.find('.royalFormErrori');
            errori.html('');
            if (res.length) {
                for (var i = 0; i < res.length; i++) {
                    $('<li>' + res[i] + '</li>').appendTo(errori);
                }
            } else {
                form.find('.royalFormOk').show();
            }
        }, 'json');
        return false;
    });
    $('#royalMapSearchForm')
        .on('change', '.interruttore', function () {
            // console.log(this);
            // console.log(markers);
            var visComune, visTipologia, visContratto, j;
            for (var i = markers.length - 1; i >= 0; i--) {
                if (typeof markers[i] == 'undefined') {
                    continue;
                }
                visComune = visTipologia = visContratto = false;
                for (j = markers[i].comune.length - 1; j >= 0; j--) {
                    if ($('.interruttore[data-tipo="comune"][data-valore="' + markers[i].comune[j] + '"]').prop('checked')) {
                        visComune = true;
                        break;
                    }
                }
                for (j = markers[i].tipologia.length - 1; j >= 0; j--) {
                    if ($('.interruttore[data-tipo="tipologia"][data-valore="' + markers[i].tipologia[j] + '"]').prop('checked')) {
                        visTipologia = true;
                        break;
                    }
                }
                for (j = markers[i].contratto.length - 1; j >= 0; j--) {
                    if ($('.interruttore[data-tipo="contratto"][data-valore="' + markers[i].contratto[j] + '"]').prop('checked')) {
                        visContratto = true;
                        break;
                    }
                }
                markers[i].marker.setVisible(visComune && visTipologia && visContratto);
            }

        });


});

jQuery(window).on('scroll', function () {
    var scrolly = window.scrollY;
    if (scrolly > 350) {
        jQuery('#header:not(.isIndex):not(.isSingle)').addClass('fixed');
    } else {
        jQuery('#header').removeClass('fixed');
    }
});

var markers = [];

function royalInitMap() {
    var geocoder;
    var container = document.getElementById('royalMap');
    if (container) {
        var address = jQuery('#royalMap').data('indirizzo');

        geocoder = new google.maps.Geocoder();
        geocoder.geocode({'address': address}, function (results, status) {
            if (status === 'OK') {
                var map = new google.maps.Map(container, {
                    zoom: 14,
                    center: results[0].geometry.location
                });
                /*var marker = */
                new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location
                });
            }
        });

    } else {
        container = document.getElementById('royalMapSearch');
        if (container) {
            var data = jQuery.parseJSON(jQuery('#royalMapSearchData').text());
            // console.log(data);
            geocoder = new google.maps.Geocoder();
            var map = new google.maps.Map(container, {
                center: {lat: 44.3594345, lng: 9.3540266},
                zoom: 10
            });
            var eleComune = [], eleTipologia = [], eleContratto = [];
            var infowindow = new google.maps.InfoWindow();
            for (var i = data.length - 1; i >= 0; i--) {
                geocoder.geocode(
                    {'address': data[i].address},
                    (function (info, i) {
                        return function (results, status) {
                            if (status === 'OK') {
                                var j;
                                markers[i] = {
                                    marker: new google.maps.Marker({
                                        map: map,
                                        position: results[0].geometry.location,
                                        title: info.title
                                    }),
                                    comune: [],
                                    tipologia: [],
                                    contratto: []
                                };
                                var contentString = '<a href="' + info.permalink + '"><img style="float: left; margin:5px;" src="' + info.thumbnail + '"></a><h3>' + info.title + '</h3><p>' + info.address + '</p>';
                                for (j = info.comune.length - 1; j >= 0; j--) {
                                    if (typeof eleComune[info.comune[j].slug] == 'undefined') {
                                        eleComune[info.comune[j].slug] = true;
                                        jQuery('<div class="fake-checkbox"><input id="' + info.comune[j].slug + '" type="checkbox" checked class="interruttore" data-tipo="comune" data-valore="' + info.comune[j].slug + '"><label for="' + info.comune[j].slug + '">' + info.comune[j].name + '</label></div>').appendTo('#royalMapSearchComune');
                                    }
                                    markers[i].comune.push(info.comune[j].slug);
                                }
                                for (j = info.contratto.length - 1; j >= 0; j--) {
                                    if (typeof eleContratto[info.contratto[j].slug] == 'undefined') {
                                        eleContratto[info.contratto[j].slug] = true;
                                        var vend = info.contratto[j].slug == "vendita";
                                        markers[i].marker.setVisible(vend);
                                        jQuery('<div class="fake-radio-menu"><input id="' + info.contratto[j].slug + '" type="radio"' + ( vend ? ' checked' : '' ) + ' class="interruttore" data-tipo="contratto" data-valore="' + info.contratto[j].slug + '"><label for="' + info.contratto[j].slug + '">' + info.contratto[j].name + '</label></div>').appendTo('#royalMapSearchContratto');
                                    }
                                    markers[i].contratto.push(info.contratto[j].slug);
                                }
                                for (j = info.tipologia.length - 1; j >= 0; j--) {
                                    if (typeof eleTipologia[info.tipologia[j].slug] == 'undefined') {
                                        eleTipologia[info.tipologia[j].slug] = true;
                                        jQuery('<div class="fake-checkbox"><input id="' + info.tipologia[j].slug + '" type="checkbox" checked class="interruttore" data-tipo="tipologia" data-valore="' + info.tipologia[j].slug + '"><label for="' + info.tipologia[j].slug + '">' + info.tipologia[j].name + '</label></div>').appendTo('#royalMapSearchTipologia');
                                    }
                                    markers[i].tipologia.push(info.tipologia[j].slug);
                                }
                                markers[i].marker.addListener('click', function () {
                                    infowindow.setContent(contentString);
                                    infowindow.open(map, markers[i].marker);
                                });
                            }
                        }
                    })
                    (data[i], i)
                );
            }
            jQuery('#royalMapSearchForm').find('.interruttore').trigger('change');
        }
    }
}

jQuery(window).on('load', function () {
    royalGallerySlider();
});

jQuery(window).on('resize', function () {
    royalGallerySlider();
})


function royalGallerySlider() {
    var $ = jQuery;
    var container = $('.annuncio-slideshow');
    var id = 0;
    $(container).each(function () {
        var inner = $(this).children('.annuncio-slideshow-inner');
        var photos = $(inner).children();
        var width = $(this).outerWidth();

        $(inner).css({
            width: width * photos.length
        });
        $(photos).each(function () {
            if ($(this).hasClass('selected')) {
                id = $(this).data('slideshowid');
                $(inner).css({
                    transform: "translateX(-" + (width * id) + "px)"
                })
            }
            $(this).css({
                width: width
            });
        });
    });

}


function moveGallery(id, type) {
    var $ = jQuery;
    var container = $('.annuncio-slideshow.' + type);
    var thumbs = $('.annuncio-slideshow-thumbs.' + type + ' .annuncio-slideshow-thumbs-inner').children();
    var inner = container.children('.annuncio-slideshow-inner');
    var photos = $(inner).children();
    var width = container.outerWidth();
    $(inner).css({
        transform: "translateX(-" + (width * id) + "px)"
    });
    $(photos).each(function () {
        $(this).removeClass('selected')
        if (id === $(this).data('slideshowid')) {
            $(this).addClass('selected');
        }
    })
    var next = (inner.siblings('.slide-next'));
    var prev = (inner.siblings('.slide-prev'));
    inner.siblings().removeClass('disabled');
    if (id === 0) {
        prev.addClass('disabled')
    }
    if (id === (photos.length - 1)) {
        next.addClass('disabled')
    }
    thumbs.removeClass('selected');
    $(thumbs[id]).addClass('selected');
}
