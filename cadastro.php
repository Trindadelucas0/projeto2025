<?php
require_once 'conexao.php';
$tipo_mensagem = '';
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    // Verificar se o email já existe
    $sql = "SELECT id FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $tipo_mensagem = 'error';
        $mensagem = "Este email já está cadastrado. Por favor, use outro email ou faça login.";
    } else if ($senha !== $confirmar_senha) {
        $tipo_mensagem = 'error';
        $mensagem = "As senhas não coincidem. Por favor, tente novamente.";
    } else {
        // Hash da senha
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        
        // Inserir novo usuário
        $sql = "INSERT INTO usuarios (nome, email, senha, ativo) VALUES (?, ?, ?, 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nome, $email, $senha_hash);
        
        if ($stmt->execute()) {
            $tipo_mensagem = 'success';
            $mensagem = "Cadastro realizado com sucesso! Redirecionando para o login...";
            
            // Redirecionar para a página de login após 2 segundos
            header("refresh:2;url=login.php");
        } else {
            $tipo_mensagem = 'error';
            $mensagem = "Erro ao cadastrar. Por favor, tente novamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
    <meta name="description" content="Cadastre-se no PontoInteligente - Sistema de controle de ponto moderno e seguro">
    <title>Cadastro - PontoInteligente</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
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
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header */
        header {
            background: var(--gradient-dark);
            color: var(--text-color);
            text-align: center;
            padding: 6rem 2rem;
            position: relative;
            overflow: hidden;
            border-bottom: 1px solid var(--glass-border);
        }

        header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 20%, rgba(0, 153, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(0, 85, 255, 0.1) 0%, transparent 50%);
            z-index: 1;
        }

        header h1 {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 2;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            letter-spacing: 1px;
            font-weight: 700;
        }

        header p {
            font-size: 1.4rem;
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto 2rem;
            position: relative;
            z-index: 2;
            line-height: 1.8;
        }

        .header-content {
            position: relative;
            z-index: 2;
            max-width: 1200px;
            margin: 0 auto;
        }

        .header-buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .header-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.8rem;
            padding: 1rem 2rem;
            border-radius: 30px;
            font-weight: 500;
            font-size: 1.1rem;
            text-decoration: none;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .header-btn-primary {
            background: var(--gradient-primary);
            color: white;
            box-shadow: 0 4px 15px rgba(0, 153, 255, 0.3);
        }

        .header-btn-secondary {
            background: var(--glass-bg);
            color: var(--text-color);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(10px);
        }

        .header-btn i {
            font-size: 1.2rem;
        }

        .header-btn:hover {
            transform: translateY(-3px);
        }

        .header-btn-primary:hover {
            box-shadow: 0 8px 20px rgba(0, 153, 255, 0.4);
        }

        .header-btn-secondary:hover {
            background: var(--gradient-primary);
            color: white;
            border-color: transparent;
        }

        /* Formulário de Cadastro */
        .cadastro-container {
            max-width: 500px;
            margin: 2rem auto;
            padding: 2rem;
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }

        .cadastro-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-primary);
            transform: scaleX(0);
            transform-origin: left;
            transition: var(--transition);
        }

        .cadastro-container:hover::before {
            transform: scaleX(1);
        }

        .cadastro-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .cadastro-header h2 {
            color: var(--primary-color);
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .cadastro-header p {
            color: var(--light-text);
            font-size: 1.1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-color);
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem 1rem;
            border-radius: 10px;
            border: 1px solid var(--glass-border);
            background: var(--glass-bg);
            color: var(--text-color);
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(0, 153, 255, 0.2);
        }

        .btn-cadastro {
            width: 100%;
            padding: 1rem;
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 1rem;
        }

        .btn-cadastro:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 153, 255, 0.4);
        }

        .cadastro-footer {
            text-align: center;
            margin-top: 2rem;
        }

        .cadastro-footer a {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .cadastro-footer a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .alert-error {
            background: rgba(255, 68, 68, 0.1);
            border: 1px solid var(--error-color);
            color: var(--error-color);
        }

        .alert-success {
            background: rgba(0, 255, 136, 0.1);
            border: 1px solid var(--success-color);
            color: var(--success-color);
        }

        /* Responsividade */
        @media screen and (max-width: 768px) {
            header h1 {
                font-size: 2.5rem;
            }

            .cadastro-container {
                margin: 1rem;
                padding: 1.5rem;
            }

            .cadastro-header h2 {
                font-size: 1.8rem;
            }
        }
    </style>
=======
    <title>Cadastro - Clínica Mente Neural</title>
    <link rel="stylesheet" href="estilo-login-usuario.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
>>>>>>> 6a4371b505c2969eaa843912487b25d6dab79c77
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <h1>📊 PontoInteligente</h1>
                <p>Crie sua conta e comece a controlar seu ponto de forma inteligente</p>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="cadastro-container">
            <div class="cadastro-header">
                <h2>Criar Conta</h2>
                <p>Preencha os dados abaixo para se cadastrar</p>
            </div>
<<<<<<< HEAD
=======
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" placeholder="Digite seu e-mail" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" placeholder="Crie uma senha" required>
            </div>
            <div class="form-group">
                <label for="confirmar_senha">Confirmar Senha:</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" required>
            </div>
            <button type="submit" id="botao-cadastro">Cadastrar</button>
        </form>
>>>>>>> 6a4371b505c2969eaa843912487b25d6dab79c77

            <?php if ($tipo_mensagem): ?>
                <div class="alert alert-<?php echo $tipo_mensagem; ?>">
                    <?php echo $mensagem; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="nome">Nome Completo</label>
                    <input type="text" id="nome" name="nome" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" required>
                </div>

                <div class="form-group">
                    <label for="confirmar_senha">Confirmar Senha</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" required>
                </div>

                <button type="submit" class="btn-cadastro">Cadastrar</button>
            </form>

            <div class="cadastro-footer">
                <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
            </div>
        </div>
    </div>
</body>
</html>
