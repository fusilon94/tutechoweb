$(document).ready(function(){
  jQuery(function($){


    function load_facturas(agencia_id){

        $.ajax({
            type: "POST",
            url: "process-request-cargar-facturas_bolivia.php",
            data: { 'agencia_id': agencia_id, 'modo': modo },
        }).done(function(data){
            $(".list_container").html(data);
        });

    };

    const agencia_first = $(".agencia_select option:selected").val();
    if (agencia_first !== '') {
        load_facturas(agencia_first);
    };


    $(".agencia_select").on("change", function(){
        const agencia_id = $(".agencia_select option:selected").val();
        if (agencia_id == '') {
            $(".list_container").empty();
        }else{
            load_facturas(agencia_id);
        }
    });

    $(".input_factura_id").on('input', function(){

        //inserting the value of textfield content, you can add if statement to check if the field is null or empty
        var search_param = $(".input_factura_id").val();
        //value of field stored into a variable
        $('.lista_row').css("display", "none");
        //remove item_found class attributed to a td AND search all td to find the one that march the search parameter
        if (search_param == '') {
            $('.lista_col').css({background:'none'});
            $('.lista_row').css("display", "flex");
        }else{
            if ($('.lista_col:contains("' + search_param + '")').html() !== undefined) {
                //if there is any td that has that record... then check for the closest tr and add a class with item_found as value
                $('.lista_col:contains("' + search_param + '")').closest('.lista_row').css('display', 'flex');
                //add some highlight to it.
                $('.lista_col').css({background:'none'});
                $('.lista_col:contains("' + search_param + '")').css({background:'#78def5'});
                //then scroll to the item
                
            };
        };
        
    });
    


    
    $(".popup_cerrar").on("click", function(){
        $(".popup_overlay").css("visibility", "hidden");
    });
    


    $(".list_container").on("click", '.lista_row', function(){

        const factura_id = $(this).attr('id'); 
        const agencia_id = $(".agencia_select option:selected").val();
        
        $.ajax({
            type: "POST",
            url: "process-request-datos-agencia-factura.php",
            data: { 'agencia_id': agencia_id, id_factura: factura_id },
            dataType: 'json'
        }).done(function(datos){
            console.log(datos)
            const datos_factura = datos['datos_factura'];
            console.log(datos_factura)
            $(".popup_content").html(`
                <div class="factura_up">
                    <div class="factura_upper_left">
                        <img src="../../objetos/logotipo2.svg" alt="" class="factura_logo">
                        <span class="razon_social">${datos['razon_social']}</span>
                        <span class="direccion_agencia"><p>${datos['direccion']}<br>${datos['direccion_complemento']}<br>${datos['location_tag']} - ${datos['departamento']} - Bolivia</p></span>
                    </div>
                    
                    <div class="factura_upper_right">
                        <span class="nit_agencia">NIT ${datos['NIT']}</span>
                        <span class="numero_factura">FACTURA N°${datos_factura['numero_factura']}</span>
                        <span class="numero_autorizacion">AUTORIZACION N°${datos_factura['numero_autorizacion']}</span>
                        <span class="original">ORIGINAL</span>
                        <span class="actividad_empresa">Intermediación en la venta, alquiler y anticretico, gestoria y administración de bienes inmuebles para particulares y empresas</span>
                    </div>
                </div>

                <div class="factura_upper_center">
                        <h2 class="class_titulo_factura">FACTURA</h2>
                </div

                <div class="factura_datos_emision">
                    <span class="factura_fecha_emision">${datos['ciudad']}, ${datos['fecha_string']}</span>
                    <span class="factura_nombre_emision">Señor(es): <span class="completar nombre_emision_completar" style="text-transform:uppercase">${datos_factura['nombre_cliente']}</span></span>
                    <span class="factura_nit_emision">N.I.T./C.I.: <span class="completar nit_emision_completar">${datos_factura['nit_cliente']}</span></span>
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
                            <span class="col3_row descuento_porcentage">${datos_factura['descuento']} %</span>
                            <span class="col4_row descuento_total">(-) ${datos['detalle_factura']['sub_total'] * datos_factura['descuento']/100}</span>
                        </span>
                    </div>
                    <div class="cuerpo_total_contenedor">
                        <span class="total_escrito">
                            Son: ${NumeroALetras(datos['detalle_factura']['sub_total'], moneda_string)}
                        </span>
                        <span class="total_monto">
                            <p>TOTAL: </p>
                            <p class="total_monto_num"><span class="total_final">${datos_factura['monto']}</span> ${moneda_symbol} ${moneda_code}</p>
                        </span>
                    </div>
                    <span class="fecha_limite_emision">FECHA LIMITE DE EMISION: ${datos_factura['fecha_limite_emision']}</span>
                    <span class="codigo_control">CODIGO DE CONTROL: ${datos_factura['codigo_control']}</span>
                </div>

                <div id="codigo_qr_container" class="codigo_qr_container"></div>

                <div class="factura_footer">
                    <span class="leyenda_sin">ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS, EL USO ILÍCITO SERÁ SANCIONADO PENALMENTE DE ACUERDO A LEY</span>
                    <span class="leyenda_consumidor">Estan prohibidas las practicas comerciales abusivas, tienes derecho a denunciarlas</span>
                    
                </div>
 
            
            `);


            const {numero_factura, numero_autorizacion, monto, fecha_impresion, nit_cliente, codigo_control} = datos_factura;

            const QR_link = `${datos['NIT']}|${numero_factura}|${numero_autorizacion}|${fecha_impresion}|${monto}|${codigo_control}|${nit_cliente}`;
            new QArt({
                value: QR_link,
                imagePath: '../../objetos/techo_factura.svg',
                filter: 'threshold',
                size: 200,
                version: 10,
                fillType: 'scale_to_fit'
            }).make(document.getElementById('codigo_qr_container'));


        });



        $(".popup_overlay").css("visibility", "unset");

    });

  });
});
