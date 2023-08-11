<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">NOTICE OF ADMISSION<span class="text-success">[ Edit ]</span></h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Member</li>
              <li class="breadcrumb-item active" aria-current="page">
                Edit NOA
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
            <form method="post" action="<?php echo base_url(); ?>member/requested-noa/update/<?= $this->myhash->hasher($row['tbl_1_id'], 'encrypt') ?>" enctype="multipart/form-data" class="mt-2" id="editNoaRequestForm">
              <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash(); ?>">

              <div class="form-group row">
                <div class="col-sm-7 mb-2">
                  <label class="colored-label">Full Name</label>
                  <input type="text" class="form-control" name="patient-name" id="patient-name" value="<?php echo $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] . ' ' . $row['suffix']; ?>" disabled>
                </div>

                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Date of Birth</label>
                  <input type="date" class="form-control" name="date-of-birth" id="date-of-birth" value="<?php echo $row['date_of_birth']; ?>" disabled>
                </div>

                <div class="col-sm-2 mb-3">
                  <?php
                    $dateOfBirth = $row['date_of_birth'];
                    $today = date("Y-m-d");
                    $diff = date_diff(date_create($dateOfBirth), date_create($today));
                    $patient_age = $diff->format('%y') . ' years old';
                  ?>
                  <label class="colored-label">Age:</label>
                  <input type="text" class="form-control" name="gender" id="gender" value="<?php echo $patient_age; ?>" disabled>
                </div>
              </div>

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
                  <input type="text" class="form-control" name="admission-date" id="admission-date" placeholder="Select Date" style="background-color:#ffff" value=<?=$row['admission_date']?>>
                  <em id="admission-date-error" class="text-danger"></em>
                </div>
              </div>

              <div class="form-group row" id="med-hr-wrapper" hidden>
                  <div class="col-sm-8 mb-0 pe-2" id="med-services-wrapper">
                    <label class="colored-label"><i class="mdi mdi-asterisk text-danger"></i> Input Medical Service/s <small class="text-danger"> *Note: Press Tab or Enter to Add More Medical Service</small></label>
                    <input class="custom-input" id="noa-med-services" name="noa-med-services" placeholder="Type and press Enter|Tab" >
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
                  <textarea class="form-control" name="chief-complaint" id="chief-complaint" cols="30" rows="6"><?php echo $row['chief_complaint']; ?></textarea>
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
                <div class="col-sm-12 mb-2 d-flex justify-content-start">
                  <button type="submit" class="btn btn-success me-2">
                    <i class="mdi mdi-content-save-settings"></i> UPDATE
                  </button>
                  <a href="<?php echo base_url(); ?>member/requested-noa/pending" class="btn btn-danger">
                    <i class="mdi mdi-close-box"></i> CANCEL
                  </a>
                </div>
              </div>
            </form>
          </div>
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
</style>
<script type="text/javascript">
  const baseUrl = `<?php echo $this->config->base_url(); ?>`;
  const redirectPage = `${baseUrl}member/requested-noa/pending`;
  const ahcproviders_names = <?= json_encode($ahcproviders)?>;
  const hospital_names = <?= json_encode($hcproviders)?>;
  const old_services = <?= json_encode($old_services)?>;
  const row = <?= json_encode($row)?>;
  const is_manual = <?= json_encode($is_accredited)?>;
  const tag_input =document.getElementById('noa-med-services');
  let is_accredited = false;
  let is_hr_has_data = true;
  $(document).ready(function() {
    // console.log('noa id',row.tbl_1_id);
    const tagify = new Tagify(tag_input);
    tagify.addTags(old_services);
    number_validator();
    $('#admission-date').flatpickr({
      dateFormat: "Y-m-d"
    });
    
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
      $('#hospital-name').val(row.hospital_id);
      // console.log('hospital name',row.hospital_id);
      enableProvider();
      // $('#hospital-name').val(row.hospital_id);
      $('#med-hr-wrapper').prop('hidden',false);
      $('#hospital-bill').val(row.hospital_bill);
      // get_services(services,row.med_services);
    }else{
      $('#healthcare-provider-category').val(1);
      $('#hospital-name').val(row.hospital_id);
      // console.log('hospital name',row.hospital_id);
      enableProvider();
      // $('#hospital-name').val(row.hospital_id);
      $('#med-hr-wrapper').prop('hidden',true);
      // get_services(services,row.med_services);
    }

    $('#editNoaRequestForm').submit(function(event) {
      let hp_bill = $('#hospital-bill').val();
      $('#hospital-bill').val(hp_bill.replace(/,/g,''));
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
    console.log('hospital_names',hospital_names);
    console.log('ahcproviders_names',ahcproviders_names);
    console.log('ahcproviders val',hc_provider);
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