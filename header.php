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
        background: var(--card-bg);
        padding: 1rem 2rem;
        display: flex;
        flex-direction: column;
        border-bottom: 1px solid var(--glass-border);
        position: relative;
        backdrop-filter: blur(10px);
        z-index: 1000;
        min-height: 120px;
    }

    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        margin-bottom: 1rem;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .header-left strong {
        color: var(--text-color);
        font-size: 1.1rem;
        white-space: nowrap;
    }

    .profile-pic {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--primary-color);
        box-shadow: 0 0 10px rgba(0, 153, 255, 0.3);
        transition: var(--transition);
        flex-shrink: 0;
    }

    .profile-pic:hover {
        transform: scale(1.1);
        border-color: var(--secondary-color);
    }

    .nav {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        width: 100%;
        padding: 0.5rem 0;
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
        white-space: nowrap;
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
        margin-top: 15px;
        justify-content: flex-end;
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

    /* Botão do menu mobile */
    .menu-toggle {
        display: none;
        background: var(--gradient-primary);
        border: none;
        color: white;
        font-size: 1.8rem;
        cursor: pointer;
        padding: 0.8rem;
        border-radius: 8px;
        transition: var(--transition);
        z-index: 1001;
        width: 50px;
        height: 50px;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(0, 153, 255, 0.3);
        position: fixed;
        bottom: 20px;
        left: 20px;
    }

    .menu-toggle:hover {
        transform: scale(1.05);
    }

    /* Overlay para quando o menu estiver aberto */
    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        z-index: 998;
        backdrop-filter: blur(5px);
        opacity: 0;
        transition: opacity 0.7s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .overlay.active {
        display: block;
        opacity: 1;
    }

    /* Menu lateral */
    .sidebar {
        display: block;
        position: fixed;
        top: 0;
        left: -280px;
        width: 280px;
        height: 100vh;
        background: var(--gradient-dark);
        backdrop-filter: blur(10px);
        z-index: 999;
        transition: left 0.7s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: var(--card-shadow);
        border-right: 1px solid var(--glass-border);
        overflow-y: auto;
        visibility: hidden;
    }

    .sidebar.active {
        left: 0;
        visibility: visible;
    }

    .sidebar-header {
        padding: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid var(--glass-border);
        background: var(--gradient-primary);
    }

    .sidebar-header h3 {
        color: white;
        margin: 0;
        font-size: 1.2rem;
        background: var(--gradient-primary);
        -webkit-background-clip: text;
        background-clip: text;
        font-weight: 600;
    }

    .sidebar-close {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        color: var(--text-color);
        padding: 1rem;
        width: 100%;
        border-radius: 8px;
        font-size: 1.1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 1rem;
        transition: all 0.3s ease;
    }

    .sidebar-close i {
        font-size: 1.2rem;
        color: var(--primary-color);
        transition: var(--transition);
    }

    .sidebar-close:hover {
        background: var(--gradient-primary);
        color: white;
        transform: translateX(5px);
        border-color: transparent;
    }

    .sidebar-close:hover i {
        color: white;
        transform: rotate(90deg);
    }

    .sidebar-content {
        padding: 1rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-height: calc(100vh - 80px);
    }

    .sidebar-nav {
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
    }

    .sidebar-nav a {
        opacity: 0;
        transform: translateX(-20px);
        transition: all 0.5s ease;
        transition-delay: calc(var(--i) * 0.1s);
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        color: var(--text-color);
        text-decoration: none;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 8px;
        font-weight: 500;
    }

    .sidebar.active .sidebar-nav a {
        opacity: 1;
        transform: translateX(0);
    }

    .sidebar-nav a:hover {
        background: var(--gradient-primary);
        transform: translateX(5px);
        color: white;
        border-color: transparent;
    }

    .sidebar-nav a i {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: var(--primary-color);
        transition: var(--transition);
    }

    .sidebar-nav a:hover i {
        color: white;
    }

    /* Animação para o botão de menu */
    .menu-toggle i {
        transition: transform 0.7s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .menu-toggle.active i {
        transform: rotate(180deg);
    }

    /* Animação para o botão de fechar */
    .sidebar-close {
        transition: transform 0.7s cubic-bezier(0.34, 1.56, 0.64, 1), color 0.3s ease;
    }

    .sidebar-close:hover {
        transform: rotate(90deg);
    }

    /* Efeito de pulso para o botão de menu */
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(0, 153, 255, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(0, 153, 255, 0); }
        100% { box-shadow: 0 0 0 0 rgba(0, 153, 255, 0); }
    }

    .menu-toggle {
        animation: pulse 2s infinite;
    }

    .menu-toggle.active {
        animation: none;
    }

    /* Responsividade */
    @media screen and (max-width: 768px) {
        .header {
            padding: 1rem;
            min-height: 80px;
        }

        .header-top {
            margin-bottom: 0;
        }

        .header-left strong {
            font-size: 1rem;
        }

        .menu-toggle {
            display: flex;
        }

        .nav {
            display: none;
        }
    }

    @media screen and (max-width: 480px) {
        .header-left strong {
            max-width: 100px;
            font-size: 0.9rem;
        }
    }
</style>
<link rel="stylesheet" href="mensalidade-ui.css">
<link rel="stylesheet" href="perfil.css">
<div class="header">
    <?php if (isset($_SESSION['usuario_id'])): ?>
        <div class="header-top">
            <div class="header-left">
                <img src="<?= $_SESSION['foto'] ?? 'uploads/perfil/default.jpg' ?>" alt="Perfil" class="profile-pic">
                <strong>Olá, <?= $_SESSION['nome'] ?? 'Usuário' ?>!</strong>
            </div>

            <button class="menu-toggle" id="menuToggle" aria-label="Menu">
                <i class="fas fa-bars"></i>
            </button>
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
        <div class="header-top">
            <div class="header-left">
                <strong>Bem-vindo!</strong>
            </div>

            <button class="menu-toggle" id="menuToggle" aria-label="Menu">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <div class="auth-buttons">
            <a href="login.php">Login</a>
            <a href="cadastro.php">Registrar</a>
        </div>
    <?php endif; ?>
</div>

<!-- Overlay para quando o menu estiver aberto -->
<div class="overlay" id="overlay"></div>

<!-- Menu lateral -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h3>Menu</h3>
        <button class="sidebar-close" id="sidebarClose">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="sidebar-content">
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <div class="sidebar-nav">
                <a href="index.php" style="--i: 1"><i class="fas fa-home"></i> Início</a>
                <a href="ver-registros.php" style="--i: 2"><i class="fas fa-clipboard-list"></i> Registros</a>
                <a href="mensalidade.php" style="--i: 3"><i class="fas fa-credit-card"></i> Mensalidade</a>
                <a href="perfil.php" style="--i: 4"><i class="fas fa-user"></i> Perfil</a>
                <a href="comentario.php" style="--i: 5"><i class="fas fa-comment"></i> Avaliação</a>
                <a href="registro-ponto.php" style="--i: 6"><i class="fas fa-clock"></i> Registrar Ponto</a>
                <a href="meus-relatorios.php" style="--i: 7"><i class="fas fa-chart-bar"></i> Relatórios Mensais</a>
                <a href="logout.php" style="--i: 8"><i class="fas fa-sign-out-alt"></i> Sair</a>
            </div>
        <?php else: ?>
            <div class="sidebar-nav">
                <a href="login.php" style="--i: 1"><i class="fas fa-sign-in-alt"></i> Login</a>
                <a href="cadastro.php" style="--i: 2"><i class="fas fa-user-plus"></i> Registrar</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarClose = document.getElementById('sidebarClose');
    const overlay = document.getElementById('overlay');
    
    // Abrir/fechar menu (toggle)
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            menuToggle.classList.toggle('active');
            
            // Alternar entre ícone de menu e X
            const icon = this.querySelector('i');
            if (icon) {
                if (sidebar.classList.contains('active')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                    document.body.style.overflow = 'hidden'; // Impede rolagem do body
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                    document.body.style.overflow = ''; // Restaura rolagem do body
                }
            }
        });
    }
    
    // Fechar menu
    if (sidebarClose) {
        sidebarClose.addEventListener('click', function() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            menuToggle.classList.remove('active');
            document.body.style.overflow = ''; // Restaura rolagem do body
            
            // Restaurar ícone de menu
            const icon = menuToggle.querySelector('i');
            if (icon) {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    }
    
    // Fechar menu ao clicar no overlay
    if (overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            menuToggle.classList.remove('active');
            document.body.style.overflow = ''; // Restaura rolagem do body
            
            // Restaurar ícone de menu
            const icon = menuToggle.querySelector('i');
            if (icon) {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    }
    
    // Fechar menu ao pressionar ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            menuToggle.classList.remove('active');
            document.body.style.overflow = ''; // Restaura rolagem do body
            
            // Restaurar ícone de menu
            const icon = menuToggle.querySelector('i');
            if (icon) {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        }
    });
});
</script>
