<div class="relatorios-container">
    <div class="relatorios-header">
        <h2>Relatórios</h2>
        <div class="relatorios-filtros">
            <select name="mes" id="mes" class="select-filtro">
                <option value="">Selecione o mês</option>
                <?php
                $meses = [
                    '01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março',
                    '04' => 'Abril', '05' => 'Maio', '06' => 'Junho',
                    '07' => 'Julho', '08' => 'Agosto', '09' => 'Setembro',
                    '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'
                ];
                foreach ($meses as $valor => $nome) {
                    $selected = ($mes_atual == $valor) ? 'selected' : '';
                    echo "<option value='$valor' $selected>$nome</option>";
                }
                ?>
            </select>
            <select name="ano" id="ano" class="select-filtro">
                <?php
                $ano_atual = date('Y');
                for ($i = $ano_atual; $i >= $ano_atual - 2; $i--) {
                    $selected = ($ano_atual == $i) ? 'selected' : '';
                    echo "<option value='$i' $selected>$i</option>";
                }
                ?>
            </select>
            <button class="btn-primary" onclick="gerarRelatorio()">Gerar Relatório</button>
        </div>
    </div>

    <div class="relatorio-card">
        <div class="relatorio-header">
            <h3>Relatório de <?php echo $meses[$mes_atual]; ?> de <?php echo $ano_atual; ?></h3>
            <div class="relatorio-acoes">
                <button class="btn-icon" onclick="exportarPDF()">
                    <i class="fas fa-file-pdf"></i>
                </button>
                <button class="btn-icon" onclick="exportarExcel()">
                    <i class="fas fa-file-excel"></i>
                </button>
            </div>
        </div>

        <div class="relatorio-conteudo">
            <div class="relatorio-resumo">
                <div class="resumo-item">
                    <span class="resumo-label">Total de Horas</span>
                    <span class="resumo-valor"><?php echo $total_horas; ?>h</span>
                </div>
                <div class="resumo-item">
                    <span class="resumo-label">Dias Trabalhados</span>
                    <span class="resumo-valor"><?php echo $dias_trabalhados; ?></span>
                </div>
                <div class="resumo-item">
                    <span class="resumo-label">Média Diária</span>
                    <span class="resumo-valor"><?php echo $media_diaria; ?>h</span>
                </div>
            </div>

            <div class="relatorio-detalhes">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Entrada</th>
                            <th>Saída</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $registro): ?>
                            <tr>
                                <td><?php echo date('d/m/Y', strtotime($registro['data'])); ?></td>
                                <td><?php echo $registro['hora_entrada']; ?></td>
                                <td><?php echo $registro['hora_saida']; ?></td>
                                <td><?php echo $registro['total_horas']; ?>h</td>
                                <td>
                                    <span class="status-badge <?php echo $registro['status']; ?>">
                                        <?php echo ucfirst($registro['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div> 