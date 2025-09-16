-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 17/09/2025 às 00:10
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `voz-do-passageiro`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `reclamacoes`
--

CREATE TABLE `reclamacoes` (
  `id` int(11) NOT NULL,
  `linha_onibus` varchar(50) NOT NULL,
  `tipo_reclamacao` varchar(100) NOT NULL,
  `descricao` text NOT NULL,
  `data_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  `autor` varchar(100) NOT NULL DEFAULT 'Anônimo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `reclamacoes`
--

INSERT INTO `reclamacoes` (`id`, `linha_onibus`, `tipo_reclamacao`, `descricao`, `data_hora`, `autor`) VALUES
(1, 'Área Verde', 'Superlotação', 'Ônibus superlotado.', '2025-09-10 00:12:26', 'Anônimo'),
(2, 'Jutaí', 'Má conservação', 'Veículo caindo aos pedaços.', '2025-09-10 02:28:16', 'Rayssa Germanotta'),
(3, 'Área Verde', 'Má conservação', 'Esse ônibus parece que foi pego do ferro velho!', '2025-09-10 11:07:31', 'Anônimo'),
(4, 'Diamantino', 'Atraso', 'O horário para ele passar é 14h, ele passou apenas 16h.', '2025-09-16 17:50:54', 'Lady Gaga'),
(5, 'Floresta Prainha', 'Outro', 'Ruim.', '2025-09-16 20:16:26', 'Lady Gaga');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `data_criacao`) VALUES
(1, 'Rayssa Germanotta', 'rayssagermanotta@gmail.com', '$2y$10$tsMZXmt9Lf0LSnvk2HEpueG06U.DWy35iVw9INrQkfHakVlyXweQu', '2025-09-10 02:27:45'),
(2, 'Lady Gaga', 'ladygaga@gmail.com', '$2y$10$wp7u4IaqkUZwsgFpwbA0LOY3es/QoUmiZUhgOTO7DsjWIcswX1DEa', '2025-09-16 17:49:49');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `reclamacoes`
--
ALTER TABLE `reclamacoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `reclamacoes`
--
ALTER TABLE `reclamacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
