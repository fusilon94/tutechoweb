$(document).ready(function(){
  jQuery(function($){

// Envia datos de busqueda perzonalizada y actualiza los thumbnails para casas
    busqueda_casa_sent = function(oObject, page_requested_received, price_sorting_received){
              var tipo_bien_clicked = $(oObject).attr('name');
              var departamento_clicked = $("#departamento_busqueda_casa option:selected").val();
              var ciudad_clicked = $("#ciudad_busqueda_casa option:selected").val();
              var superficie_clicked = $("#superficie_busqueda_casa").val();
              var precio_clicked;
              if (estado == 'En Venta') {
                if ($("#precio_busqueda_casa").val() >= (Math.round(2000000*cambio))) {
                  precio_clicked = 100000000;
                }else {
                  precio_clicked = $("#precio_busqueda_casa").val();
                };
              };
              if (estado == 'En Alquiler') {
                if ($("#precio_busqueda_casa").val() >= (Math.round(2000*cambio))) {
                  precio_clicked = 100000000;
                }else {
                  precio_clicked = $("#precio_busqueda_casa").val();
                };
              };
              var dormitorios_clicked = $("input[name='radio_casa-1']:checked").val();
              var parqueos_clicked = $("input[name='radio_casa-2']:checked").val();
              if (estado == 'En Venta') {
                  if (precio_clicked <= (Math.round(2000000*cambio))) {
                    var precio_pin = "< "+(parseInt(precio_clicked).toLocaleString('fr-FR'));
                    var precio_pin_amplitud = " " + moneda;
                  };
                  if (precio_clicked > (Math.round(2000000*cambio))) {
                    var precio_pin = "> "+(parseInt((Math.round(2000000*cambio))).toLocaleString('fr-FR'));
                    var precio_pin_amplitud = " " + moneda;
                  };
              };

              if (estado == 'En Alquiler') {
                if (precio_clicked <= (Math.round(2000*cambio))) {
                  var precio_pin = "< "+(parseInt(precio_clicked).toLocaleString('fr-FR'));
                  var precio_pin_amplitud = " " + moneda;
                };
                if (precio_clicked >= (Math.round(2000*cambio))) {
                  var precio_pin = "> "+(parseInt((Math.round(2000*cambio))).toLocaleString('fr-FR'));
                  var precio_pin_amplitud = " " + moneda;
                };
              };


              $.ajax({
                  type: "POST",
                  url: "process-request-inmuebles-thumbnails-busqueda-venta.php",
                  data: { tipo_bien_selected : tipo_bien_clicked, departamento_busqueda : departamento_clicked, ciudad_busqueda : ciudad_clicked, superficie_busqueda : superficie_clicked, precio_busqueda : precio_clicked, dormitorios_busqueda : dormitorios_clicked, parqueos_busqueda : parqueos_clicked, page_requested : page_requested_received, price_sorting : price_sorting_received, estado : estado }
              }).done(function(data){
                  $("#tab1").html(data);
              });

              if ($('#configuracion_busqueda_Toggle').css('display') !== 'none') { //CIERRA EL PANEL DE BUSQUEDA AVANZADA SI ESTA ABIERTO
                $('ul.lista_tabs li.tab_gear a').click();
              };


              $('#texto_resultado_busqueda').html('<p class="titulo_texto_resultado">Resultado de búsqueda:</p><div class="pin_resultado_busqueda">'+departamento_clicked+'</div><div class="pin_resultado_busqueda">'+ciudad_clicked+'</div><div class="pin_resultado_busqueda"> > '+superficie_clicked+' m<sup>2</sup></div><div class="pin_resultado_busqueda">'+precio_pin+precio_pin_amplitud+'</div><div class="pin_resultado_busqueda"><span class="fa fa-bed">&nbsp</span> x'+dormitorios_clicked+'</div><div class="pin_resultado_busqueda"><span class="fa fa-car">&nbsp</span> x'+parqueos_clicked+'</div><div class="pin_resultado_busqueda_editar" name="casa"><span class="far fa fa-edit" onclick="open_search_panel(this)"></span></div><div class="pin_resultado_busqueda_cerrar" name="casa"><span class="fas fa fa-trash" onclick="remove_search_results(this)"></span></div>');

              $(window).scrollTop(0); //PERMITE REMONTAR LA VISTA DE LA PAGINA

              history.pushState(page_requested_received, null, "#"+tipo_bien_clicked+"&"+departamento_clicked+"&"+ciudad_clicked+"&"+superficie_clicked+"&"+precio_clicked+"&"+dormitorios_clicked+"&"+parqueos_clicked+"&"+page_requested_received+"&"+price_sorting_received);
              //CREA UN PUSHSTATE PARA PODER REGRESAR CON EL BOTON BROWSER BACK/FOWARD
          };

// Envia datos de busqueda perzonalizada y actualiza los thumbnails para departamentos

      busqueda_departamento_sent = function(oObject, page_requested_received, price_sorting_received){
                var tipo_bien_clicked = $(oObject).attr('name');

                var departamento_clicked = $("#departamento_busqueda_departamento option:selected").val();
                var ciudad_clicked = $("#ciudad_busqueda_departamento option:selected").val();
                var superficie_clicked = $("#superficie_busqueda_departamento").val();
                var precio_clicked;
                if (estado == 'En Venta') {
                  if ($("#precio_busqueda_departamento").val() >= (Math.round(2000000*cambio))) {
                    precio_clicked = 100000000;
                  }else {
                    precio_clicked = $("#precio_busqueda_departamento").val();
                  };
                };
                if (estado == 'En Alquiler') {
                  if ($("#precio_busqueda_departamento").val() >= (Math.round(2000*cambio))) {
                    precio_clicked = 100000000;
                  }else {
                    precio_clicked = $("#precio_busqueda_departamento").val();
                  };
                };
                var dormitorios_clicked = $("input[name='radio_departamento-1']:checked").val();
                var parqueos_clicked = $("input[name='radio_departamento-2']:checked").val();
                if (estado == 'En Venta') {
                  if (precio_clicked <= (Math.round(2000000*cambio))) {
                    var precio_pin = "< "+(parseInt(precio_clicked).toLocaleString('fr-FR'));
                    var precio_pin_amplitud = " " + moneda;
                  };
                  if (precio_clicked > (Math.round(2000000*cambio))) {
                    var precio_pin = "> "+(parseInt((Math.round(2000000*cambio))).toLocaleString('fr-FR'));
                    var precio_pin_amplitud = " " + moneda;
                  };
                };

                if (estado == 'En Alquiler') {
                  if (precio_clicked <= (Math.round(2000*cambio))) {
                    var precio_pin = "< "+(parseInt(precio_clicked).toLocaleString('fr-FR'));
                    var precio_pin_amplitud = " " + moneda;
                  };
                  if (precio_clicked >= (Math.round(2000*cambio))) {
                    var precio_pin = "> "+(parseInt((Math.round(2000*cambio))).toLocaleString('fr-FR'));
                    var precio_pin_amplitud = " " + moneda;
                  };
                };

                $.ajax({
                    type: "POST",
                    url: "process-request-inmuebles-thumbnails-busqueda-venta.php",
                    data: { tipo_bien_selected : tipo_bien_clicked, departamento_busqueda : departamento_clicked, ciudad_busqueda : ciudad_clicked, superficie_busqueda : superficie_clicked, precio_busqueda : precio_clicked, dormitorios_busqueda : dormitorios_clicked, parqueos_busqueda : parqueos_clicked, page_requested : page_requested_received, price_sorting : price_sorting_received, estado : estado }
                }).done(function(data){
                    $("#tab2").html(data);
                });

                if ($('#configuracion_busqueda_Toggle').css('display') !== 'none') {
                  $('ul.lista_tabs li.tab_gear a').click();
                };

                $('#texto_resultado_busqueda').html('<p class="titulo_texto_resultado">Resultado de búsqueda:</p><div class="pin_resultado_busqueda">'+departamento_clicked+'</div><div class="pin_resultado_busqueda">'+ciudad_clicked+'</div><div class="pin_resultado_busqueda"> > '+superficie_clicked+' m<sup>2</sup></div><div class="pin_resultado_busqueda">'+precio_pin+precio_pin_amplitud+'</div><div class="pin_resultado_busqueda"><span class="fa fa-bed">&nbsp</span> x'+dormitorios_clicked+'</div><div class="pin_resultado_busqueda"><span class="fa fa-car">&nbsp</span> x'+parqueos_clicked+'</div><div class="pin_resultado_busqueda_editar" name="casa"><span class="far fa fa-edit" onclick="open_search_panel(this)"></span></div><div class="pin_resultado_busqueda_cerrar" name="departamento"><span class="fas fa fa-trash" onclick="remove_search_results(this)"></span></div>');

                $(window).scrollTop(0);

                history.pushState(page_requested_received, null, "#"+tipo_bien_clicked+"&"+departamento_clicked+"&"+ciudad_clicked+"&"+superficie_clicked+"&"+precio_clicked+"&"+dormitorios_clicked+"&"+parqueos_clicked+"&"+page_requested_received+"&"+price_sorting_received);
                //CREA UN PUSHSTATE PARA PODER REGRESAR CON EL BOTON BROWSER BACK/FOWARD
            };

// Envia datos de busqueda perzonalizada y actualiza los thumbnails para locales

      busqueda_local_sent = function(oObject, page_requested_received, price_sorting_received){
                var tipo_bien_clicked = $(oObject).attr('name');

                var departamento_clicked = $("#departamento_busqueda_local option:selected").val();
                var ciudad_clicked = $("#ciudad_busqueda_local option:selected").val();
                var superficie_clicked = $("#superficie_busqueda_local").val();
                var precio_clicked;
                if (estado == 'En Venta') {
                  if ($("#precio_busqueda_local").val() >= (Math.round(2000000*cambio))) {
                    precio_clicked = 100000000;
                  }else {
                    precio_clicked = $("#precio_busqueda_local").val();
                  };
                };
                if (estado == 'En Alquiler') {
                  if ($("#precio_busqueda_local").val() >= (Math.round(2000*cambio))) {
                    precio_clicked = 100000000;
                  }else {
                    precio_clicked = $("#precio_busqueda_local").val();
                  };
                };

                var tipo_local_clicked = $("input[name='radio_local-1']:checked").val();
                var parqueos_clicked = $("input[name='radio_local-2']:checked").val();
                if (estado == 'En Venta') {
                  if (precio_clicked <= (Math.round(2000000*cambio))) {
                    var precio_pin = "< "+(parseInt(precio_clicked).toLocaleString('fr-FR'));
                    var precio_pin_amplitud = " " + moneda;
                  };
                  if (precio_clicked > (Math.round(2000000*cambio))) {
                    var precio_pin = "> "+(parseInt((Math.round(2000000*cambio))).toLocaleString('fr-FR'));
                    var precio_pin_amplitud = " " + moneda;
                  };
                };

                if (estado == 'En Alquiler') {
                  if (precio_clicked <= (Math.round(2000*cambio))) {
                    var precio_pin = "< "+(parseInt(precio_clicked).toLocaleString('fr-FR'));
                    var precio_pin_amplitud = " " + moneda;
                  };
                  if (precio_clicked >= (Math.round(2000*cambio))) {
                    var precio_pin = "> "+(parseInt((Math.round(2000*cambio))).toLocaleString('fr-FR'));
                    var precio_pin_amplitud = " " + moneda;
                  };
                };

                $.ajax({
                    type: "POST",
                    url: "process-request-inmuebles-thumbnails-busqueda-venta.php",
                    data: { tipo_bien_selected : tipo_bien_clicked, departamento_busqueda : departamento_clicked, ciudad_busqueda : ciudad_clicked, superficie_busqueda : superficie_clicked, precio_busqueda : precio_clicked, tipo_local_busqueda : tipo_local_clicked, parqueos_busqueda : parqueos_clicked, page_requested : page_requested_received, price_sorting : price_sorting_received, estado : estado }
                }).done(function(data){
                    $("#tab3").html(data);
                });

                if ($('#configuracion_busqueda_Toggle').css('display') !== 'none') {
                  $('ul.lista_tabs li.tab_gear a').click();
                };

                $('#texto_resultado_busqueda').html('<p class="titulo_texto_resultado">Resultado de búsqueda:</p><div class="pin_resultado_busqueda">'+departamento_clicked+'</div><div class="pin_resultado_busqueda">'+ciudad_clicked+'</div><div class="pin_resultado_busqueda"> > '+superficie_clicked+' m<sup>2</sup></div><div class="pin_resultado_busqueda">'+precio_pin+precio_pin_amplitud+'</div><div class="pin_resultado_busqueda">'+tipo_local_clicked+'</div><div class="pin_resultado_busqueda"><span class="fa fa-car">&nbsp</span> x'+parqueos_clicked+'</div><div class="pin_resultado_busqueda_editar" name="casa"><span class="far fa fa-edit" onclick="open_search_panel(this)"></span></div><div class="pin_resultado_busqueda_cerrar" name="local"><span class="fas fa fa-trash" onclick="remove_search_results(this)"></span></div>');

                $(window).scrollTop(0);

                history.pushState(page_requested_received, null, "#"+tipo_bien_clicked+"&"+departamento_clicked+"&"+ciudad_clicked+"&"+superficie_clicked+"&"+precio_clicked+"&"+tipo_local_clicked+"&"+parqueos_clicked+"&"+page_requested_received+"&"+price_sorting_received);
                //CREA UN PUSHSTATE PARA PODER REGRESAR CON EL BOTON BROWSER BACK/FOWARD
            };

// Envia datos de busqueda perzonalizada y actualiza los thumbnails para terrenos

    busqueda_terreno_sent = function(oObject, page_requested_received, price_sorting_received){
              var tipo_bien_clicked = $(oObject).attr('name');

              var departamento_clicked = $("#departamento_busqueda_terreno option:selected").val();
              var ciudad_clicked = $("#ciudad_busqueda_terreno option:selected").val();

              var superficie_clicked = $("#superficie_busqueda_terreno").val();
              if (superficie_clicked > 300000) {
                var superficie_pin = "> "+30;
                var superficie_pin_medida = " ha";
              };
              if (superficie_clicked >= 5000 && superficie_clicked <= 300000) {
                var superficie_pin = "< "+(superficie_clicked/10000).toFixed(1);
                var superficie_pin_medida = " ha";
              };
              if (superficie_clicked < 5000) {
                var superficie_pin = "< "+(parseInt(superficie_clicked).toLocaleString('fr-FR'));
                var superficie_pin_medida = ' m&sup2';
              };


              var precio_clicked;
              if (estado == 'En Venta') {
                if ($("#precio_busqueda_terreno").val() >= (Math.round(2000000*cambio))) {
                  precio_clicked = 100000000;
                }else {
                  precio_clicked = $("#precio_busqueda_terreno").val();
                };
              };
              if (estado == 'En Alquiler') {
                if ($("#precio_busqueda_terreno").val() >= (Math.round(2000*cambio))) {
                  precio_clicked = 100000000;
                }else {
                  precio_clicked = $("#precio_busqueda_terreno").val();
                };
              };


              if (estado == 'En Venta') {
                if (estado == 'En Venta') {
                  if (precio_clicked <= (Math.round(2000000*cambio))) {
                    var precio_pin = "< "+(parseInt(precio_clicked).toLocaleString('fr-FR'));
                    var precio_pin_amplitud = " " + moneda;
                  };
                  if (precio_clicked > (Math.round(2000000*cambio))) {
                    var precio_pin = "> "+(parseInt((Math.round(2000000*cambio))).toLocaleString('fr-FR'));
                    var precio_pin_amplitud = " " + moneda;
                  };
                };
              };

              if (estado == 'En Alquiler') {
                if (precio_clicked <= (Math.round(2000*cambio))) {
                  var precio_pin = "< "+(parseInt(precio_clicked).toLocaleString('fr-FR'));
                  var precio_pin_amplitud = " " + moneda;
                };
                if (precio_clicked >= (Math.round(2000*cambio))) {
                  var precio_pin = "> "+(parseInt((Math.round(2000*cambio))).toLocaleString('fr-FR'));
                  var precio_pin_amplitud = " " + moneda;
                };
              };

              $.ajax({
                  type: "POST",
                  url: "process-request-inmuebles-thumbnails-busqueda-venta.php",
                  data: { tipo_bien_selected : tipo_bien_clicked, departamento_busqueda : departamento_clicked, ciudad_busqueda : ciudad_clicked, superficie_busqueda : superficie_clicked, precio_busqueda : precio_clicked, page_requested : page_requested_received, price_sorting : price_sorting_received, estado : estado }
              }).done(function(data){
                  $("#tab4").html(data);
              });

              if ($('#configuracion_busqueda_Toggle').css('display') !== 'none') {
                $('ul.lista_tabs li.tab_gear a').click();
              };


              $('#texto_resultado_busqueda').html('<p class="titulo_texto_resultado">Resultado de búsqueda:</p><div class="pin_resultado_busqueda">'+departamento_clicked+'</div><div class="pin_resultado_busqueda">'+ciudad_clicked+'</div><div class="pin_resultado_busqueda">'+superficie_pin+superficie_pin_medida+'</div><div class="pin_resultado_busqueda">'+precio_pin+precio_pin_amplitud+'</div><div class="pin_resultado_busqueda_editar" name="casa"><span class="far fa fa-edit" onclick="open_search_panel(this)"></span></div><div class="pin_resultado_busqueda_cerrar" name="terreno"><span class="fas fa fa-trash" onclick="remove_search_results(this)"></span></div>');

              $(window).scrollTop(0);

              history.pushState(page_requested_received, null, "#"+tipo_bien_clicked+"&"+departamento_clicked+"&"+ciudad_clicked+"&"+superficie_clicked+"&"+precio_clicked+"&"+page_requested_received+"&"+price_sorting_received);
              //CREA UN PUSHSTATE PARA PODER REGRESAR CON EL BOTON BROWSER BACK/FOWARD
          };
// OPEN SEARCH PANEL WHEN EDIT ICON IS CLICKED ###################

    open_search_panel = function(oObject){
      $('ul.lista_tabs li.tab_gear a').click();
    };

// CUANDO SE HACE CLICK EN EL BASURERO DE BORRAR BUSQUEDA ###############

      remove_search_results = function(oObject){

        var tab_to_click = $('#' + "busqueda_" + oObject.parentNode.getAttribute('name') + "_type");

        tab_to_click.trigger('click');


        $('#texto_resultado_busqueda').html('<p class="titulo_texto_resultado">Bienes recomendados:</p>');
        $("#paginacion_container").empty();
        $("#paginacion_container_top").empty();
        $(".pin_resultado_busqueda_count").remove();
        $(".pin_resultado_busqueda_order").remove();

            };



  });
});
