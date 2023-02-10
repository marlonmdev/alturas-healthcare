
<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">Notice of Admission <span class="text-success">[Edit]</span></h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Super Admin</li>
              <li class="breadcrumb-item active" aria-current="page">
                Edit NOA Request
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
            <form method="post" action="<?= base_url() ?>super-admin/noa/requested-noa/update/<?= $this->myhash->hasher($row['noa_id'], 'encrypt') ?>" class="mt-2" id="noaRequestForm">
              <!--  Start of Hidden Inputs -->
              <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
              <div class="form-group row">
                <div class="col-sm-7 mb-2">
                  <label class="colored-label">Full Name</label>
                  <input type="text" class="form-control" name="first-name" id="first-name" value="<?= $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] . ' ' . $row['suffix'] ?>" disabled>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Date of Birth</label>
                  <input type="date" class="form-control" name="date-of-birth" id="date-of-birth" value="<?= $row['date_of_birth'] ?>" disabled>
                </div>
                <!-- Start of Age Calculator -->
                <?php
                $birthDate = date("d-m-Y", strtotime($row['date_of_birth']));
                $currentDate = date("d-m-Y");
                $diff = date_diff(date_create($birthDate), date_create($currentDate));
                $age = $diff->format("%y");
                ?>
                <!-- End of Age Calculator -->
                <div class="col-sm-2 mb-2">
                  <label class="colored-label">Age:</label>
                  <input type="text" class="form-control" name="age" id="age" value="<?= $age ?>" disabled>
                </div>
              </div>

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
                  <input type="date" class="form-control" name="admission-date" id="admission-date" value="<?= $row['admission_date'] ?>">
                  <em id="admission-date-error" class="text-danger"></em>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-12 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Chief Complaint/Diagnosis</label>
                  <textarea class="form-control" name="chief-complaint" id="chief-complaint" cols="30" rows="6"><?= $row['chief_complaint'] ?></textarea>
                  <em id="chief-complaint-error" class="text-danger"></em>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-sm-12 mb-2 d-flex justify-content-start">
                  <button type="submit" class="btn btn-success"><i class="mdi mdi-content-save-settings"></i> UPDATE
                  </button>
                  &nbsp;&nbsp;
                  <a href="<?php echo base_url(); ?>super-admin/noa/requests-list" class="btn btn-danger"><i class="mdi mdi-close-box"></i> CANCEL</a>
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
  </div>
</div>

<script type="text/javascript">
  const baseUrl = `<?php echo base_url(); ?>`;
  const redirectPage = `${baseUrl}super-admin/noa/requests-list`;
  $(document).ready(function() {
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
