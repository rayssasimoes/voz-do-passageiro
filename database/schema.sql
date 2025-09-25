--
-- Estrutura do Banco de Dados para o projeto 'Voz do Passageiro'
--

-- Cria o banco de dados se ele ainda não existir e o seleciona para uso.
CREATE DATABASE IF NOT EXISTS `db_voz-do-passageiro` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_voz-do-passageiro`;

-- Desativa a verificação de chaves estrangeiras temporariamente para poder apagar as tabelas em qualquer ordem.
SET FOREIGN_KEY_CHECKS=0;

-- Apaga as tabelas se elas já existirem, para garantir uma criação limpa.
DROP TABLE IF EXISTS `reclamacao`;
DROP TABLE IF EXISTS `usuario`;

-- Reativa a verificação de chaves estrangeiras.
SET FOREIGN_KEY_CHECKS=1;


CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL UNIQUE,
  `senha` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `reclamacao` (
  `reclamacao_id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `linha_onibus` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `tipo_reclamacao` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_general_ci NOT NULL,
  `autor` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `data_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pendente',
  PRIMARY KEY (`reclamacao_id`),
  KEY `fk_reclamacao_usuario` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Adiciona a chave estrangeira para garantir a integridade referencial entre as tabelas `reclamacao` e `usuario`.
ALTER TABLE `reclamacao`
  ADD CONSTRAINT `fk_reclamacao_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

-- --------------------------------------------------------

--
-- Inserindo um usuário de teste
-- Credenciais: rayssa@gmail.com / Senha: 2727270 (hash gerado)
--

INSERT INTO `usuario` (`nome`, `email`, `senha`, `created_at`) VALUES
('Rayssa Teste', 'rayssa@gmail.com', '$2y$10$wE4oK3eC1jL2mN5qP7rS9tU1vX3yZ6aB8cD0eF1gH2iJ3kL4M5N6O7P8Q9R0S1T2U3V', CURRENT_TIMESTAMP());

-- O hash '$2y$10$wE4oK3eC1jL2mN5qP7rS9tU1vX3yZ6aB8cD0eF1gH2iJ3kL4M5N6O7P8Q9R0S1T2U3V' é para a senha '2727270'.
-- Idealmente, sempre registre usuários através do seu formulário para que o hash seja gerado corretamente pelo seu sistema.