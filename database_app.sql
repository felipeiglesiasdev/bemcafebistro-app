
--
-- Estrutura para tabela `categorias`
--
CREATE TABLE `categorias` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Inserindo dados de exemplo para `categorias`
--
INSERT INTO `categorias` (`id`, `nome`) VALUES
(1, 'Bebidas Quentes'),
(2, 'Bebidas Geladas'),
(3, 'Salgados'),
(4, 'Doces e Sobremesas'),
(5, 'Sanduíches');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--
CREATE TABLE `produtos` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nome` varchar(255) NOT NULL,
  `categoria_id` bigint(20) UNSIGNED NOT NULL,
  `marca` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Inserindo dados de exemplo para `produtos`
--
INSERT INTO `produtos` (`id`, `nome`, `categoria_id`, `marca`) VALUES
(1, 'Café Espresso', 1, 'Bem Café'),
(2, 'Pão de Queijo', 3, NULL),
(3, 'Suco de Laranja Natural', 2, NULL),
(4, 'Bolo de Chocolate', 4, 'Vó Maria');


-- --------------------------------------------------------

--
-- Estrutura para tabela `estoque`
--
CREATE TABLE `estoque` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `produto_id` bigint(20) UNSIGNED NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_compra` decimal(10,2) DEFAULT NULL,
  `preco_venda` decimal(10,2) NOT NULL,
  `data_compra` date DEFAULT NULL,
  `data_validade` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `vendas`
--
CREATE TABLE `vendas` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `data_venda` datetime NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `forma_pagamento` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_venda`
--
CREATE TABLE `itens_venda` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `venda_id` int(11) NOT NULL,
  `produto_id` bigint(20) UNSIGNED NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Chaves estrangeiras (relações entre as tabelas)
--
ALTER TABLE `produtos`
  ADD KEY `fk_produtos_categorias` (`categoria_id`);
  
ALTER TABLE `produtos`
  ADD CONSTRAINT `fk_produtos_categorias` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE CASCADE;

ALTER TABLE `estoque`
  ADD KEY `fk_estoque_produtos` (`produto_id`);

ALTER TABLE `estoque`
  ADD CONSTRAINT `fk_estoque_produtos` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;

ALTER TABLE `itens_venda`
  ADD KEY `fk_itens_vendas` (`venda_id`),
  ADD KEY `fk_itens_produtos` (`produto_id`);

ALTER TABLE `itens_venda`
  ADD CONSTRAINT `fk_itens_produtos` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`),
  ADD CONSTRAINT `fk_itens_vendas` FOREIGN KEY (`venda_id`) REFERENCES `vendas` (`id`);
