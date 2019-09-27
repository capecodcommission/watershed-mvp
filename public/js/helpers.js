// Replace modal contents with blade html from API route
const loadTechView = (route) => {
    $(".modal-loading").toggle();
    $('.modal-wrapper').toggle();
    $(".modal-content").load(route, function () {
        $(".modal-loading").toggle();
        $("#closeModal").toggle();
        return 1;
    });
}

// Attach click event listener to the modal-close class of the .blade_container class,
// destroy modal contents, delete graphic if graphic exists and, if necessary, set percent values to 0
$(document).on('click', '.blade_container .modal-close', function(e) {
    e.preventDefault();
    destroyModalContents();
    deleteGraphic();
    if ("{{$tech->technology_id == 400}}") {
        $("#fert-percent").val(0);
    }
    if ("{{$tech->technology_id == 401}}") {
        $("#storm-percent").val(0);
    }
})

// Hide relevant modal elements
const destroyModalContents = () => {
    $(".modal-wrapper").toggle();
    $("#closeModal").toggle();
    $(".modal-content").empty(function () {
        return 1;
    });
}

// Create and append appropriate tech icons to the selected treatments stack post-apply
const addToStack = (treatment_id, icon) => {
    let newtreatment = '<li class="technology" data-route="/edit/' + treatment_id + '" data-treatment="' + treatment_id + '">' + '<a href="" title="' + treatment_id +'">' + '<img src="http://www.cch2o.org/Matrix/icons/' + icon + '" alt=""></a></li>';
    $('ul.selected-treatments').append(newtreatment);
    return 1;
}

// Event handler for loading the appropriate view on-click of a technology in the accordion blade
$('div.technology').on('click', function (e) {
    e.preventDefault();
    let apiRoute = $(this).data('route')
    if (apiRoute) {
        loadTechView(apiRoute)
    }
})

// Event handler for loading the appropriate view on-click of a technology in the selected treatments blade
$('#stackList').on('click', 'li.technology', function (e) {
    e.preventDefault();
    let apiRoute = $(this).data('route')
    if (apiRoute) {
        loadTechView(apiRoute)
    }
})

// Remove map graphic by associated treatment id
// Graphics created mid-process, such as points or polygons, are given an id of 1 until applied
const deleteGraphic = (treatment_id = null) => {
    for (var i = map.graphics.graphics.length - 1; i >= 0; i--) {
        if (map.graphics.graphics[i].attributes) {
            if (map.graphics.graphics[i].attributes.treatment_id == treatment_id || map.graphics.graphics[i].attributes.treatment_id == 1) {
                map.graphics.remove(map.graphics.graphics[i])
            }
        }
    }
}

// Update associated treatment id of point or polygon geometry created mid-process
// Updated post-apply
const addTreatmentIdToGraphic = (treatment_id) => {
    for (var i = map.graphics.graphics.length - 1; i >= 0; i--) {
        if (map.graphics.graphics[i].attributes) {
            if (map.graphics.graphics[i].attributes.treatment_id == 1) {
                map.graphics.graphics[i].attributes.treatment_id = treatment_id;
            }
        }
    }
}

// Reset edit properties of graphic after treatment has been updated with new geometry
const resetGraphicPropsAfterUpdate = (treatment_id) => {

    let layerGraphics = map.graphics.graphics;

    layerGraphics.map((graphic) => {

        let attribs = graphic.attributes;

        if (attribs) {

            if (attribs.treatment_id === treatment_id) {

                attribs.editInProgress = 0;
            }
        }
    });
}