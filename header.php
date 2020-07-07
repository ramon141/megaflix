<?php

require 'conexao.php';

$querySelectGenero = mysqli_query($connection, "select * from genero ORDER BY rand() LIMIT 10");


if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark" style="z-index: 999">
    <a class="navbar-brand" href="/"><img src="/logo.png" width="58px" alt="MegaFlix Logo"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto" style="font-size: 18px;">
            <li class="nav-item active">
                <a class="nav-link" href="/"><u>Início</u><span class="sr-only">(current)</span></a>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Categorias
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown" style="background-color: rgb(49, 70, 94); ">
                    <?php
                        while($fetchSelectGenero = mysqli_fetch_array($querySelectGenero)){
                            echo '<a class="dropdown-item" href="/enter/generos.php?id='.$fetchSelectGenero['id_genero'].'" style="color: white">'.$fetchSelectGenero['nome'].'</a>';
                        }

                    ?>
                </div>
            </li>

        </ul>
        <form class="form-inline mt-2 mt-md-0" method="get" action="/search.php" id="formSearch">
            <div class="container h-100">
                <div class="d-flex justify-content-center h-100">
                    <div class="searchbar">
                        <input class="search_input" type="text" name="q" id="q" placeholder="Buscar..." minlength="3" required="">
                        <a href="javascript::" onclick="if(document.getElementById('q').value.length > 3) {document.getElementById('formSearch').submit();}" class="search_icon"><i class="fas fa-search"></i></a>
                    </div>
                </div>
            </div>
        </form>
        <ul class="navbar-nav">
            <li class="nav-item">&nbsp;&nbsp;</li>
            <style>
                .dropdown-menu>a:hover {
                    background-color: rgb(220, 53, 69);
                }
            </style>

            <?php
            if (!isset($_SESSION['login']) || $_SESSION['login'] != 1 && isset($_COOKIE['hashIdUser'])) {
                echo '
                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Megaflix
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="background-color: rgb(49, 70, 94); ">
                <a class="dropdown-item" href="/enter/favoritos.php" style="color: white">Lista de favoritos</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" style="color: white" onclick="window.location.href = \'/login/\'">Entrar<i class="fas fas fa-sign-in-alt pl-1"></i></a>
                <a class="dropdown-item" href="#" style="color: white" onclick="window.location.href = \'/login/?register=1\'">Resgistrar<i class="fas fas fa-user-plus pl-1"></i></a>
                </div>
                </li>

                ';
            } else {
                echo '
                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                '.$_SESSION['nomeUsuario'].'
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="background-color: rgb(49, 70, 94); ">
                <a class="dropdown-item" href="/enter/favoritos.php" style="color: white">Lista de favoritos</a>
                <a class="dropdown-item" href="/enter/configAccount.php" style="color: white">Configurações da conta</a>
                <a class="dropdown-item" href="/enter/sair.php" style="color: white">Sair</a>
                </div>
                </li>';
            }
            ?>
        </ul>
    </div>
</nav>
<br><br><br><br>