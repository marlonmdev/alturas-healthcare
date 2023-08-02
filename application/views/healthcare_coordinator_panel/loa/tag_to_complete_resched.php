<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <a href="#" onclick="goBack()" type="submit" class="btn btn-danger" data-bs-toggle="tooltip" title="Click to Go Back">
          <strong class="ls-2" style="vertical-align:middle"><i class="mdi mdi-arrow-left-bold"></i> Back</strong>
        </a>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">HSR</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="card pt-1 shadow">
      <div class="card-body">
        <div class="row">
          <div class="col-xs-12 d-flex justify-content-center align-items-center">
            <img src="<?= base_url(); ?>assets/images/logo2.png" alt="Alturas Healthcare Logo" height="70" width="300">
          </div>
          <div class="col-12 pt-3">
            <div class="text-center mb-4 mt-0"><h4 class="page-title ls-2" style="color:black;font-family:Times Roman">HEALTHCARE SERVICES RECORD</h4></div>
          </div><hr style="color:gray">
        </div>
                
        <form id="performedLoaInfo" method="post" action="<?php echo base_url();?>healthcare-coordinator/loa-requests/approved/performed-loa-info/submit" class="needs-validation" novalidate>
          <div class="row pt-3">
            <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
            <input type="hidden" name="hp-id" value="<?php echo $hp_id ?>">
            <input type="hidden" name="loa-id" value="<?php echo $loa_id ?>">
            <input type="hidden" name="emp-id" value="<?php echo $emp_id ?>">
            <input type="hidden" name="request-type" value="<?php echo $request_type ?>">
              
            <div class="col-lg-4">
              <label>Member's Name : </label>
              <input class="form-control" type="text" name="member-name" id="member-name" value="<?php echo $full_name ?>" readonly>
            </div>

            <div class="col-lg-4">
              <labe>LOA Number : </label>
              <input class="form-control" type="text" name="loa-num" id="loa-num" value="<?php echo $loa_no?>" readonly>
            </div>

            <div class="col-lg-4">
              <label>Healthcare Provider : </label>
              <input class="form-control" type="text" name="hp-name" id="hp-name" value="<?php echo $hc_provider ?>" readonly>
            </div>
          </div>

      
          <div class="row pt-3">
            <?php 
              $selectedOptions = explode(';', $med_services);
              foreach ($cost_types as $cost_type) :
                if (in_array($cost_type['ctype_id'], $selectedOptions)) :
            ?>
              <input type="hidden" class="approved-on" name="approved-on[]" value="<?php echo $approved_on ?>">
              <input type="hidden" class="expired-on" name="expired-on[]" value="<?php echo $expired_on ?>">
              <input type="hidden" name="ctype_id[]" value="<?php echo $cost_type['ctype_id']; ?>">
              
              <div class="col-lg-4 pb-3">
                <label>Medical Services : </label>
                <input type="text" class="form-control" name="ct-name[]" value="<?php echo $cost_type['item_description']; ?>" readonly>
              </div>

              <div class="col-lg-4 pb-3">
                <label>Status : </label>
                <select class="form-select status" name="status[]" id="status" onchange="viewReschedDate();enableInput();enableReason();emptyStatus()">
                  <option value="">-- Please Select --</option>
                  <option value="Performed">Performed</option>
                  <option value="Referred">Referral</option>
                  <option value="Cancelled">Cancelled</option>
                </select>
                <span class="text-danger" id="status-error"></span>
              </div>

              <!-- <div class ="col-lg-4 pb-3 referralFile" style="display: none;">
                <label class="fw-bold">Upload File:</label>
                <input type="file" class="form-control input-referral" name="referralFile[]" id="referralFile">
                <span class="text-danger" id="referral-error"></span>
              </div> -->

              <div class="col-lg-2 pb-3 performed-date">
                <label>Date Performed : </label>
                <input class="form-control input-date" name="date[]" id="date" type="text" onchange="dateValidity();" placeholder="Select Date" style="background-color:#ffff" required>
                <span class="text-danger" id="date-error"></span>
              </div>

              <div class="col-lg-2 pb-3 performed-time">
                <label>Time Performed : </label>
                <input class="form-control input-time" name="time[]" id="time" type="text" placeholder="Select Time" style="background-color:#ffff" required>
                <span class="text-danger" id="time-error"></span>
              </div>

              <div class="col-lg-4 pb-3 reason" style="display:none">
                <label>Reason : </label> 
                <input class="form-control input-reason" name="reason[]" id="reason" type="text" placeholder="Enter reason" required>
                <span class="text-danger" id="reason-error"></span>
              </div>

              <div class="row offset-4">
                <div class="col-lg-2 pb-3">
                  <label class="label-physician">Physician : </label>
                  <input class="form-control fname" name="physician-fname[]" placeholder="First Name"  autocomplete="on" required>
                  <span class="text-danger" id="physician-fname-error"></span>
                </div>
                <div class="col-lg-2 pb-3 pt-2">
                  <label></label>
                  <input class="form-control mname" name="physician-mname[]" placeholder="Middle Name"  autocomplete="on" required>
                  <span class="text-danger" id="physician-mname-error"></span>
                </div>
                <div class="col-lg-2 pb-3 pt-2">
                  <label></label>
                  <input class="form-control lname" name="physician-lname[]" placeholder="Last Name"  autocomplete="on" required> 
                  <span class="text-danger" id="physician-lname-error"></span>
                </div>
              </div>
            <?php 
              endif;
              endforeach;
            ?>
          </div>

          <div class="text-center pt-5 pb-5">
            <button class="btn btn-info fw-bold fs-4 badge" type="submit" name="submit-btn" id="submit-btn"><i class="mdi mdi-near-me"></i> Submit</button>
          </div>

        </div>
      </div>  
    </form>
  </div>
</div>


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

              setTimeout(function () {
                window.location.href = '<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/completed';
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

  const emptyStatus =  () => {
    const statusElements = document.querySelectorAll('.status');
    const physician_fname = document.querySelectorAll('.fname');
    const physician_mname = document.querySelectorAll('.mname');
    const physician_lname = document.querySelectorAll('.lname');
    const date = document.querySelectorAll('.input-date');
    const input_reason = document.querySelectorAll('.input-reason');


    for(let i = 0; i < statusElements.length; i++){
      const status = statusElements[i].value;
      if(status == ''){
        date[i].removeAttribute('required'); 
        physician_fname[i].removeAttribute('required');
        physician_mname[i].removeAttribute('required');
        physician_lname[i].removeAttribute('required');
        input_reason[i].removeAttribute('required');
      }
    }
  }

  const enableInput = () => {
    const date = document.querySelectorAll('.input-date');
    const physician_fname = document.querySelectorAll('.fname');
    const physician_mname = document.querySelectorAll('.mname');
    const physician_lname = document.querySelectorAll('.lname');
    const statusElements = document.querySelectorAll('.status');
    const reason = document.querySelectorAll('.input-reason');

    for(let i = 0; i < statusElements.length; i++){
      const status = statusElements[i].value;
      if(status == 'Performed') {
        date[i].setAttribute('required', true); 
        physician_fname[i].setAttribute('required', true);
        physician_mname[i].setAttribute('required', true);
        physician_lname[i].setAttribute('required', true);;
        reason[i].removeAttribute('required');
        reason[i].value = '';
        flatpickr(date, {
          maxDate: 'today'
        });
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
    // console.log('reason',statusElements.length);
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
        input_date[i].removeAttribute('required'); 
        input_time[i].removeAttribute('required'); 
        physician_fname[i].removeAttribute('required');
        physician_mname[i].removeAttribute('required');
        physician_lname[i].removeAttribute('required');
      }else{
        reason[i].style.display = 'none';
      }
    }
  }

  const viewReschedDate = () => {
    const statusElements = document.querySelectorAll('.status');
    const performDateElements = document.querySelectorAll('.performed-date');
    const performTimeElements = document.querySelectorAll('.performed-time');
    const physician_fname = document.querySelectorAll('.fname');
    const physician_mname = document.querySelectorAll('.mname');
    const physician_lname = document.querySelectorAll('.lname');
    const label_physician = document.querySelectorAll('.label-physician');
    // const referralFile = document.querySelectorAll('.referralFile');

    for (let i = 0; i < statusElements.length; i++) {
      const status = statusElements[i].value;
      if (status === 'Referred') {
        performDateElements[i].style.display = 'none';
        performTimeElements[i].style.display = 'none';
        physician_fname[i].style.display = 'none';
        physician_mname[i].style.display = 'none';
        physician_lname[i].style.display = 'none';
        label_physician[i].style.display = 'none';
        // referralFile[i].style.display = 'block';

        // Reset values and remove 'required' attribute
        performDateElements[i].value = '';
        performTimeElements[i].value = '';
        physician_fname[i].value = '';
        physician_mname[i].value = '';
        physician_lname[i].value = '';
        performDateElements[i].removeAttribute('required');
        performTimeElements[i].removeAttribute('required');
        physician_fname[i].removeAttribute('required');
        physician_mname[i].removeAttribute('required');
        physician_lname[i].removeAttribute('required');
      } else {
        performDateElements[i].style.display = 'block';
        performTimeElements[i].style.display = 'block';
        physician_fname[i].style.display = 'block';
        physician_mname[i].style.display = 'block';
        physician_lname[i].style.display = 'block';
        label_physician[i].style.display = 'block';
        // referralFile[i].style.display = 'none';
      }
    }
  }

  const goBack = () => {
    window.history.back();
  }

</script>
