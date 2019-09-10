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