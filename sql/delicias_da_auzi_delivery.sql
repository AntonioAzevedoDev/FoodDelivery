-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08-Ago-2023 às 00:34
-- Versão do servidor: 10.4.17-MariaDB
-- versão do PHP: 7.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `delicias_da_auzi_delivery`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `bairros`
--

CREATE TABLE `bairros` (
  `id` int(5) UNSIGNED NOT NULL,
  `nome` varchar(128) NOT NULL,
  `slug` varchar(128) NOT NULL,
  `cidade` varchar(20) NOT NULL DEFAULT 'Cascavel',
  `valor_entrega` decimal(10,2) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `deletado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `bairros`
--

INSERT INTO `bairros` (`id`, `nome`, `slug`, `cidade`, `valor_entrega`, `ativo`, `criado_em`, `atualizado_em`, `deletado_em`) VALUES
(1, 'Jardim Primavera', 'jardim-primavera', 'Cascavel', '2.00', 1, '2023-08-05 19:32:51', '2023-08-05 19:32:51', NULL),
(2, 'Centro', 'centro', 'Cascavel', '6.00', 1, '2023-07-28 15:51:03', '2023-07-28 15:51:17', NULL),
(3, 'Multirão', 'multirão', 'Cascavel', '5.00', 1, '2023-08-05 18:54:02', '2023-08-05 18:54:02', NULL),
(4, 'Planalto', 'planalto', 'Cascavel', '5.00', 1, '2023-08-05 18:54:02', '2023-08-05 18:54:02', NULL),
(5, 'Vila Xexeu', 'vila-xexeu', 'Cascavel', '5.00', 1, '2023-08-05 18:54:59', '2023-08-05 18:54:59', NULL),
(6, 'Alto Luminoso', 'alto-luminoso', 'Cascavel', '5.00', 1, '2023-08-05 18:54:59', '2023-08-05 18:54:59', NULL),
(7, 'Rio Novo', 'rio-novo', 'Cascavel', '5.00', 1, '2023-08-05 18:56:01', '2023-08-05 18:56:01', NULL),
(8, 'Malvina', 'malvina', 'Cascavel', '5.00', 1, '2023-08-05 18:56:01', '2023-08-05 18:56:01', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `categorias`
--

CREATE TABLE `categorias` (
  `id` int(5) UNSIGNED NOT NULL,
  `nome` varchar(128) NOT NULL,
  `slug` varchar(128) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `deletado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `categorias`
--

INSERT INTO `categorias` (`id`, `nome`, `slug`, `ativo`, `criado_em`, `atualizado_em`, `deletado_em`) VALUES
(1, 'Pizza doce', 'pizza-doce', 1, '2023-07-17 16:01:13', '2023-07-17 20:26:18', NULL),
(2, 'Pizza salgada', 'pizza-salgada', 1, '2023-07-17 20:17:17', '2023-07-17 20:17:17', NULL),
(3, 'Calzone', 'calzone', 1, '2023-07-31 11:15:08', '2023-07-31 11:15:08', NULL),
(4, 'Sanduiches', 'sanduiches', 1, '2023-07-31 11:15:24', '2023-07-31 11:15:24', NULL),
(5, 'Bebidas', 'bebidas', 1, '2023-07-31 11:15:33', '2023-07-31 11:15:33', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `entregadores`
--

CREATE TABLE `entregadores` (
  `id` int(5) UNSIGNED NOT NULL,
  `nome` varchar(128) NOT NULL,
  `cpf` varchar(20) NOT NULL,
  `cnh` varchar(20) NOT NULL,
  `email` varchar(128) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `endereco` varchar(240) NOT NULL,
  `imagem` varchar(240) DEFAULT NULL,
  `veiculo` varchar(240) NOT NULL,
  `placa` varchar(240) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `deletado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `entregadores`
--

INSERT INTO `entregadores` (`id`, `nome`, `cpf`, `cnh`, `email`, `telefone`, `endereco`, `imagem`, `veiculo`, `placa`, `ativo`, `criado_em`, `atualizado_em`, `deletado_em`) VALUES
(1, 'Marcos Azevedo', '575.449.720-20', '93082449664', 'marcos@email.com', '(85) 99999-9999', 'Rua Joana Darc, 321, Jardim Primavera, Cascavel - CE', '1691442937_78bd0cc3669a740d30f1.jpeg', 'Titan 150 - Vermelha - 2005', 'HVO-1735', 1, '2023-07-27 15:53:17', '2023-08-07 18:15:37', NULL),
(2, 'Homem Aranha', '267.511.923-71', '82294266718', 'murilo.caua.barbosa@avoeazul.com.br', '(85) 99457-2587', 'Praça Horácio Bessa 2163 - 956 - Centro - Cascavel', '1690490520_b98e463ea448de0056f1.webp', 'CG 2018 - Vermelha', 'HVU-7208', 1, '2023-07-27 17:12:08', '2023-07-27 17:42:00', NULL),
(3, 'Homem de Ferro', '889.131.553-20', '76619664560', 'bruno-assuncao72@mourafiorito.com', '(85) 98841-9711', 'Praça Horácio Bessa 2163 - 335 - Centro - Cascavel - CE', '1690489622_00a432c76d973b424453.jpg', 'XRE300 - Adventure', 'HWV-8880', 1, '2023-07-27 17:25:26', '2023-07-27 17:27:02', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `expediente`
--

CREATE TABLE `expediente` (
  `id` int(5) UNSIGNED NOT NULL,
  `dia` int(5) NOT NULL,
  `dia_descricao` varchar(50) NOT NULL,
  `abertura` time DEFAULT NULL,
  `fechamento` time DEFAULT NULL,
  `situacao` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `expediente`
--

INSERT INTO `expediente` (`id`, `dia`, `dia_descricao`, `abertura`, `fechamento`, `situacao`) VALUES
(1, 0, 'Domingo', '18:00:00', '23:00:00', 1),
(2, 1, 'Segunda', '13:00:00', '23:00:00', 1),
(3, 2, 'Terça', '18:00:00', '23:00:00', 1),
(4, 3, 'Quarta', '18:00:00', '23:00:00', 0),
(5, 4, 'Quinta', '18:00:00', '23:00:00', 0),
(6, 5, 'Sexta', '18:00:00', '23:00:00', 1),
(7, 6, 'Sábado', '18:00:00', '23:00:00', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `extras`
--

CREATE TABLE `extras` (
  `id` int(5) UNSIGNED NOT NULL,
  `nome` varchar(128) NOT NULL,
  `slug` varchar(128) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `descricao` text NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `deletado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `extras`
--

INSERT INTO `extras` (`id`, `nome`, `slug`, `preco`, `descricao`, `ativo`, `criado_em`, `atualizado_em`, `deletado_em`) VALUES
(1, 'Borda de cheddar', 'borda-de-cheddar', '10.00', 'Borda recheada com queijo cheddar.', 1, '2023-07-18 16:07:15', '2023-07-18 16:07:15', NULL),
(2, 'Borda de Catupiry', 'borda-de-catupiry', '10.00', 'Borda recheada com catupiry', 1, '2023-07-18 16:10:59', '2023-07-18 16:11:07', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `formas_pagamento`
--

CREATE TABLE `formas_pagamento` (
  `id` int(5) UNSIGNED NOT NULL,
  `nome` varchar(128) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `deletado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `formas_pagamento`
--

INSERT INTO `formas_pagamento` (`id`, `nome`, `ativo`, `criado_em`, `atualizado_em`, `deletado_em`) VALUES
(1, 'Dinheiro', 1, '2023-07-26 09:44:04', '2023-07-26 09:44:04', NULL),
(2, 'Cartão de crédito', 1, '2023-07-26 17:56:13', '2023-07-26 18:33:51', NULL),
(3, 'Cartão de débito', 1, '2023-07-26 18:24:00', '2023-07-26 18:34:34', NULL),
(4, 'Pix', 1, '2023-08-03 22:23:10', '2023-08-03 22:23:10', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `medidas`
--

CREATE TABLE `medidas` (
  `id` int(5) UNSIGNED NOT NULL,
  `nome` varchar(128) NOT NULL,
  `descricao` text NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `deletado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `medidas`
--

INSERT INTO `medidas` (`id`, `nome`, `descricao`, `ativo`, `criado_em`, `atualizado_em`, `deletado_em`) VALUES
(1, 'Pizza grande 12 pedaços', 'Pizza grande 12 pedaços', 1, '2023-07-18 16:28:53', '2023-07-18 16:49:47', NULL),
(2, 'Pizza media 8 pedaços', 'Pizza media 8 pedaços', 1, '2023-07-18 16:48:22', '2023-07-18 16:48:22', NULL),
(3, 'Refrigerante 250ML', 'Refrigerante 250ML', 1, '2023-07-31 11:16:35', '2023-07-31 11:16:35', NULL),
(4, 'Refrigerante 600ML', 'Refrigerante 600ML', 1, '2023-07-31 11:16:47', '2023-07-31 11:16:47', NULL),
(5, 'Refrigerante 1L', 'Refrigerante 1L', 1, '2023-07-31 11:16:58', '2023-07-31 11:16:58', NULL),
(6, 'Calzone Padrão', 'Calzone Padrão', 1, '2023-07-31 11:17:20', '2023-07-31 11:17:20', NULL),
(7, 'Sanduiche Pão de forma', 'Sanduiche Pão de forma', 1, '2023-07-31 11:21:32', '2023-07-31 11:21:32', NULL),
(8, 'Sanduiche Pão Árabe', 'Sanduiche Pão Árabe', 1, '2023-07-31 11:21:44', '2023-07-31 11:21:44', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(3, '2023-07-09-205217', 'App\\Database\\Migrations\\CriaTabelaUsuarios', 'default', 'App', 1688937302, 1),
(5, '2023-07-17-185812', 'App\\Database\\Migrations\\CriaTabelaCategorias', 'default', 'App', 1689620471, 2),
(6, '2023-07-18-182039', 'App\\Database\\Migrations\\CriaTabelaExtras', 'default', 'App', 1689704572, 3),
(7, '2023-07-18-192754', 'App\\Database\\Migrations\\CriaTabelaMedidas', 'default', 'App', 1689708518, 4),
(8, '2023-07-18-234643', 'App\\Database\\Migrations\\CriaTabelaProdutos', 'default', 'App', 1689724401, 5),
(9, '2023-07-20-144746', 'App\\Database\\Migrations\\CriaTabelaProdutosExtras', 'default', 'App', 1689864699, 6),
(10, '2023-07-21-141558', 'App\\Database\\Migrations\\CriaTabelaProdutosEspecificacoes', 'default', 'App', 1689949120, 7),
(11, '2023-07-26-123445', 'App\\Database\\Migrations\\CriaTabelaFormasPagamento', 'default', 'App', 1690375030, 8),
(12, '2023-07-27-181102', 'App\\Database\\Migrations\\CriaTabelaEntregadores', 'default', 'App', 1690483987, 9),
(13, '2023-07-28-124421', 'App\\Database\\Migrations\\CriaTabelaBairros', 'default', 'App', 1690548524, 10),
(15, '2023-07-28-193749', 'App\\Database\\Migrations\\CriaTabelaExpediente', 'default', 'App', 1690573838, 11),
(16, '2023-08-04-150637', 'App\\Database\\Migrations\\CriaTabelaPedidos', 'default', 'App', 1691161893, 12),
(17, '2023-08-07-125528', 'App\\Database\\Migrations\\CriaTabelaPedidosProdutos', 'default', 'App', 1691413028, 13);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(5) UNSIGNED NOT NULL,
  `usuario_id` int(5) UNSIGNED NOT NULL,
  `entregador_id` int(5) UNSIGNED DEFAULT NULL,
  `codigo` varchar(10) NOT NULL,
  `forma_pagamento` varchar(50) NOT NULL,
  `situacao` tinyint(1) NOT NULL DEFAULT 0,
  `produtos` text NOT NULL,
  `valor_produtos` decimal(10,2) NOT NULL,
  `valor_entrega` decimal(10,2) NOT NULL,
  `valor_pedido` decimal(10,2) NOT NULL,
  `endereco_entrega` varchar(255) NOT NULL,
  `observacoes` varchar(255) NOT NULL,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `deletado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `pedidos`
--

INSERT INTO `pedidos` (`id`, `usuario_id`, `entregador_id`, `codigo`, `forma_pagamento`, `situacao`, `produtos`, `valor_produtos`, `valor_entrega`, `valor_pedido`, `endereco_entrega`, `observacoes`, `criado_em`, `atualizado_em`, `deletado_em`) VALUES
(1, 1, 1, '35932815', 'Pix', 2, 'a:2:{i:0;a:6:{s:2:\"id\";s:1:\"7\";s:4:\"nome\";s:35:\"Coca Cola 600ML Refrigerante 600ML \";s:4:\"slug\";s:34:\"coca-cola-600ml-refrigerante-600ml\";s:5:\"preco\";s:4:\"7.00\";s:10:\"quantidade\";i:1;s:7:\"tamanho\";s:18:\"Refrigerante 600ML\";}i:1;a:5:{s:4:\"slug\";s:97:\"pizza-grande-12-pedacos-metade-pizza-de-frango-metade-pizza-de-queijo-com-extra-borda-de-catupiry\";s:4:\"nome\";s:98:\"Pizza grande 12 pedaços metade Pizza de frango metade Pizza de queijo Com extra Borda de Catupiry\";s:5:\"preco\";s:5:\"40.00\";s:10:\"quantidade\";i:1;s:7:\"tamanho\";s:24:\"Pizza grande 12 pedaços\";}}', '47.00', '6.00', '53.00', 'Jardim Primavera - Cascavel - CEP 62850-000 - CE - Rua Joana Darc - Número 321', 'Ponto de referência: Delicias da Auzi - Número: 321', '2023-08-04 16:46:16', '2023-08-07 10:17:20', NULL),
(2, 1, NULL, '12476900', 'Cartão de crédito', 3, 'a:2:{i:0;a:6:{s:2:\"id\";s:1:\"7\";s:4:\"nome\";s:35:\"Coca Cola 600ML Refrigerante 600ML \";s:4:\"slug\";s:34:\"coca-cola-600ml-refrigerante-600ml\";s:5:\"preco\";s:4:\"7.00\";s:10:\"quantidade\";i:1;s:7:\"tamanho\";s:18:\"Refrigerante 600ML\";}i:1;a:5:{s:4:\"slug\";s:97:\"pizza-grande-12-pedacos-metade-pizza-de-frango-metade-pizza-de-queijo-com-extra-borda-de-catupiry\";s:4:\"nome\";s:98:\"Pizza grande 12 pedaços metade Pizza de frango metade Pizza de queijo Com extra Borda de Catupiry\";s:5:\"preco\";s:5:\"40.00\";s:10:\"quantidade\";i:1;s:7:\"tamanho\";s:24:\"Pizza grande 12 pedaços\";}}', '47.00', '6.00', '53.00', 'Jardim Primavera - Cascavel - CEP 62850-000 - CE - ASKOK - Número 123', 'Ponto de referência: ASMDKA - Número: 123', '2023-08-04 16:54:15', '2023-08-07 10:38:24', NULL),
(3, 1, 2, '38560716', 'Dinheiro', 2, 'a:1:{i:0;a:6:{s:2:\"id\";s:1:\"1\";s:4:\"nome\";s:56:\"Pizza de chocolate com morango Pizza grande 12 pedaços \";s:4:\"slug\";s:54:\"pizza-de-chocolate-com-morango-pizza-grande-12-pedacos\";s:5:\"preco\";s:5:\"30.00\";s:10:\"quantidade\";i:1;s:7:\"tamanho\";s:24:\"Pizza grande 12 pedaços\";}}', '30.00', '2.00', '32.00', 'Jardim Primavera - Cascavel - CEP 62850-000 - CE - Rua Joana Darc - Número 321', 'Ponto de referência: Delicias da Auzi - Número: 321. - Você informou que não precisa de troco', '2023-08-07 15:45:06', '2023-08-07 16:37:09', NULL),
(4, 1, NULL, '94327370', 'Pix', 0, 'a:1:{i:0;a:5:{s:4:\"slug\";s:97:\"pizza-grande-12-pedacos-metade-pizza-de-frango-metade-pizza-de-queijo-com-extra-borda-de-catupiry\";s:4:\"nome\";s:98:\"Pizza grande 12 pedaços metade Pizza de frango metade Pizza de queijo Com extra Borda de Catupiry\";s:5:\"preco\";s:5:\"40.00\";s:10:\"quantidade\";i:1;s:7:\"tamanho\";s:24:\"Pizza grande 12 pedaços\";}}', '40.00', '2.00', '42.00', 'Jardim Primavera - Cascavel - CEP 62850-000 - CE - Rua Joana Darc - Número 321', 'Ponto de referência: Delicias da Auzi - Número: 321', '2023-08-07 17:08:16', '2023-08-07 17:08:16', NULL),
(5, 1, 3, '31720617', 'Pix', 2, 'a:2:{i:0;a:6:{s:2:\"id\";s:1:\"4\";s:4:\"nome\";s:68:\"Pizza de queijo Pizza grande 12 pedaços Com extra Borda de Catupiry\";s:4:\"slug\";s:67:\"pizza-de-queijo-pizza-grande-12-pedacos-com-extra-borda-de-catupiry\";s:5:\"preco\";s:5:\"35.00\";s:10:\"quantidade\";i:1;s:7:\"tamanho\";s:24:\"Pizza grande 12 pedaços\";}i:1;a:6:{s:2:\"id\";s:1:\"8\";s:4:\"nome\";s:29:\"Coca Cola 1L Refrigerante 1L \";s:4:\"slug\";s:28:\"coca-cola-1l-refrigerante-1l\";s:5:\"preco\";s:5:\"10.00\";s:10:\"quantidade\";i:1;s:7:\"tamanho\";s:15:\"Refrigerante 1L\";}}', '45.00', '6.00', '51.00', 'Centro - Cascavel - CEP 62850-000 - CE - Centro - Número 2254', 'Ponto de referência: Casa verde - Número: 2254', '2023-08-07 17:27:06', '2023-08-07 17:59:11', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedidos_produtos`
--

CREATE TABLE `pedidos_produtos` (
  `id` int(5) UNSIGNED NOT NULL,
  `pedido_id` int(5) UNSIGNED NOT NULL,
  `produto` varchar(128) NOT NULL,
  `quantidade` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `pedidos_produtos`
--

INSERT INTO `pedidos_produtos` (`id`, `pedido_id`, `produto`, `quantidade`) VALUES
(1, 1, 'Coca Cola 600ML Refrigerante 600ML ', 1),
(2, 1, 'Pizza grande 12 pedaços metade Pizza de frango metade Pizza de queijo Com extra Borda de Catupiry', 1),
(3, 3, 'Pizza de chocolate com morango Pizza grande 12 pedaços ', 1),
(4, 5, 'Pizza de queijo Pizza grande 12 pedaços Com extra Borda de Catupiry', 1),
(5, 5, 'Coca Cola 1L Refrigerante 1L ', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(5) UNSIGNED NOT NULL,
  `categoria_id` int(5) UNSIGNED NOT NULL,
  `nome` varchar(128) NOT NULL,
  `slug` varchar(128) NOT NULL,
  `ingredientes` text NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `imagem` varchar(200) NOT NULL,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `deletado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `produtos`
--

INSERT INTO `produtos` (`id`, `categoria_id`, `nome`, `slug`, `ingredientes`, `ativo`, `imagem`, `criado_em`, `atualizado_em`, `deletado_em`) VALUES
(1, 1, 'Pizza de chocolate com morango', 'pizza-de-chocolate-com-morango', 'Pizza de chocolate com morango', 1, '1690489282_dfaa57dbbdd35b213804.jpg', '2023-07-18 20:54:32', '2023-07-27 17:21:22', NULL),
(4, 2, 'Pizza de queijo', 'pizza-de-queijo', 'Pizza de queijo', 1, '1690586517_07fb55ea3175c6ecbc25.jpg', '2023-07-18 21:59:44', '2023-07-28 20:21:57', NULL),
(5, 2, 'Pizza de frango', 'pizza-de-frango', 'Pizza de frango', 1, '1690586529_d4b991960ff7126079ef.jpg', '2023-07-28 20:07:53', '2023-07-28 20:22:09', NULL),
(6, 5, 'Coca Cola 250 ML', 'coca-cola-250-ml', '', 1, '1690813531_90c9319bff1dacd9ed9b.webp', '2023-07-31 11:17:49', '2023-07-31 11:25:31', NULL),
(7, 5, 'Coca Cola 600ML', 'coca-cola-600ml', '', 1, '1690813570_e54e3ba3de1599b83be3.png', '2023-07-31 11:18:25', '2023-07-31 11:26:10', NULL),
(8, 5, 'Coca Cola 1L', 'coca-cola-1l', '', 1, '1690813583_dc03be8778c1f36fc652.jpg', '2023-07-31 11:19:00', '2023-07-31 11:26:23', NULL),
(9, 3, 'Calzone Frango', 'calzone-frango', '', 1, '1690813597_022d2183a7453fbf1e42.jpg', '2023-07-31 11:19:45', '2023-07-31 11:26:37', NULL),
(10, 4, 'Sanduiche de frango', 'sanduiche-de-frango', '', 1, '1690813609_5696092c87ae51ab056d.jpg', '2023-07-31 11:21:01', '2023-07-31 11:26:49', NULL),
(11, 4, 'Sanduiche de Carne', 'sanduiche-de-carne', '', 1, '1690813623_d86e09015367deda1568.jpg', '2023-07-31 11:22:29', '2023-07-31 11:27:03', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos_especificacoes`
--

CREATE TABLE `produtos_especificacoes` (
  `id` int(5) UNSIGNED NOT NULL,
  `produto_id` int(5) UNSIGNED NOT NULL,
  `medida_id` int(5) UNSIGNED NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `customizavel` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `produtos_especificacoes`
--

INSERT INTO `produtos_especificacoes` (`id`, `produto_id`, `medida_id`, `preco`, `customizavel`) VALUES
(1, 1, 2, '25.90', 1),
(2, 1, 1, '30.00', 1),
(3, 5, 1, '25.00', 1),
(4, 4, 1, '25.00', 1),
(5, 6, 3, '4.00', 0),
(6, 7, 4, '7.00', 0),
(7, 8, 5, '10.00', 0),
(8, 9, 6, '8.00', 0),
(9, 10, 7, '10.00', 1),
(10, 11, 8, '15.00', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos_extras`
--

CREATE TABLE `produtos_extras` (
  `id` int(5) UNSIGNED NOT NULL,
  `produto_id` int(5) UNSIGNED NOT NULL,
  `extra_id` int(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `produtos_extras`
--

INSERT INTO `produtos_extras` (`id`, `produto_id`, `extra_id`) VALUES
(1, 4, 2),
(2, 1, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(5) UNSIGNED NOT NULL,
  `nome` varchar(128) NOT NULL,
  `email` varchar(255) NOT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `telefone` varchar(20) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `ativo` tinyint(1) NOT NULL DEFAULT 0,
  `password_hash` varchar(255) NOT NULL,
  `ativacao_hash` varchar(64) DEFAULT NULL,
  `reset_hash` varchar(64) DEFAULT NULL,
  `reset_expira_em` datetime DEFAULT NULL,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `deletado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `cpf`, `telefone`, `is_admin`, `ativo`, `password_hash`, `ativacao_hash`, `reset_hash`, `reset_expira_em`, `criado_em`, `atualizado_em`, `deletado_em`) VALUES
(1, 'Lucas Silva Azevedo', 'admin@admin.com', '036.952.503-56', '(85) 99278-0353', 1, 1, '$2y$10$Fb5jpBBUm/Ob8dk/ZcZlkuEm/hX1zMqzOKTet4ecEN2pPewNCutRG', NULL, NULL, NULL, '2023-07-10 16:24:02', '2023-08-03 18:00:48', NULL),
(2, 'João Silva', 'joao@email.com', '634.886.120-15', '(85) 98888-9999', 0, 1, '', NULL, NULL, NULL, '2023-07-10 16:24:07', '2023-07-11 03:28:09', NULL),
(8, 'Teste', 'teste@gmail.com', '760.510.723-49', '(85) 99999-9999', 0, 1, '$2y$10$PPC2jadUdfnl/qQY3qM2j.rJXP9ZjxZmjAppkE0opL4TBu3xjyl/O', NULL, NULL, NULL, '2023-07-11 02:51:16', '2023-07-11 03:28:17', '2023-07-11 03:28:17'),
(9, 'Pedro', 'email@email.com', '861.263.890-93', '(85) 99999-9999', 0, 1, '$2y$10$cZJLs886rLex0Z2mmnKTGOsI2YsMzdvQl8Rrs8J90AXuDKoMu1vh6', NULL, NULL, NULL, '2023-07-11 03:36:14', '2023-07-11 03:36:14', NULL),
(10, 'Marcos', 'email@email1.com', '400.913.720-70', '(85) 99999-9999', 0, 1, '$2y$10$7YuOehit2j5Yo35XQbOgm.MCT0joHySO6SrurJjbXY/AJVSGxW5vS', NULL, NULL, NULL, '2023-07-11 03:36:39', '2023-07-11 03:36:39', NULL),
(12, 'Lucas Silva', 'lucassilva.eq@gmail.com', '045.385.210-60', '(85) 99278-0353', 0, 1, '$2y$10$wh2Hc2yCQXYMboPprfRgmehRxamuj0vEFZzNTaR9ZNIBCD3rQh8B6', NULL, NULL, NULL, '2023-07-12 22:56:53', '2023-07-15 18:19:04', NULL),
(17, 'Nova Conta', 'rimsha7370@uorak.com', '433.273.440-86', '', 0, 1, '$2y$10$vPIGTo8rqrdOg/9Qivj0/OOiuTl8jYyvkscBguQRyBNhBgmn0whO2', NULL, NULL, NULL, '2023-08-03 16:42:29', '2023-08-03 16:42:43', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `bairros`
--
ALTER TABLE `bairros`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices para tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices para tabela `entregadores`
--
ALTER TABLE `entregadores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `cnh` (`cnh`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `telefone` (`telefone`);

--
-- Índices para tabela `expediente`
--
ALTER TABLE `expediente`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `extras`
--
ALTER TABLE `extras`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices para tabela `formas_pagamento`
--
ALTER TABLE `formas_pagamento`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices para tabela `medidas`
--
ALTER TABLE `medidas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices para tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedidos_usuario_id_foreign` (`usuario_id`),
  ADD KEY `pedidos_entregador_id_foreign` (`entregador_id`);

--
-- Índices para tabela `pedidos_produtos`
--
ALTER TABLE `pedidos_produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedidos_produtos_pedido_id_foreign` (`pedido_id`);

--
-- Índices para tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`),
  ADD KEY `produtos_categoria_id_foreign` (`categoria_id`);

--
-- Índices para tabela `produtos_especificacoes`
--
ALTER TABLE `produtos_especificacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produtos_especificacoes_produto_id_foreign` (`produto_id`),
  ADD KEY `produtos_especificacoes_medida_id_foreign` (`medida_id`);

--
-- Índices para tabela `produtos_extras`
--
ALTER TABLE `produtos_extras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produtos_extras_produto_id_foreign` (`produto_id`),
  ADD KEY `produtos_extras_extra_id_foreign` (`extra_id`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `ativacao_hash` (`ativacao_hash`),
  ADD UNIQUE KEY `reset_hash` (`reset_hash`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `bairros`
--
ALTER TABLE `bairros`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `entregadores`
--
ALTER TABLE `entregadores`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `expediente`
--
ALTER TABLE `expediente`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `extras`
--
ALTER TABLE `extras`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `formas_pagamento`
--
ALTER TABLE `formas_pagamento`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `medidas`
--
ALTER TABLE `medidas`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `pedidos_produtos`
--
ALTER TABLE `pedidos_produtos`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `produtos_especificacoes`
--
ALTER TABLE `produtos_especificacoes`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `produtos_extras`
--
ALTER TABLE `produtos_extras`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_entregador_id_foreign` FOREIGN KEY (`entregador_id`) REFERENCES `entregadores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pedidos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `pedidos_produtos`
--
ALTER TABLE `pedidos_produtos`
  ADD CONSTRAINT `pedidos_produtos_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `produtos_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

--
-- Limitadores para a tabela `produtos_especificacoes`
--
ALTER TABLE `produtos_especificacoes`
  ADD CONSTRAINT `produtos_especificacoes_medida_id_foreign` FOREIGN KEY (`medida_id`) REFERENCES `medidas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `produtos_especificacoes_produto_id_foreign` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `produtos_extras`
--
ALTER TABLE `produtos_extras`
  ADD CONSTRAINT `produtos_extras_extra_id_foreign` FOREIGN KEY (`extra_id`) REFERENCES `extras` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `produtos_extras_produto_id_foreign` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
