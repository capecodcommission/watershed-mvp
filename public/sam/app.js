(function () {
'use strict';

// Declare app level module which depends on views, and components
angular.module('app', [
    'ngRoute',
    'ngCookies',
    'app.home',
    'ui.bootstrap',
    'ui.slider'
])
 		.config(config)
        .run(run);

config.$inject = ['$routeProvider', '$locationProvider'];
function config($routeProvider, $locationProvider) {
	
	 $routeProvider.when('/home', {
	        templateUrl: 'home/home.view.html',
	        controller: 'homeCtrl'
	    }).
	    
	    when('/login', {
	        templateUrl: 'login/login.view.html',
	        controller: 'LoginController'
	    }).
	    
	    when('/dashboard', {
	        templateUrl: 'dashboard/dashboard.view.html',
	        controller: 'DashboardController'
	    }).
	    
	    otherwise({redirectTo: '/home'});
}


run.$inject = ['$rootScope', '$location', '$cookieStore', '$http'];
function run($rootScope, $location, $cookieStore, $http) {
    
	//set loggedinuser
	$rootScope.isLoggedInUser = false;
	// keep user logged in after page refresh
    $rootScope.globals = $cookieStore.get('globals') || {};
    if ($rootScope.globals.currentUser) {
        $http.defaults.headers.common['Authorization'] = 'Basic ' + $rootScope.globals.currentUser.authdata; // jshint ignore:line
    }

    $rootScope.$on('$locationChangeStart', function (event, next, current) {
    	
    	//alert($location.path());
        // redirect to login page if not logged in and trying to access a restricted page
        var restrictedPage = $.inArray($location.path(), ['/dashboard']) === -1;
        
       if($location.path().indexOf('/home')!= -1) {
    	   
    	 restrictedPage=false;
       }
        	
        var loggedIn = $rootScope.globals.currentUser;
        
        if($location.path()=='') {
        	
        	$location.path('/home');
        }else {
        	
        	if (restrictedPage && !loggedIn) {
             	
                $location.path('/login');
             }
        }
        	 
       
    });
}

})();






