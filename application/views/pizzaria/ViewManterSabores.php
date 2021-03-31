<!DOCTYPE html>
<?php
    include($_SERVER['DOCUMENT_ROOT'] . '/pizza-api/application/views/menu/MenuPrincipal.php');
?>
<html >
    <head>
        <meta charset="UTF-8">
        <title>Sabor</title>

        <!-- por causa desse link o menu lateral fica grande-->
        <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/css/materialize.css'>

        <style>
            .main2 .waves-effect{
                z-index:0;
            }

            .width-30-pct{
                width:30%;
            }

            .text-align-center{
                text-align:center;
            }

            .margin-bottom-1em{
                margin-bottom:1em;
            }

            .breakEmail, .breakEmail a{
                /* These are technically the same, but use both */
                overflow-wrap: break-word;
                word-wrap: break-word;

                -ms-word-break: break-all;
                /* This is the dangerous one in WebKit, as it breaks things wherever */
                word-break: break-all;
                /* Instead use this non-standard one: 
                word-break: break-word;*/

                /* Adds a hyphen where the word breaks, if supported (No Blink) */
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


            .ng-invalid.ng-dirty, input[type=tel].ng-invalid.ng-dirty:focus:not([readonly]){border-bottom: 1px solid #F44336;
                                                                                            box-shadow: 0 1px 0 0 #F44336;}

            @media screen and (max-width: 1300px) {
                .container { width:90%;}
            }

            /* 
            Max width before this PARTICULAR table gets nasty
            This query will take effect for any screen smaller than 760px
            and also iPads specifically.
            */
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

                /* Hide table headers (but not display: none;, for accessibility) */
                thead tr { 
                    position: absolute;
                    top: -9999px;
                    left: -9999px;
                }

                tr { border: 1px solid #ccc; }

                td { 
                    /* Behave  like a "row" */
                    border: none;
                    border-bottom: 0px solid #eee; 
                    position: relative;
                    padding-left: 50%;
                }

                td:before { 
                    /* Now like a table header */
                    position: absolute;
                    /* Top/left values mimic padding */
                    /* top: 6px; */
                    left: 6px;
                    width: 45%; 
                    padding-right: 10px; 
                    white-space: nowrap;
                    margin-left: 20px;
                }

                /*
                Label the data
                */
                td:nth-of-type(1):before { content: "Descrição:"; font-weight: bold;}
                /*td:nth-of-type(2):before { content: "Ingredientes:"; font-weight: bold;}*/
                td:nth-of-type(2):before { content: "Status:"; font-weight: bold;}
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
            
        </style>


    </head>

    <body>

    <html>
        <head>

            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">


            <!-- include material design icons -->
            <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
        </head>
        <body>

            <!-- page content and controls will be here -->

            <div class="container main2" ng-app="myApp" ng-controller="ControladorProduto">
                <div class="row">
                    <div class="col s12">
                        <h4><br/>Sabor</h4>
                        <!-- usado para pesquisar a tabela atual -->
                        <div class="input-field col s8 nopad">
                            <input type="text" ng-model="search" class="form-control" placeholder="Pesquisar sabor..." />
                        </div>
                    </div>
                    <br>
                    <div class="sorter"><h6 style="display: inline-block;">Ordenar por: </h6>
                        <a  ng-class="{'activetab  waves-light' : predicate === 'descricao_sabor_pizza',  'disabled': predicate !== 'descricao_sabor_pizza'}"
                            ng-click="predicate = 'descricao_sabor_pizza'; reverse = !reverse" class="btn">Descricao
                            <i ng-show="predicate === 'descricao_sabor_pizza' && !reverse" class="material-icons right">expand_more</i>
                            <i ng-show="predicate === 'descricao_sabor_pizza' && reverse" class="material-icons right">expand_less</i>
                        </a>
                        <a  ng-class="{'activetab  waves-light' : predicate == 'ativo_sabor_pizza',  'disabled': predicate != 'ativo_sabor_pizza'}"
                            ng-click="predicate = 'ativo_sabor_pizza'; reverse = !reverse" class="btn">Status
                            <i ng-show="predicate == 'ativo_sabor_pizza' && !reverse" class="material-icons right">expand_more</i>
                            <i ng-show="predicate == 'ativo_sabor_pizza' && reverse" class="material-icons right">expand_less</i>
                        </a>
                    </div>

                    <div class="divider">
                        <hr /></div>

                    <!-- paginação no cabeçalho da tabela -->
                    <dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" ></dir-pagination-controls>   

                    <table class="table hoverable bordered">
                        <thead>
                            <tr>
                                <th ng-class="{'activetab waves-effect waves-light' : predicate === 'descricao_sabor_pizza'}"  ng-click="predicate = 'descricao_sabor_pizza';
                                            reverse = !reverse">Descrição
                                    <i ng-show="predicate === 'descricao_sabor_pizza' && !reverse" class="material-icons right">expand_more</i>
                                    <i ng-show="predicate === 'descricao_sabor_pizza' && reverse" class="material-icons right">expand_less</i>
                                </th>          
<!--                                <th >Ingredientes</th>-->
                                <th ng-class="{'activetab waves-effect waves-light' : predicate == 'ativo_sabor_pizza'}"  ng-click="predicate = 'ativo_sabor_pizza';
                                            reverse = !reverse">Status
                                    <i ng-show="predicate == 'ativo_sabor_pizza' && !reverse" class="material-icons right">expand_more</i>
                                    <i ng-show="predicate == 'ativo_sabor_pizza' && reverse" class="material-icons right">expand_less</i>
                                </th>
                                <th class="text-align-center">Ação</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr dir-paginate="produto in produtos | filter:search:buscaSemAcento | filter:filters | orderBy:predicate:reverse:localeSensitiveComparator | itemsPerPage: resultlimit ">                              
                                <td > {{produto.descricao_sabor_pizza}} </td>  
                                <!--<td> {{produto.ingredientes_sabor_pizza}} </td>-->
                                <td> {{produto.ativo_sabor_pizza}} </td>  
                                <td class="text-align-center" >
                                    <a ng-click="ativarProduto(produto.codigo_sabor_pizza)" title="ativar sabor" class="waves-light btn customAction" ng-if="produto.ativo_sabor_pizza == 'Inativo'"><i class="material-icons centered">check</i></a>
                                    <a ng-click="inativarProduto(produto.codigo_sabor_pizza)" title="inativar sabor" class="waves-light btn customAction" ng-if="produto.ativo_sabor_pizza == 'Ativo'"><i class="material-icons centered">close</i></a>
                                    <a ng-click="carregarProduto(produto.codigo_sabor_pizza)" title="alterar sabor" class="waves-light btn customAction" ><i class="material-icons centered">edit</i></a>
                                    <a ng-click="excluirProduto(produto.codigo_sabor_pizza)" title="excluir sabor" class="waves-light btn customAction"><i class="material-icons centered">delete</i></a>
                                </td>    
                            </tr>
                        </tbody>
                    </table>
                    <!-- paginação no rodapé da tabela -->
                    <dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)"></dir-pagination-controls>   
                    <!-- table that shows produto record list -->
                    <div style="width: 200px;" class="input-field col s2"><label for="resultlimit"> Resultados por página: </label></div>

                    <div style="min-width: 50px;" class="input-field col s1 ">   
                        <select  name="resultlimit" id="resultlimit" ng-model="resultlimit" >
                            <option value="{{item.valor}}" ng-selected="item.valor==resultlimit" ng-repeat="item in page track by item.valor">{{item.desc}}</option>
                        </select>
                    </div>   


                    <!-- modal for for creating new produto -->
                    <form name="produtoForm" id="modal-produto-form" class="modal" novalidate>
                        <div class="modal-content">
                            <h4 id="modal-produto-title">Inserir sabor</h4>
                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" ng-model="descricaoInput" name="descricaoInput" id="descricaoInput" required aria-required="true" ng-pattern="" placeholder="Nome do sabor"/>
                                    <label for="descricaoInput">Descrição</label>
                                    <div ng-if="produtoForm.descricaoInput.$error.required && produtoForm.descricaoInput.$dirty" style="color:red">Campo obrigatório.</div>
                                </div>    
                                <div class="input-field col s12">
                                    <input type="text" maxlength="80" ng-model="ingredientesInput" name="ingredientesInput" id="ingredientesInput" required aria-required="true" ng-pattern="" placeholder="Ingredientes do sabor"/>
                                    <label for="ingredientesInput">Ingredientes (máximo 80 caracteres)</label>
                                </div>
                                <div ng-if="produtoForm.ingredientesInput.$error.required && produtoForm.ingredientesInput.$dirty" style="color:red">Campo obrigatório.</div>

                                <div class="input-field col nopad s12">
                                    <a ng-class="{'disabled': produtoForm.$invalid}" id="btn-create-produto" class="waves-effect waves-light btn customAction" ng-click="produtoForm.$valid && incluirProduto()"><i class="material-icons left">add</i>Salvar</a>

                                    <a ng-class="{'disabled': produtoForm.$invalid}" id="btn-update-produto" class="waves-effect waves-light btn customAction" ng-click="produtoForm.$valid && alterarProduto()"><i class="material-icons left">edit</i>Salvar</a>

                                    <a class="modal-action modal-close waves-effect waves-light btn customAction"><i class="material-icons left">close</i>Fechar</a>
                                </div>
                            </div>
                        </div>
                    </form> <!--	END MODAL -->

                    <!-- floating button for creating produto -->
                    <div class="fixed-action-btn" style="bottom:45px; right:24px;">
                        <a class="waves-effect waves-light btn modal-trigger btn-floating btn-large red" href="#modal-produto-form" title="inserir sabor" ng-click="showCreateForm()"><i class="large material-icons">add</i></a
                    </div> <!-- END BUTTON -->


                </div> <!-- end col s12 -->
            </div> <!-- end row -->
        </div> <!-- end container -->

        <!-- include jquery-->
        <script src="<?=base_url("js/jquery-3.2.1.min.js")?>"></script>
        <!-- material design js -->
        <script src="<?=base_url("js/leanModal.js")?>"></script>
        <script src="<?=base_url("js/materialize-0.100.2/js/materialize.min.js")?>"></script>
        <!-- include angular js -->
        <script src="<?=base_url("js/angular-1.5.7/angular.js")?>"></script>    
    </body>
</html>


<script>
                 
    $(document).ready(function () {
        $('select').material_select();
    });

    // angular js codes will be here
    var app = angular.module('myApp', ['angularUtils.directives.dirPagination']);
    app.controller('ControladorProduto', function ($scope, $http) {
        // read produtos
        $scope.produtos = <?php echo json_encode($sabores) ?>;
        
       $scope.page = [{valor: "1",desc: "1"}, 
            {valor: "3",desc: "3"},
            {valor: "5",desc: "5"},
            {valor: "10", desc: "10"},
            {valor: "20",desc: "20"},
            {valor: "100",desc: "100"}];         
        $scope.resultlimit = $scope.page[2].valor;
        
       $scope.localeSensitiveComparator = function(v1, v2) {
            // If we don't get strings, just compare by index
            if (v1.type !== 'string' || v2.type !== 'string') {
              return (v1.index < v2.index) ? -1 : 1;
            }
            // Compare strings alphabetically, taking locale into account
            return v1.value.localeCompare(v2.value);
        };      
        
        $scope.buscaSemAcento = function(actual, expected) {
            if (angular.isObject(actual)) return false;
            function removeAccents(value) {
                return value.toString().replace(/á/g, 'a').replace(/ã/g, 'a').replace(/â/g, 'a').replace(/à/g, 'a')
                        .replace(/é/g, 'e').replace(/ê/g, 'e').replace(/í/g, 'i')
                        .replace(/ó/g, 'o').replace(/õ/g, 'o').replace(/ô/g, 'o').replace(/ú/g, 'u').replace(/ñ/g, 'n');
            }
            actual = removeAccents(angular.lowercase('' + actual));
            expected = removeAccents(angular.lowercase('' + expected));
            return actual.indexOf(expected) !== -1;
        }
        
            
        $scope.showCreateForm = function () {
            // clear form
            $scope.clearForm();
            // change modal title
            $('#modal-produto-title').text("Inserir Sabor");
            // hide update produto button
            $('#btn-update-produto').hide();
            // show create produto button
            $('#btn-create-produto').show();
        }; // END SHOW CREATE FORM
        
        // clear variable / form values
        $scope.clearForm = function () {
            $scope.codigo_sabor_pizza = "";
            $scope.descricaoInput = "";
            $scope.ingredientesInput = "";
            $scope.produtoForm.$setPristine();
        }; // END CLEAR FORM

        // create new produto 
        $scope.incluirProduto = function () {
            console.log(produtoForm.descricaoInput);
            // fields in key-value pairs
            $http.post('<?=base_url("index.php/ControladorPizzas/incluirSabor")?>', {
                'descricao_sabor_pizza': $scope.descricaoInput,
                'ingredientes_sabor_pizza' : $scope.ingredientesInput
            }
            ).success(function (data, status, headers, config) {
                $scope.produtos.splice($scope.produtos.length, 0 , data);
                console.log(data);
                // tell the user new produto was created
                Materialize.toast("Dados incluídos com sucesso", 4000);
                // close modal
                $('#modal-produto-form').closeModal();
                $scope.clearForm();
            }).error(function(response) {
                alert("Erro ao incluir dados.");
                deferred.reject(response);
            });
        }; //END CREATE PRODUTO

        // retrieve record to fill out the form
        $scope.carregarProduto = function (id) {
            // change modal title
            $('#modal-produto-title').text("Alterar Sabor");
            // show udpate produto button
            $('#btn-update-produto').show();
            // show create produto button
            $('#btn-create-produto').hide();
            // post id of produto to be edited
            $http.post('<?=base_url("index.php/ControladorPizzas/buscarSaborPorCodigo")?>', {
                'codigo_sabor_pizza': id
            }).success(function (data, status, headers, config) {
                        // put the values in form
                        $scope.codigo_sabor_pizza = data["codigo_sabor_pizza"];
                        $scope.descricaoInput = data["descricao_sabor_pizza"];
                        $scope.ingredientesInput = data["ingredientes_sabor_pizza"];
                        // show modal
                        $('#modal-produto-form').openModal();
                        
                    }).error(function (data, status, headers, config) {
                        Materialize.toast("Erro ao recuperar dados", 4000);
                        deferred.reject(response);
                    });
        }; //	END READ ONE

        // update produto record / save changes
        $scope.alterarProduto = function () {
            $http.post('<?=base_url("index.php/ControladorPizzas/alterarSaborPizzaria")?>', {
                'codigo_sabor_pizza': $scope.codigo_sabor_pizza,
                'descricao_sabor_pizza': $scope.descricaoInput,
                'ingredientes_sabor_pizza' : $scope.ingredientesInput
            }).success(function (data, status, headers, config) {      
                        // tell the user produto record was updated    
                        Materialize.toast("Dados alterados com sucesso", 4000);
                        for (numero=0; numero < $scope.produtos.length; numero++){
                            if(parseInt($scope.produtos[numero].codigo_sabor_pizza) === parseInt($scope.codigo_sabor_pizza)) {
                                $scope.produtos[numero].descricao_sabor_pizza = $scope.descricaoInput;
                                $scope.produtos[numero].ingredientes_sabor_pizza = $scope.ingredientesInput;
                            }
                        }
                        // close modal
                        $('#modal-produto-form').closeModal();
                        // clear modal content
                        $scope.clearForm();
                    }).error(function (response) {
                        Materialize.toast("Erro ao alterar dados", 4000);
                    });
        }; //END UPDATE

        // delete produto record
        $scope.excluirProduto = function (codigo) {
            if (confirm("Tem certeza que deseja excluir?")) {                                                                                                                 
                $http.post('<?=base_url("index.php/ControladorPizzas/confirmarRemoverSabor")?>', {
                    'codigo_sabor_pizza': codigo
                }).success(function (data, status, headers, config) {
                    Materialize.toast("Dados excluídos com sucesso", 4000);                                           
                    for (numero=0; numero < $scope.produtos.length; numero++){
                        if($scope.produtos[numero].codigo_sabor_pizza === codigo) {
                            $scope.produtos.splice(numero, 1);
                        }
                    }
                }).error(function(response) {                                                                                    
                        Materialize.toast("Erro ao remover dados", 4000);
                        deferred.reject(response);
                });
            }
        }; //END DELETE 
        
        $scope.ativarProduto = function (codigo) {
            if (confirm("Deseja reativar esse produto? Ao ativar o produto, o bot voltará a apresentar esse produto para compra.")) {
                $http.post('<?= base_url("index.php/ControladorPizzas/ativarSabor") ?>', {
                    'codigo_sabor_pizza': codigo
                }).success(function (data, status, headers, config) {
                    // tell the user bebida record was updated
                    Materialize.toast("Dados alterados com sucesso", 4000);
                    for (numero = 0; numero < $scope.produtos.length; numero++) {
                        if (parseInt($scope.produtos[numero].codigo_sabor_pizza) === parseInt(codigo)) {
                            $scope.produtos[numero].ativo_sabor_pizza = "Ativo";
                        }

                    }
                    // close modal
                    $('#modal-bebida-form').closeModal();
                    // clear modal content
                    $scope.clearForm();
                }).error(function (response) {
                    Materialize.toast("Erro ao alterar dados", 4000);
                });
            }
        }; //END UPDATE
        
        $scope.inativarProduto = function (codigo) {
            if (confirm("Deseja inativar esse produto? Ao inativar o produto, o bot não apresentará esse produto para compra até que este esteja ativo novamente.")) {
                $http.post('<?= base_url("index.php/ControladorPizzas/inativarSabor") ?>', {
                    'codigo_sabor_pizza': codigo
                }).success(function (data, status, headers, config) {
                    // tell the user bebida record was updated
                    Materialize.toast("Dados alterados com sucesso", 4000);
                    for (numero = 0; numero < $scope.produtos.length; numero++) {
                        if (parseInt($scope.produtos[numero].codigo_sabor_pizza) === parseInt(codigo)) {
                            $scope.produtos[numero].ativo_sabor_pizza = "Inativo";
                        }

                    }
                    // close modal
                    $('#modal-bebida-form').closeModal();
                    // clear modal content
                    $scope.clearForm();
                }).error(function (response) {
                    Materialize.toast("Erro ao alterar dados", 4000);
                });
            }
        }; //END UPDATE
    }); // END ANGULAR

    app.config(function (paginationTemplateProvider) {
        paginationTemplateProvider.setString('<ul class="pagination " ng-if="1 < pages.length || !autoHide">    <li class="nopad" ng-if="boundaryLinks" \n\
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

        /**
         * Config
         */
        var moduleName = 'angularUtils.directives.dirPagination';
        var DEFAULT_ID = '__default';

        /**
         * Module
         */
        angular.module(moduleName, [])
                .directive('dirPaginate', ['$compile', '$parse', 'paginationService', dirPaginateDirective])
                .directive('dirPaginateNoCompile', noCompileDirective)
                .directive('dirPaginationControls', ['paginationService', 'paginationTemplate', dirPaginationControlsDirective])
                .filter('itemsPerPage', ['paginationService', itemsPerPageFilter])
                .service('paginationService', paginationService)
                .provider('paginationTemplate', paginationTemplateProvider)
                .run(['$templateCache', dirPaginationControlsTemplateInstaller]);

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

                    // Now that we have access to the `scope` we can interpolate any expression given in the paginationId attribute and
                    // potentially register a new ID if it evaluates to a different value than the rawId.
                    var paginationId = $parse(attrs.paginationId)(scope) || attrs.paginationId || DEFAULT_ID;

                    // (TODO: this seems sound, but I'm reverting as many bug reports followed it's introduction in 0.11.0.
                    // Needs more investigation.)
                    // In case rawId != paginationId we deregister using rawId for the sake of general cleanliness
                    // before registering using paginationId
                    // paginationService.deregisterInstance(rawId);
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

                    // Delegate to the link function returned by the new compilation of the ng-repeat
                    compiled(scope);

                    // (TODO: Reverting this due to many bug reports in v 0.11.0. Needs investigation as the
                    // principle is sound)
                    // When the scope is destroyed, we make sure to remove the reference to it in paginationService
                    // so that it can be properly garbage collected
                    // scope.$on('$destroy', function destroyDirPagination() {
                    //     paginationService.deregisterInstance(paginationId);
                    // });
                };
            }

            /**
             * If a pagination id has been specified, we need to check that it is present as the second argument passed to
             * the itemsPerPage filter. If it is not there, we add it and return the modified expression.
             *
             * @param expression
             * @param paginationId
             * @returns {*}
             */
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

            /**
             * Adds the ng-repeat directive to the element. In the case of multi-element (-start, -end) it adds the
             * appropriate multi-element ng-repeat to the first and last element in the range.
             * @param element
             * @param attrs
             * @param repeatExpression
             */
            function addNgRepeatToElement(element, attrs, repeatExpression) {
                if (element[0].hasAttribute('dir-paginate-start') || element[0].hasAttribute('data-dir-paginate-start')) {
                    // using multiElement mode (dir-paginate-start, dir-paginate-end)
                    attrs.$set('ngRepeatStart', repeatExpression);
                    element.eq(element.length - 1).attr('ng-repeat-end', true);
                } else {
                    attrs.$set('ngRepeat', repeatExpression);
                }
            }

            /**
             * Adds the dir-paginate-no-compile directive to each element in the tElement range.
             * @param tElement
             */
            function addNoCompileAttributes(tElement) {
                angular.forEach(tElement, function (el) {
                    if (el.nodeType === 1) {
                        angular.element(el).attr('dir-paginate-no-compile', true);
                    }
                });
            }

            /**
             * Removes the variations on dir-paginate (data-, -start, -end) and the dir-paginate-no-compile directives.
             * @param element
             */
            function removeTemporaryAttributes(element) {
                angular.forEach(element, function (el) {
                    if (el.nodeType === 1) {
                        angular.element(el).removeAttr('dir-paginate-no-compile');
                    }
                });
                element.eq(0).removeAttr('dir-paginate-start').removeAttr('dir-paginate').removeAttr('data-dir-paginate-start').removeAttr('data-dir-paginate');
                element.eq(element.length - 1).removeAttr('dir-paginate-end').removeAttr('data-dir-paginate-end');
            }

            /**
             * Creates a getter function for the current-page attribute, using the expression provided or a default value if
             * no current-page expression was specified.
             *
             * @param scope
             * @param attrs
             * @param paginationId
             * @returns {*}
             */
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

        /**
         * This is a helper directive that allows correct compilation when in multi-element mode (ie dir-paginate-start, dir-paginate-end).
         * It is dynamically added to all elements in the dir-paginate compile function, and it prevents further compilation of
         * any inner directives. It is then removed in the link function, and all inner directives are then manually compiled.
         */
        function noCompileDirective() {
            return {
                priority: 5000,
                terminal: true
            };
        }

        function dirPaginationControlsTemplateInstaller($templateCache) {
            $templateCache.put('angularUtils.directives.dirPagination.template', '<ul class="pagination" ng-if="1 < pages.length || !autoHide"><li ng-if="boundaryLinks" ng-class="{ disabled : pagination.current == 1 }"><a href="" ng-click="setCurrent(1)">&laquo;</a></li><li ng-if="directionLinks" ng-class="{ disabled : pagination.current == 1 }"><a href="" ng-click="setCurrent(pagination.current - 1)">&lsaquo;</a></li><li ng-repeat="pageNumber in pages track by tracker(pageNumber, $index)" ng-class="{ active : pagination.current == pageNumber, disabled : pageNumber == \'...\' || ( ! autoHide && pages.length === 1 ) }"><a href="" ng-click="setCurrent(pageNumber)">{{ pageNumber }}</a></li><li ng-if="directionLinks" ng-class="{ disabled : pagination.current == pagination.last }"><a href="" ng-click="setCurrent(pagination.current + 1)">&rsaquo;</a></li><li ng-if="boundaryLinks"  ng-class="{ disabled : pagination.current == pagination.last }"><a href="" ng-click="setCurrent(pagination.last)">&raquo;</a></li></ul>');
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

            // We need to check the paginationTemplate service to see whether a template path or
            // string has been specified, and add the `template` or `templateUrl` property to
            // the DDO as appropriate. The order of priority to decide which template to use is
            // (highest priority first):
            // 1. paginationTemplate.getString()
            // 2. attrs.templateUrl
            // 3. paginationTemplate.getPath()
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

                // rawId is the un-interpolated value of the pagination-id attribute. This is only important when the corresponding dir-paginate directive has
                // not yet been linked (e.g. if it is inside an ng-if block), and in that case it prevents this controls directive from assuming that there is
                // no corresponding dir-paginate directive and wrongly throwing an exception.
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

                /**
                 * Custom "track by" function which allows for duplicate "..." entries on long lists,
                 * yet fixes the problem of wrongly-highlighted links which happens when using
                 * "track by $index" - see https://github.com/michaelbromley/angularUtils/issues/153
                 * @param id
                 * @param index
                 * @returns {string}
                 */
                scope.tracker = function (id, index) {
                    return id + '_' + index;
                };

                function goToPage(num) {
                    if (paginationService.isRegistered(paginationId) && isValidPageNumber(num)) {
                        var oldPageNumber = scope.pagination.current;

                        scope.pages = generatePagesArray(num, paginationService.getCollectionLength(paginationId), paginationService.getItemsPerPage(paginationId), paginationRange);
                        scope.pagination.current = num;
                        updateRangeValues();

                        // if a callback has been set, then call it with the page number as the first argument
                        // and the previous page number as a second argument
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

                /**
                 * This function updates the values (lower, upper, total) of the `scope.range` object, which can be used in the pagination
                 * template to display the current page range, e.g. "showing 21 - 40 of 144 results";
                 */
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

            /**
             * Generate an array of page numbers (or the '...' string) which is used in an ng-repeat to generate the
             * links used in pagination
             *
             * @param currentPage
             * @param rowsPerPage
             * @param paginationRange
             * @param collectionLength
             * @returns {Array}
             */
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

            /**
             * Given the position in the sequence of pagination links [i], figure out what page number corresponds to that position.
             *
             * @param i
             * @param currentPage
             * @param paginationRange
             * @param totalPages
             * @returns {*}
             */
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

        /**
         * This filter slices the collection into pages based on the current page number and number of items per page.
         * serve para quebrar as paginas na quantidade maxima de itens
         * @param paginationService
         * @returns {Function}
         */
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

        /**
         * Shim for the Object.keys() method which does not exist in IE < 9
         * @param obj
         * @returns {Array}
         */
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

        /**
         * This service allows the various parts of the module to communicate and stay in sync.
         */
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

        /**
         * This provider allows global configuration of the template path used by the dir-pagination-controls directive.
         */
        function paginationTemplateProvider() {

            var templatePath = 'angularUtils.directives.dirPagination.template';
            var templateString;

            /**
             * Set a templateUrl to be used by all instances of <dir-pagination-controls>
             * @param {String} path
             */
            this.setPath = function (path) {
                templatePath = path;
            };

            /**
             * Set a string of HTML to be used as a template by all instances
             * of <dir-pagination-controls>. If both a path *and* a string have been set,
             * the string takes precedence.
             * @param {String} str
             */
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

</body>
</html>
