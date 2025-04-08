<?php
if (!isset($_SESSION)) session_start();
require_once 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Verifica se o usuário está bloqueado
$id = $_SESSION['usuario_id'];
$sql = "SELECT ativo FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($ativo);
$stmt->fetch();
$stmt->close();

if ($ativo == 0) {
    echo "
    <div style='padding: 50px; text-align: center;'>
        <h2>❌ Acesso Bloqueado</h2>
        <p>Sua mensalidade está vencida ou seu acesso foi suspenso.</p>
        <p>Entre em contato para regularizar.</p>
        <a href='https://wa.me/SEU_NUMERO_AQUI' target='_blank' style='
            background-color: #25d366;
            color: white;
            padding: 15px 25px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            display: inline-block;
            margin-top: 20px;
        '>💬 Renovar pelo WhatsApp</a>
    </div>
    ";
    exit(); // Impede que o restante da página seja carregado
}
