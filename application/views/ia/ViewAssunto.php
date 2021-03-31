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

        <h4>Adicione Assunto</h4>
        <div class="form-group">
          <input class="form-control" ng-model="descricao_ia_assunto" type="text">
        </div>
        <button class="btn btn-primary" ng-click="adicionaAssunto()">Adicionar</button>
        <!--<button class="btn btn-danger" ng-click="vm.messWithCollection()">Limpar</button>-->

        <h4>Lista de assuntos</h4>
        <ul class="list-group">
          <li class="list-group-item" ng-repeat="assunto in assuntos track by $index">
            {{assunto.descricao_ia_assunto}}
            <span class="badge glyphicon glyphicon-remove" ng-click="removeAssunto(assunto.codigo_ia_assunto)"> </span>
          </li>
        </ul>
  </div>
  <div class="col-lg-4">
     <h4>Selecione o Assunto para alterar</h4>
        <select class="form-control" ng-model="assuntoSelecionado" ng-options="x.descricao_ia_assunto for x in assuntos">
            <option value="">Selecione o Assunto</option>
        </select>
        <p>Item selecionado: {{ assuntoSelecionado.descricao_ia_assunto }}</p> 
        <h4>Altera a descrição</h4>
        <div class="form-group">
          <input class="form-control" ng-model="inputDescricao" type="text">
        </div>
        <button class="btn btn-primary" ng-click="alteraAssunto(assuntoSelecionado.codigo_ia_assunto)">Alterar</button>
  </div>
</div>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.8/angular.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>

    <!--<script src="js/index.js"></script>-->

</body>

<script>
   var app = angular.module('app', []);
        app.controller('myCtrl', function($scope, $http) {
            $scope.assuntos = <?php echo json_encode($dados, JSON_NUMERIC_CHECK); ?>;

            $scope.adicionaAssunto = function() {
                $http.post('<?= base_url("index.php/ia/ControladorAssunto/solicitarIncluirAssunto") ?>', {
                        'descricao_ia_assunto': $scope.descricao_ia_assunto
                    }).success(function (data, status, headers, config) {
                        $scope.assuntos.splice($scope.assuntos.length, 0, data);
                        $scope.descricao_ia_assunto = '';
                    }).error(function (response) {
                        alert("erro solicitarIncluirAssunto");
                        deferred.reject(response);
                    });                
            }

            
            $scope.removeAssunto = function(codigo) {
                $http.post('<?= base_url("index.php/ia/ControladorAssunto/solicitarRemoverAssunto") ?>', {
                        'codigo_ia_assunto': codigo
                    }).success(function (data, status, headers, config) {
                        for (numero = 0; numero < $scope.assuntos.length; numero++) {
                            if (parseInt($scope.assuntos[numero].codigo_ia_assunto) == parseInt(codigo)) {
                                $scope.assuntos.splice(numero, 1);
                            }
                        }
                    }).error(function (response) {
                        alert("erro solicitarRemoverAssunto");
                        deferred.reject(response);
                   }); 
            }
            //
            $scope.alteraAssunto = function(codigo) {
                $http.post('<?= base_url("index.php/ia/ControladorAssunto/solicitarAlterarAssunto") ?>', {
                        'descricao_ia_assunto': $scope.inputDescricao,
                        'codigo_ia_assunto' : codigo
                    }).success(function (data, status, headers, config) {
                        for (numero = 0; numero < $scope.assuntos.length; numero++) {
                            if (parseInt($scope.assuntos[numero].codigo_ia_assunto) == parseInt(codigo)) {
                                $scope.assuntos[numero].descricao_ia_assunto = $scope.inputDescricao;
                            }
                        }
                    }).error(function (response) {
                        alert("erro solicitarAlterarAssunto");
                        deferred.reject(response);
                   }); 
            }
            
            $scope.$watch('assuntoSelecionado',function(selecionado){
                if(selecionado != null && selecionado != "" && selecionado.codigo_ia_assunto != null ){
                    $scope.inputDescricao = selecionado.descricao_ia_assunto;
                }
            });

        }); 
</script>
</html>