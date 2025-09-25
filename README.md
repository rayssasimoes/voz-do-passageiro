# Voz do Passageiro

Uma plataforma web colaborativa para monitorar e melhorar o transporte público.

## Sobre o Projeto

O **Voz do Passageiro** é uma aplicação web desenvolvida para permitir que cidadãos registrem, consultem e visualizem reclamações sobre linhas de transporte público. O objetivo é criar uma base de dados transparente que ajude a identificar problemas recorrentes, dar visibilidade à qualidade do serviço e promover melhorias.

## Principais Funcionalidades

### 1\. Gestão de Usuários

  * ✅ **Sistema de Autenticação:** Login e cadastro de novos usuários.
  * ✅ **Sessões:** Gerenciamento de sessões de usuário (`$_SESSION`).
  * ✅ **Segurança de Senha:** Senhas armazenadas criptografadas com `password_hash()`.
  * Perfil do Usuário: Página para visualizar, editar dados pessoais e alterar senha (em desenvolvimento).

### 2\. Gestão de Reclamações

  * ✅ **Envio de Reclamações:** Formulário para registrar problemas em linhas de ônibus (com opção anônima).
  * ✅ **Histórico de Reclamações:** Visualização das últimas reclamações registradas na página inicial.
  * ✅ **Minhas Reclamações:** Usuários logados podem ver e gerenciar suas próprias reclamações.
  * ✅ **Exclusão de Reclamação:** Autores podem excluir suas próprias reclamações.
  * ✅ **Rankings de Linhas:**
      * **Piores Linhas:** Listas dinâmicas das linhas com mais reclamações.
      * **Melhores Linhas:** Listas dinâmicas das linhas com menos reclamações.

### 3\. Interface e Experiência do Usuário

  * ✅ **Design Responsivo:** Layout adaptável para diferentes tamanhos de tela.
  * ✅ **Feedback:** Alertas e mensagens de feedback para ações do usuário.

## Tecnologias Utilizadas

  * **Frontend:** HTML5, CSS3, JavaScript
  * **Backend:** PHP 7.4+
  * **Banco de Dados:** MySQL 5.7+
  * **Servidor Web:** Apache (via XAMPP)

## Estrutura do Projeto
```
voz-do-passageiro/
├── .config/ 
├── .gitignore
├── config.php
├── config.example.php
├── database/
├── public/
├── src/
└── README.md
```

## Como Rodar o Projeto

Para executar o projeto em seu ambiente local, siga os passos abaixo:

### Pré-requisitos

  * **Ambiente de Servidor Local:** Você precisará de um ambiente com Apache, MySQL e PHP, como o XAMPP, WAMP ou MAMP.
  * Baixe o XAMPP aqui: [https://www.apachefriends.org/pt_br/index.html](https://www.apachefriends.org/pt_br/index.html)

### Passos para Instalação:

1.  **Clone o Repositório:**

    ```bash
    git clone <URL_DO_REPOSITORIO> voz-do-passageiro
    cd voz-do-passageiro
    ```

2.  **Crie o Banco de Dados:**

      * Inicie o Apache e o MySQL no painel de controle do seu servidor.
      * Acesse o phpMyAdmin (ex: `http://localhost/phpmyadmin`).
      * Crie um novo banco de dados (ex: `db_voz-do-passageiro`).
      * Importe o arquivo `database/schema.sql` para criar as tabelas.

3.  **Configure o Ambiente:**

      * Na raiz do projeto, crie uma cópia do arquivo `config.example.php` e renomeie-a para `config.php`.
      * Abra o novo arquivo `config.php` e edite as duas constantes principais:

        **1. `BASE_URL`:** Esta é a configuração mais importante para que o site funcione corretamente (CSS, links, etc.). Ela deve apontar para a pasta `public` do seu projeto.

        ```php
        // Altere a linha abaixo para a URL completa da pasta public do seu projeto.
        // Exemplo se o projeto está em http://localhost/voz-do-passageiro/
        define('BASE_URL', 'http://localhost/voz-do-passageiro/public');
        ```

        **2. Credenciais do Banco de Dados:** Insira os dados de acesso ao seu banco de dados local.

        ```php
        // Configurações do banco de dados
        define('DB_HOST', 'localhost');
        define('DB_NAME', 'db_voz-do-passageiro'); // O nome que você usou no passo 2
        define('DB_USER', 'root');
        define('DB_PASS', ''); // DEIXE VAZIO se o seu MySQL não tiver senha para o usuário root
        ```
      * **Importante:** O arquivo `config.php` é ignorado pelo Git para proteger suas informações e garantir que a configuração de cada desenvolvedor seja independente.

4.  **Acesse a Aplicação:**

      * Se estiver usando XAMPP/WAMP, certifique-se que a pasta do projeto está dentro de `htdocs` ou `www`.
      * Abra o navegador e acesse a URL base do projeto. Exemplo:
        `http://localhost/voz-do-passageiro/public/`

### Usuário de Teste

Após a importação do banco de dados, um usuário de teste é criado automaticamente. Você pode usá-lo para fazer login e explorar as funcionalidades:

- **Email:** `rayssa@gmail.com`
- **Senha:** `2727270`


## Estrutura do Banco de Dados

### Tabela `usuario`

  * `id_usuario` (PK, INT, AUTO\_INCREMENT)
  * `nome` (VARCHAR)
  * `email` (VARCHAR, UNIQUE)
  * `senha` (VARCHAR)
  * `created_at` (DATETIME)
  * `updated_at` (DATETIME)

### Tabela `reclamacao`

  * `reclamacao_id` (PK, INT, AUTO\_INCREMENT)
  * `usuario_id` (FK, INT, REFERENCES `usuario.id_usuario`)
  * `linha_onibus` (VARCHAR)
  * `tipo_reclamacao` (VARCHAR)
  * `descricao` (TEXT)
  * `mostrar_nome` (BOOLEAN)
  * `autor` (VARCHAR - para casos anônimos ou sem `usuario_id` direto)
  * `data_hora` (DATETIME)

## Disciplina

Este projeto foi desenvolvido como trabalho para a disciplina de **Programação Web**.

## Observação

Este projeto foi desenvolvido por mim, Rayssa Simões, com o auxílio de inteligência artificial, que atuou na depuração de erros, na refatoração de código e na implementação de funcionalidades de design e usabilidade.

---

## Solução de Problemas (Troubleshooting)

### Erro: `Access denied for user 'root'@'localhost'`

Se você seguiu os passos de instalação, deixou a senha em branco no `config.php` (`define('DB_PASS', '');`) e mesmo assim encontrou um erro de `Access denied`, isso confirma que a instalação do MySQL no seu computador **possui uma senha** para o usuário `root`.

**Como resolver:**

1.  **Use a senha correta:** Se você sabe qual é a senha do usuário `root` do seu MySQL, simplesmente coloque-a no arquivo `config.php`:
    ```php
    // Exemplo se a senha for "12345"
    define('DB_PASS', '12345');
    ```

2.  **Redefina a senha (se não souber):** Caso não lembre a senha, a maneira mais fácil é redefini-la através do phpMyAdmin:
    *   Na página inicial do phpMyAdmin, clique na aba **"Contas de usuário"** (`User accounts`).
    *   Na linha do usuário `root` com o host `localhost`, clique em **"Editar privilégios"** (`Edit privileges`).
    *   No topo da página, clique em **"Alterar senha"** (`Change password`).
    *   Digite uma nova senha (e a confirme). Para um ambiente de teste, senhas simples como `root` ou `12345` são aceitáveis.
    *   Clique em "Executar".
    *   Finalmente, coloque essa nova senha no arquivo `config.php`.