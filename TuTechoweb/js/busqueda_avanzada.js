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
    url: "../../contenido/m5/process-request-coordenadas-paises.php",
    dataType: 'json',
    async: false,
  }).done(function(data){
    datos_pais = data;
  });

// #################### CODIGO SELECT-MENU Y CHECKBOKRADIO ###############################################################################

    $(".select_menu").selectmenu();

    $( "input.check_box_radio" ).checkboxradio();

// CODIGO SLIDER RANGE PARA PRECIO ###################################

    $('.range_price').each(function(){
      var cls = $(this).attr('class');
      var matches = cls.split(/([a-zA-Z]+)\-([0-9]+)/g); // valor spliteado de un texto+numero, aca para el min-## y max-##
      var elem = $(this).parent(); // el div padre
      var options = {}; // todas los valores de opciones definidos en adelante
      var input = elem.find('input'); // el input dentro del div padre
      elem.append('<div class="uirange"></div>'); // se crea un div.uirange dentro de ese padre, sera el slider!!

   //OPTIONS
      for(i in matches){  //funcion que permite usar el numero delante de min o max como valor option
          i = i*1; // para que i sea visto como un numero
          if(matches[i] == 'min'){
            options.min = matches[i+1]*1; // para escojer el valor enfrente de i, que tambien sera visto como un numero
          }
          if(matches[i] == 'max'){
            options.max = matches[i+1]*1; // para escojer el valor enfrente de i, que tambien sera visto como un numero
          }
      }

      options.slide = function(event, ui) { // variables del JQuery-ui relativas al slider

          elem.find('label span[class="precio_val"]').empty().append((Math.round(ui.value)).toLocaleString('fr-FR')); //introducir dentro del span el valor del slider
          elem.find('label span[class="millon"]').empty().append(moneda);

          input.val(ui.value); // intrducir dentro del input el valor del slider

          if (ui.value > (Math.round(2000000*cambio)) && ui.value < (Math.round(2300000*cambio)) && ui.value != (Math.round(2299999*cambio))) {
            // force it to 0 between -1 and 1.
            elem.find('.uirange').slider('value', (Math.round(100000000*cambio)));
            elem.find('label span[class="precio_val"]').empty().append(` > ${(Math.round(2000000*cambio)).toLocaleString('fr-FR')} ${moneda}`);
            elem.find('label span[class="millon"]').empty();
            return false;
        };
          return true;
      };
      options.value = input.val();
      options.range = 'min';
      options.step = (Math.round(5000*cambio));

   //fin-OPTIONS

      elem.find('.uirange').slider(options); // el slider dentro del nuevo div tendra los diferentes parametros options

      elem.find('label span[class=\'precio_val\']').empty().append(input.val());// poner el valor predefinido inicial del input dentro del span

      const price = parseInt(elem.find('label span[class=\'precio_val\']').text()).toLocaleString('fr-FR');
      elem.find('label span[class=\'precio_val\']').text(price);

      input.hide();
    });

// CODIGO SLIDER RANGE PARA PRECIO DE BIENES EN ALQUILER ##########################

  $('.range_price_renta').each(function(){
    var cls = $(this).attr('class');
    var matches = cls.split(/([a-zA-Z]+)\-([0-9]+)/g); // valor spliteado de un texto+numero, aca para el min-## y max-##
    var elem = $(this).parent(); // el div padre
    var options = {}; // todas los valores de opciones definidos en adelante
    var input = elem.find('input'); // el input dentro del div padre
    elem.append('<div class="uirange"></div>'); // se crea un div.uirange dentro de ese padre, sera el slider!!

  //OPTIONS
     for(i in matches){  //funcion que permite usar el numero delante de min o max como valor option
         i = i*1; // para que i sea visto como un numero
         if(matches[i] == 'min'){
           options.min = matches[i+1]*1; // para escojer el valor enfrente de i, que tambien sera visto como un numero
         }
         if(matches[i] == 'max'){
           options.max = matches[i+1]*1; // para escojer el valor enfrente de i, que tambien sera visto como un numero
         }
     }

    options.slide = function(event, ui) { // variables del JQuery-ui relativas al slider
      if (ui.value >= (Math.round(350*cambio)) && ui.value < (Math.round(2000*cambio))) { //no 1000 ya que da un error, al pasar a 1001 muestra 1.00 millones
        elem.find('label span[class="precio_val"]').empty().append((Math.round(ui.value)).toLocaleString('fr-FR')); //introducir dentro del span el valor del slider
      };

        input.val(ui.value); // intrducir dentro del input el valor del slider

        if (ui.value >= (Math.round(2000*cambio)) && ui.value < (Math.round(2300*cambio)) && ui.value != (Math.round(2280*cambio))) {
          // force it to 0 between -1 and 1.
          elem.find('.uirange').slider('value', (Math.round(10000000*cambio)));
          elem.find('label span[class="precio_val"]').empty().append(` > ${(Math.round(2000*cambio)).toLocaleString('fr-FR')}`);
          return false;
      };
        return true;
    };
    options.value = input.val();
    options.range = 'min';
    options.step = (Math.round(50*cambio));

    elem.find('.uirange').slider(options); // el slider dentro del nuevo div tendra los diferentes parametros options

    elem.find('label span[class=\'precio_val\']').empty().append(input.val());// poner el valor predefinido inicial del input dentro del span

    const price = parseInt(elem.find('label span[class=\'precio_val\']').text()).toLocaleString('fr-FR');
    elem.find('label span[class=\'precio_val\']').text(`${price}`);

    input.hide();

  });

// CODIGO SLIDER RANGE PARA SUPERFICIE###################################

    $('.range').each(function(){
      var cls = $(this).attr('class');
      var matches = cls.split(/([a-zA-Z]+)\-([0-9]+)/g); // valor spliteado de un texto+numero, aca para el min-## y max-##
      var elem = $(this).parent(); // el div padre
      var options = {}; // todas los valores de opciones definidos en adelante
      var input = elem.find('input'); // el input dentro del div padre
      elem.append('<div class="uirange"></div>'); // se crea un div.uirange dentro de ese padre, sera el slider!!

   //OPTIONS
      for(i in matches){  //funcion que permite usar el numero delante de min o max como valor option
          i = i*1; // para que i sea visto como un numero
          if(matches[i] == 'min'){
            options.min = matches[i+1]*1; // para escojer el valor enfrente de i, que tambien sera visto como un numero
          }
          if(matches[i] == 'max'){
            options.max = matches[i+1]*1; // para escojer el valor enfrente de i, que tambien sera visto como un numero
          }
      }

      options.slide = function(event, ui) { // variables del JQuery-ui relativas al slider
          elem.find('label span').empty().append(ui.value); //introducir dentro del span el valor del slider
          input.val(ui.value); // intrducir dentro del input el valor del slider
      };
      options.value = input.val();
      options.range = 'min';

   //fin-OPTIONS

      elem.find('.uirange').slider(options); // el slider dentro del nuevo div tendra los diferentes parametros options

      elem.find('label span').empty().append(input.val());// poner el valor predefinido inicial del input dentro del span
      
      const superf = parseInt(elem.find('label span').text()).toLocaleString('fr-FR');
      elem.find('label span').text(`${superf}`);

      input.hide();
    });

// CODIGO SLIDER RANGE PARA SUPERFICIE TERRENOS ###################################

    $('.range_sup_terreno').each(function(){
      var cls = $(this).attr('class');
      var matches = cls.split(/([a-zA-Z]+)\-([0-9]+)/g); // valor spliteado de un texto+numero, aca para el min-## y max-##
      var elem = $(this).parent(); // el div padre
      var options = {}; // todas los valores de opciones definidos en adelante
      var input = elem.find('input[type="text"]'); // el input dentro del div padre
      elem.append('<div id="slider_sup_terreno" class="uirange"></div>'); // se crea un div.uirange dentro de ese padre, sera el slider!!

   //OPTIONS
      for(i in matches){  //funcion que permite usar el numero delante de min o max como valor option
          i = i*1; // para que i sea visto como un numero
          if(matches[i] == 'min'){
            options.min = matches[i+1]*1; // para escojer el valor enfrente de i, que tambien sera visto como un numero
          }
          if(matches[i] == 'max'){
            options.max = matches[i+1]*1; // para escojer el valor enfrente de i, que tambien sera visto como un numero
          }
      }

      options.slide = function(event, ui) { // variables del JQuery-ui relativas al slider
        if (ui.value > 5000 && ui.value < 300000) { //no 1000 ya que da un error, al pasar a 1001 muestra 1.00 millones
          elem.find('label span[class="sup_terreno_val"]').empty().append((ui.value/10000).toFixed(1)); //introducir dentro del span el valor del slider
          elem.find('label span[class="hect"]').empty().append("ha");
        };
        if (ui.value <= 5000) {
          elem.find('label span[class="sup_terreno_val"]').empty().append((ui.value).toLocaleString('fr-FR')); //introducir dentro del span el valor del slider
          elem.find('label span[class="hect"]').empty().html("m&sup2");
        };

          input.val(ui.value); // intrducir dentro del input el valor del slider

          if (ui.value > 300000 && ui.value < 350000 && ui.value != 330000) {
              // force it to 0 between -1 and 1.
              elem.find('.uirange').slider('value', 330000);
              elem.find('label span[class="sup_terreno_val"]').empty().append(" > 30 ha");
              elem.find('label span[class="hect"]').empty();
              return false;
          };
          return true;
      };
      options.value = input.val();
      options.range = 'min';

   //fin-OPTIONS

      elem.find('.uirange').slider(options); // el slider dentro del nuevo div tendra los diferentes parametros options

      elem.find('label span[class="sup_terreno_val"]').empty().append(input.val());// poner el valor predefinido inicial del input dentro del span
      
      const superf = parseInt((elem.find('label span[class="sup_terreno_val"]')).text()).toLocaleString('fr-FR');
      elem.find('label span[class="sup_terreno_val"]').text(`${superf}`);

      input.hide();
    });

// CUANDO SE HACE CLICK EN m2 DEL MENU BUSQUEDA TERRENO ###################

    $('#opcion_terreno_m2').on('click', function(){
        if ($("#opcion_terreno_m2").hasClass('opcion_terreno_inactive')) {
          $("#opcion_terreno_m2").removeClass('opcion_terreno_inactive');
          $("#opcion_terreno_m2").addClass('opcion_terreno_active');
          $("#opcion_terreno_hect").removeClass('opcion_terreno_active');
          $("#opcion_terreno_hect").addClass('opcion_terreno_inactive');

          $('#superficie_busqueda_terreno').removeClass('min-5000');
          $('#superficie_busqueda_terreno').removeClass('max-350000');
          $('#superficie_busqueda_terreno').addClass('min-20');
          $('#superficie_busqueda_terreno').addClass('max-5000');

          $('#superficie_busqueda_terreno').val('500');

          // CODIGO PARA RECREAR EL slider sup terrenos
          $('.range_sup_terreno').each(function(){
            var cls = $(this).attr('class');
            var matches = cls.split(/([a-zA-Z]+)\-([0-9]+)/g); // valor spliteado de un texto+numero, aca para el min-## y max-##
            var elem = $(this).parent(); // el div padre
            var options = {}; // todas los valores de opciones definidos en adelante
            var input = elem.find('input[type="text"]'); // el input dentro del div padre
            $("#slider_sup_terreno").remove();
            elem.append('<div id="slider_sup_terreno" class="uirange"></div>'); // se crea un div.uirange dentro de ese padre, sera el slider!!
            elem.find('label span[class="hect"]').empty().html("m&sup2");

          //OPTIONS
            for(i in matches){  //funcion que permite usar el numero delante de min o max como valor option
                i = i*1; // para que i sea visto como un numero
                if(matches[i] == 'min'){
                  options.min = matches[i+1]*1; // para escojer el valor enfrente de i, que tambien sera visto como un numero
                }
                if(matches[i] == 'max'){
                  options.max = matches[i+1]*1; // para escojer el valor enfrente de i, que tambien sera visto como un numero
                }
            }

            options.slide = function(event, ui) { // variables del JQuery-ui relativas al slider
              if (ui.value > 5000 && ui.value < 300000) { //no 1000 ya que da un error, al pasar a 1001 muestra 1.00 millones
                elem.find('label span[class="sup_terreno_val"]').empty().append((ui.value/10000).toFixed(1)); //introducir dentro del span el valor del slider
                elem.find('label span[class="hect"]').empty().append("ha");
              };
              if (ui.value <= 5000) {
                elem.find('label span[class="sup_terreno_val"]').empty().append((ui.value).toLocaleString('fr-FR')); //introducir dentro del span el valor del slider
                elem.find('label span[class="hect"]').empty().html("m&sup2");
              };

                input.val(ui.value); // intrducir dentro del input el valor del slider

                if (ui.value > 300000 && ui.value < 330000 && ui.value != 325000) {
                    // force it to 0 between -1 and 1.
                    elem.find('.uirange').slider('value', 325000);
                    elem.find('label span[class="sup_terreno_val"]').empty().append(" > 30 ha");
                    elem.find('label span[class="hect"]').empty();
                    return false;
                };
                return true;
            };
            options.value = 500;
            options.range = 'min';

          //fin-OPTIONS

            elem.find('.uirange').slider(options); // el slider dentro del nuevo div tendra los diferentes parametros options

            elem.find('label span[class="sup_terreno_val"]').empty().append(500);// poner el valor predefinido inicial del input dentro del span

            input.hide();
          });

          // fin codigo recrear slider sup terreno

        };
    });

// CUANDO SE HACE CLICK EN HECT DEL MENU BUSQUEDA TERRENO #################

      $('#opcion_terreno_hect').on('click', function(event){
          if ($("#opcion_terreno_hect").hasClass('opcion_terreno_inactive')) {

            $("#opcion_terreno_hect").removeClass('opcion_terreno_inactive');
            $("#opcion_terreno_hect").addClass('opcion_terreno_active');
            $("#opcion_terreno_m2").removeClass('opcion_terreno_active');
            $("#opcion_terreno_m2").addClass('opcion_terreno_inactive');

            $('#superficie_busqueda_terreno').removeClass('min-20');
            $('#superficie_busqueda_terreno').removeClass('max-5000');
            $('#superficie_busqueda_terreno').addClass('min-5001');
            $('#superficie_busqueda_terreno').addClass('max-330000');

            $('#superficie_busqueda_terreno').val('10000');

          // CODIGO PARA RECREAR EL slider sup terrenos
          $('.range_sup_terreno').each(function(){
            var cls = $(this).attr('class');
            var matches = cls.split(/([a-zA-Z]+)\-([0-9]+)/g); // valor spliteado de un texto+numero, aca para el min-## y max-##
            var elem = $(this).parent(); // el div padre
            var options = {}; // todas los valores de opciones definidos en adelante
            var input = elem.find('input[type="text"]'); // el input dentro del div padre
            $("#slider_sup_terreno").remove();
            elem.append('<div id="slider_sup_terreno" class="uirange"></div>'); // se crea un div.uirange dentro de ese padre, sera el slider!!
            elem.find('label span[class="hect"]').empty().append("ha");

         //OPTIONS
            for(i in matches){  //funcion que permite usar el numero delante de min o max como valor option
                i = i*1; // para que i sea visto como un numero
                if(matches[i] == 'min'){
                  options.min = matches[i+1]*1; // para escojer el valor enfrente de i, que tambien sera visto como un numero
                }
                if(matches[i] == 'max'){
                  options.max = matches[i+1]*1; // para escojer el valor enfrente de i, que tambien sera visto como un numero
                }
            }

            options.slide = function(event, ui) { // variables del JQuery-ui relativas al slider
              if (ui.value > 5000 && ui.value < 300000) { //no 1000 ya que da un error, al pasar a 1001 muestra 1.00 millones
                elem.find('label span[class="sup_terreno_val"]').empty().append((ui.value/10000).toFixed(1)); //introducir dentro del span el valor del slider
                elem.find('label span[class="hect"]').empty().append("ha");
              };
              if (ui.value <= 5000) {
                elem.find('label span[class="sup_terreno_val"]').empty().append(ui.value); //introducir dentro del span el valor del slider
                elem.find('label span[class="hect"]').empty().html("m&sup2");
              };

                input.val(ui.value); // intrducir dentro del input el valor del slider

                if (ui.value > 300000 && ui.value < 330000 && ui.value != 325000) {
                    // force it to 0 between -1 and 1.
                    elem.find('.uirange').slider('value', 325000);
                    elem.find('label span[class="sup_terreno_val"]').empty().append(" > 30 ha");
                    elem.find('label span[class="hect"]').empty();
                    return false;
                };
                return true;
            };
            options.value = 10000;
            options.range = 'min';

         //fin-OPTIONS

            elem.find('.uirange').slider(options); // el slider dentro del nuevo div tendra los diferentes parametros options

            elem.find('label span[class="sup_terreno_val"]').empty().append(1);// poner el valor predefinido inicial del input dentro del span

            input.hide();
          });

          // fin codigo recrear slider sup terreno
          };
      });

//###################CODIGO PARA COLOCAR EL MAPA DEL FORMULARIO EN CARGA INICIAL #########################################
  L.mapbox.accessToken = 'pk.eyJ1IjoiZnVzaWxvbjk0IiwiYSI6ImNqb204ODF3NjBob2szcWtrcjgwc3lwZjEifQ.y6VWJLe3kPWhEPX8x0YfvQ';

  var mymap = L.map('mapid_config', {doubleClickZoom: false })
  .addLayer(L.mapbox.styleLayer('mapbox://styles/fusilon94/ckgmmpknk1cxb19pmkrq0duuv', {maxZoom: 21, tileSize: 512, zoomOffset: -1}))
  .setView([datos_pais['lat'], datos_pais['lng']], datos_pais['zoom']);


  var oms = new OverlappingMarkerSpiderfier(mymap, {"keepSpiderfied" : true, "nearbyDistance" : 40});//Se define la layer de tipo MarkerSpiderfier, se pondra sobre todos los markers que se le agregen posteriormente (se agregan al oms en su creacion, asi como sus eventlisteners)

  var marcador_tutecho = L.icon({ // Se define el tipo de marcador que se colocara
    iconUrl: '../../objetos/marcador_tutecho.svg',

    iconSize:     [45, 102], // size of the icon
    shadowSize:   [50, 64], // size of the shadow
    iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
    shadowAnchor: [4, 62],  // the same for the shadow
    popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
  });

  var marcador_tutecho_exclusivo = L.icon({ // Se define el tipo de marcador que se colocara
    iconUrl: '../../objetos/marcador_tutecho_red.svg',

    iconSize:     [45, 102], // size of the icon
    shadowSize:   [50, 64], // size of the shadow
    iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
    shadowAnchor: [4, 62],  // the same for the shadow
    popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
  });

// SE DEFINEN LAS VARIABLES CLAVE EN RECOLECCION DE DATOS ############################################################

  var markers_list = {};// Se define la lista de marcadores departamentales
  var resultados = {}; //Se define la lista de resultados, bienes inmuebles
  var bienes_inmuebles;

// SE DEFINE LA FUNCION RESPUESTA AL CLICK EN UN MARKER DEPARTAMENTAL ################################################

  function onMarkerDepartamentoClick(e){// se define la funcion que se llamará al hacer click en un marker
    var departamentoSelected = this.options.marker_id;
    // ahora pedimos las coordenadas y el zoom del pais
    $.ajax({//Se trae el json file con las datos para el trazado del polygono
    dataType: "json",
    url: url_country_file + "views_coordinates.json",// REGLA: SOLO USAR ZOOMS 8 y 9 AL DEFINIR ESTOS FILES
    success: function(data) {
        refresh_view(data[departamentoSelected]['lat'], data[departamentoSelected]['lng'], data[departamentoSelected]['zoom']);
        hide_departamentos_limits();
        hide_departamentos_markers();
        show_inmuebles();
        // load_agencias_departamento(departamentoSelected);
    }
  }).error(function() {});

  };

//Se traer las coordenadas de los markers de Departamentos del Pais en carga inicial ################################

  $.ajax({
  dataType: "json",
  url: url_country_file + "markers.json",
  success: function(data) {

      $.when(refresh_markers_inmuebles("first_call")).done(function(){

        $.each(data, function( depa, coordenadas ) {
            markers_list[depa] = {coordenadas:coordenadas};//Se añade a la lista de marcadores aquellos de Departamentos con agencias, coordenadas y cantidad de agencias
        });

        $.each(markers_list, function( index, value ) {//Se añaden al mapa los marcadores de Departamentos con agencias

          if (typeof bienes_inmuebles[index] === 'undefined') {
            //no se hace nada, no hay resultados para ese departamento
          } else {

            let count_bienes_inmuebles = bienes_inmuebles[index].length.toString();

            L.marker([value['coordenadas']['lat'], value['coordenadas']['lng']], {icon: marcador_tutecho, marker_id: index}).addTo(mymap).bindPopup(index, {closeButton: false, className: "popup_departamento"}).on('mouseover', function (e) {this.openPopup();}).on('mouseout', function (e) {this.closePopup();}).on('click', onMarkerDepartamentoClick).bindTooltip(count_bienes_inmuebles, {permanent: true, opacity: 0.9, className: 'tooltip_departamento', direction: 'center', offset: [2.8,64]})._icon.classList.add("marcador_departamento");

          };


        });

      });

  }
  }).error(function() {});


// SE DEFINE LA FUNCION DEL REFRESH MARCADORES INMUEBLES ################################################################

function refresh_markers_inmuebles(call_type){
  let tipo_bien = $("#tipo_bien option:selected").val();
  let estado_bien = $(".box_radio_choices label.box_active").attr("name");
  let precio_max;
  let superficie_min;
  let conditions = ["default"];
  let parameters = ["default"];

  resultados.length = 0;

  if (estado_bien == "En Venta") {
    if ($("#precio_busqueda_venta").val() >= (Math.round(2000000*cambio))) {
      precio_max = 100000000;
    }else {
      precio_max = $("#precio_busqueda_venta").val();
    };
  };
  if (estado_bien == "En Alquiler") {
    if ($("#precio_busqueda_renta").val() >= (Math.round(2000*cambio))) {
      precio_max = 100000000;
    }else {
      precio_max = $("#precio_busqueda_renta").val();
    };
  };

  if (tipo_bien == "casa" || tipo_bien == "departamento" || tipo_bien == "local") {
    superficie_min = $("#superficie_busqueda").val();
  };
  if (tipo_bien == "terreno") {
    superficie_min = $("#superficie_busqueda_terreno").val();
  };


  if (call_type == "later_call") {
    reset_markers_inmueble();
  };


  $.ajax({
    type: "POST",
    async: false,
    url: "process-request-busqueda_avanzada_inmuebles.php",
    data: { tipo_bien_Choice : tipo_bien, estado_bien_Choice : estado_bien, precio_max_Choice : precio_max, superficie_min_Choice : superficie_min, conditions_sent : conditions, parameters_sent : parameters },
    success: function(data){
      bienes_inmuebles = jQuery.parseJSON(data);

      armado_markers(bienes_inmuebles, call_type);
    }
  })

};


// FUNCION PARA EL ARMADO (SOLAMENTE) DE MARKERS SEGUN DATOS DE BUSQUEDA NORMAL O AVANZADA #####################################################


function armado_markers(bienes_inmuebles, call_type){

  oms.clearMarkers(); //se borran todos los markers asociados al OverlapMarkerSpiderfy para no ocasionar falsos clusters con antiguos markers

  $.each(bienes_inmuebles, function( departamento, inmuebles ) {//de cada departamento se ve su array de resultados

    $.each(inmuebles, function(index, info_inmueble) {//de cada resultado del array se busca colocar un puntero en el mapa
      if (info_inmueble['estado'] == 'En Venta') {
        if (info_inmueble['exclusivo'] == 1) {
            if (info_inmueble['pre_venta'] == 1) {// MARKER: red + PV

              var popup_string =  popup_marker_inmueble(info_inmueble);

              var marker_individual = new L.marker([info_inmueble['mapa_coordenada_lat'], info_inmueble['mapa_coordenada_lng']], {icon: marcador_tutecho_exclusivo, marker_id: info_inmueble['referencia']});

              marker_individual.addTo(mymap).bindPopup(popup_string, {closeButton: false, className: "popup_inmueble"}).on('mouseover', function (e) {this.openPopup();}).on('mouseout', function (e) {this.closePopup();}).bindTooltip("PV", {permanent: true, opacity: 0.9, className: 'tooltip_inmueble', direction: 'center', offset: [2.8,64]})._icon.classList.add("marcador_inmueble");


            }else {// MARKER: only red

              var popup_string =  popup_marker_inmueble(info_inmueble);

              var marker_individual = new L.marker([info_inmueble['mapa_coordenada_lat'], info_inmueble['mapa_coordenada_lng']], {icon: marcador_tutecho_exclusivo, marker_id: info_inmueble['referencia']});

              marker_individual.addTo(mymap).bindPopup(popup_string, {closeButton: false, className: "popup_inmueble"}).on('mouseover', function (e) {this.openPopup();}).on('mouseout', function (e) {this.closePopup();})._icon.classList.add("marcador_inmueble");

            };
        }else {
            if (info_inmueble['pre_venta'] == 1) {// MARKER: blue + PV

              var popup_string =  popup_marker_inmueble(info_inmueble);

              var marker_individual = new L.marker([info_inmueble['mapa_coordenada_lat'], info_inmueble['mapa_coordenada_lng']], {icon: marcador_tutecho, marker_id: info_inmueble['referencia']});

              marker_individual.addTo(mymap).bindPopup(popup_string, {closeButton: false, className: "popup_inmueble"}).on('mouseover', function (e) {this.openPopup();}).on('mouseout', function (e) {this.closePopup();}).bindTooltip("PV", {permanent: true, opacity: 0.9, className: 'tooltip_inmueble', direction: 'center', offset: [2.8,64]})._icon.classList.add("marcador_inmueble");

            }else {// MARKER: only blue

              var popup_string =  popup_marker_inmueble(info_inmueble);

              var marker_individual = new L.marker([info_inmueble['mapa_coordenada_lat'], info_inmueble['mapa_coordenada_lng']], {icon: marcador_tutecho, marker_id: info_inmueble['referencia']});

              marker_individual.addTo(mymap).bindPopup(popup_string, {closeButton: false, className: "popup_inmueble"}).on('mouseover', function (e) {this.openPopup();}).on('mouseout', function (e) {this.closePopup();})._icon.classList.add("marcador_inmueble");

            };
        };
      };

      if (info_inmueble['estado'] == 'En Alquiler') {
        if (info_inmueble['exclusivo'] == 1) {
            if (info_inmueble['anticretico'] == 1) {// MARKER: red + A

              var popup_string =  popup_marker_inmueble(info_inmueble);
              var marker_individual = new L.marker([info_inmueble['mapa_coordenada_lat'], info_inmueble['mapa_coordenada_lng']], {icon: marcador_tutecho_exclusivo, marker_id: info_inmueble['referencia']});
              marker_individual.addTo(mymap).bindPopup(popup_string, {closeButton: false, className: "popup_inmueble"}).on('mouseover', function (e) {this.openPopup();}).on('mouseout', function (e) {this.closePopup();}).bindTooltip("A", {permanent: true, opacity: 0.9, className: 'tooltip_inmueble', direction: 'center', offset: [2.8,64]})._icon.classList.add("marcador_inmueble");

            }else {// MARKER: only red

              var popup_string =  popup_marker_inmueble(info_inmueble);
              var marker_individual = new L.marker([info_inmueble['mapa_coordenada_lat'], info_inmueble['mapa_coordenada_lng']], {icon: marcador_tutecho_exclusivo, marker_id: info_inmueble['referencia']});
              marker_individual.addTo(mymap).bindPopup(popup_string, {closeButton: false, className: "popup_inmueble"}).on('mouseover', function (e) {this.openPopup();}).on('mouseout', function (e) {this.closePopup();})._icon.classList.add("marcador_inmueble");

            };
        }else {
            if (info_inmueble['anticretico'] == 1) {// MARKER: blue + A

              var popup_string =  popup_marker_inmueble(info_inmueble);
              var marker_individual = new L.marker([info_inmueble['mapa_coordenada_lat'], info_inmueble['mapa_coordenada_lng']], {icon: marcador_tutecho, marker_id: info_inmueble['referencia']});
              marker_individual.addTo(mymap).bindPopup(popup_string, {closeButton: false, className: "popup_inmueble"}).on('mouseover', function (e) {this.openPopup();}).on('mouseout', function (e) {this.closePopup();}).bindTooltip("A", {permanent: true, opacity: 0.9, className: 'tooltip_inmueble', direction: 'center', offset: [2.8,64]})._icon.classList.add("marcador_inmueble");

            }else {// MARKER: only blue

              var popup_string =  popup_marker_inmueble(info_inmueble);
              var marker_individual = new L.marker([info_inmueble['mapa_coordenada_lat'], info_inmueble['mapa_coordenada_lng']], {icon: marcador_tutecho, marker_id: info_inmueble['referencia']});
              marker_individual.addTo(mymap).bindPopup(popup_string, {closeButton: false, className: "popup_inmueble"}).on('mouseover', function (e) {this.openPopup();}).on('mouseout', function (e) {this.closePopup();})._icon.classList.add("marcador_inmueble");

            };
        };
      };

      oms.addMarker(marker_individual);
      resultados[info_inmueble['referencia']] = info_inmueble;

    });

  });

  if (call_type == "first_call" || mymap.getZoom() < 8) {
    hide_inmuebles()
  };
  if (call_type == "later_call") {

    reset_markers_departamento();
    $.each(markers_list, function( index, value ) {//Se añaden al mapa los marcadores de Departamentos con agencias

      if (typeof bienes_inmuebles[index] === 'undefined') {
        //no se hace nada, no hay resultados para ese departamento
      } else {

        let count_bienes_inmuebles = bienes_inmuebles[index].length.toString();

        L.marker([value['coordenadas']['lat'], value['coordenadas']['lng']], {icon: marcador_tutecho, marker_id: index}).addTo(mymap).bindPopup(index, {closeButton: false, className: "popup_departamento"}).on('mouseover', function (e) {this.openPopup();}).on('mouseout', function (e) {this.closePopup();}).on('click', onMarkerDepartamentoClick).bindTooltip(count_bienes_inmuebles, {permanent: true, opacity: 0.9, className: 'tooltip_departamento', direction: 'center', offset: [2.8,64]})._icon.classList.add("marcador_departamento");

      };
    });
    if (mymap.getZoom() > 8) {
      hide_departamentos_markers();
    };
  };

  oms.clearListeners('click'); //borra anteriores events listener para no tener repeticiones
  oms.addListener('click', function(e) { //el event click primero pasa por el OverlapMarkerSpiderfy, y luego recien al marker
    onMarkerInmuebleClick(e);
  });

};

// SE DEFINE LA FUNCION PARA EL LLENADO DE LOS POPUPS MARKERS INMUEBLES #############################################################


function popup_marker_inmueble(info_inmueble){
  if (info_inmueble['tipo_bien'] == 'terreno') {
    if (info_inmueble['superficie_terreno_medida'] == 'hect') {
      var string_made = "<div class='marker_popup_container'><span class='popup_entire_group'><span>" + info_inmueble['precio'].replace(/\B(?=(\d{3})+(?!\d))/g, " ") + " $us</span><span>" + (info_inmueble['superficie_terreno']/10000) + " Hect</sup></span></span></div>";
    }else {//entonces es en m2
      var string_made = "<div class='marker_popup_container'><span class='popup_entire_group'><span>" + info_inmueble['precio'].replace(/\B(?=(\d{3})+(?!\d))/g, " ") + " $us</span><span>" + info_inmueble['superficie_terreno'] + " m<sup>2</sup></sup></span></span></div>";
    };
  }else if (info_inmueble['tipo_bien'] == 'local') {//entonces es local
    var string_made = "<div class='marker_popup_container'><span class='popup_half_group'><span>" + info_inmueble['precio'].replace(/\B(?=(\d{3})+(?!\d))/g, " ") + " $us</span><span>" + info_inmueble['superficie_inmueble'] + " m<sup>2</sup></span></span><span class='popup_half_group'><span><i class='fa fa-car'></i> x" + info_inmueble['parqueos'] + "</span></span></div>";
  }else {//entonces es casa o departameto
    var string_made = "<div class='marker_popup_container'><span class='popup_half_group'><span>" + info_inmueble['precio'].replace(/\B(?=(\d{3})+(?!\d))/g, " ") + " $us</span><span>" + info_inmueble['superficie_inmueble'] + " m<sup>2</sup></span></span><span class='popup_half_group'><span><i class='fa fa-bed'></i> x" + info_inmueble['dormitorios'] + "</span><span><i class='fa fa-car'></i> x" + info_inmueble['parqueos'] + "</span></span></div>";
  } ;
  return string_made;
};


// SE DEFINE LA FUNCION QUE BORRA TODOS LOS MARKERS ACTUALES INCLUIDOS LOS POPUPS Y TOOLTIPS ########################################

function reset_markers_inmueble(){
  $(".marcador_inmueble").remove();
  $(".tooltip_inmueble").remove();
  $(".popup_inmueble").remove();
};

function reset_markers_departamento(){
  $(".marcador_departamento").remove();
  $(".tooltip_departamento").remove();
  $(".popup_departamento").remove();
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
        show_inmuebles();
      };
      if (mymap.getZoom()<8) {
        show_departamentos_markers();
        show_departamentos_limits();
        hide_inmuebles();
      };

  });

// SE DEFINE LA FUNCION PARA ACTUALIZAR VISTA DEL MAPA A TODO EL PAIS #################

function refresh_view(country_lat, country_lng, country_zoom){
  mymap.flyTo([country_lat, country_lng], country_zoom)
};

// SE DEFINE LA FUNCION QUE MUESTRA LOS MARKERS DE LAS AGENCIAS INDIVIDUALES ###############################

function show_inmuebles(){
  $(".marcador_inmueble").show();
  $(".tooltip_inmueble").show();
  $(".popup_inmueble").show();
};

// SE DEFINE LA FUNCION QUE OCULTA LOS MARKERS DE LAS AGENCIAS INDIVIDUALES ###############################

function hide_inmuebles(){
  $(".marcador_inmueble").hide();
  $(".tooltip_inmueble").hide();
  $(".popup_inmueble").hide();
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
  $('.tooltip_departamento').hide();
};

// SE DEFINE LA FUNCION QUE VUELVE A MOSTRAR LOS MARKERS DE DEPARTAMENTOS #####################################

function show_departamentos_markers(){
  $('.marcador_departamento').show();
  $('.tooltip_departamento').show();
};


// CODIGO RESPUESTA A SELECCION TIPO DE BIEN INMUEBLE ################################################################


  $("#tipo_bien").on("selectmenuchange", function(){
    let tipo_bien = $("#tipo_bien option:selected").val();
    if (tipo_bien == 'terreno') {
      $(".slider_superficie_1").toggleClass("slider_hidden");
      $(".slider_superficie_2").toggleClass("slider_hidden");
    };

    if (tipo_bien == 'casa' || tipo_bien == 'departamento' || tipo_bien == 'local') {
      if ($(".slider_superficie_1").hasClass("slider_hidden")) {
        $(".slider_superficie_1").toggleClass("slider_hidden");
        $(".slider_superficie_2").toggleClass("slider_hidden");
      };
    };

    $(".filtros_container_deslizante").removeClass("filtros_open");
    $(".check_activate").html("");
    $(".elemento_checkbox").removeClass("activated").removeClass("pushed").val(0);
    $(".elemento_spinner").spinner("disable").removeClass("activated").val(0);
    $(".elemento_select").each(function(){
      let option_default = $(this).find(".option_default").val();
      $(this).selectmenu("disable").val(option_default).selectmenu("refresh").removeClass("activated");
    });
    $(".filtros_activos").removeClass("visible");
    $(".borrar_filtros_btn").removeClass("visible");

    refresh_markers_inmuebles("later_call");
  });


// CODIGO RESPUESTA A SELECCION DE BIEN EN VENTA O EN ALQUILER ############################################################

  $(".box_radio_choices label").on("click", function(){

    let box_checked = $(this).attr("name");

    if(!$(this).hasClass("ui-state-active")){
      $(".slider_precio_1").toggleClass("slider_hidden");
      $(".slider_precio_2").toggleClass("slider_hidden");
      $(".box_radio_choices label").toggleClass("box_active");
      if ($(".filtros_activos").hasClass("visible")) {
        let tipo_bien_button = ".button_" + $("#tipo_bien option:selected").val();
        $(tipo_bien_button).click();
      }else {
        refresh_markers_inmuebles("later_call");
      };

    };

  });

// CODIGO RESPUESTA A CAMBIOS DE VALOR EN SLIDERS ###########################################################################

  $(".elementos_variables").on( "slidestop", ".uirange", function( event, ui ) {
    if ($(".filtros_activos").hasClass("visible")) {
      let tipo_bien_button = ".button_" + $("#tipo_bien option:selected").val();
      $(tipo_bien_button).click();
    }else {
      refresh_markers_inmuebles("later_call");
    };
  } );


// SE CARGA INICIALMENTE LOS RESULTADOS DE VENTA DE CASAS CON PARAMETROS PREDETERMITADOS ############################################

$(".filtros_btn").on("click", function(){
  let tipo_bien_actual = $("#tipo_bien option:selected").val();
  let filtros_to_open = ".filtros_" + tipo_bien_actual;
  $(filtros_to_open).animate({ scrollTop: 0 }, "fast");
  $(filtros_to_open).toggleClass("filtros_open");
  $(".cerrar_filtros_btn").addClass("filtros_open");
  $(".borrar_filtros_btn").addClass("filtros_open");
});

$(".cerrar_filtros_btn").on("click", function(){
  $(".filtros_container_deslizante").removeClass("filtros_open");
  $(".cerrar_filtros_btn").removeClass("filtros_open");
  $(".borrar_filtros_btn").removeClass("filtros_open");
});

$(".borrar_filtros_btn").on("click", function(){
  $(".check_activate").html("");
  $(".elemento_checkbox").removeClass("activated").removeClass("pushed").val(0);
  $(".elemento_spinner").spinner("disable").removeClass("activated").val(0);
  $(".elemento_select").each(function(){
    let option_default = $(this).find(".option_default").val();
    $(this).selectmenu("disable").val(option_default).selectmenu("refresh").removeClass("activated");
  });
  $(".filtros_activos").removeClass("visible");
  $(this).removeClass("visible");
  refresh_markers_inmuebles("later_call");
});


// CODIGO QUE MANEJA EL CHECK ACTIVATE FILTROS INDIVIDUALES ###############################################################

$(".mapa_contenedor").on("click", ".check_activate", function(){
  let check_item = "#" + $(this).attr("name");

  if($(check_item).hasClass("activated")){
    $(this).html("");
    if ($(check_item).hasClass("elemento_checkbox")) {
    $(check_item).removeClass("activated").removeClass("pushed").val(0);
    };
    if ($(check_item).hasClass("elemento_spinner")) {
      $(check_item).spinner("disable").removeClass("activated").val(0);
    };
    if ($(check_item).hasClass("elemento_select")) {
      let option_default = $(check_item).find(".option_default").val();
      $(check_item).selectmenu("disable").val(option_default).selectmenu("refresh").removeClass("activated");
    };

  }else {
    $(this).html("x");
    if ($(check_item).hasClass("elemento_checkbox")) {
      $(check_item).addClass("activated").addClass("pushed").val(1);
    };
    if ($(check_item).hasClass("elemento_spinner")) {
      $(check_item).spinner("enable").addClass("activated").val(0);
    };
    if ($(check_item).hasClass("elemento_select")) {
      let option_default = $(check_item).find(".option_default").val();
      $(check_item).selectmenu("enable").val(option_default).selectmenu("refresh").addClass("activated");
    };
  };

});

// CODIGO QUE MANEJA EL CLICK EN UN ELEMENTO CHECKBOX #####################################################################

$(".mapa_contenedor").on("click", ".elemento_checkbox.activated", function(){
  $(this).toggleClass("pushed");
  if ($(this).val() == 1) {
    $(this).val(0);
  }else if ($(this).val() == 0){
    $(this).val(1);
  };
});

// CODIGO SPINNERS ###########################################################################################################

$("#dormitorios_casa").spinner({
  min: 0,
}).spinner("disable");

$("#parqueos_casa").spinner({
  min: 0,
}).spinner("disable");

$("#dormitorios_departamento").spinner({
  min: 0,
}).spinner("disable");

$("#parqueos_departamento").spinner({
  min: 0,
}).spinner("disable");

$("#espacios_local").spinner({
  min: 0,
}).spinner("disable");

$("#parqueos_local").spinner({
  min: 0,
}).spinner("disable");

$("#pisos_casa").spinner({
  min: 0,
}).spinner("disable");

$("#piso_departamento").spinner({
  min: 0,
}).spinner("disable");

$("#piso_local").spinner({
  min: 0,
}).spinner("disable");

$("#wc_casa").spinner({
  min: 0,
}).spinner("disable");

$("#wc_departamento").spinner({
  min: 0,
}).spinner("disable");

$("#wc_local").spinner({
  min: 0,
}).spinner("disable");

$("#calefaccion_casa").selectmenu().selectmenu("disable");

$("#calefaccion_departamento").selectmenu().selectmenu("disable");

$("#calefaccion_local").selectmenu().selectmenu("disable");

$("#tipo_zona_terreno").selectmenu().selectmenu("disable");

$("#tipo_local_local").selectmenu().selectmenu("disable");

$("#adaptacion_local").selectmenu().selectmenu("disable");


$('.ui-spinner-button').click(function() { // Permite accionar el evento change al usar los botones del spinner
  $(this).siblings('input').change();
  var spinner_input_id = $(this).siblings('input').attr('id');
  var spinner_value = $('#'+spinner_input_id).val();
});


// CODIGO PARA EL BOTON BUSQUEDA DENTRO DE FILTROS #######################################################################

$(".button_busqueda_deslizante").on("click", function(){
  if($(this).parent().find(".activated").length){//Checkea si algun filtro fue activado
    $(".filtros_container_deslizante").removeClass("filtros_open");
    $(".cerrar_filtros_btn").removeClass("filtros_open");
    $(".borrar_filtros_btn").removeClass("filtros_open").addClass("visible");
    if (!$(".filtros_activos").hasClass("visible")) {
      $(".filtros_activos").addClass("visible");
    };

  };

});

// CODIGO BUSQUEDA AVANZADA CASA ###########################################################################################

$(".button_casa").on("click", function(){
   if($(this).parent().find(".activated").length){//Checkea si algun filtro fue activado

      let tipo_bien_sent = "casa";
      let estado_bien_sent = $(".box_radio_choices label.box_active").attr("name");
      let precio_max_sent;
      let superficie_min_sent = $("#superficie_busqueda").val();

      if (estado_bien_sent == "En Venta") {
        precio_max_sent = $("#precio_busqueda_venta").val();
      };
      if (estado_bien_sent == "En Alquiler") {
        precio_max_sent = $("#precio_busqueda_renta").val();
      };

      let conditions = []; // las conditiones a poner entre clausulas WHERE -- AND
      let parameters = []; // los parameter a ser invocados en el sql execute

    // ESTOS SON LOS CHECKBOXES ###############################

      $(".checkbox_casa.activated").each(function(){ //todos los checkboxes cuyos valores son binarios en la db
        let condition_name = $(this).attr("name");
        let param_val = $(this).val();
        conditions.push(condition_name + " = ?");
        parameters.push(param_val);
      });

      if ($(".checkbox_casa_internet").hasClass("activated")) { // este checkbox no es binario en la db, pero la busqueda si lo es

        let condition_name = $(".checkbox_casa_internet").attr("name");
        let param_val = $(".checkbox_casa_internet").val();
        if (param_val == 0) {
          conditions.push(condition_name + " = ?");
          parameters.push("Inexistente");
        }else if (param_val == 1) {
          conditions.push(condition_name + " != ?");
          parameters.push("Inexistente");
        };

      };

    // ESTOS SON LOS SPINNERS ####################################

      $(".spinner_casa.activated").each(function(){
        let condition_name = $(this).attr("name");
        let param_val = $(this).val();
        conditions.push(condition_name + " >= ?");
        parameters.push(param_val);
      });

    // ESTOS SON LOS SELECTS #####################################

      if($(".select_casa_calefaccion").hasClass("activated")){
        let condition_name = $(".select_casa_calefaccion").attr("name");
        let param_val = $(".select_casa_calefaccion").find("option:selected").val();
        if (param_val == "Todos") {
          // NO SE TOMA EN CUENTA ESTE FILTRO
        }else if (true) {
          conditions.push(condition_name + " = ?");
          parameters.push(param_val);
        };
      };

      busqueda_avanzada_process(conditions, parameters);
    };

  });


  $(".button_departamento").on("click", function(){
    if($(this).parent().find(".activated").length){//Checkea si algun filtro fue activado
        let tipo_bien_sent = "departamento";
        let estado_bien_sent = $(".box_radio_choices label.box_active").attr("name");
        let precio_max_sent;
        let superficie_min_sent = $("#superficie_busqueda").val();

        if (estado_bien_sent == "En Venta") {
          precio_max_sent = $("#precio_busqueda_venta").val();
        };
        if (estado_bien_sent == "En Alquiler") {
          precio_max_sent = $("#precio_busqueda_renta").val();
        };

        let conditions = []; // las conditiones a poner entre clausulas WHERE -- AND
        let parameters = []; // los parameter a ser invocados en el sql execute

      // ESTOS SON LOS CHECKBOXES ###############################

        $(".checkbox_departamento.activated").each(function(){ //todos los checkboxes cuyos valores son binarios en la db
          let condition_name = $(this).attr("name");
          let param_val = $(this).val();
          conditions.push(condition_name + " = ?");
          parameters.push(param_val);
        });

        if ($(".checkbox_departamento_internet").hasClass("activated")) { // este checkbox no es binario en la db, pero la busqueda si lo es

          let condition_name = $(".checkbox_departamento_internet").attr("name");
          let param_val = $(".checkbox_departamento_internet").val();
          if (param_val == 0) {
            conditions.push(condition_name + " = ?");
            parameters.push("Inexistente");
          }else if (param_val == 1) {
            conditions.push(condition_name + " != ?");
            parameters.push("Inexistente");
          };

        };

        if ($(".checkbox_departamento_balcon").hasClass("activated")) { // este checkbox no es binario en la db, pero la busqueda si lo es

          let param_val = $(".checkbox_departamento_balcon").val();
            conditions.push("balcon = ?");
            parameters.push(param_val);
            conditions.push("terraza = ?");
            parameters.push(param_val);
        };

      // ESTOS SON LOS SPINNERS ####################################

        $(".spinner_departamento.activated").each(function(){
          let condition_name = $(this).attr("name");
          let param_val = $(this).val();
          conditions.push(condition_name + " >= ?");
          parameters.push(param_val);
        });

      // ESTOS SON LOS SELECTS #####################################

        if($(".select_departamento_calefaccion").hasClass("activated")){
          let condition_name = $(".select_departamento_calefaccion").attr("name");
          let param_val = $(".select_departamento_calefaccion").find("option:selected").val();
          if (param_val == "Todos") {
            // NO SE TOMA EN CUENTA ESTE FILTRO
          }else if (true) {
            conditions.push(condition_name + " = ?");
            parameters.push(param_val);
          };
        };
        busqueda_avanzada_process(conditions, parameters);
      };
    });

    $(".button_local").on("click", function(){
      if($(this).parent().find(".activated").length){//Checkea si algun filtro fue activado

          let tipo_bien_sent = "local";
          let estado_bien_sent = $(".box_radio_choices label.box_active").attr("name");
          let precio_max_sent;
          let superficie_min_sent = $("#superficie_busqueda").val();

          if (estado_bien_sent == "En Venta") {
            precio_max_sent = $("#precio_busqueda_venta").val();
          };
          if (estado_bien_sent == "En Alquiler") {
            precio_max_sent = $("#precio_busqueda_renta").val();
          };

          let conditions = []; // las conditiones a poner entre clausulas WHERE -- AND
          let parameters = []; // los parameter a ser invocados en el sql execute

        // ESTOS SON LOS CHECKBOXES ###############################

          $(".checkbox_local.activated").each(function(){ //todos los checkboxes cuyos valores son binarios en la db
            let condition_name = $(this).attr("name");
            let param_val = $(this).val();
            conditions.push(condition_name + " = ?");
            parameters.push(param_val);
          });

          if ($(".checkbox_local_internet").hasClass("activated")) { // este checkbox no es binario en la db, pero la busqueda si lo es

            let condition_name = $(".checkbox_local_internet").attr("name");
            let param_val = $(".checkbox_local_internet").val();
            if (param_val == 0) {
              conditions.push(condition_name + " = ?");
              parameters.push("Inexistente");
            }else if (param_val == 1) {
              conditions.push(condition_name + " != ?");
              parameters.push("Inexistente");
            };

          };

          if ($(".checkbox_local_balcon").hasClass("activated")) { // este checkbox no es binario en la db, pero la busqueda si lo es

            let param_val = $(".checkbox_local_balcon").val();
              conditions.push("balcon = ?");
              parameters.push(param_val);
              conditions.push("terraza = ?");
              parameters.push(param_val);
          };

        // ESTOS SON LOS SPINNERS ####################################

          $(".spinner_local.activated").each(function(){
            let condition_name = $(this).attr("name");
            let param_val = $(this).val();
            conditions.push(condition_name + " >= ?");
            parameters.push(param_val);
          });

        // ESTOS SON LOS SELECTS #####################################

          if($(".select_local_calefaccion").hasClass("activated")){
            let condition_name = $(".select_local_calefaccion").attr("name");
            let param_val = $(".select_local_calefaccion").find("option:selected").val();
            if (param_val == "Todos") {
              // NO SE TOMA EN CUENTA ESTE FILTRO
            }else if (true) {
              conditions.push(condition_name + " = ?");
              parameters.push(param_val);
            };
          };

          if($(".select_local_adaptacion").hasClass("activated")){
            let condition_name = $(".select_local_adaptacion").attr("name");
            let param_val = $(".select_local_adaptacion").find("option:selected").val();
              conditions.push(condition_name + " = ?");
              parameters.push(param_val);
          };

          if($(".select_local_tipo_local").hasClass("activated")){
            let condition_name = $(".select_local_tipo_local").attr("name");
            let param_val = $(".select_local_tipo_local").find("option:selected").val();
              conditions.push(condition_name + " = ?");
              parameters.push(param_val);
          };
          busqueda_avanzada_process(conditions, parameters);
        };
      });


      $(".button_terreno").on("click", function(){
        if($(this).parent().find(".activated").length){//Checkea si algun filtro fue activado

            let tipo_bien_sent = "terreno";
            let estado_bien_sent = $(".box_radio_choices label.box_active").attr("name");
            let precio_max_sent;
            let superficie_min_sent = $("#superficie_busqueda_terreno").val();

            if (estado_bien_sent == "En Venta") {
              precio_max_sent = $("#precio_busqueda_venta").val();
            };
            if (estado_bien_sent == "En Alquiler") {
              precio_max_sent = $("#precio_busqueda_renta").val();
            };

            let conditions = []; // las conditiones a poner entre clausulas WHERE -- AND
            let parameters = []; // los parameter a ser invocados en el sql execute

          // ESTOS SON LOS CHECKBOXES ###############################

            $(".checkbox_terreno.activated").each(function(){ //todos los checkboxes cuyos valores son binarios en la db
              let condition_name = $(this).attr("name");
              let param_val = $(this).val();
              conditions.push(condition_name + " = ?");
              parameters.push(param_val);
            });

          // ESTOS SON LOS SELECTS #####################################

            if($(".select_terreno_tipo_zona").hasClass("activated")){
              let condition_name = $(".select_terreno_tipo_zona").attr("name");
              let param_val = $(".select_terreno_tipo_zona").find("option:selected").val();
              if (param_val == "Todos") {
                // NO SE TOMA EN CUENTA ESTE FILTRO
              }else if (true) {
                conditions.push(condition_name + " = ?");
                parameters.push(param_val);
              };
            };
            busqueda_avanzada_process(conditions, parameters);
          };
        });


// SE DEFINE LA FUNCION QUE MANDA LOS DATOS DE BUSQUEDA AVANZADA AL PROCESS REQUEST ########################################

  function busqueda_avanzada_process(conditions, parameters){

      let tipo_bien = $("#tipo_bien option:selected").val();
      let estado_bien = $(".box_radio_choices label.box_active").attr("name");
      let precio_max;
      let superficie_min;

      resultados.length = 0;

      if (estado_bien == "En Venta") {
        precio_max = $("#precio_busqueda_venta").val();
      };
      if (estado_bien == "En Alquiler") {
        precio_max = $("#precio_busqueda_renta").val();
      };

      if (tipo_bien == "casa" || tipo_bien == "departamento" || tipo_bien == "local") {
        superficie_min = $("#superficie_busqueda").val();
      };
      if (tipo_bien == "terreno") {
        superficie_min = $("#superficie_busqueda_terreno").val();
      };

      $.ajax({//Se manda los datos de busqueda avanzada y se recupera los datos para la creacion de markers
        type: "POST",
        async: false,
        url: "process-request-busqueda_avanzada_inmuebles.php",
        data: { tipo_bien_Choice : tipo_bien, estado_bien_Choice : estado_bien, precio_max_Choice : precio_max, superficie_min_Choice : superficie_min, conditions_sent : conditions, parameters_sent : parameters },
        success: function(data){
          bienes_inmuebles = jQuery.parseJSON(data);
          reset_markers_inmueble();
          armado_markers(bienes_inmuebles, "later_call");
        }
      }).error(function() {console.log("error");});

  };


// ##########################################################################################################################
// ##################################### CODIGO PARA LA APERTURA DEL POPUP FICHA BIEN #######################################
// ##########################################################################################################################

// CODIGO PARA TRAER LA FICHA BIEN DESPUES DE HACER CLICK EN UN MARKER BIEN INMUEBLE ####################################
  function onMarkerInmuebleClick(e){
      $('.ficha_bien_container').addClass('active');
      var ficha_bien_clicked_referencia = e.options.marker_id;
      var estado = $(".box_radio_choices label.box_active").attr("name");
      var ficha_bien_tipo;
      var agente_id = $("#agente_id").val();

      if (ficha_bien_clicked_referencia.includes("C")) {
        ficha_bien_tipo = "casa";
      } else {
        if (ficha_bien_clicked_referencia.includes("D")) {
          ficha_bien_tipo = "departamento";
        } else {
          if (ficha_bien_clicked_referencia.includes("L")) {
            ficha_bien_tipo = "local";
          } else {
            if (ficha_bien_clicked_referencia.includes("T")) {
              ficha_bien_tipo = "terreno";
            };
          };
        };
      };

      $.ajax({
            type: "POST",
            url: "process-request-popup_ficha_bien_detalle.php",
            data: { ficha_bien_requested : ficha_bien_clicked_referencia, ficha_bien_tipo_requested : ficha_bien_tipo, estado : estado, agente_sent : agente_id },
        }).done(function(data){
          $('.popup_ficha_bien').html(data);
          $("body").addClass('ficha_active');
          });

  };

// CODIGO PARA LOS TABS ################################################################################################

  $("#contenedor_total").on("click", ".elemento_tab", function(){
    var div_to_show = '#' + $(this).attr('name');
    $(".elemento_tab").removeClass('active');
    $(this).addClass('active');

    $(".elemento_ficha").removeClass('active');
    $(div_to_show).addClass('active');
  });

// CODIGO PARA EL CIERRE DEL POPUP FICHA BIEN###########################################################################

  $('.ficha_bien_container').on('click', 'span.fa-times.actions_type2_icon', function(){//controla el boton de cierre

    $('.ficha_bien_container').animate({
      scrollTop: '0px'
    }, 0);
    $('.ficha_bien_container').removeClass('active');
    $("body").removeClass('ficha_active');
  });

//CODIGO PARA EL ROTAR DE LA ESTRELLA EN FAVORITOS FICHA BIEN#####################################################
  $('.ficha_bien_container').on('click', '.icon_favoritos', function(e){//controla el cambio de color y el rotar de la estrella de cada thumb
      $('.icon_favoritos span.fa-star').toggleClass("rotate_star");
    });

//CODIGO PARA LA APERTURA DEL MAPA FICHA BIEN ################################################################

  $('.ficha_bien_container').on('click', '.banner_ver_mapa', function(e){//event listener del click en abrir mapa

    // Codigo para establecer la vista del mapa OSM
    var lat = $('#mapa_coordenada_lat').val();
    var lng = $('#mapa_coordenada_lng').val();
    var zoom = $('#mapa_zoom').val();
    var direccion = $('#mapa_direccion').val();

            $('.banner_ver_mapa').remove();
            $('.mapa_ficha_contenedor').css('height', '30em');
            L.mapbox.accessToken = 'pk.eyJ1IjoiZnVzaWxvbjk0IiwiYSI6ImNqb204ODF3NjBob2szcWtrcjgwc3lwZjEifQ.y6VWJLe3kPWhEPX8x0YfvQ';

            var mymap_ficha_bien = L.map('mapa_ficha_contenedor', {doubleClickZoom: false })
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

            var marker = L.marker([lat, lng], {icon: marcador_tutecho}).addTo(mymap_ficha_bien);
            marker.bindPopup("Dirección:<br><b>" + direccion + "</b>").openPopup();

  });



// CODIGO PARA ABRIR EL VIEWER #####################################

$('.ficha_bien_container').on('click', '.open_viewer_btn', function(){
  func_abrir_viewer();
});


$('.tooltip_cerrar').on('click', function(){
  func_cerrar_tooltip();
});

$('.ficha_bien_container').on('click', '.media_viewer_cerrar' ,function(){
  func_cerrar_viewer();
});

$(".btn_abrir_right").on("click", function(){
  func_abrir_menu_derecho();
});

$(".ficha_bien_container").on("click", ".btn_entrar_tour_vr", function(){
  func_entrar_tour_vr();
});

$(".ficha_bien_container").on("click", ".foto_mini_container", function(){
  $(".foto_mini_container").removeClass('selected');

  if ($(this).hasClass('selected') == false) {
    $(this).addClass('selected');
  };
  default_image_view = false;
  var foto_clicked_id = $(this).find('.foto_prev_container').attr('id');
  var foto_clicked = '../../bienes_inmuebles/' + cookie_pais + '/' + ficha_bien_referencia + '/fotos_360/' + foto_clicked_id;
  var foto_key = $(this).find('.foto_prev_container').attr('name');

  current_foto = foto_key;

  TweenLite.to(sphere.material, 0.5, {opacity: 0, onComplete: function(){next_texture_loader(foto_clicked)}});
});


  });
});
