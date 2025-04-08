<?php
require_once '../conexao.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach ($_POST as $id => $conteudo) {
        $sql = "UPDATE textos_site SET conteudo = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $conteudo, $id);
        $stmt->execute();
    }
    echo "<p class='mensagem-sucesso'>✅ Textos atualizados com sucesso!</p>";
}

// Pega todos os textos
$sql = "SELECT * FROM textos_site";
$resultado = $conn->query($sql);

$textos = [];
while ($row = $resultado->fetch_assoc()) {
    $textos[$row['id']] = $row['conteudo'];
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

    /* Container do formulário */
    .form-container {
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

    /* Estilos dos campos */
    label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--text-color);
        font-weight: 500;
    }

    input[type="text"],
    textarea {
        width: 100%;
        padding: 0.8rem;
        margin-bottom: 1.5rem;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 8px;
        color: var(--text-color);
        transition: var(--transition);
    }

    input[type="text"]:focus,
    textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(0, 153, 255, 0.2);
    }

    /* Estilos do botão */
    button {
        background: var(--gradient-primary);
        color: white;
        border: none;
        padding: 0.8rem 2rem;
        border-radius: 8px;
        cursor: pointer;
        transition: var(--transition);
        font-weight: 500;
        width: 100%;
    }

    button:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 153, 255, 0.3);
    }

    /* Mensagem de sucesso */
    .mensagem-sucesso {
        background: rgba(0, 255, 136, 0.1);
        color: var(--success-color);
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        text-align: center;
        border: 1px solid var(--success-color);
    }

    /* Responsividade */
    @media screen and (max-width: 768px) {
        .form-container {
            padding: 1rem;
        }

        h2 {
            font-size: 1.5rem;
        }

        input[type="text"],
        textarea {
            padding: 0.6rem;
        }
    }
</style>

<h2>📝 Editar Textos da Página Inicial</h2>

<div class="form-container">
    <form method="POST">
        <label>Título do Banner:</label>
        <input type="text" name="banner_titulo" value="<?= htmlspecialchars($textos['banner_titulo'] ?? '') ?>">

        <label>Subtítulo do Banner:</label>
        <textarea name="banner_subtitulo" rows="2"><?= htmlspecialchars($textos['banner_subtitulo'] ?? '') ?></textarea>

        <label>Como Funciona:</label>
        <textarea name="como_funciona" rows="4"><?= htmlspecialchars($textos['como_funciona'] ?? '') ?></textarea>

        <label>Benefícios:</label>
        <textarea name="beneficios" rows="4"><?= htmlspecialchars($textos['beneficios'] ?? '') ?></textarea>

        <label>Rodapé:</label>
        <input type="text" name="rodape" value="<?= htmlspecialchars($textos['rodape'] ?? '') ?>">

        <button type="submit">Salvar Alterações</button>
    </form>
</div>
