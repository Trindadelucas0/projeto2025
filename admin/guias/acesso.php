<?php
require_once '../conexao.php';


// Bloquear / liberar usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usuario_id'])) {
    $id = $_POST['usuario_id'];
    $novo_status = $_POST['novo_status'];
    
    $sql = "UPDATE usuarios SET ativo = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $novo_status, $id);
    $stmt->execute();
}

// Listar todos os usuários (menos admins)
$sql = "SELECT * FROM usuarios WHERE tipo = 'admin' ORDER BY nome";
$res = $conn->query($sql);
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

<h2>🔐 Gerenciar Acesso</h2>

<table>
    <thead>
        <tr>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Status</th>
            <th>Ação</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($user = $res->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($user['nome']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= $user['ativo'] ? '✅ Ativo' : '❌ Bloqueado' ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="usuario_id" value="<?= $user['id'] ?>">
                        <input type="hidden" name="novo_status" value="<?= $user['ativo'] ? 0 : 1 ?>">
                        <button type="submit">
                            <?= $user['ativo'] ? 'Bloquear' : 'Liberar' ?>
                        </button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
