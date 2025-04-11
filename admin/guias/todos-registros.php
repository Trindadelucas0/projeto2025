<?php
require_once '../conexao.php';

// Filtros
$nome = $_GET['nome'] ?? '';
$data = $_GET['data'] ?? '';

$sql = "SELECT r.*, u.nome AS nome_usuario 
        FROM registro_ponto r 
        JOIN usuarios u ON u.id = r.usuario_id 
        WHERE 1";

$tipos = '';
$params = [];
$paramRefs = [];

// Filtro por nome
if (!empty($nome)) {
    $sql .= " AND u.nome LIKE ?";
    $tipos .= 's';
    $params[] = "%$nome%";
}

// Filtro por data
if (!empty($data)) {
    $sql .= " AND r.data = ?";
    $tipos .= 's';
    $params[] = $data;
}

$sql .= " ORDER BY r.data DESC, r.hora ASC";

// Preparar e executar
$stmt = $conn->prepare($sql);

// Se tiver parâmetros, usar bind_param com call_user_func_array
if (!empty($params)) {
    $paramRefs[] = $tipos;
    foreach ($params as $key => $value) {
        $paramRefs[] = &$params[$key]; // Referência
    }
    call_user_func_array([$stmt, 'bind_param'], $paramRefs);
}

$stmt->execute();
$resultado = $stmt->get_result();
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

    /* Container principal */
    .container {
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
        margin-bottom: 1rem;
        text-align: center;
        background: var(--gradient-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    /* Estilo para mensagem informativa */
    .info-mensagem {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-mensagem i {
        color: var(--primary-color);
        font-size: 20px;
    }

    .info-mensagem p {
        margin: 0;
        color: var(--text-color);
        font-size: 14px;
    }

    /* Estilos do formulário de filtro */
    .filtro-form {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .filtro-form input {
        padding: 0.8rem;
        border-radius: 8px;
        border: 1px solid var(--glass-border);
        background: var(--glass-bg);
        color: var(--text-color);
        flex: 1;
        min-width: 200px;
    }

    .filtro-form input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(0, 153, 255, 0.2);
    }

    .filtro-form button,
    .filtro-form a {
        padding: 0.8rem 1.5rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        text-align: center;
    }

    .filtro-form button {
        background: var(--gradient-primary);
        color: white;
    }

    .filtro-form a {
        background: var(--glass-bg);
        color: var(--text-color);
        border: 1px solid var(--glass-border);
    }

    .filtro-form button:hover,
    .filtro-form a:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 153, 255, 0.3);
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

    /* Estilos da imagem */
    .foto-thumb {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        transition: var(--transition);
    }

    .foto-thumb:hover {
        transform: scale(1.1);
    }

    /* Estilos dos links de ação */
    .acao-link {
        color: var(--text-color);
        text-decoration: none;
        margin-right: 1rem;
        transition: var(--transition);
    }

    .acao-link:hover {
        color: var(--primary-color);
    }

    /* Modal de visualização */
    #modal-foto {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    #modal-foto span {
        position: absolute;
        top: 20px;
        right: 30px;
        font-size: 30px;
        color: white;
        cursor: pointer;
        transition: var(--transition);
    }

    #modal-foto span:hover {
        color: var(--primary-color);
    }

    #imagem-ampliada {
        max-width: 90%;
        max-height: 90%;
        border: 5px solid white;
        border-radius: 10px;
        box-shadow: var(--card-shadow);
    }

    /* Responsividade */
    @media screen and (max-width: 768px) {
        .container {
            padding: 1rem;
        }

        .filtro-form {
            flex-direction: column;
        }

        .filtro-form input,
        .filtro-form button,
        .filtro-form a {
            width: 100%;
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

<h2>📁 Registros de Todos os Usuários</h2>

<div class="info-mensagem">
    <i class="fas fa-info-circle"></i>
    <p>Observação: Apenas os usuários podem editar seus próprios registros. Os administradores podem apenas visualizar os registros.</p>
</div>

<div class="container">
    <form method="GET" class="filtro-form">
        <input type="hidden" name="guia" value="todos-registros">
        <input type="text" name="nome" placeholder="Filtrar por nome" value="<?= htmlspecialchars($nome) ?>">
        <input type="date" name="data" value="<?= htmlspecialchars($data) ?>">
        <button type="submit">🔍 Filtrar</button>
        <a href="painel.php?guia=todos-registros">🔄 Limpar</a>
    </form>

    <table>
        <thead>
            <tr>
                <th>Usuário</th>
                <th>Data</th>
                <th>Tipo de Ponto</th>
                <th>Hora</th>
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado->num_rows > 0): ?>
                <?php while ($r = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['nome_usuario']) ?></td>
                        <td><?= htmlspecialchars($r['data']) ?></td>
                        <td><?= htmlspecialchars($r['tipo_ponto']) ?></td>
                        <td><?= htmlspecialchars($r['hora']) ?></td>
                        <td>
                            <?php if ($r['foto']): ?>
                                <img src="../uploads/<?= htmlspecialchars($r['foto']) ?>" alt="Foto" class="foto-thumb" onclick="verImagem(this.src)">
                            <?php else: ?>
                                Sem foto
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6">Nenhum registro encontrado.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal de visualização -->
<div id="modal-foto">
    <span onclick="fecharImagem()">✖</span>
    <img id="imagem-ampliada" src="" alt="Imagem ampliada">
</div>

<script>
    function verImagem(src) {
        document.getElementById('imagem-ampliada').src = src;
        document.getElementById('modal-foto').style.display = 'flex';
    }

    function fecharImagem() {
        document.getElementById('modal-foto').style.display = 'none';
    }
</script>
