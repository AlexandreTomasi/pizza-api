-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 06-Ago-2017 às 05:34
-- Versão do servidor: 10.1.25-MariaDB
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skybots_gerencia`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `ia_palavras_chave`
--

CREATE TABLE `ia_palavras_chave` (
  `codigo_palavra` bigint(20) NOT NULL,
  `nome_palavra` varchar(200) NOT NULL,
  `banco_associado` varchar(200) DEFAULT NULL,
  `codigo_gerente` bigint(20) NOT NULL,
  `resposta_chave` varchar(400) DEFAULT NULL COMMENT 'resposta da palavra chave',
  `ativo_palavra` int(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `ia_palavras_chave`
--

INSERT INTO `ia_palavras_chave` (`codigo_palavra`, `nome_palavra`, `banco_associado`, `codigo_gerente`, `resposta_chave`, `ativo_palavra`) VALUES
(5, 'tamanho', '@tamanhos', 1, '@tamanhos', 1),
(6, 'sabores', '@sabores', 1, 'tenho vários sabores', 1),
(8, 'tamanhos', NULL, 1, '@tamanhos', 1),
(10, 'teste2', NULL, 1, 'pppppp', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ia_palavras_chave`
--
ALTER TABLE `ia_palavras_chave`
  ADD PRIMARY KEY (`codigo_palavra`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ia_palavras_chave`
--
ALTER TABLE `ia_palavras_chave`
  MODIFY `codigo_palavra` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
