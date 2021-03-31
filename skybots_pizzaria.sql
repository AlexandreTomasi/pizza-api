-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: 20-Set-2018 às 22:46
-- Versão do servidor: 5.5.60-cll
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skybots_pizzaria`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`skybots`@`localhost` PROCEDURE `procedure_retorna_valor_configuracao` (IN `codigo_pizzaria` BIGINT(20), IN `descricao_configuracao` VARCHAR(400) CHARSET utf8)  NO SQL
BEGIN 
select distinct vc.descricao_valor_configuracao 
from skybots_pizzaria.pizzaria p 
inner join skybots_gerencia.cliente c on p.cnpj_pizzaria = c.cnpj_cliente 
inner join skybots_gerencia.valor_configuracao vc on vc.cliente_valor_configuracao = c.codigo_cliente 
inner join skybots_gerencia.configuracao conf on conf.codigo_configuracao = vc.configuracao_valor_configuracao 
where p.codigo_pizzaria = codigo_pizzaria and conf.descricao_configuracao = descricao_configuracao; 
end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `bebida`
--

CREATE TABLE `bebida` (
  `codigo_bebida` bigint(20) NOT NULL,
  `descricao_bebida` varchar(400) NOT NULL,
  `preco_bebida` decimal(10,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `pizzaria_bebida` bigint(20) NOT NULL,
  `ativo_bebida` tinyint(4) UNSIGNED NOT NULL DEFAULT '1' COMMENT '0 - nao ativo, 1 - sim ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `bebida`
--

INSERT INTO `bebida` (`codigo_bebida`, `descricao_bebida`, `preco_bebida`, `pizzaria_bebida`, `ativo_bebida`) VALUES
(1, 'Coca-cola (1,5 litros)', '5.50', 1, 2),
(2, 'Fanta Laranja (1,5 Litros)', '7.00', 1, 0),
(3, 'Guaraná Antarctica (1,5 Litros)', '7.00', 1, 0),
(4, 'Simba (2 litros)', '5.00', 1, 1),
(5, 'Coca Cola (2 litros)', '8.00', 1, 1),
(6, 'smirnoff ice', '10.00', 1, 2),
(7, 'Kubaina', '8.00', 1, 2),
(8, 'Fanta uva', '7.00', 1, 2),
(9, 'Pepsi', '8.00', 1, 2),
(10, 'tete', '10.00', 1, 2),
(11, 'Água mineral', '2.45', 1, 2),
(12, 'pepsi 2l', '7.00', 1, 2),
(13, 'Caipirinha', '10.00', 1, 2),
(14, 'Coca-cola 1,5L', '8.00', 2, 1),
(15, 'Fanta 1,5L', '8.00', 2, 1),
(16, 'Kuat 1,5L', '8.00', 2, 1),
(17, 'Sprite 1,5L', '8.00', 2, 1),
(18, 'Suco del Valle lata Uva', '5.00', 2, 1),
(19, 'Suco del Valle lata Pessego', '5.00', 2, 1),
(20, 'Suco del Valle 1 litro maracujá', '9.00', 2, 1),
(21, 'Suco del Valle 1 litro Goiaba', '9.00', 2, 1),
(22, 'aa', '5.00', 1, 2),
(23, 'a3030', '2.00', 1, 2),
(24, 'Coca-cola (1,5 litros)', '7.00', 4, 1),
(25, 'jesus', '10.00', 1, 1),
(26, 'Sprite 1,5L', '6.00', 1, 1),
(27, 'Pepsi 2L', '8.00', 1, 1),
(28, 'Sprite 2L', '8.00', 1, 1),
(29, 'Pepsi 1,5L', '7.00', 1, 1),
(30, 'Kuat 2L', '8.00', 1, 1),
(31, 'Kuat 1,5L', '7.00', 1, 1),
(32, 'Pepsi (1,5 litros)', '7.00', 4, 1),
(33, 'Fanta (1,5 litros)', '7.00', 4, 1),
(34, 'Guarana Antart 1,5L', '7.00', 4, 1),
(35, 'Sprite (1,5 litros)', '7.00', 4, 1),
(36, 'Valle Pêssego 1,5L', '7.00', 4, 1),
(37, 'Coca-cola 2litros', '10.00', 4, 1),
(38, 'Guaraná Jesus 2l', '5.00', 4, 2);

--
-- Acionadores `bebida`
--
DELIMITER $$
CREATE TRIGGER `trigger_validacao_bebida_bi` BEFORE INSERT ON `bebida` FOR EACH ROW begin
	if (new.descricao_bebida is null or new.descricao_bebida = '') then
		signal sqlstate '45000' set message_text = 'DESCRIÇÃO não pode ser vazio/nulo.';
	end if;
	if (new.preco_bebida is null or new.preco_bebida < 0 ) then
		signal sqlstate '45000' set message_text = 'PREÇO deve ser maior que 0.';
	end if;
    if (new.pizzaria_bebida is null or new.pizzaria_bebida < 0 ) then
		signal sqlstate '45000' set message_text = 'PIZZARIA não pode ser vazio/nulo.';
	end if;
    if(new.ativo_bebida is null or new.ativo_bebida < 0) then
		signal sqlstate '45000' set message_text = 'ATIVO não pode ser vazio/nulo.';
	else if (new.ativo_bebida not in (0,1)) then
			signal sqlstate '45000' set message_text = 'ATIVO inválido.';
        end if;
	end if;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trigger_validacao_bebida_bu` BEFORE UPDATE ON `bebida` FOR EACH ROW begin
	if (new.descricao_bebida is null or new.descricao_bebida = '') then
		signal sqlstate '45000' set message_text = 'DESCRIÇÃO não pode ser vazio/nulo.';
	end if;
	if (new.preco_bebida is null or new.preco_bebida < 0 ) then
		signal sqlstate '45000' set message_text = 'PREÇO deve ser maior que 0.';
	end if;
    if (new.pizzaria_bebida is null or new.pizzaria_bebida < 0 ) then
		signal sqlstate '45000' set message_text = 'PIZZARIA não pode ser vazio/nulo.';
	end if;
     if(new.ativo_bebida is null or new.ativo_bebida < 0) then
		signal sqlstate '45000' set message_text = 'ATIVO não pode ser vazio/nulo.';
	else if (new.ativo_bebida not in (0,1,2)) then
			signal sqlstate '45000' set message_text = 'ATIVO inválido.';
        end if;
	end if;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `ci_sessions`
--
-- --------------------------------------------------------

--
-- Estrutura da tabela `cliente_pizzaria`
--

CREATE TABLE `cliente_pizzaria` (
  `codigo_cliente_pizzaria` bigint(20) NOT NULL,
  `cpf_cliente_pizzaria` varchar(11) DEFAULT NULL,
  `nome_cliente_pizzaria` varchar(400) NOT NULL,
  `email_cliente_pizzaria` varchar(400) DEFAULT NULL,
  `id_facebook_cliente_pizzaria` varchar(400) NOT NULL,
  `telefone_cliente_pizzaria` varchar(11) DEFAULT NULL,
  `cep_cliente_pizzaria` varchar(10) DEFAULT NULL,
  `endereco_cliente_pizzaria` varchar(400) DEFAULT NULL,
  `complemento_endereco_cliente_pizzaria` varchar(400) DEFAULT NULL,
  `cidade_cliente_pizzaria` bigint(20) DEFAULT NULL,
  `uf_cliente_pizzaria` varchar(2) DEFAULT NULL COMMENT 'MT - Mato Grosso, MS - Mato Grosso do Sul, DF - Distrito Federal, GO - Goiânia...',
  `sexo_cliente_pizzaria` varchar(1) DEFAULT NULL COMMENT 'F - Feminino, M - Masculino',
  `referencia_endereco_cliente_pizzaria` varchar(400) DEFAULT NULL,
  `pizzaria_cliente_pizzaria` bigint(20) NOT NULL,
  `bairro_cliente_pizzaria` bigint(20) DEFAULT NULL,
  `ativo_cliente_pizzaria` tinyint(4) UNSIGNED NOT NULL DEFAULT '1' COMMENT '0 - nao ativo, 1 - sim ativo, 2 - cadastro temporario	'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `cliente_pizzaria`
--

INSERT INTO `cliente_pizzaria` (`codigo_cliente_pizzaria`, `cpf_cliente_pizzaria`, `nome_cliente_pizzaria`, `email_cliente_pizzaria`, `id_facebook_cliente_pizzaria`, `telefone_cliente_pizzaria`, `cep_cliente_pizzaria`, `endereco_cliente_pizzaria`, `complemento_endereco_cliente_pizzaria`, `cidade_cliente_pizzaria`, `uf_cliente_pizzaria`, `sexo_cliente_pizzaria`, `referencia_endereco_cliente_pizzaria`, `pizzaria_cliente_pizzaria`, `bairro_cliente_pizzaria`, `ativo_cliente_pizzaria`) VALUES
(27, NULL, 'Alexandre Tomasi', NULL, '58a3030fe4b0bd0cca6dfb549', '06599221746', '78050-690', 'Casa verde', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.584426%252C%2B-56.058936%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOjfAWpHER7GsAhjt-oCPfBgIf5lZGzR7gFTFjuhDvwZ1pF3jZeSHETtGE8JR3u01D0TSDp8hsroK-6xI7af3ciSTvRQ_RMzt8EOIVKoqBw0xAH_A&s=1&enc=AZPMrFMBIsVDnWGsHqeThgxlYTB7gAC37imMcgIVMhg7zOwYI89Bs8vLAJduKHawti59klmajDtArxPuCPD1w2Ct', 1, 'MT', 'M', '', 1, 11, 1),
(28, NULL, 'Gustavo Lima Franco', NULL, '1490689944346699', '65999898645', '78010-500', 'Av Miguel Sutil, 3271, Poção', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3DAv.%2BMiguel%2BSutil%252C%2B3271%252C%2B78015%2BCuiab%25C3%25A1%252C%2BBrazil%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATPvKlKMNbjCF_LUO3RbQd6Wbpvh1NKF-VTW7q0F3MedR0LgGWTjmv-14lKxsXfqyd1jl6yGsADj2ZnvTQW9Z3djjII44nlCiTO9-7SWntVaWBBe3Q&s=1&enc=AZPCvkNBJj0aAQOV5U9kftjg2x_mho_2P0_PWbxkm', 1, 'MT', 'M', '', 2, 89, 1),
(29, NULL, 'Sayuri Arake Joazeiro', NULL, '58a50bfee4b07cefc8a75eb9', '85478569', '78056606', 'rua maria maria, 11, cpa 4', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5625033%252C%2B-56.0321415%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNJmz5xmiv_uGuj3in_5A4KSJwkDw7w6rXlfFo7JK4D1g_vhhlDYl3URORpnSpaLp6zjc1qCCQVXGGmikzNZvf7h2Ms8bdz6Lim_cKJbMSJ6PF0sg&s=1&enc=AZMszkNQAO2Ystfou7qfu9fgi8NTcigQKc-JYofzU1_NIJMqR5lSPA2wF8kbS1jtXF-pzHeVGfPeb-B5TT1ctxdf', 1, 'MT', 'F', '', 1, 3, 1),
(30, NULL, 'Nay Frota', NULL, '1502173136543223', '992162935', '78050-700', 'Rua Ciro furtado Sodré número 223', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3DRua%2BCiro%2BFurtado%2BSodr%25C3%25A9%252C%2B78.050-625%2BCuiab%25C3%25A1%252C%2BBrazil%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATPMPUG1W2cj9SFggwOUYF4-X9XGzpui3fHz-kRol85T9F5t9hWx1Dlp1y89RoUcfFKWT1sBXNU3Q8vY-U8nMjC48vP59zIMoYSn5AvuBAgNyx71pQ&s=1&enc=AZOgDL-QcwqXJhHyyZiTqiL6Q5RZba3', 1, 'MT', 'F', '', 2, 64, 1),
(31, NULL, 'Mariana Rosendo Junior Fonseca', NULL, '1499169966831810', '984075859', '78045-310', 'Residencial vila bela', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.573254470082%252C%2B-56.096228060727%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATO4KYbCN2J6hUVxzlWlEoa51SJRWwfEdXoStomcq4hYHK44jRtv8K-TOp_xoAKcSRON-BWlUSMDNYdSIr5Mg1s5q-y2VohNPmOzFR3J1HGgGi1cug&s=1&enc=AZOBhSEy4A_Nw0TGQROj7guy4WH0G7HNn99bTkrQxK1Rc2T-EdCDVQokmjzlEHHLPNMyPBQjijPWDk', 1, 'MT', 'F', '', 2, 12, 1),
(32, NULL, 'Rozivany Monteiro', NULL, '1628151927245750', '992451000', '78050-690', 'Rua ciriaco candia 77 carumbé', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.586538%252C%2B-56.059167%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOGFOQgEg1QPUBpRo5d8Uo_KAN4q5u0L5MrildYDXRLkjM9nr5LrZK9vopM3usM8l6QGlq15_OZa91e1vEzPckPq9slNECURno-1qnnwfTRhMF9vg&s=1&enc=AZMPJgFMhfL5yEN1wgD91TP7ZYEYsdilIYd439dFj8nCAnT0-dVEBA7qvlIaxuAt7yTqsNZcrW1vnlhlnD3Xjyly', 1, 'MT', 'F', '', 2, 64, 1),
(33, NULL, 'Alexandre Tomasi', NULL, '58a26f08e4b0bd0cc837d013', '46456456456', '78056606', 'kkk', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626391%252C%2B-56.0510676%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOniyhAhkoBe0St6TIn4T11M0lpdQUrsgRToFPFFbJ073WnhnkJRD-C9JCJwr9MskCvdlquGEGzEAxsYaP92n0AJTRAEomfxGAcgSmJcGmYvRLrEg&s=1&enc=AZNSrb1UbMqnUr0gEAKqluIX78JZSjA38OC55Kxg_W3WT-5uHbl0-uhBte6x4bRexw61JO97_hGCTWk4cX5bf825', 1, 'MT', 'M', '', 1, 51, 1),
(34, NULL, 'Willian Ajala', NULL, '1711679722236419', '99691-3884', '78056606', 'Rua 66 quadra 94 casa 11 cpa 4 3° etapa', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.562526%252C%2B-56.03211%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNfX7QZWEFRsyBMT-NxwjKRKc4E7Ts97Yy2bGbR-nHjEpZAySnjGMPYrm27DIAUs_YJ0vrdgtVEKnaiXsy16a1i1f-j7ABLPnjFddYvWUzuZI5fTQ&s=1&enc=AZPRfRIe67K1lQTS6JWHMJqi2GfldMjf85zJ7l4JBp5CLYDXHWTH_1JcRoNuEHbNXi5S7nvcAKHHCqKVHYZDRXyK', 1, 'MT', 'M', '', 2, 3, 1),
(35, NULL, 'Alexandre Tomasi teste', NULL, '15913319409388079', '24345345345', '78049909', 'teste', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.572845%252C%2B-56.076049%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP9c256SX8fNX0RKC68TRotrV4wwf6PZVju9obEDTH5x3XTwPonbZYyQpB8dfROtEFGgzWvdw3sGfMxJb6ncpW-C0891AMTgwKwU-0cMPiWeCIcFg&s=1&enc=AZMULv8QbGp_n_2E3zruG95yA9VLdsmwZWE3kd8VcWrCpaZ31q0SOgJNPaXRNxoG4rh1DW0pJy5gAP11VgUs6Nxc', 1, 'MT', 'M', '', 1, 3, 1),
(36, NULL, 'Alexandre Tomasi', NULL, '1591331940938807', '56756756756', '78049909', 'teste', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.572845%252C%2B-56.076049%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP9c256SX8fNX0RKC68TRotrV4wwf6PZVju9obEDTH5x3XTwPonbZYyQpB8dfROtEFGgzWvdw3sGfMxJb6ncpW-C0891AMTgwKwU-0cMPiWeCIcFg&s=1&enc=AZMULv8QbGp_n_2E3zruG95yA9VLdsmwZWE3kd8VcWrCpaZ31q0SOgJNPaXRNxoG4rh1DW0pJy5gAP11VgUs6Nxc', 1, 'MT', 'M', '', 1, 3, 1),
(37, NULL, 'Alexandre Tomasi', NULL, '58a3030fe4b0bd0cca6dfb54', '5677567667', '78050-923', 'Hhh', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.573008%252C%2B-56.072952%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOjXCyPklbEl3slyHbYZmG17H0bAHA0pXcEqDRBUPluXp7Uf4wRSnRq5CL_xHBDSDzK_tyB4DQJDxIgcagMo0hO1PlKyEWNDbNhZS-B485BuzjFgg&s=1&enc=AZOIayQTuvVAIMm10aKzPNHcX70xLSGZNGDbvwVIsdbWaVX3-guyl1toKaPu-4qIIe9s-cTQkU3XHv8Wr7ugwuZC', 1, 'MT', 'M', '', 1, 104, 1),
(38, NULL, 'Patrícia Nunes', NULL, '1687792654585975', '65984215292', '78056606', 'Residencial São Carlos, bloco 46, APT 403... Preciso que me ligue quando estiver no portão, pois não tem como eu ver. 65984215292', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.584494%252C%2B-56.051653%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNiPgwDRVkh6IslwMHiXX8uXctnaIH7EpcE0QVz1XziCMU3ZuTGWqEz3C3qHEXbcPfQZLDx8fJ6knyUNCqWk1eyGy-ZkDFS94Z02hKzCMCf2X74xg&s=1&enc=AZMjEs8jBbpl4LnQSNZJ0VnQrIV0bGWIv2R9RLTRgguTYMzq3WPsHX7XeGEduYq-13oIMxed6T9EodGgoYHMMy3z', 1, 'MT', 'F', '', 2, 95, 1),
(39, NULL, 'Sidiane Torres', NULL, '1293634120741378', '999277870', '78049-901', 'Rua Omã bairro alvorada , apartamento 204 bloco 41', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.571769160274%252C%2B-56.084480319821%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATMWm5HJQHO7etg2P1QfyOeYVmA3LywsdyTKefOvY7zFwFO-s3pGRQbwZM9UzxDqDnSkyJdkNNDYefwBcnIjRcg79Xl3WUCVjFJI2mWRzTgE3U_M5Q&s=1&enc=AZNjiDgU7xDGToe37Rl20oHcM54NI2m_3ml-JNMIWqdqI2yoqPD3b-zdRKaVu1boJKlm_MC_BDfsly', 1, 'MT', 'F', '', 2, 4, 1),
(40, NULL, 'Alexandre Tomasi', NULL, '1533371983372255', '45345343544', '78000000', 'casa verde', '', 1, 'MT', 'M', '', 2, 1, 1),
(41, NULL, 'Alexandre Tomasi', NULL, '1576473215765981', '34534534543', '78056606', 'casa verde', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626317%252C%2B-56.0510567%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATMyzIqbNc8dIl2nCGHkjneQRRt8hJTXhEsgI8FOSX0Lumgh4IcigJqbRyO_5eXZ5PmvKx06L14lAtF9zfwd9j8AD4793C0xaU4Qeg5y2T8MqDs72A&s=1&enc=AZNfGd-PaNWLz6gFsdDDttbXq3_-AXL-cciukazixlB1tMGtU-UfArj-GxQGLj3HQZN7q3WquUYH7jCb9cyopWqL', 1, 'MT', 'M', '', 4, 1, 1),
(42, NULL, 'Sayuri Arake Joazeiro', NULL, '1358692287575214', '77788909789', '78000000', 'Ying iso', '', 1, 'MT', 'F', '', 4, 3, 1),
(43, NULL, 'Alexandre Tomasi', NULL, '1576473215765981', '32453543534', '78056606', 'casa amarela', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 1, 'MT', 'M', '', 1, 4, 1),
(44, NULL, 'Alexandre Tomasi', NULL, '1611155025618881', '3845858484', '78050-923', 'Casa.mmarela', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.573003%252C%2B-56.072965%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOOkjdBIuOJKjyv78n6GmBs1LCXuj4nKbzBEmorM262LFukj0pnKi1RFsXhknaiN6BE4ajGgiFfPALfvaTUxK9S2obWWtEybK8ZOkE0Z-aDjwj3Bg&s=1&enc=AZPMtfiqMVL9OKUwvT6yvmec1xyuQwm_nO0Z5_V4LTwMLLcCCBUl_jNzItRYAILUUghUS3_Rm8sdWMIhGMxJu6wH', 1, 'MT', 'M', '', 1, 4, 1),
(45, NULL, 'Sayuri Arake Joazeiro', NULL, '1358692287575214', '54854852365', '78000000', 'rua jga lua sodyd dpfuwbns apasha', '', 1, 'MT', 'F', '', 1, 3, 1),
(46, NULL, 'Sayuri Arake Joazeiro', NULL, '1460334934002662', '36491548', '78056606', 'Rua 66, quadra 94, número 11, CPA 4, 3° etapa', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.562526%252C%2B-56.032189%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNtULgLNuV-JvlcCnwcDXnGcAFqiHdttJODAdI-g8kXHsD4SSPooMxU3hzxKKn3PlTMIZP_D39mzeEnkFSQNZncR3Yuf9g5rxJOwfhnR1J2fBgfJw&s=1&enc=AZOOqsmERlCOxdT-XyP9Jg6PtBBqUMlFbkmmouW48zX25UUVwGxwVxCTY2dTo9H2FAy3BMw07iIx0LfwS27_GrC7', 1, 'MT', 'F', '', 2, 3, 1),
(47, NULL, 'Alinne Barrozo', NULL, '1551267801625829', '992288285', '78056606', 'Rua 55, quadra 30, casa 06, cpa 3, setor 3', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.577965%252C%2B-56.040278%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATMWqb9AVAsOZ7qjeVN4ioDFLahR03EAfB98JkzMDpEDa4bmHfydSPHWhm5PH-kmc6GbgdgldnZhuTU43BJ2XakaF5BbWK7EH4_L10t8GTfxvScIng&s=1&enc=AZNAYoQ-2AfWp9XR07K_UBe_2Ok4VjkI1HZGaYY8goBCWD94gXeMoMBTl5iNHfdIcdmKmOFPZq7FWcIR_EPwBhRz', 1, 'MT', 'F', '', 2, 141, 1),
(48, NULL, 'Gustavo Lima Franco', NULL, '1576981702389470', '65999898645', '78056606', 'Mti', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.566754%252C%2B-56.075505%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATPMDSIESNtCff6Mufearv0RuPmJ_cddPqhcOUItYAC3RFLM-l2rdLWe9AfIAR84JTUCx68fc7u5N1Srog-BBgvPvdCwJQ_yxoj_KtS4uZH2yY7dew&s=1&enc=AZOzMh3La5vKizBeA-XIL2CcI6lBPdvjppOb2HJ9RE3f82890CRa9uXl4mrCPevt-OjMmRUpWx13DUpxg3gOoITZ', 1, 'MT', 'M', '', 1, 3, 1),
(49, NULL, 'Bruna Ferreira', NULL, '1542201259203641', '992347058', '78000000', 'Rua M Quadra 14 Número 30 Bairro Sol nascente', '', 1, 'MT', 'F', '', 2, 98, 1),
(50, NULL, 'Marcela Silvério', NULL, '1677442845610219', '992537026', '78000000', 'Rua M, Quadra 67, casa 01', '', 1, 'MT', 'F', '', 1, 152, 1),
(51, NULL, 'Alexandre Tomasi', NULL, '1466988790061939', '65992217482', '78056606', 'Casa verde', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5625627%252C%2B-56.0510588%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOYRESBdVcKmaNeabSkqjVQC0kHVgOBBrfCSVnRoiJgSwidQTU1dDRxtViji-ThQsvbX9PgfA3RAZyvL97PBC4tbVnDCnrRBoLzpljsbvQWehOdmA&s=1&enc=AZM-XJ7LOR0qbckZcE3NIsJHBmOhkWSJoerNmKjDu1EvbSS4bjr3G_iyZCe8sjBQWZtgrBuJA2QSK1l8KJuiIzjZ', 1, 'MT', 'M', '', 1, 4, 1),
(52, NULL, 'Sayuri Arake Joazeiro', NULL, '1637760156254794', '737387383', '78056606', 'Já foram é aquele auakd flabudmd', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.582876%252C%2B-56.04322%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNdQx-uF-Pu15qEnTe0Rf1NdUf_rL2-BA_ggdAeBAS7pbq_3D0i0_42FoxY6gFJEYPyt6RxYN-g4IgJAoR7aG1Tx8RrOV14a1fG-HM9U8SRVRcLyw&s=1&enc=AZMfLfdX_7l8Zkd5ls39xSpbYxc35ecoN6GgNuoBMCW4WwXlAZUik1AvuTl-YHa7hz1qOC-xqJbSA09s3HYZiNUy', 1, 'MT', 'F', '', 1, 3, 1),
(53, NULL, 'Alexandre Tomasi', NULL, '1611155025618881', '06698765432', '78056606', 'Cada verde', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 1, 'MT', 'M', '', 4, 4, 1),
(54, NULL, 'Gustavo Prado', NULL, '662950600495519', '65981182065', '78056606', 'Rua gravateiro, 11, quadra 94, CPA 4, 3 etapa', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.562437%252C%2B-56.031772%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNd4cSYesygacnOyf9GlMrVxEgGLPZvhXomeqscZmjuWTNwq1YDh5pf4Enk05ocfCXrHyqBZNxGNUC4JDJmgP_XP3RLXX44J84T2ZHkHX52lvZCvV_r&s=1&enc=AZNWi855l1yp7nkAPEHsBr2iKyBuA8gZ6EvLRtrWiD5L1TT78W4x3ELqoD-4UAR4LSHZGzY22-gVB3TPZBs1RhxO', 1, 'MT', 'M', '', 2, 3, 1),
(55, NULL, 'Alexandre Tomasi', NULL, '1466988790061939', '65992217488', '78056606', 'Esse pedido é apenas um teste. DESCONSIDERAR', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5625627%252C%2B-56.0510588%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOYRESBdVcKmaNeabSkqjVQC0kHVgOBBrfCSVnRoiJgSwidQTU1dDRxtViji-ThQsvbX9PgfA3RAZyvL97PBC4tbVnDCnrRBoLzpljsbvQWehOdmA&s=1&enc=AZM-XJ7LOR0qbckZcE3NIsJHBmOhkWSJoerNmKjDu1EvbSS4bjr3G_iyZCe8sjBQWZtgrBuJA2QSK1l8KJuiIzjZ', 1, 'MT', 'M', '', 2, 4, 1),
(56, NULL, 'Cleide Franco', NULL, '1594872000598389', '999816963', '78000000', 'Av.Miguel Sutil n.3.271 Cleide Imóveis,  esquina Rua Amarilio de Almeida', '', 1, 'MT', 'F', '', 4, 89, 1),
(57, NULL, 'David Lucas', NULL, '1572214076208027', '996153035', '78000000', 'Avenida das palmeiras, s/n; condominio rio coxipo, casa 274, jd imperial, cuiaba', '', 1, 'MT', 'M', '', 2, 73, 1),
(58, NULL, 'Thamires Ramos', NULL, '1768169543214116', '06599273413', '78000000', 'Rua 13 quadra 15 casa 337', '', 1, 'MT', 'F', '', 2, 71, 1),
(59, NULL, 'Alexandre Tomasi', NULL, '1466988790061939', '66987698765', '78056606', 'Casa verde', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5625627%252C%2B-56.0510588%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOYRESBdVcKmaNeabSkqjVQC0kHVgOBBrfCSVnRoiJgSwidQTU1dDRxtViji-ThQsvbX9PgfA3RAZyvL97PBC4tbVnDCnrRBoLzpljsbvQWehOdmA&s=1&enc=AZM-XJ7LOR0qbckZcE3NIsJHBmOhkWSJoerNmKjDu1EvbSS4bjr3G_iyZCe8sjBQWZtgrBuJA2QSK1l8KJuiIzjZ', 1, 'MT', 'M', '', 4, 4, 1),
(60, NULL, 'Michel Girato', NULL, '1667072530038757', '659962-8896', '78000000', 'Rua: i Número 65 bloco 3 apartamento 51', '', 1, 'MT', 'M', '', 2, 99, 1),
(61, NULL, 'Thiago Corrêa de Oliveira', NULL, '1690910417652640', '98113-3794', '78000000', 'Av. Miguel Sutil, 6322 - edifício Villaggio Di Bonifácia, torre 2, apartamento 201', '', 1, 'MT', 'M', '', 2, 30, 1),
(63, NULL, 'Alexandre Tomasi', NULL, '1379735658798428', '65992893683', '78000000', 'Isso é apenas um teste de promoção', '', 1, 'MT', 'M', '', 2, 140, 1),
(64, NULL, 'Helder Barbosa Maciel', NULL, '1765066076886152', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(65, NULL, 'Joice Sampaio', NULL, '1840736429301371', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(66, NULL, 'Nagely Santos', NULL, '1951816848224912', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(67, NULL, 'Halimah Medina', NULL, '1900408476700838', '33652608', '78000000', 'Rua 235 quadra 77 casa 58 tijucal setor 2 Cuiabá', '', 1, 'MT', 'F', '', 2, 136, 1),
(68, NULL, 'Karen Oliveira', NULL, '1815292948492255', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(69, NULL, 'Gustavo Prado', NULL, '1896060633741693', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 2),
(70, NULL, 'Gustavo Prado', NULL, '1896060633741693', '65981150626', '78000000', 'rua 66, quadra 94, n11, cpa 4, 3 etapa', '', 1, 'MT', 'M', '', 4, 3, 1),
(71, NULL, 'Daniel Daron', NULL, '1980368462036190', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(72, NULL, 'Milkaelly Samara', NULL, '1635459993176542', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(73, NULL, 'Grazi Raitz', NULL, '2170677566277571', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(74, NULL, 'Géssica Lemes', NULL, '2047747258586596', '996251903', '78000000', 'Rua porto cercado numero 8 cpa 2', '', 1, 'MT', 'F', '', 2, 140, 1),
(75, NULL, 'Geórgia Gomes', NULL, '2368438239836847', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(76, NULL, 'Fernandinha Costa', NULL, '1824292697617574', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(77, NULL, 'Paola Emanuelly', NULL, '1643790809037665', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(78, NULL, 'Fernando Cavalcante', NULL, '712407568883415', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(79, NULL, 'Ludimily Cruz', NULL, '1690420817712804', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(80, NULL, 'Marcelli Barros', NULL, '1669442979770882', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(81, NULL, 'Mayara Camargo', NULL, '2005819112762238', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(82, NULL, 'Andressa Silva', NULL, '1606051169490727', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(83, NULL, 'Fernanda Rolon', NULL, '1679093225509052', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(84, NULL, 'Nadine Bonelli', NULL, '1422154644580091', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(85, NULL, 'Camyla Silvestre', NULL, '1677997972283540', '06599224529', '78000000', 'Av 22 qd 20', '', 1, 'MT', 'F', '', 2, 149, 1),
(86, NULL, 'Hortencia Moraes', NULL, '1864232663608643', '65 9 998292', '78000000', 'Rua 7 quadra 10   Jardim Vitória  número 323', '', 1, 'MT', 'F', '', 2, 36, 1),
(87, NULL, 'Keila Soares', NULL, '1655157861266781', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(88, NULL, 'Cassia Pereira', NULL, '1550745851718901', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(89, NULL, 'Wagner Gimenes', NULL, '1793265427362667', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(90, NULL, 'Flavia Ramos', NULL, '2095168550524106', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(91, NULL, 'Renata Magalhaes', NULL, '1663909410354652', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(92, NULL, 'Marta Mila Arruda', NULL, '1972375906147017', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(93, NULL, 'Vivian Provenzano', NULL, '1941228939222185', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(94, NULL, 'Thaiene Ramos', NULL, '1771025586289748', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(95, NULL, 'Maritza Ketlyn', NULL, '1493531294091954', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(96, NULL, 'Edilaine X Elias', NULL, '1363661810401849', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(97, NULL, 'Wender Carneiro', NULL, '1818073954974771', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, NULL, 2),
(98, NULL, 'Andréa Carmo', NULL, '2013315478710910', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(99, NULL, 'Elizete Maria', NULL, '1716103878475763', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(100, NULL, 'Angelica Andrade Oliveira', NULL, '1743228985761573', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(101, NULL, 'Ingrid Minas', NULL, '2016998915008784', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, NULL, 2),
(102, NULL, 'Laura Cristina', NULL, '1187179118052521', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(103, NULL, 'Douglas Brasqui', NULL, '1570892816370175', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(104, NULL, 'Andreia Ribeiro', NULL, '1683494255099819', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(105, NULL, 'Thuanny Fernanda', NULL, '1682472295135960', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(106, NULL, 'Eliana Gregoria de Araujo', NULL, '1545563428870481', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(107, NULL, 'Alexandre Tomasi', NULL, '1591331940938807', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, NULL, 2),
(108, NULL, 'Rosa Rondon', NULL, '1581134075265633', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(109, NULL, 'Genilton Quintino', NULL, '1904162766291666', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(110, NULL, 'Alessandra Patrícia Martins', NULL, '1741093755945175', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(111, NULL, 'Allan Corrêa da Costa', NULL, '1885798654806292', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(112, NULL, 'Cintia Santana', NULL, '1797639190275176', '992908059', '78000000', 'Avenida A Quadra 04 Casa 25 Bairro Nova Canaã 3° etapa', '', 1, 'MT', 'F', '', 2, 144, 1),
(113, NULL, 'Milene Dias', NULL, '1917680998271373', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(114, NULL, 'Thaize Carturan', NULL, '1898465213551029', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(115, NULL, 'Allex Miguel', NULL, '1913732955324928', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(116, NULL, 'Vinicius Figueiredo Sccp', NULL, '1669294939833184', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(117, NULL, 'Marineia Ribeiro', NULL, '1597659126966590', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(118, NULL, 'Ismael da Silva', NULL, '1869595336459272', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(119, NULL, 'Sandra Regina', NULL, '1792478764172730', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(120, NULL, 'Pkn Náná Felipe Silva', NULL, '2090824904323074', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(121, NULL, 'Claudineia Dourado Rodriguês', NULL, '2021864454524447', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(122, NULL, 'Vanessa Lau', NULL, '1317676281602179', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(123, NULL, 'Nayton Batista', NULL, '2086814124725397', '999878425', '78000000', 'Rua 50 Quadra 80 Casa 28 CPA 4 , Segunda Etapa', '', 1, 'MT', 'M', '', 2, 3, 1),
(124, NULL, 'Thalyssa Amorim', NULL, '2190547790960093', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(125, NULL, 'Anderléia Oliveira', NULL, '2014046165293776', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(126, NULL, 'Dayane Dos Santos', NULL, '2157602547602697', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(127, NULL, 'Lorrainy Ferreira', NULL, '1828350187243823', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(128, NULL, 'Gisely Santiago', NULL, '2048821478521667', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(129, NULL, 'Joelcio Azevedo Azevedo', NULL, '1784428481672069', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(130, NULL, 'Mackenzie Nascimento', NULL, '1269776686457404', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(131, NULL, 'Mirian Lemes', NULL, '1694600540666714', '99228-9050', '78000000', 'Avenida Gonçalo Antunes de Barros número 1710 bairro Carumbé ponto de referência oficina do Abel  AB AUTOCENTER telefone 65 99228-9050', '', 1, 'MT', 'F', '', 2, 64, 1),
(132, NULL, 'Gustavo Lima Franco', NULL, '1576981702389470', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, NULL, 2),
(133, NULL, 'Débora SkPo', NULL, '1979090612111916', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(134, NULL, 'Victor Augusto', NULL, '2055423521154931', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(135, NULL, 'Murilo Oliveira', NULL, '2247247651957312', '06599801964', '78000000', 'Rua 16 quadra 28 casa 28 cpa3 setor 5', '', 1, 'MT', 'M', '', 2, 141, 1),
(136, NULL, 'Thais Reis', NULL, '1888237921257809', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(137, NULL, 'Vanessa Sabbadini', NULL, '1884270201608254', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(138, NULL, 'Patricia Gonçalves', NULL, '1501941853240305', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(139, NULL, 'Denise Lemes', NULL, '1671420546300944', '96680894', '78000000', 'Rua do caju 277 Alvorada', '', 1, 'MT', 'F', '', 2, 4, 1),
(140, NULL, 'Leonardo Silqueira', NULL, '1823257901062555', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(141, NULL, 'Glorinha Nascimento', NULL, '1832388340191494', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(142, NULL, 'Phâmela Martins', NULL, '2534549786570190', '65 99220001', '78000000', 'Rua Piracicaba Número 150\nBairro Novo Horizonte', '', 1, 'MT', 'F', '', 2, 84, 1),
(143, NULL, 'Kath Almeida', NULL, '2120482908025709', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(144, NULL, 'JJaniane Araújo', NULL, '1889430341173832', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(145, NULL, 'Larissa Mickaelly', NULL, '1956751804358343', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(146, NULL, 'Adrielle Moraes', NULL, '1742890572504988', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(147, NULL, 'Ilza Amorim', NULL, '2395530057131030', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(148, NULL, 'Ana Paula Pahim Ferreira', NULL, '1852926931428985', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2),
(149, NULL, 'Josiane Correa', NULL, '2315141028514123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 2);

--
-- Acionadores `cliente_pizzaria`
--
DELIMITER $$
CREATE TRIGGER `trigger_validacao_cliente_pizzaria_bi` BEFORE INSERT ON `cliente_pizzaria` FOR EACH ROW begin
	-- Campos not nulls
    if (new.nome_cliente_pizzaria = '' or new.nome_cliente_pizzaria is null) then
		signal sqlstate '45000' set message_text = 'NOME não pode ser vazio/nulo.';
	end if;

    if (new.pizzaria_cliente_pizzaria = '' or new.pizzaria_cliente_pizzaria is null) then
		signal sqlstate '45000' set message_text = 'PIZZARIA não pode ser vazio/nulo.';
	end if;
    IF(NEW.id_facebook_cliente_pizzaria = '' OR NEW.id_facebook_cliente_pizzaria IS NULL) THEN
    	signal sqlstate '45000' set message_text = 'ID FACEBOOK não pode ser vazio/nulo.';
    END IF;
    -- validações de valores
    if((new.cpf_cliente_pizzaria is not null or new.cpf_cliente_pizzaria = '') and skybots_gerencia.function_valida_cpf(new.cpf_cliente_pizzaria) = 0) then
			signal sqlstate '45000' set message_text = 'CPF inválido.';
	end if;
    if((new.cep_cliente_pizzaria is not null or new.cep_cliente_pizzaria = '')  and skybots_gerencia.function_valida_cep(new.cep_cliente_pizzaria) = 0) then
			signal sqlstate '45000' set message_text = 'CEP inválido.';
	end if;
    if((new.email_cliente_pizzaria is not null or new.email_cliente_pizzaria = '')  and skybots_gerencia.function_valida_email(new.email_cliente_pizzaria) = 0) then
		signal sqlstate '45000' set message_text = 'E-MAIL inválido.';		
	END IF;
    if(new.sexo_cliente_pizzaria is not null and new.sexo_cliente_pizzaria not in ('f', 'm')) then
    	signal sqlstate '45000' set message_text = 'SEXO inválido.';		
	END IF;
     if(new.ativo_cliente_pizzaria is null or new.ativo_cliente_pizzaria < 0) then
		signal sqlstate '45000' set message_text = 'ATIVO não pode ser vazio/nulo.';
	else if (new.ativo_cliente_pizzaria not in (0,1,2)) then
			signal sqlstate '45000' set message_text = 'ATIVO inválido.';
        end if;
	end if;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trigger_validacao_cliente_pizzaria_bu` BEFORE UPDATE ON `cliente_pizzaria` FOR EACH ROW begin
	-- Campos not nulls
    if (new.nome_cliente_pizzaria = '' or new.nome_cliente_pizzaria is null) then
		signal sqlstate '45000' set message_text = 'NOME não pode ser vazio/nulo.';
	end if;
    if (new.pizzaria_cliente_pizzaria = '' or new.pizzaria_cliente_pizzaria is null) then
		signal sqlstate '45000' set message_text = 'PIZZARIA não pode ser vazio/nulo.';
	end if;
    IF(NEW.id_facebook_cliente_pizzaria = '' OR NEW.id_facebook_cliente_pizzaria IS NULL) THEN
    	signal sqlstate '45000' set message_text = 'ID FACEBOOK não pode ser vazio/nulo.';
    END IF;
    -- validações de valores
    if((new.cpf_cliente_pizzaria is not null or new.cpf_cliente_pizzaria = '') and skybots_gerencia.function_valida_cpf(new.cpf_cliente_pizzaria) = 0) then
			signal sqlstate '45000' set message_text = 'CPF inválido.';
	end if;
    if((new.cep_cliente_pizzaria is not null or new.cep_cliente_pizzaria = '')  and skybots_gerencia.function_valida_cep(new.cep_cliente_pizzaria) = 0) then
			signal sqlstate '45000' set message_text = 'CEP inválido.';
	end if;
    if((new.email_cliente_pizzaria is not null or new.email_cliente_pizzaria = '')  and skybots_gerencia.function_valida_email(new.email_cliente_pizzaria) = 0) then
		signal sqlstate '45000' set message_text = 'E-MAIL inválido.';		
	END IF;
    if(new.sexo_cliente_pizzaria is not null and new.sexo_cliente_pizzaria not in ('f', 'm')) then
    	signal sqlstate '45000' set message_text = 'SEXO inválido.';		
	END IF;
     if(new.ativo_cliente_pizzaria is null or new.ativo_cliente_pizzaria < 0) then
		signal sqlstate '45000' set message_text = 'ATIVO não pode ser vazio/nulo.';
	else if (new.ativo_cliente_pizzaria not in (0,1,2)) then
			signal sqlstate '45000' set message_text = 'ATIVO inválido.';
        end if;
	end if;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `extra_pizza`
--

CREATE TABLE `extra_pizza` (
  `codigo_extra_pizza` bigint(20) NOT NULL,
  `descricao_extra_pizza` varchar(400) NOT NULL,
  `pizzaria_extra_pizza` bigint(20) NOT NULL,
  `preco_extra_pizza` decimal(10,2) UNSIGNED NOT NULL,
  `ativo_extra_pizza` tinyint(4) UNSIGNED NOT NULL DEFAULT '1' COMMENT '0 - nao ativo, 1 - sim ativo',
  `tipo_extra_pizza_extra_pizza` bigint(20) NOT NULL,
  `tamanho_pizza_extra_pizza` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `extra_pizza`
--

INSERT INTO `extra_pizza` (`codigo_extra_pizza`, `descricao_extra_pizza`, `pizzaria_extra_pizza`, `preco_extra_pizza`, `ativo_extra_pizza`, `tipo_extra_pizza_extra_pizza`, `tamanho_pizza_extra_pizza`) VALUES
(1, 'CATUPIRY', 1, '5.00', 0, 1, 2),
(2, 'CHEDDAR', 1, '5.00', 0, 1, 4),
(3, 'ervilha', 1, '5.00', 1, 2, 4),
(4, 'Ovo', 1, '4.00', 1, 2, 4),
(5, 'palmito', 1, '3.00', 1, 2, 4),
(6, 'Champignon', 1, '3.00', 1, 2, 4),
(7, 'Alho poró', 1, '3.00', 1, 2, 4),
(8, 'Azeitona', 1, '3.00', 1, 2, 4),
(9, 'Tomate seco', 1, '6.00', 1, 1, 4),
(10, 'INTEGRAL', 1, '3.50', 1, 3, 4),
(11, 'bacon', 1, '5.50', 2, 2, 3),
(14, 'Trançada Catupiry', 2, '0.00', 1, 5, 5),
(15, 'Trançada Catupiry', 2, '0.00', 1, 5, 7),
(16, 'Trançada Cheddar', 2, '0.00', 1, 5, 5),
(17, 'Trançada Cheddar', 2, '0.00', 1, 5, 7),
(18, 'Trançada chocolate', 2, '8.00', 1, 5, 6),
(19, 'Trançada Cheddar', 2, '0.00', 1, 5, 9),
(20, 'Trançada Catupiry', 2, '0.00', 1, 5, 9),
(22, 'Vulcânica Doce de leite', 2, '10.00', 1, 5, 9),
(23, 'Vulcânica Mista Cheddar e Cream Cheese', 2, '10.00', 1, 5, 9),
(25, 'Pãozinho Frango com catupiry (28 un)', 2, '10.00', 1, 5, 9),
(26, 'Pãozinho Salsicha (28 un)', 2, '12.00', 1, 5, 9),
(27, 'Pãozinho Misto Salsicha/Frango com catupiry (28 un)', 2, '15.00', 1, 5, 9),
(29, 'Vulcânica  Doce de leite', 2, '10.00', 1, 5, 10),
(30, 'Pãozinho Frango com catupiry (32 un)', 2, '10.00', 1, 5, 5),
(31, 'Pãozinho Frango com catupiry (48 un)', 2, '25.00', 1, 5, 7),
(32, 'Pãozinho Misto Salsicha/Frango com catupiry (32 un)', 2, '15.00', 1, 5, 5),
(33, 'Pãozinho Misto Salsicha/Frango com catupiry (48 un)', 2, '30.00', 1, 5, 7),
(34, 'Pãozinho Salsicha (48 un)', 2, '25.00', 1, 5, 7),
(36, 'Pãozinho Salsicha (32 un)', 2, '10.00', 1, 5, 5),
(38, 'Vulcânica Doce de leite', 2, '15.00', 1, 5, 5),
(39, 'Vulcânica Mista Cheddar e Cream Cheese', 2, '15.00', 1, 5, 5),
(41, 'Trançada Catupiry', 2, '0.00', 2, 5, 5),
(42, 'Trançada Catupiry', 2, '0.00', 2, 5, 7),
(43, 'Trançada Cheddar', 2, '0.00', 2, 5, 5),
(44, 'Trançada Cheddar', 2, '0.00', 2, 5, 7),
(45, 'BACON', 1, '4.00', 1, 2, 4),
(46, 'ervilha', 1, '2.00', 2, 2, 2),
(47, 'ervilha', 1, '3.00', 2, 2, 3),
(48, 'Ovo', 1, '2.00', 2, 2, 2),
(49, 'Ovo', 1, '3.00', 2, 2, 3),
(50, 'Trançada chocolate', 2, '0.00', 2, 5, 6),
(51, 'NÃO, OBRIGADO', 1, '0.00', 2, 1, 4),
(52, 'CHEDDAR', 1, '5.00', 1, 1, 2),
(53, 'CHEDDAR', 1, '5.00', 1, 1, 3),
(54, 'BACON', 1, '8.00', 1, 1, 3),
(55, 'BACON', 1, '5.00', 0, 2, 2),
(56, 'BACON', 1, '5.00', 1, 2, 3),
(57, 'novo extra', 1, '10.00', 1, 2, 24),
(58, 'queijo', 1, '5.00', 1, 2, 4),
(59, 'oregano', 1, '1.00', 1, 2, 4),
(60, 'CATUPIRY', 4, '6.00', 1, 6, 23),
(61, 'CATUPIRY', 4, '5.00', 1, 6, 25),
(62, 'CATUPIRY', 4, '7.00', 1, 6, 26),
(63, 'CHEDDAR', 4, '6.00', 1, 6, 23),
(64, 'CHEDDAR', 4, '5.00', 1, 6, 25),
(65, 'CHEDDAR', 4, '7.00', 1, 6, 26),
(66, 'BACON', 4, '3.00', 1, 7, 23),
(67, 'BACON', 4, '3.00', 1, 7, 25),
(68, 'BACON', 4, '3.00', 1, 7, 26),
(69, 'Ovo', 4, '3.00', 1, 7, 23),
(70, 'Ovo', 4, '3.00', 1, 7, 25),
(71, 'Ovo', 4, '3.00', 1, 7, 26),
(72, 'palmito', 4, '3.00', 1, 7, 23),
(73, 'palmito', 4, '3.00', 1, 7, 25),
(74, 'palmito', 4, '3.00', 1, 7, 26),
(75, 'Champignon', 4, '4.00', 1, 7, 23),
(76, 'Champignon', 4, '4.00', 1, 7, 25),
(77, 'Champignon', 4, '4.00', 1, 7, 26),
(78, 'Alho poró', 4, '3.00', 1, 7, 23),
(79, 'Alho poró', 4, '3.00', 1, 7, 25),
(80, 'Alho poró', 4, '3.00', 1, 7, 26),
(81, 'Azeitona', 4, '3.00', 1, 7, 23),
(82, 'Azeitona', 4, '3.00', 1, 7, 25),
(83, 'Azeitona', 4, '3.00', 1, 7, 26),
(84, 'Queijo', 4, '4.00', 1, 7, 23),
(85, 'Queijo', 4, '4.00', 1, 7, 25),
(86, 'Queijo', 4, '4.00', 1, 7, 26),
(87, 'INTEGRAL', 4, '1.00', 1, 8, 23),
(88, 'INTEGRAL', 4, '1.00', 1, 8, 25),
(89, 'INTEGRAL', 4, '1.00', 1, 8, 26),
(90, 'Tradicional italiana', 4, '2.00', 1, 8, 23),
(91, 'Tradicional italiana', 4, '2.00', 1, 8, 25),
(92, 'Tradicional italiana', 4, '2.00', 1, 8, 26);

--
-- Acionadores `extra_pizza`
--
DELIMITER $$
CREATE TRIGGER `trigger_validacao_extra_pizza_bi` BEFORE INSERT ON `extra_pizza` FOR EACH ROW begin
	if (new.descricao_extra_pizza is null or new.descricao_extra_pizza = '') then
		signal sqlstate '45000' set message_text = 'DESCRIÇÃO não pode ser vazio/nulo.';
	end if;
	if (new.preco_extra_pizza is null or new.preco_extra_pizza < 0 ) then
		signal sqlstate '45000' set message_text = 'PREÇO deve ser maior que 0.';
	end if;
    if (new.pizzaria_extra_pizza is null or new.pizzaria_extra_pizza < 0 ) then
		signal sqlstate '45000' set message_text = 'PIZZARIA não pode ser vazio/nulo.';
	end if;
    if(new.ativo_extra_pizza is null or new.ativo_extra_pizza < 0) then
		signal sqlstate '45000' set message_text = 'ATIVO não pode ser vazio/nulo.';
	else if (new.ativo_extra_pizza not in (0,1)) then
			signal sqlstate '45000' set message_text = 'ATIVO inválido.';
        end if;
	end if;
	if(new.tipo_extra_pizza_extra_pizza < 0 and new.tipo_extra_pizza_extra_pizza is null) then
		signal sqlstate '45000' set message_text = 'TIPO EXTRA não pode ser vazio/nulo.';
	end if;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trigger_validacao_extra_pizza_bu` BEFORE UPDATE ON `extra_pizza` FOR EACH ROW begin
	if (new.descricao_extra_pizza is null or new.descricao_extra_pizza = '') then
		signal sqlstate '45000' set message_text = 'DESCRIÇÃO não pode ser vazio/nulo.';
	end if;
	if (new.preco_extra_pizza is null or new.preco_extra_pizza < 0 ) then
		signal sqlstate '45000' set message_text = 'PREÇO deve ser maior que 0.';
	end if;
    if (new.pizzaria_extra_pizza is null or new.pizzaria_extra_pizza < 0 ) then
		signal sqlstate '45000' set message_text = 'PIZZARIA não pode ser vazio/nulo.';
	end if;
    if(new.ativo_extra_pizza is null or new.ativo_extra_pizza < 0) then
		signal sqlstate '45000' set message_text = 'ATIVO não pode ser vazio/nulo.';
	else if (new.ativo_extra_pizza not in (0,1,2)) then
			signal sqlstate '45000' set message_text = 'ATIVO inválido.';
        end if;
	end if;
	if(new.tipo_extra_pizza_extra_pizza < 0 and new.tipo_extra_pizza_extra_pizza is null) then
		signal sqlstate '45000' set message_text = 'TIPO EXTRA não pode ser vazio/nulo.';
	end if;
    
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `forma_pagamento`
--

CREATE TABLE `forma_pagamento` (
  `codigo_forma_pagamento` bigint(20) NOT NULL,
  `descricao_forma_pagamento` varchar(400) NOT NULL,
  `pizzaria_forma_pagamento` bigint(20) NOT NULL,
  `ativo_forma_pagamento` tinyint(4) UNSIGNED NOT NULL DEFAULT '1' COMMENT '0 - nao ativo, 1 - sim ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `forma_pagamento`
--

INSERT INTO `forma_pagamento` (`codigo_forma_pagamento`, `descricao_forma_pagamento`, `pizzaria_forma_pagamento`, `ativo_forma_pagamento`) VALUES
(1, 'Dinheiro', 1, 1),
(1, 'Dinheiro', 2, 1),
(1, 'Dinheiro', 4, 1),
(2, 'Cartão de crédito', 2, 1),
(3, 'Cartão de débito', 2, 1),
(4, 'Alelo refeição', 2, 1),
(5, 'Sodexo refeição', 2, 1),
(6, 'American express', 2, 1),
(8, 'Cartão de credito', 1, 1),
(9, 'Cartão de débito', 1, 0),
(10, 'Sodexo refeição', 1, 0),
(11, 'Sodexo alimentação', 1, 1),
(13, 'Cartão de credito', 4, 1),
(14, 'novo', 1, 2);

--
-- Acionadores `forma_pagamento`
--
DELIMITER $$
CREATE TRIGGER `trigger_validacao_forma_pagamento_bi` BEFORE INSERT ON `forma_pagamento` FOR EACH ROW BEGIN
	if (new.descricao_forma_pagamento is null or new.descricao_forma_pagamento = '') then
		signal sqlstate '45000' set message_text = 'DESCRIÇÃO não pode ser vazio/nulo.';
	end if;	
    if (new.pizzaria_forma_pagamento is null or new.pizzaria_forma_pagamento < 0 ) then
		signal sqlstate '45000' set message_text = 'PIZZARIA não pode ser vazio/nulo.';
	end if;
    if(new.ativo_forma_pagamento is null or new.ativo_forma_pagamento < 0) then
		signal sqlstate '45000' set message_text = 'ATIVO não pode ser vazio/nulo.';
	else if (new.ativo_forma_pagamento not in (0,1)) then
			signal sqlstate '45000' set message_text = 'ATIVO inválido.';
        end if;
	end if;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trigger_validacao_forma_pagamento_bu` BEFORE UPDATE ON `forma_pagamento` FOR EACH ROW BEGIN
	if (new.descricao_forma_pagamento is null or new.descricao_forma_pagamento = '') then
		signal sqlstate '45000' set message_text = 'DESCRIÇÃO não pode ser vazio/nulo.';
	end if;	
    if (new.pizzaria_forma_pagamento is null or new.pizzaria_forma_pagamento < 0 ) then
		signal sqlstate '45000' set message_text = 'PIZZARIA não pode ser vazio/nulo.';
	end if;
    if(new.ativo_forma_pagamento is null or new.ativo_forma_pagamento < 0) then
		signal sqlstate '45000' set message_text = 'ATIVO não pode ser vazio/nulo.';
	else if (new.ativo_forma_pagamento not in (0,1,2)) then
			signal sqlstate '45000' set message_text = 'ATIVO inválido.';
        end if;
	end if;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `historico_cliente`
--

CREATE TABLE `historico_cliente` (
  `codigo_historico_cliente` bigint(20) NOT NULL COMMENT 'chave primaria',
  `quantas_vezes_pg_historico_cliente` int(4) NOT NULL DEFAULT '0' COMMENT 'quantidade de vezes que foi perguntado a avaliação	',
  `cliente_historico_cliente` bigint(20) NOT NULL COMMENT 'chave primaria com o cliente',
  `empresa_historico_cliente` bigint(20) NOT NULL COMMENT 'codigo da pizzaria',
  `ultima_interacao_historico_cliente` datetime NOT NULL COMMENT ' ultima data hora que o usuario falou com a assistente virtual',
  `pedido_historico_cliente` bigint(20) DEFAULT NULL COMMENT 'chave extrangeira com o codigo do pedido',
  `avaliacao_visual_historico_cliente` int(4) DEFAULT NULL COMMENT '1-pessimo, 2-ruim, 3-medio, 4-bom, 5-otimo',
  `ponto_melhoria_historico_cliente` int(4) DEFAULT NULL COMMENT '1-Tempo de entrega, 2-Atendimento do restaurante, 3-Embalagem do produto, 4-Itens errados do pedido, 5-Atendimento do entregador, 6-Comida, 7-Nada',
  `motivo_n_finalizar_historico_cliente` int(4) DEFAULT NULL COMMENT '1-Achei os produtos caros, 2-Não tinha o que eu desejava, 3-Achei difícil fazer o pedido inbox, 4-Resolvi pedir em outro restaurante, 5-Desisti por outros motivos',
  `escala_recomenda_historico_cliente` int(4) DEFAULT NULL COMMENT 'de 1 a 10',
  `motivo_dificuldade_historico_cliente` varchar(400) DEFAULT NULL COMMENT 'Por que você achou difícil fazer o pedido inbox',
  `oque_faltou_historico_cliente` varchar(400) DEFAULT NULL COMMENT 'O que faltou para você completar o pedido (sabor, adicional)',
  `sugestao_critica_historico_cliente` varchar(400) DEFAULT NULL COMMENT 'Sugestões, críticas ou melhorias'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `historico_cliente`
--

INSERT INTO `historico_cliente` (`codigo_historico_cliente`, `quantas_vezes_pg_historico_cliente`, `cliente_historico_cliente`, `empresa_historico_cliente`, `ultima_interacao_historico_cliente`, `pedido_historico_cliente`, `avaliacao_visual_historico_cliente`, `ponto_melhoria_historico_cliente`, `motivo_n_finalizar_historico_cliente`, `escala_recomenda_historico_cliente`, `motivo_dificuldade_historico_cliente`, `oque_faltou_historico_cliente`, `sugestao_critica_historico_cliente`) VALUES
(113, 1, 71, 2, '2018-04-26 22:01:38', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(121, 1, 70, 4, '2018-04-27 08:10:04', 703, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(122, 1, 42, 4, '2018-04-27 08:20:04', 704, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(123, 1, 42, 4, '2018-04-27 11:10:03', 706, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(124, 1, 53, 4, '2018-04-27 12:07:31', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(125, 1, 53, 4, '2018-04-27 13:36:14', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(126, 1, 53, 4, '2018-04-27 14:06:54', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(127, 1, 72, 2, '2018-04-27 20:59:36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(128, 1, 72, 2, '2018-04-27 22:02:06', NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL),
(129, 1, 73, 2, '2018-04-28 18:17:41', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(130, 0, 74, 2, '2018-04-28 18:42:52', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(131, 1, 73, 2, '2018-04-28 19:14:31', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(132, 1, 75, 2, '2018-04-28 19:28:19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(133, 1, 76, 2, '2018-04-28 19:53:58', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(134, 1, 74, 2, '2018-04-29 17:50:03', 708, 4, NULL, NULL, NULL, NULL, NULL, NULL),
(135, 1, 77, 2, '2018-04-29 19:15:44', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(136, 1, 78, 2, '2018-05-01 19:00:58', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(137, 1, 79, 2, '2018-05-01 20:03:35', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(138, 1, 80, 2, '2018-05-01 20:14:40', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(139, 1, 81, 2, '2018-05-01 21:52:37', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(140, 1, 81, 2, '2018-05-01 22:40:38', NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL),
(141, 1, 82, 2, '2018-05-02 19:54:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(142, 1, 82, 2, '2018-05-02 20:42:05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(143, 1, 42, 4, '2018-05-03 10:51:17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(144, 1, 53, 4, '2018-05-03 17:43:08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(145, 1, 53, 4, '2018-05-07 22:10:52', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(146, 1, 41, 4, '2018-05-05 11:42:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(147, 1, 41, 4, '2018-05-05 13:22:44', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(148, 1, 53, 4, '2018-05-05 12:50:03', 711, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(149, 1, 53, 4, '2018-05-05 18:51:18', 712, 5, 7, NULL, 10, NULL, NULL, NULL),
(150, 1, 83, 2, '2018-05-05 22:14:13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(151, 1, 83, 2, '2018-05-05 23:00:56', NULL, NULL, NULL, NULL, NULL, 'Poucos sabores para escolha', NULL, 'Conversar com um atendente real, nao com uma máquina'),
(152, 1, 41, 4, '2018-05-06 12:10:04', 713, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(153, 1, 84, 2, '2018-05-06 18:10:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(154, 1, 42, 4, '2018-05-11 08:43:48', NULL, NULL, NULL, 2, NULL, 'Oi', 'oi', 'oi'),
(155, 1, 46, 2, '2018-05-06 18:20:50', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(156, 1, 85, 2, '2018-07-28 22:04:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(157, 0, 86, 2, '2018-06-05 21:23:26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(158, 1, 53, 4, '2018-05-06 23:10:03', 714, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(159, 1, 85, 2, '2018-05-07 19:20:03', 715, 5, NULL, NULL, NULL, NULL, NULL, NULL),
(160, 1, 86, 2, '2018-05-07 19:50:03', 716, 5, NULL, NULL, NULL, NULL, NULL, NULL),
(161, 1, 59, 4, '2018-05-07 20:13:26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(162, 1, 53, 4, '2018-05-08 11:52:49', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(163, 1, 87, 2, '2018-05-08 19:54:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(164, 1, 88, 2, '2018-05-08 21:31:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(165, 1, 89, 2, '2018-05-08 21:34:54', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(166, 1, 53, 4, '2018-05-09 11:16:00', NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL),
(167, 1, 90, 2, '2018-05-09 19:49:56', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(168, 1, 53, 4, '2018-05-10 10:50:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(169, 1, 42, 4, '2018-05-11 09:06:12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(170, 1, 40, 2, '2018-05-11 20:58:31', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(171, 0, 49, 2, '2018-05-11 22:18:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(172, 1, 91, 2, '2018-05-12 19:01:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(173, 1, 92, 2, '2018-05-12 19:12:11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(174, 1, 92, 2, '2018-05-12 20:17:15', NULL, NULL, NULL, NULL, NULL, 'JÁ pedi por telefone. \nE ja até comemos\nUma delícia', NULL, 'APROVADA'),
(175, 1, 49, 2, '2018-05-12 23:22:11', 720, 5, 1, NULL, 10, NULL, NULL, NULL),
(176, 1, 93, 2, '2018-05-13 19:16:15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(177, 1, 94, 2, '2018-05-13 19:40:26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(178, 1, 95, 2, '2018-05-13 19:44:59', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(179, 1, 95, 2, '2018-05-13 20:33:00', NULL, NULL, NULL, NULL, NULL, 'Nao faz entrega no meu bairro', NULL, NULL),
(180, 1, 96, 2, '2018-05-13 21:34:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(181, 1, 96, 2, '2018-05-13 22:54:39', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(182, 1, 53, 4, '2018-05-17 11:09:50', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(183, 1, 53, 4, '2018-05-15 13:50:03', 721, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(184, 1, 53, 4, '2018-05-15 14:10:03', 723, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(185, 1, 53, 4, '2018-05-16 23:14:54', 724, 5, NULL, NULL, NULL, NULL, NULL, NULL),
(186, 1, 42, 4, '2018-05-16 09:53:05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(187, 1, 97, 4, '2018-05-16 12:37:52', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(188, 1, 42, 4, '2018-05-17 08:25:11', NULL, NULL, NULL, NULL, NULL, 'oi', NULL, 'oi'),
(189, 1, 42, 4, '2018-05-17 08:39:25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(190, 1, 53, 4, '2018-05-17 18:38:56', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(191, 1, 40, 2, '2018-05-17 18:28:48', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(192, 1, 98, 2, '2018-05-17 18:17:39', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(193, 1, 92, 2, '2018-05-18 19:03:04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(194, 1, 53, 4, '2018-05-18 22:16:11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(195, 1, 42, 4, '2018-05-21 08:36:38', NULL, NULL, NULL, NULL, NULL, 'oi', NULL, NULL),
(196, 1, 42, 4, '2018-05-21 08:41:56', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(197, 1, 42, 4, '2018-05-21 19:13:42', NULL, NULL, NULL, NULL, NULL, 'Oi', NULL, NULL),
(198, 1, 99, 2, '2018-05-22 18:50:17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(199, 1, 40, 2, '2018-05-23 22:36:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(200, 1, 40, 2, '2018-05-23 22:38:51', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(201, 0, 59, 4, '2018-05-23 23:14:10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(202, 1, 53, 4, '2018-05-26 12:16:30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(203, 1, 100, 2, '2018-05-26 19:18:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(204, 1, 100, 2, '2018-05-26 20:13:39', NULL, NULL, NULL, 3, NULL, 'Não responde o que devo saber', NULL, 'Pedi pelo WhatsApp'),
(205, 1, 101, 4, '2018-05-26 21:55:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(206, 1, 102, 2, '2018-05-27 19:11:38', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(207, 1, 102, 2, '2018-05-27 20:00:43', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(208, 1, 53, 4, '2018-05-28 18:54:11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(209, 1, 103, 2, '2018-05-31 21:52:37', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(210, 1, 104, 2, '2018-06-02 18:56:48', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(211, 1, 58, 2, '2018-06-12 21:18:27', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(212, 1, 53, 4, '2018-06-05 16:46:23', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(213, 1, 53, 4, '2018-06-05 22:33:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(214, 1, 53, 4, '2018-06-06 11:18:11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(215, 1, 86, 2, '2018-06-06 20:31:37', 727, 5, 2, NULL, 10, NULL, NULL, NULL),
(216, 1, 105, 2, '2018-06-08 21:57:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(217, 1, 106, 2, '2018-06-08 22:10:46', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(218, 1, 99, 2, '2018-06-09 20:43:30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(219, 1, 53, 4, '2018-07-01 18:34:18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(220, 1, 107, 4, '2018-06-11 16:07:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(221, 1, 108, 2, '2018-06-11 18:07:16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(222, 1, 53, 4, '2018-06-12 12:30:04', 729, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(223, 1, 53, 4, '2018-06-12 14:00:08', 730, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(224, 1, 53, 4, '2018-06-12 15:50:04', 731, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(225, 1, 53, 4, '2018-06-12 16:20:03', 732, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(226, 1, 53, 4, '2018-06-12 17:10:03', 733, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(227, 1, 53, 4, '2018-06-12 17:20:04', 734, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(228, 1, 53, 4, '2018-06-13 10:00:04', 736, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(229, 1, 53, 4, '2018-06-13 10:10:03', 737, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(230, 1, 53, 4, '2018-06-13 10:20:03', 738, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(231, 1, 53, 4, '2018-06-13 10:30:03', 739, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(232, 1, 53, 4, '2018-06-13 10:40:04', 740, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(233, 1, 53, 4, '2018-06-13 10:50:04', 741, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(234, 1, 53, 4, '2018-06-13 11:00:03', 743, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(235, 1, 53, 4, '2018-06-13 11:10:03', 745, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(236, 1, 109, 2, '2018-06-13 17:25:40', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(237, 1, 53, 4, '2018-06-14 10:40:03', 748, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(238, 1, 53, 4, '2018-06-14 16:10:03', 749, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(239, 1, 110, 2, '2018-06-14 20:10:28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(240, 1, 111, 2, '2018-06-14 21:39:16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(241, 1, 111, 2, '2018-06-15 07:15:00', NULL, NULL, NULL, NULL, NULL, 'Não entregam em meu endereço.', NULL, NULL),
(242, 1, 53, 4, '2018-06-15 10:20:04', 750, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(243, 1, 53, 4, '2018-06-15 11:10:03', 751, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(244, 1, 53, 4, '2018-06-15 12:40:03', 752, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(245, 1, 53, 4, '2018-06-15 12:50:03', 753, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(246, 1, 54, 2, '2018-06-16 19:43:37', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(247, 0, 112, 2, '2018-06-16 20:42:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(248, 1, 112, 2, '2018-06-17 19:50:02', 755, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(249, 1, 113, 2, '2018-06-17 20:24:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(250, 1, 113, 2, '2018-06-17 21:11:44', NULL, NULL, NULL, NULL, NULL, 'Esfihas', NULL, 'Comprá esfihas'),
(251, 1, 114, 2, '2018-06-19 20:23:10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(252, 1, 115, 2, '2018-06-24 20:08:53', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(253, 1, 115, 2, '2018-06-24 21:00:51', NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL),
(254, 1, 116, 2, '2018-06-27 18:49:36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(255, 1, 53, 4, '2018-07-01 22:08:37', NULL, NULL, NULL, NULL, NULL, 'Oi', NULL, 'Oi'),
(256, 1, 42, 4, '2018-07-04 16:01:53', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(257, 1, 46, 2, '2018-07-07 19:31:30', NULL, NULL, NULL, NULL, NULL, 'Oi', NULL, 'Cancelar'),
(258, 1, 46, 2, '2018-07-07 19:32:15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(259, 1, 117, 2, '2018-07-09 18:59:38', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(260, 1, 53, 4, '2018-07-11 22:55:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(261, 1, 91, 2, '2018-07-14 19:48:21', NULL, NULL, NULL, NULL, NULL, 'olá boa noite', NULL, 'estão fazendo entrega hoje?'),
(262, 1, 118, 2, '2018-07-14 20:50:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(263, 1, 119, 2, '2018-07-17 18:52:30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(264, 1, 120, 2, '2018-07-19 18:53:18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(265, 1, 121, 2, '2018-07-21 22:14:56', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(266, 1, 122, 2, '2018-07-22 18:22:10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(267, 0, 123, 2, '2018-07-27 20:24:29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(268, 0, 61, 2, '2018-07-27 21:40:13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(269, 1, 123, 2, '2018-07-28 19:21:27', 759, 5, 7, NULL, 9, NULL, NULL, 'Se aceitar Vale Refeicao Alelo fica melhor'),
(270, 1, 61, 2, '2018-07-28 21:26:45', 761, 5, 7, NULL, 10, NULL, NULL, NULL),
(271, 1, 124, 2, '2018-08-04 18:22:31', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(272, 1, 125, 2, '2018-08-04 19:42:17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(273, 1, 126, 2, '2018-08-05 20:30:40', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(274, 1, 127, 2, '2018-08-05 20:35:30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(275, 1, 128, 2, '2018-08-09 21:59:38', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(276, 1, 129, 2, '2018-08-12 21:09:23', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(277, 1, 130, 2, '2018-08-13 09:19:49', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(278, 1, 130, 2, '2018-08-14 01:53:12', NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL),
(279, 1, 38, 2, '2018-08-13 21:43:29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(280, 1, 41, 4, '2018-08-13 22:43:56', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(281, 0, 131, 2, '2018-08-16 21:27:52', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(282, 1, 132, 4, '2018-08-16 21:21:08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(283, 1, 131, 2, '2018-08-17 20:30:03', 762, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(284, 1, 133, 2, '2018-08-18 19:40:56', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(285, 1, 134, 2, '2018-08-19 19:23:29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(286, 1, 135, 2, '2018-08-24 20:40:43', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(287, 1, 136, 2, '2018-08-19 20:09:53', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(288, 1, 136, 2, '2018-08-19 21:00:30', NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL),
(289, 0, 38, 2, '2018-08-19 21:51:54', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(290, 1, 135, 2, '2018-08-20 19:42:03', 763, 5, 7, NULL, 10, NULL, NULL, NULL),
(291, 1, 38, 2, '2018-08-20 21:21:07', 764, 5, 7, NULL, 10, NULL, NULL, NULL),
(292, 1, 137, 2, '2018-08-23 19:17:30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(293, 1, 132, 4, '2018-08-24 17:19:01', NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL),
(294, 1, 117, 2, '2018-08-25 18:54:47', NULL, NULL, NULL, NULL, NULL, 'Boa noite', NULL, '99982-3283'),
(295, 1, 138, 2, '2018-08-26 18:34:58', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(296, 1, 139, 2, '2018-09-04 19:34:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(297, 1, 140, 2, '2018-08-26 19:51:26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(298, 1, 139, 2, '2018-08-27 18:52:16', 765, 5, 7, NULL, 10, NULL, NULL, NULL),
(299, 1, 141, 2, '2018-09-01 19:57:43', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(300, 0, 142, 2, '2018-09-02 19:17:11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(301, 1, 143, 2, '2018-09-02 20:11:52', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(302, 1, 143, 2, '2018-09-02 21:01:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(303, 1, 142, 2, '2018-09-03 18:36:28', 766, 5, 7, NULL, 10, NULL, NULL, 'Ja cortadas as fatias e colocar o valor do pedido para o entregador'),
(304, 0, 139, 2, '2018-09-11 19:33:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(305, 1, 139, 2, '2018-09-12 18:44:30', 767, 5, 7, NULL, 10, NULL, NULL, 'Gostaria que o refrigerante chegasse mas gelado otem o refrigerante chegou quente..'),
(306, 1, 144, 2, '2018-09-15 20:14:42', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(307, 1, 145, 2, '2018-09-16 19:27:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(308, 1, 146, 2, '2018-09-17 18:41:46', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(309, 1, 146, 2, '2018-09-17 19:30:58', NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL),
(310, 1, 147, 2, '2018-09-18 19:57:13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(311, 1, 148, 2, '2018-09-20 18:07:44', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(312, 1, 148, 2, '2018-09-20 19:01:01', NULL, NULL, NULL, NULL, NULL, 'Queria esfirra e não estava dando esta opção por aqui', NULL, 'Opcao'),
(313, 1, 149, 2, '2018-09-20 19:57:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `horario_atendimento`
--

CREATE TABLE `horario_atendimento` (
  `codigo_horario_atendimento` bigint(20) NOT NULL,
  `dia_semana_horario_atendimento` tinyint(4) UNSIGNED NOT NULL COMMENT '1 - segunda2 - terça3 - quarta4 - quinta5 - sexta6 - sábado7 - domingo',
  `inicio_horario_atendimento` time NOT NULL,
  `fim_horario_atendimento` time NOT NULL,
  `pizzaria_horario_atendimento` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `horario_atendimento`
--

INSERT INTO `horario_atendimento` (`codigo_horario_atendimento`, `dia_semana_horario_atendimento`, `inicio_horario_atendimento`, `fim_horario_atendimento`, `pizzaria_horario_atendimento`) VALUES
(10, 1, '18:00:00', '23:00:00', 2),
(12, 2, '18:00:00', '23:00:00', 2),
(16, 5, '18:00:00', '23:00:00', 2),
(18, 6, '18:00:00', '23:00:00', 2),
(20, 7, '18:00:00', '23:00:00', 2),
(21, 1, '00:00:00', '23:59:00', 4),
(23, 2, '00:00:00', '23:59:00', 4),
(24, 3, '00:00:00', '23:59:00', 4),
(25, 4, '00:00:00', '23:59:00', 4),
(26, 5, '00:00:00', '23:59:00', 4),
(27, 6, '00:00:00', '23:59:00', 4),
(28, 7, '00:00:00', '23:59:00', 4),
(29, 4, '18:00:00', '23:00:00', 2),
(37, 1, '00:00:00', '23:59:00', 1),
(38, 2, '00:00:00', '23:59:00', 1),
(39, 3, '00:00:00', '23:59:00', 1),
(40, 4, '00:00:00', '23:59:00', 1),
(41, 5, '00:00:00', '23:59:00', 1),
(42, 6, '00:00:00', '23:59:00', 1),
(43, 7, '00:00:00', '23:59:00', 1),
(44, 3, '18:00:00', '23:00:00', 2);

--
-- Acionadores `horario_atendimento`
--
DELIMITER $$
CREATE TRIGGER `trigger_validacao_horario_atendimento_bi` BEFORE INSERT ON `horario_atendimento` FOR EACH ROW BEGIN
	-- VALIDAÇÃO DE VALORES VAZIOS/NULOS
	if(new.dia_semana_horario_atendimento is null or new.dia_semana_horario_atendimento = '') then
		signal sqlstate '45000' set message_text = 'DIA DA SEMANA não pode ser vazio/nulo.';
	else if(new.dia_semana_horario_atendimento > 7 and new.dia_semana_horario_atendimento < 1) then
		signal sqlstate '45000' set message_text = 'DIA DA SEMANA inválido.';
    end if;
    end if;
    if(new.INICIO_horario_atendimento is null or new.INICIO_horario_atendimento = '') then
		signal sqlstate '45000' set message_text = 'HORA DE INÍCIO não pode ser vazio/nulo.';
	else if(new.INICIO_horario_atendimento > '23:59:59' and new.INICIO_horario_atendimento < '00:00:00') then
		signal sqlstate '45000' set message_text = 'HORA DE INÍCIO inválido.';
    end if;
    end if;
    if(new.FIM_horario_atendimento is null or new.FIM_horario_atendimento = '') then
		signal sqlstate '45000' set message_text = 'HORA DE FIM não pode ser vazio/nulo.';
	else if(new.FIM_horario_atendimento > '23:59:59' and new.FIM_horario_atendimento < '00:00:00') then
		signal sqlstate '45000' set message_text = 'HORA DE FIM inválido.';
    end if;
    end if;
    if(new.PIZZARIA_horario_atendimento is null or new.PIZZARIA_horario_atendimento = '') then
		signal sqlstate '45000' set message_text = 'PIZZARIA não pode ser vazio/nulo.';
    end if;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trigger_validacao_horario_atendimento_bu` BEFORE UPDATE ON `horario_atendimento` FOR EACH ROW BEGIN
	-- VALIDAÇÃO DE VALORES VAZIOS/NULOS
	if(new.dia_semana_horario_atendimento is null or new.dia_semana_horario_atendimento = '') then
		signal sqlstate '45000' set message_text = 'DIA DA SEMANA não pode ser vazio/nulo.';
	else if(new.dia_semana_horario_atendimento > 7 and new.dia_semana_horario_atendimento < 1) then
		signal sqlstate '45000' set message_text = 'DIA DA SEMANA inválido.';
    end if;
    end if;
    if(new.INICIO_horario_atendimento is null or new.INICIO_horario_atendimento = '') then
		signal sqlstate '45000' set message_text = 'HORA DE INÍCIO não pode ser vazio/nulo.';
	else if(new.INICIO_horario_atendimento > '23:59:59' and new.INICIO_horario_atendimento < '00:00:00') then
		signal sqlstate '45000' set message_text = 'HORA DE INÍCIO inválido.';
    end if;
    end if;
    if(new.FIM_horario_atendimento is null or new.FIM_horario_atendimento = '') then
		signal sqlstate '45000' set message_text = 'HORA DE FIM não pode ser vazio/nulo.';
	else if(new.FIM_horario_atendimento > '23:59:59' and new.FIM_horario_atendimento < '00:00:00') then
		signal sqlstate '45000' set message_text = 'HORA DE FIM inválido.';
    end if;
    end if;
    if(new.PIZZARIA_horario_atendimento is null or new.PIZZARIA_horario_atendimento = '') then
		signal sqlstate '45000' set message_text = 'PIZZARIA não pode ser vazio/nulo.';
    end if;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `horario_especial`
--

CREATE TABLE `horario_especial` (
  `codigo_horario_especial` bigint(20) NOT NULL,
  `data_horario_especial` datetime NOT NULL,
  `inicio_horario_especial` time DEFAULT NULL,
  `fim_horario_especial` time DEFAULT NULL,
  `pizzaria_horario_especial` bigint(20) NOT NULL,
  `aberto_horario_especial` tinyint(2) UNSIGNED DEFAULT '0' COMMENT '0 - fechado, 1 - aberto, 2 pausado',
  `ativo_horario_especial` tinyint(4) UNSIGNED NOT NULL DEFAULT '1' COMMENT '0 - nao ativo, 1 - sim ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `horario_especial`
--

INSERT INTO `horario_especial` (`codigo_horario_especial`, `data_horario_especial`, `inicio_horario_especial`, `fim_horario_especial`, `pizzaria_horario_especial`, `aberto_horario_especial`, `ativo_horario_especial`) VALUES
(40, '2017-08-16 00:00:00', '16:29:34', '16:31:34', 1, 2, 1),
(44, '2017-08-21 00:00:00', '09:00:00', '19:00:00', 1, 1, 0),
(45, '2017-08-29 00:00:00', '20:04:32', '20:11:32', 1, 2, 0),
(46, '2017-08-29 00:00:00', '10:00:00', '20:00:00', 1, 1, 0),
(47, '2017-08-29 00:00:00', '11:00:00', '21:00:00', 1, 1, 0),
(48, '2017-08-29 00:00:00', '12:00:00', '22:00:00', 1, 1, 0),
(49, '2017-08-29 00:00:00', '10:00:00', '20:00:00', 1, 1, 0),
(50, '2017-08-31 00:00:00', '00:00:00', '22:59:00', 2, 1, 0),
(51, '2017-08-31 00:00:00', '00:00:00', '22:00:00', 2, 1, 0),
(52, '2017-08-31 00:00:00', '18:06:34', '18:07:34', 1, 2, 1),
(53, '2017-08-31 00:00:00', '18:09:25', '19:09:25', 1, 2, 1),
(54, '2017-09-02 00:00:00', '09:15:11', '09:25:11', 1, 2, 1),
(55, '2017-09-02 00:00:00', '11:00:00', '23:00:00', 1, 1, 0),
(56, '2017-09-03 00:00:00', '00:00:01', '01:00:00', 1, 1, 0),
(57, '2017-09-03 00:00:00', '10:00:00', '20:00:00', 1, 1, 0),
(58, '2017-09-04 00:00:00', '10:00:00', '20:00:00', 1, 1, 0),
(59, '2017-09-05 00:00:00', '10:00:00', '20:00:00', 1, 1, 0),
(60, '2017-09-06 00:00:00', '02:00:00', '09:00:00', 1, 1, 0),
(61, '2017-09-13 00:00:00', '09:00:00', '19:00:00', 1, 1, 0),
(62, '2017-09-14 00:00:00', '10:00:00', '20:00:00', 1, 1, 0),
(63, '2017-09-13 00:00:00', '10:00:00', '20:00:00', 1, 1, 0),
(64, '2017-09-16 00:00:00', '09:00:00', '19:00:00', 1, 1, 0),
(65, '2017-09-15 00:00:00', '09:16:00', '19:16:00', 1, 1, 0),
(66, '2017-09-12 00:00:00', '10:00:00', '20:00:00', 1, 1, 0),
(67, '2017-09-13 00:00:00', '10:00:00', '20:00:00', 1, 1, 0),
(68, '2017-09-11 00:00:00', '10:20:00', '21:40:00', 1, 1, 0),
(69, '2017-09-06 00:00:00', '12:00:00', '14:00:00', 1, 1, 0),
(70, '2017-09-12 00:00:00', '00:00:00', '23:59:00', 2, 1, 0),
(71, '2017-09-18 00:00:00', '09:00:00', '23:59:00', 2, 1, 0),
(72, '2017-09-22 00:00:00', '08:00:00', '23:59:00', 2, 1, 0),
(73, '2017-09-23 00:00:00', '00:22:12', '00:30:12', 1, 2, 1),
(74, '2017-09-22 00:00:00', '23:23:56', '23:33:56', 1, 2, 0),
(75, '2017-09-22 00:00:00', '11:00:00', '21:00:00', 1, 1, 0),
(76, '2017-09-22 00:00:00', '10:00:00', '21:00:00', 1, 1, 1),
(77, '2017-09-23 00:00:00', '13:00:00', '23:59:00', 2, 1, 0),
(78, '2017-09-24 00:00:00', '01:00:00', '11:00:00', 1, 1, 1),
(79, '2017-09-24 00:00:00', '12:00:00', '23:59:00', 2, 1, 0),
(80, '2017-09-27 00:00:00', '01:00:00', '22:59:00', 2, 1, 0),
(81, '2017-09-28 00:00:00', '00:00:01', '00:59:00', 2, 1, 0),
(82, '2017-10-08 00:00:00', '11:00:00', '23:59:00', 2, 1, 0),
(83, '2017-10-09 00:00:00', '00:00:01', '00:00:00', 2, 1, 0),
(84, '2017-10-14 00:00:00', '08:00:00', '16:00:00', 2, 1, 0),
(85, '2017-10-14 00:00:00', '14:29:14', '14:49:14', 2, 2, 0),
(86, '2017-10-14 00:00:00', '14:00:00', '20:00:00', 2, 1, 0),
(87, '2017-10-14 00:00:00', '16:50:34', '17:15:34', 2, 2, 0),
(88, '2017-10-14 00:00:00', '16:51:25', '18:17:25', 2, 2, 0),
(89, '2017-10-14 00:00:00', '16:54:02', '17:04:02', 2, 2, 0),
(90, '2017-10-14 00:00:00', '01:00:00', '23:59:00', 2, 1, 0),
(91, '2017-10-15 00:00:00', '00:00:01', '00:59:00', 2, 1, 0),
(92, '2017-10-14 00:00:00', '16:55:41', '17:16:41', 2, 2, 0),
(93, '2017-10-14 00:00:00', '17:14:39', '17:35:39', 2, 2, 0),
(94, '2017-10-15 00:00:00', '11:00:00', '21:00:00', 1, 1, 0),
(95, '2017-10-22 00:00:00', '21:54:32', '23:13:32', 2, 2, 1),
(96, '2017-10-28 00:00:00', '20:19:42', '21:19:42', 2, 2, 1),
(97, '2017-10-29 00:00:00', '10:42:47', '11:42:47', 1, 2, 0),
(98, '2017-10-29 00:00:00', '20:56:00', '21:56:00', 2, 2, 1),
(99, '2017-11-04 00:00:00', '21:27:55', '22:27:55', 2, 2, 1),
(100, '2017-11-08 00:00:00', '22:00:00', '23:00:00', 2, 1, 0),
(101, '2017-11-09 00:00:00', '11:00:00', '12:11:00', 1, 0, 1),
(102, '2017-11-09 00:00:00', '18:00:00', '23:00:00', 2, 0, 1),
(103, '2017-11-16 00:00:00', '18:00:00', '23:59:59', 2, 0, 1),
(104, '2017-11-17 00:00:00', '00:00:01', '00:00:00', 2, 0, 1),
(105, '2017-11-18 00:00:00', '21:00:00', '23:59:00', 1, 0, 0),
(106, '2017-11-18 00:00:00', '22:06:40', '22:26:40', 1, 2, 0),
(107, '2017-12-11 00:00:00', '10:00:00', '20:00:00', 1, 0, 0),
(108, '2017-12-11 00:00:00', '10:00:00', '23:00:00', 1, 0, 1),
(109, '2017-12-12 00:00:00', '10:00:00', '23:00:00', 1, 0, 0),
(110, '2017-12-23 00:00:00', '22:57:09', '23:59:59', 2, 2, 1),
(111, '2017-12-24 00:00:00', '00:00:01', '01:57:09', 2, 2, 1),
(112, '2017-12-23 00:00:00', '22:57:30', '23:59:59', 2, 2, 1),
(113, '2017-12-24 00:00:00', '00:00:01', '01:57:30', 2, 2, 1),
(114, '2017-12-23 00:00:00', '22:57:38', '23:59:59', 2, 2, 1),
(115, '2017-12-24 00:00:00', '00:00:01', '00:11:38', 2, 2, 1),
(116, '2017-12-23 00:00:00', '22:57:41', '23:34:41', 2, 2, 1),
(117, '2017-12-25 00:00:00', '22:34:39', '23:34:39', 2, 2, 1),
(118, '2017-12-29 00:00:00', '23:01:09', '23:59:59', 2, 2, 1),
(119, '2017-12-30 00:00:00', '00:00:01', '00:01:09', 2, 2, 1),
(120, '2017-12-31 00:00:00', '00:00:00', '00:00:00', 2, 0, 1),
(121, '2018-01-01 00:00:00', '00:00:00', '00:00:00', 2, 0, 1),
(122, '2018-01-02 00:00:00', '00:00:00', '00:00:00', 2, 0, 1),
(123, '2018-01-03 00:00:00', '00:00:00', '00:00:00', 2, 0, 1),
(124, '2017-01-04 00:00:00', '00:00:00', '00:00:00', 2, 0, 1),
(125, '2018-01-14 00:00:00', '22:25:17', '23:25:17', 2, 2, 1),
(126, '2018-01-17 00:00:00', '22:12:00', '22:25:00', 2, 0, 0),
(127, '2018-01-31 00:00:00', '20:36:00', '21:00:00', 2, 1, 0),
(128, '2018-01-31 00:00:00', '23:40:00', '23:59:59', 2, 1, 0),
(129, '2018-02-01 00:00:00', '00:00:01', '01:00:00', 2, 1, 0),
(130, '2018-02-01 00:00:00', '00:00:00', '03:00:00', 2, 1, 0),
(131, '2018-01-31 00:00:00', '23:00:00', '23:59:00', 2, 1, 0),
(132, '2018-02-02 00:00:00', '21:36:11', '22:36:11', 2, 2, 1),
(133, '2018-02-02 00:00:00', '21:36:42', '22:36:42', 2, 2, 1),
(134, '2018-02-13 00:00:00', '21:23:33', '23:10:33', 2, 2, 1),
(135, '2018-02-18 00:00:00', '21:39:02', '23:28:02', 2, 2, 1),
(136, '2018-03-26 00:00:00', '22:03:18', '23:37:18', 2, 2, 1),
(137, '2018-03-27 00:00:00', '22:45:21', '23:45:21', 2, 2, 1),
(138, '2018-03-29 00:00:00', '22:48:28', '23:48:28', 2, 2, 1),
(139, '2018-04-01 00:00:00', '22:42:27', '23:42:27', 2, 2, 1),
(140, '2018-05-02 00:00:00', '18:00:00', '23:00:00', 2, 1, 1),
(141, '2018-05-05 00:00:00', '22:03:16', '23:33:16', 2, 2, 1),
(142, '2018-05-05 00:00:00', '23:48:45', '23:59:59', 2, 2, 1),
(143, '2018-05-06 00:00:00', '00:00:01', '01:18:45', 2, 2, 1),
(144, '2018-05-11 00:00:00', '22:57:05', '23:57:05', 2, 2, 1),
(145, '2018-06-10 00:00:00', '22:55:07', '23:55:07', 2, 2, 1),
(146, '2018-06-17 00:00:00', '20:27:34', '21:27:34', 2, 2, 1),
(147, '2018-06-17 00:00:00', '22:06:34', '23:06:34', 2, 2, 1),
(148, '2018-06-21 00:00:00', '21:46:19', '23:59:59', 2, 2, 1),
(149, '2018-06-22 00:00:00', '00:00:01', '00:20:19', 2, 2, 1),
(150, '2018-06-21 00:00:00', '21:46:25', '23:59:59', 2, 2, 1),
(151, '2018-06-22 00:00:00', '00:00:01', '00:05:25', 2, 2, 1),
(152, '2018-06-21 00:00:00', '21:46:32', '23:43:32', 2, 2, 1),
(153, '2018-07-01 00:00:00', '22:40:26', '23:40:26', 2, 2, 1),
(154, '2018-07-02 00:00:00', '21:30:17', '23:28:17', 2, 2, 1),
(155, '2018-08-04 00:00:00', '21:31:29', '22:31:29', 2, 2, 1),
(156, '2018-09-16 00:00:00', '20:27:04', '21:27:04', 2, 2, 1);

--
-- Acionadores `horario_especial`
--
DELIMITER $$
CREATE TRIGGER `trigger_validacao_horario_especial_pizzaria_bi` BEFORE INSERT ON `horario_especial` FOR EACH ROW BEGIN
-- VALIDAÇÃO DE VALORES VAZIOS/NULOS
	if(new.data_horario_especial is null or new.data_horario_especial = '') then
		signal sqlstate '45000' set message_text = 'DATA não pode ser vazio/nulo.';
    end if;
    if(new.INICIO_horario_ESPECIAL is NOT null AND new.INICIO_horario_ESPECIAL != '') then
			if(new.INICIO_horario_ESPECIAL > '23:59:59' and new.INICIO_horario_ESPECIAL < '00:00:00') then
				signal sqlstate '45000' set message_text = 'HORA DE INÍCIO inválido.';
			end if;
    end if;  
    if(new.FIM_horario_ESPECIAL is NOT null AND new.FIM_horario_ESPECIAL != '') then
			if(new.FIM_horario_ESPECIAL > '23:59:59' and new.FIM_horario_ESPECIAL < '00:00:00') then
				signal sqlstate '45000' set message_text = 'HORA DE FIM inválido.';
			end if;
    end if; 
    if(new.PIZZARIA_horario_especial is null or new.PIZZARIA_horario_especial = '') then
		signal sqlstate '45000' set message_text = 'PIZZARIA não pode ser vazio/nulo.';
    end if;
    if(new.ABERTO_HORARIO_ESPECIAL is null or new.ABERTO_HORARIO_ESPECIAL < 0) then
		signal sqlstate '45000' set message_text = 'ABERTO não pode ser vazio/nulo.';
	else if (new.ABERTO_HORARIO_ESPECIAL not in (0,1,2)) then
			signal sqlstate '45000' set message_text = 'ABERTO inválido.';
        end if;
	end if;   
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trigger_validacao_horario_especial_pizzaria_bu` BEFORE UPDATE ON `horario_especial` FOR EACH ROW BEGIN
-- VALIDAÇÃO DE VALORES VAZIOS/NULOS
	if(new.data_horario_especial is null or new.data_horario_especial = '') then
		signal sqlstate '45000' set message_text = 'DATA não pode ser vazio/nulo.';
    end if;
    if(new.INICIO_horario_ESPECIAL is NOT null AND new.INICIO_horario_ESPECIAL != '') then
			if(new.INICIO_horario_ESPECIAL > '23:59:59' and new.INICIO_horario_ESPECIAL < '00:00:00') then
				signal sqlstate '45000' set message_text = 'HORA DE INÍCIO inválido.';
			end if;
    end if;  
    if(new.FIM_horario_ESPECIAL is NOT null AND new.FIM_horario_ESPECIAL != '') then
			if(new.FIM_horario_ESPECIAL > '23:59:59' and new.FIM_horario_ESPECIAL < '00:00:00') then
				signal sqlstate '45000' set message_text = 'HORA DE FIM inválido.';
			end if;
    end if; 
    if(new.PIZZARIA_horario_especial is null or new.PIZZARIA_horario_especial = '') then
		signal sqlstate '45000' set message_text = 'PIZZARIA não pode ser vazio/nulo.';
    end if;
    if(new.ABERTO_HORARIO_ESPECIAL is null or new.ABERTO_HORARIO_ESPECIAL < 0) then
		signal sqlstate '45000' set message_text = 'ABERTO não pode ser vazio/nulo.';
	else if (new.ABERTO_HORARIO_ESPECIAL not in (0,1,2)) then
			signal sqlstate '45000' set message_text = 'ABERTO inválido.';
        end if;
	end if; 
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `item_extra_pizza`
--

CREATE TABLE `item_extra_pizza` (
  `codigo_item_extra_pizza` bigint(20) NOT NULL,
  `pizza_item_extra_pizza` bigint(20) NOT NULL COMMENT 'fk para pizza do pedido',
  `extra_pizza_item_extra_pizza` bigint(20) NOT NULL COMMENT 'fk para extra do pedido'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `item_extra_pizza`
--

INSERT INTO `item_extra_pizza` (`codigo_item_extra_pizza`, `pizza_item_extra_pizza`, `extra_pizza_item_extra_pizza`) VALUES
(329, 521, 49),
(330, 523, 14),
(331, 524, 1),
(332, 535, 46),
(333, 536, 11),
(334, 536, 49),
(335, 537, 53),
(336, 537, 11),
(337, 537, 11),
(338, 539, 16),
(339, 541, 52),
(340, 542, 54),
(341, 548, 14),
(342, 549, 53),
(343, 549, 54),
(344, 549, 54),
(345, 549, 54),
(346, 550, 53),
(347, 550, 54),
(348, 550, 54),
(349, 550, 54),
(350, 551, 45),
(351, 552, 2),
(352, 553, 2),
(353, 554, 5),
(354, 555, 17),
(355, 561, 16),
(356, 562, 2),
(357, 562, 4),
(358, 562, 7),
(359, 563, 9),
(360, 563, 4),
(361, 563, 7),
(362, 563, 10),
(363, 564, 2),
(364, 564, 7),
(365, 564, 5),
(366, 566, 2),
(367, 566, 4),
(368, 566, 7),
(369, 567, 9),
(370, 567, 5),
(371, 567, 10),
(372, 567, 45),
(373, 568, 56),
(374, 568, 54),
(375, 568, 56),
(376, 569, 14),
(377, 574, 52),
(378, 575, 52),
(379, 584, 9),
(380, 584, 4),
(381, 584, 8),
(382, 585, 5),
(383, 586, 9),
(384, 588, 57),
(385, 589, 14),
(386, 590, 14),
(387, 610, 2),
(388, 611, 2),
(389, 613, 2),
(390, 616, 2),
(391, 616, 3),
(392, 617, 2),
(393, 617, 3),
(394, 618, 2),
(395, 618, 3),
(396, 619, 2),
(397, 619, 3),
(398, 620, 2),
(399, 620, 3),
(400, 621, 2),
(401, 621, 3),
(402, 622, 53),
(403, 624, 2),
(404, 625, 2),
(405, 625, 3),
(406, 630, 2),
(407, 631, 2),
(408, 633, 56),
(409, 633, 53),
(410, 634, 9),
(411, 634, 4),
(412, 635, 52),
(413, 635, 55),
(414, 636, 9),
(415, 636, 3),
(416, 637, 52),
(417, 637, 55),
(418, 638, 9),
(419, 638, 3),
(420, 640, 20),
(421, 641, 16),
(422, 646, 56),
(423, 646, 53),
(424, 647, 9),
(425, 647, 4),
(426, 648, 56),
(427, 648, 53),
(428, 649, 9),
(429, 649, 4),
(430, 650, 56),
(431, 650, 53),
(432, 651, 9),
(433, 651, 4),
(434, 655, 56),
(435, 655, 53),
(436, 656, 9),
(437, 656, 4),
(438, 657, 56),
(439, 657, 53),
(440, 658, 9),
(441, 658, 4),
(442, 659, 57),
(443, 659, 57),
(444, 661, 53),
(445, 667, 53),
(446, 668, 53),
(447, 669, 53),
(448, 670, 53),
(449, 671, 53),
(450, 672, 53),
(451, 673, 53),
(452, 674, 53),
(453, 675, 53),
(454, 676, 53),
(455, 677, 53),
(456, 678, 53),
(457, 679, 53),
(458, 680, 53),
(459, 681, 53),
(460, 682, 53),
(461, 683, 53),
(462, 684, 53),
(463, 685, 53),
(464, 686, 53),
(465, 687, 2),
(466, 688, 2),
(467, 689, 16),
(468, 690, 34),
(469, 691, 19),
(470, 704, 2),
(471, 706, 54),
(472, 707, 2),
(473, 707, 3),
(474, 709, 2),
(475, 709, 4),
(476, 710, 16),
(477, 711, 16),
(478, 712, 14),
(479, 713, 2),
(480, 713, 3),
(481, 715, 2),
(482, 715, 4),
(483, 716, 2),
(484, 716, 3),
(485, 718, 2),
(486, 718, 4),
(487, 721, 9),
(488, 725, 53),
(489, 725, 56),
(490, 725, 56),
(491, 726, 52),
(492, 726, 55),
(493, 726, 55),
(494, 727, 2),
(495, 734, 61),
(496, 734, 67),
(497, 736, 83),
(498, 738, 60),
(499, 739, 60),
(500, 740, 60),
(501, 742, 60),
(502, 744, 62),
(503, 745, 65),
(504, 745, 68),
(505, 746, 65),
(506, 746, 68),
(507, 747, 16),
(508, 751, 14),
(509, 752, 14),
(510, 753, 14),
(511, 754, 18),
(512, 755, 60),
(513, 756, 14),
(514, 757, 18),
(515, 760, 60),
(516, 761, 19),
(517, 765, 65),
(518, 766, 65),
(519, 767, 65),
(520, 768, 65),
(521, 769, 65),
(522, 770, 65),
(523, 771, 65),
(524, 772, 65),
(525, 773, 65),
(526, 774, 65),
(527, 775, 65),
(528, 781, 60),
(529, 781, 66),
(530, 782, 60),
(531, 782, 66),
(532, 783, 60),
(533, 783, 66),
(534, 785, 60),
(535, 785, 66),
(536, 786, 60),
(537, 786, 66),
(538, 788, 60),
(539, 788, 66),
(540, 789, 60),
(541, 789, 66),
(542, 790, 63),
(543, 791, 16),
(544, 792, 20),
(545, 793, 63),
(546, 794, 63),
(547, 795, 63),
(548, 796, 63),
(549, 797, 63),
(550, 798, 63),
(551, 799, 63),
(552, 800, 63),
(553, 801, 63),
(554, 802, 63),
(555, 803, 63),
(556, 804, 63),
(557, 805, 63),
(558, 806, 63),
(559, 807, 63),
(560, 808, 60),
(561, 809, 60),
(562, 811, 63),
(563, 812, 63),
(564, 813, 63),
(565, 814, 16),
(566, 815, 19),
(567, 819, 60),
(568, 820, 60),
(569, 821, 19),
(570, 822, 60),
(571, 823, 60),
(572, 824, 19),
(573, 825, 60),
(574, 827, 66),
(575, 837, 60),
(576, 837, 69),
(577, 837, 87),
(578, 839, 60),
(579, 839, 69),
(580, 839, 87),
(581, 841, 60),
(582, 847, 60),
(583, 848, 60),
(584, 849, 60),
(585, 850, 66),
(586, 851, 30),
(587, 856, 19),
(588, 857, 22),
(589, 858, 60),
(590, 859, 60),
(591, 863, 60),
(592, 866, 60),
(593, 867, 14),
(594, 868, 14),
(595, 875, 16),
(596, 876, 19),
(597, 877, 16),
(598, 880, 14),
(599, 881, 63),
(600, 882, 63),
(601, 883, 63),
(602, 883, 69),
(603, 884, 60),
(604, 884, 66),
(605, 887, 20),
(606, 888, 19),
(607, 913, 60),
(608, 913, 69),
(609, 913, 66),
(610, 913, 87),
(611, 914, 64),
(612, 914, 73),
(613, 914, 76),
(614, 914, 91),
(615, 915, 60),
(616, 915, 69),
(617, 915, 66),
(618, 915, 87),
(619, 916, 64),
(620, 916, 73),
(621, 916, 76),
(622, 916, 91),
(623, 917, 60),
(624, 917, 69),
(625, 917, 66),
(626, 917, 87),
(627, 918, 64),
(628, 918, 73),
(629, 918, 76),
(630, 918, 91),
(631, 919, 16),
(632, 920, 60),
(633, 920, 69),
(634, 920, 66),
(635, 920, 87),
(636, 921, 64),
(637, 921, 73),
(638, 921, 76),
(639, 921, 91),
(640, 922, 60),
(641, 923, 60),
(642, 924, 20),
(643, 925, 20),
(644, 926, 19),
(645, 927, 20),
(646, 928, 14),
(647, 929, 19),
(648, 930, 19),
(649, 931, 14),
(650, 932, 19);

--
-- Acionadores `item_extra_pizza`
--
DELIMITER $$
CREATE TRIGGER `trigger_validacao_item_extra_pizza_bi` BEFORE INSERT ON `item_extra_pizza` FOR EACH ROW begin
if(new.extra_pizza_item_extra_pizza is null or new.extra_pizza_item_extra_pizza < 0) then
	signal sqlstate '45000' set message_text = 'EXTRA PIZZA não pode ser vazio/nulo.';
end if;
if(new.pizza_item_extra_pizza is null or new.pizza_item_extra_pizza < 0) then
	signal sqlstate '45000' set message_text = 'PIZZA não pode ser vazio/nulo.';
end if;
end
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trigger_validacao_item_extra_pizza_bu` BEFORE UPDATE ON `item_extra_pizza` FOR EACH ROW begin
if(new.extra_pizza_item_extra_pizza is null or new.extra_pizza_item_extra_pizza < 0) then
	signal sqlstate '45000' set message_text = 'EXTRA PIZZA não pode ser vazio/nulo.';
end if;
if(new.pizza_item_extra_pizza is null or new.pizza_item_extra_pizza < 0) then
	signal sqlstate '45000' set message_text = 'PIZZA não pode ser vazio/nulo.';
end if;
end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `item_pedido`
--

CREATE TABLE `item_pedido` (
  `codigo_item_pedido` bigint(20) NOT NULL,
  `quantidade_item_pedido` decimal(10,2) UNSIGNED NOT NULL DEFAULT '1.00',
  `valor_subtotal_item_pedido` decimal(10,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `pedido_item_pedido` bigint(20) NOT NULL,
  `pizza_item_pedido` bigint(20) DEFAULT NULL,
  `bebida_item_pedido` bigint(20) DEFAULT NULL,
  `promocao_item_pedido` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `item_pedido`
--

INSERT INTO `item_pedido` (`codigo_item_pedido`, `quantidade_item_pedido`, `valor_subtotal_item_pedido`, `pedido_item_pedido`, `pizza_item_pedido`, `bebida_item_pedido`, `promocao_item_pedido`) VALUES
(776, '1.00', '35.00', 404, 521, NULL, NULL),
(777, '1.00', '29.00', 404, 522, NULL, NULL),
(778, '1.00', '5.50', 404, NULL, 1, NULL),
(779, '1.00', '10.00', 404, NULL, NULL, NULL),
(780, '1.00', '45.00', 405, 523, NULL, NULL),
(781, '1.00', '7.00', 405, NULL, 14, NULL),
(782, '1.00', '5.00', 405, NULL, NULL, NULL),
(783, '1.00', '35.00', 406, 524, NULL, NULL),
(784, '1.00', '28.00', 406, 525, NULL, NULL),
(785, '1.00', '29.00', 406, 526, NULL, NULL),
(786, '1.00', '24.00', 406, 527, NULL, NULL),
(787, '1.00', '26.50', 406, 528, NULL, NULL),
(788, '1.00', '25.00', 406, 529, NULL, NULL),
(789, '1.00', '31.75', 406, 530, NULL, NULL),
(790, '1.00', '31.00', 406, 531, NULL, NULL),
(791, '1.00', '24.00', 406, 532, NULL, NULL),
(792, '1.00', '10.00', 406, NULL, 13, NULL),
(793, '1.00', '10.00', 406, NULL, NULL, NULL),
(794, '1.00', '30.00', 407, 533, NULL, NULL),
(795, '1.00', '10.00', 407, NULL, NULL, NULL),
(796, '1.00', '0.00', 408, 534, NULL, NULL),
(797, '1.00', '10.00', 408, NULL, NULL, NULL),
(798, '1.00', '32.00', 409, 535, NULL, NULL),
(799, '1.00', '37.50', 409, 536, NULL, NULL),
(800, '1.00', '5.50', 409, NULL, 1, NULL),
(801, '1.00', '10.00', 409, NULL, NULL, NULL),
(802, '1.00', '51.75', 410, 537, NULL, NULL),
(803, '1.00', '25.00', 410, 538, NULL, NULL),
(804, '1.00', '7.00', 410, NULL, 12, NULL),
(805, '1.00', '1.00', 410, NULL, NULL, NULL),
(806, '1.00', '45.00', 411, 539, NULL, NULL),
(807, '1.00', '5.00', 411, NULL, NULL, NULL),
(808, '1.00', '30.00', 412, 540, NULL, NULL),
(809, '1.00', '10.00', 412, NULL, NULL, NULL),
(810, '1.00', '35.00', 413, 541, NULL, NULL),
(811, '1.00', '38.00', 413, 542, NULL, NULL),
(812, '1.00', '7.00', 413, NULL, 2, NULL),
(813, '1.00', '7.00', 413, NULL, 3, NULL),
(814, '1.00', '9.00', 413, NULL, NULL, NULL),
(815, '1.00', '39.00', 414, 543, NULL, NULL),
(816, '1.00', '7.00', 414, NULL, NULL, NULL),
(817, '1.00', '30.00', 415, 544, NULL, NULL),
(818, '1.00', '25.00', 415, 545, NULL, NULL),
(819, '1.00', '1.00', 415, NULL, NULL, NULL),
(820, '1.00', '30.00', 416, 546, NULL, NULL),
(821, '1.00', '25.00', 416, 547, NULL, NULL),
(822, '1.00', '45.00', 417, 548, NULL, NULL),
(823, '1.00', '7.00', 417, NULL, 17, NULL),
(824, '1.00', '5.00', 417, NULL, NULL, NULL),
(825, '1.00', '59.00', 418, 549, NULL, NULL),
(826, '1.00', '1.00', 418, NULL, NULL, NULL),
(827, '1.00', '59.00', 419, 550, NULL, NULL),
(828, '1.00', '41.00', 420, 551, NULL, NULL),
(829, '1.00', '7.00', 420, NULL, 2, NULL),
(830, '1.00', '1.00', 420, NULL, NULL, NULL),
(831, '1.00', '42.00', 421, 552, NULL, NULL),
(832, '1.00', '1.00', 421, NULL, NULL, NULL),
(833, '1.00', '42.00', 422, 553, NULL, NULL),
(834, '1.00', '40.00', 423, 554, NULL, NULL),
(835, '1.00', '7.00', 423, NULL, 3, NULL),
(836, '1.00', '10.00', 423, NULL, NULL, NULL),
(837, '1.00', '80.00', 424, 555, NULL, NULL),
(838, '1.00', '7.00', 424, NULL, 15, NULL),
(839, '1.00', '5.00', 424, NULL, NULL, NULL),
(840, '1.00', '37.00', 425, 556, NULL, NULL),
(841, '1.00', '1.00', 425, NULL, NULL, NULL),
(842, '1.00', '37.00', 426, 557, NULL, NULL),
(843, '1.00', '1.00', 426, NULL, NULL, NULL),
(844, '1.00', '37.00', 427, 558, NULL, NULL),
(845, '1.00', '1.00', 427, NULL, NULL, NULL),
(846, '1.00', '37.00', 428, 559, NULL, NULL),
(847, '1.00', '1.00', 428, NULL, NULL, NULL),
(848, '1.00', '37.00', 429, 560, NULL, NULL),
(849, '1.00', '10.00', 429, NULL, NULL, NULL),
(850, '1.00', '45.00', 430, 561, NULL, NULL),
(851, '1.00', '5.00', 430, NULL, NULL, NULL),
(852, '1.00', '47.00', 431, 562, NULL, NULL),
(853, '1.00', '9.00', 431, NULL, NULL, NULL),
(854, '1.00', '53.50', 432, 563, NULL, NULL),
(855, '1.00', '48.00', 432, 564, NULL, NULL),
(856, '1.00', '5.00', 432, NULL, 4, NULL),
(857, '1.00', '9.00', 432, NULL, NULL, NULL),
(858, '1.00', '37.00', 433, 565, NULL, NULL),
(859, '1.00', '9.00', 433, NULL, NULL, NULL),
(860, '1.00', '49.00', 434, 566, NULL, NULL),
(861, '1.00', '52.50', 434, 567, NULL, NULL),
(862, '1.00', '48.00', 434, 568, NULL, NULL),
(863, '1.00', '7.00', 434, NULL, 3, NULL),
(864, '1.00', '9.00', 434, NULL, NULL, NULL),
(865, '1.00', '45.00', 435, 569, NULL, NULL),
(866, '1.00', '5.00', 435, NULL, NULL, NULL),
(867, '1.00', '30.00', 436, 570, NULL, NULL),
(868, '1.00', '5.00', 436, NULL, NULL, NULL),
(869, '1.00', '30.00', 439, 571, NULL, NULL),
(870, '1.00', '1.00', 439, NULL, NULL, NULL),
(871, '1.00', '30.00', 440, 572, NULL, NULL),
(872, '1.00', '1.00', 440, NULL, NULL, NULL),
(873, '1.00', '30.00', 443, 573, NULL, NULL),
(874, '1.00', '35.00', 444, 574, NULL, NULL),
(875, '1.00', '1.00', 444, NULL, NULL, NULL),
(876, '1.00', '35.00', 445, 575, NULL, NULL),
(877, '1.00', '1.00', 445, NULL, NULL, NULL),
(878, '1.00', '16.00', 446, 576, NULL, NULL),
(879, '1.00', '7.00', 446, NULL, 3, NULL),
(880, '1.00', '1.00', 446, NULL, NULL, NULL),
(881, '1.00', '35.00', 447, 577, NULL, NULL),
(882, '1.00', '9.00', 447, NULL, NULL, NULL),
(883, '1.00', '35.00', 448, 578, NULL, NULL),
(884, '1.00', '35.00', 449, 579, NULL, NULL),
(885, '1.00', '9.00', 449, NULL, NULL, NULL),
(886, '1.00', '35.00', 450, 580, NULL, NULL),
(887, '1.00', '37.00', 451, 581, NULL, NULL),
(888, '1.00', '9.00', 451, NULL, NULL, NULL),
(889, '1.00', '37.00', 452, 582, NULL, NULL),
(890, '1.00', '37.00', 453, 583, NULL, NULL),
(891, '1.00', '48.00', 454, 584, NULL, NULL),
(892, '1.00', '39.00', 454, 585, NULL, NULL),
(893, '1.00', '7.00', 454, NULL, 3, NULL),
(894, '1.00', '8.00', 454, NULL, 5, NULL),
(895, '1.00', '10.00', 454, NULL, NULL, NULL),
(896, '1.00', '41.00', 455, 586, NULL, NULL),
(897, '1.00', '7.00', 455, NULL, 3, NULL),
(898, '1.00', '9.00', 455, NULL, NULL, NULL),
(899, '1.00', '28.00', 456, 587, NULL, NULL),
(900, '1.00', '1.00', 456, NULL, NULL, NULL),
(901, '1.00', '40.00', 457, 588, NULL, NULL),
(902, '1.00', '5.00', 457, NULL, 4, NULL),
(903, '1.00', '10.00', 457, NULL, NULL, NULL),
(909, '1.00', '30.00', 460, 591, NULL, NULL),
(910, '1.00', '1.00', 460, NULL, NULL, NULL),
(911, '1.00', '40.00', 461, 592, NULL, NULL),
(912, '1.00', '8.00', 461, NULL, 24, NULL),
(913, '1.00', '10.00', 461, NULL, NULL, NULL),
(918, '1.00', '40.00', 466, 596, NULL, NULL),
(919, '1.00', '8.00', 466, NULL, 24, NULL),
(920, '1.00', '10.00', 466, NULL, NULL, NULL),
(921, '1.00', '40.00', 467, 597, NULL, NULL),
(922, '1.00', '8.00', 467, NULL, 24, NULL),
(923, '1.00', '10.00', 467, NULL, NULL, NULL),
(924, '1.00', '40.00', 468, 598, NULL, NULL),
(925, '1.00', '8.00', 468, NULL, 24, NULL),
(926, '1.00', '10.00', 468, NULL, NULL, NULL),
(927, '1.00', '40.00', 469, 599, NULL, NULL),
(928, '1.00', '8.00', 469, NULL, 24, NULL),
(929, '1.00', '10.00', 469, NULL, NULL, NULL),
(930, '1.00', '40.00', 470, 600, NULL, NULL),
(931, '1.00', '8.00', 470, NULL, 24, NULL),
(932, '1.00', '10.00', 470, NULL, NULL, NULL),
(933, '1.00', '40.00', 471, 601, NULL, NULL),
(934, '1.00', '8.00', 471, NULL, 24, NULL),
(935, '1.00', '10.00', 471, NULL, NULL, NULL),
(936, '1.00', '40.00', 472, 602, NULL, NULL),
(937, '1.00', '8.00', 472, NULL, 24, NULL),
(938, '1.00', '10.00', 472, NULL, NULL, NULL),
(939, '1.00', '40.00', 473, 603, NULL, NULL),
(940, '1.00', '8.00', 473, NULL, 24, NULL),
(941, '1.00', '10.00', 473, NULL, NULL, NULL),
(942, '1.00', '40.00', 474, 604, NULL, NULL),
(943, '1.00', '8.00', 474, NULL, 24, NULL),
(944, '1.00', '10.00', 474, NULL, NULL, NULL),
(945, '1.00', '40.00', 475, 605, NULL, NULL),
(946, '1.00', '8.00', 475, NULL, 24, NULL),
(947, '1.00', '10.00', 475, NULL, NULL, NULL),
(948, '1.00', '40.00', 476, 606, NULL, NULL),
(949, '1.00', '8.00', 476, NULL, 24, NULL),
(950, '1.00', '10.00', 476, NULL, NULL, NULL),
(951, '1.00', '40.00', 477, 607, NULL, NULL),
(952, '1.00', '8.00', 477, NULL, 24, NULL),
(953, '1.00', '10.00', 477, NULL, NULL, NULL),
(954, '1.00', '40.00', 478, 608, NULL, NULL),
(955, '1.00', '8.00', 478, NULL, 24, NULL),
(956, '1.00', '10.00', 478, NULL, NULL, NULL),
(957, '1.00', '44.00', 479, 609, NULL, NULL),
(958, '1.00', '10.00', 479, NULL, NULL, NULL),
(959, '1.00', '40.00', 480, 610, NULL, NULL),
(960, '1.00', '10.00', 480, NULL, NULL, NULL),
(961, '1.00', '40.00', 481, 611, NULL, NULL),
(962, '1.00', '10.00', 481, NULL, NULL, NULL),
(963, '1.00', '35.00', 482, 612, NULL, NULL),
(964, '1.00', '10.00', 482, NULL, NULL, NULL),
(965, '1.00', '49.00', 483, 613, NULL, NULL),
(966, '1.00', '5.00', 483, NULL, 4, NULL),
(967, '1.00', '10.00', 483, NULL, NULL, NULL),
(968, '1.00', '35.00', 484, 614, NULL, NULL),
(969, '1.00', '5.00', 484, NULL, 4, NULL),
(970, '1.00', '10.00', 484, NULL, NULL, NULL),
(971, '1.00', '44.00', 485, 615, NULL, NULL),
(972, '1.00', '10.00', 485, NULL, NULL, NULL),
(973, '1.00', '45.00', 486, 616, NULL, NULL),
(974, '1.00', '5.00', 486, NULL, 4, NULL),
(975, '1.00', '10.00', 486, NULL, NULL, NULL),
(976, '1.00', '45.00', 487, 617, NULL, NULL),
(977, '1.00', '5.00', 487, NULL, 4, NULL),
(978, '1.00', '10.00', 487, NULL, NULL, NULL),
(979, '1.00', '45.00', 488, 618, NULL, NULL),
(980, '1.00', '5.00', 488, NULL, 4, NULL),
(981, '1.00', '10.00', 488, NULL, NULL, NULL),
(982, '1.00', '45.00', 489, 619, NULL, NULL),
(983, '1.00', '5.00', 489, NULL, 4, NULL),
(984, '1.00', '10.00', 489, NULL, NULL, NULL),
(985, '1.00', '45.00', 490, 620, NULL, NULL),
(986, '1.00', '5.00', 490, NULL, 4, NULL),
(987, '1.00', '10.00', 490, NULL, NULL, NULL),
(988, '1.00', '45.00', 491, 621, NULL, NULL),
(989, '1.00', '5.00', 491, NULL, 4, NULL),
(990, '1.00', '10.00', 491, NULL, NULL, NULL),
(991, '1.00', '41.00', 492, 622, NULL, NULL),
(992, '1.00', '35.00', 492, 623, NULL, NULL),
(993, '1.00', '5.00', 492, NULL, 4, NULL),
(994, '1.00', '10.00', 492, NULL, NULL, NULL),
(995, '1.00', '40.00', 493, 624, NULL, NULL),
(996, '1.00', '45.00', 493, 625, NULL, NULL),
(997, '1.00', '5.00', 493, NULL, 4, NULL),
(998, '1.00', '10.00', 493, NULL, NULL, NULL),
(999, '1.00', '35.00', 494, 626, NULL, NULL),
(1000, '1.00', '5.00', 494, NULL, 4, NULL),
(1001, '1.00', '10.00', 494, NULL, NULL, NULL),
(1002, '1.00', '44.00', 495, 627, NULL, NULL),
(1003, '1.00', '10.00', 495, NULL, NULL, NULL),
(1004, '1.00', '44.00', 496, 628, NULL, NULL),
(1005, '1.00', '10.00', 496, NULL, NULL, NULL),
(1006, '1.00', '35.00', 497, 629, NULL, NULL),
(1007, '1.00', '10.00', 497, NULL, NULL, NULL),
(1008, '1.00', '40.00', 498, 630, NULL, NULL),
(1009, '1.00', '5.00', 498, NULL, 4, NULL),
(1010, '1.00', '10.00', 498, NULL, NULL, NULL),
(1011, '1.00', '40.00', 499, 631, NULL, NULL),
(1012, '1.00', '5.00', 499, NULL, 4, NULL),
(1013, '1.00', '10.00', 499, NULL, NULL, NULL),
(1014, '1.00', '36.00', 500, 632, NULL, NULL),
(1015, '1.00', '5.00', 500, NULL, 4, NULL),
(1016, '1.00', '1.00', 500, NULL, NULL, NULL),
(1017, '1.00', '41.00', 501, 633, NULL, NULL),
(1018, '1.00', '49.50', 501, 634, NULL, NULL),
(1019, '2.00', '10.00', 501, NULL, 4, NULL),
(1020, '1.00', '6.00', 501, NULL, 26, NULL),
(1021, '1.00', '10.00', 501, NULL, NULL, NULL),
(1022, '1.00', '40.00', 502, 635, NULL, NULL),
(1023, '1.00', '50.50', 502, 636, NULL, NULL),
(1024, '1.00', '5.00', 502, NULL, 4, NULL),
(1025, '1.00', '8.00', 502, NULL, 5, NULL),
(1026, '1.00', '10.00', 502, NULL, NULL, NULL),
(1027, '1.00', '40.00', 503, 637, NULL, NULL),
(1028, '1.00', '50.50', 503, 638, NULL, NULL),
(1029, '1.00', '5.00', 503, NULL, 4, NULL),
(1030, '1.00', '8.00', 503, NULL, 5, NULL),
(1031, '1.00', '10.00', 503, NULL, NULL, NULL),
(1032, '1.00', '65.00', 504, 639, NULL, NULL),
(1033, '1.00', '5.00', 504, NULL, NULL, NULL),
(1034, '1.00', '40.90', 505, 640, NULL, NULL),
(1035, '1.00', '5.00', 505, NULL, NULL, NULL),
(1036, '1.00', '45.00', 506, 641, NULL, NULL),
(1037, '1.00', '7.00', 506, NULL, 14, NULL),
(1038, '1.00', '5.00', 506, NULL, NULL, NULL),
(1039, '1.00', '28.00', 507, 642, NULL, NULL),
(1040, '1.00', '10.00', 507, NULL, NULL, NULL),
(1041, '1.00', '28.00', 508, 643, NULL, NULL),
(1042, '1.00', '10.00', 508, NULL, NULL, NULL),
(1043, '1.00', '28.00', 509, 644, NULL, NULL),
(1044, '1.00', '10.00', 509, NULL, NULL, NULL),
(1045, '1.00', '28.00', 510, 645, NULL, NULL),
(1046, '1.00', '10.00', 510, NULL, NULL, NULL),
(1047, '1.00', '41.00', 511, 646, NULL, NULL),
(1048, '1.00', '49.50', 511, 647, NULL, NULL),
(1049, '2.00', '10.00', 511, NULL, 4, NULL),
(1050, '1.00', '6.00', 511, NULL, 26, NULL),
(1051, '1.00', '10.00', 511, NULL, NULL, NULL),
(1052, '1.00', '41.00', 512, 648, NULL, NULL),
(1053, '1.00', '49.50', 512, 649, NULL, NULL),
(1054, '2.00', '10.00', 512, NULL, 4, NULL),
(1055, '1.00', '6.00', 512, NULL, 26, NULL),
(1056, '1.00', '10.00', 512, NULL, NULL, NULL),
(1057, '1.00', '41.00', 513, 650, NULL, NULL),
(1058, '1.00', '49.50', 513, 651, NULL, NULL),
(1059, '2.00', '10.00', 513, NULL, 4, NULL),
(1060, '1.00', '6.00', 513, NULL, 26, NULL),
(1061, '1.00', '10.00', 513, NULL, NULL, NULL),
(1062, '1.00', '28.00', 514, 652, NULL, NULL),
(1063, '1.00', '10.00', 514, NULL, NULL, NULL),
(1064, '1.00', '28.00', 515, 653, NULL, NULL),
(1065, '1.00', '10.00', 515, NULL, NULL, NULL),
(1066, '1.00', '28.00', 516, 654, NULL, NULL),
(1067, '1.00', '10.00', 516, NULL, NULL, NULL),
(1068, '1.00', '41.00', 517, 655, NULL, NULL),
(1069, '1.00', '49.50', 517, 656, NULL, NULL),
(1070, '2.00', '10.00', 517, NULL, 4, NULL),
(1071, '1.00', '6.00', 517, NULL, 26, NULL),
(1072, '1.00', '10.00', 517, NULL, NULL, NULL),
(1073, '1.00', '41.00', 518, 657, NULL, NULL),
(1074, '1.00', '49.50', 518, 658, NULL, NULL),
(1075, '2.00', '10.00', 518, NULL, 4, NULL),
(1076, '1.00', '6.00', 518, NULL, 26, NULL),
(1077, '1.00', '10.00', 518, NULL, NULL, NULL),
(1078, '1.00', '50.00', 519, 659, NULL, NULL),
(1079, '1.00', '1.00', 519, NULL, NULL, NULL),
(1080, '1.00', '44.00', 520, 660, NULL, NULL),
(1081, '1.00', '33.00', 521, 661, NULL, NULL),
(1082, '1.00', '10.00', 521, NULL, NULL, NULL),
(1083, '1.00', '36.00', 522, 662, NULL, NULL),
(1084, '1.00', '5.00', 522, NULL, 4, NULL),
(1085, '1.00', '1.00', 522, NULL, NULL, NULL),
(1086, '1.00', '36.00', 523, 663, NULL, NULL),
(1087, '1.00', '5.00', 523, NULL, 4, NULL),
(1088, '1.00', '1.00', 523, NULL, NULL, NULL),
(1089, '1.00', '36.00', 524, 664, NULL, NULL),
(1090, '1.00', '5.00', 524, NULL, 4, NULL),
(1091, '1.00', '1.00', 524, NULL, NULL, NULL),
(1092, '1.00', '30.00', 525, 665, NULL, NULL),
(1093, '1.00', '1.00', 525, NULL, NULL, NULL),
(1094, '1.00', '30.00', 526, 666, NULL, NULL),
(1095, '1.00', '1.00', 526, NULL, NULL, NULL),
(1096, '1.00', '33.00', 527, 667, NULL, NULL),
(1097, '1.00', '1.00', 527, NULL, NULL, NULL),
(1098, '1.00', '33.00', 528, 668, NULL, NULL),
(1099, '1.00', '1.00', 528, NULL, NULL, NULL),
(1100, '1.00', '33.00', 529, 669, NULL, NULL),
(1101, '1.00', '1.00', 529, NULL, NULL, NULL),
(1102, '1.00', '33.00', 530, 670, NULL, NULL),
(1103, '1.00', '1.00', 530, NULL, NULL, NULL),
(1104, '1.00', '33.00', 531, 671, NULL, NULL),
(1105, '1.00', '1.00', 531, NULL, NULL, NULL),
(1106, '1.00', '33.00', 532, 672, NULL, NULL),
(1107, '1.00', '1.00', 532, NULL, NULL, NULL),
(1108, '1.00', '33.00', 533, 673, NULL, NULL),
(1109, '1.00', '1.00', 533, NULL, NULL, NULL),
(1110, '1.00', '33.00', 534, 674, NULL, NULL),
(1111, '1.00', '1.00', 534, NULL, NULL, NULL),
(1112, '1.00', '33.00', 535, 675, NULL, NULL),
(1113, '1.00', '1.00', 535, NULL, NULL, NULL),
(1114, '1.00', '33.00', 536, 676, NULL, NULL),
(1115, '1.00', '1.00', 536, NULL, NULL, NULL),
(1116, '1.00', '33.00', 537, 677, NULL, NULL),
(1117, '1.00', '1.00', 537, NULL, NULL, NULL),
(1118, '1.00', '33.00', 538, 678, NULL, NULL),
(1119, '1.00', '10.00', 538, NULL, NULL, NULL),
(1120, '1.00', '33.00', 539, 679, NULL, NULL),
(1121, '1.00', '10.00', 539, NULL, NULL, NULL),
(1122, '1.00', '33.00', 540, 680, NULL, NULL),
(1123, '1.00', '10.00', 540, NULL, NULL, NULL),
(1124, '1.00', '33.00', 541, 681, NULL, NULL),
(1125, '1.00', '10.00', 541, NULL, NULL, NULL),
(1126, '1.00', '33.00', 542, 682, NULL, NULL),
(1127, '1.00', '10.00', 542, NULL, NULL, NULL),
(1128, '1.00', '33.00', 543, 683, NULL, NULL),
(1129, '1.00', '10.00', 543, NULL, NULL, NULL),
(1130, '1.00', '33.00', 544, 684, NULL, NULL),
(1131, '1.00', '1.00', 544, NULL, NULL, NULL),
(1132, '1.00', '33.00', 545, 685, NULL, NULL),
(1133, '1.00', '1.00', 545, NULL, NULL, NULL),
(1134, '1.00', '33.00', 546, 686, NULL, NULL),
(1135, '1.00', '10.00', 546, NULL, NULL, NULL),
(1136, '1.00', '44.50', 547, 687, NULL, NULL),
(1137, '1.00', '5.00', 547, NULL, 4, NULL),
(1138, '1.00', '10.00', 547, NULL, NULL, NULL),
(1139, '1.00', '49.00', 548, 688, NULL, NULL),
(1140, '1.00', '10.00', 548, NULL, NULL, NULL),
(1141, '1.00', '45.00', 549, 689, NULL, NULL),
(1142, '1.00', '5.00', 549, NULL, NULL, NULL),
(1143, '1.00', '105.00', 550, 690, NULL, NULL),
(1144, '1.00', '5.00', 550, NULL, NULL, NULL),
(1145, '1.00', '38.90', 551, 691, NULL, NULL),
(1146, '1.00', '7.00', 551, NULL, 14, NULL),
(1147, '1.00', '5.00', 551, NULL, NULL, NULL),
(1148, '1.00', '44.00', 552, 692, NULL, NULL),
(1149, '1.00', '10.00', 552, NULL, NULL, NULL),
(1150, '1.00', '44.00', 553, 693, NULL, NULL),
(1151, '1.00', '10.00', 553, NULL, NULL, NULL),
(1152, '1.00', '30.00', 554, 694, NULL, NULL),
(1153, '1.00', '1.00', 554, NULL, NULL, NULL),
(1154, '1.00', '44.00', 555, 695, NULL, NULL),
(1155, '1.00', '10.00', 555, NULL, NULL, NULL),
(1156, '1.00', '44.00', 556, 696, NULL, NULL),
(1157, '1.00', '10.00', 556, NULL, NULL, NULL),
(1158, '1.00', '44.00', 557, 697, NULL, NULL),
(1159, '1.00', '10.00', 557, NULL, NULL, NULL),
(1160, '1.00', '44.00', 558, 698, NULL, NULL),
(1161, '1.00', '10.00', 558, NULL, NULL, NULL),
(1162, '1.00', '44.00', 559, 699, NULL, NULL),
(1163, '1.00', '10.00', 559, NULL, NULL, NULL),
(1164, '1.00', '44.00', 560, 700, NULL, NULL),
(1165, '1.00', '10.00', 560, NULL, NULL, NULL),
(1166, '1.00', '44.00', 561, 701, NULL, NULL),
(1167, '1.00', '10.00', 561, NULL, NULL, NULL),
(1168, '1.00', '44.00', 562, 702, NULL, NULL),
(1169, '1.00', '10.00', 562, NULL, NULL, NULL),
(1170, '1.00', '37.00', 563, 703, NULL, NULL),
(1171, '1.00', '10.00', 563, NULL, NULL, NULL),
(1172, '1.00', '44.50', 564, 704, NULL, NULL),
(1173, '1.00', '37.00', 564, 705, NULL, NULL),
(1174, '1.00', '36.00', 564, 706, NULL, NULL),
(1175, '1.00', '10.00', 564, NULL, NULL, NULL),
(1176, '1.00', '54.00', 565, 707, NULL, NULL),
(1177, '1.00', '30.00', 565, 708, NULL, NULL),
(1178, '1.00', '46.00', 565, 709, NULL, NULL),
(1179, '1.00', '5.00', 565, NULL, 4, NULL),
(1180, '1.00', '10.00', 565, NULL, NULL, NULL),
(1181, '1.00', '45.00', 566, 710, NULL, NULL),
(1182, '1.00', '45.00', 566, 711, NULL, NULL),
(1183, '1.00', '45.00', 566, 712, NULL, NULL),
(1184, '1.00', '5.00', 566, NULL, NULL, NULL),
(1185, '1.00', '54.00', 567, 713, NULL, NULL),
(1186, '1.00', '30.00', 567, 714, NULL, NULL),
(1187, '1.00', '46.00', 567, 715, NULL, NULL),
(1188, '1.00', '5.00', 567, NULL, 4, NULL),
(1189, '1.00', '10.00', 567, NULL, NULL, NULL),
(1190, '1.00', '54.00', 568, 716, NULL, NULL),
(1191, '1.00', '30.00', 568, 717, NULL, NULL),
(1192, '1.00', '46.00', 568, 718, NULL, NULL),
(1193, '1.00', '5.00', 568, NULL, 4, NULL),
(1194, '1.00', '10.00', 568, NULL, NULL, NULL),
(1195, '1.00', '30.00', 569, 719, NULL, NULL),
(1196, '1.00', '5.00', 569, NULL, 4, NULL),
(1197, '1.00', '10.00', 569, NULL, NULL, NULL),
(1198, '1.00', '30.00', 570, 720, NULL, NULL),
(1199, '1.00', '5.00', 570, NULL, 4, NULL),
(1200, '1.00', '10.00', 570, NULL, NULL, NULL),
(1201, '1.00', '46.50', 571, 721, NULL, NULL),
(1202, '1.00', '10.00', 571, NULL, NULL, NULL),
(1203, '1.00', '44.00', 572, 722, NULL, NULL),
(1204, '1.00', '10.00', 572, NULL, NULL, NULL),
(1205, '1.00', '44.00', 573, 723, NULL, NULL),
(1206, '1.00', '10.00', 573, NULL, NULL, NULL),
(1207, '1.00', '32.00', 574, 724, NULL, NULL),
(1208, '1.00', '10.00', 574, NULL, NULL, NULL),
(1209, '1.00', '45.00', 575, 725, NULL, NULL),
(1210, '1.00', '40.00', 575, 726, NULL, NULL),
(1211, '1.00', '1.00', 575, NULL, NULL, NULL),
(1212, '1.00', '40.00', 576, 727, NULL, NULL),
(1213, '1.00', '10.00', 576, NULL, NULL, NULL),
(1214, '1.00', '35.00', 577, 728, NULL, NULL),
(1215, '1.00', '5.00', 577, NULL, 4, NULL),
(1216, '1.00', '10.00', 577, NULL, NULL, NULL),
(1217, '1.00', '35.00', 578, 729, NULL, NULL),
(1218, '1.00', '5.00', 578, NULL, 4, NULL),
(1219, '1.00', '10.00', 578, NULL, NULL, NULL),
(1220, '1.00', '35.00', 579, 730, NULL, NULL),
(1221, '1.00', '10.00', 579, NULL, NULL, NULL),
(1222, '1.00', '35.00', 580, 731, NULL, NULL),
(1223, '1.00', '10.00', 580, NULL, NULL, NULL),
(1224, '1.00', '35.00', 581, 732, NULL, NULL),
(1225, '1.00', '10.00', 581, NULL, NULL, NULL),
(1226, '1.00', '25.00', 582, 733, NULL, NULL),
(1227, '1.00', '5.00', 582, NULL, 4, NULL),
(1228, '1.00', '1.00', 582, NULL, NULL, NULL),
(1229, '1.00', '33.00', 583, 734, NULL, NULL),
(1230, '1.00', '7.00', 583, NULL, 35, NULL),
(1231, '1.00', '10.00', 583, NULL, NULL, NULL),
(1232, '1.00', '30.00', 584, 735, NULL, NULL),
(1233, '1.00', '10.00', 584, NULL, NULL, NULL),
(1234, '1.00', '53.00', 585, 736, NULL, NULL),
(1235, '1.00', '10.00', 585, NULL, 37, NULL),
(1236, '1.00', '10.00', 585, NULL, NULL, NULL),
(1237, '1.00', '40.00', 586, 737, NULL, NULL),
(1238, '1.00', '10.00', 586, NULL, NULL, NULL),
(1239, '1.00', '46.00', 587, 738, NULL, NULL),
(1240, '1.00', '10.00', 587, NULL, NULL, NULL),
(1241, '1.00', '46.00', 588, 739, NULL, NULL),
(1242, '1.00', '10.00', 588, NULL, NULL, NULL),
(1243, '1.00', '46.00', 589, 740, NULL, NULL),
(1244, '1.00', '10.00', 589, NULL, NULL, NULL),
(1245, '1.00', '35.00', 590, 741, NULL, NULL),
(1246, '1.00', '10.00', 590, NULL, NULL, NULL),
(1247, '1.00', '46.00', 591, 742, NULL, NULL),
(1248, '1.00', '10.00', 591, NULL, NULL, NULL),
(1249, '1.00', '35.00', 592, 743, NULL, NULL),
(1250, '1.00', '10.00', 592, NULL, NULL, NULL),
(1251, '1.00', '57.00', 593, 744, NULL, NULL),
(1252, '1.00', '10.00', 593, NULL, NULL, NULL),
(1253, '1.00', '60.33', 594, 745, NULL, NULL),
(1254, '1.00', '10.00', 594, NULL, NULL, NULL),
(1255, '1.00', '60.33', 595, 746, NULL, NULL),
(1256, '1.00', '5.00', 595, NULL, NULL, NULL),
(1257, '1.00', '45.00', 596, 747, NULL, NULL),
(1258, '1.00', '5.00', 596, NULL, NULL, NULL),
(1259, '1.00', '40.00', 597, 748, NULL, NULL),
(1260, '1.00', '10.00', 597, NULL, NULL, NULL),
(1261, '1.00', '23.00', 598, 749, NULL, NULL),
(1262, '1.00', '1.00', 598, NULL, NULL, NULL),
(1263, '1.00', '40.00', 599, 750, NULL, NULL),
(1264, '1.00', '10.00', 599, NULL, NULL, NULL),
(1265, '1.00', '45.00', 600, 751, NULL, NULL),
(1266, '1.00', '5.00', 600, NULL, 19, NULL),
(1267, '1.00', '5.00', 600, NULL, NULL, NULL),
(1268, '1.00', '45.00', 601, 752, NULL, NULL),
(1269, '1.00', '5.00', 601, NULL, 19, NULL),
(1270, '1.00', '5.00', 601, NULL, NULL, NULL),
(1271, '1.00', '45.00', 602, 753, NULL, NULL),
(1272, '1.00', '49.00', 602, 754, NULL, NULL),
(1273, '1.00', '7.00', 602, NULL, 14, NULL),
(1274, '1.00', '7.00', 602, NULL, 17, NULL),
(1275, '1.00', '5.00', 602, NULL, NULL, NULL),
(1276, '1.00', '47.00', 603, 755, NULL, NULL),
(1277, '1.00', '10.00', 603, NULL, NULL, NULL),
(1278, '1.00', '45.00', 604, 756, NULL, NULL),
(1279, '1.00', '49.00', 604, 757, NULL, NULL),
(1280, '1.00', '7.00', 604, NULL, 14, NULL),
(1281, '1.00', '7.00', 604, NULL, 17, NULL),
(1282, '1.00', '5.00', 604, NULL, NULL, NULL),
(1283, '1.00', '35.00', 605, 758, NULL, NULL),
(1284, '1.00', '10.00', 605, NULL, NULL, NULL),
(1285, '1.00', '35.00', 606, 759, NULL, NULL),
(1286, '1.00', '10.00', 606, NULL, NULL, NULL),
(1287, '1.00', '46.00', 607, 760, NULL, NULL),
(1288, '1.00', '10.00', 607, NULL, NULL, NULL),
(1289, '1.00', '38.90', 608, 761, NULL, NULL),
(1290, '1.00', '7.00', 608, NULL, 14, NULL),
(1291, '1.00', '5.00', 608, NULL, NULL, NULL),
(1292, '1.00', '30.00', 609, 762, NULL, NULL),
(1293, '1.00', '10.00', 609, NULL, NULL, NULL),
(1294, '1.00', '40.00', 610, 763, NULL, NULL),
(1295, '1.00', '10.00', 610, NULL, NULL, NULL),
(1296, '1.00', '40.00', 611, 764, NULL, NULL),
(1297, '2.00', '16.00', 611, NULL, 24, NULL),
(1298, '1.00', '15.00', 611, NULL, NULL, NULL),
(1299, '1.00', '57.00', 612, 765, NULL, NULL),
(1300, '1.00', '7.00', 612, NULL, 24, NULL),
(1301, '1.00', '15.00', 612, NULL, NULL, NULL),
(1302, '1.00', '57.00', 613, 766, NULL, NULL),
(1303, '1.00', '7.00', 613, NULL, 24, NULL),
(1304, '1.00', '15.00', 613, NULL, NULL, NULL),
(1305, '1.00', '57.00', 614, 767, NULL, NULL),
(1306, '1.00', '7.00', 614, NULL, 24, NULL),
(1307, '1.00', '15.00', 614, NULL, NULL, NULL),
(1308, '1.00', '57.00', 615, 768, NULL, NULL),
(1309, '1.00', '7.00', 615, NULL, 24, NULL),
(1310, '1.00', '15.00', 615, NULL, NULL, NULL),
(1311, '1.00', '57.00', 616, 769, NULL, NULL),
(1312, '1.00', '7.00', 616, NULL, 24, NULL),
(1313, '1.00', '15.00', 616, NULL, NULL, NULL),
(1314, '1.00', '57.00', 617, 770, NULL, NULL),
(1315, '1.00', '7.00', 617, NULL, 24, NULL),
(1316, '1.00', '15.00', 617, NULL, NULL, NULL),
(1317, '1.00', '57.00', 618, 771, NULL, NULL),
(1318, '1.00', '7.00', 618, NULL, 24, NULL),
(1319, '1.00', '15.00', 618, NULL, NULL, NULL),
(1320, '1.00', '57.00', 619, 772, NULL, NULL),
(1321, '1.00', '7.00', 619, NULL, 24, NULL),
(1322, '1.00', '15.00', 619, NULL, NULL, NULL),
(1323, '1.00', '57.00', 620, 773, NULL, NULL),
(1324, '1.00', '7.00', 620, NULL, 24, NULL),
(1325, '1.00', '15.00', 620, NULL, NULL, NULL),
(1326, '1.00', '57.00', 621, 774, NULL, NULL),
(1327, '1.00', '7.00', 621, NULL, 24, NULL),
(1328, '1.00', '15.00', 621, NULL, NULL, NULL),
(1329, '1.00', '57.00', 622, 775, NULL, NULL),
(1330, '1.00', '7.00', 622, NULL, 24, NULL),
(1331, '1.00', '15.00', 622, NULL, NULL, NULL),
(1332, '1.00', '7.90', 622, NULL, NULL, 25),
(1333, '1.00', '40.00', 623, 776, NULL, NULL),
(1334, '1.00', '15.00', 623, NULL, NULL, NULL),
(1335, '1.00', '4.95', 623, NULL, NULL, 25),
(1336, '1.00', '30.00', 624, 777, NULL, NULL),
(1337, '1.00', '40.00', 624, 778, NULL, NULL),
(1338, '3.00', '21.00', 624, NULL, 24, NULL),
(1339, '1.00', '7.00', 624, NULL, 32, NULL),
(1340, '1.00', '15.00', 624, NULL, NULL, NULL),
(1341, '1.00', '11.30', 624, NULL, NULL, 25),
(1342, '1.00', '40.00', 625, 779, NULL, NULL),
(1343, '1.00', '15.00', 625, NULL, NULL, NULL),
(1344, '1.00', '5.50', 625, NULL, NULL, 25),
(1345, '1.00', '40.00', 626, 780, NULL, NULL),
(1346, '1.00', '15.00', 626, NULL, NULL, NULL),
(1347, '1.00', '5.50', 626, NULL, NULL, 25),
(1348, '1.00', '49.00', 627, 781, NULL, NULL),
(1349, '1.00', '7.00', 627, NULL, 24, NULL),
(1350, '1.00', '15.00', 627, NULL, NULL, NULL),
(1351, '1.00', '7.10', 627, NULL, NULL, 25),
(1352, '1.00', '49.00', 628, 782, NULL, NULL),
(1353, '1.00', '7.00', 628, NULL, 24, NULL),
(1354, '1.00', '15.00', 628, NULL, NULL, NULL),
(1355, '1.00', '7.10', 628, NULL, NULL, 25),
(1356, '1.00', '49.00', 629, 783, NULL, NULL),
(1357, '1.00', '7.00', 629, NULL, 24, NULL),
(1358, '1.00', '15.00', 629, NULL, NULL, NULL),
(1361, '1.00', '49.00', 631, 785, NULL, NULL),
(1362, '1.00', '7.00', 631, NULL, 24, NULL),
(1363, '1.00', '15.00', 631, NULL, NULL, NULL),
(1364, '1.00', '49.00', 632, 786, NULL, NULL),
(1365, '1.00', '7.00', 632, NULL, 24, NULL),
(1366, '1.00', '15.00', 632, NULL, NULL, NULL),
(1369, '1.00', '49.00', 634, 788, NULL, NULL),
(1370, '1.00', '7.00', 634, NULL, 24, NULL),
(1371, '1.00', '15.00', 634, NULL, NULL, NULL),
(1372, '1.00', '7.10', 634, NULL, NULL, 25),
(1373, '1.00', '49.00', 635, 789, NULL, NULL),
(1374, '1.00', '7.00', 635, NULL, 24, NULL),
(1375, '1.00', '15.00', 635, NULL, NULL, NULL),
(1376, '1.00', '7.10', 635, NULL, NULL, 25),
(1377, '1.00', '46.00', 636, 790, NULL, NULL),
(1378, '1.00', '7.00', 636, NULL, 32, NULL),
(1379, '1.00', '15.00', 636, NULL, NULL, NULL),
(1380, '1.00', '6.80', 636, NULL, NULL, 25),
(1381, '1.00', '45.00', 637, 791, NULL, NULL),
(1382, '1.00', '5.00', 637, NULL, NULL, NULL),
(1383, '1.00', '40.90', 638, 792, NULL, NULL),
(1384, '1.00', '5.00', 638, NULL, NULL, NULL),
(1385, '1.00', '46.00', 639, 793, NULL, NULL),
(1386, '1.00', '7.00', 639, NULL, 32, NULL),
(1387, '1.00', '15.00', 639, NULL, NULL, NULL),
(1388, '1.00', '6.80', 639, NULL, NULL, 25),
(1389, '1.00', '46.00', 640, 794, NULL, NULL),
(1390, '1.00', '7.00', 640, NULL, 32, NULL),
(1391, '1.00', '15.00', 640, NULL, NULL, NULL),
(1392, '1.00', '6.80', 640, NULL, NULL, 25),
(1393, '1.00', '46.00', 641, 795, NULL, NULL),
(1394, '1.00', '7.00', 641, NULL, 32, NULL),
(1395, '1.00', '15.00', 641, NULL, NULL, NULL),
(1396, '1.00', '6.80', 641, NULL, NULL, 25),
(1397, '1.00', '46.00', 642, 796, NULL, NULL),
(1398, '1.00', '7.00', 642, NULL, 32, NULL),
(1399, '1.00', '15.00', 642, NULL, NULL, NULL),
(1400, '1.00', '6.80', 642, NULL, NULL, 25),
(1401, '1.00', '46.00', 643, 797, NULL, NULL),
(1402, '1.00', '7.00', 643, NULL, 32, NULL),
(1403, '1.00', '15.00', 643, NULL, NULL, NULL),
(1404, '1.00', '6.80', 643, NULL, NULL, 25),
(1405, '1.00', '46.00', 644, 798, NULL, NULL),
(1406, '1.00', '7.00', 644, NULL, 32, NULL),
(1407, '1.00', '15.00', 644, NULL, NULL, NULL),
(1408, '1.00', '6.80', 644, NULL, NULL, 25),
(1409, '1.00', '46.00', 645, 799, NULL, NULL),
(1410, '1.00', '7.00', 645, NULL, 32, NULL),
(1411, '1.00', '15.00', 645, NULL, NULL, NULL),
(1412, '1.00', '6.80', 645, NULL, NULL, 25),
(1413, '1.00', '46.00', 646, 800, NULL, NULL),
(1414, '1.00', '7.00', 646, NULL, 32, NULL),
(1415, '1.00', '15.00', 646, NULL, NULL, NULL),
(1416, '1.00', '6.80', 646, NULL, NULL, 25),
(1417, '1.00', '46.00', 647, 801, NULL, NULL),
(1418, '1.00', '7.00', 647, NULL, 32, NULL),
(1419, '1.00', '15.00', 647, NULL, NULL, NULL),
(1420, '1.00', '6.80', 647, NULL, NULL, 25),
(1421, '1.00', '46.00', 648, 802, NULL, NULL),
(1422, '1.00', '7.00', 648, NULL, 32, NULL),
(1423, '1.00', '15.00', 648, NULL, NULL, NULL),
(1424, '1.00', '6.80', 648, NULL, NULL, 25),
(1425, '1.00', '46.00', 649, 803, NULL, NULL),
(1426, '1.00', '7.00', 649, NULL, 32, NULL),
(1427, '1.00', '15.00', 649, NULL, NULL, NULL),
(1428, '1.00', '6.80', 649, NULL, NULL, 25),
(1429, '1.00', '46.00', 650, 804, NULL, NULL),
(1430, '1.00', '7.00', 650, NULL, 32, NULL),
(1431, '1.00', '15.00', 650, NULL, NULL, NULL),
(1432, '1.00', '6.80', 650, NULL, NULL, 25),
(1433, '1.00', '46.00', 651, 805, NULL, NULL),
(1434, '1.00', '7.00', 651, NULL, 32, NULL),
(1435, '1.00', '15.00', 651, NULL, NULL, NULL),
(1436, '1.00', '6.80', 651, NULL, NULL, 25),
(1437, '1.00', '46.00', 652, 806, NULL, NULL),
(1438, '1.00', '7.00', 652, NULL, 32, NULL),
(1439, '1.00', '15.00', 652, NULL, NULL, NULL),
(1440, '1.00', '6.80', 652, NULL, NULL, 25),
(1441, '1.00', '46.00', 653, 807, NULL, NULL),
(1442, '1.00', '7.00', 653, NULL, 24, NULL),
(1443, '1.00', '15.00', 653, NULL, NULL, NULL),
(1444, '1.00', '6.80', 653, NULL, NULL, 25),
(1445, '1.00', '46.00', 654, 808, NULL, NULL),
(1446, '1.00', '7.00', 654, NULL, 32, NULL),
(1447, '1.00', '15.00', 654, NULL, NULL, NULL),
(1448, '1.00', '46.00', 655, 809, NULL, NULL),
(1449, '1.00', '7.00', 655, NULL, 32, NULL),
(1450, '1.00', '15.00', 655, NULL, NULL, NULL),
(1451, '1.00', '40.00', 656, 810, NULL, NULL),
(1452, '1.00', '15.00', 656, NULL, NULL, NULL),
(1453, '1.00', '46.00', 657, 811, NULL, NULL),
(1454, '1.00', '7.00', 657, NULL, 24, NULL),
(1455, '1.00', '15.00', 657, NULL, NULL, NULL),
(1456, '1.00', '46.00', 658, 812, NULL, NULL),
(1457, '1.00', '7.00', 658, NULL, 24, NULL),
(1458, '1.00', '15.00', 658, NULL, NULL, NULL),
(1459, '1.00', '6.80', 658, NULL, NULL, 25),
(1460, '1.00', '46.00', 659, 813, NULL, NULL),
(1461, '1.00', '7.00', 659, NULL, 24, NULL),
(1462, '1.00', '5.30', 659, NULL, NULL, 25),
(1463, '1.00', '15.00', 659, NULL, NULL, NULL),
(1464, '1.00', '45.00', 660, 814, NULL, NULL),
(1465, '1.00', '8.00', 660, NULL, 14, NULL),
(1466, '1.00', '5.00', 660, NULL, NULL, NULL),
(1467, '1.00', '40.90', 661, 815, NULL, NULL),
(1468, '1.00', '5.00', 661, NULL, NULL, NULL),
(1469, '1.00', '40.00', 662, 816, NULL, NULL),
(1470, '1.00', '7.00', 662, NULL, 24, NULL),
(1471, '1.00', '15.00', 662, NULL, NULL, NULL),
(1472, '1.00', '6.20', 662, NULL, NULL, 25),
(1473, '1.00', '40.00', 663, 817, NULL, NULL),
(1474, '1.00', '7.00', 663, NULL, 24, NULL),
(1475, '1.00', '15.00', 663, NULL, NULL, NULL),
(1476, '1.00', '5.73', 663, NULL, NULL, 25),
(1477, '1.00', '40.00', 664, 818, NULL, NULL),
(1478, '1.00', '7.00', 664, NULL, 24, NULL),
(1479, '1.00', '4.70', 664, NULL, NULL, 25),
(1480, '1.00', '15.00', 664, NULL, NULL, NULL),
(1481, '1.00', '46.00', 665, 819, NULL, NULL),
(1482, '1.00', '10.00', 665, NULL, 37, NULL),
(1483, '1.00', '15.00', 665, NULL, NULL, NULL),
(1484, '1.00', '5.60', 665, NULL, NULL, 25),
(1485, '1.00', '46.00', 666, 820, NULL, NULL),
(1486, '1.00', '10.00', 666, NULL, 37, NULL),
(1487, '1.00', '5.60', 666, NULL, NULL, 25),
(1488, '1.00', '15.00', 666, NULL, NULL, NULL),
(1489, '1.00', '40.90', 667, 821, NULL, NULL),
(1490, '1.00', '7.00', 667, NULL, NULL, NULL),
(1491, '1.00', '46.00', 668, 822, NULL, NULL),
(1492, '1.00', '7.00', 668, NULL, 24, NULL),
(1493, '1.00', '15.00', 668, NULL, NULL, NULL),
(1494, '1.00', '5.30', 668, NULL, NULL, 25),
(1495, '1.00', '46.00', 669, 823, NULL, NULL),
(1496, '1.00', '7.00', 669, NULL, 24, NULL),
(1497, '1.00', '5.30', 669, NULL, NULL, 25),
(1498, '1.00', '15.00', 669, NULL, NULL, NULL),
(1499, '1.00', '38.90', 670, 824, NULL, NULL),
(1500, '1.00', '8.00', 670, NULL, 14, NULL),
(1501, '1.00', '5.00', 670, NULL, NULL, NULL),
(1502, '1.00', '46.00', 671, 825, NULL, NULL),
(1503, '1.00', '15.00', 671, NULL, NULL, NULL),
(1504, '1.00', '4.60', 671, NULL, NULL, 25),
(1505, '1.00', '40.00', 672, 826, NULL, NULL),
(1506, '1.00', '10.00', 672, NULL, NULL, NULL),
(1507, '1.00', '43.00', 673, 827, NULL, NULL),
(1508, '1.00', '15.00', 673, NULL, NULL, NULL),
(1509, '1.00', '40.00', 674, 828, NULL, NULL),
(1510, '1.00', '15.00', 674, NULL, NULL, NULL),
(1511, '1.00', '40.00', 675, 829, NULL, NULL),
(1512, '1.00', '15.00', 675, NULL, NULL, NULL),
(1513, '1.00', '40.00', 676, 830, NULL, NULL),
(1514, '1.00', '15.00', 676, NULL, NULL, NULL),
(1515, '1.00', '40.00', 677, 831, NULL, NULL),
(1516, '1.00', '15.00', 677, NULL, NULL, NULL),
(1517, '1.00', '40.00', 678, 832, NULL, NULL),
(1518, '1.00', '10.00', 678, NULL, NULL, NULL),
(1519, '1.00', '40.00', 679, 833, NULL, NULL),
(1520, '1.00', '10.00', 679, NULL, NULL, NULL),
(1521, '1.00', '40.00', 680, 834, NULL, NULL),
(1522, '1.00', '10.00', 680, NULL, NULL, NULL),
(1523, '1.00', '40.00', 681, 835, NULL, NULL),
(1524, '1.00', '10.00', 681, NULL, NULL, NULL),
(1525, '1.00', '4.00', 681, NULL, NULL, 25),
(1526, '1.00', '40.00', 682, 836, NULL, NULL),
(1527, '1.00', '4.00', 682, NULL, NULL, 25),
(1528, '1.00', '10.00', 682, NULL, NULL, NULL),
(1529, '1.00', '49.50', 683, 837, NULL, NULL),
(1530, '1.00', '50.00', 683, 838, NULL, NULL),
(1531, '1.00', '7.00', 683, NULL, 24, NULL),
(1532, '1.00', '7.00', 683, NULL, 32, NULL),
(1533, '1.00', '10.00', 683, NULL, NULL, NULL),
(1534, '1.00', '11.35', 683, NULL, NULL, 25),
(1535, '1.00', '49.50', 684, 839, NULL, NULL),
(1536, '1.00', '50.00', 684, 840, NULL, NULL),
(1537, '1.00', '7.00', 684, NULL, 24, NULL),
(1538, '1.00', '7.00', 684, NULL, 32, NULL),
(1539, '1.00', '11.35', 684, NULL, NULL, 25),
(1540, '1.00', '10.00', 684, NULL, NULL, NULL),
(1541, '1.00', '46.00', 685, 841, NULL, NULL),
(1542, '1.00', '15.00', 685, NULL, NULL, NULL),
(1543, '1.00', '4.60', 685, NULL, NULL, 25),
(1544, '1.00', '40.00', 686, 842, NULL, NULL),
(1545, '1.00', '15.00', 686, NULL, NULL, NULL),
(1546, '1.00', '4.00', 686, NULL, NULL, 25),
(1547, '1.00', '40.00', 687, 843, NULL, NULL),
(1548, '1.00', '4.00', 687, NULL, NULL, 25),
(1549, '1.00', '15.00', 687, NULL, NULL, NULL),
(1550, '1.00', '40.00', 688, 844, NULL, NULL),
(1551, '1.00', '10.00', 688, NULL, NULL, NULL),
(1552, '1.00', '40.00', 689, 845, NULL, NULL),
(1553, '1.00', '10.00', 689, NULL, NULL, NULL),
(1554, '1.00', '4.00', 689, NULL, NULL, 25),
(1555, '1.00', '40.00', 690, 846, NULL, NULL),
(1556, '1.00', '4.00', 690, NULL, NULL, 25),
(1557, '1.00', '10.00', 690, NULL, NULL, NULL),
(1558, '1.00', '46.00', 691, 847, NULL, NULL),
(1559, '1.00', '10.00', 691, NULL, NULL, NULL),
(1560, '1.00', '4.60', 691, NULL, NULL, 25),
(1561, '1.00', '46.00', 692, 848, NULL, NULL),
(1562, '1.00', '4.60', 692, NULL, NULL, 25),
(1563, '1.00', '10.00', 692, NULL, NULL, NULL),
(1564, '1.00', '46.00', 693, 849, NULL, NULL),
(1565, '1.00', '4.60', 693, NULL, NULL, 25),
(1566, '1.00', '10.00', 693, NULL, NULL, NULL),
(1571, '1.00', '55.00', 695, 851, NULL, NULL),
(1572, '1.00', '8.00', 695, NULL, 14, NULL),
(1573, '1.00', '5.00', 695, NULL, NULL, NULL),
(1574, '1.00', '6.30', 695, NULL, NULL, 27),
(1575, '1.00', '40.00', 696, 852, NULL, NULL),
(1576, '1.00', '40.00', 696, 853, NULL, NULL),
(1577, '1.00', '7.00', 696, NULL, 24, NULL),
(1578, '1.00', '10.00', 696, NULL, NULL, NULL),
(1579, '1.00', '8.70', 696, NULL, NULL, 25),
(1580, '1.00', '40.00', 697, 854, NULL, NULL),
(1581, '1.00', '40.00', 697, 855, NULL, NULL),
(1582, '1.00', '7.00', 697, NULL, 24, NULL),
(1583, '1.00', '8.70', 697, NULL, NULL, 25),
(1584, '1.00', '10.00', 697, NULL, NULL, NULL),
(1585, '1.00', '40.90', 698, 856, NULL, NULL),
(1586, '1.00', '50.90', 698, 857, NULL, NULL),
(1587, '1.00', '7.00', 698, NULL, NULL, NULL),
(1588, '1.00', '46.00', 699, 858, NULL, NULL),
(1589, '1.00', '4.60', 699, NULL, NULL, 25),
(1590, '1.00', '10.00', 699, NULL, NULL, NULL),
(1591, '1.00', '46.00', 700, 859, NULL, NULL),
(1592, '1.00', '4.60', 700, NULL, NULL, 25),
(1593, '1.00', '10.00', 700, NULL, NULL, NULL),
(1594, '1.00', '40.00', 701, 860, NULL, NULL),
(1595, '1.00', '4.00', 701, NULL, NULL, 25),
(1596, '1.00', '15.00', 701, NULL, NULL, NULL),
(1597, '1.00', '40.00', 702, 861, NULL, NULL),
(1598, '1.00', '4.00', 702, NULL, NULL, 25),
(1599, '1.00', '15.00', 702, NULL, NULL, NULL),
(1600, '1.00', '40.00', 703, 862, NULL, NULL),
(1601, '1.00', '10.00', 703, NULL, NULL, NULL),
(1602, '1.00', '4.00', 703, NULL, NULL, 25),
(1603, '1.00', '46.00', 704, 863, NULL, NULL),
(1604, '1.00', '4.60', 704, NULL, NULL, 25),
(1605, '1.00', '10.00', 704, NULL, NULL, NULL),
(1606, '1.00', '40.00', 705, 864, NULL, NULL),
(1607, '1.00', '40.00', 705, 865, NULL, NULL),
(1608, '1.00', '7.00', 705, NULL, 24, NULL),
(1609, '1.00', '10.00', 705, NULL, NULL, NULL),
(1610, '1.00', '46.00', 706, 866, NULL, NULL),
(1611, '1.00', '10.00', 706, NULL, NULL, NULL),
(1612, '1.00', '45.00', 707, 867, NULL, NULL),
(1613, '1.00', '5.00', 707, NULL, NULL, NULL),
(1614, '1.00', '4.50', 707, NULL, NULL, 27),
(1615, '1.00', '45.00', 708, 868, NULL, NULL),
(1616, '1.00', '4.50', 708, NULL, NULL, 27),
(1617, '1.00', '5.00', 708, NULL, NULL, NULL),
(1618, '1.00', '40.00', 709, 869, NULL, NULL),
(1619, '1.00', '7.00', 709, NULL, 24, NULL),
(1620, '1.00', '15.00', 709, NULL, NULL, NULL),
(1621, '1.00', '4.70', 709, NULL, NULL, 25),
(1622, '1.00', '40.00', 710, 870, NULL, NULL),
(1623, '1.00', '7.00', 710, NULL, 33, NULL),
(1624, '1.00', '15.00', 710, NULL, NULL, NULL),
(1625, '1.00', '12.00', 710, NULL, NULL, 26),
(1626, '1.00', '40.00', 711, 871, NULL, NULL),
(1627, '1.00', '7.00', 711, NULL, 33, NULL),
(1628, '1.00', '15.00', 711, NULL, NULL, NULL),
(1629, '1.00', '40.00', 712, 872, NULL, NULL),
(1630, '1.00', '7.00', 712, NULL, 33, NULL),
(1631, '1.00', '15.00', 712, NULL, NULL, NULL),
(1632, '1.00', '40.00', 713, 873, NULL, NULL),
(1633, '1.00', '7.00', 713, NULL, 33, NULL),
(1634, '1.00', '10.00', 713, NULL, NULL, NULL),
(1635, '1.00', '12.00', 713, NULL, NULL, 26),
(1636, '1.00', '40.00', 714, 874, NULL, NULL),
(1637, '1.00', '15.00', 714, NULL, NULL, NULL),
(1638, '1.00', '4.00', 714, NULL, NULL, 25),
(1639, '1.00', '45.00', 715, 875, NULL, NULL),
(1640, '1.00', '8.00', 715, NULL, NULL, NULL),
(1641, '1.00', '4.50', 715, NULL, NULL, 27),
(1642, '1.00', '39.90', 716, 876, NULL, NULL),
(1643, '1.00', '8.00', 716, NULL, NULL, NULL),
(1644, '1.00', '3.99', 716, NULL, NULL, 27),
(1645, '1.00', '45.00', 717, 877, NULL, NULL),
(1646, '1.00', '4.50', 717, NULL, NULL, 27),
(1647, '1.00', '8.00', 717, NULL, NULL, NULL),
(1648, '1.00', '40.00', 718, 878, NULL, NULL),
(1649, '1.00', '7.00', 718, NULL, 33, NULL),
(1650, '1.00', '15.00', 718, NULL, NULL, NULL),
(1651, '1.00', '40.00', 719, 879, NULL, NULL),
(1652, '1.00', '15.00', 719, NULL, NULL, NULL),
(1653, '1.00', '45.00', 720, 880, NULL, NULL),
(1654, '1.00', '5.00', 720, NULL, NULL, NULL),
(1655, '1.00', '4.50', 720, NULL, NULL, 27),
(1656, '1.00', '46.00', 721, 881, NULL, NULL),
(1657, '1.00', '7.00', 721, NULL, 24, NULL),
(1658, '1.00', '15.00', 721, NULL, NULL, NULL),
(1659, '1.00', '46.00', 722, 882, NULL, NULL),
(1660, '1.00', '7.00', 722, NULL, 33, NULL),
(1661, '1.00', '15.00', 722, NULL, NULL, NULL),
(1662, '1.00', '12.00', 722, NULL, NULL, 26),
(1663, '1.00', '49.00', 723, 883, NULL, NULL),
(1664, '1.00', '7.00', 723, NULL, 33, NULL),
(1665, '1.00', '7.00', 723, NULL, 32, NULL),
(1666, '1.00', '15.00', 723, NULL, NULL, NULL),
(1667, '1.00', '12.00', 723, NULL, NULL, 26),
(1668, '1.00', '49.00', 724, 884, NULL, NULL),
(1669, '1.00', '40.00', 724, 885, NULL, NULL),
(1670, '1.00', '10.00', 724, NULL, 37, NULL),
(1671, '2.00', '14.00', 724, NULL, 33, NULL),
(1672, '1.00', '15.00', 724, NULL, NULL, NULL),
(1673, '1.00', '24.00', 724, NULL, NULL, 26),
(1674, '1.00', '40.00', 725, 886, NULL, NULL),
(1675, '1.00', '15.00', 725, NULL, NULL, NULL),
(1676, '1.00', '41.90', 726, 887, NULL, NULL),
(1677, '1.00', '5.00', 726, NULL, NULL, NULL),
(1678, '1.00', '4.19', 726, NULL, NULL, 27),
(1679, '1.00', '39.90', 727, 888, NULL, NULL),
(1680, '1.00', '8.00', 727, NULL, NULL, NULL),
(1681, '1.00', '40.00', 728, 889, NULL, NULL),
(1682, '1.00', '5.00', 728, NULL, NULL, NULL),
(1683, '1.00', '40.00', 729, 890, NULL, NULL),
(1684, '1.00', '5.00', 729, NULL, NULL, NULL),
(1685, '1.00', '40.00', 730, 891, NULL, NULL),
(1686, '1.00', '5.00', 730, NULL, NULL, NULL),
(1687, '1.00', '40.00', 731, 892, NULL, NULL),
(1688, '1.00', '10.00', 731, NULL, NULL, NULL),
(1689, '1.00', '40.00', 732, 893, NULL, NULL),
(1690, '1.00', '5.00', 732, NULL, NULL, NULL),
(1691, '1.00', '40.00', 733, 894, NULL, NULL),
(1692, '1.00', '111.00', 733, NULL, NULL, NULL),
(1693, '1.00', '40.00', 734, 895, NULL, NULL),
(1694, '1.00', '5.00', 734, NULL, NULL, NULL),
(1695, '1.00', '40.00', 735, 896, NULL, NULL),
(1696, '1.00', '5.00', 735, NULL, NULL, NULL),
(1697, '1.00', '40.00', 736, 897, NULL, NULL),
(1698, '1.00', '5.00', 736, NULL, NULL, NULL),
(1699, '1.00', '40.00', 737, 898, NULL, NULL),
(1700, '1.00', '111.00', 737, NULL, NULL, NULL),
(1701, '1.00', '40.00', 738, 899, NULL, NULL),
(1702, '1.00', '15.00', 738, NULL, NULL, NULL),
(1703, '1.00', '30.00', 739, 900, NULL, NULL),
(1704, '1.00', '10.00', 739, NULL, NULL, NULL),
(1705, '1.00', '40.00', 740, 901, NULL, NULL),
(1706, '1.00', '15.00', 740, NULL, NULL, NULL),
(1707, '1.00', '40.00', 741, 902, NULL, NULL),
(1708, '1.00', '15.00', 741, NULL, NULL, NULL),
(1709, '1.00', '40.00', 742, 903, NULL, NULL),
(1710, '1.00', '15.00', 742, NULL, NULL, NULL),
(1711, '1.00', '40.00', 743, 904, NULL, NULL),
(1712, '1.00', '111.00', 743, NULL, NULL, NULL),
(1713, '1.00', '40.00', 744, 905, NULL, NULL),
(1714, '1.00', '15.00', 744, NULL, NULL, NULL),
(1715, '1.00', '40.00', 745, 906, NULL, NULL),
(1716, '1.00', '111.00', 745, NULL, NULL, NULL),
(1717, '1.00', '40.00', 746, 907, NULL, NULL),
(1718, '1.00', '15.00', 746, NULL, NULL, NULL),
(1719, '1.00', '40.00', 747, 908, NULL, NULL),
(1720, '1.00', '5.00', 747, NULL, NULL, NULL),
(1721, '1.00', '40.00', 748, 909, NULL, NULL),
(1722, '1.00', '5.00', 748, NULL, NULL, NULL),
(1723, '1.00', '40.00', 749, 910, NULL, NULL),
(1724, '1.00', '15.00', 749, NULL, NULL, NULL),
(1725, '1.00', '40.00', 750, 911, NULL, NULL),
(1726, '1.00', '15.00', 750, NULL, NULL, NULL),
(1727, '1.00', '40.00', 751, 912, NULL, NULL),
(1728, '1.00', '15.00', 751, NULL, NULL, NULL),
(1729, '1.00', '53.00', 752, 913, NULL, NULL),
(1730, '1.00', '41.50', 752, 914, NULL, NULL),
(1731, '1.00', '7.00', 752, NULL, 24, NULL),
(1732, '1.00', '7.00', 752, NULL, 32, NULL),
(1733, '1.00', '10.00', 752, NULL, NULL, NULL),
(1734, '1.00', '53.00', 753, 915, NULL, NULL),
(1735, '1.00', '41.50', 753, 916, NULL, NULL),
(1736, '1.00', '7.00', 753, NULL, 24, NULL),
(1737, '1.00', '7.00', 753, NULL, 32, NULL),
(1738, '1.00', '10.00', 753, NULL, NULL, NULL),
(1739, '1.00', '53.00', 754, 917, NULL, NULL),
(1740, '1.00', '41.50', 754, 918, NULL, NULL),
(1741, '1.00', '7.00', 754, NULL, 24, NULL),
(1742, '1.00', '7.00', 754, NULL, 32, NULL),
(1743, '1.00', '10.00', 754, NULL, NULL, NULL),
(1744, '1.00', '45.00', 755, 919, NULL, NULL),
(1745, '1.00', '8.00', 755, NULL, 15, NULL),
(1746, '1.00', '7.00', 755, NULL, NULL, NULL),
(1747, '1.00', '5.30', 755, NULL, NULL, 27),
(1748, '1.00', '53.00', 756, 920, NULL, NULL),
(1749, '1.00', '41.50', 756, 921, NULL, NULL),
(1750, '1.00', '7.00', 756, NULL, 24, NULL),
(1751, '1.00', '7.00', 756, NULL, 32, NULL),
(1752, '1.00', '15.00', 756, NULL, NULL, NULL),
(1753, '1.00', '46.00', 757, 922, NULL, NULL),
(1754, '1.00', '7.00', 757, NULL, 24, NULL),
(1755, '1.00', '10.00', 757, NULL, NULL, NULL),
(1756, '1.00', '46.00', 758, 923, NULL, NULL),
(1757, '1.00', '7.00', 758, NULL, 24, NULL),
(1758, '1.00', '10.00', 758, NULL, NULL, NULL),
(1759, '1.00', '44.95', 759, 924, NULL, NULL),
(1760, '1.00', '5.00', 759, NULL, NULL, NULL),
(1761, '1.00', '4.50', 759, NULL, NULL, 27),
(1762, '1.00', '44.95', 760, 925, NULL, NULL),
(1763, '1.00', '4.50', 760, NULL, NULL, 27),
(1764, '1.00', '5.00', 760, NULL, NULL, NULL),
(1765, '1.00', '43.90', 761, 926, NULL, NULL),
(1766, '1.00', '7.00', 761, NULL, NULL, NULL),
(1767, '1.00', '4.39', 761, NULL, NULL, 27),
(1768, '1.00', '41.90', 762, 927, NULL, NULL),
(1769, '1.00', '5.00', 762, NULL, NULL, NULL),
(1770, '1.00', '45.00', 763, 928, NULL, NULL),
(1771, '1.00', '8.00', 763, NULL, 14, NULL),
(1772, '1.00', '5.00', 763, NULL, NULL, NULL),
(1773, '1.00', '5.30', 763, NULL, NULL, 27),
(1774, '1.00', '39.90', 764, 929, NULL, NULL),
(1775, '1.00', '8.00', 764, NULL, 14, NULL),
(1776, '1.00', '5.00', 764, NULL, NULL, NULL),
(1777, '1.00', '4.79', 764, NULL, NULL, 27),
(1778, '1.00', '39.90', 765, 930, NULL, NULL),
(1779, '1.00', '8.00', 765, NULL, 14, NULL),
(1780, '1.00', '5.00', 765, NULL, NULL, NULL),
(1781, '1.00', '4.79', 765, NULL, NULL, 27),
(1782, '1.00', '45.00', 766, 931, NULL, NULL),
(1783, '1.00', '5.00', 766, NULL, NULL, NULL),
(1784, '1.00', '4.50', 766, NULL, NULL, 27),
(1785, '1.00', '39.90', 767, 932, NULL, NULL),
(1786, '1.00', '8.00', 767, NULL, 14, NULL),
(1787, '1.00', '5.00', 767, NULL, NULL, NULL);

--
-- Acionadores `item_pedido`
--
DELIMITER $$
CREATE TRIGGER `trigger_validacao_item_pedido_bi` BEFORE INSERT ON `item_pedido` FOR EACH ROW BEGIN   
    if (new.quantidade_item_pedido is null or new.quantidade_item_pedido < 0 ) then
		signal sqlstate '45000' set message_text = 'QUANTIDADE deve ser maior que 0.';
	end if;
    if (new.valor_subtotal_item_pedido is null or new.valor_subtotal_item_pedido < 0 ) then
		signal sqlstate '45000' set message_text = 'VALOR SUBTOTAL deve ser maior que 0.';
	end if;
    if (new.pedido_item_pedido is null or new.pedido_item_pedido < 0 ) then
		signal sqlstate '45000' set message_text = 'PEDIDO não pode ser vazio/nulo.';
	end if;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trigger_validacao_item_pedido_bu` BEFORE UPDATE ON `item_pedido` FOR EACH ROW BEGIN
	if (new.quantidade_item_pedido is null or new.quantidade_item_pedido < 0 ) then
		signal sqlstate '45000' set message_text = 'QUANTIDADE deve ser maior que 0.';
	end if;
    if (new.valor_subtotal_item_pedido is null or new.valor_subtotal_item_pedido < 0 ) then
		signal sqlstate '45000' set message_text = 'VALOR SUBTOTAL deve ser maior que 0.';
	end if;
    if (new.pedido_item_pedido is null or new.pedido_item_pedido < 0 ) then
		signal sqlstate '45000' set message_text = 'PEDIDO não pode ser vazio/nulo.';
	end if;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `item_pizza`
--

CREATE TABLE `item_pizza` (
  `codigo_item_pizza` bigint(20) NOT NULL,
  `sabor_pizza_item_pizza` bigint(20) NOT NULL,
  `pizza_item_pizza` bigint(20) NOT NULL COMMENT 'fk para pizza'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `item_pizza`
--

INSERT INTO `item_pizza` (`codigo_item_pizza`, `sabor_pizza_item_pizza`, `pizza_item_pizza`) VALUES
(728, 2, 521),
(729, 3, 521),
(730, 6, 522),
(731, 5, 522),
(732, 30, 523),
(733, 42, 523),
(734, 29, 523),
(735, 30, 523),
(736, 1, 524),
(737, 1, 524),
(738, 3, 525),
(739, 10, 526),
(740, 3, 526),
(741, 4, 527),
(742, 5, 527),
(743, 18, 528),
(744, 17, 528),
(745, 18, 529),
(746, 3, 530),
(747, 11, 530),
(748, 18, 530),
(749, 63, 530),
(750, 18, 531),
(751, 17, 531),
(752, 18, 531),
(753, 18, 531),
(754, 3, 532),
(755, 9, 532),
(756, 1, 533),
(757, 1, 534),
(758, 1, 535),
(759, 4, 536),
(760, 3, 536),
(761, 7, 536),
(762, 8, 536),
(763, 61, 537),
(764, 63, 537),
(765, 3, 537),
(766, 6, 537),
(767, 9, 538),
(768, 23, 539),
(769, 48, 539),
(770, 49, 539),
(771, 30, 539),
(772, 1, 540),
(773, 1, 541),
(774, 2, 541),
(775, 4, 542),
(776, 20, 543),
(777, 1, 544),
(778, 4, 545),
(779, 1, 546),
(780, 4, 547),
(781, 30, 548),
(782, 30, 548),
(783, 30, 548),
(784, 23, 548),
(785, 4, 549),
(786, 4, 550),
(787, 4, 551),
(788, 4, 552),
(789, 4, 553),
(790, 4, 554),
(791, 23, 555),
(792, 48, 555),
(793, 49, 555),
(794, 42, 555),
(795, 4, 556),
(796, 4, 557),
(797, 4, 558),
(798, 4, 559),
(799, 4, 560),
(800, 48, 561),
(801, 30, 561),
(802, 47, 561),
(803, 59, 561),
(804, 5, 562),
(805, 4, 563),
(806, 10, 564),
(807, 4, 565),
(808, 4, 566),
(809, 8, 567),
(810, 8, 567),
(811, 5, 567),
(812, 13, 567),
(813, 6, 568),
(814, 47, 569),
(815, 31, 569),
(816, 23, 569),
(817, 30, 569),
(818, 1, 570),
(819, 1, 571),
(820, 1, 572),
(821, 1, 573),
(822, 1, 574),
(823, 1, 575),
(824, 6, 576),
(825, 3, 577),
(826, 3, 578),
(827, 3, 579),
(828, 3, 580),
(829, 4, 581),
(830, 4, 582),
(831, 4, 583),
(832, 3, 584),
(833, 5, 584),
(834, 8, 585),
(835, 3, 585),
(836, 3, 586),
(837, 3, 587),
(838, 65, 588),
(839, 44, 589),
(840, 52, 589),
(841, 23, 589),
(842, 50, 589),
(843, 44, 590),
(844, 52, 590),
(845, 23, 590),
(846, 50, 590),
(847, 2, 591),
(848, 64, 592),
(849, 64, 593),
(850, 64, 594),
(851, 64, 595),
(852, 64, 596),
(853, 64, 597),
(854, 64, 598),
(855, 64, 599),
(856, 64, 600),
(857, 64, 601),
(858, 64, 602),
(859, 64, 603),
(860, 64, 604),
(861, 64, 605),
(862, 64, 606),
(863, 64, 607),
(864, 64, 608),
(865, 2, 609),
(866, 3, 610),
(867, 3, 611),
(868, 3, 612),
(869, 2, 613),
(870, 3, 614),
(871, 2, 615),
(872, 3, 616),
(873, 3, 617),
(874, 3, 618),
(875, 3, 619),
(876, 3, 620),
(877, 3, 621),
(878, 2, 622),
(879, 3, 623),
(880, 3, 624),
(881, 3, 625),
(882, 5, 625),
(883, 3, 626),
(884, 2, 627),
(885, 2, 628),
(886, 3, 629),
(887, 3, 630),
(888, 3, 631),
(889, 2, 632),
(890, 3, 633),
(891, 2, 633),
(892, 4, 633),
(893, 6, 633),
(894, 8, 634),
(895, 12, 634),
(896, 2, 635),
(897, 2, 635),
(898, 3, 636),
(899, 2, 636),
(900, 2, 637),
(901, 2, 637),
(902, 3, 638),
(903, 2, 638),
(904, 21, 639),
(905, 23, 640),
(906, 42, 640),
(907, 42, 641),
(908, 48, 641),
(909, 57, 641),
(910, 47, 641),
(911, 3, 642),
(912, 3, 643),
(913, 3, 644),
(914, 3, 645),
(915, 3, 646),
(916, 2, 646),
(917, 4, 646),
(918, 6, 646),
(919, 8, 647),
(920, 12, 647),
(921, 3, 648),
(922, 2, 648),
(923, 4, 648),
(924, 6, 648),
(925, 8, 649),
(926, 12, 649),
(927, 3, 650),
(928, 2, 650),
(929, 4, 650),
(930, 6, 650),
(931, 8, 651),
(932, 12, 651),
(933, 3, 652),
(934, 3, 653),
(935, 3, 654),
(936, 3, 655),
(937, 2, 655),
(938, 4, 655),
(939, 6, 655),
(940, 8, 656),
(941, 12, 656),
(942, 3, 657),
(943, 2, 657),
(944, 4, 657),
(945, 6, 657),
(946, 8, 658),
(947, 12, 658),
(948, 65, 659),
(949, 2, 660),
(950, 3, 661),
(951, 2, 662),
(952, 2, 663),
(953, 2, 664),
(954, 2, 665),
(955, 2, 665),
(956, 2, 666),
(957, 2, 666),
(958, 3, 667),
(959, 3, 668),
(960, 3, 669),
(961, 3, 670),
(962, 3, 671),
(963, 3, 672),
(964, 3, 673),
(965, 3, 674),
(966, 3, 675),
(967, 3, 676),
(968, 3, 677),
(969, 3, 678),
(970, 3, 679),
(971, 3, 680),
(972, 3, 681),
(973, 3, 682),
(974, 3, 683),
(975, 3, 684),
(976, 3, 685),
(977, 3, 686),
(978, 3, 687),
(979, 2, 687),
(980, 2, 688),
(981, 48, 689),
(982, 42, 689),
(983, 51, 690),
(984, 23, 690),
(985, 26, 690),
(986, 49, 690),
(987, 30, 691),
(988, 23, 691),
(989, 2, 692),
(990, 2, 693),
(991, 2, 694),
(992, 2, 695),
(993, 2, 696),
(994, 2, 697),
(995, 2, 698),
(996, 2, 699),
(997, 2, 700),
(998, 2, 701),
(999, 2, 702),
(1000, 14, 703),
(1001, 2, 704),
(1002, 3, 704),
(1003, 4, 705),
(1004, 7, 706),
(1005, 2, 707),
(1006, 4, 708),
(1007, 9, 709),
(1008, 50, 710),
(1009, 30, 710),
(1010, 48, 711),
(1011, 42, 712),
(1012, 2, 713),
(1013, 4, 714),
(1014, 9, 715),
(1015, 2, 716),
(1016, 4, 717),
(1017, 9, 718),
(1018, 65, 719),
(1019, 65, 719),
(1020, 65, 720),
(1021, 65, 720),
(1022, 2, 721),
(1023, 4, 721),
(1024, 2, 722),
(1025, 2, 723),
(1026, 3, 724),
(1027, 2, 724),
(1028, 6, 725),
(1029, 8, 726),
(1030, 3, 727),
(1031, 3, 728),
(1032, 3, 729),
(1033, 3, 730),
(1034, 3, 731),
(1035, 3, 732),
(1036, 4, 733),
(1037, 76, 734),
(1038, 64, 734),
(1039, 64, 735),
(1040, 64, 736),
(1041, 64, 737),
(1042, 64, 738),
(1043, 64, 739),
(1044, 64, 740),
(1045, 3, 741),
(1046, 64, 742),
(1047, 3, 743),
(1048, 64, 744),
(1049, 67, 745),
(1050, 64, 745),
(1051, 72, 745),
(1052, 67, 746),
(1053, 64, 746),
(1054, 72, 746),
(1055, 23, 747),
(1056, 42, 747),
(1057, 64, 748),
(1058, 3, 749),
(1059, 64, 750),
(1060, 29, 751),
(1061, 50, 751),
(1062, 29, 752),
(1063, 50, 752),
(1064, 23, 753),
(1065, 50, 753),
(1066, 52, 754),
(1067, 52, 754),
(1068, 44, 754),
(1069, 52, 754),
(1070, 72, 755),
(1071, 72, 755),
(1072, 23, 756),
(1073, 50, 756),
(1074, 52, 757),
(1075, 52, 757),
(1076, 44, 757),
(1077, 52, 757),
(1078, 3, 758),
(1079, 3, 759),
(1080, 64, 760),
(1081, 30, 761),
(1082, 23, 761),
(1083, 67, 762),
(1084, 64, 763),
(1085, 64, 764),
(1086, 64, 765),
(1087, 67, 765),
(1088, 64, 766),
(1089, 67, 766),
(1090, 64, 767),
(1091, 67, 767),
(1092, 64, 768),
(1093, 67, 768),
(1094, 64, 769),
(1095, 67, 769),
(1096, 64, 770),
(1097, 67, 770),
(1098, 64, 771),
(1099, 67, 771),
(1100, 64, 772),
(1101, 67, 772),
(1102, 64, 773),
(1103, 67, 773),
(1104, 64, 774),
(1105, 67, 774),
(1106, 64, 775),
(1107, 67, 775),
(1108, 64, 776),
(1109, 64, 777),
(1110, 66, 778),
(1111, 68, 778),
(1112, 64, 779),
(1113, 64, 780),
(1114, 64, 781),
(1115, 67, 781),
(1116, 64, 782),
(1117, 67, 782),
(1118, 64, 783),
(1119, 67, 783),
(1121, 64, 785),
(1122, 67, 785),
(1123, 64, 786),
(1124, 67, 786),
(1126, 64, 788),
(1127, 67, 788),
(1128, 64, 789),
(1129, 67, 789),
(1130, 67, 790),
(1131, 42, 791),
(1132, 31, 791),
(1133, 57, 791),
(1134, 49, 791),
(1135, 30, 792),
(1136, 57, 792),
(1137, 67, 793),
(1138, 67, 794),
(1139, 67, 795),
(1140, 67, 796),
(1141, 67, 797),
(1142, 67, 798),
(1143, 67, 799),
(1144, 67, 800),
(1145, 67, 801),
(1146, 67, 802),
(1147, 67, 803),
(1148, 67, 804),
(1149, 67, 805),
(1150, 67, 806),
(1151, 64, 807),
(1152, 67, 807),
(1153, 64, 808),
(1154, 67, 808),
(1155, 64, 809),
(1156, 67, 809),
(1157, 64, 810),
(1158, 64, 810),
(1159, 64, 811),
(1160, 67, 811),
(1161, 64, 812),
(1162, 67, 812),
(1163, 64, 813),
(1164, 67, 813),
(1165, 48, 814),
(1166, 30, 814),
(1167, 30, 815),
(1168, 57, 815),
(1169, 64, 816),
(1170, 64, 817),
(1171, 64, 818),
(1172, 67, 819),
(1173, 67, 820),
(1174, 30, 821),
(1175, 48, 821),
(1176, 64, 822),
(1177, 64, 823),
(1178, 30, 824),
(1179, 23, 824),
(1180, 64, 825),
(1181, 64, 825),
(1182, 64, 826),
(1183, 64, 827),
(1184, 64, 828),
(1185, 64, 829),
(1186, 64, 830),
(1187, 64, 831),
(1188, 64, 832),
(1189, 64, 833),
(1190, 64, 834),
(1191, 64, 835),
(1192, 64, 836),
(1193, 67, 837),
(1194, 73, 837),
(1195, 72, 837),
(1196, 74, 837),
(1197, 64, 838),
(1198, 67, 839),
(1199, 73, 839),
(1200, 72, 839),
(1201, 74, 839),
(1202, 64, 840),
(1203, 64, 841),
(1204, 64, 841),
(1205, 64, 842),
(1206, 64, 843),
(1207, 64, 844),
(1208, 64, 845),
(1209, 64, 846),
(1210, 67, 847),
(1211, 67, 848),
(1212, 67, 849),
(1213, 67, 850),
(1214, 23, 851),
(1215, 30, 851),
(1216, 67, 852),
(1217, 64, 853),
(1218, 67, 854),
(1219, 64, 855),
(1220, 30, 856),
(1221, 29, 856),
(1222, 49, 857),
(1223, 23, 857),
(1224, 67, 858),
(1225, 67, 859),
(1226, 64, 860),
(1227, 64, 861),
(1228, 64, 862),
(1229, 67, 863),
(1230, 67, 864),
(1231, 64, 865),
(1232, 67, 866),
(1233, 30, 867),
(1234, 23, 867),
(1235, 30, 868),
(1236, 23, 868),
(1237, 64, 869),
(1238, 64, 870),
(1239, 64, 871),
(1240, 70, 872),
(1241, 64, 873),
(1242, 64, 874),
(1243, 23, 875),
(1244, 30, 875),
(1245, 51, 875),
(1246, 48, 875),
(1247, 30, 876),
(1248, 23, 876),
(1249, 23, 877),
(1250, 30, 877),
(1251, 51, 877),
(1252, 48, 877),
(1253, 64, 878),
(1254, 64, 879),
(1255, 23, 880),
(1256, 51, 880),
(1257, 30, 880),
(1258, 26, 880),
(1259, 64, 881),
(1260, 64, 882),
(1261, 64, 883),
(1262, 64, 884),
(1263, 67, 885),
(1264, 64, 886),
(1265, 30, 887),
(1266, 57, 887),
(1267, 30, 888),
(1268, 51, 888),
(1269, 64, 889),
(1270, 64, 890),
(1271, 64, 891),
(1272, 64, 892),
(1273, 64, 893),
(1274, 64, 894),
(1275, 64, 895),
(1276, 64, 896),
(1277, 64, 897),
(1278, 64, 898),
(1279, 64, 899),
(1280, 64, 900),
(1281, 64, 901),
(1282, 64, 902),
(1283, 64, 903),
(1284, 64, 904),
(1285, 64, 905),
(1286, 67, 906),
(1287, 64, 907),
(1288, 64, 908),
(1289, 64, 909),
(1290, 64, 910),
(1291, 64, 911),
(1292, 64, 912),
(1293, 64, 913),
(1294, 67, 913),
(1295, 66, 913),
(1296, 68, 913),
(1297, 74, 914),
(1298, 75, 914),
(1299, 64, 915),
(1300, 67, 915),
(1301, 66, 915),
(1302, 68, 915),
(1303, 74, 916),
(1304, 75, 916),
(1305, 64, 917),
(1306, 67, 917),
(1307, 66, 917),
(1308, 68, 917),
(1309, 74, 918),
(1310, 75, 918),
(1311, 23, 919),
(1312, 48, 919),
(1313, 47, 919),
(1314, 47, 919),
(1315, 64, 920),
(1316, 67, 920),
(1317, 66, 920),
(1318, 68, 920),
(1319, 74, 921),
(1320, 75, 921),
(1321, 64, 922),
(1322, 67, 922),
(1323, 64, 923),
(1324, 67, 923),
(1325, 23, 924),
(1326, 46, 924),
(1327, 23, 925),
(1328, 46, 925),
(1329, 48, 926),
(1330, 42, 926),
(1331, 23, 927),
(1332, 42, 927),
(1333, 30, 928),
(1334, 23, 928),
(1335, 30, 929),
(1336, 23, 929),
(1337, 30, 930),
(1338, 23, 930),
(1339, 23, 931),
(1340, 30, 931),
(1341, 29, 931),
(1342, 26, 931),
(1343, 30, 932),
(1344, 51, 932);

--
-- Acionadores `item_pizza`
--
DELIMITER $$
CREATE TRIGGER `trigger_validacao_item_pizza_bi` BEFORE INSERT ON `item_pizza` FOR EACH ROW BEGIN
	if (new.pizza_item_pizza is null or new.pizza_item_pizza < 0 ) then
		signal sqlstate '45000' set message_text = 'PIZZA não pode ser vazio/nulo.';
	end if;
    if (new.sabor_pizza_item_pizza is null or new.sabor_pizza_item_pizza < 0 ) then
		signal sqlstate '45000' set message_text = 'SABOR PIZZA não pode ser vazio/nulo.';
	end if;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trigger_validacao_item_pizza_bu` BEFORE UPDATE ON `item_pizza` FOR EACH ROW BEGIN
	if (new.pizza_item_pizza is null or new.pizza_item_pizza < 0 ) then
		signal sqlstate '45000' set message_text = 'PIZZA não pode ser vazio/nulo.';
	end if;
    if (new.sabor_pizza_item_pizza is null or new.sabor_pizza_item_pizza < 0 ) then
		signal sqlstate '45000' set message_text = 'SABOR PIZZA não pode ser vazio/nulo.';
	end if;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedido`
--

CREATE TABLE `pedido` (
  `codigo_pedido` bigint(20) NOT NULL,
  `data_hora_pedido` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cliente_pizzaria_pedido` bigint(20) NOT NULL,
  `valor_total_pedido` decimal(10,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `forma_pagamento_pedido` bigint(20) NOT NULL DEFAULT '0' COMMENT 'fk para forma_pagamento',
  `observacao_pedido` varchar(400) DEFAULT NULL COMMENT 'campo para adicionar observações como, por exemplo: sem azeitona na pizza.',
  `pizzaria_pedido` bigint(20) NOT NULL,
  `telefone_pedido` varchar(11) NOT NULL,
  `endereco_pedido` varchar(400) NOT NULL,
  `numero_endereco_pedido` bigint(20) UNSIGNED DEFAULT NULL COMMENT '0 - sem número.',
  `complemento_endereco_pedido` varchar(500) DEFAULT NULL,
  `cidade_pedido` bigint(20) NOT NULL,
  `uf_pedido` varchar(2) NOT NULL,
  `referencia_endereco_pedido` varchar(400) DEFAULT NULL,
  `bairro_pedido` bigint(20) NOT NULL,
  `cep_pedido` varchar(10) DEFAULT NULL,
  `mapa_url_pedido` varchar(600) DEFAULT NULL COMMENT 'url que leva posição no mapa do Bing',
  `status_pedido` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '0 - CANCELADO, 1 - SOLICITADO, 2 - PEDIDO ATENDIDO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `pedido`
--

INSERT INTO `pedido` (`codigo_pedido`, `data_hora_pedido`, `cliente_pizzaria_pedido`, `valor_total_pedido`, `forma_pagamento_pedido`, `observacao_pedido`, `pizzaria_pedido`, `telefone_pedido`, `endereco_pedido`, `numero_endereco_pedido`, `complemento_endereco_pedido`, `cidade_pedido`, `uf_pedido`, `referencia_endereco_pedido`, `bairro_pedido`, `cep_pedido`, `mapa_url_pedido`, `status_pedido`) VALUES
(404, '2017-11-01 14:03:36', 27, '79.50', 8, '', 1, '06599221746', 'Casa verde', NULL, NULL, 1, 'MT', '', 11, '78050-690', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.584426%252C%2B-56.058936%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOjfAWpHER7GsAhjt-oCPfBgIf5lZGzR7gFTFjuhDvwZ1pF3jZeSHETtGE8JR3u01D0TSDp8hsroK-6xI7af3ciSTvRQ_RMzt8EOIVKoqBw0xAH_A&s=1&enc=AZPMrFMBIsVDnWGsHqeThgxlYTB7gAC37imMcgIVMhg7zOwYI89Bs8vLAJduKHawti59klmajDtArxPuCPD1w2Ct', 2),
(405, '2017-10-16 23:12:24', 28, '57.00', 2, ' Observação do cliente: Tem aquele desconto de segunda né?! ', 2, '65999898645', 'Av Miguel Sutil, 3271, Poção', NULL, NULL, 1, 'MT', '', 89, '78010-500', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3DAv.%2BMiguel%2BSutil%252C%2B3271%252C%2B78015%2BCuiab%25C3%25A1%252C%2BBrazil%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATPvKlKMNbjCF_LUO3RbQd6Wbpvh1NKF-VTW7q0F3MedR0LgGWTjmv-14lKxsXfqyd1jl6yGsADj2ZnvTQW9Z3djjII44nlCiTO9-7SWntVaWBBe3Q&s=1&enc=AZPCvkNBJj0aAQOV5U9kftjg2x_mho_2P0_PWbxkmwin_CXV6Y873onpwIKGbCk5rfHlv-jZC8-1gMjN6u0tIiwj', 2),
(406, '2017-11-01 14:03:36', 27, '274.25', 8, '', 1, '45465656557', 'Ff ff ff', NULL, NULL, 1, 'MT', '', 1, '78050-690', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.584426%252C%2B-56.058936%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOjfAWpHER7GsAhjt-oCPfBgIf5lZGzR7gFTFjuhDvwZ1pF3jZeSHETtGE8JR3u01D0TSDp8hsroK-6xI7af3ciSTvRQ_RMzt8EOIVKoqBw0xAH_A&s=1&enc=AZPMrFMBIsVDnWGsHqeThgxlYTB7gAC37imMcgIVMhg7zOwYI89Bs8vLAJduKHawti59klmajDtArxPuCPD1w2Ct', 1),
(407, '2017-11-01 14:03:36', 27, '40.00', 8, '', 1, '7374844848', 'Bdbd', NULL, NULL, 1, 'MT', '', 1, '78050-690', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.584426%252C%2B-56.058936%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOjfAWpHER7GsAhjt-oCPfBgIf5lZGzR7gFTFjuhDvwZ1pF3jZeSHETtGE8JR3u01D0TSDp8hsroK-6xI7af3ciSTvRQ_RMzt8EOIVKoqBw0xAH_A&s=1&enc=AZPMrFMBIsVDnWGsHqeThgxlYTB7gAC37imMcgIVMhg7zOwYI89Bs8vLAJduKHawti59klmajDtArxPuCPD1w2Ct', 1),
(408, '2017-11-01 14:03:36', 27, '0.00', 8, ' Observação do cliente: Dcgg', 1, '455345455', 'Ddx', NULL, NULL, 1, 'MT', '', 101, '78050-690', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.584426%252C%2B-56.058936%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOjfAWpHER7GsAhjt-oCPfBgIf5lZGzR7gFTFjuhDvwZ1pF3jZeSHETtGE8JR3u01D0TSDp8hsroK-6xI7af3ciSTvRQ_RMzt8EOIVKoqBw0xAH_A&s=1&enc=AZPMrFMBIsVDnWGsHqeThgxlYTB7gAC37imMcgIVMhg7zOwYI89Bs8vLAJduKHawti59klmajDtArxPuCPD1w2Ct', 1),
(409, '2017-11-01 14:03:36', 27, '85.00', 8, '', 1, '4848484884', 'Casa verfe', NULL, NULL, 1, 'MT', '', 1, '78050-690', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.584426%252C%2B-56.058936%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOjfAWpHER7GsAhjt-oCPfBgIf5lZGzR7gFTFjuhDvwZ1pF3jZeSHETtGE8JR3u01D0TSDp8hsroK-6xI7af3ciSTvRQ_RMzt8EOIVKoqBw0xAH_A&s=1&enc=AZPMrFMBIsVDnWGsHqeThgxlYTB7gAC37imMcgIVMhg7zOwYI89Bs8vLAJduKHawti59klmajDtArxPuCPD1w2Ct', 1),
(410, '2017-11-01 14:03:46', 29, '84.75', 9, ' Observação do cliente: quero bacon caprichado', 1, '85478569', 'rua maria maria, 11, cpa 4', NULL, NULL, 1, 'MT', '', 3, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5625033%252C%2B-56.0321415%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNJmz5xmiv_uGuj3in_5A4KSJwkDw7w6rXlfFo7JK4D1g_vhhlDYl3URORpnSpaLp6zjc1qCCQVXGGmikzNZvf7h2Ms8bdz6Lim_cKJbMSJ6PF0sg&s=1&enc=AZMszkNQAO2Ystfou7qfu9fgi8NTcigQKc-JYofzU1_NIJMqR5lSPA2wF8kbS1jtXF-pzHeVGfPeb-B5TT1ctxdf', 2),
(411, '2017-10-21 22:39:42', 30, '50.00', 1, '', 2, '992162935', 'Rua Ciro furtado Sodré número 223', NULL, NULL, 1, 'MT', '', 64, '78050-700', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3DRua%2BCiro%2BFurtado%2BSodr%25C3%25A9%252C%2B78.050-625%2BCuiab%25C3%25A1%252C%2BBrazil%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATPMPUG1W2cj9SFggwOUYF4-X9XGzpui3fHz-kRol85T9F5t9hWx1Dlp1y89RoUcfFKWT1sBXNU3Q8vY-U8nMjC48vP59zIMoYSn5AvuBAgNyx71pQ&s=1&enc=AZOgDL-QcwqXJhHyyZiTqiL6Q5RZba36ZfPf9U25Ua2NCH-cV9Xq1dOGscwQ2FUt7wzZMZUAnqAo4UwBonymq_X8', 2),
(412, '2017-11-01 14:03:36', 27, '40.00', 8, '', 1, '73473833833', 'Babbsbs', NULL, NULL, 1, 'MT', '', 4, '78050-690', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.584426%252C%2B-56.058936%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOjfAWpHER7GsAhjt-oCPfBgIf5lZGzR7gFTFjuhDvwZ1pF3jZeSHETtGE8JR3u01D0TSDp8hsroK-6xI7af3ciSTvRQ_RMzt8EOIVKoqBw0xAH_A&s=1&enc=AZPMrFMBIsVDnWGsHqeThgxlYTB7gAC37imMcgIVMhg7zOwYI89Bs8vLAJduKHawti59klmajDtArxPuCPD1w2Ct', 1),
(413, '2017-10-24 19:38:06', 27, '96.00', 1, 'Levar troco para R$100.  Observação do cliente: Nada', 1, '83384738377', 'Casa verde', NULL, NULL, 1, 'MT', '', 1, '78050-690', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.584426%252C%2B-56.058936%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOjfAWpHER7GsAhjt-oCPfBgIf5lZGzR7gFTFjuhDvwZ1pF3jZeSHETtGE8JR3u01D0TSDp8hsroK-6xI7af3ciSTvRQ_RMzt8EOIVKoqBw0xAH_A&s=1&enc=AZPMrFMBIsVDnWGsHqeThgxlYTB7gAC37imMcgIVMhg7zOwYI89Bs8vLAJduKHawti59klmajDtArxPuCPD1w2Ct', 1),
(414, '2017-10-28 01:08:03', 31, '46.00', 2, ' Observação do cliente: Sem cebola', 2, '984075859', 'Residencial vila bela', NULL, NULL, 1, 'MT', '', 12, '78045-310', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.573254470082%252C%2B-56.096228060727%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATO4KYbCN2J6hUVxzlWlEoa51SJRWwfEdXoStomcq4hYHK44jRtv8K-TOp_xoAKcSRON-BWlUSMDNYdSIr5Mg1s5q-y2VohNPmOzFR3J1HGgGi1cug&s=1&enc=AZOBhSEy4A_Nw0TGQROj7guy4WH0G7HNn99bTkrQxK1Rc2T-EdCDVQokmjzlEHHLPNMyPBQjijPWDk4ikFEwyCCn', 2),
(415, '2017-11-01 14:03:36', 29, '56.00', 8, '', 1, '56466646464', 'coxinha', NULL, NULL, 1, 'MT', '', 3, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5624117%252C%2B-56.0321282%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATMO3UR0hiLlI8kzvoppFrEn411x03qGo0a7Zar0hzRrsjn01ov-NQKL_v4gOIEJTQiRkX3kT5YdsFx-V6p6kwvJoRltnysOg5e3JGhEJMLkkl507g&s=1&enc=AZMIS0w3Qppe0sVi5GONPVM7oo_Wyimfm0Y5zxApz9WCSdhcjfrIlh50YRJJaZ5uadQPKMSSY3ukvJZq3dm1y_sY', 2),
(416, '2017-11-01 14:03:36', 29, '56.00', 8, '', 1, '14546464161', 'coxinha', NULL, NULL, 1, 'MT', '', 3, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5624117%252C%2B-56.0321282%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATMO3UR0hiLlI8kzvoppFrEn411x03qGo0a7Zar0hzRrsjn01ov-NQKL_v4gOIEJTQiRkX3kT5YdsFx-V6p6kwvJoRltnysOg5e3JGhEJMLkkl507g&s=1&enc=AZMIS0w3Qppe0sVi5GONPVM7oo_Wyimfm0Y5zxApz9WCSdhcjfrIlh50YRJJaZ5uadQPKMSSY3ukvJZq3dm1y_sY', 2),
(417, '2017-10-28 22:13:12', 32, '57.00', 3, ' Observação do cliente: Não quero cebola', 2, '992451000', 'Rua ciriaco candia 77 carumbé', NULL, NULL, 1, 'MT', '', 64, '78050-690', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.586538%252C%2B-56.059167%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOGFOQgEg1QPUBpRo5d8Uo_KAN4q5u0L5MrildYDXRLkjM9nr5LrZK9vopM3usM8l6QGlq15_OZa91e1vEzPckPq9slNECURno-1qnnwfTRhMF9vg&s=1&enc=AZMPJgFMhfL5yEN1wgD91TP7ZYEYsdilIYd439dFj8nCAnT0-dVEBA7qvlIaxuAt7yTqsNZcrW1vnlhlnD3Xjyly', 2),
(418, '2017-11-01 14:03:46', 29, '60.00', 9, '', 1, '77777888888', 'Ufsgnn', NULL, NULL, 1, 'MT', '', 3, '78030-970', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.608397%252C%2B-56.114051%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOH_ZTzYRtqY35fcbSJchKZvqLiTZhhcULD2TkYUJ93iY1kcIINArrZ4Ual0wV6iZ-ezzkO2kNgA53nIW2u01Csm9ldc8xrywtleWM7FgGL6cyckg&s=1&enc=AZNlVpmCy7Tn4KGUOaZqgiIVPjCWczOCeCbdhVzINjnExi4nqXnAjDqcJHH-ZjhFD00J6KdLGQ3qsLXG6m8jVLMv', 2),
(419, '2017-11-01 14:03:36', 29, '60.00', 8, '', 1, '6543455667', 'Ufsgnn', NULL, NULL, 1, 'MT', '', 3, '78030-970', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.608397%252C%2B-56.114051%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOH_ZTzYRtqY35fcbSJchKZvqLiTZhhcULD2TkYUJ93iY1kcIINArrZ4Ual0wV6iZ-ezzkO2kNgA53nIW2u01Csm9ldc8xrywtleWM7FgGL6cyckg&s=1&enc=AZNlVpmCy7Tn4KGUOaZqgiIVPjCWczOCeCbdhVzINjnExi4nqXnAjDqcJHH-ZjhFD00J6KdLGQ3qsLXG6m8jVLMv', 2),
(420, '2017-11-01 14:03:36', 29, '49.00', 8, '', 1, '77778899988', 'Ih dyjn djkd', NULL, NULL, 1, 'MT', '', 3, '78030-970', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.608397%252C%2B-56.114051%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATMYvjdvmiTgnqidhVAxn0K2nojGZ0FV9LoFoHdprspABX319FWVJNSxQHHxOSFZPLTDi5sF-ooZZbQ4KTgDmsv42Upsvne3QviEDXQGqCAAWCdaKg&s=1&enc=AZMbZYkGPW0ckA0skN6Q01huRSk4LG4o85SMRnVwj-faet2YFEEOXdky4oPrQqeEy-w6tuWhsX5nUu3G_4bbl4Up', 2),
(421, '2017-10-29 16:41:24', 29, '43.00', 8, '', 1, '789929089', 'Dna aisnlz', NULL, NULL, 1, 'MT', '', 3, '78050-700', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.58858%252C%2B-56.059254%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATPeJRG7vsVBLGKZbmy_9bjWKLVJK48cLWVoS0ZPnwvSbBhEiaSO6lLZyYvC58SgahdqOYDQzNcClA5t1wXNbs8Iek3qVBb6AJPC-K5v57Nj_EjAog&s=1&enc=AZPhmmAq8QDiLS6XJYigfAL-yuPWHfkSFYVOg6PYCjyo6gND4WFO-6i3cMo8G9G363HfFIm0kF0Hn3uNvUC_8hcz', 2),
(422, '2017-10-29 17:03:43', 29, '43.00', 8, '', 1, '3838383939', 'Dna aisnlz', NULL, NULL, 1, 'MT', '', 3, '78050-700', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.58858%252C%2B-56.059254%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATPeJRG7vsVBLGKZbmy_9bjWKLVJK48cLWVoS0ZPnwvSbBhEiaSO6lLZyYvC58SgahdqOYDQzNcClA5t1wXNbs8Iek3qVBb6AJPC-K5v57Nj_EjAog&s=1&enc=AZPhmmAq8QDiLS6XJYigfAL-yuPWHfkSFYVOg6PYCjyo6gND4WFO-6i3cMo8G9G363HfFIm0kF0Hn3uNvUC_8hcz', 1),
(423, '2017-10-29 20:46:12', 33, '57.00', 8, '', 1, '46456456456', 'kkk', NULL, NULL, 1, 'MT', '', 51, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626391%252C%2B-56.0510676%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOniyhAhkoBe0St6TIn4T11M0lpdQUrsgRToFPFFbJ073WnhnkJRD-C9JCJwr9MskCvdlquGEGzEAxsYaP92n0AJTRAEomfxGAcgSmJcGmYvRLrEg&s=1&enc=AZNSrb1UbMqnUr0gEAKqluIX78JZSjA38OC55Kxg_W3WT-5uHbl0-uhBte6x4bRexw61JO97_hGCTWk4cX5bf825', 1),
(424, '2017-10-29 22:26:34', 34, '92.00', 2, '', 2, '99691-3884', 'Rua 66 quadra 94 casa 11 cpa 4 3° etapa', NULL, NULL, 1, 'MT', '', 3, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.562526%252C%2B-56.03211%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNfX7QZWEFRsyBMT-NxwjKRKc4E7Ts97Yy2bGbR-nHjEpZAySnjGMPYrm27DIAUs_YJ0vrdgtVEKnaiXsy16a1i1f-j7ABLPnjFddYvWUzuZI5fTQ&s=1&enc=AZPRfRIe67K1lQTS6JWHMJqi2GfldMjf85zJ7l4JBp5CLYDXHWTH_1JcRoNuEHbNXi5S7nvcAKHHCqKVHYZDRXyK', 2),
(425, '2017-10-29 22:27:30', 27, '38.00', 8, '', 1, '45646456546', 'teste', NULL, NULL, 1, 'MT', '', 3, '78050-690', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.584426%252C%2B-56.058936%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOjfAWpHER7GsAhjt-oCPfBgIf5lZGzR7gFTFjuhDvwZ1pF3jZeSHETtGE8JR3u01D0TSDp8hsroK-6xI7af3ciSTvRQ_RMzt8EOIVKoqBw0xAH_A&s=1&enc=AZPMrFMBIsVDnWGsHqeThgxlYTB7gAC37imMcgIVMhg7zOwYI89Bs8vLAJduKHawti59klmajDtArxPuCPD1w2Ct', 1),
(426, '2017-10-29 22:34:50', 35, '38.00', 8, '', 1, '24345345345', 'teste', NULL, NULL, 1, 'MT', '', 3, '78049909', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.572845%252C%2B-56.076049%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP9c256SX8fNX0RKC68TRotrV4wwf6PZVju9obEDTH5x3XTwPonbZYyQpB8dfROtEFGgzWvdw3sGfMxJb6ncpW-C0891AMTgwKwU-0cMPiWeCIcFg&s=1&enc=AZMULv8QbGp_n_2E3zruG95yA9VLdsmwZWE3kd8VcWrCpaZ31q0SOgJNPaXRNxoG4rh1DW0pJy5gAP11VgUs6Nxc', 1),
(427, '2017-10-29 22:40:02', 35, '38.00', 8, '', 1, '35345345345', 'teste', NULL, NULL, 1, 'MT', '', 3, '78049909', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.572845%252C%2B-56.076049%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP9c256SX8fNX0RKC68TRotrV4wwf6PZVju9obEDTH5x3XTwPonbZYyQpB8dfROtEFGgzWvdw3sGfMxJb6ncpW-C0891AMTgwKwU-0cMPiWeCIcFg&s=1&enc=AZMULv8QbGp_n_2E3zruG95yA9VLdsmwZWE3kd8VcWrCpaZ31q0SOgJNPaXRNxoG4rh1DW0pJy5gAP11VgUs6Nxc', 1),
(428, '2017-10-29 22:47:15', 36, '38.00', 8, '', 1, '56756756756', 'teste', NULL, NULL, 1, 'MT', '', 3, '78049909', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.572845%252C%2B-56.076049%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP9c256SX8fNX0RKC68TRotrV4wwf6PZVju9obEDTH5x3XTwPonbZYyQpB8dfROtEFGgzWvdw3sGfMxJb6ncpW-C0891AMTgwKwU-0cMPiWeCIcFg&s=1&enc=AZMULv8QbGp_n_2E3zruG95yA9VLdsmwZWE3kd8VcWrCpaZ31q0SOgJNPaXRNxoG4rh1DW0pJy5gAP11VgUs6Nxc', 1),
(429, '2017-10-30 12:10:12', 37, '47.00', 8, '', 1, '5677567667', 'Hhh', NULL, NULL, 1, 'MT', '', 104, '78050-923', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.573008%252C%2B-56.072952%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOjXCyPklbEl3slyHbYZmG17H0bAHA0pXcEqDRBUPluXp7Uf4wRSnRq5CL_xHBDSDzK_tyB4DQJDxIgcagMo0hO1PlKyEWNDbNhZS-B485BuzjFgg&s=1&enc=AZOIayQTuvVAIMm10aKzPNHcX70xLSGZNGDbvwVIsdbWaVX3-guyl1toKaPu-4qIIe9s-cTQkU3XHv8Wr7ugwuZC', 1),
(430, '2017-10-30 23:23:41', 38, '50.00', 3, ' Observação do cliente: Não', 2, '65984215292', 'Residencial São Carlos, bloco 46, APT 403... Preciso que me ligue quando estiver no portão, pois não tem como eu ver. 65984215292', NULL, NULL, 1, 'MT', '', 95, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.584494%252C%2B-56.051653%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNiPgwDRVkh6IslwMHiXX8uXctnaIH7EpcE0QVz1XziCMU3ZuTGWqEz3C3qHEXbcPfQZLDx8fJ6knyUNCqWk1eyGy-ZkDFS94Z02hKzCMCf2X74xg&s=1&enc=AZMjEs8jBbpl4LnQSNZJ0VnQrIV0bGWIv2R9RLTRgguTYMzq3WPsHX7XeGEduYq-13oIMxed6T9EodGgoYHMMy3z', 2),
(431, '2017-10-31 14:08:37', 37, '56.00', 8, '', 1, '8484738484', 'Jd j DJ RJ f', NULL, NULL, 1, 'MT', '', 1, '78049909', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.572905%252C%2B-56.076398%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATMv5roCvA0DxOr9rS_81cfn7BKQwhVunapTkmFVRr-KRIz22MgBOTaPfcotugDuBcuHFS9VW3IqwI1vWu2z11yRBN_xqLyB7AFBcm3qFE4Q30BwsQ&s=1&enc=AZP94mYFUXdE_PcKFJvIE3Snfh-wrYPfegxGIFVZb1vM1j1BtZmqZsEaTJfEFs6sGlA3YABcKNN6ggpqzyYmqM3Q', 1),
(432, '2017-10-31 15:20:08', 37, '115.50', 8, '', 1, '67758575766', 'Nada', NULL, NULL, 1, 'MT', '', 1, '78049909', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.572905%252C%2B-56.076398%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATMv5roCvA0DxOr9rS_81cfn7BKQwhVunapTkmFVRr-KRIz22MgBOTaPfcotugDuBcuHFS9VW3IqwI1vWu2z11yRBN_xqLyB7AFBcm3qFE4Q30BwsQ&s=1&enc=AZP94mYFUXdE_PcKFJvIE3Snfh-wrYPfegxGIFVZb1vM1j1BtZmqZsEaTJfEFs6sGlA3YABcKNN6ggpqzyYmqM3Q', 1),
(433, '2017-10-31 17:12:30', 37, '46.00', 8, '', 1, '7457455587', 'http://www.google.com/maps/place/-23.803762,-16.957', NULL, NULL, 1, 'MT', '', 1, '78000000', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-12.245807%252C%2B-16.490852%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATPRNa_bB_7EnTwyPvkDD9HcltqxxwKg-xj-cHHVlt3BTlL4-agAdyuOYH-o9Ns0p6K1QdVrNCyBQ-xFyCnmWFr3TQSCMAZC1bevpaa29qZQzIWvig&s=1&enc=AZORENcJ1kvr81Cax6kCdmRJ4CieL-3wgBxrlCl1pGwYqFdZERA_hNktqvx_9CCfb2ijM6S-t9OsnNBO5x4PTc1z', 1),
(434, '2017-10-31 17:23:40', 37, '165.50', 9, '', 1, '6337838458', 'Casa verde', NULL, NULL, 1, 'MT', '', 1, '78000000', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-14.715577%252C%2B-31.663192%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATO-cP0Bb72bR5cFKSSpw6-uFlHWyhCnaeotREk23QTT3M1LCECuQzwnvqvPe9zjIjJIVncI5ZtmUT1rNcYKMTMqe94Yk0IKkchz3gWUY-UpvovGMA&s=1&enc=AZP0FY_0X9_xZK0nU5IZxW65IMEdp0yzTrmIinQSbCw3okZQwky1R0uNktOi8ACb92R631OGz5C8EFSO30U-yVwj', 2),
(435, '2017-10-31 21:16:27', 39, '50.00', 1, ' Observação do cliente: Nao quero o bacon tá', 2, '999277870', 'Rua Omã bairro alvorada , apartamento 204 bloco 41', NULL, NULL, 1, 'MT', '', 4, '78049-901', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.571769160274%252C%2B-56.084480319821%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATMWm5HJQHO7etg2P1QfyOeYVmA3LywsdyTKefOvY7zFwFO-s3pGRQbwZM9UzxDqDnSkyJdkNNDYefwBcnIjRcg79Xl3WUCVjFJI2mWRzTgE3U_M5Q&s=1&enc=AZNjiDgU7xDGToe37Rl20oHcM54NI2m_3ml-JNMIWqdqI2yoqPD3b-zdRKaVu1boJKlm_MC_BDfslyHAn1VS8CLc', 2),
(436, '2017-11-01 00:55:21', 29, '35.00', 9, '', 1, '45456464646', 'higolhpç', NULL, NULL, 1, 'MT', '', 140, '78050-700', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.58858%252C%2B-56.059254%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATPeJRG7vsVBLGKZbmy_9bjWKLVJK48cLWVoS0ZPnwvSbBhEiaSO6lLZyYvC58SgahdqOYDQzNcClA5t1wXNbs8Iek3qVBb6AJPC-K5v57Nj_EjAog&s=1&enc=AZPhmmAq8QDiLS6XJYigfAL-yuPWHfkSFYVOg6PYCjyo6gND4WFO-6i3cMo8G9G363HfFIm0kF0Hn3uNvUC_8hcz', 0),
(439, '2017-11-01 01:06:24', 29, '31.00', 8, '', 1, '33315451', '55555', NULL, NULL, 1, 'MT', '', 3, '78050-700', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.58858%252C%2B-56.059254%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATPeJRG7vsVBLGKZbmy_9bjWKLVJK48cLWVoS0ZPnwvSbBhEiaSO6lLZyYvC58SgahdqOYDQzNcClA5t1wXNbs8Iek3qVBb6AJPC-K5v57Nj_EjAog&s=1&enc=AZPhmmAq8QDiLS6XJYigfAL-yuPWHfkSFYVOg6PYCjyo6gND4WFO-6i3cMo8G9G363HfFIm0kF0Hn3uNvUC_8hcz', 1),
(440, '2017-11-01 01:11:21', 29, '31.00', 9, '', 1, '555847578', '5', NULL, NULL, 1, 'MT', '', 3, '78050-700', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.58858%252C%2B-56.059254%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATPeJRG7vsVBLGKZbmy_9bjWKLVJK48cLWVoS0ZPnwvSbBhEiaSO6lLZyYvC58SgahdqOYDQzNcClA5t1wXNbs8Iek3qVBb6AJPC-K5v57Nj_EjAog&s=1&enc=AZPhmmAq8QDiLS6XJYigfAL-yuPWHfkSFYVOg6PYCjyo6gND4WFO-6i3cMo8G9G363HfFIm0kF0Hn3uNvUC_8hcz', 1),
(443, '2017-11-01 01:17:56', 29, '31.00', 10, '', 1, '888887744', '5', NULL, NULL, 1, 'MT', '', 3, '78050-700', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.58858%252C%2B-56.059254%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATPeJRG7vsVBLGKZbmy_9bjWKLVJK48cLWVoS0ZPnwvSbBhEiaSO6lLZyYvC58SgahdqOYDQzNcClA5t1wXNbs8Iek3qVBb6AJPC-K5v57Nj_EjAog&s=1&enc=AZPhmmAq8QDiLS6XJYigfAL-yuPWHfkSFYVOg6PYCjyo6gND4WFO-6i3cMo8G9G363HfFIm0kF0Hn3uNvUC_8hcz', 1),
(444, '2017-11-01 01:58:34', 29, '36.00', 10, '', 1, '2155456365', 'ok', NULL, NULL, 1, 'MT', '', 3, '78050-700', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.58858%252C%2B-56.059254%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATPeJRG7vsVBLGKZbmy_9bjWKLVJK48cLWVoS0ZPnwvSbBhEiaSO6lLZyYvC58SgahdqOYDQzNcClA5t1wXNbs8Iek3qVBb6AJPC-K5v57Nj_EjAog&s=1&enc=AZPhmmAq8QDiLS6XJYigfAL-yuPWHfkSFYVOg6PYCjyo6gND4WFO-6i3cMo8G9G363HfFIm0kF0Hn3uNvUC_8hcz', 1),
(445, '2017-11-01 02:02:24', 29, '36.00', 10, '', 1, '9999854763', '99999', NULL, NULL, 1, 'MT', '', 3, '78050-700', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.58858%252C%2B-56.059254%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATPeJRG7vsVBLGKZbmy_9bjWKLVJK48cLWVoS0ZPnwvSbBhEiaSO6lLZyYvC58SgahdqOYDQzNcClA5t1wXNbs8Iek3qVBb6AJPC-K5v57Nj_EjAog&s=1&enc=AZPhmmAq8QDiLS6XJYigfAL-yuPWHfkSFYVOg6PYCjyo6gND4WFO-6i3cMo8G9G363HfFIm0kF0Hn3uNvUC_8hcz', 1),
(446, '2017-11-01 02:15:06', 29, '24.00', 10, '', 1, '54565455652', 'iohugf', NULL, NULL, 1, 'MT', '', 3, '78050-700', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.58858%252C%2B-56.059254%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATPeJRG7vsVBLGKZbmy_9bjWKLVJK48cLWVoS0ZPnwvSbBhEiaSO6lLZyYvC58SgahdqOYDQzNcClA5t1wXNbs8Iek3qVBb6AJPC-K5v57Nj_EjAog&s=1&enc=AZPhmmAq8QDiLS6XJYigfAL-yuPWHfkSFYVOg6PYCjyo6gND4WFO-6i3cMo8G9G363HfFIm0kF0Hn3uNvUC_8hcz', 1),
(447, '2017-11-01 12:23:47', 37, '44.00', 10, ' Observação do cliente: Hdjdj', 1, '57648484884', 'Hfjjff', NULL, NULL, 1, 'MT', '', 1, '78000000', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-14.715577%252C%2B-31.663192%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATO-cP0Bb72bR5cFKSSpw6-uFlHWyhCnaeotREk23QTT3M1LCECuQzwnvqvPe9zjIjJIVncI5ZtmUT1rNcYKMTMqe94Yk0IKkchz3gWUY-UpvovGMA&s=1&enc=AZP0FY_0X9_xZK0nU5IZxW65IMEdp0yzTrmIinQSbCw3okZQwky1R0uNktOi8ACb92R631OGz5C8EFSO30U-yVwj', 1),
(448, '2017-11-01 13:18:06', 37, '44.00', 10, '', 1, '848484884', 'Hfjjff', NULL, NULL, 1, 'MT', '', 1, '78000000', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-14.715577%252C%2B-31.663192%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATO-cP0Bb72bR5cFKSSpw6-uFlHWyhCnaeotREk23QTT3M1LCECuQzwnvqvPe9zjIjJIVncI5ZtmUT1rNcYKMTMqe94Yk0IKkchz3gWUY-UpvovGMA&s=1&enc=AZP0FY_0X9_xZK0nU5IZxW65IMEdp0yzTrmIinQSbCw3okZQwky1R0uNktOi8ACb92R631OGz5C8EFSO30U-yVwj', 1),
(449, '2017-11-01 13:25:48', 37, '44.00', 1, 'Levar troco para R$50.  Observação do cliente: Sem cebola', 1, '838488488', 'Na Sefaz por favot', NULL, NULL, 1, 'MT', '', 1, '78050-923', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.572492%252C%2B-56.076907%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNxvUxVnQHfmElCuGK8fm-_8PbtRUh-CLlg6oC7x1szCVMpu23ExccRmFdCzxyJIPePJHuqwl-DtdhoBw9DEntIKhcXV81AXs5bYv1DWBQXO4ACdg&s=1&enc=AZOTWFDoiXRJwy8wfjD4FjAph_5eu1ZV1D-6RB3pVrRzWDLXouBu0C1cTyC_Pr06IoK8HcKbXGZblUe-wsF-rKNj', 1),
(450, '2017-11-01 13:27:04', 37, '44.00', 10, '', 1, '84484838333', 'Na Sefaz por favot', NULL, NULL, 1, 'MT', '', 1, '78050-923', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.572492%252C%2B-56.076907%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNxvUxVnQHfmElCuGK8fm-_8PbtRUh-CLlg6oC7x1szCVMpu23ExccRmFdCzxyJIPePJHuqwl-DtdhoBw9DEntIKhcXV81AXs5bYv1DWBQXO4ACdg&s=1&enc=AZOTWFDoiXRJwy8wfjD4FjAph_5eu1ZV1D-6RB3pVrRzWDLXouBu0C1cTyC_Pr06IoK8HcKbXGZblUe-wsF-rKNj', 1),
(451, '2017-11-02 00:21:45', 33, '46.00', 10, '', 1, '34509348564', 'casa verde', NULL, NULL, 1, 'MT', '', 1, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626391%252C%2B-56.0510676%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOniyhAhkoBe0St6TIn4T11M0lpdQUrsgRToFPFFbJ073WnhnkJRD-C9JCJwr9MskCvdlquGEGzEAxsYaP92n0AJTRAEomfxGAcgSmJcGmYvRLrEg&s=1&enc=AZNSrb1UbMqnUr0gEAKqluIX78JZSjA38OC55Kxg_W3WT-5uHbl0-uhBte6x4bRexw61JO97_hGCTWk4cX5bf825', 1),
(452, '2017-11-02 00:22:52', 33, '46.00', 10, '', 1, '45645645645', 'casa verde', NULL, NULL, 1, 'MT', '', 1, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626391%252C%2B-56.0510676%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOniyhAhkoBe0St6TIn4T11M0lpdQUrsgRToFPFFbJ073WnhnkJRD-C9JCJwr9MskCvdlquGEGzEAxsYaP92n0AJTRAEomfxGAcgSmJcGmYvRLrEg&s=1&enc=AZNSrb1UbMqnUr0gEAKqluIX78JZSjA38OC55Kxg_W3WT-5uHbl0-uhBte6x4bRexw61JO97_hGCTWk4cX5bf825', 1),
(453, '2017-11-02 01:15:23', 33, '46.00', 10, '', 1, '54745767567', 'casa verde', NULL, NULL, 1, 'MT', '', 1, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626391%252C%2B-56.0510676%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOniyhAhkoBe0St6TIn4T11M0lpdQUrsgRToFPFFbJ073WnhnkJRD-C9JCJwr9MskCvdlquGEGzEAxsYaP92n0AJTRAEomfxGAcgSmJcGmYvRLrEg&s=1&enc=AZNSrb1UbMqnUr0gEAKqluIX78JZSjA38OC55Kxg_W3WT-5uHbl0-uhBte6x4bRexw61JO97_hGCTWk4cX5bf825', 1),
(454, '2017-11-04 02:35:36', 33, '112.00', 11, ' Observação do cliente: nao quero cebola', 1, '32453534563', 'casa verde', NULL, NULL, 1, 'MT', '', 7, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626391%252C%2B-56.0510676%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOniyhAhkoBe0St6TIn4T11M0lpdQUrsgRToFPFFbJ073WnhnkJRD-C9JCJwr9MskCvdlquGEGzEAxsYaP92n0AJTRAEomfxGAcgSmJcGmYvRLrEg&s=1&enc=AZNSrb1UbMqnUr0gEAKqluIX78JZSjA38OC55Kxg_W3WT-5uHbl0-uhBte6x4bRexw61JO97_hGCTWk4cX5bf825', 0),
(455, '2017-11-04 03:17:21', 33, '57.00', 10, ' Observação do cliente: sem cebola', 1, '68469846546', 'casa verde', NULL, NULL, 1, 'MT', '', 1, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626391%252C%2B-56.0510676%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOniyhAhkoBe0St6TIn4T11M0lpdQUrsgRToFPFFbJ073WnhnkJRD-C9JCJwr9MskCvdlquGEGzEAxsYaP92n0AJTRAEomfxGAcgSmJcGmYvRLrEg&s=1&enc=AZNSrb1UbMqnUr0gEAKqluIX78JZSjA38OC55Kxg_W3WT-5uHbl0-uhBte6x4bRexw61JO97_hGCTWk4cX5bf825', 2),
(456, '2017-11-07 09:56:03', 29, '29.00', 10, '', 1, '54456213548', 'ii sidk aifhl ]', NULL, NULL, 1, 'MT', '', 3, '78050-700', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.58858%252C%2B-56.059254%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATPeJRG7vsVBLGKZbmy_9bjWKLVJK48cLWVoS0ZPnwvSbBhEiaSO6lLZyYvC58SgahdqOYDQzNcClA5t1wXNbs8Iek3qVBb6AJPC-K5v57Nj_EjAog&s=1&enc=AZPhmmAq8QDiLS6XJYigfAL-yuPWHfkSFYVOg6PYCjyo6gND4WFO-6i3cMo8G9G363HfFIm0kF0Hn3uNvUC_8hcz', 1),
(457, '2017-11-09 00:01:04', 36, '55.00', 8, '', 1, '45657645645', 'casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626277%252C%2B-56.0510694%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATPdxvqTmSyYbqnnGyTBzUOP116yCK7VOeEgyX2opWdTMT1_5mKDk9axZ_l4tEflst6BMFZr3WVWIvIH2VbNTDHaTZR7_ebXnO7Shu3JSHK3a1Etxw&s=1&enc=AZPKpVIiU5qeHv8u4PeHQPgh037Y002ExdetM1I9W-Y2TgE8x89lD6qRGD-AqEy8B_XG3QhZux4kfqVQvUhRtTqY', 1),
(460, '2017-11-09 10:39:25', 29, '31.00', 1, 'Levar troco para R$51.00. ', 1, '84564864565', 'dfgb we', NULL, NULL, 1, 'MT', '', 3, '78050-700', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.58858%252C%2B-56.059254%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATPeJRG7vsVBLGKZbmy_9bjWKLVJK48cLWVoS0ZPnwvSbBhEiaSO6lLZyYvC58SgahdqOYDQzNcClA5t1wXNbs8Iek3qVBb6AJPC-K5v57Nj_EjAog&s=1&enc=AZPhmmAq8QDiLS6XJYigfAL-yuPWHfkSFYVOg6PYCjyo6gND4WFO-6i3cMo8G9G363HfFIm0kF0Hn3uNvUC_8hcz', 2),
(461, '2017-11-09 21:13:30', 41, '58.00', 13, ' Observação do cliente: ', 4, '34534534543', 'casa verde', NULL, NULL, 1, 'MT', '', 1, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626317%252C%2B-56.0510567%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATMyzIqbNc8dIl2nCGHkjneQRRt8hJTXhEsgI8FOSX0Lumgh4IcigJqbRyO_5eXZ5PmvKx06L14lAtF9zfwd9j8AD4793C0xaU4Qeg5y2T8MqDs72A&s=1&enc=AZNfGd-PaNWLz6gFsdDDttbXq3_-AXL-cciukazixlB1tMGtU-UfArj-GxQGLj3HQZN7q3WquUYH7jCb9cyopWqL', 1),
(466, '2017-11-11 23:20:11', 41, '58.00', 13, '', 4, '34534534543', 'casa verde', NULL, NULL, 1, 'MT', '', 1, '78056606', NULL, 1),
(467, '2017-11-11 23:25:25', 41, '58.00', 13, '', 4, '34534534543', 'casa verde', NULL, NULL, 1, 'MT', '', 1, '78056606', NULL, 1),
(468, '2017-11-11 23:31:55', 41, '58.00', 13, '', 4, '34534534543', 'casa verde', NULL, NULL, 1, 'MT', '', 1, '78056606', NULL, 1),
(469, '2017-11-11 23:35:13', 41, '58.00', 13, '', 4, '34534534543', 'casa verde', NULL, NULL, 1, 'MT', '', 1, '78056606', NULL, 1),
(470, '2017-11-11 23:42:25', 41, '58.00', 1, '', 4, '34534534543', 'casa verde', NULL, NULL, 1, 'MT', '', 1, '78056606', NULL, 1),
(471, '2017-11-11 23:46:34', 41, '50.00', 13, '', 4, '34534534543', 'casa verde', NULL, NULL, 1, 'MT', '', 1, '78056606', NULL, 1),
(472, '2017-11-11 23:57:53', 41, '58.00', 13, '', 4, '34534534543', 'casa verde', NULL, NULL, 1, 'MT', '', 1, '78056606', NULL, 1),
(473, '2017-11-12 00:00:32', 41, '58.00', 13, '', 4, '34534534543', 'casa verde', NULL, NULL, 1, 'MT', '', 1, '78056606', NULL, 1),
(474, '2017-11-12 00:02:17', 41, '58.00', 13, ' Observação do cliente: nada', 4, '65992217489', 'casa verde', NULL, NULL, 1, 'MT', '', 3, '78500000', NULL, 1),
(475, '2017-11-12 00:03:29', 41, '58.00', 13, ' Observação do cliente: nada', 4, '65992217489', 'casa verde', NULL, NULL, 1, 'MT', '', 3, '78500000', NULL, 1),
(476, '2017-11-12 00:05:38', 41, '58.00', 13, ' Observação do cliente: nada', 4, '65992217489', 'casa verde', NULL, NULL, 1, 'MT', '', 3, '78500000', 'www.bing.com.br', 1),
(477, '2017-11-12 00:06:10', 41, '58.00', 13, '', 4, '65992217489', 'casa verde', NULL, NULL, 1, 'MT', '', 3, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626317%252C%2B-56.0510567%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATMyzIqbNc8dIl2nCGHkjneQRRt8hJTXhEsgI8FOSX0Lumgh4IcigJqbRyO_5eXZ5PmvKx06L14lAtF9zfwd9j8AD4793C0xaU4Qeg5y2T8MqDs72A&s=1&enc=AZNfGd-PaNWLz6gFsdDDttbXq3_-AXL-cciukazixlB1tMGtU-UfArj-GxQGLj3HQZN7q3WquUYH7jCb9cyopWqL', 1),
(478, '2017-12-21 14:12:39', 41, '58.00', 13, ' Observação do cliente: ', 4, '77788909789', 'Ying iso', NULL, NULL, 1, 'MT', '', 3, '78000000', '', 1),
(479, '2017-11-17 00:21:18', 36, '40.00', 1, 'Levar troco para R$100.  Observação do cliente: ', 1, '34564564564', 'casa verde', NULL, NULL, 1, 'MT', '', 4, '78600000', 'www.google.com.br', 0),
(480, '2017-11-17 01:04:11', 43, '50.00', 8, ' Observação do cliente: nada', 1, '32453543534', 'casa amarela', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 1),
(481, '2017-11-17 01:18:53', 43, '50.00', 8, '', 1, '32453543534', 'casa amarela', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 1),
(482, '2017-11-17 01:21:24', 43, '45.00', 8, ' Observação do cliente: ', 1, '75675675675', 'fgfg', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 1),
(483, '2017-11-17 16:33:49', 44, '64.00', 8, ' Observação do cliente: ', 1, '3845858484', 'Casa.mmarela', NULL, NULL, 1, 'MT', '', 4, '78050-923', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.573003%252C%2B-56.072965%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOOkjdBIuOJKjyv78n6GmBs1LCXuj4nKbzBEmorM262LFukj0pnKi1RFsXhknaiN6BE4ajGgiFfPALfvaTUxK9S2obWWtEybK8ZOkE0Z-aDjwj3Bg&s=1&enc=AZPMtfiqMVL9OKUwvT6yvmec1xyuQwm_nO0Z5_V4LTwMLLcCCBUl_jNzItRYAILUUghUS3_Rm8sdWMIhGMxJu6wH', 1),
(484, '2017-11-17 17:16:50', 44, '50.00', 1, ' Observação do cliente: ', 1, '5554554774', 'Casa.mmarela', NULL, NULL, 1, 'MT', '', 4, '78050-923', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.573003%252C%2B-56.072965%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOOkjdBIuOJKjyv78n6GmBs1LCXuj4nKbzBEmorM262LFukj0pnKi1RFsXhknaiN6BE4ajGgiFfPALfvaTUxK9S2obWWtEybK8ZOkE0Z-aDjwj3Bg&s=1&enc=AZPMtfiqMVL9OKUwvT6yvmec1xyuQwm_nO0Z5_V4LTwMLLcCCBUl_jNzItRYAILUUghUS3_Rm8sdWMIhGMxJu6wH', 1),
(485, '2017-11-17 23:09:30', 36, '40.00', 1, 'Levar troco para R$100.  Observação do cliente: ', 1, '34564564564', 'fghfgh', NULL, NULL, 1, 'MT', '', 4, '78600000', 'www.google.com.br', 1),
(486, '2017-11-17 23:18:19', 43, '60.00', 8, ' Observação do cliente: sem cebola', 1, '(75)67567-5', 'casa amarela na av brasil', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 2),
(487, '2017-11-21 03:25:27', 43, '60.00', 1, 'Levar troco para R$100. ', 1, '123456789', 'casa amarela na av brasil', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 1),
(488, '2017-11-21 03:31:03', 43, '60.00', 8, 'Levar troco para R$100. ', 1, '123456789', 'casa amarela na av brasil', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 1),
(489, '2017-11-21 04:02:49', 43, '60.00', 8, 'Levar troco para R$100. ', 1, '123456789', 'casa amarela na av brasil', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 1),
(490, '2017-11-21 04:10:38', 43, '60.00', 1, 'Levar troco para R$100.  Observação do cliente: nada nao', 1, '65992217482', 'casa amarela na av brasil', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 1),
(491, '2017-11-21 04:17:10', 43, '60.00', 11, 'Levar troco para R$100. ', 1, '66992217482', 'casa amarela na av brasil', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 1),
(492, '2017-11-21 22:58:51', 43, '91.00', 1, 'Levar troco para R$100.  Observação do cliente: nada n', 1, '(66)99221-7', 'casa amarela na av brasil', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 1),
(493, '2017-11-21 23:21:49', 43, '100.00', 1, ' Observação do cliente: ', 1, '66992217482', 'casa amarela na av brasil', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 1),
(494, '2017-11-21 23:24:00', 43, '50.00', 8, ' Observação do cliente: ', 1, '(66)99221-7', 'casa amarela na av brasil', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 1),
(495, '2017-11-21 23:38:23', 43, '54.00', 8, ' Observação do cliente: ', 1, '66992217482', 'casa amarela na av brasil', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 1),
(496, '2017-11-21 23:39:53', 43, '54.00', 8, 'Levar troco para R$100. ', 1, '66992217482', 'casa amarela na av brasil', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 1),
(497, '2017-11-21 23:42:37', 43, '45.00', 8, ' Observação do cliente: ', 1, '66992217482', 'casa amarela na av brasil', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 1),
(498, '2017-11-22 00:22:36', 43, '55.00', 8, ' Observação do cliente: ', 1, '66992217482', 'casa verde na av brasil cpa2', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 1),
(499, '2017-11-22 00:31:43', 43, '55.00', 8, 'Levar troco para R$100. ', 1, '66992217489', 'casa verde na av brasil cpa2', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 1),
(500, '2017-11-28 00:17:50', 45, '42.00', 1, 'Levar troco para R$80.  Observação do cliente: agjka aiaguihia ajguoad', 1, '54854852365', 'rua jga lua sodyd dpfuwbns apasha', NULL, NULL, 1, 'MT', '', 3, '78000000', '', 0),
(501, '2017-11-29 19:40:07', 44, '116.50', 8, ' Observação do cliente: ', 1, '5554554774', 'Casa.mmarela', NULL, NULL, 1, 'MT', '', 4, '78050-923', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.573003%252C%2B-56.072965%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOOkjdBIuOJKjyv78n6GmBs1LCXuj4nKbzBEmorM262LFukj0pnKi1RFsXhknaiN6BE4ajGgiFfPALfvaTUxK9S2obWWtEybK8ZOkE0Z-aDjwj3Bg&s=1&enc=AZPMtfiqMVL9OKUwvT6yvmec1xyuQwm_nO0Z5_V4LTwMLLcCCBUl_jNzItRYAILUUghUS3_Rm8sdWMIhGMxJu6wH', 1),
(502, '2017-11-29 23:10:01', 43, '113.50', 8, ' Observação do cliente: ', 1, '66992217489', 'casa verde na av brasil cpa2', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 0),
(503, '2017-11-29 23:34:57', 43, '113.50', 8, 'Levar troco para R$100. ', 1, '66992217489', 'casa verde na av brasil cpa2', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 2),
(504, '2017-12-02 22:19:10', 38, '70.00', 3, '', 2, '65984215292', 'Residencial São Carlos, bloco 46, APT 403... Preciso que me ligue quando estiver no portão, pois não tem como eu ver. 65984215292', NULL, NULL, 1, 'MT', '', 95, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.584165%252C%2B-56.049257%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOJB03PUMWYXwM61hdXQ5IIsUrxtg4cdA5hdMEAU97ITjyOKV1hHrl_ixWmSOC_1RIFpmhgsRZEwlJsJgv9LiWnLs6nvqCFMbEL3JwFOEHsgSGZFg&s=1&enc=AZNFddVlYl4xgtiB6af69Ey0gaTP_Swho1I5zOustFjPskVavIZpEmXPy3ST_HYcWFvs-3ns8tndoEJIOxU-fsUi', 2),
(505, '2017-12-08 21:47:03', 46, '45.90', 1, 'Levar troco para R$50. ', 2, '36491548', 'Rua 66, quadra 94, número 11, CPA 4, 3° etapa', NULL, NULL, 1, 'MT', '', 3, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.562526%252C%2B-56.032189%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNtULgLNuV-JvlcCnwcDXnGcAFqiHdttJODAdI-g8kXHsD4SSPooMxU3hzxKKn3PlTMIZP_D39mzeEnkFSQNZncR3Yuf9g5rxJOwfhnR1J2fBgfJw&s=1&enc=AZOOqsmERlCOxdT-XyP9Jg6PtBBqUMlFbkmmouW48zX25UUVwGxwVxCTY2dTo9H2FAy3BMw07iIx0LfwS27_GrC7', 2),
(506, '2017-12-08 23:16:22', 47, '57.00', 3, '', 2, '992288285', 'Rua 55, quadra 30, casa 06, cpa 3, setor 3', NULL, NULL, 1, 'MT', '', 141, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.577965%252C%2B-56.040278%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATMWqb9AVAsOZ7qjeVN4ioDFLahR03EAfB98JkzMDpEDa4bmHfydSPHWhm5PH-kmc6GbgdgldnZhuTU43BJ2XakaF5BbWK7EH4_L10t8GTfxvScIng&s=1&enc=AZNAYoQ-2AfWp9XR07K_UBe_2Ok4VjkI1HZGaYY8goBCWD94gXeMoMBTl5iNHfdIcdmKmOFPZq7FWcIR_EPwBhRz', 2),
(507, '2017-12-11 03:48:27', 43, '38.00', 8, ' Observação do cliente: ', 1, '66992217489', 'casa verde na av brasil cpa2', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 1),
(508, '2017-12-11 03:51:30', 43, '38.00', 8, 'Levar troco para R$100. ', 1, '66992217489', 'casa verde na av brasil cpa2', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 1),
(509, '2017-12-11 03:56:05', 43, '38.00', 8, 'Levar troco para R$100. ', 1, '66992217489', 'casa verde na av brasil cpa2', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 1),
(510, '2017-12-11 03:58:18', 43, '38.00', 8, 'Levar troco para R$100. ', 1, '66992217489', 'casa verde na av brasil cpa2', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 1),
(511, '2017-12-11 04:12:37', 44, '116.50', 8, '', 1, '5554554774', 'Casa.mmarela', NULL, NULL, 1, 'MT', '', 4, '78050-923', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.573003%252C%2B-56.072965%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOOkjdBIuOJKjyv78n6GmBs1LCXuj4nKbzBEmorM262LFukj0pnKi1RFsXhknaiN6BE4ajGgiFfPALfvaTUxK9S2obWWtEybK8ZOkE0Z-aDjwj3Bg&s=1&enc=AZPMtfiqMVL9OKUwvT6yvmec1xyuQwm_nO0Z5_V4LTwMLLcCCBUl_jNzItRYAILUUghUS3_Rm8sdWMIhGMxJu6wH', 1),
(512, '2017-12-11 04:13:37', 44, '116.50', 8, '', 1, '5554554774', 'Casa.mmarela', NULL, NULL, 1, 'MT', '', 4, '78050-923', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.573003%252C%2B-56.072965%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOOkjdBIuOJKjyv78n6GmBs1LCXuj4nKbzBEmorM262LFukj0pnKi1RFsXhknaiN6BE4ajGgiFfPALfvaTUxK9S2obWWtEybK8ZOkE0Z-aDjwj3Bg&s=1&enc=AZPMtfiqMVL9OKUwvT6yvmec1xyuQwm_nO0Z5_V4LTwMLLcCCBUl_jNzItRYAILUUghUS3_Rm8sdWMIhGMxJu6wH', 1),
(513, '2017-12-11 04:32:21', 44, '116.50', 8, '', 1, '5554554774', 'Casa.mmarela', NULL, NULL, 1, 'MT', '', 4, '78050-923', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.573003%252C%2B-56.072965%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOOkjdBIuOJKjyv78n6GmBs1LCXuj4nKbzBEmorM262LFukj0pnKi1RFsXhknaiN6BE4ajGgiFfPALfvaTUxK9S2obWWtEybK8ZOkE0Z-aDjwj3Bg&s=1&enc=AZPMtfiqMVL9OKUwvT6yvmec1xyuQwm_nO0Z5_V4LTwMLLcCCBUl_jNzItRYAILUUghUS3_Rm8sdWMIhGMxJu6wH', 1);
INSERT INTO `pedido` (`codigo_pedido`, `data_hora_pedido`, `cliente_pizzaria_pedido`, `valor_total_pedido`, `forma_pagamento_pedido`, `observacao_pedido`, `pizzaria_pedido`, `telefone_pedido`, `endereco_pedido`, `numero_endereco_pedido`, `complemento_endereco_pedido`, `cidade_pedido`, `uf_pedido`, `referencia_endereco_pedido`, `bairro_pedido`, `cep_pedido`, `mapa_url_pedido`, `status_pedido`) VALUES
(514, '2017-12-11 04:36:13', 43, '38.00', 8, 'Levar troco para R$100. ', 1, '66992217489', 'casa verde na av brasil cpa2', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 1),
(515, '2017-12-11 04:37:14', 43, '38.00', 8, 'Levar troco para R$100. ', 1, '66992217489', 'casa verde na av brasil cpa2', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 1),
(516, '2017-12-11 04:41:07', 43, '38.00', 8, 'Levar troco para R$100. ', 1, '66992217489', 'casa verde na av brasil cpa2', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 1),
(517, '2017-12-11 12:00:15', 44, '116.50', 8, '', 1, '5554554774', 'Casa.mmarela', NULL, NULL, 1, 'MT', '', 4, '78050-923', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.573003%252C%2B-56.072965%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOOkjdBIuOJKjyv78n6GmBs1LCXuj4nKbzBEmorM262LFukj0pnKi1RFsXhknaiN6BE4ajGgiFfPALfvaTUxK9S2obWWtEybK8ZOkE0Z-aDjwj3Bg&s=1&enc=AZPMtfiqMVL9OKUwvT6yvmec1xyuQwm_nO0Z5_V4LTwMLLcCCBUl_jNzItRYAILUUghUS3_Rm8sdWMIhGMxJu6wH', 0),
(518, '2017-12-12 12:48:42', 44, '116.50', 8, '', 1, '5554554774', 'Casa.mmarela', NULL, NULL, 1, 'MT', '', 4, '78050-923', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.573003%252C%2B-56.072965%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOOkjdBIuOJKjyv78n6GmBs1LCXuj4nKbzBEmorM262LFukj0pnKi1RFsXhknaiN6BE4ajGgiFfPALfvaTUxK9S2obWWtEybK8ZOkE0Z-aDjwj3Bg&s=1&enc=AZPMtfiqMVL9OKUwvT6yvmec1xyuQwm_nO0Z5_V4LTwMLLcCCBUl_jNzItRYAILUUghUS3_Rm8sdWMIhGMxJu6wH', 0),
(519, '2017-12-13 20:12:10', 48, '51.00', 11, ' Observação do cliente: Sem cebola', 1, '65999898645', 'Mti', NULL, NULL, 1, 'MT', '', 3, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.566754%252C%2B-56.075505%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATPMDSIESNtCff6Mufearv0RuPmJ_cddPqhcOUItYAC3RFLM-l2rdLWe9AfIAR84JTUCx68fc7u5N1Srog-BBgvPvdCwJQ_yxoj_KtS4uZH2yY7dew&s=1&enc=AZOzMh3La5vKizBeA-XIL2CcI6lBPdvjppOb2HJ9RE3f82890CRa9uXl4mrCPevt-OjMmRUpWx13DUpxg3gOoITZ', 1),
(520, '2017-12-14 15:37:51', 36, '54.00', 8, '', 1, '67757666675', 'Bhh', NULL, NULL, 1, 'MT', '', 4, '78050-923', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.573064%252C%2B-56.072992%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATM_ulQJh4GPEmuztEE0lmDQjvtrOZiHrAKk7LcB5otGd58fT8CcpGxycpF6CUsB2c_U82Pt4d3hyBYhek6Uo4oPO8hAUKMxQUtfATBPvIHqyan8gQ&s=1&enc=AZPU3dEMniCpckd8fwm0FP8aMw1DuhAL-cRh5N4vZJdchu88jKpSgsIFfoqepzxdf2n6WH80qWNVUlDTsIZz7PC7', 0),
(521, '2017-12-14 18:27:24', 44, '43.00', 8, ' Observação do cliente: ', 1, '5554554774', 'Casa.mmarela', NULL, NULL, 1, 'MT', '', 4, '78050-923', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.573003%252C%2B-56.072965%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOOkjdBIuOJKjyv78n6GmBs1LCXuj4nKbzBEmorM262LFukj0pnKi1RFsXhknaiN6BE4ajGgiFfPALfvaTUxK9S2obWWtEybK8ZOkE0Z-aDjwj3Bg&s=1&enc=AZPMtfiqMVL9OKUwvT6yvmec1xyuQwm_nO0Z5_V4LTwMLLcCCBUl_jNzItRYAILUUghUS3_Rm8sdWMIhGMxJu6wH', 0),
(522, '2017-12-19 09:55:04', 45, '42.00', 1, 'Levar troco para R$100. ', 1, '54854852365', 'rua jga lua sodyd dpfuwbns apasha', NULL, NULL, 1, 'MT', '', 3, '78000000', '', 1),
(523, '2017-12-19 09:56:15', 45, '42.00', 1, 'Levar troco para R$100.  Observação do cliente: coxinha', 1, '54854852365', 'rua jga lua sodyd dpfuwbns apasha', NULL, NULL, 1, 'MT', '', 3, '78000000', '', 1),
(524, '2017-12-19 09:59:16', 45, '42.00', 8, 'Levar troco para R$100. ', 1, '55485575215', 'rua jga lua sodyd dpfuwbns apasha', NULL, NULL, 1, 'MT', '', 3, '78000000', '', 1),
(525, '2017-12-19 10:16:53', 45, '31.00', 1, ' Observação do cliente: ', 1, '55485575215', 'rua jga lua sodyd dpfuwbns apasha', NULL, NULL, 1, 'MT', '', 3, '78000000', '', 0),
(526, '2017-12-21 13:55:55', 45, '31.00', 1, 'Levar troco para R$100. ', 1, '55485575215', 'rua jga lua sodyd dpfuwbns apasha', NULL, NULL, 1, 'MT', '', 3, '78000000', '', 0),
(527, '2017-12-21 23:53:03', 44, '34.00', 8, '', 1, '5554554774', 'Casa.mmarela', NULL, NULL, 1, 'MT', '', 3, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.562534%252C%2B-56.051041%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATMsOiQPZzmKdirASR9lkXEWSqYi5725mxEVsKfY_WYcKczVnfaYrc3qa5FgpJ939sNPVYBdHQACHn4XPz_b6O_xZBSkemi6LLtdBbJkKNYDgbzaRA&s=1&enc=AZO7dBPkom-f9v_xnt7DdnBBt02pUx_S2AU6VAPmvTllTT-DMVQw0FSHlF7sBpnzvhbqxSpeEp3WYVB5Gwyd-qGi', 1),
(528, '2017-12-22 01:25:42', 44, '34.00', 8, ' Observação do cliente: nda', 1, '5554554774', 'Casa.mmarela', NULL, NULL, 1, 'MT', '', 3, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.562534%252C%2B-56.051041%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATMsOiQPZzmKdirASR9lkXEWSqYi5725mxEVsKfY_WYcKczVnfaYrc3qa5FgpJ939sNPVYBdHQACHn4XPz_b6O_xZBSkemi6LLtdBbJkKNYDgbzaRA&s=1&enc=AZO7dBPkom-f9v_xnt7DdnBBt02pUx_S2AU6VAPmvTllTT-DMVQw0FSHlF7sBpnzvhbqxSpeEp3WYVB5Gwyd-qGi', 1),
(529, '2017-12-22 01:29:57', 44, '34.00', 8, '', 1, '5554554774', 'Casa.mmarela', NULL, NULL, 1, 'MT', '', 3, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.562534%252C%2B-56.051041%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATMsOiQPZzmKdirASR9lkXEWSqYi5725mxEVsKfY_WYcKczVnfaYrc3qa5FgpJ939sNPVYBdHQACHn4XPz_b6O_xZBSkemi6LLtdBbJkKNYDgbzaRA&s=1&enc=AZO7dBPkom-f9v_xnt7DdnBBt02pUx_S2AU6VAPmvTllTT-DMVQw0FSHlF7sBpnzvhbqxSpeEp3WYVB5Gwyd-qGi', 1),
(530, '2017-12-22 01:32:42', 44, '34.00', 8, '', 1, '5554554774', 'Casa.mmarela', NULL, NULL, 1, 'MT', '', 3, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.562534%252C%2B-56.051041%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATMsOiQPZzmKdirASR9lkXEWSqYi5725mxEVsKfY_WYcKczVnfaYrc3qa5FgpJ939sNPVYBdHQACHn4XPz_b6O_xZBSkemi6LLtdBbJkKNYDgbzaRA&s=1&enc=AZO7dBPkom-f9v_xnt7DdnBBt02pUx_S2AU6VAPmvTllTT-DMVQw0FSHlF7sBpnzvhbqxSpeEp3WYVB5Gwyd-qGi', 1),
(531, '2017-12-22 01:34:53', 44, '34.00', 8, '', 1, '5554554774', 'Casa.mmarela', NULL, NULL, 1, 'MT', '', 3, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.562534%252C%2B-56.051041%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATMsOiQPZzmKdirASR9lkXEWSqYi5725mxEVsKfY_WYcKczVnfaYrc3qa5FgpJ939sNPVYBdHQACHn4XPz_b6O_xZBSkemi6LLtdBbJkKNYDgbzaRA&s=1&enc=AZO7dBPkom-f9v_xnt7DdnBBt02pUx_S2AU6VAPmvTllTT-DMVQw0FSHlF7sBpnzvhbqxSpeEp3WYVB5Gwyd-qGi', 1),
(532, '2017-12-22 01:42:41', 44, '34.00', 8, '', 1, '5554554774', 'Casa.mmarela', NULL, NULL, 1, 'MT', '', 3, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.562534%252C%2B-56.051041%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATMsOiQPZzmKdirASR9lkXEWSqYi5725mxEVsKfY_WYcKczVnfaYrc3qa5FgpJ939sNPVYBdHQACHn4XPz_b6O_xZBSkemi6LLtdBbJkKNYDgbzaRA&s=1&enc=AZO7dBPkom-f9v_xnt7DdnBBt02pUx_S2AU6VAPmvTllTT-DMVQw0FSHlF7sBpnzvhbqxSpeEp3WYVB5Gwyd-qGi', 1),
(533, '2017-12-22 01:44:39', 44, '34.00', 8, '', 1, '5554554774', 'verdeeeeeeee', NULL, NULL, 1, 'MT', '', 3, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.562534%252C%2B-56.051041%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATMsOiQPZzmKdirASR9lkXEWSqYi5725mxEVsKfY_WYcKczVnfaYrc3qa5FgpJ939sNPVYBdHQACHn4XPz_b6O_xZBSkemi6LLtdBbJkKNYDgbzaRA&s=1&enc=AZO7dBPkom-f9v_xnt7DdnBBt02pUx_S2AU6VAPmvTllTT-DMVQw0FSHlF7sBpnzvhbqxSpeEp3WYVB5Gwyd-qGi', 1),
(534, '2017-12-22 01:48:43', 44, '34.00', 8, '', 1, '5554554774', 'zero', NULL, NULL, 1, 'MT', '', 3, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.562534%252C%2B-56.051041%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATMsOiQPZzmKdirASR9lkXEWSqYi5725mxEVsKfY_WYcKczVnfaYrc3qa5FgpJ939sNPVYBdHQACHn4XPz_b6O_xZBSkemi6LLtdBbJkKNYDgbzaRA&s=1&enc=AZO7dBPkom-f9v_xnt7DdnBBt02pUx_S2AU6VAPmvTllTT-DMVQw0FSHlF7sBpnzvhbqxSpeEp3WYVB5Gwyd-qGi', 1),
(535, '2017-12-22 01:50:57', 44, '34.00', 8, '', 1, '5554554774', 'roxaaaaaaaaaaaaa', NULL, NULL, 1, 'MT', '', 3, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.562534%252C%2B-56.051041%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATMsOiQPZzmKdirASR9lkXEWSqYi5725mxEVsKfY_WYcKczVnfaYrc3qa5FgpJ939sNPVYBdHQACHn4XPz_b6O_xZBSkemi6LLtdBbJkKNYDgbzaRA&s=1&enc=AZO7dBPkom-f9v_xnt7DdnBBt02pUx_S2AU6VAPmvTllTT-DMVQw0FSHlF7sBpnzvhbqxSpeEp3WYVB5Gwyd-qGi', 1),
(536, '2017-12-22 01:55:44', 44, '34.00', 8, '', 1, '5554554774', 'casa grande', NULL, NULL, 1, 'MT', '', 3, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 1),
(537, '2017-12-22 02:02:55', 44, '34.00', 8, ' Observação do cliente: nada n', 1, '5554554774', 'alameda sempre verde', NULL, NULL, 1, 'MT', '', 3, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 1),
(538, '2017-12-22 02:18:03', 44, '43.00', 8, '', 1, '9999888887', 'semp', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 1),
(539, '2017-12-22 02:31:29', 44, '43.00', 8, '', 1, '9999888887', 'semp', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 1),
(540, '2017-12-22 02:39:48', 44, '43.00', 1, 'Levar troco para R$50.  Observação do cliente: teste', 1, '66655533322', 'semp', NULL, NULL, 1, 'MT', '', 5, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 1),
(541, '2017-12-22 02:45:29', 44, '43.00', 8, 'Levar troco para R$50. ', 1, '11122233345', 'anaaaa', NULL, NULL, 1, 'MT', '', 81, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 1),
(542, '2017-12-22 02:54:50', 44, '43.00', 8, ' Observação do cliente: poxaaa', 1, '11122233345', 'tecado', NULL, NULL, 1, 'MT', '', 5, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 1),
(543, '2017-12-22 02:56:24', 44, '43.00', 11, '', 1, '11122233345', 'tecado', NULL, NULL, 1, 'MT', '', 5, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 1),
(544, '2017-12-22 02:58:10', 44, '34.00', 8, ' Observação do cliente: nada man', 1, '11122233345', 'centro da av brasil', NULL, NULL, 1, 'MT', '', 3, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 1),
(545, '2017-12-22 02:59:52', 44, '34.00', 1, 'Levar troco para R$100.  Observação do cliente: sem ceola poxa', 1, '11122233345', 'centro da av brasil', NULL, NULL, 1, 'MT', '', 3, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 1),
(546, '2017-12-22 03:02:29', 44, '43.00', 11, '', 1, '11122233345', 'na casa da Anna', NULL, NULL, 1, 'MT', '', 8, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 1),
(547, '2017-12-22 03:07:19', 44, '59.50', 8, ' Observação do cliente: ', 1, '11122233345', 'na casa da Anna', NULL, NULL, 1, 'MT', '', 8, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(548, '2017-12-22 03:13:34', 44, '59.00', 8, ' Observação do cliente: ', 1, '11122233345', 'casa vewrde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(549, '2017-12-23 22:53:48', 46, '50.00', 3, '', 2, '65981150626', 'Rua 55, quadra 30, casa 06, CPA 3, setor 3', NULL, NULL, 1, 'MT', '', 141, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.573674%252C%2B-56.043852%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOZuQRDR_7g8lke-xbw9X7A85fqDsPxCQg1gOBXJ1s9PjD0lk2CKEPpRs2aHtCTHWg6uwpUNKArMB1MN-2wurzoiy6blDTen3SFMETePz2EPxzVeA&s=1&enc=AZMbnbc59Vz2BKxEBKhpFpkam1drr4GFDKchzjGOCHfaYv4Y0TG2uPQbp5kGn10Sac6n825ub3wucsw4yPXqqhRl', 2),
(550, '2017-12-24 22:30:56', 49, '110.00', 3, ' Observação do cliente: Não quero cebola', 2, '992347058', 'Passa cartão?', NULL, NULL, 1, 'MT', '', 98, '78000000', '', 1),
(551, '2017-12-28 23:46:56', 38, '50.90', 3, ' Observação do cliente: Residencial São Carlos, bloco 46, APT 403... Preciso que me ligue quando estiver no portão, pois não tem como eu ver. 65984215292', 2, '984215292', 'Residencial São Carlos, bloco 46, apartamento 403. É um bloco de esquina, de muros marrom. Assim que chegar fazer me ligar, 984215292 para abrir o portão.', NULL, NULL, 1, 'MT', '', 95, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.584244%252C%2B-56.049464%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATMivqBAEj3GsCDDt9cBlQ9k6hBgUIDi1cnzqXnGDs9-lAdRojuKfGHk3c0Xop_u_5o6bS2Cn5YPmVD3hHf7njbnsKZbzNWeO1DDuBUCpjJmz0NtFw&s=1&enc=AZNS7oOe-w92sXKi61uNx-01TCuBuF2qJDOJJj2BNnse8MTBxMKe73qAIyDavAnVnyzfrZhjQvXgVSonfKR6bHgR', 2),
(552, '2018-01-03 18:20:39', 44, '54.00', 8, ' Observação do cliente: ', 1, '11122233345', 'Casa VERDE', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(553, '2018-01-03 18:24:42', 44, '54.00', 8, '', 1, '11122233345', 'Centro', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(554, '2018-01-03 20:51:35', 45, '31.00', 1, ' Observação do cliente: ', 1, '55485575215', 'jaga aufcpakhyanai hfioajdiof hiaos dhaisd aipsd', NULL, NULL, 1, 'MT', '', 3, '78000000', '', 1),
(555, '2018-01-04 13:00:30', 44, '54.00', 8, '', 1, '11122233345', 'Ttttttttghggg', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(556, '2018-01-04 13:01:08', 44, '54.00', 8, '', 1, '11122233345', 'Ttttttttghggg', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(557, '2018-01-04 13:01:33', 44, '54.00', 8, '', 1, '11122233345', 'Ttttttttghggg', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(558, '2018-01-04 13:03:26', 44, '54.00', 8, '', 1, '11122233345', 'Ttttttttghggg', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(559, '2018-01-04 13:03:31', 44, '54.00', 8, '', 1, '11122233345', 'Ttttttttghggg', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(560, '2018-01-04 13:04:00', 44, '54.00', 8, '', 1, '11122233345', 'Ttttttttghggg', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(561, '2018-01-04 13:08:12', 44, '54.00', 8, '', 1, '11122233345', 'Ttttttttghggg', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(562, '2018-01-04 13:10:09', 44, '54.00', 8, '', 1, '11122233345', 'Ttttttttghggg', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(563, '2018-01-05 13:12:56', 44, '47.00', 8, ' Observação do cliente: ', 1, '11122233345', 'Ttttttttghggg', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(564, '2018-01-05 17:32:33', 44, '127.50', 8, ' Observação do cliente: ', 1, '11122233345', 'Ttttttttghggg', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(565, '2018-01-05 17:44:26', 44, '145.00', 1, ' Observação do cliente: ', 1, '11122233345', 'Ttttttttghggg', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(566, '2018-01-05 22:54:14', 46, '140.00', 1, ' Observação do cliente: Aniversariante ganha brinde? rs', 2, '981150626', 'Rua 66, quadra 94, número 11, CPA 4, 3 etapa', NULL, NULL, 1, 'MT', '', 3, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.560693%252C%2B-56.031792%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATMSayJ7s_qyDKWn7ZnQDeZv7DiWHwdzfJg4UCmgeBvW5YcLT_Ilx_tX9Mkh886T8-VJ9YjK4QNwWaGFjex2utnJA9U6oSwfWavl1tf8H9zm_k79eQ&s=1&enc=AZP3JbsLb7f4HuQl_H3yHPWIAnRHE23XJw75qkWIeLp438l7mpHjn_9us1cUXMIIRfr0dJz3u83DAOTHs3mnRfrR', 2),
(567, '2018-01-08 20:41:08', 44, '145.00', 8, '', 1, '11122233345', 'Ttttttttghggg', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 1),
(568, '2018-01-09 18:59:29', 44, '145.00', 8, '', 1, '11122233345', 'Ttttttttghggg', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(569, '2018-01-11 22:30:30', 50, '45.00', 1, ' Observação do cliente: ', 1, '992537026', 'Rua M, Quadra 67, casa 01', NULL, NULL, 1, 'MT', '', 152, '78000000', '', 2),
(570, '2018-01-11 22:36:25', 50, '45.00', 1, 'Levar troco para R$100. ', 1, '992537026', 'Rua M, Quadra 67, casa 01', NULL, NULL, 1, 'MT', '', 152, '78000000', '', 0),
(571, '2018-01-11 22:43:22', 50, '56.50', 1, ' Observação do cliente: ', 1, '992537026', 'Rua M, Quadra 67, casa 01', NULL, NULL, 1, 'MT', '', 152, '78000000', '', 2),
(572, '2018-01-11 23:00:50', 44, '54.00', 1, ' Observação do cliente: ', 1, '11122233345', 'Ttttttttghggg', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(573, '2018-01-11 23:03:49', 44, '54.00', 8, ' Observação do cliente: ', 1, '11122233345', 'Ttttttttghggg', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(574, '2018-01-11 23:04:37', 50, '42.00', 1, 'Levar troco para R$Troco para 100.  Observação do cliente: ', 1, '992537026', 'Rua M, Quadra 67, casa 01', NULL, NULL, 1, 'MT', '', 152, '78000000', '', 0),
(575, '2018-01-13 00:00:57', 45, '86.00', 1, ' Observação do cliente: ', 1, '55485575215', 'jaga aufcpakhyanai hfioajdiof hiaos dhaisd aipsd', NULL, NULL, 1, 'MT', '', 3, '78000000', '', 1),
(576, '2018-01-15 13:06:21', 44, '50.00', 8, ' Observação do cliente: ', 1, '11122233345', 'Ttttttttghggg', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(577, '2018-01-17 15:25:47', 44, '50.00', 1, ' Observação do cliente: ', 1, '92217482', 'Ttttttttghggg', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(578, '2018-01-17 15:27:55', 44, '50.00', 8, '', 1, '92217482', 'Ttttttttghggg', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(579, '2018-01-18 12:44:04', 51, '45.00', 8, ' Observação do cliente: ', 1, '65992217482', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5625627%252C%2B-56.0510588%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOYRESBdVcKmaNeabSkqjVQC0kHVgOBBrfCSVnRoiJgSwidQTU1dDRxtViji-ThQsvbX9PgfA3RAZyvL97PBC4tbVnDCnrRBoLzpljsbvQWehOdmA&s=1&enc=AZM-XJ7LOR0qbckZcE3NIsJHBmOhkWSJoerNmKjDu1EvbSS4bjr3G_iyZCe8sjBQWZtgrBuJA2QSK1l8KJuiIzjZ', 2),
(580, '2018-01-18 16:34:53', 51, '45.00', 8, '', 1, '65992217482', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5625627%252C%2B-56.0510588%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOYRESBdVcKmaNeabSkqjVQC0kHVgOBBrfCSVnRoiJgSwidQTU1dDRxtViji-ThQsvbX9PgfA3RAZyvL97PBC4tbVnDCnrRBoLzpljsbvQWehOdmA&s=1&enc=AZM-XJ7LOR0qbckZcE3NIsJHBmOhkWSJoerNmKjDu1EvbSS4bjr3G_iyZCe8sjBQWZtgrBuJA2QSK1l8KJuiIzjZ', 0),
(581, '2018-01-19 15:49:25', 44, '45.00', 8, ' Observação do cliente: Sem', 1, '6592217482', 'Cebola', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 1),
(582, '2018-01-19 20:00:14', 52, '31.00', 8, ' Observação do cliente: ', 1, '737387383', 'Já foram é aquele auakd flabudmd', NULL, NULL, 1, 'MT', '', 3, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.582876%252C%2B-56.04322%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNdQx-uF-Pu15qEnTe0Rf1NdUf_rL2-BA_ggdAeBAS7pbq_3D0i0_42FoxY6gFJEYPyt6RxYN-g4IgJAoR7aG1Tx8RrOV14a1fG-HM9U8SRVRcLyw&s=1&enc=AZMfLfdX_7l8Zkd5ls39xSpbYxc35ecoN6GgNuoBMCW4WwXlAZUik1AvuTl-YHa7hz1qOC-xqJbSA09s3HYZiNUy', 1),
(583, '2018-01-24 12:12:47', 53, '50.00', 13, ' Observação do cliente: ', 4, '06698765432', 'Cada verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(584, '2018-01-24 13:18:45', 42, '40.00', 13, ' Observação do cliente: ', 4, '65981150262', 'Nskskns sjsjsksn disksm', NULL, NULL, 1, 'MT', '', 3, '78000000', '', 2),
(585, '2018-01-24 13:34:22', 53, '73.00', 13, ' Observação do cliente: ', 4, '06698765432', 'Cada verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(586, '2018-01-24 14:32:37', 53, '50.00', 13, ' Observação do cliente: ', 4, '06698765432', 'Cada verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(587, '2018-01-24 15:14:28', 53, '56.00', 13, ' Observação do cliente: ', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(588, '2018-01-24 15:24:15', 53, '56.00', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(589, '2018-01-24 15:27:17', 53, '56.00', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(590, '2018-01-24 15:38:49', 51, '45.00', 8, '', 1, '65992217482', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5625627%252C%2B-56.0510588%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOYRESBdVcKmaNeabSkqjVQC0kHVgOBBrfCSVnRoiJgSwidQTU1dDRxtViji-ThQsvbX9PgfA3RAZyvL97PBC4tbVnDCnrRBoLzpljsbvQWehOdmA&s=1&enc=AZM-XJ7LOR0qbckZcE3NIsJHBmOhkWSJoerNmKjDu1EvbSS4bjr3G_iyZCe8sjBQWZtgrBuJA2QSK1l8KJuiIzjZ', 0),
(591, '2018-01-24 15:55:48', 53, '56.00', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(592, '2018-01-24 16:01:09', 51, '45.00', 8, '', 1, '65992217482', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5625627%252C%2B-56.0510588%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOYRESBdVcKmaNeabSkqjVQC0kHVgOBBrfCSVnRoiJgSwidQTU1dDRxtViji-ThQsvbX9PgfA3RAZyvL97PBC4tbVnDCnrRBoLzpljsbvQWehOdmA&s=1&enc=AZM-XJ7LOR0qbckZcE3NIsJHBmOhkWSJoerNmKjDu1EvbSS4bjr3G_iyZCe8sjBQWZtgrBuJA2QSK1l8KJuiIzjZ', 0),
(593, '2018-01-26 04:19:37', 42, '67.00', 13, ' Observação do cliente: não quero cebola', 4, '65981150262', 'Nskskns sjsjsksn disksm', NULL, NULL, 1, 'MT', '', 3, '78000000', '', 1),
(594, '2018-01-27 00:13:00', 42, '70.33', 1, ' Observação do cliente: não quero cebola', 4, '65981150262', 'Nskskns sjsjsksn disksm', NULL, NULL, 1, 'MT', '', 3, '78000000', '', 2),
(595, '2018-01-27 00:19:15', 42, '65.33', 13, '', 4, '65981150626', 'avenida coronel escolastico, 123. é o restaurante do alex.', NULL, NULL, 1, 'MT', '', 85, '78000000', '', 1),
(596, '2018-01-27 22:00:19', 54, '50.00', 1, '', 2, '65981182065', 'Rua gravateiro, 11, quadra 94, CPA 4, 3 etapa', NULL, NULL, 1, 'MT', '', 3, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.562437%252C%2B-56.031772%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNd4cSYesygacnOyf9GlMrVxEgGLPZvhXomeqscZmjuWTNwq1YDh5pf4Enk05ocfCXrHyqBZNxGNUC4JDJmgP_XP3RLXX44J84T2ZHkHX52lvZCvV_r&s=1&enc=AZNWi855l1yp7nkAPEHsBr2iKyBuA8gZ6EvLRtrWiD5L1TT78W4x3ELqoD-4UAR4LSHZGzY22-gVB3TPZBs1RhxO', 2),
(597, '2018-01-31 11:50:51', 42, '50.00', 13, ' Observação do cliente: ', 4, '65981150626', 'Rua gravateiro, 11, CPA 4, 3 etapa', NULL, NULL, 1, 'MT', '', 89, '78000000', '', 1),
(598, '2018-01-31 12:21:32', 52, '24.00', 8, ' Observação do cliente: ', 1, '981150626', 'Rua gravateiro, 11, CPA 4, 3 etapa', NULL, NULL, 1, 'MT', '', 3, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.582876%252C%2B-56.04322%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNdQx-uF-Pu15qEnTe0Rf1NdUf_rL2-BA_ggdAeBAS7pbq_3D0i0_42FoxY6gFJEYPyt6RxYN-g4IgJAoR7aG1Tx8RrOV14a1fG-HM9U8SRVRcLyw&s=1&enc=AZMfLfdX_7l8Zkd5ls39xSpbYxc35ecoN6GgNuoBMCW4WwXlAZUik1AvuTl-YHa7hz1qOC-xqJbSA09s3HYZiNUy', 2),
(599, '2018-01-31 12:24:09', 42, '50.00', 13, '', 4, '65981150626', 'Rua gravateiro, 11, CPA 4, 3 etapa', NULL, NULL, 1, 'MT', '', 89, '78000000', '', 1),
(600, '2017-01-31 22:51:18', 55, '55.00', 1, 'Levar troco para R$troco para 100 reais.  Observação do cliente: n quero cebola obg', 2, '65992217488', 'Esse pedido é apenas um teste. DESCONSIDERAR', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5625627%252C%2B-56.0510588%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOYRESBdVcKmaNeabSkqjVQC0kHVgOBBrfCSVnRoiJgSwidQTU1dDRxtViji-ThQsvbX9PgfA3RAZyvL97PBC4tbVnDCnrRBoLzpljsbvQWehOdmA&s=1&enc=AZM-XJ7LOR0qbckZcE3NIsJHBmOhkWSJoerNmKjDu1EvbSS4bjr3G_iyZCe8sjBQWZtgrBuJA2QSK1l8KJuiIzjZ', 0),
(601, '2017-01-31 22:53:52', 55, '55.00', 3, '', 2, '65992217488', 'Esse pedido é apenas um teste. DESCONSIDERAR', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5625627%252C%2B-56.0510588%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOYRESBdVcKmaNeabSkqjVQC0kHVgOBBrfCSVnRoiJgSwidQTU1dDRxtViji-ThQsvbX9PgfA3RAZyvL97PBC4tbVnDCnrRBoLzpljsbvQWehOdmA&s=1&enc=AZM-XJ7LOR0qbckZcE3NIsJHBmOhkWSJoerNmKjDu1EvbSS4bjr3G_iyZCe8sjBQWZtgrBuJA2QSK1l8KJuiIzjZ', 0),
(602, '2017-02-01 02:44:04', 40, '113.00', 2, ' Observação do cliente: pedido de teste desconsiderar por favor', 2, '6599227458', 'pedido de teste desconsiderar por favor', NULL, NULL, 1, 'MT', '', 4, '78000000', '', 0),
(603, '2018-02-01 01:34:26', 56, '57.00', 13, ' Observação do cliente: ', 4, '999816963', 'Av.Miguel Sutil n.3.271 Cleide Imóveis,  esquina Rua Amarilio de Almeida', NULL, NULL, 1, 'MT', '', 89, '78000000', '', 1),
(604, '2017-02-01 01:45:30', 40, '113.00', 2, '', 2, '6599227458', 'pedido de teste desconsiderar por favor', NULL, NULL, 1, 'MT', '', 4, '78000000', '', 0),
(605, '2018-02-01 02:12:35', 51, '45.00', 8, '', 1, '65992217482', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5625627%252C%2B-56.0510588%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOYRESBdVcKmaNeabSkqjVQC0kHVgOBBrfCSVnRoiJgSwidQTU1dDRxtViji-ThQsvbX9PgfA3RAZyvL97PBC4tbVnDCnrRBoLzpljsbvQWehOdmA&s=1&enc=AZM-XJ7LOR0qbckZcE3NIsJHBmOhkWSJoerNmKjDu1EvbSS4bjr3G_iyZCe8sjBQWZtgrBuJA2QSK1l8KJuiIzjZ', 0),
(606, '2018-02-01 17:13:24', 51, '45.00', 8, '', 1, '65992217482', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5625627%252C%2B-56.0510588%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOYRESBdVcKmaNeabSkqjVQC0kHVgOBBrfCSVnRoiJgSwidQTU1dDRxtViji-ThQsvbX9PgfA3RAZyvL97PBC4tbVnDCnrRBoLzpljsbvQWehOdmA&s=1&enc=AZM-XJ7LOR0qbckZcE3NIsJHBmOhkWSJoerNmKjDu1EvbSS4bjr3G_iyZCe8sjBQWZtgrBuJA2QSK1l8KJuiIzjZ', 1),
(607, '2018-02-02 03:32:33', 53, '56.00', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(608, '2018-02-02 23:17:48', 38, '50.90', 3, '', 2, '984215292', 'Residencial São Carlos, bloco 46, apartamento 403. É um bloco de esquina, de muros marrom. Assim que chegar fazer me ligar, 984215292 para abrir o portão.', NULL, NULL, 1, 'MT', '', 95, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.584244%252C%2B-56.049464%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATMivqBAEj3GsCDDt9cBlQ9k6hBgUIDi1cnzqXnGDs9-lAdRojuKfGHk3c0Xop_u_5o6bS2Cn5YPmVD3hHf7njbnsKZbzNWeO1DDuBUCpjJmz0NtFw&s=1&enc=AZNS7oOe-w92sXKi61uNx-01TCuBuF2qJDOJJj2BNnse8MTBxMKe73qAIyDavAnVnyzfrZhjQvXgVSonfKR6bHgR', 2),
(609, '2018-02-04 01:13:27', 53, '40.00', 13, ' Observação do cliente: ', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 1),
(610, '2018-02-16 12:30:27', 53, '45.00', 13, ' Observação do cliente: ', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(611, '2018-02-16 13:17:33', 53, '63.90', 13, ' Observação do cliente: ', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(612, '2018-02-16 15:59:27', 53, '71.10', 13, ' Observação do cliente: ', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(613, '2018-02-16 16:00:32', 53, '79.00', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(614, '2018-02-16 16:17:59', 53, '71.10', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(615, '2018-02-16 16:22:38', 53, '71.10', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(616, '2018-02-16 16:24:28', 53, '71.10', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(617, '2018-02-16 16:31:52', 53, '71.10', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2);
INSERT INTO `pedido` (`codigo_pedido`, `data_hora_pedido`, `cliente_pizzaria_pedido`, `valor_total_pedido`, `forma_pagamento_pedido`, `observacao_pedido`, `pizzaria_pedido`, `telefone_pedido`, `endereco_pedido`, `numero_endereco_pedido`, `complemento_endereco_pedido`, `cidade_pedido`, `uf_pedido`, `referencia_endereco_pedido`, `bairro_pedido`, `cep_pedido`, `mapa_url_pedido`, `status_pedido`) VALUES
(618, '2018-02-16 16:32:16', 53, '79.00', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(619, '2018-02-16 16:34:33', 53, '79.00', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(620, '2018-02-16 16:36:34', 53, '71.10', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(621, '2018-02-16 17:00:20', 53, '71.10', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(622, '2018-02-16 17:11:05', 53, '71.10', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(623, '2018-02-16 17:57:48', 53, '49.50', 13, ' Observação do cliente: Ok', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(624, '2018-02-16 18:13:07', 53, '113.00', 13, ' Observação do cliente: ', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(625, '2018-02-16 18:22:56', 53, '49.50', 13, ' Observação do cliente: ', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(626, '2018-02-16 18:26:24', 53, '49.50', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(627, '2018-02-16 18:35:54', 53, '63.90', 1, 'Levar troco para R$De 100 reais.  Observação do cliente: ', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(628, '2018-02-16 18:40:58', 53, '63.90', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(629, '2018-02-16 19:31:21', 53, '71.00', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(631, '2018-02-16 19:43:57', 53, '71.00', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(632, '2018-02-16 19:45:33', 53, '71.00', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(634, '2018-02-16 20:03:15', 53, '63.90', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(635, '2018-02-16 21:41:42', 53, '63.90', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(636, '2018-02-20 22:58:34', 53, '61.20', 13, ' Observação do cliente: ', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 1),
(637, '2018-02-23 23:49:19', 57, '50.00', 2, ' Observação do cliente: ', 2, '996153035', 'Avenida das palmeiras, s/n; condominio rio coxipo, casa 274, jd imperial, cuiaba', NULL, NULL, 1, 'MT', '', 73, '78000000', '', 2),
(638, '2018-02-25 23:32:30', 58, '45.90', 1, 'Levar troco para R$50.  Observação do cliente: ', 2, '06599273413', 'Rua 13 quadra 15 casa 337', NULL, NULL, 1, 'MT', '', 71, '78000000', '', 2),
(639, '2018-02-26 02:25:16', 53, '61.20', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(640, '2018-02-26 02:25:23', 53, '61.20', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(641, '2018-02-26 02:25:33', 53, '61.20', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(642, '2018-02-26 02:25:37', 53, '61.20', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(643, '2018-02-26 02:35:32', 53, '61.20', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(644, '2018-02-26 02:43:52', 53, '61.20', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(645, '2018-02-26 02:44:01', 53, '61.20', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(646, '2018-02-26 02:44:07', 53, '61.20', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(647, '2018-02-26 02:48:15', 53, '61.20', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(648, '2018-02-26 02:48:26', 53, '61.20', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(649, '2018-02-26 02:50:37', 53, '61.20', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(650, '2018-02-26 02:50:47', 53, '61.20', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(651, '2018-02-26 03:01:08', 53, '61.20', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(652, '2018-02-26 03:02:43', 53, '61.20', 1, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(653, '2018-02-26 13:40:44', 53, '61.20', 13, ' Observação do cliente: ', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(654, '2018-02-26 14:43:27', 59, '68.00', 13, ' Observação do cliente: ', 4, '66987698765', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5625627%252C%2B-56.0510588%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOYRESBdVcKmaNeabSkqjVQC0kHVgOBBrfCSVnRoiJgSwidQTU1dDRxtViji-ThQsvbX9PgfA3RAZyvL97PBC4tbVnDCnrRBoLzpljsbvQWehOdmA&s=1&enc=AZM-XJ7LOR0qbckZcE3NIsJHBmOhkWSJoerNmKjDu1EvbSS4bjr3G_iyZCe8sjBQWZtgrBuJA2QSK1l8KJuiIzjZ', 0),
(655, '2018-02-26 14:44:22', 59, '68.00', 13, '', 4, '66987698765', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5625627%252C%2B-56.0510588%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOYRESBdVcKmaNeabSkqjVQC0kHVgOBBrfCSVnRoiJgSwidQTU1dDRxtViji-ThQsvbX9PgfA3RAZyvL97PBC4tbVnDCnrRBoLzpljsbvQWehOdmA&s=1&enc=AZM-XJ7LOR0qbckZcE3NIsJHBmOhkWSJoerNmKjDu1EvbSS4bjr3G_iyZCe8sjBQWZtgrBuJA2QSK1l8KJuiIzjZ', 0),
(656, '2018-02-26 14:46:45', 59, '55.00', 13, ' Observação do cliente: ', 4, '66987698765', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5625627%252C%2B-56.0510588%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOYRESBdVcKmaNeabSkqjVQC0kHVgOBBrfCSVnRoiJgSwidQTU1dDRxtViji-ThQsvbX9PgfA3RAZyvL97PBC4tbVnDCnrRBoLzpljsbvQWehOdmA&s=1&enc=AZM-XJ7LOR0qbckZcE3NIsJHBmOhkWSJoerNmKjDu1EvbSS4bjr3G_iyZCe8sjBQWZtgrBuJA2QSK1l8KJuiIzjZ', 0),
(657, '2018-03-02 14:57:59', 53, '68.00', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(658, '2018-03-07 14:41:23', 53, '61.20', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(659, '2018-03-07 18:56:15', 53, '62.70', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 1),
(660, '2018-03-24 00:26:50', 38, '58.00', 1, 'Levar troco para R$100.  Observação do cliente: ', 2, '984215292', 'Residencial São Carlos, bloco 46, apartamento 403. É um bloco de esquina, de muros marrom. Assim que chegar fazer me ligar, 984215292 para abrir o portão.', NULL, NULL, 1, 'MT', '', 95, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.584244%252C%2B-56.049464%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATMivqBAEj3GsCDDt9cBlQ9k6hBgUIDi1cnzqXnGDs9-lAdRojuKfGHk3c0Xop_u_5o6bS2Cn5YPmVD3hHf7njbnsKZbzNWeO1DDuBUCpjJmz0NtFw&s=1&enc=AZNS7oOe-w92sXKi61uNx-01TCuBuF2qJDOJJj2BNnse8MTBxMKe73qAIyDavAnVnyzfrZhjQvXgVSonfKR6bHgR', 2),
(661, '2018-04-08 23:12:00', 60, '45.90', 1, 'Levar troco para R$100.  Observação do cliente: ', 2, '659962-8896', 'Rua: i Número 65 bloco 3 apartamento 51', NULL, NULL, 1, 'MT', '', 99, '78000000', '', 2),
(662, '2018-04-12 14:31:32', 53, '55.80', 13, ' Observação do cliente: ', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(663, '2018-04-12 14:39:23', 53, '51.57', 13, ' Observação do cliente: ', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(664, '2018-04-12 15:01:18', 53, '57.30', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(665, '2018-04-12 15:06:57', 53, '65.40', 13, ' Observação do cliente: ', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(666, '2018-04-12 15:08:27', 53, '65.40', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(667, '2018-04-12 22:54:36', 61, '47.90', 3, ' Observação do cliente: ', 2, '98113-3794', 'Av. Miguel Sutil, 6322 - edifício Villaggio Di Bonifácia, torre 2, apartamento 201', NULL, NULL, 1, 'MT', '', 30, '78000000', '', 2),
(668, '2018-04-14 01:22:21', 53, '62.70', 13, ' Observação do cliente: nada nao', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(669, '2018-04-14 02:12:54', 53, '62.70', 13, '', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(670, '2018-04-14 22:51:00', 38, '51.90', 3, ' Observação do cliente: ', 2, '984215292', 'Residencial São Carlos, bloco 46, apartamento 403. É um bloco de esquina, de muros marrom. Assim que chegar fazer me ligar, 984215292 para abrir o portão.', NULL, NULL, 1, 'MT', '', 95, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.584244%252C%2B-56.049464%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATMivqBAEj3GsCDDt9cBlQ9k6hBgUIDi1cnzqXnGDs9-lAdRojuKfGHk3c0Xop_u_5o6bS2Cn5YPmVD3hHf7njbnsKZbzNWeO1DDuBUCpjJmz0NtFw&s=1&enc=AZNS7oOe-w92sXKi61uNx-01TCuBuF2qJDOJJj2BNnse8MTBxMKe73qAIyDavAnVnyzfrZhjQvXgVSonfKR6bHgR', 2),
(671, '2018-04-16 02:03:59', 53, '56.40', 13, ' Observação do cliente: ', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 1),
(672, '2018-04-16 13:24:12', 42, '50.00', 13, '', 4, '65981150626', 'Rua gravateiro, 11, CPA 4, 3 etapa', NULL, NULL, 1, 'MT', '', 89, '78000000', '', 1),
(673, '2018-04-16 16:40:44', 53, '58.00', 13, ' Observação do cliente: ', 4, '06698765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(674, '2018-04-16 19:09:20', 53, '55.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(675, '2018-04-16 19:11:09', 53, '55.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(676, '2018-04-16 19:13:31', 53, '55.00', 13, '', 4, '66998765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(677, '2018-04-16 19:14:41', 53, '55.00', 13, '', 4, '66998765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(678, '2018-04-17 11:34:54', 42, '50.00', 1, ' Observação do cliente: ', 4, '65981150626', 'Rua gravateiro, 11, CPA 4, 3 etapa', NULL, NULL, 1, 'MT', '', 89, '78000000', '', 1),
(679, '2018-04-17 11:43:23', 42, '50.00', 13, '', 4, '65981150626', 'Rua gravateiro, 11, CPA 4, 3 etapa', NULL, NULL, 1, 'MT', '', 89, '78000000', '', 1),
(680, '2018-04-17 13:27:08', 42, '50.00', 13, ' Observação do cliente: ', 4, '65981150626', 'Rua gravateiro, 11, CPA 4, 3 etapa', NULL, NULL, 1, 'MT', '', 89, '78000000', '', 1),
(681, '2018-04-17 13:39:55', 42, '46.00', 13, ' Observação do cliente: ', 4, '65981150626', 'Rua gravateiro, 11, CPA 4, 3 etapa', NULL, NULL, 1, 'MT', '', 89, '78000000', '', 0),
(682, '2018-04-17 13:41:10', 42, '46.00', 13, '', 4, '65981150626', 'Rua gravateiro, 11, CPA 4, 3 etapa', NULL, NULL, 1, 'MT', '', 89, '78000000', '', 1),
(683, '2018-04-17 13:45:52', 42, '112.15', 13, ' Observação do cliente: ', 4, '65981150626', 'Rua gravateiro, 11, CPA 4, 3 etapa', NULL, NULL, 1, 'MT', '', 89, '78000000', '', 0),
(684, '2018-04-17 15:01:25', 42, '112.15', 13, '', 4, '65981150626', 'Rua gravateiro, 11, CPA 4, 3 etapa', NULL, NULL, 1, 'MT', '', 89, '78000000', '', 0),
(685, '2018-04-17 15:12:15', 53, '56.40', 13, ' Observação do cliente: ', 4, '66998765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(686, '2018-04-17 17:03:17', 53, '51.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(687, '2018-04-17 18:03:41', 53, '51.00', 13, '', 4, '66998765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(688, '2018-04-18 01:05:11', 42, '50.00', 13, ' Observação do cliente: ', 4, '65981150626', 'Rua gravateiro, 11, CPA 4, 3 etapa', NULL, NULL, 1, 'MT', '', 89, '78000000', '', 0),
(689, '2018-04-18 17:47:33', 42, '46.00', 13, ' Observação do cliente: ', 4, '65981150626', 'Rua gravateiro, 11, CPA 4, 3 etapa', NULL, NULL, 1, 'MT', '', 89, '78000000', '', 0),
(690, '2018-04-18 22:42:53', 42, '46.00', 13, '', 4, '65981150626', 'Rua gravateiro, 11, CPA 4, 3 etapa', NULL, NULL, 1, 'MT', '', 89, '78000000', '', 2),
(691, '2018-04-19 16:01:53', 42, '51.40', 13, ' Observação do cliente: ', 4, '65981150626', 'Rua gravateiro, 11, CPA 4, 3 etapa', NULL, NULL, 1, 'MT', '', 89, '78000000', '', 2),
(692, '2018-04-20 02:58:11', 42, '51.40', 13, '', 4, '65981150626', 'Rua gravateiro, 11, CPA 4, 3 etapa', NULL, NULL, 1, 'MT', '', 89, '78000000', '', 2),
(693, '2018-04-20 03:08:55', 42, '51.40', 13, '', 4, '65981150626', 'Rua gravateiro, 11, CPA 4, 3 etapa', NULL, NULL, 1, 'MT', '', 89, '78000000', '', 0),
(695, '2018-04-20 21:40:49', 63, '61.70', 2, ' Observação do cliente: Isso é apenas um teste de promoção', 2, '65992893683', 'Isso é apenas um teste de promoção', NULL, NULL, 1, 'MT', '', 140, '78000000', '', 0),
(696, '2018-04-23 13:08:09', 41, '88.30', 13, ' Observação do cliente: ', 4, '77788909789', 'Ying iso', NULL, NULL, 1, 'MT', '', 3, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 2),
(697, '2018-04-23 13:17:38', 41, '88.30', 13, '', 4, '77788909789', 'Ying iso', NULL, NULL, 1, 'MT', '', 3, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 2),
(698, '2018-04-24 01:21:22', 67, '98.80', 3, ' Observação do cliente: ', 2, '33652608', 'Rua 235 quadra 77 casa 58 tijucal setor 2 Cuiabá', NULL, NULL, 1, 'MT', '', 136, '78000000', '', 2),
(699, '2018-04-25 11:43:44', 42, '51.40', 13, '', 4, '65981150626', 'Rua gravateiro, 11, CPA 4, 3 etapa', NULL, NULL, 1, 'MT', '', 89, '78000000', '', 2),
(700, '2018-04-25 11:59:30', 42, '51.40', 13, '', 4, '65981150626', 'Rua gravateiro, 11, CPA 4, 3 etapa', NULL, NULL, 1, 'MT', '', 89, '78000000', '', 2),
(701, '2018-04-25 16:15:41', 53, '51.00', 13, '', 4, '66998765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(702, '2018-04-26 00:15:03', 53, '51.00', 13, '', 4, '66998765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(703, '2018-04-26 12:09:30', 70, '46.00', 13, ' Observação do cliente: ', 4, '65981150626', 'rua 66, quadra 94, n11, cpa 4, 3 etapa', NULL, NULL, 1, 'MT', '', 3, '78000000', '', 2),
(704, '2018-04-26 12:19:50', 42, '51.40', 13, '', 4, '65981150626', 'Rua gravateiro, 11, CPA 4, 3 etapa', NULL, NULL, 1, 'MT', '', 89, '78000000', '', 2),
(705, '2018-04-25 13:34:52', 41, '97.00', 13, '', 4, '77788909789', 'Ying iso', NULL, NULL, 1, 'MT', '', 3, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 2),
(706, '2018-04-26 15:04:51', 42, '56.00', 13, '', 4, '65981150626', 'Rua gravateiro, 11, CPA 4, 3 etapa', NULL, NULL, 1, 'MT', '', 89, '78000000', '', 2),
(707, '2018-04-28 21:43:27', 74, '45.50', 1, 'Levar troco para R$50.  Observação do cliente: Quero cebola', 2, '996251903', 'Rua porto cercado numero 8 cpa 2', NULL, NULL, 1, 'MT', '', 140, '78000000', '', 0),
(708, '2018-04-28 21:42:52', 74, '45.50', 1, 'Levar troco para R$50.  Observação do cliente: Com cebola', 2, '996251903', 'Rua porto cercado numero 8 cpa 2', NULL, NULL, 1, 'MT', '', 140, '78000000', '', 2),
(709, '2018-05-03 19:30:15', 53, '57.30', 13, ' Observação do cliente: ', 4, '66998765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(710, '2018-05-04 16:42:29', 53, '50.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(711, '2018-05-04 16:45:03', 53, '62.00', 13, '', 4, '66998765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(712, '2018-05-04 20:22:13', 53, '62.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(713, '2018-05-05 16:07:55', 41, '45.00', 13, ' Observação do cliente: ', 4, '77788909789', 'Ying iso', NULL, NULL, 1, 'MT', '', 3, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5626358%252C%2B-56.0510487%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATNZBj8bb4YRCZ87kFxfRxiWyTA_26Kh6ri1UJY370KD03vZo_x-ry7qosxKGe_XqiIbOfr00V2-G0f9MZ-hDd6QqqmEadwCmCRw7QCSqwzriIkrng&s=1&enc=AZOm2SjhTUyn-8piyettqxJbp9l-QwckHi7yLnBRJXbSZXRNkdOJ21O8xSQefcatBVG_T8uHhWQBSmycUVsNtDzy', 2),
(714, '2018-05-06 03:04:06', 53, '51.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(715, '2018-05-06 23:11:12', 85, '48.50', 3, ' Observação do cliente: Proximo uma lanchonete do Chapó', 2, '06599224529', 'Av 22 qd 20', NULL, NULL, 1, 'MT', '', 149, '78000000', '', 2),
(716, '2018-05-06 23:45:58', 86, '43.91', 1, ' Observação do cliente: ', 2, '65 9 998292', 'Rua 7 quadra 10   Jardim Vitória  número 323', NULL, NULL, 1, 'MT', '', 36, '78000000', '', 2),
(717, '2018-05-07 00:04:12', 85, '48.50', 3, ' Observação do cliente: Demora quanto tempi', 2, '06599224529', 'Rua 22 qd 22 casa 16', NULL, NULL, 1, 'MT', '', 149, '78000000', '', 0),
(718, '2018-05-06 22:13:26', 59, '62.00', 13, ' Observação do cliente: ', 4, '66987698765', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5625627%252C%2B-56.0510588%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOYRESBdVcKmaNeabSkqjVQC0kHVgOBBrfCSVnRoiJgSwidQTU1dDRxtViji-ThQsvbX9PgfA3RAZyvL97PBC4tbVnDCnrRBoLzpljsbvQWehOdmA&s=1&enc=AZM-XJ7LOR0qbckZcE3NIsJHBmOhkWSJoerNmKjDu1EvbSS4bjr3G_iyZCe8sjBQWZtgrBuJA2QSK1l8KJuiIzjZ', 2),
(719, '2018-05-08 00:24:32', 53, '55.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 0),
(720, '2018-05-12 03:10:46', 49, '45.50', 3, ' Observação do cliente: Sem cebola', 2, '992347058', 'Rua M Quadra 14 Número 30 Bairro Sol nascente', NULL, NULL, 1, 'MT', '', 98, '78000000', '', 2),
(721, '2018-05-14 17:43:39', 53, '68.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(722, '2018-05-14 18:01:52', 53, '56.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(723, '2018-05-14 18:09:32', 53, '66.00', 1, 'Levar troco para R$66.  Observação do cliente: Não quero mais nada', 4, '66998765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(724, '2018-05-14 18:38:08', 53, '104.00', 1, 'Levar troco para R$105.  Observação do cliente: ', 4, '66998765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(725, '2018-05-24 02:14:10', 59, '55.00', 13, ' Observação do cliente: ', 4, '66987698765', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5625627%252C%2B-56.0510588%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATOYRESBdVcKmaNeabSkqjVQC0kHVgOBBrfCSVnRoiJgSwidQTU1dDRxtViji-ThQsvbX9PgfA3RAZyvL97PBC4tbVnDCnrRBoLzpljsbvQWehOdmA&s=1&enc=AZM-XJ7LOR0qbckZcE3NIsJHBmOhkWSJoerNmKjDu1EvbSS4bjr3G_iyZCe8sjBQWZtgrBuJA2QSK1l8KJuiIzjZ', 1),
(726, '2018-06-03 23:53:51', 58, '42.71', 1, 'Levar troco para R$50.  Observação do cliente: ', 2, '992734135', 'Rua 13 quadra 15 casa 337', NULL, NULL, 1, 'MT', '', 71, '78000000', '', 0),
(727, '2018-06-06 00:23:26', 86, '47.90', 1, 'Levar troco para R$50.  Observação do cliente: ', 2, '65 9 998292', 'Rua 7 quadra 10   Jardim Vitória  número 323', NULL, NULL, 1, 'MT', '', 36, '78000000', '', 2),
(728, '2018-06-11 16:21:37', 53, '45.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Gfvfvfh', NULL, NULL, 1, 'MT', '', 99, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(729, '2018-06-11 16:25:03', 53, '45.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Caso verde', NULL, NULL, 1, 'MT', '', 99, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(730, '2018-06-11 17:53:28', 53, '45.00', 1, ' Observação do cliente: ', 4, '66998765432', 'Ujdjf dufnf', NULL, NULL, 1, 'MT', '', 99, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(731, '2018-06-11 19:49:44', 53, '50.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Hfg', NULL, NULL, 1, 'MT', '', 140, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(732, '2018-06-11 20:10:41', 53, '45.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Tete', NULL, NULL, 1, 'MT', '', 157, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(733, '2018-06-11 21:04:52', 53, '151.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Hcjfjnf', NULL, NULL, 1, 'MT', '', 160, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2);
INSERT INTO `pedido` (`codigo_pedido`, `data_hora_pedido`, `cliente_pizzaria_pedido`, `valor_total_pedido`, `forma_pagamento_pedido`, `observacao_pedido`, `pizzaria_pedido`, `telefone_pedido`, `endereco_pedido`, `numero_endereco_pedido`, `complemento_endereco_pedido`, `cidade_pedido`, `uf_pedido`, `referencia_endereco_pedido`, `bairro_pedido`, `cep_pedido`, `mapa_url_pedido`, `status_pedido`) VALUES
(734, '2018-06-11 21:12:19', 53, '45.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Vtbtb', NULL, NULL, 1, 'MT', '', 119, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(735, '2018-06-12 13:50:09', 53, '45.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Teste', NULL, NULL, 1, 'MT', '', 99, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(736, '2018-06-12 13:55:29', 53, '45.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Grgrb', NULL, NULL, 1, 'MT', '', 127, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(737, '2018-06-12 14:00:47', 53, '151.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Y Jr bt', NULL, NULL, 1, 'MT', '', 160, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(738, '2018-06-12 14:16:03', 53, '55.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Fcgvh gh', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(739, '2018-06-12 14:22:46', 53, '40.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Jdnd', NULL, NULL, 1, 'MT', '', 192, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(740, '2018-06-12 14:38:26', 53, '55.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Nfjfkf', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(741, '2018-06-12 14:46:47', 53, '55.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Ffh', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(742, '2018-06-12 14:56:11', 53, '55.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Teste', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(743, '2018-06-12 14:59:44', 53, '151.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Jxjff', NULL, NULL, 1, 'MT', '', 160, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(744, '2018-06-12 15:02:49', 53, '55.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Jff', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(745, '2018-06-12 15:04:33', 53, '151.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Tete', NULL, NULL, 1, 'MT', '', 160, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(746, '2018-06-12 15:07:12', 53, '55.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Nfnf', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(747, '2018-06-12 16:51:31', 53, '45.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Hgfy', NULL, NULL, 1, 'MT', '', 157, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(748, '2018-06-13 14:30:16', 53, '45.00', 13, '', 4, '66998765432', 'Hhhfbdf', NULL, NULL, 1, 'MT', '', 157, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(749, '2018-06-13 20:03:59', 53, '55.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Tete toppp', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(750, '2018-06-14 14:11:03', 53, '55.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Tete toppp', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(751, '2018-06-14 15:04:48', 53, '55.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Tete toppp', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(752, '2018-06-14 16:32:40', 53, '118.50', 13, ' Observação do cliente: ', 4, '66998765432', 'Casa roxa', NULL, NULL, 1, 'MT', '', 140, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(753, '2018-06-14 16:41:33', 53, '118.50', 13, ' Observação do cliente: ', 4, '66998765432', 'Casa roxa', NULL, NULL, 1, 'MT', '', 140, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(754, '2018-06-14 16:51:38', 53, '118.50', 13, ' Observação do cliente: ', 4, '66998765432', 'Casa azul', NULL, NULL, 1, 'MT', '', 140, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 1),
(755, '2018-06-16 23:42:21', 112, '54.70', 3, ' Observação do cliente: Nada mais', 2, '992908059', 'Avenida A Quadra 04 Casa 25 Bairro Nova Canaã 3° etapa', NULL, NULL, 1, 'MT', '', 144, '78000000', '', 2),
(756, '2018-06-19 14:42:44', 53, '123.50', 13, ' Observação do cliente: ', 4, '66998765432', 'Casa verde', NULL, NULL, 1, 'MT', '', 4, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(757, '2018-06-19 14:52:32', 53, '63.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Casa amarela', NULL, NULL, 1, 'MT', '', 140, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 2),
(758, '2018-06-19 15:03:28', 53, '63.00', 13, ' Observação do cliente: ', 4, '66998765432', 'Casa amarela', NULL, NULL, 1, 'MT', '', 140, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.5627393%252C%2B-56.0511614%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATP3ORuMLi-P6w6ZXJuBKvzEX0aWe6HvJ5x9DFU6si9UHnjQxJRgHREcKSmuMSiUn7E19CxpsOiA0VvoQQEHzuIWt0pFtd3C-muTBIhDK5OIjwsEpw&s=1&enc=AZNhb9HOr_tOwnPDK-QiRHCe5ztjufhU5XLJSLNp7Zz9kea5tr1AovF5XA9DMgxmQ6wW-NBJlsuOQKX7ead-n6YQ', 1),
(759, '2018-07-27 23:19:44', 123, '45.46', 2, ' Observação do cliente: ', 2, '999878425', 'Rua 50 Quadra 80 Casa 28 CPA 4 , Segunda Etapa', NULL, NULL, 1, 'MT', '', 3, '78000000', '', 2),
(760, '2018-07-27 23:24:29', 123, '45.46', 2, '', 2, '999878425', 'Rua 50 Quadra 80 Casa 28 CPA 4 , Segunda Etapa', NULL, NULL, 1, 'MT', '', 3, '78000000', '', 0),
(761, '2018-07-28 00:40:13', 61, '46.51', 3, ' Observação do cliente: ', 2, '98113-3794', 'Av. Miguel Sutil, 6322 - edifício Villaggio Di Bonifácia, torre 2, apartamento 201', NULL, NULL, 1, 'MT', '', 30, '78000000', '', 2),
(762, '2018-08-17 00:27:52', 131, '46.90', 1, ' Observação do cliente: ', 2, '99228-9050', 'Avenida Gonçalo Antunes de Barros número 1710 bairro Carumbé ponto de referência oficina do Abel  AB AUTOCENTER telefone 65 99228-9050', NULL, NULL, 1, 'MT', '', 64, '78000000', '', 2),
(763, '2018-08-19 22:41:45', 135, '52.70', 2, ' Observação do cliente: ', 2, '06599801964', 'Rua 16 quadra 28 casa 28 cpa3 setor 5', NULL, NULL, 1, 'MT', '', 141, '78000000', '', 2),
(764, '2018-08-20 00:51:54', 38, '48.11', 3, ' Observação do cliente: ', 2, '984215292', 'Residencial São Carlos, bloco 46, apartamento 403. É um bloco de esquina, de muros marrom. Assim que chegar fazer me ligar, 984215292 para abrir o portão.', NULL, NULL, 1, 'MT', '', 95, '78056606', 'https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D-15.584244%252C%2B-56.049464%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATMivqBAEj3GsCDDt9cBlQ9k6hBgUIDi1cnzqXnGDs9-lAdRojuKfGHk3c0Xop_u_5o6bS2Cn5YPmVD3hHf7njbnsKZbzNWeO1DDuBUCpjJmz0NtFw&s=1&enc=AZNS7oOe-w92sXKi61uNx-01TCuBuF2qJDOJJj2BNnse8MTBxMKe73qAIyDavAnVnyzfrZhjQvXgVSonfKR6bHgR', 2),
(765, '2018-08-26 22:42:26', 139, '48.11', 1, 'Levar troco para R$50.  Observação do cliente: Qual o valor total', 2, '96680894', 'Rua do caju 277 Alvorada', NULL, NULL, 1, 'MT', '', 4, '78000000', '', 2),
(766, '2018-09-02 22:17:11', 142, '45.50', 3, ' Observação do cliente: ', 2, '65 99220001', 'Rua Piracicaba Número 150\nBairro Novo Horizonte', NULL, NULL, 1, 'MT', '', 84, '78000000', '', 2),
(767, '2018-09-11 22:33:34', 139, '52.90', 3, ' Observação do cliente: ', 2, '96680894', 'Rua do caju 277 Alvorada', NULL, NULL, 1, 'MT', '', 4, '78000000', '', 2);

--
-- Acionadores `pedido`
--
DELIMITER $$
CREATE TRIGGER `trigger_validacao_pedido_bi` BEFORE INSERT ON `pedido` FOR EACH ROW BEGIN
	if (new.DATA_HORA_PEDIDO is null ) then
		signal sqlstate '45000' set message_text = 'DATA DO PEDIDO não pode ser vazio/nulo.';
	end if;
	
    if (new.endereco_pedido = '' or new.endereco_pedido is null) then
		signal sqlstate '45000' set message_text = 'ENDEREÇO não pode ser vazio/nulo.';
	end if;
    if (new.cidade_pedido < 0 or new.cidade_pedido is null) then
		signal sqlstate '45000' set message_text = 'CIDADE não pode ser vazio/nulo.';
	end if;
    if(new.bairro_pedido < 0 or new.bairro_pedido is null) then
    	signal sqlstate '45000' set message_text = 'BAIRRO não pode ser vazio/nulo.';
	END IF;
	if (new.cliente_pizzaria_pedido = '' or new.cliente_pizzaria_pedido is null) then
		signal sqlstate '45000' set message_text = 'CLIENTE não pode ser vazio/nulo.';
	end if;
    if (new.telefone_pedido = '' or new.telefone_pedido is null) then
		signal sqlstate '45000' set message_text = 'TELEFONE não pode ser vazio/nulo.';
	else IF(skybots_gerencia.function_valida_telefone(new.telefone_pedido) = 0) then
			signal sqlstate '45000' set message_text = 'TELEFONE inválido.';
	END IF;
	end if;
    if (new.uf_pedido = '' or new.uf_pedido is null) then
		signal sqlstate '45000' set message_text = 'UF não pode ser vazio/nulo.';
	end if;
	if (new.PIZZARIA_PEDIDO is null or new.PIZZARIA_PEDIDO < 0 ) then
		signal sqlstate '45000' set message_text = 'PIZZARIA não pode ser vazio/nulo.';
	end if;  	
	if((new.CEP_pedido is not null or new.CEP_pedido = '')  and skybots_gerencia.function_valida_cEP(new.CEP_pedido) = 0) then
			signal sqlstate '45000' set message_text = 'CEP inválido.';
	end if;	
    if (new.valor_total_pedido is null or new.valor_total_pedido < 0 ) then
		signal sqlstate '45000' set message_text = 'VALOR TOTAL deve ser maior que 0.';
	end if;
	if(new.numero_endereco_PEDIDO is not null and new.numero_endereco_PEDIDO < 0) then
		signal sqlstate '45000' set message_text = 'NÚMERO ENDEREÇO inválido.';
	end if;
	IF(NEW.STATUS_PEDIDO IS NULL OR NEW.STATUS_PEDIDO < 0) THEN
		signal sqlstate '45000' set message_text = 'STATUS inválido.';
	END IF;
    if (new.forma_pagamento_pedido is null or new.forma_pagamento_pedido < 0) then
		signal sqlstate '45000' set message_text = 'FORMA DO PAGAMENTO não pode ser vazio/nulo.';
	end if;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trigger_validacao_pedido_bu` BEFORE UPDATE ON `pedido` FOR EACH ROW BEGIN
	if (new.DATA_HORA_PEDIDO is null ) then
		signal sqlstate '45000' set message_text = 'DATA DO PEDIDO não pode ser vazio/nulo.';
	end if;

    if (new.endereco_pedido = '' or new.endereco_pedido is null) then
		signal sqlstate '45000' set message_text = 'ENDEREÇO não pode ser vazio/nulo.';
	end if;
    if (new.cidade_pedido < 0 or new.cidade_pedido is null) then
		signal sqlstate '45000' set message_text = 'CIDADE não pode ser vazio/nulo.';
	end if;
    if(new.bairro_pedido < 0 or new.bairro_pedido is null) then
    	signal sqlstate '45000' set message_text = 'BAIRRO não pode ser vazio/nulo.';
	END IF;
	if (new.cliente_pizzaria_pedido = '' or new.cliente_pizzaria_pedido is null) then
		signal sqlstate '45000' set message_text = 'CLIENTE não pode ser vazio/nulo.';
	end if;
    if (new.telefone_pedido = '' or new.telefone_pedido is null) then
		signal sqlstate '45000' set message_text = 'TELEFONE não pode ser vazio/nulo.';
	else IF(skybots_gerencia.function_valida_telefone(new.telefone_pedido) = 0) then
			signal sqlstate '45000' set message_text = 'TELEFONE inválido.';
	END IF;
	end if;
    if (new.uf_pedido = '' or new.uf_pedido is null) then
		signal sqlstate '45000' set message_text = 'UF não pode ser vazio/nulo.';
	end if;
	if (new.PIZZARIA_PEDIDO is null or new.PIZZARIA_PEDIDO < 0 ) then
		signal sqlstate '45000' set message_text = 'PIZZARIA não pode ser vazio/nulo.';
	end if;  	
	if((new.CEP_pedido is not null or new.CEP_pedido = '')  and skybots_gerencia.function_valida_cEP(new.CEP_pedido) = 0) then
			signal sqlstate '45000' set message_text = 'CEP inválido.';
	end if;	
	if(new.numero_endereco_PEDIDO is not null and new.numero_endereco_PEDIDO < 0) then
		signal sqlstate '45000' set message_text = 'NÚMERO ENDEREÇO inválido.';
	end if;
	IF(NEW.STATUS_PEDIDO IS NULL OR NEW.STATUS_PEDIDO < 0) THEN
		signal sqlstate '45000' set message_text = 'STATUS inválido.';
	END IF;
    if (new.valor_total_pedido is null or new.valor_total_pedido < 0 ) then
		signal sqlstate '45000' set message_text = 'VALOR TOTAL deve ser maior que 0.';
	end if;
    if (new.forma_pagamento_pedido is null or new.forma_pagamento_pedido < 0) then
		signal sqlstate '45000' set message_text = 'FORMA DO PAGAMENTO não pode ser vazio/nulo.';
	end if;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pizza`
--

CREATE TABLE `pizza` (
  `codigo_pizza` bigint(20) NOT NULL,
  `tamanho_pizza_pizza` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `pizza`
--

INSERT INTO `pizza` (`codigo_pizza`, `tamanho_pizza_pizza`) VALUES
(576, 1),
(524, 2),
(527, 2),
(528, 2),
(529, 2),
(532, 2),
(533, 2),
(534, 2),
(535, 2),
(538, 2),
(540, 2),
(541, 2),
(544, 2),
(545, 2),
(546, 2),
(547, 2),
(570, 2),
(571, 2),
(572, 2),
(573, 2),
(574, 2),
(575, 2),
(591, 2),
(635, 2),
(637, 2),
(665, 2),
(666, 2),
(694, 2),
(726, 2),
(733, 2),
(749, 2),
(479, 3),
(480, 3),
(481, 3),
(482, 3),
(483, 3),
(486, 3),
(487, 3),
(488, 3),
(489, 3),
(490, 3),
(491, 3),
(492, 3),
(495, 3),
(501, 3),
(502, 3),
(503, 3),
(504, 3),
(505, 3),
(506, 3),
(510, 3),
(511, 3),
(512, 3),
(513, 3),
(521, 3),
(522, 3),
(525, 3),
(526, 3),
(530, 3),
(531, 3),
(536, 3),
(537, 3),
(542, 3),
(549, 3),
(550, 3),
(568, 3),
(587, 3),
(622, 3),
(632, 3),
(633, 3),
(642, 3),
(643, 3),
(644, 3),
(645, 3),
(646, 3),
(648, 3),
(650, 3),
(652, 3),
(653, 3),
(654, 3),
(655, 3),
(657, 3),
(661, 3),
(662, 3),
(663, 3),
(664, 3),
(667, 3),
(668, 3),
(669, 3),
(670, 3),
(671, 3),
(672, 3),
(673, 3),
(674, 3),
(675, 3),
(676, 3),
(677, 3),
(678, 3),
(679, 3),
(680, 3),
(681, 3),
(682, 3),
(683, 3),
(684, 3),
(685, 3),
(686, 3),
(706, 3),
(708, 3),
(714, 3),
(717, 3),
(724, 3),
(725, 3),
(551, 4),
(552, 4),
(553, 4),
(554, 4),
(556, 4),
(557, 4),
(558, 4),
(559, 4),
(560, 4),
(562, 4),
(563, 4),
(564, 4),
(565, 4),
(566, 4),
(567, 4),
(577, 4),
(578, 4),
(579, 4),
(580, 4),
(581, 4),
(582, 4),
(583, 4),
(584, 4),
(585, 4),
(586, 4),
(609, 4),
(610, 4),
(611, 4),
(612, 4),
(613, 4),
(614, 4),
(615, 4),
(616, 4),
(617, 4),
(618, 4),
(619, 4),
(620, 4),
(621, 4),
(623, 4),
(624, 4),
(625, 4),
(626, 4),
(627, 4),
(628, 4),
(629, 4),
(630, 4),
(631, 4),
(634, 4),
(636, 4),
(638, 4),
(647, 4),
(649, 4),
(651, 4),
(656, 4),
(658, 4),
(660, 4),
(687, 4),
(688, 4),
(692, 4),
(693, 4),
(695, 4),
(696, 4),
(697, 4),
(698, 4),
(699, 4),
(700, 4),
(701, 4),
(702, 4),
(703, 4),
(704, 4),
(705, 4),
(707, 4),
(709, 4),
(713, 4),
(715, 4),
(716, 4),
(718, 4),
(721, 4),
(722, 4),
(723, 4),
(727, 4),
(728, 4),
(729, 4),
(730, 4),
(731, 4),
(732, 4),
(741, 4),
(743, 4),
(758, 4),
(759, 4),
(496, 5),
(497, 5),
(498, 5),
(499, 5),
(500, 5),
(507, 5),
(508, 5),
(509, 5),
(514, 5),
(515, 5),
(517, 5),
(519, 5),
(523, 5),
(539, 5),
(548, 5),
(561, 5),
(569, 5),
(589, 5),
(590, 5),
(641, 5),
(689, 5),
(710, 5),
(711, 5),
(712, 5),
(747, 5),
(751, 5),
(752, 5),
(753, 5),
(756, 5),
(791, 5),
(814, 5),
(851, 5),
(867, 5),
(868, 5),
(875, 5),
(877, 5),
(880, 5),
(919, 5),
(928, 5),
(931, 5),
(484, 6),
(485, 6),
(493, 6),
(754, 6),
(757, 6),
(555, 7),
(690, 7),
(640, 9),
(691, 9),
(761, 9),
(792, 9),
(815, 9),
(821, 9),
(824, 9),
(856, 9),
(857, 9),
(876, 9),
(887, 9),
(888, 9),
(924, 9),
(925, 9),
(926, 9),
(927, 9),
(929, 9),
(930, 9),
(932, 9),
(520, 10),
(494, 11),
(516, 11),
(518, 11),
(543, 11),
(639, 11),
(592, 23),
(593, 23),
(594, 23),
(595, 23),
(596, 23),
(597, 23),
(598, 23),
(599, 23),
(600, 23),
(601, 23),
(602, 23),
(603, 23),
(604, 23),
(605, 23),
(606, 23),
(607, 23),
(608, 23),
(737, 23),
(738, 23),
(739, 23),
(740, 23),
(742, 23),
(748, 23),
(750, 23),
(755, 23),
(760, 23),
(763, 23),
(764, 23),
(776, 23),
(778, 23),
(779, 23),
(780, 23),
(781, 23),
(782, 23),
(783, 23),
(785, 23),
(786, 23),
(788, 23),
(789, 23),
(790, 23),
(793, 23),
(794, 23),
(795, 23),
(796, 23),
(797, 23),
(798, 23),
(799, 23),
(800, 23),
(801, 23),
(802, 23),
(803, 23),
(804, 23),
(805, 23),
(806, 23),
(807, 23),
(808, 23),
(809, 23),
(810, 23),
(811, 23),
(812, 23),
(813, 23),
(816, 23),
(817, 23),
(818, 23),
(819, 23),
(820, 23),
(822, 23),
(823, 23),
(825, 23),
(826, 23),
(827, 23),
(828, 23),
(829, 23),
(830, 23),
(831, 23),
(832, 23),
(833, 23),
(834, 23),
(835, 23),
(836, 23),
(837, 23),
(839, 23),
(841, 23),
(842, 23),
(843, 23),
(844, 23),
(845, 23),
(846, 23),
(847, 23),
(848, 23),
(849, 23),
(850, 23),
(852, 23),
(853, 23),
(854, 23),
(855, 23),
(858, 23),
(859, 23),
(860, 23),
(861, 23),
(862, 23),
(863, 23),
(864, 23),
(865, 23),
(866, 23),
(869, 23),
(870, 23),
(871, 23),
(872, 23),
(873, 23),
(874, 23),
(878, 23),
(879, 23),
(881, 23),
(882, 23),
(883, 23),
(884, 23),
(885, 23),
(886, 23),
(889, 23),
(890, 23),
(891, 23),
(892, 23),
(893, 23),
(894, 23),
(895, 23),
(896, 23),
(897, 23),
(898, 23),
(899, 23),
(901, 23),
(902, 23),
(903, 23),
(904, 23),
(905, 23),
(906, 23),
(907, 23),
(908, 23),
(909, 23),
(910, 23),
(911, 23),
(912, 23),
(913, 23),
(915, 23),
(917, 23),
(920, 23),
(922, 23),
(923, 23),
(588, 24),
(659, 24),
(719, 24),
(720, 24),
(734, 25),
(735, 25),
(762, 25),
(777, 25),
(900, 25),
(914, 25),
(916, 25),
(918, 25),
(921, 25),
(736, 26),
(744, 26),
(745, 26),
(746, 26),
(765, 26),
(766, 26),
(767, 26),
(768, 26),
(769, 26),
(770, 26),
(771, 26),
(772, 26),
(773, 26),
(774, 26),
(775, 26),
(838, 26),
(840, 26);

--
-- Acionadores `pizza`
--
DELIMITER $$
CREATE TRIGGER `trigger_validacao_pizza_bi` BEFORE INSERT ON `pizza` FOR EACH ROW if (new.tamanho_pizza_pizza is null or new.tamanho_pizza_pizza < 0 ) then
		signal sqlstate '45000' set message_text = 'TAMANHO PIZZA não pode ser vazio/nulo.';
end if
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trigger_validacao_pizza_bu` BEFORE UPDATE ON `pizza` FOR EACH ROW if (new.tamanho_pizza_pizza is null or new.tamanho_pizza_pizza < 0 ) then
		signal sqlstate '45000' set message_text = 'TAMANHO PIZZA não pode ser vazio/nulo.';
end if
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pizzaria`
--

CREATE TABLE `pizzaria` (
  `codigo_pizzaria` bigint(20) NOT NULL,
  `cnpj_pizzaria` varchar(14) NOT NULL,
  `razao_social_pizzaria` varchar(400) NOT NULL,
  `nome_fantasia_pizzaria` varchar(400) NOT NULL,
  `email_pizzaria` varchar(400) NOT NULL,
  `perfil_facebook_pizzaria` varchar(400) NOT NULL,
  `telefone_pizzaria` varchar(11) NOT NULL,
  `celular_whats_pizzaria` bigint(12) DEFAULT NULL COMMENT 'celular que tem whatsApp',
  `celular_pizzaria` bigint(12) DEFAULT NULL COMMENT 'celular para receber ligações',
  `cep_pizzaria` varchar(10) NOT NULL,
  `endereco_pizzaria` varchar(400) NOT NULL,
  `numero_endereco_pizzaria` bigint(20) UNSIGNED NOT NULL,
  `complemento_endereco_pizzaria` varchar(400) DEFAULT NULL,
  `cidade_pizzaria` bigint(20) NOT NULL,
  `uf_pizzaria` varchar(2) NOT NULL,
  `token_facebook_pizzaria` varchar(400) NOT NULL,
  `data_hora_inclusao_pizzaria` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `bairro_pizzaria` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `pizzaria`
--

INSERT INTO `pizzaria` (`codigo_pizzaria`, `cnpj_pizzaria`, `razao_social_pizzaria`, `nome_fantasia_pizzaria`, `email_pizzaria`, `perfil_facebook_pizzaria`, `telefone_pizzaria`, `celular_whats_pizzaria`, `celular_pizzaria`, `cep_pizzaria`, `endereco_pizzaria`, `numero_endereco_pizzaria`, `complemento_endereco_pizzaria`, `cidade_pizzaria`, `uf_pizzaria`, `token_facebook_pizzaria`, `data_hora_inclusao_pizzaria`, `bairro_pizzaria`) VALUES
(1, '70431309000111', 'PIZZARIA VETERANA', 'PIZZARIA VETERANA', 'veteranama@gmail.com', 'aquilapizzaria', '6536340930', NULL, NULL, '78065258', 'Av. Histo. Rubens de Mendonça, do lado do Parque Massairo Okamura', 205, 'https://www.google.com.br/maps/place/Parque+Massairo+Okamura/@-15.5710089,-56.0733809,15.94z/data=!4m5!3m4!1s0x939db059aaaaaaab:0x4b69e1c2aafa7a6c!8m2!3d-15.5652729!4d-56.065397?hl=pt-BR', 1, 'MT', '123456', '2018-01-15 15:01:29', 1),
(2, '14602644000164', 'Reginaldo Pedroso de Araujo ME', 'Tarantino Pizza e Esfihas', 'tarantinopizzaeesfihas@gmail.com', 'tarantinopizzasesfihaselanches', '65992707191', NULL, NULL, '78058800', 'Avenida Dante Martins de Oliveira', 872, 'veteranama@gmail.com', 1, 'MT', '111', '2017-08-29 01:28:43', 85),
(3, '81586178000168', 'Pizzaria Moderna', 'Pizzaria Moderna', 'botsky.automacao@gmail.com', 'Pizzaria Moderna', '6592217482', NULL, NULL, '78600000', 'cuiaba', 15, 'centro', 1, 'MT', 'vnbqX6cpvXUXFcOKr5RHJ7psSpHDRzO1hXBY8dkvn50ZkZyWML3YdtoCnKH7FSjC', '2017-09-02 02:06:53', 1),
(4, '52571034000130', 'Anna funcionaria Skybots', 'Pizzaria da Anna', 'sac@skybots.com.br', 'https://www.facebook.com/annaskybots/', '65987654321', NULL, NULL, '78600000', 'Na nuvem tecnológica.', 1, 'versão 01', 1, 'MT', 'BxXmPaeffbIPttnrUQ00m6jjbqHRpi4Mo2jhqeAnswxKz17iKEpKHxd4hqTbHCSB', '2018-01-24 13:02:53', 38);

--
-- Acionadores `pizzaria`
--
DELIMITER $$
CREATE TRIGGER `trigger_validacao_pizzaria_bi` BEFORE INSERT ON `pizzaria` FOR EACH ROW BEGIN
-- Campos not nulls
	if (new.cnpj_pizzaria = '' or new.cnpj_pizzaria is null) then
		signal sqlstate '45000' set message_text = 'CNPJ não pode ser vazio/nulo.';
	ELSE if((new.cnpj_pizzaria is not null or new.cnpj_pizzaria = '') and skybots_gerencia.function_valida_CNPJ(new.cnpj_pizzaria) = 0) then
			signal sqlstate '45000' set message_text = 'CNPJ inválido.';
		END IF;
	end if;
    if (new.razao_social_pizzaria = '' or new.razao_social_pizzaria is null) then
		signal sqlstate '45000' set message_text = 'RAZÃO SOCIAL não pode ser vazio/nulo.';
	end if;
	if (new.nome_fantasia_pizzaria = '' or new.nome_fantasia_pizzaria is null) then
		signal sqlstate '45000' set message_text = 'NOME FANTASIA não pode ser vazio/nulo.';
	end if;
	if (new.email_pizzaria = '' or new.email_pizzaria is null) then
		signal sqlstate '45000' set message_text = 'E-MAIL não pode ser vazio/nulo.';
	ELSE if((new.email_pizzaria is not null or new.email_pizzaria = '')  and skybots_gerencia.function_valida_email(new.email_pizzaria) = 0) then
		signal sqlstate '45000' set message_text = 'E-MAIL inválido.';		
		END IF;
	END IF;
	if (new.perfil_facebook_pizzaria = '' or new.perfil_facebook_pizzaria is null) then
		signal sqlstate '45000' set message_text = 'PERFIL FACEBOOK não pode ser vazio/nulo.';
	end if;
	if (new.telefone_PIZZARIA = '' or new.telefone_PIZZARIA is null) then
		signal sqlstate '45000' set message_text = 'TELEFONE não pode ser vazio/nulo.';
	else IF(skybots_gerencia.function_valida_telefone(new.telefone_PIZZARIA) = 0) then
			signal sqlstate '45000' set message_text = 'TELEFONE inválido.';
		END IF;
	END IF;
	if (new.endereco_pizzaria = '' or new.endereco_pizzaria is null) then
		signal sqlstate '45000' set message_text = 'ENDEREÇO não pode ser vazio/nulo.';
	end if;	
    if (new.numero_endereco_pizzaria = '' or new.numero_endereco_pizzaria is null) then
		signal sqlstate '45000' set message_text = 'NÚMERO DO ENDEREÇO não pode ser vazio/nulo.';
	end if;    
    if (new.cidade_pizzaria < 0 or new.cidade_pizzaria is null) then
		signal sqlstate '45000' set message_text = 'CIDADE não pode ser vazio/nulo.';
	end if;
    if (new.BAIRRO_pizzaria < 0 or new.BAIRRO_pizzaria is null) then
		signal sqlstate '45000' set message_text = 'BAIRRO não pode ser vazio/nulo.';
	end if;
	if (new.uf_pizzaria = '' or new.uf_pizzaria is null) then
		signal sqlstate '45000' set message_text = 'UF não pode ser vazio/nulo.';
	end if;
    if (new.cep_pizzaria = '' or new.cep_pizzaria is null) then
		signal sqlstate '45000' set message_text = 'CEP não pode ser vazio/nulo.';
    ELSE if((new.cep_pizzaria is not null or new.cep_pizzaria = '')  and skybots_gerencia.function_valida_cEP(new.cep_pizzaria) = 0) then
			signal sqlstate '45000' set message_text = 'CEP inválido.';
		end if;   
	END IF;
	if (new.token_facebook_pizzaria = '' or new.token_facebook_pizzaria is null) then
		signal sqlstate '45000' set message_text = 'TOKEN FACEBOOK não pode ser vazio/nulo.';
	end if;	
    if (new.data_hora_inclusao_pizzaria is null ) then
		signal sqlstate '45000' set message_text = 'DATA HORA INCLUSÃO não pode ser vazio/nulo.';
	end if;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trigger_validacao_pizzaria_bu` BEFORE UPDATE ON `pizzaria` FOR EACH ROW BEGIN
-- Campos not nulls
	if (new.cnpj_pizzaria = '' or new.cnpj_pizzaria is null) then
		signal sqlstate '45000' set message_text = 'CNPJ não pode ser vazio/nulo.';
	ELSE if((new.cnpj_pizzaria is not null or new.cnpj_pizzaria = '') and skybots_gerencia.function_valida_CNPJ(new.cnpj_pizzaria) = 0) then
			signal sqlstate '45000' set message_text = 'CNPJ inválido.';
		END IF;
	end if;
    if (new.razao_social_pizzaria = '' or new.razao_social_pizzaria is null) then
		signal sqlstate '45000' set message_text = 'RAZÃO SOCIAL não pode ser vazio/nulo.';
	end if;
	if (new.nome_fantasia_pizzaria = '' or new.nome_fantasia_pizzaria is null) then
		signal sqlstate '45000' set message_text = 'NOME FANTASIA não pode ser vazio/nulo.';
	end if;
	if (new.email_pizzaria = '' or new.email_pizzaria is null) then
		signal sqlstate '45000' set message_text = 'E-MAIL não pode ser vazio/nulo.';
	ELSE if((new.email_pizzaria is not null or new.email_pizzaria = '')  and skybots_gerencia.function_valida_email(new.email_pizzaria) = 0) then
		signal sqlstate '45000' set message_text = 'E-MAIL inválido.';		
		END IF;
	END IF;
	if (new.perfil_facebook_pizzaria = '' or new.perfil_facebook_pizzaria is null) then
		signal sqlstate '45000' set message_text = 'PERFIL FACEBOOK não pode ser vazio/nulo.';
	end if;
	if (new.telefone_PIZZARIA = '' or new.telefone_PIZZARIA is null) then
		signal sqlstate '45000' set message_text = 'TELEFONE não pode ser vazio/nulo.';
	else IF(skybots_gerencia.function_valida_telefone(new.telefone_PIZZARIA) = 0) then
			signal sqlstate '45000' set message_text = 'TELEFONE inválido.';
		END IF;
	END IF;
	if (new.endereco_pizzaria = '' or new.endereco_pizzaria is null) then
		signal sqlstate '45000' set message_text = 'ENDEREÇO não pode ser vazio/nulo.';
	end if;	
    if (new.numero_endereco_pizzaria = '' or new.numero_endereco_pizzaria is null) then
		signal sqlstate '45000' set message_text = 'NÚMERO DO ENDEREÇO não pode ser vazio/nulo.';
	end if;    
    if (new.cidade_pizzaria < 0 or new.cidade_pizzaria is null) then
		signal sqlstate '45000' set message_text = 'CIDADE não pode ser vazio/nulo.';
	end if;
    if (new.BAIRRO_pizzaria < 0 or new.BAIRRO_pizzaria is null) then
		signal sqlstate '45000' set message_text = 'BAIRRO não pode ser vazio/nulo.';
	end if;
	if (new.uf_pizzaria = '' or new.uf_pizzaria is null) then
		signal sqlstate '45000' set message_text = 'UF não pode ser vazio/nulo.';
	end if;
    if (new.cep_pizzaria = '' or new.cep_pizzaria is null) then
		signal sqlstate '45000' set message_text = 'CEP não pode ser vazio/nulo.';
    ELSE if((new.cep_pizzaria is not null or new.cep_pizzaria = '')  and skybots_gerencia.function_valida_cEP(new.cep_pizzaria) = 0) then
			signal sqlstate '45000' set message_text = 'CEP inválido.';
		end if;   
	END IF;
	if (new.token_facebook_pizzaria = '' or new.token_facebook_pizzaria is null) then
		signal sqlstate '45000' set message_text = 'TOKEN FACEBOOK não pode ser vazio/nulo.';
	end if;	
    if (new.data_hora_inclusao_pizzaria is null ) then
		signal sqlstate '45000' set message_text = 'DATA HORA INCLUSÃO não pode ser vazio/nulo.';
	end if;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `produto_ativador_promocao`
--

CREATE TABLE `produto_ativador_promocao` (
  `codigo_produto_ativador_promocao` bigint(10) NOT NULL,
  `promocao_produto_ativador_promocao` bigint(10) NOT NULL,
  `quantidade_produto_ativador_promocao` bigint(10) NOT NULL DEFAULT '1',
  `bebida_produto_ativador_promocao` bigint(10) DEFAULT NULL,
  `extra_pizza_produto_ativador_promocao` bigint(10) DEFAULT NULL,
  `tipo_extra_pizza_produto_ativador_promocao` bigint(10) DEFAULT NULL,
  `sabor_pizza_produto_ativador_promocao` bigint(10) DEFAULT NULL,
  `tamanho_pizza_produto_ativador_promocao` bigint(10) DEFAULT NULL,
  `valor_pizza_produto_ativador_promocao` bigint(10) DEFAULT NULL,
  `taxa_entrega_produto_ativador_promocao` bigint(10) DEFAULT NULL,
  `pedido_produto_ativador_promocao` bigint(10) DEFAULT NULL COMMENT 'todo o pedido do cliente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `produto_ativador_promocao`
--

INSERT INTO `produto_ativador_promocao` (`codigo_produto_ativador_promocao`, `promocao_produto_ativador_promocao`, `quantidade_produto_ativador_promocao`, `bebida_produto_ativador_promocao`, `extra_pizza_produto_ativador_promocao`, `tipo_extra_pizza_produto_ativador_promocao`, `sabor_pizza_produto_ativador_promocao`, `tamanho_pizza_produto_ativador_promocao`, `valor_pizza_produto_ativador_promocao`, `taxa_entrega_produto_ativador_promocao`, `pedido_produto_ativador_promocao`) VALUES
(28, 23, 1, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, 23, 1, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, 23, 1, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 23, 1, 26, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 23, 1, 27, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 23, 1, 28, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(34, 23, 1, 29, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(35, 23, 1, 30, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 23, 1, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(37, 23, 1, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL),
(38, 23, 1, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL),
(39, 23, 1, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL),
(78, 24, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(105, 27, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(156, 25, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(194, 26, 1, NULL, NULL, NULL, NULL, 23, NULL, NULL, NULL),
(195, 26, 1, NULL, NULL, NULL, 64, NULL, NULL, NULL, NULL),
(196, 26, 1, NULL, NULL, NULL, 66, NULL, NULL, NULL, NULL),
(197, 26, 1, NULL, NULL, NULL, 67, NULL, NULL, NULL, NULL),
(198, 26, 1, NULL, NULL, NULL, 68, NULL, NULL, NULL, NULL),
(199, 26, 1, NULL, NULL, NULL, 69, NULL, NULL, NULL, NULL),
(200, 26, 1, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(201, 26, 1, 33, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `produto_promocao`
--

CREATE TABLE `produto_promocao` (
  `codigo_produto_promocao` bigint(10) NOT NULL,
  `promocao_produto_promocao` bigint(10) NOT NULL,
  `valor_produto_promocao` decimal(10,2) NOT NULL,
  `bebida_produto_promocao` bigint(10) DEFAULT NULL,
  `extra_pizza_produto_promocao` bigint(10) DEFAULT NULL,
  `tipo_extra_pizza_produto_promocao` bigint(10) DEFAULT NULL,
  `sabor_pizza_produto_promocao` bigint(10) DEFAULT NULL,
  `tamanho_pizza_produto_promocao` bigint(10) DEFAULT NULL,
  `valor_pizza_produto_promocao` bigint(10) DEFAULT NULL,
  `taxa_entrega_produto_promocao` bigint(10) DEFAULT NULL,
  `pedido_produto_promocao` bigint(10) DEFAULT NULL COMMENT '	todo o pedido do cliente	',
  `combo_produto_promocao` bigint(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `produto_promocao`
--

INSERT INTO `produto_promocao` (`codigo_produto_promocao`, `promocao_produto_promocao`, `valor_produto_promocao`, `bebida_produto_promocao`, `extra_pizza_produto_promocao`, `tipo_extra_pizza_produto_promocao`, `sabor_pizza_produto_promocao`, `tamanho_pizza_produto_promocao`, `valor_pizza_produto_promocao`, `taxa_entrega_produto_promocao`, `pedido_produto_promocao`, `combo_produto_promocao`) VALUES
(40, 23, '100.00', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(41, 23, '100.00', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(42, 23, '100.00', 26, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(43, 23, '100.00', 27, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(44, 23, '100.00', 28, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(45, 23, '100.00', 29, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(46, 23, '100.00', 30, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(47, 23, '100.00', 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(87, 24, '10.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL),
(114, 27, '10.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL),
(135, 25, '10.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL),
(142, 26, '35.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `promocao`
--

CREATE TABLE `promocao` (
  `codigo_promocao` bigint(10) NOT NULL,
  `nome_promocao` varchar(20) NOT NULL,
  `descricao_promocao` varchar(400) NOT NULL,
  `definicao_promocao` varchar(400) DEFAULT NULL COMMENT '	definição fixa da promoção',
  `data_hora_inicio_promocao` datetime NOT NULL,
  `data_hora_fim_promocao` datetime NOT NULL,
  `frequencia_repeticao_promocao` int(11) DEFAULT NULL COMMENT 'Repete a cada x semanas. 0 - não repete',
  `domingo_repeticao_promocao` tinyint(1) DEFAULT NULL COMMENT '0 - não, 1 - sim',
  `segunda_repeticao_promocao` tinyint(1) DEFAULT NULL COMMENT '0 - não, 1 - sim',
  `terca_repeticao_promocao` tinyint(1) DEFAULT NULL COMMENT '0 - não, 1 - sim',
  `quarta_repeticao_promocao` tinyint(1) DEFAULT NULL COMMENT '0 - não, 1 - sim',
  `quinta_repeticao_promocao` tinyint(1) DEFAULT NULL COMMENT '0 - não, 1 - sim',
  `sexta_repeticao_promocao` tinyint(1) DEFAULT NULL COMMENT '0 - não, 1 - sim',
  `sabado_repeticao_promocao` tinyint(1) DEFAULT NULL COMMENT '0 - não, 1 - sim',
  `tipo_desconto_promocao` tinyint(4) NOT NULL COMMENT '1 - porcentagem, 2 - valor em reais a descontar, 3 - valor em reais novo valor',
  `tipo_produto_promocao` tinyint(4) NOT NULL COMMENT '	1 - bebida, 2 - extra pizza, 3 - tipo extra pizza, 4 - tamanho pizza, 5 - sabor pizza, 6 - valor pizza, 7 - taxa entrega',
  `ativo_promocao` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0 - não, 1 - sim',
  `pizzaria_promocao` bigint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `promocao`
--

INSERT INTO `promocao` (`codigo_promocao`, `nome_promocao`, `descricao_promocao`, `definicao_promocao`, `data_hora_inicio_promocao`, `data_hora_fim_promocao`, `frequencia_repeticao_promocao`, `domingo_repeticao_promocao`, `segunda_repeticao_promocao`, `terca_repeticao_promocao`, `quarta_repeticao_promocao`, `quinta_repeticao_promocao`, `sexta_repeticao_promocao`, `sabado_repeticao_promocao`, `tipo_desconto_promocao`, `tipo_produto_promocao`, `ativo_promocao`, `pizzaria_promocao`) VALUES
(21, 'pizza ganha refri gr', 'pizza ganha refri gratis', NULL, '2018-02-08 06:00:00', '2018-02-28 00:00:00', 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1),
(22, 'pizza ganha refri gr', 'pizza ganha refri gratis', NULL, '2018-02-08 06:00:00', '2018-02-28 00:00:00', 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1),
(23, 'pizza ganha refri gr', 'pizza ganha refri gratis', NULL, '2018-02-08 06:00:00', '2018-02-28 00:00:00', 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 1),
(24, '10% no pedido', '10% no pedido', NULL, '2018-02-09 08:00:00', '2018-04-17 11:55:00', 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 1, 1),
(25, '10% descon no pedido', 'Promoção sexta, sábado e domingo com 10% de desconto (*exceto taxa de entrega*)', 'Promoção sexta, sábado e domingo com 10% de desconto (*exceto taxa de entrega*)', '2018-04-10 00:00:00', '2018-05-30 21:53:00', 2, 1, 0, 0, 0, 0, 1, 1, 1, 0, 1, 4),
(26, 'Pizza Tradic por $35', 'Pizza grande sabor tradicional mais 1 kuat ou fanta por R$35,00', 'Definição da promoção:\r\n Na compra de uma pizza grande com um sabor tradicional (calabresa, portuguesa, maiale) e um refrigerante Fanta ou Kuat sua pizza ficara apenas 39 reais.', '2018-05-03 13:58:00', '2018-05-31 23:59:00', 2, 0, 1, 1, 1, 1, 0, 0, 3, 0, 1, 4),
(27, 'A Tarantino é 10!', 'Faça seu pedido sexta, sábado e domingo por inbox e ganhe 10% de desconto no pedido (exceto taxa de entrega).*   *Promoção exclusiva pelo Messenger.', 'Essa promoção da desconto de X% no pedido com exceção da taxa de entrega.', '2018-04-20 18:00:00', '2018-12-31 23:00:00', 2, 1, 0, 0, 0, 0, 1, 1, 1, 0, 1, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `sabor_pizza`
--

CREATE TABLE `sabor_pizza` (
  `codigo_sabor_pizza` bigint(20) NOT NULL,
  `descricao_sabor_pizza` varchar(200) NOT NULL,
  `ingredientes_sabor_pizza` varchar(80) NOT NULL,
  `pizzaria_sabor_pizza` bigint(20) NOT NULL,
  `ativo_sabor_pizza` tinyint(4) UNSIGNED NOT NULL DEFAULT '1' COMMENT '0 - nao ativo, 1 - sim ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `sabor_pizza`
--

INSERT INTO `sabor_pizza` (`codigo_sabor_pizza`, `descricao_sabor_pizza`, `ingredientes_sabor_pizza`, `pizzaria_sabor_pizza`, `ativo_sabor_pizza`) VALUES
(1, 'Frango ao creme de queijo especial', 'Azeitona, Bacon, Ervilha, Lombinho, Molho de tomate, Mussarela, Orégano, Palmito', 1, 0),
(2, 'Maiale', 'Azeitona, Calabresa, Catupiry, Lombinho, Molho de tomate, Mussarela, Orégano', 1, 0),
(3, 'Marguerita', 'Azeitona, Manjericão, Molho de tomate, Mussarela, Orégano, Tomate', 1, 1),
(4, 'Mineira', 'Azeitona, Bacon, Cebola, Milho, Molho de tomate, Mussarela, Orégano, Ovos, Pimen', 1, 1),
(5, 'Mussarela', 'Azeitona, Molho de tomate, Mussarela, Orégano', 1, 1),
(6, 'Napolitana', 'Azeitona, Molho de tomate, Mussarela, Orégano, Presunto, Tomate', 1, 1),
(7, 'Palmito', 'Azeitona, Molho de tomate, Mussarela, Orégano, Palmito, Tomate', 1, 1),
(8, 'Portuguesa', 'Azeitona, Calabresa, Cebola, Milho, Mussarela, Orégano, Ovos, Presunto', 1, 1),
(9, 'Prestígio', 'Cereja, Chocolate ao Leite, Coco ralado, Leite Condensado, Mussarela', 1, 1),
(10, 'Provolone', 'Azeitona, Molho de tomate, Mussarela, Orégano, Provolone, Tomate', 1, 1),
(11, 'Quatro queijos', 'Azeitona, Catupiry, Molho de tomate, Mussarela, Orégano, Parmesão, Queijo Prato', 1, 1),
(12, 'Quatro Queijos com Alho', 'Alho frito, Azeitona, Catupiry, Molho de tomate, Mussarela, Parmesão, Provolone,', 1, 1),
(13, 'Romanesca', 'Azeitona, Molho de tomate, Mussarela, Orégano, Palmito, Parmesão', 1, 1),
(14, 'Romeu e julieta', 'Goiabada, Leite Condensado, Mussarela', 1, 1),
(15, 'Siciliana', 'Azeitona, Bacon, Champignon, Molho de tomate, Mussarela, Orégano, Pimentão', 1, 1),
(16, 'Strogonoff de Carne', 'Azeitona, Batata palha, Champignon, Molho de tomate, Mussarela, Orégano', 1, 1),
(17, 'Turim', 'Atum, Azeitona, Bacon, Catupiry, Molho de tomate, Mussarela, Orégano', 1, 1),
(18, 'Vegetariana', 'Azeitona, Brócolis, Ervilha, Molho de tomate, Mussarela, Orégano', 1, 1),
(19, 'Brigadeiro', 'Choc. Granulado, Chocolate ao Leite, Leite Condensado, Mussarela', 1, 1),
(20, '15 Esfihas abertas + Kuat 1,5 L', '05 carne, 05 queijo, 05 carioca + 01 Kuat 1,5 L', 2, 1),
(21, '30 Esfihas abertas + Kuat 1,5 L', '10 carne, 10 calabresa, 10 frango catupiry + 01 Kuat 1,5 L', 2, 1),
(22, '40 Esfihas abertas + Kuat 1,5 L', '10 carne, 10 queijo, 10 carioca, 10 napolitana + 01 Kuat 1,5 L', 2, 1),
(23, 'Calabresa', 'Molho, muçarela, calabresa, cebola e orégano', 2, 1),
(25, 'Atum', '	Molho, muçarela, atum, cebola,tomate e orégano', 2, 1),
(26, 'Baianinha apimentada', '	Molho, muçarela, calabresa triturada, pimenta calabresa e orégano', 2, 1),
(29, 'Cheff', '	Molho, muçarela, lombo, calabresa, milho, plamito, bacon, tomate,alho, cebola, ', 2, 1),
(30, 'Frango com Catupiry', '	Molho,muçarela, frango desfiado, catupiry e orégano', 2, 1),
(31, 'Frango com Bacon', '	Molho, muçarela, frango desfiado, bacon e orégano', 2, 1),
(36, 'Milho', '	Molho, muçarela, millho e orégano', 2, 1),
(39, 'Pantaneira', '	Molho, muçarela, catupiry, carne seca, banana da terra frita e orégano', 2, 2),
(40, 'Peito de Peru', '	Molho, muçarela, peito de peru, tomate, cebola e orégano', 2, 1),
(41, 'Picanha Grelhada', '	Molho, muçarela, picanha grelhada, tomate, cebola e orégano', 2, 1),
(42, 'Portuguesa Especial', '	Molho, muçarela, presunto, milho, ovo, cebola, mais muçarela, azeitona e orégan', 2, 1),
(44, 'Sonho de Valsa', '	Muçarela, chocolate meio amargo, chocolate ao leite, chocolate granulado e sonh', 2, 1),
(46, 'Strogonoff de Filé', '	Molho, muçarela, strogonoff de filé e orégano', 2, 1),
(47, 'Strogonoff de Frango', '	Molho, muçarela, strogonoff de frango e orégano', 2, 1),
(48, 'Tarantino', '	Molho, muçarela, Calabresa, bacon, alho desidratado, cebola e orégano', 2, 1),
(49, 'Marguerita', '	Molho muçarela, provolone, tomate, manjericão,azeitona e orégano', 2, 1),
(50, 'Muçarela', '	Molho, muçarela, tomate e orégano', 2, 1),
(51, 'Napolitana', '	Molho, muçarela, provolone, tomate, cebola e orégano', 2, 1),
(52, 'Prestígio', '	Muçarela, chocolate meio amargo e chocolate granulado intercalado com coco rala', 2, 1),
(53, '14 dhgdfh', '5 dffh', 1, 0),
(54, '15 Esfihas abertas + Fanta 1,5 L', '05 carne, 05 queijo, 05 carioca + 01 Fanta 1,5 L', 2, 1),
(55, '30 Esfihas abertas + Fanta1,5 L', '10 carne, 10 calabresa, 10 frango catupiry + 01 Fanta1,5 L', 2, 1),
(56, '40 Esfihas abertas + Fanta 1,5 L', '10 carne, 10 queijo, 10 carioca, 10 napolitana + 01 Fanta 1,5 L', 2, 1),
(57, 'Dom Camilo', 'Molho, muçarela, calabresa triturada, presunto triturado, cr de leite e orégano', 2, 1),
(58, 'Salaminho', 'Molho, muçarela, salaminho fatiado, tomate e orégano', 2, 1),
(59, 'Lombo ao creme', 'Molho, muçarela, lombo defumado, catupiry, tomate e orégano', 2, 1),
(60, 'teste', 'a,b,c,d,e,f', 1, 2),
(61, 'super bacon', 'a,b,c,d,e', 1, 0),
(62, 'carne', 'teest etst et e', 1, 1),
(63, 'frango', 'sg dfhg dfh', 1, 1),
(64, 'Portuguesa', 'Azeitona, Calabresa, Cebola, Milho, Mussarela, Orégano, Ovos, Presunto', 4, 1),
(65, 'novo sabor', 'pao e carne', 1, 2),
(66, 'Maiale', 'presunto', 4, 1),
(67, 'calabresa', 'calabresa', 4, 1),
(68, 'Mineira', 'Azeitona, Bacon, Cebola, Milho, Molho de tomate, Mussarela, Orégano, Ovos, Pimen', 4, 1),
(69, 'Mussarela', 'Azeitona, Molho de tomate, Mussarela, Orégano', 4, 1),
(70, 'Napolitana', 'Azeitona, Molho de tomate, Mussarela, Orégano, Presunto, Tomate', 4, 1),
(71, 'Portuguesa', 'Azeitona, Calabresa, Cebola, Milho, Mussarela, Orégano, Ovos, Presunto', 4, 0),
(72, 'Quatro queijos', 'Azeitona, Catupiry, Molho de tomate, Mussarela, Orégano, Parmesão, Queijo Prato', 4, 1),
(73, 'Strogonoff de Carne', 'Azeitona, Batata palha, Champignon, Molho de tomate, Mussarela, Orégano', 4, 1),
(74, 'Prestígio', 'Cereja, Chocolate ao Leite, Coco ralado, Leite Condensado, Mussarela', 4, 1),
(75, 'Brigadeiro', 'Choc. Granulado, Chocolate ao Leite, Leite Condensado, Mussarela', 4, 1),
(76, 'Vegetariana', 'Azeitona, Brócolis, Ervilha, Molho de tomate, Mussarela, Orégano', 4, 1);

--
-- Acionadores `sabor_pizza`
--
DELIMITER $$
CREATE TRIGGER `trigger_validacao_sabor_pizza_bi` BEFORE INSERT ON `sabor_pizza` FOR EACH ROW begin
	if (new.descricao_sabor_pizza is null or new.descricao_sabor_pizza = '') then
		signal sqlstate '45000' set message_text = 'DESCRIÇÃO não pode ser vazio/nulo.';
	end if;
	if (new.ingredientes_sabor_pizza is null or new.ingredientes_sabor_pizza < 0 ) then
		signal sqlstate '45000' set message_text = 'INGREDIENTES não pode ser vazio/nulo.';
	end if;
    if (new.pizzaria_SABOR_PIZZA is null or new.pizzaria_SABOR_PIZZA < 0 ) then
		signal sqlstate '45000' set message_text = 'PIZZARIA não pode ser vazio/nulo.';
	end if;
	if(new.ativo_sabor_pizza is null or new.ativo_sabor_pizza < 0) then
		signal sqlstate '45000' set message_text = 'ATIVO não pode ser vazio/nulo.';
	else if (new.ativo_sabor_pizza not in (0,1)) then
			signal sqlstate '45000' set message_text = 'ATIVO inválido.';
        end if;
	end if;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trigger_validacao_sabor_pizza_bu` BEFORE UPDATE ON `sabor_pizza` FOR EACH ROW begin
	if (new.descricao_sabor_pizza is null or new.descricao_sabor_pizza = '') then
		signal sqlstate '45000' set message_text = 'DESCRIÇÃO não pode ser vazio/nulo.';
	end if;
	if (new.ingredientes_sabor_pizza is null or new.ingredientes_sabor_pizza < 0 ) then
		signal sqlstate '45000' set message_text = 'INGREDIENTES não pode ser vazio/nulo.';
	end if;
    if (new.pizzaria_SABOR_PIZZA is null or new.pizzaria_SABOR_PIZZA < 0 ) then
		signal sqlstate '45000' set message_text = 'PIZZARIA não pode ser vazio/nulo.';
	end if;
    if(new.ativo_sabor_pizza is null or new.ativo_sabor_pizza < 0) then
		signal sqlstate '45000' set message_text = 'ATIVO não pode ser vazio/nulo.';
	else if (new.ativo_sabor_pizza not in (0,1,2)) then
			signal sqlstate '45000' set message_text = 'ATIVO inválido.';
        end if;
	end if;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `solicitacao_gerencia`
--

CREATE TABLE `solicitacao_gerencia` (
  `codigo_solicitacao_gerencia` bigint(20) NOT NULL,
  `pizzaria_solicitacao_gerencia` bigint(20) NOT NULL,
  `nome_cliente_solicitacao_gerencia` varchar(400) NOT NULL COMMENT 'nome do cliente que solicitou o gerente',
  `cliente_id_facebook_solicitacao_gerencia` varchar(400) NOT NULL COMMENT 'identificador do cliente que é usado pelo chatfuel',
  `data_solicitacao_gerencia` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'data da solicitação para falar com gerente',
  `data_fim_solicitacao_gerencia` timestamp NULL DEFAULT NULL COMMENT 'data hora fim da solicitação do gerente',
  `status_solicitacao_gerencia` int(2) NOT NULL DEFAULT '1' COMMENT '0 terminou, 1 solicitou'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `solicitacao_gerencia`
--

INSERT INTO `solicitacao_gerencia` (`codigo_solicitacao_gerencia`, `pizzaria_solicitacao_gerencia`, `nome_cliente_solicitacao_gerencia`, `cliente_id_facebook_solicitacao_gerencia`, `data_solicitacao_gerencia`, `data_fim_solicitacao_gerencia`, `status_solicitacao_gerencia`) VALUES
(5, 1, 'Alexandre', '1611155025618881', '2018-01-23 01:41:24', '2018-01-23 01:41:24', 0),
(6, 1, 'Alexandre', '1611155025618881', '2018-01-23 01:41:24', '2018-01-23 01:41:24', 0),
(7, 1, 'Alexandre', '1611155025618881', '2018-01-23 04:08:03', '2018-01-23 03:08:03', 0),
(8, 1, 'Alexandre', '1611155025618881', '2018-01-23 04:08:03', '2018-01-23 03:08:03', 0),
(9, 1, 'Alexandre', '1611155025618881', '2018-01-23 04:08:03', '2018-01-23 03:08:03', 0),
(10, 1, 'Alexandre', '1611155025618881', '2018-01-23 04:09:50', '2018-01-23 04:09:50', 0),
(11, 1, 'Alexandre', '1611155025618881', '2018-01-23 12:16:05', '2018-01-23 11:16:05', 0),
(12, 1, 'Sayuri', '1358692287575214', '2018-01-23 12:17:42', '2018-01-23 12:17:42', 0),
(13, 1, 'Sayuri', '1358692287575214', '2018-01-23 12:18:46', '2018-01-23 12:18:46', 0),
(14, 1, 'Sayuri', '1358692287575214', '2018-01-23 14:18:55', '2018-01-23 13:18:55', 0),
(15, 1, 'Alexandre', '1611155025618881', '2018-01-23 14:28:33', '2018-01-23 13:28:33', 0),
(16, 4, 'Alexandre', '1611155025618881', '2018-01-24 13:08:50', '2018-01-24 13:08:50', 0),
(17, 4, 'Alexandre', '1611155025618881', '2018-01-24 14:45:19', '2018-01-24 14:45:19', 0),
(18, 4, 'Sayuri', '1358692287575214', '2018-01-24 14:10:19', '2018-01-24 14:10:19', 0),
(19, 4, 'Sayuri', '1358692287575214', '2018-01-24 14:33:55', '2018-01-24 14:33:55', 0),
(20, 4, 'Sayuri', '1358692287575214', '2018-01-24 14:44:22', '2018-01-24 14:44:22', 0),
(21, 4, 'Sayuri', '1358692287575214', '2018-01-24 14:45:39', '2018-01-24 14:45:39', 0),
(22, 4, 'Alexandre', '1611155025618881', '2018-01-24 14:45:19', '2018-01-24 14:45:19', 0),
(23, 4, 'Alexandre', '1611155025618881', '2018-01-24 15:03:49', '2018-01-24 15:03:49', 0),
(24, 4, 'Sayuri', '1358692287575214', '2018-01-24 14:51:23', '2018-01-24 14:51:23', 0),
(25, 4, 'Sayuri', '1358692287575214', '2018-01-24 14:51:23', '2018-01-24 14:51:23', 0),
(26, 4, 'Alexandre', '1611155025618881', '2018-01-24 16:34:51', '2018-01-24 16:34:51', 0),
(27, 4, 'Alexandre', '1611155025618881', '2018-01-24 16:36:09', '2018-01-24 16:36:09', 0),
(28, 4, 'Alexandre', '1611155025618881', '2018-01-24 17:07:45', NULL, 0),
(29, 1, 'Alexandre', '1466988790061939', '2018-01-24 16:38:14', '2018-01-24 16:38:14', 0),
(30, 1, 'Alexandre', '1466988790061939', '2018-01-24 17:17:17', '2018-01-24 17:09:25', 0),
(31, 4, 'Alexandre', '1611155025618881', '2018-01-24 17:10:34', '2018-01-24 17:10:34', 0),
(32, 4, 'Alexandre', '1611155025618881', '2018-01-24 17:17:24', NULL, 0),
(33, 4, 'Alexandre', '1611155025618881', '2018-01-24 17:17:56', '2018-01-24 16:17:56', 0),
(34, 4, 'Matheus', '1788108977928141', '2018-01-27 01:14:03', '2018-01-27 00:14:03', 0),
(35, 4, 'Sayuri', '1358692287575214', '2018-01-27 01:21:41', '2018-01-27 01:21:41', 0),
(36, 4, 'Matheus', '1788108977928141', '2018-01-27 21:56:27', '2018-01-27 21:56:27', 0),
(37, 4, 'Alexandre', '1611155025618881', '2018-01-29 14:19:04', '2018-01-29 14:19:04', 0),
(38, 4, 'Sayuri', '1358692287575214', '2018-01-31 13:37:52', '2018-01-31 13:37:52', 0),
(39, 2, 'Alexandre', '1466988790061939', '2018-01-31 23:56:14', '2018-01-31 22:56:14', 0),
(40, 2, 'Marcela', '2031327866896307', '2018-02-06 00:40:12', '2018-02-06 00:40:12', 0),
(41, 2, 'Rosemeiry', '1545358058911123', '2018-02-11 00:32:58', '2018-02-10 23:32:58', 0),
(42, 2, 'Suelen', '1768838486501480', '2018-02-11 01:29:29', '2018-02-11 00:29:29', 0),
(43, 2, 'Bianca', '1710864508975612', '2018-02-27 22:56:12', '2018-02-27 22:56:12', 0),
(44, 2, 'Daniela', '1660978990663166', '2018-03-04 21:33:31', '2018-03-04 20:33:31', 0),
(45, 2, 'Joice', '1840736429301371', '2018-03-04 23:06:05', '2018-03-04 22:06:05', 0),
(46, 2, 'Thamires', '1768169543214116', '2018-03-05 00:39:03', '2018-03-05 00:39:03', 0),
(47, 2, 'Isabella', '1760260980720537', '2018-03-09 20:14:40', '2018-03-09 19:14:40', 0),
(48, 2, 'Rosi', '1595862050527150', '2018-03-27 01:45:56', '2018-03-27 01:45:56', 0),
(49, 2, 'Luanaa', '1632034413547212', '2018-03-27 22:47:50', '2018-03-27 22:47:50', 0),
(50, 2, 'Roseni', '1823002681051915', '2018-03-27 23:51:19', '2018-03-27 22:51:19', 0),
(51, 2, 'Victor', '1731354750312884', '2018-03-27 23:58:26', '2018-03-27 23:58:26', 0),
(52, 2, 'William', '1735127753200836', '2018-03-30 01:09:34', '2018-03-30 01:09:34', 0),
(53, 2, 'Bruna', '1642529405823647', '2018-04-03 02:13:05', '2018-04-03 01:13:05', 0),
(54, 2, 'Enivânia', '1920190231326012', '2018-04-10 22:04:26', '2018-04-10 21:04:26', 0),
(55, 2, 'Pati', '1687586024657857', '2018-04-11 01:34:06', '2018-04-11 00:34:06', 0),
(56, 4, 'Sayuri', '1358692287575214', '2018-04-11 21:33:58', '2018-04-11 20:33:58', 0),
(57, 4, 'Alexandre', '1611155025618881', '2018-04-13 16:04:44', '2018-04-13 16:04:44', 0),
(58, 4, 'Alexandre', '1611155025618881', '2018-04-14 02:22:39', '2018-04-14 01:22:39', 0),
(59, 2, 'Pati', '1687586024657857', '2018-04-15 00:32:34', '2018-04-14 23:32:34', 0),
(60, 4, 'Sayuri', '1358692287575214', '2018-04-19 16:08:26', '2018-04-19 16:08:26', 0),
(61, 4, 'Alexandre', '1576473215765981', '2018-04-19 14:28:42', '2018-04-19 14:28:42', 0),
(62, 2, 'Bruna', '1873035822746926', '2018-04-21 00:52:30', '2018-04-21 00:52:30', 0),
(63, 2, 'Bruna', '1873035822746926', '2018-04-21 00:57:02', '2018-04-20 23:57:02', 0),
(64, 2, 'Bruna', '1873035822746926', '2018-04-21 00:57:27', '2018-04-21 00:57:27', 0),
(65, 2, 'Ana Paula Garcia', '1763229777067924', '2018-04-24 02:14:44', '2018-04-24 01:14:44', 0),
(66, 2, 'Sônia Regina', '1667618439954556', '2018-04-24 02:14:44', '2018-04-24 01:14:44', 0),
(67, 2, 'Geórgia', '2368438239836847', '2018-04-29 00:56:44', '2018-04-28 23:56:44', 0),
(68, 2, 'Bertulina', '1595581790539463', '2018-05-01 00:42:05', '2018-04-30 23:42:05', 0),
(69, 2, 'Nathania', '1764744306897371', '2018-05-06 00:50:05', '2018-05-05 23:50:05', 0),
(70, 2, 'Larissa Hellen', '2046775445395675', '2018-05-06 02:27:39', '2018-05-06 01:27:39', 0),
(71, 2, 'Denzel', '1633485276758937', '2018-05-09 00:24:23', '2018-05-09 00:24:23', 0),
(72, 2, 'Silvia', '2158427357505806', '2018-05-17 20:15:47', '2018-05-17 19:15:47', 0),
(73, 2, 'Pamela', '1523887147739041', '2018-05-22 01:57:51', '2018-05-22 00:57:51', 0),
(74, 2, 'Myih', '2325743957451422', '2018-06-02 21:20:20', '2018-06-02 20:20:20', 0),
(75, 2, 'Selma', '2150072648341636', '2018-06-07 01:09:18', '2018-06-07 00:09:18', 0),
(76, 2, 'Milene', '1917680998271373', '2018-06-18 00:13:18', '2018-06-18 00:13:18', 0),
(77, 2, 'Milene', '1917680998271373', '2018-06-18 00:14:40', '2018-06-18 00:14:40', 0),
(78, 2, 'Nadya', '1914331245319807', '2018-06-23 03:06:34', '2018-06-23 02:06:34', 0),
(79, 2, 'Jennyffer', '1699026123544324', '2018-06-25 22:19:12', '2018-06-25 21:19:12', 0),
(80, 2, 'Lucélia', '2071184359576140', '2018-06-26 00:13:11', '2018-06-25 23:13:11', 0),
(81, 2, 'Rosana', '1851931174853372', '2018-07-13 22:42:44', '2018-07-13 22:42:44', 0),
(82, 2, 'Karine', '1967513043293868', '2018-07-15 02:23:40', '2018-07-15 01:23:40', 0),
(83, 2, 'Andressa', '1722161361213004', '2018-07-18 23:24:37', '2018-07-18 22:24:37', 0),
(84, 2, 'Pkn Náná', '2090824904323074', '2018-07-19 22:54:34', '2018-07-19 22:54:34', 0),
(85, 2, 'Pkn Náná', '2090824904323074', '2018-07-20 22:44:31', '2018-07-20 21:44:31', 0),
(86, 2, 'Edsio', '1761973113890651', '2018-07-20 22:44:31', '2018-07-20 21:44:31', 0),
(87, 2, 'Kelem', '1826275127441205', '2018-07-29 23:35:25', '2018-07-29 23:35:25', 0),
(88, 2, 'Mileide', '1870586509666186', '2018-07-31 21:27:06', '2018-07-31 20:27:06', 0),
(89, 2, 'Mileide', '1870586509666186', '2018-08-05 00:00:20', '2018-08-04 23:00:20', 0),
(90, 2, 'Eliamar Araujo', '1676754759100970', '2018-08-05 00:04:19', '2018-08-04 23:04:19', 0),
(91, 2, 'Dayane Dos', '2157602547602697', '2018-08-06 00:27:27', '2018-08-06 00:27:27', 0),
(92, 2, 'Débora', '1979090612111916', '2018-08-19 00:49:48', '2018-08-18 23:49:48', 0),
(93, 2, 'Denah', '1805722089521034', '2018-08-20 23:05:08', '2018-08-20 23:05:08', 0),
(94, 2, 'Denah', '1805722089521034', '2018-08-21 00:05:33', '2018-08-20 23:05:33', 0),
(95, 2, 'Simone', '2320589191314923', '2018-08-26 22:36:42', '2018-08-26 21:36:42', 0),
(96, 2, 'Denah', '1805722089521034', '2018-09-19 23:51:33', '2018-09-19 22:51:33', 0),
(97, 2, 'Thais', '2297358210278944', '2018-09-20 00:03:34', '2018-09-19 23:03:34', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tamanho_pizza`
--

CREATE TABLE `tamanho_pizza` (
  `codigo_tamanho_pizza` bigint(20) NOT NULL,
  `quantidade_sabor_tamanho_pizza` bigint(20) UNSIGNED NOT NULL DEFAULT '1' COMMENT '0 - ilimitado',
  `quantidade_fatias_tamanho_pizza` bigint(20) UNSIGNED NOT NULL COMMENT 'quantidade de fatias que esse tamanho possui',
  `descricao_tamanho_pizza` varchar(400) NOT NULL,
  `pizzaria_tamanho_pizza` bigint(20) NOT NULL,
  `ativo_tamanho_pizza` tinyint(4) UNSIGNED NOT NULL DEFAULT '1' COMMENT '0 - nao ativo, 1 - sim ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tamanho_pizza`
--

INSERT INTO `tamanho_pizza` (`codigo_tamanho_pizza`, `quantidade_sabor_tamanho_pizza`, `quantidade_fatias_tamanho_pizza`, `descricao_tamanho_pizza`, `pizzaria_tamanho_pizza`, `ativo_tamanho_pizza`) VALUES
(1, 1, 2, 'BABY', 1, 0),
(2, 2, 4, 'MEDIA', 1, 1),
(3, 4, 8, 'GRANDE', 1, 1),
(4, 4, 8, 'GIGANTE', 1, 1),
(5, 4, 8, 'Pizza Quadrada - 35x35 cm', 2, 1),
(6, 4, 8, 'Pizza Quadrada Doce - 35x35 cm', 2, 1),
(7, 4, 16, 'Pizza Quadrada Gigante - 45x45 cm', 2, 0),
(9, 2, 8, 'Pizza Redonda', 2, 1),
(10, 2, 8, 'Pizza Redonda doce', 2, 1),
(11, 1, 1, 'Kits promocionais - Esfihas abertas', 2, 1),
(18, 4, 16, 'Quadrada G doce', 2, 2),
(19, 1, 1, 'Kits promocionais - Esfihas abertas', 2, 2),
(22, 4, 12, 'MEGA', 1, 2),
(23, 4, 8, 'Pizza Grande', 4, 1),
(24, 4, 8, 'Pizza Quadrada Gigante - 45x45 cm', 1, 1),
(25, 2, 4, 'Pizza media', 4, 1),
(26, 6, 12, 'Pizza gigante', 4, 1);

--
-- Acionadores `tamanho_pizza`
--
DELIMITER $$
CREATE TRIGGER `trigger_validacao_tamanho_pizza_bi` BEFORE INSERT ON `tamanho_pizza` FOR EACH ROW BEGIN
	if (new.descricao_tamanho_pizza is null or new.descricao_tamanho_pizza = '') then
		signal sqlstate '45000' set message_text = 'DESCRIÇÃO não pode ser vazio/nulo.';
	end if;
	if (new.quantidade_sabor_tamanho_pizza is null or new.quantidade_sabor_tamanho_pizza < 0 ) then
		signal sqlstate '45000' set message_text = 'QUANTIDADE deve ser maior que 0.';
	end if;
    if (new.quantidade_fatias_tamanho_pizza is null or new.quantidade_fatias_tamanho_pizza < 1 ) then
		signal sqlstate '45000' set message_text = 'QUANTIDADE de fatias deve ser maior que 0.';
	end if;
    if (new.pizzaria_tamanho_PIZZA is null or new.pizzaria_tamanho_PIZZA < 0 ) then
		signal sqlstate '45000' set message_text = 'PIZZARIA não pode ser vazio/nulo.';
	end if;
    if(new.ativo_tamanho_pizza is null or new.ativo_tamanho_pizza < 0) then
		signal sqlstate '45000' set message_text = 'ATIVO não pode ser vazio/nulo.';
	else if (new.ativo_tamanho_pizza not in (0,1)) then
			signal sqlstate '45000' set message_text = 'ATIVO inválido.';
        end if;
	end if;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trigger_validacao_tamanho_pizza_bu` BEFORE UPDATE ON `tamanho_pizza` FOR EACH ROW BEGIN
	if (new.descricao_tamanho_pizza is null or new.descricao_tamanho_pizza = '') then
		signal sqlstate '45000' set message_text = 'DESCRIÇÃO não pode ser vazio/nulo.';
	end if;
	if (new.quantidade_sabor_tamanho_pizza is null or new.quantidade_sabor_tamanho_pizza < 0 ) then
		signal sqlstate '45000' set message_text = 'QUANTIDADE deve ser maior que 0.';
	end if;
    if (new.quantidade_fatias_tamanho_pizza is null or new.quantidade_fatias_tamanho_pizza < 1 ) then
		signal sqlstate '45000' set message_text = 'QUANTIDADE de fatias deve ser maior que 0.';
	end if;
    if (new.pizzaria_tamanho_PIZZA is null or new.pizzaria_tamanho_PIZZA < 0 ) then
		signal sqlstate '45000' set message_text = 'PIZZARIA não pode ser vazio/nulo.';
	end if;
    if(new.ativo_tamanho_pizza is null or new.ativo_tamanho_pizza < 0) then
		signal sqlstate '45000' set message_text = 'ATIVO não pode ser vazio/nulo.';
	else if (new.ativo_tamanho_pizza not in (0,1,2)) then
			signal sqlstate '45000' set message_text = 'ATIVO inválido.';
        end if;
	end if;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `taxa_entrega`
--

CREATE TABLE `taxa_entrega` (
  `codigo_taxa_entrega` bigint(20) NOT NULL,
  `bairro_taxa_entrega` bigint(20) NOT NULL,
  `preco_taxa_entrega` decimal(10,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `ativo_taxa_entrega` tinyint(4) UNSIGNED NOT NULL DEFAULT '1' COMMENT '0 - não entrega, 1 - sim entrega',
  `pizzaria_taxa_entrega` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `taxa_entrega`
--

INSERT INTO `taxa_entrega` (`codigo_taxa_entrega`, `bairro_taxa_entrega`, `preco_taxa_entrega`, `ativo_taxa_entrega`, `pizzaria_taxa_entrega`) VALUES
(37, 47, '10.00', 1, 1),
(38, 51, '10.00', 1, 1),
(39, 102, '10.00', 1, 1),
(40, 4, '10.00', 2, 1),
(41, 5, '10.00', 2, 1),
(42, 6, '10.00', 1, 1),
(43, 56, '10.00', 1, 1),
(44, 57, '10.00', 1, 1),
(45, 58, '10.00', 1, 1),
(46, 59, '10.00', 1, 1),
(47, 60, '10.00', 1, 1),
(48, 1, '9.00', 0, 1),
(49, 63, '10.00', 1, 1),
(50, 63, '10.00', 0, 1),
(51, 64, '10.00', 1, 1),
(52, 7, '10.00', 1, 1),
(53, 2, '10.00', 1, 1),
(54, 8, '10.00', 1, 1),
(55, 9, '10.00', 1, 1),
(56, 10, '10.00', 1, 1),
(57, 103, '10.00', 1, 1),
(58, 11, '10.00', 1, 1),
(59, 104, '10.00', 1, 1),
(60, 105, '10.00', 1, 1),
(61, 81, '10.00', 1, 1),
(62, 12, '10.00', 1, 1),
(63, 53, '10.00', 1, 1),
(64, 55, '10.00', 1, 1),
(65, 89, '10.00', 1, 1),
(66, 100, '10.00', 1, 1),
(67, 65, '10.00', 1, 1),
(68, 66, '10.00', 1, 1),
(69, 52, '10.00', 1, 1),
(70, 54, '10.00', 1, 1),
(71, 46, '10.00', 1, 1),
(72, 13, '10.00', 1, 1),
(73, 14, '10.00', 1, 1),
(74, 67, '10.00', 1, 1),
(75, 68, '10.00', 1, 1),
(76, 37, '10.00', 1, 1),
(77, 15, '10.00', 1, 1),
(78, 50, '10.00', 1, 1),
(79, 70, '10.00', 1, 1),
(80, 106, '10.00', 1, 1),
(81, 16, '10.00', 1, 1),
(82, 69, '10.00', 1, 1),
(83, 112, '10.00', 1, 1),
(84, 110, '10.00', 1, 1),
(85, 71, '5.00', 1, 1),
(86, 72, '10.00', 1, 1),
(87, 34, '10.00', 1, 1),
(88, 107, '10.00', 1, 1),
(89, 73, '10.00', 1, 1),
(90, 108, '10.00', 1, 1),
(91, 109, '10.00', 1, 1),
(92, 74, '10.00', 1, 1),
(93, 75, '10.00', 1, 1),
(94, 17, '10.00', 1, 1),
(95, 20, '10.00', 1, 1),
(96, 111, '10.00', 1, 1),
(97, 113, '10.00', 1, 1),
(98, 76, '10.00', 1, 1),
(99, 77, '10.00', 1, 1),
(100, 114, '10.00', 1, 1),
(101, 21, '10.00', 1, 1),
(102, 18, '10.00', 1, 1),
(103, 78, '10.00', 1, 1),
(104, 79, '10.00', 1, 1),
(105, 19, '10.00', 1, 1),
(106, 48, '10.00', 1, 1),
(107, 35, '10.00', 1, 1),
(108, 80, '10.00', 1, 1),
(109, 36, '10.00', 1, 1),
(110, 138, '10.00', 0, 1),
(111, 115, '10.00', 1, 1),
(112, 116, '10.00', 1, 1),
(113, 3, '15.00', 2, 1),
(114, 38, '10.00', 1, 1),
(115, 82, '10.00', 1, 1),
(116, 117, '10.00', 1, 1),
(117, 49, '10.00', 1, 1),
(118, 39, '10.00', 1, 1),
(119, 118, '10.00', 1, 1),
(120, 22, '10.00', 1, 1),
(121, 84, '10.00', 1, 1),
(122, 83, '10.00', 1, 1),
(123, 119, '10.00', 1, 1),
(124, 40, '10.00', 1, 1),
(125, 23, '10.00', 1, 1),
(126, 120, '10.00', 1, 1),
(127, 43, '10.00', 1, 1),
(128, 86, '10.00', 1, 1),
(129, 121, '10.00', 1, 1),
(130, 122, '10.00', 1, 1),
(131, 123, '10.00', 1, 1),
(132, 125, '10.00', 1, 1),
(133, 124, '10.00', 1, 1),
(134, 24, '10.00', 1, 1),
(135, 126, '10.00', 1, 1),
(136, 126, '10.00', 1, 1),
(137, 127, '10.00', 1, 1),
(138, 87, '10.00', 1, 1),
(139, 88, '10.00', 1, 1),
(140, 85, '10.00', 1, 1),
(141, 41, '10.00', 1, 1),
(142, 92, '10.00', 1, 1),
(143, 25, '10.00', 1, 1),
(144, 26, '10.00', 1, 1),
(145, 90, '10.00', 1, 1),
(146, 91, '10.00', 1, 1),
(147, 27, '10.00', 1, 1),
(148, 128, '10.00', 1, 1),
(149, 93, '10.00', 1, 1),
(150, 44, '10.00', 1, 1),
(151, 94, '10.00', 1, 1),
(152, 95, '10.00', 1, 1),
(153, 28, '10.00', 1, 1),
(154, 29, '10.00', 1, 1),
(155, 96, '10.00', 1, 1),
(156, 129, '10.00', 1, 1),
(157, 30, '10.00', 1, 1),
(158, 31, '10.00', 1, 1),
(159, 135, '10.00', 1, 1),
(160, 130, '10.00', 1, 1),
(161, 131, '10.00', 1, 1),
(162, 132, '10.00', 1, 1),
(163, 133, '10.00', 1, 1),
(164, 62, '10.00', 1, 1),
(165, 97, '10.00', 0, 1),
(166, 134, '10.00', 1, 1),
(167, 45, '10.00', 1, 1),
(168, 98, '10.00', 1, 1),
(169, 32, '10.00', 1, 1),
(170, 99, '10.00', 1, 1),
(171, 136, '10.00', 1, 1),
(172, 42, '10.00', 1, 1),
(173, 101, '10.00', 1, 1),
(174, 33, '10.00', 1, 1),
(175, 137, '10.00', 0, 1),
(176, 1, '10.00', 2, 1),
(177, 138, '10.00', 1, 1),
(178, 137, '10.00', 1, 1),
(179, 4, '5.00', 1, 2),
(180, 5, '5.00', 1, 2),
(181, 6, '7.00', 1, 2),
(182, 7, '7.00', 1, 2),
(183, 8, '7.00', 1, 2),
(184, 9, '7.00', 1, 2),
(185, 10, '7.00', 1, 2),
(186, 11, '8.00', 1, 2),
(187, 12, '7.00', 1, 2),
(188, 13, '7.00', 1, 2),
(189, 14, '7.00', 1, 2),
(190, 15, '7.00', 1, 2),
(191, 16, '7.00', 1, 2),
(192, 17, '8.00', 1, 2),
(193, 18, '7.00', 1, 2),
(194, 20, '8.00', 1, 2),
(195, 21, '8.00', 1, 2),
(196, 25, '7.00', 1, 2),
(197, 26, '7.00', 1, 2),
(198, 27, '7.00', 1, 2),
(199, 29, '10.00', 1, 2),
(200, 30, '7.00', 1, 2),
(201, 31, '8.00', 1, 2),
(202, 33, '7.00', 1, 2),
(203, 2, '5.00', 1, 2),
(204, 34, '8.00', 1, 2),
(205, 36, '8.00', 1, 2),
(206, 37, '7.00', 1, 2),
(207, 3, '5.00', 1, 2),
(208, 38, '5.00', 1, 2),
(209, 39, '7.00', 1, 2),
(210, 41, '7.00', 1, 2),
(211, 42, '7.00', 1, 2),
(212, 43, '7.00', 1, 2),
(213, 44, '7.00', 1, 2),
(214, 45, '5.00', 1, 2),
(215, 46, '5.00', 1, 2),
(216, 47, '5.00', 1, 2),
(217, 48, '8.00', 1, 2),
(218, 49, '7.00', 1, 2),
(219, 50, '5.00', 1, 2),
(220, 51, '5.00', 1, 2),
(221, 52, '5.00', 1, 2),
(222, 53, '5.00', 1, 2),
(223, 54, '5.00', 1, 2),
(224, 55, '5.00', 1, 2),
(225, 56, '7.00', 1, 2),
(226, 57, '5.00', 1, 2),
(227, 58, '5.00', 1, 2),
(228, 59, '5.00', 1, 2),
(229, 60, '5.00', 1, 2),
(230, 62, '7.00', 1, 2),
(231, 63, '5.00', 1, 2),
(232, 64, '5.00', 1, 2),
(233, 65, '5.00', 1, 2),
(234, 66, '5.00', 1, 2),
(235, 67, '7.00', 1, 2),
(236, 68, '5.00', 1, 2),
(237, 69, '5.00', 1, 2),
(238, 70, '7.00', 1, 2),
(239, 71, '5.00', 1, 2),
(240, 72, '7.00', 1, 2),
(241, 73, '5.00', 1, 2),
(242, 74, '5.00', 1, 2),
(243, 75, '5.00', 1, 2),
(244, 76, '7.00', 1, 2),
(245, 77, '5.00', 1, 2),
(246, 78, '7.00', 1, 2),
(247, 79, '5.00', 1, 2),
(248, 80, '5.00', 1, 2),
(249, 81, '5.00', 1, 2),
(250, 82, '5.00', 1, 2),
(251, 83, '5.00', 1, 2),
(252, 84, '5.00', 1, 2),
(253, 85, '5.00', 1, 2),
(254, 86, '7.00', 1, 2),
(255, 87, '5.00', 1, 2),
(256, 88, '5.00', 1, 2),
(257, 89, '5.00', 1, 2),
(258, 90, '7.00', 1, 2),
(259, 91, '7.00', 1, 2),
(260, 92, '5.00', 1, 2),
(261, 93, '5.00', 1, 2),
(262, 94, '5.00', 1, 2),
(263, 95, '5.00', 1, 2),
(264, 96, '5.00', 1, 2),
(265, 97, '5.00', 1, 2),
(266, 98, '5.00', 1, 2),
(267, 99, '5.00', 1, 2),
(268, 100, '8.00', 1, 2),
(269, 101, '5.00', 1, 2),
(270, 102, '8.00', 1, 2),
(271, 103, '8.00', 1, 2),
(272, 104, '7.00', 1, 2),
(273, 108, '7.00', 1, 2),
(274, 110, '7.00', 1, 2),
(275, 122, '8.00', 1, 2),
(276, 123, '8.00', 1, 2),
(277, 136, '7.00', 1, 2),
(278, 1, '7.00', 1, 2),
(279, 4, '10.00', 2, 1),
(280, 5, '10.00', 0, 1),
(281, 97, '10.00', 1, 1),
(282, 4, '10.00', 1, 1),
(283, 3, '1.00', 1, 1),
(284, 5, '10.00', 1, 1),
(285, 139, '5.00', 1, 2),
(286, 140, '5.00', 1, 2),
(287, 141, '5.00', 1, 2),
(288, 142, '5.00', 1, 2),
(289, 149, '8.00', 1, 2),
(290, 143, '7.00', 1, 2),
(291, 144, '7.00', 1, 2),
(292, 145, '7.00', 1, 2),
(293, 152, '5.00', 1, 2),
(294, 155, '5.00', 1, 2),
(295, 157, '10.00', 1, 2),
(296, 170, '7.00', 1, 2),
(297, 171, '5.00', 1, 2),
(298, 172, '5.00', 1, 2),
(299, 181, '8.00', 1, 2),
(300, 189, '7.00', 1, 2),
(301, 192, '5.00', 1, 2),
(302, 195, '5.00', 1, 2),
(303, 201, '8.00', 1, 2),
(304, 202, '7.00', 1, 2),
(305, 208, '5.00', 1, 2),
(306, 210, '7.00', 1, 2),
(307, 211, '7.00', 1, 2),
(308, 214, '7.00', 1, 2),
(309, 215, '7.00', 1, 2),
(310, 216, '7.00', 1, 2),
(311, 217, '7.00', 1, 2),
(312, 223, '5.00', 1, 2),
(313, 226, '8.00', 1, 2),
(314, 140, '5.00', 1, 1),
(315, 139, '11.00', 1, 1),
(316, 146, '10.00', 2, 1),
(317, 146, '10.00', 2, 1),
(318, 146, '10.00', 0, 1),
(319, 147, '10.00', 1, 1),
(320, 4, '15.00', 1, 4),
(321, 139, '10.00', 1, 4),
(322, 1, '10.00', 1, 4),
(323, 38, '10.00', 1, 4),
(324, 192, '10.00', 1, 4),
(325, 47, '10.00', 1, 4),
(326, 140, '10.00', 1, 4),
(327, 89, '10.00', 1, 4),
(328, 5, '10.00', 1, 4),
(329, 141, '10.00', 1, 4),
(330, 3, '10.00', 1, 4),
(331, 51, '10.00', 1, 4),
(332, 146, '10.00', 1, 4),
(333, 7, '10.00', 1, 4),
(334, 105, '10.00', 1, 4),
(335, 81, '10.00', 2, 4),
(336, 10, '10.00', 1, 4),
(337, 152, '10.00', 1, 4),
(338, 2, '10.00', 1, 4),
(339, 148, '10.00', 2, 1),
(340, 148, '10.00', 1, 1),
(341, 205, '10.00', 1, 1),
(342, 141, '10.00', 1, 1),
(343, 152, '10.00', 1, 1),
(344, 192, '10.00', 1, 1),
(345, 102, '5.00', 1, 4),
(346, 52, '5.00', 1, 4),
(347, 53, '5.00', 1, 4),
(348, 147, '5.00', 1, 4),
(349, 148, '5.00', 1, 4),
(350, 57, '5.00', 1, 4),
(351, 56, '5.00', 1, 4),
(352, 60, '5.00', 1, 4),
(353, 55, '5.00', 1, 4),
(354, 58, '5.00', 1, 4),
(355, 6, '5.00', 1, 4),
(356, 150, '5.00', 1, 4),
(357, 151, '5.00', 1, 4),
(358, 59, '5.00', 1, 4),
(359, 64, '5.00', 1, 4),
(360, 8, '5.00', 1, 4),
(361, 153, '5.00', 1, 4),
(362, 63, '5.00', 1, 4),
(363, 103, '5.00', 1, 4),
(364, 154, '5.00', 1, 4),
(365, 155, '5.00', 1, 4),
(366, 156, '5.00', 1, 4),
(367, 157, '5.00', 1, 4),
(368, 54, '5.00', 1, 4),
(369, 159, '5.00', 1, 4),
(370, 160, '111.00', 1, 4),
(371, 104, '5.00', 1, 4),
(372, 158, '5.00', 1, 4),
(373, 161, '5.00', 1, 4),
(374, 65, '5.00', 1, 4),
(375, 162, '5.00', 1, 4),
(376, 9, '5.00', 1, 4),
(377, 11, '5.00', 1, 4),
(378, 12, '5.00', 1, 4),
(379, 46, '5.00', 1, 4),
(380, 66, '5.00', 1, 4),
(381, 142, '5.00', 1, 4),
(382, 13, '5.00', 1, 4),
(383, 14, '5.00', 1, 4),
(384, 67, '5.00', 1, 4),
(385, 68, '5.00', 1, 4),
(386, 163, '5.00', 1, 4),
(387, 137, '5.00', 1, 4),
(388, 166, '5.00', 1, 4),
(389, 165, '5.00', 1, 4),
(390, 206, '5.00', 1, 4),
(391, 82, '5.00', 1, 4),
(392, 37, '5.00', 1, 4),
(393, 15, '5.00', 1, 4),
(394, 50, '5.00', 1, 4),
(395, 106, '5.00', 1, 4),
(396, 70, '5.00', 1, 4),
(397, 227, '5.00', 1, 4),
(398, 225, '5.00', 1, 4),
(399, 94, '5.00', 1, 4),
(400, 88, '5.00', 1, 4),
(401, 116, '5.00', 1, 4),
(402, 125, '5.00', 1, 4),
(403, 126, '5.00', 1, 4),
(404, 131, '5.00', 1, 4),
(405, 119, '5.00', 1, 4),
(406, 28, '5.00', 1, 4),
(407, 91, '5.00', 1, 4),
(408, 97, '5.00', 1, 4),
(409, 128, '5.00', 1, 4),
(410, 86, '5.00', 1, 4),
(411, 134, '5.00', 1, 4),
(412, 62, '5.00', 1, 4),
(413, 208, '5.00', 1, 4),
(414, 133, '5.00', 1, 4),
(415, 132, '5.00', 1, 4),
(416, 32, '5.00', 1, 4),
(417, 135, '5.00', 1, 4),
(418, 222, '5.00', 1, 4),
(419, 100, '5.00', 1, 4),
(420, 45, '5.00', 1, 4),
(421, 221, '5.00', 1, 4),
(422, 136, '5.00', 1, 4),
(423, 33, '5.00', 1, 4),
(424, 101, '5.00', 1, 4),
(425, 223, '5.00', 1, 4),
(426, 130, '5.00', 1, 4),
(427, 220, '5.00', 1, 4),
(428, 218, '5.00', 1, 4),
(429, 30, '5.00', 1, 4),
(430, 226, '5.00', 1, 4),
(431, 42, '5.00', 1, 4),
(432, 31, '5.00', 1, 4),
(433, 29, '5.00', 1, 4),
(434, 87, '5.00', 1, 4),
(435, 216, '5.00', 1, 4),
(436, 224, '5.00', 1, 4),
(437, 99, '5.00', 1, 4),
(438, 98, '5.00', 1, 4),
(439, 129, '5.00', 1, 4),
(440, 219, '5.00', 1, 4),
(441, 217, '5.00', 1, 4),
(442, 96, '5.00', 1, 4),
(443, 215, '5.00', 1, 4),
(444, 120, '5.00', 1, 4),
(445, 181, '5.00', 1, 4),
(446, 115, '5.00', 1, 4),
(447, 182, '5.00', 1, 4),
(448, 183, '5.00', 1, 4),
(449, 184, '5.00', 1, 4),
(450, 191, '5.00', 1, 4),
(451, 117, '5.00', 1, 4),
(452, 26, '5.00', 1, 4),
(453, 190, '5.00', 1, 4),
(454, 25, '5.00', 1, 4),
(455, 127, '5.00', 1, 4),
(456, 85, '5.00', 1, 4),
(457, 195, '5.00', 1, 4),
(458, 118, '5.00', 1, 4),
(459, 124, '5.00', 1, 4),
(460, 185, '20.00', 1, 4),
(461, 186, '5.00', 1, 4),
(462, 36, '5.00', 1, 4),
(463, 22, '5.00', 1, 4),
(464, 93, '5.00', 1, 4),
(465, 27, '5.00', 1, 4),
(466, 194, '5.00', 1, 4),
(467, 43, '5.00', 1, 4),
(468, 40, '5.00', 1, 4),
(469, 90, '5.00', 1, 4),
(470, 81, '1.00', 1, 4),
(471, 244, '12.00', 1, 4),
(472, 35, '8.00', 1, 2);

--
-- Acionadores `taxa_entrega`
--
DELIMITER $$
CREATE TRIGGER `trigger_validacao_taxa_entrega_bi` BEFORE INSERT ON `taxa_entrega` FOR EACH ROW BEGIN
	if(new.bairro_TAXA_ENTREGA = '' or new.bairro_TAXA_ENTREGA is null) then
    	signal sqlstate '45000' set message_text = 'BAIRRO não pode ser vazio/nulo.';
	END IF;
    if (new.PRECO_TAXA_ENTREGA is null or new.PRECO_TAXA_ENTREGA < 0 ) then
		signal sqlstate '45000' set message_text = 'PREÇO deve ser maior que 0.';
	end if;
    if(new.ativo_taxa_entrega is null or new.ativo_taxa_entrega < 0) then
		signal sqlstate '45000' set message_text = 'ATIVO não pode ser vazio/nulo.';
	else if (new.ativo_taxa_entrega not in (0,1)) then
			signal sqlstate '45000' set message_text = 'ATIVO inválido.';
        end if;
	end if;
    if(new.pizzaria_taxa_entrega = '' or new.pizzaria_taxa_entrega is null) then
    	signal sqlstate '45000' set message_text = 'PIZZARIA não pode ser vazio/nulo.';
	END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trigger_validacao_taxa_entrega_bu` BEFORE UPDATE ON `taxa_entrega` FOR EACH ROW BEGIN
	if(new.bairro_TAXA_ENTREGA = '' or new.bairro_TAXA_ENTREGA is null) then
    	signal sqlstate '45000' set message_text = 'BAIRRO não pode ser vazio/nulo.';
	END IF;
    if (new.PRECO_TAXA_ENTREGA is null or new.PRECO_TAXA_ENTREGA < 0 ) then
		signal sqlstate '45000' set message_text = 'PREÇO deve ser maior que 0.';
	end if;
    if(new.ativo_taxa_entrega is null or new.ativo_taxa_entrega < 0) then
		signal sqlstate '45000' set message_text = 'ATIVO não pode ser vazio/nulo.';
	else if (new.ativo_taxa_entrega not in (0,1,2)) then
			signal sqlstate '45000' set message_text = 'ATIVO inválido.';
        end if;
	end if;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipo_extra_pizza`
--

CREATE TABLE `tipo_extra_pizza` (
  `codigo_tipo_extra_pizza` bigint(20) NOT NULL,
  `quantidade_tipo_extra_pizza` bigint(20) UNSIGNED NOT NULL COMMENT '0 - ilimitado',
  `descricao_tipo_extra_pizza` varchar(400) CHARACTER SET latin1 NOT NULL,
  `pizzaria_tipo_extra_pizza` bigint(20) NOT NULL,
  `ativo_tipo_extra_pizza` tinyint(4) UNSIGNED NOT NULL DEFAULT '1' COMMENT '0 - nao ativo, 1 - sim ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tipo_extra_pizza`
--

INSERT INTO `tipo_extra_pizza` (`codigo_tipo_extra_pizza`, `quantidade_tipo_extra_pizza`, `descricao_tipo_extra_pizza`, `pizzaria_tipo_extra_pizza`, `ativo_tipo_extra_pizza`) VALUES
(1, 1, 'BORDA RECHEADA', 1, 1),
(2, 2, 'ADICIONAL', 1, 1),
(3, 1, 'MASSA', 1, 1),
(5, 1, 'Borda recheada', 2, 1),
(6, 1, 'BORDA RECHEADA', 4, 1),
(7, 2, 'ADICIONAL', 4, 1),
(8, 1, 'MASSA', 4, 1),
(9, 1, 'borda recheada', 4, 0);

--
-- Acionadores `tipo_extra_pizza`
--
DELIMITER $$
CREATE TRIGGER `trigger_validacao_tipo_extra_pizza_bi` BEFORE INSERT ON `tipo_extra_pizza` FOR EACH ROW BEGIN
	if (new.descricao_tipo_extra_pizza is null or new.descricao_tipo_extra_pizza = '') then
		signal sqlstate '45000' set message_text = 'DESCRIÇÃO não pode ser vazio/nulo.';
	end if;
	if (new.quantidade_tipo_extra_pizza is null or new.quantidade_tipo_extra_pizza < 0 ) then
		signal sqlstate '45000' set message_text = 'QUANTIDADE deve ser maior que 0.';
	end if;
    if (new.pizzaria_tipo_extra_PIZZA is null or new.pizzaria_tipo_extra_PIZZA < 0 ) then
		signal sqlstate '45000' set message_text = 'PIZZARIA não pode ser vazio/nulo.';
	end if;
    if(new.ativo_tipo_extra_pizza is null or new.ativo_tipo_extra_pizza < 0) then
		signal sqlstate '45000' set message_text = 'ATIVO não pode ser vazio/nulo.';
	else if (new.ativo_tipo_extra_pizza not in (0,1)) then
			signal sqlstate '45000' set message_text = 'ATIVO inválido.';
        end if;
	end if;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trigger_validacao_tipo_extra_pizza_bu` BEFORE UPDATE ON `tipo_extra_pizza` FOR EACH ROW BEGIN
	if (new.descricao_tipo_extra_pizza is null or new.descricao_tipo_extra_pizza = '') then
		signal sqlstate '45000' set message_text = 'DESCRIÇÃO não pode ser vazio/nulo.';
	end if;
	if (new.quantidade_tipo_extra_pizza is null or new.quantidade_tipo_extra_pizza < 0 ) then
		signal sqlstate '45000' set message_text = 'QUANTIDADE deve ser maior que 0.';
	end if;
    if (new.pizzaria_tipo_extra_PIZZA is null or new.pizzaria_tipo_extra_PIZZA < 0 ) then
		signal sqlstate '45000' set message_text = 'PIZZARIA não pode ser vazio/nulo.';
	end if;
    if(new.ativo_tipo_extra_pizza is null or new.ativo_tipo_extra_pizza < 0) then
		signal sqlstate '45000' set message_text = 'ATIVO não pode ser vazio/nulo.';
	else if (new.ativo_tipo_extra_pizza not in (0,1)) then
			signal sqlstate '45000' set message_text = 'ATIVO inválido.';
        end if;
	end if;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `valor_pizza`
--

CREATE TABLE `valor_pizza` (
  `codigo_valor_pizza` bigint(20) NOT NULL,
  `tamanho_pizza_valor_pizza` bigint(20) NOT NULL,
  `sabor_pizza_valor_pizza` bigint(20) NOT NULL,
  `preco_valor_pizza` decimal(10,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `pizzaria_valor_pizza` bigint(20) NOT NULL,
  `ativo_valor_pizza` tinyint(4) UNSIGNED NOT NULL DEFAULT '1' COMMENT '0 - nao ativo, 1 - sim ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `valor_pizza`
--

INSERT INTO `valor_pizza` (`codigo_valor_pizza`, `tamanho_pizza_valor_pizza`, `sabor_pizza_valor_pizza`, `preco_valor_pizza`, `pizzaria_valor_pizza`, `ativo_valor_pizza`) VALUES
(1, 6, 1, '44.00', 1, 1),
(2, 4, 2, '44.00', 1, 1),
(3, 4, 3, '35.00', 1, 1),
(4, 4, 4, '37.00', 1, 1),
(5, 4, 5, '35.00', 1, 1),
(6, 4, 6, '37.00', 1, 1),
(7, 4, 7, '35.00', 1, 1),
(8, 4, 8, '37.00', 1, 1),
(9, 4, 9, '37.00', 1, 1),
(10, 4, 10, '37.00', 1, 1),
(11, 4, 11, '42.00', 1, 1),
(12, 4, 12, '42.00', 1, 1),
(13, 4, 13, '35.00', 1, 1),
(14, 4, 14, '37.00', 1, 1),
(15, 4, 15, '37.00', 1, 1),
(16, 4, 16, '44.00', 1, 1),
(17, 4, 17, '42.00', 1, 1),
(18, 4, 18, '37.00', 1, 1),
(19, 1, 6, '16.00', 1, 0),
(20, 3, 2, '36.00', 1, 1),
(21, 3, 3, '28.00', 1, 1),
(22, 3, 4, '30.00', 1, 1),
(23, 3, 5, '28.00', 1, 1),
(24, 3, 6, '30.00', 1, 1),
(25, 3, 7, '28.00', 1, 1),
(26, 3, 8, '30.00', 1, 1),
(27, 3, 9, '30.00', 1, 1),
(28, 3, 10, '30.00', 1, 1),
(29, 3, 11, '34.00', 1, 1),
(30, 3, 12, '34.00', 1, 1),
(31, 3, 13, '28.00', 1, 1),
(32, 3, 14, '30.00', 1, 1),
(33, 3, 15, '30.00', 1, 1),
(34, 3, 16, '36.00', 1, 1),
(35, 3, 17, '34.00', 1, 1),
(36, 3, 18, '30.00', 1, 1),
(37, 2, 1, '30.00', 1, 1),
(38, 2, 2, '30.00', 1, 1),
(39, 2, 3, '23.00', 1, 1),
(40, 2, 4, '25.00', 1, 1),
(41, 2, 5, '23.00', 1, 1),
(42, 2, 6, '25.00', 1, 1),
(43, 2, 7, '23.00', 1, 1),
(44, 2, 8, '25.00', 1, 1),
(45, 2, 9, '25.00', 1, 1),
(46, 2, 10, '25.00', 1, 1),
(47, 2, 11, '28.00', 1, 1),
(48, 2, 12, '28.00', 1, 1),
(49, 2, 13, '23.00', 1, 1),
(50, 2, 14, '25.00', 1, 1),
(51, 2, 15, '25.00', 1, 1),
(52, 2, 16, '30.00', 1, 1),
(53, 2, 17, '28.00', 1, 1),
(54, 2, 18, '25.00', 1, 1),
(55, 1, 19, '12.00', 1, 0),
(56, 1, 1, '15.00', 1, 0),
(57, 1, 2, '14.00', 1, 0),
(58, 1, 3, '13.00', 1, 0),
(59, 1, 4, '15.00', 1, 0),
(60, 1, 5, '20.00', 1, 0),
(61, 11, 1, '44.00', 1, 1),
(62, 10, 1, '44.00', 1, 1),
(63, 9, 1, '44.00', 1, 1),
(64, 7, 1, '44.00', 1, 1),
(65, 5, 1, '44.00', 1, 1),
(66, 4, 1, '44.00', 1, 1),
(67, 1, 1, '44.00', 1, 0),
(68, 4, 19, '40.00', 1, 1),
(69, 9, 23, '39.90', 2, 1),
(70, 9, 50, '39.90', 2, 1),
(71, 9, 30, '39.90', 2, 1),
(72, 9, 36, '39.90', 2, 1),
(74, 9, 51, '39.90', 2, 1),
(75, 9, 26, '39.90', 2, 1),
(78, 9, 49, '43.90', 2, 1),
(79, 9, 48, '43.90', 2, 1),
(80, 9, 42, '43.90', 2, 1),
(81, 9, 29, '43.90', 2, 1),
(82, 9, 40, '50.00', 2, 1),
(83, 9, 25, '43.90', 2, 1),
(84, 9, 47, '43.90', 2, 1),
(85, 9, 31, '43.90', 2, 1),
(86, 9, 46, '50.00', 2, 1),
(87, 9, 41, '50.00', 2, 1),
(88, 9, 39, '45.00', 2, 0),
(89, 10, 52, '39.90', 2, 1),
(90, 10, 44, '45.00', 2, 1),
(91, 5, 23, '45.00', 2, 1),
(92, 5, 50, '45.00', 2, 1),
(93, 5, 30, '45.00', 2, 1),
(94, 5, 36, '45.00', 2, 1),
(96, 5, 51, '45.00', 2, 1),
(97, 5, 26, '45.00', 2, 1),
(100, 5, 49, '45.00', 2, 1),
(101, 5, 48, '45.00', 2, 1),
(102, 5, 42, '45.00', 2, 1),
(103, 5, 29, '45.00', 2, 1),
(104, 5, 40, '45.00', 2, 1),
(105, 5, 25, '45.00', 2, 1),
(106, 5, 47, '45.00', 2, 1),
(107, 5, 31, '45.00', 2, 1),
(108, 6, 52, '49.00', 2, 1),
(109, 6, 44, '49.00', 2, 1),
(110, 7, 23, '80.00', 2, 0),
(111, 7, 50, '80.00', 2, 0),
(112, 7, 30, '80.00', 2, 0),
(113, 7, 36, '80.00', 2, 0),
(115, 7, 51, '80.00', 2, 0),
(116, 7, 26, '80.00', 2, 0),
(119, 7, 49, '80.00', 2, 0),
(120, 7, 48, '80.00', 2, 0),
(121, 7, 42, '80.00', 2, 0),
(122, 7, 29, '80.00', 2, 0),
(123, 7, 40, '80.00', 2, 0),
(124, 7, 25, '80.00', 2, 0),
(125, 7, 47, '80.00', 2, 0),
(126, 7, 31, '80.00', 2, 0),
(127, 18, 52, '80.00', 2, 2),
(128, 18, 44, '80.00', 2, 2),
(129, 11, 20, '39.00', 2, 2),
(130, 11, 54, '39.00', 2, 2),
(131, 11, 21, '65.00', 2, 2),
(132, 11, 55, '65.00', 2, 2),
(133, 11, 22, '85.00', 2, 2),
(134, 11, 56, '85.00', 2, 2),
(135, 9, 57, '43.90', 2, 1),
(136, 9, 58, '43.90', 2, 1),
(137, 5, 57, '45.00', 2, 1),
(138, 9, 59, '43.90', 2, 1),
(139, 5, 58, '45.00', 2, 1),
(140, 5, 59, '45.00', 2, 1),
(141, 7, 57, '80.00', 2, 0),
(142, 7, 59, '80.00', 2, 0),
(143, 7, 58, '80.00', 2, 0),
(144, 2, 19, '20.00', 1, 1),
(145, 22, 2, '50.00', 1, 2),
(146, 22, 19, '51.00', 1, 2),
(147, 2, 19, '30.00', 1, 1),
(148, 3, 19, '10.00', 1, 1),
(149, 4, 19, '15.00', 1, 1),
(150, 5, 52, '49.90', 2, 1),
(151, 9, 52, '39.90', 2, 1),
(152, 7, 52, '80.00', 2, 0),
(153, 5, 44, '60.00', 2, 1),
(154, 9, 44, '45.00', 2, 1),
(155, 7, 44, '80.00', 2, 0),
(156, 2, 60, '20.00', 1, 1),
(157, 3, 60, '40.00', 1, 1),
(158, 3, 61, '50.00', 1, 1),
(159, 3, 1, '35.00', 1, 1),
(160, 3, 62, '35.00', 1, 1),
(161, 3, 63, '35.00', 1, 1),
(162, 23, 64, '40.00', 4, 1),
(163, 24, 65, '30.00', 1, 1),
(164, 25, 64, '30.00', 4, 1),
(165, 26, 64, '50.00', 4, 1),
(166, 23, 67, '40.00', 4, 1),
(167, 25, 67, '30.00', 4, 1),
(168, 26, 67, '50.00', 4, 1),
(169, 23, 66, '40.00', 4, 1),
(170, 25, 66, '30.00', 4, 1),
(171, 26, 66, '50.00', 4, 1),
(172, 23, 68, '40.00', 4, 1),
(173, 25, 68, '30.00', 4, 1),
(174, 26, 68, '50.00', 4, 1),
(175, 23, 69, '40.00', 4, 1),
(176, 25, 69, '30.00', 4, 1),
(177, 26, 69, '50.00', 4, 1),
(178, 23, 70, '40.00', 4, 1),
(179, 25, 70, '30.00', 4, 1),
(180, 26, 70, '50.00', 4, 1),
(181, 23, 72, '41.00', 4, 1),
(182, 25, 72, '31.00', 4, 1),
(183, 26, 72, '51.00', 4, 1),
(184, 23, 73, '42.00', 4, 1),
(185, 25, 73, '32.00', 4, 1),
(186, 26, 73, '50.00', 4, 1),
(187, 23, 74, '35.00', 4, 1),
(188, 25, 74, '25.00', 4, 1),
(189, 26, 74, '45.00', 4, 1),
(190, 23, 75, '40.00', 4, 1),
(191, 25, 75, '30.00', 4, 1),
(192, 26, 75, '50.00', 4, 1),
(193, 23, 76, '30.00', 4, 1),
(194, 25, 76, '20.00', 4, 1),
(195, 26, 76, '40.00', 4, 1);

--
-- Acionadores `valor_pizza`
--
DELIMITER $$
CREATE TRIGGER `trigger_validacao_valor_pizza_bi` BEFORE INSERT ON `valor_pizza` FOR EACH ROW BEGIN
if (new.tamanho_pizza_valor_pizza is null or new.tamanho_pizza_valor_pizza < 0 ) then
		signal sqlstate '45000' set message_text = 'TAMANHO PIZZA não pode ser vazio/nulo.';
	end if;
     if (new.SABOR_pizza_valor_pizza is null or new.SABOR_pizza_valor_pizza < 0 ) then
		signal sqlstate '45000' set message_text = 'SABOR PIZZA não pode ser vazio/nulo.';
	end if;
    if (new.preco_valor_pizza is null or new.preco_valor_pizza < 0 ) then
		signal sqlstate '45000' set message_text = 'PREÇO deve ser maior que 0.';
	end if;
    if (new.pizzaria_valor_pizza is null or new.pizzaria_valor_pizza < 0 ) then
		signal sqlstate '45000' set message_text = 'PIZZARIA não pode ser vazio/nulo.';
	end if;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trigger_validacao_valor_pizza_bu` BEFORE UPDATE ON `valor_pizza` FOR EACH ROW BEGIN
if (new.tamanho_pizza_valor_pizza is null or new.tamanho_pizza_valor_pizza < 0 ) then
		signal sqlstate '45000' set message_text = 'TAMANHO PIZZA não pode ser vazio/nulo.';
	end if;
     if (new.SABOR_pizza_valor_pizza is null or new.SABOR_pizza_valor_pizza < 0 ) then
		signal sqlstate '45000' set message_text = 'SABOR PIZZA não pode ser vazio/nulo.';
	end if;
    if (new.preco_valor_pizza is null or new.preco_valor_pizza < 0 ) then
		signal sqlstate '45000' set message_text = 'PREÇO deve ser maior que 0.';
	end if;
    if (new.pizzaria_valor_pizza is null or new.pizzaria_valor_pizza < 0 ) then
		signal sqlstate '45000' set message_text = 'PIZZARIA não pode ser vazio/nulo.';
	end if;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bebida`
--
ALTER TABLE `bebida`
  ADD PRIMARY KEY (`codigo_bebida`),
  ADD KEY `bebida_pizzaria_fk` (`pizzaria_bebida`) USING BTREE;

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indexes for table `cliente_pizzaria`
--
ALTER TABLE `cliente_pizzaria`
  ADD PRIMARY KEY (`codigo_cliente_pizzaria`),
  ADD UNIQUE KEY `cliente_pizzaria_cpf_uk` (`cpf_cliente_pizzaria`),
  ADD KEY `cliente_pizzaria_uf_fk` (`uf_cliente_pizzaria`) USING BTREE,
  ADD KEY `cliente_pizzaria_cidade_fk` (`cidade_cliente_pizzaria`) USING BTREE,
  ADD KEY `cliente_pizzaria_bairro_fk` (`bairro_cliente_pizzaria`) USING BTREE,
  ADD KEY `cliente_pizzaria_pizzaria_fk` (`pizzaria_cliente_pizzaria`) USING BTREE;

--
-- Indexes for table `extra_pizza`
--
ALTER TABLE `extra_pizza`
  ADD PRIMARY KEY (`codigo_extra_pizza`),
  ADD KEY `extra_pizza_tipo_extra_pizza_fk` (`tipo_extra_pizza_extra_pizza`),
  ADD KEY `extra_pizza_tamanho_pizza_fk` (`tamanho_pizza_extra_pizza`) USING BTREE,
  ADD KEY `extra_pizza_pizzaria_fk` (`pizzaria_extra_pizza`) USING BTREE;

--
-- Indexes for table `forma_pagamento`
--
ALTER TABLE `forma_pagamento`
  ADD PRIMARY KEY (`codigo_forma_pagamento`,`pizzaria_forma_pagamento`) USING BTREE,
  ADD KEY `forma_pagamento_pizzaria_fk` (`pizzaria_forma_pagamento`);

--
-- Indexes for table `historico_cliente`
--
ALTER TABLE `historico_cliente`
  ADD UNIQUE KEY `codigo_historico_cliente` (`codigo_historico_cliente`),
  ADD KEY `cliente_pk` (`cliente_historico_cliente`),
  ADD KEY `pizzariaE_fk` (`empresa_historico_cliente`);

--
-- Indexes for table `horario_atendimento`
--
ALTER TABLE `horario_atendimento`
  ADD PRIMARY KEY (`codigo_horario_atendimento`),
  ADD KEY `horario_atendimento_pizzaria_fk` (`pizzaria_horario_atendimento`);

--
-- Indexes for table `horario_especial`
--
ALTER TABLE `horario_especial`
  ADD PRIMARY KEY (`codigo_horario_especial`),
  ADD KEY `horario_especial_pizzaria_fk` (`pizzaria_horario_especial`);

--
-- Indexes for table `item_extra_pizza`
--
ALTER TABLE `item_extra_pizza`
  ADD PRIMARY KEY (`codigo_item_extra_pizza`),
  ADD KEY `item_extra_pizza_pizza_fk` (`extra_pizza_item_extra_pizza`),
  ADD KEY `item_extra_pizza_extra_pizza_fk` (`pizza_item_extra_pizza`);

--
-- Indexes for table `item_pedido`
--
ALTER TABLE `item_pedido`
  ADD PRIMARY KEY (`codigo_item_pedido`),
  ADD KEY `item_pedido_pizza_fk` (`pizza_item_pedido`) USING BTREE,
  ADD KEY `item_pedido_pedido_fk` (`pedido_item_pedido`) USING BTREE,
  ADD KEY `item_pedido_bebida_fk` (`bebida_item_pedido`) USING BTREE,
  ADD KEY `item_pedido_promocao` (`promocao_item_pedido`);

--
-- Indexes for table `item_pizza`
--
ALTER TABLE `item_pizza`
  ADD PRIMARY KEY (`codigo_item_pizza`),
  ADD KEY `item_pizza_pizza_fk` (`pizza_item_pizza`),
  ADD KEY `item_pizza_sabor_pizza_fk` (`sabor_pizza_item_pizza`) USING BTREE;

--
-- Indexes for table `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`codigo_pedido`),
  ADD KEY `pedido_cliente_pizzaria_fk` (`cliente_pizzaria_pedido`),
  ADD KEY `pedido_bairro_fk` (`bairro_pedido`),
  ADD KEY `pedido_cidade_fk` (`cidade_pedido`),
  ADD KEY `pedido_uf_fk` (`uf_pedido`),
  ADD KEY `pedido_forma_pagamento_fk` (`forma_pagamento_pedido`),
  ADD KEY `pedido_pizzaria_fk` (`pizzaria_pedido`) USING BTREE;

--
-- Indexes for table `pizza`
--
ALTER TABLE `pizza`
  ADD PRIMARY KEY (`codigo_pizza`),
  ADD KEY `pizza_tamanho_pizza_fk` (`tamanho_pizza_pizza`) USING BTREE;

--
-- Indexes for table `pizzaria`
--
ALTER TABLE `pizzaria`
  ADD PRIMARY KEY (`codigo_pizzaria`),
  ADD UNIQUE KEY `pizzaria_cnpj_uk` (`cnpj_pizzaria`),
  ADD KEY `pizzaria_cidade_fk` (`cidade_pizzaria`),
  ADD KEY `pizzaria_bairro_fk` (`bairro_pizzaria`),
  ADD KEY `pizzaria_uf_fk` (`uf_pizzaria`);

--
-- Indexes for table `produto_ativador_promocao`
--
ALTER TABLE `produto_ativador_promocao`
  ADD PRIMARY KEY (`codigo_produto_ativador_promocao`),
  ADD KEY `produto_ativador_promocao_bebida_fk` (`bebida_produto_ativador_promocao`),
  ADD KEY `produto_ativador_promocao_extra_pizza_fk` (`extra_pizza_produto_ativador_promocao`),
  ADD KEY `produto_ativador_promocao_tipo_extra_pizza_fk` (`tipo_extra_pizza_produto_ativador_promocao`),
  ADD KEY `produto_ativador_promocao_sabor_pizza_fk` (`sabor_pizza_produto_ativador_promocao`),
  ADD KEY `produto_ativador_promocao_tamanho_pizza_fk` (`tamanho_pizza_produto_ativador_promocao`),
  ADD KEY `produto_ativador_promocao_valor_pizza_fk` (`valor_pizza_produto_ativador_promocao`),
  ADD KEY `produto_ativador_promocao_taxa_entrega_fk` (`taxa_entrega_produto_ativador_promocao`),
  ADD KEY `produto_ativador_promocao_promocao` (`promocao_produto_ativador_promocao`);

--
-- Indexes for table `produto_promocao`
--
ALTER TABLE `produto_promocao`
  ADD PRIMARY KEY (`codigo_produto_promocao`),
  ADD KEY `produto_promocao_bebida_fk` (`bebida_produto_promocao`),
  ADD KEY `produto_promocao_extra_pizza_fk` (`extra_pizza_produto_promocao`),
  ADD KEY `produto_promocao_tipo_extra_pizza_fk` (`tipo_extra_pizza_produto_promocao`),
  ADD KEY `produto_promocao_sabor_pizza_fk` (`sabor_pizza_produto_promocao`),
  ADD KEY `produto_promocao_tamanho_pizza_fk` (`tamanho_pizza_produto_promocao`),
  ADD KEY `produto_promocao_valor_pizza_fk` (`valor_pizza_produto_promocao`),
  ADD KEY `produto_promocao_taxa_entrega_fk` (`taxa_entrega_produto_promocao`),
  ADD KEY `produto_promocao_promocao` (`promocao_produto_promocao`);

--
-- Indexes for table `promocao`
--
ALTER TABLE `promocao`
  ADD PRIMARY KEY (`codigo_promocao`),
  ADD KEY `promocao_pizzaria_fk` (`pizzaria_promocao`);

--
-- Indexes for table `sabor_pizza`
--
ALTER TABLE `sabor_pizza`
  ADD PRIMARY KEY (`codigo_sabor_pizza`),
  ADD KEY `sabor_pizza_pizzaria_fk` (`pizzaria_sabor_pizza`) USING BTREE;

--
-- Indexes for table `solicitacao_gerencia`
--
ALTER TABLE `solicitacao_gerencia`
  ADD PRIMARY KEY (`codigo_solicitacao_gerencia`),
  ADD KEY `pizzaria_fk` (`pizzaria_solicitacao_gerencia`);

--
-- Indexes for table `tamanho_pizza`
--
ALTER TABLE `tamanho_pizza`
  ADD PRIMARY KEY (`codigo_tamanho_pizza`),
  ADD KEY `tamanho_pizza_pizzaria_fk` (`pizzaria_tamanho_pizza`) USING BTREE;

--
-- Indexes for table `taxa_entrega`
--
ALTER TABLE `taxa_entrega`
  ADD PRIMARY KEY (`codigo_taxa_entrega`),
  ADD KEY `taxa_entrega_pizzaria_fk` (`pizzaria_taxa_entrega`),
  ADD KEY `taxa_entrega_bairro_fk` (`bairro_taxa_entrega`);

--
-- Indexes for table `tipo_extra_pizza`
--
ALTER TABLE `tipo_extra_pizza`
  ADD PRIMARY KEY (`codigo_tipo_extra_pizza`),
  ADD KEY `tipo_extra_pizza_pizzaria_fk` (`pizzaria_tipo_extra_pizza`);

--
-- Indexes for table `valor_pizza`
--
ALTER TABLE `valor_pizza`
  ADD PRIMARY KEY (`codigo_valor_pizza`),
  ADD KEY `valor_pizza_tamanho_pizza_fk` (`tamanho_pizza_valor_pizza`) USING BTREE,
  ADD KEY `valor_pizza_pizzaria_fk` (`pizzaria_valor_pizza`) USING BTREE,
  ADD KEY `valor_pizza_sabor_pizza_fk` (`sabor_pizza_valor_pizza`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bebida`
--
ALTER TABLE `bebida`
  MODIFY `codigo_bebida` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `cliente_pizzaria`
--
ALTER TABLE `cliente_pizzaria`
  MODIFY `codigo_cliente_pizzaria` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

--
-- AUTO_INCREMENT for table `extra_pizza`
--
ALTER TABLE `extra_pizza`
  MODIFY `codigo_extra_pizza` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `forma_pagamento`
--
ALTER TABLE `forma_pagamento`
  MODIFY `codigo_forma_pagamento` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `historico_cliente`
--
ALTER TABLE `historico_cliente`
  MODIFY `codigo_historico_cliente` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'chave primaria', AUTO_INCREMENT=314;

--
-- AUTO_INCREMENT for table `horario_atendimento`
--
ALTER TABLE `horario_atendimento`
  MODIFY `codigo_horario_atendimento` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `horario_especial`
--
ALTER TABLE `horario_especial`
  MODIFY `codigo_horario_especial` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;

--
-- AUTO_INCREMENT for table `item_extra_pizza`
--
ALTER TABLE `item_extra_pizza`
  MODIFY `codigo_item_extra_pizza` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=651;

--
-- AUTO_INCREMENT for table `item_pedido`
--
ALTER TABLE `item_pedido`
  MODIFY `codigo_item_pedido` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1788;

--
-- AUTO_INCREMENT for table `item_pizza`
--
ALTER TABLE `item_pizza`
  MODIFY `codigo_item_pizza` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1345;

--
-- AUTO_INCREMENT for table `pedido`
--
ALTER TABLE `pedido`
  MODIFY `codigo_pedido` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=768;

--
-- AUTO_INCREMENT for table `pizza`
--
ALTER TABLE `pizza`
  MODIFY `codigo_pizza` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=933;

--
-- AUTO_INCREMENT for table `pizzaria`
--
ALTER TABLE `pizzaria`
  MODIFY `codigo_pizzaria` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `produto_ativador_promocao`
--
ALTER TABLE `produto_ativador_promocao`
  MODIFY `codigo_produto_ativador_promocao` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;

--
-- AUTO_INCREMENT for table `produto_promocao`
--
ALTER TABLE `produto_promocao`
  MODIFY `codigo_produto_promocao` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT for table `promocao`
--
ALTER TABLE `promocao`
  MODIFY `codigo_promocao` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `sabor_pizza`
--
ALTER TABLE `sabor_pizza`
  MODIFY `codigo_sabor_pizza` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `solicitacao_gerencia`
--
ALTER TABLE `solicitacao_gerencia`
  MODIFY `codigo_solicitacao_gerencia` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `tamanho_pizza`
--
ALTER TABLE `tamanho_pizza`
  MODIFY `codigo_tamanho_pizza` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `taxa_entrega`
--
ALTER TABLE `taxa_entrega`
  MODIFY `codigo_taxa_entrega` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=473;

--
-- AUTO_INCREMENT for table `tipo_extra_pizza`
--
ALTER TABLE `tipo_extra_pizza`
  MODIFY `codigo_tipo_extra_pizza` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `valor_pizza`
--
ALTER TABLE `valor_pizza`
  MODIFY `codigo_valor_pizza` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=196;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `bebida`
--
ALTER TABLE `bebida`
  ADD CONSTRAINT `bebida_pizzaria_fk` FOREIGN KEY (`pizzaria_bebida`) REFERENCES `pizzaria` (`codigo_pizzaria`);

--
-- Limitadores para a tabela `cliente_pizzaria`
--
ALTER TABLE `cliente_pizzaria`
  ADD CONSTRAINT `cliente_pizzaria_bairro_fk` FOREIGN KEY (`bairro_cliente_pizzaria`) REFERENCES `skybots_gerencia`.`bairro` (`codigo_bairro`),
  ADD CONSTRAINT `cliente_pizzaria_cidade_fk` FOREIGN KEY (`cidade_cliente_pizzaria`) REFERENCES `skybots_gerencia`.`cidade` (`codigo_cidade`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `cliente_pizzaria_pizzaria_fk` FOREIGN KEY (`pizzaria_cliente_pizzaria`) REFERENCES `pizzaria` (`codigo_pizzaria`),
  ADD CONSTRAINT `cliente_pizzaria_uf_fk` FOREIGN KEY (`uf_cliente_pizzaria`) REFERENCES `skybots_gerencia`.`uf` (`codigo_uf`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `extra_pizza`
--
ALTER TABLE `extra_pizza`
  ADD CONSTRAINT `extra_pizza_pizzaria_fk` FOREIGN KEY (`pizzaria_extra_pizza`) REFERENCES `pizzaria` (`codigo_pizzaria`),
  ADD CONSTRAINT `extra_pizza_tamanho_pizza_fk` FOREIGN KEY (`tamanho_pizza_extra_pizza`) REFERENCES `tamanho_pizza` (`codigo_tamanho_pizza`),
  ADD CONSTRAINT `extra_pizza_tipo_extra_pizza_fk` FOREIGN KEY (`tipo_extra_pizza_extra_pizza`) REFERENCES `tipo_extra_pizza` (`codigo_tipo_extra_pizza`);

--
-- Limitadores para a tabela `forma_pagamento`
--
ALTER TABLE `forma_pagamento`
  ADD CONSTRAINT `forma_pagamento_pizzaria_fk` FOREIGN KEY (`pizzaria_forma_pagamento`) REFERENCES `pizzaria` (`codigo_pizzaria`);

--
-- Limitadores para a tabela `historico_cliente`
--
ALTER TABLE `historico_cliente`
  ADD CONSTRAINT `cliente_pk` FOREIGN KEY (`cliente_historico_cliente`) REFERENCES `cliente_pizzaria` (`codigo_cliente_pizzaria`),
  ADD CONSTRAINT `pizzariaE_fk` FOREIGN KEY (`empresa_historico_cliente`) REFERENCES `pizzaria` (`codigo_pizzaria`);

--
-- Limitadores para a tabela `horario_atendimento`
--
ALTER TABLE `horario_atendimento`
  ADD CONSTRAINT `horario_atendimento_pizzaria_fk` FOREIGN KEY (`pizzaria_horario_atendimento`) REFERENCES `pizzaria` (`codigo_pizzaria`);

--
-- Limitadores para a tabela `horario_especial`
--
ALTER TABLE `horario_especial`
  ADD CONSTRAINT `horario_especial_pizzaria_fk` FOREIGN KEY (`pizzaria_horario_especial`) REFERENCES `pizzaria` (`codigo_pizzaria`);

--
-- Limitadores para a tabela `item_extra_pizza`
--
ALTER TABLE `item_extra_pizza`
  ADD CONSTRAINT `item_extra_pizza_extra_pizza_fk` FOREIGN KEY (`extra_pizza_item_extra_pizza`) REFERENCES `extra_pizza` (`codigo_extra_pizza`),
  ADD CONSTRAINT `item_extra_pizza_pizza_fk` FOREIGN KEY (`pizza_item_extra_pizza`) REFERENCES `pizza` (`codigo_pizza`);

--
-- Limitadores para a tabela `item_pedido`
--
ALTER TABLE `item_pedido`
  ADD CONSTRAINT `item_pedido_bebida_fk` FOREIGN KEY (`bebida_item_pedido`) REFERENCES `bebida` (`codigo_bebida`),
  ADD CONSTRAINT `item_pedido_pedido_fk` FOREIGN KEY (`pedido_item_pedido`) REFERENCES `pedido` (`codigo_pedido`),
  ADD CONSTRAINT `item_pedido_pizza_fk` FOREIGN KEY (`pizza_item_pedido`) REFERENCES `pizza` (`codigo_pizza`),
  ADD CONSTRAINT `item_pedido_promocao` FOREIGN KEY (`promocao_item_pedido`) REFERENCES `promocao` (`codigo_promocao`);

--
-- Limitadores para a tabela `item_pizza`
--
ALTER TABLE `item_pizza`
  ADD CONSTRAINT `item_pizza_pizza_fk` FOREIGN KEY (`pizza_item_pizza`) REFERENCES `pizza` (`codigo_pizza`),
  ADD CONSTRAINT `item_pizza_sabor_pizza_fk` FOREIGN KEY (`sabor_pizza_item_pizza`) REFERENCES `sabor_pizza` (`codigo_sabor_pizza`);

--
-- Limitadores para a tabela `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_bairro_fk` FOREIGN KEY (`bairro_pedido`) REFERENCES `skybots_gerencia`.`bairro` (`codigo_bairro`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `pedido_cidade_fk` FOREIGN KEY (`cidade_pedido`) REFERENCES `skybots_gerencia`.`cidade` (`codigo_cidade`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `pedido_cliente_pizzaria_fk` FOREIGN KEY (`cliente_pizzaria_pedido`) REFERENCES `cliente_pizzaria` (`codigo_cliente_pizzaria`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `pedido_forma_pagamento_fk` FOREIGN KEY (`forma_pagamento_pedido`) REFERENCES `forma_pagamento` (`codigo_forma_pagamento`),
  ADD CONSTRAINT `pedido_pizzaria_fk` FOREIGN KEY (`pizzaria_pedido`) REFERENCES `pizzaria` (`codigo_pizzaria`),
  ADD CONSTRAINT `pedido_uf_fk` FOREIGN KEY (`uf_pedido`) REFERENCES `skybots_gerencia`.`uf` (`codigo_uf`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `pizza`
--
ALTER TABLE `pizza`
  ADD CONSTRAINT `pizza_tamanho_pizza_fk` FOREIGN KEY (`tamanho_pizza_pizza`) REFERENCES `tamanho_pizza` (`codigo_tamanho_pizza`);

--
-- Limitadores para a tabela `pizzaria`
--
ALTER TABLE `pizzaria`
  ADD CONSTRAINT `pizzaria_bairro_fk` FOREIGN KEY (`bairro_pizzaria`) REFERENCES `skybots_gerencia`.`bairro` (`codigo_bairro`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `pizzaria_cidade_fk` FOREIGN KEY (`cidade_pizzaria`) REFERENCES `skybots_gerencia`.`cidade` (`codigo_cidade`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `pizzaria_uf_fk` FOREIGN KEY (`uf_pizzaria`) REFERENCES `skybots_gerencia`.`uf` (`codigo_uf`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `produto_ativador_promocao`
--
ALTER TABLE `produto_ativador_promocao`
  ADD CONSTRAINT `produto_ativador_promocao_bebida_fk` FOREIGN KEY (`bebida_produto_ativador_promocao`) REFERENCES `bebida` (`codigo_bebida`),
  ADD CONSTRAINT `produto_ativador_promocao_extra_pizza_fk` FOREIGN KEY (`extra_pizza_produto_ativador_promocao`) REFERENCES `extra_pizza` (`codigo_extra_pizza`),
  ADD CONSTRAINT `produto_ativador_promocao_promocao` FOREIGN KEY (`promocao_produto_ativador_promocao`) REFERENCES `promocao` (`codigo_promocao`),
  ADD CONSTRAINT `produto_ativador_promocao_sabor_pizza_fk` FOREIGN KEY (`sabor_pizza_produto_ativador_promocao`) REFERENCES `sabor_pizza` (`codigo_sabor_pizza`),
  ADD CONSTRAINT `produto_ativador_promocao_tamanho_pizza_fk` FOREIGN KEY (`tamanho_pizza_produto_ativador_promocao`) REFERENCES `tamanho_pizza` (`codigo_tamanho_pizza`),
  ADD CONSTRAINT `produto_ativador_promocao_taxa_entrega_fk` FOREIGN KEY (`taxa_entrega_produto_ativador_promocao`) REFERENCES `taxa_entrega` (`codigo_taxa_entrega`),
  ADD CONSTRAINT `produto_ativador_promocao_tipo_extra_pizza_fk` FOREIGN KEY (`tipo_extra_pizza_produto_ativador_promocao`) REFERENCES `tipo_extra_pizza` (`codigo_tipo_extra_pizza`),
  ADD CONSTRAINT `produto_ativador_promocao_valor_pizza_fk` FOREIGN KEY (`valor_pizza_produto_ativador_promocao`) REFERENCES `valor_pizza` (`codigo_valor_pizza`);

--
-- Limitadores para a tabela `produto_promocao`
--
ALTER TABLE `produto_promocao`
  ADD CONSTRAINT `produto_promocao_bebida_fk` FOREIGN KEY (`bebida_produto_promocao`) REFERENCES `bebida` (`codigo_bebida`),
  ADD CONSTRAINT `produto_promocao_extra_pizza_fk` FOREIGN KEY (`extra_pizza_produto_promocao`) REFERENCES `extra_pizza` (`codigo_extra_pizza`),
  ADD CONSTRAINT `produto_promocao_promocao` FOREIGN KEY (`promocao_produto_promocao`) REFERENCES `promocao` (`codigo_promocao`),
  ADD CONSTRAINT `produto_promocao_sabor_pizza_fk` FOREIGN KEY (`sabor_pizza_produto_promocao`) REFERENCES `sabor_pizza` (`codigo_sabor_pizza`),
  ADD CONSTRAINT `produto_promocao_tamanho_pizza_fk` FOREIGN KEY (`tamanho_pizza_produto_promocao`) REFERENCES `tamanho_pizza` (`codigo_tamanho_pizza`),
  ADD CONSTRAINT `produto_promocao_taxa_entrega_fk` FOREIGN KEY (`taxa_entrega_produto_promocao`) REFERENCES `taxa_entrega` (`codigo_taxa_entrega`),
  ADD CONSTRAINT `produto_promocao_tipo_extra_pizza_fk` FOREIGN KEY (`tipo_extra_pizza_produto_promocao`) REFERENCES `tipo_extra_pizza` (`codigo_tipo_extra_pizza`),
  ADD CONSTRAINT `produto_promocao_valor_pizza_fk` FOREIGN KEY (`valor_pizza_produto_promocao`) REFERENCES `valor_pizza` (`codigo_valor_pizza`);

--
-- Limitadores para a tabela `promocao`
--
ALTER TABLE `promocao`
  ADD CONSTRAINT `promocao_pizzaria_fk` FOREIGN KEY (`pizzaria_promocao`) REFERENCES `pizzaria` (`codigo_pizzaria`);

--
-- Limitadores para a tabela `sabor_pizza`
--
ALTER TABLE `sabor_pizza`
  ADD CONSTRAINT `sabor_pizza_pizzaria_fk` FOREIGN KEY (`pizzaria_sabor_pizza`) REFERENCES `pizzaria` (`codigo_pizzaria`);

--
-- Limitadores para a tabela `solicitacao_gerencia`
--
ALTER TABLE `solicitacao_gerencia`
  ADD CONSTRAINT `pizzaria_fk` FOREIGN KEY (`pizzaria_solicitacao_gerencia`) REFERENCES `pizzaria` (`codigo_pizzaria`);

--
-- Limitadores para a tabela `tamanho_pizza`
--
ALTER TABLE `tamanho_pizza`
  ADD CONSTRAINT `tamanho_pizza_pizzaria_fk` FOREIGN KEY (`pizzaria_tamanho_pizza`) REFERENCES `pizzaria` (`codigo_pizzaria`);

--
-- Limitadores para a tabela `taxa_entrega`
--
ALTER TABLE `taxa_entrega`
  ADD CONSTRAINT `taxa_entrega_bairro_fk` FOREIGN KEY (`bairro_taxa_entrega`) REFERENCES `skybots_gerencia`.`bairro` (`codigo_bairro`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `taxa_entrega_pizzaria_fk` FOREIGN KEY (`pizzaria_taxa_entrega`) REFERENCES `pizzaria` (`codigo_pizzaria`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `tipo_extra_pizza`
--
ALTER TABLE `tipo_extra_pizza`
  ADD CONSTRAINT `tipo_extra_pizza_pizzaria_fk` FOREIGN KEY (`pizzaria_tipo_extra_pizza`) REFERENCES `pizzaria` (`codigo_pizzaria`);

--
-- Limitadores para a tabela `valor_pizza`
--
ALTER TABLE `valor_pizza`
  ADD CONSTRAINT `valor_pizza_pizzaria_fk` FOREIGN KEY (`pizzaria_valor_pizza`) REFERENCES `pizzaria` (`codigo_pizzaria`),
  ADD CONSTRAINT `valor_pizza_sabor_pizza_fk` FOREIGN KEY (`sabor_pizza_valor_pizza`) REFERENCES `sabor_pizza` (`codigo_sabor_pizza`),
  ADD CONSTRAINT `valor_pizza_tamanho_pizza_fk` FOREIGN KEY (`tamanho_pizza_valor_pizza`) REFERENCES `tamanho_pizza` (`codigo_tamanho_pizza`);

DELIMITER $$
--
-- Eventos
--
CREATE DEFINER=`skybots`@`localhost` EVENT `delete_sessions` ON SCHEDULE EVERY 1 MONTH STARTS '2018-05-16 06:00:00' ON COMPLETION NOT PRESERVE ENABLE COMMENT 'delete sessions' DO delete FROM `ci_sessions` where last_update < (CURDATE() - INTERVAL 20 DAY)$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
