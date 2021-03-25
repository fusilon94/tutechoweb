$(document).ready(function(){
  jQuery(function($){

    let cookie_pais;
    $.ajax({
      async: false,
      url: "../../js/js.cookie.js",
      dataType: "script"
    }).done(function(){
      cookie_pais = Cookies.get('tutechopais');
    });

// FUNCIONES PARA LA APERTURA DE LOS MENUS LATERALES ##########################

 function open_control_left(){
   tooltip_content.classList.remove('active');//Cierra el tooltip_content si estuviera abierto
   tooltip_content.style.top = 0 + 'px';
   tooltip_content.style.left = ( 0 + 'px' );
   $('.viewer_imagenes_opcionales').css('visibility', 'hidden');
   $(".control_left_container").toggleClass('visible');
 };

 function open_control_right(){
   tooltip_content.classList.remove('active');//Cierra el tooltip_content si estuviera abierto
   tooltip_content.style.top = 0 + 'px';
   tooltip_content.style.left = ( 0 + 'px' );
   $('.viewer_imagenes_opcionales').css('visibility', 'hidden');
   $(".control_right_container").toggleClass('visible');
 };

// CODIGO DE LOS BOTONES DE APERTURA DE LOS MENUS LATERALES ##########################

  $(".btn_abrir_left").on("click", open_control_left);
  $(".btn_abrir_right").on("click", open_control_right);

// CODIGO PARA EL CHECK DE LA FOTO TOUR ENTRY ##############################

  $(".check_foto_entry:not(.checked)").on("click", function(){
    $(".check_foto_entry").removeClass('checked').empty();//resetea todos
    $(this).addClass('checked').html("Entrada");
  });

// CODIGO PARA SELECCIONAR FOTO DE LA LISTA ##############################

  $(".foto_prev_container img").hover(function(){
      $(this).parent().parent().find('.info_foto_prev_container').toggleClass('hovered');
  });

  $(".foto_prev_container img").on("click", function(){
    $(".info_foto_prev_container").removeClass('selected');
    if ($(this).parent().parent().find('.info_foto_prev_container').hasClass('selected') == false) {
      $(this).parent().parent().find('.info_foto_prev_container').addClass('selected');
    }
  });



// ##################__#############################################################__#####################################
// ################_|**|_#########################################################_|**|_###################################
// ################\****/################## CODIGO BASE PARA EL THREE.JS #########\****/###################################
// #################\**/###########################################################\**/####################################
// ##################\/#############################################################\/#####################################

  //CREACION DE LA ESCENA, CAMARAS, RENDERIZADORES Y CONTROLES
     const viewer_container = document.getElementById('foto360_container');
     const tooltip = document.querySelector('.viewer_tooltip');
     const tooltip_content = document.querySelector('.viewer_tooltip_content');
     let sprite_active = false;
     let default_image_view = true;
     // let array_fotos = {billar:'../../360.jpg', playa:'../../360_2.jpg', restaurante:'../../360_3.jpg'}; PONER EL ARRAY FOTOS 360 PATHS

     var renderer = new THREE.WebGLRenderer();//SE DEFINE EL RENDERER
     renderer.setSize( window.innerWidth, window.innerHeight );//SE DEFINE LAS DIMENSIONES DEL RENDERER
     viewer_container.appendChild( renderer.domElement );//SE COLOCA EL RENDERER EN SU CONTAINER

     var scene = new THREE.Scene();//SE CREA UNA ESCENA VACIA
     var camera = new THREE.PerspectiveCamera( 75, window.innerWidth/window.innerHeight, 0.1, 1000 );//SE DEFINE EL TIPO DE CAMARA

     var controls = new THREE.OrbitControls( camera, renderer.domElement );//DEFINE LOS CONTROLES PARA MOVER LA CAMARA
     controls.rotateSpeed = -0.3;//en negativo ya que vemos la esfera desde adentro
     controls.enableZoom = false;// evitar el zoom en la foto ya que no tiene resolucion suficiente
     controls.enablePan = false;//evitar el whoble al hacer click derecho
     camera.position.set( -1, 0, 0 );//SE DEFINE LA POSICION DE LA CAMARA
     controls.update();//REFRESH LOS CONTROLES AL MOVER LA CAMARA - SIEMPRE PONER ESO DESPUES DE CUALQUIER CAMBIO MANUAL DE LA CAMARA


     //CREACION DE LA ESFERA QUE PROYECTA LA IMAGEN 360
       var geometry = new THREE.SphereGeometry( 50, 40, 40 );//SE DEFINE LA ESFERA, SU RADIO Y LA CANTIDAD DE CORTES HORIZONTALES y VERTICALES


       //DEFINE EL CODIGO QUE PERMITE MOSTRAR EL CONTENIDO DE LA ESCENA
         function animate() {
           requestAnimationFrame(animate);//permite crear un bucle que anima constantemente el todo
           renderer.render(scene, camera);//RENDERIZAR TODO LO DEFINIDO
         };

         function onResize() {//PERMITE RESTAURAR LOS PARAMETROS DE CAMARA SI SE REDIMENZIONA
           renderer.setSize(window.innerWidth, window.innerHeight);//SE RESTAURA EL RENDERER
           camera.aspect = window.innerWidth / window.innerHeight;// SE RESTAURA LA CAMARA
           camera.updateProjectionMatrix();//SE RESTAURA LA PROJECCION DE LA CAMARA
         };





// ############################################################################################################################
// ######################################### FIN CODIGO BASE ##################################################################
// ############################################################################################################################




      // SE CARGA EL LOGO DE TUTECHO POR DEFAULT Y SE LANZA LA ANIMACION#########################3

           var foto_clicked = '../../360_default_image.jpg';

           var texture = new THREE.TextureLoader().load(foto_clicked);//CARGA LA TEXTURA
           texture.wrapS = THREE.RepeatWrapping;//PERMITE LA INVERSION DE TEXTURAS
           texture.repeat.x = -1;//INVIERTE LA TEXTURA

           var material = new THREE.MeshBasicMaterial({//CREA LA TEXTURA PARA LA ESFERA TEXTURA
             map: texture,//ESTA ES LA TEXTURA
             side: THREE.DoubleSide//SE DEFINE QUE LA TEXTURA ES ADENTRO Y AFUERA DE LA ESFERA
           });
           material.transparent = true; //permite que el material sea transparente, servira para pasar de una foto a otra
           var sphere = new THREE.Mesh(geometry, material);//SE CREA EL OBJETO ESFERA CON TEXTURA
           scene.add(sphere);//SE PONE EL OBJETO EN LA ESCENA

           if ( WEBGL.isWebGLAvailable() ) {//VERIFICAMOS QUE EL NAVEGADOR SOPORTA WEBGL EL MOTOR DE RENDERIZACION QUE USAMOS

            animate();//RENDERIZAR - MOSTRAR LA ANIMACION

           } else {// SI EL NAVEGADOR NO LO SOPORTA ENTONCES SE MUESTRA UN ERROR

            var warning = WEBGL.getWebGLErrorMessage();
            alert(warning);

           };


// ############### SE DEFINE LA FUNCION PARA CARGAR NUEVAS IMAGENES 360 AL VISUALIZADOR #############################################
    var current_foto = ''; //se usa en las funciones de agregar link y tooltips

    function next_texture_loader(new_foto) {
      console.log(vr_json_obj);

        var new_texture = new THREE.TextureLoader().load(new_foto);//CARGA LA TEXTURA
        new_texture.wrapS = THREE.RepeatWrapping;//PERMITE LA INVERSION DE TEXTURAS
        new_texture.repeat.x = -1;//INVIERTE LA TEXTURA

        material.map = new_texture;
        TweenLite.to(sphere.material, 2, {
          opacity: 1
        });

        for (let i = scene.children.length - 1; i >= 0; i--) {//para remover todos los sprites, se debe hacer este reverse loop, porque al hacer remove, el elemento desaparece del array o lista, y se decalan los indexes, dejando siempre un sprite sin borrar
            if(scene.children[i].type === 'Sprite')
                scene.remove(scene.children[i]);
        };


        $(".elementos_container").empty(); // se vacian las listas de tooltips y links, para luego cargar las de la nueva imagen

        if (jQuery.isEmptyObject(vr_json_obj[current_foto]['links']) == false) {
          $.each(vr_json_obj[current_foto]['links'], function(link){
            var label_to_show = vr_json_obj[current_foto].links[link]['label'];
            var position_x = vr_json_obj[current_foto].links[link]['x'];
            var position_y = vr_json_obj[current_foto].links[link]['y'];
            var position_z = vr_json_obj[current_foto].links[link]['z'];

            $('.lista_links .elementos_container').append("<div class=\"elemento_link_container\" id=\"" + label_to_show + "\"><span class=\"elemento_link\">" + label_to_show + "</span><span class=\"elemento_borrar\"><i class=\"fas fa-times\"></i></span></div>");

            addTooltip(new THREE.Vector3(position_x, position_y, position_z), label_to_show, "../../objetos/dot_orange.svg");
          });
        };

        if (jQuery.isEmptyObject(vr_json_obj[current_foto]['tooltips']) == false) {
          $.each(vr_json_obj[current_foto]['tooltips'], function(tooltip){
            var label_to_show = vr_json_obj[current_foto].tooltips[tooltip]['label'];
            var position_x = vr_json_obj[current_foto].tooltips[tooltip]['x'];
            var position_y = vr_json_obj[current_foto].tooltips[tooltip]['y'];
            var position_z = vr_json_obj[current_foto].tooltips[tooltip]['z'];

            $('.lista_tooltips .elementos_container').append("<div class=\"elemento_link_container\" id=\"" + label_to_show + "\"><span class=\"elemento_link\">" + label_to_show + "</span><span class=\"elemento_borrar\"><i class=\"fas fa-times\"></i></span></div>");

            addTooltip(new THREE.Vector3(position_x, position_y, position_z), label_to_show, "../../objetos/dot_blue.svg");
          });
        };

    };

// ###############  SE CARGA LA FOTO DE LA LISTA AL HACER CLICK EN ELLA #############################################################



        $(".foto_prev_container img").on("click", function(){
          default_image_view = false;
          var referencia = $('#referencia').val();
          var foto_clicked = '../../bienes_inmuebles/' + cookie_pais + '/' + referencia + '/fotos_360/' + $(this).parent().attr('id');
          var foto_key = $(this).parent().attr('name');

          current_foto = foto_key;

          TweenLite.to(sphere.material, 0.5, {opacity: 0, onComplete: function(){next_texture_loader(foto_clicked)}});

        });

// ######################### CODIGO PRA EL CLICK DE LOS BTNs LINK Y TOOLTIP ###########################################################

    $(".btn_link_choice").on("click", function(){
      if ($(this).hasClass('active') == false) {//se activa el boton
        $(this).addClass('active');
      };
      if ($(".btn_tooltip_choice").hasClass('active')) {// se desactiva el de alado
        $(".btn_tooltip_choice").removeClass('active')
      };
      if ($(".choice_select_foto").hasClass('visible') == false) {//se muestra el select
        $(".choice_select_foto").addClass('visible');
      };
      if ($(".choice_description").hasClass('visible')) {//se oculta el textarea
        $(".choice_description").removeClass('visible');
      };
      $('.viewer_imagenes_opcionales').css('visibility', 'hidden');
      $("#tooltip_description").val("");//se hace reset al textarea
      $('#tooltip_description').css('border-color', 'initial');
      $("#tooltip_description_minifoto").val("");//se hace reset al input hidden que almacena la minifoto mini_foto_tag_opcional
      $(".mini_foto_result").empty(); // se quita el contenido del span que muestra la mini foto opcional escogida
      $('.viewer_tooltip_content .imagen_opcional_container').empty();
      let newspriteMap = new THREE.TextureLoader().load("../../objetos/dot_orange.svg");//SE CARGA LA TEXTURA DEL DOT LINK
      spriteMaterial.map = newspriteMap;

      var excluded_fotos = [];
      excluded_fotos.push(current_foto);
      $.each(vr_json_obj[current_foto]['links'], function(link){
        excluded_fotos.push(vr_json_obj[current_foto]['links'][link]['foto'].replace(".jpg", ""));
      });
      var select_foto_link = '<option value=""></option>';
      fotos_keys.forEach(function(key) {// si estamos en first entry se construye el array sin info prellenada
        if (jQuery.inArray( key, excluded_fotos ) == -1) {
          select_foto_link += '<option value="' + key.replace(/~/g, "") + '.jpg">' + key.replace(/~/g, "") + '</option>';
        };
      });
      $("#select_foto_link").html(select_foto_link);

    });

    $(".btn_tooltip_choice").on("click", function(){
      if ($(this).hasClass('active') == false) {//se activa el boton
        $(this).addClass('active');
      };
      if ($(".btn_link_choice").hasClass('active')) {// se desactiva el de alado
        $(".btn_link_choice").removeClass('active')
      };
      if ($(".choice_description").hasClass('visible') == false) {//se muestra el textarea
        $(".choice_description").addClass('visible');
      };
      if ($(".choice_select_foto").hasClass('visible')) {//se oculta el select
        $(".choice_select_foto").removeClass('visible');
      };
      $('.viewer_imagenes_opcionales').css('visibility', 'hidden');
        $('#select_foto_link').prop('selectedIndex',0);// se hace reset al select titulos fotos
      let newspriteMap = new THREE.TextureLoader().load("../../objetos/dot_blue.svg");//SE CARGA LA TEXTURA DEL DOT TOOLTIP
      spriteMaterial.map = newspriteMap;
    });



//################# SE ESCUCHA EL CLICK DERECHO SOBRE LA IMAGEN 360 y SE RECUPERA LAS COORDENADAS DEL CLICK ###################################

        const rayCaster = new THREE.Raycaster();//DEFINE EL RAYO LASER PARA VER INTERSECCIONES
        let click_coodinates = '';

        let spriteMap = new THREE.TextureLoader().load("../../objetos/dot_gray.svg");//SE CARGA LA TEXTURA DEL TOOLTOP
        let spriteMaterial = new THREE.SpriteMaterial({ map: spriteMap, alphaTest: 0.8 });//SE CREA LA TEXTURA con alphatest para asegurar que la trasparencia del svg no sea negra (ya que la foto360 es transparente y el fondo es negro)

        let sprite = new THREE.Sprite(spriteMaterial);//SE CREA EL TOOLTIP CON SU TEXTURA
        sprite.scale.multiplyScalar(2);
        sprite.visible = false; //se oculta el cualquier dot gris por default


        // Estruturacion y posicionamiento DEL TOOLTIP EN PANTALLA
          // function addDefaultTooltip(tooltip_position, tooltip_nombre) {
          function addDefaultTooltip(tooltip_position) {

            sprite.position.copy(tooltip_position.clone().normalize().multiplyScalar(48));//SE PASA LA POSICION EN PARAM AL TOOLTIP, se clona para no modificar la variable original, luego se normaliza para poder multiplicar sus vectores, y luego se lo multiplicar por 40 para que no este en el centro de la escena sino cerca del borde de la esfera
            scene.add(sprite);//SE PONE EL TOOLTIP EN LA ESCENA
            sprite.visible = true;//se muestra el dot gris, ya que por default esta en no visible
          };

        function onviewerClick(event) {//PERMITE CONOCER LAS COORDENADAS EN PANTALLA DEL LUGAR DEL CLICK

            let mouse = new THREE.Vector2((event.clientX / window.innerWidth) * 2 - 1, -(event.clientY / window.innerHeight) * 2 + 1);
            // console.log(mouse);//MUESTRA LAS COORDENADAS EN LA CONSOLA
            rayCaster.setFromCamera(mouse, camera);//DEFINE QUE EL RAYO SALE DE LA CAMARA Y VA EN LA DIRECCION DEL CLICK MOUSE


            let intersects = rayCaster.intersectObject(sphere);//DEFINE LAS INTERSECCIONES CON LA ESFERA
            if (intersects.length > 0) {//SI LA INTERSECTION EXISTE
              click_coodinates = intersects[0].point;
              addDefaultTooltip(intersects[0].point);//LLAMAR A LA FUNCION addDefaultTooltip Y PASARLE LA PRIMERA INTERSECCION, EL PARAM POINT
            };

            // debugger


        };

// ################################ SE DEFINE EL ARRAY QUE SERVIRA PARA LA CONSTRUCCION DEL VR JSON ########################################

  var vr_json_obj = {};

  if (jQuery.isEmptyObject(vr_json_edit) == false) {//si estamos en modo edicion se construye el array con la info existente
    vr_json_obj = vr_json_edit;
    $.each(vr_json_obj, function(element){
      var links_count = Object.keys(vr_json_obj[element]['links']).length;
      var tooltips_count = Object.keys(vr_json_obj[element]['tooltips']).length;

      $('.fotos_gran_container div[name="' + element + '"]').parent().find('.info_foto_prev_container .info_links p').html(links_count + " Links");
      $('.fotos_gran_container div[name="' + element  + '"]').parent().find('.info_foto_prev_container .info_tooltips').html(tooltips_count + " Tooltips");
    });

    var entry_foto_name_charged = "~" + vr_json_obj['VR_ENTRY']['imagen'].replace(".jpg", "") + "~";
    $(".foto_prev_container[name='" + entry_foto_name_charged + "']").parent().find('.check_foto_entry').addClass('checked').html("Entrada");
  }else {

    fotos_keys.forEach(function(key) {// si estamos en first entry se construye el array sin info prellenada
      var element_sub = {};

      element_sub.imagen = key.replace(/~/g, "") + '.jpg';
      element_sub.tooltips = {};
      element_sub.links = {};

      vr_json_obj[key] = element_sub;
    });

  };


//############################ SE DEFINE LA FUNCION QUE AGREGA TOOLTIPS A LA FOTO ACTUAL ########################################

  function addTooltip(tooltip_position, tooltip_nombre, dot_color) {

    let TooltipSpriteMap = new THREE.TextureLoader().load(dot_color);//SE CARGA LA TEXTURA DEL TOOLTOP
    let TooltipSpriteMaterial = new THREE.SpriteMaterial({ map: TooltipSpriteMap, alphaTest: 0.8 });//SE CREA LA TEXTURA con alphatest para asegurar que la trasparencia del svg no sea negra (ya que la foto360 es transparente y el fondo es negro)

    let Tooltip = new THREE.Sprite(TooltipSpriteMaterial);//SE CREA EL TOOLTIP CON SU TEXTURA
    Tooltip.name = tooltip_nombre;
    if (dot_color == '../../objetos/dot_orange.svg') {
      Tooltip.tipo = 'LINK';
    }else {
      Tooltip.tipo = 'TOOLTIP';
    };

    Tooltip.scale.multiplyScalar(2);

    Tooltip.position.copy(tooltip_position.clone().normalize().multiplyScalar(48));//SE PASA LA POSICION EN PARAM AL TOOLTIP, se clona para no modificar la variable original, luego se normaliza para poder multiplicar sus vectores, y luego se lo multiplicar por 40 para que no este en el centro de la escena sino cerca del borde de la esfera
    scene.add(Tooltip);//SE PONE EL TOOLTIP EN LA ESCENA

    if ($(".btn_link_choice").hasClass('active')) {
      $(".btn_link_choice").removeClass('active')
    };

    if ($(".btn_tooltip_choice").hasClass('active')) {
      $(".btn_tooltip_choice").removeClass('active')
    };
  };

// ########################## FUNCION QUE PERMITE MOSTRAR EL LABEL DEL TOOLTIP/LINK AL HACER HOVER ###########################################

  function onmouseMove(event) {// cuando se pasa el cursor encima del sprite se habre la tooltip
    let foundSprite = false;
    let mouse = new THREE.Vector2((event.clientX / window.innerWidth) * 2 - 1, -(event.clientY / window.innerHeight) * 2 + 1);
    rayCaster.setFromCamera(mouse, camera);//DEFINE QUE EL RAYO SALE DE LA CAMARA Y VA EN LA DIRECCION DEL CLICK MOUSE
    let intersects = rayCaster.intersectObjects(scene.children);
    intersects.forEach(function(intersect){//recorre todas las intersecciones y busca si se hizo click en una tooltip
      if (intersect.object.type === 'Sprite') {//si la interseccion es de tipi sprite osea una tooltip
        let p = intersect.object.position.clone().project(camera);//DEFINE LA POSICION PROYECTADA DEL OBJETO 3D SOBRE LA PNATALLA 2D
        tooltip.style.top = ( (-1 * p.y + 1) * window.innerHeight / 2 + 'px' );//DEFINE EL TOP DEL POPUP DECALADO LIGERAMENTE
        tooltip.style.left = ( (p.x + 1) * window.innerWidth / 2 + 'px' );//DEFINE EL LEFT DEL POPUP DECALADO LIGERAMENTE
        tooltip.classList.add('active');//COLOCA LA CLASE ACTIVE
        tooltip.innerHTML = intersect.object.name;//INSERTA EL NOMBRE DE LA TOOLTIP DENTRO DEL POPUP
        sprite_active = intersect.object;//SI SE ABRIO EL MENSAJE ya no es false
        foundSprite = true;//SE PASO EL CURSOR POR ENCIMA DE UNA TOOLTIP- EL CONTADOR PASA A TRUE

        TweenLite.to(intersect.object.scale, 0.5, {//animacion apra agrandar el tooltip al hover, se modifica la esca, dira 0.5s
          x: 3,
          y: 3,
          z: 3
        });

        $('.foto360_container').css('cursor', 'pointer'); //permite que el cursor cambie sobre un sprite
      };

      if (foundSprite === false && sprite_active) {
        tooltip.classList.remove('active');//SI YA NO SE SOBREVUELA NINGUNA TOOTIP SE QUITA LA CLASE ACTIVE A LAS QUE SI
        TweenLite.to(sprite_active.scale, 0.5, {//animacion para restaurar el tamaño de la tooltip al quitar el hover
          x: 2,
          y: 2,
          z: 2
        });
        sprite_active = false;//se vuelve a poner false al quitar el hover
        tooltip.style.top = ( 0 + 'px' );//DEFINE EL TOP DEL POPUP DECALADO LIGERAMENTE
        tooltip.style.left = ( 0 + 'px' );//DEFINE EL LEFT DEL POPUP DECALADO LIGERAMENTE
        $('.foto360_container').css('cursor', 'default');// permite cambiar el cursor cuando ya no se esta sobre un sprite
      };
    });
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
        var next_foto_name = vr_json_obj[current_foto]['links'][intersect.object.name]['foto'];
        var referencia = $('#referencia').val();
        var link_foto_clicked = '../../bienes_inmuebles/' + cookie_pais + '/' + referencia + '/fotos_360/' + next_foto_name;

        current_foto = "~" + next_foto_name.replace(".jpg", "") + "~"; //para obtener el nombre de la foto siguiente
        $('.info_foto_prev_container').removeClass('selected');// se resetea el sombreado en los elementos de lista de fotos
        $('.foto_prev_container[name="' + current_foto + '"]').parent().find('.info_foto_prev_container').addClass('selected');//se coloca el sombreado a a al elemento foto que corresoponde
        next_texture_loader(link_foto_clicked);//se carga la proxima foto
      };

      if (intersect.object.tipo == 'TOOLTIP') {

        let p = intersect.object.position.clone().project(camera);//DEFINE LA POSICION PROYECTADA DEL OBJETO 3D SOBRE LA PNATALLA 2D
        let content_text = vr_json_obj[current_foto]['tooltips'][intersect.object.name]['info'].replace(/\n\r?/g, '<br />');;
        let foto_opcional_content = vr_json_obj[current_foto]['tooltips'][intersect.object.name]['imagen_opcional'];
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

// #################### FUNCION PARA MOSTRAR LA LISTA DE LINKS ASSOCIADOS A UNA FOTO ###############################################

$(".fa-info-circle").hover(function(){// cuando se pasa el cursor encima del sprite se habre la tooltip

 var foto_hovered = $(this).parent().parent().parent().find('.foto_prev_container').attr('name');
 var links_list = "";
 var links_list_container = $(this).find('.links_info_list');

 $.each(vr_json_obj[foto_hovered]['links'], function(link){
   links_list += vr_json_obj[foto_hovered]['links'][link]['foto'].replace(".jpg", "") + '</br>';
   // alert(foto_hovered);
 });

 links_list_container.html(links_list);

});

// ############################## CHECK CARACTERS en INPUTS PARA LINKS Y TOOLTIPS ##################################################

  $('.regex_checked').on('input', function(){

    if ($(this).val().match(/^[\w\d\s+\-áÁéÉíÍóÓúÚñÑ&°$%&()*#@!?_><\/,.\']+$/) == null) {//Si se ingrso un caracter no permitido
      alert('Simbolo/Caracter no permitido');
      $(this).css('border-color', 'rgb(255, 0, 0)')
    }else {
      $(this).css('border-color', 'initial')
    }

  });





// #################### FUNCION QUE CONTROLA EL CLICK EN BTN AGREGAR TOOLTIP/LINK ##################################################

  $(".btn_add").on("click", function(){

    var existing_names = [];
    $.each(vr_json_obj[current_foto]['links'], function(link){
      existing_names.push(vr_json_obj[current_foto]['links'][link]['label']);
    });
    $.each(vr_json_obj[current_foto]['tooltips'], function(tooltip){
      existing_names.push(vr_json_obj[current_foto]['tooltips'][tooltip]['label']);
    });
    $('.viewer_imagenes_opcionales').css('visibility', 'hidden');

    if ($(".btn_link_choice").hasClass('active')) {
      if ($('#choice_titulo').css('border-color') == 'rgb(255, 0, 0)') {
        alert('Carácteres o simbolos no permitidos en el titulo');
      }else {
          if ($("#choice_titulo").val() == '' || $("#select_foto_link").val() == '') {
            alert('Faltan campos por completar');
          }else {
            var link_to = $("#choice_titulo").val();
            var links_count = Object.keys(vr_json_obj[current_foto]['links']).length + 1;

            if (jQuery.inArray( link_to, existing_names ) == -1) {

                addTooltip(click_coodinates, link_to, "../../objetos/dot_orange.svg");

                $(".lista_links .elementos_container").append("<div class=\"elemento_link_container\" id=\"" + link_to + "\"><span class=\"elemento_link\">" + link_to + "</span><span class=\"elemento_borrar\"><i class=\"fas fa-times\"></i></span></div>");

                $('.fotos_gran_container div[name="' + current_foto + '"]').parent().find('.info_foto_prev_container .info_links p').html(links_count + " Links");

                vr_json_obj[current_foto].links[link_to] = {};
                vr_json_obj[current_foto].links[link_to]['label'] = link_to;
                vr_json_obj[current_foto].links[link_to]['foto'] = $("#select_foto_link").val();
                vr_json_obj[current_foto].links[link_to]['x'] = click_coodinates['x'];
                vr_json_obj[current_foto].links[link_to]['y'] = click_coodinates['y'];
                vr_json_obj[current_foto].links[link_to]['z'] = click_coodinates['z'];

                $("#choice_titulo").val("");//se hace reset al titulo input
                $('#select_foto_link').prop('selectedIndex',0);// se hace reset al select titulos fotos

                if ($(".tools_consola").hasClass('visible')) {//se esconde el container
                  $(".tools_consola").removeClass('visible');
                };
                if ($(".choice_select_foto").hasClass('visible')) {//se esconde el select
                  $(".choice_select_foto").removeClass('visible');
                };

                let newspriteMap = new THREE.TextureLoader().load("../../objetos/dot_gray.svg");//SE CARGA LA TEXTURA DEL DOT DEFAULT
                spriteMaterial.map = newspriteMap;

            }else {
              alert('No pueden existir 2 dots con el mismo nombre para una misma imagen');
            };

          };
      };


    };

    if ($(".btn_tooltip_choice").hasClass('active')) {
      if ($("#choice_titulo").val() == '' || $("#tooltip_description").val() == '') {
        alert('Faltan campos por completar');
      }else {
        if ($('#choice_titulo').css('border-color') == 'rgb(255, 0, 0)' || $("#tooltip_description").css('border-color') == 'rgb(255, 0, 0)') {
          alert('Carácteres o simbolos no permitidos en el titulo o en la descripción');
        }else {
          var link_to = $("#choice_titulo").val();
          var tooltips_count = Object.keys(vr_json_obj[current_foto]['tooltips']).length + 1;

          if (jQuery.inArray( link_to, existing_names ) == -1) {

              addTooltip(click_coodinates, link_to, "../../objetos/dot_blue.svg");

              $(".lista_tooltips .elementos_container").append("<div class=\"elemento_tooltip_container\" id=\"" + link_to + "\"><span class=\"elemento_link\">" + link_to + "</span><span class=\"elemento_borrar\"><i class=\"fas fa-times\"></i></span></div>");

              $('.fotos_gran_container div[name="' + current_foto + '"]').parent().find('.info_foto_prev_container .info_tooltips').html(tooltips_count + " Tooltips");

              vr_json_obj[current_foto].tooltips[link_to] = {};
              vr_json_obj[current_foto].tooltips[link_to]['label'] = link_to;
              vr_json_obj[current_foto].tooltips[link_to]['info'] = $("#tooltip_description").val().replace("\n", "<br />");//el json no puede tener saltos de linea de este tipo sino se va todo al CARAJOOOOOO
              vr_json_obj[current_foto].tooltips[link_to]['x'] = click_coodinates['x'];
              vr_json_obj[current_foto].tooltips[link_to]['y'] = click_coodinates['y'];
              vr_json_obj[current_foto].tooltips[link_to]['z'] = click_coodinates['z'];
              if ($("#tooltip_description_minifoto").val() !== '') {
                vr_json_obj[current_foto]['tooltips'][link_to]['imagen_opcional'] = $("#tooltip_description_minifoto").val();
              }else {
                vr_json_obj[current_foto]['tooltips'][link_to]['imagen_opcional'] = '';
              };

              $("#choice_titulo").val("");//se hace reset al titulo input
              $("#tooltip_description").val("");//se hace reset al textarea
              $("#tooltip_description_minifoto").val("");//se hace reset al input hidden que almacena la minifoto mini_foto_tag_opcional
              $(".mini_foto_result").empty(); // se quita el contenido del span que muestra la mini foto opcional escogida
              $('.viewer_tooltip_content .imagen_opcional_container').empty();

              if ($(".tools_consola").hasClass('visible')) {
                $(".tools_consola").removeClass('visible');
              };
              if ($(".choice_description").hasClass('visible')) {//se oculta el textarea
                $(".choice_description").removeClass('visible');
              };

              let newspriteMap = new THREE.TextureLoader().load("../../objetos/dot_gray.svg");//SE CARGA LA TEXTURA DEL DOT DEFAULT
              spriteMaterial.map = newspriteMap;

          }else {
            alert('No pueden existir 2 dots con el mismo nombre para una misma imagen');
          };
        };

      };
    };



  });


// ############################ PARA BORRAR TOOLTIPS O LINKS DE LA LISTA DE LA FOTO ACTUAL ########################################

  $(".lista_links").on("click", ".elemento_borrar", function(){

    var linea_container = $(this).parent();
    var sprite_borrar_nombre = $(this).parent().attr('id');
    var links_count = Object.keys(vr_json_obj[current_foto]['links']).length - 1;

    linea_container.remove();

    scene.getObjectByName( sprite_borrar_nombre ).material.dispose();
    scene.remove(scene.getObjectByName( sprite_borrar_nombre ));

    $('.fotos_gran_container div[name="' + current_foto + '"]').parent().find('.info_foto_prev_container .info_links p').html(links_count + " Links");

    delete vr_json_obj[current_foto]['links'][sprite_borrar_nombre];
  });

  $(".lista_tooltips").on("click", ".elemento_borrar", function(){

    var linea_container = $(this).parent();
    var sprite_borrar_nombre = $(this).parent().attr('id');
    var tooltips_count = Object.keys(vr_json_obj[current_foto]['tooltips']).length - 1;

    linea_container.remove();
    scene.getObjectByName( sprite_borrar_nombre ).material.dispose();
    scene.remove(scene.getObjectByName( sprite_borrar_nombre ));

    $('.fotos_gran_container div[name="' + current_foto + '"]').parent().find('.info_foto_prev_container .info_tooltips').html(tooltips_count + " Tooltips");

    delete vr_json_obj[current_foto]['tooltips'][sprite_borrar_nombre];
  });


// ############################### DYNAMICA DEL POPUP IMAGENES OPCIONALES PARA TOOLTIPS ############################################

  $('.btn_opciones_minifotos').on('click', function(){
    $('.viewer_imagenes_opcionales').css('visibility', 'visible');
  });

  $('.btn_cerrar_imagenes_opcionales').on('click', function(){
    $('.viewer_imagenes_opcionales').css('visibility', 'hidden');
  });

  $('.foto_opcional').on('click', function(){
    var foto_opcional_nombre = $(this).attr('id');
    var foto_opcional_path = $(this).attr('name');
    $('.viewer_imagenes_opcionales').css('visibility', 'hidden');
    $('.mini_foto_result').html(foto_opcional_nombre + '.jpg');
    $('#tooltip_description_minifoto').val(foto_opcional_path);

  });

  $('.tooltip_cerrar').on('click', function(){
    tooltip_content.classList.remove('active');//REMUEVE LA CLASE ACTIVE
    tooltip_content.style.top = 0 + 'px';
    tooltip_content.style.left = ( 0 + 'px' );
  });

// ################################################# BOTON CREAR TOUR VR ###########################################################

  $('.btn_crear_tour').on("click", function(){

    if ($('.checked').length == 0) {
      alert('Debe escoger una imagen de entrada al Tour VR');
    }else {
      var foto_entrada_name = $('.checked').parent().parent().find('.foto_prev_container').attr('name');
      vr_json_obj['VR_ENTRY'] = vr_json_obj[foto_entrada_name];
      $("#vr_tour_string").val(JSON.stringify(vr_json_obj));
      $('#vr_tour_form').submit();
    };


  });

// ################################################ SE DEFINEN LOS EVENTLISTENERS ##########################################################

        window.addEventListener('resize', onResize);//SE LLAMA A onResize SI HAY REDIMENCION

        viewer_container.addEventListener('contextmenu', function(){//al hacer click derecho sobre la imagen 360
          if (default_image_view == false) {//no funciona sobre la imagen default de tutecho
              onviewerClick(event);//se lanza la funcion para colocar un dot gris sobre la esfera el el punto del click derecho
              if ($(".control_left_container").hasClass('visible') == false) {//se abre el menu izquierdo si esta cerrado, para acceder a config del dot
                $(".control_left_container").addClass('visible');
              };
              if ($(".control_right_container").hasClass('visible')) {//se cierra el menu derecho si esta abierto
                $(".control_right_container").removeClass('visible');
              };
              if ($(".tools_consola").hasClass('visible') == false) {
                $(".tools_consola").addClass('visible');
              };
              tooltip_content.classList.remove('active');//Cierra el tooltip_content si estuviera abierto
              tooltip_content.style.top = 0 + 'px';
              tooltip_content.style.left = ( 0 + 'px' );
          };
        });


        viewer_container.addEventListener('click', function(){
          sprite.visible = false; //se oculta el cualquier dot gris en pantalla
          if ($(".control_left_container").hasClass('visible')) {//se cierra el menu izquierda si esta abierto
            $(".control_left_container").removeClass('visible');
          };
          if ($(".control_right_container").hasClass('visible')) {//se cierra el menu derecho si esta abierto
            $(".control_right_container").removeClass('visible');
          };
          if ($(".tools_consola").hasClass('visible')) {
            $(".tools_consola").removeClass('visible');
          };
          $("#choice_titulo").val("");//se hace reset al titulo input
          $('#select_foto_link').prop('selectedIndex',0);// se hace reset al select titulos fotos
          $("#tooltip_description").val("");//se hace reset al textarea
          $("#tooltip_description_minifoto").val("");//se hace reset al input hidden que almacena la minifoto mini_foto_tag_opcional
          $(".mini_foto_result").empty(); // se quita el contenido del span que muestra la mini foto opcional escogida
          $('.regex_checked').css('border-color', 'initial');

          $('.viewer_imagenes_opcionales').css('visibility', 'hidden');

          if ($(".btn_link_choice").hasClass('active')) {// se desactiva el btn LINK
            $(".btn_link_choice").removeClass('active')
          };
          if ($(".btn_tooltip_choice").hasClass('active')) {// se desactiva el btn TOOLTIP
            $(".btn_tooltip_choice").removeClass('active')
          };
          let newspriteMap = new THREE.TextureLoader().load("../../objetos/dot_gray.svg");//SE CARGA LA TEXTURA DEL DOT DEFAULT
          spriteMaterial.map = newspriteMap;

          if ($(".choice_description").hasClass('visible')) {
            $(".choice_description").removeClass('visible');
          };
          if ($(".choice_select_foto").hasClass('visible')) {
            $(".choice_select_foto").removeClass('visible');
          };

          onSpriteClick(event);

        });

        viewer_container.addEventListener('mousemove', onmouseMove);// SE LAMMA AL onviewerClick SI HAY CLICK EN LA PANTALLA













  });
});
