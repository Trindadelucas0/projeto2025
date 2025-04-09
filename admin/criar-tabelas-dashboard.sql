-- Tabela de notícias
CREATE TABLE IF NOT EXISTS noticias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    conteudo TEXT NOT NULL,
    data_publicacao DATETIME NOT NULL,
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de frases motivacionais
CREATE TABLE IF NOT EXISTS frases_motivacionais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    frase TEXT NOT NULL,
    autor VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de campanhas
CREATE TABLE IF NOT EXISTS campanhas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT NOT NULL,
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de itens do dashboard
CREATE TABLE IF NOT EXISTS dashboard_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(50) NOT NULL,
    conteudo TEXT NOT NULL,
    cor VARCHAR(20) DEFAULT '#0099ff',
    icone VARCHAR(100) DEFAULT 'fas fa-info-circle',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inserir algumas frases motivacionais iniciais
INSERT INTO frases_motivacionais (frase, autor) VALUES
('A saúde é o que de mais valioso temos. Cuide de você e dos outros.', 'Sistema Hospitalar'),
('Cada dia é uma nova oportunidade para fazer a diferença na vida de alguém.', 'Sistema Hospitalar'),
('A dedicação ao próximo é a maior forma de amor.', 'Sistema Hospitalar'),
('Cuidar da saúde é um ato de amor próprio.', 'Sistema Hospitalar'),
('Juntos somos mais fortes na luta pela saúde.', 'Sistema Hospitalar'); 