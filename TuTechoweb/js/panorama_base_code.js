// ##################__#############################################################__#####################################
// ################_|**|_#########################################################_|**|_###################################
// ################\****/################## CODIGO BASE PARA EL THREE.JS #########\****/###################################
// #################\**/###########################################################\**/####################################
// ##################\/#############################################################\/#####################################

  //CREACION DE LA ESCENA, CAMARAS, RENDERIZADORES Y CONTROLES
     const viewer_container = document.getElementById('media_viewer_container');
     const tooltip = document.querySelector('.viewer_tooltip');
     const tooltip_content = document.querySelector('.viewer_tooltip_content');
     const rayCaster = new THREE.Raycaster();//DEFINE EL RAYO LASER PARA VER INTERSECCIONES
     let sprite_active = false
     let default_image_view = true;

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

     // SE COLOCA EL PRITE BOTTOM#########################

      let TutechoSpriteMap = new THREE.TextureLoader().load('../../objetos/logotipoSpriteBottom.svg');//SE CARGA LA TEXTURA
      let TutechoSpriteMaterial = new THREE.SpriteMaterial({ map: TutechoSpriteMap, alphaTest: 0.8 });//SE CREA LA TEXTURA con alphatest para asegurar que la trasparencia del svg no sea negra (ya que la foto360 es transparente y el fondo es negro)
      let posicion_sprite = new THREE.Vector3(0, -1, 0);

      let TutechoSprite = new THREE.Sprite(TutechoSpriteMaterial);//SE CREA EL SPRITE CON SU TEXTURA
      TutechoSprite.scale.multiplyScalar(10);
      TutechoSprite.position.copy(posicion_sprite.clone().normalize().multiplyScalar(25));
      scene.add(TutechoSprite);//SE PONE EL TOOLTIP EN LA ESCENA

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

         // EVENTS LISTENERS ############################################

           window.addEventListener('resize', onResize);//SE LLAMA A onResize SI HAY REDIMENCION

       // SE LANZA LA ANIMACION SI EL NAVEGADOR DEL CLIENTE LO SOPORTA ############################

         if ( WEBGL.isWebGLAvailable() ) {//VERIFICAMOS QUE EL NAVEGADOR SOPORTA WEBGL EL MOTOR DE RENDERIZACION QUE USAMOS

          animate();//RENDERIZAR - MOSTRAR LA ANIMACION

         } else {// SI EL NAVEGADOR NO LO SOPORTA ENTONCES SE MUESTRA UN ERROR

          var warning = WEBGL.getWebGLErrorMessage();
          alert("Su Navegador no Soporta esta Funcion");
          alert(warning);
          $(".overlay_popup_ficha_bien").removeClass('viewer_opened');//volver a mostrar el scroll de la ficha bien
          $('.overlay_media_viewer').toggleClass('active');// cerrar el viewer
         };

// ############################################################################################################################
// ######################################### FIN CODIGO BASE ##################################################################
// ############################################################################################################################


// ############### SE DEFINE LA FUNCION PARA CARGAR NUEVAS IMAGENES 360 AL VISUALIZADOR #############################################


   function next_texture_loader(new_foto) {

       camera.position.set( -1, 0, 0 );//SE REESTABLECE LA POSICION DE LA CAMARA PARA POSTERIORES ENTRADAS AL VIEWER
       controls.update();//REFRESH LOS CONTROLES AL MOVER LA CAMARA - SIEMPRE PONER ESO DESPUES DE CUALQUIER CAMBIO MANUAL DE LA CAMARA

       new_texture = new THREE.TextureLoader().load(new_foto);//CARGA LA TEXTURA
       new_texture.wrapS = THREE.RepeatWrapping;//PERMITE LA INVERSION DE TEXTURAS
       new_texture.repeat.x = -1;//INVIERTE LA TEXTURA

       material.map = new_texture;
       TweenLite.to(sphere.material, 2, {
         opacity: 1
       });

       if (viewer_mode == 'tour_VR') {

             for (let i = scene.children.length - 1; i >= 0; i--) {//para remover todos los sprites, se debe hacer este reverse loop, porque al hacer remove, el elemento desaparece del array o lista, y se decalan los indexes, dejando siempre un sprite sin borrar
                 if(scene.children[i].type === 'Sprite')
                  if (scene.children[i].tipo === 'LINK' || scene.children[i].tipo === 'TOOLTIP') {
                    scene.remove(scene.children[i]);
                  };
             };

             if (jQuery.isEmptyObject(tour_vr_json[current_foto]['links']) == false) {
               $.each(tour_vr_json[current_foto]['links'], function(link){
                 var label_to_show = tour_vr_json[current_foto].links[link]['label'];
                 var position_x = tour_vr_json[current_foto].links[link]['x'];
                 var position_y = tour_vr_json[current_foto].links[link]['y'];
                 var position_z = tour_vr_json[current_foto].links[link]['z'];

                 addTooltip(new THREE.Vector3(position_x, position_y, position_z), label_to_show, "../../objetos/dot_orange.svg");
               });
             };

             if (jQuery.isEmptyObject(tour_vr_json[current_foto]['tooltips']) == false) {
               $.each(tour_vr_json[current_foto]['tooltips'], function(tooltip){
                 var label_to_show = tour_vr_json[current_foto].tooltips[tooltip]['label'];
                 var position_x = tour_vr_json[current_foto].tooltips[tooltip]['x'];
                 var position_y = tour_vr_json[current_foto].tooltips[tooltip]['y'];
                 var position_z = tour_vr_json[current_foto].tooltips[tooltip]['z'];

                 addTooltip(new THREE.Vector3(position_x, position_y, position_z), label_to_show, "../../objetos/dot_blue.svg");
               });
             };

       };

       if ($(".control_right_container").hasClass('visible')) {
         $(".control_right_container").removeClass('visible')
       };

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

    };



    // ########################## FUNCION QUE PERMITE MOSTRAR EL LABEL DEL TOOLTIP/LINK AL HACER HOVER ###########################################

     function onmouseMove(event) {// cuando se pasa el cursor encima del sprite se habre la tooltip
       let foundSprite = false;
       let mouse = new THREE.Vector2((event.clientX / window.innerWidth) * 2 - 1, -(event.clientY / window.innerHeight) * 2 + 1);
       rayCaster.setFromCamera(mouse, camera);//DEFINE QUE EL RAYO SALE DE LA CAMARA Y VA EN LA DIRECCION DEL CLICK MOUSE
       let intersects = rayCaster.intersectObjects(scene.children);
       intersects.forEach(function(intersect){//recorre todas las intersecciones y busca si se hizo click en una tooltip
         if (intersect.object.type === 'Sprite') {//si la interseccion es de tipi sprite osea una tooltip
           if (intersect.object.tipo === 'LINK' || intersect.object.tipo === 'TOOLTIP') {
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

               $('.media_viewer_container').css('cursor', 'pointer'); //permite que el cursor cambie sobre un sprite
           };
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
           $('.media_viewer_container').css('cursor', 'default');// permite cambiar el cursor cuando ya no se esta sobre un sprite
         };
       });
     };


 // ################################################ SE DEFINEN LOS EVENTLISTENERS ##########################################################

     viewer_container.addEventListener('click', function(){
       if ($(".control_right_container").hasClass('visible')) {//se cierra el menu derecho si esta abierto
         $(".control_right_container").removeClass('visible');
       };
       if (viewer_mode == 'tour_VR') {
         onSpriteClick(event);
       };
     });

     viewer_container.addEventListener('touchstart', function(){
       console.log("TOUCH");
       if ($(".control_right_container").hasClass('visible')) {//se cierra el menu derecho si esta abierto
         $(".control_right_container").removeClass('visible');
       };
       if (viewer_mode == 'tour_VR') {
         onSpriteTouch(event.touches[0]);
       };
     });


     viewer_container.addEventListener('mousemove', function(event){
       if (viewer_mode == 'tour_VR') {
       onmouseMove(event);
       };
     });// SE LAMMA AL onviewerClick SI HAY CLICK EN LA PANTALLA
