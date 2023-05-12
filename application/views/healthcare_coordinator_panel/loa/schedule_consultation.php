<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <a href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/approved" type="submit" class="btn btn-outline-dark" data-bs-toggle="tooltip" title="Click to Go Back">
          <strong class="ls-2" style="vertical-align:middle"><i class="mdi mdi-arrow-left-bold"></i> Back</strong>
        </a>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">Consultation</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <hr style="color:red">
      <div class="col-12">
        <div class="text-center mb-4 mt-0"><h4 class="page-title ls-2">MEDICAL APPOINTMENT SCHEDULED</h4></div>
      </div>
    <hr style="color:red">
                
    <form id="performedLoaConsultInfo" method="post" action="<?php echo base_url();?>healthcare-coordinator/loa-requests/approved/performed-loa-consultation/submit" class="needs-validation" novalidate>
      <div class="row">
        <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
        <input type="hidden" name="hp-id" value="<?php echo $hp_id ?>">
        <input type="hidden" name="loa-id" value="<?php echo $loa_id ?>">
        <input type="hidden" name="emp-id" value="<?php echo $emp_id ?>">
        <input type="hidden" name="request-type" value="<?php echo $request_type ?>">
        <input type="hidden" class="approved-on" name="approved-on" value="<?php echo $approved_on ?>">
        <input type="hidden" class="expired-on" name="expired-on" value="<?php echo $expired_on ?>">
                    
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
      </div><hr>
      
      <div class="card pt-1 shadow">
        <div class="card-body">
          <div class="row">

            <div class="col-lg-3 pb-2 pe-1">
              <label class="fw-bold">Request Type : </label>
              <input type="text" class="form-control fw-bold ls-1" name="request-type" value="<?php echo $request_type; ?>" readonly>
            </div>

            <div class="col-lg-3 pb-3">
              <label class="fw-bold">Status : </label>
              <input class="form-control fw-bold" name="status" id="status" value="Performed" readonly required>
              <span class="text-danger" id="status-error"></span>
            </div>

            <div class="col-lg-2 pb-3 performed-date">
              <label class="fw-bold">Date Performed : </label>
              <input class="form-control input-date fw-bold" name="date" id="date" type="text" onchange="dateValidity();" placeholder="Select Date" style="background-color:#ffff" required>
              <span class="text-danger" id="date-error"></span>
            </div>
                            
            <div class="col-lg-2 pb-3 performed-time">
              <label class="fw-bold">Time Performed : </label>
              <input class="form-control input-time fw-bold" name="time" id="time" type="text" placeholder="Select Time" style="background-color:#ffff" required>
              <span class="text-danger" id="time-error"></span>
            </div>

            <div class="row offset-3">
              <div class="col-lg-2 pb-3">
                <label class="fw-bold label-physician">Physician/Consultant : </label>
                <input class="form-control fw-bold fname" name="physician-fname" placeholder="First Name"  autocomplete="on" required>
                <span class="text-danger" id="physician-fname-error"></span>
              </div>
              <div class="col-lg-2 pb-3 pt-2">
                <label class="fw-bold"> </label>
                <input class="form-control fw-bold mname" name="physician-mname" placeholder="Middle Name"  autocomplete="on" required>
                <span class="text-danger" id="physician-mname-error"></span>
              </div>
              <div class="col-lg-2 pb-3 pt-2">
                <label class="fw-bold"> </label>
                <input class="form-control fw-bold lname" name="physician-lname" placeholder="Last Name"  autocomplete="on" required> 
                <span class="text-danger" id="physician-lname-error"></span>
              </div>
            </div>
          </div>
          <div class="offset-10 pt-4">
            <button class="btn btn-success fw-bold fs-4 badge" type="submit" name="submit-btn" id="submit-btn"><i class="mdi mdi-near-me"></i> Submit</button>
          </div>
        </div>
      </div>  
    </form>
  </div>
</div>


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
            dateFormat: 'Y-m-d',
            maxDate: 'today'
        });
        
        $( '.input-time' ).flatpickr({
            noCalendar: true,
            enableTime: true,
            dateFormat: 'h:i K'
        });

    });

    const dateValidity = () => {
        const approved_on = document.querySelectorAll('.approved-on');
        const expire_on = document.querySelectorAll('.expired-on'); 
        const date_performed = document.querySelectorAll('.input-date');
        
        for (let i = 0; i < date_performed.length; i++){

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

</script>
