$(document).ready(function(){
  jQuery(function($){

//CODIGO PARA POBLAR SELECT CIUDADES SEGUN EL DEPARTAMENTO ESCOGIDO ###############################################

        $("select.agencia").change(function(){
            var agenciaSelected = $(".agencia option:selected").val();
            if (agenciaSelected !== '') { //si hubo una seleccion se cargan las ciudades de la db

                $.ajax({
                    type: "POST",
                    url: "process-request-agencia_params.php",
                    data: { agenciaChoice : agenciaSelected }
                }).done(function(data){
                      $(".all_params_container").empty().html(data);

                      $("#compra_casa").spinner({
                        min: 0,
                        step: 5000
                      });
                      $("#compra_departamento").spinner({
                        min: 0,
                        step: 5000
                      });
                      $("#compra_local").spinner({
                        min: 0,
                        step: 5000
                      });
                      $("#compra_terreno").spinner({
                        min: 0,
                        step: 5000
                      });

                      $("#renta_casa").spinner({
                        min: 0,
                        step: 50
                      });
                      $("#renta_departamento").spinner({
                        min: 0,
                        step: 50
                      });
                      $("#renta_local").spinner({
                        min: 0,
                        step: 50
                      });
                      $("#renta_terreno").spinner({
                        min: 0,
                        step: 10
                      });

                      $("#anticretico").spinner({
                        min: 0,
                        max: 100,
                        step: 5
                      });


                      $(".modo_btn.activo").trigger( "click" );

                });

            };
        });

// CODIGO PARA LA SELECCION DE MODO ##############################################################

  $(".contenedor_editor_sponsor_consola").on("click", ".modo_btn", function(){
    $(".modo_btn").removeClass('activo');
    $(this).addClass('activo');
    var modo_value = $(this).attr('name');
    $("#modo_input").val(modo_value);

    var modo_text_to_show = ".text_modo" + modo_value;
    $(".modo_text").removeClass('activo');
    $(modo_text_to_show).addClass('activo');
  });


// FIRST CHARGE, CARGA LA EXPLICACION DEL MODO SI DISPONIBLE ################################

$(".modo_btn.activo").trigger( "click" );


// HOVER EL UN BOTON MODO MUESTRA MOMENTANEAMENTE SU EXPLICACION #####################################

function hover_in_modo_btn(){
  var modo_value = $(this).attr('name');
  var modo_text_to_show = ".text_modo" + modo_value;
  $(".modo_text").removeClass('activo');
  $(modo_text_to_show).addClass('activo');
};

function hover_out_modo_btn(){
  $(".modo_btn.activo").trigger( "click" );
};

$(".contenedor_editor_sponsor_consola").on( "mouseleave", ".modo_btn", hover_out_modo_btn );
$(".contenedor_editor_sponsor_consola").on( "mouseenter", ".modo_btn", hover_in_modo_btn );


// CODIGO DEL BOTON REGISTRAR ###################################################################

$(".contenedor_editor_sponsor_consola").on("click", ".registrar_btn", function(){
    var errores = '';
    $(".input_obligatorio_spinner").each(function(){
      if ($(this).val() == 0 || $(this).val() == '') {
        errores = 'error';
      };
    });

    if ($("#modo_input").val() < 0 || $("#modo_input").val() > 5 || $("#modo_input").val() == '') {
      errores = 'error';
    };

    if (errores == '') {
      $('#agencia').prop('disabled', false);
      $("#agencia_params_form").submit();
    }else {
      alert("Faltan valores por ingresar");
    };
});


// CODIGO PARA ESTABLECER LOS SPINNERS ##########################################################

  $("#compra_casa").spinner({
    min: 0,
    step: 5000
  });
  $("#compra_departamento").spinner({
    min: 0,
    step: 5000
  });
  $("#compra_local").spinner({
    min: 0,
    step: 5000
  });
  $("#compra_terreno").spinner({
    min: 0,
    step: 5000
  });

  $("#renta_casa").spinner({
    min: 0,
    step: 50
  });
  $("#renta_departamento").spinner({
    min: 0,
    step: 50
  });
  $("#renta_local").spinner({
    min: 0,
    step: 50
  });
  $("#renta_terreno").spinner({
    min: 0,
    step: 10
  });

  $("#anticretico").spinner({
    min: 0,
    step: 5000
  });

  });
});
