<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">COMPLETED REQUEST</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">Completed</li>
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
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">PENDING</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/approved" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">APPROVED</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/disapproved"
              role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">DISAPPROVED</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link active" href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/completed" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">COMPLETED</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/rescheduled" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">REFERRAL</span></a
              >
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/expired" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">EXPIRED</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/cancelled" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">CANCELLED</span>
            </a>
          </li>
        </ul>

        <div class="col-lg-5 ps-5 pb-3 offset-7 pt-1 pb-4">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text bg-dark text-white"><i class="mdi mdi-filter"></i></span>
            </div>
            <select class="form-select fw-bold" name="completed-hospital-filter" id="completed-hospital-filter">
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
              <table class="table table-hover table-responsive" id="completedLoaTable">
                <thead style="background-color:#00538C">
                  <tr>
                    <th class="fw-bold" style="color: white;">LOA NO.</th>
                    <th class="fw-bold" style="color: white;">NAME OF PATIENT</th>
                    <th class="fw-bold" style="color: white;">TYPE OF REQUEST</th>
                    <th class="fw-bold" style="color: white;">HEALTHCARE PROVIDER</th>
                    <th class="fw-bold" style="color: white;">RX FILE</th>
                    <th class="fw-bold" style="color: white;">DATE OF REQUEST</th>
                    <th class="fw-bold" style="color: white;">STATUS</th>
                    <th class="fw-bold" style="color: white;width:150px">ACTION</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <?php include 'view_completed_loa_details.php'; ?>
      </div>
      <?php include 'managers_key_modal.php'; ?>
    </div>
    <?php include 'performed_loa_info_modal.php'; ?>
  </div>
  <?php include 'view_performed_consult_loa.php'; ?>
</div>



<script>
  const baseUrl = "<?php echo base_url(); ?>";
  const fileName = `<?php echo strtotime(date('Y-m-d h:i:s')); ?>`;

  $(document).ready(function() {

    let completedTable = $('#completedLoaTable').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}healthcare-coordinator/loa/requests-list/completed/fetch`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: function(data) {
            data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
            data.filter = $('#completed-hospital-filter').val();
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

    $('#completed-hospital-filter').change(function(){
      completedTable.draw();
    });

    $('#managersKeyForm').submit(function(event){
      event.preventDefault();
      $.ajax({
        type: "post",
        url: `${baseUrl}healthcare-coordinator/reschedule/managers-key/check`,
        data: $(this).serialize(),
        dataType: "json",
        success: function (res) {
            const { status, message, mgr_username_error, mgr_password_error, loa_id, company_doctor } = res;

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
              $('#managersKeyModal').modal('hide');
              window.location.href = `${baseUrl}healthcare-coordinator/loa/requested-loa/create_new_loa/${loa_id}/${company_doctor}`;
            }
        },
      });
    });

  });
  
  const showManagersKeyModal = (loa_id) => {
    $('#managersKeyModal').modal('show');
    $('#managersKeyForm')[0].reset();
    $('#mgr-username').removeClass('is-invalid');
    $('#mgr-password').removeClass('is-invalid');
    $('#mgr-username-error').html('');
    $('#mgr-password-error').html('');
    $('#msg-error').html('');
    $('#expired-loa-id').val(loa_id);
  }

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

  function viewCompletedLoaInfo(req_id) {
    $.ajax({
      url: `${baseUrl}healthcare-coordinator/loa/completed/view/${req_id}`,
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
          work_related,
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
          approved_by,
          approved_on,
          percentage
        } = res;

        $("#viewLoaModal").modal("show");

        switch (req_status) {
          case 'Pending':
            $('#loa-status').html(`<strong class="text-warning">[${req_status}]</strong>`);
            break;
          case 'Approved':
            $('#loa-status').html(`<strong class="text-success">[${req_status}]</strong>`);
            break;
          case 'Disapproved':
            $('#loa-status').html(`<strong class="text-danger">[${req_status}]</strong>`);
            break;
          case 'Closed':
            $('#loa-status').html(`<strong class="text-info">[${req_status}]</strong>`);
            break;
        }
        const med_serv = med_services !== '' ? med_services : 'None';
        const at_physician = attending_physician !== '' ? attending_physician : 'None';
        $('#loa-no').html(loa_no);
        $('#approved-by').html(approved_by);
        $('#approved-on').html(approved_on);
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

  
  const viewPerformedLoaInfo = (loa_id) => {
    $.ajax({
      url: `${baseUrl}healthcare-coordinator/loa/performed-loa-info/view/${loa_id}`,
      type: 'GET',
      dataType: 'json',
      success: function(response){
        
        $('#pfLoaInfoModal').modal('show');

        let tbody = '';
        
        $.each(response, function(index, item){
          
          tbody += '<tr><td>'+ item.item_description +'</td><td>'+ item.status + '</td><td>' + item.date_performed +' '+ item.time_performed +'</td><td>'+ item.physician_fname +' '+ item.physician_mname + ' ' + item.physician_lname +'</td><td>'+ item.reason_cancellation +'</td></tr>';

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
        
        tbody += '<tr><td>'+ response.request_type +'</td><td>'+ response.status + '</td><td>' + response.date_performed + ' '+ response.time_performed +'</td><td>'+ response.physician_fname +' ' + response.physician_mname + ' ' + response.physician_lname +'</td></tr>';

        $('#pf-consult-tbody').html(tbody);
        $('#pf-consult-loa-no').html(response.loa_no);

      }

    });
  }


 

</script>