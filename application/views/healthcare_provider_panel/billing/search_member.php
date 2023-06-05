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
    <script src="<?php echo base_url(); ?>assets/js/lone/jqueryv3.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lone/sweetalert2v11.js"></script>
    <!-- Start of Container fluid  -->
    <div class="container-fluid">
        <div class="row">

            <?php if ($this->session->flashdata('error')): ?>
                <script>
                   Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Member Not Found'
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.backdrop || result.dismiss === Swal.DismissReason.esc) {
                            // User clicked outside the modal or pressed the escape key
                            return;
                        }
                        // Reset the form here
                        const form = document.getElementById('search-form-1');
                        form.reset();
                    });
                </script>
            <?php endif; ?>

            <div class="col-12">
                <div class="card shadow">
                    <div class="border border-2 border-dark"></div>
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
                                <!-- <option value="">Select Search Method</option> -->
                                <option value="healthcard">Healthcard Number</option>
                                <option value="name">Patient Name</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-6 offset-3 mb-5 d-none" id="search-by-healthcard">
                        <form method="POST" action="<?php echo base_url(); ?>healthcare-provider/billing/search-by-healthcard" id="search-form-1" class="needs-validation" novalidate>
                            <div class="input-group">
                                <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
                                <input type="text" class="form-control" id="healthcard-no" name="healthcard_no" placeholder="Search Healthcard Number"  aria-describedby="btn-search" required>
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

        </div>
    </div>
<script>

    // ES6 syntax for window.addEventListener('load', (e) {});
    onload = (event) => {
        searchMethods();
    };
    
    $(document).ready(function(){
        /* This is a jQuery function that is used to hide and show the search form. */
        $("#search-select").on('change', function(){
            searchMethods();
        });
            $("#search-form-1")[0].reset();
            $("#search-by-name").addClass('d-none');
            $("#search-by-healthcard").removeClass('d-none is-invalid is-valid');
            $("#healthcard-no").focus();

            // $("#search-form-1").on('submit', function(event){
            // event.preventDefault();
            //     $.ajax({
            //         type: 'POST',
            //         url: $(this).attr('action'),
            //         data: $(this).serialize(),
            //         dataType: 'json',
            //         success: function(res){
            //         if(res.status == 'error'){
            //             swal({
            //             title: 'Error',
            //             text: res.message,
            //             timer: 3000,
            //             showConfirmButton: false,
            //             type: 'error'
            //             });
            //             $("#search-form-1")[0].reset();
            //         }
            //     }
            //  });
            // });
    });    

    const searchMethods = () => {
        if($('#search-select').val() == "healthcard"){

            $("#search-form-1")[0].reset();
            $("#search-by-name").addClass('d-none');
            $("#search-by-healthcard").removeClass('d-none is-invalid is-valid');

        } else if($('#search-select').val() == "name"){

            $("#search-form-2")[0].reset();
            $("#search-by-healthcard").addClass('d-none');
            $("#search-by-name").removeClass('d-none is-invalid is-valid');

        }else{

            $("#search-by-healthcard").addClass('d-none');
            $("#search-by-name").addClass('d-none');

        }
    }


    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function() {
        'use strict'
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation');
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


    // Quagga.init({
    //       inputStream : {
    //           name : "Live",
    //           type : "LiveStream",
    //           target: document.querySelector('#scanner-container')
    //       },
    //       decoder : {
    //           readers : ["ean_reader"]
    //       }
    //   }, function(err) {
    //       if (err) {
    //           console.log(err);
    //           return;
    //       }
    //       Quagga.start();
    //   });

    //   Quagga.onDetected(function(data) {
    //       var code = data.codeResult.code;
    //       document.querySelector('#healthcard-no').value = code;
    //       document.querySelector('#search-form-1').submit();
    // });
</script>