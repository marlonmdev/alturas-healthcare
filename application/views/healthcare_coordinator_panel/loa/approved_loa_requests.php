
<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">Letter of Authorization</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">
                Approved LOA
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
              class="nav-link"
              href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Pending</span></a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link active"
              href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/approved"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Approved</span></a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/disapproved"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Disapproved</span></a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/completed"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Completed</span></a
            >
          </li>
          <li class="nav-item">
              <a
              class="nav-link"
              href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/rescheduled"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Rescheduled</span></a
              >
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/expired"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Expired</span></a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/cancelled"
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
                    <th class="fw-bold">LOA No.</th>
                    <th class="fw-bold">Name</th>
                    <th class="fw-bold">LOA Type</th>
                    <th class="fw-bold">Healthcare Provider</th>
                    <th class="fw-bold">RX File</th>
                    <th class="fw-bold">Expiry Date</th>
                    <th class="fw-bold">Status</th>
                    <th class="fw-bold">Actions</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>

            </div>
          </div>
          <?php include 'loa_cancellation_modal.php'; ?>
        </div>

        <?php include 'view_approved_loa_details.php'; ?>

      </div>
     <!-- End Row  -->  
      </div>
      <?php include 'performed_loa_info_modal.php'; ?>
    <!-- End Container fluid  -->
    </div>
    <?php include 'view_performed_consult_loa.php'; ?>
  <!-- End Page wrapper  -->
  </div>
<!-- End Wrapper -->
<script>
  const baseUrl = `<?php echo base_url(); ?>`;
  const fileName = `<?php echo strtotime(date('Y-m-d h:i:s')); ?>`;

  $(document).ready(function() {

    let aprrovedTable = $('#approvedLoaTable').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}healthcare-coordinator/loa/requests-list/approved/fetch`,
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

    
    $('#approved-hospital-filter').change(function(){
      aprrovedTable.draw();
    });


    $('#loaCancellationForm').submit(function(event) {
      const nextPage = `${baseUrl}healthcare-coordinator/loa/requests-list/cancelled`;
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
            cancellation_reason_error
          } = response;
          switch (status) {
            case 'error':
              // is-invalid class is a built in classname for errors in bootstrap
              if (cancellation_reason_error !== '') {
                $('#cancellation-reason-error').html(cancellation_reason_error);
                $('#cancellation-reason').addClass('is-invalid');
              } else {
                $('#cancellation-reason-error').html('');
                $('#cancellation-reason').removeClass('is-invalid');
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
              $('#loaCancellationModal').modal('hide');
              $("#memberApprovedLoa").DataTable().ajax.reload();
              setTimeout(function() {
                window.location.href = nextPage;
              }, 3200);
              break;
          }
        }
      });
    });

  });

  function viewImage(path) {
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

  function viewApprovedLoaInfo(loa_id) {
    $.ajax({
      url: `${baseUrl}healthcare-coordinator/loa/approved/view/${loa_id}`,
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
          expiry_date,
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
        $('#work-related-val').html(work_related);
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
      }
    });
  }

  const viewPerformedLoaInfo = (loa_id) => {
    $.ajax({
      url: `${baseUrl}healthcare-coordinator/loa/performed-loa-info/view/${loa_id}`,
      type: 'GET',
      dataType: 'json',
      success: function(response){
        
        $('#pfLoaInfoModal').modal('show');

        let tbody = '';
        
        $.each(response, function(index, item){
          
          tbody += '<tr><td>'+ item.item_description +'</td><td>'+ item.status + '</td><td>' + item.date_performed +' '+ item.time_performed +'</td><td>'+ item.physician_fname +' '+ item.physician_mname + ' ' + item.physician_lname +'</td><td>'+ item.reschedule_on +'</td><td>'+ item.reason_cancellation +'</td></tr>';

          $('#pf-loa-no').html(item.loa_no);
        });
        $('#pf-tbody').html(tbody);
       
      }
    });
  }

  const viewPerformedLoaConsult = (loa_id) => {
    $.ajax({
      url: `${baseUrl}healthcare-coordinator/loa/performed-consult-loa-info/view/${loa_id}`,
      type: 'GET',
      dataType: 'json',
      success: function(response){
      
        $('#consultLoaInfoModal').modal('show');

        let tbody = '';
        
        tbody += '<tr><td>'+ response.request_type +'</td><td>'+ response.status + '</td><td>' + response.date_time_performed +'</td><td>'+ response.physician +'</td></tr>';

        $('#pf-consult-tbody').html(tbody);
        $('#pf-consult-loa-no').html(response.loa_no);

      }

    });
  }

  const loaCancellation = (loa_id, loa_no) => {
    $("#loaCancellationModal").modal("show");

    $('#cancellation-reason').val('');
    $('#cancellation-reason').removeClass('is-invalid');
    $('#cancellation-reason-error').html('');

    $('#cur-loa-id').val(loa_id);
    $('#cur-loa-no').val(loa_no);
    $("#loaCancellationForm").attr("action", `${baseUrl}healthcare-coordinator/loa/requests-list/cancel-request/${loa_id}`);
  }

</script>