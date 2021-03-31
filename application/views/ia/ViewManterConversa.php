<!DOCTYPE html>
<?php
include($_SERVER['DOCUMENT_ROOT'] . '/pizza-api/application/views/menu/MenuGerencia.php');
?>
<html >
<head>
  <meta charset="UTF-8">
  <title>Inteligencia Artificial</title>

  <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css'>

</head>

<body>
  <div class="container-fluid" ng-app="app" ng-controller="myCtrl as vm">
  <h1>Conversação</h1>
  <div class="col-lg-4">
        <h4>Selecione o Assunto</h4>
        <select class="form-control" ng-model="assuntoSelecionado" ng-options="x.descricao_ia_assunto for x in assuntos">
            <option value="">Selecione o assunto</option>
        </select>
        <p>Item selecionado: {{ assuntoSelecionado.descricao_ia_assunto }}</p>

        <h4>Adicione Pergunta</h4>
        <div class="form-group">
          <input class="form-control" ng-model="novaPalavra" type="text">
        </div>
        <button class="btn btn-primary" ng-click="adicionaPalavra()">Adicionar</button>
        <!--<button class="btn btn-danger" ng-click="vm.messWithCollection()">Limpar</button>-->

        <h4>Lista de perguntas</h4>
        <ul class="list-group">
          <li class="list-group-item" ng-repeat="palavra in palavras track by $index">
            {{palavra.descricao_ia_conversacao}}          
            <span class="badge glyphicon glyphicon-remove" ng-click="removePalavra(palavra.codigo_ia_conversacao , 0)"> </span>
          </li>
        </ul>
  </div>
  <div class="col-lg-4">
        <h4>Resposta padrão</h4>
        <div class="form-group">
          <input class="form-control" ng-model="novaResposta" type="text">
        </div>
        <button class="btn btn-primary" ng-click="adicionaResposta()">Adicionar</button>
        <!--<button class="btn btn-danger" ng-click="vm.messWithCollection()">Limpar</button>-->

        <h4>Lista de respostas</h4>
        <ul class="list-group">
          <li class="list-group-item" ng-repeat="resposta in respostas track by $index">
            {{resposta.descricao_ia_conversacao}}          
            <span class="badge glyphicon glyphicon-remove" ng-click="removePalavra(resposta.codigo_ia_conversacao ,1)"> </span>
            </br></br>
          </li>
        </ul>
  </div>
  <div class="col-lg-4">
      <h4>Teste conversação</h4>
      <div class="form-group">
          <input class="form-control" ng-model="testerEntrada" type="text">
      </div>
      <button class="btn btn-primary" ng-click="simular()">Testar</button>
      <h4>Resposta: {{testerResposta}}</h4>
      
  </div>
</div>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.8/angular.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>

    <!--<script src="js/index.js"></script>-->

</body>

<script>
   var app = angular.module('app', []);
        app.controller('myCtrl', function($scope, $http) {
            $scope.respostas = [];       
            $scope.palavras =[];
           // $scope.tipos = ['saudação'];
            $scope.assuntos = <?php echo json_encode($dados, JSON_NUMERIC_CHECK); ?>;

            $scope.adicionaPalavra = function() {
                $http.post('<?= base_url("index.php/ia/ControladorConversa/solicitarIncluirPergunta") ?>', {
                        'novaPalavra': $scope.novaPalavra,
                        'assuntoSelecionado': $scope.assuntoSelecionado.codigo_ia_assunto
                    }).success(function (data, status, headers, config) {
                        $scope.palavras.splice($scope.palavras.length, 0, data);
                        $scope.novaPalavra = '';
                    }).error(function (response) {
                        alert("erro solicitarIncluirPalavra");
                        deferred.reject(response);
                    });                
            }
            
            $scope.adicionaResposta = function() {               
                if($scope.novaResposta == null || $scope.novaResposta == ""){
                    $scope.novaResposta = "";
                }
                $http.post('<?= base_url("index.php/ia/ControladorConversa/solicitarIncluirResposta") ?>', {
                        'texto_resposta': $scope.novaResposta,
                        'assuntoSelecionado': $scope.assuntoSelecionado.codigo_ia_assunto
                    }).success(function (data, status, headers, config) {
                        $scope.respostas.splice($scope.respostas.length, 0, data);
                        $scope.novaResposta = '';
                    }).error(function (response) {
                        alert("erro solicitarIncluirPalavra");
                        deferred.reject(response);
                    });                
            }
            
            $scope.removePalavra = function(codigo, op) {
                $http.post('<?= base_url("index.php/ia/ControladorConversa/solicitarRemoverPerguntaOuResposta") ?>', {
                        'codigo_ia_conversacao': codigo
                    }).success(function (data, status, headers, config) {
                        if(op == 0){
                            for (numero = 0; numero < $scope.palavras.length; numero++) {
                                if (parseInt($scope.palavras[numero].codigo_ia_conversacao) == parseInt(codigo)) {
                                    $scope.palavras.splice(numero, 1);
                                }
                            }
                        }else{
                            for (numero = 0; numero < $scope.respostas.length; numero++) {
                                if (parseInt($scope.respostas[numero].codigo_ia_conversacao) == parseInt(codigo)) {
                                    $scope.respostas.splice(numero, 1);
                                }
                            }
                        }
                    }).error(function (response) {
                        alert("erro solicitarRemoverPalavra");
                        deferred.reject(response);
                   }); 
            }
           
            
            $scope.simular = function(){
                if($scope.testerEntrada != null){
                    //var op = Math.floor(Math.random() * $scope.respostas.length )
                    //$scope.testerResposta = $scope.respostas[op];
                    $http.post('<?= base_url("index.php/ia/ControladorConversa/solicitarResposta") ?>', {
                        'pergunta': $scope.testerEntrada
                    }).success(function (data, status, headers, config) {
                        $scope.testerResposta = data;
                    }).error(function (response) {
                        alert("erro solicitarResposta");
                        deferred.reject(response);
                   }); 
                    
                }else{
                    $scope.testerResposta ='';
                }
            }
            
            $scope.$watch('assuntoSelecionado',function(selecionado){
                if(selecionado != null && selecionado != "" && selecionado.codigo_ia_assunto != null ){
                    $http.post('<?= base_url("index.php/ia/ControladorConversa/solicitarBuscarPergunta") ?>', {
                        'assuntoSelecionado': selecionado.codigo_ia_assunto
                    }).success(function (data, status, headers, config) {
                        $scope.palavras = data;
                    }).error(function (response) {
                        alert("erro solicitarBuscarPalavra");
                        deferred.reject(response);
                    });
                    
                    $http.post('<?= base_url("index.php/ia/ControladorConversa/solicitarBuscarResposta") ?>', {
                        'assuntoSelecionado': selecionado.codigo_ia_assunto
                    }).success(function (data, status, headers, config) {
                        $scope.respostas = data;
                    }).error(function (response) {
                        alert("erro solicitarBuscarResposta");
                        deferred.reject(response);
                    });
                }
            });
            
        }); 
</script>
</html>