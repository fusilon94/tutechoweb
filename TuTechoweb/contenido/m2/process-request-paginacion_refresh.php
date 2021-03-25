<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Verificar que se envio la solicitud por AJAX

$page_requested = $_POST["page_requested"];
$numero_paginas = ceil($_POST["numero_paginas"]);

// Definicion de variables que condicionaran el estado de cada boton en la paginacion_refresh

    if ($page_requested == 1) {
       $btn_backward_disabled = 'disabled';
       $btn_backward_value = 0;
       $btn_num1_active = 'active';}
    else {
      $btn_backward_disabled = '';
      $btn_backward_value = ($page_requested - 1);
      $btn_num1_active = '';};

    if ($numero_paginas >= 3 && $numero_paginas <= 5){
        $btn_num2_value = 2;};

    if ($numero_paginas >= 3 && $numero_paginas <= 5){
        $btn_num2_value = 2;};

    if ($numero_paginas >= 4 && $numero_paginas <= 5){
        $btn_num3_value = 3;};

    if ($numero_paginas == 5){
        $btn_num4_value = 4;};


    if ($numero_paginas > 5 && $page_requested <= 3){
        $btn_num2_value = 2;
        $btn_num3_value = 3;
        $btn_num4_value = 4;};

    if ($numero_paginas > 5 && $page_requested > 3 && $page_requested < ($numero_paginas - 1)){
      $btn_num2_value = ($page_requested - 1);
      $btn_num3_value = $page_requested;
      $btn_num4_value = ($page_requested + 1);
    };

    if ($numero_paginas > 5 && $page_requested >= ($numero_paginas - 1)){
      $btn_num2_value = ($numero_paginas - 3);
      $btn_num3_value = ($numero_paginas - 2);
      $btn_num4_value = ($numero_paginas - 1);
    };

    if (($numero_paginas >= 2 && $page_requested == $numero_paginas) || $numero_paginas == 1){
      $btn_foward_disabled = 'disabled';
      $btn_foward_value = 0;
    } else {
      $btn_foward_disabled = '';
      $btn_foward_value = ($page_requested + 1);
    };

    if ($numero_paginas >= 2 && $page_requested == $numero_paginas){
      $btn_num5_active = 'active';
    } else {
      $btn_num5_active = '';
    };

    if ($numero_paginas >= 3 && $page_requested == 2){
      $btn_num2_active = 'active';
    } else {
      $btn_num2_active = '';
    };

    if (($numero_paginas >= 4 && $page_requested == 3) || ($numero_paginas > 4 && $page_requested == ($numero_paginas - 2)) || ($numero_paginas > 5 && $page_requested >3 && $page_requested < ($numero_paginas - 1))){
      $btn_num3_active = 'active';
    } else {
      $btn_num3_active = '';
    };

    if (($numero_paginas == 5 && $page_requested == 4) || ($numero_paginas > 5 && $page_requested == ($numero_paginas - 1))){
      $btn_num4_active = 'active';
    } else {
      $btn_num4_active = '';
    };


// Estructura dinamica de la paginacion boton por boton

    echo "<li name='btn_backward' class='pag_buton pag_backward ".$btn_backward_disabled."' value='".$btn_backward_value."'><p>&lt;</p></li>"; // boton backward

    echo "<li name='btn_num1' class='pag_buton pag_num ".$btn_num1_active."' value='1'><p>1</p></li>"; // boton num #1

    if ($numero_paginas >= 6 && $page_requested >= 4) { // boton [...] principio
    echo "<li name='btn_dots_first' class='pag_dots pag_first_dots'><p>...</p></li>";
    };

    if ($numero_paginas >= 3) { // boton num #2
    echo "<li name='btn_num2' class='pag_buton pag_num ".$btn_num2_active."' value='".$btn_num2_value."'><p>".$btn_num2_value."</p></li>";
    };

    if ($numero_paginas >= 4) { // boton num #3
    echo "<li name='btn_num3' class='pag_buton pag_num ".$btn_num3_active."' value='".$btn_num3_value."'><p>".$btn_num3_value."</p></li>";
    };

    if ($numero_paginas >= 5) { // boton num #4
    echo "<li name='btn_num4' class='pag_buton pag_num ".$btn_num4_active."' value='".$btn_num4_value."'><p>".$btn_num4_value."</p></li>";
    };

    if ($numero_paginas >= 6 && $page_requested < ($numero_paginas - 2)) { // boton [...] final
    echo "<li name='btn_dots_last' class='pag_dots pag_last_dots'><p>...</p></li>";
    };

    if ($numero_paginas >= 2) { // boton num #last
    echo "<li name='btn_num5' class='pag_buton pag_num ".$btn_num5_active."' value='".$numero_paginas."'><p>".$numero_paginas."</p></li>";
    };

    echo "<li name='page_tracker_movile' class='page_tracker_movile'><p>".$page_requested."/".$numero_paginas."</p></li>"; // boton foward

    echo "<li name='btn_foward' class='pag_buton pag_foward ".$btn_foward_disabled."' value='".$btn_foward_value."'><p>&gt;</p></li>"; // boton foward

}
?>
