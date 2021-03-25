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

  $(".popup_acordion").accordion({
    heightStyle: "fill",
    collapsible: true,
    active: false,
  });

  $('.popup_activacion_cerrar i.fa-times').on("click", function(){
    $('.popup_activacion').css('visibility',  'hidden');
  });


  $(".boton_borrador_formulario_borrar").on("click", function(){
    var parent = $(this).parent().find("div.boton_borrador_formulario_borrar_confirmar");
    var trashicon = $(this).find("i.fas");

    $(trashicon).toggleClass("fa-power-off fa-times");

    if ($(parent).is(":hidden")) {
      $(parent).show("slide", { direction: "left" }, 800);
    } else {
      $(parent).hide("slide", { direction: "left" }, 800);
    };
  });


  $(".boton_borrador_formulario_borrar_confirmar").on("click", function(){
    var formulario_referencia = $(this).parent().find('.boton_borrador_formulario').attr('id');
    var formulario_tabla = $(this).parent().find('.boton_borrador_formulario').attr('name');
    var container = $(this).parent();
    var pais = $(this).parent().find('.boton_borrador_formulario').attr('data_pais');

    $('.opcion_activar').html('<div class="parametro_individual"><label for="atractivo_bien">Atractivo del Bien Inmueble (0-100)</label><input id="atractivo_bien" value="50" type="text" autocomplete="off" readonly="readonly" name="atractivo_bien" class="input_obligatorio_spinner"></div><div class="elemento_formulario"><div class="tutecho_bien_btn">Bien de la Empresa?</div><input type="hidden" id="tutecho_bien_btn" name="tutecho_bien_btn" value="0"></div><div class="activar_btn">Activar Bien Inmueble</div><input type="hidden" id="referencia_clicked" name="referencia_clicked" value="' + formulario_referencia + '"><input type="hidden" id="tabla" name="tabla" value="' + formulario_tabla + '"><input type="hidden" id="pais" name="pais" value="' + pais + '">');

    $('.opcion_reportar_error').html('<h3 style="text-align: center">Reclamo:</h3><textarea id="reclamo_texto" style="width: 100%; display: table" name="reclamo_texto" rows="8" cols="80" placeholder="Escriba y detalle los errores detectados y a quien corresponde arreglarlos"></textarea><h3 style="text-align: center">A que personas hacer el reclamo?</h3><div class="lista_btn"><div class="reporte_btn reporte_registrador">Registrador</div><input type="hidden" id="reporte_registrador" name="reporte_registrador" value="0"><div class="reporte_btn reporte_fotografo">Fotógrafo</div><input type="hidden" id="reporte_fotografo" name="reporte_fotografo" value="0"><div class="reporte_btn reporte_creador_vr">Creador VR</div><input type="hidden" id="reporte_creador_vr" name="reporte_creador_vr" value="0"></div><div class="reportar_btn">Enviar Reclamo</div>');

    $("#atractivo_bien").spinner({
      min: 0,
      max: 100,
      step: 5
    });

    $('.popup_activacion').css('visibility',  'unset');

  });

// CODIGO PARA EL BOTON "BIEN DE TUTECHO" y otros botones #######################################################

  $(".popup_activacion").on("click", ".tutecho_bien_btn", function(){
    $(".tutecho_bien_btn").toggleClass('active');
    if ($(this).hasClass('active')) {
      $("#tutecho_bien_btn").attr('value', '1');
    }else {
      $("#tutecho_bien_btn").attr('value', '0') ;
    }

  });

  $(".popup_activacion").on("click", ".reporte_registrador", function(){
    $(".reporte_registrador").toggleClass('active');
    if ($(this).hasClass('active')) {
      $("#reporte_registrador").attr('value', '1');
    }else {
      $("#reporte_registrador").attr('value', '0') ;
    }

  });

  $(".popup_activacion").on("click", ".reporte_fotografo", function(){
    $(".reporte_fotografo").toggleClass('active');
    if ($(this).hasClass('active')) {
      $("#reporte_fotografo").attr('value', '1');
    }else {
      $("#reporte_fotografo").attr('value', '0') ;
    }

  });

  $(".popup_activacion").on("click", ".reporte_creador_vr", function(){
    $(".reporte_creador_vr").toggleClass('active');
    if ($(this).hasClass('active')) {
      $("#reporte_creador_vr").attr('value', '1');
    }else {
      $("#reporte_creador_vr").attr('value', '0') ;
    }

  });

// CODIGO BTN ACTIVAR BIEN INMUEBLE CONFIRMAR ##############################################################

  $(".opcion_activar").on("click", ".activar_btn", function(){
    if ($("#atractivo_bien").val() > 0 && $("#atractivo_bien").val() <= 100 && $("#atractivo_bien").val() !== '') {
      var atractivo = $("#atractivo_bien").val();
      var bien_tutecho = $("#tutecho_bien_btn").val();
      var referencia = $("#referencia_clicked").val();
      var tabla = $("#tabla").val();
      var lista_botones = $("div[name='" + referencia + "']");
      var pais = $("#pais").val();

        $.ajax({
              type: "POST",
              url: "process-request-activar_bien_o_enviar_reclamo.php",
              data: { atractivo_sent : atractivo, bien_tutecho_sent : bien_tutecho, referencia_sent : referencia, tabla_sent : tabla, pais_sent : pais },
          }).done(function(data){
            $(".popup_success_text").html(data);
            $('.popup_activacion').css('visibility',  'hidden');
            $(".popup_success").css('visibility', 'unset');
            lista_botones.css('display', 'none');
          });

    }else {
      alert("Tiene que ingresar un valor para el atractivo del Bien Inmueble");
    };
  });

// CODIGO BTN ENVIAR RECLAMO ##############################################################

  $(".opcion_reportar_error").on("click", ".reportar_btn", function(){
    if ($("#reclamo_texto").val() !== '') {
      if ($("#reporte_registrador").val() == "1" || $("#reporte_fotografo").val() == "1" || $("#reporte_creador_vr").val()) {
        var reclamo = $("#reclamo_texto").val();
        var destino_registrador = $("#reporte_registrador").val();
        var destino_fotografo = $("#reporte_fotografo").val();
        var destino_creador_vr = $("#reporte_creador_vr").val();
        var referencia = $("#referencia_clicked").val();
        var tabla = $("#tabla").val();
        var lista_botones = $("div[name='" + referencia + "']");
        var pais = $("#pais").val();

        $.ajax({
              type: "POST",
              url: "process-request-activar_bien_o_enviar_reclamo.php",
              data: { reclamo_sent : reclamo, destino_registrador_sent : destino_registrador, destino_fotografo_sent : destino_fotografo, destino_creador_vr_sent : destino_creador_vr, referencia_sent : referencia, tabla_sent : tabla, pais_sent : pais },
          }).done(function(data){
            $(".popup_success_text").html(data);
            $('.popup_activacion').css('visibility',  'hidden');
            $(".popup_success").css('visibility', 'unset');
            lista_botones.css('display', 'none');
          });

      }else {
       alert("Tiene que ingresar un Reclamo y almenos un destinatario");
      };
    }else {
      alert("Tiene que ingresar un Reclamo y almenos un destinatario");
    };
  });

// CODIGO POPUP SUCCESS ####################################################################################

  $('.popup_success_cerrar i.fa-times').on("click", function(){
    $('.popup_success').css('visibility',  'hidden');
  });

// CODIGO PARA TRAER LA FICHA BIEN DESPUES DE HACER CLICK EN UN THUMBNAIL BIEN INMUEBLE ####################################
  $('.contenedor_borradores').on('click', '.boton_borrador_formulario', function(){
      $('.ficha_bien_container').addClass('active');
      var ficha_bien_clicked_referencia = $(this).attr('id');
      var estado = $(this).attr('name');
      var ficha_bien_tipo;
      var pais = $(this).attr('data_pais');

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
            url: "process-request-popup_ficha_bien_activar.php",
            data: { ficha_bien_requested : ficha_bien_clicked_referencia, ficha_bien_tipo_requested : ficha_bien_tipo, estado : estado, pais_sent : pais },
        }).done(function(data){
          $('.popup_ficha_bien').html(data);
          $("body").addClass('ficha_active');
          });

  });

// CODIGO PARA LOS TABS ################################################################################################

  $(".contenedor_borradores").on("click", ".elemento_tab", function(){
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
