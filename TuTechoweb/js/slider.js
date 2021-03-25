// Sitio del plugin para mas info: woocommerce.com/flexslider/  //

$(document).ready(function(){
  jQuery(function($){
  $('.flexslider.index_slider').flexslider(
    {prevText: "",
     nextText: "",  // aca lo dejamos sin completar, asi no aparece el texto, solo las flechitas //
     pauseOnAction: false,  // para que no se pause cuando cliqueamos los puntos de paginacion //
     pauseOnHover: true,
     slideshowSpeed: 6000,  // aca se define el tiempo de transicion en milisegundos //
     animation: "fade",
     animationSpeed: 1000,});

  $('.flexslider.bien_individual_slider').flexslider(
    {prevText: "",
     nextText: "",
     pauseOnAction: false,
     pauseOnHover: true,
     slideshowSpeed: 10000,
     animation: "fade",
     animationSpeed: 1000,
     controlNav: "thumbnails"
   });


    });
  })
