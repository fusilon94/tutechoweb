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

// CODIGO PARA TRAER LA FICHA BIEN DESPUES DE HACER CLICK EN UN THUMBNAIL BIEN INMUEBLE ####################################
  if(window.location.hash) {

        var referencia = window.location.hash;
        var ficha_bien_tipo;

        if (referencia.includes("C")) {
          ficha_bien_tipo = "casa";
        } else {
          if (referencia.includes("D")) {
            ficha_bien_tipo = "departamento";
          } else {
            if (referencia.includes("L")) {
              ficha_bien_tipo = "local";
            } else {
              if (referencia.includes("T")) {
                ficha_bien_tipo = "terreno";
              };
            };
          };
        };

        $.ajax({
              type: "POST",
              url: "process-request-ficha_bien_individual.php",
              data: { ficha_bien_requested : referencia, ficha_bien_tipo_requested : ficha_bien_tipo},
          }).done(function(data){
            $('.popup_ficha_bien').html(data);
            });

  } else {
    alert("no hay");
  };

  // CODIGO PARA EL CIERRE DEL POPUP FICHA BIEN###########################################################################

    $('.ficha_bien_container').on('click', 'span.puerta_exit.actions_type2_icon', function(){//controla el boton de cierre
      window.location.href = "../../index.php";
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

      if ($(".resumen_exclusivo_tag").css('display') == 'block') {

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

      }else {

            var lat_offset = parseFloat(lat)+0.001000;
            var lng_offset = parseFloat(lng)+0.001000;
            var zoom_offset = parseFloat(zoom)-0.5;

            $('.banner_ver_mapa').remove();
            $('.mapa_ficha_contenedor').css('height', '30em');

            L.mapbox.accessToken = 'pk.eyJ1IjoiZnVzaWxvbjk0IiwiYSI6ImNqb204ODF3NjBob2szcWtrcjgwc3lwZjEifQ.y6VWJLe3kPWhEPX8x0YfvQ';

            var mymap_ficha_bien = L.map('mapa_ficha_contenedor', {scrollWheelZoom: false })
            .addLayer(L.mapbox.styleLayer('mapbox://styles/fusilon94/ckgmmpknk1cxb19pmkrq0duuv', {maxZoom: 18, tileSize: 512, zoomOffset: -1}))
            .setView([lat_offset, lng_offset], zoom_offset);

            var circle = L.circle([lat_offset, lng_offset], {
                  color: '#0984CC',
                  fillColor: '#0984CC',
                  fillOpacity: 0.5,
                  radius: 250
              }).addTo(mymap_ficha_bien);

            circle.bindPopup("<b>Dirección Aproximada</b><br>Bien Inmueble NO Exclusivo");
      };
  });



// CODIGO PARA LA APARICION DEL POPUPSPONSOR AL HACER CLICK EN UN SPONSOR#######################################
  $('.ficha_bien_container').on('click', '.elemento_sponsor', function(){
    var nombre_sponsor = $(this).children("input").val();
    var sponsor = $(this);

    $("#popup_sponsor_overlay").css('display', 'flex');

    $.ajax({
          type: "POST",
          url: "process-request-popup_sponsor.php",
          data: { nombre_sponsor_sent : nombre_sponsor },
      }).done(function(data){
        sponsor.append(data);
        });
    }
  );

//CODIGO PARA CERRAR EL POPUP SPONSOR (CLICK AFUERA O CLICK EN CERRAR) #################################################
  $('.ficha_bien_container').on('click', '#popup_sponsor_overlay' ,function(){

    $(".popup_sponsor").remove();
    $('#popup_sponsor_overlay').css('display', 'none')

  });

  $('.ficha_bien_container').on('click', 'span.popup_sponsor_cerrar' ,function(){

    $(".popup_sponsor").remove();
    $('#popup_sponsor_overlay').css('display', 'none')

  });

//CODIGO QUE EVITA QUE SE ABRAN MAS POPUP SPONSORS AL HACER CLICK EN EL MISMO YA QUE ES UN ELEMENTO CHILD DEL SPONSOR
  $('.ficha_bien_container').on('click', '.popup_sponsor' ,function(e){
    if($(e.target).not('span.popup_sponsor_cerrar')){
          e.stopPropagation();//evita que active el click event de su contenedor, la elemento sponsor de la ficha bien
        }
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
