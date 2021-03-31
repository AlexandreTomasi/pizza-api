<!DOCTYPE html>
<?php
include($_SERVER['DOCUMENT_ROOT'] . '/pizza-api/application/views/menu/MenuGerencia.php');
?>
<html >
<head>
  <meta charset="UTF-8">
  <title>Configurações Personagem</title>

  <link rel='stylesheet prefetch' href="<?=base_url("css/bootstrap.css")?>">

</head>

<body>
  <div class="container-fluid" ng-app="app" ng-controller="myCtrl as vm">
  <h1>Respostas Personagem</h1>
  <div class="col-lg-4">
        <h4>Selecione o grupo de respostas</h4>
        <select class="form-control" ng-model="assuntoSelecionado" ng-options="x.grupo for x in respostas">
            <option value="">Selecione o grupo</option>
        </select>
        <p>Item selecionado: {{ assuntoSelecionado.grupo }}</p>
        <button class="btn btn-primary" ng-click="aumentarGrupo()">Criar mais uma sequencia do grupo</button>
        </br></br>
        <button class="btn btn-primary" ng-click="removerGrupoAtual()">Remover essa sequencia do grupo</button>
  </div>
  <div class="col-lg-4">
        <h4>Respostas</h4>
        <div class="form-group">
          <input class="form-control" ng-model="novaResposta" type="text">
        </div>
        <button class="btn btn-primary" ng-click="adicionaResposta()">Adicionar</button>
        <!--<button class="btn btn-danger" ng-click="vm.messWithCollection()">Limpar</button>-->

        <h4>Lista de respostas</h4>
        <ul class="list-group">
          <li class="list-group-item" ng-repeat="resposta in frases track by $index">
            {{resposta}}          
            <span class="badge glyphicon glyphicon-remove" ng-click="removeResposta(resposta)"> </span>
            </br></br>
          </li>
        </ul>
  </div>
</div>
    <!-- include jquery-->
    <script src="<?=base_url("js/jquery-3.2.1.min.js")?>"></script>
    <!-- include angular js -->
        <script src="<?=base_url("js/angular-1.5.7/angular.js")?>"></script>

</body>

<script>
   var app = angular.module('app', []);
        app.controller('myCtrl', function($scope, $http) {
            $scope.respostas = <?php echo json_encode($dados, JSON_NUMERIC_CHECK); ?>;
            $scope.frases = [];
            
            $scope.adicionaResposta = function() {               
                if($scope.novaResposta == null || $scope.novaResposta == ""){
                    $scope.novaResposta = "";
                }
                $http.post('<?= base_url("index.php/ia/ControladorPersonMsgsPadrao/solicitarIncluirResposta") ?>', {
                        'texto_resposta': $scope.novaResposta,
                        'codigo_grupo': $scope.assuntoSelecionado.codigo
                    }).success(function (data, status, headers, config) {
                        for (numero = 0; numero < $scope.respostas.length; numero++) {
                            if (parseInt($scope.respostas[numero].codigo) == parseInt($scope.assuntoSelecionado.codigo)) {
                                $scope.respostas[numero].respostas.splice($scope.respostas[numero].respostas.length, 0, $scope.novaResposta);
                                $scope.frases =  $scope.respostas[numero].respostas;
                            }
                         }
                         
                        $scope.novaResposta = '';
                    }).error(function (response) {
                        alert("erro solicitarIncluirPalavra");
                        deferred.reject(response);
                    });                
            }
            
            $scope.removeResposta = function(resposta) {
                $http.post('<?= base_url("index.php/ia/ControladorPersonMsgsPadrao/solicitarRemoverResposta") ?>', {
                        'texto_resposta': resposta,
                        'codigo_grupo': $scope.assuntoSelecionado.codigo
                    }).success(function (data, status, headers, config) {
                        for (numero = 0; numero < $scope.respostas.length; numero++) {
                            if (parseInt($scope.respostas[numero].codigo) == parseInt($scope.assuntoSelecionado.codigo)) {
                                //$scope.respostas[numero].respostas = data.novo;
                                if(data.novo == ""){
                                    $scope.respostas[numero].respostas = [];
                                    $scope.frases = [];
                                }else{
                                    $scope.respostas[numero].respostas = data.novo;
                                    $scope.frases = data.novo;
                                }
                            }
                         }
                    }).error(function (response) {
                        alert("erro solicitarRemoverPalavra");
                        deferred.reject(response);
                   }); 
            }
           
            
            $scope.$watch('assuntoSelecionado',function(selecionado){   
                if(selecionado != null && selecionado != "" && selecionado.codigo != null ){
                    $scope.frases = selecionado.respostas;
                }
            });
            
            $scope.aumentarGrupo = function() {               
                if($scope.assuntoSelecionado.codigo == null || $scope.assuntoSelecionado.codigo == ""){
                    return "";
                }
                $http.post('<?= base_url("index.php/ia/ControladorPersonMsgsPadrao/solicitarAumentarGrupo") ?>', {
                        'codigo_grupo': $scope.assuntoSelecionado.codigo
                    }).success(function (data, status, headers, config) {
                       $scope.respostas =  data.resposta;
                        
                    }).error(function (response) {
                        alert("erro solicitarIncluirPalavra");
                        deferred.reject(response);
                    });                
            }
            $scope.removerGrupoAtual = function() {
                if($scope.assuntoSelecionado.codigo == null || $scope.assuntoSelecionado.codigo == ""){
                    return "";
                }
                decisao = confirm("Tem certeza que deseja remover o grupo?");
                if (!decisao) {
                    return "";
                }
                $http.post('<?= base_url("index.php/ia/ControladorPersonMsgsPadrao/removerGrupo") ?>', {
                        'codigo_grupo': $scope.assuntoSelecionado.codigo
                    }).success(function (data, status, headers, config) {
                        for (numero = 0; numero < $scope.respostas.length; numero++) {
                            if (parseInt($scope.respostas[numero].codigo) == parseInt($scope.assuntoSelecionado.codigo)) {
                                    $scope.respostas.splice(numero, 1);
                                }
                            }
                        
                    }).error(function (response) {
                        alert("erro solicitarIncluirPalavra");
                        deferred.reject(response);
                    });                  
            }
        }); 
</script>
</html>