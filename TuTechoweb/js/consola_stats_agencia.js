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
      url: "process-request-consola-stats-agencia.php",
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
      url: "process-request-consola-stats-agencia.php",
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
    if (subcategoria == 'XXXXX') {

      
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
      ]}}, margin : {b : 12, r: 30, l: 30, t: 80}});
        
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
      ]}}, margin : {b : 25, r: 30, l: 30, t: 80}});

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
