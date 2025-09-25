document.addEventListener('DOMContentLoaded', () => {
    const tabButtons = document.querySelectorAll('.auth-tabs .tab-button');
    const authFormContents = document.querySelectorAll('.auth-form-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove 'active' de todos os botões e conteúdos
            tabButtons.forEach(btn => btn.classList.remove('active'));
            authFormContents.forEach(content => content.classList.remove('active'));

            // Adiciona 'active' ao botão clicado
            button.classList.add('active');

            // Adiciona 'active' ao conteúdo correspondente
            const targetTab = button.dataset.tab; // Pega o valor do atributo data-tab
            document.getElementById(`${targetTab}-form`).classList.add('active');
        });
    });

    // Função para alternar a visibilidade da senha
    window.togglePasswordVisibility = function(id) {
        const input = document.getElementById(id);
        if (input.type === 'password') {
            input.type = 'text';
        } else {
            input.type = 'password';
        }
    };
});