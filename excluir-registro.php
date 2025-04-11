<?php
session_start();
require 'conexao.php';
require 'proteger.php';
require 'verificar-ativo.php';

// Verifica se o ID foi fornecido
if (!isset($_GET['id'])) {
    $_SESSION['mensagem'] = "❌ ID do registro não especificado.";
    header("Location: ver-registros.php");
    exit();
}

$id = $_GET['id'];
$usuario_id = $_SESSION['usuario_id'];

// Verifica se o registro pertence ao usuário logado
$sql_verificar = "SELECT id FROM registro_ponto WHERE id = ? AND usuario_id = ?";
$stmt_verificar = $conn->prepare($sql_verificar);
$stmt_verificar->bind_param("ii", $id, $usuario_id);
$stmt_verificar->execute();
$resultado = $stmt_verificar->get_result();

if ($resultado->num_rows === 0) {
    $_SESSION['mensagem'] = "❌ Você não tem permissão para excluir este registro.";
    header("Location: ver-registros.php");
    exit();
}

// Busca informações da foto antes de excluir
$sql_foto = "SELECT foto FROM registro_ponto WHERE id = ?";
$stmt_foto = $conn->prepare($sql_foto);
$stmt_foto->bind_param("i", $id);
$stmt_foto->execute();
$resultado_foto = $stmt_foto->get_result();
$registro = $resultado_foto->fetch_assoc();

// Exclui o registro
$sql = "DELETE FROM registro_ponto WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // Se houver uma foto associada, exclui o arquivo
    if ($registro && $registro['foto']) {
        $caminho_foto = "uploads/" . $registro['foto'];
        if (file_exists($caminho_foto)) {
            unlink($caminho_foto);
        }
    }
    
    $_SESSION['mensagem'] = "✅ Registro excluído com sucesso!";
} else {
    $_SESSION['mensagem'] = "❌ Erro ao excluir o registro: " . $conn->error;
}

header("Location: ver-registros.php");
exit();
?> 