<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">Letter of Authorization</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Super Admin</li>
              <li class="breadcrumb-item active" aria-current="page">Billed LOA</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row">

      <div class="col-12">
        <ul class="nav nav-tabs mb-4" role="tablist">
          <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>super-admin/loa/requests-list"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Pending</span></a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>super-admin/loa/requests-list/approved"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Approved</span></a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>super-admin/loa/requests-list/disapproved"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Disapproved</span></a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>super-admin/loa/requests-list/completed"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Completed</span></a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>super-admin/loa/requests-list/cancelled"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Cancelled</span></a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>super-admin/loa/requests-list/expired"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Expired</span></a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link active"
              href="<?php echo base_url(); ?>super-admin/loa/requests-list/billed"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Billed</span></a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>super-admin/loa/requests-list/paid"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Paid</span></a
            >
          </li>
        </ul>

        <div class="col-lg-5 ps-5 pb-3 offset-7 pt-1 pb-4">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text bg-dark text-white"><i class="mdi mdi-filter"></i></span>
            </div>
            <select class="form-select fw-bold" name="billed-hospital-filter" id="billed-hospital-filter">
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
              <table class="table table-hover table-responsive" id="billedLoaTable">
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

        <?php include 'view_approved_loa_details.php'; ?>

      </div>
    </div>
  </div>
</div>
<script>
  const baseUrl = "<?php echo base_url(); ?>";
  const fileName = `<?php echo strtotime(date('Y-m-d h:i:s')); ?>`;

  $(document).ready(function() {
    let billedTable = $('#billedLoaTable').DataTable({
      processing: true,
      serverSide: true,
      order: [],

      ajax: {
        url: `${baseUrl}super-admin/loa/requests-list/billed/fetch`,
        type: "POST",
        data: function(data) {
          data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
          data.filter = $('#billed-hospital-filter').val();
        }
      },

      columnDefs: [{
        "targets": [4, 6, 7],
        "orderable": false,
      }, ],
      responsive: true,
      fixedHeader: true,
    });

    $('#billed-hospital-filter').change(function(){
      billedTable.draw();
    });

  });

  const viewImage = (path) => {
    let item = [{
      src: path,
      title: 'Attached RX File'
    }];
    let options = {
      index: 0
    };
    let photoviewer = new PhotoViewer(item, options);
  }

  const saveAsImage = () => {
    const element = document.querySelector("#printableDiv");
    html2canvas(element)
    .then(function(canvas) {
      const imgData = canvas.toDataURL("image/png");
      const link = document.createElement("a");
      link.download = `loa_${fileName}.png`;
      link.href = imgData;
      link.click();
    });
  }


    const viewBilledLoaInfo = (req_id) => {
    $.ajax({
      url: `${baseUrl}super-admin/loa/billed/view/${req_id}`,
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
          percentage,
          approved_by,
          approved_on,
          billed_on
        } = res;

        $("#viewLoaModal").modal("show");

        const med_serv = med_services !== '' ? med_services : 'None';
        const at_physician = attending_physician !== '' ? attending_physician : 'None';

        $('#loa-no').html(loa_no);
        $('#loa-status').html(`<strong class="text-success">[${req_status}]</strong>`);
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
        $('#billed-on').html(billed_on);
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
