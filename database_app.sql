CREATE TABLE produtos (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    categoria VARCHAR(100) NULL,
    marca VARCHAR(100) NULL
);

-- ===============================
-- TABELA ESTOQUE
-- ===============================
CREATE TABLE estoque (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    produto_id BIGINT NOT NULL,
    quantidade INT NOT NULL,
    preco_compra DECIMAL(10,2) NOT NULL,
    preco_venda DECIMAL(10,2) NOT NULL,
    data_compra DATE NOT NULL,
    data_validade DATE NULL,
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
);

-- ===============================
-- TABELA VENDAS
-- ===============================
CREATE TABLE vendas (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    data_venda DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    forma_pagamento ENUM('PIX', 'Débito', 'Crédito', 'Vale') NOT NULL
);

-- ===============================
-- TABELA ITENS DA VENDA
-- ===============================
CREATE TABLE itens_venda (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    venda_id BIGINT NOT NULL,
    produto_id BIGINT NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (venda_id) REFERENCES vendas(id),
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
);