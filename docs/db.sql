CREATE DATABASE IF NOT EXISTS showfeira;
USE showfeira;

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS `categoria` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `descricao` VARCHAR(100) NOT NULL,
  `ativo` CHAR(1) NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `usuario` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `senha` VARCHAR(255) NOT NULL,
  `telefone` VARCHAR(20) NOT NULL,
  `ativo` CHAR(1) NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `produto` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(100) NOT NULL,
  `categoria_id` INT NOT NULL,
  `descricao` TEXT NOT NULL,
  `imagem` VARCHAR(255) NOT NULL,
  `valor` DECIMAL(10,2) NOT NULL,
  `destaque` CHAR(1) NOT NULL DEFAULT 'N',
  `ativo` CHAR(1) NOT NULL DEFAULT 'S',
  CONSTRAINT `fk_produto_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pedido` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `usuario_id` INT NOT NULL,
  `data_pedido` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `valor_total` DECIMAL(10,2) NOT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'pendente',
  `mercado_pago_preference_id` VARCHAR(255) NULL,
  CONSTRAINT `fk_pedido_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pedido_item` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `pedido_id` INT NOT NULL,
  `produto_id` INT NOT NULL,
  `quantidade` INT NOT NULL,
  `valor_unitario` DECIMAL(10,2) NOT NULL,
  `subtotal` DECIMAL(10,2) NOT NULL,
  CONSTRAINT `fk_item_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedido` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_item_produto` FOREIGN KEY (`produto_id`) REFERENCES `produto` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Usuário Administrador padrão (senha: admin123)
INSERT INTO `usuario` (`id`, `nome`, `email`, `senha`, `telefone`, `ativo`) VALUES
(1, 'Administrador', 'admin@feira.com', '$2y$10$/0GA87.zZMPcsB7PLhv2guot9G/Wi1.yhCP3RUDoyPotjakeJ4pNW', '(44) 99999-9999', 'S')
ON DUPLICATE KEY UPDATE `id`=`id`;

INSERT INTO `categoria` (`id`, `descricao`, `ativo`) VALUES
(1, 'Frutas', 'S'),
(2, 'Verduras', 'S'),
(3, 'Legumes', 'S')
ON DUPLICATE KEY UPDATE `id`=`id`;

INSERT INTO `produto` (`id`, `nome`, `categoria_id`, `descricao`, `imagem`, `valor`, `destaque`, `ativo`) VALUES
(1, 'Banana Nanica', 1, '<p>Banana fresca selecionada da horta.</p>', 'banana.jpg', 6.50, 'S', 'S'),
(2, 'Abacaxi Pérola', 1, '<p>Abacaxi doce e saboroso direto do produtor.</p>', 'abacaxi.jpg', 8.90, 'S', 'S'),
(3, 'Brócolis Ninja', 2, '<p>Brócolis fresco e rico em nutrientes.</p>', 'brocolis.jpg', 7.20, 'N', 'S'),
(4, 'Cenoura Orgânica', 3, '<p>Cenoura orgânica de alta qualidade.</p>', 'cenoura.jpg', 5.00, 'N', 'S')
ON DUPLICATE KEY UPDATE `id`=`id`;