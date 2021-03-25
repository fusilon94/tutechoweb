$(document).ready(function(){
  jQuery(function($){


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








  });
});
