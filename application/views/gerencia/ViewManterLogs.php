<!DOCTYPE html>
<?php
include($_SERVER['DOCUMENT_ROOT'] . '/pizza-api/application/views/menu/MenuGerencia.php');
?>

<html >
<head>
  <meta charset="UTF-8">
  <title>Logs</title>

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

<body>
    <div class="container-fluid" ng-app="app" ng-controller="myCtrl as vm">
        <h1>Logs</h1>
        <div class="col-lg-11">
            <table >
                <div>
                        <textarea class="materialize-textarea" readonly="true" type="text" ng-model="texto" name="texto" id="texto"></textarea>                                    
                </div> 
            </table>
            
      
        </div>
    </div>
    
            <!-- include jquery -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

        <!-- material design js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/js/materialize.min.js"></script>

        <!-- include angular js -->
        <script src="<?=base_url("js/angular-1.5.7/angular.js")?>"></script>
</body>
</html>
<script>
   var app = angular.module('app', []);
        app.controller('myCtrl', function($scope, $http) {
            $scope.texto = <?php echo json_encode($dados, JSON_NUMERIC_CHECK); ?>;


        }); 
</script>