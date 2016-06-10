(function () {
    'use strict';

    angular
        .module('app')
        .factory('VoteService', VoteService);

    VoteService.$inject = ['$timeout', '$filter', '$q', '$http'];

    function VoteService($timeout, $filter, $q, $http) {

        var service = {};
        
        service.submitVote = submitVote;

        return service;

        function submitVote(vote) {

            var deferred = $q.defer();

            $timeout(function () {
                saveVote(vote)
                
                    .then(function (response) {

                        if(response.status === 200) {

                            if (response.data === 'success') {

                                deferred.resolve({ success: true, message: response.data });

                            } else {

                                deferred.resolve({ success: false, message: response.data });
                            }
                        }

                    });
            }, 1000);

            return deferred.promise;
        }

        function saveVote(vote) {

            console.log(vote);

            return $http.post('http://ec2-75-101-215-227.compute-1.amazonaws.com:8888/submitvote', vote).then(handleSuccess, handleError('Error submitted vote'));
        }

        function handleSuccess(data) {
            return data;
        }

        function handleError(error) {
            return function () {
                return { success: false, message: error };
            };
        }

    }
})();