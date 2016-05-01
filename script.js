
var map = [],
    markers = [];

function initMap() {
    var myOptions = {
        zoom: 14,
        center: new google.maps.LatLng(locations[0].latitude, locations[0].longitude),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById("map"), myOptions);

    var infowindow = new google.maps.InfoWindow();
    var marker, i;

    for (i = 0; i < locations.length; i++) {
        if (locations[i].latitude !== '') {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i].latitude, locations[i].longitude),
                map: map
            });

            marker.addListener('click', (function(marker, i) {
                return function() {
                    infowindow.setContent(locations[i].markup);
                    infowindow.open(map, marker);
                };
            })(marker, i));
        }
    }
}
