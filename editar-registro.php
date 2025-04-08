<?php
require 'conexao.php';
include 'proteger.php';
include 'verificar-ativo.php'; 

if (!isset($_GET['id'])) {
    die("ID do registro não especificado.");
}

$id = $_GET['id'];
$mensagem = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = $_POST['data'];
    $tipo = $_POST['tipo_ponto'];
    $hora = $_POST['hora'];

    // Verifica se uma nova foto foi enviada
    $nova_foto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['foto']['tmp_name'];
        $nova_foto = uniqid() . '-' . $_FILES['foto']['name'];
        move_uploaded_file($tmp, "uploads/" . $nova_foto);
    }

    if ($nova_foto) {
        $sql = "UPDATE registro_ponto SET data = ?, tipo_ponto = ?, hora = ?, foto = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $data, $tipo, $hora, $nova_foto, $id);
    } else {
        $sql = "UPDATE registro_ponto SET data = ?, tipo_ponto = ?, hora = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $data, $tipo, $hora, $id);
    }

    if ($stmt->execute()) {
        $mensagem = "✅ Registro atualizado com sucesso!";
        return "ver-registros.php";
    } else {
        $mensagem = "❌ Erro ao atualizar: " . $conn->error;
    }
}

// Busca os dados atuais do registro
$sql = "SELECT * FROM registro_ponto WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$registro = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Registro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
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

        .header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: var(--text-color);
        }

        .header i {
            color: var(--primary-color);
            font-size: 28px;
        }

        .mensagem {
            background: rgba(255, 68, 68, 0.1);
            color: var(--error-color);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-left: 4px solid var(--error-color);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-color);
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 10px;
            font-size: 14px;
            color: var(--text-color);
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 153, 255, 0.1);
        }

        .foto-atual {
            margin: 20px 0;
            padding: 20px;
            background: var(--glass-bg);
            border-radius: 10px;
            text-align: center;
            border: 1px solid var(--glass-border);
        }

        .foto-atual img {
            max-width: 200px;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 10px;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: white;
        }

        .btn-secondary {
            background: var(--glass-bg);
            color: var(--text-color);
            border: 1px solid var(--glass-border);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 153, 255, 0.3);
        }

        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            .container {
                margin: 1rem;
                padding: 1rem;
            }
            
            .btn-group {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <i class="fas fa-edit"></i>
            <h1>Editar Registro</h1>
        </div>

        <?php if ($mensagem): ?>
            <div class="mensagem">
                <i class="fas fa-exclamation-circle"></i>
                <?= $mensagem ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Data:</label>
                <input type="date" name="data" class="form-control" value="<?= $registro['data'] ?>" required>
            </div>

            <div class="form-group">
                <label>Tipo de Ponto:</label>
                <select name="tipo_ponto" class="form-control" required>
                    <option <?= $registro['tipo_ponto'] == 'entrada' ? 'selected' : '' ?> value="entrada">Entrada</option>
                    <option <?= $registro['tipo_ponto'] == 'saida' ? 'selected' : '' ?> value="saida">Saída</option>
                    <option <?= $registro['tipo_ponto'] == 'almoco' ? 'selected' : '' ?> value="almoco">Saída para Almoço</option>
                    <option <?= $registro['tipo_ponto'] == 'retorno' ? 'selected' : '' ?> value="retorno">Retorno do Almoço</option>
                    <option <?= $registro['tipo_ponto'] == 'intervalo_inicio' ? 'selected' : '' ?> value="intervalo_inicio">Início do Intervalo</option>
                    <option <?= $registro['tipo_ponto'] == 'intervalo_fim' ? 'selected' : '' ?> value="intervalo_fim">Fim do Intervalo</option>
                </select>
            </div>

            <div class="form-group">
                <label>Hora:</label>
                <input type="time" name="hora" class="form-control" value="<?= $registro['hora'] ?>" required>
            </div>

            <?php if ($registro['foto']): ?>
                <div class="foto-atual">
                    <h3>Foto Atual:</h3>
                    <img src="uploads/<?= htmlspecialchars($registro['foto']) ?>" alt="Foto atual">
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label>Alterar Foto (opcional):</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
            </div>

            <div class="btn-group">
                <a href="ver-registros.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</body>
</html>
