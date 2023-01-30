<!-- Start of Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
        <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Billing</h4>
            <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item">Healthcare Provider</li>
                <li class="breadcrumb-item active" aria-current="page">
                    Billing
                </li>
                </ol>
            </nav>
            </div>
        </div>
        </div>
    </div>
    <!-- End Bread crumb and right sidebar toggle -->
    <script src="<?php echo base_url(); ?>assets/js/lone/sweetalert2v11.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lone/jqueryv3.js"></script>
    <!-- Start of Container fluid  -->
    <div class="container-fluid">
        <div class="row">

            <?php if ($this->session->flashdata('error')): ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Member Not Found!'
                    })
                </script>
            <?php endif; ?>

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                    <h4 class="card-title">Search For Billing</h4>
                    <div class="col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-8 offset-sm-2 mt-3 mb-5">
                        <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-dark text-white">
                            <i class="mdi mdi-filter me-2"></i>Search By
                            </span>
                        </div>
                        <select class="form-select" name="search_select" id="search-select">
                            <option value="">Select Search Method</option>
                            <option value="healthcard">Healthcard Number</option>
                            <option value="name">Patient Name</option>
                        </select>
                        </div>
                    </div>

                    <div class="col-6 offset-3 mb-5 d-none" id="search-by-healthcard">
                        <form method="POST" action="<?php echo base_url(); ?>healthcare-provider/billing/search-by-healthcard" id="search-form-1" class="needs-validation" novalidate>
                        <div class="input-group">
                            <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
                            <input type="text" class="form-control" name="healthcard_no" placeholder="Search Healthcard Number"  aria-describedby="btn-search" required>
                            <button type="submit" class="btn btn-info" id="btn-search"><i class="mdi mdi-magnify me-1"></i>Search</button>
                        </div>
                        </form>
                    </div>

                    <div class="col-sm-12 col-md-10 offset-md-1 text-center mb-5 d-none" id="search-by-name">
                        <form method="POST" action="<?php echo base_url(); ?>healthcare-provider/billing/search-by-name" id="search-form-2" class="needs-validation" novalidate>
                        <div class="input-group">
                            <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
                            <span class="input-group-text bg-dark text-white">Name :</span>
                            <input type="text" name="first_name" class="form-control" placeholder="Enter Firstname" required>
                            <input type="text" name="last_name" class="form-control" placeholder="Enter Lastname" required>
                            <span class="input-group-text bg-dark text-white">Birthday :</span>
                            <input type="date" name="date_of_birth" class="form-control" required>
                            <button type="submit" class="btn btn-info" id="btn-search"><i class="mdi mdi-magnify me-1"></i>Search</button>
                        </div>
                        </form>
                    </div>
                    
                    </div>
                </div>
            </div>

    <!-- <div class="container py-2">
        <h2> <i class="bx bxs-receipt align-middle bx-icon icon-dark"></i> Search For Billing</h2>
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
                <form action="<?php echo base_url('healthcare-provider/billing/billing-person/find-by-id'); ?>" class="needs-validation" method="post" novalidate>
                    <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <div class="row">
                        <div class="col-6 offset-3">
                            <input type="text" class="form-control" id="inputPersonDate" name="healthCardNo" placeholder="Enter Healthcard Number" required>
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
                <form method="post" action="<?php echo base_url('healthcare-provider/billing/billing-person/find'); ?>" class="needs-validation" novalidate>
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
    </div> -->

    </div>
</div>


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
    
    $(document).ready(function(){
        /* This is a jQuery function that is used to hide and show the search form. */
        $("#search-select").on('change', function(){
            if($(this).val() == "healthcard"){
                $("#search-form-1")[0].reset();
                $("#search-by-name").addClass('d-none');
                $("#search-by-healthcard").removeClass('d-none is-invalid is-valid');
            } else if($(this).val() == "name"){
                $("#search-form-2")[0].reset();
                $("#search-by-healthcard").addClass('d-none');
                $("#search-by-name").removeClass('d-none is-invalid is-valid');
            }else{
                $("#search-by-healthcard").addClass('d-none');
                $("#search-by-name").addClass('d-none');
            }
        });
    });    


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