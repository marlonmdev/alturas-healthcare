<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <a href="#" onclick="window.history.back()" class="btn btn-danger"><i class="mdi mdi-arrow-left-bold"></i> BACK</a>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">Admission Form</li>
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

            <form method="post" action="<?= base_url() ?>healthcare-coordinator/noa/request-noa/submit" enctype="multipart/form-data" class="mt-2" id="admissionform">
              <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
              <input type="hidden" name="emp-id" id="emp-id">
              <input type="hidden" name="member-id" id="member-id">
              <input type="hidden" name="loa-request-type" value="Emergency">

              <span class="text-info fs-3 fw-bold ls-2"><i class="mdi mdi-account-card-details"></i> PATIENT DETAILS</span><br><br>
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
                  <label class="colored-label">Age:</label>
                  <input type="text" class="form-control" name="age" id="age" disabled>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Gender</label>
                  <input type="text" class="form-control" id="gender" name="gender" disabled>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">PhilHealth Number</label>
                  <input type="text" class="form-control" id="philhealth_no" name="philhealth_no" disabled>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Blood Type</label>
                  <input type="text" class="form-control" id="blood_type" name="blood_type" disabled>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Home Address</label>
                  <input type="text" class="form-control" id="home_address" name="home_address" disabled>
                </div>
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">City Address</label>
                  <input type="text" class="form-control" id="city_address" name="city_address" disabled>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Contact Number</label>
                  <input type="text" class="form-control" id="contact_no" name="contact_no" disabled>
                </div>
                <div class="col-sm-6">
                  <label class="colored-label">Email</label>
                  <input type="email" class="form-control" id="email" name="email" disabled>
                </div>
              </div>

              <span class="text-info mt-4 fs-3 fw-bold ls-2"><i class="mdi mdi-contact-mail"></i> CONTACT PERSON DETAILS</span><br><br>
              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Contact Person Name</label>
                  <input type="text" class="form-control" id="contact_person" name="contact_person" disabled>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Contact Number</label>
                  <input type="text" class="form-control" id="contact_person_no" name="contact_person_no" disabled>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Address</label>
                  <input type="text" class="form-control" id="contact_person_addr" name="contact_person_addr" disabled>
                </div>
              </div>

              <span class="text-info fs-3 fw-bold ls-2"><i class="mdi mdi-file-document-box"></i> REQUEST DETAILS</span><br><br>
              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Company</label>
                  <input type="text" class="form-control" id="requesting_company" name="requesting_company" disabled>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Healthcard Number</label>
                  <input type="text" class="form-control" id="health_card_no" name="health_card_no" disabled>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Remaining MBL</label>
                  <input type="text" class="form-control" id="remaining_mbl" name="remaining_mbl" disabled>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-lg-6 col-sm-12 col-lg-offset-3 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Name of Hospital</label>
                  <select class="form-select" name="hospital-name" id="hospital-name">
                    <option value="" selected>Select Hospital</option>
                    <?php
                    if (!empty($hospitals)) :
                      foreach ($hospitals as $hospital) :
                    ?>
                        <option value="<?= $hospital['hp_id']; ?>"><?= $hospital['hp_name']; ?></option>
                    <?php
                      endforeach;
                    endif;
                    ?>
                  </select>
                  <em id="hospital-name-error" class="text-danger"></em>
                </div>
                <div class="col-lg-6 col-sm-12 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Date Request</label>
                  <input type="text" class="form-control" name="admission-date" id="admission-date" placeholder="Select Date" style="background-color:#ffff">
                  <em id="admission-date-error" class="text-danger"></em>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-12 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Chief Complaint/Diagnosis</label>
                  <textarea class="form-control" name="chief-complaint" id="chief-complaint" cols="30" rows="6"></textarea>
                  <em id="chief-complaint-error" class="text-danger"></em>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-sm-12 mb-2 d-flex justify-content-center">
                  <button type="submit" id = "submit" class="btn btn-info me-2"><i class="mdi mdi-content-save"></i> SUBMIT</button>
                  <button type="reset" class="btn btn-danger"><i class="mdi mdi-broom"></i> CLEAR</button>
                </div>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>






<script type="text/javascript">
  const baseUrl = '<?= base_url() ?>';
  const pathname = window.location.pathname; // Get the pathname
  const segments = pathname.split('/'); // Split the pathname into segments
  const lastSegment = segments[segments.length - 1];

  $(document).ready(function() {
    getMemberValues(lastSegment);

    $('#admission-date').flatpickr({
      dateFormat: "Y-m-d"
    });

    $('#admissionform').submit(function(event) {
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
            hospital_name_error,
            chief_complaint_error,
            admission_date_error
          } = response;

          if (status === 'error') {
            // is-invalid class is a built in classname for errors in bootstrap
            if (hospital_name_error !== '') {
              $('#hospital-name-error').html(hospital_name_error);
              $('#hospital-name').addClass('is-invalid');
            } else {
              $('#hospital-name-error').html('');
              $('#hospital-name').removeClass('is-invalid');
            }

            if (admission_date_error !== '') {
              $('#admission-date-error').html(admission_date_error);
              $('#admission-date').addClass('is-invalid');
            } else {
              $('#admission-date-error').html('');
              $('#admission-date').removeClass('is-invalid');
            }

            if (chief_complaint_error !== '') {
              $('#chief-complaint-error').html(chief_complaint_error);
              $('#chief-complaint').addClass('is-invalid');
            } else {
              $('#chief-complaint-error').html('');
              $('#chief-complaint').removeClass('is-invalid');
              $('#chief-complaint').addClass('is-valid');
            }

          } else if (status === 'save-error') {
            swal({
              title: 'Failed',
              text: message,
              timer: 3000,
              showConfirmButton: false,
              type: 'error'
            });
          } else if (status === 'success') {
            swal({
              title: 'Success',
              text: message,
              timer: 3000,
              showConfirmButton: false,
              type: 'success'
            });
            setTimeout(function() {
              window.location.href = `${baseUrl}healthcare-coordinator/noa/requests-list`;
            }, 3200);
          }
        },
      });
    });
  });

  function getMemberValues(member_id) {
    $.ajax({
      url: `${baseUrl}healthcare-coordinator/noa/member/search1/${member_id}`,
      method: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const {
          token,member_id,first_name,middle_name,last_name,suffix,date_of_birth,age,gender,philhealth_no,blood_type,home_address,city_address,contact_no,email,contact_person,contact_person_no,contact_person_addr,health_card_no,requesting_company,mbl,emp_id } = res;

        $('#search-results').addClass('d-none');
        $('#input-search-member').val('');
        $('#member-id').val(member_id);
        $('#emp-id').val(emp_id);
        $('#full-name').val(`${first_name} ${middle_name} ${last_name} ${suffix}`);
        $('#date-of-birth').val(date_of_birth);
        $('#age').val(age);
        $('#gender').val(gender);
        $('#philhealth_no').val(philhealth_no);
        $('#blood_type').val(blood_type);
        $('#home_address').val(home_address);
        $('#city_address').val(city_address);
        $('#contact_no').val(contact_no);
        $('#email').val(email);
        $('#contact_person').val(contact_person);
        $('#contact_person_no').val(contact_person_no);
        $('#contact_person_addr').val(contact_person_addr);
        $('#health_card_no').val(health_card_no);
        $('#requesting_company').val(requesting_company);
        const formattedMbl = number_format(parseFloat(mbl), 2, '.', ',');
        $('#remaining_mbl').val(`₱ ${formattedMbl}`);
      }
    });
  }

  function number_format(number, decimals, decimalSeparator, thousandsSeparator) {
    decimals = typeof decimals !== 'undefined' ? decimals : 2;
    decimalSeparator = typeof decimalSeparator !== 'undefined' ? decimalSeparator : '.';
    thousandsSeparator = typeof thousandsSeparator !== 'undefined' ? thousandsSeparator : ',';
    var parts = number.toFixed(decimals).toString().split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandsSeparator);
    return parts.join(decimalSeparator);
  }
</script>



























<!-- <div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">NOTICE OF ADMISSION</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">
                NOA Requisition
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

            <form method="post" action="<?= base_url() ?>healthcare-coordinator/noa/request-noa/submit" enctype="multipart/form-data" class="mt-2" id="noaRequestForm">

              <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
              <input type="hidden" name="member-id" id="member-id">

              <div class="form-group row">
                <div class="col-lg-5 mt-3 mb-3" id="search-member-div">
                  <input type="text" class="form-control" name="search-member" id="input-search-member" onkeyup="searchHmoMember()" placeholder="Search Member Here..." />
                  <div id="member-search-div">
                    <div id="search-results" class="border-top-0"></div>
                  </div>
                </div>
                <input type="number" id="mbl" hidden>
              </div>
              <div class="form-group row">
                <div class="col-sm-7 mb-2">
                  <label class="colored-label">Full Name</label>
                  <input type="text" class="form-control" name="full-name" id="full-name" disabled>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Date of Birth</label>
                  <input type="date" class="form-control" name="date-of-birth" id="date-of-birth" disabled>
                </div>
                <div class="col-sm-2 mb-2">
                  <label class="colored-label">Age:</label>
                  <input type="text" class="form-control" name="age" id="age" disabled>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-lg-8 col-sm-12 col-lg-offset-3 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Name of Hospital</label>
                  <select class="form-select" name="hospital-name" id="hospital-name">
                    <option value="" selected>Select Hospital</option>
                    <?php
                    if (!empty($hospitals)) :
                      foreach ($hospitals as $hospital) :
                    ?>
                        <option value="<?= $hospital['hp_id']; ?>"><?= $hospital['hp_name']; ?></option>
                    <?php
                      endforeach;
                    endif;
                    ?>
                  </select>
                  <em id="hospital-name-error" class="text-danger"></em>
                </div>
                <div class="col-lg-4 col-sm-12 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Admission Date</label>
                  <input type="text" class="form-control" name="admission-date" id="admission-date" placeholder="Select Date" style="background-color:#ffff">
                  <em id="admission-date-error" class="text-danger"></em>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-12 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Chief Complaint/Diagnosis</label>
                  <textarea class="form-control" name="chief-complaint" id="chief-complaint" cols="30" rows="6"></textarea>
                  <em id="chief-complaint-error" class="text-danger"></em>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-sm-12 mb-2 d-flex justify-content-start">
                  <button type="submit" class="btn btn-primary me-2"><i class="mdi mdi-content-save"></i> SUBMIT</button>
                  <button type="reset" class="btn btn-danger"><i class="mdi mdi-close-box"></i> CLEAR</button>
                </div>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>






<script type="text/javascript">
  const baseUrl = '<?= base_url() ?>';
  const pathname = window.location.pathname; // Get the pathname
  const segments = pathname.split('/'); // Split the pathname into segments
  const lastSegment = segments[segments.length - 1];

  $(document).ready(function() {
    getMemberValues(lastSegment);
    $('#admission-date').flatpickr({
      dateFormat: "Y-m-d"
    });

    $('#noaRequestForm').submit(function(event) {
      event.preventDefault();
      let $data = new FormData($(this)[0]);
      // if($('#mbl').val()<=0){
      //   $.alert({
      //     title: "<h3 style='font-weight: bold; color: #dc3545; margin-top: 0;'>Unable to Request</h3>",
      //     content: "<div style='font-size: 16px; color: #333;'>We apologize for the inconvenience, but it looks like His/Her MBL balance is currently empty. Please ensure that you have enough MBL in your account before attempting to make a request. Thank you for your understanding.</div>",

      //     type: "red",
      //     buttons: {
      //         ok: {
      //             text: "OK",
      //             btnClass: "btn-danger",
      //             // action: function(){
      //             //   window.history.back()
      //             // },
      //         },
      //     },
      // });
      // }else{
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
            hospital_name_error,
            chief_complaint_error,
            admission_date_error
          } = response;

          if (status === 'error') {
            // is-invalid class is a built in classname for errors in bootstrap
            if (hospital_name_error !== '') {
              $('#hospital-name-error').html(hospital_name_error);
              $('#hospital-name').addClass('is-invalid');
            } else {
              $('#hospital-name-error').html('');
              $('#hospital-name').removeClass('is-invalid');
            }

            if (admission_date_error !== '') {
              $('#admission-date-error').html(admission_date_error);
              $('#admission-date').addClass('is-invalid');
            } else {
              $('#admission-date-error').html('');
              $('#admission-date').removeClass('is-invalid');
            }

            if (chief_complaint_error !== '') {
              $('#chief-complaint-error').html(chief_complaint_error);
              $('#chief-complaint').addClass('is-invalid');
            } else {
              $('#chief-complaint-error').html('');
              $('#chief-complaint').removeClass('is-invalid');
              $('#chief-complaint').addClass('is-valid');
            }

          } else if (status === 'save-error') {
            swal({
              title: 'Failed',
              text: message,
              timer: 3000,
              showConfirmButton: false,
              type: 'error'
            });
          } else if (status === 'success') {
            swal({
              title: 'Success',
              text: message,
              timer: 3000,
              showConfirmButton: false,
              type: 'success'
            });
            setTimeout(function() {
              window.location.href = `${baseUrl}healthcare-coordinator/noa/requests-list`;
            }, 3200);
          }
        },
      });
      // End of AJAX Request
      //}
    });
  });

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
      url: `${baseUrl}healthcare-coordinator/member/search`,
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
      url: `${baseUrl}healthcare-coordinator/loa/member/search/${member_id}`,
      method: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const {
          token,
          member_id,
          first_name,
          middle_name,
          last_name,
          suffix,
          date_of_birth,
          age,
          mbl,
        } = res;
        $('#search-results').addClass('d-none');
        $('#input-search-member').val('');
        $('#member-id').val(member_id);
        $('#full-name').val(`${first_name} ${middle_name} ${last_name} ${suffix}`);
        $('#date-of-birth').val(date_of_birth);
        $('#age').val(age);
        $('#mbl').val(mbl);
      }
    });
  }
</script> -->