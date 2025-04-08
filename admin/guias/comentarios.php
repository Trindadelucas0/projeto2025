<?php
require_once '../conexao.php';

// Busca os comentários com nome do usuário
$sql = "SELECT c.*, u.nome 
        FROM comentarios c 
        INNER JOIN usuarios u ON u.id = c.usuario_id 
        ORDER BY c.data_envio DESC";

$resultado = $conn->query($sql);
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

    /* Estilos para as estrelas */
    .estrelas {
        color: #ffd700;
        letter-spacing: 2px;
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

<h2>💬 Comentários dos Usuários</h2>

<table>
    <thead>
        <tr>
            <th>Usuário</th>
            <th>Comentário</th>
            <th>Nota</th>
            <th>Data</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($resultado->num_rows > 0): ?>
            <?php while ($comentario = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($comentario['nome']) ?></td>
                    <td><?= nl2br(htmlspecialchars($comentario['comentario'])) ?></td>
                    <td>
                        <span class="estrelas"><?= str_repeat("⭐", $comentario['nota']) ?></span>
                        (<?= $comentario['nota'] ?>/5)
                    </td>
                    <td><?= date("d/m/Y H:i", strtotime($comentario['data_envio'])) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">Nenhum comentário encontrado.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
