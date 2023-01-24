<main id="main" class="main">
  <div class="pagetitle">
    <h1 class="text-secondary">Letter of Authorization</h1>
  </div>

  <section class="section dashboard">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">

            <form method="post" action="<?= base_url(); ?>super-admin/loa/request-loa/submit" class="mt-2" id="loaRequestForm" enctype="multipart/form-data">
              <!-- Start of Hidden Inputs -->
              <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
              <input type="hidden" name="requested-by" value="<?= $this->session->userdata('emp_id') ?>">
              <input type="hidden" name="emp-id" id="emp-id">
              <div class="form-group row">
                <div class="col-lg-5 mt-3 mb-3" id="search-member-div">
                  <input type="text" class="form-control" name="search-member" id="input-search-member" onkeyup="searchHmoMember()" placeholder="Search Member Here..." />
                  <div id="member-search-div">
                    <div id="search-results" class="border-top-0"></div>
                  </div>
                </div>
              </div>
              <input type="hidden" name="first-name" id="first-name">
              <input type="hidden" name="middle-name" id="middle-name">
              <input type="hidden" name="last-name" id="last-name">
              <input type="hidden" name="suffix" id="suffix">
              <!-- End of Hidden Inputs -->
              <h2 class="divider-heading mt-2">PATIENT DETAILS</h2>
              <div class="div-divider"><span></span></div>
              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Full Name</label>
                  <input type="text" class="form-control has-data" name="full-name" id="full-name" readonly>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Date of Birth</label>
                  <input type="date" class="form-control has-data" name="date-of-birth" id="date-of-birth" readonly>
                </div>

                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Age</label>
                  <input type="text" class="form-control has-data" name="age" id="age" readonly>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-2 mb-2">
                  <label class="colored-label">Gender</label>
                  <input type="text" class="form-control has-data" name="gender" id="gender" readonly>
                </div>
                <div class="col-sm-4 mb-2">
                  <label class="colored-label">PhilHealth Number</label>
                  <input type="text" class="form-control has-data" name="philhealth-no" id="philhealth-no" readonly>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Blood Type</label>
                  <input type="text" class="form-control has-data" name="blood-type" id="blood-type" readonly>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Home Address</label>
                  <input type="text" class="form-control has-data" name="home-address" id="home-address" readonly>
                  <em id="patient-address-error" class="text-danger"></em>
                </div>
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">City Address</label>
                  <input type="text" class="form-control has-data" name="city-address" id="city-address" readonly>
                </div>

              </div>

              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Contact Number</label>
                  <input type="text" class="form-control has-data" name="contact-no" id="contact-no" readonly>
                </div>
                <div class="col-sm-6">
                  <label class="colored-label">Email</label>
                  <input type="email" class="form-control has-data" name="email" id="email" readonly>
                </div>
              </div>

              <h2 class="divider-heading mt-4">CONTACT PERSON DETAILS</h2>
              <div class="div-divider"><span></span></div>
              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Contact Person Name</label>
                  <input type="text" class="form-control has-data" name="contact-person" id="contact-person" readonly>
                </div>
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Contact Number</label>
                  <input type="text" class="form-control has-data" name="contact-person-no" id="contact-person-no" readonly>
                </div>

              </div>

              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Address</label>
                  <input type="text" class="form-control has-data" name="contact-person-addr" id="contact-person-addr" readonly>
                </div>
              </div>
              <h2 class="divider-heading mt-4 mb-0">LOA REQUEST DETAILS</h2>
              <div class="div-divider"><span></span></div>
              <div class="form-group row">
                <div class="col-lg-7 col-sm-12 col-lg-offset-3 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> HealthCare Provider</label>
                  <select class="form-select" name="healthcare-provider" id="healthcare-provider">
                    <option value="" selected>Select HealthCare Provider</option>
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
                <div class="col-lg-5 col-sm-12 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Type of LOA Request</label>
                  <select class="form-select" name="loa-request-type" id="loa-request-type" onchange="showMedServices()">
                    <option value="" selected>Select LOA Request Type</option>
                    <option value="Consultation">Consultation</option>
                    <option value="Diagnostic Test">Diagnostic Test</option>
                    <!-- <option value="Special Diagnostic Procedures">Special Diagnostic Procedures</option> -->
                  </select>
                  <em id="loa-request-type-error" class="text-danger"></em>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-lg-7s col-sm-12 mb-2 d-none" id="med-services-div">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Select Medical Service/s</label><br>
                  <div id="med-services-wrapper">
                    <select class="form-select" multiple="multiple" id="med-services" name="med-services[]">
                      <?php
                      if (!empty($costtypes)) :
                        foreach ($costtypes as $ct) :
                      ?>
                          <option value="<?= $ct['ctype_id']; ?>"><?= $ct['cost_type']; ?></option>
                      <?php
                        endforeach;
                      endif;
                      ?>
                    </select>
                  </div>
                  <em id="med-services-error" class="text-danger"></em>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Health Card Number</label>
                  <input type="text" class="form-control has-data" name="health-card-no" id="health-card-no" readonly>
                </div>
                <div class="col-sm-5 mb-2">
                  <label class="colored-label">Requesting Company</label>
                  <input type="text" class="form-control has-data" name="requesting-company" id="requesting-company" readonly>
                </div>
                <div class="col-lg-4 col-sm-12 col-lg-offset-4 mb-2">
                  <?php
                  $month = date('m');
                  $day = date('d');
                  $year = date('Y');
                  $today = $year . '-' . $month . '-' . $day;
                  ?>
                  <label class="colored-label">Request Date of Availment</label>
                  <input type="text" class="form-control has-data" name="request-date" value="<?= $today; ?>" readonly>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-12 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Chief Complaint/Diagnosis</label>
                  <textarea class="form-control" name="chief-complaint" id="chief-complaint" cols="30" rows="6"></textarea>
                  <em id="chief-complaint-error" class="text-danger"></em>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-lg-6 col-sm-12 col-lg-offset-3 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Requesting Physician</label>
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
                  <h2 class="divider-heading mt-4 mb-0">FILE ATTACHMENT</h2>
                  <div class="div-divider"><span></span></div>
                </div>

                <div class="form-group">
                  <div class="col-sm-12 mb-2">
                    <p id="file-type-info">
                      Allowed file types: <strong class="text-primary">jpg, jpeg, png</strong>.
                    </p>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-12 mb-2">
                    <label class="colored-label mb-1">RX/Request from Accredited Doctor</label>
                    <div id="rx-file-wrapper">
                      <input type="file" class="dropify" name="rx-file" id="rx-file" data-height="300" data-max-file-size="3M" accept=".jpg, .jpeg, .png">
                    </div>
                    <em id="rx-file-error" class="text-danger"></em>
                  </div>
                </div>
              </section>

              <div class="row mt-3">
                <div class="col-sm-12 mb-2 d-flex justify-content-start">
                  <button type="submit" class="btn btn-primary">
                    SUBMIT
                  </button>
                  &nbsp;&nbsp;
                  <button type="reset" class="btn btn-danger">
                    RESET
                  </button>
                </div>
              </div>
            </form>
            <!-- End of Form -->
          </div>
          <!-- End of Card Body -->
        </div>
        <!-- End of Card -->
      </div>
    </div>
  </section>

</main>
<script>
  const baseUrl = "<?php echo base_url(); ?>";
  $(document).ready(function() {
    $('#loaRequestForm').submit(function(event) {
      event.preventDefault();
      let $data = new FormData($(this)[0]);
      $.ajax({
        type: "post",
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
                window.location.href = `${baseUrl}super-admin/loa/requests-list`;
              }, 3200);
              break;
          }
        },
      })
    });
  });


  function showMedServices() {
    const loaType = document.querySelector('#loa-request-type').value;
    const medServices = document.querySelector('#med-services-div');
    const fileAttachment = document.querySelector('#div-attachment');
    if (loaType === "Consultation" || loaType === "") {
      medServices.className = "d-none";
      fileAttachment.className = "d-none";
    } else {
      medServices.className = "col-lg-7 col-sm-12 mb-2 d-block";
      fileAttachment.className = "form-group row d-block";
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
      url: `${baseUrl}super-admin/member/search`,
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

  function getMemberValues(emp_id) {
    $.ajax({
      url: `${baseUrl}super-admin/loa/member/search/${emp_id}`,
      method: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const {
          token,
          emp_id,
          position_level,
          first_name,
          middle_name,
          last_name,
          suffix,
          date_of_birth,
          age,
          gender,
          philhealth_no,
          blood_type,
          home_address,
          city_address,
          contact_no,
          email,
          contact_person,
          contact_person_addr,
          contact_person_no,
          health_card_no,
          requesting_company,
        } = res;
        $('#search-results').addClass('d-none');
        $('#input-search-member').val('');
        $('#emp-id').val(emp_id);
        $('#first-name').val(first_name);
        $('#middle-name').val(middle_name);
        $('#last-name').val(last_name);
        $('#suffix').val(suffix);
        $('#full-name').val(`${first_name} ${middle_name} ${last_name} ${suffix}`);
        $('#date-of-birth').val(date_of_birth);
        $('#age').val(age);
        $('#gender').val(gender);
        $('#philhealth-no').val(philhealth_no);
        $('#blood-type').val(blood_type);
        $('#home-address').val(home_address);
        $('#city-address').val(city_address);
        $('#contact-no').val(contact_no);
        $('#email').val(email);
        $('#contact-person').val(contact_person);
        $('#contact-person-no').val(contact_person_no);
        $('#contact-person-addr').val(contact_person_addr);
        $('#health-card-no').val(health_card_no);
        $('#requesting-company').val(requesting_company);
      }
    });
  }
</script>