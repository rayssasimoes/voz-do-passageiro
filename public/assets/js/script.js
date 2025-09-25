document.addEventListener('DOMContentLoaded', () => {
    // ===============================
    // --- ELEMENTOS GLOBAIS ---
    // ===============================
    const themeToggle = document.getElementById('theme-toggle');
    const htmlElement = document.documentElement;

    // ===============================
    // --- ELEMENTOS DA PÁGINA PRINCIPAL ---
    // ===============================
    const form = document.getElementById('form-reclamacao');
    const listaReclamacoes = document.getElementById('lista-reclamacoes');
    const formStatus = document.getElementById('form-status');

    // ===============================
    // --- FUNÇÕES AUXILIARES ---
    // ===============================
    function escapeHTML(str) {
        if (typeof str !== 'string') return 'Anônimo';
        return str.replace(/[&<>'\"]/g, tag => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            "'": '&#39;',
            '"': '&quot;'
        }[tag] || tag));
    }

    // ===============================
    // --- CARREGAR RECLAMAÇÕES ---
    // ===============================
    async function carregarReclamacoes() {
        if (!listaReclamacoes) return;
        const API_GET_URL = 'api.php?action=get_reclamacao';

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
                    <h3>Linha: ${escapeHTML(r.linha_onibus)}</h3>
                    <p><strong>Tipo:</strong> ${escapeHTML(r.tipo_reclamacao)}</p>
                    <p class="reclamacao-autor">Por: <strong>${escapeHTML(r.autor)}</strong></p>
                    <p>${escapeHTML(r.descricao).replace(/\n/g, '<br>')}</p>
                    <small>Registrado em: ${r.data_formatada}</small>
                `;
                listaReclamacoes.appendChild(card);
            });

        } catch (error) {
            console.error('Erro ao carregar reclamações:', error);
            listaReclamacoes.innerHTML = '<p style="color: red;">Erro ao carregar as reclamações.</p>';
        }
    }

    // ===============================
    // --- GERENCIAMENTO DE TEMA ---
    // ===============================
    function applyTheme(theme) {
        htmlElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const currentTheme = htmlElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            applyTheme(newTheme);
        });
    }

    const savedTheme = localStorage.getItem('theme') || 'light';
    applyTheme(savedTheme);

    // ===============================
    // --- MODAL DE LOGOUT (ATUALIZADO) ---
    // ===============================
    // Referencia o botão Sair do header
    const logoutBtnHeader = document.getElementById('logout-btn');
    // Referencia o overlay da modal
    const logoutConfirmOverlay = document.getElementById('logout-confirm-overlay');
    const confirmLogoutBtn = document.getElementById('confirm-logout-btn');
    const cancelLogoutBtn = document.getElementById('cancel-logout-btn');

    if (logoutBtnHeader && logoutConfirmOverlay) {
        // Exibe modal ao clicar no botão 'Sair' do header
        logoutBtnHeader.addEventListener('click', function(event) {
            event.preventDefault(); // Impede o redirecionamento imediato
            logoutConfirmOverlay.classList.add('active'); // Mostra a modal
        });

        // Botão Cancelar: fecha a modal
        cancelLogoutBtn.addEventListener('click', function() {
            logoutConfirmOverlay.classList.remove('active'); // Esconde a modal
        });

        // Fechar modal ao clicar fora (no overlay)
        logoutConfirmOverlay.addEventListener('click', function(event) {
            if (event.target === logoutConfirmOverlay) {
                logoutConfirmOverlay.classList.remove('active');
            }
        });

        // Botão Confirmar: prossegue com o logout
        confirmLogoutBtn.addEventListener('click', function() {
            // O href para o logout vem do botão original no header
            window.location.href = logoutBtnHeader.href;
        });
    }

    // ===============================
    // --- MENU HAMBURGUER ---
    // ===============================
    const hamburger = document.querySelector('.hamburger');
    const mainNav = document.querySelector('.main-nav');

    if (hamburger && mainNav) {
        hamburger.addEventListener('click', () => {
            mainNav.classList.toggle('active');
        });
    }
});