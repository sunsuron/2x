<?= $header ?>

<section id="content">
	<div class="content-wrap">
		<div class="container clearfix">
			<div class="card mb-0 shadow">
				<div class="card-body" style="padding: 40px;">
					<h3>// <?= lang('reset_password') ?></h3>

					<form class="row mb-0" action="<?= u('/reset-password') ?>" method="post">

						<div class="col-12 form-group">
							<label for="rpasswd-new-passwd"><?= lang('password') ?> <span
									class="text-danger">*</span></label>
							<input type="password" class="form-control" id="rpasswd-new-passwd" name="password" />
						</div>

						<div class="col-12 form-group">
							<label for="rpasswd-new-passwd"><?= lang('password_again') ?> <span
									class="text-danger">*</span></label>
							<input type="password" class="form-control" id="rpasswd-new-passwd" name="password_again" />
						</div>

						<div class="col-12 form-group">
							<button class="button button-3d m-0"><i class="icon-check-circle"></i>Reset
								Password</button> <i class="icon-spinner icon-lg icon-spin d-none"></i>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>
</section>

<?= $footer ?>