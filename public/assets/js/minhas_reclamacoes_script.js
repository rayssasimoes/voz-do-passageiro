document.addEventListener('DOMContentLoaded', () => {
    // Elementos da página
    const btnNovaReclamacao = document.getElementById('btn-nova-reclamacao');
    const formContainer = document.getElementById('form-reclamacao-container');
    const formReclamacao = document.getElementById('form-reclamacao');
    const reclamacoesList = document.getElementById('reclamacoes-list');
    const formStatus = document.getElementById('form-status');
    const deleteModal = document.getElementById('delete-confirm-overlay');
    const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
    const cancelDeleteBtn = document.getElementById('cancel-delete-btn');

    const submitBtn = formReclamacao ? formReclamacao.querySelector('button[type="submit"]') : null;

    let currentReclamacaoIdToDelete = null;

    // --- FUNÇÕES ---

    /**
     * Renderiza a lista de reclamações na tela.
     * @param {Array} reclamacoes - A lista de reclamações vinda da API.
     */
    const renderReclamacoes = (reclamacoes) => {
        reclamacoesList.innerHTML = ''; // Limpa a lista antes de renderizar
        if (!reclamacoes || reclamacoes.length === 0) {
            reclamacoesList.innerHTML = '<p>Nenhuma reclamação feita ainda.</p>';
            return;
        }

        reclamacoes.forEach(reclamacao => {
            const card = document.createElement('div');
            card.classList.add('reclamacao-card');
            card.innerHTML = `
                <h4>Linha: ${reclamacao.linha_onibus}</h4>
                <p><strong>Tipo:</strong> ${reclamacao.tipo_reclamacao}</p>
                <p>${reclamacao.descricao}</p>
                <small>Registrado em: ${reclamacao.data_formatada}</small>
                <button class="btn-apagar" data-id="${reclamacao.reclamacao_id}">Apagar</button>
            `;
            reclamacoesList.appendChild(card);
        });
    };

    /**
     * Busca as reclamações do usuário no servidor e as renderiza.
     */
    const carregarReclamacoes = async () => {
        try {
            const response = await fetch('api.php?action=get_reclamacoes_pessoais');
            if (!response.ok) {
                const errorText = await response.text();
                throw new Error(`Falha ao buscar suas reclamações. Resposta do servidor: ${errorText}`);
            }
            const reclamacoes = await response.json();
            renderReclamacoes(reclamacoes);
        } catch (error) {
            reclamacoesList.innerHTML = `<p style="color: red;">${error.message}</p>`;
        }
    };

    /**
     * Envia o formulário de nova reclamação para a API.
     */
    const handleFormSubmit = async (event) => {
        event.preventDefault();
        if (submitBtn.disabled) return; // Previne múltiplos envios

        submitBtn.disabled = true;
        submitBtn.textContent = 'Enviando...';
        formStatus.textContent = '';

        const dados = {
            linha_onibus: formReclamacao.linha_onibus.value,
            tipo_reclamacao: formReclamacao.tipo_reclamacao.value,
            descricao: formReclamacao.descricao.value,
            mostrar_nome: formReclamacao.mostrar_nome.checked
        };

        try {
            const response = await fetch('api.php?action=add_reclamacao', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(dados)
            });
            const resultado = await response.json();

            if (!resultado.sucesso) {
                throw new Error(resultado.mensagem || 'Erro desconhecido ao salvar.');
            }

            formStatus.textContent = resultado.mensagem;
            formStatus.style.color = 'green';
            setTimeout(() => { formStatus.textContent = ''; }, 3000);
            formReclamacao.reset();
            formContainer.classList.add('hidden');
            await carregarReclamacoes(); // Recarrega a lista

        } catch (error) {
            formStatus.textContent = error.message;
            formStatus.style.color = 'red';
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Enviar Reclamação';
        }
    };

    /**
     * Apaga uma reclamação via API.
     */
    const handleApagarClick = async () => {
        if (!currentReclamacaoIdToDelete) return;

        try {
            const response = await fetch('api.php?action=delete_reclamacao', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ reclamacao_id: currentReclamacaoIdToDelete })
            });
            const resultado = await response.json();

            if (!resultado.sucesso) {
                throw new Error(resultado.mensagem || 'Erro ao apagar.');
            }

            await carregarReclamacoes();
        } catch (error) {
            // Exibe o erro de forma mais amigável
            alert(`Não foi possível apagar a reclamação: ${error.message}`);
        }
        
        deleteModal.classList.remove('active');
        currentReclamacaoIdToDelete = null;
    };

    // --- REGISTRO DE EVENTOS ---

    // Botão para mostrar/esconder formulário
    if (btnNovaReclamacao) {
        btnNovaReclamacao.addEventListener('click', () => {
            formContainer.classList.toggle('hidden');
        });
    }

    // Submit do formulário
    if (formReclamacao) {
        formReclamacao.addEventListener('submit', handleFormSubmit);
    }

    // Delegação de evento para os botões de apagar
    reclamacoesList.addEventListener('click', (event) => {
        if (event.target.classList.contains('btn-apagar')) {
            currentReclamacaoIdToDelete = event.target.getAttribute('data-id');
            deleteModal.classList.add('active');
        }
    });

    // Ações do modal de deleção
    if (deleteModal) {
        cancelDeleteBtn.addEventListener('click', () => {
            deleteModal.classList.remove('active');
            currentReclamacaoIdToDelete = null;
        });

        confirmDeleteBtn.addEventListener('click', handleApagarClick);
    }

    // --- INICIALIZAÇÃO ---
    carregarReclamacoes();
});