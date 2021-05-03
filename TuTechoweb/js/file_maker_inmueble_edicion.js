$(document).ready(function(){
    jQuery(function($){

    let datos_file;
    let tipo_bien;

    if (id_file.includes("C")) {  tipo_bien = "casa";
        } else { if (id_file.includes("D")) {  tipo_bien = "departamento";
            } else { if (id_file.includes("L")) {  tipo_bien = "local";
                } else { if (id_file.includes("T")) {  tipo_bien = "terreno";
                 };
            };
        };
    };

    $("#tipo_inmueble").val(tipo_bien);

    let tabla_name = "borradores_" + tipo_bien;

    $.ajax({
        type: "POST",
        url: "process-request-datos-file-inmueble.php",
        data: { referencia_sent : id_file, pais_sent : pais_selected, tabla_sent : tabla_name },
        dataType: 'json',
        async: false,
    }).done(function(data){
        datos_file = data;
        console.log(datos_file);
    });

    function tipo_doc(dato){
        return (dato == datos_file['propietario_tipo_doc'] ? 'selected' : '');
    };
    function button_state(dato){
        return (datos_file[dato] == 1 ? 'active' : '');
    };
    function contrato_especial_comentario(){
        return (datos_file['contrato_especial'] == 0 ? 'disabled' : '');
    };
    function conciliacion_tipo(dato){
        if (dato == '1 Mes') {
            $(".opcion_mes").addClass('activo');
        }else if(dato == '10%'){
            $(".opcion_porcentage").addClass('activo');
        };
        
        $("#opcion_conciliacion").val(dato);
    };

    function get_contactos() {
        let contactos;
    
        $.ajax({
            type: "POST",
            url: "process-request-agentes.php",
            data: { conciliador_sent :  datos_file['conciliador'] },
            async: false,
        }).done(function(data){
            contactos = data;
            console.log(data);
            console.log(datos_file['conciliador']);
        });
        return contactos;
    };

// ########## POBLADO DEL INPUTS CONTENDOR

    $(".inputs_contenedor").html(`

        <span class="input_wrap">
        <label for="nombre_cliente">Nombre del Cliente: </label>
        <input id="nombre_cliente" type="text" name="nombre_cliente" value="${datos_file['propietario_nombre']}">
        </span>
        
        <span class="input_wrap">
            <label for="apellido_cliente">Apellido(s) del Cliente: </label>
            <input id="apellido_cliente" type="text" name="apellido_cliente" value="${datos_file['propietario_apellido']}">
        </span>

        <div class="input_wrap">
            <label for="tipo_doc_identidad">Tipo de documento de identidad: </label>
            <select name="tipo_doc_identidad" id="tipo_doc_identidad">
            <option value=""></option>
            <option value="carnet de identidad" ${tipo_doc('carnet de identidad')}>Carnet de Identidad</option>
            <option value="pasaporte" ${tipo_doc('pasaporte')}>Pasaporte</option>
            </select>
        </div>

        <span class="input_wrap">
            <label for="numero_doc_identidad">N° del documento de identidad: </label>
            <input id="numero_doc_identidad" type="text" name="numero_doc_identidad" value="${datos_file['propietario_carnet']}" placeholder="XXXXXXX LP">
        </span>

        <span class="input_wrap">
            <label for="email_cliente">Email del Cliente: </label>
            <input id="email_cliente" type="text" name="email_cliente" value="${datos_file['propietario_email']}">
        </span>

        <span class="input_wrap">
            <label for="telefono_cliente">Telefono del Cliente: </label>
            <input id="telefono_cliente" type="text" name="telefono_cliente" value="${datos_file['propietario_telefono']}" placeholder="(+591) XXXXXXXX">
        </span>

        <div class="input_wrap">
            <label for="agencia_id">Agencia: </label>
            <select name="agencia_id" id="agencia_id">
            </select>
        </div>

        <span class="input_wrap">
            <label for="pais">Pais: </label>
            <input id="pais" type="text" name="pais" value="${datos_file['pais']}" readonly>
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
            <input id="direccion_cliente" type="text" name="direccion_cliente" value="${datos_file['propietario_direccion']}" placeholder="#Vivienda Av/Calle, Barrio">
        </span>

        <span class="input_wrap">
            <label for="direccion_inmueble">Dirección del Inmueble: </label>
            <input id="direccion_inmueble" type="text" name="direccion_inmueble" value="${datos_file['direccion']}" placeholder="#Vivienda Av/Calle, Barrio">
        </span>

        <span class="input_wrap opcional">
            <label for="direccion_inmueble_complemento">Complemento dirección Inmueble: </label>
            <input id="direccion_inmueble_complemento" type="text" name="direccion_inmueble_complemento" value="${datos_file['direccion_complemento']}" placeholder="Edf XXXX, Dept. XXXX">
        </span>

        <span class="input_wrap">
            <label for="base_imponible">Base Imponible ${moneda}: </label>
            <input id="base_imponible" type="text" name="base_imponible" value="${datos_file['base_imponible']}">
        </span>

        <span class="input_wrap">
            <label for="avaluo">Avaluo ${moneda}: </label>
            <input id="avaluo" type="text" name="avaluo" value="${datos_file['avaluo']}">
        </span>

        <span class="input_wrap">
            <label for="impuestos">Impuestos Anuales ${moneda}: </label>
            <input id="impuestos" type="text" name="impuestos" value="${datos_file['impuestos']}">
        </span>
        
        <span class="input_wrap opcional">
            <label for="mantenimiento">Mantenimiento mensual ${moneda} - OPCIONAL: </label>
            <input id="mantenimiento" type="text" name="mantenimiento" value="${datos_file['mantenimiento']}">
        </span>

    `);

    if (tipo_bien == "terreno") {
        $(".inputs_contenedor").append(`
        
        <span class="input_wrap">
            <label for="superficie_terreno">Superficie del Terreno (m<sup>2</sup>): </label>
            <input id="superficie_terreno" type="text" name="superficie_terreno" value="${datos_file['superficie_terreno']}">
        </span>

        `);
    }else if(tipo_bien == "casa"){
        $(".inputs_contenedor").append(`
        
        <span class="input_wrap">
            <label for="superficie_inmueble">Superficie del Inmueble (m<sup>2</sup>): </label>
            <input id="superficie_inmueble" type="text" name="superficie_inmueble" value="${datos_file['superficie_inmueble']}">
        </span>

        <span class="input_wrap">
            <label for="superficie_terreno">Superficie del Terreno (m<sup>2</sup>): </label>
            <input id="superficie_terreno" type="text" name="superficie_terreno" value="${datos_file['superficie_terreno']}">
        </span>
        `);
    }else if(tipo_bien == "departamento" || tipo_bien == "local"){
        $(".inputs_contenedor").append(`
        
        <span class="input_wrap">
            <label for="superficie_inmueble">Superficie del Inmueble (m<sup>2</sup>): </label>
            <input id="superficie_inmueble" type="text" name="superficie_inmueble" value="${datos_file['superficie_inmueble']}">
        </span>
        `);
    };

    if (tipo_file_recieved == 'venta') {
        $(".inputs_contenedor").append(`

        <span class="input_wrap">
            <label for="precio_inmueble">Precio del Inmueble ${moneda}: </label>
            <input id="precio_inmueble" type="text" name="precio_inmueble" value="${datos_file['precio']}">
        </span>

        <div class="input_wrap">
            <span class="form_btn ${button_state('pre_venta')}" id="pre_venta_btn">Pre-Venta</span>
            <input id="pre_venta" type="hidden" name="pre_venta" value="${datos_file['pre_venta']}">
        </div>
        
        `);
        
    }else if(tipo_file_recieved == 'alquiler'){

        $(".inputs_contenedor").append(`

        <span class="input_wrap">
            <label for="precio_inmueble"><p class="precio_alquiler_label">Alquiler mensual</p> &nbsp${moneda}: </label>
            <input id="precio_inmueble" type="text" name="precio_inmueble" value="${datos_file['precio']}">
        </span>

        <div class="input_wrap">
            <span class="form_btn ${button_state('anticretico')}" id="anticretico_btn">Anticretico</span>
            <input id="anticretico" type="hidden" name="anticretico" value="${datos_file['anticretico']}">
        </div>

        <div class="input_wrap">
            <span class="form_btn ${button_state('gestion_acordada')}">Gestion Acordada</span>
            <input id="gestion" type="hidden" name="gestion" value="${datos_file['gestion_acordada']}">
        </div>
        
        
        `);

    };

    $(".inputs_contenedor").append(`
    
        <div class="input_wrap">
            <span class="form_btn ${button_state('exclusivo')}">Exclusivo</span>
            <input id="exclusivo" type="hidden" name="exclusivo" value="${datos_file['exclusivo']}">
        </div>

        <div class="input_wrap">
            <span class="form_btn ${button_state('contrato_especial')}" id="contrato_especial_btn">Contrato Especial</span>
            <input id="contrato_especial" type="hidden" name="contrato_especial" value="${datos_file['contrato_especial']}">
        </div>
        
        <span class="input_wrap">
            <label for="contrato_especial_comentario">Explique el motivo: </label>
            <textarea name="contrato_especial_comentario" id="contrato_especial_comentario" rows="1" class="pregunta_textarea" oninput="auto_grow(this)" ${contrato_especial_comentario()}>${datos_file['contrato_especial_comentario']}</textarea>
        </span>
    `);

    if (agencia_express == 0) {
        $(".inputs_contenedor").append(`
            <span class="input_wrap">
                <label for="conciliador">Conciliador - OPCIONAL: </label>
                <select id='conciliador' name='conciliador' style='width: 400px;'>
                <option value=''>Sin Conciliador</option>
                ${get_contactos()} 
                </select>
            </span>

            <span class="input_wrap opcion_conciliador_wrap_input">
                <label for="opcion_conciliador_wrap">Opcion Conciliador: </label>
                <div class="opcion_conciliador_wrap">
                    <span class="opcion_mes opcion_conciliador" value="1 Mes">1 Mes</span>
                    <span class="opcion_porcentage opcion_conciliador" value="10%">10%</span>
                </div>
                <input type="hidden" name="opcion_conciliacion" class="opcional" id="opcion_conciliacion" value="">
            </span>
        `);

        $("#conciliador").select2();
        if (datos_file['conciliador'] !== '') {
            $(".opcion_conciliador_wrap_input").css("visibility", "unset");  
        };

        $("#conciliador").on("change", function(){
            const conciliador = $("#conciliador option:selected").attr("value");
            
            if (conciliador == '') {
                $(".opcion_conciliador_wrap_input").css("visibility", "hidden");
                $(".opcion_conciliador").removeClass("activo");
                $("#opcion_conciliacion").val("").addClass("opcional");
            }else{
                $(".opcion_conciliador_wrap_input").css("visibility", "unset");
                $("#opcion_conciliacion").removeClass("opcional");
            };
        });

        $(".opcion_conciliador").on("click", function(){
            $(".opcion_conciliador").removeClass("activo");
            $(this).addClass("activo");
            const opcion_selected = $(this).attr('value');
            $("#opcion_conciliacion").val(opcion_selected);
        });

        conciliacion_tipo(datos_file['conciliacion_tipo']);
    };

// ########## POBLADO DEL DRAGS CONTENDOR

const carpeta_destino = '../../bienes_inmuebles_files/' + pais_selected + '/' + id_file;



$(".drags_contenedor").html(`

    <div class="contenedor_foto">
        <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Contrato Firmado (PDF) </p>
        <div id="campo_contrato" class="campo_foto">
            <span class="thumb_pdf_container"><i class="fas fa-file-pdf"></i><p>ORIGINAL</p></span>
            <div class="thumb_foto_normal_p_container" onclick="thumb_click_operator(this, 'pdf')">
                <p class="thumb_foto_normal_p">Cambiar PDF</p>
            </div>
            <i class="fa fa-undo-alt return_change_foto return_foto" onclick="return_foto_click_operator(this, 'pdf')"></i>
            <label for="contrato" id="contrato_label"><p>Sube el PDF</br><span>Click or Drop</span></p><i class="far fa-check-circle"></i></label>
            <input type="file" id="contrato" name="contrato" data-id="contrato" onchange="check(this)" accept="application/pdf" disabled>
        </div>
    </div>

    <div class="contenedor_foto">
        <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Información de Derecho Propietario (PDF) </p>
        <div id="campo_info_derecho_propietario" class="campo_foto">
            <span class="thumb_pdf_container"><i class="fas fa-file-pdf"></i><p>ORIGINAL</p></span>
            <div class="thumb_foto_normal_p_container" onclick="thumb_click_operator(this, 'pdf')">
                <p class="thumb_foto_normal_p">Cambiar PDF</p>
            </div>
            <i class="fa fa-undo-alt return_change_foto return_foto" onclick="return_foto_click_operator(this, 'pdf')"></i>
            <label for="info_derecho_propietario" id="info_derecho_propietario_label"><p>Sube el PDF</br><span>Click or Drop</span></p><i class="far fa-check-circle"></i></label>
            <input type="file" id="info_derecho_propietario" name="info_derecho_propietario" data-id="info_derecho_propietario" onchange="check(this)" accept="application/pdf" disabled>
        </div>
    </div>

    <div class="contenedor_foto">
        <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Documento(s) de identidad: Propietario(s) (PDF) </p>
        <div id="campo_doc_identidad_propietario" class="campo_foto">
            <span class="thumb_pdf_container"><i class="fas fa-file-pdf"></i><p>ORIGINAL</p></span>
            <div class="thumb_foto_normal_p_container" onclick="thumb_click_operator(this, 'pdf')">
                <p class="thumb_foto_normal_p">Cambiar PDF</p>
            </div>
            <i class="fa fa-undo-alt return_change_foto return_foto" onclick="return_foto_click_operator(this, 'pdf')"></i>
            <label for="doc_identidad_propietario" id="doc_identidad_propietario_label"><p>Sube el PDF</br><span>Click or Drop</span></p><i class="far fa-check-circle"></i></label>
            <input type="file" id="doc_identidad_propietario" name="doc_identidad_propietario" data-id="doc_identidad_propietario" onchange="check(this)" accept="application/pdf" disabled>
        </div>
    </div>
    `);

    if (datos_file['poder_notariado'] == 1) {
        $(".drags_contenedor").append(` 
        
        <div class="contenedor_foto opcional">
            <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Poder Notariado (PDF - OPCIONAL) </p>
            <div id="campo_poder_notariado" class="campo_foto">
                <span class="thumb_pdf_container"><i class="fas fa-file-pdf"></i><p>ORIGINAL</p></span>
                <div class="thumb_foto_normal_p_container" onclick="thumb_click_operator(this, 'pdf')">
                    <p class="thumb_foto_normal_p">Cambiar PDF</p>
                </div>
                <i class="fa fa-undo-alt return_change_foto return_foto" onclick="return_foto_click_operator(this, 'pdf')"></i>
                <label for="poder_notariado" id="poder_notariadio_label"><p>Sube el PDF</br><span>Click or Drop</span></p><i class="far fa-check-circle"></i></label>
                <input type="file" id="poder_notariado" name="poder_notariado" data-id="poder_notariado" onchange="check(this)" accept="application/pdf" disabled>
            </div>
        </div>
        
        `);
    }else{
        $(".drags_contenedor").append(` 
        
        <div class="contenedor_foto opcional">
            <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Poder Notariado (PDF - OPCIONAL) </p>
            <div id="campo_poder_notariado" class="campo_foto">
                <label for="poder_notariado" id="poder_notariadio_label"><p>Sube el PDF</br><span>Click or Drop</span></p><i class="far fa-check-circle"></i></label>
                <input type="file" id="poder_notariado" name="poder_notariado" data-id="poder_notariado" onchange="check(this)" accept="application/pdf">
            </div>
        </div>
        
        `);
    };

    if (datos_file['doc_identidad_apoderado'] == 1) {
        $(".drags_contenedor").append(` 
        
        <div class="contenedor_foto opcional">
            <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Documento de Identidad: Apoderado (PDF - OPCIONAL) </p>
            <div id="campo_doc_identidad_apoderado" class="campo_foto">
                <span class="thumb_pdf_container"><i class="fas fa-file-pdf"></i><p>ORIGINAL</p></span>
                <div class="thumb_foto_normal_p_container" onclick="thumb_click_operator(this, 'pdf')">
                    <p class="thumb_foto_normal_p">Cambiar PDF</p>
                </div>
                <i class="fa fa-undo-alt return_change_foto return_foto" onclick="return_foto_click_operator(this, 'pdf')"></i>
                <label for="doc_identidad_apoderado" id="doc_identidad_apoderado_label"><p>Sube el PDF</br><span>Click or Drop</span></p><i class="far fa-check-circle"></i></label>
                <input type="file" id="doc_identidad_apoderado" name="doc_identidad_apoderado" data-id="doc_identidad_apoderado" onchange="check(this)" accept="application/pdf" disabled>
            </div>
        </div>
        
        `);
    }else{
        $(".drags_contenedor").append(` 
        
        <div class="contenedor_foto opcional">
            <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Documento de Identidad: Apoderado (PDF - OPCIONAL) </p>
            <div id="campo_doc_identidad_apoderado" class="campo_foto">
                <label for="doc_identidad_apoderado" id="doc_identidad_apoderado_label"><p>Sube el PDF</br><span>Click or Drop</span></p><i class="far fa-check-circle"></i></label>
                <input type="file" id="doc_identidad_apoderado" name="doc_identidad_apoderado" data-id="doc_identidad_apoderado" onchange="check(this)" accept="application/pdf">
            </div>
        </div>
        
        `);
    };

    

    if (datos_file['pre_venta'] == 1) {

        if (datos_file['aprobacion_planos'] == 1) {
            $(".drags_contenedor").append(`

                <div class="contenedor_foto">
                    <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Aprobación Planos (PDF) </p>
                    <div id="campo_aprobacion_planos" class="campo_foto">
                        <span class="thumb_pdf_container"><i class="fas fa-file-pdf"></i><p>ORIGINAL</p></span>
                        <div class="thumb_foto_normal_p_container" onclick="thumb_click_operator(this, 'pdf')">
                            <p class="thumb_foto_normal_p">Cambiar PDF</p>
                        </div>
                        <i class="fa fa-undo-alt return_change_foto return_foto" onclick="return_foto_click_operator(this, 'pdf')"></i>
                        <label for="aprobacion_planos" id="aprobacion_planos_label"><p>Sube el PDF</br><span>Click or Drop</span></p><i class="far fa-check-circle"></i></label>
                        <input type="file" id="aprobacion_planos" name="aprobacion_planos" data-id="aprobacion_planos" onchange="check(this)" accept="application/pdf" disabled>
                    </div>
                </div>

            `);
        }else{
            $(".drags_contenedor").append(`

                <div class="contenedor_foto">
                    <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Aprobación Planos (PDF) </p>
                    <div id="campo_aprobacion_planos" class="campo_foto">
                        <label for="aprobacion_planos" id="aprobacion_planos_label"><p>Sube el PDF</br><span>Click or Drop</span></p><i class="far fa-check-circle"></i></label>
                        <input type="file" id="aprobacion_planos" name="aprobacion_planos" data-id="aprobacion_planos" onchange="check(this)" accept="application/pdf">
                    </div>
                </div>

            `);
        };

        

    }else if (datos_file['pre_venta'] == 0){
        $(".drags_contenedor").append(`

            <div class="contenedor_foto" style="display:none">
                <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Aprobación Planos (PDF) </p>
                <div id="campo_aprobacion_planos" class="campo_foto">
                    <label for="aprobacion_planos" id="aprobacion_planos_label"><p>Sube el PDF</br><span>Click or Drop</span></p><i class="far fa-check-circle"></i></label>
                    <input type="file" id="aprobacion_planos" name="aprobacion_planos" data-id="aprobacion_planos" onchange="check(this)" accept="application/pdf" disabled>
                </div>
            </div>

        `);
    };


if (tipo_file_recieved == 'venta') {

    if (datos_file['pre_venta'] == 0) {

        if (datos_file['pagos_impuestos'] == 1) {
            $(".drags_contenedor").append(`
                <div class="contenedor_foto">
                    <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Últimos 3 pagos de impuestos (PDF) </p>
                    <div id="campo_pagos_impuestos" class="campo_foto">
                    <span class="thumb_pdf_container"><i class="fas fa-file-pdf"></i><p>ORIGINAL</p></span>
                    <div class="thumb_foto_normal_p_container" onclick="thumb_click_operator(this, 'pdf')">
                        <p class="thumb_foto_normal_p">Cambiar PDF</p>
                    </div>
                    <i class="fa fa-undo-alt return_change_foto return_foto" onclick="return_foto_click_operator(this, 'pdf')"></i>
                    <label for="pagos_impuestos" id="pagos_impuestos_label"><p>Sube el PDF</br><span>Click or Drop</span></p><i class="far fa-check-circle"></i></label>
                    <input type="file" id="pagos_impuestos" name="pagos_impuestos" data-id="pagos_impuestos" onchange="check(this)" accept="application/pdf" disabled>
                    </div>
                </div>
            `); 
        }else{
            $(".drags_contenedor").append(`
                <div class="contenedor_foto">
                    <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Últimos 3 pagos de impuestos (PDF) </p>
                    <div id="campo_pagos_impuestos" class="campo_foto">
                    <label for="pagos_impuestos" id="pagos_impuestos_label"><p>Sube el PDF</br><span>Click or Drop</span></p><i class="far fa-check-circle"></i></label>
                    <input type="file" id="pagos_impuestos" name="pagos_impuestos" data-id="pagos_impuestos" onchange="check(this)" accept="application/pdf">
                    </div>
                </div>
            `); 
        };
        
    }else{
        $(".drags_contenedor").append(`
            <div class="contenedor_foto" style="display:none">
                <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Últimos 3 pagos de impuestos (PDF) </p>
                <div id="campo_pagos_impuestos" class="campo_foto">
                <label for="pagos_impuestos" id="pagos_impuestos_label"><p>Sube el PDF</br><span>Click or Drop</span></p><i class="far fa-check-circle"></i></label>
                <input type="file" id="pagos_impuestos" name="pagos_impuestos" data-id="pagos_impuestos" onchange="check(this)" accept="application/pdf" disabled>
                </div>
            </div>
        `); 
    };
    
};

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

$("#pre_venta_btn").on("click", function(){ // El estado "active" del boton cambia antes de la lectura de este eventlistener (ver mas arriba)

    if ($(this).hasClass('active')) {// se volvio activo
        
        $("#pagos_impuestos").prop('disabled', true).parent().parent().css('display', 'none');

        if (datos_file['pre_venta'] == 1 ) { 
                $("#aprobacion_planos").prop('disabled', true).parent().parent().css('display', 'flex');
        }else{
                $("#aprobacion_planos").prop('disabled', false).parent().parent().css('display', 'flex');
        };
       
    }else{// se volvio inactivo

        $("#aprobacion_planos").prop('disabled', true).parent().parent().css('display', 'none');

        if (datos_file['pre_venta'] == 0) {      
            $("#pagos_impuestos").prop('disabled', true).parent().parent().css('display', 'flex');
        }else{
            $("#pagos_impuestos").prop('disabled', false).parent().parent().css('display', 'flex');
        };
        
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
        data: { paisChoice : pais_selected, agencia_selected : datos_file['agencia_registro_id'] }
    }).done(function(data){
        $("#agencia_id").html(data);
    });
    
// ### Poblar el select de Departamentos FIRST CHARGE

    $.ajax({
        type: "POST",
        url: "process-request-departamentos.php",
        data: { paisChoice : pais_selected, departamento_selected : datos_file['departamento'] },
        async: false
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
                data: { departamentoChoice : departamentoSelected, ciudad_selected : datos_file['ciudad'] },
                async: false
            }).done(function(data){
                $("#ciudad").prop('disabled', false).html(data);// se activa el select ciudades y pobla
                $("#barrio").empty().prop('disabled', true).val('');
            });

        }else { // si se seleciono vacio, entonces se vacian y bloquean los select ciudad y barrio
        $("#ciudad").empty().prop('disabled', true).val('');
        $("#barrio").empty().prop('disabled', true).val('');
        };
    });

    $("#departamento").trigger('change');

// ### Poblar el select de Varrios de acuerdo a la Ciudad seleccionada

    $("#ciudad").change(function(){
        var ciudadSelected = $("#ciudad option:selected").val();

        if (ciudadSelected !== '') { // si hubo seleccion se cargan los barrios de la db
        $.ajax({
            type: "POST",
            url: "process-request-barrios.php",
            data: { ciudadesChoice : ciudadSelected, barrio_selected : location_tag_selected }
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

    if ($("#ciudad option:selected").val() !== datos_file['location_tag']) {
        var ciudadSelected = $("#ciudad option:selected").val();
        var location_tag_selected = datos_file['location_tag'];
        $.ajax({
            type: "POST",
            url: "process-request-barrios.php",
            data: { ciudadesChoice : ciudadSelected, barrio_selected : location_tag_selected}
        }).done(function(data){
            $("#barrio").prop('disabled', false).html(data);
        });
    };




        
    });
});