jQuery(function ($) {
    $('.toggler-menu').on('click', function () {
        console.log('qui');
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

    $('.controls').on('click', function () {
        var wrapper = $('.annuncio-slideshow-inner');
        var children = $(wrapper).children();
        var count = children.length;
        var selected;
        if ($(this).hasClass('slide-prev')) {
            selected = children[count - 1];
            $(selected).prependTo($(wrapper));
        } else {
            selected = children[0];
            $(selected).appendTo($(wrapper));
        }
    });

    $('.toggler-flats').on('click', function () {
        console.log('qui');
        $('.immobili_menu_container').toggleClass('open');
    });
    $('.immobili_menu_inner').on('click', function (e) {
        e.stopPropagation();
    });

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
            if ( res.length) {
                for (var i = 0; i < res.length; i++) {
                    $('<li>' + res[i] + '</li>').appendTo(errori);
                }
            } else {
                form.find('.royalFormOk').show();
            }
        }, 'json');
        return false;
    });
    $('#royalMapSearchForm').on('change', '.interruttore', function () {
        console.log(this);
        console.log(markers);
        var visComune, visTipologia, visContratto, j;
        for (var i = markers.length - 1; i >= 0; i--) {
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
    if (scrolly > 150) {
        jQuery('#header:not(.isIndex)').addClass('fixed');
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
            console.log(data);
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
                                        jQuery('<li><label><input type="checkbox" checked class="interruttore" data-tipo="comune" data-valore="' + info.comune[j].slug + '">&nbsp;' + info.comune[j].name + '</label></li>').appendTo('#royalMapSearchComune');
                                    }
                                    markers[i].comune.push(info.comune[j].slug);
                                }
                                for (j = info.contratto.length - 1; j >= 0; j--) {
                                    if (typeof eleContratto[info.contratto[j].slug] == 'undefined') {
                                        eleContratto[info.contratto[j].slug] = true;
                                        jQuery('<li><label><input type="checkbox" checked class="interruttore" data-tipo="contratto" data-valore="' + info.contratto[j].slug + '">&nbsp;' + info.contratto[j].name + '</label></li>').appendTo('#royalMapSearchContratto');
                                    }
                                    markers[i].contratto.push(info.contratto[j].slug);
                                }
                                for (j = info.tipologia.length - 1; j >= 0; j--) {
                                    if (typeof eleTipologia[info.tipologia[j].slug] == 'undefined') {
                                        eleTipologia[info.tipologia[j].slug] = true;
                                        jQuery('<li><label><input type="checkbox" checked class="interruttore" data-tipo="tipologia" data-valore="' + info.tipologia[j].slug + '">&nbsp;' + info.tipologia[j].name + '</label></li>').appendTo('#royalMapSearchTipologia');
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
                )
                ;
            }
        }
    }
}
