<?= $header ?>

<section id="page-title">
    <div class="container clearfix">
        <h1><?= $breadcrumbs[count($breadcrumbs) - 1]['text'] ?></h1>
        <span><?= lang('my_task_span') ?></span>
        <nav>
            <ol class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) : ?>
                    <?php if (!$breadcrumb['is_active']) : ?>
                        <li class="breadcrumb-item"><a href="<?= $breadcrumb['href'] ?>"><?= $breadcrumb['text'] ?></a>
                        </li>
                    <?php else : ?>
                        <li class="breadcrumb-item active"><?= $breadcrumb['text'] ?></li>
                    <?php endif ?>
                <?php endforeach ?>
            </ol>
        </nav>
    </div>
</section>

<section id="task">
    <div class="task-wrap">
        <div class="clearfix">
            <div class="col-lg-12 p-0 mb-3">
                <div class="text-right">
                    <button class="button button-3d button-dirtygreen m-0 new"><i class="icon-pen-nib"></i>
                        <span><?= lang('new_task') ?></span></button>
                    <i class="icon-spinner icon-lg icon-spin d-none"></i>
                </div>
            </div>
            <div class="card shadow">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th>#</th>
                                <th><?= lang('created_at') ?></th>
                                <th><?= lang('created_by') ?></th>
                                <th><?= lang('task_desc') ?></th>
                                <th><?= lang('task_datetime_start') ?></th>
                                <th><?= lang('task_datetime_end') ?></th>
                                <th><?= lang('total_datetime_spent') ?></th>

                                <th><?= lang('status') ?></th>
                            </tr>
                        </thead>
                        <?php if ($tasks->num_rows) : ?>
                            <tbody>
                                <?php foreach ($tasks->rows as $i => $task) : ?>
                                    <tr>
                                        <td class="text-right">
                                            <a href="<?= u('/admin/task/edit?task_id=%d', $task['task_id']) ?>" title="<?= lang('edit') ?>" class="btn btn-outline-secondary"><i class="icon-pencil-alt icon-lg"></i></a>
                                            <a href="<?= u('/admin/task/delete?task_id=%d', $task['task_id']) ?>" title="<?= lang('delete') ?>" class="btn btn-outline-secondary"><i class="icon-trash-alt icon-lg"></i></a>
                                        </td>
                                        <td><?= (($page - 1) * PAGE_LIMIT) + ($i + 1) ?></td>
                                        <td><?= date('M j, g:i a', strtotime($task['created_at'])) ?></td>
                                        <td><?= fullname($task['created_by']) ?></td>
                                        <td><?= excerpt($task['task_desc']) ?></td>
                                        <td><?= $task['task_datetime_start']  == '0000-00-00 00:00:00' ? '&mdash;' :  date('M j, g:i a', strtotime($task['task_datetime_start']))  ?></td>
                                        <td><?= $task['task_datetime_end']    == '0000-00-00 00:00:00' ? '&mdash;' :  date('M j, g:i a', strtotime($task['task_datetime_end']))    ?></td>
                                        <td><?= $task['total_datetime_spent'] == '00:00:00' ? '&mdash;' :  date('H:i:s', strtotime($task['total_datetime_spent'])) ?></td>
                                        <td>
                                            <?php if ($task['is_canceled']) : ?>
                                                <span class="badge badge-danger"><?= lang('canceled') ?></span>
                                            <?php else : ?>
                                                <?php if ($task['is_approved']) : ?>
                                                    <span class="badge badge-success"><?= lang('approved') ?></span>
                                                <?php else : ?>
                                                    <span class="badge badge-warning"><?= lang('pending_client_approval') ?></span>
                                                <?php endif ?>

                                                <?php if ($task['in_progress'] && !$task['is_completed']) : ?>
                                                    <span class="badge badge-success"><?= lang('in_progress') ?></span>
                                                <?php else : ?>
                                                    <?php if (!$task['in_progress'] && !$task['is_completed']) : ?>
                                                        <span class="badge badge-warning"><?= lang('not_started') ?></span>
                                                    <?php endif ?>
                                                <?php endif ?>

                                                <?php if ($task['is_completed']) : ?>
                                                    <span class="badge badge-success"><?= lang('completed') ?></span>
                                                <?php else : ?>
                                                    <span class="badge badge-warning"><?= lang('not_completed') ?></span>
                                                <?php endif ?>

                                                <?php if ($task['is_active']) : ?>
                                                    <span class="badge badge-success"><?= lang('active') ?></span>
                                                <?php else : ?>
                                                    <span class="badge badge-warning"><?= lang('inactive') ?></span>
                                                <?php endif ?>
                                            <?php endif ?>
                                        </td>
                                    </tr>
                                <?php endforeach ?>

                            <?php else : ?>
                                <tr>
                                    <td colspan="10" class="text-center"><small><em class="text-right"><?= lang('no_item') ?></em></small></td>
                                </tr>
                            </tbody>
                        <?php endif ?>
                    </table>
                </div>
                <?php if ($pagination) : ?>
                    <?= $pagination ?>
                <?php endif ?>
            </div>
        </div>
    </div>
</section>

<div class="modal fade new" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="icon-pen-nib"></i> <?= lang('new_task') ?></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form class="row mb-0" action="<?= u('/reset-password') ?>" method="post">
                    <div class="col-12 form-group">
                        <label for="ncontent-created_by"><?= lang('created_by') ?> <span class="text-danger">*</span></label>
                        <select class="form-control selectpicker show-tick show-menu-arrow" id="ncontent-created_by" name="created_by">
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <i class="icon-spinner icon-lg icon-spin d-none"></i>
                <button type="button" class="button button-3d m-0 button-blue"><i class="icon-ok-sign"></i><?= lang('save_changes') ?></button>
                <button type="button" class="button button-3d button-teal" data-dismiss="modal"><i class="icon-remove-sign"></i><?= lang('close') ?></button>
            </div>
        </div>
    </div>
</div>

<?= $footer ?>