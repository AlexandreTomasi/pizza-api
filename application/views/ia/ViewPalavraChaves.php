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
  <h1>Palavra Chave e Conexões</h1>
  <div class="col-lg-4">

        <h4>Adicione Palavra Chave</h4>
        <div class="form-group">
          <input class="form-control" ng-model="descricao_palavra" type="text">
        </div>
        <button class="btn btn-primary" ng-click="adicionaPalavraChave()">Adicionar</button>
        <!--<button class="btn btn-danger" ng-click="vm.messWithCollection()">Limpar</button>-->

        <h4>Lista de palavras chaves</h4>
        <ul class="list-group">
          <li class="list-group-item" ng-repeat="palavra in palavras track by $index">
            {{palavra.nome_ia_palavras_chave}}
            <span class="badge glyphicon glyphicon-remove" ng-click="removePalavraChave(palavra.codigo_ia_palavras_chave)"> </span>
          </li>
        </ul>
  </div>
  <div class="col-lg-4">
     <h4>Selecione a chave para alterar</h4>
        <select class="form-control" ng-model="palavraSelecionado" ng-options="x.nome_ia_palavras_chave for x in palavras">
            <option value="">Selecione a palavra Chave</option>
        </select>
        <h4>Altera a descrição</h4>
        <div class="form-group">
          <input class="form-control" ng-model="inputDescricao" type="text">
        </div>
        <button class="btn btn-primary" ng-click="alteraPalavraChave(palavraSelecionado.codigo_ia_palavras_chave)">Alterar</button>
        
        <h4>Digite a mensagem associada</h4>
        <h5>Palavras Reservadas: @tamanhos , @sabores , @tipo extra , @extras , @bebidas </h5>
        <h6>Palavras reservadas serão substituídas na resposta por dados do banco referente a elas.</h6>
        
        <textarea class="form-control" type="text" ng-model="opcoes" name="opcoes" id="opcoes"></textarea>
        <h4></h4>
        <button class="btn btn-primary" ng-click="alteraPerguntaDaPalavraChave(palavraSelecionado)">Confirma</button>
  </div>
</div>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.8/angular.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>

    <!--<script src="js/index.js"></script>-->

</body>
<script>
   var app = angular.module('app', []);
        app.controller('myCtrl', function($scope, $http) {
            $scope.palavras = <?php echo json_encode($dados, JSON_NUMERIC_CHECK); ?>;
            $scope.bancos =<?php echo json_encode($bancos); ?>;
            $scope.opcoes = [];
                
            $scope.adicionaPalavraChave = function() {
                $http.post('<?= base_url("index.php/ia/ControladorPalavraChave/solicitarIncluirPalavraChave") ?>', {
                        'nome_ia_palavras_chave': $scope.descricao_palavra
                    }).success(function (data, status, headers, config) {
                        $scope.palavras.splice($scope.palavras.length, 0, data);
                        $scope.descricao_palavra = '';
                    }).error(function (response) {
                        alert("erro solicitarIncluirAssunto");
                        deferred.reject(response);
                    });                
            }

            
            $scope.removePalavraChave = function(codigo) {
                $http.post('<?= base_url("index.php/ia/ControladorPalavraChave/solicitarRemoverPalavraChave") ?>', {
                        'codigo_ia_palavras_chave': codigo
                    }).success(function (data, status, headers, config) {
                        for (numero = 0; numero < $scope.palavras.length; numero++) {
                            if (parseInt($scope.palavras[numero].codigo_ia_palavras_chave) == parseInt(codigo)) {
                                $scope.palavras.splice(numero, 1);
                            }
                        }
                    }).error(function (response) {
                        alert("erro solicitarRemoverAssunto");
                        deferred.reject(response);
                   }); 
            }
            //
            $scope.alteraPalavraChave = function(codigo) {
                $http.post('<?= base_url("index.php/ia/ControladorPalavraChave/solicitarAlterarPalavraChave") ?>', {
                        'nome_ia_palavras_chave': $scope.inputDescricao,
                        'codigo_ia_palavras_chave' : codigo
                    }).success(function (data, status, headers, config) {
                        for (numero = 0; numero < $scope.palavras.length; numero++) {
                            if (parseInt($scope.palavras[numero].codigo_ia_palavras_chave) == parseInt(codigo)) {
                                $scope.palavras[numero].nome_ia_palavras_chave = $scope.inputDescricao;
                            }
                        }
                    }).error(function (response) {
                        alert("erro solicitarAlterarAssunto");
                        deferred.reject(response);
                   }); 
            }
            
            $scope.alteraPerguntaDaPalavraChave = function(palavraChave) {
                if(palavraChave != null && palavraChave != "" && $scope.palavraSelecionado != null && $scope.palavraSelecionado.codigo_ia_palavras_chave != ""){
                $http.post('<?= base_url("index.php/ia/ControladorPalavraChave/solicitarAlterarPalavraChaveBanco") ?>', {
                        'resposta_ia_palavras_chave': $scope.opcoes,
                        'codigo_ia_palavras_chave' : $scope.palavraSelecionado.codigo_ia_palavras_chave
                    }).success(function (data, status, headers, config) {
                        for (numero = 0; numero < $scope.palavras.length; numero++) {
                            if (parseInt($scope.palavras[numero].codigo_ia_palavras_chave) == parseInt($scope.palavraSelecionado.codigo_ia_palavras_chave)) {
                                $scope.palavras[numero].resposta_ia_palavras_chave = $scope.opcoes;
                                alert("Alterado associação");
                                break;
                            }
                        }
                        
                    }).error(function (response) {
                        alert("erro solicitarAlterarAssunto");
                        deferred.reject(response);
                   });
               }else{
                   alert("Selecione a palavra antes");
               }
            }
            
            $scope.$watch('palavraSelecionado',function(selecionado){
                if(selecionado != null && selecionado != "" && selecionado.codigo_ia_palavras_chave != null ){
                    $scope.inputDescricao = selecionado.nome_ia_palavras_chave;
                    $scope.opcoes =selecionado.resposta_ia_palavras_chave;
                }
            });
            

        }); 
</script>

</html>