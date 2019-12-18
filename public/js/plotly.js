const updatePlotly = (progress) => {

    let thePlotlyDiv = document.getElementById('plotlyDiv');

    let theData = {
        type: 'bar',
        x: [progress],
        y: [''],
        orientation: 'h',
    };

    let data = [theData];

    let layout = {
        title: {
            text: 'Scenario Progress (' + progress +'% / Goal)',
            font: {
                family: 'Courier New, monospace',
                size: 10
            },
        },
        xaxis: {range: [0, 100]},
    };

    Plotly.newPlot(thePlotlyDiv, data, layout, {
        displayModeBar: false
    }, { responsive: true});

};