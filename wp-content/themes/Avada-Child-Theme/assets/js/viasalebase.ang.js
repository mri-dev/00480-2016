var app = angular.module('viasalebase', []);

app.controller( 'formValidator', function($scope){
  $scope.orderer = {
    dob: "",
    tel: ""
  };
  $scope.travellers = [];
} );
