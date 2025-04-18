<?php
session_start();
require 'conexao.php';
require 'proteger.php';
require 'verificar-ativo.php';
include_once "header.php";

// Logs para verificar a sessão
error_log("Sessão iniciada: " . (session_status() === PHP_SESSION_ACTIVE ? "Sim" : "Não"));
error_log("Conteúdo da sessão: " . print_r($_SESSION, true));

$usuario_id = $_SESSION['usuario_id'];

// Log do ID do usuário
error_log("ID do usuário da sessão: " . $usuario_id);

// Buscar registros do usuário logado
$sql = "SELECT * FROM registro_ponto 
        WHERE usuario_id = ? 
        ORDER BY data DESC, hora ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

// Adicionando logs para depuração
error_log("Usuário ID: " . $usuario_id);
error_log("SQL Query: " . $sql);
error_log("Número de registros encontrados: " . $resultado->num_rows);
if ($resultado->num_rows === 0) {
    error_log("Nenhum registro encontrado para o usuário");
} else {
    error_log("Registros encontrados: " . $resultado->num_rows);
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
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Registros</title>
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
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
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

        /* Tabela de registros */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 2rem;
            background: var(--card-bg);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--glass-border);
        }
        
        /* Estilo para mensagens */
        .mensagem {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            text-align: center;
            font-weight: 500;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--glass-border);
        }

        th {
            background: var(--gradient-dark);
            color: var(--text-color);
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 1px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background: var(--glass-bg);
        }

        /* Imagem na tabela */
        img {
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: var(--transition);
        }

        img:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        /* Botões de ação */
        .editar-btn, .excluir-btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            margin-right: 0.5rem;
        }

        .editar-btn {
            background: var(--gradient-primary);
            color: white;
        }

        .excluir-btn {
            background: rgba(255, 68, 68, 0.1);
            color: var(--error-color);
            border: 1px solid var(--error-color);
        }

        .editar-btn:hover, .excluir-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        /* Formulário de carga horária */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-color);
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            max-width: 200px;
            padding: 0.8rem 1rem;
            border-radius: 10px;
            border: 1px solid var(--glass-border);
            background: var(--glass-bg);
            color: var(--text-color);
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(0, 153, 255, 0.2);
        }

        /* Resultado de horas */
        #resultado-horas {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--glass-border);
        }

        #resultado-horas p {
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }

        #resultado-horas span {
            font-weight: 500;
            color: var(--primary-color);
        }

        #resultado-horas button {
            margin-top: 1rem;
            padding: 0.8rem 1.5rem;
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }

        #resultado-horas button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 153, 255, 0.4);
        }

        /* Formulário de relatório mensal */
        form {
            margin-top: 2rem;
        }

        form button {
            padding: 0.8rem 1.5rem;
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }

        form button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 153, 255, 0.4);
        }
       
        .oculto {
            display: none;
                }



        /* Responsividade */
        @media screen and (max-width: 768px) {
            .container {
                padding: 10px;
            }

            h2 {
                font-size: 1.8rem;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            th, td {
                padding: 0.8rem;
            }

            .editar-btn, .excluir-btn {
                padding: 0.4rem 0.8rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>📜 Meus Registros de Ponto</h2>

    <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="mensagem">
            <?= $_SESSION['mensagem'] ?>
        </div>
        <?php unset($_SESSION['mensagem']); ?>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Tipo de Ponto</th>
                <th>Horário</th>
                <th>Foto</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody id="tabela-registros">
            <?php if ($resultado->num_rows > 0): ?>
                <?php while ($registro = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($registro['data'])) ?></td>
                        <td><?= htmlspecialchars($registro['tipo_ponto']) ?></td>
                        <td><?= htmlspecialchars($registro['hora']) ?></td>
                        <td>
                            <?php if ($registro['foto']): ?>
                                <img src="uploads/<?= htmlspecialchars($registro['foto']) ?>" alt="Foto" width="80">
                            <?php else: ?>
                                Sem foto
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="editar-registro.php?id=<?= $registro['id'] ?>" class="editar-btn">✏️ Editar</a>
                            <a href="excluir-registro.php?id=<?= $registro['id'] ?>" class="excluir-btn">🗑️ Excluir</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">Nenhum registro encontrado.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="form-group">
    <label for="carga-horaria">Carga Horária Diária (HH:MM):</label>
    <input type="time" id="carga-horaria" value="08:00" required>
</div>

<button id="btn-calcular" class="pulse">🧠 Calcular Horas</button>

<div id="resultado-horas" class="oculto">
    <p>Horas Trabalhadas: <span id="horas-trabalhadas">00:00</span></p>
    <button onclick="gerarPDF()">📄 Gerar PDF</button>
</div>

    <!-- ✅ Botão para gerar relatório mensal manualmente -->
    <form action="gerar-relatorio-mensal.php" method="post">
        <input type="hidden" name="usuario_id" value="<?= $usuario_id ?>">
        <button type="submit">📅 Gerar Relatório Mensal</button>
    </form>

</div>

<script src="/JS/ver-registros.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<!-- SCRIPT FINAL UNIFICADO -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const tabela = document.getElementById("tabela-registros");
    const linhas = tabela.querySelectorAll("tbody tr");
    const cargaInput = document.getElementById("carga-horaria");
    const btnCalcular = document.getElementById("btn-calcular");
    const resultadoHoras = document.getElementById("resultado-horas");

    btnCalcular.addEventListener("click", () => {
        calcularHoras();
        resultadoHoras.style.display = "block"; // Exibir o resultado
    });

    function calcularHoras() {
        const carga = cargaInput.value;
        if (!/^\d{2}:\d{2}$/.test(carga)) return;

        const [ch, cm] = carga.split(":").map(Number);
        const cargaDiaria = ch * 60 + cm;
        const cargaMensal = cargaDiaria * 30;

        const registrosPorData = {};

        linhas.forEach(linha => {
            const colunas = linha.querySelectorAll("td");
            if (colunas.length >= 3) {
                const data = colunas[0].textContent.trim();
                const tipo = colunas[1].textContent.trim().toLowerCase();
                const hora = colunas[2].textContent.trim().slice(0, 5);

                if (!registrosPorData[data]) registrosPorData[data] = {};
                registrosPorData[data][tipo] = hora;
            }
        });

        let totalMin = 0;

        for (const data in registrosPorData) {
    const dia = registrosPorData[data];
    let minutosDia = 0;

    if (dia["feriado"]) {
        // Se for feriado, soma a carga horária diária completa
        minutosDia = cargaDiaria;
    } else {
        if (dia["entrada"] && dia["almoco"]) {
            minutosDia += calcularDiferenca(dia["entrada"], dia["almoco"]);
        }
        if (dia["retorno"] && dia["saida"]) {
            minutosDia += calcularDiferenca(dia["retorno"], dia["saida"]);
        }
        if (dia["intervalo_inicio"] && dia["intervalo_fim"]) {
            minutosDia -= calcularDiferenca(dia["intervalo_inicio"], dia["intervalo_fim"]);
        }
    }

    totalMin += minutosDia;
}


        const trabalhadas = formatarTempo(totalMin);

        document.getElementById("horas-trabalhadas").textContent = trabalhadas;
    }

    function calcularDiferenca(h1, h2) {
        const [h1h, h1m] = h1.split(":").map(Number);
        const [h2h, h2m] = h2.split(":").map(Number);
        return (h2h * 60 + h2m) - (h1h * 60 + h1m);
    }

    function formatarTempo(minutos) {
        const h = String(Math.floor(minutos / 60)).padStart(2, '0');
        const m = String(minutos % 60).padStart(2, '0');
        return `${h}:${m}`;
    }

    // PDF
    window.jsPDF = window.jspdf.jsPDF;

window.gerarPDF = async function () {
    const pdf = new jsPDF();
    const hoje = new Date().toLocaleDateString('pt-BR');
    const porData = {};
    let totalMin = 0;

    // Configurações de estilo
    const styles = {
        header: { fontSize: 16, color: [0, 0, 0] },
        subheader: { fontSize: 12, color: [80, 80, 80] },
        normal: { fontSize: 10, color: [0, 0, 0] },
        highlight: { fontSize: 11, color: [0, 102, 204] }
    };

    // Função auxiliar para desenhar linha
    function drawLine(y) {
        pdf.setDrawColor(200, 200, 200);
        pdf.line(14, y, 196, y);
    }

    // Função para adicionar texto com estilo
    function addText(text, x, y, style) {
        pdf.setFontSize(style.fontSize);
        pdf.setTextColor(...style.color);
        pdf.text(text, x, y);
    }

    // Agrupar os registros por data
    for (let i = 0; i < linhas.length; i++) {
        const colunas = linhas[i].querySelectorAll("td");
        if (colunas.length > 0) {
            const data = colunas[0].innerText.trim();
            const tipo = colunas[1].innerText.trim().toLowerCase();
            const hora = colunas[2].innerText.trim().slice(0, 5);
            const img = colunas[3].querySelector("img");
            const imgSrc = img ? img.src : null;

            if (!porData[data]) porData[data] = [];
            porData[data].push({ tipo, hora, imgSrc });
        }
    }

    const [ch, cm] = cargaInput.value.split(":").map(Number);
    const cargaDiaria = (ch * 60 + cm);
    const datas = Object.keys(porData);
    let paginaAtual = 0;

    for (let i = 0; i < datas.length; i++) {
        // Nova página para cada registro
        if (i > 0) {
            pdf.addPage();
        }

        const data = datas[i];
        const registros = porData[data];
        const tipos = {};
        const imagens = [];

        // Cabeçalho da página
        addText("Relatório de Ponto", 14, 20, styles.header);
        drawLine(22);

        let y = 35;

        // Box principal para o registro
        pdf.setFillColor(250, 250, 250);
        pdf.setDrawColor(230, 230, 230);
        pdf.roundedRect(10, y-10, 190, 250, 3, 3, 'FD');

        // Cabeçalho do registro
        addText(`Registro do Dia ${data}`, 14, y, styles.subheader);
        y += 8;
        drawLine(y);
        y += 10;

        // Lista de pontos em duas colunas
        let coluna1 = y;
        let coluna2 = y;
        
        registros.forEach((ponto, index) => {
            tipos[ponto.tipo] = ponto.hora;
            if (index < registros.length/2) {
                addText(`${capitalize(ponto.tipo)}: ${ponto.hora}`, 20, coluna1, styles.normal);
                coluna1 += 8;
            } else {
                addText(`${capitalize(ponto.tipo)}: ${ponto.hora}`, 110, coluna2, styles.normal);
                coluna2 += 8;
            }
            if (ponto.imgSrc) imagens.push(ponto.imgSrc);
        });

        y = Math.max(coluna1, coluna2) + 5;

        // Total do dia
        let minutosDia = 0;
        if (tipos["feriado"]) {
            minutosDia = cargaDiaria;
        } else {
            if (tipos["entrada"] && tipos["almoco"])
                minutosDia += calcularDiferenca(tipos["entrada"], tipos["almoco"]);
            if (tipos["retorno"] && tipos["saida"])
                minutosDia += calcularDiferenca(tipos["retorno"], tipos["saida"]);
            if (tipos["intervalo_inicio"] && tipos["intervalo_fim"])
                minutosDia -= calcularDiferenca(tipos["intervalo_inicio"], tipos["intervalo_fim"]);
        }

        totalMin += minutosDia;

        drawLine(y-2);
        addText(`Total do dia: ${formatarTempo(minutosDia)}`, 14, y+5, styles.highlight);
        y += 20;

        // Grid de imagens (2x2)
        if (imagens.length > 0) {
            const imgWidth = 85;  // Aumentado para melhor visualização
            const imgHeight = 85; // Mantendo proporção quadrada
            const spacing = 10;
            const startX = (190 - (imgWidth * 2 + spacing)) / 2 + 10;
            
            // Título da seção de imagens
            addText("Registros Fotográficos", 14, y, styles.subheader);
            y += 10;

            // Desenha as imagens em grid 2x2
            for (let j = 0; j < imagens.length; j++) {
                const row = Math.floor(j / 2);
                const col = j % 2;
                const imgData = await carregarImagem(imagens[j]);
                
                if (imgData) {
                    const x = startX + (imgWidth + spacing) * col;
                    const currentY = y + (imgHeight + spacing) * row;
                    
                    // Box de fundo para cada imagem
                    pdf.setFillColor(255, 255, 255);
                    pdf.setDrawColor(240, 240, 240);
                    pdf.roundedRect(x-2, currentY-2, imgWidth+4, imgHeight+4, 2, 2, 'FD');
                    
                    pdf.addImage(imgData, "PNG", x, currentY, imgWidth, imgHeight);
                }
            }
        }

        paginaAtual++;
    }

    // Página final com resumo
    pdf.addPage();
    const trabalhadas = formatarTempo(totalMin);

    // Design da página final
    pdf.setFillColor(240, 240, 240);
    pdf.roundedRect(10, 10, 190, 80, 3, 3, 'F');
    
    addText("Resumo do Período", 14, 30, styles.header);
    drawLine(32);
    
    // Box com o total de horas
    pdf.setFillColor(255, 255, 255);
    pdf.roundedRect(20, 45, 170, 35, 3, 3, 'F');
    
    addText("Total de Horas Trabalhadas", 65, 65, styles.subheader);
    addText(trabalhadas, 95, 75, {...styles.highlight, fontSize: 16});

    pdf.save("relatorio-ponto.pdf");
};

// Utilitários
function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function calcularDiferenca(h1, h2) {
    const [h1h, h1m] = h1.split(":").map(Number);
    const [h2h, h2m] = h2.split(":").map(Number);
    return (h2h * 60 + h2m) - (h1h * 60 + h1m);
}

function formatarTempo(minutos) {
    const h = String(Math.floor(minutos / 60)).padStart(2, '0');
    const m = String(minutos % 60).padStart(2, '0');
    return `${h}:${m}`;
}

async function carregarImagem(url) {
    return new Promise((resolve) => {
        const img = new Image();
        img.crossOrigin = "anonymous";
        img.onload = () => {
            const canvas = document.createElement("canvas");
            canvas.width = img.width;
            canvas.height = img.height;
            const ctx = canvas.getContext("2d");
            ctx.drawImage(img, 0, 0);
            resolve(canvas.toDataURL("image/png"));
        };
        img.onerror = () => resolve(null);
        img.src = url;
    });
}


});
</script>
</body>
</html>
