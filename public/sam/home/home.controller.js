'use strict';

angular.module('app.home',[])

    .controller('homeCtrl', function($scope, VoteService) {

        console.log('home controller');

        $scope.activeTab = 1;

        $scope.setActiveTab = function (tabToset) {

            $scope.activeTab = tabToset;
        }

        // sortable ranks
        $scope.items = [{'name' : 'Capital Cost','color' : '#e6550d'},
            {'name' : 'Const and O&M Jobs created','color' : '#e6550d'},
            {'name' : 'Property loss value avoided','color' : '#e6550d'},
            {'name' : 'Operation & Maint cost','color' : '#31a354'},
            {'name' : 'Life cycle cost','color' : '#31a354'},
            {'name' : 'Resiliency to Flooding','color' : '#31a354'},
            {'name' : 'Growth compatibility','color' : '#3182bd'},
            {'name' : 'Useful no of years','color' : '#3182bd'},
            {'name' : 'Variability in Performance','color' : '#3182bd'}];

        $scope.sliderVals = {
            slider1: [33, 66],
            slider2: [20, 35],
            slider3: [30, 60],
            slider4: [10, 60],
        };


        $(function () {
            $("#slider-range1").slider({
                range: true,
                min: 1,
                max: 100,
                step: 1,
                values: $scope.sliderVals.slider1,
                //orientation: "vertical",
                slide: function (event, ui) {
                    //$("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
                    //console.log(ui.values[1] +'%');
                    $('#slider-range1-right-div').css('width', 100 - ui.values[1] +'%');
                    var newPercentage = [ui.values[0], ui.values[1] - ui.values[0], (100-ui.values[1])];

                    $scope.UpdatePieChart(newPercentage);
                    UpdateAsterScore();
                }
            }).append("<div id='slider-range1-right-div' style='width:" + (100- $scope.sliderVals.slider1[1]) + "%; background:#E85305; float: right; height: 100%; border-radius: 0 4px 4px 0;'></div>");
            $( "#slider-range1 .ui-slider-range" ).css('background', '#BEDB39');
            $( "#slider-range1" ).css('background', '#14A6CC');
            

        });

        $(function () {
            $("#slider-range2").slider({
                range: true,
                min: 1,
                max: 100,
                step: 1,
                values: $scope.sliderVals.slider2,
                //orientation: "vertical",
                slide: function (event, ui) {
                    //$("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
                    //console.log(ui.values[1] +'%');
                    $('#slider-range2-right-div').css('width', 100 - ui.values[1] +'%');
                    if(asterData.length > 0) {
                        //Update size of Confidence arc
                        asterData[0].score = ui.values[0];
                        asterData[1].score = ui.values[1] - ui.values[0];
                        asterData[2].score = (100-ui.values[1]);
                        $scope.UpdateRank();
                        UpdateAsterScore();
                    }
                }
                }).append("<div id='slider-range2-right-div' style='width:" + (100- $scope.sliderVals.slider2[1]) + "%; background:#004066; float: right; height: 100%; border-radius: 0 4px 4px 0;'></div>");
            $( "#slider-range2 .ui-slider-range" ).css('background', '#14A6CC');
            $( "#slider-range2" ).css('background', '#7AFFFF');

        });

        $(function () {
            $("#slider-range3").slider({
                range: true,
                min: 1,
                max: 100,
                step: 1,
                values: $scope.sliderVals.slider3,
                //orientation: "vertical",
                slide: function (event, ui) {
                    //$("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
                    //console.log(ui.values[1] +'%');
                    $('#slider-range3-right-div').css('width', 100 - ui.values[1] +'%');
                    if(asterData.length > 0) {
                        //Update size of Cost arc
                        asterData[3].score = ui.values[0];
                        asterData[4].score = ui.values[1] - ui.values[0];
                        asterData[5].score = (100-ui.values[1]);
                        $scope.UpdateRank();
                        UpdateAsterScore();
                    }
                }
            }).append("<div id='slider-range3-right-div' style='width:" + (100- $scope.sliderVals.slider3[1]) + "%; background:#98B513; float: right; height: 100%; border-radius: 0 4px 4px 0;'></div>");
            $( "#slider-range3 .ui-slider-range" ).css('background', '#BEDB39');
            $( "#slider-range3" ).css('background', '#E4FF5F');

        });

        $(function () {
            $("#slider-range4").slider({
                range: true,
                min: 1,
                max: 100,
                step: 1,
                values: $scope.sliderVals.slider4,
                //orientation: "vertical",
                slide: function (event, ui) {
                    //$("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
                    //console.log(ui.values[1] +'%');
                    $('#slider-range4-right-div').css('width', 100 - ui.values[1] +'%');
                    if(asterData.length > 0) {
                        //Update size of Cost arc
                        asterData[6].score = ui.values[0];
                        asterData[7].score = ui.values[1] - ui.values[0];
                        asterData[8].score = (100-ui.values[1]);
                        $scope.UpdateRank();
                        UpdateAsterScore();
                    }
                }

            }).append("<div id='slider-range4-right-div' style='width:" + (100- $scope.sliderVals.slider4[1]) + "%; background:#820000; float: right; height: 100%; border-radius: 0 4px 4px 0;'></div>");

            $( "#slider-range4 .ui-slider-range" ).css('background', '#E85305');
            $( "#slider-range4" ).css('background', '#FFB96B');

        });

        function UpdateAsterScore() {
            $scope.asterscore = getasterscore();
            svg.selectAll("text")
                .text(function() { return $scope.asterscore; });
        }

        //Declare Data variables
        var donutData = getDonutData();
        var asterData = getAsterData();
        $scope.asterscore = getasterscore();
        var pieChart = null;
        var asterpath = null;
        var outerPath = null;

        //Declare Screen Size
        var width = 600,
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

        /*var svg = d3.select("#donutAsterchartArea").append("svg")
            .attr(addClass("svg-container"))
            .append("g")
            .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");*/

        var svg = d3.select("div#donutAsterchartArea")
            .append("div")
            .classed("svg-container",true)
            .append("svg")
            .attr('preserveAspectRatio','xMinYMin meet')
            .attr('viewBox','155 0 300 400')
            .classed("svg-content-responsive",true)
            .append("g")
            .attr("transform", "translate(" + width /2 + "," + (height /2) + ")");

        //Draw PIE chart using initial data

        //Store data to global variable
       //donutData =  getDonutData();

        /* pieArcg = svg.selectAll(".arc")
         .data(pie(data))
         .enter().append("g")
         .attr("class", "arc");

         pieArcg.append("path")
         .style("fill", function(d) { return d.data.color; })
         .attr("d", arc)
         .each(function(d) { this._current = d; }); // store the initial angles



         pieArcg.append("text")
         .attr("transform", function(d) { return "translate(" + arc.centroid(d) + ")"; })
         .attr("dy", ".35em")
         .text(function(d) { return d.data.age; });*/

        pieChart = svg.datum(donutData).selectAll("path")
            .data(pie)
            .enter().append("path")
            .attr("fill", function(d, i) { return d.data.color; })
            .attr("stroke", "white")
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
       // asterData = getAsterData();

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
            .attr("stroke", "white")
            .attr("d", asterarc)
            .each(function(d) { this._astercurrent = d; })// store the initial angles*/
            .on('mouseover', astertip.show)
            .on('mouseout', astertip.hide);


        outerPath = svg.selectAll(".outlineArc")
            .data(asterpie(asterData))
            .enter().append("path")
            .attr("fill", "none")
            .attr("stroke", "white")
            .attr("class", "outlineArc")
            .attr("d", outlineArc)
            .each(function(d) { this._asteroutercurrent = d; })// store the initial angles*/;


        // calculate the weighted mean score
        function getasterscore() {
            return (Math.floor((Math.random() * 100) + 1));
        }
        svg.append("svg:text")
            .attr("class", "aster-score")
            .attr("dy", ".35em")
            .attr("text-anchor", "middle") // text-align: right
            .text(function() { return $scope.asterscore; });



        //////////////////////////////

        $scope.UpdatePieChart =  function(newPercentage){

            var parmNo = 0;
            if(donutData.length > 0) {
                //Update size of Confidence arc
                for (var i = 0; i < donutData.length; i++) {
                    donutData[i].population = newPercentage[i];
                    for (var j = 0; j < 3; j++) {
                        asterData[parmNo].weight = newPercentage[i]/100;
                        parmNo++;
                    }
                }

                asterData.forEach(function(d) {
                    d.id     =  d.id;
                    d.order  = d.order;
                    d.color  =  d.color;
                    d.weight = +d.weight;
                    d.score  =  d.score;
                    d.width  = +d.weight;
                    d.label  =  d.label;
                });

                //Update Donut Chart
                pieChart.data(pie(donutData));
                pieChart.transition().duration(750).attrTween("d", arcTween); // redraw the arcs

                //Update Aster Chart
                asterpath.data(asterpie(asterData));
                asterpath.transition().duration(50).attrTween("d", asterarcTween); // redraw the arcs
                outerPath.data(asterpie(asterData));
                outerPath.transition().duration(50).attrTween("d", outerarcTween); // redraw the arcs

            }


            //Update Score

        }

        $scope.UpdateRank = function(){
            //alert('update pie chart');

                //Update Aster Chart
                asterpath.data(asterpie(asterData));
                asterpath.transition().duration(50).attrTween("d", asterarcTween); // redraw the arcs


        }


        // Store the displayed angles in _current.
        // Then, interpolate from _current to the new angles.
        // During the transition, _current is updated in-place by d3.interpolate.
        function arcTween(a) {
            var i = d3.interpolate(this._current, a);
            this._current = i(0);
            return function(t) {
                return arc(i(t));
            };
        }

        function asterarcTween(a) {
            var i = d3.interpolate(this._astercurrent, a);
            this._astercurrent = i(0);
            return function(t) {
                return asterarc(i(t));
            };
        }



        function outerarcTween(a) {
            var i = d3.interpolate(this._asteroutercurrent, a);
            this._asteroutercurrent = i(0);
            return function(t) {
                return outlineArc(i(t));
            };
        }

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
                    "color": "#7AFFFF",
                    "label": "Growth Compatibility"
                },
                {
                    "id": "AO",
                    "order": "2",
                    "score": "100",
                    "weight": "1",
                    "color": "#14A6CC",
                    "label": "Construction and O&M Jobs Created"
                },
                {
                    "id": "NP",
                    "order": "3",
                    "score": "60",
                    "weight": "1",
                    "color": "#004066",
                    "label": "Property Value Loss Avoided"
                },
                {
                    "id": "CS",
                    "order": "4",
                    "score": "70",
                    "weight": "1",
                    "color": "#E4FF5F",
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
                    "color": "#98B513",
                    "label": "Life Cycle Cost"
                },
                {
                    "id": "LIV",
                    "order": "7",
                    "score": "70",
                    "weight": "1",
                    "color": "#FFB96B",
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
                    "color": "#820000",
                    "label": "Resiliency to Flooding"
                }
            ];
        }
        
        $scope.submitVote = function() {
           console.log('voting..');
           
           var vote = '{"vote" : "test"}'; // build voting data here...
           
           VoteService.submitVote(vote)
           .then(function (response) {
               
        	   if (response.success) {
                   console.log('vote saved!!');
               } else {
                   console.log('failed!!');
               }
           });
        };

    });