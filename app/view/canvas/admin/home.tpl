<?=$header ?>

<section id="page-title">
    <div class="container clearfix">
        <h1><?= $breadcrumbs[count($breadcrumbs)-1]['text'] ?></h1>
        <span><?=  lang('dashboard_span') ?></span>
        <nav>
            <ol class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) : ?>
                <?php if (!$breadcrumb['is_active']) : ?>
                <li class="breadcrumb-item"><a href="<?= $breadcrumb['href'] ?>"><?= $breadcrumb['text'] ?></a>
                </li>
                <?php else: ?>
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
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <a href="<?= u('/admin/task') ?>">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4"><i class="icon-tasks icon-3x"></i></div>
                                    <div class="col-8 text-right">
                                        <div class="huge"><?= $total_tasks ?></div>
                                        <div>My Task</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <a href="<?= u('/profile') ?>">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4"><i class="icon-user-circle icon-3x"></i></div>
                                    <div class="col-8 text-right">
                                        <div class="huge">&nbsp;</div>
                                        <div>My Profile</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card shadow">
                        <a href="<?= u('/logout') ?>">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4"><i class="icon-sign-out-alt icon-3x"></i></div>
                                    <div class="col-8 text-right">
                                        <div class="huge">&nbsp;</div>
                                        <div>Log Out</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?=$footer ?>