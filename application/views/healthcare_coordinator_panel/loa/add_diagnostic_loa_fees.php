<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title ls-2">Letter of Authorization</h4>
                <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item">Healthcare Coordinator</li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Add LOA Fees 
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
                        <a href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/completed" type="submit" class="btn btn-outline-dark" data-bs-toggle="tooltip" title="Click to Go Back">
                            <strong class="ls-2" style="vertical-align:middle">
                                <i class="mdi mdi-arrow-left-bold"></i> Go Back
                            </strong>
                        </a>
                    </div>
            </div>
                
            <form id="performedLoaInfo" method="post" action="<?php echo base_url();?>healthcare-coordinator/loa-requests/completed/ performed-loa-info/submit" class="needs-validation" novalidate>
                <div class="row">

                        <div class="col-lg-7 border border-dark">
                                
                                <label class="fw-bold pt-3">Medical Services : </label>
                                <input class="form-control fw-bold text-danger" name="med-services" readonly>
                            
                                <label class="fw-bold pt-2">Service Fee : </label>
                                <input class="form-control fw-bold text-danger" name="service-fee" readonly>

                                <label class="fw-bold pt-2">Quantity : </label>
                                <input class="form-control fw-bold text-danger" name="quantity" readonly>
                            
                        </div>
                  
                        <div class="col-lg-4 offset-1 border border-danger">
                          
                                <label class="fw-bold pt-3">Member's Name : </label>
                                <input class="form-control fw-bold text-danger" name="member-name" readonly>
                           
                                <label class="fw-bold pt-2">Healthcare Provider : </label>
                                <input class="form-control fw-bold text-danger" name="hc-provider" readonly>

                                <label class="fw-bold pt-2">Request Type : </label>
                                <input class="form-control fw-bold text-danger" name="request-type" readonly>

                                <label class="fw-bold pt-2">LOA Number : </label>
                                <input class="form-control fw-bold text-danger" name="loa-no" readonly><br>
                            
                        </div>

                </div>
                        <!-- <div class="offset-10 pt-4">
                            <button class="btn btn-success fw-bold fs-4 badge" type="submit" name="submit-btn" id="submit-btn"><i class="mdi mdi-near-me"></i> Submit</button>
                        </div> -->
                    </div>
                </div>  
            </form>
    </div>
    <!-- End Container fluid  -->
</div>
<!-- End Wrapper -->

<script>
    const form = document.querySelector('#performedLoaInfo');
    $(document).ready(function(){
        
        $('#performedLoaInfo').submit(function(event){
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
                            $('#performedLoaInfo')[0].reset();
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
            dateFormat: 'm-d-Y H:i',
        });

    });

    const enableInput = () => {
        const date = document.querySelector('#date');
        const physician = document.querySelector('#physician');
        const status = document.querySelector('#status');

        if(status.value == 'Performed') {
            date.removeAttribute('readonly');
            physician.removeAttribute('readonly');
        }else{
            date.setAttribute('readonly', true);
            physician.setAttribute('readonly', true);
        }
    }

   

</script>
