$(document).ready(function(){
    jQuery(function($){

    let datos_file;

    $.ajax({
        type: "POST",
        url: "process-request-datos-file-inmueble-cierre.php",
        data: { referencia_sent : id_file, pais_sent : pais_selected, tabla_sent : tabla },
        dataType: 'json',
        async: false,
    }).done(function(data){
        datos_file = data;
    });

    
    
    function get_contactos() {
        let contactos;
    
        $.ajax({
            type: "POST",
            url: "process-request-agentes.php",
            async: false,
        }).done(function(data){
            contactos = data;
        });
        return contactos;
    };
    
    const { propietario_nombre, propietario_apellido, propietario_carnet, propietario_tipo_doc, location_tag, estado, anticretico, pre_venta, agencia_registro_id, direccion, fecha_actual, reservado, agente_cierre } = datos_file;

    function estado_inmueble(estado, anticretico, pre_venta){
        if (estado == 'En Venta') {
            if (pre_venta == 1) {
                return 'Pre-Venta';
            }else{
                return 'Venta';
            };
        }else if(estado == 'En Alquiler'){
            if (anticretico == 1) {
                return 'Alquiler';
            }else{
                return 'Anticretico';
            };
        }
    };

// ########## POBLADO DEL INPUTS CONTENDOR

    $(".inputs_contenedor").html(`

        <span class="input_wrap">
            <label for="fecha_cierre">Fecha de Cierre: </label>
            <input id="fecha_cierre" type="text" name="fecha_cierre" value="${fecha_actual}" readonly>
        </span>

        <span class="input_wrap">
            <label for="nombre_cliente">Nombre del Cliente: </label>
            <input id="nombre_cliente" type="text" name="nombre_cliente" value="${propietario_nombre} ${propietario_apellido}" readonly>
        </span>

        <div class="input_wrap">
            <label for="tipo_doc_identidad">Tipo de documento de identidad: </label>
            <input id="tipo_doc_identidad" type="text" name="tipo_doc_identidad" value="${propietario_tipo_doc}" readonly>
        </div>

        <span class="input_wrap">
            <label for="numero_doc_identidad">N° del documento de identidad: </label>
            <input id="numero_doc_identidad" type="text" name="numero_doc_identidad" value="${propietario_carnet}" readonly>
        </span>

        <div class="input_wrap">
            <label for="agencia_id">Agencia: </label>
            <input id="agencia_id" type="text" name="agencia_id" value="${agencia_registro_id}" readonly>
        </div>

        <div class="input_wrap">
            <label for="location_tag">Ubicación: </label>
            <input id="location_tag" type="text" name="location_tag" value="${location_tag}" readonly>
        </div>

        <span class="input_wrap">
            <label for="direccion_inmueble">Dirección del Inmueble: </label>
            <input id="direccion_inmueble" type="text" name="direccion_inmueble" value="${direccion}" readonly>
        </span>

        <span class="input_wrap">
            <label for="precio_inmueble">Precio Final de ${estado_inmueble(estado, anticretico, pre_venta)} (${moneda}): </label>
            <input id="precio_inmueble" type="text" name="precio_inmueble" value="">
        </span>
        
    `);


    if (agencia_express == 1 || reservado == 1) {
        $(".inputs_contenedor").append(`
        <span class="input_wrap">
            <label for="agente_cierre">Agente de Cierre: </label>
            <select id="agente_cierre" type="text" name="agente_cierre" value="" readonly>
                <option value="${agente_cierre['id']}">${agente_cierre['nombre']} ${agente_cierre['apellido']}</option>
            </select>
        </span>
        `);
        
    } else {
        $(".inputs_contenedor").append(`
            <span class="input_wrap">
                <label for="agente_cierre">Agente de Cierre: </label>
                <select id="agente_cierre" type="text" name="agente_cierre" value="" readonly>
                    ${get_contactos()};
                </select>
            </span>
        `);

        $("#agente_cierre").select2();
        
    };

    
    // ########## POBLADO DEL DRAGS CONTENDOR



    $(".drags_contenedor").html(`
        <div class="contenedor_foto">
            <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Contrato Privado Firmado (PDF) </p>
            <div id="campo_contrato" class="campo_foto">
                <label for="contrato_privado" id="contrato_privado_label"><p>Sube el PDF</br><span>Click or Drop</span></p><i class="far fa-check-circle"></i></label>
                <input type="file" id="contrato_privado" name="contrato_privado" data-id="contrato_privado" onchange="check(this)" accept="application/pdf">
            </div>
        </div>

        <div class="contenedor_foto">
            <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Cierre Intermediación (PDF) </p>
            <div id="campo_contrato" class="campo_foto">
                <label for="cierre_intermediacion" id="cierre_intermediacion_label"><p>Sube el PDF</br><span>Click or Drop</span></p><i class="far fa-check-circle"></i></label>
                <input type="file" id="cierre_intermediacion" name="cierre_intermediacion" data-id="cierre_intermediacion" onchange="check(this)" accept="application/pdf">
            </div>
        </div>
        
        <div class="contenedor_foto">
            <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Carnet Cliente (PDF) </p>
            <div id="campo_contrato" class="campo_foto">
                <label for="carnet_cliente_cierre" id="carnet_cliente_cierre_label"><p>Sube el PDF</br><span>Click or Drop</span></p><i class="far fa-check-circle"></i></label>
                <input type="file" id="carnet_cliente_cierre" name="carnet_cliente_cierre" data-id="carnet_cliente_cierre" onchange="check(this)" accept="application/pdf">
            </div>
        </div>

        <div class="contenedor_foto">
            <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default">  Respaldos (PDF) </p>
            <div id="campo_contrato" class="campo_foto">
                <label for="respaldos_cierre" id="respaldos_cierre_label"><p>Sube el PDF</br><span>Click or Drop</span></p><i class="far fa-check-circle"></i></label>
                <input type="file" id="respaldos_cierre" name="respaldos_cierre" data-id="respaldos_cierre" onchange="check(this)" accept="application/pdf">
            </div>
        </div>

    `);

// ####### VERIFICACION JS DE LLENADO DE DATOS DE LOS INPUTS
  
  $("#precio_inmueble").on('input', function(){
    if ($(this).val().match(/^[0-9.,\/]+$/g) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
      $(this).addClass("borde_rojo");
    } else {
        $(this).removeClass("borde_rojo");
    };
  });


        
});
});