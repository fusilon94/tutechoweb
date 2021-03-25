$(document).ready(function(){
  jQuery(function($){

    $(".popup_cerrar").on("click", function(){
        $(".overlay").css("visibility", "hidden");
    });

    $(".info_db_btn").on("click", function(){
        const pais = $(this).attr('data1');
        const id = $(this).attr('data2');
        const tipo_file = $(this).attr('name');
        let tabla = '';

        if (tipo_file == 'inmueble') {
            if (id.includes("C")) { tabla = "borradores_casa";
              } else { if (id.includes("D")) { tabla = "borradores_departamento";
                } else { if (id.includes("L")) { tabla = "borradores_local";
                  } else { if (id.includes("T")) { tabla = "borradores_terreno";
                    };
                  };
                };
              };

        }else if (tipo_file == 'personal'){
          tabla = 'agentes';
        };

        $.ajax({
            type: "POST",
            url: "process-request-info_db_validar.php",
            data: { pais_sent : pais,
                    tabla_sent : tabla,
                    id_sent : id},
            dataType: "json"
        }).done(function(data){
          $(".overlay").css("visibility", "unset");

          $(".popup_content").html(`
          <h2 class="lista_datos_db_h2">Data Base</h2>
          <hr>
          <div class="lista_datos_db">
            
          </div>
          `);

          let lista_datos = "";

          $.each(data, function(index, value){
            let extra_dato;
            if(index == "mantenimiento" || index == "avaluo" || index == "base_imponible" || index == "precio"){
               extra_dato = moneda;
            }else if (index == "superficie_terreno" || index == "superficie_inmueble") {
               extra_dato = 'm<sup>2</sup>';
            }else{
              extra_dato = '';
            }

            let content = `
              <span class="dato_db">
              <p class="dato_db_label">${index.replace('_', ' ')}: </p>
              <p>${value}</p><p>&nbsp${extra_dato}</p>
              </span>
            `;
            lista_datos += content;
          });

          $(".lista_datos_db").html(lista_datos);

        });

          
      
        
    });

    $(".reclamar_btn").on("click", function(){
        $(".overlay").css("visibility", "unset");
        $(".popup_content").html(`
        <h2>RECLAMO</h2>
        <hr>
        <label for="reclamo_text">Escribe tu reclamo:</label>
        <textarea name="reclamo_text" class="reclamo_text" rows="5" value=""></textarea>
        <span class="confirmar_reclamo">ENVIAR</span>
        `);
    });

    $(".validar_btn").on("click", function(){
        $(".overlay").css("visibility", "unset");
        $(".popup_content").html(`
        <h2>¿Desea VALIDAR este File?</h2>
        <hr>
        <b>File Agente:</b>
        <p>- Se crean codigos temporales de acceso </p>
        <p>- Se notifica al Jefe de Agencia Local</p>
        <br/>
        <b>File Inmueble:</b>
        <p>- Se habilita el llenado de Formularios</p>
        <p>- Se habilita el completado de Fotos</p>

        <span class="confirmar_validacion">VALIDAR</span>
        `);
    });

    $(".popup_content").on("click", ".confirmar_reclamo", function(){

        let accion_selected = 'reclamo';
        let reclamo_texto = $(".reclamo_text").val();

        if (reclamo_texto == '') {
            alert("Debe escribir un reclamo informativo");
        }else{
            $("#accion_selected").val(accion_selected);
            $("#mensaje").val(reclamo_texto);



            $("#formulario_validacion_file").submit();

        };
    });

    $(".popup_content").on("click", ".confirmar_validacion", function(){

        let accion_selected = 'validacion';
        $("#accion_selected").val(accion_selected);

        $("#formulario_validacion_file").submit();

        
    });


  });
});
