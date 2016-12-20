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
                var marker = new google.maps.Marker({
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