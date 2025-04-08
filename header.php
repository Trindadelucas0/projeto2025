<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
require_once 'conexao.php';

// Recuperar a foto do banco se não estiver na sessão
if (!isset($_SESSION['foto']) && isset($_SESSION['usuario_id'])) {
    $id = $_SESSION['usuario_id'];
    $sql = "SELECT foto FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($foto);
    $stmt->fetch();
    $_SESSION['foto'] = $foto ?? 'uploads/perfil/default.jpg';
    $stmt->close();
}
?>

<style>
    /* Variáveis de cores e estilos */
    :root {
        --primary-color: #0099ff;
        --secondary-color: #0055ff;
        --gradient: linear-gradient(130deg, #131313, #1c1c1c);
        --gradient-primary: linear-gradient(45deg, #0099ff, #0055ff);
        --gradient-dark: linear-gradient(130deg, #0a0a0a, #131313);
        --text-color: #e0e0e0;
        --light-text: #6c757d;
        --background-color: #131313;
        --card-bg: rgba(36, 36, 53, 0.8);
        --card-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        --success-color: #00ff88;
        --error-color: #ff4444;
        --glass-bg: rgba(255, 255, 255, 0.05);
        --glass-border: rgba(255, 255, 255, 0.1);
    }

    /* Estilos do cabeçalho */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 2rem;
        background: var(--gradient-dark);
        color: var(--text-color);
        flex-wrap: wrap;
        box-shadow: var(--card-shadow);
        position: sticky;
        top: 0;
        z-index: 100;
        border-bottom: 1px solid var(--glass-border);
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .profile-pic {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--primary-color);
        box-shadow: 0 0 10px rgba(0, 153, 255, 0.3);
        transition: var(--transition);
    }

    .profile-pic:hover {
        transform: scale(1.1);
        border-color: var(--secondary-color);
    }

    .nav {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .nav a {
        color: var(--text-color);
        text-decoration: none;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: var(--transition);
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
    }

    .nav a:hover {
        background: var(--gradient-primary);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 153, 255, 0.3);
    }

    .auth-buttons {
        display: flex;
        gap: 1rem;
    }

    .auth-buttons a {
        background: var(--gradient-primary);
        color: white;
        padding: 0.8rem 1.5rem;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 500;
        transition: var(--transition);
    }

    .auth-buttons a:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 153, 255, 0.3);
    }

    /* Responsividade */
    @media screen and (max-width: 768px) {
        .header {
            padding: 1rem;
            flex-direction: column;
            gap: 1rem;
        }

        .nav {
            flex-direction: column;
            width: 100%;
        }

        .nav a {
            width: 100%;
            text-align: center;
        }

        .auth-buttons {
            width: 100%;
            flex-direction: column;
        }

        .auth-buttons a {
            width: 100%;
            text-align: center;
        }
    }
</style>
<link rel="stylesheet" href="mensalidade-ui.css">
<link rel="stylesheet" href="perfil.css">
<div class="header">
    <?php if (isset($_SESSION['usuario_id'])): ?>
        <div class="header-left">
            <img src="<?= $_SESSION['foto'] ?? 'uploads/perfil/default.jpg' ?>" alt="Perfil" class="profile-pic">
            <strong>Olá, <?= $_SESSION['nome'] ?? 'Usuário' ?>!</strong>
        </div>

        <div class="nav">
            <a href="index.php">🏠 Início</a>
            <a href="ver-registros.php">📋 Registros</a>
            <a href="mensalidade.php">💳 Mensalidade</a>
            <a href="perfil.php">👤 Perfil</a>
            <a href="comentario.php">💬 Avaliação</a>
            <a href="registro-ponto.php">🕒 Registrar Ponto</a>
            <a href="meus-relatorios.php">📊 Relatórios Mensais</a>
            <a href="logout.php">🚪 Sair</a>
        </div>
    <?php else: ?>
        <div class="header-left">
            <strong>Bem-vindo!</strong>
        </div>
        <div class="auth-buttons">
            <a href="login.php">Login</a>
            <a href="cadastro.php">Registrar</a>
        </div>
    <?php endif; ?>
</div>
