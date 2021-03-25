<?php
$php_view_entry_control = "algunvalor";

if (!isset($_COOKIE['tutechopais'])) {
  header('Location: tutechopais.php');
};

require 'ficha_bien.view.php';
?>
