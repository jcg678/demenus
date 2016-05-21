function initMap()
{
    console.log("prueba");
    var jaen = {lat: 37.8139348, lng: -3.3807777};
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 9,
        center: jaen,
        streetViewControl: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        zoomControl: true,
        zoomControlOptions: {
            position: google.maps.ControlPosition.RIGHT_BOTTOM
        },
        mapTypeControlOptions: {
            style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
            position: google.maps.ControlPosition.RIGHT_BOTTOM
        }
    });

}
