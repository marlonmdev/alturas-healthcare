
<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">Letter of Authorization <span class="text-success">[Edit]</span></h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Super Admin</li>
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
        <a class="btn btn-dark" href="<?php echo base_url(); ?>super-admin/loa/requests-list">
          <i class="mdi mdi-arrow-left-bold"></i>
          Go Back
        </a>
      </div>

      <div class="col-lg-12">
        <div class="card shadow">
          <div class="card-body">
            <form method="post" action="<?= base_url() ?>super-admin/loa/requested-loa/update/<?= $this->myhash->hasher($row['loa_id'], 'encrypt') ?>" class="mt-2" id="loaRequestForm" enctype="multipart/form-data">
              <!-- Start of Hidden Inputs -->
              <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
              <input type="hidden" name="status" value="<?= $row['status']; ?>">
              <input type="hidden" name="emp-id" value="<?= $row['emp_id']; ?>">
              <input type="hidden" name="first-name" value="<?= $row['first_name'] ?>">
              <input type="hidden" name="middle-name" value="<?= $row['middle_name'] ?>">
              <input type="hidden" name="last-name" value="<?= $row['last_name'] ?>">
              <input type="hidden" name="suffix" value="<?= $row['suffix'] ?>">
              <!-- End of Hidden Inputs -->
              <span class="text-info fs-3 fw-bold ls-2"><i class="mdi mdi-account-card-details"></i> PATIENT DETAILS</span><br>
              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Full Name</label>
                  <input type="text" class="form-control has-data" name="full-name" value="<?= $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] . ' ' . $row['suffix'] ?>" readonly>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Date of Birth</label>
                  <input type="date" class="form-control has-data" name="date-of-birth" value="<?= $row['date_of_birth'] ?>" readonly>
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
                  <input type="text" class="form-control has-data" name="age" value="<?= $age ?>" readonly>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-2 mb-2">
                  <label class="colored-label">Gender</label>
                  <input type="text" class="form-control has-data" name="gender" value="<?= $row['gender'] ?>" readonly>
                </div>
                <div class="col-sm-4 mb-2">
                  <label class="colored-label">PhilHealth Number</label>
                  <input type="text" class="form-control has-data" name="philhealth-no" value="<?= $row['philhealth_no'] ?>" readonly>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Blood Type</label>
                  <input type="text" class="form-control has-data" name="blood-type" value="<?= $row['blood_type'] ?>" readonly>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Home Address</label>
                  <input type="text" class="form-control has-data" name="home-address" id="patient-address" value="<?= $row['home_address'] ?>" readonly>
                  <em id="patient-address-error" class="text-danger"></em>
                </div>
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">City Address</label>
                  <input type="text" class="form-control has-data" name="city-address" value="<?= $row['city_address'] ?>" readonly>
                </div>

              </div>

              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Contact Number</label>
                  <input type="text" class="form-control has-data" name="contact-no" value="<?= $row['contact_no'] ?>" readonly>
                </div>
                <div class="col-sm-6">
                  <label class="colored-label">Email</label>
                  <input type="email" class="form-control has-data" name="email" value="<?= $row['email'] ?>" readonly>
                </div>
              </div>

              <span class="text-info mt-4 fs-3 fw-bold ls-2"><i class="mdi mdi-contact-mail"></i> CONTACT PERSON DETAILS</span>
              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Contact Person Name</label>
                  <input type="text" class="form-control has-data" name="contact-person" value="<?= $row['contact_person'] ?>" readonly>
                </div>
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Contact Number</label>
                  <input type="text" class="form-control has-data" name="contact-person-no" value="<?= $row['contact_person_no'] ?>" readonly>
                </div>

              </div>

              <div class="form-group row">
                <div class="col-sm-6 mb-2">
                  <label class="colored-label">Address</label>
                  <input type="text" class="form-control has-data" name="contact-person-addr" value="<?= $row['contact_person_addr'] ?>" readonly>
                </div>
              </div>
              <span class="text-info fs-3 fw-bold ls-2"><i class="mdi mdi-file-document-box"></i> LOA REQUEST DETAILS</span>
              <div class="form-group row">
                <div class="col-lg-7 col-sm-12 col-lg-offset-3 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> HealthCare Provider</label>
                  <select class="form-select" name="healthcare-provider" id="healthcare-provider">
                    <option value="" selected>Select HealthCare Provider</option>
                    <?php
                    if (!empty($hcproviders)) {
                      foreach ($hcproviders as $hcprovider) :
                        if ($row['hcare_provider'] === $hcprovider['hp_id']) {
                    ?>
                          <option value="<?= $hcprovider['hp_id']; ?>" selected><?= $hcprovider['hp_name']; ?></option>
                        <?php
                        } else {
                        ?>
                          <option value="<?= $hcprovider['hp_id']; ?>"><?= $hcprovider['hp_name']; ?></option>
                    <?php
                        }
                      endforeach;
                    }
                    ?>
                  </select>
                  <em id="healthcare-provider-error" class="text-danger"></em>
                </div>
                <div class="col-lg-5 col-sm-12 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Type of LOA Request</label>
                  <select class="form-select" name="loa-request-type" id="loa-request-type" onchange="showMedServices()">
                    <option value="" selected>Select LOA Request Type</option>
                    <?php
                    if ($row['loa_request_type'] === "Consultation") :
                    ?>
                      <option value="Consultation" selected>Consultation</option>
                      <option value="Diagnostic Test">Diagnostic Test</option>
                    <?php
                    elseif ($row['loa_request_type'] === "Diagnostic Test") :
                    ?>
                      <option value="Consultation">Consultation</option>
                      <option value="Diagnostic Test" selected>Diagnostic Test</option>
                    <?php
                    endif;
                    ?>
                  </select>
                  <em id="loa-request-type-error" class="text-danger"></em>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-lg-7 col-sm-12 mb-2 <?= $row['loa_request_type'] === 'Consultation' ? 'd-none' : ''; ?>" id="med-services-div">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Select Medical Service/s</label><br>
                  <div id="med-services-wrapper">
                    <select class="form-select" multiple="multiple" id="med-services" name="med-services[]">
                      <?php
                      $selectedOptions = explode(';', $row['med_services']);
                      foreach ($costtypes as $costtype) :
                      ?>
                        <option value="<?= $costtype['ctype_id']; ?>" <?= in_array($costtype['ctype_id'], $selectedOptions) ? 'selected' : ''; ?>>
                          <?= $costtype['cost_type']; ?>
                        </option>
                      <?php
                      endforeach;
                      ?>
                    </select>
                  </div>
                  <em id="med-services-error" class="text-danger"></em>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-3 mb-2">
                  <label class="colored-label">Health Card Number</label>
                  <input type="text" class="form-control has-data" name="health-card-no" value="<?= $row['health_card_no'] ?>" readonly>
                </div>
                <div class="col-sm-5 mb-2">
                  <label class="colored-label">Requesting Company</label>
                  <input type="text" class="form-control has-data" name="requesting-company" value="<?= $row['requesting_company'] ?>" readonly>
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
                  <textarea class="form-control" name="chief-complaint" id="chief-complaint" cols="30" rows="6"><?= $row['chief_complaint'] ?></textarea>
                  <em id="chief-complaint-error" class="text-danger"></em>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-lg-6 col-sm-12 col-lg-offset-3 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Requesting Physician</label>
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
                  <div class="col-sm-12 mb-2">
                    <label class="colored-label mb-1">RX/Request from Accredited Doctor</label>
                    <div id="rx-file-wrapper">
                      <input type="file" class="dropify" name="rx-file" id="rx-file" data-height="300" data-max-file-size="3M" data-default-file="<?= base_url() ?>uploads/loa_attachments/<?= $row['rx_file'] ?>" accept=".jpg, .jpeg, .png">
                    </div>
                    <em id="rx-file-error" class="text-danger"></em>
                  </div>
                </div>
              </section>

              <div class="row mt-2">
                <div class="col-sm-12 mb-2 d-flex justify-content-start">
                  <button type="submit" class="btn btn-success">
                    <i class="mdi mdi-content-save-settings"></i> UPDATE
                  </button>
                  &nbsp;&nbsp;
                  <a href="<?= base_url() ?>super-admin/loa/requests-list" class="btn btn-danger">
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
<script>
  const baseUrl = `<?php echo base_url() ?>`;
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
    /* Checking the value of the select element and if the value is Consultation or empty, it will hide the divs. */
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
</script>