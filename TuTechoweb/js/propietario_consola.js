$(document).ready(function(){
  jQuery(function($){


    $.ajax({
        type: "POST",
        url: "process-request-propietario-consola.php",
        data: { referencia_sent : referencia },
    }).done(function(data){
      $(".contenido").html(data)
    });


    $(".popup_cerrar").on('click', function() {
      $(".popup_overlay").css("visibility", "hidden");
    });

    $(".popup_cerrar_exito").on('click', function() {
      $(".popup_overlay_exito").css("visibility", "hidden");
    });


    $('.contenido').on('click', '.action_reclamo', function() {

      const agente_id = $(this).parent().find('.agente_nombre').attr('agente_id');
      const agente_nombre = $(this).parent().find('.agente_nombre').text();
      const fecha = $(this).parent().find('.fecha_visita').text().split(' - ')[0];
      const hora = $(this).parent().find('.fecha_visita').text().split(' - ')[1];
      const foto_src = $(this).parent().find('img').attr('src');
 
      $(".popup_content").html(`
          <h2>Reclamo sobre la Visita:</h2>
          <div class="visita_wrap_popup">
            <span class="agente_foto"><img src="${foto_src}" alt=""></span>
            <span class="agente_info">
              <span class="agente_nombre">${agente_nombre}</span>
              <span class="fecha_visita">${fecha} - ${hora}</span>
            </span>
          </div>
          <textarea class="reclamo" placeholder="-- Aquí, tu reclamo --"></textarea>

          <span class="mensaje_error"></span>
      `);

      $(".popup_overlay").css("visibility", "unset");


      // Event listener se remplaza a si mismo
      document.querySelector('.btn_enviar_reclamo').onclick =  function() {

        const reclamo = $('.reclamo').val();

        if (reclamo == '') {
          $('.mensaje_error').html(`
          <i class="fa fa-exclamation-circle"></i>
          <p>Debe escribir un reclamo</p>
          `).css("visibility", "unset");
          return
        }

        $.ajax({
          type: "POST",
          url: "process-request-propietario-consola.php",
          data: { 
            referencia_reclamo_sent : referencia,
            agente_id_sent: agente_id,
            agente_nombre_sent: agente_nombre,
            fecha_sent: fecha,
            hora_sent: hora,
            reclamo_sent: reclamo,
          },
        }).done(function(data){

          if (data == 'exito') {
            $(".popup_overlay").css("visibility", "hidden");
            $(".popup_overlay_exito").css("visibility", "unset");
          }else{
            alert("Error de sistema")
          };
          

        });

        
      };
      
      
    })





  });
});
