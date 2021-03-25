$(document).ready(function(){
    jQuery(function($){

    let datos_file;

    $.ajax({
        type: "POST",
        url: "process-request-datos-file-agente.php",
        data: { agente_id_sent : id_file, pais_sent : pais_selected },
        dataType: 'json',
        async: false,
    }).done(function(data){
        datos_file = data;
        // console.log(datos_file);
    });

    function tipo_doc(dato){if(dato == datos_file['doc_tipo']){return 'selected';}else{return '';};};
    function genero(dato){if(dato == datos_file['genero']){return 'selected';}else{return '';};};

// ########## POBLADO DEL INPUTS CONTENDOR

    $(".inputs_contenedor").html(`

        <span class="input_wrap">
        <label for="nombre_agente">Nombre del Agente: </label>
        <input id="nombre_agente" type="text" name="nombre_agente" value="${datos_file['nombre']}">
        </span>
        
        <span class="input_wrap">
            <label for="apellido_agente">Apellido(s) del Agente: </label>
            <input id="apellido_agente" type="text" name="apellido_agente" value="${datos_file['apellido']}">
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
            <input id="numero_doc_identidad" type="text" name="numero_doc_identidad" value="${datos_file['doc_identidad']}" placeholder="XXXXXXX LP">
        </span>

        <div class="input_wrap">
            <label for="genero_agente">Genero del Agente:</label>
            <select name="genero_agente" id="genero_agente">
            <option value=""></option>
            <option value="mujer" ${genero('mujer')}>Mujer</option>
            <option value="hombre" ${genero('hombre')}>Hombre</option>
            </select>
        </div>

        <span class="input_wrap">
            <label for="email_agente">Email del Agente: </label>
            <input id="email_agente" type="text" name="email_agente" value="${datos_file['email']}">
        </span>

        <div class="input_wrap">
            <label for="agencia_id">Agencia que contrata al Agente: </label>
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
            <select name="ciudad" id="ciudad">
                <option></option>
            </select>
        </div>

        <span class="input_wrap">
            <label for="direccion_agente">Dirección del agente: </label>
            <input id="direccion_agente" type="text" name="direccion_agente" value="${datos_file['domicilio']}" placeholder="#Vivienda Av/Calle, Barrio">
        </span>

        <span class="input_wrap opcional">
            <label for="direccion_complemento">Complemento dirección: </label>
            <input id="direccion_complemento" type="text" name="direccion_complemento" value="${datos_file['domicilio_complemento']}" placeholder="Edf XXXX, Dept. XXXX">
        </span>
    `);

// ########## POBLADO DEL DRAGS CONTENDOR

 const carpeta_destino = '../../agentes/' + pais_selected + '/' + id_file;
 const current_date_time = new Date();

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
        <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Foto Agente, fondo BLANCO (jpg) </p>
        <div id="campo_foto_blanco" class="campo_foto">
        <img src="${carpeta_destino}/foto_blanco.jpg?=${current_date_time}" alt="Foto Blanco" class="thumb_foto_normal">
        <div class="thumb_foto_normal_p_container" onclick="thumb_click_operator(this, 'image')">
            <p class="thumb_foto_normal_p">Cambiar Fotografía</p>
        </div>
        <i class="fa fa-undo-alt return_change_foto return_foto" onclick="return_foto_click_operator(this, 'image')"></i>
        <label for="foto_blanco" id="foto_blanco_label"><p>Sube la Foto</br><span>Click or Drop</span></p></label>
        <input type="file" id="foto_blanco" name="foto_blanco" onchange="check_jpg(this)" accept="image/jpeg" disabled>
        </div>
    </div>

    <div class="contenedor_foto">
        <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Foto Agente, fondo PLOMO (jpg) </p>
        <div id="campo_foto_plomo" class="campo_foto">
        <img src="${carpeta_destino}/foto_plomo.jpg?=${current_date_time}" alt="Foto Plomo" class="thumb_foto_normal">
        <div class="thumb_foto_normal_p_container" onclick="thumb_click_operator(this, 'image')">
            <p class="thumb_foto_normal_p">Cambiar Fotografía</p>
        </div>
        <i class="fa fa-undo-alt return_change_foto return_foto" onclick="return_foto_click_operator(this, 'image')"></i>
        <label for="foto_plomo" id="foto_plomo_label"><p>Sube la Foto</br><span>Click or Drop</span></p></label>
        <input type="file" id="foto_plomo" name="foto_plomo" onchange="check_jpg(this)" accept="image/jpeg" disabled>
        </div>
    </div>

    <div class="contenedor_foto">
        <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Antecedentes Policiales y Penales (PDF) </p>
        <div id="campo_antecedentes" class="campo_foto">
        <span class="thumb_pdf_container"><i class="fas fa-file-pdf"></i><p>ORIGINAL</p></span>
        <div class="thumb_foto_normal_p_container" onclick="thumb_click_operator(this, 'pdf')">
            <p class="thumb_foto_normal_p">Cambiar PDF</p>
        </div>
        <i class="fa fa-undo-alt return_change_foto return_foto" onclick="return_foto_click_operator(this, 'pdf')"></i>
        <label for="antecedentes" id="antecedentes_label"><p>Sube el PDF</br><span>Click or Drop</span></p><i class="far fa-check-circle"></i></label>
        <input type="file" id="antecedentes" name="antecedentes" data-id="antecedentes" onchange="check(this)" accept="application/pdf" disabled>
        </div>
    </div>

    <div class="contenedor_foto">
        <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Documento de identidad (PDF) </p>
        <div id="campo_doc_identidad" class="campo_foto">
        <span class="thumb_pdf_container"><i class="fas fa-file-pdf"></i><p>ORIGINAL</p></span>
        <div class="thumb_foto_normal_p_container" onclick="thumb_click_operator(this, 'pdf')">
            <p class="thumb_foto_normal_p">Cambiar PDF</p>
        </div>
        <i class="fa fa-undo-alt return_change_foto return_foto" onclick="return_foto_click_operator(this, 'pdf')"></i>
        <label for="doc_identidad" id="doc_identidad_label"><p>Sube el PDF</br><span>Click or Drop</span></p><i class="far fa-check-circle"></i></label>
        <input type="file" id="doc_identidad" name="doc_identidad" data-id="doc_identidad" onchange="check(this)" accept="application/pdf" disabled>
        </div>
    </div>

    <div class="contenedor_foto">
        <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Acreditación Trabajador Independiente (PDF) </p>
        <div id="acreditacion_autonomo" class="campo_foto">
        <span class="thumb_pdf_container"><i class="fas fa-file-pdf"></i><p>ORIGINAL</p></span>
        <div class="thumb_foto_normal_p_container" onclick="thumb_click_operator(this, 'pdf')">
            <p class="thumb_foto_normal_p">Cambiar PDF</p>
        </div>
        <i class="fa fa-undo-alt return_change_foto return_foto" onclick="return_foto_click_operator(this, 'pdf')"></i>
        <label for="acreditacion_autonomo" id="acreditacion_autonomo_label"><p>Sube el PDF</br><span>Click or Drop</span></p><i class="far fa-check-circle"></i></label>
        <input type="file" id="acreditacion_autonomo" name="acreditacion_autonomo" data-id="acreditacion_autonomo" onchange="check(this)" accept="application/pdf" disabled>
        </div>
    </div>

    <div class="contenedor_foto">
        <p style="text-align: center; color: #333333; font-weight: bold; width: 100%; cursor: default"> Curriculum Vitae (PDF) </p>
        <div id="campo_cv" class="campo_foto">
        <span class="thumb_pdf_container"><i class="fas fa-file-pdf"></i><p>ORIGINAL</p></span>
        <div class="thumb_foto_normal_p_container" onclick="thumb_click_operator(this, 'pdf')">
            <p class="thumb_foto_normal_p">Cambiar PDF</p>
        </div>
        <i class="fa fa-undo-alt return_change_foto return_foto" onclick="return_foto_click_operator(this, 'pdf')"></i>
        <label for="cv" id="cv_label"><p>Sube el PDF</br><span>Click or Drop</span></p><i class="far fa-check-circle"></i></label>
        <input type="file" id="cv" name="cv" data-id="cv" onchange="check(this)" accept="application/pdf" disabled>
        </div>
    </div>

`);


// ###### COMPORTAMIENTO DE LOS CAMPOS DRAG&DROP con IMAGEN DE PREVISUALIZACION

    $.uploadPreview({
        input_field: "#foto_blanco",   // Default: .image-upload
        preview_box: "#campo_foto_blanco",  // Default: .image-preview
        label_field: "#foto_blanco_label",    // Default: .image-label
        label_default: "<p>Sube la Foto</br><span>Click or Drop</span></p>",   // Default: Choose File
        label_selected: "Cambia esta imagen",  // Default: Change File
        no_label: false                 // Default: false
    });

    $.uploadPreview({
        input_field: "#foto_plomo",   // Default: .image-upload
        preview_box: "#campo_foto_plomo",  // Default: .image-preview
        label_field: "#foto_plomo_label",    // Default: .image-label
        label_default: "<p>Sube la Foto</br><span>Click or Drop</span></p>",   // Default: Choose File
        label_selected: "Cambia esta imagen",  // Default: Change File
        no_label: false                 // Default: false
    });

    // !! LOS CAMPOS QUE NO REQUIERAN PREVISUALIZACION TENDRAN UNICAMENTE UN SIGNO CHECK VERDE, SEGUN UN ONLINE onchange, DEFINIDO EN EL JS COMUN

// ####### VERIFICACION JS DE LLENADO DE DATOS DE LOS INPUTS

  $("#nombre_agente").on('input', function(){
    if ($(this).val().match(/^[\w\d\s áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
    } else {
        $(this).removeClass("borde_rojo");
    };
  });

  $("#apellido_agente").on('input', function(){
    if ($(this).val().match(/^[\w\d\s áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
    } else {
        $(this).removeClass("borde_rojo");
    };
  });

  $("#numero_doc_identidad").on('input', function(){
    if ($(this).val().match(/^[\w\d\s+\-& \/áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
    } else {
        $(this).removeClass("borde_rojo");
    };
  });

  $("#email_agente").on('input', function(){
    if ($(this).val().match(/^[\w\d\s+\-.áÁéÉíÍóÓúÚñÑ@\']+$/) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
    } else {
        $(this).removeClass("borde_rojo");
    };
  });

  $("#direccion_agente").on('input', function(){
    if ($(this).val().match(/^[\w\d\s+\-&.,#:\/áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
    } else {
        $(this).removeClass("borde_rojo");
    };
  });

  $("#direccion_complemento").on('input', function(){
    if ($(this).val().match(/^[\w\d\s+\-&.,#:\/áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
    } else {
        $(this).removeClass("borde_rojo");
    };
  });


// #### Para el label de departamento

    $(".departamento_label").html(`${datos_pais['org_territorial']} :`);


// ### Poblar el select de Agencias FIRST CHARGE

    $.ajax({
        type: "POST",
        url: "process-request-agencias-pais-cupo-limite.php",
        data: { paisChoice : pais_selected, agencia_selected : datos_file['agencia_id'] }
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
                data: { departamentoChoice : departamentoSelected, ciudad_selected : datos_file['ciudad'] }
            }).done(function(data){
                $("#ciudad").prop('disabled', false).html(data);// se activa el select ciudades y pobla
            });

        }else { // si se seleciono vacio, entonces se vacian y bloquean los select ciudad y barrio
        $("#ciudad").empty().prop('disabled', true).val('');
        };
    });

    $("#departamento").trigger('change');




        
    });
});