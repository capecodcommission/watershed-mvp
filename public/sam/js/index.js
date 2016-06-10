angular.module('app', [])

.controller('mainController', function($scope) {
  
  // BUTTONS ======================
  
  // define some random object
  $scope.bigData = {};
  
  $scope.bigData.breakfast = false;
  $scope.bigData.lunch = false;
  $scope.bigData.dinner = false;
  
  // COLLAPSE =====================
  $scope.isCollapsed = false;
  
});