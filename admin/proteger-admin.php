<?php
// Verificar se o usuário está logado e é administrador
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}

// Verificar se o usuário é administrador
$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT nivel_acesso FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario || $usuario['nivel_acesso'] !== 'admin') {
    // Redirecionar para o dashboard com mensagem de erro
    $_SESSION['mensagem'] = "Acesso negado. Você não tem permissão para acessar esta área.";
    $_SESSION['tipo_mensagem'] = "erro";
    header("Location: ../dashboard.php");
    exit();
}
?> 