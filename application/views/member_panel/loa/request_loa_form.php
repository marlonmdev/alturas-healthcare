<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">LETTER OF AUTHORIZATION</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Member</li>
              <li class="breadcrumb-item active" aria-current="page">
                Request LOA
              </li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="card shadow">
          <div class="card-body">

            <form method="post" action="<?= base_url(); ?>member/request-loa/submit" class="mt-2" id="memberLoaRequestForm" enctype="multipart/form-data">

              <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">

              <span class="text-info fs-3 fw-bold ls-2"><i class="mdi mdi-account-card-details"></i> PATIENT DETAILS</span><br>
              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Full Name</label>
                  <input type="text" class="form-control" name="full-name" value="<?= $member['first_name'] . ' ' . $member['middle_name'] . ' ' . $member['last_name'] . ' ' . $member['suffix'] ?>" disabled>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Date of Birth</label>
                  <input type="date" class="form-control" name="date-of-birth" value="<?= $member['date_of_birth'] ?>" disabled>
                </div>
                <!-- Start of Age Calculation -->
                <?php
                  $birthDate = date("d-m-Y", strtotime($member['date_of_birth']));
                  $currentDate = date("d-m-Y");
                  $diff = date_diff(date_create($birthDate), date_create($currentDate));
                  $age = $diff->format("%y");
                ?>
                <!-- End of Age Calculation -->
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Age</label>
                  <input type="text" class="form-control" name="age" value="<?= $age ?>" disabled>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Gender</label>
                  <input type="text" class="form-control" name="gender" value="<?= $member['gender'] ?>" disabled>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">PhilHealth Number</label>
                  <input type="text" class="form-control" name="philhealth-no" value="<?= $member['philhealth_no'] ?>" disabled>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Blood Type</label>
                  <input type="text" class="form-control" name="blood-type" value="<?= $member['blood_type'] ?>" disabled>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Home Address</label>
                  <input type="text" class="form-control" name="home-address" id="patient-address" value="<?= $member['home_address'] ?>" disabled>
                </div>
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">City Address</label>
                  <input type="text" class="form-control" name="city-address" value="<?= $member['city_address'] ?>" disabled>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Contact Number</label>
                  <input type="text" class="form-control" name="contact-no" value="<?= $member['contact_no'] ?>" disabled>
                </div>
                <div class="col-sm-6">
                  <label class="colored-label">Email</label>
                  <input type="email" class="form-control" name="email" value="<?= $member['email'] ?>" disabled>
                </div>
              </div>

              <span class="text-info mt-4 fs-3 fw-bold ls-2"><i class="mdi mdi-contact-mail"></i> CONTACT PERSON DETAILS</span>
              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Contact Person Name</label>
                  <input type="text" class="form-control" name="contact-person" value="<?= $member['contact_person'] ?>" disabled>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Contact Number</label>
                  <input type="text" class="form-control" name="contact-person-no" value="<?= $member['contact_person_no'] ?>" disabled>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Address</label>
                  <input type="text" class="form-control" name="contact-person-addr" value="<?= $member['contact_person_addr'] ?>" disabled>
                </div>
              </div>

              <span class="text-info fs-3 fw-bold ls-2"><i class="mdi mdi-file-document-box"></i> LOA REQUEST DETAILS</span>
              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label"><i class="mdi mdi-asterisk text-danger"></i> Healthcare Provider</label>
                  <select class="form-select" name="healthcare-provider" id="healthcare-provider" oninput="enableRequestType()">
                    <option value="" selected>Select Healthcare Provider</option>
                    <?php
                    if (!empty($hcproviders)) :
                      foreach ($hcproviders as $hcprovider) :
                    ?>
                        <option value="<?= $hcprovider['hp_id']; ?>"><?= $hcprovider['hp_name']; ?></option>
                    <?php
                      endforeach;
                    endif;
                    ?>
                  </select>
                  <em id="healthcare-provider-error" class="text-danger"></em>
                </div>

                <div class="col-sm-3 mb-2">
                  <label class="colored-label"><i class="mdi mdi-asterisk text-danger"></i> Type of Request</label>
                  <select class="form-select" name="loa-request-type" id="loa-request-type" onchange="showMedServices()" disabled>
                    <option value="" selected>Select LOA Request Type</option>
                    <option value="Consultation">Consultation</option>
                    <option value="Diagnostic Test">Diagnostic Test</option>
                  </select>
                  <em id="loa-request-type-error" class="text-danger"></em>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-lg-12 col-sm-12 mb-2 d-none" id="med-services-div">
                  <label class="colored-label"><i class="mdi mdi-asterisk text-danger"></i> Select Medical Service/s</label><br>
                  <div id="med-services-wrapper"></div>
                  <em id="med-services-error" class="text-danger"></em>
                </div>
              </div>

              <input type="hidden" class="form-control" name="price" id="price">
              <input type="hidden" class="form-control" name="total_price" id="total_price">
              <input type="hidden" class="form-control" name="remaining_mbl" id="remaining_mbl" value="<?= $mbl['remaining_balance'] ?>">

              <div class="form-group row">
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Healthcard Number</label>
                  <input type="text" class="form-control" name="health-card-no" value="<?= $member['health_card_no'] ?>" disabled>
                </div>
                <div class="col-sm-5 mb-2">
                  <label class="colored-label">Requesting Company</label>
                  <input type="text" class="form-control" name="requesting-company" value="<?= $member['company'] ?>" disabled>
                </div>
                <div class="col-lg-4 col-sm-12 col-lg-offset-4 mb-2">
                  <?php
                    $month = date('m');
                    $day = date('d');
                    $year = date('Y');
                    $today = $year . '-' . $month . '-' . $day;
                  ?>
                  <label class="colored-label">Request Date</label>
                  <input type="text" class="form-control" name="request-date" value="<?= $today; ?>" disabled>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-12 mb-2">
                  <label class="colored-label"><i class="mdi mdi-asterisk text-danger"></i> Chief Complaint/Diagnosis</label>
                  <textarea class="form-control" name="chief-complaint" id="chief-complaint" cols="30" rows="6"></textarea>
                  <em id="chief-complaint-error" class="text-danger"></em>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-lg-6 col-sm-12 col-lg-offset-3 mb-2">
                  <label class="colored-label"><i class="mdi mdi-asterisk text-danger"></i> Requesting Physician</label>
                  <select class="form-select" id="requesting-physician" name="requesting-physician">
                    <option value="" selected>Select Requesting Physician</option>
                    <?php
                    if (!empty($doctors)) :
                      foreach ($doctors as $doctor) :
                    ?>
                        <option value="<?= $doctor['doctor_id']; ?>"><?= $doctor['doctor_name']; ?></option>
                    <?php
                      endforeach;
                    endif;
                    ?>
                  </select>
                  <em id="requesting-physician-error" class="text-danger"></em>
                </div>
                <div class="col-lg-6 col-sm-12 col-lg-offset-3 mb-2">
                  <label class="colored-label">Attending Physician <small class="text-danger"> *Note: Press Tab to Add More Physician</small></label>
                  <input type="text" class="form-control" name="attending-physician" id="tags-input">
                </div>
              </div>

              <section class="row d-none" id="div-attachment">
                <div class="form-group">
                 <span class="text-info fs-3 fw-bold ls-2"><i class="mdi mdi-attachment"></i> FILE ATTACHMENT</span>
                </div>

                <div class="form-group">
                  <div class="col-sm-12 mb-2">
                    <p id="file-type-info">
                      Allowed file types: <strong class="text-primary">jpg, jpeg, png</strong>.
                    </p>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-12 mb-4">
                    <label class="colored-label mb-1"><i class="mdi mdi-asterisk text-danger"></i> RX/Request from Accredited Doctor</label>
                    <div id="rx-file-wrapper">
                      <input type="file" class="dropify" name="rx-file" id="rx-file" data-height="300" data-max-file-size="5M" accept=".jpg, .jpeg, .png">
                    </div>
                    <em id="rx-file-error" class="text-danger"></em>
                  </div>
                </div>
              </section>

              <div class="row mt-2">
                <div class="col-sm-12 mb-2 d-flex justify-content-start">
                  <!-- <?php
                    $emp_id=$this->session->userdata('emp_id');
                    $pending = $this->loa_model->db_get_status_pending($emp_id);
                    $disabled = !empty($pending) && (!isset($pending['status']) || $pending['status'] == 'Pending');
                  ?>
                  <button type="submit" class="btn btn-primary me-2"<?php if($disabled) echo "disabled";?>><i class="mdi mdi-content-save-settings"></i> SUBMIT</button> -->



                  <?php
                    $emp_id = $this->session->userdata('emp_id');
                    $pending = $this->loa_model->db_get_status_pending($emp_id);
    
                    // Retrieve remaining balance from the database
                    $this->load->database(); // Load database library
                    $query = $this->db->get('max_benefit_limits');
                    $balance = $query->row()->remaining_balance;
                    $disabled = !empty($pending) && (!isset($pending['status']) || $pending['status'] == 'Pending' || $balance <= 100);
                  ?>
                  <button type="submit" class="btn btn-primary me-2" <?php if($disabled) echo "disabled";?>><i class="mdi mdi-content-save-settings"></i> 
                    <?php 
                      if ($balance <= 100){
                        echo "You have no MBL balance to submit form";
                      }else{
                        echo "SUBMIT";
                    }?>
                  </button>


                  <a href="#" onclick="window.history.back()" class="btn btn-danger"><i class="mdi mdi-arrow-left-bold"></i> GO BACK</a>
                </div>
              </div>

            </form>
          </div>
        </div> 
      </div>
    </div>
  </div>
</div>


<script>
  const baseUrl = "<?= base_url() ?>";
  $(document).ready(function() {
    $('#healthcare-provider').on('change', function(){
      const hp_id = $(this).val();
      const token = `<?php echo $this->security->get_csrf_hash(); ?>`;

      if(hp_id != ''){
        $.ajax({
          url: `${baseUrl}member/get-services/${hp_id}`,
          type: "GET",
          dataType: "json",
          success:function(response){
            $('#med-services-wrapper').empty();                
            $('#med-services-wrapper').append(response);
            $(".chosen-select").chosen({
              width: "100%",
              no_results_text: "Oops, nothing found!"
            }); 
          }
        });
      }
    });


    $('#med-services-wrapper').on('change', function() {
      var prices = [];
      $('#med-services option:selected').each(function() {
        prices.push(parseFloat($(this).data('price')));
      });
      var total = prices.reduce(function(acc, val) {
        return acc + val;
      }, 0);
      $('#price').val(prices.join(","));
      $('#total_price').val(total.toFixed(2));
    });


    $('#memberLoaRequestForm').submit(function(event) {
      event.preventDefault();
      let $data = new FormData($(this)[0]);
      $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: $data,
        dataType: "json",
        processData: false,
        contentType: false,
        success: function(response) {
          const {
            token,
            status,
            message,
            healthcare_provider_error,
            loa_request_type_error,
            med_services_error,
            chief_complaint_error,
            requesting_physician_error,
            rx_file_error
          } = response;
          switch (status) {
            case 'error':
              // is-invalid class is a built in classname for errors in bootstrap
              if (healthcare_provider_error !== '') {
                $('#healthcare-provider-error').html(healthcare_provider_error);
                $('#healthcare-provider').addClass('is-invalid');
              } else {
                $('#healthcare-provider-error').html('');
                $('#healthcare-provider').removeClass('is-invalid');
              }

              if (loa_request_type_error !== '') {
                $('#loa-request-type-error').html(loa_request_type_error);
                $('#loa-request-type').addClass('is-invalid');
              } else {
                $('#loa-request-type-error').html('');
                $('#loa-request-type').removeClass('is-invalid');
              }

              if (med_services_error !== '') {
                $('#med-services-error').html(med_services_error);
                $('#med-services-wrapper').addClass('div-has-error');
              } else {
                $('#med-services-error').html('');
                $('#med-services-wrapper').removeClass('div-has-error');
              }

              if (chief_complaint_error !== '') {
                $('#chief-complaint-error').html(chief_complaint_error);
                $('#chief-complaint').addClass('is-invalid');
              } else {
                $('#chief-complaint-error').html('');
                $('#chief-complaint').removeClass('is-invalid');
              }

              if (requesting_physician_error !== '') {
                $('#requesting-physician-error').html(requesting_physician_error);
                $('#requesting-physician').addClass('is-invalid');
              } else {
                $('#requesting-physician-error').html('');
                $('#requesting-physician').removeClass('is-invalid');
              }
              // div-has-error is a custom style found in assets/dashboard/css/custom.css
              if (rx_file_error !== '') {
                $('#rx-file-error').html(rx_file_error);
                $('#rx-file-wrapper').addClass('div-has-error');
              } else {
                $('#rx-file-error').html('');
                $('#rx-file-wrapper').removeClass('div-has-error');
              }
              break;
            case 'save-error':
              swal({
                title: 'Failed',
                text: message,
                timer: 3000,
                showConfirmButton: false,
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
              $('.dropify-clear').click();
              setTimeout(function() {
                window.location.href = `${baseUrl}member/requested-loa/pending`;
              }, 3200);
              break;
          }
        },
      })
    });


  });

  const enableRequestType = () => {
    const hc_provider = document.querySelector('#healthcare-provider').value;

    const request_type = document.querySelector('#loa-request-type');
      if( hc_provider != '' ){
        request_type.disabled = false;
      }else{
        request_type.disabled = true;
        request_type.value = '';
      }
  } 
  
  const showMedServices = () => {
    const loaType = document.querySelector('#loa-request-type').value;
    const medServices = document.querySelector('#med-services-div');
    const fileAttachment = document.querySelector('#div-attachment');

    if (loaType === "Consultation" || loaType === ""){
      medServices.className = "d-none";
      fileAttachment.className = "d-none";
    } else if (loaType === "Diagnostic Test") {
      medServices.className = "col-lg-7 col-sm-12 mb-2 d-block";
      fileAttachment.className = "form-group row d-block";
    }
  }
</script>