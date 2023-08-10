<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block flex-column flex-sm-row align-items-left">
        <h4 class="page-title ls-2">LETTER OF AUTHORIZATION</h4>
        <div class="ms-auto text-end order-first order-sm-last">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Company Doctor</li>
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

            <form class="mt-2" id="overrideLOARequestForm" enctype="multipart/form-data">

              <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
              <input type="hidden" name="doctor-id" value="<?php echo $doctor_id; ?>">
              <input type="hidden" name="emp-id" id="emp-id">
              <div class="form-group row">
                <div class="col-lg-5 mt-3 mb-3" id="search-member-div">
                  <input type="text" class="form-control" name="search-member" id="input-search-member" onkeyup="searchHmoMember()" placeholder="Search Member Here..." />
                  <div id="member-search-div">
                    <div id="search-results" class="border-top-0"></div>
                  </div>
                </div>
                <input type="number" id="mbl" hidden>
                <input id="member-id" hidden>
              </div>
              <br>
              <span class="text-info fs-3 fw-bold ls-2"><i class="mdi mdi-account-card-details"></i> PATIENT DETAILS</span><br>
              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Full Name</label>
                  <input type="text" class="form-control" name="full-name" id="full-name" disabled>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Date of Birth</label>
                  <input type="date" class="form-control" name="date-of-birth" id="date-of-birth" disabled>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Age</label>
                  <input type="text" class="form-control" name="age" id="age" disabled>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Gender</label>
                  <input type="text" class="form-control" name="gender" id="gender" disabled>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">PhilHealth Number</label>
                  <input type="text" class="form-control" name="philhealth-no" id="philhealth-no" disabled>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Blood Type</label>
                  <input type="text" class="form-control" name="blood-type" id="blood-type" disabled>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Home Address</label>
                  <input type="text" class="form-control" name="home-address" id="patient-address" disabled>
                </div>
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">City Address</label>
                  <input type="text" class="form-control" name="city-address" id="city-address" disabled>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Contact Number</label>
                  <input type="text" class="form-control" name="contact-no" id="contact-no" disabled>
                </div>
                <div class="col-sm-6">
                  <label class="colored-label">Email</label>
                  <input type="email" class="form-control" name="email" id="email" disabled>
                </div>
              </div>

              <span class="text-info mt-4 fs-3 fw-bold ls-2"><i class="mdi mdi-contact-mail"></i> CONTACT PERSON DETAILS</span>
              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Contact Person Name</label>
                  <input type="text" class="form-control" name="contact-person" id="contact-person" disabled>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Contact Number</label>
                  <input type="text" class="form-control" name="contact-person-no" id="contact-person-no" disabled>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Address</label>
                  <input type="text" class="form-control" name="contact-person-addr" id="contact-person-addr" disabled>
                </div>
              </div>

              <span class="text-info fs-3 fw-bold ls-2"><i class="mdi mdi-file-document-box"></i> LOA REQUEST DETAILS</span>
              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label"><i class="mdi mdi-asterisk text-danger"></i> Healthcare Provider</label>
                  <select class="form-select" name="healthcare-provider" id="healthcare-provider" oninput="enableRequestType()">
                    <option selected>Select Healthcare Provider</option>
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
                    <option selected>Select LOA Request Type</option>
                    <option value="Diagnostic Test">Diagnostic Test</option>
                    <option value="Emergency">Emergency</option>
                  </select>
                  <em id="loa-request-type-error" class="text-danger"></em>
                </div>

                <div class="col-sm-3 mb-2">
                  <label class="colored-label"><i class="mdi mdi-asterisk text-danger"></i>MBL Balance</label>
                  <input type="text" class="form-control" name="remaining_mbl" id="remaining_mbl" disabled>
                </div>
                
              </div>
              <div class="form-group row">
                <div class="col-lg-12 col-sm-12 mb-2 d-none" id="hospitalized-div">
                  <label class="colored-label"><i class="mdi mdi-asterisk text-danger"></i> Date Hospitalized</label><br>
                  <input type="text" class="form-control" name="hopitalized-date" id="hopitalized-date" placeholder="Select Date" style="background-color:#ffff">
                  <em id="hospitalized-error" class="text-danger"></em>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-lg-12 col-sm-12 mb-2 d-none" id="med-services-div">
                  <label class="colored-label"><i class="mdi mdi-asterisk text-danger"></i> Select Medical Service/s</label><br>
                  <div id="med-services-wrapper"></div>
                  <em id="med-services-error" class="text-danger"></em>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Healthcard Number</label>
                  <input type="text" class="form-control" name="health-card-no" id="health-card-no" disabled>
                </div>
                <div class="col-sm-5 mb-2">
                  <label class="colored-label">Requesting Company</label>
                  <input type="text" class="form-control" name="requesting-company" id="requesting-company" disabled>
                </div>
                <div class="col-lg-4 col-sm-12 col-lg-offset-4 mb-2">
                  <label class="colored-label">Request Date</label>
                  <input type="text" class="form-control" name="request-date" value="<?= date('F d,Y'); ?>" disabled>
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
                <div class="col-lg-6 col-sm-12 col-lg-offset-3 mb-2" id="req-physician-div">
                  <label class="colored-label"><i class="mdi mdi-asterisk text-danger"></i> Requesting Physician</label>
                  <select class="form-select" id="requesting-physician" name="requesting-physician">
                    <option value="<?php echo $doctor['doctor_id']; ?>"><?php echo $doctor['doctor_name']; ?></option>
                  </select>
                  <em id="requesting-physician-error" class="text-danger"></em>
                </div>
                <div class="col-lg-6 col-sm-12 col-lg-offset-3 mb-2" id="physician-div">
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
                  <button type="submit" class="btn btn-primary me-2" id="submit"><i class="mdi mdi-content-save-settings"></i> 
                  SUBMIT
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
    $('#hopitalized-date').flatpickr({
      dateFormat: "F d,Y"
    });

    $('#healthcare-provider').on('change', function(){
      const hp_id = $(this).val();
      const token = `<?php echo $this->security->get_csrf_hash(); ?>`;

      if(hp_id != ''){
        $.ajax({
          url: `${baseUrl}company-doctor/get-services/${hp_id}`,
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
    
      $('#overrideLOARequestForm').submit(function(event) {
        event.preventDefault();
        let data = new FormData($(this)[0]);

        $.ajax({
          type: "post",
          url: `${baseUrl}company-doctor/override/loa/submit`,
          data: data,
          dataType: "json",
          processData: false,
          contentType: false,
          success: function(response) {
              const {
                token,
                status,
                message,
                hospitalized_date_error,
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

                  if (hospitalized_date_error !== '') {
                    $('#hospitalized-error').html(hospitalized_date_error);
                    $('#hopitalized-date').addClass('is-invalid');
                  } else {
                    $('#hospitalized-error').html('');
                    $('#hopitalized-date').removeClass('is-invalid');
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
                    window.location.href = `${baseUrl}company-doctor/loa/requests-list`;
                  }, 3200);
                  break;
              }
            },
        });
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
    const hospitalized = document.querySelector('#hospitalized-div');
    const req_physician = document.querySelector('#req-physician-div');
    const physician = document.querySelector('#physician-div');
    const hosp_date = document.querySelector('#hopitalized-date');
    const med_services = document.querySelector('#med-services');

  if (loaType === "Diagnostic Test") {
      medServices.className = "col-lg-7 col-sm-12 mb-2 d-block";
      fileAttachment.className = "form-group row d-block";
      hospitalized.className = "d-none";
      physician.className = "d-block col-lg-6 col-sm-12 mb-2";
      req_physician.className = "d-block col-lg-6 col-sm-12 mb-2";
      hosp_date.value = '';

    } else if (loaType === "Emergency") {
      hospitalized.className = "col-lg-3 col-sm-12 mb-2 d-block";
      medServices.className = "d-none";
      fileAttachment.className = "d-none";
      physician.className = "d-none";
      req_physician.className = "d-none";
      med_services.selectedIndex = -1;

      // If using the 'chosen-select' library, trigger the update event after clearing the selection
      if (typeof $ !== 'undefined' && typeof $.fn.chosen !== 'undefined') {
        $(med_services).trigger('chosen:updated');
      }
    }
  }

  function searchHmoMember() {
    const token = "<?= $this->security->get_csrf_hash() ?>";
    const search_input = document.querySelector('#input-search-member');
    const result_div = document.querySelector('#search-results');
    var search = search_input.value;
    if (search !== '') {
      load_member_data(token, search);
    } else {
      result_div.innerHTML = '';
    }
  } 

  function load_member_data(token, search) {
    $.ajax({
      url: `${baseUrl}company-doctor/member/search`,
      method: "POST",
      data: {
        token: token,
        search: search
      },
      success: function(data) {
        $('#search-results').removeClass('d-none');
        $('#search-results').html(data);
      }
    });
  }

  function getMemberValues(member_id) {
    $.ajax({
      url: `${baseUrl}company-doctor/loa/member/search/${member_id}`,
      method: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const {
          token,member_id,emp_id,first_name,middle_name,last_name,suffix,gender,date_of_birth,age,philhealth_no,blood_type,home_address,city_address,contact_no,email,contact_person,contact_person_addr,contact_person_no,health_card_no,requesting_company,mbl
        } = res;

        $('#search-results').addClass('d-none');
        $('#input-search-member').val('');
        $('#member-id').val(member_id);
        $('#full-name').val(`${first_name} ${middle_name} ${last_name} ${suffix}`);
        $('#date-of-birth').val(date_of_birth);
        $('#age').val(age);
        $('#gender').val(gender);
        $('#philhealth-no').val(philhealth_no);
        $('#blood-type').val(blood_type);
        $('#patient-address').val(home_address);
        $('#city-address').val(city_address);
        $('#contact-no').val(contact_no);
        $('#email').val(email);
        $('#contact-person').val(contact_person);
        $('#contact-person-no').val(contact_person_no);
        $('#contact-person-addr').val(contact_person_addr);
        $('#remaining_mbl').val(mbl);
        $('#health-card-no').val(health_card_no);
        $('#requesting-company').val(requesting_company);
        $('#emp-id').val(emp_id);

      }
    });
  }

</script>