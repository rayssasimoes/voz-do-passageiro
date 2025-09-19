document.addEventListener('DOMContentLoaded', () => {
    // --- ELEMENTOS GLOBAIS ---
    const themeToggle = document.getElementById('theme-toggle');
    const htmlElement = document.documentElement;

    // --- ELEMENTOS DA PÁGINA PRINCIPAL (PODEM NÃO EXISTIR EM OUTRAS PÁGINAS) ---
    const form = document.getElementById('form-reclamacao');
    const listaReclamacoes = document.getElementById('lista-reclamacoes');
    const formStatus = document.getElementById('form-status');

    // ==========================================================
    // LÓGICA DO FORMULÁRIO E DA LISTA
    // Só executa se os elementos da página principal existirem
    // ==========================================================
    if (form && listaReclamacoes && formStatus) {
        const API_GET_URL = 'index.php?action=get_reclamacoes';
        const API_POST_URL = 'index.php?action=post_reclamacao';

        const carregarReclamacoes_func = async () => {
            try {
                const response = await fetch(API_GET_URL);
                if (!response.ok) throw new Error('Falha na rede.');
                const reclamacoes = await response.json();
                listaReclamacoes.innerHTML = ''; 
                if (reclamacoes.length === 0) {
                    listaReclamacoes.innerHTML = '<p class="no-data">Nenhuma reclamação registrada ainda.</p>';
                    return;
                }
                reclamacoes.forEach(r => {
                    const card = document.createElement('div');
                    card.className = 'reclamacao-card';
                    card.innerHTML = `
                        <h3>Linha: ${escapeHTML_func(r.linha_onibus)}</h3>
                        <p><strong>Tipo:</strong> ${escapeHTML_func(r.tipo_reclamacao)}</p>
                        <p class="reclamacao-autor">Por: <strong>${escapeHTML_func(r.autor)}</strong></p>
                        <p>${escapeHTML_func(r.descricao).replace(/\n/g, '<br>')}</p>
                        <small>Registrado em: ${r.data_formatada}</small>
                    `;
                    listaReclamacoes.appendChild(card);
                });
            } catch (error) {
                console.error('Erro ao carregar reclamações:', error);
                listaReclamacoes.innerHTML = '<p style="color: red;">Erro ao carregar as reclamações.</p>';
            }
        };

        // Chama a função para carregar a lista inicial
        carregarReclamacoes_func();

        // Adiciona o listener de envio do formulário
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const formData = new FormData(form);
            const dados = Object.fromEntries(formData.entries());

            const mostrarNomeCheckbox = document.getElementById('mostrar_nome');
            dados.mostrar_nome = mostrarNomeCheckbox.checked;

            formStatus.textContent = 'Enviando...';
            formStatus.className = '';
            try {
                const response = await fetch(API_POST_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(dados)
                });
                const resultado = await response.json();
                if (resultado.sucesso) {
                    formStatus.textContent = resultado.mensagem;
                    formStatus.className = 'status-sucesso';
                    form.reset();
                    await carregarReclamacoes_func(); 
                } else {
                    throw new Error(resultado.mensagem || 'Ocorreu um erro.');
                }
            } catch (error) {
                console.error('Erro ao enviar formulário:', error);
                formStatus.textContent = `Erro: ${error.message}`;
                formStatus.className = 'status-erro';
            }
        });

        function escapeHTML_func(str) {
            if (!str) return 'Anônimo'; 
            return str.replace(/[&<>'"]/g, 
                tag => ({'&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;'}[tag] || tag));
        }
    }


    // ==========================================================
    // LÓGICA DO SELETOR DE TEMA
    // Roda em todas as páginas, desde que o botão exista
    // ==========================================================
    const applyTheme = (theme) => {
        htmlElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
    };
    
    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const currentTheme = htmlElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            applyTheme(newTheme);
        });
    }

    // Aplica o tema salvo em todas as páginas, independente de qualquer coisa
    const savedTheme = localStorage.getItem('theme') || 'light';
    applyTheme(savedTheme);
});
