<!DOCTYPE html>
<html >
    <head>
        <meta charset="UTF-8">
        <style>
            @import url(https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css);
            @import url(http://fonts.googleapis.com/css?family=Titillium+Web:300);
            * {
                margin: 0;
                padding: 0;
            }

            /* Isto é necessário para não haver rolagens horizontais quando movermos os elementos */
            html, body {overflow-x: hidden;} /* isso vai esconder tudo que 'transbordar' no eixo x, que no caso é o eixo que movemos o menu pra la e pra cá. è uma precaução, tipo vc esconde se algo se mover mais do q o planejado */

            body {
                font: 100% arial, verdana, tahoma, sans-serif;
                color: black;
            }

            header {
                padding: 10px;
                overflow: hidden;/* mesma coisa, já q o hamburguer fica no header, é uma precaução*/
                color: black;
            }

            header h1 {
                display: inline-block;/* faz o hamburguer e o titulo ficarem na mesma linha , bloqueados */
                vertical-align: middle;
                text-align: center;
                width: 80%;
                font-size: 1.5em;
            }
            h1 a {color: black; text-decoration: none;}

            header input {
                float: right;
                padding: 10px;
                width: 200px;
                border: none;
            }

            .main {
                padding: 30px;
            }
            .main p {
                font-size: .9em;
                line-height: 1.2em;
                margin-bottom: 20px;
            }

            .menu-anchor { /*hamburguer  */
                width: 40px;
                height: 32px;
                display: inline-block; /* importante apenas para o jeito como fiz o hamburguer, se vc usar uma imagem, n é necessário*/
                vertical-align: middle; /* coloca tudo no middle*/
                cursor: pointer;
                background: transparent;
                z-index: 9999;
                left: 10px;
                top:10px;
                position: fixed;
            }

            .menu-anchor:before { /*cria o icone de hamburguer */
                content: "";
                display: block;
                margin: 7px auto;
                width: 70%;
                height: 0.25em;
                background: black; /* para entender como funciona, remova essa linha e observe */
                box-shadow: 0 .45em 0 0 black, 0 .9em 0 0 black; /*remova um desses dois e observe, depois remova os 2 e observe, coloque de volta depois claro! */
            }

            menu { /* painel do menu */
                position: fixed;/* IMPORTANTE tem que ser fixed, isso fixa ele no lugar*/
                top: 0; /*ele cobre o height todo, entao começa no 0px do top */
                left: 0;/*ele fica na esquerda, entao começa no 0px do left*/
                z-index: 1;/*vc ja sabe , coloca ele por cima */
                width: 220px; /* largura do menu lateral , guarde esse valor na memoria*/
                height: 100%;/*ocupa o espaço vertical todo*/
                padding-top: 10px;

                box-shadow: inset -5px -10px 10px 0 rgba(0,0,0,.3)/*sombrinha da direita*/
            }

            menu li a {/* estilos dos submenus*/
                display: block;
                border-bottom: 0px solid rgba(255,255,255,.3);
                margin: 0px;
                padding: 0px;
                color: black;
                text-decoration: nome;
            }

            menu li a:hover {
                background: black;
                color: white;
            }


            /*
                    Aqui você esconde o menu para fora da tela 
                    O valor é exatamente a largura do menu lateral */
            menu {
                -webkit-transform: translateX(-220px); /* -220 pq ai vc joga ele pra fora da janela como posião inicial*/
                -moz-transform: translateX(-220px); /* repito 4 vezes, para funcionar em outros navegadores, ex: -moz- é firefox*/
                -ms-transform: translateX(-220px);
                transform: translateX(-220px);
                -webkit-transition: all .25s linear;
                -moz-transition: all .25s linear;/*seto o tipo de animação q ele vai fazer, no caso qndo clicarmos no hamburguer*/
                -ms-transition: all .25s linear;
                transition: all .25s linear;
            }

            /*
                    Essa é a posição original do HEADER e do MAIN
            */
            header, .main {
                -webkit-transform: translateX(0);/* seto a posição original em 0 do header e do main  */
                -moz-transform: translateX(0);
                -ms-transform: translateX(0);
                transform: translateX(0);
                -webkit-transition: all .25s linear;
                -moz-transition: all .25s linear;
                -ms-transition: all .25s linear;
                transition: all .25s linear;
            }

            /*
               Com a classe menu-active na tag HTML
            */
            .menu-active menu { /* ativa o menu, lembra q ele tava em -220? setamos para 0, e com a animação q configuramos la em cima ele faz aquele efeito de slide, em 0 ele vai estar na janela */
                -webkit-transform: translateX(0);
                -moz-transform: translateX(0);
                -ms-transform: translateX(0);
                transform: translateX(0);
            }

            /* jogamos tanto o header qnto o main 220px pra direita, q da aquele efeito deles andando com o menu*/
            .menu-active header, 
            .menu-active .main {
                -webkit-transform: translateX(220px);
                -moz-transform: translateX(220px);
                -ms-transform: translateX(220px);
                transform: translateX(220px);

            }

            /* css do menu da pg antiga*/ 
            .fa-2x {
                font-size: 2em;
            }
            .fa {
                position: relative;
                display: table-cell;
                width: 60px;
                height: 36px;
                text-align: center;
                vertical-align: middle;
                font-size:20px;
            }


            .main-menu:hover,nav.main-menu.expanded, .submenu {
                width:220px;
                overflow:visible;
                overflow-y:scroll;
            }

            .main-menu  {
                background:#fbfbfb;
                border-right:1px solid #e5e5e5;
                position:inherit;
                top:0;
                bottom:0;
                height:100%;
                left:0;
                width:220px;
                overflow:visible;
                -webkit-transition:width .05s linear;
                transition:width .05s linear;
                -webkit-transform:translateZ(0) scale(1,1);
                z-index:1000;
                list-style: none;
                padding: 0;
                margin: 0;
            }

            .main-menu>ul {
                margin:7px 0;
            }

            .main-menu li {
                position: inherit;
                display:block;
                width:220px;
            }

            .main-menu li>a {
                position:inherit;
                display:table;
                border-collapse:collapse;
                border-spacing:0;
                color:black;
                font-family: arial;
                font-size: 14px;
                text-decoration:none;
                -webkit-transform:translateZ(0) scale(1,1);
                -webkit-transition:all .1s linear;
                transition:all .1s linear;

            }

            .main-menu .nav-icon {
                position:inherit;
                display:table-cell;
                width:60px;
                height:36px;
                text-align:center;
                vertical-align:middle;
                font-size:18px;
            }

            .main-menu .nav-text {
                position:inherit;
                display:table-cell;
                vertical-align:middle;
                width:190px;
                font-family: 'Titillium Web', sans-serif;
                line-height: initial;
            }

            .main-menu>ul.logout {
                position:relative;
                left:0;
                bottom:0;
            }

            .no-touch .scrollable.hover {
                overflow-y:visible;
            }

            .no-touch .scrollable.hover:hover {
                overflow-y:auto;
                overflow:visible;
            }

            a:hover,a:focus {
                text-decoration:none;
            }

            nav {
                -webkit-user-select:none;
                -moz-user-select:none;
                -ms-user-select:none;
                -o-user-select:none;
                -user-select:none;
            }

            nav ul,nav li {


                outline:0;
                margin:0;
                padding:0;
            }

            .main-menu li:hover>a,nav.main-menu li.active>a,.dropdown-menu>li>a:hover,.dropdown-menu>li>a:focus,.dropdown-menu>.active>a,.dropdown-menu>.active>a:hover,.dropdown-menu>.active>a:focus,.no-touch .dashboard-page nav.dashboard-menu ul li:hover a,.dashboard-page nav.dashboard-menu ul li.active a {
                color:#fff;
                background-color:#5fa2db;
            }

            .main-menu li:hover .submenu {
                display: block;
                max-height: 500px;
            }

            .titulo{
                color:black;
                font-weight: bold;  
                font-size: 2em;
                top:30px;
                text-align:center;
            }

            .titulo-fixo{
                width:75%;
                height:60px;
                position:fixed;
                background-color: white;
                top:30px;
                left: 50%;
                transform: translate(-50%, -50%);
                border-bottom: 1px solid grey;
            }

            .titulo a{
                color:black;
            }

            .submenu{
                background:#fffcfc;      
                top:0;
                bottom:0;    
                left:0;
                overflow:hidden;
                -webkit-transition:width .05s linear;
                transition:width .05s linear;
                -webkit-transform:translateZ(0) scale(1,1);
                z-index:1000;
                list-style: none;
                padding: 0;
                margin: 0;
                max-height: 0;
                -webkit-transition: all 0.5s ease-out;
            }

            hr { 
                display: inherit;
                margin-left: auto;
                margin-right: auto;
                border-style: inset;
                border-width: 1px;
                width: 75%;
                color:#999;    
            }

            .submenu a {
                background-color: #f2efef;
            }

            .area{
                float: left;
                background: #e2e2e2;
                /*background: #fbfbfb;*/
                width: 100%;
                height: 100%;
            }

            @font-face {
                font-family: 'Titillium Web';
                font-style: normal;
                font-weight: 300;
                src: local('Titillium WebLight'), local('TitilliumWeb-Light'), url(http://themes.googleusercontent.com/static/fonts/titilliumweb/v2/anMUvcNT0H1YN4FII8wpr24bNCNEoFTpS2BTjF6FB5E.woff) format('woff');
            }

        </style>


    </head>

    <body>

        <header>
            <!--<span class="menu-anchor"></span>  botao hamburguer-->               
        </header>
        <span class="menu-anchor"></span>
        <div class="titulo-fixo">
            <h4 class="titulo"><a href="http://www.skybots.com.br"><div id="wrapper" style="width:100%; text-align:center"><img src='../../image/skybots.png' height='42px;' width='auto' /></div></a></h4>
        </div>

        <menu> <!-- menu escondido, tudo q vc por em volta das tags <menu> -->
            <div class="area"></div>
            <nav class="main-menu">
                <div style="height: 45px;"></div>

                <ul>     
                    <li><a href="<?= base_url("index.php/ControladorManterPedidos/buscarPedidos") ?>">
                            <i class="fa fa-shopping-basket fa-2x"></i>
                            <span class="nav-text">
                                Pedidos
                            </span>  
                        </a>                   
                    </li>                
                    <li><a href="<?= base_url("index.php/ControladorCliente/buscarClientesAtivos") ?>">
                            <i class="fa fa-user fa-2x"></i>
                            <span class="nav-text">
                                Clientes
                            </span>
                        </a>
                    </li>                
                    <li><a href="<?= base_url("index.php/ControladorFormaPagamento/buscarFormasPagamento") ?>">
                            <i class="fa fa-credit-card fa-2x"></i>
                            <span class="nav-text">
                                Formas de pagamento
                            </span>
                        </a>
                    </li>
                    <li><a href="<?= base_url("index.php/ControladorTaxaEntrega/buscarTaxaEntrega") ?>">
                            <i class="fa fa-truck fa-2x"></i>
                            <span class="nav-text">
                                Taxas de entrega
                            </span>
                        </a>
                    </li>
                    <li><a href="#">
                            <i class="fa fa-clock-o fa-2x"></i>
                            <span class="nav-text">
                                Horário de atendimento
                            </span>
                            <ul class="submenu">
                                <li><a href="<?= base_url("index.php/ControladorHorarioAtendimento/manterHorarioAtendimento") ?>"><i class="fa fa-2x"></i>
                                        <span class="nav-text">
                                            Horário normal
                                        </span>  
                                    </a>
                                </li>
                                <li><a href="<?= base_url("index.php/ControladorHorarioEspecial/manterHorarioEspecial") ?>"><i class="fa fa-2x"></i>
                                        <span class="nav-text">
                                            Horário especial
                                        </span>  
                                    </a>
                                </li>
                            </ul>
                        </a>
                    </li>
                    <li><a href="<?= base_url("index.php/cadastroBebida/solicitarAlterarBebida") ?>">
                            <i class="fa fa-glass fa-2x"></i>
                            <span class="nav-text">
                                Bebidas
                            </span>  
                        </a>                   
                    </li>
                    <li><a href="#">
                            <i class="fa fa-cutlery fa-2x"></i>
                            <span class="nav-text">
                                Pizzas
                            </span>
                            <ul class="submenu">
                                <li><a href="<?= base_url("index.php/ControladorPizzas/buscarTipoExtras") ?>"><i class="fa fa-2x"></i>
                                        <span class="nav-text">
                                            Tipo extra
                                        </span>  
                                    </a>
                                </li>
                                <li><a href="<?= base_url("index.php/ControladorPizzas/buscarTodosExtras") ?>"><i class="fa fa-2x"></i>
                                        <span class="nav-text">
                                            Extra
                                        </span>  
                                    </a>
                                </li>
                                <li><a href="<?= base_url("index.php/ControladorPizzas/buscarTodosSabores") ?>"><i class="fa fa-2x"></i>
                                        <span class="nav-text">
                                            Sabor
                                        </span>  
                                    </a>
                                </li>
                                <li><a href="<?= base_url("index.php/ControladorPizzas/buscarTodosTamanhos") ?>"><i class="fa fa-2x"></i>
                                        <span class="nav-text">
                                            Tamanho
                                        </span>  
                                    </a>
                                </li>
                                <li><a href="<?= base_url("index.php/ControladorPizzas/buscarTodosValoresPizzas") ?>"><i class="fa fa-2x"></i>
                                        <span class="nav-text">
                                            Pizza
                                        </span>  
                                    </a>
                                </li>                            
                            </ul>
                        </a>
                    </li>
                     <li><a href="<?= base_url("index.php/ControladorPromocao/buscarPromocaos") ?>">
                            <i class="fa fa-tags fa-2x"></i>
                            <span class="nav-text">
                                Promoção
                            </span>  
                        </a>                   
                    </li>
                    
                    <li><a href="<?= base_url("index.php/ControladorDadosEmpresa/alterarConfigEmpresa") ?>">
                            <i class="fa fa-cog fa-2x"></i>
                            <span class="nav-text">
                                Configurações gerais
                            </span>  
                        </a>                   
                    </li>
                </ul>

                <hr class="hr"/>


                <ul class="logout">
                    <li>
                        <a href="<?= base_url("index.php/ControladorDadosEmpresa/alterarDadosEmpresa") ?>">
                            <i class="fa fa-address-card fa-2x"></i>
                            <span class="nav-text">
                                Dados Cadastrais
                            </span>
                        </a>
                    </li>


                    <li>
                        <a href="<?= base_url("index.php/Logar/logout") ?>">
                            <i class="fa fa-power-off fa-2x"></i>
                            <span class="nav-text">
                                Logout
                            </span>
                        </a>
                    </li>  
                </ul>
            </nav>
        </menu>

        <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

        <script>
            $(document).ready(function () {

                $('.menu-anchor').on('click touchstart', function (e) {
                    $('html').toggleClass('menu-active');/* coloca a classe menu-active no html qndo o menu-anchor -hamburguer- for clicado */
                    e.preventDefault();/* impede que o evento de click padrão seja executado - por exemplo se fosse um button de submit, impediria o submit do form */
                });
            });

            if (window.innerHeight > window.innerWidth) { // portrait orientation
// show a message that asks the user to use landscape mode
                alert("Melhor visualizado no modo paisagem");

            }
        </script>

    </body>
</html>
