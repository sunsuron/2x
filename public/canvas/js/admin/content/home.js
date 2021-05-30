$(function () {
    $(document).on('click', 'button.new', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $('div.modal.new').modal('show');
    });
});