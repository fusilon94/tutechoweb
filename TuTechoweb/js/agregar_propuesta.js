
function auto_grow(element) {
  element.style.height = "5px";
  element.style.height = (element.scrollHeight + 8)+"px";
}

$(document).ready(function(){
  jQuery(function($){


    $(".popup_cerrar").on("click", function(){
      $(".popup_overlay").css("visibility", "hidden");
    });

    $(".popup_borrar_cerrar").on("click", function(){
      $(".popup_borrar_overlay").css("visibility", "hidden");
    });

    $('.referencia').on("input", function(){
      if ($(this).val().match(/^[\w\d\s -/+_#%&áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
        if ($(this).val() !== '') {
            $(this).addClass("borde_rojo");
        };
      } else {
        $(this).removeClass("borde_rojo");
      };
    });

    $('.btn_cambiar_codigos').on('click', function(){

        const referencia = $('.referencia').val();
        const action_sent = 'first_entry';

        if ($('.referencia').hasClass("borde_rojo")) {
          $('.error_message p').html('Caracteres no permitidos en Referencia');
          $('.error_message').css('visibility', 'unset');
        }else if(referencia == ''){
          $('.error_message p').html('Ingrese una Referencia');
          $('.error_message').css('visibility', 'unset');
        }else{
          $.ajax({
              type: "POST",
              url: "process-request-agregar-propuesta.php",
              data: {
                  referencia_sent : referencia,
                  action_sent : action_sent,
              },
          }).done(function(data){
          
              if (data === "error") {
                  $('.error_message p').html('Referencia invalida');
                  $('.error_message').css('visibility', 'unset');
              } else {
                  $('.formulario').css('display', 'none');
                  $('.lista_propuestas_wrap').html(data);
                  $(".propuestas_contenedor").css('display', 'flex');
              }
          
          }); 

        };
          
    })


    
    $('.agregar_btn').on('click', function(){

        const referencia = $('.referencia').val();
        const action_sent = 'nueva_propuesta';

        $.ajax({
            type: "POST",
            url: "process-request-agregar-propuesta.php",
            data: {
                referencia_sent : referencia,
                action_sent : action_sent,
            },
        }).done(function(data){
        
            if (data === "error") {
                $('.error_message').css('visibility', 'unset')
                $('.error_message p').html('Referencia invalida');
            } else {
                $(".popup_content").html(data);
                $(".lista_contactos_form").select2();
                $(".popup_overlay").css("visibility", "unset");

                $('.propuesta_monto_form').on("input", function(){
                  if ($(this).val().match(/^[0-9]+$/g) == null) {//Si se ingrso un caracter no permitido
                    if ($(this).val() !== '') {
                        $(this).addClass("borde_rojo");
                    };
                  } else {
                    $(this).removeClass("borde_rojo");
                  };
                });

                $('.propuesta_comentario').on("input", function(){
                  if ($(this).val().match(/^[\w\d\s .,?!€$%-/+_#%&áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
                    if ($(this).val() !== '') {
                        $(this).addClass("borde_rojo");
                    };
                  } else {
                    $(this).removeClass("borde_rojo");
                  };
                });
            }
        
        });
    })
    
    $('.guardar_btn').on('click', function(){

        const referencia = $('.referencia').val();
        const action_sent = $('.modo').val();
        const cliente = $('.lista_contactos_form option:selected').attr('value');
        const telefono = $('.lista_contactos_form option:selected').attr('telefono');
        const monto = $('.propuesta_monto_form').val();
        const comentario = $('.propuesta_comentario').val();
        const propuesta_id = $('.propuesta_id').val();

        if (cliente == '' || monto == '' || telefono == '') {
          $('.error_message_form p').html('Complete todos los datos obligatorios');
          $('.error_message_form').css('visibility', 'unset');
          return
        }; 
        if($('.propuesta_monto_form').hasClass("borde_rojo") || $('.propuesta_comentario').hasClass("borde_rojo")){
          $('.error_message_form p').html('Caracteres no permitidos en formulario');
          $('.error_message_form').css('visibility', 'unset');
          return
        };
        
        $.ajax({
            type: "POST",
            url: "process-request-agregar-propuesta.php",
            data: {
                referencia_sent : referencia,
                action_sent : action_sent,
                propuesta_id_sent: propuesta_id,
                cliente: cliente,
                telefono: telefono,
                monto: monto,
                comentario: comentario,
            },
        }).done(function(data){
        
            if (data === "error") {
                $('.error_message').css('visibility', 'unset')
                $('.error_message p').html('Referencia invalida');
            } else {
                $('.lista_propuestas_wrap').html(data);
                $(".propuestas_contenedor").css('display', 'flex');
                $(".popup_overlay").css("visibility", "hidden");
            }
        
        });    
    });

    $(".propuestas_contenedor").on("click", ".editar", function(){

        const referencia = $('.referencia').val();
        const action_sent = 'editar_propuesta';
        const propuesta_id = $(this).parent().parent().attr("key");
        $.ajax({
            type: "POST",
            url: "process-request-agregar-propuesta.php",
            data: {
                referencia_sent : referencia,
                action_sent : action_sent,
                propuesta_id_sent : propuesta_id
            },
        }).done(function(data){
        
            if (data === "error") {
                $('.error_message').css('visibility', 'unset')
                $('.error_message p').html('Referencia invalida');
            } else {
                $(".popup_content").html(data);
                $(".lista_contactos_form").select2();
                $(".popup_overlay").css("visibility", "unset");

                $('.propuesta_monto_form').on("input", function(){
                  if ($(this).val().match(/^[0-9]+$/g) == null) {//Si se ingrso un caracter no permitido
                    if ($(this).val() !== '') {
                        $(this).addClass("borde_rojo");
                    };
                  } else {
                    $(this).removeClass("borde_rojo");
                  };
                });

                $('.propuesta_comentario').on("input", function(){
                  if ($(this).val().match(/^[\w\d\s .,?!€$%-/+_#%&áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
                    if ($(this).val() !== '') {
                        $(this).addClass("borde_rojo");
                    };
                  } else {
                    $(this).removeClass("borde_rojo");
                  };
                });
            }
        
        });

    });

    $(".propuestas_contenedor").on("click", ".borrar", function(){

      const monto = $(this).parent().parent().find(".col.propuesta").text();
      const fecha = $(this).parent().parent().find(".col.fecha").text();
      const propuesta_id = $(this).parent().parent().attr("key");

      $(".popup_borrar_content").html(`
        <p>Está a punto de borrar la propuesta: <br>Fecha: ${fecha} <br>Monto: ${monto}</p>
        <input class="propuesta_id" type="hidden" value="${propuesta_id}">
      `);
      $(".popup_borrar_overlay").css("visibility", "unset");

    });


    $(".btn_borrar_confirmar").on("click", function(){

      const referencia = $('.referencia').val();
      const action_sent = 'borrar_propuesta';
      const propuesta_id = $('.propuesta_id').val();
      
      $.ajax({
          type: "POST",
          url: "process-request-agregar-propuesta.php",
          data: {
              referencia_sent : referencia,
              action_sent : action_sent,
              propuesta_id_sent : propuesta_id
          },
      }).done(function(data){

        $('.lista_propuestas_wrap').html(data);
        $(".popup_borrar_overlay").css("visibility", "hidden");

      });

    });



  });
});
