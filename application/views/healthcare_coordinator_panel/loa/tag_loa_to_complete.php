
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
                
            <form id="performedLoaInfo" method="post" action="<?php echo base_url();?>healthcare-coordinator/loa-requests/approved/performed-loa-info/submit" class="needs-validation" novalidate>
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

                            <?php 
                                /* Exploding the string into an array and then checking if the array
                                contains the value. */
                                $selectedOptions = explode(';', $med_services);
                                foreach ($cost_types as $cost_type) :
                                    if (in_array($cost_type['ctype_id'], $selectedOptions)) :
                            ?>
                                <input type="hidden" class="approved-on" name="approved-on[]" value="<?php echo $approved_on ?>">
                                <input type="hidden" class="expired-on" name="expired-on[]" value="<?php echo $expired_on ?>">
                                <input type="hidden" name="ctype_id[]" value="<?php echo $cost_type['ctype_id']; ?>">
                                <div class="col-lg-4 pb-3">
                                    <label class="fw-bold">Medical Services : </label>
                                    <input type="text" class="form-control fw-bold ls-1" name="ct-name[]" value="<?php echo $cost_type['item_description']; ?>" readonly>
                                </div>

                                <div class="col-lg-2 pb-3">
                                    <label class="fw-bold">Status : </label>
                                    <select class="form-select fw-bold status" name="status[]" id="status" onchange="viewReschedDate();enableInput(); enableReason();" required>
                                        <option value="">-- Please Select --</option>
                                        <option value="Performed">Performed</option>
                                        <option value="Reschedule">Reschedule</option>
                                        <option value="Cancelled">Cancelled</option>
                                    </select>
                                    <span class="text-danger" id="status-error"></span>
                                </div>

                                <div class="col-lg-2 pb-3 performed-date">
                                    <label class="fw-bold">Date Performed : </label>
                                    <input class="form-control input-date fw-bold" name="date[]" id="date" type="text" onchange="dateValidity();" placeholder="Select Date" style="background-color:#ffff" required>
                                    <span class="text-danger" id="date-error"></span>
                                </div>
                                <div class="col-lg-2 pb-3 performed-time">
                                    <label class="fw-bold">Time Performed : </label>
                                    <input class="form-control input-time fw-bold" name="time[]" id="time" type="text" placeholder="Select Time" style="background-color:#ffff" required>
                                    <span class="text-danger" id="time-error"></span>
                                </div>
                                <div class="col-lg-2 pb-3 resched-date" style="display:none">
                                    <label class="fw-bold">Reschedule on : </label>
                                    <input class="form-control input-resched-date fw-bold" name="resched-date[]" id="resched-date" onchange="reachedDateValidity();" type="text" placeholder="Select Date" style="background-color:#ffff" required>
                                    <span class="text-danger" id="resched-date-error"></span>
                                </div>
                                <div class="col-lg-3 pb-3 reason" style="display:none">
                                    <label class="fw-bold">Reason : </label> 
                                    <input class="form-control fw-bold input-reason" name="reason[]" id="reason" type="text" placeholder="Enter reason" required>
                                    <span class="text-danger" id="reason-error"></span>
                                </div>
                                <div class="row offset-4">
                                    <div class="col-lg-2 pb-3">
                                        <label class="fw-bold label-physician">Physician : </label>
                                        <input class="form-control fw-bold fname" name="physician-fname[]" placeholder="First Name"  autocomplete="off" required>
                                        <span class="text-danger" id="physician-fname-error"></span>
                                    </div>
                                    <div class="col-lg-2 pb-3 pt-2">
                                        <label class="fw-bold"> </label>
                                        <input class="form-control fw-bold mname" name="physician-mname[]" placeholder="Middle Name"  autocomplete="off" required>
                                        <span class="text-danger" id="physician-mname-error"></span>
                                    </div>
                                    <div class="col-lg-2 pb-3 pt-2">
                                        <label class="fw-bold"> </label>
                                        <input class="form-control fw-bold lname" name="physician-lname[]" placeholder="Last Name"  autocomplete="off" required> 
                                        <span class="text-danger" id="physician-lname-error"></span>
                                    </div>
                                </div>
                              
                            <hr>
                            <?php 
                                    endif;
                                endforeach;
                            ?>
                        </div>
                        
                        <div class="offset-10 pt-3">
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
    const form = document.querySelector('#performedLoaInfo');
    $(document).ready(function(){
        
        $('#performedLoaInfo').submit(function(event){
            event.preventDefault();

            if(!form.checkValidity()){
                form.classList.add('was-validated');
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
            enableTime: false,
            dateFormat: 'm-d-Y',
        });

        $(".input-resched-date").flatpickr({
            enableTime: false,
            dateFormat: 'm-d-Y',
        });

        $( '.input-time' ).flatpickr({
            noCalendar: true,
            enableTime: true,
            dateFormat: 'h:i K'
        });

        // $('.fname').autocomplete({
        //     source: function(request, response) {
        //     $.ajax({
        //         url: '<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/approved/autocomplete',
        //         data: {
        //         term: request.term
        //         },
        //         dataType: "json",
        //         success: function(data) {
        //         response(data);
        //         }
        //     });
        //     }
        // });

    });

    const dateValidity = () => {
        const approved_on = document.querySelectorAll('.approved-on');
        const expire_on = document.querySelectorAll('.expired-on'); 
        const date_performed = document.querySelectorAll('.input-date');
        
        for (let i = 0; date_performed.length; i++){

            const date_performance = new Date(date_performed[i].value);
            const approved_date = new Date(approved_on[i].value);
            const expired_date = new Date(expire_on[i].value);

            if(date_performance < approved_date){
                swal({
                    title: 'Invalid Date',
                    text: 'Date Performed must not be less than Date Approved [ '+ approved_on[i].value +' ]',
                    showConfirmButton: true,
                    type: 'error'
                });
                date_performed[i].value = '';
                flatpickr(date_performed[i]).close();
                return;

                }else if (date_performance > expired_date) {
                    swal({
                        title: 'Invalid Date',
                        text: 'Date Performed must not be greater than Expiration Date [ '+ expire_on[i].value +' ]',
                        showConfirmButton: true,
                        type: 'error'
                    });
                date_performed[i].value = '';
                flatpickr(date_performed[i]).close();
                return;
                }
            }
    }

    const reachedDateValidity = () => {
        const approved_on = document.querySelectorAll('.approved-on');
        const expire_on = document.querySelectorAll('.expired-on'); 
        const date_resched = document.querySelectorAll('.input-resched-date');

        for(let i = 0; i < date_resched.length; i++){
            const date_reschedule = new Date(date_resched[i].value);
            const approved_date = new Date(approved_on[i].value);
            const expired_date = new Date(expire_on[i].value);

            if(date_reschedule <= expired_date){
                swal({
                    title: 'Invalid Date',
                    text: 'Reschedule Date must not be less than or equal to Expiration Date [ '+ expire_on[i].value +' ]',
                    showConfirmButton: true,
                    type: 'error'
                });
                date_resched[i].value = '';
                flatpickr(date_resched[i]).close();
                return;
            }
        }
    }

    const enableInput = () => {
        const date = document.querySelectorAll('.input-date');
        const physician_fname = document.querySelectorAll('.fname');
        const physician_mname = document.querySelectorAll('.mname');
        const physician_lname = document.querySelectorAll('.lname');
        const statusElements = document.querySelectorAll('.status');
        const reschedDateElements = document.querySelectorAll('.input-resched-date');
        const reason = document.querySelectorAll('.input-reason');

        for(let i = 0; i < statusElements.length; i++){
            const status = statusElements[i].value;

            if(status == 'Performed') {
                date[i].setAttribute('required', true); 
                physician_fname[i].setAttribute('required', true);
                physician_mname[i].setAttribute('required', true);
                physician_lname[i].setAttribute('required', true);
                reschedDateElements[i].value = '';
                reason[i].value = '';
            }
        }
       
    }

    const enableReason = () => {
        const reason = document.querySelectorAll('.reason');
        const statusElements = document.querySelectorAll('.status');
        const physician_fname = document.querySelectorAll('.fname');
        const physician_mname = document.querySelectorAll('.mname');
        const physician_lname = document.querySelectorAll('.lname');
        const label_physician = document.querySelectorAll('.label-physician');
        const performDateElements = document.querySelectorAll('.performed-date');
        const performTimeElements = document.querySelectorAll('.performed-time');
        const input_date = document.querySelectorAll('.input-date');
        const input_time = document.querySelectorAll('.input-time');
        const reschedDateElements = document.querySelectorAll('.input-resched-date');

        for(let i = 0; i < statusElements.length; i++){
            const status = statusElements[i].value;

            if(status == 'Cancelled') {
                reason[i].style.display = 'block';
                performDateElements[i].style.display = 'none';
                performTimeElements[i].style.display = 'none';
                physician_fname[i].style.display = 'none';
                physician_mname[i].style.display = 'none';
                physician_lname[i].style.display = 'none';
                label_physician[i].style.display = 'none';
                input_date[i].value = '';
                input_time[i].value = '';
                physician_fname[i].value = '';
                physician_mname[i].value = '';
                physician_lname[i].value = '';
                reschedDateElements[i].value = '';
            }else{
                reason[i].style.display = 'none';
            }
        }
    }

    const viewReschedDate = () => {
        const statusElements = document.querySelectorAll('.status');
        const performDateElements = document.querySelectorAll('.performed-date');
        const performTimeElements = document.querySelectorAll('.performed-time');
        const reschedDateElements = document.querySelectorAll('.resched-date');
        const physician_fname = document.querySelectorAll('.fname');
        const physician_mname = document.querySelectorAll('.mname');
        const physician_lname = document.querySelectorAll('.lname');
        const label_physician = document.querySelectorAll('.label-physician');
        const input_date = document.querySelectorAll('.input-date');
        const input_time = document.querySelectorAll('.input-time');
        const input_reason = document.querySelectorAll('.input-reason');


        for (let i = 0; i < statusElements.length; i++) {
            const status = statusElements[i].value;

            if (status === 'Reschedule') {
            input_date[i].value = '';
            input_time[i].value = '';
            physician_fname[i].value = '';
            physician_mname[i].value = '';
            physician_lname[i].value = '';
            input_reason[i].value = '';
            performDateElements[i].style.display = 'none';
            performTimeElements[i].style.display = 'none';
            reschedDateElements[i].style.display = 'block';
            physician_fname[i].style.display = 'none';
            physician_mname[i].style.display = 'none';
            physician_lname[i].style.display = 'none';
            label_physician[i].style.display = 'none';
            }else{
            reschedDateElements[i].style.display = 'none';
            performDateElements[i].style.display = 'block';
            performTimeElements[i].style.display = 'block';
            physician_fname[i].style.display = 'block';
            physician_mname[i].style.display = 'block';
            physician_lname[i].style.display = 'block';
            label_physician[i].style.display = 'block';
            }
        }
    }
</script>
