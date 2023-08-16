<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <a href="<?php echo base_url(); ?>healthcare-coordinator/noa/requests-list" class="btn btn-danger"><i class="mdi mdi-arrow-left-bold"></i> Back</a>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">Edit Admission Form</li>
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
          <div class="card-body mt-3">
            <div class="col-xs-12 d-flex justify-content-center align-items-center">
              <img src="<?= base_url(); ?>assets/images/logo2.png" alt="Alturas Healthcare Logo" height="70" width="300">
            </div>
            <div class="col-12 pt-2">
              <div class="text-center mb-4 mt-0"><h4 class="page-title fs-3" style="color:black;font-family:Times Roman;">ADMISSION FORM</h4></div>
            </div><hr>
            <form method="post" action="<?= base_url() ?>healthcare-coordinator/noa/requested-noa/update/<?= $this->myhash->hasher($row['noa_id'], 'encrypt') ?>" class="mt-2" id="noaRequestForm">
              <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">

              <span class="text-info fs-3 fw-bold ls-2"><i class="mdi mdi-account-card-details"></i> PATIENT DETAILS</span><br><br>
              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Full Name</label>
                  <input type="text" class="form-control" value="<?= $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] . ' ' . $row['suffix'] ?>" disabled>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Date of Birth</label>
                  <input type="date" class="form-control" value="<?= $row['date_of_birth'] ?>" disabled>
                </div>
                <div class="col-sm-3 mb-2">
                  <?php
                    $birthDate = date("d-m-Y", strtotime($row['date_of_birth']));
                    $currentDate = date("d-m-Y");
                    $diff = date_diff(date_create($birthDate), date_create($currentDate));
                    $age = $diff->format("%y");
                  ?>
                  <label class="colored-label">Age:</label>
                  <input type="text" class="form-control" value="<?= $age ?>" disabled>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Gender</label>
                  <input type="text" class="form-control" value="<?= $row['gender'] ?>" disabled>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">PhilHealth Number</label>
                  <input type="text" class="form-control" value="<?= $row['philhealth_no'] ?>" disabled>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Blood Type</label>
                  <input type="text" class="form-control" value="<?= $row['blood_type'] ?>" disabled>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Home Address</label>
                  <input type="text" class="form-control" value="<?= $row['home_address'] ?>" disabled>
                </div>
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">City Address</label>
                  <input type="text" class="form-control" value="<?= $row['city_address'] ?>" disabled>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Contact Number</label>
                  <input type="text" class="form-control" value="<?= $row['contact_no'] ?>" disabled>
                </div>
                <div class="col-sm-6">
                  <label class="colored-label">Email</label>
                  <input type="email" class="form-control" value="<?= $row['email'] ?>" disabled>
                </div>
              </div>

              <span class="text-info mt-4 fs-3 fw-bold ls-2"><i class="mdi mdi-contact-mail"></i> CONTACT PERSON DETAILS</span><br><br>
              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Contact Person Name</label>
                  <input type="text" class="form-control" value="<?= $row['contact_person'] ?>" disabled>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Contact Number</label>
                  <input type="text" class="form-control" value="<?= $row['contact_person_no'] ?>" disabled>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Address</label>
                  <input type="text" class="form-control" value="<?= $row['contact_person_addr'] ?>" disabled>
                </div>
              </div>

              <span class="text-info fs-3 fw-bold ls-2"><i class="mdi mdi-file-document-box"></i> REQUEST DETAILS</span><br><br>
              <div class="form-group row">
                <div class="col-sm-4 mb-2">
                  <label class="colored-label"><i class="mdi mdi-asterisk text-danger"></i> Hospital Provider Category</label>
                  <select class="form-select" name="healthcare-provider-category" id="healthcare-provider-category" >
                    <option value="" selected disabled>Select Healthcare Provider Category</option>
                    <option value="1">Affiliated</option>
                    <option value="2">Not Affiliated</option>
                  </select>
                  <em id="healthcare-provider-category-error" class="text-danger"></em>
                </div>

                <div class="col-lg-4 col-sm-12 col-lg-offset-3 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i>  Healthcare Provider</label>
                  <select class="form-select" name="hospital-name" id="hospital-name">
                    <option value="" selected disabled>Select Healthcare Provider</option>
                  </select>
                  <em id="hospital-name-error" class="text-danger"></em>
                </div>

                <div class="col-lg-4 col-sm-12 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Admission Date</label>
                  <input type="text" class="form-control" name="admission-date" id="admission-date" value="<?= $row['admission_date'] ?>" placeholder="Select Date" style="background-color:#ffff">
                  <em id="admission-date-error" class="text-danger"></em>
                </div>
              </div>

              <div class="form-group row" id="med-hr-wrapper" hidden>
                  <div class="col-sm-8 mb-0 pe-2" id="med-services-wrapper">
                    <label class="colored-label"><i class="mdi mdi-asterisk text-danger"></i> Input Medical Service/s <small class="text-danger"> *Note: Press Tab or Enter to Add More Medical Service</small></label>
                    <input class="form-control" id="noa-med-services" name="noa-med-services" placeholder="Type and press Enter|Tab" >
                    </input>
                    <em id="noa-med-services-error" class="text-danger"></em>
                  </div>
                  <div class="col-lg-4 col-sm-12 mb-2" id="hospital-bill-wrapper" >
                  <label class="colored-label"><i class="bx bx-health icon-red"></i>Total Bill</label>
                  <input type="text" class="form-control" name="hospital-bill" id="hospital-bill" placeholder="Enter Hospital Bill" style="background-color:#ffff" autocomplete="off" >
                  <em id="hospital-bill-error" class="text-danger"></em>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-12 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Chief Complaint/Diagnosis</label>
                  <textarea class="form-control" name="chief-complaint" id="chief-complaint" cols="30" rows="6"><?= $row['chief_complaint'] ?></textarea>
                  <em id="chief-complaint-error" class="text-danger"></em>
                </div>
              </div>

              <section class="row" id="receipt-wrapper"  hidden>
                <div class="form-group">
                 <span class="text-info fs-3 fw-bold ls-2"><i class="mdi mdi-attachment"></i> FILE ATTACHMENT</span>
                </div>

                <div class="form-group" >
                  <div class="col-sm-12 mb-2">
                    <p id="file-type-info">
                      Allowed file types: <strong class="text-primary">jpg, jpeg, png</strong>.
                    </p>
                  </div>
                </div>
                <div class="form-group">
                <div class="col-sm-12 mb-4" >
                  <i class="mdi mdi-asterisk text-danger" id="mdi"></i><label class="colored-label mb-1" id="rx-title"> Hospital Receipt</label>
                    <div id="hospital-receipt-wrapper">
                      <input type="file" class="dropify" name="hospital-receipt" id="hospital-receipt" data-height="300" data-max-file-size="5M" accept=".jpg, .jpeg, .png">
                      <input type="hidden" name="file-attachment-receipt" value="<?= $row['hospital_receipt'] ?>">
                    </div>
                    <em id="hospital-receipt-error" class="text-danger"></em>
                  </div>
                </div>
              </section>
              <br>

              <div class="row">
                <div class="col-sm-12 mb-2 d-flex justify-content-center">
                  <button type="submit" class="btn btn-info me-2"><i class="mdi mdi-autorenew"></i> UPDATE</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>







<!-- <script type="text/javascript">
  const baseUrl = `<?= base_url() ?>`;
  const redirectPage = `${baseUrl}healthcare-coordinator/noa/requests-list`;

  $(document).ready(function() {

    $('#admission-date').flatpickr({
      dateFormat: "Y-m-d"
    });

    $('#noaRequestForm').submit(function(event) {
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
              window.location.href = redirectPage;
            }, 3200);
          }
        },
      });
      // End of AJAX Request
    });
  });
</script> -->

<script type="text/javascript">
  const baseUrl = `<?= base_url() ?>`;
  const redirectPage = `${baseUrl}healthcare-coordinator/noa/requests-list`;

  const ahcproviders_names = <?= json_encode($ahcproviders)?>;
  const hospital_names = <?= json_encode($hcproviders)?>;
  const old_services = <?= json_encode($old_services)?>;
  const row = <?= json_encode($row)?>;
  const is_manual = <?= json_encode($is_accredited)?>;
  const tag_input =document.getElementById('noa-med-services');
  let is_accredited = false;
  let is_hr_has_data = true;

  $(document).ready(function() {
    const tagify = new Tagify(tag_input);
    tagify.addTags(old_services);
    number_validator();

    if(row.hospital_receipt){
      $(".dropify").dropify({
        messages: {
          default: "Drop files here or click to browse",
          replace: "Drag and drop an image or file here or click to replace",
        },
        defaultFile: baseUrl+"/uploads/hospital_receipt/"+row.hospital_receipt, 
      });
    }else{
      $(".dropify").dropify({
        messages: {
          default: "Drop files here or click to browse",
          replace: "Drag and drop an image or file here or click to replace",
        },
      });
    }
      

      $('.dropify').on('dropify.afterClear', function (event, element) {
        is_hr_has_data = false;
      });

      $('.dropify').on('change', function (event) {
        if(event.target.files[0]){
          is_hr_has_data = true;
          console.log('true');
        }
      });

      $('#healthcare-provider-category').on('change', function(event){
          enableProvider();
      }); 

      if(is_manual){
      $('#healthcare-provider-category').val(2);
      enableProvider();
      $('#hospital-name').val(row.hospital_id);
      $('#med-hr-wrapper').prop('hidden',false);
      $('#hospital-bill').val(row.hospital_bill);
      // get_services(services,row.med_services);
    }else{
      $('#healthcare-provider-category').val(1);
      enableProvider();
      $('#hospital-name').val(row.hospital_id);
      $('#med-hr-wrapper').prop('hidden',true);
      // get_services(services,row.med_services);
    }

    $('#admission-date').flatpickr({
      dateFormat: "Y-m-d"
    });

    $('#noaRequestForm').submit(function(event) {
      event.preventDefault();
      let $data = new FormData($(this)[0]);
      $data.append('is_accredited',is_accredited);
      $data.append('is_hr_has_data',is_hr_has_data);
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
            admission_date_error,
            hospital_receipt_error,
            med_services_error,
            hospital_bill_error,
            healthcare_provider_category_error
          } = response;
          // const base_url = window.location.origin;

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
              $('#chief-complaint').addClass('div-has-error');
            } else {
              $('#chief-complaint-error').html('');
              $('#chief-complaint').removeClass('div-has-error');
            }

            if (med_services_error !== '') {
                $('#edit-med-services-error').html(med_services_error);
                $('#edit-med-services-wrapper').addClass('div-has-error');
              } else {
                $('#edit-med-services-error').html('');
                $('#edit-med-services-wrapper').removeClass('div-has-error');
              }

              if (hospital_receipt_error !== '') {
                $('#hospital-receipt-error').html(hospital_receipt_error);
                $('#receipt-wrapper').addClass('div-has-error');
              } else {
                $('#hospital-receipt-error').html('');
                $('#receipt-wrapper').removeClass('div-has-error');
              }

              if (hospital_bill_error !== '') {
                $('#hospital-bill-error').html(hospital_bill_error);
                $('#hospital-bill-wrapper').addClass('div-has-error');
              } else {
                $('#hospital-bill-error').html('');
                $('#hospital-bill-wrapper').removeClass('div-has-error');
              }

              if (healthcare_provider_category_error !== '') {
                $('#healthcare-provider-category-error').html(healthcare_provider_category_error);
                $('#healthcare-provider-category').addClass('is-invalid');
              } else {
                $('#healthcare-provider-category-error').html('');
                $('#healthcare-provider-category').removeClass('is-invalid');
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

            $('.dropify-clear').click();
            setTimeout(function() {
              window.location.href = redirectPage;
            }, 3200);
          }
        },
      });
      // End of AJAX Request

    });
  });

const number_validator = () => {
  $('#hospital-bill').on('keydown',function(event){
    let value = $('#hospital-bill').val();
    let length  = $('#hospital-bill').val().length;
    const key = event.key;
    console.log('key',key);
    console.log('length',length);
    if(length+1 <=1 && (key === '0'|| key === '.' || key ==='')){
      event.preventDefault();
    }
    if(/^[a-zA-Z]$/.test(key)) {
      event.preventDefault(); 
    }
    if(/^[!@#$%^&*()\-_=+[\]{};':"\\|,<>/?`~]$/.test(key)) {
      event.preventDefault(); 
    }
    if(value.match(/\./) && /\./.test(key)) {
      event.preventDefault(); 
    }
    if(/\s/.test(key)){
      event.preventDefault();
    }
      
    });
  }

  const enableProvider = () => {
    const hc_provider = document.querySelector('#healthcare-provider-category').value;
    console.log('hospital_names',hospital_names);
    console.log('hospital_names',hospital_names);
    console.log('ahcproviders_names',ahcproviders_names);
    const optionElement = document.createElement('option');
    const request_type = document.querySelector('#hospital-name');
      if( hc_provider != '' ){
        removeOption();
        request_type.disabled = false;
        if(hc_provider === '1'){
            ahcproviders_names.forEach( function(hospital){
            optionElement.value = hospital.hp_id;
            optionElement.text = hospital.hp_name;
            request_type.appendChild(optionElement);
          });
          is_accredited = true;
          $('#med-hr-wrapper').prop('hidden',true)
          $('#receipt-wrapper').prop('hidden',true)
          $('#med-hr-wrapper').prop('hidden',true);
        }
        if(hc_provider === '2'){
            hospital_names.forEach( function(hospital){
            optionElement.value = hospital.hp_id;
            optionElement.text = hospital.hp_name;
            request_type.appendChild(optionElement);
          });
          is_accredited = false;
          $('#med-hr-wrapper').prop('hidden',false)
          $('#receipt-wrapper').prop('hidden',false)
          $('#med-hr-wrapper').prop('hidden',false);
        }
      }else{
        request_type.disabled = true;
        request_type.value = '';
      }
  } 

  
function removeOption() {
  var selectElement = document.getElementById('hospital-name');
  for (var i = 0; i < selectElement.options.length; i++) {
    if (selectElement.options[i].value !== '' ) {
      selectElement.remove(i);
    }
  }
}
</script>