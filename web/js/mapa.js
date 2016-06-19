
var latitud;
var longitud;
var map;
var puntos =[];
var allInfos = [];
var markerCluster;

$( document ).ready(function() {
    $('#quitar').click(function () {

       removepuntos(puntos);
    });

    $('#reiniciar').click(function () {

        initMap();
    });

    $('#buscar').click(function () {
        nombre = $("#nombre").val();
        localidad=$("#localidad").val();
        cp=$("#cp").val();
        provincia=$("#provincia").val();

        removepuntos(puntos);

        $.ajax({
            url: "busqueda_avanzada",
            data: {nombre: nombre,localidad: localidad,cp: cp,provincia: provincia},
            scriptCharset: "utf-8",
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                lanzarToast(data.length);
                pintaPuntos(data);

            },
            error: function (xhr, status) {
                alert('Disculpe, existió un problema ->' + status);
            }
        });

    });
    
});


function  removepuntos(conjunto) {

    conjunto.forEach(function (marker) {
        marker.setMap(null);
        
    });
    markerCluster.clearMarkers();
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
            position: google.maps.ControlPosition.RIGHT_CENTER
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
            icon: 'images/marker/marker.png',
            map: map,
            title: item.nombre,
            animation: google.maps.Animation.DROP,
        });

        puntos.push(marker);

        var contentString ="<h4><i class='fa fa-cutlery'></i>  "+item.nombre+"</h4>"
            +"<h5><i class='fa fa-list-alt'></i>  <a href='verficha/"+item.id+"' target='_blank' >Ver ficha</a></h5>";
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
    markerCluster = new MarkerClusterer(map, puntos);

}


function initMap()
{


    function success(pos) {

        var crd = pos.coords;
        latitud= crd.latitude;
        longitud=crd.longitude;

        var punto = {lat: latitud, lng: longitud};
        zoom=14;

        crearMapa(punto,zoom);

        var marker = new google.maps.Marker({
            position:punto,
            map: map,
            title: 'DeMenus'
        });
        marker.setIcon('https://dl.dropboxusercontent.com/u/20056281/Iconos/male-2.png');


    };



    if (navigator.geolocation)
    {
        punto = {lat: 37.8139348, lng: -3.3807777};
        zoom = 9;
        navigator.geolocation.getCurrentPosition(success, crearMapa(punto,zoom));
    }
    else
    {
        zoom=16;

        var punto = {lat: 37.8139348, lng: -3.3807777};
        crearMapa(punto,zoom);
    }

    
}


function closeInfos() {
    for (i = 0; i < allInfos.length; i++) {
        allInfos[i].close();
    }
}


function lanzarToast(x) {
    if(x>=1){
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-full-width",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        var toast=toastr["success"]("<b>Hay "+x+" coincidencias</b>");
    }else{

        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-full-width",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        var toast=toastr["error"]("Ningún resultado");
    }
}