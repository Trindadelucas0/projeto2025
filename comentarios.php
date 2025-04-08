<div class="comentarios-container">
    <div class="comentarios-header">
        <h2>Comentários</h2>
        <button class="btn-primary" onclick="abrirModalComentario()">Novo Comentário</button>
    </div>

    <div class="comentarios-lista">
        <?php foreach ($comentarios as $comentario): ?>
            <div class="comentario-card">
                <div class="comentario-header">
                    <div class="comentario-autor">
                        <img src="<?php echo $comentario['foto_perfil'] ?? 'assets/img/default-avatar.png'; ?>" alt="Foto do perfil" class="avatar">
                        <span><?php echo htmlspecialchars($comentario['nome']); ?></span>
                    </div>
                    <div class="comentario-data">
                        <?php echo date('d/m/Y H:i', strtotime($comentario['data_comentario'])); ?>
                    </div>
                </div>
                <div class="comentario-conteudo">
                    <?php echo nl2br(htmlspecialchars($comentario['comentario'])); ?>
                </div>
                <?php if ($comentario['id_usuario'] == $_SESSION['id_usuario']): ?>
                    <div class="comentario-acoes">
                        <button class="btn-icon" onclick="editarComentario(<?php echo $comentario['id']; ?>)">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-icon" onclick="excluirComentario(<?php echo $comentario['id']; ?>)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal de Comentário -->
<div id="modalComentario" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Novo Comentário</h3>
            <button class="btn-icon" onclick="fecharModalComentario()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="formComentario" class="comentario-form">
            <textarea name="comentario" placeholder="Digite seu comentário..." required></textarea>
            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="fecharModalComentario()">Cancelar</button>
                <button type="submit" class="btn-primary">Enviar</button>
            </div>
        </form>
    </div>
</div> 