document.addEventListener('DOMContentLoaded', () => {
    const btnEditarInfo = document.getElementById('btn-editar-info');
    const btnSalvarInfo = document.getElementById('btn-salvar-info');
    const formInfoPessoal = document.getElementById('form-info-pessoal');
    const nomeInput = document.getElementById('nome');
    const emailInput = document.getElementById('email');

    // Carregar dados do localStorage
    const savedNome = localStorage.getItem('usuario_nome');
    const savedEmail = localStorage.getItem('usuario_email');

    if (savedNome) {
        nomeInput.value = savedNome;
    }
    if (savedEmail) {
        emailInput.value = savedEmail;
    }

    btnEditarInfo.addEventListener('click', () => {
        nomeInput.disabled = false;
        emailInput.disabled = false;
        btnEditarInfo.classList.add('hidden');
        btnSalvarInfo.classList.remove('hidden');
    });

    formInfoPessoal.addEventListener('submit', (e) => {
        e.preventDefault();
        nomeInput.disabled = true;
        emailInput.disabled = true;
        btnEditarInfo.classList.remove('hidden');
        btnSalvarInfo.classList.add('hidden');
        
        // Salvar dados no localStorage
        localStorage.setItem('usuario_nome', nomeInput.value);
        localStorage.setItem('usuario_email', emailInput.value);

        console.log('Informações salvas:', { nome: nomeInput.value, email: emailInput.value });
    });

    const btnAlterarSenha = document.getElementById('btn-alterar-senha');
    const modalAlterarSenha = document.getElementById('modal-alterar-senha');
    const btnCancelarAlterarSenha = document.getElementById('btn-cancelar-alterar-senha');

    btnAlterarSenha.addEventListener('click', () => {
        modalAlterarSenha.classList.add('active');
    });

    btnCancelarAlterarSenha.addEventListener('click', () => {
        modalAlterarSenha.classList.remove('active');
    });

    const btnApagarConta = document.getElementById('btn-apagar-conta');
    const modalApagarConta = document.getElementById('modal-apagar-conta');
    const btnConfirmarApagarConta = document.getElementById('btn-confirmar-apagar-conta');
    const btnCancelarApagarConta = document.getElementById('btn-cancelar-apagar-conta');

    btnApagarConta.addEventListener('click', () => {
        modalApagarConta.classList.add('active');
    });

    btnCancelarApagarConta.addEventListener('click', () => {
        modalApagarConta.classList.remove('active');
    });

    btnConfirmarApagarConta.addEventListener('click', () => {
        console.log('Conta apagada');
        modalApagarConta.classList.remove('active');
        // Aqui você adicionaria a lógica para apagar a conta do usuário
    });
});