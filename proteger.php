<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Se o usuário não estiver logado, redireciona para o login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
