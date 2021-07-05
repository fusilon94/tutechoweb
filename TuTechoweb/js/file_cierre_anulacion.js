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
    
    const { propietario_nombre, propietario_apellido, propietario_carnet, propietario_tipo_doc, location_tag, agencia_registro_id, direccion, fecha_actual } = datos_file;


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

        
    `);


    
    // ########## POBLADO DEL DRAGS CONTENDOR

    $(".drags_contenedor").html(`
        <div class="contenedor_foto">
            <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Motivo + Respaldo (PDF) </p>
            <div id="campo_contrato" class="campo_foto">
                <label for="motivo" id="motivo_anulacion_label"><p>Sube el PDF</br><span>Click or Drop</span></p><i class="far fa-check-circle"></i></label>
                <input type="file" id="motivo_anulacion" name="motivo_anulacion" data-id="motivo" onchange="check(this)" accept="application/pdf">
            </div>
        </div>

    `);


});
});