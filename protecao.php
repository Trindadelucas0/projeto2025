<div class="protecao-container">
    <div class="protecao-header">
        <h2>Proteção de Dados</h2>
        <div class="protecao-status">
            <span class="status-badge <?php echo $status_protecao; ?>">
                <?php echo ucfirst($status_protecao); ?>
            </span>
        </div>
    </div>

    <div class="protecao-card">
        <div class="protecao-info">
            <div class="info-item">
                <span class="info-label">Última Atualização</span>
                <span class="info-valor"><?php echo date('d/m/Y H:i', strtotime($ultima_atualizacao)); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Nível de Proteção</span>
                <span class="info-valor"><?php echo $nivel_protecao; ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Status do Backup</span>
                <span class="status-badge <?php echo $status_backup; ?>">
                    <?php echo ucfirst($status_backup); ?>
                </span>
            </div>
        </div>

        <div class="protecao-acoes">
            <button class="btn-primary" onclick="realizarBackup()">
                <i class="fas fa-download"></i> Realizar Backup
            </button>
            <button class="btn-secondary" onclick="verificarIntegridade()">
                <i class="fas fa-shield-alt"></i> Verificar Integridade
            </button>
        </div>

        <div class="protecao-historico">
            <h3>Histórico de Proteção</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Ação</th>
                        <th>Status</th>
                        <th>Detalhes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historico as $registro): ?>
                        <tr>
                            <td><?php echo date('d/m/Y H:i', strtotime($registro['data'])); ?></td>
                            <td><?php echo $registro['acao']; ?></td>
                            <td>
                                <span class="status-badge <?php echo $registro['status']; ?>">
                                    <?php echo ucfirst($registro['status']); ?>
                                </span>
                            </td>
                            <td><?php echo $registro['detalhes']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div> 