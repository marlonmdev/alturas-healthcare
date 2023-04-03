
<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">LOA Requests</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Company Doctor</li>
              <li class="breadcrumb-item active" aria-current="page">
                Pending LOA
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

        <ul class="nav nav-tabs mb-4" role="tablist">
          <li class="nav-item">
            <a
              class="nav-link active"
              href="<?php echo base_url(); ?>company-doctor/loa/requests-list"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Pending</span></a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>company-doctor/loa/requests-list/approved"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Approved</span></a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>company-doctor/loa/requests-list/disapproved"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Disapproved</span></a
            >
          </li>
            <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>company-doctor/loa/requests-list/completed"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Completed</span></a
            >
          </li>
          <!-- </li>
            <li class="nav-item">
            <a
              class="nav-link"
              href="< echo base_url(); ?>company-doctor/loa/requests-list/expired"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Expired</span>
            </a>
          </li> -->
          <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>company-doctor/loa/requests-list/cancelled"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Cancelled</span></a
            >
          </li>
        </ul>

        <div class="col-lg-5 ps-5 pb-3 offset-7 pt-1 pb-4">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-dark text-white">
                    <i class="mdi mdi-filter"></i>
                    </span>
                </div>
                <select class="form-select fw-bold" name="pending-hospital-filter" id="pending-hospital-filter">
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
              <table class="table table-hover table-responsive" id="pendingLoaTable">
                <thead>
                  <tr>
                    <th class="fw-bold">LOA No.</th>
                    <th class="fw-bold">Name</th>
                    <th class="fw-bold">LOA Type</th>
                    <th class="fw-bold">Healthcare Provider</th>
                    <th class="fw-bold">RX File</th>
                    <th class="fw-bold">Request Date</th>
                    <th class="fw-bold">Status</th>
                    <th class="fw-bold">Actions</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>  
            </div>
          </div>
        </div>

        <?php include 'view_loa_details.php'; ?>
        <?php include 'loa_disapprove_reason.php'; ?>
       <!-- End Row  -->  
      </div>
    <!-- End Container fluid  -->
    <?php include 'loa_approval_modal.php'; ?>
    </div>
  <!-- End Page wrapper  -->
  </div>
<!-- End Wrapper -->
<script>
  const baseUrl = `<?php echo base_url(); ?>`;
  const fileName = `<?php echo strtotime(date('Y-m-d h:i:s')); ?>`;

  $(document).ready(function() {

    let pendingTable =  $('#pendingLoaTable').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}company-doctor/loa/requests-list/fetch`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: function(data) {
          data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
          data.filter = $('#pending-hospital-filter').val();
        }
      },

      //Set column definition initialisation properties.
      columnDefs: [{
        "targets": [4, 6, 7], // numbering column
        "orderable": false, //set not orderable
      }, ],
      responsive: true,
      fixedHeader: true,
    });

    $('#pending-hospital-filter').change(function(){
      pendingTable.draw();
    });

    // Get today's date
    const today = new Date();
    // Create a new Date object representing tomorrow's date
    const tomorrow = new Date(today);
    tomorrow.setDate(today.getDate() + 1);

    $("#expiration-date").flatpickr({
      // dateFormat: 'm-d-Y',
      minDate: tomorrow
    });

  });

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
        link.download = `loa_${fileName}.png`;
        link.href = imgData;

        // Click the link to download the image
        link.click();
      });
  }

  const viewImage = (path) => {
    let item = [{
      src: path, // path to image
      title: 'Attached RX File' // If you skip it, there will display the original image name
    }];
    // define options (if needed)
    let options = {
      index: 0 // this option means you will start at first image
    };
    // Initialize the plugin
    let photoviewer = new PhotoViewer(item, options);
  }

  const viewPendingLoaInfo = (req_id) => {
    $.ajax({
      url: `${baseUrl}company-doctor/loa/requests-list/view/${req_id}`,
      type: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const base_url = window.location.origin;
        const {
          status,
          token,
          loa_no,
          first_name,
          middle_name,
          last_name,
          suffix,
          date_of_birth,
          age,
          gender,
          philhealth_no,
          blood_type,
          contact_no,
          home_address,
          city_address,
          email,
          contact_person,
          contact_person_addr,
          contact_person_no,
          healthcare_provider,
          loa_request_type,
          med_services,
          health_card_no,
          requesting_company,
          request_date,
          chief_complaint,
          requesting_physician,
          attending_physician,
          rx_file,
          work_related,
          percentage,
          availment_date,
          req_status,
          member_mbl,
          remaining_mbl
        } = res;

        $("#viewLoaModal").modal("show");
        
        let rstat = '';
        if(req_status == 'Pending'){
          req_stat = `<strong class="text-warning">[${req_status}]</strong>`;
        }else{
          req_stat = `<strong class="text-cyan">[${req_status}]</strong>`;
        }

        const med_serv = med_services !== '' ? med_services : 'None';
        const at_physician = attending_physician !== '' ? attending_physician : 'None';

        $('#loa-no').html(loa_no);
        $('#loa-status').html(req_stat);
        $('#member-mbl').html(member_mbl);
        $('#remaining-mbl').html(remaining_mbl);
        $('#full-name').html(`${first_name} ${middle_name} ${last_name} ${suffix}`);
        $('#date-of-birth').html(date_of_birth);
        $('#age').html(age);
        $('#gender').html(gender);
        $('#philhealth-no').html(philhealth_no);
        $('#blood-type').html(blood_type);
        $('#contact-no').html(contact_no);
        $('#home-address').html(home_address);
        $('#city-address').html(city_address);
        $('#email').html(email);
        $('#contact-person').html(contact_person);
        $('#contact-person-addr').html(contact_person_addr);
        $('#contact-person-no').html(contact_person_no);
        $('#healthcare-provider').html(healthcare_provider);
        $('#loa-request-type').html(loa_request_type);
        $('#loa-med-services').html(med_serv);
        $('#health-card-no').html(health_card_no);
        $('#requesting-company').html(requesting_company);
        $('#request-date').html(request_date);
        $('#chief-complaint').html(chief_complaint);
        $('#requesting-physician').html(requesting_physician);
        $('#attending-physician').html(at_physician);
        $('#percentage').html(percentage);
        if(work_related != ''){
          $('#work-related-info').removeClass('d-none');
          $('#work-related-val').html(work_related);
          
        }else{
          $('#work-related-info').addClass('d-none');
          $('#work-related-val').html('');
        }
      }
    });
  }

  const showExpDateInput = () => {
    const exp_type = $('#expiration-type').val();
    if(exp_type === 'custom'){
      $('#exp-date-div').removeClass('d-none');
    }else{
      $('#exp-date-div').addClass('d-none');
    }
  }

  // $.confirm is a syntax of Jquery Confirm plugin
  const approveLoaRequest = (loa_id) => {
    const nextPage = `${baseUrl}company-doctor/loa/requests-list/approved`;

    $('#loaApprovalModal').modal('show');
    $('#appr-loa-id').val(loa_id);

  }

  const disapproveLoaRequest = (loa_id) => {
    $('#loaDisapprovedReasonModal').modal('show');
    $('#disapprove-reason-error').html('');
    $('#disapprove-reason').removeClass('is-invalid');
    $("#loaDisapproveForm").attr("action", `${baseUrl}company-doctor/loa/requests-list/disapprove/${loa_id}`);
    $('#loa-id').val(loa_id);
  }

  $(document).ready(function() {

    $('#loaApproveForm').submit(function(event) {
      const nextPage = `${baseUrl}company-doctor/loa/requests-list/approved`;
      event.preventDefault();

      $.ajax({
        type: "post",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType: "json",
        success: function(response) {
          const {
            token,
            status,
            message,
            expiration_date_error
          } = response;
          switch (status) {
            case 'error':
              // is-invalid class is a built in classname for errors in bootstrap
              if (expiration_date_error !== '') {
                $('#expiration-date-error').html(expiration_date_error);
                $('#expiration-date').addClass('is-invalid');
              } else {
                $('#expiration-date-error').html('');
                $('#expiration-date').removeClass('is-invalid');
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
              
              $('#loaApprovalModal').modal('hide');
              setTimeout(function() {
                window.location.href = nextPage;
              }, 3200);
              break;
          }
        }
      });
    });


    $('#loaDisapproveForm').submit(function(event) {
      const nextPage = `${baseUrl}company-doctor/loa/requests-list/disapproved`;
      event.preventDefault();
      $.ajax({
        type: "post",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType: "json",
        success: function(response) {
          const {
            token,
            status,
            message,
            disapprove_reason_error
          } = response;
          switch (status) {
            case 'error':
              // is-invalid class is a built in classname for errors in bootstrap
              if (disapprove_reason_error !== '') {
                $('#disapprove-reason-error').html(disapprove_reason_error);
                $('#disapprove-reason').addClass('is-invalid');
              } else {
                $('#disapprove-reason-error').html('');
                $('#disapprove-reason').removeClass('is-invalid');
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
              $('#loaDisapprovedReasonModal').modal('hide');
              setTimeout(function() {
                window.location.href = nextPage;
              }, 3200);
              break;
          }
        }
      });
    });

  });
</script>