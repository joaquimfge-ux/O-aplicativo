-- =====================================================================
-- ESPECIFICAÇÃO DO SISTEMA - DISPOSITIVOS DE ARMAZENAMENTO PERSONALIZADOS
-- Script DDL de Criação do Banco de Dados e Tabelas (MySQL)
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `dispositivos_db`
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE `dispositivos_db`;

-- Desabilita checagem de chaves para drops limpos caso necessário
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `personalizacao`;
DROP TABLE IF EXISTS `pedido`;
DROP TABLE IF EXISTS `dispositivo`;
DROP TABLE IF EXISTS `capacidade`;
DROP TABLE IF EXISTS `formato`;
DROP TABLE IF EXISTS `usuario`;

SET FOREIGN_KEY_CHECKS = 1;

-- 1. TABELA USUARIO (RF01, RF02, RF03, RF24, RF25, RF26, RF27)
CREATE TABLE `usuario` (
    `id_usuario` INT AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(150) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `telefone` VARCHAR(30) NULL,
    `senha` VARCHAR(255) NOT NULL,
    `tipo_usuario` ENUM('cliente', 'funcionario', 'administrador') NOT NULL DEFAULT 'cliente',
    `ativo` TINYINT(1) NOT NULL DEFAULT 1,
    `criado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. TABELA FORMATO (RF04, RF05, DER: FORMATO 1:N DISPOSITIVO)
CREATE TABLE `formato` (
    `id_formato` INT AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(100) NOT NULL,
    `descricao` TEXT NULL,
    `icone` VARCHAR(50) DEFAULT 'bi-hdd'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. TABELA DISPOSITIVO / PRODUTO (JOGOS) (RF04, RF05, RF20, RF21, RF22, RF23)
CREATE TABLE `dispositivo` (
    `id_dispositivo` INT AUTO_INCREMENT PRIMARY KEY,
    `id_formato` INT NOT NULL,
    `nome` VARCHAR(150) NOT NULL,
    `genero` VARCHAR(100) DEFAULT 'Aventura',
    `plataforma` VARCHAR(100) DEFAULT 'Super Nintendo (SNES)',
    `desenvolvedora` VARCHAR(100) NULL,
    `ano_lancamento` INT NULL,
    `descricao` TEXT NULL,
    `preco` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `imagem` VARCHAR(255) DEFAULT NULL,
    `ativo` TINYINT(1) NOT NULL DEFAULT 1,
    `criado_em` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_dispositivo_formato` 
        FOREIGN KEY (`id_formato`) REFERENCES `formato` (`id_formato`) 
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. TABELA CAPACIDADE (RF06, RF07)
CREATE TABLE `capacidade` (
    `id_capacidade` INT AUTO_INCREMENT PRIMARY KEY,
    `capacidade_gb` INT NOT NULL UNIQUE,
    `preco_adicional` DECIMAL(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. TABELA PEDIDO (RF12, RF13, RF14, RF15, RF16, RF17, RF18, RF19)
CREATE TABLE `pedido` (
    `id_pedido` INT AUTO_INCREMENT PRIMARY KEY,
    `id_usuario` INT NOT NULL,
    `id_dispositivo` INT NOT NULL,
    `id_capacidade` INT NOT NULL,
    `data_pedido` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `status` VARCHAR(50) NOT NULL DEFAULT 'Pendente',
    `valor_total` DECIMAL(10,2) NOT NULL,
    CONSTRAINT `fk_pedido_usuario` 
        FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_pedido_dispositivo` 
        FOREIGN KEY (`id_dispositivo`) REFERENCES `dispositivo` (`id_dispositivo`) 
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_pedido_capacidade` 
        FOREIGN KEY (`id_capacidade`) REFERENCES `capacidade` (`id_capacidade`) 
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. TABELA PERSONALIZACAO (RF08, RF09, RF10, RF11, DER: PEDIDO 0:1 PERSONALIZACAO)
CREATE TABLE `personalizacao` (
    `id_personalizacao` INT AUTO_INCREMENT PRIMARY KEY,
    `id_pedido` INT NOT NULL UNIQUE,
    `descricao` TEXT NOT NULL,
    `status` VARCHAR(50) NOT NULL DEFAULT 'Em Análise',
    `tipo_conteudo` VARCHAR(100) DEFAULT NULL,
    `texto_gravacao` VARCHAR(150) DEFAULT NULL,
    `cor_acabamento` VARCHAR(50) DEFAULT NULL,
    CONSTRAINT `fk_personalizacao_pedido` 
        FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`) 
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
