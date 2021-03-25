$(document).ready(function(){

// Codigo para establecer la vista del mapa OSM
var lat = $('#mapa_sponsor_coordenada_lat').val();
var lng = $('#mapa_sponsor_coordenada_lng').val();
var zoom = $('#mapa_sponsor_zoom').val();



if (lat !== '' && lng !== '') {

  L.mapbox.accessToken = 'pk.eyJ1IjoiZnVzaWxvbjk0IiwiYSI6ImNqb204ODF3NjBob2szcWtrcjgwc3lwZjEifQ.y6VWJLe3kPWhEPX8x0YfvQ';
  var mymap = L.map('mapid_sponsor', {attributionControl: false, scrollWheelZoom: false, boxZoom: false, doubleClickZoom: false })
  .addLayer(L.mapbox.styleLayer('mapbox://styles/fusilon94/ckgmmpknk1cxb19pmkrq0duuv', {maxZoom: 18, tileSize: 512, zoomOffset: -1}))
  .setView([lat, lng], zoom);
  
}else {
  L.mapbox.accessToken = 'pk.eyJ1IjoiZnVzaWxvbjk0IiwiYSI6ImNqb204ODF3NjBob2szcWtrcjgwc3lwZjEifQ.y6VWJLe3kPWhEPX8x0YfvQ';
  var mymap = L.map('mapid_sponsor', {zoomControl: false})
  .addLayer(L.mapbox.styleLayer('mapbox://styles/fusilon94/ckgmmpknk1cxb19pmkrq0duuv', {maxZoom: 18, tileSize: 512, zoomOffset: -1}))
  .setView([-16.495842685360415, -68.14600431193249], 5);
};


if (lat !== '' && lng !== '') {
  var marker = L.marker([lat, lng]).addTo(mymap);
}else {
var marker = L.marker();
};

})
