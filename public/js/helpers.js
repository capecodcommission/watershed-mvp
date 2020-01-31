// Replace modal contents with blade html from API route
const loadTechView = (route) => {
    $(".modal-loading").toggle();
    $('.modal-wrapper').toggle();
    $(".modal-content").load(route, function () {
        $(".modal-loading").toggle();
        $("#closeModal").toggle();
        return 1;
    });
};

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

    var url = "/delete_session_geometry";
    $.ajax({
        method: 'GET',
        url: url
    })
    .done(function(){
    })
    .fail(function(msg) {
        alert('Please contact the cape Cod Commission.' + msg.statusText)
    })
});

// Hide relevant modal elements
const destroyModalContents = () => {
    $(".modal-wrapper").toggle();
    $("#closeModal").toggle();
    $(".modal-content").empty(function () {
        return 1;
    });
};

// Create and append appropriate tech icons to the selected treatments stack post-apply
const addToStack = (treatment_id, icon, techId = null) => {
    let newtreatment = '<li class="technology" data-route="/edit/' + treatment_id + '" data-treatment="' + treatment_id + '" data-techid="' + techId + '">' + '<a href="" title="' + treatment_id +'">' + '<img src="http://www.cch2o.org/assets/icons/SVG/' + icon + '" alt=""></a></li>';
    $('ul.selected-treatments').append(newtreatment);
    return 1;
};

// Event handler for loading the appropriate view on-click of a technology in the accordion blade
$('div.technology').on('click', function (e) {
    e.preventDefault();
    let apiRoute = $(this).data('route');
    if (apiRoute) {
        loadTechView(apiRoute);
    }
});

// Event handler for loading the appropriate view on-click of a technology in the selected treatments blade
$(document).on('click', '.selected-treatments .technology', function(e) {
    e.preventDefault();
    let apiRoute = $(this).data('route');
    if (apiRoute) {
        loadTechView(apiRoute);
    }
});

// Remove map graphic by associated treatment id
// Graphics created mid-process, such as points or polygons, are given an id of 1 until applied
const deleteGraphic = (treatment_id = null) => {
    let layerGraphics = map.graphics.graphics;
    layerGraphics.filter((graphic) => {
        return graphic.attributes;
    }).map((graphic) => {
        let attribs = graphic.attributes;
        if (treatment_id == 'dump') {
            if (graphic.geometry.type == 'point' && attribs.treatment_id == 1) {
                return map.graphics.remove(graphic);
            }
        }
        else if (attribs.treatment_id == 1 || attribs.treatment_id == treatment_id) {
            return map.graphics.remove(graphic);
        }
    })
};

// Update associated treatment id of point or polygon geometry created mid-process
// Updated post-apply
const addTreatmentIdToGraphic = (treatment_id) => {
    let layerGraphics = map.graphics.graphics;
    layerGraphics.filter((graphic) => {
        return graphic.attributes;
    }).map((graphic) => {
        let attribs = graphic.attributes;
        if (attribs.treatment_id === 1) {
            attribs.treatment_id = treatment_id;
        }
    })
};

// Reset edit properties of graphic after treatment has been updated with new geometry
const resetGraphicPropsAfterUpdate = (treatment_id) => {
    let layerGraphics = map.graphics.graphics;
    layerGraphics.filter((graphic) => {
        return graphic.attributes;
    }).map((graphic) => {
        let attribs = graphic.attributes;
        if (attribs.treatment_id == treatment_id && attribs.editInProgress) {
            attribs.editInProgress = 0;
        }
    });
};

// Toggle clickable UI element visibility during geometry creation or modification
const toggleUI = (show) => {

    if (show) {
        $('.modal-wrapper').show();
        $('#new_accordion').show();
        $('#progress').show();
        $('#overall_progress').show();
        $('.selected-treatments').show();
    } else {
        $('.modal-wrapper').hide();
        $('#new_accordion').hide();
        $('#progress').hide();
        $('#overall_progress').hide();
        $('.selected-treatments').hide();
    }
};

const hideControlPannel = () => {
    document.getElementById("angle_down_button").style.opacity = "0";
    document.getElementById("new_accordion").style.animation = "1s ease-out 0s 1 slideOutToBottom";
    setTimeout(function() {
        document.getElementById("new_accordion").style.opacity = "0";
      }, 900);
    setTimeout(function() {
      document.getElementById("angle_up_button").style.display = "flex";
    }, 1100);
};

const showControlPannel = () => {
    document.getElementById("angle_up_button").style.display = "none";
    document.getElementById("new_accordion").style.opacity = "1";
    document.getElementById("new_accordion").style.animation = "1s ease-in 0s 1 slideInFromBottom";
    setTimeout(function() {
        document.getElementById("new_accordion").style = "";
      }, 900);
    document.getElementById("angle_down_button").style.opacity = "1";

};