$(document).ready(function(){
  jQuery(function($){

// CODIGO PARA POBLAR SELECT AGENCIAS SEGUN PAIS INGRESADO

    $("select.pais").change(function(){

    let pais_selected = $("#pais option:selected").val();
    if (pais_selected !== '') {
        $.ajax({
            type: "POST",
            url: "process-request-agencias-pais.php",
            data: { paisChoice : pais_selected }
        }).done(function(data){
            
            $("#agencia").prop('disabled', false).html(data);
            
        });

    } else if (pais_selected == ''){
        $("#agencia").prop('disabled', true).empty();  
    };
    });


//CODIGO PARA TRAER LA TABLA PRECIOS DE LA AGENCIA, SINO EXISTE UNA TRAER TEMPLATE

    $("select.agencia").change(function(){
        let agenciaSelected = $(".agencia option:selected").val();
        let pais_selected = $("#pais option:selected").val();

        if (agenciaSelected !== '') { //si hubo una seleccion se cargan las ciudades de la db

            $.ajax({
                type: "POST",
                url: "process-request-agencia-precios-tabla.php",
                data: { agenciaChoice : agenciaSelected,  paisChoice : pais_selected}
            }).done(function(data){
                    $(".all_params_container").empty().html(data);
            });

        }else{
            $(".all_params_container").empty();  
        };
    });


// CODIGO PARA AUMENTAR LINEAS A LA TABLA DE PRECIOS

    $(".all_params_container").on("click", ".btn_agregar1", function(){
        let contenedor = $(this).parent().parent();
        let moneda = $(this).attr('data1');
        
        contenedor.before(`<div class='int_row row'>
        <div class='rango col'>
            <input type='text' value='' class='min'>
            <p>&nbsp-&nbsp</p>
            <input type='text' value='' class='max'></input>
            <span class='moneda'>&nbsp${moneda}</span>
        </div>
        <div class='exclusivo col'>
            <input type='text' value='' class='monto'>
            <select class='tipo'>
                <option value='fijo'>${moneda}</option>
                <option value='porcentage'>% PVF</option>
            </select>
        </div>
        <div class='no_exclusivo col'>
            <input type='text' value='' class='monto'>
            <select class='tipo'>
                <option value='fijo'>${moneda}</option>
                <option value='porcentage'>% PVF</option>
                <option value='no_disponible'>NO HAY</option>
            </select>
            <span class='btn btn_quitar btn_quitar_row1'><i class='fas fa-times-circle'></i></span>
        </div>
    </div>`);

    });

    $(".all_params_container").on("click", ".btn_agregar2", function(){
        let contenedor = $(this).parent().parent();
        let moneda = $(this).attr('data1');
        
        contenedor.before(`<div class='int_row row'>
        <div class='rango col'>
            <input type='text' value='' class='min'>
            <p>&nbsp-&nbsp</p>
            <input type='text' value='' class='max'></input>
            <span class='moneda'>&nbsp${moneda}</span>
        </div>
        <div class='exclusivo col'>
            <input type='text' value='' class='monto'>
            <select class='tipo'>
                <option value='fijo'>${moneda}</option>
                <option value='porcentage'>% del Alquiler</option>
            </select>
        </div>
        <div class='no_exclusivo col'>
            <input type='text' value='' class='monto'>
            <select class='tipo'>
                <option value='fijo'>${moneda}</option>
                <option value='porcentage'>% del Alquiler</option>
                <option value='no_disponible'>NO HAY</option>
            </select>
            <span class='btn btn_quitar btn_quitar_row2'><i class='fas fa-times-circle'></i></span>
        </div>
    </div>`);

    });

    $(".all_params_container").on("input", "input:not(.otros)", function(){
        if($(this).val().match(/^[+\-0-9().\/]+$/g) == null){
            $(".popup_contenido").html("Solo deben ingresarse numeros");
            $(".popup_overlay").css("visibility", "unset");
            $(this).addClass("borde_rojo");
        }else{
            $(this).removeClass("borde_rojo");
        };
    });

    $(".all_params_container").on("input", "input.otros", function(){
        if($(this).val().match(/^[\w\d\s -+áÁéÉíÍóÓúÚñÑ%/$€\']+$/) == null){
            $(".popup_contenido").html("Caracter no permitido");
            $(".popup_overlay").css("visibility", "unset");
            $(this).addClass("borde_rojo");
        }else{
            $(this).removeClass("borde_rojo");
        };
    });

    $(".all_params_container").on("click", ".btn_guardar_tabla_precios", function(){
        let errores = '';

        $(".all_params_container input").each(function(){
            if ($(this).val() == ''){
                errores = 'error';
            };
            if ($(this).css('border-color') == "rgb(255, 0, 0)") {
                errores = 'error'; 
            };
        });

        if (errores !== '') {
            $(".popup_contenido").html("Todos los campos deben llenarse correctamente");
            $(".popup_overlay").css("visibility", "unset");
        }else{

            let json_constructor = {
                fecha : '',
                venta : {
                    first : {
                        rango : {max : ''},
                        exclusivo : {tipo : '', monto : ''},
                        no_exclusivo : {tipo : '', monto : ''}
                    },
                    intermediate : {
                        0 : {
                            rango : {min : '', max : ''},
                            exclusivo : {tipo : '', monto : ''},
                            no_exclusivo : {tipo : '', monto : ''}
                        }
                    },
                    last : {
                        rango : {min : ''},
                        exclusivo : {tipo : '', monto : ''},
                        no_exclusivo : {tipo : '', monto : ''}
                    },
                    lotes : {
                        max_lotes : '',
                        exclusivo : {tipo : '', monto : ''},
                        no_exclusivo : {tipo : '', monto : ''}
                    }
                },
                alquiler : {
                    first : {
                        rango : {max : ''},
                        exclusivo : {tipo : '', monto : ''},
                        no_exclusivo : {tipo : '', monto : ''}
                    },
                    intermediate : {
                        0 : {
                            rango : {min : '', max : ''},
                            exclusivo : {tipo : '', monto : ''},
                            no_exclusivo : {tipo : '', monto : ''}
                        }
                    },
                    last : {
                        rango : {min : ''},
                        exclusivo : {tipo : '', monto : ''},
                        no_exclusivo : {tipo : '', monto : ''}
                    },
                    lotes : {
                        max_lotes : '',
                        exclusivo : {tipo : '', monto : ''},
                        no_exclusivo : {tipo : '', monto : ''}
                    }
                },
                otros : {
                    administracion : {
                        monto : ''
                    },
                    check_estado : {
                        monto : '',
                        min : ''
                    }
                }
            };

            // SE GUARDAN LOS DATOS DE VENTA EN EL JSON

            json_constructor['fecha'] = current_date;

            let tabla_venta = $(".tabla_venta");

            json_constructor['venta']['first']['rango']['max'] = tabla_venta.find('.first_row .rango .max').val();
            
            json_constructor['venta']['first']['exclusivo']['tipo'] = tabla_venta.find('.first_row .exclusivo .tipo option:selected').val();
            json_constructor['venta']['first']['exclusivo']['monto'] = tabla_venta.find('.first_row .exclusivo .monto').val();
            
            json_constructor['venta']['first']['no_exclusivo']['tipo'] = tabla_venta.find('.first_row .no_exclusivo .tipo option:selected').val();
            json_constructor['venta']['first']['no_exclusivo']['monto'] = tabla_venta.find('.first_row .no_exclusivo .monto').val();

            
            let count = 0;
            tabla_venta.find(".int_row").each(function(){
                json_constructor['venta']['intermediate'][count] = {
                    rango : {min : '', max : ''},
                    exclusivo : {tipo : '', monto : ''},
                    no_exclusivo : {tipo : '', monto : ''}
                };

                json_constructor['venta']['intermediate'][count]['rango']['min'] = $(this).find('.rango .min').val();
                json_constructor['venta']['intermediate'][count]['rango']['max'] = $(this).find('.rango .max').val();
                
                json_constructor['venta']['intermediate'][count]['exclusivo']['tipo'] = $(this).find('.exclusivo .tipo option:selected').val();
                json_constructor['venta']['intermediate'][count]['exclusivo']['monto'] = $(this).find('.exclusivo .monto').val();
                
                json_constructor['venta']['intermediate'][count]['no_exclusivo']['tipo'] = $(this).find('.no_exclusivo .tipo option:selected').val();
                json_constructor['venta']['intermediate'][count]['no_exclusivo']['monto'] = $(this).find('.no_exclusivo .monto').val();
                
                count += 1;   
            });
            
            json_constructor['venta']['last']['rango']['min'] = tabla_venta.find('.last_row .rango .min').val();
            
            json_constructor['venta']['last']['exclusivo']['tipo'] = tabla_venta.find('.last_row .exclusivo .tipo option:selected').val();
            json_constructor['venta']['last']['exclusivo']['monto'] = tabla_venta.find('.last_row .exclusivo .monto').val();
            
            json_constructor['venta']['last']['no_exclusivo']['tipo'] = tabla_venta.find('.last_row .no_exclusivo .tipo option:selected').val();
            json_constructor['venta']['last']['no_exclusivo']['monto'] = tabla_venta.find('.last_row .no_exclusivo .monto').val();
            

            json_constructor['venta']['lotes']['max_lotes'] = tabla_venta.find('.max_lotes_row .max_lotes').val();
            
            json_constructor['venta']['lotes']['exclusivo']['tipo'] = tabla_venta.find('.lotes_row .exclusivo .tipo option:selected').val();
            json_constructor['venta']['lotes']['exclusivo']['monto'] = tabla_venta.find('.lotes_row .exclusivo .monto').val();
            
            json_constructor['venta']['lotes']['no_exclusivo']['tipo'] = tabla_venta.find('.lotes_row .no_exclusivo .tipo option:selected').val();
            json_constructor['venta']['lotes']['no_exclusivo']['monto'] = tabla_venta.find('.lotes_row .no_exclusivo .monto').val();


            // SE GUARDAN LOS DATOS DE ALQUILER EN EL JSON

            let tabla_alquiler = $(".tabla_alquiler");


            json_constructor['alquiler']['first']['rango']['max'] = tabla_alquiler.find('.first_row .rango .max').val();
            
            json_constructor['alquiler']['first']['exclusivo']['tipo'] = tabla_alquiler.find('.first_row .exclusivo .tipo option:selected').val();
            json_constructor['alquiler']['first']['exclusivo']['monto'] = tabla_alquiler.find('.first_row .exclusivo .monto').val();
            
            json_constructor['alquiler']['first']['no_exclusivo']['tipo'] = tabla_alquiler.find('.first_row .no_exclusivo .tipo option:selected').val();
            json_constructor['alquiler']['first']['no_exclusivo']['monto'] = tabla_alquiler.find('.first_row .no_exclusivo .monto').val();

            
            let count2 = 0;
            tabla_alquiler.find(".int_row").each(function(){
                json_constructor['alquiler']['intermediate'][count2] = {
                    rango : {min : '', max : ''},
                    exclusivo : {tipo : '', monto : ''},
                    no_exclusivo : {tipo : '', monto : ''}
                };

                json_constructor['alquiler']['intermediate'][count2]['rango']['min'] = $(this).find('.rango .min').val();
                json_constructor['alquiler']['intermediate'][count2]['rango']['max'] = $(this).find('.rango .max').val();
                
                json_constructor['alquiler']['intermediate'][count2]['exclusivo']['tipo'] = $(this).find('.exclusivo .tipo option:selected').val();
                json_constructor['alquiler']['intermediate'][count2]['exclusivo']['monto'] = $(this).find('.exclusivo .monto').val();
                
                json_constructor['alquiler']['intermediate'][count2]['no_exclusivo']['tipo'] = $(this).find('.no_exclusivo .tipo option:selected').val();
                json_constructor['alquiler']['intermediate'][count2]['no_exclusivo']['monto'] = $(this).find('.no_exclusivo .monto').val();
                
                count2 += 1;   
            });
            
            json_constructor['alquiler']['last']['rango']['min'] = tabla_alquiler.find('.last_row .rango .min').val();
            
            json_constructor['alquiler']['last']['exclusivo']['tipo'] = tabla_alquiler.find('.last_row .exclusivo .tipo option:selected').val();
            json_constructor['alquiler']['last']['exclusivo']['monto'] = tabla_alquiler.find('.last_row .exclusivo .monto').val();
            
            json_constructor['alquiler']['last']['no_exclusivo']['tipo'] = tabla_alquiler.find('.last_row .no_exclusivo .tipo option:selected').val();
            json_constructor['alquiler']['last']['no_exclusivo']['monto'] = tabla_alquiler.find('.last_row .no_exclusivo .monto').val();
            

            json_constructor['alquiler']['lotes']['max_lotes'] = tabla_alquiler.find('.max_lotes_row .max_lotes').val();
            
            json_constructor['alquiler']['lotes']['exclusivo']['tipo'] = tabla_alquiler.find('.lotes_row .exclusivo .tipo option:selected').val();
            json_constructor['alquiler']['lotes']['exclusivo']['monto'] = tabla_alquiler.find('.lotes_row .exclusivo .monto').val();
            
            json_constructor['alquiler']['lotes']['no_exclusivo']['tipo'] = tabla_alquiler.find('.lotes_row .no_exclusivo .tipo option:selected').val();
            json_constructor['alquiler']['lotes']['no_exclusivo']['monto'] = tabla_alquiler.find('.lotes_row .no_exclusivo .monto').val();


            // SE GUARDAN LOS DATOS DE ALQUILER EN EL JSON

            let tabla_otros = $(".tabla_otros");

            json_constructor['otros']['administracion']['monto'] = tabla_otros.find('.administracion_row .monto').val();
            
            json_constructor['otros']['check_estado']['monto'] = tabla_otros.find('.estado_row .monto').val();
            json_constructor['otros']['check_estado']['min'] = tabla_otros.find('.min_row .min_precio').val();
            

            const final_json = JSON.stringify(json_constructor);
            let pais_selected = $("#pais option:selected").val();
            let agencia_selected = $(".agencia option:selected").val();
            $.ajax({
                type: "POST",
                url: "process-request-agencia-json-pdf.php",
                data: { json_sent : final_json,
                        pais_sent : pais_selected,
                        agencia_sent : agencia_selected }
            }).done(function(data){
                
                $(".popup_contenido").html('Tabla de Precios guardada exitosamente');
                $(".popup_overlay").css("visibility", "unset");
                $(".btn_previsualizar").css("display", 'flex');
            });
            
        };

    });

// CODIGO PARA QUITAR LINEAS A LA TABLA DE PRECIOS

$(".all_params_container").on("click", ".btn_quitar", function(){

    let count_intermediary = $(this).parent().parent().parent().find('.int_row').length;
    let contenedor = $(this).parent().parent();

    if (count_intermediary > 1) {
        contenedor.remove();
    }else{
        $(".popup_contenido").html('Debe quedar almenos una fila intermediaria');
        $(".popup_overlay").css("visibility", "unset");
    };
    
});

// Btn cerrar popup

$(".cerrar_popup").on("click", function(){
    $(".popup_overlay").css("visibility", "hidden");
});

// Btn cerrar preview

$(".cerrar_preview").on("click", function(){
    $(".preview_overlay").css("visibility", "hidden");
});

// Btn PREVISUALIZAR PDF

$(".all_params_container").on("click", ".btn_previsualizar", function(){

    let pais_selected = $("#pais option:selected").val();
    let agencia_selected = $(".agencia option:selected").val();
    $.ajax({
        type: "POST",
        url: "process-request-agencia-tabla-precios-previsualizacion.php",
        data: { pais_sent : pais_selected,
                agencia_sent : agencia_selected }
    }).done(function(data){
        
        $(".preview_contenido").html(data);
        
        let count = 0;
        $(".tabla_venta_gris tr").each(function(){
            
            if (count == 0) {
                count += 1;
            } else if (count % 2 == 0){//si es numero
                    
                    count += 1;
            } else {// si es impar
                    $(this).addClass('fondo_gris');
                    count += 1;
            };
            
        });

        count = 0;
        $(".tabla_alquiler_gris tr").each(function(){
            
            if (count == 0) {
                count += 1;
            } else if (count % 2 == 0){//si es numero
                    
                    count += 1;
            } else {// si es impar
                    $(this).addClass('fondo_gris');
                    count += 1;
            };
            
        });

        $(".preview_overlay").css("visibility", "unset");
    });
    
});


  });
});
