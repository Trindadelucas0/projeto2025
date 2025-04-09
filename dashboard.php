<?php
session_start();
require_once 'conexao.php';
require_once 'proteger.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Buscar informações do usuário
$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT nome, nivel_acesso, ultimo_login FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Erro na preparação da consulta: " . $conn->error);
}
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

// Buscar itens do dashboard
$sql_dashboard = "SELECT * FROM dashboard_items ORDER BY created_at DESC";
$result_dashboard = $conn->query($sql_dashboard);
$itens_dashboard = [];
if ($result_dashboard) {
    while ($item = $result_dashboard->fetch_assoc()) {
        $itens_dashboard[$item['tipo']][] = $item;
    }
}

// Verificar se as tabelas existem antes de fazer as consultas
$result = $conn->query("SHOW TABLES LIKE 'noticias'");
$noticias_existe = $result->num_rows > 0;

$result = $conn->query("SHOW TABLES LIKE 'frases_motivacionais'");
$frases_existe = $result->num_rows > 0;

// Buscar notícias se a tabela existir
if ($noticias_existe) {
    $sql_noticias = "SELECT * FROM noticias WHERE status = 'ativo' ORDER BY data_publicacao DESC LIMIT 5";
    $result_noticias = $conn->query($sql_noticias);
}

// Buscar frase motivacional se a tabela existir
if ($frases_existe) {
    $sql_frase = "SELECT * FROM frases_motivacionais ORDER BY RAND() LIMIT 1";
    $result_frase = $conn->query($sql_frase);
    $frase = $result_frase->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Dashboard - Sistema Hospitalar</title>
    <link rel="stylesheet" href="estilo-base.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background: var(--gradient);
            color: var(--text-color);
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .container-principal {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
            padding-top: 80px; /* Espaço para o header fixo */
        }

        .welcome-card {
            background: linear-gradient(145deg, var(--card-bg), #343450);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: var(--card-shadow);
            border-left: 4px solid var(--primary-color);
        }

        .welcome-card h2 {
            color: var(--primary-color);
            margin-bottom: 15px;
            font-size: 1.5rem;
            line-height: 1.3;
        }

        .welcome-card p {
            margin: 8px 0;
            color: var(--text-color);
            font-size: 0.95rem;
        }

        .admin-link {
            margin-top: 15px;
        }

        .btn {
            display: inline-block;
            background: var(--gradient-primary);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            font-weight: 500;
            text-align: center;
            width: 100%;
            max-width: 300px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 153, 255, 0.3);
        }

        .dashboard-grid {
            display: grid;
            gap: 15px;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        }

        .dashboard-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
        }

        .dashboard-card h3 {
            color: var(--primary-color);
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-color);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.2rem;
        }

        .dashboard-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            border-left: 3px solid var(--primary-color);
        }

        .dashboard-item p {
            color: var(--text-color);
            line-height: 1.6;
            margin: 0;
            font-size: 0.95rem;
        }

        .motivational-quote {
            background: linear-gradient(145deg, var(--card-bg), #343450);
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            font-style: italic;
            margin-top: 20px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(0, 255, 136, 0.1);
        }

        .motivational-quote p {
            font-size: 1.1em;
            color: var(--text-color);
            margin-bottom: 10px;
            line-height: 1.5;
        }

        .motivational-quote small {
            color: var(--primary-color);
            display: block;
            margin-top: 10px;
            font-size: 0.9em;
        }

        /* Responsividade */
        @media screen and (max-width: 768px) {
            .container-principal {
                padding: 10px;
                padding-top: 70px;
            }

            .welcome-card {
                padding: 15px;
                margin-bottom: 15px;
                border-radius: 10px;
            }

            .welcome-card h2 {
                font-size: 1.3rem;
                margin-bottom: 10px;
            }

            .welcome-card p {
                font-size: 0.9rem;
                margin: 6px 0;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .dashboard-card {
                padding: 15px;
                border-radius: 10px;
            }

            .dashboard-card h3 {
                font-size: 1.1rem;
                margin-bottom: 12px;
            }

            .dashboard-item {
                padding: 12px;
                margin-bottom: 12px;
            }

            .dashboard-item p {
                font-size: 0.9rem;
            }

            .motivational-quote {
                padding: 20px;
                margin-top: 15px;
                border-radius: 10px;
            }

            .motivational-quote p {
                font-size: 1rem;
            }

            .btn {
                padding: 10px 20px;
                font-size: 0.95rem;
            }
        }

        @media screen and (max-width: 480px) {
            .container-principal {
                padding: 8px;
                padding-top: 60px;
            }

            .welcome-card {
                padding: 12px;
                margin-bottom: 12px;
            }

            .welcome-card h2 {
                font-size: 1.2rem;
            }

            .welcome-card p {
                font-size: 0.85rem;
            }

            .dashboard-card {
                padding: 12px;
            }

            .dashboard-card h3 {
                font-size: 1rem;
            }

            .dashboard-item {
                padding: 10px;
                margin-bottom: 10px;
            }

            .dashboard-item p {
                font-size: 0.85rem;
            }

            .motivational-quote {
                padding: 15px;
            }

            .motivational-quote p {
                font-size: 0.95rem;
            }

            .motivational-quote small {
                font-size: 0.8rem;
            }

            .btn {
                padding: 12px 20px;
                font-size: 0.9rem;
            }
        }

        /* Ajustes para telas muito pequenas */
        @media screen and (max-width: 320px) {
            .container-principal {
                padding: 5px;
                padding-top: 55px;
            }

            .welcome-card, .dashboard-card, .motivational-quote {
                padding: 10px;
                margin-bottom: 10px;
            }

            .welcome-card h2 {
                font-size: 1.1rem;
            }

            .dashboard-card h3 {
                font-size: 0.95rem;
            }

            .dashboard-item p {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container-principal">
        <!-- Seção de Boas-vindas -->
        <div class="welcome-card">
            <h2>Bem-vindo(a), <?php echo htmlspecialchars($usuario['nome']); ?>!</h2>
            <p>Nível de Acesso: <?php echo htmlspecialchars($usuario['nivel_acesso']); ?></p>
            <?php if ($usuario['ultimo_login']): ?>
                <p>Último login: <?php echo date('d/m/Y H:i', strtotime($usuario['ultimo_login'])); ?></p>
            <?php endif; ?>
            <?php if ($usuario['nivel_acesso'] === 'admin'): ?>
                <div class="admin-link">
                    <a href="admin/painel.php?guia=dashboard" class="btn">Gerenciar Dashboard</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="dashboard-grid">
            <!-- Dicas de Segurança -->
            <?php if (isset($itens_dashboard['dica_seguranca'])): ?>
            <div class="dashboard-card">
                <h3><i class="fas fa-lock"></i> Dicas de Segurança Digital</h3>
                <?php foreach ($itens_dashboard['dica_seguranca'] as $item): ?>
                    <div class="dashboard-item" style="border-left-color: <?php echo htmlspecialchars($item['cor']); ?>">
                        <p><?php echo nl2br(htmlspecialchars($item['conteudo'])); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Campanhas -->
            <?php if (isset($itens_dashboard['campanha'])): ?>
            <div class="dashboard-card">
                <h3><i class="fas fa-bullseye"></i> Campanhas em Andamento</h3>
                <?php foreach ($itens_dashboard['campanha'] as $item): ?>
                    <div class="dashboard-item" style="border-left-color: <?php echo htmlspecialchars($item['cor']); ?>">
                        <p><?php echo nl2br(htmlspecialchars($item['conteudo'])); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Informações -->
            <?php if (isset($itens_dashboard['info'])): ?>
            <div class="dashboard-card">
                <h3><i class="fas fa-info-circle"></i> Informações Internas</h3>
                <?php foreach ($itens_dashboard['info'] as $item): ?>
                    <div class="dashboard-item" style="border-left-color: <?php echo htmlspecialchars($item['cor']); ?>">
                        <p><?php echo nl2br(htmlspecialchars($item['conteudo'])); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Frases Motivacionais -->
        <?php if (isset($itens_dashboard['motivacao'])): ?>
            <?php foreach ($itens_dashboard['motivacao'] as $item): ?>
                <div class="motivational-quote" style="border-color: <?php echo htmlspecialchars($item['cor']); ?>">
                    <p><?php echo nl2br(htmlspecialchars($item['conteudo'])); ?></p>
                    <small><i class="<?php echo htmlspecialchars($item['icone']); ?>"></i> Sistema Hospitalar</small>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script src="JS/script.js"></script>
</body>
</html> 