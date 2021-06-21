$(document).ready(function () {
  jQuery(function ($) {


  const pais_cookie = Cookies.get('tutechopais');

  let datos_pais;
  $.ajax({
    type: "POST",
    url: "../../contenido/m5/process-request-coordenadas-paises.php",
    dataType: 'json',
    async: false,
  }).done(function(data){
    datos_pais = data;
  });    
  
  $(".departamento_label").html(`${datos_pais['org_territorial']} :`);


  // Abrir llaves detalles
  $('.show_all_keys').on('click', function(){
    const agencia_id = $("#agencia").val();
    const agencia_nombre = $("#agencia option:selected").text();

    $('.popup_content').html(`
      <h2 class="lavero_titulo">Llavero <br> Agencia: ${agencia_nombre}</h2>
      <div class="llavero_titulos llavero_row">
        <span>Referencia</span>
        <span>Actual</span>
        <span>Ultimo</span>
      </div>
      <div class="llaves_resultados_contenedor"></div>
    `);

    $.ajax({
      type: "POST",
      url: "process-request-gestor-llaves.php",
      data: { agencia_llaves_sent : agencia_id },
      dataType: 'json'//SE ESPECIFICA QUE SE ESPERA DATA EN MODO JSON
    }).done(function(data){
      if (Object.entries(data).length > 0) {//si no es un array vacio
        data.forEach(function(bien){
          $('.llaves_resultados_contenedor').append(`
            <div class="llavero_row">
              <span>${bien['referencia']}</span>
              <span>${show_holders(bien['llave_holder'])}</span>
              <span>${show_holders(bien['llave_last_holder'])}</span>
            </div>
          `)
        });
      }else {
        $('.llaves_resultados_contenedor').append(`<h2>Llavero Vacío</h2>`);
      };
    });

    $('.overlay_popup').toggleClass('opened');
    $("body").toggleClass("popup_active");
  })


  // ############### Retornar llave
  $('.retornar_btn').on('click', function(){

    $('.popup_content').html(`
      <h2>Retorno de Llave(s)</h2>
      <span class="lista_llaves_retorno_container">
        <label for="lista_llaves_retorno">Lista de Llave(s)</label>
        <select name="lista_llaves_retorno" class="lista_llaves_retorno">
          <option value=""></option>
        </select>
      </span>
      <span class="retornar_confirmar_btn">Retornar Llave(s)</span>
    `)

    $.ajax({
        type: "POST",
        url: "process-request-gestor-llaves.php",
        data: { action_requested : 'lista_llaves_agente' },
        dataType: 'json'
    }).done(function(data){

      if (Object.entries(data).length > 0) {//si no es un array vacio

        data.forEach(function(referencia){
          $('.lista_llaves_retorno').append(`
            <option value="${referencia['referencia']}">${referencia['referencia']}</option>
          `)
        });
      }else {
        $('.popup_content').html(`<h2>No retiraste ninguna llave</h2>`);
      };

      
    });



    $('.overlay_popup').toggleClass('opened');
    $("body").toggleClass("popup_active");
  })

  $(".popup_content").on('click', '.retornar_confirmar_btn', function(){

    const referencia_retorno = $(".lista_llaves_retorno option:selected").val();

    if (referencia_retorno !== '') {

      $.ajax({
          type: "POST",
          url: "process-request-gestor-llaves.php",
          data: { referencia_retorno_sent : referencia_retorno }
      }).done(function(data){

        if (data !== 'exito') {
          $('.popup_content').html(`<h2>Hubo un error, inténtelo más tarde</h2>`);
          console.log(data);
        }else{
          $('.popup_content').html(`<h2>Retorno registrado exitosamente</h2>`);
          $('.resultados_container').empty();
        };
      });

    };

  });


  // #### Retirar Llaves
  $('.resultados_container').on('click', '.action_key', function(){
    const referencia_key = $(this).parent().parent().find(".boton_borrador_formulario").attr("id");
    $('.popup_content').html(`
      <h2>Desea retirar la(s) llave(s) de este inmueble?</h2>
      <span class="popup_boton btn_retirar" referencia="${referencia_key}">Retirar LLave(s)</span>
    `);
    $('.overlay_popup').toggleClass('opened');
    $("body").toggleClass("popup_active");
  });

  $(".overlay_popup").on("click", ".btn_retirar", function(){
      const referencia_key_retirar = $(this).attr("referencia");
      $.ajax({
          type: "POST",
          url: "process-request-gestor-llaves.php",
          data: { referencia_retirar_sent : referencia_key_retirar }
      }).done(function(data){
          if (data !== 'exito') {
            $('.popup_content').html(`<h2>Hubo un error, inténtelo más tarde</h2>`);
            console.log(data);
          }else{
            $('.popup_content').html(`<h2>Exito</br>No olvide retornar la(s) llave(s) HOY mismo</h2>`);
            const id_selector = document.getElementById(referencia_key_retirar)
            $(id_selector).parent().find(".action_container").html(action_selector(1, agente_id));
          };
      });
  });


  // Ver detalle Holder Keys
  $('.resultados_container').on('click', '.foto_holder_container', function(){

    const key_id = $(this).parent().parent().find(".boton_borrador_formulario").attr("id");

    $.ajax({
      type: "POST",
      url: "process-request-gestor-llaves.php",
      data: { check_key_sent : key_id }
    }).done(function(data){

        $('.popup_content').html(data);

        if ($("#agentes_lista").length > 0) {//para inicializar el select con busqueda, en caso de panel de transferencia de llaves
          $("#agentes_lista").select2();
        };

        if ($('.agente_wrap').length > 0) {
          $('.popup').css('background-color', 'rgb(18 18 19 / 91%)');
        }


    });



    $('.overlay_popup').toggleClass('opened');
    $("body").toggleClass("popup_active");
  });

  $('.overlay_popup').on('click', '#agregar_contacto', function(){
    const referencia = $(this).attr('referencia')
    // TRAER LISTA DE VISITAS PARA AGREGAR EL CONTACTO A UNA DE ELLAS
    $.ajax({
      type: "POST",
      url: "process-request-libreta-contactos.php",
      data: { action_sent : 'get_visitas'}
    }).done(function(data){
        if (data == 'error') {
            $(".error_wrap").html(`Error de Formulario`).css("visibility", "unset");
        }else{
          $('#agregar_contacto').css('display', 'none')
          $(".info_agente_wrap").append(`
            <div class="contacto_visita_select_wrap">
              <label for="visita_select">Selecciona la Visita:</label>
              <select name="visita_select" class="visita_select"></select>
            </div>
            <span id=\"agregar_contacto_final\" class=\"estado_agente\" referencia=\"{$referencia}\">Agregar a mi visita</span>
          `);
          $('.visita_select').html(data)

          $('.visita_select option').each(function() {
            if ($(this).attr('referencia') !== referencia) {
              $(this).remove()
            }
          })

        };

    });
  })

  // AGREGAR KEY_HOLDER A VISITA
  $('.overlay_popup').on('click', '#agregar_contacto_final', function(){

    const key_holder_id = $('.agente_wrap').attr('data');
    const index_visita = $(".visita_select option:selected").attr("key");
    const agencia_tag_visita = $(".visita_select option:selected").attr("agencia_tag");
    const referencia_visita = $(".visita_select option:selected").attr("referencia");
    
    console.log('click!!')
    $.ajax({
      type: "POST",
      url: "process-request-gestor-llaves.php",
      data: {
        key_holder_id_sent : key_holder_id,
        index_visita_sent: index_visita,
        agencia_tag_visita_sent: agencia_tag_visita,
        referencia_visita_sent: referencia_visita,
      }
    }).done(function(data){
        console.log(data)
        if (data === 'exito') {
          $('.popup_content').html(`
            <h2 style='color: rgb(230 230 230)'>Contacto añadido exitosamente</h2>
          `);

        }
    });
  });


  //Tranferir llaves a otro Agente
  $(".overlay_popup").on("click", ".btn_tranferir", function(){
    const referencia_key_transferir = $(this).attr("referencia");
    const agente_transferir = $("#agentes_lista").val();
    
    if (agente_transferir !== '') {
      $.ajax({
        type: "POST",
        url: "process-request-gestor-llaves.php",
        data: { transfer_key_sent : referencia_key_transferir, agente_transferir_sent : agente_transferir }
      }).done(function(data){
  
        if (data !== 'exito' && data !== 'pendiente') {
          $('.popup_content').html(`<h2>Hubo un error, inténtelo más tarde</h2>`);
          console.log(data);
        }else if(data == 'exito'){
          $('.popup_content').html(`<h2>Transferencia solicitada al Agente,<br> - Aceptación pendiente - </h2>`);
          $('.resultados_container').empty();
        }else if(data == 'pendiente'){
          $('.popup_content').html(`<h2>Transferencia en curso, respuesta Pendiente</h2>`);
        };
  
      });
    };

  });

//CODIGO PARA POBLAR SELECT CIUDADES SEGUN EL DEPARTAMENTO ESCOGIDO ###############################################

$("select.departamento").change(function(){
  var departamentoSelected = $(".departamento option:selected").val();
  if (departamentoSelected !== '') { //si hubo una seleccion se cargan las ciudades de la db

      $.ajax({
          type: "POST",
          url: "process-request-ciudades.php",
          data: { departamentoChoice : departamentoSelected }
      }).done(function(data){
          $("#ciudad").prop('disabled', false).html(data);// se activa el select ciudades y pobla
          $("#barrio").empty().prop('disabled', true);//se vacia y bloquea el select barrios si tenia algo
          $('.resultados_container').empty();
      });

  }else { // si se seleciono vacio, entonces se vacian y bloquean los select ciudad y barrio
    $("#ciudad").empty().prop('disabled', true).val('');
    $("#barrio").empty().prop('disabled', true).val('');
    $('.resultados_container').empty();
  };
});


//CODIGO PARA POBLAR SELECT BARRIOS SEGUN LA CIUDAD ESCOGIDA  Y MOSTRAR RESULTADOS SPONSORS###############################################

$("select.ciudad").change(function(){
  var ciudadSelected = $(".ciudad option:selected").val();

  if (ciudadSelected !== '') { // si hubo seleccion se cargan los barrios de la db
    $.ajax({
        type: "POST",
        url: "process-request-barrios.php",
        data: { ciudadesChoice : ciudadSelected }
    }).done(function(data){
        if (data !== '<option></option>') {//si hubo resultados entonces se pobla y activa  el select barrios
          $("#barrio").prop('disabled', false).html(data);
          $('.resultados_container').empty();
        }else { // si no hubo resultados se desactiva y vacia el select barrios
          $("#barrio").empty().prop('disabled', true);
          $('.resultados_container').empty();//se vacian los resultados
        };
    });

    $.ajax({
        type: "POST",
        url: "process-request-ciudad_poblado_check.php",
        data: { ciudad_sent : ciudadSelected }
    }).done(function(data){
      if (data == 'poblado') {
        $('.resultados_container').empty();
        find_bien_inmueble(ciudadSelected);
      };
    });

  }else {// si se selecciono vacio, entonces se desactiva y vacia el select barrios y se vacian los resultados sponsor
    $("#barrio").empty().prop('disabled', true).val('');
    $('.resultados_container').empty();
  };
});


  //CODIGO PARA MOSTRAR RESULTADOS SEGUN EL BARRIO ESCOGIDO ###############################################

  $("select.barrio").change(function(){
    var barrioSelected = $(".barrio option:selected").val();

    if (barrioSelected !== '') { //
      $('.resultados_container').empty();
      find_bien_inmueble(barrioSelected);
    }else {//si se escogio barrio vacio a proposito se vacian los resultados sponsor
      $('.resultados_container').empty();
    };
  });

  // CODIGO PARA BUSQUEDA SEGUN REFERENCIA ############################################################

  $(".input_referencia_btn").on("click", function(){
    var referencia_picked = $("#input_referencia").val();
    $("#input_direccion").val('');
    $("#departamento").val('');
    $("#ciudad").empty().prop('disabled', true).val('');
    $("#barrio").empty().prop('disabled', true).val('');
    find_bien_inmueble_by_reference(referencia_picked);
  });

  // CODIGO PARA BUSCAR SEGUN DIRECCION ##########################################################################

  $("#input_direccion").on("input", function(){
    var direccion_key = $("#input_direccion").val();
    if (direccion_key !== '') {
    $("#input_referencia").val('');
    $("#departamento").val('');
    $("#ciudad").empty().prop('disabled', true).val('');
    $("#barrio").empty().prop('disabled', true).val('');
    find_bien_inmueble_by_direccion(direccion_key);
    }else{
    $('.resultados_container').empty();
    };

  });


  $("#agencia").selectmenu();//SE INITIALIZA EL SELECTMENU

  $("#agencia").on("selectmenuchange", function(){//SE TRAE LOS ITEM AL CAMBIAR DE AGENCIA

  });

  $(".popup_cerrar").on("click", function(){
    $(".overlay_popup").toggleClass("opened");
    $("body").toggleClass("popup_active");
    $(".popup_content").empty();
    $('.popup').css('background-color', 'rgb(255, 255, 255)');
  });




  
  // SE DEFINE LA FUNCION QUE PERMITE TRAER LOS RESULTADOS DE LA BASE DE DATOS #####################################

  function action_selector(llave, holder){
    if (llave == 1) {
      if (holder !== '') {
        return `<span class="foto_holder_container">
                  <img src="../../agentes/${pais_cookie}/${holder}/foto_plomo_min.jpg" alt="" title="ID: ${holder}">
                  <i class='fas fa-key' aria-hidden='true'></i>
                </span>`;
      }else{
        return `<i class='fas fa-key action_key' aria-hidden='true'></i>`;
      };
    }else{
      return '<span class="no_action">-</span>';
    };
  };

  function show_holders(holder){
    if (holder !== '') {
      return `<img src="../../agentes/${pais_cookie}/${holder}/foto_plomo_min.jpg" alt="" title="ID: ${holder}">`
    }else{
      return '<span class="no_holder">-</span>';;
    };

  };

  function find_bien_inmueble(barrio_param){
    const agencia_id = $("#agencia").val();
    $.ajax({
        type: "POST",
        url: "process-request-gestor-llaves-resultados.php",
        data: { barrio_sent : barrio_param, agencia_id_sent: agencia_id },
        dataType: 'json'//SE ESPECIFICA QUE SE ESPERA DATA EN MODO JSON
    }).done(function(data){
      const bienes_array = data;
      const location_tag = barrio_param;
      $('.resultados_container').empty();//se vacian los resultados

      if (Object.entries(bienes_array).length > 0) {//si no es un array vacio

        $('.resultados_container').append("<span class='label_resultadors_contenedor'>Bienes activos en: " + barrio_param + "</span>");

        bienes_array.forEach(function(bien){
          if (bien['visibilidad'] == 'visible') {
            $('.resultados_container').append(`
              <div class='boton_borrador_mini_contenedor'>
                <div id='${bien['referencia']}' name='${bien['estado']}' class='boton_borrador_formulario'>
                  <i class='fas fa-search' aria-hidden='true'></i>
                  <p><span class='nombre'>${bien['referencia']} - ${location_tag}</span></p>
                </div>
                <div class='action_container'>
                  ${action_selector(bien['llave'], bien['llave_holder'])}
                </div>
              </div>
            `);
          };
        });

      };

      if ($(".boton_borrador_mini_contenedor").length == 0) {
        $('.resultados_container').append("- NO EXISTEN RESULTADOS -");
      };

    });

  };

  function find_bien_inmueble_by_reference(reference_param){
    const agencia_id = $("#agencia").val()
    $.ajax({
        type: "POST",
        url: "process-request-gestor-llaves-resultados.php",
        data: { reference_sent : reference_param, agencia_id_sent: agencia_id },
        dataType: 'json'//SE ESPECIFICA QUE SE ESPERA DATA EN MODO JSON
    }).done(function(bienes_array){

      $('.resultados_container').empty();//se vacian los resultados

      if (bienes_array.length > 0) {//si no es vacio
        $('.resultados_container').append("<span class='label_resultadors_contenedor'>Bienes activos en: " + bienes_array[0]['location_tag'] + "</span>");

          if (bienes_array[0]['visibilidad'] == 'visible') {
            $('.resultados_container').append(`
              <div class='boton_borrador_mini_contenedor'>
                <div id='${bienes_array[0]['referencia']}' name='${bienes_array[0]['estado']}' class='boton_borrador_formulario'>
                  <i class='fas fa-search' aria-hidden='true'></i>
                  <p><span class='nombre'>${bienes_array[0]['referencia']} - ${bienes_array[0]['location_tag']}</span></p>
                </div>
                <div class='action_container'>
                  ${action_selector(bienes_array[0]['llave'], bienes_array[0]['llave_holder'])}
                </div>
              </div>
            `);
          };

      };

      if ($(".boton_borrador_mini_contenedor").length == 0) {
        $('.resultados_container').append("- NO EXISTEN RESULTADOS -");
      };

    });
  };

  function find_bien_inmueble_by_direccion(direccion_param){
    const agencia_id = $("#agencia").val()
    $.ajax({
        type: "POST",
        url: "process-request-gestor-llaves-resultados.php",
        data: { direccion_sent : direccion_param, agencia_id_sent: agencia_id },
        dataType: 'json'//SE ESPECIFICA QUE SE ESPERA DATA EN MODO JSON
    }).done(function(bienes_array){

      $('.resultados_container').empty();//se vacian los resultados

      if (Object.entries(bienes_array).length > 0) {//si no es un array vacio

        bienes_array.forEach(function(bien){
          if (bien['visibilidad'] == 'visible') {
            $('.resultados_container').append(`
              <div class='boton_borrador_mini_contenedor'>
                <div id='${bien['referencia']}' name='${bien['estado']}' class='boton_borrador_formulario'>
                  <i class='fas fa-search' aria-hidden='true'></i>
                  <p><span class='nombre'>${bien['referencia']} - ${bien['location_tag']}</span></p>
                </div>
                <div class='action_container'>
                  ${action_selector(bien['llave'], bien['llave_holder'])}
                </div>
              </div>
            `);
          };
        });

      };

      if ($(".boton_borrador_mini_contenedor").length == 0) {
        $('.resultados_container').append("- NO EXISTEN RESULTADOS -");
      };

    });
  };




  });
});
