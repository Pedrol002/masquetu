-- Consolidated database schema for user_tn

CREATE DATABASE IF NOT EXISTS user_tn;
USE user_tn;

-- Tabela de usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cpf VARCHAR(14) NOT NULL,
    usuario VARCHAR(100) NOT NULL,
    telefone VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    token_recuperacao VARCHAR(255),
    token_expira DATETIME
);

-- Tabela para redefinição de senha
CREATE TABLE redefinir_senha (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  token VARCHAR(64) NOT NULL,
  expiracao DATETIME NOT NULL,
  usado BOOLEAN DEFAULT FALSE,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Tabela de produtos
CREATE TABLE produto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    imagem VARCHAR(255),
    categoria VARCHAR(100) DEFAULT NULL
);

-- Tabelas para salvar pedidos e itens
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NULL,
    nome VARCHAR(150) DEFAULT NULL,
    email VARCHAR(150) DEFAULT NULL,
    cep VARCHAR(10) DEFAULT NULL,
    endereco VARCHAR(255) DEFAULT NULL,
    cidade VARCHAR(100) DEFAULT NULL,
    uf VARCHAR(2) DEFAULT NULL,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    frete DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    subtotal DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    cep VARCHAR(10),
    endereco VARCHAR(255),
    uf VARCHAR(2),
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

CREATE TABLE pedido_itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    produto_id INT NULL,
    nome VARCHAR(255) NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    quantidade INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE
);

-- Criar usuário admin
CREATE USER IF NOT EXISTS 'admin'@'localhost' IDENTIFIED BY 'admin';
GRANT ALL PRIVILEGES ON user_tn.* TO 'admin'@'localhost';
FLUSH PRIVILEGES;

-- Inserir usuário de teste
INSERT INTO usuarios (usuario, email, cpf, telefone, senha)
VALUES ('usuario_teste', 'teste@teste.com', '12345678900', '11999999999',
        '$2y$10$Zq6nR0Q3h/vJY2HklT1Z8uOSqU3Yw.LK05zX4c53hHlDbKjEOYOTK');

INSERT INTO produto (nome, descricao, preco, imagem) VALUES
('Tênis Nike Revolution 7 Masculino', 'Se você busca uma corrida mais confortável e de alto desempenho, o Nike Revolution 7 é a escolha ideal.', 309.99, 'nike.png'),
('Tênis Mizuno Wave Frontier 15 - Masculino', 'O Mizuno Wave Frontier 15 é um tênis de corrida de alta performance projetado para corredores que buscam uma experiência de corrida suave e confortável.', 469.99, 'Mizuno.png'),
('Tênis ASICS Gel-Nagoya 7 Masculino', 'O Tênis ASICS GEL-Nagoya 7 masculino foi totalmente repaginado, trazendo um design moderno aliado a tecnologias avançadas que atendem às necessidades dos corredores mais exigentes.', 379.99, 'asics.png'),
('Tênis adidas RunFalcon 5 Feminino', 'O Tênis adidas RunFalcon 5 Feminino combina estilo e conforto para o seu dia a dia.', 349.99, 'adidas.png'),
('Tênis Mizuno Wave Dynasty 6 Masculino', 'Experimente o máximo em desempenho e conforto com o Tênis Mizuno Wave Dynasty 6.', 349.99, 'mizunera.png');

-- Tabelas para salvar pedidos e itens
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NULL,
    nome VARCHAR(150) DEFAULT NULL,
    email VARCHAR(150) DEFAULT NULL,
    cep VARCHAR(10) DEFAULT NULL,
    endereco VARCHAR(255) DEFAULT NULL,
    cidade VARCHAR(100) DEFAULT NULL,
    uf VARCHAR(2) DEFAULT NULL,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    frete DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    subtotal DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    cep VARCHAR(10),
    endereco VARCHAR(255),
    uf VARCHAR(2),
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

CREATE TABLE IF NOT EXISTS pedido_itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    produto_id INT NULL, -- se tiver tabela produtos: referência
    nome VARCHAR(255) NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    quantidade INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE
);
