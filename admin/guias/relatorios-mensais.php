<?php
require_once '../conexao.php';

// Consulta os relatórios mensais
$sql = "SELECT u.nome AS usuario, r.mes, r.carga_diaria, r.total_trabalhado, r.horas_extras, r.horas_faltantes, r.criado_em
        FROM relatorio_mensal r
        JOIN usuarios u ON u.id = r.usuario_id
        ORDER BY r.criado_em DESC";

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatórios Mensais</title>
    <link rel="stylesheet" href="style.css">
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

        .container {
            padding: 2rem;
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

        /* Estilos do botão */
        button {
            background: var(--gradient-primary);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 153, 255, 0.3);
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
            .container {
                padding: 1rem;
            }

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
</head>
<body>
    <div class="container">
        <h2>📊 Relatórios Mensais Fechados</h2>

        <?php if ($resultado->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Usuário</th>
                        <th>Mês</th>
                        <th>Carga Diária</th>
                        <th>Total Trabalhado</th>
                        <th>Horas Extras</th>
                        <th>Horas Faltantes</th>
                        <th>Data do Relatório</th>
                        <th>PDF</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($relatorio = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($relatorio['usuario']) ?></td>
                            <td><?= htmlspecialchars($relatorio['mes']) ?></td>
                            <td><?= $relatorio['carga_diaria'] ?></td>
                            <td><?= $relatorio['total_trabalhado'] ?></td>
                            <td><?= $relatorio['horas_extras'] ?></td>
                            <td><?= $relatorio['horas_faltantes'] ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($relatorio['criado_em'])) ?></td>
                            <td>
                                <button onclick="gerarPDF(
                                    `<?= addslashes($relatorio['usuario']) ?>`,
                                    `<?= $relatorio['mes'] ?>`,
                                    `<?= $relatorio['carga_diaria'] ?>`,
                                    `<?= $relatorio['total_trabalhado'] ?>`,
                                    `<?= $relatorio['horas_extras'] ?>`,
                                    `<?= $relatorio['horas_faltantes'] ?>`,
                                    `<?= date('d/m/Y H:i', strtotime($relatorio['criado_em'])) ?>`
                                )">📥 PDF</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum relatório mensal foi gerado ainda.</p>
        <?php endif; ?>
    </div>

<script>
    window.jsPDF = window.jspdf.jsPDF;

    function gerarPDF(usuario, mes, carga, trabalhado, extras, faltantes, dataRelatorio) {
        const pdf = new jsPDF();

        pdf.setFontSize(18);
        pdf.text("🧠 Mente Neural - Relatório Mensal", 14, 20);

        pdf.setFontSize(12);
        pdf.text(`Funcionário: ${usuario}`, 14, 30);
        pdf.text(`Mês Referente: ${mes}`, 14, 38);
        pdf.text(`Carga Diária: ${carga}`, 14, 46);
        pdf.text(`Total Trabalhado: ${trabalhado}`, 14, 54);
        pdf.text(`Horas Extras: ${extras}`, 14, 62);
        pdf.text(`Horas Faltantes: ${faltantes}`, 14, 70);
        pdf.text(`Gerado em: ${dataRelatorio}`, 14, 78);

        pdf.save(`relatorio-${usuario.toLowerCase().replace(/\s+/g, "_")}-${mes}.pdf`);
    }
</script>
</body>
</html>
