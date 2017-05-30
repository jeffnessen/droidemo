var droidApp = angular.module('droidApp', [
 'ngAria',
 'ngMaterial',
 'ngAnimate'
]);

droidApp.controller('questionsListController', function($scope, $http){
    console.log("Loading questionsListController");
    $scope.listForm = {};

    $scope.getQuestions = function() {
      var questions = [];
	    $http.get('http://localhost:40101/questions').then(
	      function(ldata) {
          console.log('Successfully pulled data');
		      $scope.questions = ldata;
          console.log(ldata);
        },
	      function(ldata) {
		       alert("Error pulling data");
           console.log('error pulling data');
        });
    };

    $scope.listForm.listSubmit = function() {
      console.log('Submitting data');
      $http.post('http://localhost:8080/testtarget.php', $scope.listForm).then(
         function(pdata) {
           console.log(pdata);
         },
         function(err){
           console.log(err);
         });
    };
    $scope.getVname = function () {
      var rname = {};
      rname = $scope.question.vname;
      return(rname);
    };
})
