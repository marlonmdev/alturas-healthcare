<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="<?= base_url() ?>assets/images/hmo-logo.png">
    <!-- CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/vendors/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/custom.css">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/vendors/Toastr/build/toastr.min.css">
    <title><?= $page_title ?></title>
</head>

<body>
    <div class="image-background"></div>
    <section class="vh-100" id="main-content">
        <div class="container py-5 h-100" id="form-div">
            <div class="row d-flex align-items-center justify-content-center h-100" id="login-div">
                <div class="col-sm-8 col-md-6 col-lg-4 col-xl-4">
                    <div class="text-center">
                        <img src="<?= base_url() ?>assets/images/AhcLogo.png" class="img-fluid mx-auto" alt="Logo" width="400" height="auto">
                    </div>
                    <div class="mb-3">
                        <h5 class="text-center"><strong><span class="text-dark opacity-75">Login to your Account</span></strong></h5>
                    </div>
                    <form id="login-form">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

                        <div class="form-floating form-outline mb-3">
                            <input type="text" name="username" id="input-username" value="" class="form-control form-control-sm" placeholder="Username" autocomplete="off" />
                            <label class="form-label" for="username"><strong>Username</strong></label>
                        </div>

                        <div class="form-floating form-outline mb-3">
                            <input type="password" name="password" value="" id="input-password" class="form-control form-control-sm" placeholder="Password" autocomplete="off" />
                            <label class="form-label" for="password"><strong>Password</strong></label>
                        </div>

                        <div class="d-flex justify-content-end mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checkbox-show-password" onclick="show_password()" />
                                <label class="form-check-label" for="checkbox-show-password"><strong>Show Password</strong></label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mb-3">
                            <button type="button" class="btn btn-primary btn-lg" id="login-btn" onclick="check_login()"><img src="<?= base_url() ?>assets/images/preloader1.gif" class="d-none" width="30px" alt="Loader" id="loaderGif"> <span id="btnLoginText" style="vertical-align:middle;">LOG IN</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script src="<?= base_url() ?>assets/vendors/jquery/jquery.min.js"></script>
    <script src="<?= base_url() ?>assets/vendors/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url() ?>assets/vendors/Toastr/build/toastr.min.js"></script>
    <script src="<?= base_url() ?>assets/js/authentication.js"></script>
</body>
</html>