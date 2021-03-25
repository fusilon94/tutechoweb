$(document).ready(function(){
  jQuery(function($){
    
    let datos_pais;
    $.ajax({
      type: "POST",
      url: "../../contenido/m5/process-request-coordenadas-paises.php",
      dataType: 'json',
      async: false,
    }).done(function(data){
      datos_pais = data;
    });

    $(".departamento_label").html(` ${datos_pais['org_territorial']}:`);


// CODIGO ACTIVAR VISTA PRELIMINAR EN TABLET Y MOBILE ############################

    $('.switch_container').on('click', "span.boton_ver_vista_preliminar", function(){
      $(".popups_container").toggleClass('visible');
      $("span.boton_ver_vista_preliminar i").toggleClass('fa-eye-slash fa-eye');
      $(".boton_ver_vista_preliminar").toggleClass('active');
    });

// CODIGO VISTA PRELIMINAR SWITCH ENTRE DESKTOP Y MOBILE #############################################

    $('.switch_vista_preliminar').on('click', '.switch', function(){

      $(".switch_desktop").toggleClass("active");
      $(".switch_mobile").toggleClass("active");

      $(".popup_sponsor").toggleClass("popup_visible");
      $(".popup_sponsor2").toggleClass("popup_visible");
      $('#mapa_sponsor_zoom').trigger('change');

    });

// CODIGO QUE LLENA DINAMICAMENTE LA INFO INGRESADA EN LOS INPUT TYPE TEXT #####################

    $("#nombre").on("input", function(){//Si hubo un cambio en el input (esto ve cambio en timepo real)
      if ($(this).val() !== '') {//si se ingreso un valor y no se dejó vacío
        $(".popup_sponsor_titulo label").text($(this).val());//actualiza la prev PC y la Mobile
        $(".popup_sponsor_titulo2 label").text($(this).val());
        if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
          $(this).parent().children("label").children('i').css('color', 'rgb(68, 235, 54)');
        };
        if ($(this).val().match(/^[\w\d\s+\-áÁéÉíÍóÓúÚñÑ&\/,.\']+$/) == null) {//Si se ingrso un caracter no permitido
          alert('Simbolo/Caracter no permitido');
          $(this).css('border-color', 'rgb(255, 0, 0) ')
        }else {
          $(this).css('border-color', 'initial')
        }
      }else {
        $(".popup_sponsor_titulo label").text("Negocio");
        $(".popup_sponsor_titulo2 label").text("Negocio");
        if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
          $(this).parent().children("label").children('i').css('color', 'rgb(228, 46, 46)');
        };
      }
    });

    $("#subtitulo").on("input", function(){//Si hubo un cambio en el input (esto ve cambio en timepo real)
      if ($(this).val() !== '') {//si se ingreso un valor y no se dejó vacío
        $(".popup_sponsor_descripcion").text($(this).val());//actualiza la prev PC y la Mobile
        $(".popup_sponsor_descripcion2").text($(this).val());
        if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
          $(this).parent().children("label").children('i').css('color', 'rgb(68, 235, 54)');
        };
        if ($(this).val().match(/^[\w\d\s+\-&.,()\/!?:%*áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
          alert('Simbolo/Caracter no permitido');
          $(this).css('border-color', 'rgb(255, 0, 0) ')
        }else {
          $(this).css('border-color', 'initial')
        }
      }else {
        $(".popup_sponsor_descripcion").text("- Subtitulo o Descripción -");
        $(".popup_sponsor_descripcion2").text("- Subtitulo o Descripción -");
        if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
          $(this).parent().children("label").children('i').css('color', 'rgb(228, 46, 46)');
        };
      }
    });

    $("#direccion").on("input", function(){//Si hubo un cambio en el input (esto ve cambio en timepo real)
      if ($(this).val() !== '') {//si se ingreso un valor y no se dejó vacío
        $(".popup_sponsor_direccion").text($(this).val());//actualiza la prev PC y la Mobile
        $(".popup_sponsor_direccion2").text($(this).val());
        if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
          $(this).parent().children("label").children('i').css('color', 'rgb(68, 235, 54)');
        };
        if ($(this).val().match(/^[\w\d\s+\-&.,#:\/áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
          alert('Simbolo/Caracter no permitido');
          $(this).css('border-color', 'rgb(255, 0, 0) ')
        }else {
          $(this).css('border-color', 'initial')
        }
      }else {
        $(".popup_sponsor_direccion").text("Dirección del negocio");
        $(".popup_sponsor_direccion2").text("Dirección del negocio");
        if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
          $(this).parent().children("label").children('i').css('color', 'rgb(228, 46, 46)');
        };
      }
    });

    $("#contacto").on("input", function(){//Si hubo un cambio en el input (esto ve cambio en timepo real)
      if ($(this).val() !== '') {//si se ingreso un valor y no se dejó vacío
        $(".popup_sponsor_contacto").text($(this).val());//actualiza la prev PC y la Mobile
        $(".popup_sponsor_contacto2").text($(this).val());
        if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
          $(this).parent().children("label").children('i').css('color', 'rgb(68, 235, 54)');
        };
        if ($(this).val().match(/^[+\-0-9().# \/]+$/g) == null) {//Si se ingrso un caracter no permitido
          alert('Simbolo/Caracter no permitido');
          $(this).css('border-color', 'rgb(255, 0, 0) ')
        }else {
          $(this).css('border-color', 'initial')
        }
      }else {
        $(".popup_sponsor_contacto").text("Número de contacto");
        $(".popup_sponsor_contacto2").text("Número de contacto");
        if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
          $(this).parent().children("label").children('i').css('color', 'rgb(228, 46, 46)');
        };
      }
    });

    $("#email").on("input", function(){//Si hubo un cambio en el input (esto ve cambio en timepo real)
      if ($(this).val() !== '') {//si se ingreso un valor y no se dejó vacío
        $(".popup_sponsor_web").text($(this).val());//actualiza la prev PC y la Mobile
        $(".popup_sponsor_web2").text($(this).val());
        if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
          $(this).parent().children("label").children('i').css('color', 'rgb(68, 235, 54)');
        };
        if ($(this).val().match(/^[\w\d\s+\-&._#@áÁéÉíÍóÓúÚñÑ\'\/]+$/) == null) {//Si se ingrso un caracter no permitido
          alert('Simbolo/Caracter no permitido');
          $(this).css('border-color', 'rgb(255, 0, 0) ')
        }else {
          $(this).css('border-color', 'initial')
        }
      }else {
        $(".popup_sponsor_web").text("Email o sitio web del negocio");
        $(".popup_sponsor_web2").text("Email o sitio web del negocio");
        if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
          $(this).parent().children("label").children('i').css('color', 'rgb(228, 46, 46)');
        };
      }
    });

    $("#responsable").on("input", function(){//Si hubo un cambio en el input (esto ve cambio en timepo real)
      if ($(this).val() !== '') {//si se ingreso un valor y no se dejó vacío
        if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
          $(this).parent().children("label").children('i').css('color', 'rgb(68, 235, 54)');
        };
        if ($(this).val().match(/^[\w\d\s-áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
          alert('Simbolo/Caracter no permitido');
          $(this).css('border-color', 'rgb(255, 0, 0) ')
        }else {
          $(this).css('border-color', 'initial')
        }
      }else {
        if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
          $(this).parent().children("label").children('i').css('color', 'rgb(228, 46, 46)');
        };
      };

    });

      $("#responsable_contacto").on("input", function(){//Si hubo un cambio en el input (esto ve cambio en timepo real)
        if ($(this).val() !== '') {//si se ingreso un valor y no se dejó vacío
          if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
            $(this).parent().children("label").children('i').css('color', 'rgb(68, 235, 54)');
          };
          if ($(this).val().match(/^[+\-0-9(). \/]+$/g) == null) {//Si se ingrso un caracter no permitido
            alert('Simbolo/Caracter no permitido');
            $(this).css('border-color', 'rgb(255, 0, 0) ')
          }else {
            $(this).css('border-color', 'initial')
          }
        }else {
            if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
            $(this).parent().children("label").children('i').css('color', 'rgb(228, 46, 46)');
          };
        }
      });

        $("#fecha_vencimiento").on("change", function(){//Si detecta un cambio en el value del input
          if ($(this).val() !== '') {//si se escogio una fecha
            if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
              $(this).parent().children("label").children('i').css('color', 'rgb(68, 235, 54)');
            };
          }else {
            if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
              $(this).parent().children("label").children('i').css('color', 'rgb(228, 46, 46)');
            };
        };
    });

// CODIGO PARA POBLAR LOS SELECT CIUDADES Y BARRIOS y RECUPERAR COORDENADAS PARA EL MAPA ########################

    $("select.departamento").change(function(){
        var departamentoSelected = $(".departamento option:selected").val();
        if (departamentoSelected !== '') { //si hubo una seleccion se cargan las ciudades de la db

          if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
            $(this).parent().children("label").children('i').css('color', 'rgb(68, 235, 54)');
          };

            $.ajax({
                type: "POST",
                url: "process-request-ciudades.php",
                data: { departamentoChoice : departamentoSelected }
            }).done(function(data){
                $("#ciudad").prop('disabled', false).html(data);// se activa el select ciudades y pobla
            });

            // para tambien inducir cambios en el select Barrios, vacia la lista
            $("#barrio").empty().prop('disabled', true);//se vacia y bloquea el select barrios si tenia algo
            if ($("#barrio").parent().children("label").children('i').length) {//si el DOT de verif existe
              $("#barrio").parent().children("label").children('i').css('color', 'rgb(228, 46, 46)');
            };
            // ahora pedimos las coordenadas y el zoom del departamento elejido para actualizar el mapa
            $.ajax({
                type: "POST",
                url: "process-request-coordenadas_mapa_sponsor_registro.php",
                data: { departamentoChoice : departamentoSelected }
            }).done(function(data){
                $(".mapa_coordenadas_container").html(data);
                refresh_mapa_registro_sponsor();
            });

        }else { // si se seleciono vacio, entonces se vacian y bloquean los select ciudad y barrio
          $("#ciudad").empty().prop('disabled', true).val('');
          if ($("#ciudad").parent().children("label").children('i').length) {//si el DOT de verif existe
          $("#ciudad").parent().children("label").children('i').css('color', 'rgb(228, 46, 46)');
          };
          $("#barrio").empty().prop('disabled', true).val('');
          if ($("#barrio").parent().children("label").children('i').length) {//si el DOT de verif existe
            $("#barrio").parent().children("label").children('i').remove();
          };
          if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
            $(this).parent().children("label").children('i').css('color', 'rgb(228, 46, 46)');
          };
        };
    });


    $("select.ciudad").change(function(){
        var ciudadSelected = $(".ciudad option:selected").val();
        if (ciudadSelected !== '') { // si hubo seleccion se cargan los barrios de la db
          if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
            $(this).parent().children("label").children('i').css('color', 'rgb(68, 235, 54)');
          };
          $.ajax({
              type: "POST",
              url: "process-request-barrios.php",
              data: { ciudadesChoice : ciudadSelected }
          }).done(function(data){
              if (data !== '<option></option>') {//si hubo resultados entonces se pobla y activa  el select barrios
                $("#barrio").prop('disabled', false).html(data);
              }else { // sino hubo resultados se desactiva y vacia el select barrios
                $("#barrio").empty().prop('disabled', true);
                if ($("#barrio").parent().children("label").children('i').length) {//si el DOT de verif existe
                  $("#barrio").parent().children("label").children('i').css('color', 'rgb(228, 46, 46)');
                };
              };
          });

          //ahora pedimos las coordenadas y el zoom del departamento elejido para actualizar el mapa
          $.ajax({
              type: "POST",
              url: "process-request-coordenadas_mapa_sponsor_registro.php",
              data: { ciudadesChoice : ciudadSelected }
          }).done(function(data){
              $(".mapa_coordenadas_container").html(data);
              refresh_mapa_registro_sponsor();
          });

        } else {// si se selecciono vacio, entonces se desactiva y vacia el select barrios
          $("#barrio").empty().prop('disabled', true).val('');
          if ($("#barrio").parent().children("label").children('i').length) {//si el DOT de verif existe
            $("#barrio").parent().children("label").children('i').css('color', 'rgb(228, 46, 46)');
          };
          if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
            $(this).parent().children("label").children('i').css('color', 'rgb(228, 46, 46)');
          };
        };

    });

    $("select.barrio").change(function(){
      var barrioSelected = $(".barrio option:selected").val();

      if (barrioSelected !== '') { // si hubo seleccion se piden las coordenadas del barrio
        if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
          $(this).parent().children("label").children('i').css('color', 'rgb(68, 235, 54)');
        };
        $.ajax({
            type: "POST",
            url: "process-request-coordenadas_mapa_sponsor_registro.php",
            data: { barrioChoice : barrioSelected }
        }).done(function(data){
            $(".mapa_coordenadas_container").html(data);
            refresh_mapa_registro_sponsor();
        });
      }else {
        if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
          $(this).parent().children("label").children('i').css('color', 'rgb(228, 46, 46)');
        };
      };
    });


// SE DEFINE LA FUNCION PARA ACTUALIZAR VISTA DEL MAPA SEGUN LOS DATOS INGRESADOS EN DEPARTAMENTO-CIUDAD-BARRIO #######################

function refresh_mapa_registro_sponsor(){
  $("#mapid_sponsor_config").remove();//se borra el mapa anterior para cargar uno nuevo
  $(".popup_sponsor_mapa_config").prepend("<div id=\"mapid_sponsor_config\" style=\"height:100%; width:100%;\"></div>");

  var lat = $('#mapa_sponsor_coordenada_lat').val();
  var lng = $('#mapa_sponsor_coordenada_lng').val();
  var zoom = $('#mapa_sponsor_zoom').val();

  L.mapbox.accessToken = 'pk.eyJ1IjoiZnVzaWxvbjk0IiwiYSI6ImNqb204ODF3NjBob2szcWtrcjgwc3lwZjEifQ.y6VWJLe3kPWhEPX8x0YfvQ';

  var mymap2 = L.map('mapid_sponsor_config', {doubleClickZoom: false })
  .addLayer(L.mapbox.styleLayer('mapbox://styles/fusilon94/ckgmmpknk1cxb19pmkrq0duuv', {maxZoom: 18, tileSize: 512, zoomOffset: -1}))
  .setView([lat, lng], zoom);

  var marker = L.marker();

  function onMapClick2(e){

    marker.setLatLng(e.latlng)
          .addTo(mymap2);

    var current_zoom = mymap2.getZoom();//se guarda el zoom actual en una variable
    $('#mapa_sponsor_coordenada_lat').val(e.latlng.lat).trigger('change');
    $('#mapa_sponsor_coordenada_lng').val(e.latlng.lng).trigger('change');
    $('#mapa_sponsor_zoom').val(current_zoom).trigger('change');//se guardan las coordenadas y el zoom en inputs para ser enviados con el formulario

  };

  mymap2.on("contextmenu", onMapClick2);
};

// CODIGO QUE PONE EL MAPA EN LA PREVISUALISACION DEL POPUP DESPUES DEL CLICK DERECHO o LONG TOUCH EN MAPA CONFIG##############
$(".popup_sponsor_mapa_config").on("change", "#mapa_sponsor_zoom" , function() {

  if ($(".popup_sponsor_mapa_config p i").length) {//si el DOT de verif existe
  $(".popup_sponsor_mapa_config p i").css('color', 'rgb(68, 235, 54)');
  };

  $("#mapid_sponsor").remove();//se borran ambos mapas de las previsulizaciones para cargar nuevo
  $("#mapid_sponsor2").remove();
  $("#popup_sponsor_mapa").prepend("<div id=\"mapid_sponsor\"></div>");//se vuelven a crear los contenedor pa los neuvos mapas
  $("#popup_sponsor_mapa2").prepend("<div id=\"mapid_sponsor2\"></div>");

  var lat2 = $('#mapa_sponsor_coordenada_lat').val();
  var lng2 = $('#mapa_sponsor_coordenada_lng').val();
  var zoom2 = $('#mapa_sponsor_zoom').val();


  if (zoom2 !== '') {
    $(".popup_sponsor_mapa").css('height', 'unset');
    // Se crea en mapa estatico en el modo PC

    L.mapbox.accessToken = 'pk.eyJ1IjoiZnVzaWxvbjk0IiwiYSI6ImNqb204ODF3NjBob2szcWtrcjgwc3lwZjEifQ.y6VWJLe3kPWhEPX8x0YfvQ';

    var mymap2 = L.map('mapid_sponsor', {zoomControl: false, attributionControl: false, dragging: false, scrollWheelZoom: false, boxZoom: false, doubleClickZoom: false  })
    .addLayer(L.mapbox.styleLayer('mapbox://styles/fusilon94/ckgmmpknk1cxb19pmkrq0duuv', {maxZoom: 18, tileSize: 512, zoomOffset: -1}))
    .setView([lat2, lng2], zoom2);

    if (lat2 !== '' && lng2 !== '') {
      var marker2 = L.marker([lat2, lng2]).addTo(mymap2);
    }else {
    var marker2 = L.marker();
    };


    // Se crea en mapa estatico en el modo MOBILE

    L.mapbox.accessToken = 'pk.eyJ1IjoiZnVzaWxvbjk0IiwiYSI6ImNqb204ODF3NjBob2szcWtrcjgwc3lwZjEifQ.y6VWJLe3kPWhEPX8x0YfvQ';

    var mymap3 = L.map('mapid_sponsor2', {zoomControl: false, attributionControl: false, dragging: false, scrollWheelZoom: false, boxZoom: false, doubleClickZoom: false  })
    .addLayer(L.mapbox.styleLayer('mapbox://styles/fusilon94/ckgmmpknk1cxb19pmkrq0duuv', {maxZoom: 18, tileSize: 512, zoomOffset: -1}))
    .setView([lat2, lng2], zoom2);


    if (lat2 !== '' && lng2 !== '') {
      var marker3 = L.marker([lat2, lng2]).addTo(mymap3);
    }else {
      var marker3 = L.marker();
    };

  };


});


// CODIGO PARA ADJUNTAR A GALERIA LOGOS PREDETERMIANDOS SI NOMBRE EMPRESA YA EXISTENTE EN LA DB, MOSTRAR LOGO O LOGOS QUE TENEMOS##################

 $("#nombre").on("focusout", function(){//al colocar info en la input y apretar afuera (esto evita lanzar el codigo en cada single change that occurs)
   if ($(this).val() !== '') {
   var sponsor_nombre = $(this).val();

     $.ajax({
         type: "POST",
         url: "process-request-sponsor-logo.php",
         data: { sponsor_nombre_sent : sponsor_nombre }
     }).done(function(data){
         $(".existente").remove();
         $(".galeria_logos").css('height', 'unset').prepend(data);
     });

   }else {
      $(".existente").remove();
      if ($(".categoria option:selected").val() == '') {
        $(".galeria_logos").css('height', '3em');
      };
   };
 });

//CODIGO PARA POBLAR GALERIA LOGOS Y LA DE ILLUSTRACIONES CON LOGOS E ILLUSTRACIONES PREDETERMINADAS SEGUN LA CATEGORIA ESCOGIDA#######################################

  $("select.categoria").change(function(){

    var categoriaSelected = $(".categoria option:selected").val();

    if (categoriaSelected !== '') {

      if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
      $(this).parent().children("label").children('i').css('color', 'rgb(68, 235, 54)');
      };

      $.ajax({
          type: "POST",
          url: "process-request-logos-predeterminados.php",
          data: { categoria_sent : categoriaSelected }
      }).done(function(data){
          $(".predeterminado").remove();
          $(".galeria_logos").css('height', 'unset').append(data);
      });

      $.ajax({
          type: "POST",
          url: "process-request-ilustraciones.php",
          data: { categoria_sent : categoriaSelected }
      }).done(function(data){
          $(".galeria_ilustraciones").css('height', 'unset').html(data);
      });

    }else {
        if ($(this).parent().children("label").children('i').length) {//si el DOT de verif existe
        $(this).parent().children("label").children('i').css('color', 'rgb(228, 46, 46)');
        };
      $(".predeterminado").remove();
      $(".galeria_ilustraciones").css('height', '3em').empty();
      if ($("#nombre").val() == '') {
        $(".galeria_logos").css('height', '3em');
      };

    };

  });


// CODIGO DATEPICKER ###########################################

    $( "#fecha_vencimiento" ).datepicker({//esto funciona con jqueryUI a tener en el header del .view
        changeMonth: true,
        changeYear: true,
        minDate: 0,
    });
    $("#fecha_vencimiento").datepicker("option", "dateFormat", "yy/mm/dd");

// ##########################################################################################
// ###############################DRAG AND DROP FEATURES ####################################
// ##########################################################################################

  $(".campo_logo").on('dragenter', function (e){  // lo que pasa cuando drag por encima, y cuando te vas
      $(this).css('border', '3px dashed #007fff');
  });

  $(".campo_logo").on('dragover', function (e){
      e.preventDefault();
      e.stopPropagation();
      $(this).css('border', '1px solid #007fff');
      return false;
  });

  $(".campo_logo").on('dragleave', function(e) {
      e.preventDefault();
      e.stopPropagation();
      $(this).css('border', '3px dashed gray');
  });

  $(".campo_logo").on('drop', function(e) {
    $(this).css('border', '3px dashed gray');
});


  $.uploadPreview({
      input_field: "#logo",   // Default: .image-upload
      preview_box: "#campo_logo",  // Default: .image-preview
      label_field: "#logo_label",    // Default: .image-label
      label_default: "<p>SUBE EL LOGO<br><span>Click or Drop</span></p>",   // Default: Choose File
      label_selected: "Cambia esta imagen",  // Default: Change File
      no_label: false                 // Default: false
  });

  $.uploadPreview({
      input_field: "#logo",   // Default: .image-upload
      preview_box: ".logo_preview",  // Default: .image-preview
      no_label: true                 // Default: false
  });

  $("#logo").on("input", function() {

    if ($("#logo").val() !== '') {
      $("span.logo").css('border', '3px solid rgb(255, 255, 255)');
      $("#galeria_logos_input").val("");
    };

  });

// CODIGO QUE DETECTA CAMBIO EN EL LOGO PREVISUALISACION Y ADAPTA LOS STYLES ###############

  var observer = new MutationObserver(function(mutations) {
      mutations.forEach(function(mutationRecord) {
          if (!$("span.logo_preview").hasClass('filled')) { //si NO tiene la clase "filled"
            if ($("#logo_preview1").css('background-image') !== 'none') {
              //NO class FILLED + IMAGE
                $("span.logo_preview").addClass('filled');
                $("span.logo_preview").css('background-size', 'contain').empty();
                if ($("#contenedor_logo p i").length) {//si el DOT de verif existe
                $("#contenedor_logo p i").css('color', 'rgb(68, 235, 54)');
                };
              } else {
                //NO class FILLED + NO IMAGE
                if ($("#contenedor_logo p i").length) {//si el DOT de verif existe
                $("#contenedor_logo p i").css('color', 'rgb(228, 46, 46)');
                };
              };

          }else { //si SI la tiene

            if ($("#logo_preview1").css('background-image') !== 'none') {
              $("span.logo_preview").css('background-size', 'contain');
              // FILLED + IMAGE
                if ($("#contenedor_logo p i").length) {//si el DOT de verif existe
                $("#contenedor_logo p i").css('color', 'rgb(68, 235, 54)');
                };
              } else {
                //FILLED + NO IMAGE

                if ($("#contenedor_logo p i").length) {//si el DOT de verif existe
                $("#contenedor_logo p i").css('color', 'rgb(228, 46, 46)');
                };
              };

          };
      });
  });

  var target = document.getElementById('logo_preview1');
  observer.observe(target, { attributes : true, attributeFilter : ['style'] });//crea un eventlistener para chequear cambios en estilos de un elemento (llamar al elemento y no al objeto!!!!)


// CODIGO PARA CAMBIAR DINAMICAMENTE EL COLOR DEL BORDE DE LAS PREVISUALISACIONES ##################

  $(".galeria_colores").on("click", "span.color_borde", function(){
      var color_picked = $(this).css("background-color");

      $(".popup_sponsor").css("background-color", color_picked);//se carga el borde a las previsualizaciones
      $(".popup_sponsor2").css("background-color", color_picked);
      $("span.color_borde").css('border', '3px solid rgb(255, 255, 255)');//se reinicializa el borde de los thumbs
      $(this).css('border', '3px solid rgb(153, 153, 152)');//se marca como elegido solo al que se le dio click
      $("#galeria_colores_input").val(color_picked);//se guarda el valor clickeado en el input hidden

  });

// CODIGO PARA CARGAR LOGO GENERICO EN LAS PREVISUALISACIONES DE POPUP ###############################

  $(".galeria_logos").on("click", "span.logo", function(){
      $("#logo").val('').trigger('change');
      $(".campo_logo label p").css('background-color', '#aaa8a8');
      $(".campo_logo label span").css('color', '#007fff');
      var logo_picked = $(this).children("img").attr('src');

      $(".logo_preview").css('background-image', 'url(' + logo_picked + ')');
      $("span.logo").css('border', '3px solid rgb(255, 255, 255)');//se reinicializa el borde de los thumbs
      $(this).css('border', '3px solid rgb(153, 153, 152)');//se marca como elegido solo al que se le dio click
      $("#galeria_logos_input").val(logo_picked);//se guarda el valor clickeado en el input hidden
  });

// CODIGO PARA CARGAR ILUSTRACIONES EN LAS PREVISUALISACIONES DE POPUP ###############################

  $(".galeria_ilustraciones").on("click", "span.ilustracion", function(){
      var ilustracion_picked = $(this).children("img").attr('src');

      $(".popup_sponsor_illustration").css('background-image', 'url(' + ilustracion_picked + ')');
      $(".popup_sponsor_illustration2").css('background-image', 'url(' + ilustracion_picked + ')');
      $("span.ilustracion").css('border', '3px solid rgb(255, 255, 255)');//se reinicializa el borde de los thumbs
      $(this).css('border', '3px solid rgb(153, 153, 152)');//se marca como elegido solo al que se le dio click
      $("#galeria_ilustraciones_input").val(ilustracion_picked);//se guarda el valor clickeado en el input hidden

      if ($(".galeria_ilustraciones_contenedor p i").length) {//si el DOT de verif existe
      $(".galeria_ilustraciones_contenedor p i").css('color', 'rgb(68, 235, 54)');
      };
  });

// CODIGO PARA CERRAR EL POPUP DE ERRORES##########################################################################

  $(".popup_errores_cerrar").on("click", function(){
    $(".popup_errores").css('visibility', 'hidden');
  });

// CODIGO QUE CHECKEA QUE EL NEGOCIO NO TENGA YA UNA SUCURSAL EN EL MISMO BARRIO ##################################

  function check_for_existing_sponsor(param2){
      var labelSelected = $("#nombre").val();
      var barrioSelected = $(".barrio option:selected").val();
      var modo_borrador_edicion = $("#modo_borrador_edicion").val();
      if (modo_borrador_edicion == '' || modo_borrador_edicion == 'modo_borrador'){//SI SE ESTA EN MODO NORMAL ENTONCES SE TIENE QUE VERIFICAR SI EXISTEN SUCURSALES EN EL MISMO BARRIO

            if (barrioSelected === undefined) {//significa que se escogio una ciudad de tipo poblado
              var ciudadSelected = $(".ciudad option:selected").val();

              $.ajax({
                  type: "POST",
                  url: "process-request-sponsor-existance.php",
                  data: { ciudad_sent : ciudadSelected, label_sent : labelSelected},
                  dataType: 'json'//SE ESPECIFICA QUE SE ESPERA DATA EN MODO JSON
              }).done(function(data){
                var direcciones_array = data;
                if (Object.entries(direcciones_array).length > 0) {

                  $('.overlay_popup_verificacion_nueva_sucursal').css('display', 'flex');

                  $('.popup_verificacion_nueva_sucursal p.popup_sucursal_texto').html('<i class="fa fa-exclamation-circle"></i>Sucrusales detectadas en ' + ciudadSelected);

                  $('.popup_verificacion_nueva_sucursal div.popup_sucursal_lista').empty();
                  direcciones_array.forEach(function(element){
                    $('.popup_verificacion_nueva_sucursal div.popup_sucursal_lista').append("<span class='elemento_direccion'>" + element['direccion'] + ' - ' + (element['visibilidad'] == 'visible' ? '<span style=\'color: #7cb342e0\'>Activo</span>': '<span style=\'color: #9e9e9ee0\'>Inactivo</span>') + "</span>");
                  });

                  $('.popup_verificacion_nueva_sucursal p.popup_sucursal_pregunta').html('¿Desea crear una nueva sucursal?');

                  $('.btn_crear_nueva_sucursal span').html('Crear nueva Sucursal');

                }else {
                  if (param2 == 'registrar') {//estamos tratando de registrar un sponsor y hay que mostrar la advertencia sobre datos inalterables

                    $('.overlay_popup_verificacion_nueva_sucursal').css('display', 'flex');

                    $('.popup_verificacion_nueva_sucursal p.popup_sucursal_texto').html('<i class="fa fa-exclamation-circle"></i>- Aviso Importante -<br> Al registrar los datos siguientes <b>NO</b> podran ser modificados:');

                    $('.popup_verificacion_nueva_sucursal div.popup_sucursal_lista').empty().html("Nombre Empresa, Dirección, Logo, Info del Responsable, Fecha de vencimiento");

                    $('.popup_verificacion_nueva_sucursal p.popup_sucursal_pregunta').html('¿Desea continuar con el registro?');

                    $('.btn_crear_nueva_sucursal span').html('Registrar');

                  }else {//si no estamos guardando un borrador
                    $("#formulario_registro_sponsor").submit();
                  };

                };

              });
            }else {//se escogio un barrio , la verificacion de barrio vacio si hizo antes de llamar a esta funcion

                $.ajax({
                    type: "POST",
                    url: "process-request-sponsor-existance.php",
                    data: { barrio_sent : barrioSelected, label_sent : labelSelected },
                    dataType: 'json'//SE ESPECIFICA QUE SE ESPERA DATA EN MODO JSON
                }).done(function(data){
                  var direcciones_array = data;
                  if (Object.entries(direcciones_array).length > 0) {//si no es un array vacio

                      $('.overlay_popup_verificacion_nueva_sucursal').css('display', 'flex');

                      $('.popup_verificacion_nueva_sucursal p.popup_sucursal_texto').html('<i class="fa fa-exclamation-circle"></i>Sucrusales detectadas en ' + barrioSelected);

                      $('.popup_verificacion_nueva_sucursal div.popup_sucursal_lista').empty();
                      direcciones_array.forEach(function(element){
                        $('.popup_verificacion_nueva_sucursal div.popup_sucursal_lista').append("<span class='elemento_direccion'>" + element['direccion'] + ' - ' + (element['visibilidad'] == 'visible' ? '<span style=\'color: #7cb342e0\'>Activo</span>': '<span style=\'color: #9e9e9ee0\'>Inactivo</span>') + "</span>");
                      });

                      $('.popup_verificacion_nueva_sucursal p.popup_sucursal_pregunta').html('¿Desea crear una nueva sucursal?');

                      $('.btn_crear_nueva_sucursal span').html('Crear nueva Sucursal');

                  }else {
                    if (param2 == 'registrar') {//estamos tratando de registrar un sponsor y hay que mostrar la advertencia sobre datos inalterables

                      $('.overlay_popup_verificacion_nueva_sucursal').css('display', 'flex');

                      $('.popup_verificacion_nueva_sucursal p.popup_sucursal_texto').html('<i class="fa fa-exclamation-circle"></i>- Aviso Importante -<br> Al registrar los datos siguientes <b>NO</b> podran ser modificados:');

                      $('.popup_verificacion_nueva_sucursal div.popup_sucursal_lista').empty().html("Nombre Empresa, Dirección, Logo, Info del Responsable, Fecha de vencimiento");

                      $('.popup_verificacion_nueva_sucursal p.popup_sucursal_pregunta').html('¿Desea continuar con el registro?');

                      $('.btn_crear_nueva_sucursal span').html('Registrar');

                    }else {//si no estamos guardando un borrador
                      $("#formulario_registro_sponsor").submit();
                    };
                  };

                });

            };

      }else {//para modo editor
        $("#formulario_registro_sponsor").submit();
      };


  };

// CODIGO PARA ESCUCHAR SI SE DECIDE O NO CREAR UNA SEGUNDA O MAS SUCURSALES DEL MISMO ESPONSOR EN ESE BARRIO ###################

    $(".btn_cancelar").on("click", function(){
      $("#boton_validar_form").css('display', 'flex');
      $("#boton_submit_form").css('display', 'none');
      $('.overlay_popup_verificacion_nueva_sucursal').css('display', 'none');
    });

    $(".btn_crear_nueva_sucursal").on("click", function(){
      if ($('.btn_crear_nueva_sucursal span').html() == 'Registrar') {//si se decide registrar entonces enviar el formulario
        $("#formulario_registro_sponsor").submit();
      };
      if ($('.btn_crear_nueva_sucursal span').html() == 'Crear nueva Sucursal') {//si se decide crear sucursal nueva, luego mostar aviso siguiente
        if($("#boton_form_input").val() == "borrador"){
            $("#formulario_registro_sponsor").submit();
        }else {
            $('.overlay_popup_verificacion_nueva_sucursal').css('display', 'none');
            $('.overlay_popup_verificacion_nueva_sucursal').css('display', 'flex');
            $('.popup_verificacion_nueva_sucursal p.popup_sucursal_texto').html('<i class="fa fa-exclamation-circle"></i>- Aviso Importante -<br> Al registrar los datos siguientes no podran ser modificados:');

            $('.popup_verificacion_nueva_sucursal div.popup_sucursal_lista').empty().html("Nombre Empresa, Dirección, Logo, Info del Responsable, Fecha de vencimiento");

            $('.popup_verificacion_nueva_sucursal p.popup_sucursal_pregunta').html('¿Desea continuar con el registro?');

            $('.btn_crear_nueva_sucursal span').html('Registrar');
        };

      };

    });

// CODIGO QUE CONTROLA LOS DATOS DESPUES DE PRESIONAR "GUARDAR BORRADOR" o "VALIDAR DATOS" o "REGISTRAR DATOS"###################
  function check_empty_inputs(param){
        var errores = '';

        $(".input_obligatorio").each(function(){//CHECK si no existen campos mal rellenados
          var input_chars_filter = $(this).css('border-color');

          if (input_chars_filter == 'rgb(255, 0, 0)') {
            errores = "error";

          };

        });

        if ($('#modo_borrador_edicion').val() == 'modo_editor') {
          $(".edit_check").each(function() {//CHECK si todos los campos obligatorios contienen valores
            var input_val = $(this).val();
            if (input_val == '') {
              errores = "error";
              if ($(this).parent().children("label").children('i').length == 0) {//si el DOT de verif NO existe
                $(this).parent().children("label").prepend("<i class='fa fa-circle'></i>");
              };
            };

          });
        }else {
          $(".input_obligatorio").each(function() {//CHECK si todos los campos obligatorios contienen valores
            var input_val = $(this).val();
            if (input_val == '') {
              errores = "error";
              if ($(this).parent().children("label").children('i').length == 0) {//si el DOT de verif NO existe
                $(this).parent().children("label").prepend("<i class='fa fa-circle'></i>");
              };
            };

          });
        };



        if ($(".ciudad option:selected").val() === undefined) {//CHECK si se ingreso ciudad - Requisito Min para ingresas borrador - undefine si aun no se pobló ese select
          errores = "error";
          if ($("#ciudad").parent().children("label").children("i").length == 0) {//si el DOT de verif NO existe
            $("#ciudad").parent().children('label').prepend("<i class='fa fa-circle'></i>");
          };


        }else {// si se pobló ese select
          if ($(".ciudad option:selected").val() == '') {// pero de escogió valor vacio
            errores = "error";
            if ($("#ciudad").parent().children("label").children("i").length == 0) {//si el DOT de verif NO existe
              $("#ciudad").parent().children('label').prepend("<i class='fa fa-circle'></i>");
            };
          };
        };


        if ($(".barrio option:selected").val() === undefined) {// si undefined entonces el select barrios NO tiene opciones, por ende se escogio una ciudad de tipo poblado
          // TODO ok
        }else {//el selesct barrios tiene opciones
          if ($(".barrio option:selected").val() == '') {//pero se escogio la opcion vacía
            errores = "error";
            if ($("#barrio").parent().children("label").children("i").length == 0) {//si el DOT de verif NO existe
              $("#barrio").parent().children('label').prepend("<i class='fa fa-circle'></i>");
            };

          };
        };


        $(".input_mapa_obligatorio").each(function() {//CHECK si los input mapa contiene valores
          var input_mapa_val = $(this).val();
          if (input_mapa_val == '') {
            errores = "error";
            if ($(".popup_sponsor_mapa_config p i").length == 0) {//si el DOT de verif NO existe
              $(".popup_sponsor_mapa_config p").prepend("<i class='fa fa-circle'></i>");
            };
          };
        });


        if ($("#logo_preview1").css('background-image') == 'none') {//CHECK si se ingreso un logo, viendo si exite ne las previsualizaciones
          errores = "error";
          if ($("#contenedor_logo").children("p").children("i").length == 0) {//si el DOT de verif NO existe
            $("#contenedor_logo").children("p").prepend("<i class='fa fa-circle'></i>");
          };
        };



        if ($(".input_ilustracion_obligatorio").val() == '') {//CHECK si se eligio una ilustracion
          errores = "error";
          if ($(".galeria_ilustraciones_contenedor p i").length == 0) {//si el DOT de verif NO existe
            $(".galeria_ilustraciones_contenedor p").prepend("<i class='fa fa-circle'></i>");
          };
        };



        if (errores !== '') {//LAST CHECK - si durante los check hubo reporte de errores
          $(".popup_errores").css('visibility', 'visible');
          $("#boton_validar_form").css('display', 'flex');
          $("#boton_submit_form").css('display', 'none');
        }else {//SI NO HUBO NINGUN ERROR
          $("#boton_validar_form").css('display', 'none');
          $("#boton_submit_form").css('display', 'flex');
          if (param == 'registrar') {//si se hizo click en registrar y no hay errores submit el form
            var param2 = 'registrar';
            check_for_existing_sponsor(param2);
          };
        };
  };

  function check_for_borrador(){

    var errores = '';

    $(".input_obligatorio").each(function(){
      var input_chars_filter = $(this).css('border-color');

      if (input_chars_filter == 'rgb(255, 0, 0)') {//CHECK si no existen campos mal rellenados
        errores = "error";
      };

    });

    if ($("#nombre").val() === '') {//CHECK si el nombre no esta vacio - Requisito minimo para ingresar borrador
      errores = "error";
      if ($("#nombre").parent().children("label").children("i").length == 0) {//si el DOT de verif NO existe
        $("#nombre").parent().children('label').prepend("<i class='fa fa-circle'></i>");
      };

    };

    if ($("#direccion").val() === '') {//CHECK si el nombre no esta vacio - Requisito minimo para ingresar borrador
      errores = "error";
      if ($("#direccion").parent().children("label").children("i").length == 0) {//si el DOT de verif NO existe
        $("#direccion").parent().children('label').prepend("<i class='fa fa-circle'></i>");
      };

    };

    if ($(".ciudad option:selected").val() === undefined) {//CHECK si se ingreso ciudad - Requisito Min para ingresas borrador - undefine si aun no se pobló ese select
      errores = "error";
      if ($("#ciudad").parent().children("label").children("i").length == 0) {//si el DOT de verif NO existe
        $("#ciudad").parent().children('label').prepend("<i class='fa fa-circle'></i>");
      };

    }else {// si se pobló ese select
      if ($(".ciudad option:selected").val() == '') {// pero de escogió valor vacio
        errores = "error";
        if ($("#ciudad").parent().children("label").children("i").length == 0) {//si el DOT de verif NO existe
          $("#ciudad").parent().children('label').prepend("<i class='fa fa-circle'></i>");
        };
      };
    };

    if ($(".barrio option:selected").val() === undefined) {// si undefined entonces el select barrios NO tiene opciones, por ende se escogio una ciudad de tipo poblado
      // TODO ok
    }else {//el selesct barrios tiene opciones
      if ($(".barrio option:selected").val() == '') {//pero se escogio la opcion vacía
        errores = "error";
        if ($("#barrio").parent().children("label").children("i").length == 0) {//si el DOT de verif NO existe
          $("#barrio").parent().children('label').prepend("<i class='fa fa-circle'></i>");
        };

      };
    };

    if (errores !== '') {//LAST CHECK - si durante los check hubo reporte de errores
      $(".popup_errores").css('visibility', 'visible');
    }else {//si no hubo errores sumbit este form
      check_for_existing_sponsor("nothing in param");
    };

  };


  $("#boton_fin_formulario_contenedor").on("click", "button.boton_fin_formulario", function(){
    var button_pushed = $(this).val();

      if (button_pushed == 'validar_datos') {// si se apretó en Validar datos
        check_empty_inputs('validar');//lanzar el chequeo total
      };

      if (button_pushed == 'registar_datos') {// si se apretó en Registrar Sponsor
        if($('#modo_borrador_edicion').val() == 'modo_editor'){
          $("#boton_form_input").val("editar");//guardar que boton se seleciono en un input hidden
        }else {
          $("#boton_form_input").val("registrar");//guardar que boton se seleciono en un input hidden
        };

        check_empty_inputs('registrar');//lanzar el chequeo total
      };

      if (button_pushed == 'guardar_borrador') {// si se apretó en Borrador
        $("#boton_form_input").val("borrador");//guardar que boton se seleciono en un input hidden
        check_for_borrador();//lanzar el chequeo parcial
      };


  });





  });
});
