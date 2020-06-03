<?php

if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

if(isset($_SESSION['login']) != 1){
    header("Location: /login/?erro=1");
}
