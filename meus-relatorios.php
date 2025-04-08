<?php
session_start();
require 'conexao.php';
require 'proteger.php';
require 'verificar-ativo.php';
include_once "header.php";

$usuario_id = $_SESSION['usuario_id'];

$sql = "SELECT * FROM relatorio_mensal WHERE usuario_id = ? ORDER BY mes DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios Mensais</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
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
            max-width: 1000px;
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

        /* Tabela */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 20px;
            background: var(--glass-bg);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 1rem;
            text-align: center;
            border-bottom: 1px solid var(--glass-border);
        }

        th {
            background: var(--gradient-primary);
            color: white;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        /* Botão */
        button {
            padding: 0.5rem 1rem;
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 153, 255, 0.3);
        }

        /* Mensagem quando não há dados */
        tr td[colspan="5"] {
            padding: 2rem;
            text-align: center;
            color: var(--light-text);
            font-style: italic;
        }

        /* Cards para visualização em dispositivos móveis */
        .cards-container {
            display: none;
            margin-top: 20px;
        }

        .card {
            background: var(--glass-bg);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 1px solid var(--glass-border);
            transition: var(--transition);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--glass-border);
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 500;
            color: var(--primary-color);
        }

        .card-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .card-item {
            display: flex;
            flex-direction: column;
        }

        .card-label {
            font-size: 0.8rem;
            color: var(--light-text);
            margin-bottom: 0.3rem;
        }

        .card-value {
            font-size: 1rem;
            font-weight: 500;
        }

        .card-actions {
            display: flex;
            justify-content: flex-end;
        }

        /* Responsividade */
        @media screen and (max-width: 768px) {
            .container {
                margin: 1rem;
                padding: 1rem;
            }

            h2 {
                font-size: 1.8rem;
            }

            table {
                display: none;
            }

            .cards-container {
                display: block;
            }

            .card-content {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h2><i class="fas fa-chart-bar"></i> Relatórios Mensais</h2>

    <!-- Tabela para desktop -->
    <table>
        <thead>
            <tr>
                <th>Mês</th>
                <th>Total Trabalhado</th>
                <th>Horas Extras</th>
                <th>Horas Faltantes</th>
                <th>PDF</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($resultado->num_rows > 0): ?>
            <?php while ($relatorio = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($relatorio['mes']) ?></td>
                    <td><?= htmlspecialchars($relatorio['total_trabalhado']) ?></td>
                    <td><?= htmlspecialchars($relatorio['horas_extras']) ?></td>
                    <td><?= htmlspecialchars($relatorio['horas_faltantes']) ?></td>
                    <td>
                        <button onclick="gerarPDF('<?= $relatorio['mes'] ?>', '<?= $relatorio['carga_diaria'] ?>', '<?= $relatorio['total_trabalhado'] ?>', '<?= $relatorio['horas_extras'] ?>', '<?= $relatorio['horas_faltantes'] ?>')">
                            <i class="fas fa-download"></i> Baixar PDF
                        </button>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5">Nenhum relatório encontrado.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <!-- Cards para dispositivos móveis -->
    <div class="cards-container">
        <?php 
        // Resetar o ponteiro do resultado para usar novamente
        if ($resultado->num_rows > 0) {
            $resultado->data_seek(0);
            while ($relatorio = $resultado->fetch_assoc()): 
        ?>
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><?= htmlspecialchars($relatorio['mes']) ?></div>
                </div>
                <div class="card-content">
                    <div class="card-item">
                        <div class="card-label">Total Trabalhado</div>
                        <div class="card-value"><?= htmlspecialchars($relatorio['total_trabalhado']) ?></div>
                    </div>
                    <div class="card-item">
                        <div class="card-label">Horas Extras</div>
                        <div class="card-value"><?= htmlspecialchars($relatorio['horas_extras']) ?></div>
                    </div>
                    <div class="card-item">
                        <div class="card-label">Horas Faltantes</div>
                        <div class="card-value"><?= htmlspecialchars($relatorio['horas_faltantes']) ?></div>
                    </div>
                </div>
                <div class="card-actions">
                    <button onclick="gerarPDF('<?= $relatorio['mes'] ?>', '<?= $relatorio['carga_diaria'] ?>', '<?= $relatorio['total_trabalhado'] ?>', '<?= $relatorio['horas_extras'] ?>', '<?= $relatorio['horas_faltantes'] ?>')">
                        <i class="fas fa-download"></i> Baixar PDF
                    </button>
                </div>
            </div>
        <?php 
            endwhile;
        } else {
            echo '<div class="card"><div class="card-content">Nenhum relatório encontrado.</div></div>';
        }
        ?>
    </div>
</div>

<script>
    window.jsPDF = window.jspdf.jsPDF;

    function gerarPDF(mes, carga, trabalhado, extras, faltantes) {
        const pdf = new jsPDF();

        pdf.setFontSize(18);
        pdf.text("Relatório Mensal - Mente Neural", 14, 20);

        pdf.setFontSize(12);
        pdf.text("Mês: " + mes, 14, 30);
        pdf.text("Carga Diária: " + carga, 14, 38);
        pdf.text("Total Trabalhado: " + trabalhado, 14, 46);
        pdf.text("Horas Extras: " + extras, 14, 54);
        pdf.text("Horas Faltantes: " + faltantes, 14, 62);

        pdf.text("Gerado automaticamente pelo sistema", 14, 80);

        pdf.save("relatorio-" + mes + ".pdf");
    }
</script>
</body>
</html>
