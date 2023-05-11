<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">EXPIRED REQUEST</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">Expired</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <ul class="nav nav-tabs mb-4" role="tablist">
          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-coordinator/noa/requests-list" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">PENDING</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-coordinator/noa/requests-list/approved" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">APPROVED</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-coordinator/noa/requests-list/disapproved" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">DISAPPROVED</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link active" href="<?php echo base_url(); ?>healthcare-coordinator/noa/requests-list/expired" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">EXPIRED</span>
            </a>
          </li>

          <!-- <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-coordinator/noa/requests-list/completed" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">COMPLETED</span>
            </a>
          </li> -->
        </ul>

        <div class="col-lg-5 ps-5 pb-3 offset-7 pt-1 pb-4">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text bg-dark text-white"><i class="mdi mdi-filter"></i></span>
            </div>
            <select class="form-select fw-bold" name="expired-hospital-filter" id="approved-hospital-filter">
              <option value="">Select Hospital</option>
              <?php foreach($hcproviders as $option) : ?>
                <option value="<?php echo $option['hp_id']; ?>"><?php echo $option['hp_name']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="card shadow">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover" id="expiredNoaTable">
                <thead style="background-color:#00538C">
                  <tr>
                    <th class="fw-bold" style="color: white">NOA NO.</th>
                    <th class="fw-bold" style="color: white">NAME OF PATIENT</th>
                    <th class="fw-bold" style="color: white">DATE OF ADMISSION</th>
                    <th class="fw-bold" style="color: white">NAME OF HOSPITAL</th>
                    <th class="fw-bold" style="color: white">DATE OF EXPIRATION</th>
                    <th class="fw-bold" style="color: white">STATUS</th>
                    <th class="fw-bold" style="color: white">ACTION</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- View NOA Details Modal -->
        <div class="modal fade" id="viewNoaModal" tabindex="-1" data-bs-backdrop="static">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <section id="printableDiv">
                <div class="modal-header">
                  <h4 class="modal-title ls-2">NOA #: <span id="noa-no" class="text-primary"></span> <span id="noa-status"></span></h4>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                  </button>
                </div>
                <div class="modal-body">
                  <div class="container">
                    <div class="row text-center">
                      <h4><strong>PATIENT DETAILS</strong></h4>
                    </div>
                    <div class="row">
                      <table class="table table-bordered table-striped table-hover table-responsive table-sm">
                        <tr>
                          <td class="fw-bold ls-1">Requested On :</td>
                          <td class="fw-bold ls-1" id="request-date"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Approved By :</td>
                          <td class="fw-bold ls-1" id="approved-by"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Approved On :</td>
                          <td class="fw-bold ls-1" id="approved-on"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Expiry Date :</td>
                          <td class="fw-bold ls-1" id="expiry-date"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Member's Maximum Benefit Limit :</td>
                          <td class="fw-bold ls-1">&#8369;<span id="member-mbl"></span></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Member's Remaining MBL :</td>
                          <td class="fw-bold ls-1">&#8369;<span id="remaining-mbl"></span></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Percentage :</td>
                          <td class="fw-bold ls-1" id="percentage"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Full Name :</td>
                          <td class="fw-bold ls-1" id="full-name"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Date of Birth :</td>
                          <td class="fw-bold ls-1" id="date-of-birth"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Age :</td>
                          <td class="fw-bold ls-1" id="age"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Hospital :</td>
                          <td class="fw-bold ls-1" id="hospital-name"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Admission Date :</td>
                          <td class="fw-bold ls-1" id="admission-date"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Chief Complaint :</td>
                          <td class="fw-bold ls-1" id="chief-complaint"></td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
              </section>
              <div class="modal-footer">
                <button class="btn btn-dark ls-1 me-2" onclick="saveAsImage()"><i class="mdi mdi-file-image"></i> Save as Image</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <!-- End of View NOA -->

      </div>
        <?php include 'managers_key_modal.php'; ?>
      <!-- End Row  -->  
      </div>
    <!-- End Container fluid  -->
      <?php include 'back_date_modal.php'; ?>
    </div>
  <!-- End Page wrapper  -->
  </div>
<!-- End Wrapper -->
<script>
  const baseUrl = `<?php echo base_url(); ?>`;
  const fileName = `<?php echo strtotime(date('Y-m-d h:i:s')); ?>`;
  
  $(document).ready(function() {

    let expiredTable = $('#expiredNoaTable').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}healthcare-coordinator/noa/requests-list/expired/fetch`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: function(data) {
          data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
          data.filter = $('#expired-hospital-filter').val();
        }
      },

      //Set column definition initialisation properties.
      columnDefs: [{
        "targets": [5, 6], // numbering column
        "orderable": false, //set not orderable
      }, ],
      responsive: true,
      fixedHeader: true,
    });

    // Get today's date
    const today = new Date();
    // Create a new Date object representing tomorrow's date
    const tomorrow = new Date(today);
    tomorrow.setDate(today.getDate() + 1);

    $("#expiration-date").flatpickr({
      enableTime: false,
      dateFormat: 'Y-m-d',
      minDate: tomorrow
    });

    $('#expired-hospital-filter').change(function(){
      expiredTable.draw();
    });

  });


  $('#managersKeyForm').submit(function(event){
    event.preventDefault();
    $.ajax({
      type: "post",
      url: `${baseUrl}healthcare-coordinator/managers-key/check`,
      data: $(this).serialize(),
      dataType: "json",
      success: function (res) {
          const { status, message, mgr_username_error, mgr_password_error, noa_id, noa_no } = res;

          if (status == "error") {
            if (mgr_username_error !== '') {
              $('#mgr-username-error').html(mgr_username_error);
              $('#mgr-username').addClass('is-invalid');
            } else {
              $('#mgr-username-error').html('');
              $('#mgr-username').removeClass('is-invalid');
            }

            if (mgr_password_error !== '') {
              $('#mgr-password-error').html(mgr_password_error);
              $('#mgr-password').addClass('is-invalid');
            } else {
              $('#mgr-password-error').html('');
              $('#mgr-password').removeClass('is-invalid');
            }

            if (message !== '') {
              $('#msg-error').html(message);
              $('#mgr-username').addClass('is-invalid');
              $('#mgr-password').addClass('is-invalid');
            } else {
              $('#msg-error').html('');
              $('#mgr-username').removeClass('is-invalid');
              $('#mgr-password').removeClass('is-invalid');
            }

          } else {
            $("#managersKeyModal").modal("hide");
            showBackDateForm(noa_id, noa_no);
          }
      },
    });
  });

  $('#backDateForm').submit(function(event){
      event.preventDefault();
      $.ajax({
        type: "post",
        url: `${baseUrl}healthcare-coordinator/noa/requests-list/expired/backdate`,
        data: $(this).serialize(),
        dataType: "json",
        success: function (res) {
          const { status, message } = res;

          switch (status) {
            case 'error':
              // is-invalid class is a built in classname for errors in bootstrap
              if (expiry_date_error !== '') {
                $('#expiry-date-error').html(expiry_date_error);
                $('#expiry-date').addClass('is-invalid');
              } else {
                $('#expiry-date-error').html('');
                $('#expiry-date').removeClass('is-invalid');
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
              
              $("#backDateModal").modal("hide");
              $("#expiredNoaTable").DataTable().ajax.reload();
              break;
          }
        },
      });              
  });

  const showBackDateForm = (noa_id, noa_no) => {
    $("#backDateModal").modal("show");
    $('#bd-noa-id').val(noa_id);
    $('#bd-noa-no').val(noa_no);
  }

  const backDate = (noa_id, noa_no) => {
    $('#managersKeyModal').modal('show');
    $('#expired-noa-id').val(noa_id);
    $('#expired-noa-no').val(noa_no);
    $('#mgr-username').val('');
    $('#mgr-username').removeClass('is-invalid');
    $('#mgr-username-error').html('');
    $('#mgr-password').val('');
    $('#mgr-password').removeClass('is-invalid');
    $('#mgr-password-error').html('');
  }

  const saveAsImage = () => {
    // Get the div element you want to save as an image
    const element = document.querySelector("#printableDiv");
    // Use html2canvas to take a screenshot of the element
    html2canvas(element)
      .then(function(canvas) {
        // Convert the canvas to an image data URL
        const imgData = canvas.toDataURL("image/png");
        // Create a temporary link element to download the image
        const link = document.createElement("a");
        link.download = `noa_${fileName}.png`;
        link.href = imgData;

        // Click the link to download the image
        link.click();
      });
  }


  const viewExpiredNoaInfo = (req_id) => {
    $.ajax({
      url: `${baseUrl}healthcare-coordinator/noa/expired/view/${req_id}`,
      type: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const base_url = window.location.origin;
        const {
          status,
          token,
          noa_no,
          approved_by,
          approved_on,
          expiry_date,
          member_mbl,
          remaining_mbl,
          first_name,
          middle_name,
          last_name,
          suffix,
          date_of_birth,
          age,
          hospital_name,
          health_card_no,
          requesting_company,
          admission_date,
          chief_complaint,
          work_related,
          request_date,
          req_status,
          percentage
        } = res;

        $("#viewNoaModal").modal("show");
        switch (req_status) {
          case 'Pending':
            $('#noa-status').html('<strong class="text-warning">[' + req_status + ']</strong>');
            break;
          case 'Approved':
            $('#noa-status').html('<strong class="text-success">[' + req_status + ']</strong>');
            break;
          case 'Disapproved':
            $('#noa-status').html('<strong class="text-danger">[' + req_status + ']</strong>');
            break;
          case 'Expired':
            $('#noa-status').html('<strong class="text-danger">[' + req_status + ']</strong>');
            break;
        }
        $('#noa-no').html(noa_no);
        $('#approved-by').html(approved_by);
        $('#approved-on').html(approved_on);
        $('#expiry-date').html(expiry_date);
        $('#member-mbl').html(member_mbl);
        $('#remaining-mbl').html(remaining_mbl);
        $('#full-name').html(`${first_name} ${middle_name} ${last_name} ${suffix}`);
        $('#date-of-birth').html(date_of_birth);
        $('#age').html(age);
        $('#hospital-name').html(hospital_name);
        $('#admission-date').html(admission_date);
        $('#chief-complaint').html(chief_complaint);
        $('#request-date').html(request_date);
        if(work_related == 'Yes'){ 
					if(percentage == ''){
					  wpercent = '100% W-R';
					  nwpercent = '';
					}else{
					   wpercent = percentage+'%  W-R';
					   result = 100 - parseFloat(percentage);
					   if(percentage == '100'){
						   nwpercent = '';
					   }else{
						   nwpercent = result+'% Non W-R';
					   }
					  
					}	
			   }else if(work_related == 'No'){
				   if(percentage == ''){
					   wpercent = '';
					   nwpercent = '100% Non W-R';
					}else{
					   nwpercent = percentage+'% Non W-R';
					   result = 100 - parseFloat(percentage);
					   if(percentage == '100'){
						   wpercent = '';
					   }else{
						   wpercent = result+'%  W-R';
					   }
					 
					}
			   }
        $('#percentage').html(wpercent+', '+nwpercent);
      }
    });
  }
</script>