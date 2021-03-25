var contador = 1;

$(document).ready(function(){
   jQuery(function($){

   $('.menu_boton').on("click", function(){
                               if(contador == 1)
                                  {$('nav').animate({left: '0'});
                                  contador = 0;}
                               else
                                  {contador = 1;
                                  $('nav').animate({left:'-100%'})
                                  }
  });

 

   $(".cambiar_pais").on("click", function(){
         Cookies.remove('tutechopais');
         location.reload();
   });


   });


   $(document).on("keydown", ":input:not(textarea):not(:submit)", function(event) {
      if (event.key == "Enter") {
         event.preventDefault();
     };
  });

});
