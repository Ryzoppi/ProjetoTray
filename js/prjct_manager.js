document.addEventListener('DOMContentLoaded', () => {
  initializePhases();
});

function initializePhases() {
  preparandoFases();

  document.querySelector('.fase').click();
}

function preparandoFases() {
  const phases = document.querySelectorAll('.fase');

  phases.forEach(fase => {
    fase.addEventListener('click', mudaFaseSelecionada);
  });
}

function mudaFaseSelecionada() {
  document.querySelectorAll('.fase').forEach(f => f.classList.remove('selecionada'));

  this.classList.add('selecionada');

  const idColuna = parseInt(this.dataset.id);
  if (document.querySelector('.adicionar_tarefa') !== null) {
    document.getElementById('coluna_idCol').value = idColuna;
  }

  atualizarDetalhesFase(idColuna);
  listarTarefasDaColuna(idColuna);
}

function atualizarDetalhesFase(idColuna) {
  const elements = {
    nomeFase: document.getElementById('nome-fase'),
    tarefasCompletas: document.getElementById('completas'),
    totalTarefas: document.getElementById('total'),
    statusColuna: document.getElementById('status'),
    barraProgresso: document.querySelector('.progresso')
  };

  const colunaSelecionada = colunasData.find(col => col.idCol === idColuna);
  if (!colunaSelecionada) return;

  elements.nomeFase.textContent = colunaSelecionada.nomeCol;

  const tarefasDaFase = tarefasData.filter(tarefa => tarefa.coluna_idCol === idColuna);
  const totalTarefasDaFase = tarefasDaFase.length;
  const tarefasConcluidas = tarefasDaFase.filter(t => t.estado_tarefa === 2).length;

  elements.tarefasCompletas.textContent = tarefasConcluidas;
  elements.totalTarefas.textContent = totalTarefasDaFase;

  const percentual = totalTarefasDaFase > 0
    ? Math.round((tarefasConcluidas / totalTarefasDaFase) * 100)
    : 0;

  elements.barraProgresso.style.width = `${percentual}%`;

  updateStatusText(elements.statusColuna, percentual);
}

function updateStatusText(element, percentage) {
  if (percentage === 100) {
    element.textContent = 'Concluído';
    element.style.color = '#4CAF50';
  } else if (percentage > 0) {
    element.textContent = 'Em andamento';
    element.style.color = '#2196F3';
  } else {
    element.textContent = 'Não iniciado';
    element.style.color = '#9E9E9E';
  }
}

function listarTarefasDaColuna(idColuna) {
  const containerTarefas = document.getElementById('tarefas');
  const tarefasDaColuna = tarefasData.filter(tarefa => tarefa.coluna_idCol === idColuna);

  containerTarefas.innerHTML = tarefasDaColuna.length === 0
    ? '<p class="sem-tarefas">Nenhuma tarefa nesta fase.</p>'
    : tarefasDaColuna.map(createTaskElement).join('');

  addTaskEventListeners(idColuna);
}

function createTaskElement(tarefa) {
  const isClientView = document.querySelector('.adicionar_tarefa') === null;
  
  return `
    <div class="tarefa-item estado-${tarefa.estado_tarefa}" data-id="${tarefa.idTarefa}">
      <div class="tarefa-conteudo">
        <div>
          <h3>${tarefa.nomeTarefa}</h3>
          <p>${tarefa.descTarefa || 'Sem descrição'}</p>
        </div>
        <div class="tarefa-acoes">
          ${isClientView
            ? `<button class="btn-sugestao" data-id="${tarefa.idTarefa}">Sugestão</button>`
            : `<button class="btn-editar" data-id="${tarefa.idTarefa}">Editar</button>
               <button class="btn-excluir" data-id="${tarefa.idTarefa}">Excluir</button>`
          }
        </div>
      </div>
      <div class="tarefa-status">
        <span class="status-badge">${getStatusText(tarefa.estado_tarefa)}</span>
      </div>
    </div>
  `;
}

function addTaskEventListeners(idColuna) {
  if (document.querySelector('.adicionar_tarefa') === null) {
    document.querySelectorAll('.btn-sugestao').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        sugerirMudanca(parseInt(btn.dataset.id), idColuna);
      });
    });
  } else {
    document.querySelectorAll('.btn-editar').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        editarTarefa(parseInt(btn.dataset.id), idColuna);
      });
    });

    document.querySelectorAll('.btn-excluir').forEach(btn => {
      btn.addEventListener('click', async (e) => {
        e.stopPropagation();
        await excluirTarefa(parseInt(btn.dataset.id), idColuna);
      });
    });
  }
}

async function editarTarefa(idTarefa, idColuna) {
  const tarefa = tarefasData.find(t => t.idTarefa === idTarefa && t.coluna_idCol === idColuna);
  if (!tarefa) return;

  const form = {
    nomeTarefa: document.getElementById('nomeTarefa'),
    descTarefa: document.getElementById('descTarefa'),
    idTarefaField: document.getElementById('idTarefa'),
    coluna_idCol: document.getElementById('coluna_idCol'),
    estadoTarefa: document.getElementById('estadoTarefa')
  };

  // Preenche os campos do formulário
  form.nomeTarefa.value = tarefa.nomeTarefa;
  form.descTarefa.value = tarefa.descTarefa || '';
  form.idTarefaField.value = idTarefa;
  form.coluna_idCol.value = tarefa.coluna_idCol;
  form.estadoTarefa.value = tarefa.estado_tarefa;
  form.estadoTarefa.style.display = 'block';

  // Altera o texto do botão para "Atualizar"
  const formActions = document.getElementById('btn_adiciona_tarefa');
  const submitBtn = document.getElementById('enviar_form');
  submitBtn.textContent = 'Atualizar';

  // Cria ou atualiza o botão de cancelar
  let cancelBtn = formActions.querySelector('.cancel-btn');
  if (!cancelBtn) {
    cancelBtn = document.createElement('button');
    cancelBtn.className = 'cancel-btn';
    cancelBtn.textContent = 'Cancelar';
    cancelBtn.type = 'button';
    formActions.insertBefore(cancelBtn, submitBtn);

    // Adiciona evento ao botão de cancelar
    cancelBtn.addEventListener('click', () => {
      resetForm();
      const faseAtiva = document.querySelector('.fase.selecionada');
      if (faseAtiva) {
        listarTarefasDaColuna(parseInt(faseAtiva.dataset.id));
      }
    });
  }

  // Rola a página para o formulário
  document.querySelector('body').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function resetForm() {
  const form = {
    nomeTarefa: document.getElementById('nomeTarefa'),
    descTarefa: document.getElementById('descTarefa'),
    idTarefaField: document.getElementById('idTarefa'),
    estadoTarefa: document.getElementById('estadoTarefa')
  };

  form.nomeTarefa.value = '';
  form.descTarefa.value = '';
  form.idTarefaField.value = '';
  form.estadoTarefa.style.display = 'none';

  const submitBtn = document.getElementById('enviar_form');
  submitBtn.textContent = 'Adicionar';

  // Remove o botão de cancelar se existir
  const cancelBtn = document.querySelector('#btn_adiciona_tarefa .cancel-btn');
  if (cancelBtn) {
    cancelBtn.remove();
  }
}

async function excluirTarefa(idTarefa, idColuna) {
  const response = await fetch('excluir_tarefa.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ idTarefa, idColuna })
  });

  // Processar a resposta do servidor
  const result = await response.json();

  if (result.success) {
    // Atualizar os dados locais antes de recarregar a lista
    const taskIndex = tarefasData.findIndex(t => t.idTarefa === idTarefa);
    if (taskIndex !== -1) {
      tarefasData.splice(taskIndex, 1);
    }

    // Recarregar a lista de tarefas e detalhes da fase
    const faseAtiva = document.querySelector('.fase.selecionada');
    if (faseAtiva) {
      const idColunaAtiva = parseInt(faseAtiva.dataset.id);
      listarTarefasDaColuna(idColunaAtiva);
      atualizarDetalhesFase(idColunaAtiva);
      resetForm();
    }
  }
}

function getStatusText(estado) {
  const statusMap = {
    0: 'Não iniciada',
    1: 'Em andamento',
    2: 'Concluída'
  };
  return statusMap[estado] || 'Desconhecido';
}

function sugerirMudanca(idTarefa, idColuna) {
  const tarefa = tarefasData.find(t => t.idTarefa === idTarefa && t.coluna_idCol === idColuna);
  document.getElementById('tarefa_selecionada').value = tarefa.nomeTarefa;
  
  // Rola a página para o formulário
  document.querySelector('.sugestao-container').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

document.addEventListener("DOMContentLoaded", () => {
  const opnTimelineModal = document.querySelector('#opnTimelineModal');
  const closeTimelineModal = document.querySelector('#closeTimelineModal');
  const timelineModal = document.querySelector('#dialogTimelineModal');

  opnTimelineModal.addEventListener("click", () => {
      timelineModal.showModal();
  });

  closeTimelineModal.addEventListener("click", () => {
    timelineModal.close();
  });
});
