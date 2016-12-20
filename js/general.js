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
                zoom: 14,
                center: {lat: 45, lon: 45}
            });
            for (var i = data.length - 1; i >= 0; i--) {
                geocoder.geocode({'address': data[i].address}, function (results, status) {
                    if (status === 'OK') {
                        new google.maps.Marker({
                            map: map,
                            position: results[0].geometry.location
                        });
                    }
                });
            }
        }
    }
}