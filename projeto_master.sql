-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 10/04/2025 às 20:32
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
-- Banco de dados: `projeto_master`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  `nota` int(11) DEFAULT NULL CHECK (`nota` between 1 and 5),
  `data_envio` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `dashboard_items`
--

CREATE TABLE `dashboard_items` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `icone` varchar(50) DEFAULT NULL,
  `cor` varchar(20) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `registro_ponto`
--

CREATE TABLE `registro_ponto` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `data` date NOT NULL,
  `tipo_ponto` varchar(50) NOT NULL,
  `hora` time NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `registro_ponto`
--

INSERT INTO `registro_ponto` (`id`, `nome`, `data`, `tipo_ponto`, `hora`, `foto`, `usuario_id`) VALUES
(59, '', '2025-04-02', 'entrada', '08:00:00', NULL, 4),
(60, '', '2025-04-02', 'almoco', '12:00:00', NULL, 4),
(61, '', '2025-04-02', 'retorno', '14:00:00', NULL, 4),
(62, '', '2025-04-02', 'saida', '18:00:00', NULL, 4),
(63, '', '2025-04-03', 'entrada', '08:00:00', NULL, 4),
(64, '', '2025-04-03', 'almoco', '12:00:00', NULL, 4),
(65, '', '2025-04-03', 'retorno', '14:00:00', NULL, 4),
(66, '', '2025-04-03', 'saida', '18:00:00', NULL, 4),
(67, '', '2025-04-04', 'entrada', '08:00:00', '67ed1e9e09158-WP5.png', 4),
(68, '', '2025-04-04', 'almoco', '12:00:00', '67ed1ea9dce3f-WP5.png', 4),
(69, '', '2025-04-04', 'retorno', '14:00:00', '67ed1eb8502e0-WP5.png', 4),
(70, '', '2025-04-04', 'saida', '18:00:00', '67ed1ec45f1f7-WP5.png', 4);

-- --------------------------------------------------------

--
-- Estrutura para tabela `relatorio_mensal`
--

CREATE TABLE `relatorio_mensal` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `mes` varchar(7) NOT NULL,
  `carga_diaria` varchar(5) NOT NULL,
  `total_trabalhado` varchar(5) NOT NULL,
  `horas_extras` varchar(5) NOT NULL,
  `horas_faltantes` varchar(5) NOT NULL,
  `criado_em` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `textos_site`
--

CREATE TABLE `textos_site` (
  `id` varchar(50) NOT NULL,
  `conteudo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `textos_site`
--

INSERT INTO `textos_site` (`id`, `conteudo`) VALUES
('banner_subtitulo', 'Registros digitais, relatÃ³rios automÃ¡ticos e facilidade para sua equipe.'),
('banner_titulo', 'Transforme o controle de ponto da sua clÃ­nica'),
('beneficios', 'Evita fraudes no controle de horas, centraliza dados dos funcionÃ¡rios e otimiza a gestÃ£o.'),
('como_funciona', 'FuncionÃ¡rios registram entrada, saÃ­da, almoÃ§o e intervalo com foto e horÃ¡rio exato.'),
('rodape', 'ClÃ­nica Mente Neural - Todos os direitos reservados.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tokens`
--

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `criado_em` datetime DEFAULT current_timestamp(),
  `expiracao` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tokens`
--

INSERT INTO `tokens` (`id`, `usuario_id`, `token`, `criado_em`, `expiracao`) VALUES
(1, 1, 'fdee318727562a43dcffb86e029f47c37bdd0033f9bd519c8db71248286e04fa', '2025-04-10 15:14:21', '2025-05-10 20:14:21');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `tipo` enum('admin','admin2','usuario') NOT NULL DEFAULT 'usuario',
  `nivel_acesso` varchar(20) DEFAULT 'usuario',
  `ultimo_login` datetime DEFAULT NULL,
  `carga_mensal` int(11) DEFAULT 240,
  `carga_mensal_minutos` int(11) DEFAULT 2400
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `email`, `senha`, `nome`, `foto`, `ativo`, `tipo`, `nivel_acesso`, `ultimo_login`, `carga_mensal`, `carga_mensal_minutos`) VALUES
(1, 'kayrtonsmnbvc123@gmail.com', '$2y$10$G53nuj9eG8gnvDTGytsc8utmgPzdXLkCkQ2fsd7.ysiJcoTAJ3bCC', 'kayron', 'uploads/perfil/67f80da7d0fbf-WP5.png', 1, 'admin', 'usuario', '2025-04-10 15:14:32', 240, 5760),
(2, 'lucas@gmail.com', '$2y$10$YHsKzCGCXIWhnMpC./dAleZAbIXRody8Pd.hCI8aM7Juafhj5Or3O', 'marcos', 'uploads/perfil/67e969d8d607c-Captura de tela 2025-03-08 182039.png', 1, 'usuario', 'usuario', NULL, 240, 2400),
(3, 'admin@exemplo.com', '$2y$10$ufXParicV.iX23.gUkzdH.elp.3oVg3ssaOhVqEeq6Pc9Q26bZvRa', 'anne', 'uploads/perfil/67eb287e35c85-Captura de tela 2025-02-18 202609.png', 1, 'usuario', 'usuario', NULL, 240, 2400),
(4, 'homeofice@kayrton.com', '$2y$10$.tu7728lv4wFHpnBCQ0fnuQgCPMOxt7IY639o4qVNTqZ84ba8ltVu', 'matheus', NULL, 1, 'usuario', 'usuario', NULL, 240, 2400);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `dashboard_items`
--
ALTER TABLE `dashboard_items`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `registro_ponto`
--
ALTER TABLE `registro_ponto`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `relatorio_mensal`
--
ALTER TABLE `relatorio_mensal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `textos_site`
--
ALTER TABLE `textos_site`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

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
-- AUTO_INCREMENT de tabela `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `dashboard_items`
--
ALTER TABLE `dashboard_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `registro_ponto`
--
ALTER TABLE `registro_ponto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT de tabela `relatorio_mensal`
--
ALTER TABLE `relatorio_mensal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `relatorio_mensal`
--
ALTER TABLE `relatorio_mensal`
  ADD CONSTRAINT `relatorio_mensal_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `tokens`
--
ALTER TABLE `tokens`
  ADD CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
