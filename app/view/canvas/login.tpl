<?= $header ?>

<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">
            <div class="card mb-0 shadow">
                <div class="card-body" style="padding: 40px;">
                    <form class="mb-0" action="<?= u('/login') ?>" method="post">

                        <h3>// Log In</h3>

                        <div class="row">
                            <div class="col-12 form-group">
                                <label for="login-email">Email<span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="login-email" name="email" data-toggle="popover" data-content="Example:<br>lily.james@gmail.com">
                            </div>

                            <div class="col-12 form-group">
                                <label for="login-password">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="login-password" name="password" data-toggle="popover" title="Enter Your Password" data-content="If you forgot your password, click the <em>Forgot Password</em> link below. Register new account if you have not registered before." />
                            </div>

                            <div class="col-12 form-group">
                                <button class="button button-3d m-0"><i class="icon-check-circle"></i>Log In</button> <i class="icon-spinner icon-lg icon-spin d-none"></i>
                            </div>

                            <div class="col-sm-9">
                                <ul class="nav nav-pills">
                                    <li style="padding-right: 10px;"><a href="<?= u('/register') ?>">Register</a></li>
                                    <li><a href="<?= u('/forgot-password') ?>">Forgot Password</a></li>
                                </ul>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $footer ?>