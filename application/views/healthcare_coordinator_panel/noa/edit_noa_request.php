<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <a href="<?php echo base_url(); ?>healthcare-coordinator/noa/requests-list" class="btn btn-dark"><i class="mdi mdi-arrow-left-bold"></i> Back</a>
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
            <form method="post" action="<?= base_url() ?>healthcare-coordinator/noa/requested-noa/update/<?= $this->myhash->hasher($row['noa_id'], 'encrypt') ?>" class="mt-2" id="noaRequestForm">
              <!--  Start of Hidden Inputs -->
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
                <div class="col-lg-8 col-sm-12 col-lg-offset-3 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Name of Hospital</label>
                  <select class="form-select" name="hospital-name" id="hospital-name">
                    <option value="" selected>Select Hospital</option>
                    <?php
                    if (!empty($hospitals)) {
                      foreach ($hospitals as $hospital) :
                        if ($row['hospital_id'] === $hospital['hp_id']) {
                    ?>
                          <option value="<?= $hospital['hp_id']; ?>" selected><?= $hospital['hp_name']; ?></option>
                        <?php
                        } else {
                        ?>
                          <option value="<?= $hospital['hp_id']; ?>"><?= $hospital['hp_name']; ?></option>
                    <?php
                        }
                      endforeach;
                    }
                    ?>
                  </select>
                  <em id="hospital-name-error" class="text-danger"></em>
                </div>
                <div class="col-lg-4 col-sm-12 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Admission Date</label>
                  <input type="text" class="form-control" name="admission-date" id="admission-date" value="<?= $row['admission_date'] ?>" placeholder="Select Date" style="background-color:#ffff">
                  <em id="admission-date-error" class="text-danger"></em>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-12 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Chief Complaint/Diagnosis</label>
                  <textarea class="form-control" name="chief-complaint" id="chief-complaint" cols="30" rows="6"><?= $row['chief_complaint'] ?></textarea>
                  <em id="chief-complaint-error" class="text-danger"></em>
                </div>
              </div><br>

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







<script type="text/javascript">
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
</script>