<?php
require_once '../conexao.php';

// Busca todos os usuários ativos
$usuarios = $conn->query("SELECT id, nome FROM usuarios ORDER BY nome");

function buscarRegistros($conn, $usuario_id) {
    $sql = "SELECT tipo_ponto, hora, data FROM registro_ponto 
            WHERE usuario_id = ? 
            AND data >= CURDATE() - INTERVAL 30 DAY 
            ORDER BY data ASC, hora ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function calcularTotais($registros, $cargaHorariaDiaria = "08:00") {
    $porData = [];
    foreach ($registros as $r) {
        $data = $r['data'];
        $tipo = $r['tipo_ponto'];
        $hora = substr($r['hora'], 0, 5); // corta os segundos
        $porData[$data][$tipo] = $hora;
    }

    $minutosTrabalhados = 0;

    foreach ($porData as $dia => $pontos) {
        if (isset($pontos['entrada'], $pontos['almoco'])) {
            $minutosTrabalhados += diferencaMinutos($pontos['entrada'], $pontos['almoco']);
        }
        if (isset($pontos['retorno'], $pontos['saida'])) {
            $minutosTrabalhados += diferencaMinutos($pontos['retorno'], $pontos['saida']);
        }
        if (isset($pontos['intervalo_inicio'], $pontos['intervalo_fim'])) {
            $minutosTrabalhados -= diferencaMinutos($pontos['intervalo_inicio'], $pontos['intervalo_fim']);
        }
    }

    list($h, $m) = explode(":", $cargaHorariaDiaria);
    $cargaMinDia = $h * 60 + $m;
    $cargaMensal = $cargaMinDia * 30;

    $extras = $minutosTrabalhados > $cargaMensal ? $minutosTrabalhados - $cargaMensal : 0;
    $faltas = $minutosTrabalhados < $cargaMensal ? $cargaMensal - $minutosTrabalhados : 0;

    return [
        'trabalhado' => formatarTempo($minutosTrabalhados),
        'extras' => formatarTempo($extras),
        'faltantes' => formatarTempo($faltas)
    ];
}

function diferencaMinutos($h1, $h2) {
    [$h1h, $h1m] = explode(":", $h1);
    [$h2h, $h2m] = explode(":", $h2);
    return abs(($h2h * 60 + $h2m) - ($h1h * 60 + $h1m));
}

function formatarTempo($minutos) {
    return str_pad(floor($minutos / 60), 2, "0", STR_PAD_LEFT) . ":" . str_pad($minutos % 60, 2, "0", STR_PAD_LEFT);
}
?>

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

    /* Estilos da tabela */
    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 2rem 0;
        background: var(--card-bg);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: var(--card-shadow);
    }

    th, td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid var(--glass-border);
    }

    th {
        background: var(--gradient-primary);
        color: white;
        font-weight: 500;
        text-transform: uppercase;
        font-size: 0.9rem;
        letter-spacing: 1px;
    }

    tr:hover {
        background: var(--glass-bg);
    }

    td {
        color: var(--text-color);
    }

    /* Estilos do título */
    h2 {
        color: var(--primary-color);
        font-size: 1.8rem;
        margin-bottom: 2rem;
        text-align: center;
        background: var(--gradient-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    /* Cores para horas extras e faltantes */
    .horas-extras {
        color: var(--success-color);
    }

    .horas-faltantes {
        color: var(--error-color);
    }

    /* Responsividade */
    @media screen and (max-width: 768px) {
        table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }

        th, td {
            padding: 0.8rem;
        }

        h2 {
            font-size: 1.5rem;
        }
    }
</style>

<h2>📊 Resumo de Horas dos Funcionários (Últimos 30 dias)</h2>

<table>
    <thead>
        <tr>
            <th>Funcionário</th>
            <th>Horas Trabalhadas</th>
            <th>Horas Extras</th>
            <th>Horas Faltantes</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($usuario = $usuarios->fetch_assoc()): 
            $registros = buscarRegistros($conn, $usuario['id']);
            $totais = calcularTotais($registros);
        ?>
            <tr>
                <td><?= htmlspecialchars($usuario['nome']) ?></td>
                <td><?= $totais['trabalhado'] ?></td>
                <td class="horas-extras"><?= $totais['extras'] ?></td>
                <td class="horas-faltantes"><?= $totais['faltantes'] ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
