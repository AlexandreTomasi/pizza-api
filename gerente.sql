-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 03-Ago-2017 às 14:23
-- Versão do servidor: 5.5.39
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `skybots_gerencia`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `gerente`
--

CREATE TABLE IF NOT EXISTS `gerente` (
`codigo_gerente` bigint(20) NOT NULL,
  `cpf_gerente` varchar(11) NOT NULL,
  `id_facebook_gerente` varchar(400) NOT NULL,
  `nome_gerente` varchar(400) NOT NULL,
  `email_gerente` varchar(400) NOT NULL,
  `senha_gerente` varchar(400) NOT NULL,
  `telefone_gerente` varchar(11) DEFAULT NULL,
  `endereco_gerente` varchar(400) DEFAULT NULL,
  `cep_gerente` varchar(8) DEFAULT NULL,
  `ativo_gerente` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0 - não, 1 - sim'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `gerente`
--

INSERT INTO `gerente` (`codigo_gerente`, `cpf_gerente`, `id_facebook_gerente`, `nome_gerente`, `email_gerente`, `senha_gerente`, `telefone_gerente`, `endereco_gerente`, `cep_gerente`, `ativo_gerente`) VALUES
(1, '03323458150', '58a3030fe4b0bd0cca6dfb54', 'Alexandre Tomasi', 'botsky.automacao@gmail.com', '7b06b3210a1908af587ae41a9db59c9f', '06592893683', 'brasil cuiaba cpa2', '78055508', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gerente`
--
ALTER TABLE `gerente`
 ADD PRIMARY KEY (`codigo_gerente`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gerente`
--
ALTER TABLE `gerente`
MODIFY `codigo_gerente` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
