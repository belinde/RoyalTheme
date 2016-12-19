function royalInitMap() {
    var container = document.getElementById('royalMap');
    if (container) {
        var address = jQuery('#royalMap').data('indirizzo');

        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({'address': address}, function(results, status) {
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

    }
}