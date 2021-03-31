-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 08-Nov-2017 às 17:23
-- Versão do servidor: 10.1.28-MariaDB
-- PHP Version: 7.1.10

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
-- Estrutura da tabela `personagem_respostas`
--

CREATE TABLE `personagem_respostas` (
  `codigo_personagem_respostas` bigint(20) NOT NULL,
  `descricao_personagem_respostas` varchar(400) NOT NULL,
  `respostas_personagem_respostas` text NOT NULL,
  `gerente_personagem_respostas` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `personagem_respostas`
--

INSERT INTO `personagem_respostas` (`codigo_personagem_respostas`, `descricao_personagem_respostas`, `respostas_personagem_respostas`, `gerente_personagem_respostas`) VALUES
(1, 'SAUDAÇÃO NUNCA FEZ PEDIDO', 'Olá, meu nome é Anna.||Oi, eu sou a Anna.||Oieeee, meu nome é Anna.||Olá, tudo bem? Eu sou a Anna.||Oi, tudo bem? Eu sou a Anna.', 2),
(2, 'SAUDAÇÃO PEDIDO RECENTE', 'Olá, que bom te ver aqui de volta.||Oi, você voltou.||Oieee, que bom te ver aqui de novo.||Olá, você de novo.||Que bom te ver aqui de novo.||Oi, @nome_usuario. Que bom te ver aqui de novo!', 2),
(3, 'SAUDAÇÃO PEDIDO NÃO RECENTE', 'Olá, faz tempo que não te vejo por aqui.||Oi, faz tempo que você não pede pizza comigo né?', 2),
(5, 'SAUDAÇÃO TODOS CASOS', 'Como posso te ajudar, @nome_usuario?||O que você deseja hoje?||Como posso te ajudar?||Escolha uma das opções abaixo:', 2),
(11, 'SAUDAÇÃO NUNCA FEZ PEDIDO 0', 'Sou a atendente virtual da pizzaria @nome_pizzaria.||Estou aqui para anotar os seus pedidos.||Atendente virtual da pizzaria @nome_pizzaria.', 2),
(12, 'SAUDAÇÃO NUNCA FEZ PEDIDO 1', ':D||:)', 2),
(14, 'SAUDAÇÃO PEDIDO RECENTE 0', '<3', 2),
(16, 'SAUDAÇÃO PEDIDO NÃO RECENTE 0', ':(', 2),
(17, 'SAUDAÇÃO NUNCA FEZ PEDIDO 2', 'Eu vou te ajudar a fazer o seu primeiro pedido.||Deixa eu te ajudar a fazer o primeiro pedido comigo.', 2),
(18, 'SAUDAÇÃO NUNCA FEZ PEDIDO 3', 'Clique nas opções conforme as imagens e siga o fluxo @mao_baixo||É fácil, só clicar nas opções conforme essas imagens @mao_baixo.||É só clicar nas opções conforme as imagens:', 2),
(19, 'SAUDAÇÃO NUNCA FEZ PEDIDO 4', '', 2),
(20, 'SAUDAÇÃO NUNCA FEZ PEDIDO 5', '', 2),
(21, 'SAUDAÇÃO NUNCA FEZ PEDIDO 6', '', 2),
(22, 'SAUDAÇÃO NUNCA FEZ PEDIDO 7', 'Você também pode cancelar o pedido a qualquer momento.||Se necessário, você pode cancelar o pedido também.', 2),
(23, 'SAUDAÇÃO NUNCA FEZ PEDIDO 8', 'Para isso, digite CANCELAR||É só escrever CANCELAR.', 2),
(25, 'MENSAGEM INICIAL ÚLTIMO PEDIDO', 'Deixa eu verificar qual foi seu último pedido comigo.||Vou ver qual foi seu último pedido comigo.||Espere um momento.||Deixa eu pegar qual foi seu último pedido.', 2),
(26, 'RESUMO PEDIDO ÚLTIMO PEDIDO', 'Achei! Seu último pedido foi:||Encontrei seu último pedido:', 2),
(29, 'RESUMO PEDIDO ÚLTIMO PEDIDO 1', 'Deseja confirmar?||É isso mesmo?||Confirma pedido?', 2),
(30, 'OBSERVACAO ÚLTIMO PEDIDO', 'Tem alguma observação para o pedido, tipo: \"Não quero cebola\"?||Alguma observação pro pedido?', 2),
(31, 'OBSERVACAO ÚLTIMO PEDIDO 0', 'Caso deseje algo, só digitar||Escreva, fazendo favor: @mao_baixo||Caso tenha algo, só escrever:', 2),
(32, 'RESUMO PEDIDO ÚLTIMO PEDIDO 0', '@resumo_ultimo_pedido', 2),
(33, 'ENDERECO ÚLTIMO PEDIDO', 'O último endereço de entrega foi:||A entrega no último pedido foi feita em:||Esse foi o último endereço de entrega:', 2),
(34, 'ENDERECO ÚLTIMO PEDIDO 0', '@ultimo_endereco', 2),
(35, 'ENDERECO ÚLTIMO PEDIDO 1', 'É isso mesmo?||Confirma o endereço?||Confirma local de entrega?', 2),
(36, 'TELEFONE ÚLTIMO PEDIDO', 'O telefone de contato que eu tenho aqui é:||Seu telefone é:||Esse foi o telefone do último pedido:', 2),
(37, 'TELEFONE ÚLTIMO PEDIDO 0', '@cliente_telefone', 2),
(38, 'TELEFONE ÚLTIMO PEDIDO 1', 'É isso mesmo?||Confirma o telefone?||Confirma número?', 2),
(39, 'RESUMO ÚLTIMO PEDIDO FINAL', 'Seu pedido é:||Aqui um resumo do seu pedido:', 2),
(40, 'RESUMO ÚLTIMO PEDIDO FINAL 0', '@resumo_ultimo_pedido_final', 2),
(41, 'FORMA PAGAMENTO ÚLTIMO PEDIDO', 'Qual a forma de pagamento?||Escolha a forma de pagamento:||Como será feito o pagamento?', 2),
(42, 'FORMA PAGAMENTO DINHEIRO', 'Qual o troco?', 2),
(43, 'CONFIRMACAO PEDIDO', 'Inclui seu pedido', 2),
(44, 'MENSAGEM INICIAL GERENTE', 'Tudo bem! Vou chamá-lo.||Ok, deixa eu chamar ele.||Tudo bem, aguarde um momento enquanto chamo ele.||Ok, espere um momento enquanto eu o chamo.', 2),
(45, 'MENSAGEM FINAL GERENTE', 'Espero que tudo tenha se resolvido.||Espero que tenha dado tudo certo.', 2),
(46, 'MENSAGEM FINAL GERENTE 0', 'Posso te ajudar em mais alguma coisa?||Deseja mais alguma coisa, @nome_usuario?', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `personagem_respostas`
--
ALTER TABLE `personagem_respostas`
  ADD PRIMARY KEY (`codigo_personagem_respostas`),
  ADD KEY `gerente_key` (`gerente_personagem_respostas`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `personagem_respostas`
--
ALTER TABLE `personagem_respostas`
  MODIFY `codigo_personagem_respostas` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
