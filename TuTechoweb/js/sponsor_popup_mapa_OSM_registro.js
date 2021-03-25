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

//###################CODIGO PARA COLOCAR EL MAPA DEL FORMULARIO EN CARGA INICIAL #########################################

  if ($('#mapa_sponsor_coordenada_lat').val() !== '') {//cargar mapa de form editar o form borrador

    var lat_borrador = $('#mapa_sponsor_coordenada_lat').val();
    var lng_borrador = $('#mapa_sponsor_coordenada_lng').val();
    var zoom_borrador = $('#mapa_sponsor_zoom').val();

    L.mapbox.accessToken = 'pk.eyJ1IjoiZnVzaWxvbjk0IiwiYSI6ImNqb204ODF3NjBob2szcWtrcjgwc3lwZjEifQ.y6VWJLe3kPWhEPX8x0YfvQ';

    var mymap = L.map('mapid_sponsor_config', {doubleClickZoom: false })
    .addLayer(L.mapbox.styleLayer('mapbox://styles/fusilon94/ckgmmpknk1cxb19pmkrq0duuv', {maxZoom: 18, tileSize: 512, zoomOffset: -1}))
    .setView([lat_borrador, lng_borrador], zoom_borrador);

  } else {//cargar mapa inicial, crear nuevo sponsor
    L.mapbox.accessToken = 'pk.eyJ1IjoiZnVzaWxvbjk0IiwiYSI6ImNqb204ODF3NjBob2szcWtrcjgwc3lwZjEifQ.y6VWJLe3kPWhEPX8x0YfvQ';

    var mymap = L.map('mapid_sponsor_config', {doubleClickZoom: false })
    .addLayer(L.mapbox.styleLayer('mapbox://styles/fusilon94/ckgmmpknk1cxb19pmkrq0duuv', {maxZoom: 18, tileSize: 512, zoomOffset: -1}))
    .setView([datos_pais['lat'], datos_pais['lng']], datos_pais['zoom']);

  }


  if ($('#mapa_sponsor_coordenada_lat').val() !== '') {
    var lat_borrador = $('#mapa_sponsor_coordenada_lat').val();
    var lng_borrador = $('#mapa_sponsor_coordenada_lng').val();
    var zoom_borrador = $('#mapa_sponsor_zoom').val();

    var marker = L.marker([lat_borrador, lng_borrador]).addTo(mymap);
  }else {
  var marker = L.marker();
  };


function onMapClick(e){

  marker.setLatLng(e.latlng)
        .addTo(mymap);

  var current_zoom = mymap.getZoom();
  $('#mapa_sponsor_coordenada_lat').val(e.latlng.lat).trigger('change');
  $('#mapa_sponsor_coordenada_lng').val(e.latlng.lng).trigger('change');
  $('#mapa_sponsor_zoom').val(current_zoom).trigger('change');
};

mymap.on("contextmenu", onMapClick);//crea un marker al hacer click derecho en PC o click touch largo en mobile


//#############CODIGO PARA COLOCAR EL MAPA DE PREVISUALIZACION EN CARGA INICIAL DE BORRADOR O EDIT #########################

// CODIGO QUE PONE EL MAPA EN LA PREVISUALISACION DEL POPUP DESPUES DEL CLICK DERECHO o LONG TOUCH EN MAPA CONFIG##############

  if ($('#mapa_sponsor_coordenada_lat').val() !== '') {//cargar mapa de form editar o form borrador

    var lat_borrador = $('#mapa_sponsor_coordenada_lat').val();
    var lng_borrador = $('#mapa_sponsor_coordenada_lng').val();
    var zoom_borrador = $('#mapa_sponsor_zoom').val();

    if (zoom_borrador !== '') {
      $(".popup_sponsor_mapa").css('height', 'unset');
      // Se crea en mapa estatico en el modo PC

      L.mapbox.accessToken = 'pk.eyJ1IjoiZnVzaWxvbjk0IiwiYSI6ImNqb204ODF3NjBob2szcWtrcjgwc3lwZjEifQ.y6VWJLe3kPWhEPX8x0YfvQ';

      var mymap2 = L.map('mapid_sponsor', {zoomControl: false, attributionControl: false, dragging: false, scrollWheelZoom: false, boxZoom: false, doubleClickZoom: false })
      .addLayer(L.mapbox.styleLayer('mapbox://styles/fusilon94/ckgmmpknk1cxb19pmkrq0duuv', {maxZoom: 18, tileSize: 512, zoomOffset: -1}))
      .setView([lat_borrador, lng_borrador], zoom_borrador);


      if (lat_borrador !== '' && lng_borrador !== '') {
        var marker2 = L.marker([lat_borrador, lng_borrador]).addTo(mymap2);
      }else {
      var marker2 = L.marker();
      };


      // Se crea en mapa estatico en el modo MOBILE
      L.mapbox.accessToken = 'pk.eyJ1IjoiZnVzaWxvbjk0IiwiYSI6ImNqb204ODF3NjBob2szcWtrcjgwc3lwZjEifQ.y6VWJLe3kPWhEPX8x0YfvQ';

      var mymap3 = L.map('mapid_sponsor2', {zoomControl: false, attributionControl: false, dragging: false, scrollWheelZoom: false, boxZoom: false, doubleClickZoom: false })
      .addLayer(L.mapbox.styleLayer('mapbox://styles/fusilon94/ckgmmpknk1cxb19pmkrq0duuv', {maxZoom: 18, tileSize: 512, zoomOffset: -1}))
      .setView([lat_borrador, lng_borrador], zoom_borrador);


      if (lat_borrador !== '' && lng_borrador !== '') {
        var marker3 = L.marker([lat_borrador, lng_borrador]).addTo(mymap3);
      }else {
        var marker3 = L.marker();
      };

    };

  };






})
