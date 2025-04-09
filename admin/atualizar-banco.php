<?php
require_once __DIR__ . '/../conexao.php';

// Adicionar coluna ultimo_login na tabela usuarios se não existir
$sql = "SHOW COLUMNS FROM usuarios LIKE 'ultimo_login'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    $sql = "ALTER TABLE usuarios ADD COLUMN ultimo_login DATETIME DEFAULT NULL";
    $conn->query($sql);
}

// Adicionar coluna nivel_acesso na tabela usuarios se não existir
$sql = "SHOW COLUMNS FROM usuarios LIKE 'nivel_acesso'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    $sql = "ALTER TABLE usuarios ADD COLUMN nivel_acesso ENUM('usuario', 'admin') DEFAULT 'usuario'";
    $conn->query($sql);
}

// Criar tabelas do dashboard
$sql = file_get_contents(__DIR__ . '/criar-tabelas-dashboard.sql');
$conn->multi_query($sql);

echo "Banco de dados atualizado com sucesso!";
?> 