<?= $header ?>

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

<?= $footer ?>
