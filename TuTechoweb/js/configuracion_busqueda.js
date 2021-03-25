$(document).ready(function(){
  jQuery(function($){

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
          elem.find('label span').empty().append((ui.value)); //introducir dentro del span el valor del slider
          input.val(ui.value); // intrducir dentro del input el valor del slider
      };
      options.value = input.val();
      options.range = 'min';

   //fin-OPTIONS

      elem.find('.uirange').slider(options); // el slider dentro del nuevo div tendra los diferentes parametros options

      elem.find('label span').empty().append(input.val());// poner el valor predefinido inicial del input dentro del span

      input.hide();
    });

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
                  elem.find('label span[class="hect"]').empty().append(" ha");
                };
                if (ui.value <= 5000) {
                  elem.find('label span[class="sup_terreno_val"]').empty().append((ui.value).toLocaleString('fr-FR')); //introducir dentro del span el valor del slider
                  elem.find('label span[class="hect"]').empty().html(" m&sup2");
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

              const superf = parseInt(elem.find('label span[class="sup_terreno_val"]').text()).toLocaleString('fr-FR');
              elem.find('label span[class="sup_terreno_val"]').text(`${superf}`);

              input.hide();
            });

// POPULATE SELECT FIELDS WITH INFO FROM DATABASE ######################

      $('#departamento_busqueda_casa').on('selectmenuchange', function() {
        var departamentoSelected = $("#departamento_busqueda_casa option:selected").val();
        $.ajax({
            type: "POST",
            url: "process-request-ciudades_busqueda.php",
            data: { departamentoChoice : departamentoSelected }
        }).done(function(data){
            $("#ciudad_busqueda_casa").html(data);
            $(".select_menu").selectmenu("refresh");
        });
      });

      $('#departamento_busqueda_departamento').on('selectmenuchange', function() {
        var departamentoSelected = $("#departamento_busqueda_departamento option:selected").val();
        $.ajax({
            type: "POST",
            url: "process-request-ciudades_busqueda.php",
            data: { departamentoChoice : departamentoSelected }
        }).done(function(data){
            $("#ciudad_busqueda_departamento").html(data);
            $(".select_menu").selectmenu("refresh");
        });
      });

      $('#departamento_busqueda_local').on('selectmenuchange', function() {
        var departamentoSelected = $("#departamento_busqueda_local option:selected").val();
        $.ajax({
            type: "POST",
            url: "process-request-ciudades_busqueda.php",
            data: { departamentoChoice : departamentoSelected }
        }).done(function(data){
            $("#ciudad_busqueda_local").html(data);
            $(".select_menu").selectmenu("refresh");
        });
      });

      $('#departamento_busqueda_terreno').on('selectmenuchange', function() {
        var departamentoSelected = $("#departamento_busqueda_terreno option:selected").val();
        $.ajax({
            type: "POST",
            url: "process-request-ciudades_busqueda.php",
            data: { departamentoChoice : departamentoSelected }
        }).done(function(data){
            $("#ciudad_busqueda_terreno").html(data);
            $(".select_menu").selectmenu("refresh");
        });
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
              elem.find('label span[class="hect"]').empty().html(" m&sup2");

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
                  elem.find('label span[class="hect"]').empty().append(" ha");
                };
                if (ui.value <= 5000) {
                  elem.find('label span[class="sup_terreno_val"]').empty().append((ui.value).toLocaleString('fr-FR')); //introducir dentro del span el valor del slider
                  elem.find('label span[class="hect"]').empty().html(" m&sup2");
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
              const superf = parseInt(elem.find('label span[class="sup_terreno_val"]').text()).toLocaleString('fr-FR');
              elem.find('label span[class="sup_terreno_val"]').text(`${superf}`);

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
            elem.find('label span[class="hect"]').empty().append(" ha");

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
                elem.find('label span[class="hect"]').empty().append(" ha");
              };
              if (ui.value <= 5000) {
                elem.find('label span[class="sup_terreno_val"]').empty().append((ui.value).toLocaleString('fr-FR')); //introducir dentro del span el valor del slider
                elem.find('label span[class="hect"]').empty().html(" m&sup2");
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
            
            const superf = parseInt(elem.find('label span[class="sup_terreno_val"]').text()).toLocaleString('fr-FR');
            elem.find('label span[class="sup_terreno_val"]').text(`${superf}`);

            input.hide();
          });

          // fin codigo recrear slider sup terreno
          };
      });


// CODIGO SELECT-MENU ############################################

    $(function(){
      $(".select_menu").selectmenu();
    });

// CODIGO CHECKBOX-RADIO #########################################

    $(function(){
      $(".form_checkbox input").checkboxradio();
    });

    $( function() {
      $( "input.check_box_radio" ).checkboxradio();
    } );

// CODIGO BUTTON #################################################

    $( function() {
        $(".form_btn button").button();
        $("button").click(function(event){
          $(".pin_resultado_busqueda_order").remove();
          event.preventDefault();
        });
    } );

  });
});
