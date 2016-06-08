
var latitud;
var longitud;
var map;
var puntos =[];
var allInfos = [];

$( document ).ready(function() {
    $('#quitar').click(function () {
        console.log(puntos);
       removepuntos(puntos);
    });
});


function  removepuntos(conjunto) {
    conjunto.forEach(function (marker) {
        marker.setMap(null);
        
    });
}

function cargartodos() {
    var datos;
    $.ajax({
        url: "todos_locales",
        scriptCharset: "utf-8",
        type: 'POST',
        processData: true,
        dataType: 'json',
        success: function (data) {
            pintaPuntos(data);

        },
        error: function (xhr, status) {
            alert('Disculpe, existió un problema ->' + status);
        }
    });

}

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
    cargartodos();
}

function pintaPuntos(objetos) {
    allInfos=[];
    puntos = [];
    objetos.forEach(function (item) {
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(item.latitud, item.longitud),
            map: map,
            title: item.nombre,
            animation: google.maps.Animation.DROP,
        });

        puntos.push(marker);

        var contentString ="Nombre:"+item.nombre+"<br>"+"<a href='verficha/"+item.id+"' target='_blank' >Ver ficha</a>";
        var infowindow = new google.maps.InfoWindow({
            content: contentString
        });
        allInfos.push(infowindow);
        google.maps.event.addListener(marker, 'click', (function () {
            closeInfos();
            map.panTo(new google.maps.LatLng(item.latitud, item.longitud));
            infowindow.open(map, this);
        }));

    })


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



    if (navigator.geolocation) {
        punto = {lat: 37.8139348, lng: -3.3807777};
        zoom = 9;
        navigator.geolocation.getCurrentPosition(success, crearMapa(punto,zoom));

    }
    else
    {
        zoom=16;
        console.log("XXx");
        var punto = {lat: 37.8139348, lng: -3.3807777};
        crearMapa(punto,zoom);

    }

    console.log("prueba");


    
}


function closeInfos() {
    for (i = 0; i < allInfos.length; i++) {
        allInfos[i].close();
    }
}



