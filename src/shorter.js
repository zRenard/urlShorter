var shorterApp = angular.module('shorterApp', ['ngAnimate', 'ngRoute', 'shorterControler', 'monospaced.qrcode']).config([
		'$compileProvider',
    function ($compileProvider) {
        $compileProvider.aHrefSanitizationWhitelist(/^\s*(https?):/);
		}]);

var shorterControler = angular.module('shorterControler', []);

shorterControler.controller('qrCode', ['$scope', '$location', function ($scope, $location) {
    function refreshValues() {
        $scope.locationAbsUrl = $location.absUrl();
    }
    refreshValues();
    $scope.$on("$locationChangeSuccess", function (event) {
        refreshValues();
    });
    if ($scope.srcID == null || $scope.srcID == '' || document.querySelector($scope.srcID) == null) {
        $scope.qrLocation = $scope.locationAbsUrl;
    } else {
        $scope.qrLocation = angular.element(document.querySelector($scope.srcID))[0].href;
    }
}]);
