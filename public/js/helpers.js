function loadTechView (route) {
    $('.modal-wrapper').show();
    $('#techView').load(route, function () {
        $('#closeModal').show();
        return 1;
    })
}

function destroyModalContents () {
    $('#closeModal').hide();
    $('#techView').empty();
    $('.modal-wrapper').hide();
    return 1;
}

function addToStack (treatment_id, icon, url) {
    let newtreatment = '<li class="technology" data-route="' + url + '" data-treatment="' + treatment_id + '">' + '<a title="' + treatment_id +'">' + '<img src="http://www.watershedmvp.org/images/SVG/' + icon + '" alt=""></a></li>';
    $('ul.selected-treatments').append(newtreatment);
    $('ul.selected-treatments li[data-treatment="' + treatment_id + '"] a').popdown()
    return 1;
}

$('.technology').on('click', function (e) {
    e.preventDefault();
    let apiRoute = $(this).data('route')
    if (apiRoute) {
        loadTechView(apiRoute)
    }
})