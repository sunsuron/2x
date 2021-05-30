<footer id="footer" class="dark">
    <div class="container"></div>
</footer>
</div>

<div id="gotoTop" class="icon-angle-up"></div>

<script src="<?= sprintf('%s/js/jquery.js',      u(TEMPLATE)) ?>"></script>
<script src="<?= sprintf('%s/js/plugins.min.js', u(TEMPLATE)) ?>"></script>
<script src="<?= sprintf('%s/js/functions.js',   u(TEMPLATE)) ?>"></script>
<script src="<?= sprintf('%s/js/wazap.js',       u(TEMPLATE)) ?>"></script>
<?php foreach ($scripts as $script) : ?>
    <script src="<?= sprintf('%s', $script) ?>"></script>
<?php endforeach ?>

</body>

</html>