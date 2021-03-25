$(document).ready(function(){
  jQuery(function($){

//Al hacer lick en el tag favoritos, se habre el popupfavoritos
$('main').on('click', '.tag_favoritos', function(){
  $('.overlay_popup_enter_favoritos').addClass('active');
});

//Al hacer click en la cruz del popup favoritos, este se cierra
$('.popup_favoritos').on('click', '.popup_favoritos_cerrar_btn i.fa-times', function(){
  $('.popupfavoritos').css('opacity', '0');
  $('.overlay_popup_enter_favoritos').removeClass('active');
});

//logistica del cambio dynamico del popup favoritos entre signin y login
var popup_favoritos_contador = 0;

$('.popup_favoritos').on('click', '#signup', function(){
  if (popup_favoritos_contador == 1) {
    $('form.signup').removeClass('slide-up');
    $('form.login').addClass('slide-up');
    popup_favoritos_contador = 0;
  } else {
    // nothing happends
  }
});

$('.popup_favoritos').on('click', '#login', function(){
  if (popup_favoritos_contador == 0) {
    $('form.login').removeClass('slide-up');
    $('form.signup').addClass('slide-up');
    popup_favoritos_contador = 1;
  } else {
    // nothing happends
  }
});

$('#sections_tabs_contenedor article').on('click', '.favoritos_star_icon', function(e){//controla el cambio de color y el rotar de la estrella de cada thumb
    $(this).toggleClass("rotate_star");
    e.stopPropagation();//evita que active el click event de su contenedor, el thumbnail del bien inmueble
  });



  });
});
