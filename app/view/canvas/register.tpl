<?= $header ?>

<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">
            <div class="card mb-0 shadow">
                <div class="card-body" style="padding: 40px;">
                    <h3>// Register</h3>

                    <form class="row mb-0" action="<?= u('/register') ?>" method="post">

                        <div class="col-12 form-group">
                            <label for="register-email">Email <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="register-email" name="email"
                                data-toggle="popover" title="Enter Your Email Address"
                                data-content="Example:<br>lily.james@gmail.com" />
                        </div>

                        <div class="col-12 form-group">
                            <button class="button button-3d m-0"><i class="icon-check-circle"></i>Send
                                Verification Link</button> <i class="icon-spinner icon-lg icon-spin d-none"></i>
                        </div>

                        <div class="col-sm-9">
                            <ul class="nav nav-pills">
                                <li style="padding-right: 10px;"><a href="<?= u('/login') ?>">Log In</a></li>
                                <li><a href="<?= u('/forgot-password') ?>">Forgot Password</a></li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $footer ?>