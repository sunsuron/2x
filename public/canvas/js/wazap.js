$(function () {
    // modal
    $('.modal-onload').modal({
        'backdrop': 'static'
    });

    // popover
    $('[data-toggle="popover"]').popover({
        'html': true,
        'placement': 'top',
        'trigger': 'focus'
    })

    // new windows
    $(document).on('click', '.newin', function (e) {
        e.preventDefault();
        window.open(this.href);
    });

    // spellcheck
    $('input[type="text"], input[type="email"], textarea').attr('spellcheck', false);

    // autocomplete
    $('input[type="text"], input[type="email"]').prop('autocomplete', 'off');

});


function round(num, dec) {
    var result = String(Math.round(num * Math.pow(10, dec)) / Math.pow(10, dec));
    if (result.indexOf('.') < 0) {
        result += '.';
    }
    while (result.length - result.indexOf('.') <= dec) {
        result += '0';
    }
    return result;
}

function $_GET() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
        vars[key] = value;
    });
    return vars;
}

function pad(num, size) {
    let s = num + ""
    while (s.length < size) s = "0" + s
    return s
}