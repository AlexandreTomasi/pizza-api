<!DOCTYPE html>
<?php
include($_SERVER['DOCUMENT_ROOT'] . '/pizza-api/application/views/menu/MenuGerencia.php');
?>
<html >
    <head>
        <meta charset="UTF-8">
        <title>Pedidos</title>

        <!-- por causa desse link o menu lateral fica grande-->
        <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/css/materialize.css'>
        
        <style>
            /* vercao nova com botao de pausar*/
            h3{color: #555;}
            
              #floating-button {
                width: 55px;
                height: 55px;
                border-radius: 50%;
                background: #db4437;
                position: fixed;
                bottom: 30px;
                right: 30px;
                cursor: pointer;
                box-shadow: 0px 2px 5px #666;
              }
              .plus {
                color: white;
                position: absolute;
                top: 0;
                display: block;
                bottom: 0;
                left: 0;
                right: 0;
                text-align: center;
                padding: 0;
                margin: 0;
                line-height: 55px;
                font-size: 38px;
                font-family: 'Roboto';
                font-weight: 300;
                animation: plus-out 0.3s;
                transition: all 0.3s;
              }
              #container-floating {
                position: fixed;
                width: 55px;
                height: 55px;
                bottom: 30px;
                right: 30px;
                z-index: 50px;
                z-index: 100;
              }
              #container-floating.is-opened .plus {
                animation: plus-in 0.15s linear;
                animation-fill-mode: forwards;
              }
              .close {
                position: absolute;
                top: 0;
                bottom: 0;
                left: 0;
                display: block;
                right: 0;
                padding: 0;
                opacity: 0;
                margin: auto;
                line-height: 65px;
                transform: rotateZ(-70deg);
                transition: all 0.3s;
                animation: close-out 0.3s;
              }
              #container-floating.is-opened .close {
                animation: close-in 0.2s;
                animation-delay: 0.1s;
                animation-fill-mode: forwards;
              }
              .nds {
                width: 40px;
                height: 40px;
                border-radius: 20px;
                position: fixed;
                z-index: 300;
                transform: scale(0);
                transition: width .25s;
                cursor: pointer;
              }
              .nd1 {
                background: #F44336;
                right: 37px;
                bottom: 100px;
                animation-delay: 0.2s;
                animation: bounce-out-nds 0.3s linear;
                animation-fill-mode: forwards;
              }
              .nd2 {
                background: #009926;
                right: 37px;
                bottom: 150px;
                animation-delay: 0.2s;
                animation: bounce-out-nds 0.3s linear;
                animation-fill-mode: forwards;
              }
              @keyframes bounce-nds {
                from {opacity: 0;}
                to {
                  opacity: 1;
                  transform: scale(1);
                }
              }
              @keyframes bounce-out-nds {
                from {
                  opacity: 1;
                  transform: scale(1);
                }
                to {
                  opacity: 0;
                  transform: scale(0);
                }
              }
              #container-floating.is-opened .nds {
                animation: bounce-nds 0.1s linear;
                animation-fill-mode: forwards;
              }
              .reminder {
                position: absolute;
                left: 0;
                right: 0;
                margin: auto;
                top: 0;
                bottom: 0;
                line-height: 40px;
              }

        </style>


        <style>
            .main2 .waves-effect{ z-index:0;}
            .width-30-pct{width:30%;}
            .text-align-center{text-align:center;}
            .margin-bottom-1em{margin-bottom:1em;}
            .breakEmail, .breakEmail a{
                overflow-wrap: break-word;
                word-wrap: break-word;
                -ms-word-break: break-all;
                word-break: break-all;
                -ms-hyphens: auto;
                -moz-hyphens: auto;
                -webkit-hyphens: auto;
                hyphens: auto;
            }
            .activetab {
                background-color: #ee6e73 !important;
                color: #fff!important;   min-width: 50px;
            }
            .activetab i.right { margin-left: 0px; }
            tr th {cursor: pointer;}
            .divider, .sorter {display: none;}
            .sorter .btn {    margin-bottom: 2px;
                              font-size: .75em; padding: 0 2px; }
            .customAction {
                margin-bottom: 2px;
                background-color: #0086b3;              
            }.customAction:hover{
                margin-bottom: 2px;
                background-color: #000080;
            }
            .ng-invalid.ng-dirty, input[type=tel].ng-invalid.ng-dirty:focus:not([readonly]){
                border-bottom: 1px solid #F44336;
                box-shadow: 0 1px 0 0 #F44336;
            }/* altera cor do botao*/
            .btn-leitura{pointer-events: none;}
            .borda-bottom{
                border-left: none;
                border-right: none;
                border-top: none;
            }
            .tabela-leitura{padding: 5px 0px 0px;}
            .ng-invalid.ng-dirty, input[type=tel].ng-invalid.ng-dirty:focus:not([readonly]){border-bottom: 1px solid #F44336;
                                                                                            box-shadow: 0 1px 0 0 #F44336;}
            @media screen and (max-width: 1300px) {
                .container { width:90%;}
            }
            @media screen and (max-width: 500px) {

                td, th {padding: 7px 2px;}
                .customAction {padding: 0 5px;}
                .waves-effect {font-size: .75em;}

                h4 {    font-size: 1.5em;}
                .modal {width: 95%;}
                .row .col.nopad {padding: 0;}
                td { padding-left: 115px}
                td:before {width: 90px; margin-left:0; }
            }
            @media screen and (max-width: 400px) {
                .sorter {width: 100%; margin-top: 0px;     display: block;    clear: both;}
                .container .row {margin: 0;}
                .container {width: 100%;}
            }
            @media 
            only screen and (max-width: 760px),
            (min-device-width: 768px) and (max-device-width: 1024px)  {
                tr {margin-bottom: 10px;}
                .divider {display: block; width:100%;}
                .sorter {display: inline-block; margin-top: 15px;}
                .nopad {padding: 0!important; margin: 0;}
                /* Force table to not be like tables anymore */
                table, thead, tbody, th, td, tr { 
                    display: block; 
                }
                thead tr { 
                    position: absolute;
                    top: -9999px;
                    left: -9999px;
                }
                tr { border: 1px solid #ccc; }
                td { 
                    border: none;
                    border-bottom: 0px solid #eee; 
                    position: relative;
                    padding-left: 50%;
                }

                td:before { 
                    position: absolute;
                    left: 6px;
                    width: 45%; 
                    padding-right: 10px; 
                    white-space: nowrap;
                    margin-left: 20px;
                }
                td.dadosInc:nth-of-type(1):before { content: "Data/Hora:"; font-weight: bold;}
                td.dadosInc:nth-of-type(2):before { content: "Cliente:"; font-weight: bold;}
                td.dadosInc:nth-of-type(3):before { content: "Status:"; font-weight: bold;}

                td.dadosDet:nth-of-type(1):before { content: "Quantidade:";color: #9e9e9e;font-size: 0.8rem;font-weight:normal;}
                td.dadosDet:nth-of-type(2):before { content: "Produto:"; color: #9e9e9e;font-size: 0.8rem;font-weight:normal;}
                td.dadosDet:nth-of-type(3):before { content: "Pre??o unit??rio:"; color: #9e9e9e;font-size: 0.8rem;font-weight:normal;}
                td.dadosDet:nth-of-type(4):before { content: "Pre??o total:"; color: #9e9e9e;font-size: 0.8rem;font-weight:normal;}
            }
        </style>
    </head>
    <body>
    <html>
        <head>
            <meta http-equiv="refresh" content="5(SIGNIFICA QUE IR?? ATUALIZAR AUTOM??TICAMENTE EM 5 segundos);url=URL_DESTINO(PODE SER A MESMA P??GINA)">
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <!-- include material design icons -->
            <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
        </head>
        <body>
            <!-- page content and controls will be here -->
            <div class="container main2" ng-app="myApp" ng-controller="listaPedidos">
                <div class="row">
                    <div class="col s12" ng-submit="entrar()" event-focus="submit">
                        <h4><br/>Pedidos</h4>
                        <div class="row">
                            <div class="input-field col s3 nopad">
                                <select ng-model="selected" ng-change="hasChanged()" ng-options="item.title for item in fieldTable"></select>
                            </div>
                            <!-- usado para pesquisar a tabela atual -->
                            <div class="input-field col s9 nopad"><input type="text" ng-model="search" class="form-control" placeholder="Pesquisar pedidos..." /></div>
                        </div>
                        <div class="row">
                            <div class="col s6 nopad"><input type="date" ng-model="dataInicio" name="dataInicio" id="dataInicio" required aria-required="true"/></div>
                            <div class="col s6 nopad"><input type="date" ng-model="dataFim" name="dataFim" id="dataFim" required aria-required="true"/></div>
                        </div>
                        <h6>Atualiza????o em <span ng-bind="chat.getContador()"></span> segundos.</h6><br>
                        <!-- usado para ordenar a tabela atual -->
                        <div class="sorter"><h6 style="display: inline-block;">Ordenar por: </h6>
                            <a  ng-class="{'activetab  waves-light' : predicate === 'codigo_pedido',  'disabled': predicate !== 'codigo_pedido'}"
                                ng-click="predicate = 'codigo_pedido'; reverse = !reverse" class="btn">Data/Hora
                                <i ng-show="predicate === 'codigo_pedido' && !reverse" class="material-icons right">expand_more</i>
                                <i ng-show="predicate === 'codigo_pedido' && reverse" class="material-icons right">expand_less</i>
                            </a>
                            <a  ng-class="{'activetab  waves-light' : predicate === 'nome_cliente',  'disabled': predicate !== 'nome_cliente'}"
                                ng-click="predicate = 'nome_cliente'; reverse = !reverse" class="btn">Cliente
                                <i ng-show="predicate === 'nome_cliente' && !reverse" class="material-icons right">expand_more</i>
                                <i ng-show="predicate === 'nome_cliente' && reverse" class="material-icons right">expand_less</i>
                            </a>
                            <a  ng-class="{'activetab waves-effect waves-light' : predicate === 'status_pedido',  'disabled': predicate !== 'status_pedido'}"
                                ng-click="predicate = 'status_pedido'; reverse = !reverse" class="btn">Status
                                <i ng-show="predicate === 'status_pedido' && !reverse" class="material-icons right">expand_more</i>
                                <i ng-show="predicate === 'status_pedido' && reverse" class="material-icons right">expand_less</i>
                            </a>
                        </div>
                        <div class="divider"><hr /></div>
                        <!-- pagina????o no cabe??alho da tabela -->
                        <dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" ></dir-pagination-controls>   
                        <table class="table hoverable bordered custom-comparator">
                            <thead>
                                <tr>
                                    <th  ng-class="{'activetab waves-effect waves-light' : predicate === 'codigo_pedido'}"  ng-click="predicate = 'codigo_pedido';
                                                reverse = !reverse">Data/Hora
                                        <i ng-show="predicate === 'codigo_pedido' && !reverse" class="material-icons right">expand_more</i>
                                        <i ng-show="predicate === 'codigo_pedido' && reverse" class="material-icons right">expand_less</i>
                                    </th>
                                    <th  ng-class="{'activetab waves-effect waves-light' : predicate === 'nome_cliente'}"  ng-click="predicate = 'nome_cliente';
                                                reverse = !reverse">Cliente
                                        <i ng-show="predicate === 'nome_cliente' && !reverse" class="material-icons right">expand_more</i>
                                        <i ng-show="predicate === 'nome_cliente' && reverse" class="material-icons right">expand_less</i>
                                    </th>
                                    <th  ng-class="{'activetab waves-effect waves-light' : predicate === 'status_pedido'}"  ng-click="predicate = 'status_pedido';
                                                reverse = !reverse">Status
                                        <i ng-show="predicate === 'status_pedido' && !reverse" class="material-icons right">expand_more</i>
                                        <i ng-show="predicate === 'status_pedido' && reverse" class="material-icons right">expand_less</i>
                                    </th>
                                    <th class="text-align-center">A????o</th>
                                </tr>
                            </thead>

                            <tbody>
                               <!-- <tr ng-repeat="friend in friends | orderBy:'favoriteLetter':false:localeSensitiveComparator">-->
                                <tr dir-paginate="produto in chat.listar(dataInicio, dataFim) | filter:search:buscaSemAcento | filter:filters | orderBy: predicate :reverse: localeSensitiveComparator | itemsPerPage: resultlimit ">                              
                                    <td class="dadosInc"><span ng-bind="produto.data_hora_pedido"></span> </td>   
                                    <td class="dadosInc"> <span ng-bind="produto.nome_cliente"></span></td>
                                    <td class="dadosInc" ng-model="status"> <span ng-bind="produto.status_pedido"></span> </td>
                                    <td class="text-align-center">                                    
                                        <a ng-click="carregarProduto(produto.codigo_pedido)" title="atender pedido" class="waves-light btn customAction" ><i class="material-icons centered">check_circle</i></a>
                                        <a ng-click="carregarCancelarProduto(produto.codigo_pedido)" title="cancelar pedido" class="waves-light btn customAction"><i class="material-icons centered">cancel</i></a>
                                    </td>    
                                </tr>
                            </tbody>
                        </table>
                        <!-- pagina????o no rodap?? da tabela -->
                        <dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)"></dir-pagination-controls>   
                        <!-- table that shows bebida record list -->
                        <div class="input-field col s2"><label for="resultlimit"> Resultados por p??gina: </label></div>

                        <div style="min-width: 50px;" class="input-field col s1 ">   
                            <select  name="resultlimit" id="resultlimit" ng-model="resultlimit" >
                                <option value="{{item.valor}}" ng-selected="item.valor === resultlimit" ng-repeat="item in page track by item.valor">{{item.desc}}</option>
                            </select>
                        </div>   
                        <!-- modal para criar ou alterar produto -->
                        <form name="produtoForm" id="modal-produto-form" class="modal" novalidate>
                            <div class="modal-content">
                                <h4 id="modal-produto-title">Atender Pedido</h4>
                                <div class="row">
                                    <div align="left"><h5><br/>Dados do Cliente</h5></div>
                                    <div >
                                        <label for="nome_cliente">Cliente</label>
                                        <input readonly="true" type="text" ng-model="nome_cliente" name="nome_cliente" id="nome_cliente" required aria-required="true" />                                   
                                    </div>
                                    <div>
                                        <label for="endereco_pedido">Endere??o</label>
                                        <textarea class="materialize-textarea" readonly="true" type="text" ng-model="endereco_pedido" name="endereco_pedido" id="endereco_pedido"></textarea>                                    
                                    </div> 
                                    <div class="row">
                                        <div class="col s6">
                                            <label for="bairro_pedido">Bairro</label>
                                            <input readonly="true" type="text" ng-model="bairro_pedido" name="bairro_pedido" id="bairro_pedido"/>
                                        </div>
                                        <div class="col s6">
                                            <label for="cep_pedido">CEP</label>
                                            <input readonly="true" type="text" cep-input ng-model="cep_pedido" name="cep_pedido" id="cep_pedido"/>
                                        </div>
                                        <div class="col s6">
                                            <label for="cidade_pedido">Cidade</label>
                                            <input readonly="true" type="text" ng-model="cidade_pedido" name="cidade_pedido" id="cidade_pedido"  />
                                        </div>
                                        <div class="col s6">
                                            <label for="uf_pedido">UF</label>
                                            <input readonly="true" type="text" ng-model="uf_pedido" name="uf_pedido" id="uf_pedido"  />
                                        </div>
                                        <div class="col s6">
                                            <label for="referencia_endereco_pedido">Ponto de refer??ncia</label>
                                            <input readonly="true" type="text" ng-model="referencia_endereco_pedido" name="referencia_endereco_pedido" id="referencia_endereco_pedido" />
                                        </div>
                                        <div class="col s6">
                                            <label for="telefone_pedido">Telefone</label>
                                            <input readonly="true" type="text" phone-input ng-model="telefone_pedido" name="telefone_pedido" id="telefone_pedido" />
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"></div>
                                    <a href="{{mapa_url_pedido}}" target="_blank"><i class="fa fa-location-arrow"></i>
                                        <span class="nav-text">Localiza????o</span>  
                                    </a>                   
                                    <div align="left"><h5><br/>Dados do Pedido</h5></div>
                                    <div class="row">
                                        <div class="col s6">
                                            <label for="data_hora_pedido">Data/Hora</label>
                                            <input readonly="true" type="text" ng-model="data_hora_pedido" name="data_hora_pedido" id="data_hora_pedido"  />
                                        </div>
                                        <div class="col s6">
                                            <label for="status_pedido">Status</label>
                                            <input readonly="true" type="text" ng-model="status_pedido" name="status_pedido" id="status_pedido"  />
                                        </div>
                                        <div class="col s12">
                                            <label for="observacao_pedido">Observa????o</label>
                                            <input readonly="true" type="text" ng-model="observacao_pedido" name="observacao_pedido" id="observacao_pedido"  />
                                        </div>
                                    </div>
                                    <table >
                                        <thead>
                                            <tr>
                                                <th  style="color: #9e9e9e;font-size: 0.8rem;font-weight:normal;">Quantidade</th>
                                                <th  style="color: #9e9e9e;font-size: 0.8rem;font-weight:normal;">Produto</th>
                                                <th style="color: #9e9e9e;font-size: 0.8rem;font-weight:normal;">Pre??o unit??rio </th>
                                                <th style="color: #9e9e9e;font-size: 0.8rem;font-weight:normal;" >Pre??o total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="item in tabela">
                                                <td class="dadosDet" >{{item.quantidade}}</td>
                                                <td class="dadosDet">{{item.produto}}</td>
                                                <td class="dadosDet"> {{item.preco_unitario}}</td>
                                                <td class="dadosDet">{{item.preco_total}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="col s6">
                                            <label for="forma_pagamento_pedido">Forma de Pagamento</label>
                                            <input readonly="true" type="text" ng-model="forma_pagamento_pedido" name="forma_pagamento_pedido" id="forma_pagamento_pedido"  />
                                        </div>
                                        <div class="col s6">
                                            <label for="valor_total_pedido">Valor Total</label>
                                            <input readonly="true" type="text" ng-model="valor_total_pedido" name="valor_total_pedido" id="valor_total_pedido"  />
                                        </div>
                                    <div >
                                        <label id="motivoLabel" for="motivo">Motivo</label>
                                        <input type="text" ng-model="motivo" name="motivo" id="motivo" ng-required="myVar" placeholder="Esta mensagem ser?? enviada automaticamente para o cliente explicando o cancelamento do pedido."  /> 
                                        <div ng-if="produtoForm.motivo.$error.required && produtoForm.motivo.$dirty" style="color:red">Campo obrigat??rio.</div>
                                    </div>

                                    <div class="input-field col nopad s12">
                                        <a ng-class="{'disabled': produtoForm.$invalid}" id="btn-update-produto" class="waves-effect waves-light btn customAction" ng-click="produtoForm.$valid && alterarProduto()"><i class="material-icons left">done</i>Iniciar Atendimento</a>
                                        <a ng-class="{'disabled': produtoForm.$invalid}" id="btn-cancelar-produto" class="waves-effect waves-light btn customAction" ng-click="produtoForm.$valid && cancelarProduto()"><i class="material-icons left">done</i>Cancelar pedido</a>
                                        <a class="modal-action modal-close waves-effect waves-light btn customAction"><i class="material-icons left">close</i>Fechar</a>
                                    </div>
                                </div>
                            </div>
                        </form> <!--    END MODAL -->
                        <form name="tempoParada" id="modal-tempo-form" class="modal" novalidate>
                            <div class="modal-content">
                                <h4 id="modal-produto-title">Deseja pausar por quantos minutos?</h4>
                                <div class="row">  
                                    <div class="input-field col s12">
                                        <label >Por {{minutos}} minutos.</label>
                                        <br/><br/>
                                    </div>
                                    <div class="input-field col s12">
                                        <p class="range-field">
                                            <input type="range" name="range" ng-model="minutos" min="1"  max="180">     
                                        <div ng-if="tempoParada.minutos.$error.required && tempoParada.minutos.$dirty" style="color:red">Campo obrigat??rio.</div> 
                                        </p>
                                    </div>

                                    <div class="input-field col s12 center">
                                        <a ng-class="{'disabled': tempoParada.$invalid}" id="btn-create-produto" class="waves-effect waves-light btn customAction" ng-click="tempoParada.$valid && pausarChatBot()"><i class="material-icons left">add</i>Salvar</a>
                                        <a class="modal-action modal-close waves-effect waves-light btn customAction"><i class="material-icons left">close</i>Fechar</a>
                                    </div>
                                </div>
                            </div>
                        </form> <!--    END MODAL -->
                        
                        <form name="tempoAtiva" id="modal-ativa-form" class="modal" novalidate>
                            <div class="modal-content">
                                <h4 id="modal-produto-title">Deseja cancelar ??ltimo pause?</h4>
                                <div class="row">  
                                    <div class="input-field col s12 center">
                                        <a ng-class="{'disabled': tempoAtiva.$invalid}" id="btn-create-produto" class="waves-effect waves-light btn customAction" ng-click="tempoAtiva.$valid && despausarChatBot()"><i class="material-icons left">add</i>Sim Desejo</a>
                                        <a class="modal-action modal-close waves-effect waves-light btn customAction"><i class="material-icons left">close</i>Fechar</a>
                                    </div>
                                </div>
                            </div>
                        </form> <!--    END MODAL -->
                        <!-- vercao nova com botao de pausar-->
                         <div id="container-floating">
                             <div class="nd2 nds center material-icons" style="font-size: 40px;" ng-click="chamaModalAtiva()" title="reiniciar bot">power_on</div>
                             <div class="nd1 nds center material-icons" style="font-size: 40px;" ng-click="chamaModal()" title="pausar bot">power_off</div>
                             <div id="floating-button">
                                 <a style="cursor:default" class="waves-effect waves-light  btn-floating btn-large red" id="off" ng-if="!chat.solicitaStatus()" >Off</a>
                                 <a style="cursor:default" class="waves-effect waves-light  btn-floating btn-large green" id="on" ng-if="chat.solicitaStatus()" >On</a>
                             </div>
                         </div>
                        <!-- vercao antiga sem botao
                        <div id="estatus" class="fixed-action-btn" style="bottom:45px; right:24px;">
                            <a style="cursor:default" class="waves-effect waves-light  btn-floating btn-large red" id="off" ng-if="!chat.solicitaStatus()" >Off</a>
                            <a style="cursor:default" class="waves-effect waves-light  btn-floating btn-large green" id="on" ng-if="chat.solicitaStatus()" >On</a>
                        </div> -->
                    </div> 
                </div> <!-- end col s12 -->
            </div> <!-- end row -->
        </div> <!-- end container -->
        <!-- include jquery -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <!-- material design js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/js/materialize.min.js"></script>
        <!-- include angular js -->
        <script src="<?= base_url("js/angular-1.5.7/angular.js") ?>"></script>       
    </body>
</html>

<script>
    $('#floating-button').click(function () {
        $(this).closest('#container-floating').toggleClass('is-opened');
        $('.nds').removeClass('is-opened');
        $('body').toggleClass('is-blur');
    })

    $('.nds').click(function () {
        $('.nds').not(this).removeClass('is-opened');
        $(this).toggleClass('is-opened');
    })


    $(document).ready(function () {
        $('select').material_select();
    });
    
    function printTextArea(impressao) {
         var tudo = "";
         for(var i = 0; i < impressao.length; i++){
             if(tudo == ""){
                tudo = tudo+impressao[i];
            }else{
                tudo = tudo+"<br><br>"+impressao[i];
            }
         }
         /*
	 childWindow = window.open('','childWindow','location=yes, menubar=yes, toolbar=yes');
         childWindow.document.open();
	 childWindow.document.write('<html><head></head><body style="font: 12px Lucida Console">');
         childWindow.document.write(tudo.replace(/\n/gi,'<br>').replace(/\s/g,'&nbsp;'));
	 childWindow.document.write('</body></html>');
	 childWindow.print();
	 childWindow.document.close();
	 childWindow.close();*/
        window.open("dadosParaImprimir2");
    }
</script>

<script>
    // angular js codes will be here
    var app = angular.module('myApp', ['angularUtils.directives.dirPagination']);
    app.controller('listaPedidos', function ($scope, $http, ChatFactory) {
        $scope.chat = ChatFactory;
        $scope.tabela = [];
        $scope.impressao = [];
        $scope.fieldTable = [{
                field: "",
                title: "TODOS"
            }, {
                field: "Solicitado",
                title: "Solicitado"
            }, {
                field: "Cancelado",
                title: "Cancelado"
            }, {
                field: "Pedido Atendido",
                title: "Pedido Atendido"
            }];
        $scope.selected = $scope.fieldTable[0];
        $scope.dataInicio = new Date();
        $scope.dataFim = new Date();
        $scope.dataInicio.setDate($scope.dataInicio.getDate() - 2);
        $scope.dataInicio.setHours(00);
        $scope.dataInicio.setMinutes(00);
        $scope.dataInicio.setSeconds(00);
        $scope.dataFim.setDate($scope.dataFim.getDate() + 1);
        $scope.dataFim.setHours(12);
        $scope.dataFim.setMinutes(00);
        $scope.dataFim.setSeconds(00);
        $scope.chamaModal = function () {
            $('#modal-tempo-form').openModal();
        }
        $scope.chamaModalAtiva = function () {
            $('#modal-ativa-form').openModal();
        }
        $scope.hasChanged = function () {
            $scope.filters = $scope.selected.field;
        };
        $scope.myVar = false;
        $scope.minutos = 60;
        $scope.checaStatus = function (status) {
            $scope.desabilita = true;
            if (status == 'Solicitado') {
                desabilita = false;
            }
            return desabilita;
        };
        $scope.page = [{valor: "1", desc: "1"},
            {valor: "3", desc: "3"},
            {valor: "5", desc: "5"},
            {valor: "10", desc: "10"},
            {valor: "20", desc: "20"},
            {valor: "100", desc: "100"}];
        $scope.resultlimit = $scope.page[2].valor;
        $scope.buscaSemAcento = function (actual, expected) {
            if (angular.isObject(actual))
                return false;
            function removeAccents(value) {
                return value.toString().replace(/??/g, 'a').replace(/??/g, 'a').replace(/??/g, 'a').replace(/??/g, 'a')
                        .replace(/??/g, 'e').replace(/??/g, 'e').replace(/??/g, 'i')
                        .replace(/??/g, 'o').replace(/??/g, 'o').replace(/??/g, 'o').replace(/??/g, 'u').replace(/??/g, 'n');
            }
            actual = removeAccents(angular.lowercase('' + actual));
            expected = removeAccents(angular.lowercase('' + expected));
            return actual.indexOf(expected) !== -1;
        }
        $scope.localeSensitiveComparator = function (v1, v2) {
            if (v1.type !== 'string' || v2.type !== 'string') {
                return (v1.index < v2.index) ? -1 : 1;
            }
            return v1.value.localeCompare(v2.value);
        };
        $scope.clearForm = function () {
            $scope.codigo_pedido = "";
            $scope.nome_cliente = "";
            $scope.data_hora_pedido = "";
            $scope.valor_total_pedido = "";
            $scope.forma_pagamento_pedido = "";
            $scope.observacao_pedido = "";
            $scope.telefone_pedido = "";
            $scope.endereco_pedido = "";
            $scope.numero_endereco_pedido = "";
            $scope.complemento_endereco_pedido = "";
            $scope.cidade_pedido = "";
            $scope.uf_pedido = "";
            $scope.referencia_endereco_pedido = "";
            $scope.bairro_pedido = "";
            $scope.cep_pedido = "";
            $scope.status_pedido = "";
            $scope.motivo = "";
            $scope.produtoForm.$setPristine();
        }; // END CLEAR FORM

        // retrieve record to fill out the form
        $scope.carregarProduto = function (id) {
            $scope.impressao = [];
            // change modal title
            $('#modal-produto-title').text("Atender Pedido");
            // show udpate produto button
            $('#btn-update-produto').show();
            // show create produto button
            $('#btn-cancelar-produto').hide();
            $('#motivo').hide();
            $('#motivoLabel').hide();
            $scope.myVar = false;
            // post id of produto to be edited
            $http.post('<?= base_url("index.php/ControladorManterPedidos/buscarPedidosPorCodigo") ?>', {
                'codigo_pedido': id
            }).success(function (data, status, headers, config) {
                // put the values in form
                $scope.codigo_pedido = data[0].codigo_pedido;
                $scope.nome_cliente = data[0].nome_cliente;
                $scope.data_hora_pedido = data[0].data_hora_pedido;
                $scope.valor_total_pedido = data[0].valor_total_pedido;
                $scope.forma_pagamento_pedido = data[0].forma_pagamento_pedido;
                $scope.observacao_pedido = data[0].observacao_pedido;
                $scope.telefone_pedido = data[0].telefone_pedido;
                $scope.endereco_pedido = data[0].endereco_pedido;
                $scope.numero_endereco_pedido = data[0].numero_endereco_pedido;
                $scope.mapa_url_pedido = data[0].mapa_url_pedido;
                $scope.cidade_pedido = data[0].cidade_pedido;
                $scope.uf_pedido = data[0].uf_pedido;
                $scope.referencia_endereco_pedido = data[0].referencia_endereco_pedido;
                $scope.bairro_pedido = data[0].bairro_pedido;
                $scope.cep_pedido = data[0].cep_pedido;
                $scope.status_pedido = data[0].status_pedido;
                //$scope.detalhesPedido = data[0].detalhesPedido;
                if (data[1] != null) {
                    $scope.tabela = data[1];
                }
                if(data[2] != null){
                    $scope.impressao = data[2];
                }else{
                    $scope.impressao = null;
                }
                $('#modal-produto-form').openModal();
            }).error(function (data, status, headers, config) {
                Materialize.toast("Erro ao recuperar dados", 4000);
                deferred.reject(response);
            });
        }; //   END READ ONE
        // update produto record / save changes
        $scope.alterarProduto = function () {
            if (0 === "Pedido Atendido".localeCompare($scope.status_pedido)) {
                Materialize.toast("O pedido j?? se encontra como Pedido Atendido", 4000);
                if($scope.impressao != null){
                    printTextArea($scope.impressao);
                }
                return "";
            }
            if (0 === "Cancelado".localeCompare($scope.status_pedido)) {
                Materialize.toast("N??o ?? poss??vel iniciar atendimento. O pedido se encontra cancelado", 4000);
                return "";
            }
            decisao = confirm("O status do pedido ser?? alterado para PEDIDO ATENDIDO e o cliente receber?? automaticamente uma mensagem a respeito do atendimento do pedido. Deseja confirmar?");
            if (!decisao) {
                return "";
            }
            $http.post('<?= base_url("index.php/ControladorManterPedidos/atenderPedidoAvisaCliente") ?>', {
                'codigo_pedido': $scope.codigo_pedido
            }).success(function (data, status, headers, config) {
                Materialize.toast("Pedido iniciado com sucesso", 4000);
                $('#modal-produto-form').closeModal();
                // clear modal content
                $scope.clearForm();
            }).error(function (response) {
                Materialize.toast("Erro ao comunicar o in??cio do atendimento do pedido ao cliente", 8000);
                return "";
            });
            if($scope.impressao != null){
                printTextArea($scope.impressao);
            }
            //$scope.impressao = [];
        }; //END UPDATE


        // retrieve record to fill out the form
        $scope.carregarCancelarProduto = function (id) {
            // change modal title
            $('#modal-produto-title').text("Cancelar Pedido");
            // show udpate produto button
            $('#btn-update-produto').hide();
            // show create produto button
            $('#btn-cancelar-produto').show();
            $('#motivo').show();
            $('#motivoLabel').show();
            $scope.myVar = true;
            // post id of produto to be edited
            $http.post('<?= base_url("index.php/ControladorManterPedidos/buscarPedidosPorCodigo") ?>', {
                'codigo_pedido': id
            }).success(function (data, status, headers, config) {
                // put the values in form
                $scope.codigo_pedido = data[0].codigo_pedido;
                $scope.nome_cliente = data[0].nome_cliente;
                $scope.data_hora_pedido = data[0].data_hora_pedido;

                $scope.valor_total_pedido = data[0].valor_total_pedido;
                $scope.forma_pagamento_pedido = data[0].forma_pagamento_pedido;
                $scope.observacao_pedido = data[0].observacao_pedido;
                $scope.telefone_pedido = data[0].telefone_pedido;
                $scope.endereco_pedido = data[0].endereco_pedido;
                $scope.numero_endereco_pedido = data[0].numero_endereco_pedido;
                $scope.mapa_url_pedido = data[0].mapa_url_pedido;
                $scope.cidade_pedido = data[0].cidade_pedido;
                $scope.uf_pedido = data[0].uf_pedido;
                $scope.referencia_endereco_pedido = data[0].referencia_endereco_pedido;
                $scope.bairro_pedido = data[0].bairro_pedido;
                $scope.cep_pedido = data[0].cep_pedido;
                $scope.status_pedido = data[0].status_pedido;
                if (data[1] != null) {
                    $scope.tabela = data[1];
                }
                if (0 === "Cancelado".localeCompare($scope.status_pedido)) {
                    Materialize.toast("O pedido j?? se encontra cancelado", 4000);
                    return "";
                }
                // show modal
                $('#modal-produto-form').openModal();
            }).error(function (data, status, headers, config) {
                Materialize.toast("Erro ao recuperar dados", 4000);
                deferred.reject(response);
            });
        }; //   END READ ONE
        $scope.cancelarProduto = function () {
            // show create bebida button
            if (confirm("O status do pedido ser?? alterado para PEDIDO CANCELADO e o cliente receber?? automaticamente uma mensagem com o motivo a respeito do cancelamento do pedido. Deseja confirmar?")) {
                $http.post('<?= base_url("index.php/ControladorManterPedidos/cancelarPedido") ?>', {
                    'codigo_pedido': $scope.codigo_pedido,
                    'mensagem_cancelamento': $scope.motivo
                }).success(function (data, status, headers, config) {
                    Materialize.toast("Pedido cancelado com sucesso", 4000);
                    $scope.clearForm();
                    $('#modal-produto-form').closeModal();
                }).error(function (response) {
                    Materialize.toast("Erro ao cancelar pedido", 4000);
                    deferred.reject(response);
                });
            }
        };
        $scope.pausarChatBot = function () {
            if (confirm("Tem certeza que deseja pausar o bot por " + $scope.minutos + " minutos? Novos pedidos s?? chegar??o caso voc?? reinicie o ChatBot novamente ou o tempo pausado tenha terminado.")) {
                $http.post('<?= base_url("index.php/ControladorManterPedidos/pausarChatBot") ?>', {
                    'minutos': $scope.minutos
                }).success(function (data, status, headers, config) {
                    Materialize.toast("ChatBot pausado com sucesso", 4000);
                    $('#modal-tempo-form').closeModal();
                }).error(function (response) {
                    var resposta = ((response.split("<p>Message:", -1))[1].split("</p>",-1))[0];
                    Materialize.toast((resposta != " (null)" ? resposta : "Erro ao reiniciar o ChatBot"), 4000);
                });
            }
        };
        $scope.despausarChatBot = function () {
                $http.post('<?= base_url("index.php/ControladorManterPedidos/despausarChatBot") ?>', {
                }).success(function (data, status, headers, config) {
                    Materialize.toast("ChatBot reiniciado com sucesso", 4000);
                    $('#modal-ativa-form').closeModal();
                }).error(function (response) {
                    var resposta = ((response.split("<p>Message:", -1))[1].split("</p>",-1))[0];
                    Materialize.toast((resposta != " (null)" ? resposta : "Erro ao reiniciar o ChatBot."), 4000);
                });
        };
    }); // END ANGULAR

    app.directive('phoneInput', function ($filter, $browser) {
        return {
            require: 'ngModel',
            link: function ($scope, $element, $attrs, ngModelCtrl) {
                var listener = function () {
                    var value = $element.val().replace(/[^0-9]/g, '');
                    $element.val($filter('tel')(value, false));
                };
                // This runs when we update the text field
                ngModelCtrl.$parsers.push(function (viewValue) {
                    return viewValue.replace(/[^0-9]/g, '').slice(0, 11);
                });
                // This runs when the model gets updated on the scope directly and keeps our view in sync
                ngModelCtrl.$render = function () {
                    $element.val($filter('tel')(ngModelCtrl.$viewValue, false));
                };
                $element.bind('change', listener);
                $element.bind('keydown', function (event) {
                    var key = event.keyCode;
                    // If the keys include the CTRL, SHIFT, ALT, or META keys, or the arrow keys, do nothing.
                    // This lets us support copy and paste too
                    if (key === 91 || (15 < key && key < 19) || (37 <= key && key <= 40)) {
                        return;
                    }
                    $browser.defer(listener); // Have to do this or changes don't get picked up properly
                });
                $element.bind('paste cut', function () {
                    $browser.defer(listener);
                });
            }
        };
    });
    app.filter('tel', function () {
        return function (tel) {
            if (!tel) {
                return '';
            }
            var value = tel.toString().trim().replace(/^\+/, '');

            if (value.match(/[^0-9]/)) {
                return tel;
            }
            var country, city, number;

            switch (value.length) {
                case 1:
                case 2:
                case 3:
                    city = value;
                    break;

                default:
                    city = value.slice(0, 2);
                    number = value.slice(2);
            }

            if (number) {
                if (number.length > 3 && number.length < 11) {
                    if (number.slice(0, 1) === "9" && number.length === 9) {
                        number = number.slice(0, 5) + '-' + number.slice(5, number.length);
                    } else {
                        number = number.slice(0, 4) + '-' + number.slice(4, number.length);
                    }
                }
                if (value.length == 8) {// fixo sem ddd
                    return value.slice(0, 4) + '-' + value.slice(4, value.length);
                }
                if (value.length == 9) {//celular sem ddd
                    return value.slice(0, 5) + '-' + value.slice(5, value.length);
                }
                return ("(" + city + ") " + number).trim();
            } else {
                return "(" + city;
            }

        };
    });
    app.directive('cepInput', function ($filter, $browser) {
        return {
            require: 'ngModel',
            link: function ($scope, $element, $attrs, ngModelCtrl) {
                var listener = function () {
                    var value = $element.val().replace(/[^0-9]/g, '');
                    $element.val($filter('cep')(value, false));
                };

                // This runs when we update the text field
                ngModelCtrl.$parsers.push(function (viewValue) {
                    return viewValue.replace(/[^0-9]/g, '').slice(0, 10);
                });

                // This runs when the model gets updated on the scope directly and keeps our view in sync
                ngModelCtrl.$render = function () {
                    $element.val($filter('cep')(ngModelCtrl.$viewValue, false));
                };

                $element.bind('change', listener);
                $element.bind('keydown', function (event) {
                    var key = event.keyCode;
                    // If the keys include the CTRL, SHIFT, ALT, or META keys, or the arrow keys, do nothing.
                    // This lets us support copy and paste too
                    if (key === 91 || (15 < key && key < 19) || (37 <= key && key <= 40)) {
                        return;
                    }
                    $browser.defer(listener); // Have to do this or changes don't get picked up properly
                });

                $element.bind('paste cut', function () {
                    $browser.defer(listener);
                });
            }

        };
    });
    app.filter('cep', function () {
        return function (tel) {
            if (!tel) {
                return '';
            }

            var value = tel.toString().trim().replace(/^\+/, '');

            if (value.match(/[^0-9]/)) {
                return tel;
            }

            var country, city, number;

            switch (value.length) {
                case 1:
                case 2:
                case 3:
                    city = value;
                    break;

                default:
                    city = value.slice(0, 2);
                    number = value.slice(2);
            }

            if (number) {
                if (number.length > 3) {
                    number = number.slice(0, 3) + '-' + number.slice(3, 6);
                } else {
                    number = number;
                }

                return (city + "." + number).trim();
            } else {
                return city;
            }

        };
    });
    app.config(function (paginationTemplateProvider) {
        paginationTemplateProvider.setString
                ('<ul class="pagination " ng-if="1 < pages.length || !autoHide">    <li class="nopad" ng-if="boundaryLinks" \n\
        ng-class="{ disabled : pagination.current == 1 }">        <a href="" ng-click="setCurrent(1)"><i class="material-icons">first_page</i></a>    </li>\n\
        <li class="nopad" ng-if="directionLinks" ng-class="{ disabled : pagination.current == 1 }">        <a href="" ng-click="setCurrent(pagination.current - 1)">\n\
        <i class="material-icons">chevron_left</i></a>    </li>    <li ng-click="setCurrent(pageNumber)" ng-repeat="pageNumber in pages track by tracker(pageNumber, $index)"\n\
        ng-class="{ active : pagination.current == pageNumber, disabled : pageNumber == \'...\' }">        <a href="" >{{ pageNumber }}</a>    </li>    <li class="nopad"\n\
        ng-if="directionLinks" ng-class="{ disabled : pagination.current == pagination.last }"> <a href="" ng-click="setCurrent(pagination.current + 1)"><i class="material-icons">\n\
        chevron_right</i></a>    </li> <li class="nopad" ng-if="boundaryLinks"  ng-class="{ disabled : pagination.current == pagination.last }"><a href=""\n\
        ng-click="setCurrent(pagination.last)"><i class="material-icons">last_page</i></a></li></ul>');
    });
    // jquery codes will be here
    $(document).ready(function () {
        // initialize modal
        $('.modal-trigger').leanModal();
    });
    (function () {
        var moduleName = 'angularUtils.directives.dirPagination';
        var DEFAULT_ID = '__default';
        angular.module(moduleName, [])
                .directive('dirPaginate', ['$compile', '$parse', 'paginationService', dirPaginateDirective])
                .directive('dirPaginateNoCompile', noCompileDirective)
                .directive('dirPaginationControls', ['paginationService', 'paginationTemplate', dirPaginationControlsDirective])
                .filter('itemsPerPage', ['paginationService', itemsPerPageFilter])
                .service('paginationService', paginationService)
                .provider('paginationTemplate', paginationTemplateProvider)
                .factory('ChatFactory', atualiza)
                .run(['$templateCache', dirPaginationControlsTemplateInstaller]);

        function atualiza($http, $timeout) {
            var mensagens = [];
            var statusBot = 0;
            var contador = 1;
            var quantPedidos = 0;
            var dataInicial = new Date;
            var dataFinal = new Date;

            dataInicial.setDate(dataInicial.getDate() - 2);
            dataInicial.setHours(00);
            dataInicial.setMinutes(00);
            dataInicial.setSeconds(00);
            dataFinal.setDate(dataFinal.getDate() + 1);
            dataFinal.setHours(12);
            dataFinal.setMinutes(00);
            dataFinal.setSeconds(00);

            ativarRefresh();
            return {
                listar: listar,
                solicitaStatus: solicitaStatus,
                getContador: getContador
            };
            function getContador() {
                return contador;
            }

            function listar(datainicio, datafim) {
                dataInicial = datainicio;
                dataFinal = datafim;
                return mensagens;
            }
            function solicitaStatus() {
                if (statusBot === 1) {
                    return true;
                } else {
                    return false;
                }
            }
            function ativarRefresh() {
                contador--;
                if (contador === 0) {
                    atualizar();
                    contador = 10;
                }
                promise = $timeout(ativarRefresh, 1000);
            }
            function verificaBotAtivo() {
                $http.get('<?= base_url("index.php/ControladorManterPedidos/verificarHorarioAtendimentoNormalEspecial") ?>')
                        .success(function (data) {
                            statusBot = parseInt(data);
                            // Materialize.toast("quantidade de pedidos: "+mensagens.length, 3000);
                        }).error(function (response) {
                    Materialize.toast("N??o foi poss??vel verificar se o bot est?? ativo", 4000);
                });
            }
            function atualizar() {
                verificaBotAtivo();
                $http.post('<?= base_url("index.php/ControladorManterPedidos/buscarPedidosConstantemente") ?>', {
                    'dataInicial': dataInicial,
                    'dataFinal': dataFinal
                }).success(function (data) {
                    mensagens = data;
                    if (quantPedidos === 0) {
                        quantPedidos = mensagens.length;
                    } else {
                        if (quantPedidos < mensagens.length) {
                            alert("Chegou um novo pedido! :)");
                            quantPedidos = mensagens.length;
                        }
                    }
                }).error(function (response) {
                    Materialize.toast("N??o foi poss??vel buscar novos pedidos", 4000);
                });
            }
        }
        ;

        function dirPaginateDirective($compile, $parse, paginationService) {

            return  {
                terminal: true,
                multiElement: true,
                priority: 100,
                compile: dirPaginationCompileFn
            };

            function dirPaginationCompileFn(tElement, tAttrs) {

                var expression = tAttrs.dirPaginate;
                // regex taken directly from https://github.com/angular/angular.js/blob/v1.4.x/src/ng/directive/ngRepeat.js#L339
                var match = expression.match(/^\s*([\s\S]+?)\s+in\s+([\s\S]+?)(?:\s+as\s+([\s\S]+?))?(?:\s+track\s+by\s+([\s\S]+?))?\s*$/);

                var filterPattern = /\|\s*itemsPerPage\s*:\s*(.*\(\s*\w*\)|([^\)]*?(?=\s+as\s+))|[^\)]*)/;
                if (match[2].match(filterPattern) === null) {
                    throw 'pagination directive: the \'itemsPerPage\' filter must be set.';
                }
                var itemsPerPageFilterRemoved = match[2].replace(filterPattern, '');
                var collectionGetter = $parse(itemsPerPageFilterRemoved);

                addNoCompileAttributes(tElement);

                // If any value is specified for paginationId, we register the un-evaluated expression at this stage for the benefit of any
                // dir-pagination-controls directives that may be looking for this ID.
                var rawId = tAttrs.paginationId || DEFAULT_ID;
                paginationService.registerInstance(rawId);

                return function dirPaginationLinkFn(scope, element, attrs) {
                    var paginationId = $parse(attrs.paginationId)(scope) || attrs.paginationId || DEFAULT_ID;

                    paginationService.registerInstance(paginationId);

                    var repeatExpression = getRepeatExpression(expression, paginationId);
                    addNgRepeatToElement(element, attrs, repeatExpression);

                    removeTemporaryAttributes(element);
                    var compiled = $compile(element);

                    var currentPageGetter = makeCurrentPageGetterFn(scope, attrs, paginationId);
                    paginationService.setCurrentPageParser(paginationId, currentPageGetter, scope);

                    if (typeof attrs.totalItems !== 'undefined') {
                        paginationService.setAsyncModeTrue(paginationId);
                        scope.$watch(function () {
                            return $parse(attrs.totalItems)(scope);
                        }, function (result) {
                            if (0 <= result) {
                                paginationService.setCollectionLength(paginationId, result);
                            }
                        });
                    } else {
                        paginationService.setAsyncModeFalse(paginationId);
                        scope.$watchCollection(function () {
                            return collectionGetter(scope);
                        }, function (collection) {
                            if (collection) {
                                var collectionLength = (collection instanceof Array) ? collection.length : Object.keys(collection).length;
                                paginationService.setCollectionLength(paginationId, collectionLength);
                            }
                        });
                    }
                    compiled(scope);
                };
            }

            function getRepeatExpression(expression, paginationId) {
                var repeatExpression,
                        idDefinedInFilter = !!expression.match(/(\|\s*itemsPerPage\s*:[^|]*:[^|]*)/);

                if (paginationId !== DEFAULT_ID && !idDefinedInFilter) {
                    repeatExpression = expression.replace(/(\|\s*itemsPerPage\s*:\s*[^|\s]*)/, "$1 : '" + paginationId + "'");
                } else {
                    repeatExpression = expression;
                }

                return repeatExpression;
            }
            function addNgRepeatToElement(element, attrs, repeatExpression) {
                if (element[0].hasAttribute('dir-paginate-start') || element[0].hasAttribute('data-dir-paginate-start')) {
                    // using multiElement mode (dir-paginate-start, dir-paginate-end)
                    attrs.$set('ngRepeatStart', repeatExpression);
                    element.eq(element.length - 1).attr('ng-repeat-end', true);
                } else {
                    attrs.$set('ngRepeat', repeatExpression);
                }
            }
            function addNoCompileAttributes(tElement) {
                angular.forEach(tElement, function (el) {
                    if (el.nodeType === 1) {
                        angular.element(el).attr('dir-paginate-no-compile', true);
                    }
                });
            }
            function removeTemporaryAttributes(element) {
                angular.forEach(element, function (el) {
                    if (el.nodeType === 1) {
                        angular.element(el).removeAttr('dir-paginate-no-compile');
                    }
                });
                element.eq(0).removeAttr('dir-paginate-start').removeAttr('dir-paginate').removeAttr('data-dir-paginate-start').removeAttr('data-dir-paginate');
                element.eq(element.length - 1).removeAttr('dir-paginate-end').removeAttr('data-dir-paginate-end');
            }
            function makeCurrentPageGetterFn(scope, attrs, paginationId) {
                var currentPageGetter;
                if (attrs.currentPage) {
                    currentPageGetter = $parse(attrs.currentPage);
                } else {
                    // If the current-page attribute was not set, we'll make our own.
                    // Replace any non-alphanumeric characters which might confuse
                    // the $parse service and give unexpected results.
                    // See https://github.com/michaelbromley/angularUtils/issues/233
                    var defaultCurrentPage = (paginationId + '__currentPage').replace(/\W/g, '_');
                    scope[defaultCurrentPage] = 1;
                    currentPageGetter = $parse(defaultCurrentPage);
                }
                return currentPageGetter;
            }
        }
        function noCompileDirective() {
            return {
                priority: 5000,
                terminal: true
            };
        }

        function dirPaginationControlsTemplateInstaller($templateCache) {
            $templateCache.put('angularUtils.directives.dirPagination.template',
                    '<ul class="pagination" ng-if="1 < pages.length || !autoHide"><li ng-if="boundaryLinks" ng-class="{ disabled : pagination.current == 1 }"><a href="" ng-click="setCurrent(1)">&laquo;</a></li><li ng-if="directionLinks" ng-class="{ disabled : pagination.current == 1 }"><a href="" ng-click="setCurrent(pagination.current - 1)">&lsaquo;</a></li><li ng-repeat="pageNumber in pages track by tracker(pageNumber, $index)" ng-class="{ active : pagination.current == pageNumber, disabled : pageNumber == \'...\' || ( ! autoHide && pages.length === 1 ) }"><a href="" ng-click="setCurrent(pageNumber)">{{ pageNumber }}</a></li><li ng-if="directionLinks" ng-class="{ disabled : pagination.current == pagination.last }"><a href="" ng-click="setCurrent(pagination.current + 1)">&rsaquo;</a></li><li ng-if="boundaryLinks"  ng-class="{ disabled : pagination.current == pagination.last }"><a href="" ng-click="setCurrent(pagination.last)">&raquo;</a></li></ul>');
        }

        function dirPaginationControlsDirective(paginationService, paginationTemplate) {
            var numberRegex = /^\d+$/;
            var DDO = {
                restrict: 'AE',
                scope: {
                    maxSize: '=?',
                    onPageChange: '&?',
                    paginationId: '=?',
                    autoHide: '=?'
                },
                link: dirPaginationControlsLinkFn
            };
            var templateString = paginationTemplate.getString();
            if (templateString !== undefined) {
                DDO.template = templateString;
            } else {
                DDO.templateUrl = function (elem, attrs) {
                    return attrs.templateUrl || paginationTemplate.getPath();
                };
            }
            return DDO;

            function dirPaginationControlsLinkFn(scope, element, attrs) {
                var rawId = attrs.paginationId || DEFAULT_ID;
                var paginationId = scope.paginationId || attrs.paginationId || DEFAULT_ID;

                if (!paginationService.isRegistered(paginationId) && !paginationService.isRegistered(rawId)) {
                    var idMessage = (paginationId !== DEFAULT_ID) ? ' (id: ' + paginationId + ') ' : ' ';
                    if (window.console) {
                        console.warn('Pagination directive: the pagination controls' + idMessage + 'cannot be used without the corresponding pagination directive, which was not found at link time.');
                    }
                }

                if (!scope.maxSize) {
                    scope.maxSize = 9;
                }
                scope.autoHide = scope.autoHide === undefined ? true : scope.autoHide;
                scope.directionLinks = angular.isDefined(attrs.directionLinks) ? scope.$parent.$eval(attrs.directionLinks) : true;
                scope.boundaryLinks = angular.isDefined(attrs.boundaryLinks) ? scope.$parent.$eval(attrs.boundaryLinks) : false;

                var paginationRange = Math.max(scope.maxSize, 5);
                scope.pages = [];
                scope.pagination = {
                    last: 1,
                    current: 1
                };
                scope.range = {
                    lower: 1,
                    upper: 1,
                    total: 1
                };

                scope.$watch('maxSize', function (val) {
                    if (val) {
                        paginationRange = Math.max(scope.maxSize, 5);
                        generatePagination();
                    }
                });

                scope.$watch(function () {
                    if (paginationService.isRegistered(paginationId)) {
                        return (paginationService.getCollectionLength(paginationId) + 1) * paginationService.getItemsPerPage(paginationId);
                    }
                }, function (length) {
                    if (0 < length) {
                        generatePagination();
                    }
                });

                scope.$watch(function () {
                    if (paginationService.isRegistered(paginationId)) {
                        return (paginationService.getItemsPerPage(paginationId));
                    }
                }, function (current, previous) {
                    if (current !== previous && typeof previous !== 'undefined') {
                        goToPage(scope.pagination.current);
                    }
                });

                scope.$watch(function () {
                    if (paginationService.isRegistered(paginationId)) {
                        return paginationService.getCurrentPage(paginationId);
                    }
                }, function (currentPage, previousPage) {
                    if (currentPage !== previousPage) {
                        goToPage(currentPage);
                    }
                });

                scope.setCurrent = function (num) {
                    if (paginationService.isRegistered(paginationId) && isValidPageNumber(num)) {
                        num = parseInt(num, 10);
                        paginationService.setCurrentPage(paginationId, num);
                    }
                };
                scope.tracker = function (id, index) {
                    return id + '_' + index;
                };

                function goToPage(num) {
                    if (paginationService.isRegistered(paginationId) && isValidPageNumber(num)) {
                        var oldPageNumber = scope.pagination.current;

                        scope.pages = generatePagesArray(num, paginationService.getCollectionLength(paginationId), paginationService.getItemsPerPage(paginationId), paginationRange);
                        scope.pagination.current = num;
                        updateRangeValues();
                        if (scope.onPageChange) {
                            scope.onPageChange({
                                newPageNumber: num,
                                oldPageNumber: oldPageNumber
                            });
                        }
                    }
                }

                function generatePagination() {
                    if (paginationService.isRegistered(paginationId)) {
                        var page = parseInt(paginationService.getCurrentPage(paginationId)) || 1;
                        scope.pages = generatePagesArray(page, paginationService.getCollectionLength(paginationId), paginationService.getItemsPerPage(paginationId), paginationRange);
                        scope.pagination.current = page;
                        scope.pagination.last = scope.pages[scope.pages.length - 1];
                        if (scope.pagination.last < scope.pagination.current) {
                            scope.setCurrent(scope.pagination.last);
                        } else {
                            updateRangeValues();
                        }
                    }
                }
                function updateRangeValues() {
                    if (paginationService.isRegistered(paginationId)) {
                        var currentPage = paginationService.getCurrentPage(paginationId),
                                itemsPerPage = paginationService.getItemsPerPage(paginationId),
                                totalItems = paginationService.getCollectionLength(paginationId);

                        scope.range.lower = (currentPage - 1) * itemsPerPage + 1;
                        scope.range.upper = Math.min(currentPage * itemsPerPage, totalItems);
                        scope.range.total = totalItems;
                    }
                }
                function isValidPageNumber(num) {
                    return (numberRegex.test(num) && (0 < num && num <= scope.pagination.last));
                }
            }
            function generatePagesArray(currentPage, collectionLength, rowsPerPage, paginationRange) {
                var pages = [];
                var totalPages = Math.ceil(collectionLength / rowsPerPage);
                var halfWay = Math.ceil(paginationRange / 2);
                var position;

                if (currentPage <= halfWay) {
                    position = 'start';
                } else if (totalPages - halfWay < currentPage) {
                    position = 'end';
                } else {
                    position = 'middle';
                }

                var ellipsesNeeded = paginationRange < totalPages;
                var i = 1;
                while (i <= totalPages && i <= paginationRange) {
                    var pageNumber = calculatePageNumber(i, currentPage, paginationRange, totalPages);

                    var openingEllipsesNeeded = (i === 2 && (position === 'middle' || position === 'end'));
                    var closingEllipsesNeeded = (i === paginationRange - 1 && (position === 'middle' || position === 'start'));
                    if (ellipsesNeeded && (openingEllipsesNeeded || closingEllipsesNeeded)) {
                        pages.push('...');
                    } else {
                        pages.push(pageNumber);
                    }
                    i++;
                }
                return pages;
            }
            function calculatePageNumber(i, currentPage, paginationRange, totalPages) {
                var halfWay = Math.ceil(paginationRange / 2);
                if (i === paginationRange) {
                    return totalPages;
                } else if (i === 1) {
                    return i;
                } else if (paginationRange < totalPages) {
                    if (totalPages - halfWay < currentPage) {
                        return totalPages - paginationRange + i;
                    } else if (halfWay < currentPage) {
                        return currentPage - halfWay + i;
                    } else {
                        return i;
                    }
                } else {
                    return i;
                }
            }
        }
        function itemsPerPageFilter(paginationService) {
            return function (collection, itemsPerPage, paginationId) {
                if (typeof (paginationId) === 'undefined') {
                    paginationId = DEFAULT_ID;
                }
                if (!paginationService.isRegistered(paginationId)) {
                    throw 'pagination directive: the itemsPerPage id argument (id: ' + paginationId + ') does not match a registered pagination-id.';
                }
                var end;
                var start;
                if (angular.isObject(collection)) {
                    itemsPerPage = parseInt(itemsPerPage) || 9999999999;
                    if (paginationService.isAsyncMode(paginationId)) {
                        start = 0;
                    } else {
                        start = (paginationService.getCurrentPage(paginationId) - 1) * itemsPerPage;
                    }
                    end = start + itemsPerPage;
                    paginationService.setItemsPerPage(paginationId, itemsPerPage);

                    if (collection instanceof Array) {
                        // the array just needs to be sliced
                        return collection.slice(start, end);
                    } else {
                        // in the case of an object, we need to get an array of keys, slice that, then map back to
                        // the original object.
                        var slicedObject = {};
                        angular.forEach(keys(collection).slice(start, end), function (key) {
                            slicedObject[key] = collection[key];
                        });
                        return slicedObject;
                    }
                } else {
                    return collection;
                }
            };
        }
        function keys(obj) {
            if (!Object.keys) {
                var objKeys = [];
                for (var i in obj) {
                    if (obj.hasOwnProperty(i)) {
                        objKeys.push(i);
                    }
                }
                return objKeys;
            } else {
                return Object.keys(obj);
            }
        }
        function paginationService() {

            var instances = {};
            var lastRegisteredInstance;

            this.registerInstance = function (instanceId) {
                if (typeof instances[instanceId] === 'undefined') {
                    instances[instanceId] = {
                        asyncMode: false
                    };
                    lastRegisteredInstance = instanceId;
                }
            };

            this.deregisterInstance = function (instanceId) {
                delete instances[instanceId];
            };

            this.isRegistered = function (instanceId) {
                return (typeof instances[instanceId] !== 'undefined');
            };

            this.getLastInstanceId = function () {
                return lastRegisteredInstance;
            };

            this.setCurrentPageParser = function (instanceId, val, scope) {
                instances[instanceId].currentPageParser = val;
                instances[instanceId].context = scope;
            };
            this.setCurrentPage = function (instanceId, val) {
                instances[instanceId].currentPageParser.assign(instances[instanceId].context, val);
            };
            this.getCurrentPage = function (instanceId) {
                var parser = instances[instanceId].currentPageParser;
                return parser ? parser(instances[instanceId].context) : 1;
            };

            this.setItemsPerPage = function (instanceId, val) {
                instances[instanceId].itemsPerPage = val;
            };
            this.getItemsPerPage = function (instanceId) {
                return instances[instanceId].itemsPerPage;
            };

            this.setCollectionLength = function (instanceId, val) {
                instances[instanceId].collectionLength = val;
            };
            this.getCollectionLength = function (instanceId) {
                return instances[instanceId].collectionLength;
            };

            this.setAsyncModeTrue = function (instanceId) {
                instances[instanceId].asyncMode = true;
            };

            this.setAsyncModeFalse = function (instanceId) {
                instances[instanceId].asyncMode = false;
            };

            this.isAsyncMode = function (instanceId) {
                return instances[instanceId].asyncMode;
            };
        }

        function paginationTemplateProvider() {

            var templatePath = 'angularUtils.directives.dirPagination.template';
            var templateString;
            this.setPath = function (path) {
                templatePath = path;
            };
            this.setString = function (str) {
                templateString = str;
            };

            this.$get = function () {
                return {
                    getPath: function () {
                        return templatePath;
                    },
                    getString: function () {
                        return templateString;
                    }
                };
            };
        }
    })();
</script>