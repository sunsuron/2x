<!DOCTYPE html>
<html dir="ltr" lang="en-US">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title><?= sprintf('%s / %s', $title, lang('sitename_title')) ?></title>
	<link rel="shortcut icon" type="image/png" href="<?= sprintf('%s/images/favicon.png', u(TEMPLATE)) ?>">
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700|Poppins:300,400,500,600,700|PT+Serif:400,400i&display=swap">
	<link rel="stylesheet" type="text/css" href="<?= sprintf('%s/css/bootstrap.css',  u(TEMPLATE)) ?>">
	<link rel="stylesheet" type="text/css" href="<?= sprintf('%s/style.css',          u(TEMPLATE)) ?>">
	<link rel="stylesheet" type="text/css" href="<?= sprintf('%s/css/dark.css',       u(TEMPLATE)) ?>">
	<link rel="stylesheet" type="text/css" href="<?= sprintf('%s/css/font-icons.css', u(TEMPLATE)) ?>">
	<link rel="stylesheet" type="text/css" href="<?= sprintf('%s/css/custom.css',     u(TEMPLATE)) ?>">
	<?php foreach ($links as $link) : ?>
		<link rel="stylesheet" type="text/css" href="<?= sprintf('%s', $link) ?>">
	<?php endforeach ?>
</head>

<body class="stretched">
	<div id="wrapper">

		<header id="header" class="full-header dark">
			<div id="header-wrap">
				<div class="container">
					<div class="header-row">
						<div id="logo">
							<a href="<?= u('/') ?>" class="standard-logo" data-dark-logo="images/logo-dark.png">2x Rec</a>
							<a href="<?= u('/') ?>" class="retina-logo" data-dark-logo="images/logo-dark.png">2x Rec</a>
						</div>
						<div id="primary-menu-trigger">
							<svg class="svg-trigger" viewBox="0 0 100 100">
								<path d="m 30,33 h 40 c 3.722839,0 7.5,3.126468 7.5,8.578427 0,5.451959 -2.727029,8.421573 -7.5,8.421573 h -20">
								</path>
								<path d="m 30,50 h 40"></path>
								<path d="m 70,67 h -40 c 0,0 -7.5,-0.802118 -7.5,-8.365747 0,-7.563629 7.5,-8.634253 7.5,-8.634253 h 20">
								</path>
							</svg>
						</div>
						<nav class="primary-menu">


							<?php if (!is_logged()) : ?>
								<ul class="menu-container">
									<li class="menu-item <?= $root == 'learn-more' ? 'active' : '' ?>">
										<a class=" menu-link" href="<?= u('/learn-more') ?>">
											<div><i class="icon-life-ring"></i> Learn More</div>
										</a>
									</li>

									<li class="menu-item <?= $root == 'login' ? 'active' : '' ?>">
										<a class="menu-link" href="<?= u('/login') ?>">
											<div><i class="icon-sign-in-alt"></i> Log In</div>
										</a>
									</li>
									<li class="menu-item <?= $root == 'register' ? 'active' : '' ?>">
										<a class="menu-link" href="<?= u('/register') ?>">
											<div><i class="icon-user-plus"></i> Register</div>
										</a>
									</li>
									<li class="menu-item <?= $root == 'contact' ? 'active' : '' ?>">
										<a class="menu-link" href="<?= u('/contact') ?>">
											<div><i class="icon-phone-square"></i> Contact</div>
										</a>
									</li>
								</ul>
							<?php else : ?>
								<?php if (in_array($acl['superadmin'], $_SESSION['acl'])) : ?>
									<ul class="menu-container">
										<li class="menu-item <?= $root == 'learn-more' ? 'active' : '' ?>">
											<a class=" menu-link" href="<?= u('/learn-more') ?>">
												<div><i class="icon-life-ring"></i> Learn More</div>
											</a>
										</li>
										<li class="menu-item <?= $root == 'contact' ? 'active' : '' ?>">
											<a class="menu-link" href="<?= u('/contact') ?>">
												<div><i class="icon-phone-square"></i> Contact</div>
											</a>
										</li>
										<li class="menu-item <?= $root == 'admin' ? 'active' : '' ?>">
											<a class="menu-link" href="<?= u('/admin') ?>">
												<div><i class="icon-user-shield"></i> <?= lang('member_area') ?></div>
											</a>
										</li>
										<li class="menu-item <?= $root == 'superadmin' ? 'active' : '' ?>">
											<a class="menu-link" href="<?= u('/superadmin') ?>">
												<div><i class="icon-user-cog"></i> Superadmin</div>
											</a>
										</li>
										<li class="menu-item">
											<a class="menu-link" href="<?= u('/logout') ?>">
												<div><i class="icon-sign-out-alt"></i> Logout</div>
											</a>
										</li>
									</ul>
								<?php elseif (in_array($acl['admin'], $_SESSION['acl']) || in_array($acl['icp'], $_SESSION['acl'])) : ?>
									<ul class="menu-container">
										<li class="menu-item <?= $root == 'learn-more' ? 'active' : '' ?>">
											<a class=" menu-link" href="<?= u('/learn-more') ?>">
												<div><i class="icon-life-ring"></i> Learn More</div>
											</a>
										</li>
										<li class="menu-item <?= $root == 'contact' ? 'active' : '' ?>">
											<a class="menu-link" href="<?= u('/contact') ?>">
												<div><i class="icon-phone-square"></i> Contact</div>
											</a>
										</li>
										<li class="menu-item <?= $root == 'admin' ? 'active' : '' ?>">
											<a class="menu-link" href="<?= u('/admin') ?>">
												<div><i class="icon-user-shield"></i> <?= lang('member_area') ?></div>
											</a>
										</li>
										<li class="menu-item">
											<a class="menu-link" href="<?= u('/logout') ?>">
												<div><i class="icon-sign-out-alt"></i> Logout</div>
											</a>
										</li>
									</ul>
								<?php endif ?>
							<?php endif ?>


					</div>
				</div>
			</div>
		</header>

		<div class="modal fade modal-ajax" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title"><i class="icon-info-circle"></i> Information</h5>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body m-0"></div>
					<div class="modal-footer">
						<button type="button" class="button button-3d" data-dismiss="modal"><i class="icon-remove-sign"></i>Close</button>
						<button type="button" class="button button-3d extra-hidden-button d-none"><span></span> <i class="icon-circle-arrow-right"></i></button>
					</div>
				</div>
			</div>
		</div>

		<?php if (isset($_SESSION['error']) && $_SESSION['error']) : ?>
			<div class="modal fade modal-onload" tabindex="-1">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"><i class="icon-info-circle"></i> Information</h5>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
						<div class="modal-body m-0">
							<?php foreach ($_SESSION['error'] as $error) : ?>
								<p class="m-0"><?= $error ?></p>
							<?php endforeach ?>
						</div>
						<div class="modal-footer">
							<button type="button" class="button button-3d" data-dismiss="modal"><i class="icon-remove-sign"></i>Close</button>
						</div>
					</div>
				</div>
			</div>
		<?php endif ?>