# Voz do Passageiro

Uma plataforma web colaborativa para monitorar e melhorar o transporte público.

---

## Sobre o Projeto

O **Voz do Passageiro** é uma aplicação web desenvolvida para permitir que cidadãos registrem, consultem e visualizem reclamações sobre linhas de transporte público. O objetivo é criar uma base de dados transparente que ajude a identificar problemas recorrentes e a dar visibilidade à qualidade do serviço.

### Principais Funcionalidades

* **Sistema de Autenticação:** Login e cadastro de usuários.
* **Envio de Reclamações:** Formulário para registrar problemas em linhas de ônibus.
* **Rankings:** Listas dinâmicas das "Melhores" e "Piores" linhas, com base no número de reclamações.
* **Histórico de Reclamações:** Visualização das últimas reclamações registradas.
* **Temas:** Alternância entre tema claro e escuro para melhor experiência do usuário.
* **Perfil do Usuário:** Página para editar dados pessoais e senha.

---

## Tecnologias Utilizadas

* **Frontend:** HTML, CSS, JavaScript
* **Backend:** PHP
* **Banco de Dados:** MySQL

---

## Como Rodar o Projeto

Para executar o projeto em seu ambiente local (como XAMPP, WAMP ou MAMP), siga os passos abaixo:

1.  **Clone o Repositório:**
    ```bash
    git clone [https://www.youtube.com/watch?v=GRf6so_sois](https://www.youtube.com/watch?v=GRf6so_sois)
    ```

2.  **Configurar o Banco de Dados:**
    * No phpMyAdmin, crie um banco de dados chamado `voz-do-passageiro`.
    * Importe a estrutura das tabelas usando o código SQL abaixo. Crie a tabela `reclamacoes`:
    ```sql
    CREATE TABLE reclamacoes (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        linha_onibus VARCHAR(20) NOT NULL,
        tipo_reclamacao VARCHAR(50) NOT NULL,
        descricao TEXT NOT NULL,
        autor VARCHAR(255) DEFAULT 'Anônimo',
        data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    ```
    * Em seguida, crie a tabela `usuarios`:
    ```sql
    CREATE TABLE usuarios (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        senha VARCHAR(255) NOT NULL,
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    ```

3.  **Configurar o Servidor Local:**
    * Mova a pasta do projeto clonado (`voz-do-passageiro`) para o diretório `htdocs` (no XAMPP) ou similar do seu servidor local.
    * Inicie o servidor Apache e o MySQL.

4.  **Acessar a Aplicação:**
    * Abra o navegador e acesse a URL: `http://localhost/voz-do-passageiro/index.php`

---

## Disciplina

Este projeto foi desenvolvido como trabalho para a disciplina de **Programação Web**.

---

## Observação

Este projeto foi desenvolvido com o auxílio de inteligência artificial, que atuou na depuração de erros, na refatoração de código e na implementação de funcionalidades de design e usabilidade.