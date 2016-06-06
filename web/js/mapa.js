
var latitud;
var longitud;

function crearMapa(punto,zoom){

    map = new google.maps.Map(document.getElementById('map'), {
        zoom: zoom,
        center: punto,
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

function initMap()
{


    function success(pos) {

        var crd = pos.coords;
         latitud= crd.latitude;
        longitud=crd.longitude;

        console.log('Your current position is:');
        console.log('Latitude : ' + crd.latitude);
        console.log('Longitude: ' + crd.longitude);
        console.log('More or less ' + crd.accuracy + ' meters.');
        var punto = {lat: latitud, lng: longitud};
        zoom=14;

        crearMapa(punto,zoom);

        var marker = new google.maps.Marker({
            position:punto,
            map: map,
            title: 'Hello World!'
        });
        marker.setIcon('https://dl.dropboxusercontent.com/u/20056281/Iconos/male-2.png');

    };


    function error(err) {
        console.warn('ERROR(' + err.code + '): ' + err.message);
    };
    if (navigator.geolocation) {
        punto = {lat: 37.8139348, lng: -3.3807777};
        zoom = 9;
        navigator.geolocation.getCurrentPosition(success, crearMapa(punto,zoom));
    }
    else
    {
        zoom=9;
        console.log("XXx");
        var punto = {lat: 37.8139348, lng: -3.3807777};
        crearMapa(punto,zoom);
    }

    console.log("prueba");

/*
    var ajax_data = {
        "id"     : blog.id,
        "name"   : blog.name,
        "url"    : blog.url,
        "author" : blog.author
    };

    $.ajax({
        url: destination.url,
        data: ajax_data,
        type: "post",
        success: function(json) {

        },
        error:function (xhr, ajaxOptions, thrownError) {

        }
    });
*/




}





