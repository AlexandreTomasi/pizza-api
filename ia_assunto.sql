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
-- Estrutura da tabela `ia_assunto`
--

CREATE TABLE `ia_assunto` (
  `codigo_assunto` bigint(20) NOT NULL,
  `descricao_assunto` varchar(200) NOT NULL,
  `codigo_gerente` bigint(20) NOT NULL,
  `ativo_assunto` int(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `ia_assunto`
--

INSERT INTO `ia_assunto` (`codigo_assunto`, `descricao_assunto`, `codigo_gerente`, `ativo_assunto`) VALUES
(1, 'saudação', 1, 1),
(2, 'pedir pizza', 1, 1),
(3, 'solicitar tamanhos', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ia_assunto`
--
ALTER TABLE `ia_assunto`
  ADD PRIMARY KEY (`codigo_assunto`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ia_assunto`
--
ALTER TABLE `ia_assunto`
  MODIFY `codigo_assunto` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
