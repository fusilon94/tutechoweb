$(document).ready(function(){
  jQuery(function($){

    let cookie_pais;
    $.ajax({
      async: false,
      url: "../../js/js.cookie.js",
      dataType: "script"
    }).done(function(){
      cookie_pais = Cookies.get('tutechopais');
    });


  var url_country_file = `../../geojson_files/${cookie_pais}/`;

  let datos_pais;
  
  $.ajax({
    type: "POST",
    url: "process-request-coordenadas-paises.php",
    dataType: 'json',
    async: false,
  }).done(function(data){
    datos_pais = data;
  });

  $(".departamento_label").html(datos_pais['org_territorial']);
  $(".departamento_blur").html(`Elige ${datos_pais['org_territorial']}/Ciudad`);

//###################CODIGO PARA COLOCAR EL MAPA DEL FORMULARIO EN CARGA INICIAL #########################################

  L.mapbox.accessToken = 'pk.eyJ1IjoiZnVzaWxvbjk0IiwiYSI6ImNqb204ODF3NjBob2szcWtrcjgwc3lwZjEifQ.y6VWJLe3kPWhEPX8x0YfvQ';

  var mymap = L.map('mapid_config', {doubleClickZoom: false })
    .addLayer(L.mapbox.styleLayer('mapbox://styles/fusilon94/ckgmmpknk1cxb19pmkrq0duuv', {maxZoom: 18, tileSize: 512, zoomOffset: -1}))
    .setView([datos_pais['lat'], datos_pais['lng']], datos_pais['zoom']);

  var marcador_tutecho = L.icon({ // Se define el tipo de marcador que se colocara
    iconUrl: '../../objetos/marcador_tutecho.svg',

    iconSize:     [45, 102], // size of the icon
    shadowSize:   [50, 64], // size of the shadow
    iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
    shadowAnchor: [4, 62],  // the same for the shadow
    popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
  });

  var markers_list = {};// Se define la lista de marcadores

  function onMarkerClick(e){// se define la funcion que se llamará al hacer click en un marker
    var departamentoSelected = this.options.marker_id;
    $('#departamento_busqueda').val(departamentoSelected).selectmenu("refresh"); // SE RESTABLECE EL VALOR DE DEPARTAMENTO EN EL MENU SELECT
    refresh_ciudades(departamentoSelected);
    // ahora pedimos las coordenadas y el zoom del pais
    $.ajax({//Se trae el json file con las datos para el trazado del polygono
    dataType: "json",
    url: url_country_file + "views_coordinates.json",// REGLA: SOLO USAR ZOOMS 8 y 9 AL DEFINIR ESTOS FILES
    success: function(data) {
        refresh_view(data[departamentoSelected]['lat'], data[departamentoSelected]['lng'], data[departamentoSelected]['zoom']);
        hide_departamentos_limits();
        hide_departamentos_markers();
        show_agencias();
        $(".blur_container").hide();
        load_agencias_departamento(departamentoSelected);
    }
  }).error(function() {});

  };
//Se traer las coordenadas de los markers de Departamentos del Pais en carga inicial ################################
  $.ajax({
  dataType: "json",
  url: url_country_file + "markers.json",
  success: function(data) {
      $.each(departamentos, function( index, value ) { // Se compara la lista de Markers_Departamentos y la lista Departamentos_con_agencias
        $.each(data, function( depa, coordenadas ) {
          if (depa == value['departamentos']) {
            markers_list[depa] = {coordenadas:coordenadas, agencias:value['agencias']};;//Se añade a la lista de marcadores aquellos de Departamentos con agencias, coordenadas y cantidad de agencias
          };
        });
      });

      $.each(markers_list, function( index, value ) {//Se añaden al mapa los marcadores de Departamentos con agencias
        L.marker([value['coordenadas']['lat'], value['coordenadas']['lng']], {icon: marcador_tutecho, marker_id: index}).addTo(mymap).bindPopup(index, {closeButton: false, className: "popup_map"}).on('mouseover', function (e) {this.openPopup();}).on('mouseout', function (e) {this.closePopup();}).on('click', onMarkerClick).bindTooltip(value['agencias'], {permanent: true, opacity: 0.9, className: 'tooltip_map', direction: 'center', offset: [2.8,64]})._icon.classList.add("marcador_departamento");
        //se definen las coordenadas del marcador, se define el tipo de marcador, se lo agrega al mapa, se le otorga un popup que contiene el nombre del Departamento y No tiene boton de cierre, se le agrega una classe para estilarlo, se define que la apertura y el cierre se hace con evento hover unicamente, al hacer click se llama a la funcion que controla el evento relacionado, se agrega un tooltip fijo, estilisado, centreado y bien posicionado para mostrar la cantidad de agencias, y se le agrega al icon una classe comun para mostrarlo o ocultarlo despues.
      });
  }
  }).error(function() {});

//Se traer las coordenadas de los markers de agencias del Pais en carga inicial pero que se mantienen hidden ################################

  var agencias = {};

  $.ajax({
      type: "POST",
      url: "process-request-agencias.php",
      dataType: "json",
  }).done(function(data){
      var agencias_datos = data;

      $.each(agencias_datos, function( index, value ) {//Se añaden al mapa los marcadores de Departamentos con agencias

        if (value['express'] == 1) {
          L.marker([value['mapa_coordenada_lat'], value['mapa_coordenada_lng']], {icon: marcador_tutecho, marker_id: value['id']}).addTo(mymap).bindPopup("TuTecho EXPRESS</br>" + value['location_tag'], {closeButton: false, className: "popup_map"}).on('mouseover', function (e) {this.openPopup();}).on('mouseout', function (e) {this.closePopup();}).on('click', onAgenciaClick)._icon.classList.add("marcador_agencias");
          //se definen las coordenadas del marcador agencia, se define el tipo de marcador, se lo agrega al mapa, se le otorga un popup que contiene el nombre del location_tag y No tiene boton de cierre, se le agrega una classe para estilarlo, se define que la apertura y el cierre se hace con evento hover unicamente, al hacer click se llama a la funcion que controla el evento relacionado, y se le agrega al icon una classe comun para mostrarlo o ocultarlo despues.
          L.circle([value['mapa_coordenada_lat'], value['mapa_coordenada_lng']], {
              color: 'rgb(51, 136, 255)',
              fillColor: 'rgb(51, 136, 255)',
              fillOpacity: 0.3,
              radius: 4000
          }).addTo(mymap);
          agencias[value['id']] = value;
        }else{
          L.marker([value['mapa_coordenada_lat'], value['mapa_coordenada_lng']], {icon: marcador_tutecho, marker_id: value['id']}).addTo(mymap).bindPopup("TuTecho</br>" + value['location_tag'], {closeButton: false, className: "popup_map"}).on('mouseover', function (e) {this.openPopup();}).on('mouseout', function (e) {this.closePopup();}).on('click', onAgenciaClick)._icon.classList.add("marcador_agencias");
          //se definen las coordenadas del marcador agencia, se define el tipo de marcador, se lo agrega al mapa, se le otorga un popup que contiene el nombre del location_tag y No tiene boton de cierre, se le agrega una classe para estilarlo, se define que la apertura y el cierre se hace con evento hover unicamente, al hacer click se llama a la funcion que controla el evento relacionado, y se le agrega al icon una classe comun para mostrarlo o ocultarlo despues.
          agencias[value['id']] = value;
        };

      });

      $(".marcador_agencias").hide();
  });

// SE DEFINE LA FUNCION A SER LLAMADA AL HACER CLICK EN UN MARCADOR DE AGENCIA INDIVIDUAL ###########################################

function onAgenciaClick(){
  var agenciaSelected = this.options.marker_id;
  mymap.flyTo([agencias[agenciaSelected]['mapa_coordenada_lat'], agencias[agenciaSelected]['mapa_coordenada_lng']], agencias[agenciaSelected]['mapa_zoom'])
  $(".blur_container").hide();
  load_agencia_selected(agenciaSelected);
};

// SE DEFINE Y CREA EL LAYER PARA DELIMITAR LOS DEPARTAMENTOS DEL PAIS ################################################################

var district_boundary = new L.geoJson(); // Se define una capa polygona de tipo geoJson
district_boundary.addTo(mymap); // se agrega la capa al mapa

$.ajax({//Se trae el json file con las datos para el trazado del polygono
dataType: "json",
url: url_country_file + "geojson.json",
success: function(data) {
    $(data.features).each(function(key, data) {//se agregan features a cada layer del trazado
        district_boundary.addData(data);//se los agrega a la capa geoJson
        district_boundary.setStyle({// se define la opacidad del contenido del trazado y el espesor de la linea contorno, el color el default
          fillOpacity: 0.4,
          weight: 2.5
          });
    });
}
}).error(function() {});

district_boundary.eachLayer(function (layer) {
    layer.bindPopup(layer.feature.properties.name);//a cada layer se le pone un nombre distinto para evitar error de llamado
    layer.style.pointerEvents = 'none';//para que no recojan eventos de tipo click
});

// SE DEFINE LOS EVENTOS SEGUIDOS A UN CAMBIO DE ZOOM LEVEL EN EL MAPA ####################################

  mymap.on('zoomend' , function (e) {

      if (mymap.getZoom()>8){
        hide_departamentos_markers();
        hide_departamentos_limits();
        show_agencias();
      };
      if (mymap.getZoom()<8) {
        show_departamentos_markers();
        show_departamentos_limits();
        hide_agencias();
      };

  });


// CODIGO SELECT-MENU ############################################

    $(function(){
      $(".select_menu").selectmenu();
    });

// POPULATE SELECT FIELDS WITH INFO FROM DATABASE ######################

    $('#departamento_busqueda').on('selectmenuchange', function() {
      var departamentoSelected = $("#departamento_busqueda option:selected").val();
      show_departamentos_limits();
      show_departamentos_markers();
      hide_agencias();

      if (departamentoSelected == 'Todo el País') {
        $("#ciudad_busqueda").prop('disabled', true).html("<option>Todas las ciudades</option>");
        $(".select_menu").selectmenu("refresh");

        // ahora pedimos las coordenadas y el zoom del pais
        $.ajax({//Se trae el json file con las datos para el trazado del polygono
        dataType: "json",
        url: url_country_file + "views_coordinates.json",
        success: function(data) {
            refresh_view(data['Country']['lat'], data['Country']['lng'], data['Country']['zoom']);
            show_departamentos_limits();
            show_departamentos_markers();
            hide_agencias();
            $(".all_results_container").empty();
            $(".blur_container").show();
            refresh_contador_agencias_total();
        }
      }).error(function() {});

      }else {
        $.ajax({
            type: "POST",
            url: "process-request-ciudades_busqueda_agencias.php",
            data: { departamentoChoice : departamentoSelected }
        }).done(function(data){
            $("#ciudad_busqueda").prop('disabled', false).html(data);
            $(".select_menu").selectmenu("refresh");
        });

        // ahora pedimos las coordenadas y el zoom del departamento elejido para actualizar el mapa
        $.ajax({
            type: "POST",
            url: "process-request-coordenadas_depa_ciudad_agencias.php",
            data: { departamentoChoice : departamentoSelected }
        }).done(function(data){
            $(".mapa_coordenadas_container").html(data);
            refresh_view_departamento();
            $(".blur_container").hide();
            load_agencias_departamento(departamentoSelected);
        });

      };

    });

    $("#ciudad_busqueda").on('selectmenuchange', function() {
        var ciudadSelected = $("#ciudad_busqueda option:selected").val();
          //ahora pedimos las coordenadas y el zoom del departamento elejido para actualizar el mapa

          if (ciudadSelected == 'Todas las ciudades') {
            var departamento_value = $("#departamento_busqueda option:selected").val();

            // ahora pedimos las coordenadas y el zoom del departamento elejido para actualizar el mapa
            $.ajax({
                type: "POST",
                url: "process-request-coordenadas_depa_ciudad_agencias.php",
                data: { departamentoChoice : departamento_value }
            }).done(function(data){
                $(".mapa_coordenadas_container").html(data);
                refresh_view_departamento();
                $(".blur_container").hide();
                load_agencias_departamento(departamento_value);
            });

          }else {
            $.ajax({
                type: "POST",
                url: "process-request-coordenadas_depa_ciudad_agencias.php",
                data: { ciudadesChoice : ciudadSelected }
            }).done(function(data){
                $(".mapa_coordenadas_container").html(data);
                refresh_view_ciudad();
                hide_departamentos_limits();
                hide_departamentos_markers();
                show_agencias();
                $(".blur_container").hide();
                load_agencias_ciudad(ciudadSelected);
            });
          };

    });

// SE DEFINE LA FUNCION PARA ACTUALIZAR VISTA DEL MAPA A TODO EL PAIS #################

function refresh_view(country_lat, country_lng, country_zoom){
  mymap.flyTo([country_lat, country_lng], country_zoom)
};

// SE DEFINE LA FUNCION PARA ACTUALIZAR VISTA DEL MAPA SEGUN LOS DATOS INGRESADOS EN DEPARTAMENTO #################

function refresh_view_departamento(){
  var lat = parseFloat($('#mapa_coordenada_lat').val());
  var lng = parseFloat($('#mapa_coordenada_lng').val());
  var zoom = parseInt($('#mapa_zoom').val());
  mymap.flyTo([lat, lng], zoom)
};

// SE DEFINE LA FUNCION PARA ACTUALIZAR VISTA DEL MAPA SEGUN LOS DATOS INGRESADOS EN CIUDAD #######################

function refresh_view_ciudad(){
  var lat = parseFloat($('#mapa_coordenada_lat').val());
  var lng = parseFloat($('#mapa_coordenada_lng').val());
  var zoom = parseInt($('#mapa_zoom').val());
  mymap.flyTo([lat, lng], zoom)
};

// SE DEFINE LA FUNCION QUE MUESTRA LOS MARKERS DE LAS AGENCIAS INDIVIDUALES ###############################

function show_agencias(){
  $(".marcador_agencias").show();
};

// SE DEFINE LA FUNCION QUE OCULTA LOS MARKERS DE LAS AGENCIAS INDIVIDUALES ###############################

function hide_agencias(){
  $(".marcador_agencias").hide();
};

// SE DEFINE LA FUNCION QUE OCULTA LA DIVISION DE DEPARTAMENTOS ###################################

function hide_departamentos_limits(){
  // $('.leaflet-overlay-pane').hide();
  district_boundary.setStyle({// se define la opacidad del contenido del trazado y el espesor de la linea contorno, el color el default
    fillOpacity: 0,
    weight: 2
    });
};

// SE DEFINE LA FUNCION QUE MUESTRA LA DIVISION DE DEPARTAMENTOS ###################################

function show_departamentos_limits(){
  // $('.leaflet-overlay-pane').show();
  district_boundary.setStyle({// se define la opacidad del contenido del trazado y el espesor de la linea contorno, el color el default
    fillOpacity: 0.4,
    weight: 2.5
    });
};

// SE DEFINE LA FUNCION QUE OCULTA LOS MARKERS DE DEPARTAMENTOS #####################################

function hide_departamentos_markers(){
  $('.marcador_departamento').hide();
  $('.tooltip_map').hide();
};

// SE DEFINE LA FUNCION QUE VUELVE A MOSTRAR LOS MARKERS DE DEPARTAMENTOS #####################################

function show_departamentos_markers(){
  $('.marcador_departamento').show();
  $('.tooltip_map').show();
};

// SE DEFINE LA FUNCION PARA ACTUALIZAR EL SELECT CIUDADES AL HACER CLICK EN UN MARKER #########################

function refresh_ciudades(departamentoSelected){

  $.ajax({
      type: "POST",
      url: "process-request-ciudades_busqueda_agencias.php",
      data: { departamentoChoice : departamentoSelected }
  }).done(function(data){
      $("#ciudad_busqueda").prop('disabled', false).html(data);
      $(".select_menu").selectmenu("refresh");
  });

};

// SE DEFINE LA FUNCION QUE TRAE LOS BANNERS DE LAS AGENCIAS DE UN DEPARTAMENTO ################################

function load_agencias_departamento(departamentoSelected){

  $.ajax({
      type: "POST",
      url: "process-request-all-agencias-departamento.php",
      data: { departamentoChoice : departamentoSelected }
  }).done(function(data){
    $(".all_results_container").html(data);
    refresh_contador_agencias();
  });

};

// SE DEFINE LA FUNCION QUE TRAE LOS BANNERS DE LAS AGENCIAS DE UNA CIUDAD ################################

function load_agencias_ciudad(ciudadSelected){

  $.ajax({
      type: "POST",
      url: "process-request-all-agencias-departamento.php",
      data: { ciudadChoice : ciudadSelected }
  }).done(function(data){
      $(".all_results_container").html(data);
      refresh_contador_agencias();
  });

};

// SE DEFINE LA FUNCION QUE TRAE LOS BANNERS DE LAS AGENCIAS DE UNA CIUDAD ################################

function load_agencia_selected(agenciaSelected){

  $.ajax({
      type: "POST",
      url: "process-request-all-agencias-departamento.php",
      data: { agenciaChoice : agenciaSelected }
  }).done(function(data){
      $(".all_results_container").html(data);
      refresh_contador_agencias();
  });

};

// SE DEFINE LA FUNCION QUE ACTUALIZA EL CONTADOR DE AGENCIAS DEL MAPA ######################################

function refresh_contador_agencias(){
  var cantidad_agencias_departamento = $(".result_container").length;
  $(".contador_agencia_total").css("display", "none");
  if (cantidad_agencias_departamento > 1) {
    $(".contador_agencias").html(cantidad_agencias_departamento + " Agencias").css("display", "block");
  }else {
    $(".contador_agencias").html(cantidad_agencias_departamento + " Agencia").css("display", "block");
  };
};

// SE DEFINE LA FUNCION PARA MOSTRAR EL CONTADOR DE AGENCIAS TOTAL DEL PAIS ##################################

function refresh_contador_agencias_total(){
  $(".contador_agencias").css("display", "none");
  $(".contador_agencia_total").css("display", "block");
};


// CODIGO PARA EL CIERRE DEL POPUP AGENCIA ##############################################################

$(".popup_overlay").on("click", ".popup_agencia_cerrar", function(){
  $(".popup_overlay").toggleClass("abierto");
  window.history.back();
});

// CODIGO PARA LA APERTURA DEL POPUP AGENCIA #############################################################

$(".all_results_container").on("click", ".agencia_mas_info", function(){
  var agencia_clicked = $(this).parent().parent().parent().attr('id');

  $.ajax({
      type: "POST",
      url: "process-request-popup-agencia.php",
      data: { agenciaChoice : agencia_clicked }
  }).done(function(data){
      $(".popup_overlay").html(data);

      $('.flexslider.foto_popup_agencia').flexslider(
        {prevText: "",
        nextText: "",  // aca lo dejamos sin completar, asi no aparece el texto, solo las flechitas //
        pauseOnAction: false,  // para que no se pause cuando cliqueamos los puntos de paginacion //
        pauseOnHover: true,
        slideshowSpeed: 5000,  // aca se define el tiempo de transicion en milisegundos //
        animation: "fade",
        animationSpeed: 1000,
        directionNav: false,
        controlNav: false});

      var popup_agencia_lat = $("#popup_agencia_lat").val();
      var popup_agencia_lng = $("#popup_agencia_lng").val();
      var popup_agencia_zoom = $("#popup_agencia_zoom").val();
      var popup_agencia_express = $("#popup_agencia_express").val();
      crear_mapa_popup_agencia(popup_agencia_lat, popup_agencia_lng, popup_agencia_zoom, popup_agencia_express);
      history.pushState('agencia_popup_opened', null, "#"+agencia_clicked);
      $(".popup_overlay").toggleClass("abierto");
  });
});

// CODIGO PARA LA CREACION DEL MAPA DEL POPUP AGENCIA #######################################################

function crear_mapa_popup_agencia(popup_lat, popup_lng, popup_zoom, popup_express){
     L.mapbox.accessToken = 'pk.eyJ1IjoiZnVzaWxvbjk0IiwiYSI6ImNqb204ODF3NjBob2szcWtrcjgwc3lwZjEifQ.y6VWJLe3kPWhEPX8x0YfvQ';

    var popup_map = L.map('mapid_config_popup', {doubleClickZoom: false })
    .addLayer(L.mapbox.styleLayer('mapbox://styles/fusilon94/ckgmmpknk1cxb19pmkrq0duuv', {maxZoom: 18, tileSize: 512, zoomOffset: -1}))
    .setView([popup_lat, popup_lng], popup_zoom);

    var marcador_tutecho_popup = L.icon({ // Se define el tipo de marcador que se colocara
      iconUrl: '../../objetos/marcador_tutecho.svg',

      iconSize:     [45, 102], // size of the icon
      shadowSize:   [50, 64], // size of the shadow
      iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
      shadowAnchor: [4, 62],  // the same for the shadow
      popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
    });

    L.marker([popup_lat, popup_lng], {icon: marcador_tutecho_popup}).addTo(popup_map);
    if (popup_express == 1) {
        L.circle([popup_lat, popup_lng], {
              color: 'rgb(51, 136, 255)',
              fillColor: 'rgb(51, 136, 255)',
              fillOpacity: 0.3,
              radius: 4000
        }).addTo(popup_map);
    };
    
};

// SE DEFINE LA FUNCION PARA ABRIR POPUP EN CASO DE REFRESH PAGE HABIENDO HASH ##############################

function abrir_popup_hash(agencia_clicked){
  $.ajax({
      type: "POST",
      url: "process-request-popup-agencia.php",
      data: { agenciaChoice : agencia_clicked }
  }).done(function(data){
      $(".popup_overlay").html(data);

      $('.flexslider.foto_popup_agencia').flexslider(
        {prevText: "",
        nextText: "",  // aca lo dejamos sin completar, asi no aparece el texto, solo las flechitas //
        pauseOnAction: false,  // para que no se pause cuando cliqueamos los puntos de paginacion //
        pauseOnHover: true,
        slideshowSpeed: 5000,  // aca se define el tiempo de transicion en milisegundos //
        animation: "fade",
        animationSpeed: 1000,
        directionNav: false,
        controlNav: false});
       
      var popup_agencia_lat = $("#popup_agencia_lat").val();
      var popup_agencia_lng = $("#popup_agencia_lng").val();
      var popup_agencia_zoom = $("#popup_agencia_zoom").val();
      var popup_agencia_express = $("#popup_agencia_express").val();
      crear_mapa_popup_agencia(popup_agencia_lat, popup_agencia_lng, popup_agencia_zoom, popup_agencia_express);
      $(".popup_overlay").toggleClass("abierto");
  });
};

// CODIGO QUE MANEJA EL EVENTO BACK/FOWARD BUTTON EN EL NAVEGADOR ##########################################

function checkState(e) {
  if(e.state) {//VERIFICA SI SE HA RETORNADO A UN PUNTO EN EL CUAL SIGUE EXISTIENDO UN STATE
    var hashVal = window.location.hash.substr(1);
    abrir_popup_hash(hashVal);
  }else {// VERIFICA SI SE HA RETORNADO AL INICIO DEL agencias.php FIRST ENTRY,
    if ($(".popup_overlay").hasClass('abierto')) {
      $(".popup_overlay").toggleClass("abierto");
    };

  };
};

window.onpopstate = checkState; // cuando un nuevo state aparece, cuando se oprime el Back/Foward Browser button

// CODIGO PARA CONTROLAR EL EVENTO REFRESH PAGE Y LECTURA DEL HASH ##########################################

if (window.history.state == null) { //SI NO HAY NINGUN STATE DEFINIDO, SIGNIFICA QUE ES LA PRIMERA ENTRADA
  if (window.location.hash) {//SI LA FIRST CHARGE CONTIENE HASH, ENTONCES ES UN LINK COMPARTIDO
    var hashVal = window.location.hash.substr(1);
    history.pushState("popup_shared_link", null, "#"+hashVal);
    abrir_popup_hash(hashVal);
  }else {//FIRST CHARGE VERDADERA
    //NO SE HACE NADA
  };
}else {// HAY UN STATE DEFINIDO, ESTAMOS RECARGANDO UNA PAGINA YA NAVEGADA
  var hashVal = window.location.hash.substr(1);
  abrir_popup_hash(hashVal);
}


$(".popup_overlay").on("click", ".week_btn", function(){
  let current_week_count = parseInt($(".horario_week_count").val());
  let agencia_clicked = window.location.hash.substr(1);

  if ($(this).hasClass("next")) {
    $(".horario_week_count").val(current_week_count+1);
  };

  if ($(this).hasClass("preview")) {
    $(".horario_week_count").val(current_week_count-1);
  };

  let new_week_count = parseInt($(".horario_week_count").val());


  $.ajax({
      type: "POST",
      url: "process-request-popup-agencia-horario-week.php",
      data: { week_count_sent : new_week_count, agenciaChoice :  agencia_clicked},
      dataType: 'json'
    }).done(function(data){
      $('.tabla_horarios').hide().empty().html(data['week']).fadeIn(500);
      $('.aviso_wrap').hide().empty().html(data['avisos']).fadeIn(500);

  });

  
  if (new_week_count > 0) {
    $(".week_btn.preview").css("visibility", "unset");
  }else{
    $(".week_btn.preview").css("visibility", "hidden");
  };
});


// Btn PREVISUALIZAR PDF


$(".cerrar_preview").on("click", function(){
  $(".preview_overlay").css("visibility", "hidden");
});

$(".popup_overlay").on("click", ".btn_tabla_precios", function(){

  let agencia_selected = window.location.hash.substr(1);

  let href_print = "tabla_precios_individual.php#" + agencia_selected + "&" + cookie_pais;

  $(".tabla_print_btn_wrap").prop("href", href_print);

  
  $.ajax({
      type: "POST",
      url: "process-request-agencia-tabla-precios-previsualizacion.php",
      data: { pais_sent : cookie_pais,
              agencia_sent : agencia_selected }
  }).done(function(data){
      
      $(".preview_contenido").html(data);
      
      let count = 0;
      $(".tabla_venta_gris tr").each(function(){
          
          if (count == 0) {
              count += 1;
          } else if (count % 2 == 0){//si es numero
                  
                  count += 1;
          } else {// si es impar
                  $(this).addClass('fondo_gris');
                  count += 1;
          };
          
      });

      count = 0;
      $(".tabla_alquiler_gris tr").each(function(){
          
          if (count == 0) {
              count += 1;
          } else if (count % 2 == 0){//si es numero
                  
                  count += 1;
          } else {// si es impar
                  $(this).addClass('fondo_gris');
                  count += 1;
          };
          
      });

      $(".preview_overlay").css("visibility", "unset");
  });
  
});

// CODIGO PARA LLEVAR EL SCROLL A LOS AGENTES EN CASO DE NO TENER NuMERO DE AGENCIA

$(".popup_overlay").on("click", ".btn_ver_agentes", function(){

  $(".popup_overlay").scrollTop(0);           

  let t = $(".popup_overlay").offset().top;
  
  $(".popup_overlay").scrollTop($(".titulo_agentes").offset().top - t - 100);

});


// Btn PREVISUALIZAR PDF


$(".cerrar_popup_agente").on("click", function(){
  $(".popup_agente_overlay").css("visibility", "hidden");
});


$(".popup_overlay").on("click", ".agente_wrap.disponible", function(){

  const agente_id = $(this).attr("data");

  $.ajax({
    type: "POST",
    url: "process-request-popup-agencia.php",
    data: { agente_id_sent : agente_id }
  }).done(function(data){

    $(".popup_agente_contenido").html(data);
    $(".popup_agente_overlay").css("visibility", "unset");
    
  });

});




// var marker = L.marker([,], {icon: marcador_tutecho}); //LO SIGUIENTE ES PARA TESTEAR POSICION DE MARCADORES
// function onMapClick(e){
//
//   marker.setLatLng(e.latlng).addTo(mymap);
//
//   var current_zoom = mymap.getZoom();
//   $('#mapa_coordenada_lat').val(e.latlng.lat).trigger('change');
//   $('#mapa_coordenada_lng').val(e.latlng.lng).trigger('change');
//   $('#mapa_zoom').val(current_zoom).trigger('change');
// };
//
// mymap.on("contextmenu", onMapClick);//crea un marker al hacer click derecho en PC o click touch largo en mobile



  });
});
