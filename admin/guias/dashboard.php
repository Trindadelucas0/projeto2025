<?php
// Não precisa iniciar a sessão aqui pois já está iniciada no painel.php
// session_start();
require_once __DIR__ . '/../../conexao.php';

// Verifica se é admin
if (!isset($_SESSION['usuario_id']) || ($_SESSION['tipo'] ?? '') !== 'admin') {
    echo "<script>window.location.href = '../../login.php';</script>";
    exit();
}

// Cria a tabela se não existir
$sql_criar_tabela = "CREATE TABLE IF NOT EXISTS dashboard_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(50) NOT NULL,
    conteudo TEXT NOT NULL,
    cor VARCHAR(20) DEFAULT '#0099ff',
    icone VARCHAR(50) DEFAULT 'fas fa-info-circle',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($sql_criar_tabela)) {
    die("Erro ao criar tabela: " . $conn->error);
}

// Processa o formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['acao']) && $_POST['acao'] === 'editar') {
        $id = (int)$_POST['id'];
        $tipo = $_POST['tipo'];
        $conteudo = $_POST['conteudo'];
        $cor = $_POST['cor'];
        $icone = $_POST['icone'];
        
        $stmt = $conn->prepare("UPDATE dashboard_items SET tipo = ?, conteudo = ?, cor = ?, icone = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $tipo, $conteudo, $cor, $icone, $id);
        $stmt->execute();
        $mensagem = "Item atualizado com sucesso!";
    } else {
        $tipo = $_POST['tipo'] ?? '';
        $conteudo = $_POST['conteudo'] ?? '';
        $cor = $_POST['cor'] ?? '#0099ff';
        $icone = $_POST['icone'] ?? 'fas fa-info-circle';
        
        if ($tipo && $conteudo) {
            $stmt = $conn->prepare("INSERT INTO dashboard_items (tipo, conteudo, cor, icone) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $tipo, $conteudo, $cor, $icone);
            $stmt->execute();
            $mensagem = "Item adicionado com sucesso!";
        }
    }
}

// Processa exclusão
if (isset($_GET['acao']) && $_GET['acao'] === 'excluir' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("DELETE FROM dashboard_items WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $mensagem = "Item excluído com sucesso!";
    echo "<script>window.location.href = '?guia=dashboard';</script>";
    exit();
}

// Processa edição
if (isset($_GET['acao']) && $_GET['acao'] === 'editar' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM dashboard_items WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item_editar = $result->fetch_assoc();
}

// Busca os itens existentes
$result = $conn->query("SELECT * FROM dashboard_items ORDER BY id DESC");
if ($result === false) {
    die("Erro ao buscar itens: " . $conn->error);
}
$itens = $result->fetch_all(MYSQLI_ASSOC);
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

    .dashboard-admin {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    .dashboard-admin h2 {
        color: var(--primary-color);
        font-size: 1.8rem;
        margin-bottom: 2rem;
        text-align: center;
        background: var(--gradient-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .card {
        background: var(--card-bg);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--card-shadow);
    }

    .form-dashboard {
        display: grid;
        gap: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-group label {
        color: var(--text-color);
        font-weight: 500;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 0.8rem;
        border-radius: 8px;
        border: 1px solid var(--glass-border);
        background: var(--glass-bg);
        color: var(--text-color);
    }

    .form-group textarea {
        min-height: 100px;
        resize: vertical;
    }

    .btn-primary {
        background: var(--gradient-primary);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        transition: var(--transition);
    }

    .btn-secondary {
        background: var(--glass-bg);
        color: var(--text-color);
        border: 1px solid var(--glass-border);
        padding: 1rem 2rem;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        transition: var(--transition);
        text-decoration: none;
        text-align: center;
        margin-top: 1rem;
    }

    .btn-primary:hover,
    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 153, 255, 0.3);
    }

    .itens-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .item-card {
        background: var(--glass-bg);
        border-radius: 10px;
        padding: 1.5rem;
        transition: var(--transition);
    }

    .item-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--card-shadow);
    }

    .item-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .item-tipo {
        background: var(--gradient-primary);
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.9rem;
        color: white;
    }

    .item-actions {
        margin-left: auto;
        display: flex;
        gap: 0.5rem;
    }

    .btn-edit,
    .btn-delete {
        padding: 0.5rem;
        border-radius: 4px;
        color: var(--text-color);
        text-decoration: none;
        transition: var(--transition);
    }

    .btn-edit:hover {
        color: var(--primary-color);
        background: rgba(0, 153, 255, 0.1);
    }

    .btn-delete:hover {
        color: #ff4444;
        background: rgba(255, 68, 68, 0.1);
    }

    .item-content {
        color: var(--text-color);
        line-height: 1.6;
    }

    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .alert-success {
        background: rgba(0, 255, 136, 0.1);
        border: 1px solid var(--success-color);
        color: var(--success-color);
    }

    .form-text {
        font-size: 0.875rem;
        color: var(--light-text);
        margin-top: 0.25rem;
    }

    @media screen and (max-width: 768px) {
        .dashboard-admin {
            padding: 1rem;
        }
        
        .card {
            padding: 1.5rem;
        }
        
        .itens-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="dashboard-admin">
    <h2><i class="fas fa-tachometer-alt"></i> Gerenciar Dashboard</h2>
    
    <?php if (isset($mensagem)): ?>
        <div class="alert alert-success">
            <?php echo $mensagem; ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <h3><?php echo isset($item_editar) ? 'Editar Item' : 'Adicionar Novo Item'; ?></h3>
        <form method="POST" class="form-dashboard">
            <?php if (isset($item_editar)): ?>
                <input type="hidden" name="acao" value="editar">
                <input type="hidden" name="id" value="<?php echo $item_editar['id']; ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="tipo">Tipo de Item:</label>
                <select name="tipo" id="tipo" required>
                    <option value="dica_seguranca" <?php echo (isset($item_editar) && $item_editar['tipo'] === 'dica_seguranca') ? 'selected' : ''; ?>>Dica de Segurança Digital</option>
                    <option value="campanha" <?php echo (isset($item_editar) && $item_editar['tipo'] === 'campanha') ? 'selected' : ''; ?>>Campanha em Andamento</option>
                    <option value="motivacao" <?php echo (isset($item_editar) && $item_editar['tipo'] === 'motivacao') ? 'selected' : ''; ?>>Frase Motivacional</option>
                    <option value="info" <?php echo (isset($item_editar) && $item_editar['tipo'] === 'info') ? 'selected' : ''; ?>>Informação Interna</option>
                </select>
            </div>

            <div class="form-group">
                <label for="conteudo">Conteúdo:</label>
                <textarea name="conteudo" id="conteudo" required placeholder="Digite o conteúdo do item. Para campanhas, inclua o título na primeira linha."><?php echo isset($item_editar) ? htmlspecialchars($item_editar['conteudo']) : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label for="cor">Cor do Item:</label>
                <input type="color" name="cor" id="cor" value="<?php echo isset($item_editar) ? htmlspecialchars($item_editar['cor']) : '#0099ff'; ?>">
                <small class="form-text">Dicas: Use azul (#0099ff) para dicas de segurança, rosa (#ff69b4) para Outubro Rosa, azul claro (#87ceeb) para campanhas de vacinação</small>
            </div>

            <div class="form-group">
                <label for="icone">Ícone (FontAwesome):</label>
                <input type="text" name="icone" id="icone" value="<?php echo isset($item_editar) ? htmlspecialchars($item_editar['icone']) : 'fas fa-info-circle'; ?>" required>
                <small class="form-text">Sugestões: 🔐 fas fa-lock (segurança), 🎯 fas fa-bullseye (campanhas), 💪 fas fa-fist-raised (motivação)</small>
            </div>

            <button type="submit" class="btn-primary">
                <i class="fas fa-<?php echo isset($item_editar) ? 'save' : 'plus'; ?>"></i>
                <?php echo isset($item_editar) ? 'Salvar Alterações' : 'Adicionar Item'; ?>
            </button>

            <?php if (isset($item_editar)): ?>
                <a href="?guia=dashboard" class="btn-secondary">Cancelar Edição</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="card">
        <h3>Itens Existentes</h3>
        <div class="itens-grid">
            <?php foreach ($itens as $item): ?>
                <div class="item-card" data-tipo="<?php echo htmlspecialchars($item['tipo']); ?>" style="border-left: 4px solid <?php echo htmlspecialchars($item['cor']); ?>">
                    <div class="item-header">
                        <i class="<?php echo htmlspecialchars($item['icone']); ?>"></i>
                        <span class="item-tipo"><?php echo ucfirst(str_replace('_', ' ', $item['tipo'])); ?></span>
                        <div class="item-actions">
                            <a href="?guia=dashboard&acao=editar&id=<?php echo $item['id']; ?>" class="btn-edit" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="?guia=dashboard&acao=excluir&id=<?php echo $item['id']; ?>" class="btn-delete" onclick="return confirm('Tem certeza que deseja excluir este item?')" title="Excluir">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                    <div class="item-content">
                        <?php echo nl2br(htmlspecialchars($item['conteudo'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoSelect = document.getElementById('tipo');
    const corInput = document.getElementById('cor');
    const iconeInput = document.getElementById('icone');

    // Função para atualizar cor e ícone baseado no tipo
    function atualizarEstilo() {
        const tipo = tipoSelect.value;
        switch(tipo) {
            case 'dica_seguranca':
                corInput.value = '#0099ff';
                iconeInput.value = 'fas fa-lock';
                break;
            case 'campanha':
                corInput.value = '#ff69b4';
                iconeInput.value = 'fas fa-bullseye';
                break;
            case 'motivacao':
                corInput.value = '#4CAF50';
                iconeInput.value = 'fas fa-fist-raised';
                break;
            case 'info':
                corInput.value = '#FFC107';
                iconeInput.value = 'fas fa-info-circle';
                break;
        }
    }

    tipoSelect.addEventListener('change', atualizarEstilo);
});
</script> 