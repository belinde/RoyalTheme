jQuery(function ($) {
    $('.toggler-menu').on('click', function () {
        console.log('qui');
        $('.menu-mobile-inner').toggleClass('visible');
        $('.toggler-bg').toggleClass('visible');
    });
    $('.immobili_vendite').on('click', function () {
        $(this).addClass('active');
        $('.menu_vendite').addClass('visible');
        $('.immobili_affitti').removeClass('active');
        $('.menu_affitti').removeClass('visible');
    });
    $('.immobili_affitti').on('click', function () {
        $(this).addClass('active');
        $('.menu_affitti').addClass('visible');
        $('.immobili_vendite').removeClass('active');
        $('.menu_vendite').removeClass('visible');
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
    })
});

jQuery(window).on('scroll', function () {
    var scrolly = window.scrollY;
    if (scrolly > 150) {
        jQuery('#header').addClass('fixed');
    } else {
        jQuery('#header').removeClass('fixed');
    }
});

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
            } else {
                alert('Geocode was not successful for the following reason: ' + status);
            }
        });

    } else {
        container = document.getElementById('royalMapSearch');
        if (container) {
            var data = jQuery.parseJSON(jQuery('#royalMapSearch').text());
            geocoder = new google.maps.Geocoder();
            var map = new google.maps.Map(container, {
                center: {lat: 44.3594345, lng: 9.3540266},
                zoom: 10
            });
            for (var i = data.length - 1; i >= 0; i--) {
                geocoder.geocode(
                    {'address': data[i].address},
                    (function (info) {
                        return function (results, status) {
                            if (status === 'OK') {
                                var contentString = '<a href="' + info.permalink + '"><img style="float: left; margin:5px;" src="' + info.thumbnail + '"></a><h3>' + info.title + '</h3><p>' + info.address + '</p>';
                                var infowindow = new google.maps.InfoWindow({
                                    content: contentString
                                });
                                var marker = new google.maps.Marker({
                                    map: map,
                                    position: results[0].geometry.location,
                                    title: info.title
                                });
                                marker.addListener('click', function () {
                                    infowindow.open(map, marker);
                                });
                            }
                        }
                    })(data[i])
                );
            }
        }
    }
}