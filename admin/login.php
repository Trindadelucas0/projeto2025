<?php
session_start();
require_once '../conexao.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = ? AND tipo = 'admin' LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $admin = $resultado->fetch_assoc();

        if (password_verify($senha, $admin['senha'])) {
            $_SESSION['usuario_id'] = $admin['id'];
            $_SESSION['nome'] = $admin['nome'];
            $_SESSION['email'] = $admin['email'];
            $_SESSION['foto'] = $admin['foto'] ?? 'uploads/perfil/default.jpg';
            $_SESSION['tipo'] = 'admin';

            header("Location: painel.php");
            exit();
        } else {
            $mensagem = "❌ Senha incorreta.";
        }
    } else {
        $mensagem = "❌ Administrador não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrativo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
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

        /* Reset e estilos base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
<<<<<<< HEAD
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background: var(--gradient);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow-x: hidden;
        }

        /* Container principal */
        .container {
            width: 100%;
            max-width: 400px;
            margin: 2rem;
            padding: 2rem;
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
=======
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .box {
            background: white;
            padding: 40px;
            border-radius: 20px;
            width: 350px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
        }
        .box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #3498db, #2ecc71);
        }
        .box h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 600;
        }
        .box h2 i {
            color: #3498db;
            margin-right: 10px;
        }
        .input-group {
            margin-bottom: 20px;
            position: relative;
        }
        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #3498db;
        }
        input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 50px;
            font-size: 14px;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }
        input:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
>>>>>>> 6a4371b505c2969eaa843912487b25d6dab79c77
        }

        .container::before {
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

        .container:hover::before {
            transform: scaleX(1);
        }

        /* Título da página */
        h2 {
            color: var(--primary-color);
            font-size: 2rem;
            margin-bottom: 2rem;
            text-align: center;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* Mensagem de feedback */
        .mensagem {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            text-align: center;
            font-weight: 500;
            background: rgba(255, 68, 68, 0.1);
            border: 1px solid var(--error-color);
            color: var(--error-color);
        }

        /* Formulário */
        form {
            margin-top: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        input {
            width: 100%;
            padding: 1rem;
            border-radius: 10px;
            border: 1px solid var(--glass-border);
            background: var(--glass-bg);
            color: var(--text-color);
            font-size: 1rem;
            transition: var(--transition);
        }

        input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(0, 153, 255, 0.2);
        }

        input::placeholder {
            color: var(--light-text);
        }

        /* Botão */
        button {
            width: 100%;
<<<<<<< HEAD
            padding: 1rem;
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }

        button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 153, 255, 0.4);
        }

        /* Responsividade */
        @media screen and (max-width: 768px) {
            .container {
                margin: 1rem;
                padding: 1.5rem;
            }

            h2 {
                font-size: 1.8rem;
            }
=======
            padding: 12px;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.3);
        }
        .mensagem {
            background: #fff3f3;
            color: #e74c3c;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
            border: 1px solid #fadbd8;
        }
        .admin-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #2ecc71;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
>>>>>>> 6a4371b505c2969eaa843912487b25d6dab79c77
        }
    </style>
</head>
<body>
<<<<<<< HEAD
    <div class="container">
        <h2>👤 Login Admin</h2>

        <?php if ($mensagem): ?>
            <div class="mensagem">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <input type="email" name="email" placeholder="E-mail" required>
            </div>
            <div class="form-group">
                <input type="password" name="senha" placeholder="Senha" required>
            </div>
            <button type="submit">Entrar</button>
=======
    <div class="box">
        <span class="admin-badge">Área Administrativa</span>
        <h2><i class="fas fa-shield-alt"></i> Login Admin</h2>
        <?php if ($mensagem): ?>
            <div class="mensagem">
                <i class="fas fa-exclamation-circle"></i> <?= $mensagem ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="E-mail" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="senha" placeholder="Senha" required>
            </div>
            <button type="submit">
                <i class="fas fa-sign-in-alt"></i> Entrar
            </button>
>>>>>>> 6a4371b505c2969eaa843912487b25d6dab79c77
        </form>
    </div>
</body>
</html>
