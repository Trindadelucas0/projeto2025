document.getElementById("calcular-horas").addEventListener("click", () => {
    const cargaInput = document.getElementById("carga-horaria").value;
    if (!cargaInput || !/^\d{2}:\d{2}$/.test(cargaInput)) {
        alert("Informe a carga horária diária no formato HH:MM");
        return;
    }

    const [chHoras, chMinutos] = cargaInput.split(":").map(Number);
    const cargaDiariaMin = chHoras * 60 + chMinutos;

    const registros = document.querySelectorAll("#tabela-registros tr");
    const porData = {};

    registros.forEach(linha => {
        const colunas = linha.querySelectorAll("td");
        if (colunas.length < 3) return;

        const data = colunas[0].innerText.trim();
        const tipo = colunas[1].innerText.trim();
        const hora = colunas[2].innerText.trim();

        if (!porData[data]) porData[data] = [];
        porData[data].push({ tipo, hora });
    });

    let totalMinTrabalhado = 0;

    Object.values(porData).forEach(pontos => {
        const horarios = {};

        pontos.forEach(p => {
            horarios[p.tipo] = p.hora;
        });

        let minutosDia = 0;
        if (horarios.entrada && horarios.saida) {
            minutosDia += calcularDiferenca(horarios.entrada, horarios.saida);
        }
        if (horarios.almoco && horarios.retorno) {
            minutosDia += calcularDiferenca(horarios.retorno, horarios.almoco);
        }
        if (horarios.intervalo_inicio && horarios.intervalo_fim) {
            minutosDia += calcularDiferenca(horarios.intervalo_fim, horarios.intervalo_inicio);
        }

        totalMinTrabalhado += minutosDia;
    });

    const cargaMensalMin = cargaDiariaMin * 30;
    const horasTrabalhadas = formatarTempo(totalMinTrabalhado);
    const horasExtras = totalMinTrabalhado > cargaMensalMin ? formatarTempo(totalMinTrabalhado - cargaMensalMin) : "00:00";
    const horasFaltantes = totalMinTrabalhado < cargaMensalMin ? formatarTempo(cargaMensalMin - totalMinTrabalhado) : "00:00";

    document.getElementById("horas-trabalhadas").textContent = horasTrabalhadas;
    document.getElementById("horas-extras").textContent = horasExtras;
    document.getElementById("horas-faltantes").textContent = horasFaltantes;
});

function calcularDiferenca(hora1, hora2) {
    const [h1, m1] = hora1.split(":").map(Number);
    const [h2, m2] = hora2.split(":").map(Number);
    const t1 = h1 * 60 + m1;
    const t2 = h2 * 60 + m2;
    return Math.abs(t2 - t1);
}

function formatarTempo(minutos) {
    const h = String(Math.floor(minutos / 60)).padStart(2, "0");
    const m = String(minutos % 60).padStart(2, "0");
    return `${h}:${m}`;
}
