
<style>
#primary_nav_wrap
{
	margin-top:15px
}

#primary_nav_wrap ul
{
	list-style:none;
	position:relative;
	float:left;
	margin:0;
	padding:0
}

#primary_nav_wrap ul a
{
	display:block;
	color:#333;
	text-decoration:none;
	font-weight:700;
	font-size:12px;
	line-height:32px;
	padding:0 15px;
	font-family:"HelveticaNeue","Helvetica Neue",Helvetica,Arial,sans-serif
}

#primary_nav_wrap ul li
{
	position:relative;
	float:left;
	margin:0;
	padding:0
}

#primary_nav_wrap ul li.current-menu-item
{
	background:#ddd
}

#primary_nav_wrap ul li:hover
{
	background:#ABB7B7
}

#primary_nav_wrap ul ul
{
	display:none;
	position:absolute;
	top:100%;
	left:0;
	background:#A2DED0;
	padding:0
}

#primary_nav_wrap ul ul li
{
	float:none;
	width:100px
}

#primary_nav_wrap ul ul a
{
	line-height:120%;
	padding:10px 15px
}

#primary_nav_wrap ul ul ul
{
	top:0;
	left:100%
}

#primary_nav_wrap ul li:hover > ul
{
	display:block
}
</style>

<html lang="en">
    <head>
        <link rel="stylesheet" href="<?=base_url("css/bootstrap.css")?>">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Menu Dropdown Horizontal - Linha de Código</title>
     <!-- Aqui chamamos o nosso arquivo css externo -->
        <link rel="stylesheet" type="text/css"  href="estilo.css" />
    <!--[if lte IE 8]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]--> 
    </head>
        
    <body>
        <div class="container">
        <!--<h1> Dados da Empresa</h1>--> 
        <?php if( $this->session->flashdata("sucess") ) :?>
            <p class="alert alert-success" > <?= $this->session->flashdata("sucess") ?></p>
        <?php  endif?>
        <?php if( $this->session->flashdata("danger") ) :?>
            <p class="alert alert-danger" > <?= $this->session->flashdata("danger") ?></p>
        <?php  endif?>
        <nav id="primary_nav_wrap">
            <ul>
              <li class="current-menu-item"><a href="#">Home</a></li>
              <li><a href="#">Incluir Pizza</a>
                <ul>
                  <li class="dir"><a href="<?=base_url("index.php/ControladorJson/incluiPedidoSolicitado/"."?Codg_pizzaria=1&TamanhoPizza=2&Sabor=2-3&ItemExtra=1&ItemBebida=1-1-2-1-3-2-1-1-1-2&BairroCliente=1"
                        ."&first_name=Alexandre&last_name=tomasi&chatfuel_user_id=58a3030fe4b0bd0cca6dfb54&map_url=www.skybots.com.br&messenger_user_id=2354235324534534"
                        ."&TelefoneCliente=65992217489&gender=male&state=Mato Grosso&city=Cuiabá&zip=78500000&EnderecoComplemento=do lado da padaria boa esperanca"
                        ."&address=Secretaria da Fazenda, 3415, Avenida Historiador Rubens de Mendonça, Centro Político Administrativo, Cuiabá&CustoTotalPedidoCliente=48.20&FormaPgCliente=2"
                        ."&ObservacaoCliente=nenhuma&pri name=ale&TrocoCliente=0")?>">
                        insere o pedido 1 pizza</a></li>
                  <li class="dir"><a href="<?=base_url("index.php/ControladorJsonUltimoPedido/incluiPedidoSolicitadoUP/"."?Codg_pizzaria=1"
                        ."&chatfuel_user_id=58a3030fe4b0bd0cca6dfb54&TelefoneClienteUP=65992217489&state=Mato Grosso&city=Cuiabá&zip=78500000&EnderecoComplementoUP=do lado da padaria boa esperanca"
                        ."&address=Avenida Amarílio de Almeida, Do Poção, Cuiabá, Microrregião de Cuiabá, Mesorregião Centro-Sul Mato-Grossense, Mato Grosso, Central-West Region, Brazil.&FormaPgClienteUP=2"
                        ."&ObservacaoClienteUP=nenhuma&messenger_user_id=1234567894562&TrocoClienteUP=0&map_url=dgf")?>">
                        reinsere ultimo pedido</a></li>     
                  <li class="dir"><a href="<?=base_url("index.php/ControladorJson/incluiPedidoSolicitado/"."?Codg_pizzaria=1&TamanhoPizza=4-1&Sabor=2@19&ItemExtra=9&ItemBebida=1&BairroCliente=1"
                        ."&fb_first_name=Alexandre&fb_last_name=tomasi&fb_id=58a3030fe4b0bd0cca6dfb54&chatfuel_user_id=58a3030fe4b0bd0cca6dfb54&TrocoCliente=0&map_url=fnfntn546h546h"
                        ."&TelefoneCliente=65992217489&gender=male&state=Mato Grosso&city=Cuiabá&zip=78500000&EnderecoComplemento=do lado da padaria boa esperanca&messenger_user_id=34t54yt54"
                        ."&address=Secretaria da Fazenda, 3415, Avenida Historiador Rubens de Mendonça, Centro Político Administrativo, Cuiabá&CustoTotalPedidoCliente=70&FormaPgCliente=2"
                        ."&ObservacaoCliente=nenhuma")?>">insere o pedido com 2 pizza</a></li>
                </ul>
              </li>
              <li><a href="#">Orçamentos</a>
                <ul>
                    <li class="dir"><a href="<?=base_url("index.php/ControladorJson/solicitaOrcamentoPedido"."?Codg_pizzaria=1&ItemBebida=1-1-2-1-3-2-1-1-1-2&Sabor=1@2&ItemExtra=1&BairroCliente=1&TamanhoPizza=1-1")?>">
                            Gera orçamento</a></li>
                    <li class="dir"><a href="<?=base_url("index.php/ControladorJson/solicitaDadosPedido"."?Codg_pizzaria=1&ItemBebida=1-1-2-1-3-2-1-1-1-2&Sabor=1@2&ItemExtra=1&BairroCliente=1&TamanhoPizza=1-1")?>">
                            Dados do pedido</a></li>

                    <li class="dir"><a href="<?=base_url("index.php/ControladorJsonUltimoPedido/solicitaOrcamentoPedidoUP/"."?Codg_pizzaria=1&chatfuel_user_id=58a3030fe4b0bd0cca6dfb54")?>">
                            gera orcamento UP</a></li>
                    <li class="dir"><a href="<?=base_url("index.php/ControladorJsonUltimoPedido/buscaUltimoPedidoCliente/"."?Codg_pizzaria=1&chatfuel_user_id=58a3030fe4b0bd0cca6dfb54")?>">
                            dados do pedido UP</a></li>            
                </ul>
              </li>
              <li><a href="#">Sabores</a>
                <ul>
                  <li class="dir"><a href="<?=base_url("index.php/ControladorJson/buscaSaboresPizza"."?Codg_pizzaria=1&Pg=1&TamanhoPizza=2&Sabor=1")?>">
                          busca sabores</a></li>
                  <li class="dir"><a href="<?=base_url("index.php/ControladorJson/incrementaFaixaSabores"."?Codg_pizzaria=1&Pg=1&TamanhoPizza=2&FluxoRespostaPadrao=0")?>">
                          incrementa faixa sabores</a></li>
                  <li class="dir"><a href="<?=base_url("index.php/ControladorJson/verificaSaboresVersusFatias"."?Codg_pizzaria=1&TamanhoPizza=4&Sabor=1-2-3")?>">
                          valida quantidade de sabores</a></li>
                </ul>
              </li>
              <li><a href="#">Extras</a>
                <ul>
                  <li><a href="<?=base_url("index.php/ControladorJson/buscaSaboresPizza?Codg_pizzaria=1&Pg=2&TamanhoPizza=4&Sabor=1")?>">
                          Faixas</a>
                    </li>   
                    <li><a href="<?=base_url("index.php/ControladorJson/buscaExtraParaPizza?Codg_pizzaria=1&FaixaExtra=1&QuantidadePizza=2&ItemExtra=@1&TamanhoPizza=4")?>">
                            Buscar Extra</a>
                    </li>
                    <li><a href="<?=base_url("index.php/ControladorJson/setaValorAdicionalExtra?Codg_pizzaria=1&last_clicked_button_name=BACON&QuantidadePizza=2&ItemExtra=@1&TamanhoPizza=4")?>">
                            Setar valor Extra</a>
                    </li>
                    <li class="dir"><a href="<?=base_url("index.php/ControladorJson/verificaExisteExtraCadastrado"."?Codg_pizzaria=1&FaixaExtra=1&Sabor=3-4@7&ItemExtra=1-3-4-10&QuantidadePizza=1&Sabor=0&TamanhoPizza=4")?>">
                            verifica Existe Extra Cadastrado</a></li>
                    <li class="dir"><a href="<?=base_url("index.php/ControladorJson/buscaExtraParaPizza"."?Codg_pizzaria=1&FaixaExtra=1&FaixaTamanho=1&TamanhoPizza=0&&last_clicked_button_name=nada&QuantidadePizza=1&ItemExtra=1")?>">
                            Busca mais opcoes</a></li>
                </ul>
              </li>
              <li><a href="<?=base_url("index.php/ControladorJson/buscaTamanhosPizzas")?>">Configurações</a>
                <ul>
            

        
<li class="dir"><a href="<?=base_url("index.php/ControladorJson/verificaBairroPermitidoPorNome/"."?Codg_pizzaria=1"."&NomeBairroCliente=Centro Político Administrativo")?>"
                   >verifica bairro novo</a></li>
<li class="dir"><a href="<?=base_url("index.php/ControladorJsonUltimoPedido/buscaEnderecoDoUltimoPedido/"."?Codg_pizzaria=1"."&chatfuel_user_id=58a3030fe4b0bd0cca6dfb54")?>"
                   >busca ultimo endereco</a></li>
<li class="dir"><a href="<?=base_url("index.php/ControladorJson/recebeBairroDigitado/"."?Codg_pizzaria=1"."&last_user_freeform_input=Jardim Europa&BairroCliente=0")?>"
                   >Recebe bairro</a></li>
<li class="dir"><a href="<?=base_url("index.php/ControladorJson/verificaBairroPermitido/"."?Codg_pizzaria=1"."&address=Secretaria da Fazenda, 3415, Avenida Historiador Rubens de Mendonça, Centro Político Administrativo, Cuiabá")?>">verifica bairro</a></li>
<li class="dir"><a href="<?=base_url("index.php/ControladorJson/verificaTelefoneValido/"."?Codg_pizzaria=1"."&TelefoneCliente=65 99221-7485")?>">verifica telefone</a></li>
<li class="dir"><a href="<?=base_url("index.php/ControladorIA/perguntas/"."?Codg_pizzaria=1"."&last_user_freeform_input=nadadad&first_name=Alexandre&last_name=Tomasi")?>">pergunta?</a></li>
<li class="dir"><a href="<?=base_url("index.php/ControladorJson/verificarHorarioAtendimentoNormalEspecial/"."?Codg_pizzaria=1"."&TelefoneCliente=65 99221-7485")?>">verifica horario atendimento</a></li>
<li class="dir"><a href="<?=base_url("index.php/ControladorJsonUltimoPedido/verificaTrocoValidoUP/"."?Codg_pizzaria=1"."&chatfuel_user_id=58a3030fe4b0bd0cca6dfb54&TrocoClienteUP=70")?>">verifica troco valido</a></li>


<li class="dir"><a href="<?=base_url("index.php/ControladorJson/pedidoCancelamentoDoUsuario"."?Codg_pizzaria=1&chatfuel_user_id=58a3030fe4b0bd0cca6dfb54&fluxo=41")?>">pedido Cancelamento Do Usuario</a></li>
<li class="dir"><a href="<?=base_url("index.php/ControladorJson/solicitaExtraBebidaPizza"."?Codg_pizzaria=1")?>">Galeria mais opções</a></li>

<li class="dir"><a href="<?=base_url("index.php/ControladorJsonUltimoPedido/buscaUltimoPedidoCliente/"."?Codg_pizzaria=1&chatfuel_user_id=58a3030fe4b0bd0cca6dfb54&last_visited_block_name=Quero repetir meu último pedido!&fluxo=100")?>">ultimo pedido</a></li>





<li class="dir"><a href="<?=base_url("index.php/ControladorJson/respostaAoClienteSobrePedido/"."?Codg_pizzaria=1"."&chatfuel_user_id=58a3030fe4b0bd0cca6dfb54&messenger_user_id=1266198270132804")?>">envia msg alexandre</a></li>
 <li class="dir"><a href="<?=base_url("index.php/ControladorJson/buscaTamanhosPizza/"."?Codg_pizzaria=1&TamanhoPizza=2&Sabor=2-3&ItemExtra=1&ItemBebida=1&BairroCliente=1"
        ."&fb_first_name=Alexandre&fb_last_name=tomasi&fb_id=58a3030fe4b0bd0cca6dfb54"
        ."&TelefoneCliente=65992217489&gender=male&state=Mato Grosso&city=Cuiabá&zip=78500000&EnderecoComplemento=do lado da padaria boa esperanca"
        ."&address=Secretaria da Fazenda, 3415, Avenida Historiador Rubens de Mendonça, Centro Político Administrativo, Cuiabá&CustoTotalPedidoCliente=70&FormaPgCliente=2"
        ."&ObservacaoCliente=nenhuma&pri name=ale&TrocoCliente=0&last_clicked_button_name=BABY&QuantidadePizza=1&Pg=1")?>">testa validações</a></li>

<li class="dir"><a href="<?=base_url("index.php/ControladorJson/formataTelefone")?>">formata dada</a></li>
<li class="dir"><a href="<?=base_url("index.php/ControladorJson/mensagemWelcomeDaPizzaria/"."?Codg_pizzaria=1")?>">msg welcome</a></li>
<li class="dir"><a href="<?=base_url("index.php/ControladorJson/solicitaExtraBebidaPizza/"."?Codg_pizzaria=1")?>">pergunta bebida pizza finalizar</a></li>
                
                </ul> 
              </li>
              <li><a href="#">Contate-nos</a>
                <ul>
                  <li class="dir"><a href="https://api.chatfuel.com/bots/58a26cbee4b0bd0cc832db73/users/58a3030fe4b0bd0cca6dfb54/send?chatfuel_token=BxXmPaeffbIPttnrUQ00m6jjbqHRpi4Mo2jhqeAnswxKz17iKEpKHxd4hqTbHCSB&chatfuel_block_id=58e65f8ae4b064edb6a68492&resposta=Pronto">Submit me!</a></li>
                  <li class="dir"><a href="">procura bairro</a></li>
                </ul>
              </li>
              <li><a href="<?=base_url("index.php/logar/logout")?>">Deslogar</a></li>
              <li><a href="#">Testes</a>
                <ul>
                    
                </ul>
              </li>
            </ul>
            </nav>
            
        </div>
    </body>
</html>