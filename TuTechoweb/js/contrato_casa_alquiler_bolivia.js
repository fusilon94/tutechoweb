$(document).ready(function(){
jQuery(function($){
    //  ############ LLENADO AUTOMATICO DE CONTRATO ##########################

async function recaudar_datos(agente_id){
    
    // ### FECHA CONTRATO
    // var fecha = new Date();
    // var options = { year: 'numeric', month: 'long', day: 'numeric' };
    // var fecha_string = fecha.toLocaleDateString("es-ES", options);

    // ### DATOS AGENCIA
    let datos_agencia = await $.ajax({
        type: "POST",
        url: "process-request-datos-agencia.php",
        data: { agente_id : agente_id },
        dataType: 'json'
    }).done(function(data){
        return data;
    });

    let barrio_agencia = '';
    if(datos_agencia['agencia']['barrio'] !== ""){
        barrio_agencia = datos_agencia['agencia']['barrio'] + ", ";
    };

    // ### RETORNAR TODOS LOS DATOS RECAUDADOS
    results = {
        'datos_agencia': datos_agencia,
        'barrio_agencia': barrio_agencia
    }
    return results

};

// ################### CONTRATO INICIAL
recaudar_datos(agente_id).then(function(datos){

    // HEADER TUTECHO
    $(".contrato_contenedor").html(`
    <div class="header_contrato_wrap">
        <div class="header_contrato_left">
            <img src="../../objetos/logotipo2.svg" alt="" class="contrato_logo">
            <div class="razon_social">${datos['datos_agencia']['agencia']['razon_social']}</div>
            <div class="direccion_agencia"><p>${datos['datos_agencia']['agencia']['direccion']}<br>${datos['datos_agencia']['agencia']['direccion_complemento']}<br>${datos['datos_agencia']['agencia']['location_tag']} - ${datos['datos_agencia']['agencia']['departamento']} - Bolivia</p></div>
        </div>
        
        <div class="header_contrato_right">
            <p>Referencia Inmueble: <span>______</span></p>
        </div>
    </div>
    
    `);




    // CLAUSULA 1
    $(".contrato_contenedor").append(`
    <p style="text-align: right;">&nbsp;</p>
    <p style="text-align: right;">&nbsp;</p>
    <p style="text-align: right;">&nbsp;</p>
    <p style="text-align: right;">&nbsp;</p>
    <p style="text-align: right;">&nbsp;</p>
    <p style="text-align: right;">&nbsp;</p>
    <h3 style="text-align: center;"><strong>Contrato de Arrendamiento</strong></h3>
    <p style="text-align: center;"><strong>&nbsp;</strong></p>
    <p style="text-align: center;"><strong>&nbsp;</strong></p>
    <p style="text-align: right;">&nbsp;</p>


    <p style="text-align: justify;">Conste por el presente documento privado, que a petici&oacute;n de cualquiera de las partes podr&aacute; elevarse a la categor&iacute;a de instrumento p&uacute;blico a s&oacute;lo reconocimiento de firmas, un Contrato de Arrendamiento sujeto a las siguientes cl&aacute;usulas:</p>
    <p style="text-align: center;"><strong>&nbsp;</strong></p>


    <p><strong><u>1.Cl&aacute;usula Primera</u>:(De las partes)</strong></p>
    <p style="text-align: center;"><strong>&nbsp;</strong></p>
    <p>1.1. El ARRENDADOR:</p>
    <span id="contrato_arrendador" style="text-align: justify; margin-left: 2.5em"><i class="fas fa-circle"></i> ______</span>
    <p><strong>&nbsp;</strong>Persona que ser&aacute; en adelante denominada como el ARRENDADOR.</p>
    <p style="text-align: center;"><strong>&nbsp;</strong></p>
    <p>1.2. El ARRENDATARIO:</p>
    <span id="contrato_arrendatario" style="text-align: justify; margin-left: 2.5em"><i class="fas fa-circle"></i> ______</span>
    <p><strong>&nbsp;</strong>Persona que ser&aacute; en adelante denominada como el ARRENDATARIO.</p>
        
    `);

    // CLAUSULA 2
    $(".contrato_contenedor").append(`

    <p style="text-align: center;"><strong>&nbsp;</strong></p>
    <p><strong><u>2.Cl&aacute;usula Segunda</u></strong><strong>:</strong> (<strong>Del objeto</strong>)</p>
    <p style="text-align: center;"><strong>&nbsp;</strong></p>
    <p><strong>&nbsp;</strong>El <strong>ARRENDADOR</strong> arrienda al <strong>ARRENDATARIO</strong> el siguiente bien inmueble:</p>
    <span>-______</span>
    <p style="text-align: center;"><strong>&nbsp;</strong></p>
    <p><strong>&nbsp;</strong>El contrato se rige por las disposiciones legales vigentes y en especial por las establecidas en el Art. 685 y siguientes del C&oacute;digo Civil.</p>

    `);

    // CLAUSULA 3
    $(".contrato_contenedor").append(`
    <p style="text-align: center;"><strong>&nbsp;</strong></p>
    <p><strong><u>3.Cl&aacute;usula Tercera</u></strong>: <strong>(Del plazo)</strong></p>
    <p style="text-align: center;"><strong>&nbsp;</strong></p>
    <p style="text-align: justify;"><strong>&nbsp;</strong>El presente contrato dar&aacute; inicio el <span>______</span> y tendr&aacute; fin el <span>______</span>. En caso de que ambas partes decidieran ampliar en plazo del Contrato de Arrendamiento, necesariamente se debe realizar un nuevo documento de contrato de alquiler.</p>
    `);

    // CLAUSULA 4
    $(".contrato_contenedor").append(`
    <p style="text-align: center;"><strong>&nbsp;</strong></p>
    <p><strong><u>4.Cl&aacute;usula Cuarta:</u></strong><strong> (Del canon de arrendamiento) </strong>
    <p style="text-align: center;"><strong>&nbsp;</strong></p>
    <p style="text-align: justify;">Se fija como canon <span>______</span> de arrendamiento la suma total de <span>______</span>, <strong>mes adelantado</strong>. El pago del canon debe hacerse hasta el <span>______</span> de cada <span>______</span>.</p>
    <p style="text-align: justify;">El pago del canon de arrendamiento se har&aacute; en la modalidad siguiente:</p>
    <p style="text-align: justify;"><span>______</span>.</p>
    <p style="text-align: justify;">Dicho pago incluye <span>______</span>.</p>
    <p style="text-align: justify;">El incumplimiento del pago del canon de arrendamiento por <span>______</span> consecutivos dar&aacute; lugar a la resoluci&oacute;n ipso-facto del presente contrato.</p>
    `);

    // CLAUSULA 5
    $(".contrato_contenedor").append(`
    <p style="text-align: center;"><strong>&nbsp;</strong></p>
    <p><strong><u>5.Cl&aacute;usula Quinta:</u></strong><strong> (Pago de servicios) </strong>&nbsp;</p>
    <p style="text-align: center;"><strong>&nbsp;</strong></p>
    <p><strong>&nbsp;</strong>El <strong>ARRENDATARIO</strong> deber&aacute; pagar por su cuenta las facturas de su consumo de <span>______</span>.</p>
    `);

    // CLAUSULA 6
    $(".contrato_contenedor").append(`
    <p style="text-align: center;"><strong>&nbsp;</strong></p>
    <p><strong><u>6.Cl&aacute;usula Sexta:</u></strong><strong> (De la garant&iacute;a) </strong></p>
    <p style="text-align: center;"><strong>&nbsp;</strong></p>
    <p style="text-align: justify;">El <strong>ARRENDATARIO</strong><strong>, </strong>como garant&iacute;a de buen uso y utilizaci&oacute;n del bien inmueble <strong>y muebles</strong>, deber&aacute; dar al <strong>ARRENDADOR</strong> la suma de <span>______</span>. A la terminaci&oacute;n del contrato, el <strong>ARRENDADOR</strong> devolver&aacute; el total de la garant&iacute;a, deduci&eacute;ndose &uacute;nicamente el monto invertido a la reparaci&oacute;n de desperfectos y da&ntilde;os causados al inmueble o muebles, fuera del deterioro natural de uso. Si la cantidad indicada no alcanzar&aacute; para cubrir los gastos, el <strong>ARRENDATARIO</strong><strong>, </strong>se compromete a cancelar la diferencia, suma que tendr&aacute; tambien la calidad de l&iacute;quida y exigible para los fines del presente contrato.</p>
    `);

    // CLAUSULA 7
    $(".contrato_contenedor").append(`
    <p style="text-align: center;"><strong>&nbsp;</strong></p>
    <p><strong><u>7.Cl&aacute;usula S&eacute;ptima:</u></strong><strong> (De los derechos y obligaciones)</strong></p>
    <p><strong>&nbsp;</strong></p>
    <p><strong>7.1.Saneamiento y Evicci&oacute;n</strong></p>
    <p><strong>&nbsp;</strong></p>
    <p><strong>7.2.Restitucion de la Cosa</strong></p>
    <p><strong>&nbsp;</strong></p>
    <p><strong>7.3.Subarrendamiento</strong></p>
    <p><strong>&nbsp;</strong></p>
    <p><strong>7.4.Mantenimiento</strong></p>
    <p><strong>&nbsp;</strong></p>
    <p><strong>7.5.Innovaciones</strong></p>
    <p><strong>&nbsp;</strong></p>
    <p><strong>7.6.Prohibiciones</strong></p>
    <p><strong>&nbsp;</strong></p>
    <p><strong>7.7.Extinccion del contrato</strong></p>
    <p><strong>&nbsp;</strong></p>
    <p><strong>7.8.Extras</strong></p>
    <p><strong>&nbsp;</strong></p>
    `);

    // CLAUSULA 8
    $(".contrato_contenedor").append(`
    <p style="text-align: center;"><strong>&nbsp;</strong></p>
    <p><strong><u>8.</u></strong><strong><u>Cl&aacute;usula Octava:</u></strong><strong> (Aceptaci&oacute;n) </strong></p>
    <p><strong>&nbsp;</strong></p>
    <p style="text-align: justify;">Nosotros, ARRENDADOR y ARRENDATARIO, aceptamos y damos nuestra plena conformidad con todas y cada una de las cl&aacute;usulas precedentes y condiciones estipuladas en el presente contrato, en fe de lo cual suscribimos y firmamos en doble ejemplar de id&eacute;ntico tenor en la ciudad de <span>______</span>, en fecha, <span>______</span>.</p>
    `);

    // CODIGO QR

    $(".contrato_contenedor").append(`
    <div id="codigo_qr_container" class="codigo_qr_container"></div>
    `);

    const QR_link = `www.tutecho.com/contratos_internos_check/REFERENCIA_INMUEBLE`;
    new QArt({
        value: QR_link,
        imagePath: '../../objetos/techo_factura.svg',
        filter: 'threshold',
        size: 100,
        version: 10,
        fillType: 'scale_to_fit'
    }).make(document.getElementById('codigo_qr_container'));


    $(".contrato_contenedor span").each(function(){
        if (!$(this).hasClass("dato")) {
            $(this).addClass("dato");
        };
    });

});

// #################################### OBJETO DATOS ALMACENADOS #####################################################

const datos_formulario = {
    arrendador: [{}],
    arrendatario: [{}]
};

// #################################### DEFINICION DE FUNCIONES ESPECIFICAS ##########################################

function edit_select(dato, dato_compare){
    if (dato == dato_compare) {
        return 'selected';
    }else{
        return '';
    };
};


function crear_formulario_datos(categoria, parametros){//tipo es string, parametros es un array/objeto
    let formulario_datos;

    let edit_estado = $(`.group_question_wrap[count="${parametros.count}"][categoria="${categoria}"]`).find(".estado_datos");
    let datos = {};
    if (edit_estado.hasClass("activo")) { 
        datos = datos_formulario[categoria][parametros.count-1].datos;
    };
    
    if (categoria === 'arrendador') {

        if (parametros['tipo']=='') {
            formulario_datos = `<h2>Datos Incompletos</br>->Debe escoger un tipo de ARRENDADOR</h2>`;
        }else{
            
            if (parametros['tipo'] == 'natural') {
                const { nombre = '', tipo_doc = '', numero_identidad = '', domicilio = '' } = datos;//para el modo EDIT

                formulario_datos = `
                    <h2 style="margin-bottom: 1em">Arrendador #${parametros['count']}</h2>

                    <div class="popup_preguntas_wrap">

                        <div class="popup_wrap_vertical">
                            <label for="popup_nombre">Nombre y Apellidos</label>
                            <input name="popup_nombre" value="${nombre}" class="popup_input_string popup_nombre">
                        </div>
                        <div class="popup_wrap_vertical">
                            <label for="popup_doc_identidad">Documento de Identidad</label>
                            <select name="popup_doc_identidad" class="popup_doc_identidad">
                                <option value="" ${edit_select(tipo_doc, "")}></option>
                                <option value="Carnet de Identidad" ${edit_select(tipo_doc, 'Carnet de Identidad')}>Carnet de Identidad</option>
                                <option value="Pasaporte" ${edit_select(tipo_doc, 'Pasaporte')}>Pasaporte</option>
                            </select>
                        </div>
                        <div class="popup_wrap_vertical">
                            <label for="popup_num_identidad">N° de Documento</label>
                            <input name="popup_num_identidad" value="${numero_identidad}" placeholder="" class="popup_input_num popup_num_identidad">
                        </div>
                        <div class="popup_wrap_vertical">
                            <label for="popup_domicilio">Domicilio</label>
                            <input name="popup_domicilio" value="${domicilio}" placeholder="ej: #numero, calle/avenida, ciudad - departamento" class="popup_input_string popup_domicilio">
                        </div>

                `;
            }else if(parametros['tipo'] == 'juridico'){
                const { razon_social = '', tipo_entidad = '', nit = '', domicilio = '', representante_nombre = '', representante_doc_identidad = '', representante_num_identidad = '', representante_cargo = '' } = datos;//para el modo EDIT

                formulario_datos = `
                    <h2 style="margin-bottom: 1em">Arrendador #${parametros['count']}</h2>

                    <div class="popup_preguntas_wrap">

                        <div class="popup_wrap_vertical">
                            <label for="popup_razon_social">Razón Social</label>
                            <input name="popup_razon_social" value="${razon_social}" class="popup_input_string popup_razon_social">
                        </div>
                        <div class="popup_wrap_vertical">
                            <label for="popup_tipo_entidad">Tipo de Entidad</label>
                            <select name="popup_tipo_entidad" class="popup_tipo_entidad">
                                <option value="" ${edit_select(tipo_entidad, '')}></option>
                                <option value="Sociedad Anónima" ${edit_select(tipo_entidad, 'Sociedad Anónima')}>Sociedad Anónima</option>
                                <option value="Sociedad a Resposabilidad Limitada" ${edit_select(tipo_entidad, 'Sociedad a Resposabilidad Limitada')}>Sociedad a Resposabilidad Limitada</option>
                            </select>
                        </div>
                        <div class="popup_wrap_vertical">
                            <label for="popup_nit">NIT</label>
                            <input name="popup_nit" value="${nit}" placeholder="" class="popup_input_string popup_nit">
                        </div>
                        <div class="popup_wrap_vertical">
                            <label for="popup_domicilio_sede">Direccion principal</label>
                            <input name="popup_domicilio_sede" value="${domicilio}" placeholder="" class="popup_input_string popup_domicilio_sede">
                        </div>
                        <div class="popup_wrap_vertical">
                            <label for="popup_representante_nombre">Nombre y Apellido del representante</label>
                            <input name="popup_representante_nombre" value="${representante_nombre}" placeholder="ej: #numero, calle/avenida, ciudad - departamento"  class="popup_input_string popup_representante_nombre">
                        </div>
                        <div class="popup_wrap_vertical">
                            <label for="popup_representante_doc_identidad">Documento de Identidad</label>
                            <select name="popup_representante_doc_identidad" class="popup_representante_doc_identidad">
                                <option value="" ${edit_select(representante_doc_identidad, '')}></option>
                                <option value="Carnet de Identidad" ${edit_select(representante_doc_identidad, 'Carnet de Identidad')}>Carnet de Identidad</option>
                                <option value="Pasaporte" ${edit_select(representante_doc_identidad, 'Pasaporte')}>Pasaporte</option>
                            </select>
                        </div>
                        <div class="popup_wrap_vertical">
                            <label for="popup_representante_num_identidad">N° de Documento</label>
                            <input name="popup_representante_num_identidad" value="${representante_num_identidad}" placeholder="" class="popup_input_num popup_representante_num_identidad">
                        </div>
                        <div class="popup_wrap_vertical">
                            <label for="popup_representante_cargo">Cargo que ocupa</label>
                            <input name="popup_representante_cargo" value="${representante_cargo}" placeholder=""  class="popup_input_string popup_representante_cargo">
                        </div>

                `;
            };
            

            if (parametros['poder'] == true) {
                const { poder_num = '', notaria = '' } = datos;//para el modo EDIT

                formulario_datos += `
                    <div class="popup_wrap_vertical">
                        <label for="popup_poder_num">N° de Poder</label>
                        <input name="popup_poder_num" value="${poder_num}" class="popup_input_num popup_poder_num">
                    </div>
                    <div class="popup_wrap_vertical">
                        <label for="popup_notaria">Notaria</label>
                        <input name="popup_notaria" value="${notaria}" placeholder="ej: Notaria de Fé Pública n°42"  class="popup_input_string popup_notaria">
                    </div>
                `;
            }

            formulario_datos += `
            </div>
            <span class="popup_datos_mensaje"></span>
            <span class="popup_guardar_datos" parametros=${JSON.stringify(parametros)} categoria="arrendador">Guardar Datos</span>
            `;
        };


    } else if (categoria === 'arrendatario') {

        if (parametros['tipo']=='') {
            formulario_datos = `<h2>Datos Incompletos</br>->Debe escoger un tipo de ARRENDATARIO</h2>`;
        }else{
            
            if (parametros['tipo'] == 'natural') {
                const { nombre = '', tipo_doc = '', numero_identidad = '', domicilio = '' } = datos;//para el modo EDIT

                formulario_datos = `
                    <h2 style="margin-bottom: 1em">Arrendatario #${parametros.count}</h2>

                    <div class="popup_preguntas_wrap">

                        <div class="popup_wrap_vertical">
                            <label for="popup_nombre">Nombre y Apellidos</label>
                            <input name="popup_nombre" value="${nombre}" class="popup_input_string popup_nombre">
                        </div>
                        <div class="popup_wrap_vertical">
                            <label for="popup_doc_identidad">Documento de Identidad</label>
                            <select name="popup_doc_identidad" class="popup_doc_identidad">
                                <option value="" ${edit_select(tipo_doc, "")}></option>
                                <option value="Carnet de Identidad" ${edit_select(tipo_doc, 'Carnet de Identidad')}>Carnet de Identidad</option>
                                <option value="Pasaporte" ${edit_select(tipo_doc, 'Pasaporte')}>Pasaporte</option>
                            </select>
                        </div>
                        <div class="popup_wrap_vertical">
                            <label for="popup_num_identidad">N° de Documento</label>
                            <input name="popup_num_identidad" value="${numero_identidad}" placeholder="" class="popup_input_num popup_num_identidad">
                        </div>
                        
                `;

                if (parametros['poder'] == true || parametros['terceros'] == true) {
                    formulario_datos += `
                        <div class="popup_wrap_vertical">
                            <label for="popup_domicilio">Domicilio</label>
                            <input name="popup_domicilio" value="${domicilio}" placeholder="ej: #numero, calle/avenida, ciudad - departamento" class="popup_input_string popup_domicilio">
                        </div>
                    `;
                };
            }else if(parametros['tipo'] == 'juridico'){
                const { razon_social = '', tipo_entidad = '', nit = '', domicilio = '', representante_nombre = '', representante_doc_identidad = '', representante_num_identidad = '', representante_cargo = '' } = datos;//para el modo EDIT

                formulario_datos = `
                    <h2 style="margin-bottom: 1em">Arrendador #${parametros['count']}</h2>

                    <div class="popup_preguntas_wrap">

                        <div class="popup_wrap_vertical">
                            <label for="popup_razon_social">Razón Social</label>
                            <input name="popup_razon_social" value="${razon_social}" class="popup_input_string popup_razon_social">
                        </div>
                        <div class="popup_wrap_vertical">
                            <label for="popup_tipo_entidad">Tipo de Entidad</label>
                            <select name="popup_tipo_entidad" class="popup_tipo_entidad">
                                <option value="" ${edit_select(tipo_entidad, '')}></option>
                                <option value="Sociedad Anónima" ${edit_select(tipo_entidad, 'Sociedad Anónima')}>Sociedad Anónima</option>
                                <option value="Sociedad a Resposabilidad Limitada" ${edit_select(tipo_entidad, 'Sociedad a Resposabilidad Limitada')}>Sociedad a Resposabilidad Limitada</option>
                            </select>
                        </div>
                        <div class="popup_wrap_vertical">
                            <label for="popup_nit">NIT</label>
                            <input name="popup_nit" value="${nit}" placeholder="" class="popup_input_string popup_nit">
                        </div>
                        <div class="popup_wrap_vertical">
                            <label for="popup_domicilio_sede">Direccion principal</label>
                            <input name="popup_domicilio_sede" value="${domicilio}" placeholder="" class="popup_input_string popup_domicilio_sede">
                        </div>
                        <div class="popup_wrap_vertical">
                            <label for="popup_representante_nombre">Nombre y Apellido del representante</label>
                            <input name="popup_representante_nombre" value="${representante_nombre}" placeholder="ej: #numero, calle/avenida, ciudad - departamento"  class="popup_input_string popup_representante_nombre">
                        </div>
                        <div class="popup_wrap_vertical">
                            <label for="popup_representante_doc_identidad">Documento de Identidad</label>
                            <select name="popup_representante_doc_identidad" class="popup_representante_doc_identidad">
                                <option value="" ${edit_select(representante_doc_identidad, '')}></option>
                                <option value="Carnet de Identidad" ${edit_select(representante_doc_identidad, 'Carnet de Identidad')}>Carnet de Identidad</option>
                                <option value="Pasaporte" ${edit_select(representante_doc_identidad, 'Pasaporte')}>Pasaporte</option>
                            </select>
                        </div>
                        <div class="popup_wrap_vertical">
                            <label for="popup_representante_num_identidad">N° de Documento</label>
                            <input name="popup_representante_num_identidad" value="${representante_num_identidad}" placeholder="" class="popup_input_num popup_representante_num_identidad">
                        </div>
                        <div class="popup_wrap_vertical">
                            <label for="popup_representante_cargo">Cargo que ocupa</label>
                            <input name="popup_representante_cargo" value="${representante_cargo}" placeholder=""  class="popup_input_string popup_representante_cargo">
                        </div>

                `;
            };
            

            if (parametros['poder'] == true) {
                const { poder_num = '', notaria = '' } = datos;//para el modo EDIT

                formulario_datos += `
                    <div class="popup_wrap_vertical">
                        <label for="popup_poder_num">N° de Poder</label>
                        <input name="popup_poder_num" value="${poder_num}" class="popup_input_num popup_poder_num">
                    </div>
                    <div class="popup_wrap_vertical">
                        <label for="popup_notaria">Notaria</label>
                        <input name="popup_notaria" value="${notaria}" placeholder="ej: Notaria de Fé Pública n°42"  class="popup_input_string popup_notaria">
                    </div>
                `;
            };

            if (parametros['terceros'] == true) {
                let count_terceros = 1;
                formulario_datos += `
                    <h2 style="width: 100%; margin-bottom: 0.8em; margin-top: 0.3em;text-align: center">--- TERCERO(S) ---</h2>
                `;

                if ('terceros' in datos) {
                    
                    datos.terceros.forEach(function(tercero){

                        const { nombre = '', tipo_doc = '', numero_identidad = '' } = tercero;//para el modo EDIT
    
                        formulario_datos += `
                            <div class="terceros_group">
                                <h2 style="margin-bottom: 1em; width: 100%; font-size: 1.1em">TERCERO #${count_terceros} <i class="borrar_tercero fa fa-trash-alt"></i></h2>
                                <div class="popup_wrap_vertical">
                                    <label for="popup_terceros_nombre">Nombre y Apellidos</label>
                                    <input name="popup_terceros_nombre" value="${nombre}" class="popup_input_num popup_terceros_nombre">
                                </div>
                                <div class="popup_wrap_vertical">
                                    <label for="popup_terceros_doc_identidad">Documento de Identidad</label>
                                    <select name="popup_terceros_doc_identidad" class="popup_terceros_doc_identidad">
                                        <option value="" ${edit_select(tipo_doc, '')}></option>
                                        <option value="Carnet de Identidad" ${edit_select(tipo_doc, 'Carnet de Identidad')}>Carnet de Identidad</option>
                                        <option value="Pasaporte" ${edit_select(tipo_doc, 'Pasaporte')}>Pasaporte</option>
                                    </select>
                                </div>
                                <div class="popup_wrap_vertical">
                                    <label for="popup_terceros_num_identidad">N° de Documento</label>
                                    <input name="popup_terceros_num_identidad" value="${numero_identidad}" placeholder="" class="popup_input_num popup_terceros_num_identidad">
                                </div>
                                <hr style="width: 80%; margin-bottom: 1em">
                            </div>
                           
                        `;

                        count_terceros += 1;
    
                    });

                }else{
                    formulario_datos += `
                    <div class="terceros_group">
                    <h2 style="margin-bottom: 1em; width: 100%; font-size: 1.1em">TERCERO #${count_terceros} <i class="borrar_tercero fa fa-trash-alt"></i></h2>
                        <div class="popup_wrap_vertical">
                            <label for="popup_terceros_nombre">Nombre y Apellidos</label>
                            <input name="popup_terceros_nombre" value="" class="popup_input_num popup_terceros_nombre">
                        </div>
                        <div class="popup_wrap_vertical">
                            <label for="popup_terceros_doc_identidad">Documento de Identidad</label>
                            <select name="popup_terceros_doc_identidad" class="popup_terceros_doc_identidad">
                                <option value=""></option>
                                <option value="Carnet de Identidad">Carnet de Identidad</option>
                                <option value="Pasaporte">Pasaporte</option>
                            </select>
                        </div>
                        <div class="popup_wrap_vertical">
                        <label for="popup_terceros_num_identidad">N° de Documento</label>
                        <input name="popup_terceros_num_identidad" value="" placeholder="" class="popup_input_num popup_terceros_num_identidad">
                        </div>
                        <hr style="width: 80%; margin-bottom: 1em">
                    </div>
                   
                `;
                };

                

                formulario_datos += `
                    <span id="agregar_terceros" class="agregar_terceros"><i class="fas fa-plus-square" style="margin-rig: 0.5em; font-size: 1.3em"></i></i>AGREGAR TERCERO</span>
                `;
            };
            

            formulario_datos += `
            </div>
            <span class="popup_datos_mensaje"></span>
            <span class="popup_guardar_datos" parametros=${JSON.stringify(parametros)} categoria="arrendatario">Guardar Datos</span>
            `;
        };


    };//ir agregando mas tipos si fuera necesario


    $(".popup_datos_contenido").html(formulario_datos);//se carga el formulario datos en el popup datos 
};



function guardar_formulario_datos(categoria, parametros) {
    
    if (categoria === 'arrendador') {
        const { tipo, poder, count } = parametros;

        let datos = {};

        if (tipo == "natural") {
            
            datos = {
                nombre: $('.popup_nombre').val(),
                tipo_doc: $('.popup_doc_identidad option:selected').val(),
                numero_identidad: $('.popup_num_identidad').val(),
                domicilio: $('.popup_domicilio').val(),
            };

        }else if(tipo == "juridico"){
            datos = {
                razon_social: $('.popup_razon_social').val(),
                tipo_entidad: $('.popup_tipo_entidad option:selected').val(),
                nit: $('.popup_nit').val(),
                domicilio: $('.popup_domicilio_sede').val(),
                representante_nombre: $('.popup_representante_nombre').val(),
                representante_doc_identidad: $('.popup_representante_doc_identidad option:selected').val(),
                representante_num_identidad: $('.popup_representante_num_identidad').val(),
                representante_cargo: $('.popup_representante_cargo').val(),
            };
        };

        if (poder == true) {

            datos.poder_num = $('.popup_poder_num').val();
            datos.notaria = $('.popup_notaria').val();
            
        };

        
        datos_formulario.arrendador[count-1] = {
            tipo: tipo,
            poder: poder,
            datos: datos,
        };

    }else if(categoria === 'arrendatario'){
        const { tipo, poder, terceros, count } = parametros;

        let datos = {};

        if (tipo == "natural") {
            
            datos = {
                nombre: $('.popup_nombre').val(),
                tipo_doc: $('.popup_doc_identidad option:selected').val(),
                numero_identidad: $('.popup_num_identidad').val(),
            };
            
            if (poder == true || terceros == true) {
                datos.domicilio = $('.popup_domicilio').val();
            };

        }else if(tipo == "juridico"){
            datos = {
                razon_social: $('.popup_razon_social').val(),
                tipo_entidad: $('.popup_tipo_entidad option:selected').val(),
                nit: $('.popup_nit').val(),
                domicilio: $('.popup_domicilio_sede').val(),
                representante_nombre: $('.popup_representante_nombre').val(),
                representante_doc_identidad: $('.popup_representante_doc_identidad option:selected').val(),
                representante_num_identidad: $('.popup_representante_num_identidad').val(),
                representante_cargo: $('.popup_representante_cargo').val(),
            };
        };

        if (poder == true) {

            datos.poder_num = $('.popup_poder_num').val();
            datos.notaria = $('.popup_notaria').val();
            
        };

        if (terceros == true) {

            datos.terceros = []

            $(".terceros_group").each(function(){

                tercero = {
                    nombre: $(this).find('.popup_terceros_nombre').val(),
                    tipo_doc: $(this).find('.popup_terceros_doc_identidad option:selected').val(),
                    numero_identidad: $(this).find('.popup_terceros_num_identidad').val(),
                };
                
                datos.terceros.push(tercero);
            });

        };

        
        datos_formulario.arrendatario[count-1] = {
            tipo: tipo,
            poder: poder,
            terceros: terceros,
            datos: datos,
        };

    };

    
};

function refresh_contrato() {

    // ###### ETAPA 1 --> ARRENDADORES

    let arrendador_text = '';
    datos_formulario['arrendador'].forEach(function(arrendador){
        let arrendador_element = '';
        if (Object.keys(arrendador).length == 0) {
            arrendador_element += ` <i class="fas fa-circle"></i> ______</br></br>`;
        }else{
            
            if (arrendador['tipo'] == "natural") {

                arrendador_element += `
                     <i class="fas fa-circle"></i> ${arrendador.datos.nombre}, con ${arrendador.datos.tipo_doc} n°${arrendador.datos.numero_identidad}, con domicilio en ${arrendador.datos.domicilio}
                `;
                
            } else if (arrendador['tipo'] == "juridico"){
                
                arrendador_element += `
                     <i class="fas fa-circle"></i> ${arrendador.datos.razon_social}, ${arrendador.datos.tipo_entidad} con NIT n°${arrendador.datos.nit}, con dirección principal en ${arrendador.datos.domicilio}, siendo representada por ${arrendador.datos.representante_nombre}, con ${arrendador.datos.representante_doc_identidad} n°${arrendador.datos.representante_num_identidad}, que ocupa el cargo de ${arrendador.datos.representante_cargo}
                `;

            };

            if (arrendador['poder'] == true) {
                arrendador_element += `
                    , con Poder Notariado espécifico y suficiente n°${arrendador.datos.poder_num} otorgado por la ${arrendador.datos.notaria}</br></br>
                `;
            }else{
                arrendador_element += `.</br></br>`;  
            };

            
        };

        arrendador_text += arrendador_element;

        
    });

    $('#contrato_arrendador').html(arrendador_text);
    
    // ###### ETAPA 2 --> ARRENDATARIOS

    let arrendatario_text = '';
    datos_formulario['arrendatario'].forEach(function(arrendatario){
        let arrendatario_element = '';
        if (Object.keys(arrendatario).length == 0) {
            arrendatario_element += `
                 <i class="fas fa-circle"></i> ______</br></br>
                `;
        }else{
            
            if (arrendatario['tipo'] == "natural") {

                arrendatario_element += `
                     <i class="fas fa-circle"></i> ${arrendatario.datos.nombre}, con ${arrendatario.datos.tipo_doc} n°${arrendatario.datos.numero_identidad}
                `;

                if (arrendatario.poder == true || arrendatario.terceros == true ){
                    arrendatario_element += `, con domicilio en ${arrendatario.datos.domicilio}`;
                };
                
            } else if (arrendatario['tipo'] == "juridico"){
                
                arrendatario_element += `
                     <i class="fas fa-circle"></i> ${arrendatario.datos.razon_social}, ${arrendatario.datos.tipo_entidad} con NIT n°${arrendatario.datos.nit}, con dirección principal en ${arrendatario.datos.domicilio}, siendo representada por ${arrendatario.datos.representante_nombre}, con ${arrendatario.datos.representante_doc_identidad} n°${arrendatario.datos.representante_num_identidad}, que ocupa el cargo de ${arrendatario.datos.representante_cargo}
                `;

            };

            if (arrendatario['poder'] == true) {
                arrendatario_element += `
                    , con Poder Notariado espécifico y suficiente n°${arrendatario.datos.poder_num} otorgado por la ${arrendatario.datos.notaria}
                `;
            };
            if (arrendatario.terceros == true) {

                arrendatario_element += `
                    , se da como Garante y toma total responsablidad por el Uso y Goce del inmueble cedido a:
                    `;
                
                arrendatario.datos.terceros.forEach(function(tercero){

                    arrendatario_element += `</br>
                    <p style="margin-left: 3.5em">-&nbsp;${tercero.nombre}, con ${tercero.tipo_doc} n°${tercero.numero_identidad}</p>`;

                });

                arrendatario_element += `</br></br>`;
                
            }else{
                arrendatario_element += `.</br></br>`;  
            };
            
            
            
        };

        arrendatario_text += arrendatario_element;

        
    });

    $('#contrato_arrendatario').html(arrendatario_text);
    
    
};

function refresh_count(categoria){ // Restablece el count de los arrendadores cuando se borra alguno

    let count_total = 0;
    if (categoria === 'arrendador') {
        count_total = $(".etapas_wrap").find(".arrendador_wrap").length;
        let count = 1;
        $(".etapas_wrap").find(".arrendador_wrap").each(function(){
            if (count <= count_total) {
    
                $(this).attr("count", count)
    
                $(this).find(".titulo_group").html(`
                    <span style="color: #fff">ARRENDADOR #${count}</span>
                    <span class="borrar_elemento_btn"><i class="fa fa-trash-alt"></i></span>
                `); 
    
            };
    
            count += 1;
            
        });
    }else if (categoria === 'arrendatario') {
        count_total = $(".etapas_wrap").find(".arrendatario_wrap").length;
        let count = 1;
        $(".etapas_wrap").find(".arrendatario_wrap").each(function(){
            if (count <= count_total) {
    
                $(this).attr("count", count)
    
                $(this).find(".titulo_group").html(`
                    <span style="color: #fff">ARRENDATARIO #${count}</span>
                    <span class="borrar_elemento_btn"><i class="fa fa-trash-alt"></i></span>
                `); 
    
            };
    
            count += 1;
            
        });
    };

};

function add_check(categoria, count){
    if (categoria === 'arrendador') {

        $(".etapas_wrap").find(".arrendador_wrap").eq(count-1).find(".estado_datos").addClass("activo");
        
    } else if (categoria === 'arrendatario'){
        $(".etapas_wrap").find(".arrendatario_wrap").eq(count-1).find(".estado_datos").addClass("activo");
    };
};

function check_estado_change(element){
    const estado = $(element).parents('.group_question_wrap').find(".estado_datos");
    if (!estado.hasClass("activo")) { return };

    estado.removeClass("activo");

    let categoria = $(element).parents('.group_question_wrap').attr("categoria");
    let count = $(element).parents('.group_question_wrap').attr("count");

    datos_formulario[categoria][count-1] = {};
    refresh_contrato();
};

// #################################### REGEX ESPECIFICOS ##########################################

    $(".popup_datos_contenido").on("input", ".popup_input_string", function(){
        if ($(this).val().match(/^[\w\d\s #áÁéÉíÍóÓúÚñÑ\']+$/) == null) {//Si se ingrso un caracter no permitido
            if ($(this).val() !== '') {
                $(this).addClass("borde_rojo");
                $(".popup_datos_mensaje").html("<p>Sólo use caracteres permitidos</p>").css("visibility", "unset");
            };
          } else {
            $(this).removeClass("borde_rojo");
            $(".popup_datos_mensaje").html("").css("visibility", "hidden");
          };
    });

    $(".popup_datos_contenido").on("input", ".popup_input_num", function(){
        if ($(this).val().match(/^[\w\d\s+\-0-9:#%&-_$ \/]+$/g) == null) {//Si se ingrso un caracter no permitido
            if ($(this).val() !== '') {
                $(this).addClass("borde_rojo");
                $(".popup_datos_mensaje").html("<p>Sólo use caracteres permitidos</p>").css("visibility", "unset");
            };
          } else {
            $(this).removeClass("borde_rojo");
            $(".popup_datos_mensaje").html("").css("visibility", "hidden");
          };
    });

    $(".popup_datos_contenido").on("input", ".popup_input_num_strict", function(){
        if ($(this).val().match(/^[+\-0-9: \/]+$/g) == null) {//Si se ingrso un caracter no permitido
            if ($(this).val() !== '') {
                $(this).addClass("borde_rojo");
                $(".popup_datos_mensaje").html("<p>Sólo use caracteres permitidos</p>").css("visibility", "unset");
            };
          } else {
            $(this).removeClass("borde_rojo");
            $(".popup_datos_mensaje").html("").css("visibility", "hidden");
          };
    });

// #################################### DEFINICION DE MECANICAS ESPECIFICAS ##########################################


$(".etapas_wrap").on("click", ".check_element", function(){//funcion de checks boxes en preguntas

    if ($(this).html() == '<i class="far fa-square"></i>') {
        $(this).html(`<i class="fas fa-check-square"></i>`).addClass("activo");
    }else if($(this).html() == '<i class="fas fa-check-square"></i>'){
        $(this).html(`<i class="far fa-square"></i>`).removeClass("activo");
    };

    const parent_wrap = $(this).parents('.group_question_wrap').attr("categoria")
    if (parent_wrap == "arrendador" || parent_wrap == 'arrendatario') {
        check_estado_change( $(this) );
    };
});


$(".etapas_wrap").on("change", ".pregunta_select_short", function(){
    
    check_estado_change( $(this) );

});

$(".etapas_wrap").on("click", ".datos_btn", function(){

    let categoria = $(this).attr('categoria');
    let parametros;

    if (categoria == 'arrendador') {
        let count = $(this).parent().parent().parent().attr("count");
        let tipo = $(this).parent().parent().find(".pregunta_select_short").find("option:selected").attr("value");
        let poder = $(this).parent().parent().find(".check_poder").hasClass("activo");
        parametros = {
            tipo: tipo,
            poder: poder,
            count: count
        };
    }else if(categoria == 'arrendatario'){
        let count = $(this).parent().parent().parent().attr("count");
        let tipo = $(this).parent().parent().find(".pregunta_select_short").find("option:selected").attr("value");
        let poder = $(this).parent().parent().find(".check_poder").hasClass("activo");
        let terceros = $(this).parent().parent().find(".check_terceros").hasClass("activo");
        parametros = {
            tipo: tipo,
            poder: poder,
            terceros: terceros,
            count: count
        };


    };

    crear_formulario_datos(categoria, parametros);
   
    $(".overlay_datos").css("visibility", "unset"); 
       
});

$(".etapas_wrap").on("click", ".borrar_elemento_btn", function(){
    const categoria = $(this).parent().parent().attr("categoria");
    let count_wraps;
    if (categoria == "arrendador") {
        count_wraps = $(this).parents('.preguntas_wrap').find(".arrendador_wrap").length;

    }else if(categoria == "arrendatario"){
        count_wraps = $(this).parents('.preguntas_wrap').find(".arrendatario_wrap").length;
    };
    

    if (count_wraps > 1) {
        let count = $(this).parent().parent().attr("count");

        $(this).parent().parent().remove();
        datos_formulario[categoria].splice(count-1, 1);
        refresh_count(categoria);
        refresh_contrato();
    } else {
        console.log(count_wraps)
    };
    
});


$(".etapas_wrap").on("click", ".agregar_arrendador_btn", function(){// ###" AGREGAR ARRENDADOR ONCLICK ###"
    const count_wraps = ($(this).parent().find(".group_question_wrap").length) + 1;

    $(this).parent().find(".group_question_wrap").last().after(`
        <div class="arrendador_wrap group_question_wrap" count="${count_wraps}" categoria="arrendador">
            <span class="titulo_group">
                <span style="color: #fff">ARRENDADOR #${count_wraps}</span>
                <span class="borrar_elemento_btn"><i class="fa fa-trash-alt"></i></span>
            </span>
            <span class="pregunta_elemento">
                <div class="pregunta_arrendador_group">
                    <span class="tipo_wrap_vertical">
                        <label for="arrendatario_tipo" class="pregunta_label">Tipo:</label>
                        <select name="arrendatario_tipo" class="pregunta_select_short">
                            <option value=""></option>
                            <option value="natural">Natural</option>
                            <option value="juridico">Jurídico</option>
                        </select>
                    </span>
                    <span class="checks_wrap_horizontal">
                        <span class="check_wrap">
                            <p class="check_label">Poder <i class="fas fa-info-circle" title="Representa a alguien más con Poder especifico y suficiente notariado"></i></p>
                            <span class="check_element check_poder"><i class="far fa-square"></i></span>
                        </span>
                        
                    </span>

                </div>
                <div class="datos_btn_group">
                    <span class="datos_btn" categoria="arrendador">Completar Datos</span>
                    <span class="estado_datos fa-stack fa-1x">
                        <i class="fas fa-circle fa-stack-1x"></i>
                        <i class="fas fa-check-circle fa-stack-1x"></i>
                    </span>
                </div>
            </span>
            </hr>
        </div>
    `);

    datos_formulario.arrendador.push({});
    refresh_contrato();
});


$(".etapas_wrap").on("click", ".agregar_arrendatario_btn", function(){// ###" AGREGAR ARRENDATARIO ONCLICK ###"
    const count_wraps = ($(this).parent().find(".group_question_wrap").length) + 1;

    $(this).parent().find(".group_question_wrap").last().after(`
        <div class="arrendatario_wrap group_question_wrap" count="${count_wraps}" categoria="arrendatario">
                <span class="titulo_group">
                <span style="color: #fff">ARRENDATARIO #${count_wraps}</span>
                <span class="borrar_elemento_btn"><i class="fa fa-trash-alt"></i></span>
            </span>
            <span class="pregunta_elemento">
                <div class="pregunta_arrendador_group">
                    <span class="tipo_wrap_vertical">
                        <label for="arrendatario_tipo" class="pregunta_label">Tipo:</label>
                        <select name="arrendatario_tipo" class="pregunta_select_short">
                            <option value=""></option>
                            <option value="natural">Natural</option>
                            <option value="juridico">Jurídico</option>
                        </select>
                    </span>
                    <span class="checks_wrap_horizontal">
                        <span class="check_wrap">
                            <p class="check_label">Poder <i class="fas fa-info-circle" title="Representa a alguien más con Poder especifico y suficiente notariado"></i></p>
                            <span class="check_element check_poder"><i class="far fa-square"></i></span>
                        </span>
                        <span class="check_wrap">
                            <p class="check_label">Terceros <i class="fas fa-info-circle"  title="Toma la responsabilidad pero el Uso y Goce del Inmueble será para un Tercero"></i></p>
                            <span class="check_element check_terceros"><i class="far fa-square"></i></span>
                        </span>
                    </span>

                </div>
                <div class="datos_btn_group">
                    <span class="datos_btn" categoria="arrendatario">Agregar Datos</span>
                    <span class="estado_datos fa-stack fa-1x">
                        <i class="fas fa-circle fa-stack-1x"></i>
                        <i class="fas fa-check-circle fa-stack-1x"></i>
                    </span>
                </div>
            </span>
            <hr>
        </div>
    `);

    datos_formulario.arrendatario.push({});
    refresh_contrato();
});


// AGREGAR UN TERCERO
$('.popup_datos_contenido').on('click', '.agregar_terceros', function(){
    const count_terceros = $(".popup_datos_contenido").find(".terceros_group").length + 1;
    $(this).prev().after(`
    <div class="terceros_group">
    <h2 style="margin-bottom: 1em; width: 100%; font-size: 1.1em">TERCERO #${count_terceros} <i class="borrar_tercero fa fa-trash-alt"></i></h2>
        <div class="popup_wrap_vertical">
            <label for="popup_terceros_nombre">Nombre y Apellidos</label>
            <input name="popup_terceros_nombre" value="" class="popup_input_num popup_terceros_nombre">
        </div>
        <div class="popup_wrap_vertical">
            <label for="popup_terceros_doc_identidad">Documento de Identidad</label>
            <select name="popup_terceros_doc_identidad" class="popup_terceros_doc_identidad">
                <option value=""></option>
                <option value="Carnet de Identidad">Carnet de Identidad</option>
                <option value="Pasaporte">Pasaporte</option>
            </select>
        </div>
        <div class="popup_wrap_vertical">
        <label for="popup_terceros_num_identidad">N° de Documento</label>
        <input name="popup_terceros_num_identidad" value="" placeholder="" class="popup_input_num popup_terceros_num_identidad">
        </div>
        <hr style="width: 80%; margin-bottom: 1em">
    </div>
    `)
});
// BORRAR UN TERCERO
$('.popup_datos_contenido').on('click', '.borrar_tercero', function(){
    
    if ($('.terceros_group').length <= 1) { return };

    $(this).parent().parent().remove();
    let count = 1;
    $('.terceros_group').each(function(){
        $(this).find('h2').html(`TERCERO #${count} <i class="borrar_tercero fa fa-trash-alt"></i>`)
        count++
    })

});

$(".popup_datos_contenido").on("click", ".popup_guardar_datos", function(){
    //VALIDACION DE LLENADO DE FORMULARIO
    let error = false;
    $(".popup_datos_contenido").find("input:not(.opcional)").each(function(){
        if ($(this).val() == '') { error = true };
    });

    $(".popup_datos_contenido").find("select:not(.opcional) option:selected").each(function(){
        if ($(this).val() == '') { error = true };
    });

    const categoria = $('.popup_guardar_datos').attr("categoria");
    const parametros = JSON.parse( $('.popup_guardar_datos').attr("parametros") );

    if ( error == false ) { 
        guardar_formulario_datos(categoria, parametros);
        refresh_contrato();
        add_check( categoria, parametros.count );
        $(".popup_datos_contenido").empty();
        $(".overlay_datos").css("visibility", "hidden");
        
    };

    
});

// ####################################  FIRST CHARGE ################################################################

preguntas_grupos_cantidad = 10;
let count = 1;

while (count <= preguntas_grupos_cantidad) {
    $(".etapas_wrap").append(`<div id="etapa_${count}" class="preguntas_wrap"></div>`); 
    count += 1;
};


$(".contrato_titulo").html("CONTRATO DE ARRENDAMIENTO - BOLIVIA");
// ############# ETAPA 1 - DE LAS PARTES - ARRENDADOR

$("#etapa_1").html(`

<div class="arrendador_wrap group_question_wrap" count="1" categoria="arrendador">
    <span class="titulo_group">
        <span style="color: #fff">ARRENDADOR #1</span>
        <span class="borrar_elemento_btn"><i class="fa fa-trash-alt"></i></span>
    </span>
    <span class="pregunta_elemento">
        <div class="pregunta_arrendador_group">
            <span class="tipo_wrap_vertical">
                <label for="arrendatario_tipo" class="pregunta_label">Tipo:</label>
                <select name="arrendatario_tipo" class="pregunta_select_short">
                    <option value=""></option>
                    <option value="natural">Natural</option>
                    <option value="juridico">Jurídico</option>
                </select>
            </span>
            <span class="checks_wrap_horizontal">
                <span class="check_wrap">
                    <p class="check_label">Poder <i class="fas fa-info-circle" title="Representa a alguien más con Poder especifico y suficiente notariado"></i></p>
                    <span class="check_element check_poder"><i class="far fa-square"></i></span>
                </span>
                
            </span>

        </div>
        <div class="datos_btn_group">
            <span class="datos_btn" categoria="arrendador">Completar Datos</span>
            <span class="estado_datos fa-stack fa-1x">
                <i class="fas fa-circle fa-stack-1x"></i>
                <i class="fas fa-check-circle fa-stack-1x"></i>
            </span>
        </div>
    </span>
    <hr>
</div>

<div class="agregar_arrendador_btn"><i class="fas fa-plus"></i><i class="fas fa-user"></i>&nbsp&nbspAgregar</div>

`);

// Llamamos a las funciones que ponen el scroll cuando hacemos focus  rellenan el documento 
focus_fill_input("#nombre_agente", ".agente_nombre");
focus_fill_select("#tipo_documento_identidad", ".agente_documento_identidad");
focus_fill_input("#numero_identidad", ".agente_documento_numero");

$("#tipo_documento_identidad").on("change", function(){
    let valor = $("#tipo_documento_identidad option:selected").val();
    if (valor == 'Carnet de Identidad') {
        $(".agente_documento_identidad_abrev").html('C.I.').trigger("change");
    };

    if (valor == 'Pasaporte') {
        $(".agente_documento_identidad_abrev").html('PASS').trigger("change");
    };

    if (valor == '') {
        $(".agente_documento_identidad_abrev").html('______').trigger("change");
    };
});
    
// ############# ETAPA 2

function get_ciudades(departamentoSelected) {
    $.ajax({
    type: "POST",
    url: "process-request-ciudades.php",
    data: { departamentoChoice : departamentoSelected }
    }).done(function(data){
        $("#ciudad_agente").prop('disabled', false).html(data);// se activa el select ciudades y poblado
    });
};

$("#etapa_2").html(`
<div class="arrendatario_wrap group_question_wrap" count="1" categoria="arrendatario">
    <span class="titulo_group">
        <span style="color: #fff">ARRENDATARIO #1</span>
        <span class="borrar_elemento_btn"><i class="fa fa-trash-alt"></i></span>
    </span>
    <span class="pregunta_elemento">
        <div class="pregunta_arrendador_group">
            <span class="tipo_wrap_vertical">
                <label for="arrendatario_tipo" class="pregunta_label">Tipo:</label>
                <select name="arrendatario_tipo" class="pregunta_select_short">
                    <option value=""></option>
                    <option value="natural">Natural</option>
                    <option value="juridico">Jurídico</option>
                </select>
            </span>
            <span class="checks_wrap_horizontal">
                <span class="check_wrap">
                    <p class="check_label">Poder <i class="fas fa-info-circle" title="Representa a alguien más con Poder especifico y suficiente notariado"></i></p>
                    <span class="check_element check_poder"><i class="far fa-square"></i></span>
                </span>
                <span class="check_wrap">
                    <p class="check_label">Terceros <i class="fas fa-info-circle"  title="Toma la responsabilidad pero el Uso y Goce del Inmueble será para un Tercero"></i></p>
                    <span class="check_element check_terceros"><i class="far fa-square"></i></span>
                </span>
            </span>

        </div>
        <div class="datos_btn_group">
            <span class="datos_btn" categoria="arrendatario">Agregar Datos</span>
            <span class="estado_datos fa-stack fa-1x">
                <i class="fas fa-circle fa-stack-1x"></i>
                <i class="fas fa-check-circle fa-stack-1x"></i>
            </span>
        </div>
    </span>
    <hr>
    </div>

    <div class="agregar_arrendatario_btn"><i class="fas fa-plus"></i><i class="fas fa-user"></i>&nbsp&nbspAgregar</div>
`)

$("#departamento_agente").on('change', function(){
    const departamento_selected = $(".departamento_agente option:selected").val();
    get_ciudades(departamento_selected);
    if (departamento_selected == '') {
        $("#ciudad_agente").html("<option value='' selected></option>").trigger('change').prop('disabled', true);
    };
});

focus_fill_input("#direccion_agente", ".agente_domicilio");
focus_fill_select("#departamento_agente", ".agente_departamento");
focus_fill_select("#ciudad_agente", ".agente_ciudad");

// ############# ETAPA 3

$("#etapa_3").html(`
    <span class="pregunta_elemento">
        <label for="registro_agente_autonomo" class="pregunta_label">Entidad y documento con los cuales el AGENTE esta inscrito como profesional autónomo:</label>
        <textarea name="registro_agente_autonomo" id="registro_agente_autonomo" rows="1" class="pregunta_input" oninput="auto_grow(this)" placeholder="Fundempresa, registro n°xxxxxxxx-x"></textarea>
    </span>
`);

focus_fill_input("#registro_agente_autonomo", ".registro_agente_autonomo");

    // ############# ETAPA 3

    $("#etapa_4").html(`
    <span class="pregunta_elemento">
        <label for="email_agente" class="pregunta_label">Correo electrónico personal (no comercial) del AGENTE a contratar:</label>
        <textarea name="email_agente" id="email_agente" rows="1" class="pregunta_input" oninput="auto_grow(this)" placeholder="agente@gmail.com"></textarea>
    </span>
`);

focus_fill_input("#email_agente", ".email_agente");





});
});