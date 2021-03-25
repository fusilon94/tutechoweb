$(document).ready(function(){
  jQuery(function($){

//CODIGO PARA POBLAR SELECT CIUDADES SEGUN EL DEPARTAMENTO ESCOGIDO ###############################################

        $("select.agencia").change(function(){
            var agenciaSelected = $(".agencia option:selected").val();
            if (agenciaSelected !== '') { //si hubo una seleccion se cargan las ciudades de la db

                $.ajax({
                    type: "POST",
                    url: "process-request-agencia-horarios.php",
                    data: { agenciaChoice : agenciaSelected }
                }).done(function(data){
                      $(".all_params_container").html(data);
                      $( ".date_picker_config" ).datepicker({
                        dateFormat: "dd/mm/yy"
                      });
                });

            };
        });

    //############## CODIGO PARA ACTIVAR/INACTIVAR DIA o TARDE ################################## 

        $(".all_params_container").on("click", ".check_sub_label", function(){
          let input_associated = $(this).parent().find("input");
          let selects_associated;
          if (input_associated.hasClass("check_dia")) {
            selects_associated = $(this).parent().parent().find(".select_day");
          }else{
            selects_associated = $(this).parent().parent().find(".select_tarde");
          };

          if ($(this).hasClass('inactivo')) {
            $(this).toggleClass('inactivo').html("Activo");
            input_associated.val(1);
            selects_associated.prop('disabled', false);
          }else{
            $(this).toggleClass('inactivo').html("Inactivo");
            input_associated.val(0);
            selects_associated.prop('disabled', true);
          };
          
        });

    //############## CODIGO PARA EL BOTON DE BORRAR EXCEPCION ##################################         

        $(".all_params_container").on("click", ".trash_excepcion", function(){
          let btn_confirmar = $(this).parent().find(".confirmar_borrar_excepcion");

          if (btn_confirmar.hasClass('activado')) {
            btn_confirmar.hide("slide", { direction: "left" }, 800).toggleClass("activado");
            $(this).html("<i class='fas fa-trash-alt'></i>");
          }else{
            btn_confirmar.show("slide", { direction: "left" }, 800).toggleClass("activado");
            $(this).html("<i class='fas fa-times-circle'></i>");
          };
        });

    //############## CODIGO PARA CERRAR POPUP ################################## 

        $(".cerrar_popup").on("click", function(){
          $(".popup_overlay").css("visibility", "hidden");
      });

    //############## CODIGO PARA GUARDAR CAMBIOS EN HORARIOS ################################## 

        $(".all_params_container").on("click", ".guardar_btn", function(){

          let error = '';

          $(".selects_day_wrap select:not(:disabled)").each(function(){
            if ($(this).find("option:selected").val() == '') {
              error = 'error';
            };
          });

          if (error !== '') {
            $(".popup_contenido").html('Todos los campos deben llenarse');
            $(".popup_overlay").css("visibility", "unset");
          }else{

          

              let json_constructor_horario = {
                lunes : {
                  day_week : 1,
                  dia : {
                    activo : '',
                    min : '',
                    max : ''
                  },
                  tarde : {
                    activo : '',
                    min : '',
                    max : ''
                  }
                },
                martes : {
                  day_week : 2,
                  dia : {
                    activo : '',
                    min : '',
                    max : ''
                  },
                  tarde : {
                    activo : '',
                    min : '',
                    max : ''
                  }
                },
                miercoles : {
                  day_week : 3,
                  dia : {
                    activo : '',
                    min : '',
                    max : ''
                  },
                  tarde : {
                    activo : '',
                    min : '',
                    max : ''
                  }
                },
                jueves : {
                  day_week : 4,
                  dia : {
                    activo : '',
                    min : '',
                    max : ''
                  },
                  tarde : {
                    activo : '',
                    min : '',
                    max : ''
                  }
                },
                viernes : {
                  day_week : 5,
                  dia : {
                    activo : '',
                    min : '',
                    max : ''
                  },
                  tarde : {
                    activo : '',
                    min : '',
                    max : ''
                  }
                },
                sabado : {
                  day_week : 6,
                  dia : {
                    activo : '',
                    min : '',
                    max : ''
                  },
                  tarde : {
                    activo : '',
                    min : '',
                    max : ''
                  }
                }
              };


              $(".day_wrap").each(function(){

                let day_name = $(this).attr('name');
                let check_dia = $(this).find('input.check_dia').val();
                let check_tarde = $(this).find('input.check_tarde').val();

                if (check_dia == 1) {

                  let dia_min = $(this).find('#'+day_name+"_dia_min option:selected").val();
                  let dia_max = $(this).find('#'+day_name+"_dia_max option:selected").val();

                  json_constructor_horario[day_name]['dia']['activo'] = 1;
                  json_constructor_horario[day_name]['dia']['min'] = dia_min;
                  json_constructor_horario[day_name]['dia']['max'] = dia_max;

                }else{

                  json_constructor_horario[day_name]['dia']['activo'] = 0;

                };


                if (check_tarde == 1) {

                  let tarde_min = $(this).find('#'+day_name+"_tarde_min option:selected").val();
                  let tarde_max = $(this).find('#'+day_name+"_tarde_max option:selected").val();

                  json_constructor_horario[day_name]['tarde']['activo'] = 1;
                  json_constructor_horario[day_name]['tarde']['min'] = tarde_min;
                  json_constructor_horario[day_name]['tarde']['max'] = tarde_max;

                }else{

                  json_constructor_horario[day_name]['tarde']['activo'] = 0;

                };


              });

              let final_horario_json = JSON.stringify(json_constructor_horario);
              let agencia_selected = $(".agencia option:selected").val();
              $.ajax({
                  type: "POST",
                  url: "process-request-agencia-horario-json.php",
                  data: { json_horario_sent : final_horario_json,
                          agencia_sent : agencia_selected }
              }).done(function(data){
                  $(".popup_contenido").html('Horarios-Agencia guardado exitosamente');
                  $(".popup_overlay").css("visibility", "unset");
              });

              crear_json_excepciones();//Por ultimo se genera el json excepciones para que siempre exista el File

            };
        });


    //############## SE DEFINE LA FUNCION PARA ARMAR EL JSON SEGUN LOS FERIADOS Y EXCEPCIONES MOSTRADOS EN PANTALLA #############
    
    function crear_json_excepciones(){
      // AHORA SE PROSIGUE A ARMAR EL JSON
      let json_constructor_excepciones = {
        feriados : {},
        otros : {}
      };

      $(".excepciones_curso_wrap .excepcion_text").each(function(){
        let tipo_excepcion = $(this).find('.tipo_text').text();
        let fecha_excepcion = $(this).find('.fecha_text').attr('value');
        let descripcion_excepcion = $(this).find('.descripcion_text').text();

        json_constructor_excepciones['otros'][fecha_excepcion] = {descripcion : '', tipo : ''};
        json_constructor_excepciones['otros'][fecha_excepcion]['descripcion'] = descripcion_excepcion;
        json_constructor_excepciones['otros'][fecha_excepcion]['tipo'] = tipo_excepcion;
      });


      $(".excepciones_feriados_wrap .excepcion_text").each(function(){
        let fecha_excepcion = $(this).find('.fecha_text').attr('value');
        let descripcion_excepcion = $(this).find('.descripcion_text').text();

        json_constructor_excepciones['feriados'][fecha_excepcion] = {descripcion : ''};
        json_constructor_excepciones['feriados'][fecha_excepcion]['descripcion'] = descripcion_excepcion;
      });

      let final_excepciones_json = JSON.stringify(json_constructor_excepciones);
      let agencia_selected = $(".agencia option:selected").val();
      $.ajax({
          type: "POST",
          url: "process-request-agencia-horario-json.php",
          data: { json_excepciones_sent : final_excepciones_json,
                  agencia_sent : agencia_selected }
      }).done(function(data){
          console.log("Excepciones guardadas exitosamente");
      });

      return;
    };

    //############## CODIGO PARA EL CONFIG EXCEPCION ################################## 

      $(".all_params_container").on("click", ".btn_agregar", function(){

        let tipo = $(".config_excepcion_select option:selected").val();
        let fecha = $(".date_picker_config").val();
        let descripcion = $(".descripcion_config").val();

        if (tipo == '' || fecha == '' || descripcion == '') {//se verifica que todos los inputs esten llenos

          $(".popup_contenido").html('Los tres campos deben llenarse');
          $(".popup_overlay").css("visibility", "unset");

        }else{

          let error_fecha = '';

          $(".fecha_text").each(function(){
            if($(this).attr('value') == fecha){
              error_fecha = 'error';
            };
          });

          if (error_fecha !== '') {// se verifica que la fecha escogida no exista en la lista de excepciones

            $(".popup_contenido").html('La fecha elegida está en uso');
            $(".popup_overlay").css("visibility", "unset");

          }else{

            if (tipo == 'feriado') {//se adjunta a la lista actual de feriados
              $(".excepciones_feriados_wrap").prepend(`
                <span class=\"excepcion_row\">
                    <span class=\"excepcion_text\">
                        <i class=\"fas fa-circle new\"></i>
                        <p class=\"fecha_text\" value=\"${fecha}\">${fecha.substr(0, 5)}</p>
                        <p>-</p>
                        <p class=\"descripcion_text\">${descripcion}</p>
                    </span>
                    <span class=\"borrar_excepcion_wrap\">
                        <span class=\"trash_excepcion\"><i class=\"fas fa-trash-alt\"></i></span>
                        <span class=\"confirmar_borrar_excepcion\">BORRAR</span>
                    </span>
                </span>
              `);

              $(".popup_contenido").html('Feriado agregado Exitosamente');
              $(".popup_overlay").css("visibility", "unset");
            }else{//o bien se adjunta a la lista actual de excepciones en curso
              $(".excepciones_curso_wrap").prepend(`
                <span class=\"excepcion_row\">
                    <span class=\"excepcion_text\">
                      <i class=\"fas fa-circle new\"></i>
                      <p class=\"fecha_text\" value=\"${fecha}\">${fecha}</p>
                      <p>-</p>
                      <p class=\"tipo_text\">${tipo}</p>
                      <p>-</p>
                      <p class=\"descripcion_text\">${descripcion}</p>
                    </span>
                    <span class=\"borrar_excepcion_wrap\">
                        <span class=\"trash_excepcion\"><i class=\"fas fa-trash-alt\"></i></span>
                        <span class=\"confirmar_borrar_excepcion\">BORRAR</span>
                    </span>
                </span>
              `);

              $(".popup_contenido").html('Excepción agregada Exitosamente');
              $(".popup_overlay").css("visibility", "unset");
            };

            // SE RESETEA EL EXCUSAS CONFIG
            $(".config_excepcion_select").prop('selectedIndex',0);
            $(".date_picker_config").val('');
            $(".descripcion_config").val('');

            
            crear_json_excepciones();//Por ultimo se genera el json excepciones
            
            $(".popup_contenido").html('Excepción agregada Exitosamente');
            $(".popup_overlay").css("visibility", "unset");
          };

        };

      });


      // ############# CODIGO PARA BORRAR EXCEPCIONES INDIVIDUALES ###############################

      $(".all_params_container").on("click", ".confirmar_borrar_excepcion", function(){

        let excepcion_container = $(this).parent().parent();

        excepcion_container.remove();

        crear_json_excepciones();//Por ultimo se genera el json excepciones

        $(".popup_contenido").html('Excepción borrada exitosamente');
        $(".popup_overlay").css("visibility", "unset");

      });





  });
});
