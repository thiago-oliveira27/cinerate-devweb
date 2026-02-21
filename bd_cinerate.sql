-- ============================================================
-- CineRate - Backup do Banco de Dados
-- Arquivo: bd_cinerate.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS cinerate
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE cinerate;

-- ---- Tabela: usuarios ----
CREATE TABLE IF NOT EXISTS usuarios (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome        VARCHAR(100) NOT NULL,
    email       VARCHAR(150) NOT NULL UNIQUE,
    senha_hash  VARCHAR(255) NOT NULL,
    admin       TINYINT(1)   NOT NULL DEFAULT 0,
    criado_em   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---- Tabela: titulos ----
CREATE TABLE IF NOT EXISTS titulos (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titulo      VARCHAR(200) NOT NULL,
    genero      VARCHAR(80)  NOT NULL,
    ano         SMALLINT     NOT NULL,
    tipo        ENUM('filme','serie','documentario') NOT NULL,
    sinopse     TEXT,
    poster_url  VARCHAR(500),
    usuario_id  INT UNSIGNED,
    criado_em   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---- Tabela: avaliacoes ----
CREATE TABLE IF NOT EXISTS avaliacoes (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titulo_id   INT UNSIGNED NOT NULL,
    usuario_id  INT UNSIGNED NOT NULL,
    nota        TINYINT      NOT NULL CHECK (nota BETWEEN 1 AND 5),
    comentario  TEXT         NOT NULL,
    criado_em   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_avaliacao (titulo_id, usuario_id),
    FOREIGN KEY (titulo_id)  REFERENCES titulos(id)   ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)  ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Dados de Exemplo
-- ============================================================

-- Usuário Admin (senha: admin123)
INSERT INTO usuarios (nome, email, senha_hash, admin) VALUES
('Administrador', 'admin@cinerate.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);

-- Usuário comum (senha: usuario123)
INSERT INTO usuarios (nome, email, senha_hash, admin) VALUES
('Maria Silva', 'maria@exemplo.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0);

-- Títulos de exemplo
INSERT INTO titulos (titulo, genero, ano, tipo, sinopse, poster_url, usuario_id) VALUES
('Oppenheimer',
 'Drama',
 2023,
 'filme',
 'A história do físico J. Robert Oppenheimer e seu papel no desenvolvimento da bomba atômica durante a Segunda Guerra Mundial.',
 'https://image.tmdb.org/t/p/original/1OsQJEoSXBjduuCvDOlRhoEUaHu.jpg',
 1),
('Breaking Bad',
 'Crime',
 2008,
 'serie',
 'Um professor de química do ensino médio diagnosticado com câncer de pulmão começa a fabricar metanfetamina para garantir o futuro financeiro de sua família.',
 'https://image.tmdb.org/t/p/original/hGwm9Cj3CdbJIqQWNExQqiYmCd4.jpg',
 1),
('Planeta Terra II',
 'Natureza',
 2016,
 'documentario',
 'Uma série de documentários que mostra a vida selvagem em diferentes habitats ao redor do mundo.',
 'https://image.tmdb.org/t/p/original/zoJh5hPviHhXizZ8o8s2crkYrq4.jpg',
 1),
('Interstellar',
 'Ficção Científica',
 2014,
 'filme',
 'Um grupo de astronautas viaja por um buraco de minhoca em busca de um novo lar para a humanidade.',
 'https://image.tmdb.org/t/p/original/6ricSDD83BClJsFdGB6x7cM0MFQ.jpg',
 2),
('Stranger Things',
 'Ficção Científica',
 2016,
 'serie',
 'Quando um menino desaparece, sua cidade descobre um mistério envolvendo experimentos secretos e forças sobrenaturais.',
 'https://image.tmdb.org/t/p/original/twfKp60THrcOIep9sjHODOOfO8d.jpg',
 2);

-- Avaliações de exemplo
INSERT INTO avaliacoes (titulo_id, usuario_id, nota, comentario) VALUES
(1, 2, 5, 'Uma obra-prima cinematográfica. Nolan mais uma vez supera as expectativas com uma direção magistral e atuações incríveis.'),
(2, 1, 5, 'Uma das melhores séries já produzidas. A evolução do personagem Walt é simplesmente fascinante.'),
(3, 2, 4, 'Imagens deslumbrantes e narração envolvente. Uma verdadeira obra sobre a beleza do nosso planeta.'),
(4, 1, 5, 'Épico e emocionante. A trilha sonora e as cenas espaciais são de tirar o fôlego.'),
(5, 1, 4, 'Uma série muito bem produzida com personagens cativantes e uma trama que prende do início ao fim.');
