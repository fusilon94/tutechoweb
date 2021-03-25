$(document).ready(function(){
  jQuery(function($){
    
    let datos_pais;
    $.ajax({
      type: "POST",
      url: "../../contenido/m5/process-request-coordenadas-paises.php",
      dataType: 'json',
      async: false,
    }).done(function(data){
      datos_pais = data;
    });
    
    $(".departamento_label").html(`${datos_pais['org_territorial']}: `);


// CODIGO VALIDACION FORMULARIO - al rellenar campos requeridos ###################################

        $('.campo_req').on('change', function (){
          var parent_name = this.closest(".section_container").getAttribute('name');
          var title_dot = document.getElementById(parent_name).querySelector('.fas');
          if ($(title_dot).css('display') == 'none') {
              // no se hace nada
          } else {

            var requiredlength = $('.'+parent_name).length;
            console.log(requiredlength);
            var value = $('.'+parent_name).filter(function () {
                return this.value != '';
            });

            if (value.length>=0 && (value.length !== requiredlength)) {
              if ($(title_dot).css('color') == 'rgb(13, 217, 21)') {
                $(title_dot).removeClass('fa-check');
                $(title_dot).addClass('fa-circle');
              };
              $(title_dot).css('color', 'rgb(242, 48, 42)');
            } else {
              $(title_dot).removeClass('fa-circle');
              $(title_dot).addClass('fa-check');
              $(title_dot).css('color', 'rgb(13, 217, 21)')
            };
          };
        });


// CODIGO VALIDACION FORMULARIO FINAL - al hacer click en submit button ###########

      $('#boton_submit_form').on('click', function () {
        var boton_validation = document.getElementById('#boton_validar_form');
        var boton_submit = document.getElementById('#boton_submit_form');

        var reqlength = $('.campo_req').length;
        console.log(reqlength);
        var value = $('.campo_req').filter(function () {
            return this.value != '';
        });

        if (value.length>=0 && (value.length !== reqlength)) {
            $( "#dialog" ).dialog( "open" );
            $('#boton_submit_form').css('display', 'none');
        } else {
              this.form.submit();
          };
      });

// CODIGO VENTANA ALERT LINKED TO VALIDAR DATOS BUTTON #########

$( "#dialog" ).dialog({
      autoOpen: false,
      buttons: {
        Ok: function() {
          $( this ).dialog( "close" );
        }
      },
      show: {
        effect: "blind",
        duration: 500
      },
      hide: {
        effect: "fade",
        duration: 500
      }
    });

// CODIGO TABS TIPO ACORDION ###################################

        $( "#accordion" ).accordion({
          collapsible: true,
          heightStyle: "content",
          icons: { "header": "ui-icon-caret-1-s", "activeHeader": "ui-icon-caret-1-n" },
        });

// CODIGO VENTANA INFORMACION ##################################

        $( ".info_ventana" ).dialog({  // esta es una class para todas las ventanas
            autoOpen: false,
            show: {
            effect: "blind",
            duration: 1000
            },
            hide: {
            duration: 500
            }
        });

// CODIGO DATEPICKER ###########################################

        $( "#fecha_registro" ).datepicker({
            changeMonth: true,
            changeYear: true
        });

        $( "#fecha_venta" ).datepicker({
            changeMonth: true,
            changeYear: true
        });

// Mecanismo de envio de valores para inputs de tipo checkbox #####################

      $('input[type="checkbox"]').on('change', function(e){
        if($(this).prop('checked'))
        {
            $(this).next().val(1);
        } else {
            $(this).next().val(0);
        }
      });

// Esto permite verificar que en ciertos espacios solo se introduzca numeros, en caso contrario mostrar una alerta

    validarSiNumero = function(numero){
      var RE = /^\d*\.?\d*$/;
      if (RE.test(numero)) {
      } else {
          alert("El valor " + numero + " no es un número");
      };
    };
    
  });
});
