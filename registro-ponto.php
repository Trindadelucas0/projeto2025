<?php
session_start();
require 'conexao.php';
require 'proteger.php';
require 'verificar-ativo.php';
include "header.php";

$mensagem = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = $_SESSION['usuario_id'];
    $data = $_POST['data'];
    $tipo = $_POST['tipo_ponto'];
    $hora = $_POST['hora'];

    $foto_nome = null;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto_tmp = $_FILES['foto']['tmp_name'];
        $foto_nome = uniqid() . '-' . $_FILES['foto']['name'];
        move_uploaded_file($foto_tmp, 'uploads/' . $foto_nome);
    }

    $sql = "INSERT INTO registro_ponto (usuario_id, data, tipo_ponto, hora, foto) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $usuario_id, $data, $tipo, $hora, $foto_nome);

    if ($stmt->execute()) {
        $mensagem = "✅ Ponto registrado com sucesso!";
    } else {
        $mensagem = "❌ Erro ao registrar ponto: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Ponto</title>
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
            max-width: 800px;
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
        p {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            text-align: center;
            font-weight: 500;
        }

        /* Formulário */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-color);
            font-weight: 500;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.8rem 1rem;
            border-radius: 10px;
            border: 1px solid var(--glass-border);
            background: var(--glass-bg);
            color: var(--text-color);
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(0, 153, 255, 0.2);
        }

        .form-group input[type="file"] {
            padding: 0.5rem;
            background: transparent;
            border: 1px dashed var(--glass-border);
        }

        .form-group input[type="file"]::-webkit-file-upload-button {
            background: var(--gradient-primary);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 1rem;
            transition: var(--transition);
        }

        .form-group input[type="file"]::-webkit-file-upload-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 153, 255, 0.3);
        }

        /* Botões */
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
            margin-bottom: 1rem;
        }

        button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 153, 255, 0.4);
        }

        #ver-registros-btn {
            background: var(--glass-bg);
            color: var(--text-color);
            border: 1px solid var(--glass-border);
        }

        #ver-registros-btn:hover {
            background: var(--gradient-primary);
            color: white;
            border-color: transparent;
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

            .form-group input,
            .form-group select {
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>📝 Registro de Ponto</h2>

        <?php if ($mensagem): ?>
            <p style="color: <?php echo strpos($mensagem, '✅') !== false ? 'var(--success-color)' : 'var(--error-color)'; ?>">
                <?php echo $mensagem; ?>
            </p>
        <?php endif; ?>

        <form id="ponto-form" method="POST" action="" enctype="multipart/form-data">

            <div class="form-group">
                <label for="data">Data:</label>
                <input type="date" id="data" name="data" required>
            </div>

            <div class="form-group">
                <label for="tipo-ponto">Tipo de Ponto:</label>
                <select id="tipo-ponto" name="tipo_ponto" required>
                    <option value="">Selecione o tipo de ponto</option>
                    <option value="entrada">Entrada</option>
                    <option value="saida">Saída</option>
                    <option value="almoco">Saída para Almoço</option>
                    <option value="retorno">Retorno do Almoço</option>
                    <option value="intervalo_inicio">Início do Intervalo</option>
                    <option value="intervalo_fim">Fim do Intervalo</option>
                </select>
            </div> 

            <div class="form-group">
                <label for="hora">Horário:</label>
                <input type="time" id="hora" name="hora" required>
            </div>

            <div class="form-group">
                <label for="foto">Foto:</label>
                <input type="file" id="foto" name="foto" accept="image/*">
            </div>

            <button type="submit" id="botao">Registrar Ponto</button>
        </form>

        <button id="ver-registros-btn" onclick="window.location.href='ver-registros.php'">Ver Registros</button>
    </div>

    <script src="JS/registro.js"></script>
</body>
</html>
