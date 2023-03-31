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
    <script>
        $(document).ready(() => {
            // runs on Enter key keypress
            $("#login-form").keypress((event) => {
                // 13 - key code for Enter
                if (event.which === 13) {
                    check_login();
                }
            });
        });

        const show_password = () => {
            let input_password = document.querySelector("#input-password");
            if (input_password.type === "password") {
                input_password.type = "text";
            } else {
                input_password.type = "password";
            }
        }

        /**
         * It sends the data from the login form to the server, and if the server responds with a status of
         * "error", it displays the error message. Otherwise, it calls the login_validated function
         */
        const check_login = () => {
            $.ajax({
                url: "check-login",
                data: $("#login-form").serialize(),
                type: "post",
                dataType: "json",
                beforeSend: function () {
                    $("#btnLoginText").html("CHECKING...");
                },
                success: function (res) {
                    const { status, message, username, password } = res;
                    const input_username = document.querySelector("#input-username");
                    const input_password = document.querySelector("#input-password");
                    if (status == "error") {
                        toastr.options = {
                            closeButton: true,
                            preventDuplicates: true,
                            positionClass: "toast-top-right",
                        };
                        input_username.value = username;
                        input_password.value = password;
                        input_username.classList.add("invalid-input", "text-danger");
                        input_password.classList.add("invalid-input", "text-danger");
                        toastr["error"](message);
                        $("#btnLoginText").html("LOG IN");
                    } else {
                          login_validated(res.token, res.user_id, res.emp_id, res.fullname, res.user_role, res.dsg_hcare_prov, res.doctor_id, res.logged_in, res.next_route, res.next_page);
                    }
                },
            });
        }

        const login_validated = (token, user_id, emp_id, fullname, user_role, dsg_hcare_prov, doctor_id, logged_in,
        next_route, next_page) => {
            $.ajax({
                url: next_route,
                data: {token, user_id, emp_id, fullname, user_role, dsg_hcare_prov, doctor_id, logged_in},
                type: "post",
                dataType: "json",
                success: function (response) {
                    const { status, message } = response;

                    if (status == "success") {
                        $("#loaderGif").removeClass("d-none");
                        $("#loaderGif").show();
                        $("#btnLoginText").html("LOGGING IN...");
                        updateExpiredLoa(user_role, emp_id, next_page);
                    } else {
                        toastr.options = {
                            closeButton: true,
                            preventDuplicates: true,
                        };

                        toastr["error"](message);
                        $("#btnLoginText").html("LOG IN");
                    }
                },
            });
        }

        const baseUrl = `<?php echo base_url(); ?>`;

        const updateExpiredLoa = (user_role, emp_id, next_page) => {
            switch(user_role){
                case 'member':
                    $.ajax({
                        url: `${baseUrl}check-member/approved-loa/expired/update/${emp_id}`,
                        method: "GET",
                        success: function(res) {
                            setTimeout(function () {
                                window.location.href = next_page;
                            }, 500);
                        }
                    });
                    break;
                case 'healthcare-provider':
                    setTimeout(function () {
                        window.location.href = next_page;
                    }, 500);   
                    break;
                default:
                    $.ajax({
                        url: `${baseUrl}check-all/approved-loa/expired/update`,
                        method: "GET",
                        success: function(res) {
                            setTimeout(function () {
                                window.location.href = next_page;
                            }, 500);
                        }
                    });
            }
        }

    </script>
</body>
</html>