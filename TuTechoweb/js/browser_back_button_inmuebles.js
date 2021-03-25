$(document).ready(function(){
  jQuery(function($){

    function back_open_ficha_bien(referencia_to_open){
        $('.overlay_popup_ficha_bien').addClass('active');
        var referencia_to_sent = referencia_to_open;
        var tipo_to_sent;
        if (referencia_to_sent.includes("C")) {
          tipo_to_sent = "casa";
        } else {
          if (referencia_to_sent.includes("D")) {
            tipo_to_sent = "departamento";
          } else {
            if (referencia_to_sent.includes("L")) {
              tipo_to_sent = "local";
            } else {
              if (referencia_to_sent.includes("T")) {
                tipo_to_sent = "terreno";
              };
            };
          };
        };

        $.ajax({
              type: "POST",
              url: "process-request-popup_ficha_bien.php",
              data: { ficha_bien_requested : referencia_to_sent, ficha_bien_tipo_requested : tipo_to_sent, estado : estado },
          }).done(function(data){
            $('.popup_ficha_bien').html(data);
            $("body").addClass('ficha_active');
            });
    };

  // Mechanismo para el refresh de info al hacer click en el BROWSER BACK/FOWARD button

      window.onpopstate = checkState; // cuando un nuevo state aparece, cuando se oprime el Back/Foward Browser button

      function checkState(e) {
        //PRIMERO SE CIERRA TODA FICHA BIEN QUE PUEDA ESTAR ABIERTA
        $('.overlay_popup_ficha_bien').animate({
          scrollTop: '0px'
        }, 0)
        $('.overlay_popup_ficha_bien').removeClass('active');
        $("body").removeClass('ficha_active');

        //VERIFICA SI SE HA RETORNADO A UN PUNTO EN EL CUAL SIGUE EXISTIENDO UN STATE
      if(e.state) {
        var hashVal = window.location.hash.substr(1);
        var hashArray = hashVal.replace(/%20/g, " ").trim().split("&"); // SE ALAMCENAN LOS DATOS DEL STATE (url) DENTRO DE UN ARRAY
        if (hashArray[0].includes("~")) {
          var hash_tab = hashArray[0].trim().split("~");//gets de tab name form the hash
          var active_tab_name_hashinfo = hash_tab[0];//gets de tab name form the hash
        }else {
          var active_tab_name_hashinfo = hashArray[0];//gets de tab name form the hash
        };

        if (hashArray[1]) { // LO QUE SUCEDE SI EN ESTE PUNTO HUBO RESULTADOS DE BUSQUEDA AVANZADA
          window.precio_order_backbutton_refresh();//para restablecer la forma del botton price sorting y restablecer el valor de su contador segun el hash

          var back_button_clicked_search = "search_done"; // SE DEFINE UN FLAG PARA DETERMINAR EL EVENTO CLICK DEL BROWSER BACK/FOWARD BUTTON
          $("ul.lista_tabs li.tab a[name='"+active_tab_name_hashinfo+"']").trigger('click', {param : back_button_clicked_search}); // SE DETONA EL EVENTO CLICK DEL TAB Y SE ENVIA EL FLAG

//####### ACA SE BUSCA RESTABLECER LOS VALUES DES MENU CONFIG BUSQUEDA DE CASAS CON LA INFO DEL HASH
            if (active_tab_name_hashinfo == 'casa') {

              var departamento_selected_hash = hashArray[1]; // SE ALMACENA EL DEPARTAMENTO SELECCIONADO QUE APARECE EN EL HASH
              $("#departamento_busqueda_casa").val(departamento_selected_hash).selectmenu("refresh"); // SE RESTABLECE EL VALOR DE DEPARTAMENTO EN EL MENU SELECT


              var departamentoSelected = $("#departamento_busqueda_casa option:selected").val();//USAMOS EL VALOR DEL SELECT PARA EL AJAX YNO EL DEL HASH, ESTO NOS PERMITE VERIFICAR QUE EL CODIGO ANTERIOR SE HIZO BIEN
              $.ajax({
                  type: "POST",
                  url: "process-request-ciudades_busqueda.php",
                  data: { departamentoChoice : departamentoSelected }
              }).done(function(data){
                  $("#ciudad_busqueda_casa").html(data);//POBLAMOS EL SELECT CIUDADES
                  $(".select_menu").selectmenu("refresh");
                  var ciudad_selected_hash = hashArray[2];
                  $("#ciudad_busqueda_casa").val(ciudad_selected_hash).selectmenu("refresh"); // SE RESTABLECE EL VALOR DE CIUDADES EN EL MENU SELECT
              });

              var superficie_selected_hash = hashArray[3];
              $('#superficie_busqueda_casa').parent().find('.uirange').slider("value", superficie_selected_hash );// RESTABLECE LA POSICION DEL SLIDER
              $('#superficie_busqueda_casa').parent().find('label span').empty().append(superficie_selected_hash);// RESTABLECE EL VALOR DEL LABEL DEL SLIDER
              $("#superficie_busqueda_casa").val(superficie_selected_hash);// RESTABLECE EL VALOR DEL SLIDER


              var elem_precio_casa = $('#precio_busqueda_casa').parent();
              if (estado == 'En Venta') {
                    var precio_selected_hash = hashArray[4];

                    //TODOS ESTOS IFs SON PARA RESTABLECER EL LABEL DEL SLIDER PRECIO
                    
                      elem_precio_casa.find('label span[class="precio_val"]').empty().append((Math.round(precio_selected_hash)).toLocaleString('fr-FR')); //introducir dentro del span el valor del slider
                      elem_precio_casa.find('label span[class="millon"]').empty().append(moneda);
                    
                    if (precio_selected_hash > (Math.round(2300000*cambio))) {
                          // force it to 0 between -1 and 1.
                          elem_precio_casa.find('.uirange').slider('value', (Math.round(100000000*cambio)));
                          elem_precio_casa.find('label span[class="precio_val"]').empty().append(` > ${(Math.round(2000000*cambio)).toLocaleString('fr-FR')} ${moneda}`);
                          elem_precio_casa.find('label span[class="millon"]').empty();
                    };
              };

              if (estado == 'En Alquiler') {
                var precio_selected_hash = hashArray[4];
                if (precio_selected_hash >= (Math.round(350*cambio)) && precio_selected_hash < (Math.round(2000*cambio))) { //no 1000 ya que da un error, al pasar a 1001 muestra 1.00 millones
                  elem_precio_casa.find('label span[class="precio_val"]').empty().append((Math.round(precio_selected_hash)).toLocaleString('fr-FR')); //introducir dentro del span el valor del slider
                };
                if (precio_selected_hash >= (Math.round(2000*cambio))) { //no 1000 ya que da un error, al pasar a 1001 muestra 1.00 millones
                  elem_precio_casa.find('label span[class="precio_val"]').empty().append(` > ${(Math.round(2000*cambio)).toLocaleString('fr-FR')}`); //introducir dentro del span el valor del slider
                };
              };

              elem_precio_casa.find('.uirange').slider("value", precio_selected_hash);// RESTABLECE LA POSICION DEL SLIDER
              $("#precio_busqueda_casa").val(precio_selected_hash);// RESTABLECE EL VALOR DEL SLIDER

              var hab_check_input_id_hash = "radio_casa-1." + hashArray[5];//RESTABLECE EL CHECKBOX DOMITORIOS
              $(document.getElementById(hab_check_input_id_hash).previousElementSibling).click();

              var parqueo_num = parseInt(hashArray[6])+1;//RESTABLECE EL CHECKBOX PARQUEOS
              var parqueo_check_input_id_hash = "radio_casa-2." + (parseInt(hashArray[6])+1);
              $(document.getElementById(parqueo_check_input_id_hash).previousElementSibling).click();

              //RESTABLECE LOS STICKERS RESULTADOS DE BUSQUEDA

              if (estado == 'En Venta') {
                if (hashArray[4] <= (Math.round(2000000*cambio))) {
                  var precio_pin_hash = "< "+ ((parseInt(hashArray[4])).toLocaleString('fr-FR'));
                  var precio_pin_amplitud_hash = " " + moneda;
                };
                if (hashArray[4] > (Math.round(2000000*cambio))) {
                  var precio_pin_hash = "> " +  ((Math.round(2000000*cambio)).toLocaleString('fr-FR'));
                  var precio_pin_amplitud_hash = " " + moneda;
                };
              };
              if (estado == 'En Alquiler') {
                if (hashArray[4] >= (Math.round(350*cambio)) && hashArray[4] < (Math.round(2000*cambio))) {
                  var precio_pin_hash = "< " + ((parseInt(hashArray[4])).toLocaleString('fr-FR'));
                  var precio_pin_amplitud_hash = " " + moneda;
                };
                if (hashArray[4] >= (Math.round(2000*cambio))) {
                  var precio_pin_hash = "> " + ((Math.round(2000*cambio)).toLocaleString('fr-FR'));
                  var precio_pin_amplitud_hash = " " + moneda;
                };
              };

              $('#texto_resultado_busqueda').html('<p class="titulo_texto_resultado">Resultado de búsqueda:</p><div class="pin_resultado_busqueda">'+hashArray[1]+'</div><div class="pin_resultado_busqueda">'+hashArray[2]+'</div><div class="pin_resultado_busqueda"> > '+hashArray[3]+' m<sup>2</sup></div><div class="pin_resultado_busqueda">'+precio_pin_hash+precio_pin_amplitud_hash+'</div><div class="pin_resultado_busqueda"><span class="fa fa-bed">&nbsp</span> x'+hashArray[5]+'</div><div class="pin_resultado_busqueda"><span class="fa fa-car">&nbsp</span> x'+hashArray[6]+'</div><div class="pin_resultado_busqueda_editar" name="casa"><span class="far fa fa-edit" onclick="open_search_panel(this)"></span></div><div class="pin_resultado_busqueda_cerrar" name="casa"><span class="fas fa fa-trash" onclick="remove_search_results(this)"></span></div>');

              $.ajax({
                  type: "POST",
                  url: "process-request-inmuebles-thumbnails-busqueda-venta.php",
                  data: { tipo_bien_selected : hashArray[0], departamento_busqueda : hashArray[1], ciudad_busqueda : hashArray[2], superficie_busqueda : hashArray[3], precio_busqueda : hashArray[4], dormitorios_busqueda : hashArray[5], parqueos_busqueda : hashArray[6], page_requested : hashArray[7], price_sorting : hashArray[8], estado : estado }
              }).done(function(data){
                  $("#tab1").html(data);
              });

              $(window).scrollTop(0);

            };

//####### ACA SE BUSCA RESTABLECER LOS VALUES DES MENU CONFIG BUSQUEDA DE DEPARTAMENTOS CON LA INFO DEL HASH

          if (active_tab_name_hashinfo == 'departamento') {

            var departamento_selected_hash = hashArray[1]; // SE ALMACENA EL DEPARTAMENTO SELECCIONADO QUE APARECE EN EL HASH
            $("#departamento_busqueda_departamento").val(departamento_selected_hash).selectmenu("refresh"); // SE RESTABLECE EL VALOR DE DEPARTAMENTO EN EL MENU SELECT


            var departamentoSelected = $("#departamento_busqueda_departamento option:selected").val();//USAMOS EL VALOR DEL SELECT PARA EL AJAX YNO EL DEL HASH, ESTO NOS PERMITE VERIFICAR QUE EL CODIGO ANTERIOR SE HIZO BIEN
            $.ajax({
                type: "POST",
                url: "process-request-ciudades_busqueda.php",
                data: { departamentoChoice : departamentoSelected }
            }).done(function(data){
                $("#ciudad_busqueda_departamento").html(data);//POBLAMOS EL SELECT CIUDADES
                $(".select_menu").selectmenu("refresh");
                var ciudad_selected_hash = hashArray[2];
                $("#ciudad_busqueda_departamento").val(ciudad_selected_hash).selectmenu("refresh"); // SE RESTABLECE EL VALOR DE CIUDADES EN EL MENU SELECT
            });

            var superficie_selected_hash = hashArray[3];
            $('#superficie_busqueda_departamento').parent().find('.uirange').slider("value", superficie_selected_hash );// RESTABLECE LA POSICION DEL SLIDER
            $('#superficie_busqueda_departamento').parent().find('label span').empty().append(superficie_selected_hash);// RESTABLECE EL VALOR DEL LABEL DEL SLIDER
            $("#superficie_busqueda_departamento").val(superficie_selected_hash);// RESTABLECE EL VALOR DEL SLIDER


            var elem_precio_casa = $('#precio_busqueda_departamento').parent();
            if (estado == 'En Venta') {
                  var precio_selected_hash = hashArray[4];

                  //TODOS ESTOS IFs SON PARA RESTABLECER EL LABEL DEL SLIDER PRECIO
                    
                  elem_precio_casa.find('label span[class="precio_val"]').empty().append((Math.round(precio_selected_hash)).toLocaleString('fr-FR')); //introducir dentro del span el valor del slider
                  elem_precio_casa.find('label span[class="millon"]').empty().append(moneda);
                
                if (precio_selected_hash > (Math.round(2300000*cambio))) {
                      // force it to 0 between -1 and 1.
                      elem_precio_casa.find('.uirange').slider('value', (Math.round(100000000*cambio)));
                      elem_precio_casa.find('label span[class="precio_val"]').empty().append(` > ${(Math.round(2000000*cambio)).toLocaleString('fr-FR')} ${moneda}`);
                      elem_precio_casa.find('label span[class="millon"]').empty();
                };
            };

            if (estado == 'En Alquiler') {
                var precio_selected_hash = hashArray[4];
                if (precio_selected_hash >= (Math.round(350*cambio)) && precio_selected_hash < (Math.round(2000*cambio))) { //no 1000 ya que da un error, al pasar a 1001 muestra 1.00 millones
                  elem_precio_casa.find('label span[class="precio_val"]').empty().append((Math.round(precio_selected_hash)).toLocaleString('fr-FR')); //introducir dentro del span el valor del slider
                };
                if (precio_selected_hash >= (Math.round(2000*cambio))) { //no 1000 ya que da un error, al pasar a 1001 muestra 1.00 millones
                  elem_precio_casa.find('label span[class="precio_val"]').empty().append(` > ${(Math.round(2000*cambio)).toLocaleString('fr-FR')}`); //introducir dentro del span el valor del slider
                };
              };

            elem_precio_casa.find('.uirange').slider("value", precio_selected_hash);// RESTABLECE LA POSICION DEL SLIDER
            $("#precio_busqueda_departamento").val(precio_selected_hash);// RESTABLECE EL VALOR DEL SLIDER

            var hab_check_input_id_hash = "radio_departamento-1." + hashArray[5];//RESTABLECE EL CHECKBOX DOMITORIOS
            $(document.getElementById(hab_check_input_id_hash).previousElementSibling).click();

            var parqueo_num = parseInt(hashArray[6])+1;//RESTABLECE EL CHECKBOX PARQUEOS
            var parqueo_check_input_id_hash = "radio_departamento-2." + (parseInt(hashArray[6])+1);
            $(document.getElementById(parqueo_check_input_id_hash).previousElementSibling).click();

            //RESTABLECE LOS STICKERS RESULTADOS DE BUSQUEDA

            if (estado == 'En Venta') {
              if (hashArray[4] <= (Math.round(2000000*cambio))) {
                var precio_pin_hash = "< "+ ((parseInt(hashArray[4])).toLocaleString('fr-FR'));
                var precio_pin_amplitud_hash = " " + moneda;
              };
              if (hashArray[4] > (Math.round(2000000*cambio))) {
                var precio_pin_hash = "> " +  ((Math.round(2000000*cambio)).toLocaleString('fr-FR'));
                var precio_pin_amplitud_hash = " " + moneda;
              };
            };
            if (estado == 'En Alquiler') {
              if (hashArray[4] >= (Math.round(350*cambio)) && hashArray[4] < (Math.round(2000*cambio))) {
                var precio_pin_hash = "< " + ((parseInt(hashArray[4])).toLocaleString('fr-FR'));
                var precio_pin_amplitud_hash = " " + moneda;
              };
              if (hashArray[4] >= (Math.round(2000*cambio))) {
                var precio_pin_hash = "> " + ((Math.round(2000*cambio)).toLocaleString('fr-FR'));
                var precio_pin_amplitud_hash = " " + moneda;
              };
            };

            $('#texto_resultado_busqueda').html('<p class="titulo_texto_resultado">Resultado de búsqueda:</p><div class="pin_resultado_busqueda">'+hashArray[1]+'</div><div class="pin_resultado_busqueda">'+hashArray[2]+'</div><div class="pin_resultado_busqueda"> > '+hashArray[3]+' m<sup>2</sup></div><div class="pin_resultado_busqueda">'+precio_pin_hash+precio_pin_amplitud_hash+'</div><div class="pin_resultado_busqueda"><span class="fa fa-bed">&nbsp</span> x'+hashArray[5]+'</div><div class="pin_resultado_busqueda"><span class="fa fa-car">&nbsp</span> x'+hashArray[6]+'</div><div class="pin_resultado_busqueda_editar" name="casa"><span class="far fa fa-edit" onclick="open_search_panel(this)"></span></div><div class="pin_resultado_busqueda_cerrar" name="departamento"><span class="fas fa fa-trash" onclick="remove_search_results(this)"></span></div>');

            $.ajax({
                type: "POST",
                url: "process-request-inmuebles-thumbnails-busqueda-venta.php",
                data: { tipo_bien_selected : hashArray[0], departamento_busqueda : hashArray[1], ciudad_busqueda : hashArray[2], superficie_busqueda : hashArray[3], precio_busqueda : hashArray[4], dormitorios_busqueda : hashArray[5], parqueos_busqueda : hashArray[6], page_requested : hashArray[7], price_sorting : hashArray[8], estado : estado }
            }).done(function(data){
                $("#tab2").html(data);
            });

            $(window).scrollTop(0);

          };

//####### ACA SE BUSCA RESTABLECER LOS VALUES DES MENU CONFIG BUSQUEDA DE LOCALES CON LA INFO DEL HASH

          if (active_tab_name_hashinfo == 'local') {

            var departamento_selected_hash = hashArray[1]; // SE ALMACENA EL DEPARTAMENTO SELECCIONADO QUE APARECE EN EL HASH
            $("#departamento_busqueda_local").val(departamento_selected_hash).selectmenu("refresh"); // SE RESTABLECE EL VALOR DE DEPARTAMENTO EN EL MENU SELECT


            var departamentoSelected = $("#departamento_busqueda_local option:selected").val();//USAMOS EL VALOR DEL SELECT PARA EL AJAX YNO EL DEL HASH, ESTO NOS PERMITE VERIFICAR QUE EL CODIGO ANTERIOR SE HIZO BIEN
            $.ajax({
                type: "POST",
                url: "process-request-ciudades_busqueda.php",
                data: { departamentoChoice : departamentoSelected }
            }).done(function(data){
                $("#ciudad_busqueda_local").html(data);//POBLAMOS EL SELECT CIUDADES
                $(".select_menu").selectmenu("refresh");
                var ciudad_selected_hash = hashArray[2];
                $("#ciudad_busqueda_local").val(ciudad_selected_hash).selectmenu("refresh"); // SE RESTABLECE EL VALOR DE CIUDADES EN EL MENU SELECT
            });

            var superficie_selected_hash = hashArray[3];
            $('#superficie_busqueda_local').parent().find('.uirange').slider("value", superficie_selected_hash );// RESTABLECE LA POSICION DEL SLIDER
            $('#superficie_busqueda_local').parent().find('label span').empty().append(superficie_selected_hash);// RESTABLECE EL VALOR DEL LABEL DEL SLIDER
            $("#superficie_busqueda_local").val(superficie_selected_hash);// RESTABLECE EL VALOR DEL SLIDER


            var elem_precio_casa = $('#precio_busqueda_local').parent();
            if (estado == 'En Venta') {
                  var precio_selected_hash = hashArray[4];

                  //TODOS ESTOS IFs SON PARA RESTABLECER EL LABEL DEL SLIDER PRECIO
                    
                  elem_precio_casa.find('label span[class="precio_val"]').empty().append((Math.round(precio_selected_hash)).toLocaleString('fr-FR')); //introducir dentro del span el valor del slider
                  elem_precio_casa.find('label span[class="millon"]').empty().append(moneda);
                
                if (precio_selected_hash > (Math.round(2300000*cambio))) {
                      // force it to 0 between -1 and 1.
                      elem_precio_casa.find('.uirange').slider('value', (Math.round(100000000*cambio)));
                      elem_precio_casa.find('label span[class="precio_val"]').empty().append(` > ${(Math.round(2000000*cambio)).toLocaleString('fr-FR')} ${moneda}`);
                      elem_precio_casa.find('label span[class="millon"]').empty();
                };
            };

            if (estado == 'En Alquiler') {
                var precio_selected_hash = hashArray[4];
                if (precio_selected_hash >= (Math.round(350*cambio)) && precio_selected_hash < (Math.round(2000*cambio))) { //no 1000 ya que da un error, al pasar a 1001 muestra 1.00 millones
                  elem_precio_casa.find('label span[class="precio_val"]').empty().append((Math.round(precio_selected_hash)).toLocaleString('fr-FR')); //introducir dentro del span el valor del slider
                };
                if (precio_selected_hash >= (Math.round(2000*cambio))) { //no 1000 ya que da un error, al pasar a 1001 muestra 1.00 millones
                  elem_precio_casa.find('label span[class="precio_val"]').empty().append(` > ${(Math.round(2000*cambio)).toLocaleString('fr-FR')}`); //introducir dentro del span el valor del slider
                };
              };

            elem_precio_casa.find('.uirange').slider("value", precio_selected_hash);// RESTABLECE LA POSICION DEL SLIDER
            $("#precio_busqueda_local").val(precio_selected_hash);// RESTABLECE EL VALOR DEL SLIDER

            if (hashArray[5] == "Comercial") {//Restablece el CHECKBOX TIPO_LOCAL
              var tipo_local_check_input_id_hash = "radio_local-1.1";
            } else {
              if (hashArray[5] == "Oficina") {
                var tipo_local_check_input_id_hash = "radio_local-1.2";
              } else {
                var tipo_local_check_input_id_hash = "radio_local-1.3";
              };
            };
            $(document.getElementById(tipo_local_check_input_id_hash).previousElementSibling).click();

            var parqueo_num = parseInt(hashArray[6])+1;//RESTABLECE EL CHECKBOX PARQUEOS
            var parqueo_check_input_id_hash = "radio_local-2." + (parseInt(hashArray[6])+1);
            $(document.getElementById(parqueo_check_input_id_hash).previousElementSibling).click();

            //RESTABLECE LOS STICKERS RESULTADOS DE BUSQUEDA

            if (estado == 'En Venta') {
              if (hashArray[4] <= (Math.round(2000000*cambio))) {
                var precio_pin_hash = "< "+ ((parseInt(hashArray[4])).toLocaleString('fr-FR'));
                var precio_pin_amplitud_hash = " " + moneda;
              };
              if (hashArray[4] > (Math.round(2000000*cambio))) {
                var precio_pin_hash = "> " +  ((Math.round(2000000*cambio)).toLocaleString('fr-FR'));
                var precio_pin_amplitud_hash = " " + moneda;
              };
            };
            if (estado == 'En Alquiler') {
              if (hashArray[4] >= (Math.round(350*cambio)) && hashArray[4] < (Math.round(2000*cambio))) {
                var precio_pin_hash = "< " + ((parseInt(hashArray[4])).toLocaleString('fr-FR'));
                var precio_pin_amplitud_hash = " " + moneda;
              };
              if (hashArray[4] >= (Math.round(2000*cambio))) {
                var precio_pin_hash = "> " + ((Math.round(2000*cambio)).toLocaleString('fr-FR'));
                var precio_pin_amplitud_hash = " " + moneda;
              };
            };


            $('#texto_resultado_busqueda').html('<p class="titulo_texto_resultado">Resultado de búsqueda:</p><div class="pin_resultado_busqueda">'+hashArray[1]+'</div><div class="pin_resultado_busqueda">'+hashArray[2]+'</div><div class="pin_resultado_busqueda"> > '+hashArray[3]+' m<sup>2</sup></div><div class="pin_resultado_busqueda">'+precio_pin_hash+precio_pin_amplitud_hash+'</div><div class="pin_resultado_busqueda">'+hashArray[5]+'</div><div class="pin_resultado_busqueda"><span class="fa fa-car">&nbsp</span> x'+hashArray[6]+'</div><div class="pin_resultado_busqueda_editar" name="casa"><span class="far fa fa-edit" onclick="open_search_panel(this)"></span></div><div class="pin_resultado_busqueda_cerrar" name="local"><span class="fas fa fa-trash" onclick="remove_search_results(this)"></span></div>');

            $.ajax({
                type: "POST",
                url: "process-request-inmuebles-thumbnails-busqueda-venta.php",
                data: { tipo_bien_selected : hashArray[0], departamento_busqueda : hashArray[1], ciudad_busqueda : hashArray[2], superficie_busqueda : hashArray[3], precio_busqueda : hashArray[4], tipo_local_busqueda : hashArray[5], parqueos_busqueda : hashArray[6], page_requested : hashArray[7], price_sorting : hashArray[8], estado : estado }
            }).done(function(data){
                $("#tab3").html(data);
            });

            $(window).scrollTop(0);

          };

//####### ACA SE BUSCA RESTABLECER LOS VALUES DES MENU CONFIG BUSQUEDA DE TERRENOS CON LA INFO DEL HASH

          if (active_tab_name_hashinfo == 'terreno') {

            var departamento_selected_hash = hashArray[1]; // SE ALMACENA EL DEPARTAMENTO SELECCIONADO QUE APARECE EN EL HASH
            $("#departamento_busqueda_terreno").val(departamento_selected_hash).selectmenu("refresh"); // SE RESTABLECE EL VALOR DE DEPARTAMENTO EN EL MENU SELECT


            var departamentoSelected = $("#departamento_busqueda_terreno option:selected").val();//USAMOS EL VALOR DEL SELECT PARA EL AJAX YNO EL DEL HASH, ESTO NOS PERMITE VERIFICAR QUE EL CODIGO ANTERIOR SE HIZO BIEN
            $.ajax({
                type: "POST",
                url: "process-request-ciudades_busqueda.php",
                data: { departamentoChoice : departamentoSelected }
            }).done(function(data){
                $("#ciudad_busqueda_terreno").html(data);//POBLAMOS EL SELECT CIUDADES
                $(".select_menu").selectmenu("refresh");
                var ciudad_selected_hash = hashArray[2];
                $("#ciudad_busqueda_terreno").val(ciudad_selected_hash).selectmenu("refresh"); // SE RESTABLECE EL VALOR DE CIUDADES EN EL MENU SELECT
            });
        //RESTABLECER LA POSICION DEL SLIDER SUPERFICIE TERRENO Y LA MEDIDA CORRECTA - M2 o HECT
            var superficie_selected_hash = hashArray[3];
            if (superficie_selected_hash >= 5001) {
              $('#opcion_terreno_hect').click();
              var superficie_terreno_hash_valToshow = (hashArray[3]*1).toFixed(0);
              $('#superficie_busqueda_terreno').parent().find('.uirange').slider("value", superficie_terreno_hash_valToshow );// RESTABLECE LA POSICION DEL SLIDER
            } else {
              $('#opcion_terreno_m2').click();
              var superficie_terreno_hash_valToshow = (hashArray[3]*1).toFixed(0);
              $('#superficie_busqueda_terreno').parent().find('.uirange').slider("value", superficie_terreno_hash_valToshow );// RESTABLECE LA POSICION DEL SLIDER
            };
         //RESTABLECER AHORA EL LABEL DEL SLIDER TERRENO SEGUN SI ES M2 O HECT
            if (superficie_selected_hash > 5000 && superficie_selected_hash < 300000) { //no 1000 ya que da un error, al pasar a 1001 muestra 1.00 millones
              $('#superficie_busqueda_terreno').parent().find('label span[class="sup_terreno_val"]').empty().append((superficie_selected_hash/10000).toFixed(1)); //introducir dentro del span el valor del slider
              $('#superficie_busqueda_terreno').parent().find('label span[class="hect"]').empty().append(" ha");
            };
            if (superficie_selected_hash <= 5000) {
              $('#superficie_busqueda_terreno').parent().find('label span[class="sup_terreno_val"]').empty().append(superficie_selected_hash); //introducir dentro del span el valor del slider
              $('#superficie_busqueda_terreno').parent().find('label span[class="hect"]').empty().html(" m&sup2");
            };

            if (superficie_selected_hash > 300000 && superficie_selected_hash <= 330000 && superficie_selected_hash != 325000) {
                // force it to 0 between -1 and 1.
                $('#superficie_busqueda_terreno').parent().find('.uirange').slider('value', 325000);
                $('#superficie_busqueda_terreno').parent().find('label span[class="sup_terreno_val"]').empty().append(" > 30 ha");
                $('#superficie_busqueda_terreno').parent().find('label span[class="hect"]').empty();
            };
            $("#superficie_busqueda_terreno").val(superficie_selected_hash);// RESTABLECE EL VALOR DEL SLIDER

            var elem_precio_casa = $('#precio_busqueda_terreno').parent();
            if (estado == 'En Venta') {
                  var precio_selected_hash = hashArray[4];

                  //TODOS ESTOS IFs SON PARA RESTABLECER EL LABEL DEL SLIDER PRECIO
                    
                  elem_precio_casa.find('label span[class="precio_val"]').empty().append((Math.round(precio_selected_hash)).toLocaleString('fr-FR')); //introducir dentro del span el valor del slider
                  elem_precio_casa.find('label span[class="millon"]').empty().append(moneda);
                
                if (precio_selected_hash > (Math.round(2300000*cambio))) {
                      // force it to 0 between -1 and 1.
                      elem_precio_casa.find('.uirange').slider('value', (Math.round(100000000*cambio)));
                      elem_precio_casa.find('label span[class="precio_val"]').empty().append(` > ${(Math.round(2000000*cambio)).toLocaleString('fr-FR')} ${moneda}`);
                      elem_precio_casa.find('label span[class="millon"]').empty();
                };
            };

            if (estado == 'En Alquiler') {
                var precio_selected_hash = hashArray[4];
                if (precio_selected_hash >= (Math.round(350*cambio)) && precio_selected_hash < (Math.round(2000*cambio))) { //no 1000 ya que da un error, al pasar a 1001 muestra 1.00 millones
                  elem_precio_casa.find('label span[class="precio_val"]').empty().append((Math.round(precio_selected_hash)).toLocaleString('fr-FR')); //introducir dentro del span el valor del slider
                };
                if (precio_selected_hash >= (Math.round(2000*cambio))) { //no 1000 ya que da un error, al pasar a 1001 muestra 1.00 millones
                  elem_precio_casa.find('label span[class="precio_val"]').empty().append(` > ${(Math.round(2000*cambio)).toLocaleString('fr-FR')}`); //introducir dentro del span el valor del slider
                };
              };

            elem_precio_casa.find('.uirange').slider("value", precio_selected_hash);// RESTABLECE LA POSICION DEL SLIDER
            $("#precio_busqueda_terreno").val(precio_selected_hash);// RESTABLECE EL VALOR DEL SLIDER

            //RESTABLECE LOS STICKERS RESULTADOS DE BUSQUEDA

            if (hashArray[3] > 300000) {
              var superficie_pin_hash = ">"+30;
              var superficie_pin_medida_hash = " ha";
            };
            if (hashArray[3] >= 5000 && hashArray[3] <= 300000) {
              var superficie_pin_hash = "<"+(hashArray[3]/10000).toFixed(1);
              var superficie_pin_medida_hash = " ha";
            };
            if (hashArray[3] < 5000) {
              var superficie_pin_hash = "<"+(hashArray[3]*1).toFixed(0);
              var superficie_pin_medida_hash = ' m&sup2';
            };


            if (estado == 'En Venta') {
              if (hashArray[4] <= (Math.round(2000000*cambio))) {
                var precio_pin_hash = "< "+ ((parseInt(hashArray[4])).toLocaleString('fr-FR'));
                var precio_pin_amplitud_hash = " " + moneda;
              };
              if (hashArray[4] > (Math.round(2000000*cambio))) {
                var precio_pin_hash = "> " +  ((Math.round(2000000*cambio)).toLocaleString('fr-FR'));
                var precio_pin_amplitud_hash = " " + moneda;
              };
            };
            if (estado == 'En Alquiler') {
              if (hashArray[4] >= (Math.round(350*cambio)) && hashArray[4] < (Math.round(2000*cambio))) {
                var precio_pin_hash = "< " + ((parseInt(hashArray[4])).toLocaleString('fr-FR'));
                var precio_pin_amplitud_hash = " " + moneda;
              };
              if (hashArray[4] >= (Math.round(2000*cambio))) {
                var precio_pin_hash = "> " + ((Math.round(2000*cambio)).toLocaleString('fr-FR'));
                var precio_pin_amplitud_hash = " " + moneda;
              };
            };

            $('#texto_resultado_busqueda').html('<p class="titulo_texto_resultado">Resultado de búsqueda:</p><div class="pin_resultado_busqueda">'+hashArray[1]+'</div><div class="pin_resultado_busqueda">'+hashArray[2]+'</div><div class="pin_resultado_busqueda">'+superficie_pin_hash+superficie_pin_medida_hash+'</div><div class="pin_resultado_busqueda">'+precio_pin_hash+precio_pin_amplitud_hash+'</div><div class="pin_resultado_busqueda_editar" name="casa"><span class="far fa fa-edit" onclick="open_search_panel(this)"></span></div><div class="pin_resultado_busqueda_cerrar" name="terreno"><span class="fas fa fa-trash" onclick="remove_search_results(this)"></span></div>');

            $.ajax({
                type: "POST",
                url: "process-request-inmuebles-thumbnails-busqueda-venta.php",
                data: { tipo_bien_selected : hashArray[0], departamento_busqueda : hashArray[1], ciudad_busqueda : hashArray[2], superficie_busqueda : hashArray[3], precio_busqueda : hashArray[4], page_requested : hashArray[5], price_sorting : hashArray[6], estado : estado }
            }).done(function(data){
                $("#tab4").html(data);
            });

            $(window).scrollTop(0);

          };

          // CODIGO PARA ABRIR LA FICHA BIEN EN CASO QUE ESTE DENTRO DEL HASH CON RESULTADO DE BUSQUEDA AVANZADA
          if (active_tab_name_hashinfo == 'casa' || active_tab_name_hashinfo == 'departamento' || active_tab_name_hashinfo == 'local') {
            if (hashArray[9]) {
              back_open_ficha_bien(hashArray[9].replace(/%23/g, "#"));
            };
          }else {//sino, estamos en terrenos
            if (hashArray[7]) {
              back_open_ficha_bien(hashArray[7].replace(/%23/g, "#"));
            };
          };


    } else { // LO QUE SUCEDE CUANDO NO HUBO RESULTADOS DE BUSQUEDA AVANZADA, SE RECARGAN LOS BIENES RECOMDEDADOS DEL TAB ACTIVO
      var back_button_clicked = "no_search_done"; // SE DEFINE UN FLAG PARA DETERMINAR EL EVENTO CLICK DEL BROWSER BACK/FOWARD BUTTON
      $("ul.lista_tabs li.tab a[name='"+active_tab_name_hashinfo+"']").trigger('click', {param : back_button_clicked}); // SE DETONA EL EVENTO CLICK DEL TAB Y SE ENVIA EL FLAG
      $('.fa-trash').click();

      // CODIGO PARA ABRIR LA FICHA BIEN EN CASO QUE ESTE DENTRO DEL HASH DE SOLO BIENES RECOMENDADOS
      if (hashArray[0].includes("~")) {//significa que hay una referencia bien en el hash
        var hash_referencia = hashArray[0].trim().split("~");//gets de tab name form the hash
        back_open_ficha_bien(hash_referencia[1].replace(/%23/g, "#"));
      };
    };


      }else { // VERIFICA SI SE HA RETORNADO AL INICIO DEL venta_inmueble.php FIRST ENTRY, EN ESE CASO SE RECARGA BIENES RECOMENDADOS PARA CASAS

        $('ul.lista_tabs li.tab a:first').addClass('active_tab');
        $('#sections_tabs_contenedor article').hide();
        $('#sections_tabs_contenedor article:first').show();
                      // !!! aca no se define ningun window.state o window.pushstate, asi se podra salir de la pagina inmuebles al hacer suficientes browser back button clicks
        var tipo_bien_start = 'casa';
        $.ajax({
            type: "POST",
            url: "process-request-inmuebles-thumbnails.php",
            data: { tipo_bien_selected : tipo_bien_start, estado : estado }
        }).done(function(data){
            $("#tab1").html(data);
            $('#texto_resultado_busqueda').html('<p class="titulo_texto_resultado">Bienes recomendados:</p>');
        });


      };
  };



  });
});
