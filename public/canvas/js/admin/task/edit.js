/**
 * @author Charles Langkung
 */

$(function () {
    try {
        $('.datetimepicker_start, .datetimepicker_end').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss'
        });
    } catch (e) {

    }

    $(document).on('click', '[name="subtask_status"][value="in_progress"]:checked', function () {
        $('.datetimepicker_start').val(moment(new Date()).format('YYYY-MM-DD HH:mm:ss'));
    });

    $(document).on('click', '[name="subtask_status"][value="is_completed"]:checked', function () {
        $('.datetimepicker_end').val(moment(new Date()).format('YYYY-MM-DD HH:mm:ss'));
    });
});


$(function () {
    $(document).on('submit', 'form:eq(0)', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $(this).find('button').prop('disabled', true);
        $(this).find('i.icon-spinner').toggleClass('d-none');

        let data = $(this).serializeArray();
        data.push({
            name: 'update_master_task',
            value: ''
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
                location.href = location.href;
            }
        }, 'json');
    });
    $('input[type="text"]:eq(0)').focus();
});

$(function () {
    $(document).on('submit', 'form:eq(1)', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $(this).find('button').prop('disabled', true);
        $(this).find('i.icon-spinner').toggleClass('d-none');

        let data = $(this).serializeArray();
        data.push({
            name: 'new_subtask',
            value: ''
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
                location.href = location.href;
            }
        }, 'json');
    });
    $('input[type="text"]:eq(0)').focus();
});