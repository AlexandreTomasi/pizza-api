<!DOCTYPE html>
<?php
include($_SERVER['DOCUMENT_ROOT'] . '/pizza-api/application/views/menu/MenuGerencia.php');
?>

<html >
<head>
  <meta charset="UTF-8">
  <title></title>

  <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css'>
  <style>
      textarea {
        height:-webkit-fill-available;
        margin-left: 5%;
        max-width:95%;
        color:#999;
        font-weight:400;
        font-size:14px;
        width:100%;
        background:#fff;
        border-radius:3px;
        line-height:2em;
        border:none;
        box-shadow:0px 0px 5px 1px rgba(0,0,0,0.1);
        padding:30px;
      }

      * {
        -webkit-font-smoothing:antialiased !important;
      }
  </style>
</head>

<body >
    <div class="container-fluid" ng-app="app" ng-controller="myCtrl as vm">
        <h1></h1>
        <div class="col-lg-11">
            <table >
                <div >
                    <button style="margin-left: 50%" class="btn btn-primary" ng-click="imprimirTexto()">Imprimir</button>
                 </div>
                <div id="teste">
                    <textarea  style="font: bold 14px Lucida Console" class="materialize-textarea" readonly="true" type="text" ng-model="texto" name="texto" id="texto"></textarea>                                    
                </div> 
            </table>
            
      
        </div>
    </div>
    
            <!-- include jquery -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>


        <!-- include angular js -->
        <script src="<?=base_url("js/angular-1.5.7/angular.js")?>"></script>
</body>
</html>

<script>    
    function printTextArea() {
	 childWindow = window.open('','childWindow','location=yes, menubar=yes, toolbar=yes');
         childWindow.document.open();
	 childWindow.document.write('<html><head></head><body style="font: bold 14px Lucida Console">');
         childWindow.document.write(document.getElementById('texto').value.replace(/\n/gi,'<br>').replace(/\s/g,'&nbsp;'));
	 childWindow.document.write('</body></html>');
	 childWindow.print();
	 childWindow.document.close();
	 childWindow.close();
    }
 </script>
<script>
   var app = angular.module('app', []);
        app.controller('myCtrl', function($scope, $http, $window) {
            $scope.texto = <?php echo json_encode($dados, JSON_NUMERIC_CHECK); ?>;
            $scope.imprimirTexto = function() {
                $http.post('<?= base_url("index.php/gerencia/ControladorManterGerencia/buscarPedidoFormatado") ?>', {
                        'texto': $scope.texto
                    }).success(function (data, status, headers, config) {
                        $scope.texto = data["dados"];
                              /*  childWindow = $window.open('','childWindow','location=yes, menubar=yes, toolbar=yes');
                                $window.document.open();
                                $window.document.write('<html><head></head><body style="font: bold 14px Lucida Console">');
                                $window.document.write($scope.texto.replace(/\n/gi,'<br>').replace(/\s/g,'&nbsp;'));
                                $window.document.write('</body></html>');
                                $window.print();
                                $window.document.close();
                                $window.close();*/
                                 //  childWindow = $window.open('','childWindow','location=yes, menubar=yes, toolbar=yes');
                                 //   document.open();
                                  //  document.write('<html><head></head><body style="font: bold 14px Lucida Console">');
                                   // document.write($scope.texto.replace(/\n/gi,'<br>').replace(/\s/g,'&nbsp;'));
                                   // document.write('</body></html>');
                                    //$window.print();
                                  //  document.close();
                                    //childWindow.close();
                                //printTextArea();
                                
                                //pega o Html da DIV
                                var divElements = document.getElementById('teste').innerHTML;
                                //pega o HTML de toda tag Body
                                var oldPage = document.body.innerHTML;

                                //Alterna o body 
                                document.body.innerHTML ='<html><head></head><body style="font: bold 14px Lucida Console">'+(data["dados"].replace(/\n/gi,'<br>').replace(/\s/g,'&nbsp;'))
                                +('</body></html>');
                               // document.body.innerHTML = 
                                //  '<html><head><title></title></head><body style="font: bold 14px Lucida Console">' + data["dados"] + "</body>";

                                //Imprime o body atual
                                $window.print();

                                //Retorna o conteudo original da página. 
                                document.body.innerHTML = oldPage;

                    }).error(function (response) {
                        alert("erro ");
                   }); 
                //printTextArea();
            }

        }); 
</script>


