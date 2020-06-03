<?php
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <a class="navbar-brand" href="/"><img src="/logo.png" width="58px" alt="MegaFlix Logo"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto" style="font-size: 18px;">
            <li class="nav-item active">
                <a class="nav-link" href="/"><u>Início</u><span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/enter/serie.php">Séries</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/enter/filme.php">Filmes</a>
            </li>

        </ul>
        <form class="form-inline mt-2 mt-md-0" method="get" action="/search.php">
            <div class="container h-100">
                <div class="d-flex justify-content-center h-100">
                    <div class="searchbar">
                        <input class="search_input" type="q" name="q" placeholder="Buscar...">
                        <a href="#" class="search_icon"><i class="fas fa-search"></i></a>
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
            if (!isset($_SESSION['login']) || $_SESSION['login'] != 1) {
                echo '<li class="nav-item">
                        <button class="btn btn-rounded btn-purple" onclick="window.location.href = \'/login/\'"
                            style="border-radius: 22px; background-color: rgb(49, 70, 94);color: white;">Entrar<i
                                class="fas fas fa-sign-in-alt pl-1"></i></button>
                        <button class="btn btn-rounded btn-danger" onclick="window.location.href = \'/login/?register=1\'"
                            style="border-radius: 22px; background-color: rgb();color: white;">Registrar<i
                                class="fas fas fa-user-plus pl-1"></i></button>
                    </li>';
            } else {
                echo '
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            '.$_SESSION['nomeUsuario'].'
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="background-color: rgb(49, 70, 94); ">
                            <a class="dropdown-item" href="#" style="color: white">Lista de favoritos</a>
                            <a class="dropdown-item" href="#" style="color: white">Configurações da conta</a>
                            <a class="dropdown-item" href="/enter/sair.php" style="color: white">Sair</a>
                            </div>
                        </li>';
            }
            ?>
        </ul>
    </div>
</nav>
<br><br><br><br>