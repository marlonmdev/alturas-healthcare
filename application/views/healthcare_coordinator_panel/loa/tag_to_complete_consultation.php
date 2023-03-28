
<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title ls-2">LOA</h4>
                <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item">Healthcare Coordinator</li>
                    <li class="breadcrumb-item active" aria-current="page">
                        LOA for Completion 
                    </li>
                    </ol>
                </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- Start of Container fluid  -->
    <div class="container-fluid">
            <div class="col-12 mb-4 mt-0">
                        <div class="input-group">
                            <a href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/approved" type="submit" class="btn btn-outline-dark" data-bs-toggle="tooltip" title="Click to Go Back">
                                <strong class="ls-2" style="vertical-align:middle">
                                    <i class="mdi mdi-arrow-left-bold"></i> Go Back
                                </strong>
                            </a>
                        </div>
                </div>
                
            <form id="performedLoaConsultInfo" method="post" action="<?php echo base_url();?>healthcare-coordinator/loa-requests/approved/performed-loa-consultation/submit" class="needs-validation" novalidate>
                <div class="row">
                    <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="hp-id" value="<?php echo $hp_id ?>">
                    <input type="hidden" name="loa-id" value="<?php echo $loa_id ?>">
                    <input type="hidden" name="emp-id" value="<?php echo $emp_id ?>">
                    <input type="hidden" name="request-type" value="<?php echo $request_type ?>">
                    
                    <div class="col-lg-4">
                        <label class="fw-bold">Member's Name : </label>
                        <input class="form-control text-danger fw-bold" type="text" name="member-name" id="member-name" value="<?php echo $full_name ?>" readonly>
                    </div>
                    <div class="col-lg-3">
                        <label class="fw-bold">LOA Number : </label>
                        <input class="form-control text-danger fw-bold" type="text" name="loa-num" id="loa-num" value="<?php echo $loa_no?>" readonly>
                    </div>     
                    <div class="col-lg-5">
                        <label class="fw-bold">Healthcare Provider : </label>
                        <input class="form-control text-danger fw-bold" type="text" name="hp-name" id="hp-name" value="<?php echo $hc_provider ?>" readonly>
                    </div>
                </div>
                <hr>
                <div class="card pt-1 shadow">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-lg-3 pb-2 pe-1">
                                    <label class="fw-bold">Request Type : </label>
                                    <input type="text" class="form-control fw-bold ls-1" name="request-type" value="<?php echo $request_type; ?>" readonly>
                            </div>

                            <div class="col-lg-3 pb-3">
                                <label class="fw-bold">Status : </label>
                                <select class="form-select fw-bold" name="status" id="status" onchange="enableInput()" required>
                                    <option value="">Select...</option>
                                    <option value="Performed">Performed</option>
                                    <option value="Not yet performed">Not yet performed</option>
                                </select>
                                <span class="text-danger" id="status-error"></span>
                            </div>

                            <div class="col-lg-3 pb-3">
                                <label class="fw-bold">Date & Time of Consultation : </label>
                                <input class="form-control input-date fw-bold" name="date" id="date" type="text" placeholder="Select Date" style="background-color:#ffff">
                                <span class="text-danger" id="date-error"></span>
                            </div>

                            <div class="col-lg-3 pb-3">
                                <label class="fw-bold">Doctor/Consultant : </label>
                                <input class="form-control fw-bold" name="physician" id="physician">
                                <span class="text-danger" id="physician-error"></span>
                            </div>

                        </div>
                        <div class="offset-10 pt-4">
                            <button class="btn btn-success fw-bold fs-4 badge" type="submit" name="submit-btn" id="submit-btn"><i class="mdi mdi-near-me"></i> Submit</button>
                        </div>
                    </div>
                </div>  
            </form>
    </div>
    <!-- End Container fluid  -->
</div>
<!-- End Wrapper -->

<script>
    const form = document.querySelector('#performedLoaConsultInfo');
    $(document).ready(function(){
        
        $('#performedLoaConsultInfo').submit(function(event){
            event.preventDefault();

            if(!form.checkValidity()){
                form.classLIst.add('was-validated');
                return;
            }

            let url = $(this).attr('action');
            let data = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'json',
                success: function(response) {
                    const {
                        token,status,message
                    } = response;

                    switch(status){
                       
                        case 'failed':
                            swal({
                                title: 'Error',
                                text: message,
                                timer: 3000,
                                showConfirmButton: true,
                                type: 'error'
                            });
                        break;

                        case 'success':
                            swal({
                                title: 'Success',
                                text: message,
                                timer: 3000,
                                showConfirmButton: false,
                                type: 'success'
                            });
                            $('#performedLoaConsultInfo')[0].reset();
                            setTimeout(function () {
                                window.location.href = '<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/approved';
                            }, 2600);
                            
                        break;

                        case 'complete-success':
                            swal({
                                title: 'Success',
                                text: message,
                                timer: 3000,
                                showConfirmButton: false,
                                type: 'success'
                            });
                            $('#performedLoaConsultInfo')[0].reset();
                            setTimeout(function () {
                                window.location.href = '<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/completed';
                            }, 2600);
                            
                        break;
                    }
                }
            });
        });

        $(".input-date").flatpickr({
            enableTime: true,
            dateFormat: 'm-d-Y h:i K',
        });

    });

    const enableInput = () => {
        const date = document.querySelector('#date');
        const physician = document.querySelector('#physician');
        const status = document.querySelector('#status').value;

        if(status == 'Performed') {
            date.setAttribute('required', true);
            physician.setAttribute('required', true);
        }else{
            date.removeAttribute('required');
            physician.removeAttribute('required');
        }
    }

   

</script>