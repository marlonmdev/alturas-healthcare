<script src="<?php echo base_url(); ?>assets/js/lone/sweetalert2v11.js"></script>
<script src="<?php echo base_url(); ?>assets/js/lone/jqueryv3.js"></script>
<main id="main" class="main">
    <?php if ($this->session->flashdata('error')): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Member Not Found!',
            })
        </script>
    <?php endif; ?>

    <div class="container py-2">
        <h2> <i class="bx  bxs-group align-middle bx-icon icon-dark"></i> Search Member</h2>
    </div>

    <ul class="find-nav nav-tabs">
        <li class="active" style="margin-left: 30%;">
            <a href="#tab1" data-toggle="tab"><strong>Search By Healthcard #</strong></a>
        </li>
        <li>
            <a href="#tab2" data-toggle="tab"><strong>Search By Name</strong></a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab1">
            <div class="container px-5 py-5">
                <form action="<?php echo base_url('healthcare-provider/profile/viewId'); ?>" class="needs-validation" method="post" novalidate>
                    <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <div class="row">
                        <div class="col-6 offset-3">
                            <input type="text" class="form-control" id="inputId" name="healthCardNo" placeholder="Enter Healthcard Number" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 offset-3 d-flex justify-content-center align-items-center">
                            <button type="submit" class="btn btn-primary my-4 submit-btn">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="tab-pane" id="tab2">
            <div class="container px-5 py-5">
                <form method="post" action="<?php echo base_url('healthcare-provider/profile/view'); ?>" class="needs-validation" novalidate>
                    <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="row">
                        <div class="col">
                            <input type="text" class="form-control" name="firstNameMember" id="inputFirstName" placeholder="Enter Firstname" required>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" name="lastNameMember" id="inputLastName" placeholder="Enter Lastname" required>
                        </div>
                        <div class="col">
                            <input type="date" class="form-control" name="dateMember" id="inputPersonDate" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 offset-3 d-flex justify-content-center align-items-center">
                            <button type="submit" class="btn btn-primary my-4 submit-btn">SUBMIT</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<!-- <style>
    input[type="text"], input[type="date"] {
        padding: 1.1em;
    }

    button.submit-btn {
        padding: 1em 2em !important;
        font-size: .9em !important;
    }

    .find-nav {
        padding-left: 0px;
        margin-bottom: 0px;
        list-style-type: none;
        list-style-image: none;
        list-style-position: outside;
    }

    .find-nav, .nav-tabs {
        background-color: #0075DA;
        height: 70px;
        color: #ffff;
    }

    .nav-tabs > li {
        float: left;
        margin-bottom: -1px;
    }

    nav, .nav-tabs > li > a {
        color: #fff;
        text-decoration-line: none;
        position: relative;
        display: block;
        padding-top: 25px;
        padding-right: 15px;
        padding-bottom: 24px;
        padding-left: 15px;
    }

    .nav-tabs > li.active > a {
        color: #ffff;
    }

    .nav-tabs > li.active {
        background-color: #FFB103;
    }

    .tab-content {
        color: #ffff;
        background-color: #ffffff;
        padding: 5px 15px;
    }

    .tab-content > .tab-pane {
        display: none;
    }

    .tab-content > .active {
        display: block;
    }

    li.active a:before {
        border-color: #FFB103 transparent transparent transparent;
        border-style: solid;
        border-width: 16px 16px 0px 16px;
        content: "";
        display: block;
        left: calc(50% - 10px);
        position: absolute;
        width: 0px;
        top: 70px;
        z-index: 10;
    }
</style> -->

<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function() {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
    })()

</script>