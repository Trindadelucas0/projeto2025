<?php
require_once 'conexao.php';
$tipo_mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $lembrar = isset($_POST['lembrar']) ? true : false;

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        if (password_verify($senha, $usuario['senha'])) {
            if ($usuario['ativo'] == 1) {
                session_start();
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nome'] = $usuario['nome'];
                $_SESSION['foto'] = $usuario['foto'];
                $_SESSION['nivel_acesso'] = $usuario['nivel_acesso'];

                if ($lembrar) {
                    $token = bin2hex(random_bytes(32));
                    $expiracao = date('Y-m-d H:i:s', strtotime('+30 days'));
                    
                    $sql = "INSERT INTO tokens (usuario_id, token, expiracao) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("iss", $usuario['id'], $token, $expiracao);
                    $stmt->execute();
                    
                    setcookie('lembrar_token', $token, strtotime('+30 days'), '/', '', true, true);
                }

                // Atualizar último login
                $sql = "UPDATE usuarios SET ultimo_login = NOW() WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $usuario['id']);
                $stmt->execute();

                header("Location: dashboard.php");
                exit();
            } else {
                $tipo_mensagem = 'error';
                $mensagem = "Sua conta está inativa. Entre em contato com o suporte.";
            }
        } else {
            $tipo_mensagem = 'error';
            $mensagem = "Email ou senha incorretos.";
        }
    } else {
        $tipo_mensagem = 'error';
        $mensagem = "Email ou senha incorretos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Faça login no Controle de Ponto Inteligente - Sistema de controle de ponto moderno e seguro">
    <title>Login - Controle de Ponto Inteligente</title>
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

        /* Formulário de Login */
        .login-container {
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

        .login-container::before {
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

        .login-container:hover::before {
            transform: scaleX(1);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h2 {
            color: var(--primary-color);
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .login-header p {
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

        .form-group .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group .checkbox-group input {
            width: auto;
        }

        .form-group .checkbox-group label {
            margin-bottom: 0;
        }

        .btn-login {
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

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 153, 255, 0.4);
        }

        .login-footer {
            text-align: center;
            margin-top: 2rem;
        }

        .login-footer a {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .login-footer a:hover {
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

            .login-container {
                margin: 1rem;
                padding: 1.5rem;
            }

            .login-header h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <h1>📊 Controle de Ponto Inteligente</h1>
                <p>Faça login para acessar seu controle de ponto</p>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="login-container">
            <div class="login-header">
                <h2>Bem-vindo de volta!</h2>
                <p>Entre com suas credenciais para acessar sua conta</p>
            </div>

            <?php if ($tipo_mensagem === 'error'): ?>
                <div class="alert alert-error">
                    <?php echo $mensagem; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" required>
                </div>

                <div class="form-group">
                    <div class="checkbox-group">
                        <input type="checkbox" id="lembrar" name="lembrar">
                        <label for="lembrar">Lembrar de mim</label>
                    </div>
                </div>

                <button type="submit" class="btn-login">Entrar</button>
            </form>

            <div class="login-footer">
                <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
                <p><a href="recuperar-senha.php">Esqueceu sua senha?</a></p>
            </div>
        </div>
    </div>
</body>
</html>
