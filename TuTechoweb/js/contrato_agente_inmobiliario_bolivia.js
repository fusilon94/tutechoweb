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
    
    recaudar_datos(agente_id).then(function(datos){
    // ################### CONTRATO INICIAL
     $(".contrato_contenedor").html(`
     <p style="text-align: right;">&nbsp;</p>
     <p style="text-align: right;">&nbsp;</p>
     <p style="text-align: right;">&nbsp;</p>
     <p style="text-align: right;">&nbsp;</p>
     <p style="text-align: right;">&nbsp;</p>
     <p style="text-align: right;">&nbsp;</p>
     <h3 style="text-align: center;"><strong>Contrato de Agente Inmobiliario</strong></h3>
     <h3 style="text-align: center;">&nbsp;</h3>
     <h4 style="text-align: center;"><strong>TuTecho Corp</strong></h4>
     <p style="text-align: center;"><strong>&nbsp;</strong></p>
     <p style="text-align: center;"><strong>&nbsp;</strong></p>
     <p style="text-align: right;">&nbsp;</p>
     <p style="text-align: justify;">Conste por el presente contrato privado de prestaci&oacute;n de servicios de Corretaje por comisiones, y/o Asesoramiento en la promoci&oacute;n inmobiliaria, que con el solo reconocimiento de firmas y r&uacute;bricas ser&aacute; elevado a instrumento p&uacute;blico.</p>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;"><strong>LAS PARTES</strong></p>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;"><strong>De una parte,</strong></p>
     <p style="text-align: justify;">Tutecho Inmobiliaria S.A., con NIT ${datos['datos_agencia']['agencia']['NIT']}, y domicilio en Bolivia en:</p>
     <p style="text-align: justify;">${datos['datos_agencia']['agencia']['direccion']}, ${datos['datos_agencia']['agencia']['direccion_complemento']},</p>
     <p style="text-align: justify;">${datos['barrio_agencia']}${datos['datos_agencia']['agencia']['ciudad']}, ${(datos['datos_agencia']['agencia']['departamento']).toUpperCase()} &ndash; BOLIVIA</p>
     <p style="text-align: justify;">Interviene, ${datos['datos_agencia']['agente']['nombre']} ${datos['datos_agencia']['agente']['apellido']} con ${datos['datos_agencia']['agente']['doc_tipo']} n° ${datos['datos_agencia']['agente']['doc_identidad']}, mayor de edad y h&aacute;bil por derecho, de profesi&oacute;n ${(datos['datos_agencia']['agente']['poder'] == 'hombre' ? 'abogado' : 'abogada')} y con domicilio en:</p>
     <p style="text-align: justify;">${datos['datos_agencia']['agente']['domicilio']}</p>
     <p style="text-align: justify;">${datos['datos_agencia']['agencia']['ciudad']}, ${(datos['datos_agencia']['agencia']['departamento']).toUpperCase()} &ndash; BOLIVIA</p>
     <p style="text-align: justify;">quien comparece en nombre y representaci&oacute;n de Tutecho Inmobiliaria en calidad de: Jefe de Agencia &ndash; Sucursal ${datos['datos_agencia']['agencia']['location_tag']}, con poder bastante y suficiente N°${datos['datos_agencia']['agente']['poder']}, otorgado por la Notar&iacute;a de Fe P&uacute;blica No. ${datos['datos_agencia']['agente']['notaria']} a cargo del Dr. ${datos['datos_agencia']['agente']['notario_nombre']}, y quien en lo sucesivo y al efecto de su identificaci&oacute;n se denominar&aacute; como la AGENCIA.</p>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;"><strong>Y de otra parte,</strong></p>
     <p style="text-align: justify;"><span class="agente_nombre">______</span> con <span class="agente_documento_identidad">______</span> N° <span class="agente_documento_numero">______</span>, mayor de edad y h&aacute;bil por derecho, con domicilio en:</p>
     <p style="text-align: justify;"><span class="agente_domicilio">______</span></p>
     <p style="text-align: justify;"><span class="agente_ciudad">______</span>, <span class="agente_departamento">______</span> &ndash; BOLIVIA</p>
     <p style="text-align: justify;">Quien comparece en su propio nombre y derecho, y quien en lo sucesivo y al efecto de su identificaci&oacute;n se denominar&aacute; como el AGENTE.</p>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;">Las Partes, en calidad con la que act&uacute;an, y reconoci&eacute;ndose capacidad jur&iacute;dica para contratar, obligarse y en especial para el otorgamiento del presente CONTRATO DE AGENTE INMOBILIARIO, est&aacute;n sujetas a las siguientes cl&aacute;usulas:</p>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;"><strong>PRIMERA. FACULTADES DE LAS PARTES</strong></p>
     <ul style="text-align: justify;">
     <li>El AGENTE es un profesional independiente que se encarga de manera continuada o estable de promover, negociar o concretar operaciones mercantiles por cuenta y en nombre ajenos, y se encuentra inscrito como tal en <span class="registro_agente_autonomo">______</span></li>
     <li>La AGENCIA se dedica a la siguiente actividad:</li>
     </ul>
     <p style="text-align: justify;"><em>Intermediaci&oacute;n en la compra, venta y alquiler de propiedades tales como casas, departamentos, oficinal, locales comerciales y terrenos. Adem&aacute;s de prestar servicios diversos en el campo de la Gestor&iacute;a Legal.</em></p>
     <p style="text-align: justify;"><strong>&nbsp;</strong></p>
     <p style="text-align: justify;"><strong>SEGUNDA. OBJETO</strong></p>
     <p style="text-align: justify;">Los servicios de corretaje por comisi&oacute;n y asesor&iacute;a del AGENTE son de intermediaci&oacute;n entre la AGENCIA y las empresas o clientes y cuya finalidad es la promoci&oacute;n, de manera continuada y estable, de actos u operaciones de comercio inmobiliario dentro de los l&iacute;mites territoriales establecidos en la cl&aacute;usula referida a la Zona Geogr&aacute;fica de actividad. Estos servicios ser&aacute;n coordinados entre el AGENTE y la AGENCIA, comprendiendo principalmente, en forma enunciativa y no limitativa los siguientes objetivos:</p>
     <ul style="text-align: justify;">
     <li>Visitar y/o contactar a posibles clientes, en sus domicilios, oficinas o lugares de trabajo.</li>
     <li>Promover la comercializaci&oacute;n inmobiliaria, en el territorio designado, de los servicios de la AGENCIA tal y como son especificados en los manuales y estatutos de la AGENCIA. Dichos servicios de la AGENCIA son susceptibles de variaci&oacute;n, limitaci&oacute;n, cambio, suspensi&oacute;n temporal o definitiva, a tenor de las necesidades, situaci&oacute;n, evoluci&oacute;n o fluctuaciones del mercado, a criterio de la direcci&oacute;n comercial de la AGENCIA.</li>
     <li>Desarrollar su actividad bajo las pautas marcadas por la AGENCIA, pero siempre manteniendo la debida independencia para realizar su tarea.</li>
     <li>Recibir reclamaciones por parte de los clientes en cuanto a defectos o vicios de calidad del servicio bajo promoci&oacute;n.</li>
     </ul>
     <p style="text-align: justify;"><strong>&nbsp;</strong></p>
     <p style="text-align: justify;">El AGENTE podr&aacute; negociar, en nombre de la AGENCIA, operaciones de compraventa, si bien no tendr&aacute; la facultad de concluir contratos en nombre de la AGENCIA ni de obligarle jur&iacute;dicamente de cualquier otra forma. Se limitar&aacute; a informar a los clientes de las condiciones de servicio establecidas por la AGENCIA&nbsp; y mediar entre este, &nbsp;los clientes y los potenciales clientes.</p>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;"><strong>TERCERA. NATURALEZA DEL CONTRATO</strong></p>
     <p style="text-align: justify;">La relaci&oacute;n de las partes firmantes en este contrato tiene, exclusivamente, car&aacute;cter mercantil, no existiendo v&iacute;nculo laboral alguno entre el AGENTE y la AGENCIA, o, en su caso, el personal trabajador de aquel.</p>
     <p style="text-align: justify;">El AGENTE conforme a la naturaleza del contrato, prestar&aacute; sus servicios de corretaje por comisi&oacute;n a la AGENCIA,&nbsp; en forma independiente y por cuenta propia, sin&nbsp; relaci&oacute;n de dependencia, aclar&aacute;ndose que el AGENTE no tiene un horario que cumplir, ni firmar libros de asistencia y el contrato es por objetivo cumplido.</p>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;"><strong>CUARTA. RIESGO DE LAS OPERACIONES</strong></p>
     <p style="text-align: justify;">El riesgo y ventura de las operaciones promovidas por el AGENTE ser&aacute;n, en todo caso, asumidas por este mismo, el AGENTE.</p>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;"><strong>QUINTA. INDEPENDENCIA DEL AGENTE</strong></p>
     <p style="text-align: justify;">El AGENTE actuar&aacute; como intermediario independiente respecto de la AGENCIA, en consecuencia, dispondr&aacute; de plena independencia para determinar los criterios conforme a los que organiza y dirige sus actividades y la organizaci&oacute;n del tiempo que dedica a las mismas, siempre y cuando no cree conflicto con funciones precisas que se le haya encomendado.</p>
     <p style="text-align: justify;">No obstante, la independencia y autonom&iacute;a del AGENTE en el desarrollo de su actividad profesional se establecen sin perjuicio de que, en todo caso, deber&aacute; respetar las instrucciones generales y razonables especificadas a detalle en los manuales y estatutos internos de la AGENCIA.</p>
     <p style="text-align: justify;">Ambas Partes acuerdan que el AGENTE &nbsp;no podr&aacute; desarrollar su actividad profesional por cuenta de terceros distintos de la AGENCIA (subcontratar personal de apoyo).</p>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;"><strong>SEXTA. ZONA GEOGR&Aacute;FICA DE ACTIVIDAD</strong></p>
     <p style="text-align: justify;">El AGENTE promover&aacute; los actos u operaciones de comercio inmobiliario en nombre de la AGENCIA en la siguiente Zona Geogr&aacute;fica de actividad o territorio:</p>
     <p style="text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; La urbe de ${datos['datos_agencia']['agencia']['ciudad']} &ndash; BOLIVIA</p>
     <p style="text-align: justify;">En todo caso, el AGENTE se obliga a no promover ning&uacute;n acto u operaci&oacute;n de comercio inmobiliario en nombre de la AGENCIA fuera de dicha Zona Geogr&aacute;fica de actividad o territorio, ni a personas f&iacute;sicas o jur&iacute;dicas de la Zona Geogr&aacute;fica de Actividad que, por el motivo de actividad que desarrollan, pudieran comerciar con los servicios fuera de la Zona Geogr&aacute;fica de Actividad.</p>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;"><strong>S&Eacute;PTIMA. PLAZO DEL CONTRATO</strong></p>
     <p style="text-align: justify;">Mediante el presente Contrato, el AGENTE promover&aacute;, de manera continuada y estable, actos u operaciones de comercio inmobiliario por cuenta y en nombre de la AGENCIA por un periodo de prueba de tres meses a partir de la fecha del presente Contrato.</p>
     <p style="text-align: justify;">Una vez finalizado el periodo de prueba, si la AGENCIA lo considera pertinente y adecuado, la duraci&oacute;n de este Contrato podr&aacute; ser extendida sin necesidad de suscribir otro instrumento, convirti&eacute;ndose este contrato a uno de duraci&oacute;n indefinida.</p>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;"><strong>OCTAVA. OBJETIVOS M&Iacute;NIMOS DE DESEMPE&Ntilde;O</strong></p>
     <p style="text-align: justify;"><u>Durante el periodo de prueba de tres meses:</u></p>
     <p style="text-align: justify;">El AGENTE se compromete, a alcanzar los objetivos m&iacute;nimos de desempe&ntilde;o siguientes:</p>
     <p style="text-align: justify;">Concretar la venta de un bien inmueble (casa, departamento, oficina, local o terreno), o bien concretar el anticr&eacute;tico de 2 bienes inmuebles, o bien concretar 2 contratos de alquiler, o bien generar en conjunto mil d&oacute;lares americanos en ganancia neta para la AGENCIA (sin contar la comisi&oacute;n del AGENTE).</p>
     <p style="text-align: justify;">Si el AGENTE no alcanzara el m&iacute;nimo establecido, la AGENCIA podr&aacute;, si as&iacute; lo decide, rescindir el Contrato al terminar el periodo de prueba.</p>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;"><u>Despu&eacute;s del periodo de prueba:</u></p>
     <p style="text-align: justify;">El AGENTE se compromete, cada tres meses, a alcanzar los objetivos m&iacute;nimos de desempe&ntilde;o siguientes:</p>
     <p style="text-align: justify;">Concretar la venta de dos bienes inmuebles (casas, departamentos, oficinas, locales o terrenos), o bien concretar el anticr&eacute;tico de 3 bienes inmuebles, o bien concretar 5 contratos de alquiler, o bien generar en conjunto tres mil d&oacute;lares americanos en ganancia neta para la AGENCIA (sin contar la comisi&oacute;n del AGENTE).</p>
     <p style="text-align: justify;">Si, en una primera instancia, el AGENTE no alcanzara el desempe&ntilde;o m&iacute;nimo establecido, la AGENCIA podr&aacute;, si as&iacute; lo decide, entregar un aviso de advertencia de desempe&ntilde;o al AGENTE. Si, en una segunda instancia, consecutiva, el AGENTE nuevamente no alcanzara el desempe&ntilde;o m&iacute;nimo establecido y ya hubiera recibido un aviso de advertencia de desempe&ntilde;o, la AGENCIA podr&aacute;, si as&iacute; lo decide, rescindir el contrato con efecto inmediato.</p>
     <p style="text-align: justify;">Los objetivos m&iacute;nimos de desempe&ntilde;o establecidos en esta cl&aacute;usula est&aacute;n sujetos a cambios, los cuales deber&aacute;n ser comunicados por escrito al AGENTE en un tiempo no menor a dos meses antes de ser establecidos oficialmente.</p>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;"><strong>NOVENA. REMUNERACI&Oacute;N DEL AGENTE</strong></p>
     <p style="text-align: justify;">Como contraprestaci&oacute;n econ&oacute;mica al ejercicio de su actividad profesional, el AGENTE tendr&aacute; derecho a percibir una remuneraci&oacute;n sobre la culminaci&oacute;n de compraventa, alquiler o anticr&eacute;tico de bienes inmuebles promocionados y/o gestionados por su persona y en beneficio de la AGENCIA durante la vigencia del Contrato y dentro de la Zona Geogr&aacute;fica de actividad.</p>
     <p style="text-align: justify;">La AGENCIA satisfar&aacute; al AGENTE en concepto de comisi&oacute;n, un 50 % (cincuenta por ciento) sobre la ganancia bruta que genere a favor de la AGENCIA. Este porcentaje de comisi&oacute;n sobre la ganancia bruta generada por el AGENTE a favor de la AGENCIA est&aacute; sujeto a posibles cambios siempre y cuando estos sean comunicados por escrito al AGENTE en un tiempo no menor a dos meses antes de ser establecidos oficialmente. Tambi&eacute;n est&aacute; sujeto a cambios por circunstancias espec&iacute;ficas que se encuentran detalladas en los manuales y estatutos de la AGENCIA, los cuales el AGENTE afirma tener conocimiento.</p>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;"><strong>D&Eacute;CIMA. FORMA DE PAGO</strong></p>
     <p style="text-align: justify;">Las comisiones se considerar&aacute;n devengadas en el momento en que la AGENCIA hubiera ejecutado el acto u operaci&oacute;n de comercio y recibido el pago por parte del o los clientes.</p>
     <p style="text-align: justify;">El abono de las comisiones a las que tenga derecho el AGENTE se realizar&aacute; en los siguientes 7 d&iacute;as h&aacute;biles a la ejecuci&oacute;n del acto u operaci&oacute;n de comercio por la AGENCIA, contra la presentaci&oacute;n de las correspondientes facturas por parte del AGENTE.</p>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;"><strong>DECIMOPRIMERA. OBLIGACIONES DEL AGENTE</strong></p>
     <p style="text-align: justify;">Son deberes y obligaciones del AGENTE:</p>
     <ul style="text-align: justify;">
     <li>Comprobar fehacientemente la identidad y capacidad de los clientes.</li>
     <li>Proponer los negocios con claridad y exactitud, absteni&eacute;ndose de hacer ofertas falsas que puedan inducir a error a los interesados &oacute; a la AGENCIA.</li>
     <li>Conservar los documentos que hubieran servido como base a su intermediaci&oacute;n.</li>
     <li>Guardar secreto de las negociaciones que se le encargue, salvo orden judicial.</li>
     <li>Promover las actividades y operaciones de comercio relativas a este Contrato con buena fe y con la mayor lealtad a la AGENCIA y a sus intereses.</li>
     <li>Ejecutar sus obligaciones con la mayor diligencia y en concordancia con los intereses de la AGENCIA, que a su vez reconoce conocer.</li>
     <li>Realizar un seguimiento continuo de clientes y clientes potenciales, realizar ofertas a los mismos, y realizar el seguimiento continuado de las ofertas en curso. Asimismo, se compromete a la obtenci&oacute;n de pedidos de servicio, el seguimiento de los mismos y la resoluci&oacute;n de las incidencias en relaci&oacute;n con los mismos, tales como la soluci&oacute;n de las dificultades de tramitaci&oacute;n y aclaraci&oacute;n de incidencias t&eacute;cnicas y/o comerciales.</li>
     <li>Asistir a la AGENCIA en los cobros, y, si procediera, la colaboraci&oacute;n con los Clientes en la obtenci&oacute;n de los servicios.</li>
     <li>El AGENTE reconoce saber que uno de los principales objetivos por los que se establece este Contrato es para expandir la actividad e incrementar la presencia de la AGENCIA en el territorio correspondiente a la Zona Geogr&aacute;fica de actividad, por tanto, se compromete a realizar sus mejores esfuerzos para incrementar el volumen de contrataci&oacute;n de servicios de la AGENCIA. En consecuencia, para dar cumplimiento a esta obligaci&oacute;n, ambas Partes acordar&aacute;n objetivos de desempe&ntilde;o, que como ya mencionado, pueden diferir de aquellos mencionados en la Cl&aacute;usula S&eacute;ptima de este Contrato, y cuyo cumplimiento deber&aacute; ser revisado cada trimestre.</li>
     <li>Mantener actualizados todos los datos e informaciones de clientes, contactos, nuevas tendencias, incidencias y todo otro aquel relativo a la actividad realizada en la Zona Geogr&aacute;fica de actividad. En consecuencia, se compromete a informar de forma inmediata a la AGENCIA de todas las solicitudes de servicio que reciba de clientes y potenciales clientes.</li>
     <li>Recibir en nombre de la AGENCIA toda clase de reclamaciones de terceros o clientes sobre defectos o vicios de la calidad de los servicios como consecuencia de las operaciones promovidas, aunque no se hubiesen concluido.</li>
     <li>Dar uso correcto de todas las herramientas de trabajo, f&iacute;sicas y virtuales, que le sean otorgadas por la AGENCIA durante el periodo de validez de este Contrato. El AGENTE se compromete igualmente a no permitir el acceso a esas herramientas a terceros que no cuenten expresamente con autorizaci&oacute;n pertinente y otorgada por la AGENCIA misma.</li>
     </ul>
     <p>&nbsp;</p>
     <p style="text-align: justify;"><strong>DECIMOSEGUNDA. OBLIGACIONES DE LA AGENCIA</strong></p>
     <p style="text-align: justify;">La AGENCIA se obliga a actuar lealmente y de buena fe en sus relaciones con el AGENTE y conforme a los usos y costumbres profesionales propios de su sector de actividad.</p>
     <p style="text-align: justify;">La AGENCIA se obliga al pago, en tiempo y forma, de las comisiones de acuerdo con lo previsto en este Contrato. Asimismo, se obliga a poner a disposici&oacute;n del AGENTE las herramientas f&iacute;sicas y virtuales necesarias para el ejercicio de la promoci&oacute;n de los servicios, as&iacute; como el soporte t&eacute;cnico que fuere necesario para el funcionamiento, y por ende, para la comercializaci&oacute;n de los servicios. Igualmente, la AGENCIA se encargar&aacute; de comunicar al AGENTE los precios o tarifas de los servicios vigentes en cada momento, notific&aacute;ndole con antelaci&oacute;n suficiente las modificaciones que se produzcan en los mismos.</p>
     <p style="text-align: justify;">El AGENTE informar&aacute; a la AGENCIA de cualquier pedido y/o solicitud de servicio obtenido. La AGENCIA, podr&aacute; rechazar los pedidos que le haya tramitado el AGENTE, si bien el rechazo continuado de pedidos se considerar&aacute; contrario a la buena fe y ser&aacute; causa de incumplimiento del Contrato por parte de la AGENCIA. En todo caso, la AGENCIA informar&aacute; al AGENTE en un plazo m&aacute;ximo de quince d&iacute;as h&aacute;biles, desde la recepci&oacute;n de los pedidos y/o solicitudes de servicios que le transmita, si los acepta o los rechaza; entendi&eacute;ndose aceptado si, transcurrido el citado plazo, la AGENCIA no hubiese contestado. De no ser aceptados por la AGENCIA, el AGENTE no tendr&aacute; derecho de reclamo alguno.</p>
     <p style="text-align: justify;">Con el objetivo de asistir al AGENTE de la forma m&aacute;s eficaz posible, la AGENCIA se obliga a comunicar al AGENTE aquellos contactos directos que clientes o colaboradores de la Zona Geogr&aacute;fica de actividad establezcan con la AGENCIA, proporcion&aacute;ndole asimismo los datos e informaciones correspondientes a las ofertas y/o servicios realizados y solicitudes de servicios recibidas directamente por la AGENCIA en la Zona Geogr&aacute;fica de actividad del AGENTE.</p>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;"><strong>DECIMOTERCERA. GASTOS DE REPRESENTACI&Oacute;N Y DESPLAZAMIENTO</strong></p>
     <p style="text-align: justify;">Todos los gastos desembolsados por el AGENTE en el ejercicio de su actividad profesional, y particularmente aquellos relativos a los gastos de representaci&oacute;n y desplazamiento, ser&aacute;n a cargo de &eacute;ste y se considerar&aacute;n cubiertos por la remuneraci&oacute;n que le pudiera corresponder en virtud de lo expuesto en la cl&aacute;usula relativa a la Remuneraci&oacute;n en el presente Contrato.</p>
     <p style="text-align: justify;">No obstante, en el caso de que la AGENCIA solicitase al AGENTE desplazarse para el desarrollo de una acci&oacute;n o actividad concreta (por ejemplo, reunirse con un cliente en particular, en una localizaci&oacute;n alejada de la Zona Geogr&aacute;fica de actividad) y por ello el AGENTE incurriese en gastos de representaci&oacute;n y/o de desplazamiento extraordinarias no previstas dentro del desarrollo normal de su actividad, y por tanto no cubiertas de la forma especificada en el p&aacute;rrafo anterior, la AGENCIA se har&aacute; cargo de los gastos efectivamente incurridos de acuerdo con los recibos que el AGENTE deber&aacute; adjuntar a la Nota de Gastos correspondiente, en la que se especificar&aacute; la misi&oacute;n y acciones realizadas por el AGENTE.</p>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;"><strong>DECIMOCUARTA. SEGUIMIENTO DE LA ACTIVIDAD</strong></p>
     <p style="text-align: justify;">El AGENTE se compromete, a fin de facilitar el seguimiento de la ejecuci&oacute;n del Contrato, a remitir peri&oacute;dicamente a la AGENCIA informaci&oacute;n y/o documentaci&oacute;n sobre el estado de las actividades desarrolladas en ejecuci&oacute;n del presente Contrato. En particular, el AGENTE preparar&aacute; informes en los que se detallar&aacute;n las visitas y/o reuniones con clientes actuales y/o potenciales, los resultados alcanzados y otros datos relevantes para el desarrollo de un plan de actuaci&oacute;n.</p>
     <p style="text-align: justify;">No obstante, la AGENCIA se reserva la facultad de exigir al AGENTE otra informaci&oacute;n o documentaci&oacute;n adicional que pudiera necesitar para poder realizar un correcto seguimiento de las actividades y/o acciones comerciales que el AGENTE pudiera estar desarrollando en virtud del presente Contrato.</p>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;"><strong>DECIMOQUINTA. EXCLUSIVIDAD Y NO COMPETENCIA</strong></p>
     <p style="text-align: justify;">El AGENTE, durante la vigencia del presente Contrato, no representar&aacute; a otras agencias inmobiliarias competidoras a la AGENCIA. En consecuencia, el AGENTE no participar&aacute; de actividades profesionales que tengan relaci&oacute;n con servicios que compitan con aquellos objetos del presente Contrato; as&iacute;, no promover&aacute; la comercializaci&oacute;n (por cuenta propia o ajena) de ning&uacute;n servicio id&eacute;ntico o similar a los de la AGENCIA. Se entiende que ser&aacute;n similares a los de la AGENCIA aquellos dirigidos al mismo segmento de mercado y destinado al mismo fin.</p>
     <p style="text-align: justify;">La obligaci&oacute;n de no competencia a la AGENCIA se aplicar&aacute; durante el per&iacute;odo de duraci&oacute;n del contrato y persistir&aacute; durante un per&iacute;odo de dos a&ntilde;os tras la extinci&oacute;n del Contrato salvo que el Contrato no alcanzase la duraci&oacute;n de dos a&ntilde;os, en cuyo caso dicho periodo de limitaci&oacute;n de competencia post-contractual ser&aacute; de un a&ntilde;o.</p>
     <p style="text-align: justify;">La AGENCIA guarda para s&iacute; la facultad de intervenir y negociar directamente, sin intermediaci&oacute;n del AGENTE, con clientes y potenciales clientes situados en la Zona Geogr&aacute;fica de actividad.</p>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;"><strong>DECIMOSEXTA. EL DEBER DE SECRETO Y CONFIDENCIALIDAD</strong></p>
     <p style="text-align: justify;">Las Partes se comprometen a que el desarrollo de este Contrato se rija en la m&aacute;s absoluta confidencialidad, respetando el deber de diligencia y secreto profesional. As&iacute;, ambas Partes se obligan a no revelar ninguna informaci&oacute;n relativa a este Contrato, ya sea relativa a las negociaciones, transacciones o cualquier asunto de las Partes, ni el contenido ni existencia del Contrato aqu&iacute; suscrito, ni sobre las actividades que se vayan a desarrollar con respecto a servicios relativos a este Contrato.</p>
     <p style="text-align: justify;">Particularmente, el AGENTE se compromete a no revelar a terceros durante la vigencia del presente Contrato, ni despu&eacute;s de su terminaci&oacute;n, informaci&oacute;n de car&aacute;cter t&eacute;cnico o comercial (Informaci&oacute;n Confidencial), incluyendo t&eacute;cnicas, procesos, conocimientos, clientes, c&oacute;digos de acceso, m&eacute;todos de venta o datos, precios, ni utilizar dicha informaci&oacute;n para prop&oacute;sitos diferentes a los establecidos en este Contrato. En consecuencia, no podr&aacute; facilitar a terceros o utilizar para su propio beneficio la informaci&oacute;n que obtenga en el ejercicio de las actividades profesionales que desarrolla por el presente Contrato.</p>
     <p style="text-align: justify;">As&iacute;, el AGENTE se compromete expresamente a no realizar copias, grabar, reproducir, manipular, revelar a terceros, o poner a disposici&oacute;n de estos la informaci&oacute;n o documentaci&oacute;n (Informaci&oacute;n Confidencial) que pueda recibir directa o indirectamente de la AGENCIA e, igualmente, a la finalizaci&oacute;n del Contrato se obliga frente a la AGENCIA a devolverle o, en &uacute;ltima instancia, a destruir dichas informaciones y datos que se hallasen en su poder.</p>
     <p style="text-align: justify;">Las obligaciones del AGENTE emanadas de esta cl&aacute;usula permanecer&aacute;n en vigor durante la vigencia del Contrato y durante un periodo de dos a&ntilde;os siguientes a su finalizaci&oacute;n, excepto para aquellas informaciones que llegaran al dominio p&uacute;blico no mediando culpa o negligencia del AGENTE.</p>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;"><strong>DECIMOS&Eacute;PTIMA. PROTECCI&Oacute;N DE DATOS</strong></p>
     <p style="text-align: justify;">Las Partes son conscientes de que mediante la firma de este Contrato consienten que sus datos personales recogidos en el presente Contrato, as&iacute; como aquellos que se pudiesen recoger en el futuro podr&aacute;n ser eventualmente utilizados para fines de gesti&oacute;n administrativa y/o comercial.</p>
     <p style="text-align: justify;">En todo caso, las Partes se comprometen a que estos datos personales no ser&aacute;n comunicados en ning&uacute;n caso a terceros, aunque, si se diese el caso de que fuera a realizarse alg&uacute;n tipo comunicaci&oacute;n de datos personales, se comprometen siempre y de forma previa, a solicitar el consentimiento expreso, informado, e inequ&iacute;voco de la Parte que es titular de dichos datos de car&aacute;cter personal.</p>
     <p style="text-align: justify;">Asimismo, el AGENTE se compromete a adoptar las medidas t&eacute;cnicas y/u organizativas necesarias para proteger los datos de car&aacute;cter personal a los que tenga acceso y a evitar su alteraci&oacute;n, p&eacute;rdida, tratamiento y acceso no autorizado.</p>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;"><strong>DECIMOCTAVA. PROPIEDAD INTELECTUAL</strong></p>
     <p style="text-align: justify;">El AGENTE reconoce por medio del presente Contrato que las marcas, logotipos, nombres y tantos otros derechos de propiedad intelectual e industrial que conciernen a los servicios objeto de este Contrato, se encuentran debidamente registrados por la AGENCIA. As&iacute;, el AGENTE se obliga a no registrar ninguna marca, nombre o logotipo igual o similar a los pertenecientes a la AGENCIA ni dentro ni fuera de su Zona Geogr&aacute;fica de actividad.</p>
     <p style="text-align: justify;">Asimismo, el AGENTE se obliga a no hacer uso de estas marcas, logotipos, nombres y tantos otros derechos de propiedad intelectual e industrial que conciernen a los servicios objeto de este Contrato, para fines distintos a los del cumplimiento del Contrato.</p>
     <p style="text-align: justify;">Igualmente, el AGENTE se compromete a notificar a la AGENCIA, tan pronto como tenga conocimiento de ello, de cualquier violaci&oacute;n o uso indebido de marcas, logotipos, nombres y tantos otros derechos de propiedad intelectual e industrial que conciernen a los productos y/o servicios objeto de este Contrato, con el fin de que la AGENCIA pueda iniciar los procedimientos legales que le corresponden.</p>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;"><strong>DECIMONOVENA. DECLARACI&Oacute;N DE NO CONFLICTO DE INTERESES NI&nbsp; VINCULOS DE FAMILIARIDAD</strong></p>
     <p style="text-align: justify;">Las Partes declaran que no existe conflicto de intereses entre ellas al momento de la firma de este Contrato.</p>
     <p style="text-align: justify;">Asimismo, el AGENTE declara no tener v&iacute;nculos de familiaridad con ning&uacute;n empleado o agente inmobiliario de la AGENCIA, dentro de una misma sucursal de la AGENCIA en una Zona Geogr&aacute;fica de actividad. Como aclaraci&oacute;n, los v&iacute;nculos de familiaridad que se toman en cuenta son:</p>
     <ul style="text-align: justify;">
     <li>Consanguinidad hasta el segundo grado. Es decir, hijos, padres, hermanos, t&iacute;os, primos, sobrinos, abuelos y nietos.</li>
     <li>De matrimonio.</li>
     <li>De afinidad hasta el segundo grado. Es decir, suegros, cu&ntilde;ados, yernos o nueras.</li>
     <li>Primero civil. Es decir, hijos y padres adoptivo</li>
     </ul>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;"><strong>VIG&Eacute;SIMA. CAUSAS DE RESOLUCION</strong></p>
     <p style="text-align: justify;">Las Partes se comprometen a cumplir las obligaciones que emanan de este Contrato para cada una de ellas en los t&eacute;rminos y condiciones establecidos a lo largo del mismo.</p>
     <p style="text-align: justify;">En todo caso, el Contrato podr&aacute; ser resuelto por simple denuncia unilateral de cualquiera de las Partes, sin necesidad de invocar causa alguna, mediante preaviso expreso y por escrito. El plazo de preaviso ser&aacute; de un mes por cada a&ntilde;o que haya estado vigente el Contrato, con un m&aacute;ximo de tres meses. Si el Contrato hubiera estado vigente por tiempo inferior a un a&ntilde;o, el plazo de preaviso ser&aacute; de un mes.</p>
     <p style="text-align: justify;">El incumplimiento de las obligaciones legales o contractuales, as&iacute; como la insolvencia definitiva o provisional, la suspensi&oacute;n de pagos, la quiebra y/o el acuerdo de liquidaci&oacute;n de cualquiera de las Partes, dar&aacute; derecho a la otra Parte a rescindir el Contrato sin preaviso, si bien se notificar&aacute; a la otra Parte la causa de extinci&oacute;n expresamente y por escrito. Cuando el incumplimiento contractual fuere la falta de pagos por parte de la AGENCIA dar&aacute; derecho al AGENTE a rescindir el Contrato y, si lo estimara oportuno, a proceder a su reclamaci&oacute;n conforme a la Ley.</p>
     <p style="text-align: justify;">En todo caso, en el supuesto de que alguna de las Partes incumpliera alguna de las obligaciones del Contrato, o las cumpliera de forma defectuosa, y este incumplimiento o cumplimiento defectuoso fuera subsanable, la Parte que a su vez s&iacute; hubiera cumplido con las suyas podr&aacute;, previamente al ejercicio de su derecho de rescisi&oacute;n del contrato, optar por exigir expresamente y por escrito dicho cumplimiento o subsanaci&oacute;n a la otra Parte.</p>
     <p style="text-align: justify;">El AGENTE siendo una persona f&iacute;sica, el Contrato tambi&eacute;n ser&aacute; resuelto por causa de su fallecimiento, o incapacidad o cualquier otra causa que le imposibilite cumplir con la ejecuci&oacute;n del Contrato con la calidad y continuidad a que se compromete en virtud del mismo, con independencia de cualquier otro incumplimiento contractual que pudiera producirse.</p>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;"><strong>VIGESIMOPRIMERA. RESPONSABILIDAD E INDEMNIZACI&Oacute;N POR DA&Ntilde;OS Y PERJUICIOS</strong></p>
     <p style="text-align: justify;">Incurrir&aacute; en responsabilidad cualquiera de las Partes que act&uacute;e de forma negligente o culposa en el cumplimiento de las obligaciones establecidas en el presente Contrato y ocasionare con ello un da&ntilde;o o perjuicio a la otra Parte. La Parte que tenga que afrontar cualquier tipo de da&ntilde;o o perjuicio en virtud de la actuaci&oacute;n de la otra Parte podr&aacute; reclamar la indemnizaci&oacute;n por los da&ntilde;os y perjuicios ocasionados.</p>
     <p style="text-align: justify;">El AGENTE realizar&aacute; las actividades objeto de este Contrato con la buena fe y lealtad debida y de acuerdo a los intereses de la AGENCIA, comprometi&eacute;ndose a asumir la responsabilidad por los errores, defectos o demoras producidas en su ejecuci&oacute;n, o su incorrecta ejecuci&oacute;n o de su no ejecuci&oacute;n. No obstante, el AGENTE no ser&aacute; responsable de los errores, defectos o demoras producidas en la ejecuci&oacute;n, o la incorrecta ejecuci&oacute;n o de la no ejecuci&oacute;n del Contrato, cuando esto emane de la omisi&oacute;n o falseamiento de cualquier informaci&oacute;n, documento o dato facilitado por la AGENCIA; el AGENTE tampoco estar&aacute; obligado a verificar la autenticidad y aptitud de dichos datos o informaciones facilitadas por la AGENCIA.</p>
     <p style="text-align: justify;">El AGENTE no tendr&aacute; derecho a la indemnizaci&oacute;n por da&ntilde;os y perjuicios cuando:</p>
     <ul style="text-align: justify;">
     <li>Hubiese incurrido en un incumplimiento de las obligaciones legales o contractuales; o</li>
     <li>Hubiese denunciado el contrato, salvo que la denuncia tuviera como causa circunstancias imputables a la AGENCIA, o se fundara en la edad, la invalidez o la enfermedad del AGENTE y no pudiera exig&iacute;rsele razonablemente la continuidad de sus actividades; o</li>
     <li>Hubiese cedido, con el consentimiento de la AGENCIA, a un tercero los derechos y las obligaciones de que era titulas en virtud del Contrato.</li>
     </ul>
     <p>&nbsp;</p>
     <p style="text-align: justify;"><strong>VIGESIMOSEGUNDA</strong><strong>: SOLUCION DE DIVERGENCIAS</strong></p>
     <p style="text-align: justify;">Las partes sin que medie ning&uacute;n vicio del consentimiento, dolo, error&nbsp; o lesi&oacute;n y teniendo el presente contrato naturaleza de car&aacute;cter civil acuerdan que cualquier divergencia que surja en la interpretaci&oacute;n de cualquiera de las cl&aacute;usulas del presente documento, se someter&aacute; a lo previsto por la Ley 1770 de Arbitraje y Conciliaci&oacute;n, constituyendo la presente cl&aacute;usula un compromiso asumido por las partes, con validez de cl&aacute;usula compromisoria de acuerdo a lo establecido por el Art. 1479 del C&oacute;digo de Comercio y Art&iacute;culos 10 y siguientes de la Ley 1770 de Arbitraje y Conciliaci&oacute;n.</p>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;"><strong>VIGESIMOTERCERA. DOMICILIO PARA NOTIFICACIONES</strong></p>
     <p style="text-align: justify;">Para realizar cualquier notificaci&oacute;n entre las Partes que tenga como origen el presente Contrato, estas acuerdan que su domicilio a efectos de las mismas sean las direcciones indicadas al principio de este Contrato. Para que una notificaci&oacute;n entre las Partes sea efectuada de forma v&aacute;lida, deber&aacute; realizarse por un medio fehaciente que deje constancia del momento en que ha sido enviada, a qu&eacute; direcci&oacute;n ha sido enviada y el momento de su recepci&oacute;n por la otra Parte. Cuando se produjera un cambio en el domicilio a efectos de notificaciones, se deber&aacute; comunicar esta nueva informaci&oacute;n, lo m&aacute;s pronto posible, a la otra Parte y siguiendo el procedimiento aqu&iacute; establecido.</p>
     <p style="text-align: justify;">No obstante, siempre y cuando sea posible garantizar la autenticidad del emisor, del destinatario, y del contenido del mensaje, y con el objetivo de mantener una comunicaci&oacute;n fluida entre las Partes, se facilitan las siguientes direcciones de correo electr&oacute;nico:</p>
     <p style="text-align: justify;">El AGENTE:</p>
     <p style="text-align: justify;"><a href="mailto:Laura-lopez@gmail.com"><span class="email_agente">______</span></a></p>
     <p style="text-align: justify;">La AGENCIA:</p>
     <p style="text-align: justify;"><a href="mailto:contacto@tutecho.com">contacto-bo@tutecho.com</a></p>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;"><strong>VIGESIMOCUARTA. ACEPTACION</strong></p>
     <p style="text-align: justify;">Las Partes reconocen quedar obligadas por el presente Contrato as&iacute; como sus correspondientes anexos, si los hubiere, y sus efectos jur&iacute;dicos y se comprometen a su cumplimiento de buena fe.</p>
     <p style="text-align: justify;">En prueba de conformidad y aceptaci&oacute;n de todo lo establecido, ambas Partes firman este Contrato en dos ejemplares y a un solo efecto, en el lugar y fecha:</p>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;">Fecha: ________________________________</p>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;">Lugar: _____________________________________________________</p>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;">&nbsp;</p>
     <p style="text-align: justify;">&nbsp;</p>
     <table style="width: 100%; margin-left: auto; margin-right: auto;">
    <tbody>
    <tr style="height: 83px;">
    <td style="width: 446.5px; height: 83px; text-align: center; vertical-align: middle;">
    <p>Firma: ..........................................</p>
    <p style="text-align: justify;">&nbsp;</p>
    <p>Aclaraci&oacute;n: ...................................</p>
    <p style="text-align: justify;">&nbsp;</p>
    <p>${(datos['datos_agencia']['agente']['doc_tipo'] == 'carnet de identidad' ? 'C.I.' : 'PASS')} : .............................................</p>
    </td>
    <td style="width: 435.5px; height: 83px; text-align: center; vertical-align: middle;">
    <p>Firma: ...........................................</p>
    <p style="text-align: justify;">&nbsp;</p>
    <p>Aclaraci&oacute;n: ...................................</p>
    <p style="text-align: justify;">&nbsp;</p>
    <p><span class="agente_documento_identidad_abrev">______</span> : ............................................</p>
    </td>
    </tr>
    <tr style="height: 13px;">
    <td style="width: 446.5px; height: 13px; text-align: center; vertical-align: middle;"><p style="text-align: justify;">&nbsp;</p><strong >AGENCIA</strong></td>
    <td style="width: 435.5px; height: 13px; text-align: center; vertical-align: middle;"><p style="text-align: justify;">&nbsp;</p><strong>AGENTE</strong></td>
    </tr>
    <tr style="height: 11px;">
    <td style="width: 446.5px; height: 11px; text-align: center; vertical-align: middle;">
    <p style="font-size: 8em; color: #fff">□</p>
    </td>
    <td style="width: 446.5px; height: 11px; text-align: center; vertical-align: middle;">
    <p style="font-size: 8em; color: #fff">□</p>
    </td>
    </tr>
    <tr style="height: 13px;">
    <td style="width: 446.5px; height: 13px; text-align: center; vertical-align: middle;">Huella / Sello</td>
    <td style="width: 446.5px; height: 13px; text-align: center; vertical-align: middle;">Huella</td>
    </tr>
    </tbody>
    </table>
     `);


        $(".contrato_contenedor span").each(function(){
            if (!$(this).hasClass("dato")) {
                $(this).addClass("dato");
            };
        });

    });

    
    
    
    // ####################################  FIRST CHARGE ################################################################

    preguntas_grupos_cantidad = 4;
    let count = 1;
    
    while (count <= preguntas_grupos_cantidad) {
        $(".etapas_wrap").append(`<div id="etapa_${count}" class="preguntas_wrap"></div>`); 
        count += 1;
    };


    // ############# ETAPA 1
   
    $("#etapa_1").html(`
    <span class="pregunta_elemento">
        <label for="nombre_agente" class="pregunta_label">Nombre completo del Agente que se contrata:</label>
        <textarea name="nombre_agente" id="nombre_agente" rows="1" class="pregunta_input" oninput="auto_grow(this)"></textarea>
    </span>
    <span class="pregunta_elemento">
        <label for="tipo_documento_identidad" class="pregunta_label">Tipo de documento identificativo del Agente:</label>
        <select name="tipo_documento_identidad" id="tipo_documento_identidad" class="pregunta_select">
            <option value=""></option>
            <option value="Carnet de Identidad">Carnet de Identidad</option>
            <option value="Pasaporte">Pasaporte</option>
        </select>
    </span>
    <span class="pregunta_elemento">
        <label for="numero_identidad" class="pregunta_label">Número del documento de identidad:</label>
        <textarea name="numero_identidad" id="numero_identidad" rows="1" class="pregunta_input" oninput="auto_grow(this)"></textarea>
    </span>
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
        <span class="pregunta_elemento">
            <label for="direccion_agente" class="pregunta_label">Dirrecion del domicilio del Agente (#numero, Calle/Avenida, Barrio/Zona):</label>
            <textarea name="direccion_agente" id="direccion_agente" rows="1" class="pregunta_input" oninput="auto_grow(this)"></textarea>
        </span>
        <span class="pregunta_elemento">
            <label for="departamento_agente" class="pregunta_label">Departamento:</label>
            <select name="departamento_agente" id="departamento_agente" class="pregunta_select departamento_agente">
                <option value=""></option>
                <option value="LA PAZ">La Paz</option>
                <option value="CHUQUISACA">Chuquisaca</option>
                <option value="COCHABAMBA">Cochabamba</option>
                <option value="SANTA CRUZ">Santa Cruz</option>
                <option value="POTOSI">Potosi</option>
                <option value="ORURO">Oruro</option>
                <option value="PANDO">Pando</option>
                <option value="BENI">Beni</option>
                <option value="TARIJA">Tarija</option>
            </select>
        </span>
        <span class="pregunta_elemento">
            <label for="ciudad_agente" class="pregunta_label">Ciudad:</label>
            <select name="ciudad_agente" id="ciudad_agente" class="pregunta_select" disabled>
                <option value=""></option>
            </select>
        </span>
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