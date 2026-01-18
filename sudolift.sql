-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 17/01/2026 às 21:13
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
-- Banco de dados: `sudolift`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `ativacao_muscular`
--

CREATE TABLE `ativacao_muscular` (
  `id` int(11) NOT NULL,
  `exercicio_id` int(11) NOT NULL,
  `musculo` varchar(50) NOT NULL,
  `fator` decimal(3,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ativacao_muscular`
--

INSERT INTO `ativacao_muscular` (`id`, `exercicio_id`, `musculo`, `fator`) VALUES
(19, 21, 'Peitoral Médio', 1.0),
(20, 21, 'Tríceps', 0.7),
(21, 21, 'Deltoide Anterior', 0.6),
(22, 22, 'Peitoral Superior', 1.0),
(23, 22, 'Deltoide Anterior', 0.7),
(24, 22, 'Tríceps', 0.6),
(28, 24, 'Peitoral Médio', 1.0),
(29, 24, 'Deltoide Anterior', 0.3),
(30, 25, 'Dorsais', 1.0),
(31, 25, 'Bíceps', 0.6),
(32, 25, 'Costas Superiores', 0.5),
(33, 26, 'Costas Superiores', 1.0),
(34, 26, 'Dorsais', 0.7),
(35, 26, 'Bíceps', 0.6),
(36, 26, 'Lombar', 0.4),
(37, 20, 'Bíceps', 1.0),
(38, 20, 'Antebraço', 0.3),
(39, 23, 'Peitoral Inferior', 1.0),
(40, 23, 'Tríceps', 0.6),
(41, 23, 'Deltoide Anterior', 0.4),
(42, 27, 'Costas Superiores', 1.0),
(43, 27, 'Dorsais', 0.6),
(44, 27, 'Bíceps', 0.5),
(45, 28, 'Deltoide Anterior', 1.0),
(46, 28, 'Deltoide Lateral', 0.6),
(47, 28, 'Tríceps', 0.6),
(48, 29, 'Deltoide Lateral', 1.0),
(49, 30, 'Deltoide Anterior', 1.0),
(50, 31, 'Deltoide Posterior', 1.0),
(51, 31, 'Costas Superiores', 0.6),
(52, 32, 'Bíceps', 1.0),
(53, 32, 'Antebraço', 0.3),
(54, 33, 'Bíceps', 0.6),
(55, 33, 'Antebraço', 0.6),
(56, 34, 'Tríceps', 1.0),
(57, 35, 'Tríceps', 1.0),
(62, 36, 'Quadríceps', 1.0),
(63, 36, 'Glúteo', 0.8),
(64, 36, 'Posterior de Coxa', 0.4),
(65, 36, 'Lombar', 0.4),
(66, 37, 'Quadríceps', 1.0),
(67, 37, 'Glúteo', 0.6),
(68, 37, 'Posterior de Coxa', 0.3),
(69, 38, 'Quadríceps', 1.0),
(70, 39, 'Posterior de Coxa', 1.0),
(71, 40, 'Posterior de Coxa', 1.0),
(72, 40, 'Glúteo', 0.7),
(73, 40, 'Lombar', 0.5),
(74, 41, 'Panturrilha', 1.0),
(75, 42, 'Abdômen Superior', 1.0),
(76, 43, 'Abdômen Inferior', 1.0),
(77, 44, 'Oblíquos', 1.0),
(78, 45, 'Glúteo', 1.0),
(79, 45, 'Posterior de Coxa', 0.8),
(80, 45, 'Lombar', 0.7),
(81, 45, 'Costas Superiores', 0.5),
(82, 46, 'Trapézio', 1.0),
(83, 47, 'Abdômen Superior', 0.6),
(84, 47, 'Abdômen Inferior', 0.6),
(85, 47, 'Oblíquos', 0.6),
(86, 47, 'Lombar', 0.5);

-- --------------------------------------------------------

--
-- Estrutura para tabela `exercicios`
--

CREATE TABLE `exercicios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `equipamento` varchar(50) DEFAULT 'Nenhum',
  `grupo_muscular` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `exercicios`
--

INSERT INTO `exercicios` (`id`, `nome`, `tipo`, `imagem`, `equipamento`, `grupo_muscular`) VALUES
(20, 'Rosca Direta (Polia)', NULL, 'ex_1768668241.mp4', 'Máquina', 'Bíceps'),
(21, 'Supino Reto (Barra)', NULL, 'ex_1768668500.mp4', 'Barra', 'Peitoral Médio'),
(22, 'Supino Inclinado (Barra)', NULL, 'ex_1768668697.mp4', 'Barra', 'Peitoral Superior'),
(23, 'Supino Declinado (Barra)', NULL, 'ex_1768668799.mp4', 'Barra', 'Peitoral Inferior'),
(24, 'Crucifixo Reto (Halter)', NULL, 'ex_1768669058.mp4', 'Haltere', 'Peitoral Médio'),
(25, 'Puxada Alta na Polia (Máquina)', NULL, 'ex_1768669395.mp4', 'Máquina', 'Dorsais'),
(26, 'Remadas Dobradas (Barra)', NULL, 'ex_1768669995.mp4', 'Barra', 'Costas Superiores'),
(27, 'Remada baixa (Polia)', NULL, 'ex_1768670412.mp4', 'Máquina', 'Costas Superiores'),
(28, 'Desenvolvimento (Barra)', NULL, 'ex_1768670940.mp4', 'Barra', 'Deltoide Anterior'),
(29, 'Elevação Lateral (Halter)', NULL, 'ex_1768671813.mp4', 'Haltere', 'Deltoide Lateral'),
(30, 'Elevação Frontal (Halter)', NULL, 'ex_1768672197.mp4', 'Haltere', 'Deltoide Anterior'),
(31, 'Crucifixo Invertido (Máquina)', NULL, 'ex_1768672596.mp4', 'Máquina', 'Deltoide Posterior'),
(32, 'Rosca Direta na Barra W', NULL, 'ex_1768672857.mp4', 'Barra', 'Bíceps'),
(33, 'Rosca Martelo (Halter)', NULL, 'ex_1768673416.mp4', 'Haltere', 'Bíceps'),
(34, 'Triceps Testa (Barra)', NULL, 'ex_1768673563.mp4', 'Barra', 'Tríceps'),
(35, 'Triceps Corda (Polia)', NULL, 'ex_1768673700.mp4', 'Máquina', 'Tríceps'),
(36, 'Agachamento (Barra)', NULL, 'ex_1768673901.mp4', 'Barra', 'Quadríceps'),
(37, 'Leg Press 45 (Máquina)', NULL, 'ex_1768674034.mp4', 'Máquina', 'Quadríceps'),
(38, 'Cadeira Extensora (Máquina)', NULL, 'ex_1768674129.mp4', 'Máquina', 'Quadríceps'),
(39, 'Mesa Flexora (Máquina)', NULL, 'ex_1768674223.mp4', 'Máquina', 'Posterior de Coxa'),
(40, 'Levantamento Terra Romeno (Barra)', NULL, 'ex_1768674303.mp4', 'Barra', 'Posterior de Coxa'),
(41, 'Elevação de Panturrilha em Pé (Barra)', NULL, 'ex_1768674412.mp4', 'Barra', 'Panturrilha'),
(42, 'Abdominal', NULL, 'ex_1768674584.mp4', 'Outro', 'Abdômen Superior'),
(43, 'Elevação de Pernas', NULL, 'ex_1768674652.mp4', 'Outro', 'Abdômen Inferior'),
(44, 'Abdominal Toque no Calcanhar', NULL, 'ex_1768674742.mp4', 'Outro', 'Oblíquos'),
(45, 'Levantamento Terra (Barra)', NULL, 'ex_1768674850.mp4', 'Barra', 'Glúteo'),
(46, 'Encolhimento (Barra)', NULL, 'ex_1768674937.mp4', 'Barra', 'Trapézio'),
(47, 'Prancha Abdominal', NULL, 'ex_1768675006.mp4', 'Outro', 'Abdômen Superior');

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_treino`
--

CREATE TABLE `itens_treino` (
  `id` int(11) NOT NULL,
  `treino_id` int(11) NOT NULL,
  `exercicio_id` int(11) NOT NULL,
  `series` int(11) NOT NULL,
  `repeticoes` int(11) NOT NULL,
  `carga_kg` float NOT NULL,
  `observacao` text DEFAULT NULL,
  `descanso` varchar(10) DEFAULT '00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `itens_treino`
--

INSERT INTO `itens_treino` (`id`, `treino_id`, `exercicio_id`, `series`, `repeticoes`, `carga_kg`, `observacao`, `descanso`) VALUES
(105, 42, 20, 3, 10, 0, '', '00:00'),
(106, 42, 20, 3, 10, 0, '', '00:00'),
(107, 42, 20, 3, 10, 0, '', '00:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `treinos`
--

CREATE TABLE `treinos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `nome_treino` varchar(100) DEFAULT NULL,
  `data_treino` date NOT NULL,
  `comentario` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `treinos`
--

INSERT INTO `treinos` (`id`, `usuario_id`, `nome_treino`, `data_treino`, `comentario`) VALUES
(42, 1, 'Nova Rotina', '2026-01-17', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `treino_series`
--

CREATE TABLE `treino_series` (
  `id` int(11) NOT NULL,
  `item_treino_id` int(11) NOT NULL,
  `numero_serie` int(11) NOT NULL,
  `carga_kg` varchar(20) DEFAULT NULL,
  `repeticoes` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `treino_series`
--

INSERT INTO `treino_series` (`id`, `item_treino_id`, `numero_serie`, `carga_kg`, `repeticoes`) VALUES
(195, 105, 1, '0', '10'),
(196, 105, 2, '0', '10'),
(197, 105, 3, '0', '10'),
(198, 106, 1, '0', '10'),
(199, 106, 2, '0', '10'),
(200, 106, 3, '0', '10'),
(201, 107, 1, '0', '10'),
(202, 107, 2, '0', '10'),
(203, 107, 3, '0', '10');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `perfil` varchar(20) NOT NULL DEFAULT 'atleta',
  `data_cadastro` datetime DEFAULT current_timestamp(),
  `foto_perfil` varchar(255) DEFAULT 'padrao.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `perfil`, `data_cadastro`, `foto_perfil`) VALUES
(1, 'Administrador', 'admin@sudolift.com', '$2y$10$uIC.KOh5NZVjgBo2GzKMd.vb6Xcb3kZaHZFkev4wvgqoP2g5mjD42', 'admin', '2026-01-11 20:57:29', 'padrao.png'),
(5, 'Emanuel Rocha', 'emanuel@sudolift.com', '$2y$10$MXUL0QSN3Az1y0CHdCcx0uIFPdaQKDD6h8Tc239UNRE1nw10DZa4m', 'admin', '2026-01-17 13:38:47', 'user_1768667927.jpg');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `ativacao_muscular`
--
ALTER TABLE `ativacao_muscular`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exercicio_id` (`exercicio_id`);

--
-- Índices de tabela `exercicios`
--
ALTER TABLE `exercicios`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `itens_treino`
--
ALTER TABLE `itens_treino`
  ADD PRIMARY KEY (`id`),
  ADD KEY `treino_id` (`treino_id`),
  ADD KEY `exercicio_id` (`exercicio_id`);

--
-- Índices de tabela `treinos`
--
ALTER TABLE `treinos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `treino_series`
--
ALTER TABLE `treino_series`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_treino_id` (`item_treino_id`);

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
-- AUTO_INCREMENT de tabela `ativacao_muscular`
--
ALTER TABLE `ativacao_muscular`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT de tabela `exercicios`
--
ALTER TABLE `exercicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de tabela `itens_treino`
--
ALTER TABLE `itens_treino`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT de tabela `treinos`
--
ALTER TABLE `treinos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de tabela `treino_series`
--
ALTER TABLE `treino_series`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=218;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `ativacao_muscular`
--
ALTER TABLE `ativacao_muscular`
  ADD CONSTRAINT `ativacao_muscular_ibfk_1` FOREIGN KEY (`exercicio_id`) REFERENCES `exercicios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `itens_treino`
--
ALTER TABLE `itens_treino`
  ADD CONSTRAINT `itens_treino_ibfk_1` FOREIGN KEY (`treino_id`) REFERENCES `treinos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `itens_treino_ibfk_2` FOREIGN KEY (`exercicio_id`) REFERENCES `exercicios` (`id`);

--
-- Restrições para tabelas `treinos`
--
ALTER TABLE `treinos`
  ADD CONSTRAINT `treinos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `treino_series`
--
ALTER TABLE `treino_series`
  ADD CONSTRAINT `treino_series_ibfk_1` FOREIGN KEY (`item_treino_id`) REFERENCES `itens_treino` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
