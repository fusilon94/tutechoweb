
let cookie_pais;
$.ajax({
  async: false,
  url: "../../js/js.cookie.js",
  dataType: "script"
}).done(function(){
  cookie_pais = Cookies.get('tutechopais');
});

// CODIGO PARA LANZAR LA ANIMACION AL HACER CLICK EN EL BTN FOTOS 360 y ABRIR EL VIEWER #################################################

  func_abrir_viewer = function(){
    if ($('.overlay_media_viewer').hasClass('active')) {
      if (viewer_mode == 'tour_VR') {
        for (let i = scene.children.length - 1; i >= 0; i--) {//para remover todos los sprites, se debe hacer este reverse loop, porque al hacer remove, el elemento desaparece del array o lista, y se decalan los indexes, dejando siempre un sprite sin borrar
            if(scene.children[i].type === 'Sprite')
             if (scene.children[i].tipo === 'LINK' || scene.children[i].tipo === 'TOOLTIP') {
               scene.remove(scene.children[i]);
             };
        };
      };
      $(".tutorial_360viewer").css('display', 'none');
      $(".overlay_tutorial_vr").css('display', 'none');
      $(".ficha_bien_container").removeClass('viewer_opened');
      $('.overlay_media_viewer').toggleClass('active');
      $('.fotos_gran_container').empty();
      if ($(".control_right_container").hasClass('visible')) {
        $(".control_right_container").removeClass('visible')
      };
    }else {
      $(".ficha_bien_container").addClass('viewer_opened');
      $('.overlay_media_viewer').toggleClass('active');
      $(".tutorial_360viewer").css('display', 'flex');
      $('.fotos_gran_container').empty();
    };



    $.each(fotos_json, function(foto){
      var content = "<div class=\"foto_mini_container\"><div class=\"foto_prev_container\" id=\"" + foto.replace(/~/g, "") + ".jpg\" name=\"" + foto + "\"><img src=\"../../bienes_inmuebles/" + cookie_pais + "/" + ficha_bien_referencia + "/fotos/" + foto.replace(/~/g, "") + ".jpg\" alt=\"IMAGEN PREV\"></div><div class=\"info_foto_prev_container\"><span class=\"foto_prev_titulo\">" + foto.replace(/~/g, "") + "</span></div></div>";

      $('.fotos_gran_container').append(content);

    });

    if (viewer_mode == 'tour_VR') {

      $(".overlay_tutorial_vr").css('display', 'flex');

      var entry_foto = '../../bienes_inmuebles/' + cookie_pais + '/' + ficha_bien_referencia + '/fotos_360/' + tour_vr_json['VR_ENTRY']['imagen'];
      var new_texture = new THREE.TextureLoader().load(entry_foto);//CARGA LA TEXTURA
      new_texture.wrapS = THREE.RepeatWrapping;//PERMITE LA INVERSION DE TEXTURAS
      new_texture.repeat.x = -1;//INVIERTE LA TEXTURA
      material = new THREE.MeshBasicMaterial({//CREA LA TEXTURA PARA LA ESFERA TEXTURA
        map: new_texture,//ESTA ES LA TEXTURA
        side: THREE.DoubleSide//SE DEFINE QUE LA TEXTURA ES ADENTRO Y AFUERA DE LA ESFERA
      });
      material.transparent = true; //permite que el material sea transparente, servira para pasar de una foto a otra
      sphere = new THREE.Mesh(geometry, material);//SE CREA EL OBJETO ESFERA CON TEXTURA
      scene.add(sphere);//SE PONE EL OBJETO EN LA ESCENA

      $('.foto_prev_container[name="' + tour_vr_json['VR_ENTRY']['imagen'].replace(".jpg", "") + '"]').parent().addClass('selected');

      current_foto = "~" + tour_vr_json['VR_ENTRY']['imagen'].replace(".jpg", "") + "~";

      for (let i = scene.children.length - 1; i >= 0; i--) {//para remover todos los sprites, se debe hacer este reverse loop, porque al hacer remove, el elemento desaparece del array o lista, y se decalan los indexes, dejando siempre un sprite sin borrar
          if(scene.children[i].type === 'Sprite')
           if (scene.children[i].tipo === 'LINK' || scene.children[i].tipo === 'TOOLTIP') {
             scene.remove(scene.children[i]);
           };
      };

      if (jQuery.isEmptyObject(tour_vr_json['VR_ENTRY']['links']) == false) {
        $.each(tour_vr_json['VR_ENTRY']['links'], function(link){
          var label_to_show = tour_vr_json['VR_ENTRY'].links[link]['label'];
          var position_x = tour_vr_json['VR_ENTRY'].links[link]['x'];
          var position_y = tour_vr_json['VR_ENTRY'].links[link]['y'];
          var position_z = tour_vr_json['VR_ENTRY'].links[link]['z'];

          addTooltip(new THREE.Vector3(position_x, position_y, position_z), label_to_show, "../../objetos/dot_orange.svg");
        });
      };

      if (jQuery.isEmptyObject(tour_vr_json['VR_ENTRY']['tooltips']) == false) {
        $.each(tour_vr_json['VR_ENTRY']['tooltips'], function(tooltip){
          var label_to_show = tour_vr_json['VR_ENTRY'].tooltips[tooltip]['label'];
          var position_x = tour_vr_json['VR_ENTRY'].tooltips[tooltip]['x'];
          var position_y = tour_vr_json['VR_ENTRY'].tooltips[tooltip]['y'];
          var position_z = tour_vr_json['VR_ENTRY'].tooltips[tooltip]['z'];

          addTooltip(new THREE.Vector3(position_x, position_y, position_z), label_to_show, "../../objetos/dot_blue.svg");
        });
      };

    }else {
      var new_texture = new THREE.TextureLoader().load('../../360_default_image.jpg');//CARGA LA TEXTURA
      new_texture.wrapS = THREE.RepeatWrapping;//PERMITE LA INVERSION DE TEXTURAS
      new_texture.repeat.x = -1;//INVIERTE LA TEXTURA
      material = new THREE.MeshBasicMaterial({//CREA LA TEXTURA PARA LA ESFERA TEXTURA
        map: new_texture,//ESTA ES LA TEXTURA
        side: THREE.DoubleSide//SE DEFINE QUE LA TEXTURA ES ADENTRO Y AFUERA DE LA ESFERA
      });
    };
    material.transparent = true; //permite que el material sea transparente, servira para pasar de una foto a otra
    sphere = new THREE.Mesh(geometry, material);//SE CREA EL OBJETO ESFERA CON TEXTURA
    scene.add(sphere);//SE PONE EL OBJETO EN LA ESCENA

    TweenLite.to(sphere.material, 2, {
      opacity: 1
    });
    default_image_view = true;

    camera.position.set( -1, 0, 0 );//SE REESTABLECE LA POSICION DE LA CAMARA PARA POSTERIORES ENTRADAS AL VIEWER
    controls.update();//REFRESH LOS CONTROLES AL MOVER LA CAMARA - SIEMPRE PONER ESO DESPUES DE CUALQUIER CAMBIO MANUAL DE LA CAMARA


  };



// #################### FUNCION QUE PERMITE MOSTRAR LA DINAMICA DE LOS DOT LINK Y DOT TOOLTIP ######################################

function onSpriteClick(event) {// cuando se pasa el cursor encima del sprite se habre la tooltip

 let mouse = new THREE.Vector2((event.clientX / window.innerWidth) * 2 - 1, -(event.clientY / window.innerHeight) * 2 + 1);
 rayCaster.setFromCamera(mouse, camera);//DEFINE QUE EL RAYO SALE DE LA CAMARA Y VA EN LA DIRECCION DEL CLICK MOUSE
 let intersects = rayCaster.intersectObjects(scene.children);
   tooltip_content.classList.remove('active');//Cierra el tooltip_content si estuviera abierto
   tooltip_content.style.top = 0 + 'px';
   tooltip_content.style.left = ( 0 + 'px' );
   $('.viewer_tooltip_content .imagen_opcional_container').empty();
 intersects.forEach(function(intersect){//recorre todas las intersecciones y busca si se hizo click en una tooltip
   if (intersect.object.type === 'Sprite') {//si la interseccion es de tipi sprite osea una tooltip
     if (intersect.object.tipo == 'LINK'){
       var next_foto_name = tour_vr_json[current_foto]['links'][intersect.object.name]['foto'];
       var link_foto_clicked = '../../bienes_inmuebles/' + cookie_pais + '/' + ficha_bien_referencia + '/fotos_360/' + next_foto_name;

       current_foto = "~" + next_foto_name.replace(".jpg", "") + "~"; //para obtener el nombre de la foto siguiente
       $('.foto_mini_container').removeClass('selected');// se resetea el sombreado en los elementos de lista de fotos
       $('.foto_prev_container[name="' + current_foto + '"]').parent().addClass('selected');//se coloca el sombreado a a al elemento foto que corresoponde
       TweenLite.to(sphere.material, 0.5, {opacity: 0, onComplete: function(){next_texture_loader(link_foto_clicked)}});//se carga la proxima foto
     };

     if (intersect.object.tipo == 'TOOLTIP') {

       let p = intersect.object.position.clone().project(camera);//DEFINE LA POSICION PROYECTADA DEL OBJETO 3D SOBRE LA PNATALLA 2D
       let content_text = tour_vr_json[current_foto]['tooltips'][intersect.object.name]['info'].replace(/\n\r?/g, '<br />');;
       let foto_opcional_content = tour_vr_json[current_foto]['tooltips'][intersect.object.name]['imagen_opcional'];
       $('.viewer_tooltip_content .tooltip_text').html(content_text);//INSERTA EL NOMBRE DE LA TOOLTIP DENTRO DEL POPUP
       if (foto_opcional_content !== '') {
         var img_container = '<img src="' + foto_opcional_content + '" alt="foto">'
         $('.viewer_tooltip_content .imagen_opcional_container').html(img_container);
       };
       tooltip_content.style.top = '18em';//DEFINE EL TOP DEL POPUP DECALADO LIGERAMENTE
       tooltip_content.style.left = ( (window.innerWidth / 2) - ($('.viewer_tooltip_content').width() / 2) + 40 + 'px' );//DEFINE EL LEFT DEL POPUP DECALADO LIGERAMENTE
       tooltip_content.classList.add('active');//COLOCA LA CLASE ACTIVE

     };



   };

 });
};

function onSpriteTouch(touch) {// cuando se pasa el cursor encima del sprite se habre la tooltip

 let mouse = new THREE.Vector2((touch.clientX / window.innerWidth) * 2 - 1, -(touch.clientY / window.innerHeight) * 2 + 1);
 rayCaster.setFromCamera(mouse, camera);//DEFINE QUE EL RAYO SALE DE LA CAMARA Y VA EN LA DIRECCION DEL CLICK MOUSE
 let intersects = rayCaster.intersectObjects(scene.children);
   tooltip_content.classList.remove('active');//Cierra el tooltip_content si estuviera abierto
   tooltip_content.style.top = 0 + 'px';
   tooltip_content.style.left = ( 0 + 'px' );
   $('.viewer_tooltip_content .imagen_opcional_container').empty();
 intersects.forEach(function(intersect){//recorre todas las intersecciones y busca si se hizo click en una tooltip
   if (intersect.object.type === 'Sprite') {//si la interseccion es de tipi sprite osea una tooltip
     if (intersect.object.tipo == 'LINK'){
       var next_foto_name = tour_vr_json[current_foto]['links'][intersect.object.name]['foto'];
       var link_foto_clicked = '../../bienes_inmuebles/' + cookie_pais + '/' + ficha_bien_referencia + '/fotos_360/' + next_foto_name;

       current_foto = "~" + next_foto_name.replace(".jpg", "") + "~"; //para obtener el nombre de la foto siguiente
       $('.foto_mini_container').removeClass('selected');// se resetea el sombreado en los elementos de lista de fotos
       $('.foto_prev_container[name="' + current_foto + '"]').parent().addClass('selected');//se coloca el sombreado a a al elemento foto que corresoponde
       TweenLite.to(sphere.material, 0.5, {opacity: 0, onComplete: function(){next_texture_loader(link_foto_clicked)}});//se carga la proxima foto
     };

     if (intersect.object.tipo == 'TOOLTIP') {

       let p = intersect.object.position.clone().project(camera);//DEFINE LA POSICION PROYECTADA DEL OBJETO 3D SOBRE LA PNATALLA 2D
       let content_text = tour_vr_json[current_foto]['tooltips'][intersect.object.name]['info'].replace(/\n\r?/g, '<br />');;
       let foto_opcional_content = tour_vr_json[current_foto]['tooltips'][intersect.object.name]['imagen_opcional'];
       $('.viewer_tooltip_content .tooltip_text').html(content_text);//INSERTA EL NOMBRE DE LA TOOLTIP DENTRO DEL POPUP
       if (foto_opcional_content !== '') {
         var img_container = '<img src="' + foto_opcional_content + '" alt="foto">'
         $('.viewer_tooltip_content .imagen_opcional_container').html(img_container);
       };
       tooltip_content.style.top = '18em';//DEFINE EL TOP DEL POPUP DECALADO LIGERAMENTE
       tooltip_content.style.left = ( (window.innerWidth / 2) - ($('.viewer_tooltip_content').width() / 2) + 40 + 'px' );//DEFINE EL LEFT DEL POPUP DECALADO LIGERAMENTE
       tooltip_content.classList.add('active');//COLOCA LA CLASE ACTIVE

     };



   };

 });
};

//#################### CODIGO PARA ABRIR EL MENU DE FOTOS #############################################3

      func_abrir_menu_derecho = function(){
        $(".tutorial_360viewer").css('display', 'none');
        $(".overlay_tutorial_vr").css('display', 'none');
        $(".control_right_container").toggleClass('visible');
      };

      func_entrar_tour_vr = function(){
        $(".overlay_tutorial_vr").css('display', 'none');
        $(".tutorial_360viewer").css('display', 'none');
      };

// ################## CODIGO PARA CERRAR POPUP DEL TOOLTIP AZUL ################################

  func_cerrar_tooltip = function(){
    tooltip_content.classList.remove('active');//REMUEVE LA CLASE ACTIVE
    tooltip_content.style.top = 0 + 'px';
    tooltip_content.style.left = ( 0 + 'px' );
  };

// CODIGO PARA DETENER LA ANIMACION AL HACER Click en el btn CERRAR #################################################

      func_cerrar_viewer = function(){
        var colorTexture = new THREE.Color("black");
        material.color = colorTexture;
        if (viewer_mode == 'tour_VR') {
          for (let i = scene.children.length - 1; i >= 0; i--) {//para remover todos los sprites, se debe hacer este reverse loop, porque al hacer remove, el elemento desaparece del array o lista, y se decalan los indexes, dejando siempre un sprite sin borrar
              if(scene.children[i].type === 'Sprite')
               if (scene.children[i].tipo === 'LINK' || scene.children[i].tipo === 'TOOLTIP') {
                 scene.remove(scene.children[i]);
               };
          };
        };
        $(".tutorial_360viewer").css('display', 'none');
        $(".overlay_tutorial_vr").css('display', 'none');
        $(".ficha_bien_container").removeClass('viewer_opened');
        $('.overlay_media_viewer').toggleClass('active');
        $('.fotos_gran_container').empty();
        if ($(".control_right_container").hasClass('visible')) {
          $(".control_right_container").removeClass('visible')
        };
      };
