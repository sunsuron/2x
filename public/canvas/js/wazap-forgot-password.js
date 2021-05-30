$(function () {
    $(document).on('submit', 'form:eq(0)', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $(this).find('button').prop('disabled', true);
        $(this).find('i.icon-spinner').toggleClass('d-none');

        let data = $(this).serializeArray();
        data.push({
            name: 'fpasswd',
            value: ''
        });
        var self = this;
        $.post(this.action, $.param(data), function (resp) {
            $(self).find('button').prop('disabled', false);
            $(self).find('i.icon-spinner').toggleClass('d-none');
            if (resp.msg) {
                let modalhtml = '';
                $.each(resp.msg, function (i, val) {
                    modalhtml += `<p class="m-0">${val}</p>`;
                });
                $('.modal-body').html(modalhtml);
                $('.modal-ajax').modal({
                    'backdrop': 'static'
                });
            }
            $(self).find('[type="text"]').val('');
        }, 'json');
    });
});