$(document).ready(function(){
    jQuery(function($){

//SE DEFINE EL CONTADOR DE ELEMENTOS, LA LISTA DE REFERENCIAS, LA LISTA DE REFERENCIAS QUE MOSTRAR A CONTINUACION Y EL STEP DE ELEMENTO COMO VARIABLES O CONSTANTES GLOBALES
var total_elements; // TODOS LOS ELEMENTOS QUE HAY EN EXISTENCIA PARA EL TAB ACTUAL
var elements_to_show; // ELEMENTOS QUE MOSTRAR A CONTINUACION SI ES QUE HAY
var elements_count;  // CONTADOR DE THUMBS MOSTRADOS
const step_of_elements = 6; // CUANTOS ELEMENTOS NUEVOS MAX SE AGREGAN AL FINAL DEL SCROLL
const first_charge_quantity = 10; // CUANTOS ELEMENTOS CARGAR EN UN INICIO

function open_ficha_bien(referencia_to_open){
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
          data: { ficha_bien_requested : referencia_to_sent, ficha_bien_tipo_requested : tipo_to_sent, estado: estado },
      }).done(function(data){
        $('.popup_ficha_bien').html(data);
        $("body").addClass('ficha_active');
        });
};

function tag_current_tab_mechanism(){//funcion que permite controlar el tag_current_tab al hacer scroll hacia abajo, y saber siempre en que TAB uno s eencuentra, esto sera llamado con la funcion scroll_check_timer
  var current_tab = window.location.hash.substr(1).replace(/%20/g, " ").trim().split("&");//gets de tab name form the hash
  if (current_tab[0].includes("~")) {
    var hash_tab = current_tab[0].trim().split("~");
    console.log(hash_tab[0] + " Y " + hash_tab[1]);

    if (hash_tab[0] == "local") {
      var tag_current_tab_text = (hash_tab[0])[0].toUpperCase() + hash_tab[0].slice(1) + "es";
    } else {
      var tag_current_tab_text = (hash_tab[0])[0].toUpperCase() + hash_tab[0].slice(1) + "s";
    };
  }else {
    if (current_tab[0] == "local") {
      var tag_current_tab_text = (current_tab[0])[0].toUpperCase() + current_tab[0].slice(1) + "es";
    } else {
      var tag_current_tab_text = (current_tab[0])[0].toUpperCase() + current_tab[0].slice(1) + "s";
    };
  };


		if( $(window).scrollTop() > 250 ){
			$('.tag_current_tab').slideDown(300).html(tag_current_tab_text);
		} else {
			$('.tag_current_tab').slideUp(300);
		}
};

$('.ir-arriba').click(function(){ //cuando se haga click en "ir arriba", se sube la pantalla con una animacion suave que dura 300ms
  $('body, html').animate({
    scrollTop: '0px'
  }, 300)
});

// SE DEFINE LA FUNCION ENCARGADA DE CARGAR LOS PRIMEROS ELEMENTOS EN CASO DE FIRST ENTRY, DE PAGE RELOAD O DE CARGA DE UN NUEVO TAB POR CLICK EVENT
function first_elements_charge(){ //the call is at the end of the js
  if (window.location.hash.includes("&") == false) {//check if bienes recomendados is needed
    if (window.location.hash.substr(1).includes("~")) {
      var hash_tab = window.location.hash.substr(1).trim().split("~");//gets de tab name form the hash
      var current_tab = hash_tab[0];//gets de tab name form the hash
    }else {
      var current_tab = window.location.hash.substr(1);//gets de tab name form the hash
    };

    if (elements_count === undefined) {// verifica si es First Entry o Refreshed page
      // if (window.location.hash) {
      //   alert("there is something in hash");
      // }else {
      //   alert("first Entry or reload");
      // };

      $.ajax({
            type: "POST",
            url: "process-request-inmuebles-thumbnails.php",
            data: { list_of_references_requested : current_tab, estado : estado },
            dataType: 'json'//SE ESPECIFICA QUE SE ESPERA DATA EN MODO JSON
        }).done(function(data){
          total_elements = data;//EL DATA EN JASON SE GUARDA COMO UN ARREGLO JS
          console.log(data);
          if (total_elements.length == 0) {//verifica si la db esta vacia
            elements_count = 0;//si lo esta entonces se define el count a 0
          } else {
            if (total_elements.length >= first_charge_quantity) {//check si hay almenos 15 elementos que mostrar
              elements_to_show = total_elements.slice(0, first_charge_quantity);
              elements_count = first_charge_quantity;
            }else {//si no, entonces se muestra lo que hay
              elements_to_show = total_elements;
              elements_count = total_elements.length;
            };
            console.log("primera carga:")
            console.log(elements_to_show);
            console.log("Count: " + elements_count);
            $.ajax({
                  type: "POST",
                  url: "process-request-inmuebles-thumbnails.php",
                  data: { tipo_bien_selected : current_tab, first_thumbs_default : elements_to_show, estado : estado},
              }).done(function(data){
                    if (current_tab == 'casa') {
                       $("#tab1").html(data);
                     };
                     if (current_tab == 'departamento') {
                       $("#tab2").html(data);
                     };
                     if (current_tab == 'local') {
                       $("#tab3").html(data);
                     };
                     if (current_tab == 'terreno') {
                       $("#tab4").html(data);
                     };

                     $('#texto_resultado_busqueda').html('<p class="titulo_texto_resultado">Bienes recomendados:</p>');

                     $(window).scrollTop(0);
              });
          };
        });

    };
  };
};

// SE DEFINE LA FUNCION ENCARGADA DE LIMITAR EL INTERVALO DE TIEMPO QUE PASA ANTES DE VERIFICAR SI EL SCROLL DE LA PAGINA LLAMA O NO A SUMAR MAS ELEMENTOS INMUEBLES AL TAB ACTUAL
    function scroll_check_timer(fn, wait){//the call is at the en of the js
      var scroll_time = Date.now();
      return function(){
        if ((scroll_time + wait - Date.now()) < 0) {
          fn();//llama la funcion en param, que es scroll_check_position
          tag_current_tab_mechanism();//llama la funcion de controla el tag_current_tab
          scroll_time = Date.now();
        };
      };
    };

// SE DEFINE LA FUNCION ENCARGADA DE VERIFICAR LA POSITION DEL SCROLL CON RESPECTO AL BOTTOM Y CARGAR NUEVOS ELEMENTOS AL TAB ACTUAL
    function scroll_check_position(){
      if (window.location.hash.includes("&") == false) {//check if bienes recomendados is needed

        if (($(window).scrollTop() > 1300)) {//este condicional controla el boton "Ir arriba"
          $('.ir-arriba').slideDown(300);//lo hace aparecer
        } else {
          $('.ir-arriba').slideUp(300);//lo hace desaparecer
        };

        if (($(window).scrollTop() + $(window).height()) > ($(document).height() - 200)) {
          $('.ir-arriba').css('display', 'none');
        };//si se acerca al footer, se hace desaparecer el boton "ir arriba", para que no obstruya contenido

        if (window.location.hash.substr(1).includes("~")) {
          var hash_tab = window.location.hash.substr(1).trim().split("~");//gets de tab name form the hash
          var current_tab = hash_tab[0];//gets de tab name form the hash
        }else {
          var current_tab = window.location.hash.substr(1);//gets de tab name form the hash
        };

        if (elements_count === undefined) {// verifica si ya se hizo la primera carga de elementos
          //do nothing
        }else {
          if (($(window).scrollTop() + $(window).height()) > ($(document).height() - 580)) {//check the scroll distance regarding the bottom
            console.log("scroll botton detected");
            if (total_elements.length == elements_count) {
              //NO NOTHING, THERE IS NO MORE ELEMENTS TO SHOW
              console.log("there is NO more elements to show");
            } else {
              console.log("there is MORE elements to show");
              if ((total_elements.length - elements_count) >= step_of_elements) {
                elements_to_show = total_elements.slice(elements_count, (elements_count + step_of_elements));
                elements_count = elements_count + step_of_elements;
              } else {
                elements_to_show = total_elements.slice(elements_count);
                elements_count = total_elements.length;
              };

              console.log(elements_to_show);
              console.log(elements_count);

              $.ajax({
                    type: "POST",
                    url: "process-request-inmuebles-thumbnails.php",
                    data: { tipo_bien_selected : current_tab, next_thumbs_default : elements_to_show, estado : estado},
                }).done(function(data){
                      if (current_tab == 'casa') {
                         $("#tab1").append(data);
                       };
                       if (current_tab == 'departamento') {
                         $("#tab2").append(data);
                       };
                       if (current_tab == 'local') {
                         $("#tab3").append(data);
                       };
                       if (current_tab == 'terreno') {
                         $("#tab4").append(data);
                       };
                });
            };
          };
        };
      };
    };

// SE DEFINE LA FUNCION ENCARGADA DE CARGAR THUMBS CON LA INFO DEL HASH SI EXISTE, YA SEAN RESUTADOS DE BUSQUEDA O SOLO RECOMENDADOS
  function cargar_thumbs_segun_hash(){
    if (hashArray[1]) {// SI EXISTE INFORMACION ADICIONAL AL DEL TAB, ENTONCES HUBO RESULTADOS DE BUSQUEDA AVANZADA, REFRESH CASE ONLY
      $('ul.lista_tabs li.tab a').removeClass('active_tab');//CAMBIA ESTLIOS DE ACTIVE TAB
      $("ul.lista_tabs li.tab a[name='"+active_tab_name_hashinfo+"']").addClass('active_tab');
      $('#sections_tabs_contenedor article').hide();

      var activeTab = $("ul.lista_tabs li.tab a[name='"+active_tab_name_hashinfo+"']").attr('href');
      $(activeTab).show();//MUESTRA EL SECTION CORRESPONDIENTE AL TAB ACTIVO

      var search_associated_tab = 'div.' + $("ul.lista_tabs li.tab a[name='"+active_tab_name_hashinfo+"']").attr('id');
      $('div.busqueda_contenedor_interno').css('display', 'none');
      $(search_associated_tab).css('display', 'flex'); // PERMITE CARGAR EL MENU BUSQUEDA AVANZADA CORRESPONDIENTE A CADA TAB

      window.precio_order_backbutton_refresh(); //para restablecer el boton price sorting a su estado segun la info del hash en caso de refresh

      //####### ACA SE BUSCA RESTABLECER LOS VALUES DES MENU CONFIG BUSQUEDA DE DEPARTAMENTOS CON LA INFO DEL HASH

                if (active_tab_name_hashinfo == 'casa') {

                  var departamento_selected_hash = hashArray[1]; // SE ALMACENA EL DEPARTAMENTO SELECCIONADO QUE APARECE EN EL HASH
                  $("#departamento_busqueda_casa").val(departamento_selected_hash).selectmenu().selectmenu("refresh"); // SE RESTABLECE EL VALOR DE DEPARTAMENTO EN EL MENU SELECT

                  var departamentoSelected = $("#departamento_busqueda_casa option:selected").val();//USAMOS EL VALOR DEL SELECT PARA EL AJAX YNO EL DEL HASH, ESTO NOS PERMITE VERIFICAR QUE EL CODIGO ANTERIOR SE HIZO BIEN
                  $.ajax({
                      type: "POST",
                      url: "process-request-ciudades_busqueda.php",
                      data: { departamentoChoice : departamentoSelected }
                  }).done(function(data){
                      $("#ciudad_busqueda_casa").html(data);//POBLAMOS EL SELECT CIUDADES
                      $(".select_menu").selectmenu().selectmenu("refresh");
                      var ciudad_selected_hash = hashArray[2];
                      $("#ciudad_busqueda_casa").val(ciudad_selected_hash).selectmenu().selectmenu("refresh"); // SE RESTABLECE EL VALOR DE CIUDADES EN EL MENU SELECT
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

                };

      //####### ACA SE BUSCA RESTABLECER LOS VALUES DES MENU CONFIG BUSQUEDA DE DEPARTAMENTOS CON LA INFO DEL HASH

                if (active_tab_name_hashinfo == 'departamento') {

                  var departamento_selected_hash = hashArray[1]; // SE ALMACENA EL DEPARTAMENTO SELECCIONADO QUE APARECE EN EL HASH
                  $("#departamento_busqueda_departamento").val(departamento_selected_hash).selectmenu().selectmenu("refresh"); // SE RESTABLECE EL VALOR DE DEPARTAMENTO EN EL MENU SELECT


                  var departamentoSelected = $("#departamento_busqueda_departamento option:selected").val();//USAMOS EL VALOR DEL SELECT PARA EL AJAX YNO EL DEL HASH, ESTO NOS PERMITE VERIFICAR QUE EL CODIGO ANTERIOR SE HIZO BIEN
                  $.ajax({
                      type: "POST",
                      url: "process-request-ciudades_busqueda.php",
                      data: { departamentoChoice : departamentoSelected }
                  }).done(function(data){
                      $("#ciudad_busqueda_departamento").html(data);//POBLAMOS EL SELECT CIUDADES
                      $(".select_menu").selectmenu().selectmenu("refresh");
                      var ciudad_selected_hash = hashArray[2];
                      $("#ciudad_busqueda_departamento").val(ciudad_selected_hash).selectmenu().selectmenu("refresh"); // SE RESTABLECE EL VALOR DE CIUDADES EN EL MENU SELECT
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

                };

      //####### ACA SE BUSCA RESTABLECER LOS VALUES DES MENU CONFIG BUSQUEDA DE LOCALES CON LA INFO DEL HASH

                if (active_tab_name_hashinfo == 'local') {

                  var departamento_selected_hash = hashArray[1]; // SE ALMACENA EL DEPARTAMENTO SELECCIONADO QUE APARECE EN EL HASH
                  $("#departamento_busqueda_local").val(departamento_selected_hash).selectmenu().selectmenu("refresh"); // SE RESTABLECE EL VALOR DE DEPARTAMENTO EN EL MENU SELECT


                  var departamentoSelected = $("#departamento_busqueda_local option:selected").val();//USAMOS EL VALOR DEL SELECT PARA EL AJAX YNO EL DEL HASH, ESTO NOS PERMITE VERIFICAR QUE EL CODIGO ANTERIOR SE HIZO BIEN
                  $.ajax({
                      type: "POST",
                      url: "process-request-ciudades_busqueda.php",
                      data: { departamentoChoice : departamentoSelected }
                  }).done(function(data){
                      $("#ciudad_busqueda_local").html(data);//POBLAMOS EL SELECT CIUDADES
                      $(".select_menu").selectmenu().selectmenu("refresh");
                      var ciudad_selected_hash = hashArray[2];
                      $("#ciudad_busqueda_local").val(ciudad_selected_hash).selectmenu().selectmenu("refresh"); // SE RESTABLECE EL VALOR DE CIUDADES EN EL MENU SELECT
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

                };

      //####### ACA SE BUSCA RESTABLECER LOS VALUES DES MENU CONFIG BUSQUEDA DE TERRENO CON LA INFO DEL HASH

                if (active_tab_name_hashinfo == 'terreno') {

                  var departamento_selected_hash = hashArray[1]; // SE ALMACENA EL DEPARTAMENTO SELECCIONADO QUE APARECE EN EL HASH
                  $("#departamento_busqueda_terreno").val(departamento_selected_hash).selectmenu().selectmenu("refresh"); // SE RESTABLECE EL VALOR DE DEPARTAMENTO EN EL MENU SELECT


                  var departamentoSelected = $("#departamento_busqueda_terreno option:selected").val();//USAMOS EL VALOR DEL SELECT PARA EL AJAX YNO EL DEL HASH, ESTO NOS PERMITE VERIFICAR QUE EL CODIGO ANTERIOR SE HIZO BIEN
                  $.ajax({
                      type: "POST",
                      url: "process-request-ciudades_busqueda.php",
                      data: { departamentoChoice : departamentoSelected }
                  }).done(function(data){
                      $("#ciudad_busqueda_terreno").html(data);//POBLAMOS EL SELECT CIUDADES
                      $(".select_menu").selectmenu().selectmenu("refresh");
                      var ciudad_selected_hash = hashArray[2];
                      $("#ciudad_busqueda_terreno").val(ciudad_selected_hash).selectmenu().selectmenu("refresh"); // SE RESTABLECE EL VALOR DE CIUDADES EN EL MENU SELECT
                  });
              //RESTABLECER LA POSICION DEL SLIDER SUPERFICIE TERRENO Y LA MEDIDA CORRECTA - M2 o HECT
                  var superficie_selected_hash = hashArray[3];
                  if (superficie_selected_hash >= 5001) {
                    $("#opcion_terreno_hect").click();
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
                    var superficie_pin_hash = "> "+30;
                    var superficie_pin_medida_hash = " ha";
                  };
                  if (hashArray[3] >= 5000 && hashArray[3] <= 300000) {
                    var superficie_pin_hash = "< "+(hashArray[3]/10000).toFixed(1);
                    var superficie_pin_medida_hash = " ha";
                  };
                  if (hashArray[3] < 5000) {
                    var superficie_pin_hash = "< "+(hashArray[3]*1).toFixed(0);
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

                };



    } else {// SI NO, MOSTRAR BIENES RECOMENDADOS DEL TAB ACTIVO, REFRESH CASE ONLY
      $("ul.lista_tabs li.tab a[name='"+active_tab_name_hashinfo+"']").click();
    };

  };

// CODIGO DE CUANDO SE HACE CLICK EN ALGUN TAB INMUEBLE

    $('ul.lista_tabs li.tab a').on('click', function(event, data){ //RECOJE DATA SOLO EN CASO DE BROWSER BACK/FOWARD CLICK
      var tipo_bien_clicked = $(this).attr('name');
      var tab_que_llenar = $(this).attr("href");
      var tab_class = $(this).attr("class");


      $('ul.lista_tabs li.tab a').removeClass('active_tab');
      $(this).addClass('active_tab');
      $('#sections_tabs_contenedor article').hide(); // RESTAURA EL ESTILO ACTIVO DE LOS TAB

      if ($('#configuracion_busqueda_Toggle').css('display') == 'block') {
        $('ul.lista_tabs li.tab_gear a').click(); // CIERRA EL MENU BUSQUEDA AVANZADA SI ESTUVIERA ABIERTO
      };

      var search_associated_tab = 'div.' + $(this).attr('id');
      $('div.busqueda_contenedor_interno').css('display', 'none');
      $(search_associated_tab).css('display', 'flex'); // PERMITE CARGAR EL MENU BUSQUEDA AVANZADA CORRESPONDIENTE A CADA TAB

      var activeTab = $(this).attr('href');
      $(activeTab).show();//MUESTRA EL SECTION CORRESPONDIENTE AL TAB ACTIVO

        if(typeof (data) === 'undefined' && tab_class !== 'active_tab') { //SI EL BROWSER BACK/FOWARD BUTTON NO FUE USADO ENTONCES SE SIGUE GENERDANDO NUEVOS WINDOWS.PUSHSTATES
          var activeTab_name_clicked = $(this).attr('name');
          if (window.location.hash.includes("&") == false && window.location.hash.includes("~") == false) {
            history.pushState(activeTab_name_clicked, null, "#"+activeTab_name_clicked);
          };
          if (window.location.hash.includes("&")) {
            var hash_current_state = window.location.hash.substr(1).trim().split("&");
            if (hash_current_state[0] == 'casa' || hash_current_state[0] == 'departamento' || hash_current_state[0] == 'local') {
              if (hash_current_state[9]) {
                open_ficha_bien(hash_current_state[9].replace(/%23/g, "#"));//se abre la ficha bien
              }else {
                history.pushState(activeTab_name_clicked, null, "#"+activeTab_name_clicked);
              };
            }else {//entonces estamos en terreno
              if (hash_current_state[7]) {
                open_ficha_bien(hash_current_state[7].replace(/%23/g, "#"));//se abre la ficha bien
              }else {
                history.pushState(activeTab_name_clicked, null, "#"+activeTab_name_clicked);
              };
            };

          };

        }else { // SI FUE USADO ENTONCES NO SE CREAN NUEVOS PUSHSTATES, YA QUE SI ASI FUERA, CREARIA UN BUCLE INFINITO
          // nothing happens
        };

        if (window.location.hash.includes("&") == false) {//verifica si hay que cargar elementos recomendados
          elements_count = void(0);// set count to undefined in order to be able to launch de first_elements_charge function
          $('#texto_resultado_busqueda').html('<p class="titulo_texto_resultado">Bienes recomendados:</p>');
          $("#paginacion_container").empty();
          $("#paginacion_container_top").empty();
          $(".pin_resultado_busqueda_count").remove();
          $(".pin_resultado_busqueda_order").remove();
          first_elements_charge();//se lanza la funcion de carga de priemros elementos
          if (window.location.hash.substr(1).includes("~")) {
            var hash_referencia = window.location.hash.substr(1).trim().split("~");//gets de tab name form the hash
            open_ficha_bien(hash_referencia[1].replace(/%23/g, "#"));
          };
        }else {
          if (typeof (data) === 'undefined') {//si se hizo BACK a bienes recomendados
            var activeTab_name_clicked = $(this).attr('name');

            var hash_current_state = window.location.hash.substr(1).trim().split("&");//se almacena el hash
            if (hash_current_state[0] == 'casa' || hash_current_state[0] == 'departamento' || hash_current_state[0] == 'local') {
              if (hash_current_state[9]) {//si existe referencia en el hash
                open_ficha_bien(hash_current_state[9].replace(/%23/g, "#"));//se abre la ficha bien
              }else {//si no hay ningun hash
                history.pushState(activeTab_name_clicked, null, "#"+activeTab_name_clicked);
              };
            }else {//sino, estamos en terrenos
              if (hash_current_state[7]) {
                open_ficha_bien(hash_current_state[7].replace(/%23/g, "#"));
              }else {
                history.pushState(activeTab_name_clicked, null, "#"+activeTab_name_clicked);
              };
            };

            $('#texto_resultado_busqueda').html('<p class="titulo_texto_resultado">Bienes recomendados:</p>');
            $("#paginacion_container").empty();
            $("#paginacion_container_top").empty();
            $(".pin_resultado_busqueda_count").remove();
            $(".pin_resultado_busqueda_order").remove();
            elements_count = void(0);// set count to undefined in order to be able to launch de first_elements_charge function
            first_elements_charge();//se lanza la funcion de carga de priemros elementos
          };

        };
      return false; //PERMITE QUE NO APAREZCA EL HREF DEL TAB CLICKEADO EN EL URL
    });


// Display block contenido del TAB casa por default y display el menu busqueda avanzada correspondiente y cargar thumbs

    if (window.history.state == null) { //SI NO HAY NINGUN STATE DEFINIDO, SIGNIFICA QUE ES LA PRIMERA ENTRADA
      $('ul.lista_tabs li.tab a:first').addClass('active_tab');//ACTIVAR EL PRIMER TAB
      $('#sections_tabs_contenedor article').hide();//OCULTAR TODOS LOS SECTION
      $('#sections_tabs_contenedor article:first').show();//MOSTRAR EL PRIMER SECTION

      if (window.location.hash) {// VERIFICA QUE LA PRIMERA ENTRADA CONTIENE HASH, SI ES ASI, ES UN LINK/URL COMPARTIDO
        history.replaceState("casa", null, window.location.hash);//SE CREA UN STATE CON LA INFO DEL HASH DEL LINK/URL COMPARTIDO, PARA PODER USARLO EN EL BACK EVENT Y EL REFRESH EVENT SIN PROBLEMAS
        var hashVal = window.location.hash.substr(1);//SOLO QUITA EL HASHTAG DEL URL VALUE
        var hashArray = hashVal.replace(/%20/g, " ").trim().split("&");
        if (hashVal.includes("&") == false) {

            if (hashVal.includes("~")) {
              var hash_tab = hashVal.trim().split("~");//gets de tab name form the hash
              var active_tab_name_hashinfo = hash_tab[0];//gets de tab name form the hash

              open_ficha_bien(hash_tab[1].replace(/%23/g, "#"));//se abre la ficha bien correspondiente
            }else {
              var active_tab_name_hashinfo = hashVal;//gets de tab name form the hash
            };
        }else {

            var active_tab_name_hashinfo = hashArray[0];//gets de tab name form the hash

            if (hashArray[0] == 'casa' || hashArray[0] == 'departamento' || hashArray[0] == 'local') {
              if (hashArray[9]) {//si existe referencia en el hash
                open_ficha_bien(hashArray[9].replace(/%23/g, "#"));//se abre la ficha bien
              };
            }else {//entonces estamos en terreno
              if (hashArray[7]) {//si existe referencia en el hash
                open_ficha_bien(hashArray[7].replace(/%23/g, "#"));//se abre la ficha bien
              };
            };

        };


        cargar_thumbs_segun_hash();//se cargan los thumbs con la info del hash del LINK/URL compartido, ya sean resultados sugeridos o resultados de busqueda avanzada

      }else {
        history.replaceState("casa", null, "#casa"); // SE REMPLAZA EL STATE VACIO PARA PERMITIR LA LOGICA DE BACK AL TAB CASA INITIAL Y QUE NO SE RECARGUE UNA PAGINA VACIA POR ESTAR EL HASH VACIO
      };

      document.cookie = "refresh_inmuebles_history=;SameSite=Lax";// SE CREA EL COOKIE VACIO POR SER LA PRIMERA ENTRADA, PERMITIRA EVITAR QUE REFRESCAR EXACTAMENTE LA MISMA PAGINA Y MISMO HASH GENERE DEMASIADOS HISTORY STATES SIN RAZON


    } else { //SI NO ES LA PRIMERA ENTRADA, ENTONCES SE ESTA REFRESCANDO UNA PAGINA YA NAVEGADA, QUE PUEDE O NO TENER RESULTADOS DE BUSQUEDA AVANZADA
      var hashVal = window.location.hash.substr(1);//SOLO QUITA EL HASHTAG DEL URL VALUE
      var hashArray = hashVal.replace(/%20/g, " ").trim().split("&");

      if (hashVal.includes("&") == false) {

            if (hashVal.includes("~")) {
              var hash_tab = hashVal.trim().split("~");//gets de tab name form the hash
              var active_tab_name_hashinfo = hash_tab[0];//gets de tab name form the hash

              open_ficha_bien(hash_tab[1].replace(/%23/g, "#"));//se abre la ficha bien correspondiente
            }else {
              var active_tab_name_hashinfo = hashVal;//gets de tab name form the hash
            };

      }else {
        var active_tab_name_hashinfo = hashArray[0];//gets de tab name form the hash
        if (hashArray[0] == 'casa' || hashArray[0] == 'departamento' || hashArray[0] == 'local') {
          if (hashArray[9]) {//si existe referencia en el hash
            open_ficha_bien(hashArray[9].replace(/%23/g, "#"));//se abre la ficha bien
          };
        }else {//entonces estamos en terreno
          if (hashArray[7]) {//si existe referencia en el hash
            open_ficha_bien(hashArray[7].replace(/%23/g, "#"));//se abre la ficha bien
          };
        };

      };


      function getCookie(cookiename) //DEFINE LA FUNCION PARA RECUPERAR COOKIES
          {
          // Get name followed by anything except a semicolon
          var cookiestring=RegExp(""+cookiename+"[^;]+").exec(document.cookie);
          // Return everything after the equal sign, or an empty string if the cookie name not found
          return decodeURIComponent(!!cookiestring ? cookiestring.toString().replace(/^[^=]+./,"") : "");
        };

      var cookie_refresh_inmuebles_history = getCookie('refresh_inmuebles_history');//ALMACENA EL COOKIE PARA COMPARARLO
      if (cookie_refresh_inmuebles_history == '') {//SI EL COOKIE NO TIENE VALOR< ES LA PRIMERA VEZ EN ESA PAGINA< ENTONCES SE REESCRIBE EL COOKIE CON EL VALOR DEL HASH
        document.cookie = "refresh_inmuebles_history=" + hashVal + ";SameSite=Lax";
      } else {//NO ES LA PRIMERA VEZ EN LA PAGINA, SE COMPARA EL COOKIE CON EL HASH ACTUAL
        if (cookie_refresh_inmuebles_history !== hashVal.replace(/%20/g, " ")) {// SE RECARGAN PAGINAS CON HASH DISTINTOS
          document.cookie = "refresh_inmuebles_history=" + hashVal + ";SameSite=Lax";
        } else {// SE RECARGA EXACTAMENTE LA MISMA PAGINA QUE ANTES< PARA EVITAR CREACIONDE HISTORY STATE< SE HACE UN BACK
          document.cookie = "refresh_inmuebles_history=randomtext;SameSite=Lax";
          window.history.back();
        };
      };

      cargar_thumbs_segun_hash(); // se cargan los thumbs recomendados si no hay busqueda especificada en el hash. y si lo hay se cargan los resultados de busqueda
    };



// Cambiar orientacion de la flechita del TAB Configuracion de Busqueda

    var geartab_contador = 1;
    $('ul.lista_tabs li.tab_gear a').click(function(){
      // alert("contador es = " + pin_precio_contador);
      if(geartab_contador == 1)
         {$('li.tab_gear a span.arrow_js').removeClass('fa-angle-down');
          $('li.tab_gear a span.arrow_js').addClass('fa-angle-up');
         geartab_contador = 0;
         }
      else
         {geartab_contador = 1;
         $('li.tab_gear a span.arrow_js').removeClass('fa-angle-up');
         $('li.tab_gear a span.arrow_js').addClass('fa-angle-down');
         }
       });

// Permite la aparicion/desaparicion del formulario de Configuraciond e Busqueda

    $('ul.lista_tabs li.tab_gear a').click(function(){
      $('#configuracion_busqueda_Toggle').slideToggle();
      return false; //PARA EVITAR QUE APAREZCA EL HREF DEL TAB GEAR EN EL URL DE LA PAGINA VISUALIZADA
    });


//SI ES FIRST ENTRY O ES REFRESH Y SE NECESITA MOSTRAR BIENES RECOMENDADOS, LA FUNCION SIGUIENTE HARA EL TRABAJO

      first_elements_charge();

//SE PONE EN MARCHA EL TIMER QUE VERIFICA COSNTANTEMENTE LA POSITION DEL SCROLL PARA CARGAR O NO NUEVOS ELEMENTOS RECOMENDADOS

      window.addEventListener('scroll', scroll_check_timer(scroll_check_position, 100));


  });
});
