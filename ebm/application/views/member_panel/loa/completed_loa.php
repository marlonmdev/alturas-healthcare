<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">COMPLETED REQUEST</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Member</li>
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
      <nav class="navbar navbar-expand-md navbar-light">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url(); ?>member/requested-loa/pending" role="tab">
                  <span class="hidden-sm-up"></span>
                  <span class="hidden-xs-down fs-5 text-info font-bold">| PENDING</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url(); ?>member/requested-loa/approved" role="tab">
                  <span class="hidden-sm-up"></span>
                  <span class="hidden-xs-down fs-5 text-info font-bold">| APPROVED</span>
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url(); ?>member/requested-loa/disapproved" role="tab">
                  <span class="hidden-sm-up"></span>
                  <span class="hidden-xs-down fs-5 text-info font-bold">| DISAPPROVED</span>
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link active" href="<?php echo base_url(); ?>member/requested-loa/completed" role="tab">
                  <span class="hidden-sm-up"></span>
                  <span class="hidden-xs-down fs-5 font-bold">| COMPLETED</span>
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url(); ?>member/requested-loa/expired" role="tab">
                  <span class="hidden-sm-up"></span>
                  <span class="hidden-xs-down fs-5 text-info font-bold">| EXPIRED</span>
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url(); ?>member/requested-loa/cancelled" role="tab">
                  <span class="hidden-sm-up"></span>
                  <span class="hidden-xs-down fs-5 text-info font-bold">| CANCELLED</span>
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url(); ?>member/requested-loa/billed" role="tab">
                  <span class="hidden-sm-up"></span>
                  <span class="hidden-xs-down fs-5 text-info font-bold">| BILLED</span>
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url(); ?>member/requested-loa/paid" role="tab">
                  <span class="hidden-sm-up"></span>
                  <span class="hidden-xs-down fs-5 text-info font-bold">| PAID</span>
                </a>
              </li>
            </ul>
          </div>
        </nav>


        <div class="card shadow">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover" id="memberCompletedLoa">
                <thead style="background-color:#00538C">
                  <tr>
                    <th class="fw-bold" style="color: white">LOA NO.</th>
                    <th class="fw-bold" style="color: white">HEALTHCARE PROVIDER</th>
                    <th class="fw-bold" style="color: white">TYPE OF REQUEST</th>
                    <th class="fw-bold" style="color: white">DATE OF REQUEST</th>
                    <th class="fw-bold" style="color: white">RX FILE</th>
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

      </div>
      <?php include 'view_completed_loa_details.php'; ?>
    </div>
  </div>
</div>


<script>
  const baseUrl = `<?php echo base_url(); ?>`;
  const fileName = `<?php echo strtotime(date('Y-m-d h:i:s')); ?>`;

  $(document).ready(function() {

    $('#memberCompletedLoa').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}member/requested-loa/completed/fetch`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: {
          'token': '<?php echo $this->security->get_csrf_hash(); ?>'
        }
      },

      //Set column definition initialisation properties.
      columnDefs: [{
        // "targets": [5, 6, 7], // numbering column
        "targets": [4, 5, 6],
        "orderable": false, //set not orderable
      }, ],
      responsive: true,
      fixedHeader: true,
    });

  });

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

  const viewCompletedLoaInfo = (req_id) => {
    $.ajax({
      url: `${baseUrl}member/requested-loa/view/${req_id}`,
      type: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const base_url = window.location.origin;
        const {
          status,
          token,
          loa_no,
          approved_by,
          approved_on,
          expiry_date,
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
          percentage
        } = res;

        $("#viewLoaModal").modal("show");

        const med_serv = med_services !== '' ? med_services : 'None';
        const at_physician = attending_physician !== '' ? attending_physician : 'None';

        $('#loa-no').html(loa_no);
        $('#loa-status').html(`<strong class="text-info">[${req_status}]</strong>`);
        $('#approved-by').html(approved_by);
        $('#approved-on').html(approved_on);
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
</script>