(function () {
    'use strict';

    angular
        .module('app')
        .factory('ChartDataService', ChartDataService);

    ChartDataService.$inject = ['$timeout', '$filter', '$q', '$http'];

    function ChartDataService($timeout, $filter, $q, $http) {

        var service = {};
        
        service.getChartData = getChartData;
        
        service.getChartJson = getChartJson;

        return service;

        function getChartData(scenarioId) {

            var deferred = $q.defer();

            $timeout(function () {
                getCData(scenarioId)
                
                    .then(function (response) {

                    	console.log('jjj'+response.data);
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

        function getChartJson(scenarioId) {

            return $http.get('http://ec2-75-101-215-227.compute-1.amazonaws.com:8888/dummy/'+scenarioId).then(handleSuccess, handleError('Error submitted vote'));
            /*return {
                "donutdata": [{
                    "age": "Community",
                    "population": 33,
                    "color": "#14A6CC"
                }, {
                    "age": "Cost",
                    "population": 34,
                    "color": "#BEDB39"
                }, {
                    "age": "Confidence",
                    "population": 33,
                    "color": "#E85305"
                }],
                "asterdata": [{
                    "id": "FIS",
                    "order": "1",
                    "score": "60",
                    "weight": "1",
                    "color": "#7AFFFF",
                    "label": "Growth Compatibility"
                }, {
                    "id": "AO",
                    "order": "2",
                    "score": "20",
                    "weight": "1",
                    "color": "#14A6CC",
                    "label": "Construction and O&M Jobs Created"
                }, {
                    "id": "NP",
                    "order": "3",
                    "score": "20",
                    "weight": "1",
                    "color": "#004066",
                    "label": "Property Value Loss Avoided"
                }, {
                    "id": "CS",
                    "order": "4",
                    "score": "70",
                    "weight": "1",
                    "color": "#E4FF5F",
                    "label": "Capital Cost"
                }, {
                    "id": "CP",
                    "order": "5",
                    "score": "20",
                    "weight": "1",
                    "color": "#BEDB39",
                    "label": "Operation & Maintenance Cost"
                }, {
                    "id": "TR",
                    "order": "6",
                    "score": "10",
                    "weight": "1",
                    "color": "#98B513",
                    "label": "Life Cycle Cost"
                }, {
                    "id": "LIV",
                    "order": "7",
                    "score": "30",
                    "weight": "1",
                    "color": "#FFB96B",
                    "label": "Useful Number of Years"
                }, {
                    "id": "ICO",
                    "order": "8",
                    "score": "30",
                    "weight": "1",
                    "color": "#E85305",
                    "label": "Variability in Performance"
                }, {
                    "id": "CW",
                    "order": "9",
                    "score": "40",
                    "weight": "1",
                    "color": "#820000",
                    "label": "Resiliency to Flooding"
                }]
            };*/
        }

        function handleSuccess(data) {
        	console.log(data);
            return data;
        }

        function handleError(error) {
            return function () {
                return { success: false, message: error };
            };
        }

    }
})();