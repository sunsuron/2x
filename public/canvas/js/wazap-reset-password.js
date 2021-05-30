$(function () {
    $(document).on('submit', 'form:eq(0)', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $(this).find('button').prop('disabled', true);
        $(this).find('i.icon-spinner').toggleClass('d-none');

        let data = $(this).serializeArray();
        data.push({
            name: 'rpasswd',
            value: ''
        }, {
            name: 'vcode',
            value: $_GET()['vcode']
        });
        var self = this;
        $.post(this.action, $.param(data), function (resp) {
            $(self).find('button').prop('disabled', false);
            $(self).find('i.icon-spinner').toggleClass('d-none');
            if (resp.err) {
                let modalhtml = '';
                $.each(resp.msg, function (i, val) {
                    modalhtml += `<p class="m-0">${val}</p>`;
                });
                $('.modal-body').html(modalhtml);
                $('.modal-ajax').modal({
                    'backdrop': 'static'
                });
            } else {
                if (resp.msg) {
                    let modalhtml = '';
                    $.each(resp.msg, function (i, val) {
                        modalhtml += `<p class="m-0">${val}</p>`;
                    });

                    $('.modal-ajax').find('.modal-footer button:eq(1) span').html('Proceed to admin area');
                    $('.modal-ajax').find('.modal-footer button:eq(1)').on('click', function () {
                        location.href = '/admin'
                    });
                    $('.modal-ajax').find('.modal-footer button:eq(0)').addClass('d-none');
                    $('.modal-ajax').find('.modal-footer button:eq(1)').removeClass('d-none');

                    $('.modal-body').html(modalhtml);
                    $('.modal-ajax').modal({
                        'backdrop': 'static'
                    });
                }
            }
            $(self).find('[type="password"]').val('');
        }, 'json');
    });
    $('input[type="text"]:eq(0)').focus();
});