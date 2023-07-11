

<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">LETTER OF AUTHORIZATION</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Member</li>
              <li class="breadcrumb-item active" aria-current="page">
              Request Emergency LOA
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
      <div class="col-lg-12">
        <div class="card shadow">
          <div class="card-body mt-3">

            <form method="post" action="<?= base_url() ?>member/request-loa/submit" enctype="multipart/form-data" class="mt-2" id="memberNoaRequestForm">
              <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
              <input type="hidden" name="loa-request-type" value="Emergency">

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
                <div class="col-lg-8 col-sm-12 col-lg-offset-3 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Name of Hospital</label>
                  <select class="form-select" name="healthcare-provider" id="healthcare-provider">
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
                  <em id="healthcare-provider-error" class="text-danger"></em>
                </div>
                <div class="col-lg-4 col-sm-12 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Date Hospitalized</label>
                  <input type="text" class="form-control" name="admission-date" id="admission-date" placeholder="Select Date" style="background-color:#ffff">
                  <em id="admission-date-error" class="text-danger"></em>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-12 mb-0">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Chief Complaint/Diagnosis</label>
                  <textarea class="form-control" name="chief-complaint" id="chief-complaint" cols="30" rows="6"></textarea>
                  <em id="chief-complaint-error" class="text-danger"></em>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-sm-12 mb-2 d-flex justify-content-start">
                  <button type="submit" id = "submit" class="btn btn-primary me-2">
                    <i class="mdi mdi-content-save"></i> SUBMIT
                  </button>
                  <a href="JavaScript:void(0)" onclick="window.history.back()" class="btn btn-danger">
                   <i class="mdi mdi-arrow-left-bold"></i> GO BACK
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

<script type="text/javascript">
  const baseUrl = `<?php echo base_url(); ?>`;
  const mbl = "<?=$mbl['remaining_balance']?>";
  $(document).ready(function() {
    $('#submit').prop('disabled',false); 

    $('#admission-date').flatpickr({
      dateFormat: "Y-m-d"
    });

    if(mbl<=0){
        $('#submit').prop('disabled',true);
        $.alert({
          title: "<h3 style='font-weight: bold; color: #dc3545; margin-top: 0;'>Unable to Request</h3>",
          content: "<div style='font-size: 16px; color: #333;'>We sincerely apologize for any inconvenience caused. It appears that your MBL balance  is currently empty. In order to proceed with a request, we kindly request that you ensure there are sufficient MBL funds available. We greatly appreciate your understanding and cooperation in this matter.</div>",

          type: "red",
          buttons: {
              ok: {
                  text: "OK",
                  btnClass: "btn-danger",
                  // action: function(){
                  //   window.history.back()
                  // },
              },
          },
      });
      }else  if(mbl< 500){
        $.alert({
          title: "<h3 style='font-weight: bold; color: #dc3545; margin-top: 0;'>Warning</h3>",
          content: "<div style='font-size: 16px; color: #333;'>We sincerely apologize for any inconvenience caused. It appears that the MBL balance is currently below 500.00. We greatly appreciate your understanding and cooperation in this matter.</div>",

          type: "red",
          buttons: {
              ok: {
                  text: "OK",
                  btnClass: "btn-danger",
              },
          },
        });
      }

    $('#memberNoaRequestForm').submit(function(event) {
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
              chief_complaint_error,
              admission_date_error
            } = response;

            if (status === 'error') {
              // is-invalid class is a built in classname for errors in bootstrap
              if (healthcare_provider_error !== '') {
                $('#healthcare-provider-error').html(healthcare_provider_error);
                $('#healthcare-provider').addClass('is-invalid');
              } else {
                $('#healthcare-provider-error').html('');
                $('#healthcare-provider').removeClass('is-invalid');
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
                window.location.href = `${baseUrl}member/requested-loa/pending`;
              }, 3200);
            }
          },
        });
      // End of AJAX Request
    
    });
  });
 
</script>