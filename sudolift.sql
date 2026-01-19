-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 19/01/2026 às 19:43
-- Versão do servidor: 11.8.3-MariaDB-log
-- Versão do PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `u459760425_sudolift`
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
-- Estrutura para tabela `citacoes`
--

CREATE TABLE `citacoes` (
  `id` int(11) NOT NULL,
  `descricao` text NOT NULL,
  `autor` varchar(100) DEFAULT 'Desconhecido'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `citacoes`
--

INSERT INTO `citacoes` (`id`, `descricao`, `autor`) VALUES
(1, 'O único treino ruim é aquele que não aconteceu.', 'Desconhecido'),
(2, 'Motivação é o que te faz começar. Hábito é o que te faz continuar.', 'Jim Ryun'),
(3, 'Se não te desafia, não te muda.', 'Fred DeVito'),
(4, 'Sem dor, sem ganho.', 'Ben Franklin'),
(5, 'O corpo alcança o que a mente acredita.', 'Napoleão Hill'),
(6, 'A disciplina é a mãe do êxito.', 'Ésquilo'),
(7, 'Não deixe para amanhã o que você pode treinar hoje.', 'Desconhecido'),
(8, 'Força não vem da capacidade física. Vem de uma vontade indomável.', 'Mahatma Gandhi'),
(9, 'A dor que você sente hoje será a força que você sentirá amanhã.', 'Arnold Schwarzenegger'),
(10, 'O último 1% é o que conta.', 'Arnold Schwarzenegger'),
(11, 'Se você não vê o resultado, continue. O progresso é invisível no começo.', 'Arnold Schwarzenegger'),
(12, 'A mente é o limite. Enquanto a mente consegue imaginar o fato de que você pode fazer algo, você consegue fazer.', 'Arnold Schwarzenegger'),
(13, 'Todo mundo quer ser fisiculturista, mas ninguém quer levantar pesos pesados.', 'Ronnie Coleman'),
(14, 'Leve e fácil, leve e fácil!', 'Ronnie Coleman'),
(15, 'A coragem não é a ausência do medo, mas o triunfo sobre ele.', 'Nelson Mandela'),
(16, 'Nós somos o que repetidamente fazemos. A excelência, portanto, não é um feito, mas um hábito.', 'Aristóteles'),
(17, 'Se você quer algo que nunca teve, precisa fazer algo que nunca fez.', 'Thomas Jefferson'),
(18, 'O segredo de progredir é começar.', 'Mark Twain'),
(19, 'A motivação é passageira. A disciplina é eterna.', 'Desconhecido'),
(20, 'Não diminua a meta. Aumente o esforço.', 'Desconhecido'),
(21, 'Seu único limite é você.', 'Desconhecido'),
(22, 'Transforme \'eu queria\' em \'eu vou\'.', 'Desconhecido'),
(23, 'O corpo que você quer esperar do outro lado do esforço que você não quer fazer.', 'Desconhecido'),
(24, 'Disciplina é escolher o que você quer mais em vez do que você quer agora.', 'Abraham Lincoln'),
(25, 'O suor é a gordura chorando.', 'Desconhecido'),
(26, 'Treine enquanto eles dormem, estude enquanto eles se divertem, persista enquanto eles descansam, e então, viva o que eles sonham.', 'Desconhecido'),
(27, 'Não pare quando estiver cansado. Pare quando tiver terminado.', 'David Goggins'),
(28, 'Você não conhece seus limites até estar disposto a ultrapassá-los.', 'Desconhecido'),
(29, 'Eu odiava cada minuto dos treinos, mas dizia para mim mesmo: Não pare. Sofra agora e viva o resto de sua vida como um campeão.', 'Muhammad Ali'),
(30, 'O impossível é apenas uma grande palavra jogada ao vento.', 'Muhammad Ali'),
(31, 'Você perde 100% dos chutes que não dá.', 'Wayne Gretzky'),
(32, 'O sucesso não é acidental. É trabalho duro, perseverança, aprendizado, estudo, sacrifício e, acima de tudo, amor pelo que você está fazendo.', 'Pelé'),
(33, 'Acredite que você pode, assim você já está no meio do caminho.', 'Theodore Roosevelt'),
(34, 'O futuro pertence àqueles que acreditam na beleza de seus sonhos.', 'Eleanor Roosevelt'),
(35, 'Não importa o quão devagar você vá, desde que você não pare.', 'Confúcio'),
(36, 'Sucesso é a soma de pequenos esforços repetidos dia após dia.', 'Robert Collier'),
(37, 'A melhor maneira de prever o futuro é criá-lo.', 'Peter Drucker'),
(38, 'Não espere. O tempo nunca será o \'certo\'.', 'Napoleon Hill'),
(39, 'Se fosse fácil, todo mundo faria.', 'Desconhecido'),
(40, 'Sua zona de conforto vai te matar.', 'Desconhecido'),
(41, 'Mais forte do que ontem.', 'Desconhecido'),
(42, 'Não conte os dias, faça os dias contarem.', 'Muhammad Ali'),
(43, 'Faça hoje o que seu eu futuro vai agradecer.', 'Desconhecido'),
(44, 'Foco, força e fé.', 'Desconhecido'),
(45, 'A persistência é o caminho do êxito.', 'Charles Chaplin'),
(46, 'Quem não luta pelo futuro que quer, deve aceitar o futuro que vier.', 'Desconhecido'),
(47, 'Vencedores não são pessoas que nunca falham, são pessoas que nunca desistem.', 'Desconhecido'),
(48, 'A vida começa no final da sua zona de conforto.', 'Neale Donald Walsch'),
(49, 'Não se sabote, se supere.', 'Desconhecido'),
(50, 'Acorde com determinação, vá dormir com satisfação.', 'Desconhecido'),
(51, 'Sem luta, não há vitória.', 'Desconhecido'),
(52, 'O único lugar onde o sucesso vem antes do trabalho é no dicionário.', 'Vidal Sassoon'),
(53, 'Seja mais forte que sua melhor desculpa.', 'Desconhecido');

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
(112, 50, 42, 3, 10, 0, '', '');

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
(50, 1, 'Nova Rotina', '2026-01-18', '');

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
(218, 112, 1, '30', '10'),
(219, 112, 2, '0', '10'),
(220, 112, 3, '0', '10');

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
(5, 'Emanuel Rocha', 'emanuel@sudolift.com', '$2y$10$MXUL0QSN3Az1y0CHdCcx0uIFPdaQKDD6h8Tc239UNRE1nw10DZa4m', 'admin', '2026-01-17 13:38:47', 'user_1768667927.jpg'),
(6, 'Instrutor da Silva', 'instrutor@sudolift.com', '$2y$10$7hTMx3a2sOFEKhDGNpT74OjDeFQptqgoLKLsXzBlMBeMoZf75unqG', 'instrutor', '2026-01-18 02:04:35', 'user_1768701875.png'),
(7, 'Escrivão', 'escrivao@sudolift.com', '$2y$10$Y173DySMFJiRVGlA01ymgeCdMkBg07e1zasWpr8tXrdSWCtMpUtcK', 'escrivao', '2026-01-18 02:16:46', 'user_1768701854.png');

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
-- Índices de tabela `citacoes`
--
ALTER TABLE `citacoes`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT de tabela `citacoes`
--
ALTER TABLE `citacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de tabela `exercicios`
--
ALTER TABLE `exercicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de tabela `itens_treino`
--
ALTER TABLE `itens_treino`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT de tabela `treinos`
--
ALTER TABLE `treinos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de tabela `treino_series`
--
ALTER TABLE `treino_series`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=221;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
