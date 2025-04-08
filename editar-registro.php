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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            min-height: 100vh;
            margin: 0;
            padding: 40px 20px;
            color: #2c3e50;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            animation: fadeIn 0.5s ease;
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
            color: #2c3e50;
        }

        .header i {
            color: #3498db;
            font-size: 28px;
        }

        .mensagem {
            background: #fff3f3;
            color: #e74c3c;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .form-control:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .foto-atual {
            margin: 20px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            text-align: center;
        }

        .foto-atual img {
            max-width: 200px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .btn-secondary {
            background: #e9ecef;
            color: #2c3e50;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
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
