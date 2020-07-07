<?php
session_start();

$_SESSION['ramon'] = "barbosa";

session_destroy();

session_start();


print_r($_SESSION);

session_destroy();
?>