$(document).ready(function(){


  let datos_pais;
    $.ajax({
      type: "POST",
      url: "../../contenido/m5/process-request-coordenadas-paises.php",
      dataType: 'json',
      async: false,
    }).done(function(data){
      datos_pais = data;
    });    

    
var lat = $('#mapa_coordenada_lat').val();
var lng = $('#mapa_coordenada_lng').val();
var zoom = $('#mapa_zoom').val();

L.mapbox.accessToken = 'pk.eyJ1IjoiZnVzaWxvbjk0IiwiYSI6ImNqb204ODF3NjBob2szcWtrcjgwc3lwZjEifQ.y6VWJLe3kPWhEPX8x0YfvQ';

if (lat !== '' && lng !== '') {

  var mymap = L.map('mapid', {doubleClickZoom: false })
.addLayer(L.mapbox.styleLayer('mapbox://styles/fusilon94/ckgmmpknk1cxb19pmkrq0duuv', {maxZoom: 17, tileSize: 512, zoomOffset: -1}))
.setView([lat, lng], zoom);

}else {
  
  var mymap = L.map('mapid', {doubleClickZoom: false })
.addLayer(L.mapbox.styleLayer('mapbox://styles/fusilon94/ckgmmpknk1cxb19pmkrq0duuv', {maxZoom: 17, tileSize: 512, zoomOffset: -1}))
.setView([datos_pais['lat'], datos_pais['lng']], datos_pais['zoom']);

};

var marcador_tutecho = L.icon({
    iconUrl: '../../objetos/marcador_tutecho.svg',

    iconSize:     [45, 102], // size of the icon
    shadowSize:   [50, 64], // size of the shadow
    iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
    shadowAnchor: [4, 62],  // the same for the shadow
    popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
});

if (lat !== '' && lng !== '') {
  var marker = L.marker([lat, lng], {icon: marcador_tutecho}).addTo(mymap);
}else {
var marker = L.marker([,], {icon: marcador_tutecho});
};

function onMapClick(e){

  marker.setLatLng(e.latlng)
        .addTo(mymap);

  var current_zoom = mymap.getZoom();
  $('#mapa_coordenada_lat').val(e.latlng.lat).trigger('change');
  $('#mapa_coordenada_lng').val(e.latlng.lng).trigger('change');
  $('#mapa_zoom').val(current_zoom).trigger('change');
};

mymap.on("contextmenu", onMapClick);

})
