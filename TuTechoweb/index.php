<?php
$php_view_entry_control = "algunvalor";

if (!isset($_COOKIE['tutechopais'])) {
  header('Location: tutechopais.php');
};

require 'index.view.php';
?>
