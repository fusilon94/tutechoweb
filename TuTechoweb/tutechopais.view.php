<?php
if(isset($php_view_entry_control)){} else{header('Location: tutechopais.php');}; //para evitar que alguien entre directamente al .view.php y porque en los view no se abre ninguna session
?>
<!DOCTYPE html>
<html lang="es">
 <head>
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">
<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
<meta name="msapplication-TileColor" content="#2d89ef">
<meta name="theme-color" content="#ffffff">
      <title>Escoje tu país</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="Description" content="#">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

      <link rel="stylesheet" type="text/css" href="estilos.css">
      <link rel="stylesheet" href="css/font_awesome.css">
      <link rel="stylesheet" href="css/estilos_tutechopais.css">
      <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">

 </head>
 <body>

 <div id="fondo"></div>
      <div id="contenedor_total">
<!-- BARRA DE NAVEGACION -->
      	  <header>
      	    <div id="contenedor_menu_boton"> <!-- Boton de menu visible en pantallas mobiles -->
                <ul>
                  <li><a href="#"><img src="objetos/logotipo2.svg" alt="Tu Techo.com" class="menu_logo"></a></li>
                </ul>
            </div>
      	    <nav class="scroll">
      	     <div id="menu">
                <ul class="ulmenu">
                  <li class="logo_element limenu"><a href="#" class="a_menu"><img src="objetos/logotipo2.svg" alt="Tu Techo.com" class="logo_img"></a></li>
                </ul>
             </div>
           </nav>
          </header>

<!-- CONTENIDO PRINCIPAL -->
<main>
  <h1 class="titulo">Escoje tu país</h1>
  <hr class="barra">


    <form id="formulario_tutechopais" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

    <div class="contenedor_comandos_consola">

      <?php
            foreach ($paises as $pais) {
                  echo"
                  <div class=\"comando_consola\">
                        <button type=\"submit\" name=\"" . $pais['pais'] . "\" value=\"" . $pais['pais'] . "\" class=\"boton_fin_formulario\">
                              <img src=\"objetos/flag_" . $pais['pais'] . ".svg\" alt=\"" . $pais['pais'] . "\">
                              <p>" . ucfirst($pais['pais']) . "</p>
                        </button>
                  </div>
                  ";
            };
      ?>

    </div>

    </form>


</main>
    </div>

 </body>
</html>
