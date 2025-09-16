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

Para executar o projeto em seu ambiente local, siga os passos abaixo:

1.  **Instalar o XAMPP:**
    * O projeto utiliza PHP e MySQL. Você precisará de um servidor local como o XAMPP.
    * Baixe e instale o XAMPP em seu computador através do site oficial: [https://www.apachefriends.org/pt_br/index.html](https://www.apachefriends.org/pt_br/index.html)

2.  **Clone o Repositório:**
    ```bash
    git clone [https://www.youtube.com/watch?v=GRf6so_sois](https://www.youtube.com/watch?v=GRf6so_sois)
    ```

3.  **Configurar o Banco de Dados:**
    *   Inicie o MySQL através do painel do XAMPP.
    *   Acesse o phpMyAdmin (normalmente em `http://localhost/phpmyadmin`).
    *   Crie um novo banco de dados com o nome `voz-do-passageiro`.
    *   Selecione o banco de dados recém-criado e vá para a aba "Importar".
    *   Clique em "Escolher arquivo" e selecione o arquivo `voz-do-passageiro.sql` localizado na pasta `database` do projeto.
    *   Clique em "Executar" para criar as tabelas e importar os dados.

4.  **Configurar o Servidor Local:**
    * Mova a pasta do projeto clonado (`voz-do-passageiro`) para o diretório `htdocs` (no XAMPP) ou similar do seu servidor local.
    * Inicie o servidor Apache e o MySQL.

5.  **Acessar a Aplicação:**
    * Abra o navegador e acesse a URL: `http://localhost/voz-do-passageiro/index.php`

---

## Disciplina

Este projeto foi desenvolvido como trabalho para a disciplina de **Programação Web**.

---

## Observação

Este projeto foi desenvolvido com o auxílio de inteligência artificial, que atuou na depuração de erros, na refatoração de código e na implementação de funcionalidades de design e usabilidade.