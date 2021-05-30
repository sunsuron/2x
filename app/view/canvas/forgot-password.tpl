<?= $header ?>

<section id="content">
	<div class="content-wrap">
		<div class="container clearfix">
			<div class="card mb-0 shadow">
				<div class="card-body" style="padding: 40px;">
					<h3>// Forgot Password</h3>

					<form class="row mb-0" action="<?= u('/forgot-password') ?>" method="post">

						<div class="col-12 form-group">
							<label for="reset-email">Email <span class="text-danger">*</span></label>
							<input type="text" class="form-control" id="reset-email" name="email" />
						</div>

						<div class="col-12 form-group">
							<button class="button button-3d m-0"><i class="icon-check-circle"></i>Send
								Reset Link Now</button> <i class="icon-spinner icon-lg icon-spin d-none"></i>
						</div>

						<div class="col-sm-9">
							<ul class="nav nav-pills">
								<li style="padding-right: 10px;"><a href="<?= u('/login') ?>">Log In</a></li>
								<li><a href="<?= u('/register') ?>">Register</a></li>
							</ul>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>

<?= $footer ?>