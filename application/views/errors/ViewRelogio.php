<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>mdPickers - date/time pickers for Angular Material</title>
  <meta name="viewport" content="width=device-width, user-scalable=no, 
initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
  
  <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto:400,700'>
<link rel='stylesheet prefetch' href='https://fonts.googleapis.com/icon?family=Material+Icons'>
<link rel='stylesheet prefetch' href='https://cdn.rawgit.com/angular/bower-material/v1.0.6/angular-material.min.css'>
<link rel='stylesheet prefetch' href='https://cdn.rawgit.com/alenaksu/mdPickers/0.7.4/dist/mdPickers.min.css'>

      <style>
          body {
            font-family: 'Roboto', sans-serif;
          }
          #content {
            height: 100%;
          }
          #content > md-content {
            background-color: #eee;
          }
      </style>
  
</head>

<body>
  <md-content id="content" layout="column" flex ng-app="app" ng-controller="MainCtrl as ctrl">
  
  <md-content flex layout="column" layout-align="center center" layout-padding>
    
    <div layout="row" ng-form="demoForm">
          <div layout-padding>
            
          <div layout-padding>
            <h2>Time picker</h2>
            <div>
              <h4 class="md-subhead">Standard time picker</h4>            
              <mdp-time-picker ng-model="currentDate"></mdp-time-picker>
            </div>
            
            
          </div>
        </div>
  </md-content>
</md-content>
  <!--<script src='https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js'></script>-->
<script src='https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js'></script>
<script src='https://cdn.rawgit.com/angular/bower-material/v1.0.6/angular-material.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js'></script>
<script src='https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular-animate.min.js'></script>
<script src='https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular-aria.min.js'></script>
<script src='https://cdn.rawgit.com/alenaksu/mdPickers/0.7.4/dist/mdPickers.min.js'></script>
<script src='https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular-messages.min.js'></script>

    <script>
        (function() {
                var module = angular.module("app", [
            "ngMaterial",
            "ngAnimate",
            "ngAria",
            "ngMessages",
            "mdPickers"
          ]); 

          module.controller("MainCtrl", ['$scope', '$mdpDatePicker', '$mdpTimePicker', function($scope, $http){
                $scope.currentDate = new Date();
          }]);
        })();
    </script>

</body>
</html>
