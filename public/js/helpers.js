// Replace modal contents with blade html from API route
function loadTechView (route) {
    $('.modal-wrapper').show();
    $('#techView').load(route, function () {
        $('#closeModal').show();
        return 1;
    })
}

// Hide and remove relevant modal components on-close
function destroyModalContents () {
    $('#closeModal').hide();
    $('#techView').empty();
    $('.modal-wrapper').hide();
    return 1;
}

// Create and append appropriate tech icons to the selected treatments stack post-apply
function addToStack (treatment_id, icon) {
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
function deleteGraphic (treatment_id = null) {
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
function addTreatmentIdToGraphic (treatment_id) {
    for (var i = map.graphics.graphics.length - 1; i >= 0; i--) {
        if (map.graphics.graphics[i].attributes) {
            if (map.graphics.graphics[i].attributes.treatment_id == 1) {
                map.graphics.graphics[i].attributes.treatment_id = treatment_id;
            }
        }
    }
}