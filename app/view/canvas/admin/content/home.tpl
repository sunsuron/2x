<?= $header ?>

<section id="page-title">
    <div class="container clearfix">
        <h1><?= $breadcrumbs[count($breadcrumbs) - 1]['text'] ?></h1>
        <span><?= lang('my_content_span') ?></span>
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
            <div class="col-lg-12 p-0 mb-3">
                <div class="text-right">
                    <button class="button button-3d button-dirtygreen m-0 new"><i class="icon-pen-nib"></i>
                        <span><?= lang('new_content') ?></span></button>
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
                                <th><?= lang('content_link') ?></th>
                                <th><?= lang('click_count') ?></th>
                                <th><?= lang('content_type') ?></th>
                                <th><?= lang('created_at') ?></th>
                                <th><?= lang('status') ?></th>
                            </tr>
                        </thead>
                        <?php if ($contents->num_rows) : ?>
                            <tbody>
                                <?php foreach ($contents->rows as $i => $content) : ?>
                                    <tr>
                                        <td class="text-right">
                                            <a href="<?= u('/admin/content/edit?content_id=%d', $content['content_id']) ?>" title="<?= lang('edit') ?>" class="btn btn-outline-secondary"><i class="icon-pencil-alt icon-lg"></i></a>
                                            <a href="<?= u('/admin/content/delete?content_id=%d', $content['content_id']) ?>" title="<?= lang('delete') ?>" class="btn btn-outline-secondary"><i class="icon-trash-alt icon-lg"></i></a>
                                        </td>
                                        <td><?= (($page - 1) * PAGE_LIMIT) + ($i + 1) ?></td>
                                        <td><i class="icon-external-link"></i> <a href="<?= $content['content_link'] ?>" class="newin">
                                                <?= domain($content['content_link']) ?></a></td>
                                        <td><?= $content['click_count'] ?></td>
                                        <td><?= $content['content_type'] ?></td>
                                        <td><?= date('Y M j', strtotime($content['created_at'])) ?></td>
                                        <td>
                                            <?php if ($content['is_approved']) : ?>
                                                <span class="badge badge-success"><?= lang('approved') ?></span>
                                            <?php else : ?>
                                                <span class="badge badge-warning"><?= lang('pending') ?></span>
                                            <?php endif ?>

                                            <?php if ($content['is_active']) : ?>
                                                <span class="badge badge-success"><?= lang('active') ?></span>
                                            <?php else : ?>
                                                <span class="badge badge-warning"><?= lang('inactive') ?></span>
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
                <h5 class="modal-title"><i class="icon-pen-nib"></i> <?= lang('new_content') ?></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form class="row mb-0" action="<?= u('/reset-password') ?>" method="post">
                    <div class="col-12 form-group">
                        <label for="ncontent-content_link"><?= lang('content_link') ?> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="ncontent-content_link" name="content_link" data-toggle="popover" title="<?= lang('po_paste_link') ?>" data-content="<?= lang('po_paste_link_content') ?>" />
                    </div>
                    <div class="col-12 form-group">
                        <label for="ncontent-content_type"><?= lang('content_type') ?> <span class="text-danger">*</span></label>
                        <select class="form-control selectpicker show-tick show-menu-arrow" id="ncontent-content_type" name="content_type">
                            <?php foreach ($content_types as $content_type) : ?>
                                <option name="<?= $content_type['name'] ?>"><?= $content_type['name'] ?></option>
                            <?php endforeach ?>
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