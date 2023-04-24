<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">APPROVED REQUEST</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Company Doctor</li>
              <li class="breadcrumb-item active" aria-current="page">Approved LOA</li>
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
            <a class="nav-link" href="<?php echo base_url(); ?>company-doctor/loa/requests-list" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">PENDING</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link active" href="<?php echo base_url(); ?>company-doctor/loa/requests-list/approved" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">APPROVED</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>company-doctor/loa/requests-list/disapproved" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">DISAPPROVED</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>company-doctor/loa/requests-list/completed" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">COMPLETED</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link"  href="<?php echo base_url(); ?>company-doctor/loa/requests-list/referral" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">REFERRAL</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>company-doctor/loa/requests-list/expired" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">EXPIRED</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>company-doctor/loa/requests-list/cancelled" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">CANCELLED</span>
            </a>
          </li>
        </ul>

        <div class="col-lg-5 ps-5 pb-3 offset-7 pt-1 pb-4">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text bg-dark text-white">
                <i class="mdi mdi-filter"></i>
              </span>
            </div>
            <select class="form-select fw-bold" name="approved-hospital-filter" id="approved-hospital-filter">
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
              <table class="table table-hover table-responsive" id="approvedLoaTable">
                <thead>
                  <tr>
                    <th class="fw-bold">LOA NO.</th>
                    <th class="fw-bold">NAME OF PATIENT</th>
                    <th class="fw-bold">TYPE OF REQUEST</th>
                    <th class="fw-bold">HEALTHCARE PROVIDER</th>
                    <th class="fw-bold">RX FILE</th>
                    <th class="fw-bold">DATE OF EXPIRATION</th>
                    <th class="fw-bold">STATUS</th>
                    <th class="fw-bold">ACTION</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>  
            </div>
          </div>
          <?php include 'back_date_modal.php'; ?>
        </div>
        <?php include 'view_approved_loa_details.php'; ?>
      </div>
    </div>
  </div>
</div>

<script>
  const baseUrl = `<?php echo base_url(); ?>`;
  const fileName = `<?php echo strtotime(date('Y-m-d h:i:s')); ?>`;

  $(document).ready(function() {
    let approvedTable = $('#approvedLoaTable').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}company-doctor/loa/requests-list/approved/fetch`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: function(data) {
          data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
          data.filter = $('#approved-hospital-filter').val();
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

    // Get today's date
    const today = new Date();
    // Create a new Date object representing tomorrow's date
    const tomorrow = new Date(today);
    tomorrow.setDate(today.getDate());

    $("#expiry-date").flatpickr({
      enableTime: false,
      dateFormat: 'Y-m-d',
      minDate: tomorrow
    });

    $('#approved-hospital-filter').change(function(){
      approvedTable.draw();
    });


    $('#backDateForm').submit(function(event){
      event.preventDefault();
      $.ajax({
        type: "post",
        url: `${baseUrl}company-doctor/loa/requests-list/expired/backdate_expired`,
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
              $("#expiredLoaTable").DataTable().ajax.reload();
            break;
          }
        },
      });
    });
  });

  const showBackDateForm = (loa_id, loa_no,expiry_date) => {
    $("#backDateModal").modal("show");
    $('#bd-loa-id').val(loa_id);
    $('#bd-loa-no').val(loa_no);
    $('#loa-no').html(loa_no);
    $('#expired-on').val(expiry_date);
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


  const viewApprovedLoaInfo = (req_id) => {
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
          member_mbl,
          remaining_mbl,
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
          req_status,
          work_related,
          approved_by,
          approved_on,
          expiry_date
        } = res;

        $("#viewLoaModal").modal("show");

        const med_serv = med_services !== '' ? med_services : 'None';
        const at_physician = attending_physician !== '' ? attending_physician : 'None';
        
        $('#loa-no').html(loa_no);
        $('#loa-status').html(`<strong class="text-success">[${req_status}]</strong>`);
        $('#approved-by').html(approved_by);
        $('#approved-on').html(approved_on);
        $('#expiry-date').html(expiry_date);
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
        $('#work-related-val').html(work_related);
      }
    });
  }

  // const dateValidity = () => {
  //   const expire_on = document.querySelectorAll('.expired-on'); 
  //   const date_performed = document.querySelectorAll('.input-date');

  //   for (let i = 0; i < date_performed.length; i++){
  //     const date_performance = new Date(date_performed[i].value);
  //     const expired_date = new Date(expire_on[i].value);
  //     const max_valid_date = new Date(expired_date.getTime() + 14 * 24 * 60 * 60 * 1000); // 14 days after expired_date

  //     if(date_performance < expired_date || date_performance > max_valid_date){
  //       swal({
  //         title: 'Invalid Date',
  //         text: 'It must be within 14 days after Expiration Date ['+ expire_on[i].value +']',
  //         showConfirmButton: true,
  //         type: 'error'
  //       });
  //       date_performed[i].value = '';
  //       flatpickr(date_performed[i]).close();
  //       return;
  //     }
  //   }
  // }

  //Trappings for Date Picker
  const dateValidity = () => {
    const expire_on = document.querySelectorAll('.expired-on'); 
    const date_performed = document.querySelectorAll('.input-date');
    const current_date = new Date();

    for (let i = 0; i < date_performed.length; i++){
      const date_performance = new Date(date_performed[i].value);
      const expired_date = new Date(expire_on[i].value);
      const min_valid_date = new Date(current_date.getTime() - 1 * 24 * 60 * 60 * 1000); // 1 day before current_date
      const max_valid_date = new Date(expired_date.getTime() + 14 * 24 * 60 * 60 * 1000); // 14 days after expired_date

      if(date_performance < min_valid_date || date_performance > max_valid_date){
        swal({
          title: 'Invalid Date',
          // text: 'It must be between '+ min_valid_date.toDateString() +' and '+ max_valid_date.toDateString(),
          text: 'It must be between '+ current_date.toDateString() +' and '+ max_valid_date.toDateString(),
          showConfirmButton: true,
          type: 'error'
        });
        date_performed[i].value = '';
        flatpickr(date_performed[i]).close();
        return;
      }
    }
  }
  //end
</script>