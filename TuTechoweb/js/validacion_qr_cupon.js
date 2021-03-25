$(document).ready(function(){
  jQuery(function($){

    if (window.location.hash) {//verificar que se introdujo codigo qr en el hash
      var qr_code = window.location.hash.substr(1);

      $.ajax({
          type: "POST",
          url: "process-request-validar-qr-cupon.php",
          data: { qr_code_sent : qr_code },
      }).done(function(data){
        var promo_info = data;
        if (promo_info !== "<span class=\"codigo_no_valido\">Código NO válido</span>") {
          $(".validez_cupon_btn").css('visibility', 'visible');
        };
        $(".promocion_contenedor").html(promo_info);
      });


    }else {
      window.location.replace("http://localhost:81/TuTechoweb/index.php");//si no se ingreso codigoqr en el hash entonces redirigir
    };


    $(".validez_cupon_btn").on("click", function(){

        var qr_code = window.location.hash.substr(1);

        $.ajax({
            type: "POST",
            url: "process-request-validar-qr-cupon.php",
            data: { verificacion_requerida : qr_code },
        }).done(function(data){

          if (data == 'requiere_codigo') {
            $('.overlay_popup_aviso_advertencia').css('visibility',  'visible');
          };
          if (data == 'exito') {
            $(".validez_cupon_btn").css('visibility', 'hidden');
            $(".promocion_contenedor").html("<span class=\"codigo_no_valido\">Éxito<i class=\"fas fa-check-circle\"></i></span><span class=\"extra_aviso\">Asegurese SIEMPRE de estar en Tutecho.com al validar cupones</span>");
          };

        });


    });

    $('.btn_cancelar').on("click", function(){
      $('.overlay_popup_aviso_advertencia').css('visibility',  'hidden');
    });

    $('.btn_aceptar').on("click", function(){
      var codigo_sponsor = $("#codigo_sponsor").val();
      var qr_code = window.location.hash.substr(1);

      if (codigo_sponsor !== '') {

          $.ajax({
              type: "POST",
              url: "process-request-validar-qr-cupon.php",
              data: { codigo_sponsor_sent : codigo_sponsor, qr_code_extra : qr_code },
          }).done(function(data){
              if (data == 'codigo_incorrecto') {
                alert('Código Incorrecto');
              }else {
                if (data !== '') {
                  $('.overlay_popup_aviso_advertencia').css('visibility',  'hidden');
                  $(".validez_cupon_btn").css('visibility', 'hidden');
                  $(".promocion_contenedor").html("<span class=\"codigo_no_valido\">Éxito<i class=\"fas fa-check-circle\"></i></span><span class=\"codigo_no_valido\">Código Respuesta: " + data + "</span><span class=\"extra_aviso\">Asegurese SIEMPRE de estar en Tutecho.com al validar cupones</span>");
                };
              };

          });

      }else {
        alert('Ingrese su código - Por Favor');
      };
    });

  });
});
