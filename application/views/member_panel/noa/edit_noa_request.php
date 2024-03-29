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
            <form method="post" action="<?php echo base_url(); ?>member/requested-noa/update/<?= $this->myhash->hasher($row['noa_id'], 'encrypt') ?>" enctype="multipart/form-data" class="mt-2" id="editNoaRequestForm">
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
                <div class="col-lg-8 col-sm-12 col-lg-offset-3 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Name of Hospital</label>

                  <select class="form-select" name="hospital-name" id="hospital-name">
                    <option value="" selected>Select Hospital</option>
                    <?php if (!empty($hospitals)) {
                      foreach ($hospitals as $hospital) :
                        if ($row['hospital_id'] === $hospital['hp_id']) {
                    ?>
                          <option value="<?= $hospital['hp_id']; ?>" selected><?= $hospital['hp_name']; ?></option>
                        <?php }else{?>
                          <option value="<?= $hospital['hp_id']; ?>"><?= $hospital['hp_name']; ?></option>
                        <?php }
                      endforeach;
                    } ?>
                  </select>
                  <em id="hospital-name-error" class="text-danger"></em>
                </div>
                
                <div class="col-lg-4 col-sm-12 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Admission Date</label>
                  <input type="text" class="form-control" name="admission-date" id="admission-date" value="<?php echo $row['admission_date']; ?>" placeholder="Select Date" style="background-color:#ffff">
                  <em id="admission-date-error" class="text-danger"></em>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-12 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Chief Complaint/Diagnosis</label>
                  <textarea class="form-control" name="chief-complaint" id="chief-complaint" cols="30" rows="6"><?php echo $row['chief_complaint']; ?></textarea>
                  <em id="chief-complaint-error" class="text-danger"></em>
                </div>
              </div>
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

<script type="text/javascript">
  const baseUrl = `<?php echo $this->config->base_url(); ?>`;
  const redirectPage = `${baseUrl}member/requested-noa/pending`;

  $(document).ready(function() {

    $('#admission-date').flatpickr({
      dateFormat: "Y-m-d"
    });

    $('#editNoaRequestForm').submit(function(event) {
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
</script>