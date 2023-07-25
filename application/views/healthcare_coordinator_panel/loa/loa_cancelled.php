<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">CANCELLED REQUEST</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">Cancelled</li>
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
          <li class="nav-item1">
            <a class="nav-link1" href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold"><i class="pending mdi mdi-dots-horizontal"></i> PENDING</span>
            </a>
          </li>

          <li class="nav-item1">
            <a class="nav-link1" href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/approved" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold"><i class="approved mdi mdi-thumb-up"></i> APPROVED</span>
            </a>
          </li>

          <li class="nav-item1">
            <a class="nav-link1" href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/disapproved" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold"><i class="disapproved mdi mdi-thumb-down"></i> DISAPPROVED</span>
            </a>
          </li>

          <li class="nav-item1">
            <a class="nav-link1" href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/completed" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold"><i class="completed mdi mdi-check"></i> COMPLETED</span>
            </a>
          </li>

          <li class="nav-item1">
            <a class="nav-link1" href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/rescheduled" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold"><i class="referral mdi mdi-file-document-box"></i> REFERRAL</span>
            </a>
          </li>

          <li class="nav-item1">
            <a class="nav-link1" href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/expired" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold"><i class="expired mdi mdi-calendar-clock"></i> EXPIRED</span>
            </a>
          </li>

          <li class="nav-item1">
            <a class="nav-link1 active" href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/cancelled" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold"><i class="cancelled mdi mdi-close"></i> CANCELLED</span>
            </a>
          </li>
        </ul>

        <div class="col-lg-5 ps-5 pb-3 offset-7 pt-1 pb-4">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text bg-dark text-white"><i class="mdi mdi-filter"></i></span>
            </div>
            <select class="form-select fw-bold" name="cancelled-hospital-filter" id="cancelled-hospital-filter">
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
              <table class="table table-hover table-responsive" id="cancelledLoaTable">
                <thead style="background-color:#00538C">
                  <tr>
                    <th class="fw-bold" style="color: white;">LOA NO.</th>
                    <th class="fw-bold" style="color: white;">NAME OF PATIENT</th>
                    <th class="fw-bold" style="color: white;">TYPE OF REQUEST</th>
                    <th class="fw-bold" style="color: white;">HEALTHCARE PROVIDER</th>
                    <th class="fw-bold" style="color: white;">RX FILE</th>
                    <th class="fw-bold" style="color: white;">DATE OF REQUEST</th>
                    <th class="fw-bold" style="color: white;">STATUS</th>
                    <th class="fw-bold" style="color: white;">ACTION</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <?php include 'view_cancelled_loa_details.php'; ?>
        </div>
      </div>
    </div>
  </div>
</div>



<script>
  const baseUrl = `<?php echo base_url(); ?>`;
  const fileName = `<?php echo strtotime(date('Y-m-d h:i:s')); ?>`;

  $(document).ready(function() {

    let cancelledTable = $('#cancelledLoaTable').DataTable({
            processing: true, //Feature control the processing indicator.
            serverSide: true, //Feature control DataTables' server-side processing mode.
            order: [], //Initial no order.

            // Load data for the table's content from an Ajax source
            ajax: {
                url: `${baseUrl}healthcare-coordinator/loa/requests-list/cancelled/fetch`,
                type: "POST",
                // passing the token as data so that requests will be allowed
                data: function(data) {
                    data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
                    data.filter = $('#cancelled-hospital-filter').val();
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

        $('#cancelled-hospital-filter').change(function(){
            cancelledTable.draw();
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

    function viewCancelledLoaInfo(req_id) {
        $.ajax({
            url: `${baseUrl}healthcare-coordinator/loa/cancelled/view/${req_id}`,
            type: "GET",
            success: function(response) {
                const res = JSON.parse(response);
                const base_url = window.location.origin;
                const {
                  status,
                  token,
                  loa_no,
                  request_date,
                  cancelled_by,
                  cancelled_on,
                  cancellation_reason,
                  member_mbl,
                  remaining_mbl,
                  work_related,
                  health_card_no,
                  first_name,
                  middle_name,
                  last_name,
                  suffix,
                  date_of_birth,
                  age,
                  gender,
                  blood_type,
                  philhealth_no,
                  home_address,
                  city_address,
                  contact_no,
                  email,
                  contact_person,
                  contact_person_addr,
                  contact_person_no,
                  healthcare_provider,
                  loa_request_type,
                  med_services,
                  requesting_company,
                  chief_complaint,
                  requesting_physician,
                  attending_physician,
                  rx_file,
                  req_status,
                  percentage,
                  approved_on,
                  approved_by  
                } = res;

                $("#viewLoaModal").modal("show");

                const dob = date_of_birth !== '' ? date_of_birth : 'None';
                const ag = age !== '' ? age : 'None';
                const gndr = gender !== '' ? gender : 'None';
                const bt = blood_type !== '' ? blood_type : 'None';
                const pn = philhealth_no !== '' ? philhealth_no : 'None';
                const ha = home_address !== '' ? home_address : 'None';
                const ca = city_address !== '' ? city_address : 'None';
                const cn = contact_no !== '' ? contact_no : 'None';
                const em = email !== '' ? email : 'None';
                const cp = contact_person !== '' ? contact_person : 'None';
                const cpa = contact_person_addr !== '' ? contact_person_addr : 'None';
                const cpn = contact_person_no !== '' ? contact_person_no : 'None';
                const med_serv = med_services !== '' ? med_services : 'None';
                const at_physician = attending_physician !== '' ? attending_physician : 'None';
                $('#loa-no').html(loa_no);
                $('#loa-status').html(`<strong style="color:maroon">[${req_status}]</strong>`);
                $('#cancelled-by').html(cancelled_by);
                $('#cancelled-on').html(cancelled_on);
                $('#cancellation-reason').html(`<strong class="text-danger">${cancellation_reason}</strong>`);
                $('#member-mbl').html(member_mbl);
                $('#remaining-mbl').html(remaining_mbl);
                $('#full-name').html(`${first_name} ${middle_name} ${last_name} ${suffix}`);
                $('#date-of-birth').html(dob);
                $('#age').html(ag);
                $('#gender').html(gndr);
                $('#philhealth-no').html(pn);
                $('#blood-type').html(bt);
                $('#contact-no').html(cn);
                $('#home-address').html(ha);
                $('#city-address').html(ca);
                $('#email').html(em);
                $('#contact-person').html(cp);
                $('#contact-person-addr').html(cpa);
                $('#contact-person-no').html(cpn);
                $('#healthcare-provider').html(healthcare_provider);
                $('#loa-request-type').html(loa_request_type);
                $('#loa-med-services').html(med_serv);
                $('#health-card-no').html(health_card_no);
                $('#requesting-company').html(requesting_company);
                $('#request-date').html(request_date);
                $('#chief-complaint').html(chief_complaint);
                $('#requesting-physician').html(requesting_physician);
                $('#attending-physician').html(at_physician);
                $('#work_related').html(work_related);
                $('#approved_on').html(approved_on);
                $('#approved_by').html(approved_by);
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

<style>
  .nav-item1 {
    list-style-type: none;
  }

  .nav-link1 {
    display: inline-block;
    padding: 10px;
    padding-top:1px;
    padding-bottom:1px;
    text-decoration: none;
    background-color: #e6e6e6;
    color: #000;
    border: 1px solid #000;
    border-bottom: 3px solid #000;
    border-radius: 15px;
  }

  .nav-link1:hover {
    background-color: #002244;
    color: #fff;
    border: 1px solid #000;
  }

  .font-bold {
    font-weight: bold;
  }

  .hidden-xs-down {
    display: inline-block;
  }

  .fs-5 {
    font-size: 1.2rem;
  }
  .pending{
    color:red
  }
  .approved{
    color:green
  }
  .disapproved{
    color:red
  }
  .completed{
    color:green
  }
  .referral{
    color:orange
  }
  .expired{
    color:#a32cc4
  }
  .cancelled{
    color:red
  }
</style>
