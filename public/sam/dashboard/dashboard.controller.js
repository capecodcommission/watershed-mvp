(function () {
    'use strict';

    angular
        .module('app')
        .controller('DashboardController', DashboardController);

    DashboardController.$inject = ['$rootScope', 'ChartDataService'];
    function DashboardController( $rootScope, ChartDataService, $scope) {
        
    	console.log('dashboard controller');
    	
    	var chartData='';
    	
    	$rootScope.search = function search(scenarioId) {
        	
    		ChartDataService.getChartJson(scenarioId)
            .then(function (response) {
            	
            	if(response.status === 200) {
            		chartData=response.data;
                	populateChart();
            	} else {
            		console.log('get chart data not successful');
            	}
            	
            });
    		
        	/*ChartDataService.getChartJson(scenarioId, function (response) {
        		
        		console.log(response);
                if (response.statusText==='OK') {
                	console.log('get chart data successful');
                	chartData=response.data;
                	populateChart();
                } else {
                	console.log('get chart data not successful');
                }
            });*/
        };
    	
    	function populateChart() {
    		
    		var donutData = [];
    	    var asterData = [];
    	    var pieChart = null;
    	    var asterpath = null;
    	    var outerPath = null;

    	    //Declare Screen Size
    	    var width = 300,
    	            height = 300,
    	            radius = Math.min(width, height) / 2;

    	    //Get Color schme
    	    var color = d3.scale.category20();

    	    /************Draw outer Pie chart***********/
    	    //Get Inital Arc & Pie & SVG
    	    var arc = d3.svg.arc()
    	            .outerRadius(radius - 10)
    	            .innerRadius(radius - 43);

    	    var pie = d3.layout.pie()
    	            .sort(null)
    	            .value(function(d) { return d.population; });

    	    var svg = d3.select("#donutAsterchartArea").append("svg")
    	            .attr("width", width)
    	            .attr("height", height)
    	            .append("g")
    	            .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

    	    //Draw PIE chart using initial data

    	    //Store data to global variable
    	   // donutData =  getDonutData();
    	    donutData = chartData.donutdata;


    	    pieChart = svg.datum(donutData).selectAll("path")
    	            .data(pie)
    	            .enter().append("path")
    	            .attr("fill", function(d, i) { return d.data.color; })
    	            .attr("d", arc)
    	            .each(function(d) { this._current = d; }); // store the initial angles*/

    	    //Draw Aster Chart
    	    var     asterwidth = width*0.375,
    	            asterheight = height*0.72,
    	            asterradius = Math.min(asterwidth, asterheight) / 2,
    	            innerRadius = 0.3 * asterradius;

    	    var asterpie = d3.layout.pie()
    	            .sort(null)
    	            .value(function(d) { return d.width; });

    	    var astertip = d3.tip()
    	            .attr('class', 'd3-tip')
    	            .offset([0, 0])
    	            .html(function(d) {
    	              return d.data.label + ": <span style='color:orangered'>" + d.data.score + "%</span>";
    	            });

    	    var asterarc = d3.svg.arc()
    	            .innerRadius(innerRadius)
    	            .outerRadius(function (d) {
    	              return (asterradius - innerRadius) * (d.data.score / 100.0) + innerRadius;
    	            });

    	    var outlineArc = d3.svg.arc()
    	            .innerRadius(innerRadius)
    	            .outerRadius(asterradius);

    	    var astersvg = d3.select("#donutAsterchartArea").append("svg")
    	            .attr("width", asterwidth)
    	            .attr("height", asterheight)
    	            .append("g")
    	            .attr("transform", "translate(" + asterwidth / 2 + "," + asterheight / 2 + ")");

    	    astersvg.call(astertip);

    	    //asterData = getAsterData();
    	    asterData = chartData.asterdata;

    	    asterData.forEach(function(d) {
    	      d.id     =  d.id;
    	      d.order  = +d.order;
    	      d.color  =  d.color;
    	      d.weight = +d.weight;
    	      d.score  = +d.score;
    	      d.width  = +d.weight;
    	      d.label  =  d.label;
    	    });
    	    // for (var i = 0; i < data.score; i++) { console.log(data[i].id) }

    	    asterpath = svg.selectAll(".solidArc")
    	            .data(asterpie(asterData))
    	            .enter().append("path")
    	            .attr("fill", function(d) { return d.data.color; })
    	            .attr("class", "solidArc")
    	            .attr("stroke", "#f8f8f8")
    	            .attr("d", asterarc)
    	            .each(function(d) { this._astercurrent = d; })// store the initial angles*/
    	            .on('mouseover', astertip.show)
    	            .on('mouseout', astertip.hide);

    	    outerPath = svg.selectAll(".outlineArc")
    	            .data(asterpie(asterData))
    	            .enter().append("path")
    	            .attr("fill", "none")
    	            .attr("stroke", "#f8f8f8")
    	            .attr("class", "outlineArc")
    	            .attr("d", outlineArc)
    	            .each(function(d) { this._asteroutercurrent = d; })// store the initial angles*/;


    	    // calculate the weighted mean score
    	    var asterscore =
    	            asterData.reduce(function(a, b) {
    	              //console.log('a:' + a + ', b.score: ' + b.score + ', b.weight: ' + b.weight);
    	              return a + (b.score * b.weight);
    	            }, 0) /
    	            asterData.reduce(function(a, b) {
    	              return a + b.weight;
    	            }, 0);

    	    svg.append("svg:text")
    	            .attr("class", "aster-score")
    	            .attr("dy", ".35em")
    	            .attr("text-anchor", "middle") // text-align: right
    	            .text(Math.round(asterscore));
    		
    	}
    	 



    	    //////////////////////////////

    	    function getDonutData(){
    	      return [
    	        {
    	          "age": "Community",
    	          "population": 33,
    	          "color": "#14A6CC"
    	        },
    	        {
    	          "age": "Cost",
    	          "population": 33,
    	          "color": "#BEDB39"
    	        },
    	        {
    	          "age": "Confidence",
    	          "population": 33,
    	          "color": "#E85305"
    	        }
    	      ];

    	    }

    	    function getAsterData(){
    	      return [
    	        {
    	          "id": "FIS",
    	          "order": "1",
    	          "score": "60",
    	          "weight": "1",
    	          "color": "#14A6CC",
    	          "label": "Growth Compatibility"
    	        },
    	        {
    	          "id": "AO",
    	          "order": "2",
    	          "score": "100",
    	          "weight": "1",
    	          "color": "#2EC0E6",
    	          "label": "Construction and O&M Jobs Created"
    	        },
    	        {
    	          "id": "NP",
    	          "order": "3",
    	          "score": "60",
    	          "weight": "1",
    	          "color": "#008DB3",
    	          "label": "Property Value Loss Avoided"
    	        },
    	        {
    	          "id": "CS",
    	          "order": "4",
    	          "score": "70",
    	          "weight": "1",
    	          "color": "#D8F553",
    	          "label": "Capital Cost"
    	        },
    	        {
    	          "id": "CP",
    	          "order": "5",
    	          "score": "70",
    	          "weight": "1",
    	          "color": "#BEDB39",
    	          "label": "Operation & Maintenance Cost"
    	        },
    	        {
    	          "id": "TR",
    	          "order": "6",
    	          "score": "40",
    	          "weight": "1",
    	          "color": "#A5C220",
    	          "label": "Life Cycle Cost"
    	        },
    	        {
    	          "id": "LIV",
    	          "order": "7",
    	          "score": "70",
    	          "weight": "1",
    	          "color": "#FF6D1F",
    	          "label": "Useful Number of Years"
    	        },
    	        {
    	          "id": "ICO",
    	          "order": "8",
    	          "score": "60",
    	          "weight": "1",
    	          "color": "#E85305",
    	          "label": "Variability in Performance"
    	        },
    	        {
    	          "id": "CW",
    	          "order": "9",
    	          "score": "70",
    	          "weight": "1",
    	          "color": "#CF3A00",
    	          "label": "Resiliency to Flooding"
    	        }
    	      ];
    	    }
    }

})();
