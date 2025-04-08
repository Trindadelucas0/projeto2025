<?php
session_start();
require 'conexao.php';
require 'proteger.php';

$usuario_id = $_POST['usuario_id'];

// Verifica os 30 primeiros dias únicos do usuário
$sqlDias = "SELECT DISTINCT data FROM registro_ponto WHERE usuario_id = ? ORDER BY data ASC LIMIT 30";
$stmtDias = $conn->prepare($sqlDias);
$stmtDias->bind_param("i", $usuario_id);
$stmtDias->execute();
$resultadoDias = $stmtDias->get_result();

if ($resultadoDias->num_rows < 30) {
    echo "Você ainda não possui 30 dias de registros para gerar um relatório.";
    exit;
}

$dias = [];
while ($row = $resultadoDias->fetch_assoc()) {
    $dias[] = $row['data'];
}

$inicio = $dias[0];
$fim = $dias[29];

// Pega os registros do período
$sql = "SELECT * FROM registro_ponto WHERE usuario_id = ? AND data BETWEEN ? AND ? ORDER BY data ASC, hora ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $usuario_id, $inicio, $fim);
$stmt->execute();
$resultado = $stmt->get_result();

$registros = [];
while ($row = $resultado->fetch_assoc()) {
    $registros[$row['data']][] = $row;
}

function diffMin($h1, $h2) {
    [$h1h, $h1m] = explode(":", $h1);
    [$h2h, $h2m] = explode(":", $h2);
    return abs(((int)$h2h * 60 + (int)$h2m) - ((int)$h1h * 60 + (int)$h1m));
}

function formatarMin($min) {
    $h = str_pad(floor($min / 60), 2, '0', STR_PAD_LEFT);
    $m = str_pad($min % 60, 2, '0', STR_PAD_LEFT);
    return "$h:$m";
}

$cargaDiaria = 8 * 60;
$cargaMensal = $cargaDiaria * 30;

$totalMin = 0;
foreach ($registros as $pontos) {
    $horarios = [];
    foreach ($pontos as $ponto) {
        $horarios[$ponto['tipo_ponto']] = substr($ponto['hora'], 0, 5);
    }

    $diaMin = 0;
    if (!empty($horarios['entrada']) && !empty($horarios['almoco']))
        $diaMin += diffMin($horarios['entrada'], $horarios['almoco']);
    if (!empty($horarios['retorno']) && !empty($horarios['saida']))
        $diaMin += diffMin($horarios['retorno'], $horarios['saida']);

    $totalMin += $diaMin;
}

$trabalhadas = formatarMin($totalMin);
$extras = $totalMin > $cargaMensal ? formatarMin($totalMin - $cargaMensal) : "00:00";
$faltantes = $totalMin < $cargaMensal ? formatarMin($cargaMensal - $totalMin) : "00:00";
$mesAno = date("Y-m", strtotime($inicio));

// Verifica se já existe
$verifica = $conn->prepare("SELECT id FROM relatorio_mensal WHERE usuario_id = ? AND mes = ?");
$verifica->bind_param("is", $usuario_id, $mesAno);
$verifica->execute();
$verifica->store_result();

if ($verifica->num_rows === 0) {
    $insert = $conn->prepare("INSERT INTO relatorio_mensal (usuario_id, mes, carga_diaria, total_trabalhado, horas_extras, horas_faltantes) VALUES (?, ?, ?, ?, ?, ?)");
    $insert->bind_param("isssss", $usuario_id, $mesAno, "08:00", $trabalhadas, $extras, $faltantes);
    $insert->execute();

    $delete = $conn->prepare("DELETE FROM registro_ponto WHERE usuario_id = ? AND data BETWEEN ? AND ?");
    $delete->bind_param("iss", $usuario_id, $inicio, $fim);
    $delete->execute();

    echo "✅ Relatório mensal gerado com sucesso!";
} else {
    echo "⚠️ Relatório deste mês já foi gerado anteriormente.";
}

echo "<br><a href='ver-registros.php'>⬅️ Voltar</a>";
?>
