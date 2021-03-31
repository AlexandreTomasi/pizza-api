<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Tester Json Produto</title>
      <style rel="stylesheet">
          *{padding: 0;margin: 0;}

            ul, li { list-style: none;}

            .menu{
              position: relative;
              width: 100%;
              height: 50px;
              background: #333;
              font-family: Arial;
              font-size: 16px;
              color: #000080;
            }

            .menu ul{
              position: relative; 
            }

            .menu  ul  li{
              position: relative;
              float: left;
              width: 150px;
            }

            .menu ul li a{
              display: block;
              text-decoration: none;
              color: #003399;
              text-align: center;
              padding: 16px;
              background: #333;
              color: #fff;
            }

            .menu ul ul{
              position: absolute;
              visibility: hidden;
            }

            .menu ul ul li{
              border-bottom: 1px solid #bbb;
            }

            .menu ul li a:hover{ 
              background: #ddd;
              color: #444;
            }

            .menu ul ul li a{
              background: #88a;
              color: #fff;
            }

            .menu ul li ul li a:hover{ 
              background: #888;
              color: #444;
            }

            .menu ul li:hover ul{
              visibility: visible;
            }

            input#bt_menu{
              display: none;
            }

            label[for="bt_menu"]{
              font-size: 25px;
              color: #fff;
              width: 100%;
              height: 50px;
              line-height: 50px;
              background: #333;
              cursor: pointer;
              font-family: Arial;
              text-align: center;
              display: none;
              z-index: 50;
            }

            @media(max-width: 800px){
              label[for="bt_menu"]{
                display: block;
              }

              .menu{
                position: absolute;
                top: -500px;
                margin-top: 5px;
                transition: all .4s;
                z-index: 1;
              }

              #bt_menu:checked ~ .menu{
                top: 50px;
              }

              .menu ul li{
                width: 100%;
                float: none;
              }

              .menu ul ul{
                position: static;
                overflow: hidden;
                max-height: 0;
                transition: all 0.4s;
              }

              .menu ul li:hover ul{
                height: auto;
                max-height: 250px;
              }

            }

            .main{
              font-family: Arial;
              padding: 20px;
            }

            .main h1{
              font-size: 32px;
              color: #003399;
              padding-bottom: 25px;
            }

            .main p{
              text-indent: 15px;
              padding-bottom: 8px;
            }
      </style>
  
</head>

<body>

<nav class="menu">
  <ul>
    <li><a href="">Mensagens</a>
        <ul>
            <li>
                <a href="<?=base_url("index.php/controladorPersonagem/ControladorPrincipal/primeiraMensagemPersonagem/"."?Codg_pizzaria=1&Codg_personagem=2"
                        ."&chatfuel_user_id=1591331940938807&first_name=Alexandre&last_name=Tomasi")?>">
                    Primeira Saudação
            </a>
            </li>
            <li>
                <a href="<?=base_url("index.php/controladorPersonagem/ControladorPrincipal/mostrarMenuInicial/"."?Codg_pizzaria=1&Codg_personagem=2"
                        ."&chatfuel_user_id=1591331940938807&first_name=Alexandre&last_name=Tomasi")?>">
                    Menu Inicial Principal
                </a>
            </li>
        </ul>
    </li>
    <li><a href="">Resumo</a>
        <ul>
            <li>
                <a href="<?=base_url("index.php/controladorPersonagem/Resumo_orcamento_pedido/resumoUltimoPedido/"."?Codg_pizzaria=1&Codg_personagem=2"
                        ."&chatfuel_user_id=1591331940938807&first_name=Alexandre&last_name=Tomasi")?>">
                    Primeiro resumo UP
            </a>
            </li>
            <li>
                <a href="<?=base_url("index.php/controladorPersonagem/Resumo_orcamento_pedido/mostrarMenuInicial/"."?Codg_pizzaria=1&Codg_personagem=2"
                        ."&chatfuel_user_id=1591331940938807&first_name=Alexandre&last_name=Tomasi")?>">
                    Fim resumo UP
                </a>
            </li>
            <li>
                <a href="<?=base_url("index.php/controladorPersonagem/Resumo_orcamento_pedido/orcamentoUltimoPedido/"."?Codg_pizzaria=1&Codg_personagem=2"
                        ."&chatfuel_user_id=1591331940938807&first_name=Alexandre&last_name=Tomasi")?>">
                    Orçamento up
                </a>
            </li>
        </ul>
    </li>
    <li><a href="">Bairros</a>
        <ul>
            <li><a href="http://localhost/produto-api/index.php/controladorJson/comunicador_json_produto/mensagemPerguntaBairroCliente?CodgEmpresa=1&NomeBairroCliente=">
                    Pergunta Bairro ?</a></li>
            <li><a href="http://localhost/produto-api/index.php/controladorJson/comunicador_json_produto/verificaBairroPermitidoPorNome?CodgEmpresa=1&NomeBairroCliente=araes">
                    Procura Bairro digitado?</a></li>
            <li><a href="http://localhost/produto-api/index.php/controladorJson/comunicador_json_produto/recebeBairroDigitado?CodgEmpresa=1&last_user_freeform_input=Sim&BairroCliente=1">
                    Recebe Bairro?</a></li>
            
        </ul>
    </li>
    <li><a href="">Produto</a>
      <ul>
        <li><a href="http://localhost/produto-api/index.php/controladorJson/comunicador_json_produto/buscaProduto?CodgEmpresa=1&FaixaProduto=1&ProdutoSelecionado=&CaminhoProduto=">
                    Busca Produto raiz</a></li>
        <li><a href="http://localhost/produto-api/index.php/controladorJson/comunicador_json_produto/recebeProduto?CodgEmpresa=1&FaixaProduto=1&ProdutoSelecionado=&CaminhoProduto=&last_clicked_button_name=Lanche">
                    Recebe Produto raiz</a></li>
        <li><a href="http://localhost/produto-api/index.php/controladorJson/comunicador_json_produto/buscaProduto?CodgEmpresa=1&FaixaProduto=1&ProdutoSelecionado=90&CaminhoProduto=90">
                    Busca Produto</a></li>
        <li><a href="http://localhost/produto-api/index.php/controladorJson/comunicador_json_produto/recebeProduto?CodgEmpresa=1&FaixaProduto=1&ProdutoSelecionado=90&NovoItem=0&CaminhoProduto=90&QuantidadeItem=1&last_clicked_button_name=Tamanho do lanche">
                    Recebe Produto</a></li>
        <li><a href="http://localhost/produto-api/index.php/controladorJson/comunicador_json_produto/incrementaFaixaProduto?CodgEmpresa=1&FaixaProduto=0&ProdutoSelecionado=90&CaminhoProduto=90&last_clicked_button_name=Tamanho do lanche">
                    Incrementa faixa de produtos</a></li>
        <li><a href="http://localhost/produto-api/index.php/controladorJson/comunicador_json_produto/incrementaFaixaQuantidade?CodgEmpresa=1&FaixaQuantidade=1&last_user_freeform_input=">
                    Incrementa faixa de quantidade</a></li>
        <li><a href="http://localhost/produto-api/index.php/controladorJson/comunicador_json_produto/perguntaQuantidadeDesejada?CodgEmpresa=1&FaixaQuantidade=1&last_clicked_button_name=Refrigerante 300ml">
                    Pergunta quantidade desejada </a></li>
      </ul>
    </li>
    <li><a href="">PEDIDO</a>
      <ul>
          <li><a href="http://localhost/produto-api/index.php/controladorJson/comunicador_json_produto/mostrarCarrinhoDeCompras?CodgEmpresa=1&ProdutoSelecionado=90q1@80q1@20q0@22q0@1q0@26q0@88q0@52q4F&CaminhoProduto=90-80-20-81-22-82-1-83-26-84-85-86-87">
          Mostrar carrinho </a></li>
          <li><a href="http://localhost/produto-api/index.php/controladorJson/comunicador_json_produto/buscarSubtotalDoPedido?CodgEmpresa=1&BairroCliente=1&ProdutoSelecionado=90q1@80q1@20q0@22q0@1q0@26q0@88q0@52q4F&CaminhoProduto=90-80-20-81-22-82-1-83-26-84-85-86-87">
          Orçamento do pedido </a></li>
          <li><a href="http://localhost/produto-api/index.php/controladorJson/comunicador_json_produto/incluiPedidoSolicitado?CodgEmpresa=1&ProdutoSelecionado=90q1@80q1@20q0@22q0@1q0@26q0@88q0@52q4F&ObservacaoCliente=&TrocoCliente=0&first_name=Alexandre&last_name=tomasi&chatfuel_user_id=58a3030fe4b0bd0cca6dfb54&TelefoneCliente=65992217489&gender=male&map_url=tttt&messenger_user_id=1234567894562&BairroCliente=3&state=Mato Grosso&city=Cuiabá&zip=78500000&address=Avenida Amarílio de Almeida, Do Poção, Cuiabá, Microrregião de Cuiabá, Mesorregião Centro-Sul Mato-Grossense, Mato Grosso, Central-West Region, Brazil.&EnderecoComplemento=do lado da padaria boa esperanca&FormaPgCliente=2">
          Inserir pedido </a></li>
          <li><a href="http://localhost/produto-api/index.php/controladorJson/comunicador_json_produto/resumoPedido?CodgEmpresa=1&ProdutoSelecionado=90q1@80q1@20q0@22q0@1q0@26q0@88q0@52q4F&CaminhoProduto=90-80-20-81-22-82-1-83-26-84-85-86-87&BairroCliente=3">
          Resumo Pedido </a></li>
          <li><a href="http://localhost/produto-api/index.php/controladorJson/comunicador_json_produto/alterarQuantidadeProduto?CodgEmpresa=1&ProdutoSelecionado=90q1@80q1@20q0@22q0@1q0@26q0@88q0@52q4F&CaminhoProduto=90-80-20-81-22-82-1-83-26-84-85-86-87&NumeroGaleria=0&FaixaQuantidade=1&last_clicked_button_name=Alterar quantidade">
          Alterar Quantidade </a></li>
          <li><a href="http://localhost/produto-api/index.php/controladorJson/comunicador_json_produto/recebeAlterarProduto?CodgEmpresa=1&ProdutoSelecionado=90q1@80q1@20q0@22q0@1q0@26q0@88q0@52q4F&CaminhoProduto=90-80-20-81-22-82-1-83-26-84-85-86-87&NumeroGaleria=0&FaixaQuantidade=1&last_user_freeform_input=2">
          Receber Quantidade Pedido</a></li>
          <li><a href="http://localhost/produto-api/index.php/controladorJson/comunicador_json_produto/finalizaProduto?CodgEmpresa=1&ProdutoSelecionado=90q1@80q1@20q0@22q0@1q0@26q0@88q0@52q4F&CaminhoProduto=90-80-20-81-22-82-1-83-26-84-85-86-87&NumeroGaleria=0&FaixaQuantidade=1&last_user_freeform_input=2">
          Finaliza Pedido</a></li>
          <li><a href="http://localhost/produto-api/index.php/controladorJson/comunicador_json_produto/finalizaProduto?CodgEmpresa=1&ProdutoSelecionado=90q1@80q1@20q0@22q0@1q0@26q0@88q0@52q4F&CaminhoProduto=90-80-20-81-22-82-1-83-26-84-85-86-87&NumeroGaleria=0&FaixaQuantidade=1&last_user_freeform_input=2">
          Remover Pedido</a></li>
          <li><a href="http://localhost/produto-api/index.php/controladorJson/comunicador_json_produto/pedidoCancelamentoDoUsuario?CodgEmpresa=1&chatfuel_user_id=58a3030fe4b0bd0cca6dfb54&fluxo=44&FaixaQuantidade=1&last_user_freeform_input=2">
          Cancelar Pedido</a></li>
      </ul>
    </li>

    <li><a href="">Outros</a>
        <ul>
            <li>
                <a href="<?=base_url("index.php/controladorPersonagem/ControladorPrincipal/solicitaObservacaoPedido/"."?Codg_pizzaria=1&Codg_personagem=2"
                        ."&chatfuel_user_id=1591331940938807&first_name=Alexandre&last_name=Tomasi")?>">
                    Pergunta Observação Pedido
                </a>
            </li>
            <li>
                <a href="<?=base_url("index.php/controladorPersonagem/ControladorPrincipal/confirmaUltimoTelefone/"."?Codg_pizzaria=1&Codg_personagem=2"
                        ."&chatfuel_user_id=1591331940938807&first_name=Alexandre&last_name=Tomasi")?>">
                    Confirma ultimo Telefone
                </a>
            </li>
            <li>
                <a href="<?=base_url("index.php/controladorPersonagem/Controlador_forma_pagamento/buscaFormasPagamento/"."?Codg_pizzaria=1&Codg_personagem=2"
                        ."&chatfuel_user_id=1591331940938807&first_name=Alexandre&last_name=Tomasi")?>">
                    Formas de Pagamento
                </a>
            </li>
        </ul>
    </li>
    <li><a href="">Pagamento</a>
        <ul> 
            <li>
                <a href="<?=base_url("index.php/controladorPersonagem/Controlador_forma_pagamento/buscaFormasPagamento/"."?Codg_pizzaria=1&Codg_personagem=2"
                        ."&chatfuel_user_id=1591331940938807&first_name=Alexandre&last_name=Tomasi")?>">
                    Formas de Pagamento
                </a>
            </li>
            <li>
                <a href="<?=base_url("index.php/controladorPersonagem/Controlador_forma_pagamento/recebeFormaPagamentoUP/"."?Codg_pizzaria=1&Codg_personagem=2"
                        ."&chatfuel_user_id=1591331940938807&first_name=Alexandre&last_name=Tomasi&last_clicked_button_name=Dinheiro")?>">
                    Recebe forma de Pagamento
                </a>
            </li>
            <li>
                <a href="<?=base_url("index.php/controladorPersonagem/Controlador_forma_pagamento/recebeTrocoInformadoUP/"."?Codg_pizzaria=1&Codg_personagem=2"
                        ."&chatfuel_user_id=1591331940938807&first_name=Alexandre&last_name=Tomasi&TrocoClienteUP=Para 50,5")?>">
                    Recebe troco
                </a>
            </li>
        </ul>
    </li>
  </ul>
</nav>

<div class="main">
  <h1>Menu de links para testar Json do bot generico</h1>
  <p></p>
   <p></p>

</div>
  
  
</body>
</html>
