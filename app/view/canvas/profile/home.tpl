<?= $header ?>

<section id="content">
	<div class="content-wrap">
		<div class="container clearfix">
			<div class="card mb-0 shadow">
				<div class="card-body" style="padding: 40px;">
					<h3>// Profile</h3>

					<form class="row mb-0" action="<?= u('/profile') ?>" method="post">
						<div class="col-lg-6">
							<div class="row">
								<p class="lead"><?= lang('login_details') ?></p>
							</div>
							<div class="col-12 form-group">
								<label for="profile-mobile_no">Mobile No<span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="profile-mobile_no" name="mobile_no" data-toggle="popover" title="Enter Your Mobile Number" data-content="Mobile number will be required when logging in to the system. Example:<br>0179553208" value="<?= isset($user['mobile_no']) ? $user['mobile_no'] : '' ?>" />
							</div>
							<div class="col-12 form-group">
								<?php if (in_array($acl['icp'], $_SESSION['acl'])) : ?>
									<label for="profile-password">Password
										<span class="text-danger">*</span></label>
								<?php else : ?>
									<label for="profile-password">Update Password</label>
								<?php endif ?>
								<input type="password" class="form-control" id="profile-password" name="password" data-toggle="popover" title="Enter Your Password" data-content="Password will be required when logging in to the system." />
							</div>

						</div>
						<div class="col-lg-6">
							<div class="row">
								<p class="lead"><?= lang('personal_details') ?></p>
							</div>

							<div class="col-12 form-group">
								<label for="profile-fullname">Full Name <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="profile-fullname" name="fullname" data-toggle="popover" title="Enter Your Full Name" data-content="This information is based on your NRIC." value="<?= isset($user['fullname']) ? $user['fullname'] : '' ?>" />
							</div>

							<div class="col-12 form-group">
								<button class="button button-3d m-0"><i class="icon-check-circle"></i> Submit</button>
								<i class="icon-spinner icon-lg icon-spin d-none"></i>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>

<?= $footer ?>