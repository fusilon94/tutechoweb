$(document).ready(function(){
    jQuery(function($){
     //  ############ LLENADO AUTOMATICO DE CONTRATO ##########################

    async function recaudar_datos(agencia_id, id_factura){

        // ### DATOS AGENCIA

        let datos_agencia = await $.ajax({
            type: "POST",
            url: "process-request-datos-agencia-factura.php",
            data: { agencia_id : agencia_id, id_factura : id_factura },
            dataType: 'json'
        }).done(function(data){
            return data;
        });

        return datos_agencia;

    };
    
    recaudar_datos(agencia_id, id_factura).then(function(datos){
    // ################### CONTRATO INICIAL


     $(".contrato_contenedor").html(`
     <div class="factura_up">
        <div class="factura_upper_left">
            <img src="../../objetos/logotipo2.svg" alt="" class="factura_logo">
            <span class="razon_social">${datos['razon_social']}</span>
            <span class="direccion_agencia"><p>${datos['direccion']}<br>${datos['direccion_complemento']}<br>${datos['location_tag']} - ${datos['departamento']} - Bolivia</p></span>
        </div>
        
        <div class="factura_upper_right">
            <span class="nit_agencia">NIT ${datos['NIT']}</span>
            <span class="numero_factura">N°#####</span>
            <span class="numero_autorizacion">N°#################</span>
            <span class="original">ORIGINAL</span>
            <span class="actividad_empresa">Intermediación en la venta, alquiler y anticretico, gestoria y administración de bienes inmuebles para particulares y empresas</span>
        </div>
    </div>

    <div class="factura_upper_center">
            <h2 class="class_titulo_factura">FACTURA</h2>
    </div

    <div class="factura_datos_emision">
        <span class="factura_fecha_emision">${datos['ciudad']}, ${datos['fecha_string']}</span>
        <span class="factura_nombre_emision">Señor(es): <span class="completar nombre_emision_completar" style="text-transform:uppercase">______</span></span>
        <span class="factura_nit_emision">N.I.T./C.I.: <span class="completar nit_emision_completar">______</span></span>
    </div>

    <div class="factura_cuerpo">
        <span class="cuerpo_titulos">
            <span class="col1">CANT.</span>
            <span class="col2">DESCRIPCIÓN</span>
            <span class="col3">P.UNIT.</span>
            <span class="col4">SUB TOTAL</span>
        </span>
        <hr>
        <div class="contendor_objetos">
            <span class="objeto_row">
                <span class="col1_row">${datos['detalle_factura']['cantidad']}</span>
                <span class="col2_row">${datos['detalle_factura']['descripcion']}</span>
                <span class="col3_row">${datos['detalle_factura']['precio_unitario']}</span>
                <span class="col4_row sub_total">${datos['detalle_factura']['sub_total']}</span>
            </span>
            <span class="objeto_row_descuento">
                <span class="col1_row"></span>
                <span class="col2_row_descuento">DESCUENTO</span>
                <span class="col3_row descuento_porcentage">0%</span>
                <span class="col4_row descuento_total">(-) 0</span>
            </span>
        </div>
        <div class="cuerpo_total_contenedor">
            <span class="total_escrito">
                Son: ${NumeroALetras(datos['detalle_factura']['sub_total'], moneda_string)}
            </span>
            <span class="total_monto">
                <p>TOTAL: </p>
                <p class="total_monto_num"><span class="total_final">${datos['detalle_factura']['sub_total']}</span> ${moneda_symbol} ${moneda_code}</p>
            </span>
        </div>
        <span class="fecha_limite_emision">FECHA LIMITE DE EMISION: DD/MM/AAAA</span>
        <span class="codigo_control">CODIGO DE CONTROL: ##-##-##-##-##</span>
    </div>

    <div id="codigo_qr_container" class="codigo_qr_container"></div>

    <div class="factura_footer">
        <span class="leyenda_sin">ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS, EL USO ILÍCITO SERÁ SANCIONADO PENALMENTE DE ACUERDO A LEY</span>
        <span class="leyenda_consumidor">Estan prohibidas las practicas comerciales abusivas, tienes derecho a denunciarlas</span>
        
    </div>
    `);


        $(".contrato_contenedor span.completar").each(function(){
            if (!$(this).hasClass("dato")) {
                $(this).addClass("dato");
            };
        });

        var QR_link = "CODIGO QR DE PRUEBA";

        new QArt({
          value: QR_link,
          imagePath: '../../objetos/techo_factura.svg',
          filter: 'threshold',
          size: 200,
          version: 10,
          fillType: 'scale_to_fit'
        }).make(document.getElementById('codigo_qr_container'));

    });

    
    
    
    // ####################################  FIRST CHARGE ################################################################

    if(acceso == '3' || acceso == '10'){
        preguntas_grupos_cantidad = 1;
    }else if(acceso == '1' || acceso == '11' || acceso == '12'){
        preguntas_grupos_cantidad = 2;
    };
    
    let count = 1;
    
    while (count <= preguntas_grupos_cantidad) {
        $(".etapas_wrap").append(`<div id="etapa_${count}" class="preguntas_wrap"></div>`); 
        count += 1;
    };


    // ############# ETAPA 1

    $(".contrato_titulo").html("FACTURA INTERMEDIACIÓN");
   
    $("#etapa_1").html(`
    <span class="pregunta_elemento">
        <label for="nombre_emision" class="pregunta_label">Nombre del Cliente:</label>
        <textarea name="nombre_emision" id="nombre_emision" rows="1" class="pregunta_input" oninput="auto_grow(this)" style="text-transform:uppercase"></textarea>
    </span>
    <span class="pregunta_elemento">
        <label for="nit_emision" class="pregunta_label">N.I.T o C.I.:</label>
        <textarea name="nit_emision" id="nit_emision" rows="1" class="pregunta_input" oninput="auto_grow(this)"></textarea>
    </span>
    `);
    
    // Llamamos a las funciones que ponen el scroll cuando hacemos focus  rellenan el documento 
    focus_fill_input("#nombre_emision", ".nombre_emision_completar");
    focus_fill_input("#nit_emision", ".nit_emision_completar");
        
    // ############# ETAPA 2

    if(acceso == '1' || acceso == '11' || acceso == '12'){
        $("#etapa_2").html(`
            <span class="pregunta_elemento">
                <label for="descuento_select" class="pregunta_label">Departamento:</label>
                <select name="descuento_select" id="descuento_select" class="pregunta_select descuento_select">
                    <option value="0">0%</option>
                    <option value="0.02">2%</option>
                    <option value="0.05">5%</option>
                    <option value="0.07">7%</option>
                    <option value="0.1">10%</option>
                    <option value="0.12">12%</option>
                    <option value="0.15">15%</option>
                    <option value="0.17">17%</option>
                    <option value="0.2">20%</option>
                    <option value="0.22">22%</option>
                    <option value="0.25">25%</option>
                </select>
            </span>
        `)

        focus_fill_select("#descuento_select", ".descuento_porcentage"); 

        $("#descuento_select").on('change', function(){
            const descuento_factor = parseFloat($(".descuento_select option:selected").val());
            const descuento_text = $(".descuento_select option:selected").text();

            $('.descuento_porcentage').text(descuento_text);

            let precio_sub_total;
            $('.sub_total').each(function(){
                const sub_total = parseFloat($(this).text());
                precio_sub_total =+ sub_total;
            });

            const descuento_total = (precio_sub_total * descuento_factor).toFixed(0);
            $('.descuento_total').text(`(-) ${descuento_total}`);

            const precio_final = precio_sub_total - descuento_total;

            $('.total_final').text(precio_final);

            const precio_final_string = NumeroALetras(precio_final, moneda_string);
            
            $('.total_escrito').text(`Son: ${precio_final_string}`)

        });
    };
    


    // BOTON CONFIRMAR IMPRESION + CONEXION CON IMPUESTOS Y GENERACION DE CODIGOS

    $(".btn_confirmar_impresion").on("click", function(){


        //IMPORTANTE!!!! : en el process request, conectarse con IMPUESTOS, solicitar codigos, y completar tabla factura en la DB
        $.ajax({
            type: "POST",
            url: "##########",
            data: { '############': '' },
            dataType: 'json',
            async: false
        }).done(function(data){

            const {numero_factura, numero_autorizacion, fecha_limite_emision, codigo_control} = data;

            $(".numero_factura").html(`N°${numero_factura}`);
            $(".numero_autorizacion").html(`N°${numero_autorizacion}`);
            $(".fecha_limite_emision").html(`FECHA LIMITE DE EMISION: ${fecha_limite_emision}`);
            $(".codigo_control").html(`CODIGO DE CONTROL: ${codigo_control}`);

            const codigo_QR = `NIT_EMPRESA|${numero_factura}|${numero_autorizacion}|FECHA_ACTUAL|MONTO_FINAL|${codigo_control}|NIT_CONSUMIDOR`;

            new QArt({
                value: codigo_QR,
                imagePath: '../../objetos/techo_factura.svg',
                filter: 'threshold',
                size: 200,
                version: 10,
                fillType: 'scale_to_fit'
            }).make(document.getElementById('codigo_qr_container'))
            
            // QArt.make() tarda 150 ms en ejecutarse, es necesario un timeout antes del print
            setTimeout(function(){
                $(".overlay").css("visibility", "hidden");
                window.print();
            }, 2000);
              

            
        }).error(function(){
            alert('ERROR DE CONEXION CON IMPUESTOS NACIONALES, INTENTE NUEVAMENTE');
        });





        
    });
   
  
    });
  });