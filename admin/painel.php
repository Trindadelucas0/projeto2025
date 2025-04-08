<?php
session_start();
require_once '../conexao.php';

// Verifica se é admin
if (!isset($_SESSION['usuario_id']) || ($_SESSION['tipo'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit();
}

// Define a guia padrão
$guia = $_GET['guia'] ?? 'acesso';

// Caminho do conteúdo
$guia_path = "guias/{$guia}.php";
if (!file_exists($guia_path)) $guia_path = "guias/acesso.php";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
<<<<<<< HEAD
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

        /* Reset e estilos base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background: var(--gradient);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Container principal */
        .painel-container {
            display: flex;
            min-height: 100vh;
        }

        /* Menu lateral */
        .menu-lateral {
            width: 280px;
            background: var(--card-bg);
            padding: 2rem;
            border-right: 1px solid var(--glass-border);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .menu-lateral h2 {
            color: var(--primary-color);
            font-size: 1.8rem;
            margin-bottom: 2rem;
            text-align: center;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .menu-lateral ul {
            list-style: none;
        }

        .menu-lateral li {
            margin-bottom: 0.5rem;
        }

        .menu-lateral a {
            display: block;
            padding: 0.8rem 1rem;
            color: var(--text-color);
            text-decoration: none;
            border-radius: 10px;
            transition: var(--transition);
        }

        .menu-lateral a:hover {
            background: var(--glass-bg);
            transform: translateX(5px);
        }

        .menu-lateral a.active {
            background: var(--gradient-primary);
            color: white;
        }

        .menu-lateral .voltar {
            margin-top: 2rem;
            text-align: center;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
        }

        .menu-lateral .voltar:hover {
            background: var(--gradient-primary);
            transform: none;
        }

        /* Conteúdo principal */
        .painel-conteudo {
            flex: 1;
            margin-left: 280px;
            padding: 2rem;
        }

        /* Responsividade */
        @media screen and (max-width: 768px) {
            .painel-container {
                flex-direction: column;
            }

            .menu-lateral {
                width: 100%;
                height: auto;
                position: relative;
                padding: 1rem;
            }

            .painel-conteudo {
                margin-left: 0;
                padding: 1rem;
            }

            .menu-lateral h2 {
                font-size: 1.5rem;
            }
        }
    </style>
=======
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="painel.css">
    <link rel="stylesheet" href="guias.css">
>>>>>>> 6a4371b505c2969eaa843912487b25d6dab79c77
</head>
<body>

<div class="painel-container">
    <aside class="menu-lateral">
<<<<<<< HEAD
        <h2>👤 Admin</h2>
        <ul>
            <li><a href="?guia=acesso" class="<?php echo $guia === 'acesso' ? 'active' : ''; ?>">🔐 Acesso</a></li>
            <li><a href="?guia=textos" class="<?php echo $guia === 'textos' ? 'active' : ''; ?>">📝 Textos do Site</a></li>
            <li><a href="?guia=comentarios" class="<?php echo $guia === 'comentarios' ? 'active' : ''; ?>">💬 Comentários</a></li>
            <li><a href="?guia=satisfacao" class="<?php echo $guia === 'satisfacao' ? 'active' : ''; ?>">📊 Satisfação</a></li>
            <li><a href="?guia=resumo-funcionarios" class="<?php echo $guia === 'resumo-funcionarios' ? 'active' : ''; ?>">📊 Resumo de Funcionários</a></li>
            <li><a href="?guia=todos-registros" class="<?php echo $guia === 'todos-registros' ? 'active' : ''; ?>">📁 Todos os Registros</a></li>
            <li><a href="?guia=relatorios-mensais" class="<?php echo $guia === 'relatorios-mensais' ? 'active' : ''; ?>">📁 Relatório Mensal</a></li>
=======
        <h2><i class="fas fa-shield-alt"></i> Painel Admin</h2>
        <ul>
            <li><a href="?guia=acesso" class="<?= $guia === 'acesso' ? 'active' : '' ?>">
                <i class="fas fa-key"></i> Acesso
            </a></li>
            <li><a href="?guia=textos" class="<?= $guia === 'textos' ? 'active' : '' ?>">
                <i class="fas fa-edit"></i> Textos do Site
            </a></li>
            <li><a href="?guia=comentarios" class="<?= $guia === 'comentarios' ? 'active' : '' ?>">
                <i class="fas fa-comments"></i> Comentários
            </a></li>
            <li><a href="?guia=satisfacao" class="<?= $guia === 'satisfacao' ? 'active' : '' ?>">
                <i class="fas fa-chart-bar"></i> Satisfação
            </a></li>
            <li><a href="?guia=resumo-funcionarios" class="<?= $guia === 'resumo-funcionarios' ? 'active' : '' ?>">
                <i class="fas fa-users"></i> Resumo de Funcionários
            </a></li>
            <li><a href="?guia=todos-registros" class="<?= $guia === 'todos-registros' ? 'active' : '' ?>">
                <i class="fas fa-folder"></i> Todos os Registros
            </a></li>
            <li><a href="?guia=relatorios-mensais" class="<?= $guia === 'relatorios-mensais' ? 'active' : '' ?>">
                <i class="fas fa-file-alt"></i> Relatório Mensal
            </a></li>
>>>>>>> 6a4371b505c2969eaa843912487b25d6dab79c77
        </ul>
        <a href="../index.php" class="voltar">
            <i class="fas fa-arrow-left"></i> Voltar ao Site
        </a>
    </aside>

    <main class="painel-conteudo">
        <?php include $guia_path; ?>
    </main>
</div>

</body>
</html>
