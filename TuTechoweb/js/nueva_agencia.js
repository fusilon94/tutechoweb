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

    let datos_pais;
    $.ajax({
      type: "POST",
      url: "../../contenido/m5/process-request-coordenadas-paises.php",
      dataType: 'json',
      async: false,
    }).done(function(data){
      datos_pais = data;
    });
    
    $(".departamento_label").html(`${datos_pais['org_territorial']} :`);
    

    //CODIGO PARA POBLAR SELECT CIUDADES SEGUN EL DEPARTAMENTO ESCOGIDO ###############################################

            $("select.departamento").change(function(){
                var departamentoSelected = $(".departamento option:selected").val();
                if (departamentoSelected !== '') { //si hubo una seleccion se cargan las ciudades de la db

                    $.ajax({
                        type: "POST",
                        url: "process-request-ciudades.php",
                        data: { departamentoChoice : departamentoSelected }
                    }).done(function(data){
                        $("#ciudad").prop('disabled', false).html(data);// se activa el select ciudades y pobla
                        $("#barrio").empty().prop('disabled', true);//se vacia y bloquea el select barrios si tenia algo
                    });

                    // Se pide las coordenadas y el zoom del departamento elejido
                    $.ajax({
                        type: "POST",
                        url: "process-request-coordenadas_mapa_agencia.php",
                        data: { departamentoChoice : departamentoSelected }
                    }).done(function(data){
                        $(".mapa_coordenadas_container").html(data);
                        refresh_mapa_registro_sponsor();
                    });

                }else { // si se seleciono vacio, entonces se vacian y bloquean los select ciudad y barrio
                  $("#ciudad").empty().prop('disabled', true).val('');
                  $("#barrio").empty().prop('disabled', true).val('');
                };
            });


    //CODIGO PARA POBLAR SELECT BARRIOS SEGUN LA CIUDAD ESCOGIDA  Y MOSTRAR RESULTADOS SPONSORS###############################################

            $("select.ciudad").change(function(){
                var ciudadSelected = $(".ciudad option:selected").val();

                if (ciudadSelected !== '') { // si hubo seleccion se cargan los barrios de la db
                  $.ajax({
                      type: "POST",
                      url: "process-request-barrios.php",
                      data: { ciudadesChoice : ciudadSelected }
                  }).done(function(data){
                      if (data !== '<option></option>') {//si hubo resultados entonces se pobla y activa  el select barrios
                        $("#barrio").prop('disabled', false).html(data);
                      }else { // si no hubo resultados se desactiva y vacia el select barrios
                        $("#barrio").empty().prop('disabled', true);
                      };
                  });

                  $.ajax({
                      type: "POST",
                      url: "process-request-ciudad_poblado_check.php",
                      data: { ciudad_sent : ciudadSelected }
                  }).done(function(data){
                    if (data == 'poblado') {
                      // nothing
                    };
                  });

                  //ahora pedimos las coordenadas y el zoom del departamento elejido para actualizar el mapa
                  $.ajax({
                      type: "POST",
                      url: "process-request-coordenadas_mapa_agencia.php",
                      data: { ciudadesChoice : ciudadSelected }
                  }).done(function(data){
                      $(".mapa_coordenadas_container").html(data);
                      refresh_mapa_registro_sponsor();
                  });

                }else {// si se selecciono vacio, entonces se desactiva y vacia el select barrios y se vacian los resultados sponsor
                  $("#barrio").empty().prop('disabled', true).val('');
                };
            });


//CODIGO PARA MOSTRAR RESULTADOS SPONSOR SEGUN EL BARRIO ESCOGIDO ###############################################

        $("select.barrio").change(function(){
          var barrioSelected = $(".barrio option:selected").val();
          $.ajax({
              type: "POST",
              url: "process-request-coordenadas_mapa_agencia.php",
              data: { barrioChoice : barrioSelected }
          }).done(function(data){
              $(".mapa_coordenadas_container").html(data);
              refresh_mapa_registro_sponsor();
          });
        });

// SE DEFINE LA FUNCION PARA ACTUALIZAR VISTA DEL MAPA SEGUN LOS DATOS INGRESADOS EN DEPARTAMENTO-CIUDAD-BARRIO #######################

function refresh_mapa_registro_sponsor(){
  $("#mapid_config").remove();//se borra el mapa anterior para cargar uno nuevo
  $(".map_wrap").prepend("<div id=\"mapid_config\" style=\"height:100%; width:100%; border: 1px solid rgb(57, 57, 57);\"></div>");

  var lat = $('#mapa_coordenada_lat').val();
  var lng = $('#mapa_coordenada_lng').val();
  var zoom = $('#mapa_zoom').val();

  L.mapbox.accessToken = 'pk.eyJ1IjoiZnVzaWxvbjk0IiwiYSI6ImNqb204ODF3NjBob2szcWtrcjgwc3lwZjEifQ.y6VWJLe3kPWhEPX8x0YfvQ';

  var mymap2 = L.map('mapid_config', {doubleClickZoom: false })
  .addLayer(L.mapbox.styleLayer('mapbox://styles/fusilon94/ckgmmpknk1cxb19pmkrq0duuv', {maxZoom: 18, tileSize: 512, zoomOffset: -1}))
  .setView([lat, lng], zoom);

  var marcador_tutecho = L.icon({
    iconUrl: '../../objetos/marcador_tutecho.svg',

    iconSize:     [45, 102], // size of the icon
    shadowSize:   [50, 64], // size of the shadow
    iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
    shadowAnchor: [4, 62],  // the same for the shadow
    popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
  });

  var marker = L.marker([,], {icon: marcador_tutecho});

  function onMapClick2(e){

    marker.setLatLng(e.latlng)
          .addTo(mymap2);

    var current_zoom = mymap2.getZoom();//se guarda el zoom actual en una variable
    $('#mapa_coordenada_lat').val(e.latlng.lat).trigger('change');
    $('#mapa_coordenada_lng').val(e.latlng.lng).trigger('change');
    $('#mapa_zoom').val(current_zoom).trigger('change');//se guardan las coordenadas y el zoom en inputs para ser enviados con el formulario

  };

  mymap2.on("contextmenu", onMapClick2);
};


//###################CODIGO PARA COLOCAR EL MAPA DEL FORMULARIO EN CARGA INICIAL #########################################

  if ($('#mapa_coordenada_lat').val() !== '') {//cargar mapa de form editar o form borrador

    var lat_borrador = $('#mapa_coordenada_lat').val();
    var lng_borrador = $('#mapa_coordenada_lng').val();
    var zoom_borrador = $('#mapa_zoom').val();

    L.mapbox.accessToken = 'pk.eyJ1IjoiZnVzaWxvbjk0IiwiYSI6ImNqb204ODF3NjBob2szcWtrcjgwc3lwZjEifQ.y6VWJLe3kPWhEPX8x0YfvQ';

    var mymap = L.map('mapid_config', {doubleClickZoom: false })
    .addLayer(L.mapbox.styleLayer('mapbox://styles/fusilon94/ckgmmpknk1cxb19pmkrq0duuv', {maxZoom: 18, tileSize: 512, zoomOffset: -1}))
    .setView([lat_borrador, lng_borrador], zoom_borrador);

  } else {//cargar mapa inicial, crear nuevo sponsor
    L.mapbox.accessToken = 'pk.eyJ1IjoiZnVzaWxvbjk0IiwiYSI6ImNqb204ODF3NjBob2szcWtrcjgwc3lwZjEifQ.y6VWJLe3kPWhEPX8x0YfvQ';

    var mymap = L.map('mapid_config', {doubleClickZoom: false })
    .addLayer(L.mapbox.styleLayer('mapbox://styles/fusilon94/ckgmmpknk1cxb19pmkrq0duuv', {maxZoom: 18, tileSize: 512, zoomOffset: -1}))
    .setView([datos_pais['lat'], datos_pais['lng']], datos_pais['zoom']);
  }

  var marcador_tutecho = L.icon({
    iconUrl: '../../objetos/marcador_tutecho.svg',

    iconSize:     [45, 102], // size of the icon
    shadowSize:   [50, 64], // size of the shadow
    iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
    shadowAnchor: [4, 62],  // the same for the shadow
    popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
  });

  if ($('#mapa_coordenada_lat').val() !== '') {
    var lat_borrador = $('#mapa_coordenada_lat').val();
    var lng_borrador = $('#mapa_coordenada_lng').val();
    var zoom_borrador = $('#mapa_zoom').val();

    var marker = L.marker([lat_borrador, lng_borrador], {icon: marcador_tutecho}).addTo(mymap);
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

mymap.on("contextmenu", onMapClick);//crea un marker al hacer click derecho en PC o click touch largo en mobile

// CODIGO VERIFICACION REGEX #########################################################

  $("#direccion").on('input', function(){
    if ($(this).val().match(/^[\w\d\s+\-&.,#:\/áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
    };
  });

  $("#direccion_complemento").on('input', function(){
    if ($(this).val().match(/^[\w\d\s+\-&.,#:\/áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
    };
  });

  $("#telefono").on('input', function(){
    if ($(this).val().match(/^[+\-0-9().# \/]+$/g) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
    };
  });

  $("#nit").on('input', function(){
    if ($(this).val().match(/^[\w\d\s+\-áÁéÉíÍóÓúÚñÑ&#\/,.\']+$/) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
    };
  });

// CODIGO BOTON CREAR AGENCIA ########################################################

  $(".boton_crear_agencia").on('click', function(){
    var departamento = $(".departamento option:selected").val();
    var ciudad = $(".ciudad option:selected").val();
    var direccion = $("#direccion").val();
    var complemento = $("#direccion_complemento").val();
    var telefono = $("#telefono").val();
    var nit = $("#nit").val();
    var lat = $("#mapa_coordenada_lat").val();
    var lng = $("#mapa_coordenada_lng").val();
    var zoom = $("#mapa_zoom").val();
    var foto = $("#foto").val();
    var foto2 = $("#foto2").val();
    var modo = $("#modo").val();

    if (departamento == '' || ciudad == '' || direccion == '' || complemento == '' || telefono == '' || nit == '' || lat == '' || lng == '' || zoom == '' || (modo == '' && foto == '' && foto2 == '')) {
      $(".popup_success_text").html('Todos los campos deben llenarse');
      $(".popup_success").css('visibility', 'unset');
    }else {
      if (direccion.match(/^[\w\d\s+\-&.,#:\/áÁéÉíÍóÓúÚñÑ\']+$/) == null || complemento.match(/^[\w\d\s+\-&.,#:\/áÁéÉíÍóÓúÚñÑ\']+$/) == null || telefono.match(/^[+\-0-9().# \/]+$/g) == null || nit.match(/^[\w\d\s+\-áÁéÉíÍóÓúÚñÑ&#\/,.\']+$/) == null) {
        $(".popup_success_text").html('Caracteres no permitidos en algun campo');
        $(".popup_success").css('visibility', 'unset');
      }else {
        $("#nueva_agencia_form").submit();
      };

    };

  });

// ##########################################################################################
// ###############################DRAG AND DROP FEATURES ####################################
// ##########################################################################################

  $(".campo_foto").on('dragenter', function (e){  // lo que pasa cuando drag por encima, y cuando te vas
      $(this).css('border', '3px dashed #007fff');
  });

  $(".campo_foto").on('dragover', function (e){
      e.preventDefault();
      e.stopPropagation();
      $(this).css('border', '1px solid #007fff');
      return false;
  });

  $(".campo_foto").on('dragleave', function(e) {
      e.preventDefault();
      e.stopPropagation();
      $(this).css('border', '3px dashed gray');
      $(this).css('background', 'white');
  });

  $.uploadPreview({
      input_field: "#foto",   // Default: .image-upload
      preview_box: "#campo_foto",  // Default: .image-preview
      label_field: "#foto_label",    // Default: .image-label
      label_default: "<p>Sube una foto de la Agencia<br><span>Click or Drop</span></p>",   // Default: Choose File
      label_selected: "Cambia esta imagen",  // Default: Change File
      no_label: false                 // Default: false
  });

  $.uploadPreview({
    input_field: "#foto2",   // Default: .image-upload
    preview_box: "#campo_foto2",  // Default: .image-preview
    label_field: "#foto_label2",    // Default: .image-label
    label_default: "<p>Sube una foto de la Agencia<br><span>Click or Drop</span></p>",   // Default: Choose File
    label_selected: "Cambia esta imagen",  // Default: Change File
    no_label: false                 // Default: false
});


// CODIGO POPUP SUCCESS ####################################################################################

  $('.popup_success_cerrar i.fa-times').on("click", function(){
    $('.popup_success').css('visibility',  'hidden');
  });

  });
});
