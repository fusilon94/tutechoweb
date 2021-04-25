$(document).ready(function(){
  jQuery(function($){
    const CONTENEDOR = document.getElementById('graph_container');

   $('.menu_lateral_btn').on('click', function(){

      const menu = $('.menu_lateral')
      if($(this).hasClass("activo")){
          menu.animate({right: '-23em'});
          $(this).removeClass("activo");
      }else{   
          menu.animate({right: '0'});
          $(this).addClass("activo");
      };
    
   });

   $(".tabs_wrap").on("click", ".tab_lista:not(.activo)", function(){
      $(".tab").toggleClass("activo");
      $(".tab_content_lista").toggleClass("activo");
      $(".tab_content_graph").toggleClass("activo");
   });

   $(".tabs_wrap").on("click", ".tab_graph:not(.activo)", function(){
      $(".tab").toggleClass("activo");
      $(".tab_content_lista").toggleClass("activo");
      $(".tab_content_graph").toggleClass("activo");

    });


    $.ajax({
      type: "POST",
      url: "process-request-consola-stats-personal.php",
      data: { action_sent: 'get_categorias' }
    }).done(function(data){

      $(".categorias_wrap").html(data);

      $('.menu_lateral').animate({right: '0'});
      $('.menu_lateral_btn').addClass("activo");
    
    });
  

// ############################ EVENT LISTENERS CATEGORIAS ###############################################

$(".categorias_wrap").on("click", ".categoria", function(){

    $(this).parent().find(".categoria").removeClass("activo");
    $(this).addClass("activo");
    const menu = $('.menu_lateral');
    menu.animate({right: '-23em'});
    $('.menu_lateral_btn').removeClass("activo");

    const categoria = $(this).attr('data');

    $.ajax({
      type: "POST",
      url: "process-request-consola-stats-personal.php",
      data: { action_sent: 'get_sub_categorias', categoria_sent : categoria }
    }).done(function(data){

      $(".sub_categorias_wrap").html(data);
      $(".sub_categorias_wrap").find(".sub_categorie_btn:first").trigger("click");
    });

    
})


// ############################ EVENT LISTENER SUBCATEGORIAS #############################################


  $(".sub_categorias_wrap").on("click", ".sub_categorie_btn", function(){

    $(this).parent().find(".sub_categorie_btn").removeClass("activo");
    $(this).addClass("activo");
    
    const subcategoria = $(this).attr('data');

    // Histogramas Simples count
    if (subcategoria == 'histograma_count_total_visitas') {

      const trace = ['2013-10-02', '2013-11-04', '2013-12-05', '2013-11-06', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14', '2014-05-14', '2014-05-14', '2014-05-14', '2014-05-14']
      histograma_count_total(CONTENEDOR, trace, '<b>Visitas - Agente</b>', 'Visitas');

      data_radio_buttons = {'Semana': '604800000', 'Mes': 'M1', 'Trimestre': 'M3', 'Año': 'M12'}
    
      $(".actions_graph_wrap").html(boton_simple('Navegación', 'btn_slide_range', '') + radio_butons('histogram_time_step', data_radio_buttons, 'M1'));
      
      return

    }; 
    if (subcategoria == 'histograma_count_total_conciliaciones') {

      const trace = ['2013-10-02', '2013-11-04', '2013-12-05', '2013-11-06', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14', '2014-05-14', '2014-05-14', '2014-05-14', '2014-05-14']
      histograma_count_total(CONTENEDOR, trace, '<b>Conciliaciones - Agente', 'Conciliaciones</b>');

      data_radio_buttons = {'Semana': '604800000', 'Mes': 'M1', 'Trimestre': 'M3', 'Año': 'M12'}
    
      $(".actions_graph_wrap").html(boton_simple('Navegación', 'btn_slide_range', '') + radio_butons('histogram_time_step', data_radio_buttons, 'M1'));
      
      return

    };
    
    if (subcategoria == 'histograma_count_total_cierres_jefe') {

      const trace = ['2013-10-02', '2013-11-04', '2013-12-05', '2013-11-06', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14', '2014-05-14', '2014-05-14', '2014-05-14', '2014-05-14']

      histograma_count_total(CONTENEDOR, trace, '<b>Cierres - Jefe de Agencia</b>', 'Cierres');

      data_radio_buttons = {'Semana': '604800000', 'Mes': 'M1', 'Trimestre': 'M3', 'Año': 'M12'}
    
      $(".actions_graph_wrap").html(boton_simple('Navegación', 'btn_slide_range', '') + radio_butons('histogram_time_step', data_radio_buttons, 'M1'));
      
      return

    };

    if (subcategoria == 'histograma_count_total_registros_fotografo') {

      const trace = ['2013-10-02', '2013-11-04', '2013-12-05', '2013-11-06', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14', '2014-05-14', '2014-05-14', '2014-05-14', '2014-05-14']

      histograma_count_total(CONTENEDOR, trace, '<b>Registros - Fotógrafo</b>', 'Registros');

      data_radio_buttons = {'Semana': '604800000', 'Mes': 'M1', 'Trimestre': 'M3', 'Año': 'M12'}
    
      $(".actions_graph_wrap").html(boton_simple('Navegación', 'btn_slide_range', '') + radio_butons('histogram_time_step', data_radio_buttons, 'M1'));
      
      return

    };
    // Histograma simple sum
    if (subcategoria == 'histograma_sum_total_comisiones') {

      const trace = [['2013-10-02', '2013-11-04', '2013-12-05', '2013-11-06', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14', '2014-05-14', '2014-05-14', '2014-05-14', '2014-05-14'], [3000, 2200, 1800, 3500, 4000, 1000, 1200, 2300, 3000, 4000, 2123, 4884, 5453, 43094, 1000]]

      histograma_sum_total(CONTENEDOR, trace, '<b>Comisiones - Agente</b>', 'Total Comision');

      data_radio_buttons = {'Semana': '604800000', 'Mes': 'M1', 'Trimestre': 'M3', 'Año': 'M12'};
    
      $(".actions_graph_wrap").html(boton_simple('Navegación', 'btn_slide_range', '') + radio_butons('histogram_time_step', data_radio_buttons, 'M1'));
      
      return

    };

    // Histogramas stacked count
    if(subcategoria == 'histograma_count_stacked_exito_visitas'){

      const traces = {
        'Exitos':  ['2013-10-02', '2013-11-04', '2013-12-05', '2013-11-06', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14', '2014-05-14', '2014-05-14', '2014-05-14', '2014-05-14'],
        'Fallidos': ['2013-10-02', '2013-10-10', '2013-10-13', '2013-10-21', '2013-10-02', '2013-11-04', '2013-12-05', '2013-11-06', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14', '2014-05-14', '2014-05-14', '2014-05-14', '2014-05-14'],

      };
      
      histograma_count_stacked(CONTENEDOR, traces, '<b>Exito en Visitas - Agente</b>', 'Visitas');

      data_radio_buttons = {'Semana': '604800000', 'Mes': 'M1', 'Trimestre': 'M3', 'Año': 'M12'}
    
      $(".actions_graph_wrap").html(boton_simple('Navegación', 'btn_slide_range', '') + radio_butons('histogram_time_step', data_radio_buttons, 'M1'));

      return

    };

    if(subcategoria == 'histograma_count_stacked_tipo_inmueble_visitas'){

      const traces = {
        'Casas':  ['2013-11-06', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14', '2014-05-14', '2014-05-14', '2014-05-14'],
        'Depatamentos': ['2013-10-10', '2013-10-13', '2013-10-21', '2013-10-02', '2013-11-04', '2013-12-05', '2013-11-06', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14', '2014-05-14', '2014-05-14', '2014-05-14'],
        'Locales': ['2013-10-02', '2013-10-10', '2013-10-21', '2013-10-02', '2013-11-04', '2013-12-05', '2013-11-06', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14'],
        'Terrenos': ['2013-10-02', '2013-10-10', '2013-10-13', '2013-10-21', '2013-10-02', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14', '2014-05-14', '2014-05-14'],

      };
      
      histograma_count_stacked(CONTENEDOR, traces, '<b>Visitas por Tipo Inmueble - Agente</b>', 'Visitas');

      data_radio_buttons = {'Semana': '604800000', 'Mes': 'M1', 'Trimestre': 'M3', 'Año': 'M12'}
    
      $(".actions_graph_wrap").html(boton_simple('Navegación', 'btn_slide_range', '') + radio_butons('histogram_time_step', data_radio_buttons, 'M1'));

      return

    };
    if(subcategoria == 'histograma_count_stacked_conciliaciones'){

      const traces = {
        'Personas':  ['2013-11-06', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14', '2014-05-14', '2014-05-14', '2014-05-14'],
        'Proyectos': ['2013-10-10', '2013-10-13', '2013-10-21', '2013-10-02', '2013-11-04', '2013-12-05', '2013-11-06', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14', '2014-05-14', '2014-05-14', '2014-05-14'],
      };
      
      histograma_count_stacked(CONTENEDOR, traces, '<b>Conciliaciones por Tipo - Agente</b>', 'Conciliaciones');

      data_radio_buttons = {'Semana': '604800000', 'Mes': 'M1', 'Trimestre': 'M3', 'Año': 'M12'}
    
      $(".actions_graph_wrap").html(boton_simple('Navegación', 'btn_slide_range', '') + radio_butons('histogram_time_step', data_radio_buttons, 'M1'));

      return

    };
    
    if(subcategoria == 'histograma_count_stacked_tipo_cierres'){

      const traces = {
        'Ventas':  ['2013-11-06', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14', '2014-05-14', '2014-05-14', '2014-05-14'],
        'Alquileres': ['2013-10-10', '2013-10-13', '2013-10-21', '2013-10-02', '2013-11-04', '2013-12-05', '2013-11-06', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14', '2014-05-14', '2014-05-14', '2014-05-14'],
        'Anticreticos': ['2013-10-02', '2013-10-10', '2013-10-21', '2013-10-02', '2013-11-04', '2013-12-05', '2013-11-06', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14'],
      };
      
      histograma_count_stacked(CONTENEDOR, traces, '<b>Cierres por Tipo - Agente</b>', 'Cierres');

      data_radio_buttons = {'Semana': '604800000', 'Mes': 'M1', 'Trimestre': 'M3', 'Año': 'M12'}
    
      $(".actions_graph_wrap").html(boton_simple('Navegación', 'btn_slide_range', '') + radio_butons('histogram_time_step', data_radio_buttons, 'M1'));

      return

    };
    if(subcategoria == 'histograma_count_stacked_tipo_inmueble_cierres'){

      const traces = {
        'Casas':  ['2013-11-06', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14', '2014-05-14', '2014-05-14', '2014-05-14'],
        'Depatamentos': ['2013-10-10', '2013-10-13', '2013-10-21', '2013-10-02', '2013-11-04', '2013-12-05', '2013-11-06', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14', '2014-05-14', '2014-05-14', '2014-05-14'],
        'Locales': ['2013-10-02', '2013-10-10', '2013-10-21', '2013-10-02', '2013-11-04', '2013-12-05', '2013-11-06', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14'],
        'Terrenos': ['2013-10-02', '2013-10-10', '2013-10-13', '2013-10-21', '2013-10-02', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14', '2014-05-14', '2014-05-14'],

      };
      
      histograma_count_stacked(CONTENEDOR, traces, '<b>Cierres por Tipo Inmueble - Agente</b>', 'Cierres');

      data_radio_buttons = {'Semana': '604800000', 'Mes': 'M1', 'Trimestre': 'M3', 'Año': 'M12'}
    
      $(".actions_graph_wrap").html(boton_simple('Navegación', 'btn_slide_range', '') + radio_butons('histogram_time_step', data_radio_buttons, 'M1'));

      return

    };
    if(subcategoria == 'histograma_count_stacked_tramites'){

      const traces = {
        'Avaluo':  ['2013-11-06', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14', '2014-05-14', '2014-05-14', '2014-05-14'],
        'Venta Terceros': ['2013-10-10', '2013-10-13', '2013-10-21', '2013-10-02', '2013-11-04', '2013-12-05', '2013-11-06', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14', '2014-05-14', '2014-05-14', '2014-05-14'],
        'Gestion Renta': ['2013-10-02', '2013-10-10', '2013-10-21', '2013-10-02', '2013-11-04', '2013-12-05', '2013-11-06', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14'],
        'Saneamiento Papeles': ['2013-10-02', '2013-10-10', '2013-10-13', '2013-10-21', '2013-10-02', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14', '2014-05-14', '2014-05-14'],
        'Declaratoria Herederos': ['2013-10-02', '2013-10-10', '2013-10-13', '2013-10-21', '2013-10-02', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14', '2014-05-14', '2014-05-14'],
        'Cierres': ['2013-10-02', '2013-10-10', '2013-10-13', '2013-10-21', '2013-10-02', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14', '2014-05-14', '2014-05-14'],

      };
      
      histograma_count_stacked(CONTENEDOR, traces, '<b>Tramites por Tipo - Jefe de Agencia</b>', 'Tramites Concluidos');

      data_radio_buttons = {'Semana': '604800000', 'Mes': 'M1', 'Trimestre': 'M3', 'Año': 'M12'}
    
      $(".actions_graph_wrap").html(boton_simple('Navegación', 'btn_slide_range', '') + radio_butons('histogram_time_step', data_radio_buttons, 'M1'));

      return

    };

    //SUM STACKED
    if(subcategoria == 'histograma_sum_stacked_tipo_inmueble_comisiones'){

      const traces = {
        'Casas':  [['2013-10-02', '2013-11-04', '2013-12-05', '2013-11-06', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14', '2014-05-14', '2014-05-14', '2014-05-14', '2014-05-14'], [3000, 2200, 1800, 3500, 4000, 1000, 1200, 2300, 3000, 4000, 2123, 4884, 5453, 43094, 1000]],
        'Depatamentos': [['2013-10-02', '2013-11-04', '2013-12-05', '2013-11-06', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14', '2014-05-14', '2014-05-14', '2014-05-14', '2014-05-14'], [3000, 2200, 1800, 3500, 4000, 1000, 1200, 2300, 3000, 4000, 2123, 4884, 5453, 43094, 1000]],
        'Locales': [['2013-10-02', '2013-11-04', '2013-12-05', '2013-11-06', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14', '2014-05-14', '2014-05-14', '2014-05-14', '2014-05-14'], [3000, 2200, 1800, 3500, 4000, 1000, 1200, 2300, 3000, 4000, 2123, 4884, 5453, 43094, 1000]],
        'Terrenos': [['2013-10-02', '2013-11-04', '2013-12-05', '2013-11-06', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2013-12-04', '2013-11-04', '2014-01-14', '2014-05-14', '2014-05-14', '2014-05-14', '2014-05-14'], [3000, 2200, 1800, 3500, 4000, 1000, 1200, 2300, 3000, 4000, 2123, 4884, 5453, 43094, 1000]],

      };
      
      histograma_sum_stacked(CONTENEDOR, traces, '<b>Comisiones por Tipo Inmueble - Agente</b>', 'Total Comision');

      data_radio_buttons = {'Semana': '604800000', 'Mes': 'M1', 'Trimestre': 'M3', 'Año': 'M12'}
    
      $(".actions_graph_wrap").html(boton_simple('Navegación', 'btn_slide_range', '') + radio_butons('histogram_time_step', data_radio_buttons, 'M1'));

      return

    };

    //OVERLAYED HISTOGRAM
    if(subcategoria == 'histograma_count_total_overlayed_registros'){

      const traces = [
        ['2013-10-10', '2013-10-13', '2013-10-21','2013-10-02', '2013-11-04', '2013-12-05', '2013-11-06', '2013-12-04', '2014-01-14','2014-05-14'],
        [0.2, 0.05, 1, 0.5, 0.8, 0.1, 0.1, 0.3, 0.5, 0.3],
        ['2013-11-06', '2013-12-04', '2013-11-04', '2013-11-05','2013-11-06','2013-11-07','2013-11-08','2013-11-09','2013-12-05', '2013-11-04', '2013-12-06', '2013-12-07','2013-12-08','2013-12-09','2013-12-10','2013-12-04', '2013-11-04', '2014-01-14', '2014-05-14', '2014-05-15', '2014-05-16'],
      ];//[0] -> list of dates (no repeticions), [1] -> mean value for each date, [2] -> samples from agent

      const labels = ['Promedio Agencia + Tolerancia', 'Registros'];
      
      histograma_count_overlayed_fixed_tolerance(CONTENEDOR, traces, labels, '<b>Registros - Agente</b>', 'Registros');

      data_radio_buttons = {'Semana': '604800000', 'Mes': 'M1', 'Trimestre': 'M3', 'Año': 'M12'}
    
      $(".actions_graph_wrap").html(boton_simple('Navegación', 'btn_slide_range', '') + radio_butons('histogram_time_step_overlayed_fixed_tolerance', data_radio_buttons, 'M1'));

      return

    };

    if (subcategoria == 'histograma_count_overlayed_cierres') {

      const traces = [
        ['2013-10-10', '2013-10-13', '2013-10-21', '2013-10-02', '2013-11-04', '2013-12-05', '2013-11-06', '2013-12-04', '2014-01-14','2014-05-14'],
        [0.44, 0.65, 1.5, 0.5, 1.3, 0.1, 0.1, 0.3, 0.5, 0.3],
        ['2013-10-10', '2013-10-13', '2013-10-21', '2013-10-02', '2013-11-04', '2013-12-05', '2013-11-06', '2013-12-04', '2014-01-14','2014-05-14'],
        [1, 1, 0, 0, 0, 2, 1, 0, 0, 1],
      ];//[0] -> list of dates (no repeticions), [1] -> mean value for each date, [2] -> samples from agent
      const labels = ['Promedio Agencia + SD', 'Cierres'];
      const std_list = [0.45337, 0.3554383, 0.41359, 0.13548, 0.9443454, 0.6781351, 0.333354, 0.13548]//STD for every month minus current month

      histograma_count_overlayed_standard_deviation(CONTENEDOR, traces, labels, '<b>Cierres - Agente', 'Cierres</b>', std_list)

      data_radio_buttons = {'Semana': '604800000', 'Mes': 'M1', 'Trimestre': 'M3', 'Año': 'M12'}
    
      $(".actions_graph_wrap").html(boton_simple('Navegación', 'btn_slide_range', '') + radio_butons('histogram_time_step_overlayed_standard_deviation', data_radio_buttons, 'M1'));
      
      return

    };


    //BAR Charts
    if(subcategoria == 'bar_chart_visitas_tipo_inmueble'){

      const labels = ['Casas', 'Departamentos', 'Locales', 'Terrenos'];
      const promedios_tiempos_visitas = [55, 23, 20, 16];//promedio de tiempo en minutos
      const std_list = [15.184, 8.168, 11.681, 10.168];

      crear_bar_chart_std(CONTENEDOR, labels, promedios_tiempos_visitas, std_list, '<b>Tiempos de Visitas - Agente<br>Promedio+SD</b>', 'Tiempo (min)');
      $(".actions_graph_wrap").html("");
    };

    if(subcategoria == 'bar_chart_grouped_cierres'){

      const labels = ['Venta', 'Alquiler', 'Anticretico'];
      const cierres_listas = {
        'Casas':[55, 23, 20, 16],
        'Depart.':[45, 28, 21, 12],
        'Locales': [23, 12, 33, 10],
        'Terrenos': [32, 5, 22, 18]};//promedio de tiempo en minutos

      crear_bar_chart_grouped(CONTENEDOR, labels, cierres_listas, '<b>Cierres por Tipo Inmueble - Agente</b>', 'Cierres');
      $(".actions_graph_wrap").html("");
    };

    if(subcategoria == 'bar_chart_vertical_cierres'){

      const labels = ['Anticreticos', 'Alquileres', 'Ventas'];
      const total_cierres = [12, 41, 33];

      crear_bar_chart_vertical(CONTENEDOR, labels, total_cierres, '<b>Cierres por Tipo - Agente</b>', 'Cierres');
      $(".actions_graph_wrap").html("");
    };

    if(subcategoria == 'bar_chart_vertical_comisiones'){

      const labels = ['Terrenos', 'Locales', 'Depart.', 'Casas'];
      const total_cierres = [7530, 3600, 10140, 33200];

      crear_bar_chart_vertical(CONTENEDOR, labels, total_cierres, '<b>Comisiones por Tipo Inmueble - Agente</b>', 'Total Comision');
      $(".actions_graph_wrap").html("");
    };


    //LINE PLOTS

    if(subcategoria == 'line_plot_visitas_tipo_inmueble'){

      const tiempos_lista = {
        'Casas': [['2013-10-10', '2013-10-13', '2013-10-15', '2013-10-22', '2013-11-04', '2013-11-10', '2013-12-05', '2013-12-06', '2013-12-16', '2014-01-14','2014-05-14'], [43, 42, 38, 32, 55, 38, 63, 48, 47, 37, 42]],
        'Departamentos': [['2013-10-10', '2013-10-13', '2013-10-21', '2013-10-22', '2013-11-04', '2013-12-05', '2013-12-06', '2013-12-16', '2014-01-14','2014-05-14'],[35, 38, 31, 25, 37, 42, 26, 20, 34, 32]],
        'Locales': [['2013-10-10', '2013-10-13', '2013-10-21', '2013-10-22', '2013-11-04', '2013-12-05', '2013-12-06', '2013-12-16', '2014-01-14','2014-05-14'],[28, 35, 32, 25, 20, 45, 30, 30, 22, 42]],
        'Terrenos': [['2013-10-10', '2013-10-13', '2013-10-21', '2013-10-22', '2013-11-04', '2013-12-05', '2013-12-06', '2013-12-16', '2014-01-14','2014-05-14'],[15, 60, 23, 28, 30, 24, 44, 43, 24, 30]]
      };

      crear_line_plot(CONTENEDOR, tiempos_lista, '<b>Tiempos de Visitas - Agente</b>', 'Tiempo (min)');
      $(".actions_graph_wrap").html(boton_simple('Navegación', 'btn_slide_range', ''));
    };

    if(subcategoria == 'line_plot_comisiones'){

      const comisiones = {
        'Comisiones': [['2013-10-10', '2013-10-13', '2013-10-15', '2013-10-22', '2013-11-04', '2013-11-10', '2013-12-05', '2013-12-06', '2013-12-16', '2014-01-14','2014-02-14'], [1200, 2500, 3800, 4200, 4800, 5020, 6500, 9050, 10350, 12500, 23875]],//put cumulative data
      };

      crear_line_plot(CONTENEDOR, comisiones, '<b>Ganancias por Comisiones</b>', `Ganancias (${moneda_code})`);
      $(".actions_graph_wrap").html(boton_simple('Navegación', 'btn_slide_range', ''));
    };



    // Donuts
    if(subcategoria == 'donut_tipo_inmueble_visitas'){

      const values = [83, 25, 43, 58];
      const labels = ["Casas", "Departamentos", "Locales", "Terrenos"];

      // Adicionamos los valores del array values
      sum = values.reduce((a, b) => { return a + b });

      crear_donut(CONTENEDOR, values, labels, "<b>Total Visitas - Agente<br>según Tipo Inmueble</b>", `${sum}<br>Visitas`)

      data_donut_buttons = {'Porcentajes': "label+percent", 'Valores': "label+value"}

      $(".actions_graph_wrap").html(radio_butons('donut_label_choice', data_donut_buttons, "label+percent"));

      return

    };
    if(subcategoria == 'donut_tipo_inmueble_registros'){

      const values = [83, 25, 43, 58];
      const labels = ["Casas", "Departamentos", "Locales", "Terrenos"];
      
      // Adicionamos los valores del array values
      sum = values.reduce((a, b) => { return a + b });

      crear_donut(CONTENEDOR, values, labels, "<b>Total Registros - Agente<br>según Tipo Inmueble</b>", `${sum}<br>Registros`)

      data_donut_buttons = {'Porcentajes': "label+percent", 'Valores': "label+value"}

      $(".actions_graph_wrap").html(radio_butons('donut_label_choice', data_donut_buttons, "label+percent"));

      return

    };

    if(subcategoria == 'donut_tipo_inmueble_registros_fotografo'){

      const values = [83, 75, 93, 88];
      const labels = ["Casas", "Departamentos", "Locales", "Terrenos"];

      // Adicionamos los valores del array values
      sum = values.reduce((a, b) => { return a + b });

      crear_donut(CONTENEDOR, values, labels, "<b>Total Registros - Fotógrafo<br>según Tipo Inmueble</b>", `${sum}<br>Registros`)

      data_donut_buttons = {'Porcentajes': "label+percent", 'Valores': "label+value"}

      $(".actions_graph_wrap").html(radio_butons('donut_label_choice', data_donut_buttons, "label+percent"));

      return

    };

    if(subcategoria == 'donut_tipo_inmueble_comisiones'){

      const values = [1583, 1225, 943];
      const labels = ["Venta", "Alquiler", "Anticretico"];

      // Adicionamos los valores del array values
      sum = values.reduce((a, b) => { return a + b });

      crear_donut(CONTENEDOR, values, labels, "<b>Total Comision - Agente<br>según Tipo Cierre</b>", `Total:<br>${sum} ${moneda_code}`)

      data_donut_buttons = {'Porcentajes': "label+percent", 'Valores': "label+value"}

      $(".actions_graph_wrap").html(radio_butons('donut_label_choice', data_donut_buttons, "label+percent"));

      return

    };

    if(subcategoria == 'donut_tipo_inmueble_cierres_jefe'){

      const values = [46, 34, 11, 99];
      const labels = ["Contrato Privado", "Minuta", "Transferencia", "Intermediacion"];

      // Adicionamos los valores del array values
      sum = values.reduce((a, b) => { return a + b });

      crear_donut(CONTENEDOR, values, labels, "<b>Total Cierres - Jefe de Agencia</b>", `${sum}<br>Cierres`)

      data_donut_buttons = {'Porcentajes': "label+percent", 'Valores': "label+value"}

      $(".actions_graph_wrap").html(radio_butons('donut_label_choice', data_donut_buttons, "label+percent"));

      return

    };


    if(subcategoria == 'pie_reservados_cierres'){

      const values = [83, 25];
      const labels = ["Bolsa Comun", "Reservados"];
      
      crear_pie(CONTENEDOR, values, labels, '<b>Total Cierres - Agente<br>Reservados / Bolsa Común</b>');

      data_pie_buttons = {'Porcentajes': "label+percent", 'Valores': "label+value"}

      $(".actions_graph_wrap").html(radio_butons('pie_label_choice', data_pie_buttons, "label+percent"));

      return

    };


  });


  //  #########################  EVENT LISTENERS  NAVEGACION ######################################
  

    $(".actions_graph_wrap").on("click", ".btn_slide_range", function(){
        
      $(this).toggleClass("activo");
      if ($(this).hasClass("activo")) {

        Plotly.relayout(CONTENEDOR, {xaxis: {type: 'date', rangeslider: {visible: true}, 
        rangeselector: {buttons: [
          {
          count: 3,
          label: '3 meses',
          step: 'month',
          stepmode: 'backward'
          },
          {
          count: 1,
          label: '1 año',
          step: 'year',
          stepmode: 'backward'
          },
          {
          step: 'all',
          label: 'TODO'
          }
      ]}}, margin : {b : 12, r: 50, l: 50, t: 80}});
        
      }else{

        Plotly.relayout(CONTENEDOR, {xaxis: {type: 'date', rangeslider: {visible: false}, rangeselector: {buttons: [
          {
          count: 3,
          label: '3 meses',
          step: 'month',
          stepmode: 'backward'
          },
          {
          count: 1,
          label: '1 año',
          step: 'year',
          stepmode: 'backward'
          },
          {
          step: 'all',
          label: 'TODO'
          }
      ]}}, margin : {b : 50, r: 50, l: 50, t: 80}});

      };

  });


  // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ RADIO BUTTONS @@@@@@@@@@@@@@@@@@@@@@@@@@@@@


  $(".actions_graph_wrap").on("click", ".histogram_time_step", function(){

    $(this).parent().find(".radio_btn").removeClass("activo");
    $(this).addClass("activo");

    const time_step = $(this).attr('data');

    Plotly.restyle(CONTENEDOR, {xbins: {size: time_step}}, 0)

  });

  $(".actions_graph_wrap").on("click", ".histogram_time_step_overlayed_fixed_tolerance", function(){

    $(this).parent().find(".radio_btn").removeClass("activo");
    $(this).addClass("activo");

    const time_step = $(this).attr('data');

    if (time_step === '604800000') {
      Plotly.restyle(CONTENEDOR, {
        xbins: {size: time_step},
        error_y: {
          type: 'constant',
          symmetric: false,
          value: 0,
          valueminus: 1,
          color: '#85144B',
          thickness: 3,
          visible: true
        },
      }, 0)
      Plotly.restyle(CONTENEDOR, {xbins: {size: time_step}}, 1)
      return
    }
    if (time_step === 'M1') {
      Plotly.restyle(CONTENEDOR, {
        xbins: {size: time_step},
        error_y: {
          type: 'constant',
          symmetric: false,
          value: 0,
          valueminus: 3,
          color: '#85144B',
          thickness: 3,
          visible: true
        },
      }, 0)
      Plotly.restyle(CONTENEDOR, {xbins: {size: time_step}}, 1)
      return
    }
    if (time_step === 'M3') {
      Plotly.restyle(CONTENEDOR, {
        xbins: {size: time_step},
        error_y: {
          type: 'constant',
          symmetric: false,
          value: 0,
          valueminus: 6,
          color: '#85144B',
          thickness: 3,
          visible: true
        },
      }, 0)
      Plotly.restyle(CONTENEDOR, {xbins: {size: time_step}}, 1)
      return
    }
    if (time_step === 'M12') {
      Plotly.restyle(CONTENEDOR, {
        xbins: {size: time_step},
        error_y: {
          type: 'constant',
          symmetric: false,
          value: 0,
          valueminus: 10,
          color: '#85144B',
          thickness: 3,
          visible: true
        },
      }, 0)
      Plotly.restyle(CONTENEDOR, {xbins: {size: time_step}}, 1)
    }

  });
  
  // STANDARD DEVIATION BUTTONS
  $(".actions_graph_wrap").on("click", ".histogram_time_step_overlayed_standard_deviation", function(){

    $(this).parent().find(".radio_btn").removeClass("activo");
    $(this).addClass("activo");

    const time_step = $(this).attr('data');

    if (time_step === '604800000') {
      Plotly.restyle(CONTENEDOR, {
        xbins: {size: time_step},
        error_y: {},
      }, 0)
      Plotly.restyle(CONTENEDOR, {xbins: {size: time_step}}, 1);

      Plotly.restyle(CONTENEDOR, {text: ["", ""]});
      return
    }

    if (time_step === 'M1') {
      const std_list_requested = [0.45337, 0.3554383, 0.41359, 0.13548, 0.9443454, 0.6781351, 0.333354, 0.13548];
      
      Plotly.restyle(CONTENEDOR, {
        xbins: {size: time_step},
        error_y: {
          type: 'data',
          array: std_list_requested,
          color: '#85144B',
          thickness: 3,
          visible: true
        },
      }, 0)
      Plotly.restyle(CONTENEDOR, {xbins: {size: time_step}}, 1);

      const text_list = get_z_scores(CONTENEDOR);
      Plotly.restyle(CONTENEDOR, {text: [text_list, ""]});

      return
    }
    if (time_step === 'M3') {
      const std_list_requested = [2.338939, 1.839398];

      Plotly.restyle(CONTENEDOR, {
        xbins: {size: time_step},
        error_y: {
          type: 'data',
          array: std_list_requested,
          color: '#85144B',
          thickness: 3,
          visible: true
        },
      }, 0)
      Plotly.restyle(CONTENEDOR, {xbins: {size: time_step}}, 1);

      const text_list = get_z_scores(CONTENEDOR);
      Plotly.restyle(CONTENEDOR, {text: [text_list, ""]});

      return
    }
    if (time_step === 'M12') {
      const std_list_requested = [1.72884634];

      Plotly.restyle(CONTENEDOR, {
        xbins: {size: time_step},
        error_y: {
          type: 'data',
          array: std_list_requested,
          color: '#85144B',
          thickness: 3,
          visible: true
        },
      }, 0)
      Plotly.restyle(CONTENEDOR, {xbins: {size: time_step}}, 1);

      const text_list = get_z_scores(CONTENEDOR);
      Plotly.restyle(CONTENEDOR, {text: [text_list, ""]});

      return
    }

    

  });


  $(".actions_graph_wrap").on("click", ".pie_label_choice", function(){

    $(this).parent().find(".radio_btn").removeClass("activo");
    $(this).addClass("activo");

    const info = $(this).attr('data');

    Plotly.restyle(CONTENEDOR, {textinfo: info}, 0)

  });
  
  
  $(".actions_graph_wrap").on("click", ".donut_label_choice", function(){

    $(this).parent().find(".radio_btn").removeClass("activo");
    $(this).addClass("activo");

    const info = $(this).attr('data');

    Plotly.restyle(CONTENEDOR, {textinfo: info}, 0)

  });

  });
});
