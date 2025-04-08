<div class="status-container">
    <div class="status-header">
        <h2>Verificação de Status</h2>
        <div class="status-filtros">
            <input type="date" id="data" class="input-filtro" value="<?php echo date('Y-m-d'); ?>">
            <button class="btn-primary" onclick="verificarStatus()">Verificar</button>
        </div>
    </div>

    <div class="status-card">
        <div class="status-info">
            <div class="info-item">
                <span class="info-label">Data</span>
                <span class="info-valor"><?php echo date('d/m/Y', strtotime($data)); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Status</span>
                <span class="status-badge <?php echo $status; ?>">
                    <?php echo ucfirst($status); ?>
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Total de Horas</span>
                <span class="info-valor"><?php echo $total_horas; ?>h</span>
            </div>
        </div>

        <div class="status-detalhes">
            <div class="detalhe-item">
                <div class="detalhe-header">
                    <h4>Entrada</h4>
                    <span class="hora"><?php echo $hora_entrada; ?></span>
                </div>
                <div class="detalhe-conteudo">
                    <p>Localização: <?php echo $localizacao_entrada; ?></p>
                    <p>IP: <?php echo $ip_entrada; ?></p>
                </div>
            </div>

            <div class="detalhe-item">
                <div class="detalhe-header">
                    <h4>Saída</h4>
                    <span class="hora"><?php echo $hora_saida ?? 'Não registrada'; ?></span>
                </div>
                <?php if ($hora_saida): ?>
                    <div class="detalhe-conteudo">
                        <p>Localização: <?php echo $localizacao_saida; ?></p>
                        <p>IP: <?php echo $ip_saida; ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div> 