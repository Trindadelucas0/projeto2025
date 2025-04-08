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

<<<<<<< HEAD
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
        <?php while ($user = $resultado->fetch_assoc()): ?>
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
=======
<div class="guia-container animate-fade-in">
    <div class="guia-header">
        <h1><i class="fas fa-key"></i> Gerenciar Acesso</h1>
        <button class="btn btn-primary" onclick="abrirModal('novo-admin')">
            <i class="fas fa-plus"></i> Novo Administrador
        </button>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="icon blue">
                <i class="fas fa-users"></i>
            </div>
            <h3>Total de Administradores</h3>
            <p class="value"><?= $res->num_rows ?></p>
        </div>
        <div class="stat-card">
            <div class="icon green">
                <i class="fas fa-user-check"></i>
            </div>
            <h3>Administradores Ativos</h3>
            <p class="value"><?= $res->num_rows ?></p>
        </div>
    </div>

    <div class="table-container">
        <div class="table-header">
            <h2>Lista de Administradores</h2>
            <div class="table-actions">
                <button class="btn btn-primary" onclick="exportarDados()">
                    <i class="fas fa-download"></i> Exportar
                </button>
            </div>
        </div>

        <table class="modern-table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($admin = $res->fetch_assoc()): ?>
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <img src="<?= $admin['foto'] ?? 'uploads/perfil/default.jpg' ?>" 
                                 alt="Foto" 
                                 style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                            <?= htmlspecialchars($admin['nome']) ?>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($admin['email']) ?></td>
                    <td>
                        <span class="status-badge status-active">
                            <i class="fas fa-check-circle"></i> Ativo
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <button class="btn btn-primary" onclick="editarAdmin(<?= $admin['id'] ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger" onclick="excluirAdmin(<?= $admin['id'] ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Novo Admin -->
<div id="modal-novo-admin" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-user-plus"></i> Novo Administrador</h2>
            <button onclick="fecharModal('novo-admin')" class="close-btn">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="processar-admin.php" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nome</label>
                <input type="text" name="nome" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Senha</label>
                <input type="password" name="senha" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Foto de Perfil</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="fecharModal('novo-admin')">Cancelar</button>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Salvar
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Estilos do Modal */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    background: white;
    border-radius: 15px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    animation: modalSlideIn 0.3s ease;
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    margin: 0;
    color: #2c3e50;
    font-size: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.close-btn {
    background: none;
    border: none;
    font-size: 20px;
    color: #666;
    cursor: pointer;
    padding: 5px;
    transition: color 0.3s ease;
}

.close-btn:hover {
    color: #e74c3c;
}

.modal-footer {
    padding: 20px;
    border-top: 1px solid #e9ecef;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

@keyframes modalSlideIn {
    from {
        transform: translateY(-20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>

<script>
function abrirModal(id) {
    document.getElementById('modal-' + id).style.display = 'flex';
}

function fecharModal(id) {
    document.getElementById('modal-' + id).style.display = 'none';
}

function editarAdmin(id) {
    // Implementar edição
}

function excluirAdmin(id) {
    if (confirm('Tem certeza que deseja excluir este administrador?')) {
        // Implementar exclusão
    }
}

function exportarDados() {
    // Implementar exportação
}
</script>
>>>>>>> 6a4371b505c2969eaa843912487b25d6dab79c77
