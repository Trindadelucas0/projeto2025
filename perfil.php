<?php
session_start();
require 'conexao.php';

$mensagem = '';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES['foto_perfil'])) {
    if ($_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['foto_perfil']['tmp_name'];
        $nome_arquivo = uniqid() . '-' . $_FILES['foto_perfil']['name'];
        $destino = "uploads/perfil/" . $nome_arquivo;

        if (move_uploaded_file($tmp, $destino)) {
            $_SESSION['foto'] = $destino;

            // Atualizar no banco (opcional)
            $stmt = $conn->prepare("UPDATE usuarios SET foto = ? WHERE id = ?");
            $stmt->bind_param("si", $destino, $_SESSION['usuario_id']);
            $stmt->execute();

            $mensagem = "✅ Foto atualizada com sucesso!";
        } else {
            $mensagem = "❌ Falha ao mover a foto.";
        }
    } else {
        $mensagem = "❌ Nenhuma foto enviada ou erro ao enviar.";
    }
}
?>
<?php include 'proteger.php'; 
include 'verificar-ativo.php';?>
<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil</title>
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
            max-width: 600px;
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
        }

        .mensagem.sucesso {
            background: rgba(0, 255, 136, 0.1);
            border: 1px solid var(--success-color);
            color: var(--success-color);
        }

        .mensagem.erro {
            background: rgba(255, 68, 68, 0.1);
            border: 1px solid var(--error-color);
            color: var(--error-color);
        }

        /* Informações do perfil */
        .info-perfil {
            margin-bottom: 2rem;
        }

        .info-perfil p {
            margin-bottom: 1rem;
            padding: 0.8rem;
            background: var(--glass-bg);
            border-radius: 10px;
            border: 1px solid var(--glass-border);
        }

        .info-perfil strong {
            color: var(--primary-color);
            margin-right: 0.5rem;
        }

        /* Foto de perfil */
        .foto-perfil {
            text-align: center;
            margin-bottom: 2rem;
        }

        .foto-perfil img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid var(--primary-color);
            box-shadow: 0 4px 15px rgba(0, 153, 255, 0.3);
            transition: var(--transition);
        }

        .foto-perfil img:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 153, 255, 0.4);
        }

        /* Formulário */
        form {
            margin-top: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-color);
            font-weight: 500;
        }

        input[type="file"] {
            width: 100%;
            padding: 0.8rem;
            border-radius: 10px;
            border: 1px dashed var(--glass-border);
            background: transparent;
            color: var(--text-color);
            font-size: 1rem;
            transition: var(--transition);
        }

        input[type="file"]::-webkit-file-upload-button {
            background: var(--gradient-primary);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 1rem;
            transition: var(--transition);
        }

        input[type="file"]::-webkit-file-upload-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 153, 255, 0.3);
        }

        /* Botão */
        button {
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

            .foto-perfil img {
                width: 120px;
                height: 120px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>👤 Meu Perfil</h2>

        <?php if ($mensagem): ?>
            <div class="mensagem <?php echo strpos($mensagem, '✅') !== false ? 'sucesso' : 'erro'; ?>">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>

        <div class="info-perfil">
            <p><strong>Nome:</strong> <?php echo $_SESSION['nome'] ?? ''; ?></p>
            <p><strong>E-mail:</strong> <?php echo $_SESSION['email'] ?? ''; ?></p>
        </div>

        <div class="foto-perfil">
            <strong>Foto de Perfil:</strong>
            <img src="<?php echo $_SESSION['foto'] ?? 'uploads/perfil/default.jpg'; ?>" alt="Perfil">
        </div>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="foto_perfil">Alterar Foto:</label>
                <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*" required>
            </div>
            <button type="submit">Atualizar Foto</button>
        </form>
    </div>
</body>
</html>
