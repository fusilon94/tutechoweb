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

// CODIGO POPUP ACTIVACION ACORDION #######################################################################

  $('.popup_activacion_cerrar i.fa-times').on("click", function(){
    $('.popup_activacion').css('visibility',  'hidden');
  });

  $('.resultados_sponsors').on("click", ".boton_formulario_borrar_confirmar", function(){
    var formulario_referencia = $(this).parent().find('.boton_borrador_formulario').attr('id');
    var formulario_tabla = $(this).parent().find('.boton_borrador_formulario').attr('name');

    $(".botones_choices").html('<span class="boton_choice btn_contrato">Contrato<input type="hidden" id="contrato_input" name="contrato_input" value="0"></span><span class="boton_choice btn_formulario">Formulario<input type="hidden" id="formulario_input" name="formulario_input" value="0"></span><span class="boton_choice btn_fotografias">Fotografias<input type="hidden" id="fotografias_input" name="fotografias_input" value="0"></span><span class="boton_choice btn_tour_vr">Tour VR<input type="hidden" id="tour_vr_input" name="tour_vr_input" value="0"></span><input type="hidden" id="referencia_picked" name="referencia_picked" value="' + formulario_referencia + '"><input type="hidden" id="tabla_picked" name="tabla_picked" value="' + formulario_tabla + '">')

    $('.popup_activacion').css('visibility',  'unset');

  });

// CODIGO PARA EL BOTON "BIEN DE TUTECHO" y otros botones #######################################################

  $(".popup_activacion").on("click", ".boton_choice", function(){
    $(this).toggleClass('active');
    if ($(this).hasClass('active')) {
      $(this).find('input').attr('value', '1');
    }else {
      $(this).find('input').attr('value', '0') ;
    }
  });

  $(".popup_activacion").on("click", ".btn_autorizar", function(){
    var contrato_input = $("#contrato_input").val();
    var formulario_input = $("#formulario_input").val();
    var fotografias_input = $("#fotografias_input").val();
    var tour_vr_input = $("#tour_vr_input").val();
    var referencia_bien = $("#referencia_picked").val();
    var tabla_bien = $("#tabla_picked").val();

    if (contrato_input == 1 || formulario_input == 1 || fotografias_input == 1 || tour_vr_input == 1) {

        $.ajax({
              type: "POST",
              url: "process-request-autorizar_edicion_all.php",
              data: { contrato_sent : contrato_input, formulario_sent : formulario_input, fotografias_sent : fotografias_input, tour_vr_sent : tour_vr_input, referencia_autorizar_sent : referencia_bien, tabla_sent : tabla_bien },
          }).done(function(data){
            $(".popup_success_text").html(data);
            $(".popup_success").css('visibility', 'unset');
            $('.popup_activacion').css('visibility',  'hidden');
          });

    }else {
        alert('Debe selecionar almenos una opcion');
    };

  });

// CODIGO POPUP SUCCESS ####################################################################################

  $('.popup_success_cerrar i.fa-times').on("click", function(){
    $('.popup_success').css('visibility',  'hidden');
  });

// CODIGO PARA TRAER LA FICHA BIEN DESPUES DE HACER CLICK EN UN THUMBNAIL BIEN INMUEBLE ####################################
  $('.resultados_sponsors').on('click', '.boton_borrador_formulario', function(){
      $('.ficha_bien_container').addClass('active');
      var ficha_bien_clicked_referencia = $(this).attr('id');
      var estado = $(this).attr('name');
      var ficha_bien_tipo;

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
            url: "process-request-popup_ficha_bien_autorizar_edicion.php",
            data: { ficha_bien_requested : ficha_bien_clicked_referencia, ficha_bien_tipo_requested : ficha_bien_tipo, estado : estado },
        }).done(function(data){
          $('.popup_ficha_bien').html(data);
          $("body").addClass('ficha_active');
          });

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

            var mymap_ficha_bien = L.map('mapa_ficha_contenedor', {scrollWheelZoom: false })
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
