<?php
require_once '../conexao.php';

// Pega a quantidade de comentários por nota
$sql = "SELECT nota, COUNT(*) AS total FROM comentarios GROUP BY nota ORDER BY nota";
$resultado = $conn->query($sql);

$notas = [];
$totais = [];

while ($row = $resultado->fetch_assoc()) {
    $notas[] = $row['nota'];
    $totais[] = $row['total'];
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

    /* Container do gráfico */
    .grafico-container {
        background: var(--card-bg);
        border-radius: 15px;
        padding: 2rem;
        margin: 2rem 0;
        box-shadow: var(--card-shadow);
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

    /* Responsividade */
    @media screen and (max-width: 768px) {
        .grafico-container {
            padding: 1rem;
        }

        h2 {
            font-size: 1.5rem;
        }
    }
</style>

<h2>📊 Nível de Satisfação dos Usuários</h2>

<div class="grafico-container">
    <canvas id="graficoSatisfacao" width="600" height="300"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('graficoSatisfacao').getContext('2d');
    const grafico = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($notas) ?>,
            datasets: [{
                label: 'Quantidade de Avaliações',
                data: <?= json_encode($totais) ?>,
                backgroundColor: 'rgba(0, 153, 255, 0.7)',
                borderColor: 'rgba(0, 153, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: '#e0e0e0'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: '#e0e0e0'
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: '#e0e0e0'
                    }
                }
            }
        }
    });
</script>
