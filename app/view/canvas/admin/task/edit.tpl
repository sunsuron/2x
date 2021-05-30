<?= $header ?>

<section id="page-title">
    <div class="container clearfix">
        <h1><?= $breadcrumbs[count($breadcrumbs) - 1]['text'] ?></h1>
        <span><?= lang('edit_task') ?></span>
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

<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">
            <div class="card mb-0 shadow">
                <div class="card-body" style="padding: 40px;">

                    <form name="master_task" class="mb-0" action="<?= u(sprintf('/admin/task/edit?task_id=%d', $task['task_id'])) ?>" method="post">
                        <h3>// <?= lang('master_task') ?></h3>

                        <ul class="list-group">
                            <li class="list-group-item"><strong><?= lang('date_created') ?></strong><br><small><?= date('Y M j, H:i:s', strtotime($task['created_at'])) ?></small></li>
                            <li class="list-group-item"><strong><?= lang('created_by') ?></strong><br><small><?= fullname($task['created_by']) ?></small></li>
                            <li class="list-group-item"><strong><?= lang('task_description') ?></strong></li>
                            <li class="list-group-item">
                                <pre><?= $task['task_desc'] ?></pre>
                            </li>
                            <li class="list-group-item"><strong><?= lang('status') ?></strong><br>

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

                            </li>
                        </ul>
                        <br>
                        <div class="row">
                            <div class="col-12 form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="master_task_status" id="master_task_in_progress" value="in_progress" <?= $task['in_progress'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="master_task_in_progress">
                                        <?= lang('in_progress') ?>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="master_task_status" id="master_task_is_completed" value="is_completed" <?= $task['is_completed'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="master_task_is_completed">
                                        <?= lang('completed') ?>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="master_task_status" id="master_task_is_canceled" value="is_canceled" <?= $task['is_canceled'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="master_task_is_canceled">
                                        <?= lang('canceled') ?>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="master_task_status" id="master_task_is_deleted" value="is_deleted">
                                    <label class="form-check-label" for="master_task_is_deleted">
                                        <?= lang('deleted') ?>
                                    </label>
                                </div>
                            </div>

                            <div class="col-12 form-group">
                                <button type="submit" class="button button-3d m-0"><i class="icon-check-circle"></i><?= lang('update_master_task') ?></button> <i class="icon-spinner icon-lg icon-spin d-none"></i>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
</section>

<?= $footer ?>