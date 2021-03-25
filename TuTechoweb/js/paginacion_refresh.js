$(document).ready(function(){
  jQuery(function($){

// Cambiar orientacion de la flechita del Pin Ordenar por Precio

      // var pin_precio_contador = 1;

    window.precio_order_backbutton_refresh = function(){ //Define la function de forma global para que pueda ser llamada desde el backbutton.js file, Esta funcion restablece la forma del boton price sorting segun la info en hash en caso de BACK, y en caso de REFRESH lo crea de cero con la forma correcta. En ambos casos se define el contador con el valor en hash
          var hashInfo = window.location.hash.substr(1).replace(/%20/g, " ").trim().split("&");
          if (hashInfo[0].includes("~")) {
            var hash_tab = hashInfo[0].trim().split("~");//gets de tab name form the hash
            var hash_active_tab = hash_tab[0];//gets de tab name form the hash
          }else {
            var hash_active_tab = hashInfo[0];//gets de tab name form the hash
          };

          if (hashInfo[1]) {//definir el contador segun valor de hash, la posicion en el hash varia segun el tab activo
            if (hash_active_tab == "casa" || hash_active_tab == "departamento" || hash_active_tab == "local") {
              pin_precio_contador = hashInfo[8];
            }else {
                pin_precio_contador = hashInfo[6];
            };
          };
          if ($(".pin_resultado_busqueda_order").length === 0){//en caso de REFRESH la pagina carga de nuevo y el boton no existe aun a en este momento de lectura del codigo, entonces hay que crearlo. en caso de BACK ya existe y no es necesario recrear otro
              $("#paginacion_top_section").append('<div class="pin_resultado_busqueda_order"><p>Ordenar por:</p><p class="precio_order" onclick="precio_order_change(this)">Precio <span class="precio_order_span fas fa fa-random"></span></p></div>');
            };

          //quita la forma en caso de BACK, sea cual sea. En caso de REFRESH estas lineas no hacen anda
          $('.precio_order_span').removeClass('fa-random');
          $('.precio_order_span').removeClass('fa-arrow-up');
          $('.precio_order_span').removeClass('fa-arrow-down');
          //restablece la forma del boton segun valor del contador
          if(pin_precio_contador == 1){
              $('.precio_order_span').addClass('fa-random');
            }else {
              if (pin_precio_contador == 2) {
                $('.precio_order_span').addClass('fa-arrow-up');
              }else {
                if (pin_precio_contador == 3) {
                  $('.precio_order_span').addClass('fa-arrow-down');
                };
              };
            };
    };



      precio_order_change = function(oObject){//cambia la forma y valor del contador al hacer click en el boton price sorting y se llama a la funcion que hara los cambios en los thumbs
        if(pin_precio_contador == 1){
            $('.precio_order_span').removeClass('fa-random');
            $('.precio_order_span').addClass('fa-arrow-up');
            pin_precio_contador = 2;
            precio_order_refresh_thumbs();
          }else {
            if (pin_precio_contador == 2) {
              $('.precio_order_span').removeClass('fa-arrow-up');
              $('.precio_order_span').addClass('fa-arrow-down');
              pin_precio_contador = 3;
              precio_order_refresh_thumbs();
            }else {
              if (pin_precio_contador == 3) {
                $('.precio_order_span').removeClass('fa-arrow-down');
                $('.precio_order_span').addClass('fa-random');
                pin_precio_contador = 1;
                precio_order_refresh_thumbs();
              };
            };
          };
        };


      precio_order_refresh_thumbs = function(){//funcion que se encarga de producir los cambios en los thumbnails segun el valor del contador del price sorting
        var active_tab_inmueble = $('ul.lista_tabs li.tab a.active_tab').attr('name');
        var button_sent_search = $("button[name='"+active_tab_inmueble+"']").get();
        var function_to_call = "busqueda_"+active_tab_inmueble+"_sent"; //DEFINE LA FUNCION QUE LLAMAR
        var hashInfo = window.location.hash.substr(1).replace(/%20/g, " ").trim().split("&");
        if (active_tab_inmueble == "casa" || active_tab_inmueble == "departamento" || active_tab_inmueble == "local") {
          var page_requested = hashInfo[7]; //DEFINE LA PAGINA REQUERIDA
        }else {
            var page_requested = hashInfo[5]; //DEFINE LA PAGINA REQUERIDA
        };
        window[function_to_call](button_sent_search, page_requested, pin_precio_contador); // LLAMA A LA FUNCION ADECUADA ESPECIFICANDO LA PAGINA REQUERIDA
        $(window).scrollTop(0); // PERMITE RETORNAR LA VISTA A LO ALTO DE LA PANTALLA
      };

// CUANDO SE HACE CLICK EN UN BOTON DE LA PAGINACION

    $('#paginacion_container').on("click", "li.pag_buton:not(.disabled):not(.active) p", function(){ // to maintain de click event listener to futur created element do it this way, and not with just click(). At the beggining show static (that already exists in page load) parent, then the event, on what the event applyes, and then the function
      var active_tab_inmueble = $('ul.lista_tabs li.tab a.active_tab').attr('name');
      var button_sent_search = $("button[name='"+active_tab_inmueble+"']").get();
      var function_to_call = "busqueda_"+active_tab_inmueble+"_sent"; //DEFINE LA FUNCION QUE LLAMAR
      var page_requested = $(this).parent().val(); //DEFINE LA PAGINA REQUERIDA
      window[function_to_call](button_sent_search, page_requested, pin_precio_contador); // LLAMA A LA FUNCION ADECUADA ESPECIFICANDO LA PAGINA REQUERIDA
      $(window).scrollTop(0); // PERMITE RETORNAR LA VISTA A LO ALTO DE LA PANTALLA

    });

    $('#paginacion_container_top').on("click", "li.pag_buton:not(.disabled):not(.active) p", function(){ // to maintain de click event listener to futur created element do it this way, and not with just click(). At the beggining show static parent, then the event, on what the event applyes and then the function
      var active_tab_inmueble = $('ul.lista_tabs li.tab a.active_tab').attr('name');
      var button_sent_search = $("button[name='"+active_tab_inmueble+"']").get();
      var function_to_call = "busqueda_"+active_tab_inmueble+"_sent"; //DEFINE LA FUNCION QUE LLAMAR
      var page_requested = $(this).parent().val(); //DEFINE LA PAGINA REQUERIDA
      window[function_to_call](button_sent_search, page_requested, pin_precio_contador); // LLAMA A LA FUNCION ADECUADA ESPECIFICANDO LA PAGINA REQUERIDA
      $(window).scrollTop(0); // PERMITE RETORNAR LA VISTA A LO ALTO DE LA PANTALLA

    });

// DEFINICION DE LA FUNCION PAGINACION_REFRESH
    paginacion_refresh = function(page_requested_received, numero_articulos_received, articulos_por_pagina_received){

        var numero_paginas_calculated = numero_articulos_received/articulos_por_pagina_received;

        var current_tab_search = window.location.hash.substr(1).replace(/%20/g, " ").trim().split("&");

        if (current_tab_search[0].includes("~")) {
          var hash_tab = current_tab_search[0].trim().split("~");//gets de tab name form the hash
          var hash_current_tab_search = hash_tab[0];//gets de tab name form the hash
        }else {
          var hash_current_tab_search = current_tab_search[0];//gets de tab name form the hash
        };    

        if (hash_current_tab_search[0] == "local") {
          var plural_tab_name = "es";
        } else {
          var plural_tab_name = "s";
        };


        if (Math.ceil(numero_paginas_calculated) <= 1 ) {//si solo hay una pagina, no hya necesidad de paginacion, ni de elementos totales, ni de boton price sorting
          $("#paginacion_container").empty();
          $("#paginacion_container_top").empty();
          $(".pin_resultado_busqueda_count").remove();
          $(".pin_resultado_busqueda_order").remove();

        }else {//sino, crear paginacion, y todo lo demas
              $.ajax({
                  type: "POST",
                  url: "process-request-paginacion_refresh.php",
                  data: { page_requested : page_requested_received, numero_paginas : numero_paginas_calculated }
              }).done(function(data){
                  $("#paginacion_container").html(data);
                  $(".pin_resultado_busqueda_count").remove();

                  $("#paginacion_top_section").prepend('<div class="pin_resultado_busqueda_count"><span class="fas fa fa-check-circle"></span><p>'+numero_articulos_received+' '+current_tab_search[0]+plural_tab_name+' disponibles</p></div>');
                  $("#paginacion_container_top").html(data);
                  if ($(".pin_resultado_busqueda_order").length === 0){//crear el boton price sorting y fijar contador a 1, unicamente si el boton no existe ya. Esto evita crear mas de uno si se manda una segunda busqueda. En caso de BACK, no se crea otro tampoco. En caso de REFRESH, se crea uno segun hash pero no en esta funcion.
                      $("#paginacion_top_section").append('<div class="pin_resultado_busqueda_order"><p>Ordenar por:</p><p class="precio_order" onclick="precio_order_change(this)">Precio <span class="precio_order_span fas fa fa-random"></span></p></div>');
                      pin_precio_contador = 1;

                    };

              });
        };

    };




  });
});
