let aux = 0;

/* Função que faz o sino alterar quando clicado */
document.getElementById('sino').addEventListener('click', (event) => {
    /* Evita que a página atualize */
    event.preventDefault();

    const link = document.getElementById('sino');
    link.innerHTML = '';

    const img = document.createElement('img');

    if (aux == 0) {
        img.src = 'assets/sino-selecionado.png';
        img.alt = 'Sino de notificações selecionado';
        aux = 1;
    } else {
        img.src = 'assets/sino.png';
        img.alt = 'Sino de notificações';
        aux = 0;
    }

    img.id = 'sinoImg';

    link.append(img);
});

const modalCriarProjeto = document.getElementById("modalCriarProjeto");
const abrirCriarProjeto = document.getElementById("criar");
const fecharCriarProjeto = document.getElementById("fecharCriarProjeto");

// Abre o modal
abrirCriarProjeto.onclick = () => {
    modalCriarProjeto.classList.add("show");
}

// Fecha o modal
fecharCriarProjeto.onclick = () => {
    modalCriarProjeto.classList.remove("show");
}

// Fecha o modal ao clicar fora do conteúdo
window.onclick = (event) => {
    if (event.target == modalCriarProjeto) {
        modalCriarProjeto.classList.remove("show");
    }
}

const modalNotificacoes = document.getElementById("modalNotificacoes");
const abrirNotificacoes = document.getElementById("sino");
let auxNotificacoes = 0

function posicionarModal() {
    // Calcula a posição do botão
    const rect = abrirNotificacoes.getBoundingClientRect();
    // Define a posição do modal
    modalNotificacoes.style.top = `${rect.bottom + window.scrollY}px`; // Abaixo do botão
    modalNotificacoes.style.left = `${rect.left - 100}px`;
    modalNotificacoes.style.height = "50vh";
}

// Abre o modal
abrirNotificacoes.onclick = () => {
    if (auxNotificacoes == 0){
        posicionarModal();
        modalNotificacoes.classList.add("show");
        auxNotificacoes = 1;
    } else{
        modalNotificacoes.classList.remove("show");
        auxNotificacoes = 0;
    }
}

// Fecha o modal ao clicar fora do conteúdo
window.onclick = (event) => {
    if (event.target == modalNotificacoes) {
        modalNotificacoes.classList.remove("show");
    }
}

document.getElementById('busca').addEventListener('input', () => {
    var busca = document.getElementById('busca').value;
    var xhr = new XMLHttpRequest();

    if (busca.length > 0) {
        xhr.open('GET', 'busca.php?termo=' + busca, true);
    } else {
        xhr.open('GET', 'busca.php', true);
    }

    // Definindo o onreadystatechange antes de enviar a requisição
    xhr.onreadystatechange = () => {
        if (xhr.readyState === 4) { // Verifica se a requisição foi concluída
            var resultadosDiv = document.getElementById('listaProjetos');
            resultadosDiv.innerHTML = '';

            if (xhr.status === 200) { // Verifica se a requisição foi bem-sucedida
                try {
                    var resultados = JSON.parse(xhr.responseText);

                    if (resultados.length > 0) {
                        resultados.forEach((produto) => {
                            // Cria a div principal com a classe "degradeFundo"
                            var divDegradeFundo = document.createElement('div');
                            divDegradeFundo.className = 'degradeFundo';

                            var linkProjeto = document.createElement('a');
                            linkProjeto.href = "abreProjeto.php?id=" + produto.idProj;
                        
                            // Cria a div "infoProjeto"
                            var divInfoProjeto = document.createElement('div');
                            divInfoProjeto.className = 'infoProjeto';
                        
                            // Cria o elemento h2 para o nome do projeto
                            var h2 = document.createElement('h2');
                            h2.textContent = produto.nomeProj; // Acessa o nome do projeto
                        
                            // Cria a div "maisInfoProjeto"
                            var divMaisInfoProjeto = document.createElement('div');
                            divMaisInfoProjeto.className = 'maisInfoProjeto';
                        
                            // Cria o link "maisLink"
                            var aMaisLink = document.createElement('a');
                            aMaisLink.className = 'maisLink';
                            aMaisLink.href = '#';
                            aMaisLink.textContent = '...';
                        
                            // Adiciona o link à div "maisInfoProjeto"
                            divMaisInfoProjeto.appendChild(aMaisLink);
                        
                            // Monta a estrutura
                            linkProjeto.appendChild(h2);

                            divInfoProjeto.appendChild(linkProjeto);
                            divInfoProjeto.appendChild(divMaisInfoProjeto);
                            divDegradeFundo.appendChild(divInfoProjeto);
                        
                            // Adiciona a div "degradeFundo" ao container de resultados
                            resultadosDiv.appendChild(divDegradeFundo);
                        });
                    } else {
                        resultadosDiv.textContent = 'Nenhum resultado encontrado.';
                    }
                } catch (erro) {
                    console.error("Erro ao processar a resposta:", erro.message);
                }
            } else {
                console.error("Erro na requisição:", xhr.status, xhr.statusText);
                resultadosDiv.textContent = 'Erro ao buscar resultados.';
            }
        }
    };

    xhr.send();
});