<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block flex-column flex-sm-row align-items-left">
        <h4 class="page-title ls-2">NOTICE OF ADMISSION</h4>
        <div class="ms-auto text-end order-first order-sm-last">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Member</li>
              <li class="breadcrumb-item active" aria-current="page">
                Request NOA
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
          <div class="card-body mt-3">

            <form method="post" action="<?= base_url() ?>member/request-noa/submit" enctype="multipart/form-data" class="mt-2" id="memberNoaRequestForm">
              <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
              <input type="hidden" class="form-control" name="type_request" id="type_request" value="Admission">

              <div class="form-group row">
                <div class="col-sm-7 mb-2">
                  <label class="colored-label">Full Name</label>
                  <input type="text" class="form-control" name="first-name" id="first-name" value="<?= $member['first_name'] . ' ' . $member['middle_name'] . ' ' . $member['last_name'] . ' ' . $member['suffix'] ?>" disabled>
                </div>

                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Date of Birth</label>
                  <input type="date" class="form-control" name="date-of-birth" id="date-of-birth" value="<?= $member['date_of_birth'] ?>" disabled>
                </div>

                <div class="col-sm-2 mb-3">
                  <?php
                  $dateOfBirth = $member['date_of_birth'];
                  $today = date("Y-m-d");
                  $diff = date_diff(date_create($dateOfBirth), date_create($today));
                  $patient_age = $diff->format('%y');
                  ?>
                  <label class="colored-label">Age:</label>
                  <input type="text" class="form-control" name="gender" id="gender" value="<?= $patient_age; ?>" disabled>
                </div>
              </div>

              <div class="form-group row">

                <div class="col-sm-4 mb-2">
                  <label class="colored-label"><i class="mdi mdi-asterisk text-danger"></i> Hospital Provider Category</label>
                  <select class="form-select" name="healthcare-provider-category" id="healthcare-provider-category" oninput="enableProvider()">
                    <option value="" selected>Select Healthcare Provider Category</option>
                    <option value="1">Affiliated</option>
                    <option value="2">Non-Affiliated</option>
                  </select>
                  <em id="healthcare-provider-category-error" class="text-danger"></em>
                </div>

                <div class="col-lg-4 col-sm-12 col-lg-offset-3 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i>  Healthcare Provider</label>
                  <select class="form-select" name="hospital-name" id="hospital-name" oninput="" disabled>
                    <option value="" selected>Select Healthcare Provider</option>
                  </select>
                  <em id="hospital-name-error" class="text-danger"></em>
                </div>

                <div class="col-lg-4 col-sm-12 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Admission Date</label>
                  <input type="text" class="form-control" name="admission-date" id="admission-date" placeholder="Select Date" style="background-color:#ffff">
                  <em id="admission-date-error" class="text-danger"></em>
                </div>
              </div>

              <div class="form-group row" id="med-services-wrapper" hidden>
                  <div class="col-sm-8 mb-0 pe-2"  >
                    <label class="colored-label"><i class="mdi mdi-asterisk text-danger"></i> Input Medical Service/s <small class="text-danger"> *Note: Press Tab or Enter to Add More Medical Service</small></label>
                    <input class="custom-input" id="noa-med-services" name="noa-med-services" placeholder="Type and press Enter|Tab">
                    </input>
                    <em id="noa-med-services-error" class="text-danger"></em>
                  </div>
                  <div class="col-lg-4 col-sm-12 mb-2">
                  <label class="colored-label"><i class="mdi mdi-asterisk text-danger"></i></i> Total Bill</label>
                  <input type="text" class="form-control" name="hospital-bill" id="hospital-bill" placeholder="Enter Hospital Bill" style="background-color:#ffff" autocomplete="off">
                  <em id="hospital-bill-error" class="text-danger"></em>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-12 mb-0">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Chief Complaint/Diagnosis</label>
                  <textarea class="form-control" name="chief-complaint" id="chief-complaint" cols="30" rows="6"></textarea>
                  <em id="chief-complaint-error" class="text-danger"></em>
                </div>
              </div>

              <section class="row" id="receipt-wrapper" hidden >
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
                    </div>
                    <em id="hospital-receipt-error" class="text-danger"></em>
                  </div>
                </div>
              </section>
             
              <br>
              <div class="row">
                <div class="col-sm-12 mb-2 d-flex justify-content-start">
                  <button type="submit" class="btn btn-primary me-2" id="submit">
                    <i class="mdi mdi-content-save"></i> SUBMIT
                  </button>
                  <a href="JavaScript:void(0)" onclick="window.history.back()" class="btn btn-danger">
                   <i class="mdi mdi-arrow-left-bold"></i> GO BACK
                  </a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>




<style>
 .custom-input {
  width: 100%;
}

</style>
<script type="text/javascript">
  const baseUrl = `<?php echo base_url(); ?>`;
  const mbl = "<?=$mbl['remaining_balance']?>";
  const ahcproviders_names = <?= json_encode($ahcproviders)?>;
  const hospital_names = <?= json_encode($hcproviders)?>;
  let is_accredited = false;
  const input_bill = document.getElementById('hospital-bill');
  const med_services = document.getElementById('noa-med-services');
  $(document).ready(function() {
    new Tagify(med_services);
    
    $('#admission-date').flatpickr({
          dateFormat: "Y-m-d"
        });

    input_bill.setAttribute('autocomplete', 'off');
    number_validator();
    $('#hospital-bill').on('input',function(){
      let value = $('#hospital-bill').val();
      let length  = $('#hospital-bill').val().length;
      console.log('value',value);
      if(length <=1 && (value === '0'|| value === '.' || value ==='')){
        $('#hospital-bill').val('');
      }
    });

    if(mbl<=0){
    $('#submit').prop('disabled',true); 
    $('#healthcare-provider-category').prop('disabled',true); 
    $('#admission-date').prop('disabled',true); 
    $('#chief-complaint').prop('disabled',true); 
    $.alert({
          title: `<h3 style='font-weight: bold; color: #dc3545; margin-top: 0;'>Unable to Request: Insufficient MBL Balance</h3>`,
          content: "<div style='font-size: 16px; color: #333;'>We apologize for the inconvenience, but it appears that your MBL balance in your account is currently empty. Before proceeding with your request, please ensure that you have sufficient MBL balance. Thank you for your understanding.</div>",
          type: "red",
          buttons: {
            ok: {
              text: "OK",
              btnClass: "btn-danger",
            }
          }
        });
    }
    // new Tagify('noa-med-services');
    $('#memberNoaRequestForm').submit(function(event) {
      let hp_bill = $('#hospital-bill').val();
      $('#hospital-bill').val(hp_bill.replace(/,/g,''));
      event.preventDefault();
        let $data = new FormData($(this)[0]);
        $data.append('is_accredited',is_accredited);
      //   if(mbl<=0){
      //   $.alert({
      //     title: "<h3 style='font-weight: bold; color: #dc3545; margin-top: 0;'>Unable to Request</h3>",
      //     content: "<div style='font-size: 16px; color: #333;'>We apologize for the inconvenience, but it looks like your MBL balance is currently empty. Please ensure that you have enough MBL in your account before attempting to make a request. Thank you for your understanding.</div>",
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
      //     // $('#submit').prop('disabled',true);      
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
              admission_date_error,
              hospital_receipt_error,
              med_services_error,
              hospital_bill_error,
              healthcare_provider_category_error
            } = response;

            if (status === 'error') {
              $('#hospital-bill').val(hp_bill);
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
              console.log('hospital_receipt_error',hospital_receipt_error);
              console.log('med_services_error',med_services_error);
              console.log('hospital_bill_error',hospital_bill_error);
              if (chief_complaint_error !== '') {
                $('#chief-complaint-error').html(chief_complaint_error);
                $('#chief-complaint').addClass('is-invalid');
              } else {
                $('#chief-complaint-error').html('');
                $('#chief-complaint').removeClass('is-invalid');
                $('#chief-complaint').addClass('is-valid');
              }

              if (med_services_error !== '') {
                $('#noa-med-services-error').html(med_services_error);
                $('#noa-med-services').addClass('is-invalid');
              } else {
                $('#noa-med-services').html('');
                $('#noa-med-services').removeClass('is-invalid');
              }

              if (hospital_bill_error !== '') {
                $('#hospital-bill-error').html(hospital_bill_error);
                $('#hospital-bill').addClass('is-invalid');
              } else {
                $('#hospital-bill-error').html('');
                $('#hospital-bill').removeClass('is-invalid');
              }

              if (hospital_receipt_error !== '') {
                $('#hospital-receipt-error').html(hospital_receipt_error);
                $('#hospital-receipt').addClass('is-invalid');
              } else {
                $('#hospital-receipt-error').html('');
                $('#hospital-receipt').removeClass('is-invalid');
              }
              if (healthcare_provider_category_error !== '') {
                $('#healthcare-provider-category-error').html(healthcare_provider_category_error);
                $('#healthcare-provider-category').addClass('is-invalid');
              } else {
                $('#healthcare-provider-category-error').html('');
                $('#healthcare-provider-category').removeClass('is-invalid');
              }

            } else if (status === 'save-error') {
              $('#hospital-bill').val(hp_bill);
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
                window.location.href = `${baseUrl}member/requested-noa/pending`;
              }, 3200);
            }
          },
        });
      // End of AJAX Request
    // }
    
    });

    $('#healthcare-provider-category').on('change',function(){
        $('#noa-med-services').val('');
      });
      $('#hospital-name').on('change',function(){
        $('#noa-med-services').val('');
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

    $('#hospital-bill').on('keyup',function(event){
      let val = event.key;
      if(val!=='.' && $(this).val() !==''){
        $(this).val(Number($(this).val().replace(/,/g,'')).toLocaleString(2));
      }
      
    });
  }

  const enableProvider = () => {
    const hc_provider = document.querySelector('#healthcare-provider-category').value;
    console.log('hospital_names',hospital_names);
    console.log('ahcproviders_names',ahcproviders_names);
    const request_type = document.querySelector('#hospital-name');
    request_type.innerHTML = '<option value="" selected>Select Healthcare Provider</option>';
      if( hc_provider != '' ){
        removeOption();
        request_type.disabled = false;
        if(hc_provider === '1'){
            ahcproviders_names.forEach( function(hospital){
            const optionElement = document.createElement('option');
            optionElement.value = hospital.hp_id;
            optionElement.text = hospital.hp_name;
            request_type.appendChild(optionElement);
          });
          is_accredited = true;
          $('#med-services-wrapper').prop('hidden',true)
          $('#receipt-wrapper').prop('hidden',true)
        }
        if(hc_provider === '2'){
            hospital_names.forEach( function(hospital){
            const optionElement = document.createElement('option');
            optionElement.value = hospital.hp_id;
            optionElement.text = hospital.hp_name;
            request_type.appendChild(optionElement);
          });
          is_accredited = false;
          $('#med-services-wrapper').prop('hidden',false)
          $('#receipt-wrapper').prop('hidden',false)
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