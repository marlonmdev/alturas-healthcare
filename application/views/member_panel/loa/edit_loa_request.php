
<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">Letter of Authorization <span class="text-success">[ Edit ]</span></h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Member</li>
              <li class="breadcrumb-item active" aria-current="page">
                Edit LOA
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
    <div class="row">

      <div class="col-12 mb-3">
        <a class="btn btn-dark" href="<?php echo base_url(); ?>member/requested-loa/pending">
          <i class="mdi mdi-arrow-left-bold"></i>
          Go Back
        </a>
      </div>

      <div class="col-lg-12">
        <div class="card shadow">
          <div class="card-body">
            <form method="post" action="<?php echo base_url(); ?>member/requested-loa/update/<?= $this->myhash->hasher($row['loa_id'], 'encrypt') ?>" class="mt-2" id="memberLoaRequestForm" enctype="multipart/form-data">
              <!-- Start of Hidden Inputs -->
              <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
              <input type="hidden" name="loa-id" id="loa-id" value="<?= $this->myhash->hasher($row['loa_id'], 'encrypt') ?>">
              <!-- End of Hidden Inputs -->
             <span class="text-info fs-3 fw-bold ls-2"><i class="mdi mdi-account-card-details"></i> PATIENT DETAILS</span><br>
              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Full Name</label>
                  <input type="text" class="form-control" name="full-name" value="<?= $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] . ' ' . $row['suffix'] ?>" disabled>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Date of Birth</label>
                  <input type="date" class="form-control" name="date-of-birth" value="<?= $row['date_of_birth'] ?>" disabled>
                </div>
                <!-- Start of Age Calculator -->
                <?php
                $birthDate = date("d-m-Y", strtotime($row['date_of_birth']));
                $currentDate = date("d-m-Y");
                $diff = date_diff(date_create($birthDate), date_create($currentDate));
                $age = $diff->format("%y");
                ?>
                <!-- End of Age Calculator -->
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Age</label>
                  <input type="text" class="form-control" name="age" value="<?= $age ?>" disabled>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-2 mb-2">
                  <label class="colored-label">Gender</label>
                  <input type="text" class="form-control" name="gender" value="<?= $row['gender'] ?>" disabled>
                </div>
                <div class="col-sm-4 mb-2">
                  <label class="colored-label">PhilHealth Number</label>
                  <input type="text" class="form-control" name="philhealth-no" value="<?= $row['philhealth_no'] ?>" disabled>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Blood Type</label>
                  <input type="text" class="form-control" name="blood-type" value="<?= $row['blood_type'] ?>" disabled>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Home Address</label>
                  <input type="text" class="form-control" name="home-address" id="patient-address" value="<?= $row['home_address'] ?>" disabled>
                  <em id="patient-address-error" class="text-danger"></em>
                </div>
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">City Address</label>
                  <input type="text" class="form-control" name="city-address" value="<?= $row['city_address'] ?>" disabled>
                </div>

              </div>

              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Contact Number</label>
                  <input type="text" class="form-control" name="contact-no" value="<?= $row['contact_no'] ?>" disabled>
                </div>
                <div class="col-sm-6">
                  <label class="colored-label">Email</label>
                  <input type="email" class="form-control" name="email" value="<?= $row['email'] ?>" disabled>
                </div>
              </div>

              <span class="text-info mt-4 fs-3 fw-bold ls-2"><i class="mdi mdi-contact-mail"></i> CONTACT PERSON DETAILS</span>
              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Contact Person Name</label>
                  <input type="text" class="form-control" name="contact-person" value="<?= $row['contact_person'] ?>" disabled>
                </div>
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Contact Number</label>
                  <input type="text" class="form-control" name="contact-person-no" value="<?= $row['contact_person_no'] ?>" disabled>
                </div>

              </div>

              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Address</label>
                  <input type="text" class="form-control" name="contact-person-addr" value="<?= $row['contact_person_addr'] ?>" disabled>
                </div>
              </div>
              <span class="text-info fs-3 fw-bold ls-2"><i class="mdi mdi-file-document-box"></i> LOA REQUEST DETAILS</span>
              <div class="form-group row">
                <div class="col-sm-3 mb-2">
                  <label class="colored-label"><i class="mdi mdi-asterisk text-danger"></i> Hospital Provider Category</label>
                  <select class="form-select" name="healthcare-provider-category" id="healthcare-provider-category" oninput="enableProvider()">
                    <option value="" selected disabled>Select Healthcare Provider Category</option>
                    <option value="1">Affiliated</option>
                    <option value="2">Not Affiliated</option>
                  </select>
                  <em id="healthcare-provider-category-error" class="text-danger"></em>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label"><i class="mdi mdi-asterisk text-danger"></i> Healthcare Provider</label>
                  <select class="form-select" name="healthcare-provider" id="healthcare-provider" onchange="enableRequestType()" >
                    <option value="" selected disabled>Select Healthcare Provider</option>
                  </select>
                  <em id="healthcare-provider-error" class="text-danger"></em>
                </div>

                <div class="col-sm-3 mb-2">
                  <label class="colored-label"><i class="mdi mdi-asterisk text-danger"></i> Type of Request</label>
                  <select class="form-select" name="loa-request-type" id="loa-request-type" onchange="showMedServices()" > 
                    <option value="" selected>Select LOA Request Type</option>
                    <!-- <option value="Consultation">Consultation</option> -->
                    <option value="Diagnostic Test">Diagnostic Test</option>
                  </select>
                  <em id="loa-request-type-error" class="text-danger"></em>
                </div>

                <div class="col-sm-3 mb-2">
                  <label class="colored-label"><i class="mdi mdi-asterisk text-danger"></i>MBL Balance</label>
                  <input type="text" class="form-control" name="remaining_mbl" id="remaining_mbl" value="<?= $mbl ?>" disabled>
                </div>
              </div>

              <div class="form-group row" id="edit-med-services-wrapper" >
                  <div class="col-sm-8 mb-2  pe-2"  >
                    <label class="colored-label"><i class="mdi mdi-asterisk text-danger"></i> Select Medical Service/s <small class="text-danger"> *Note: Press Tab or Enter to Add More Medical Service</small></label>
                    <input class="custom-input" id="edit-med-services" name="edit-med-services" placeholder="Type and press Enter|Tab">
                    <em id="edit-med-services-error" class="text-danger"></em>
                  </div>
                  <div class="col-lg-4 col-sm-12 mb-2" id="hospital-bill-wrapper" hidden >
                  <label class="colored-label"><i class="bx bx-health icon-red"></i>Total Bill</label>
                  <input type="text" class="form-control" name="hospital-bill" id="hospital-bill" value=<?=number_format($row['hospital_bill'],2)?> placeholder="Enter Hospital Bill" style="background-color:#ffff" autocomplete="off">
                  <em id="hospital-bill-error" class="text-danger"></em>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Healthcard Number</label>
                  <input type="text" class="form-control" name="health-card-no" value="<?= $row['health_card_no'] ?>" disabled>
                </div>
                <div class="col-sm-5 mb-2">
                  <label class="colored-label">Requesting Company</label>
                  <input type="text" class="form-control" name="requesting-company" value="<?= $row['requesting_company'] ?>" disabled>
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
                  <textarea class="form-control" name="chief-complaint" id="chief-complaint" cols="30" rows="6"><?= $row['chief_complaint'] ?></textarea>
                  <em id="chief-complaint-error" class="text-danger"></em>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-lg-6 col-sm-12 col-lg-offset-3 mb-2">
                  <label class="colored-label"><i class="mdi mdi-asterisk text-danger"></i> Requesting Physician</label>
                  <select class="form-select" id="requesting-physician" name="requesting-physician">
                    <option value="" selected>Select Requesting Physician</option>
                    <?php
                    if (!empty($doctors)) {
                      foreach ($doctors as $doctor) :
                        if ($row['requesting_physician'] === $doctor['doctor_id']) {
                    ?>
                          <option value="<?= $doctor['doctor_id']; ?>" selected><?= $doctor['doctor_name']; ?></option>
                        <?php
                        } else {
                        ?>
                          <option value="<?= $doctor['doctor_id']; ?>"><?= $doctor['doctor_name']; ?></option>
                    <?php
                        }
                      endforeach;
                    }
                    ?>
                  </select>
                  <em id="requesting-physician-error" class="text-danger"></em>
                </div>
                <div class="col-lg-6 col-sm-12 col-lg-offset-3 mb-2">
                  <label class="colored-label">Attending Physician <small class="text-danger"> *Note: Press Tab to Add More Physician</small></label>
                  <input type="text" class="form-control" name="attending-physician" value="<?= $row['attending_physician'] ?>" id="tags-input">
                </div>
              </div>
                    
              <section class="row <?= $row['loa_request_type'] === 'Consultation' ? 'd-none' : ''; ?>" id="div-attachment">
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
                  <div class="col-sm-12 mb-4" id="rx-wrapper" hidden>
                  <i class="mdi mdi-asterisk text-danger" id="mdi"></i><label class="colored-label mb-1" id="rx-title"> RX/Request from Accredited Doctor</label>
                    <div id="rx-file-wrapper">
                      <input type="file" class="dropify_rx" name="rx-file" id="rx-file"  data-height="300" data-max-file-size="5M" accept=".jpg, .jpeg, .png">
                      <input type="hidden" name="file-attachment-rx" value="<?= $row['rx_file'] ?>">
                    </div>
                    <em id="rx-file-error" class="text-danger"></em>
                  </div>
                  <div class="col-sm-12 mb-4" id="receipt-wrapper" hidden>
                  <i class="mdi mdi-asterisk text-danger" id="mdi"></i><label class="colored-label mb-1" id="rx-title"> Hospital Receipt</label>
                    <div id="hospital-receipt-wrapper">
                      <input type="file" class="dropify_hr" name="hospital-receipt" id="hospital-receipt"  data-height="300" data-max-file-size="5M" accept=".jpg, .jpeg, .png">
                      <input type="hidden" name="file-attachment-receipt" value="<?= $row['hospital_receipt'] ?>">
                    </div>
                    <em id="hospital-receipt-error" class="text-danger"></em>
                  </div>
                </div>
              </section>

              <div class="row mt-2">
                <div class="col-sm-12 mb-2 d-flex justify-content-start">
                  <button type="submit" class="btn btn-success me-2">
                    <i class="mdi mdi-content-save-settings"></i> UPDATE
                  </button>
                  <a href="<?php echo base_url(); ?>member/requested-loa/pending" class="btn btn-danger">
                    <i class="mdi mdi-close-box"></i> CANCEL
                  </a>
                </div>
              </div>
            </form>
            <!-- End of Form -->
          </div>
          <!-- End of Card Body -->
        </div>
        <!-- End of Card -->
      </div>
    <!-- End Row  -->  
    </div>
  <!-- End Container fluid  -->
  </div>
<!-- End Page wrapper  -->
</div>
<!-- End Wrapper -->
</div>
<style>
 .custom-input {
  width: 100%;
}

/* input[type="text"]::-webkit-inner-spin-button,
input[type="text"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
} */
</style>
<script>
  const baseUrl = `<?= $this->config->base_url() ?>`;
  const redirectPage = `${baseUrl}member/requested-loa/pending`;
  const token = `<?php echo $this->security->get_csrf_hash(); ?>`;
  const is_manual = <?= json_encode($is_accredited)?>;
  const row = <?= json_encode($row)?>;
  const ahcproviders_names = <?= json_encode($ahcproviders)?>;
  const hospital_names = <?= json_encode($hcproviders)?>;
  const services = <?= json_encode($costtypes)?>;
  let tagify;
  let is_accredited = false;
  let is_rx_has_data = true;
  let is_hr_has_data = true;
  $(document).ready(function() {
    console.log('is_manual',is_manual);
    console.log('row',row);

    number_validator();
    if(row.rx_file){
      $(".dropify_rx").dropify({
        messages: {
          default: "Drop files here or click to browse",
          replace: "Drag and drop an image or file here or click to replace",
        },
        defaultFile: baseUrl+"/uploads/loa_attachments/"+row.rx_file, 
      });
    }else{
      $(".dropify_rx").dropify({
        messages: {
          default: "Drop files here or click to browse",
          replace: "Drag and drop an image or file here or click to replace",
        },
      });
    }
    
    if(row.hospital_receipt){
      $(".dropify_hr").dropify({
        messages: {
          default: "Drop files here or click to browse",
          replace: "Drag and drop an image or file here or click to replace",
        },
        defaultFile: baseUrl+"/uploads/hospital_receipt/"+row.hospital_receipt, 
      });
    }else{
      $(".dropify_hr").dropify({
        messages: {
          default: "Drop files here or click to browse",
          replace: "Drag and drop an image or file here or click to replace",
        },
      });
    }
    

      $('.dropify_hr').on('dropify.afterClear', function (event, element) {
        is_hr_has_data = false;
        console.log('false');
      });

      $('.dropify_rx').on('dropify.afterClear', function (event, element) {
        is_rx_has_data = false;
        console.log('false');
      });

      $('.dropify_rx').on('change', function (event) {
        if(event.target.files[0]){
          is_rx_has_data = true;
          console.log('true');
        }
      });

      $('.dropify_hr').on('change', function (event) {
        if(event.target.files[0]){
          is_hr_has_data = true;
          console.log('true');
        }
      });

    $('#memberLoaRequestForm').submit(function(event) {
      let hp_bill = $('#hospital-bill').val();
      $('#hospital-bill').val(hp_bill.replace(/,/g,''));
  
      event.preventDefault();
      let $data = new FormData($(this)[0]);
      // console('data',data);
      $data.append('is_accredited',is_accredited);
      $data.append('is_hr_has_data',is_hr_has_data);
      $data.append('is_rx_has_data',is_rx_has_data);
     
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
            rx_file_error,
            hospital_receipt_error,
            hospital_bill_error
          } = response;
          switch (status) {
            case 'error':
              $('#hospital-bill').val(hp_bill);
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
                $('#edit-med-services-error').html(med_services_error);
                $('#edit-med-services-wrapper').addClass('div-has-error');
              } else {
                $('#edit-med-services-error').html('');
                $('#edit-med-services-wrapper').removeClass('div-has-error');
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
              break;
            case 'save-error':
              $('#hospital-bill').val(hp_bill);
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
              setTimeout(function() {
                window.location.href = redirectPage;
              }, 3200);
              break;
          }
        },
      })
    });

    tagify_values();
      $('#healthcare-provider').on('change',function(){
        $('#edit-med-services').val('');
          tagify_values();
      });

      if(is_manual){
      $('#healthcare-provider-category').val(2);
      enableProvider();
      $('#healthcare-provider').val(row.hcare_provider);
      $('#loa-request-type').val(row.loa_request_type);
      $('#hospital-bill-wrapper').prop('hidden',false);
      // get_services(services,row.med_services);
    }else{
      $('#healthcare-provider-category').val(1);
      enableProvider();
      $('#healthcare-provider').val(row.hcare_provider);
      $('#loa-request-type').val(row.loa_request_type);
      $('#hospital-bill-wrapper').prop('hidden',true);
      // get_services(services,row.med_services);
    }

    $('#healthcare-provider-category').on('change',function(){
        $('#edit-med-services').val('');
        tagify_values();
        if( $('#healthcare-provider-category').val()==='1'){
          $('#hospital-bill-wrapper').prop('hidden',true);
        }else{
          $('#hospital-bill-wrapper').prop('hidden',false);
        }
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
    const medServices = document.querySelector('#edit-med-services-div');
    const fileAttachment = document.querySelector('#div-attachment');
    if (loaType === "Consultation" || loaType === "") {
      medServices.className = "d-none";
      fileAttachment.className = "d-none";
    } else {
      medServices.className = "col-lg-7 col-sm-12 mb-2 d-block";
      fileAttachment.className = "form-group row d-block";
    }
  }

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
    $('#hospital-bill').on('keyup',function(event){
      let val = event.key;
      if(val!=='.' && $(this).val() !==''){
        $(this).val(Number($(this).val().replace(/,/g,'')).toLocaleString(2));
      }
      
    });
}
  // const enableRequestType = () => {
  //   const hc_provider = document.querySelector('#healthcare-provider').value;

  //   const request_type = document.querySelector('#loa-request-type');
  //     if( hc_provider != '' ){
  //       request_type.disabled = false;
  //     }else{
  //       request_type.disabled = true;
  //       request_type.value = '';
  //     }
  // } 

  const enableProvider = () => {
    const hc_provider = document.querySelector('#healthcare-provider-category').value;
    const optionElement = document.createElement('option');
    const request_type = document.querySelector('#healthcare-provider');
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
          $('#receipt-wrapper').prop('hidden',true);
          $('#hospital-bill-wrapper').prop('hidden',true);
          $('#rx-wrapper').prop('hidden',false);
        }
        if(hc_provider === '2'){
            hospital_names.forEach( function(hospital){
            optionElement.value = hospital.hp_id;
            optionElement.text = hospital.hp_name;
            request_type.appendChild(optionElement);
          });
          is_accredited = false;
          $('#receipt-wrapper').prop('hidden',false);
          $('#hospital-bill-wrapper').prop('hidden',false);
          $('#rx-wrapper').prop('hidden',true);
        }
      }else{
        request_type.disabled = true;
        request_type.value = '';
      }
  } 

  function removeOption() {
  var selectElement = document.getElementById('healthcare-provider');
  for (var i = 0; i < selectElement.options.length; i++) {
    if (selectElement.options[i].value !== '' ) {
      selectElement.remove(i);
    }
  }
}

const get_services = (tagfiy,response,added_services) => {
  let exp_services = added_services.split(';');
  let prev_services = [];
  exp_services.forEach(value => {
    let is_custom = true;
  //  const find_services = services.find(item => item.ctype_id === '1');
   const find_services =response.map(arr =>{
   
    if(arr.ctyp_id === value){
      is_custom = false;
      return arr ;
    }else{
      if(is_custom){
        is_custom = true;
      }
    }
   }).filter(Boolean);

   if(is_custom){
    console.log('is_custom',is_custom);
    prev_services.push(value);
   }
   find_services.forEach(function (item) {
                // Build the tag text, including the description and price
                const tagText = is_accredited
                  ? `${item.ctyp_description} - ₱${item.ctyp_price}`
                  : `${item.ctyp_description}`; 

                // Optionally, you can directly add the tag to Tagify using addTags method
                const tagData = {
                  value: tagText,
                  tagid: item.ctyp_id,
                };
                
                prev_services.push(tagData);
              });
    });

  // console.log('prev_services',prev_services);
  tagfiy.addTags(prev_services);
}

const tagify_values = () =>{
  // $('#edit-med-services').val('');
        let hp_id = $('#healthcare-provider').val();
        const token = `<?php echo $this->security->get_csrf_hash(); ?>`;
        const intput_service = [];
        const input = document.getElementById('edit-med-services');
        
        // Declare tagify outside the if statement
        console.log('hp_id',hp_id);
        hp_id = is_accredited ? hp_id : 1;
        if (hp_id !== '') { 
          $.ajax({
            url: `${baseUrl}member/get-services/${hp_id}`,
            type: 'GET',
            dataType: 'json',
            data: { token: token },
            success: function (response) {
              console.log(response); // Check the response in the console
              console.log('is_accredited',is_accredited);
              response.forEach(function (item) {
                // Build the tag text, including the description and price
                const tagText = is_accredited
                  ? `${item.ctyp_description} - ₱${item.ctyp_price}`
                  : `${item.ctyp_description}`; 

                // Optionally, you can directly add the tag to Tagify using addTags method
                const tagData = {
                  value: tagText,
                  tagid: item.ctyp_id,
                  // Use tagText as the visible text
                  // data: {
                  //   price: item.ctyp_price,
                  //   // You can add any other additional data you need here
                  // },
                };
                intput_service.push(tagData);
              });

              // Initialize Tagify with the intput_service array containing both tagData and tag text
              // console.log('tagifiy',tagify);
              if (tagify) {
                tagify.settings.whitelist = intput_service;
                tagify.settings.enforceWhitelist = (is_accredited)?true:false;
                // tagify.dropdown.show.call(tagify, ''); // Refresh the dropdown to reflect the new whitelist
              } else {
                tagify = new Tagify(input, {
                  whitelist: intput_service,
                  enforceWhitelist: (is_accredited)?true:false,
                });
                if(row.med_services){
                  // console.log('services',services);
                  get_services(tagify,response,row.med_services);
                }
              }

              tagify.on('change', function () {
                const selectedTags = tagify.value.map((tag) => {
                  return {
                    value: tag.value,
                    tagid: tag.tagid,
                    // Use __tagifyTagData.tagText to get the visible text
                    // data: tag.data.price, // Use __tagifyTagData.data to get additional data
                  };
                });
                console.log('selected tag', selectedTags);
              });

            },
            error: function (xhr, status, error) {
              console.error('Ajax request failed:', error);
            },
          });
        }
}

</script>