<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Tester Json Agenda</title>
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
    <li><a href="">MENSAGENS</a>
        <ul>
            <li><a href="http://localhost/agenda-api/index.php/controladorJson/comunicador_json_agenda/mensagemWelcomeDaAgenda?CodgProfissional=4&first_name=Alexandre">
                    Welcome</a></li>
            <li><a href="http://localhost/agenda-api/index.php/controladorJson/comunicador_json_agenda/respostaPadrao?CodgProfissional=4&first_name=Alexandre&last_name=tomasi&last_user_freeform_input=nadadad&last_visited_block_name=Welcome_message&fluxo=1">
                    Resposta Padrao</a></li>
        </ul>
    </li>
    <li><a href="">FAIXAS</a>
        <ul>
            <li><a href="http://localhost/agenda-api/index.php/controladorJson/comunicador_json_agenda/incrementaFaixaProfissionais?CodgProfissional=4&FaixaProfissionais=0&OpcaoProfProcEspe=1">
                    Faixa Profissional</a></li>
            <li><a href="http://localhost/agenda-api/index.php/controladorJson/comunicador_json_agenda/incrementaFaixaProfissionalProcedimento?CodgProfissional=4&FaixaProcedimento=0&OpcaoProfProcEspe=1&ProfSelecionado=3">
                    Faixa Profissional Procedimento</a></li>
            
        </ul>
    </li>
    <li><a href="">AGENDA</a>
      <ul>
        <li><a href="http://localhost/agenda-api/index.php/controladorJson/comunicador_json_agenda/buscaProfissionaisFaixa?CodgProfissional=4&FaixaProfissionais=1&OpcaoProfProcEspe=2">
                    Busca Profissionais</a></li>
        <li><a href="http://localhost/agenda-api/index.php/controladorJson/comunicador_json_agenda/verificaProfissionalUnico?CodgProfissional=4&FaixaProfissionais=1&OpcaoProfProcEspe=2">
                    Verifica Prof Unico</a></li>
        <li><a href="http://localhost/agenda-api/index.php/controladorJson/comunicador_json_agenda/trocaOpcaoEscolhaUsuario?CodgProfissional=4&last_clicked_button_name=Escolher por profis&OpcaoProfProcEspe=2">
                    Troca Opção Usuario</a></li>
        <li><a href="http://localhost/agenda-api/index.php/controladorJson/comunicador_json_agenda/recebeProfisEspeciProcedSelecionado?CodgProfissional=4&last_clicked_button_name=Maria&OpcaoProfProcEspe=2&ProfSelecionado=0">
                    Recebe Profissional</a></li>
        <li><a href="http://localhost/agenda-api/index.php/controladorJson/comunicador_json_agenda/buscaProfissionalProcedimento?CodgProfissional=4&FaixaProcedimento=1&OpcaoProfProcEspe=2&ProfSelecionado=3">
                    Busca Procedimentos</a></li>
      </ul>
    </li>
    <li><a href="">SERVIÇOS</a>
      <ul>
          <li><a href="">Auditoria</a></li>
      </ul>
    </li>

    <li><a href="">CONTATO</a></li>
  </ul>
</nav>

<div class="main">
  <h1>Menu de links para testar Json da agenda</h1>
  <p></p>
   <p></p>

</div>
  
  
</body>
</html>
