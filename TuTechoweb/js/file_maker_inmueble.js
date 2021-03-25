$(document).ready(function(){
    jQuery(function($){

    let tipo_bien;

    if(id_file == ''){
        tipo_bien = tipo_doc;
    }else{
        if (id_file.includes("C")) {  tipo_bien = "casa";
            } else { if (id_file.includes("D")) {  tipo_bien = "departamento";
                } else { if (id_file.includes("L")) {  tipo_bien = "local";
                    } else { if (id_file.includes("T")) {  tipo_bien = "terreno";
                    };
                };
            };
        };
    };

    $("#tipo_inmueble").val(tipo_bien);

// ########## POBLADO DEL INPUTS CONTENDOR

    $(".inputs_contenedor").html(`

        <span class="input_wrap">
        <label for="nombre_cliente">Nombre del Cliente: </label>
        <input id="nombre_cliente" type="text" name="nombre_cliente" value="">
        </span>
        
        <span class="input_wrap">
            <label for="apellido_cliente">Apellido(s) del Cliente: </label>
            <input id="apellido_cliente" type="text" name="apellido_cliente" value="">
        </span>

        <div class="input_wrap">
            <label for="tipo_doc_identidad">Tipo de documento de identidad: </label>
            <select name="tipo_doc_identidad" id="tipo_doc_identidad">
            <option value=""></option>
            <option value="carnet de identidad">Carnet de Identidad</option>
            <option value="pasaporte">Pasaporte</option>
            </select>
        </div>

        <span class="input_wrap">
            <label for="numero_doc_identidad">N° del documento de identidad: </label>
            <input id="numero_doc_identidad" type="text" name="numero_doc_identidad" value="" placeholder="XXXXXXX LP">
        </span>

        <span class="input_wrap">
            <label for="email_cliente">Email del Cliente: </label>
            <input id="email_cliente" type="text" name="email_cliente" value="">
        </span>

        <span class="input_wrap">
            <label for="telefono_cliente">Telefono del Cliente: </label>
            <input id="telefono_cliente" type="text" name="telefono_cliente" value="" placeholder="(+591) XXXXXXXX">
        </span>

        <div class="input_wrap">
            <label for="agencia_id">Agencia: </label>
            <select name="agencia_id" id="agencia_id">
            </select>
        </div>

        <span class="input_wrap">
            <label for="pais">Pais: </label>
            <input id="pais" type="text" name="pais" value="${pais_selected}" readonly>
        </span>

        <div class="input_wrap">
            <label for="departamento" class="departamento_label"></label>
            <select name="departamento" id="departamento">
            <option></option>
            </select>
        </div>

        <div class="input_wrap">
            <label for="ciudad">Ciudad: </label>
            <select name="ciudad" id="ciudad" disabled>
                <option></option>
            </select>
        </div>

        <div class="input_wrap">
            <label for="barrio">Barrio: </label>
            <select name="barrio" id="barrio" disabled>
                <option></option>
            </select>
        </div>

        <span class="input_wrap">
            <label for="direccion_cliente">Dirección del Cliente: </label>
            <input id="direccion_cliente" type="text" name="direccion_cliente" value="" placeholder="#Vivienda Av/Calle, Barrio">
        </span>

        <span class="input_wrap">
            <label for="direccion_inmueble">Dirección del Inmueble: </label>
            <input id="direccion_inmueble" type="text" name="direccion_inmueble" value="" placeholder="#Vivienda Av/Calle, Barrio">
        </span>

        <span class="input_wrap opcional">
            <label for="direccion_inmueble_complemento">Complemento dirección Inmueble: </label>
            <input id="direccion_inmueble_complemento" type="text" name="direccion_inmueble_complemento" value="" placeholder="Edf XXXX, Dept. XXXX">
        </span>

        <span class="input_wrap">
            <label for="base_imponible">Base Imponible ${moneda}: </label>
            <input id="base_imponible" type="text" name="base_imponible" value="">
        </span>

        <span class="input_wrap">
            <label for="avaluo">Avaluo ${moneda}: </label>
            <input id="avaluo" type="text" name="avaluo" value="">
        </span>

        <span class="input_wrap">
            <label for="impuestos">Impuestos Anuales ${moneda}: </label>
            <input id="impuestos" type="text" name="impuestos" value="">
        </span>
        
        <span class="input_wrap opcional">
            <label for="mantenimiento">Mantenimiento mensual ${moneda} - OPCIONAL: </label>
            <input id="mantenimiento" type="text" name="mantenimiento" value="">
        </span>

    `);

    if (tipo_doc == "terreno") {
        $(".inputs_contenedor").append(`
        
        <span class="input_wrap">
            <label for="superficie_terreno">Superficie del Terreno (m<sup>2</sup>): </label>
            <input id="superficie_terreno" type="text" name="superficie_terreno" value="">
        </span>

        `);
    }else if(tipo_doc == "casa"){
        $(".inputs_contenedor").append(`
        
        <span class="input_wrap">
            <label for="superficie_inmueble">Superficie del Inmueble (m<sup>2</sup>): </label>
            <input id="superficie_inmueble" type="text" name="superficie_inmueble" value="">
        </span>

        <span class="input_wrap">
            <label for="superficie_terreno">Superficie del Terreno (m<sup>2</sup>): </label>
            <input id="superficie_terreno" type="text" name="superficie_terreno" value="">
        </span>
        `);
    }else if(tipo_doc == "departamento" || tipo_doc == "local"){
        $(".inputs_contenedor").append(`
        
        <span class="input_wrap">
            <label for="superficie_inmueble">Superficie del Inmueble (m<sup>2</sup>): </label>
            <input id="superficie_inmueble" type="text" name="superficie_inmueble" value="">
        </span>
        `);
    };

    if (tipo_file_recieved == 'venta') {
        $(".inputs_contenedor").append(`

        <span class="input_wrap">
            <label for="precio_inmueble">Precio del Inmueble ${moneda}: </label>
            <input id="precio_inmueble" type="text" name="precio_inmueble" value="">
        </span>

        <div class="input_wrap">
            <span class="form_btn" id="pre_venta_btn">Pre-Venta</span>
            <input id="pre_venta" type="hidden" name="pre_venta" value="0">
        </div>
        
        `);
        
    }else if(tipo_file_recieved == 'alquiler'){

        $(".inputs_contenedor").append(`

        <span class="input_wrap">
            <label for="precio_inmueble"><p class="precio_alquiler_label">Alquiler mensual</p> &nbsp${moneda}: </label>
            <input id="precio_inmueble" type="text" name="precio_inmueble" value="">
        </span>

        <div class="input_wrap">
            <span class="form_btn" id="anticretico_btn">Anticretico</span>
            <input id="anticretico" type="hidden" name="anticretico" value="0">
        </div>

        <div class="input_wrap">
            <span class="form_btn">Gestion Acordada</span>
            <input id="gestion" type="hidden" name="gestion" value="0">
        </div>
        
        
        `);

    };

    $(".inputs_contenedor").append(`
    
        <div class="input_wrap">
            <span class="form_btn">Exclusivo</span>
            <input id="exclusivo" type="hidden" name="exclusivo" value="0">
        </div>

        <div class="input_wrap">
            <span class="form_btn" id="contrato_especial_btn">Contrato Especial</span>
            <input id="contrato_especial" type="hidden" name="contrato_especial" value="0">
        </div>
        
        <span class="input_wrap">
            <label for="contrato_especial_comentario">Explique el motivo: </label>
            <textarea name="contrato_especial_comentario" id="contrato_especial_comentario" rows="1" class="pregunta_textarea" oninput="auto_grow(this)" disabled></textarea>
        </span>
    `);

// ########## POBLADO DEL DRAGS CONTENDOR


$(".drags_contenedor").html(`

    <div class="contenedor_foto">
        <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Contrato Firmado (PDF) </p>
        <div id="campo_contrato" class="campo_foto">
        <label for="contrato" id="contrato_label"><p>Sube el PDF</br><span>Click or Drop</span></p><i class="far fa-check-circle"></i></label>
        <input type="file" id="contrato" name="contrato" data-id="contrato" onchange="check(this)" accept="application/pdf">
        </div>
    </div>

    <div class="contenedor_foto">
        <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Información de Derecho Propietario (PDF) </p>
        <div id="campo_info_derecho_propietario" class="campo_foto">
        <label for="info_derecho_propietario" id="info_derecho_propietario_label"><p>Sube el PDF</br><span>Click or Drop</span></p><i class="far fa-check-circle"></i></label>
        <input type="file" id="info_derecho_propietario" name="info_derecho_propietario" data-id="info_derecho_propietario" onchange="check(this)" accept="application/pdf">
        </div>
    </div>

    <div class="contenedor_foto">
        <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Documento(s) de identidad: Propietario(s) (PDF) </p>
        <div id="campo_doc_identidad_propietario" class="campo_foto">
        <label for="doc_identidad_propietario" id="doc_identidad_propietario_label"><p>Sube el PDF</br><span>Click or Drop</span></p><i class="far fa-check-circle"></i></label>
        <input type="file" id="doc_identidad_propietario" name="doc_identidad_propietario" data-id="doc_identidad_propietario" onchange="check(this)" accept="application/pdf">
        </div>
    </div>

    <div class="contenedor_foto opcional">
        <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Poder Notariado (PDF - OPCIONAL) </p>
        <div id="campo_poder_notariado" class="campo_foto">
        <label for="poder_notariado" id="poder_notariadio_label"><p>Sube el PDF</br><span>Click or Drop</span></p><i class="far fa-check-circle"></i></label>
        <input type="file" id="poder_notariado" name="poder_notariado" data-id="poder_notariado" onchange="check(this)" accept="application/pdf">
        </div>
    </div>

    <div class="contenedor_foto opcional">
        <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Documento de Identidad: Apoderado (PDF - OPCIONAL) </p>
        <div id="campo_doc_identidad_apoderado" class="campo_foto">
        <label for="doc_identidad_apoderado" id="doc_identidad_apoderado_label"><p>Sube el PDF</br><span>Click or Drop</span></p><i class="far fa-check-circle"></i></label>
        <input type="file" id="doc_identidad_apoderado" name="doc_identidad_apoderado" data-id="doc_identidad_apoderado" onchange="check(this)" accept="application/pdf">
        </div>
    </div>

    <div class="contenedor_foto" style="display: none">
        <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Aprobación Planos (PDF) </p>
        <div id="campo_aprobacion_planos" class="campo_foto">
        <label for="aprobacion_planos" id="aprobacion_planos_label"><p>Sube el PDF</br><span>Click or Drop</span></p><i class="far fa-check-circle"></i></label>
        <input type="file" id="aprobacion_planos" name="aprobacion_planos" data-id="aprobacion_planos" onchange="check(this)" accept="application/pdf" disabled>
        </div>
    </div>

`);

if (tipo_file_recieved == 'venta') {
    $(".drags_contenedor").append(`
        <div class="contenedor_foto">
            <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Últimos 3 pagos de impuestos (PDF) </p>
            <div id="campo_pagos_impuestos" class="campo_foto">
            <label for="pagos_impuestos" id="pagos_impuestos_label"><p>Sube el PDF</br><span>Click or Drop</span></p><i class="far fa-check-circle"></i></label>
            <input type="file" id="pagos_impuestos" name="pagos_impuestos" data-id="pagos_impuestos" onchange="check(this)" accept="application/pdf">
            </div>
        </div>
    `); 
}

// ####### VERIFICACION JS DE LLENADO DE DATOS DE LOS INPUTS

  $("#nombre_cliente").on('input', function(){
    if ($(this).val().match(/^[\w\d\s áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
      $(this).addClass("borde_rojo");
    } else {
        $(this).removeClass("borde_rojo");
    };
  });

  $("#apellido_cliente").on('input', function(){
    if ($(this).val().match(/^[\w\d\s áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
      $(this).addClass("borde_rojo");
    } else {
        $(this).removeClass("borde_rojo");
    };
  });

  $("#numero_doc_identidad").on('input', function(){
    if ($(this).val().match(/^[\w\d\s+\-& \/áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
      $(this).addClass("borde_rojo");
    } else {
        $(this).removeClass("borde_rojo");
    };
  });

  $("#email_cliente").on('input', function(){
    if ($(this).val().match(/^[\w\d\s+\-.áÁéÉíÍóÓúÚñÑ@\']+$/) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
      $(this).addClass("borde_rojo");
    } else {
        $(this).removeClass("borde_rojo");
    };
  });

  $("#telefono_cliente").on('input', function(){
    if ($(this).val().match(/^[+\-0-9().# \/]+$/g) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
      $(this).addClass("borde_rojo");
    } else {
        $(this).removeClass("borde_rojo");
    };
  });

  $("#direccion_cliente").on('input', function(){
    if ($(this).val().match(/^[\w\d\s+\-&.,#:\/áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
      $(this).addClass("borde_rojo");
    } else {
        $(this).removeClass("borde_rojo");
    };
  });

  $("#direccion_inmueble").on('input', function(){
    if ($(this).val().match(/^[\w\d\s+\-&.,#:\/áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
      $(this).addClass("borde_rojo");
    } else {
        $(this).removeClass("borde_rojo");
    };
  });

  $("#direccion_inmueble_complemento").on('input', function(){
    if ($(this).val().match(/^[\w\d\s+\-&.,#:\/áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
      $(this).addClass("borde_rojo");
    } else {
        $(this).removeClass("borde_rojo");
    };
  });

  $("#base_imponible").on('input', function(){
    if ($(this).val().match(/^[0-9.,\/]+$/g) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
      $(this).addClass("borde_rojo");
    } else {
        $(this).removeClass("borde_rojo");
    };
  });

  $("#impuestos").on('input', function(){
    if ($(this).val().match(/^[0-9.,\/]+$/g) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
      $(this).addClass("borde_rojo");
    } else {
        $(this).removeClass("borde_rojo");
    };
  });

  $("#avaluo").on('input', function(){
    if ($(this).val().match(/^[0-9.,\/]+$/g) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
      $(this).addClass("borde_rojo");
    } else {
        $(this).removeClass("borde_rojo");
    };
  });

  $("#mantenimiento").on('input', function(){
    if ($(this).val().match(/^[\w\d\s+\-., \']+$/) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
      $(this).addClass("borde_rojo");
    } else {
        $(this).removeClass("borde_rojo");
    };
  });
  
  $("#superficie_inmueble").on('input', function(){
    if ($(this).val().match(/^[0-9.,\/]+$/g) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
      $(this).addClass("borde_rojo");
    } else {
        $(this).removeClass("borde_rojo");
    };
  });
  
  $("#precio_inmueble").on('input', function(){
    if ($(this).val().match(/^[0-9.,\/]+$/g) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
      $(this).addClass("borde_rojo");
    } else {
        $(this).removeClass("borde_rojo");
    };
  });
  
  $("#contrato_especial_comentario").on('input', function(){
    if ($(this).val().match(/^[\w\d\s+\-&.,#:\/áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
      $(this).addClass("borde_rojo");
    } else {
        $(this).removeClass("borde_rojo");
    };
  });

// #### Funcionamiento de los botones checkbox

$(".form_btn").on("click", function(){
    $(this).toggleClass('active');
    if ($(this).hasClass('active')) {
        $(this).parent().find('input').val('1');
    } else {
        $(this).parent().find('input').val('0');
    };
});

$("#contrato_especial_btn").on("click", function(){
    if ($(this).hasClass('active')) {
        $("#contrato_especial_comentario").prop('disabled', false);
    }else{
        $("#contrato_especial_comentario").prop('disabled', true); 
    };
});

$("#pre_venta_btn").on("click", function(){
    if ($(this).hasClass('active')) {
        $("#pagos_impuestos").prop('disabled', true).parent().parent().css('display', 'none');
        $("#aprobacion_planos").prop('disabled', false).parent().parent().css('display', 'flex'); 
    }else{
        $("#pagos_impuestos").prop('disabled', false).parent().parent().css('display', 'flex');
        $("#aprobacion_planos").prop('disabled', true).parent().parent().css('display', 'none');
    };
});

$("#anticretico_btn").on("click", function(){
    if ($(this).hasClass('active')) {
        $(".precio_alquiler_label").html("Precio Anticretico");
        $("#precio_inmueble").val(""); 
    }else{
        $(".precio_alquiler_label").html("Alquiler mensual");
        $("#precio_inmueble").val("");
    };
});


// #### Para el label de departamento

    $(".departamento_label").html(`${datos_pais['org_territorial']} :`);


// ### Poblar el select de Agencias FIRST CHARGE

    $.ajax({
        type: "POST",
        url: "process-request-agencias-pais.php",
        data: { paisChoice : pais_selected }
    }).done(function(data){
        $("#agencia_id").html(data);
    });
    
// ### Poblar el select de Departamentos FIRST CHARGE

    $.ajax({
        type: "POST",
        url: "process-request-departamentos.php",
        data: { paisChoice : pais_selected }
    }).done(function(data){
        $("#departamento").html(data);
    });

// ### Poblar el select de Ciudades de acuerdo al Departamento seleccionado

    $("#departamento").change(function(){
        var departamentoSelected = $("#departamento option:selected").val();
        if (departamentoSelected !== '') { //si hubo una seleccion se cargan las ciudades de la db

            $.ajax({
                type: "POST",
                url: "process-request-ciudades.php",
                data: { departamentoChoice : departamentoSelected }
            }).done(function(data){
                $("#ciudad").prop('disabled', false).html(data);// se activa el select ciudades y pobla
                $("#barrio").empty().prop('disabled', true).val('');
            });

        }else { // si se seleciono vacio, entonces se vacian y bloquean los select ciudad y barrio
        $("#ciudad").empty().prop('disabled', true).val('');
        $("#barrio").empty().prop('disabled', true).val('');
        };
    });

// ### Poblar el select de Varrios de acuerdo a la Ciudad seleccionada

    $("#ciudad").change(function(){
        var ciudadSelected = $("#ciudad option:selected").val();

        if (ciudadSelected !== '') { // si hubo seleccion se cargan los barrios de la db
        $.ajax({
            type: "POST",
            url: "process-request-barrios.php",
            data: { ciudadesChoice : ciudadSelected }
        }).done(function(data){
            if (data !== '<option></option>') {//si hubo resultados entonces se pobla y activa  el select barrios
                $("#barrio").prop('disabled', false).html(data);
            }else { // si no hubo resultados se desactiva y vacia el select barrios
                $("#barrio").empty().prop('disabled', true);
            };
        });

        }else {// si se selecciono vacio, entonces se desactiva y vacia el select barrios y se vacian los resultados sponsor
        $("#barrio").empty().prop('disabled', true).val('');
        };
    });




        
    });
});