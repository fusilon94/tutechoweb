
 function auto_grow(element) {
  element.style.height = "5px";
  element.style.height = (element.scrollHeight + 8)+"px";
}

$(document).ready(function(){
  jQuery(function($){

    function get_contactos() {
      let contactos;

      $.ajax({
        type: "POST",
        url: "process-request-calendario.php",
        data: { fecha_tag_sent: 'get_contactos', agente_id_sent: agente_id_default },
        async: false,
      }).done(function(data){
        contactos = data;
      });
      return contactos;
    };

    // CODIGO BOTON AGREGAR EVENTO

    $(".calendario_contendor").on("click", ".day_agregar_btn", function(event){

      let date_selected = $(this).parent().attr("id");

      $(".popup").html(`

      <span class="cerrar_popup"><i class="fas fa-times-circle"></i></span>
      <span class="popup_contenido">


      <input type="hidden" class="popup_date" value="${date_selected}">
      
        <div class="popup_cabecera">
          <span class="tab_popup tab_tarea activo">Tarea</span>
          <span class="tab_popup tab_evento">Evento</span>
        </div>

        <div class="contenido_tarea activo">
            
            <div class="botones_tarea_wrap">
              <span class="btn_registro_opcion">Registro</span>
              <span class="btn_visita_opcion">Visita</span>
            </div>

            <input type="text" class="inmueble_referencia" placeholder="Referencia Inmueble">

            <input type="text" class="hora_tarea" placeholder="Hora de la tarea" value="">

            <span class=\"visitante_wrap\">
              <select id='visitante' style='width: 200px;'>
                <option value='0'>Selecciona Visitante</option>
                ${get_contactos()} 
              </select>
            </span>

            <span class="error_wrap_tarea"></span>

            <span class="btn_guardar_tarea">Guardar</span>

        </div>

        <div class="contenido_evento">
          
          <input type="text" class="titulo_evento" placeholder="Titulo del Evento">

          <input type="text" class="hora_evento" placeholder="Hora del Evento (opcional)" value="">

          <div class="select_tipo_evento_wrap">
            <label for="tipo_evento">Tipo de Evento:</label>
            <select name="tipo_evento" class="tipo_evento">
              <option value="recordatorio">Recordatorio</option>
              <option value="check_list">Check List</option>
              <option value="aniversario">Cumpleaños</option>
              <option value="comida">Comida</option>
              <option value="viaje">Viaje</option>
            </select>
          </div>

          <div class="complemento_wrap">
            <textarea class="descripcion_evento" placeholder="Descripción: (opcional)"></textarea>
          </div>

          <span class="error_wrap_evento"></span>

          <span class="btn_guardar_evento">Guardar</span>

        </div>

        </span>

      `);

      $("#visitante").select2();

      $(".popup_overlay").css("opacity", 1).css("visibility", "unset");

      $('.hora_tarea').clockTimePicker({
        autosize:true,
        vibrate:false,
        alwaysSelectHoursFirst:true
      });

      $('.hora_evento').clockTimePicker({
        autosize:true,
        vibrate:false,
        alwaysSelectHoursFirst:true
      });

      event.stopPropagation();//para evitar lanzar los eventos de click en el elemento padre, solo funciona porque el eventhandler del padre se define despues de el del hijo
    });


    $(".popup_overlay").on("click", ".tab_popup:not(.activo)", function(){
      $(".tab_popup").toggleClass("activo");

      if ($(this).hasClass("tab_tarea")) {
        $(".contenido_evento").removeClass("activo");
        $(".contenido_tarea").addClass("activo");
      }else if($(this).hasClass("tab_evento")){
        $(".contenido_tarea").removeClass("activo");
        $(".contenido_evento").addClass("activo");
      };

    });

    $(".popup_overlay").on("click", ".botones_tarea_wrap span", function(){

      if ($(this).hasClass("btn_registro_opcion")) {
        $(".btn_visita_opcion").removeClass("activo");
        $(".btn_registro_opcion").addClass("activo");
        $(".visitante_wrap").css("display", "none");
      }else if($(this).hasClass("btn_visita_opcion")){
        $(".btn_registro_opcion").removeClass("activo");
        $(".btn_visita_opcion").addClass("activo");
        $(".visitante_wrap").css("display", "flex");
      };

    });

    // REGEX INPUTS POPUP AGREGAR EVENTO/TAREA

    $(".popup_overlay").on("input", ".inmueble_referencia", function(){
      if ($(this).val().match(/^[\w\d\s #áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
        if ($(this).val() !== '') {
            $(this).addClass("borde_rojo");
            $(".error_wrap_tarea").html("Sólo use caracteres permitidos").css("visibility", "unset");
        };
      } else {
        $(this).removeClass("borde_rojo");
        $(".error_wrap_tarea").html("").css("visibility", "hidden");
      };
    });

    $(".popup_overlay").on("input", ".hora_tarea", function(){
      if ($(this).val().match(/^[+\-0-9: \/]+$/g) == null) {//Si se ingrso un caracter no permitido
        if ($(this).val() !== '') {
            $(this).addClass("borde_rojo");
            $(".error_wrap_tarea").html("Sólo use caracteres permitidos").css("visibility", "unset");
        };
      } else {
        $(this).removeClass("borde_rojo");
        $(".error_wrap_tarea").html("").css("visibility", "hidden");
      };
    });


    $(".popup_overlay").on("input", ".titulo_evento", function(){
      if ($(this).val().match(/^[\w\d\s #áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
        if ($(this).val() !== '') {
            $(this).addClass("borde_rojo");
            $(".error_wrap_evento").html("Sólo use caracteres permitidos").css("visibility", "unset");
        };
      } else {
        $(this).removeClass("borde_rojo");
        $(".error_wrap_evento").html("").css("visibility", "hidden");
      };
    });

    $(".popup_overlay").on("input", ".hora_evento", function(){
      if ($(this).val().match(/^[+\-0-9: \/]+$/g) == null) {//Si se ingrso un caracter no permitido
        if ($(this).val() !== '') {
            $(this).addClass("borde_rojo");
            $(".error_wrap_evento").html("Sólo use caracteres permitidos").css("visibility", "unset");
        };
      } else {
        $(this).removeClass("borde_rojo");
        $(".error_wrap_evento").html("").css("visibility", "hidden");
      };
    });


    $(".popup_overlay").on("input", ".descripcion_evento", function(){
      if ($(this).val().match(/^[\w\d\s+\-&.,#:\/áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
        if ($(this).val() !== '') {
            $(this).addClass("borde_rojo");
            $(".error_wrap_evento").html("Sólo use caracteres permitidos").css("visibility", "unset");
        };
      } else {
        $(this).removeClass("borde_rojo");
        $(".error_wrap_evento").html("").css("visibility", "hidden");
      };
    });

    $(".popup_overlay").on("input", ".check_element textarea", function(){
      if ($(this).val().match(/^[\w\d\s+\-&.,#:\/áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
        if ($(this).val() !== '') {
            $(this).addClass("borde_rojo");
            $(".error_wrap_evento").html("Sólo use caracteres permitidos").css("visibility", "unset");
        };
      } else {
        $(this).removeClass("borde_rojo");
        $(".error_wrap_evento").html("").css("visibility", "hidden");
      };
    });


    // CODIGO GUARDAR TAREA

    $(".popup_overlay").on("click", ".btn_guardar_tarea", function(){

      const fecha_selected = $(".popup_date").val();
      let tipo_tarea;
      let visitante = '';

      if ($(".btn_registro_opcion").hasClass("activo")) {
        tipo_tarea = 'registro';
      }else if($(".btn_visita_opcion").hasClass("activo")){
        tipo_tarea = 'visita';
        if ($("#visitante option:selected").attr("value") !== '') {
          visitante = {'nombre': $("#visitante option:selected").attr("value"), 'telefono': $("#visitante option:selected").attr("telefono")};
        };
      };

      const referencia_inmueble = $(".inmueble_referencia").val();
      const hora_tarea = $(".hora_tarea").val();

      const past_event = $("#past_event_check").val();
      let agencia_selected = agencia_tag_default;
      let agente_selected = agente_id_default;

      if ($("#agencia_select").lenght) {
        if ($("#agencia_select option:selected").val() !== '') {
          agencia_selected = $("#agencia_select option:selected").val();
        };
      };

      if ($("#agente_select").lenght) {
        if ($("#agente_select option:selected").val() !== '') {
          agente_selected = $("#agente_select option:selected").val();
        };
      };


      if (tipo_tarea == '' || referencia_inmueble == '' || hora_tarea == '') {

        $(".error_wrap_tarea").html("Quedan espacios por llenar").css("visibility", "unset");

      }else if($(".inmueble_referencia").hasClass("borde_rojo") || $(".hora_tarea").hasClass("borde_rojo")){

        $(".error_wrap_tarea").html("El formulario debe llenarse correctamente").css("visibility", "unset");

      }else{

        if (tipo_tarea == 'visita' && visitante == '') {
          $(".error_wrap_tarea").html("Quedan espacios por llenar").css("visibility", "unset");
        }else{

          $.ajax({
              type: "POST",
              url: "process-request-calendario.php",
              data: { fecha_tag_sent: 'refresh_agente', date_tag_sent : fecha_selected, tipo_tarea_sent : tipo_tarea, hora_tarea_sent : hora_tarea, referencia_sent : referencia_inmueble, agencia_tag_sent : agencia_tag_default, agente_id_sent : agente_id_default, past_events_sent : past_event, visitante_sent: visitante }
          }).done(function(data){

            if (data == 'error') {
                $(".error_wrap_tarea").html("La referencia NO existe").css("visibility", "unset");
              
            }else if(data == 'error_fecha_limite'){
              $(".error_wrap_tarea").html("Tiene sólo 3 dias para registrar tareas").css("visibility", "unset");
            }else{
              const cuadro_id = "#" + fecha_selected;

              $(".calendario_contendor").html(data);

              $(".calendario_contendor").scrollTop(0);           

              const t = $(".calendario_contendor").offset().top;
              
              $(".calendario_contendor").scrollTop($(cuadro_id).offset().top - t - 5);// Sepone el scroll en el dia de hoy
              let titulo_first = $(cuadro_id).attr("mes") + " " + $(cuadro_id).attr("year");

              $(".cabecera_titulo").html(titulo_first).attr("data", $(cuadro_id).attr("id").slice(3));

              $(".popup_overlay").css("opacity", 0).css("visibility", "hidden");

            };

            
          });

        };

      };
      

    });


    // CODIGO CAMBIAR TIPO EVENTO
    $('.popup_overlay').on("change", ".tipo_evento",  function(){

      const tipo_evento = $('.tipo_evento option:selected').val();

      if (tipo_evento == "recordatorio" || tipo_evento == "comida" || tipo_evento == "viaje") {
        $(".complemento_wrap").html(`
          <textarea class="descripcion_evento" placeholder="Descripción: (opcional)"></textarea>
        `);
      } else if (tipo_evento == "aniversario") {
        $(".complemento_wrap").html(`
          <div class="anniversario_opciones_wrap">
            <span class="anniversario_opcion unico activo">Único</span>
            <span class="anniversario_opcion recurrente">Recurrente</span>
          </div>
        `);
      } else if (tipo_evento == "check_list") {
        $(".complemento_wrap").html(`
          <div class="check_list_wrap">
            <span class="check_element">
              <textarea rows="1" oninput="auto_grow(this)" style="height: 29px;"></textarea>
              <span class="borrar_check_element"><i class="fas fa-times-circle"></i></span>
            </span>
            
            <span class="agregar_check_element">
              <i class="fas fa-plus-circle"></i>
              <i class="fas fa-caret-right"></i>
            </span>
          </div>
        `);
      };

    });



    // CODIGO GUARDAR EVENTO

    $(".popup_overlay").on("click", ".btn_guardar_evento", function(){
      const fecha_selected = $(".popup_date").val();
      const titulo_evento = $(".titulo_evento").val();
      const hora_evento = $(".hora_evento").val();
      const tipo_evento = $('.tipo_evento option:selected').val();
      let complemento = '';

      const past_event = $("#past_event_check").val();
      let agencia_selected = agencia_tag_default;
      let agente_selected = agente_id_default;

      if ($("#agencia_select").lenght) {
        if ($("#agencia_select option:selected").val() !== '') {
          agencia_selected = $("#agencia_select option:selected").val();
        };
      };

      if ($("#agente_select").lenght) {
        if ($("#agente_select option:selected").val() !== '') {
          agente_selected = $("#agente_select option:selected").val();
        };
      };

      if ($(".descripcion_evento").length) {

        complemento = $(".descripcion_evento").val();

      }else if($(".anniversario_opciones_wrap").length){
        
        if ($(".unico").hasClass("activo")) {
          complemento = 0;
        }else if($(".recurrente").hasClass("activo")){
          complemento = 1;
        };
        
        
      }else if($(".check_list_wrap").length){

        let check_list = [];

        $(".check_element").each(function(){

          if ($(this).find("textarea").val() !== '') {
            const new_element = {'titulo': $(this).find("textarea").val(), 'checked' : 0};
            check_list.push(new_element);
          };

        });

        complemento = JSON.stringify(check_list);
        
      };

      if (fecha_selected == '' || titulo_evento == '' || tipo_evento == '') {
        
        $(".error_wrap_evento").html("El formulario debe llenarse correctamente").css("visibility", "unset");

      }else{

        if (tipo_evento == 'check_list' && complemento == '[]') {

          $(".error_wrap_evento").html("El formulario debe llenarse correctamente").css("visibility", "unset");

        }else{

          $.ajax({
            type: "POST",
            url: "process-request-calendario.php",
            data: { fecha_tag_sent: 'refresh_agente', date_tag_sent : fecha_selected, tipo_evento_sent : tipo_evento, hora_evento_sent : hora_evento, titulo_evento_sent : titulo_evento, complemento_sent: complemento, agencia_tag_sent : agencia_tag_default, agente_id_sent : agente_id_default, past_events_sent : past_event }
          }).done(function(data){
    
            if (data == 'error') {
                $(".error_wrap_evento").html("Error de Formulario").css("visibility", "unset");
            }else{
              const cuadro_id = "#" + fecha_selected;
    
              $(".calendario_contendor").html(data);
    
              $(".calendario_contendor").scrollTop(0);           
    
              const t = $(".calendario_contendor").offset().top;
              
              $(".calendario_contendor").scrollTop($(cuadro_id).offset().top - t - 5);// Sepone el scroll en el dia de hoy
              let titulo_first = $(cuadro_id).attr("mes") + " " + $(cuadro_id).attr("year");
    
              $(".cabecera_titulo").html(titulo_first).attr("data", $(cuadro_id).attr("id").slice(3));
    
              $(".popup_overlay").css("opacity", 0).css("visibility", "hidden");
    
            };
    
            
          });
      };

        

    };
      

    });



  });
});
