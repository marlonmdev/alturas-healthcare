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
                  Billed LOA
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
              href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/billed"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Billed</span></a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/for-charging"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">For Charging</span></a
            >
          </li>
        </ul>
        <div class="row pt-2 pb-2">
            <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash() ?>">
            <div class="col-lg-5 ps-5 pb-3 pt-1 pb-4">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-dark text-white">
                        <i class="mdi mdi-filter"></i>
                        </span>
                    </div>
                    <select class="form-select fw-bold" name="billed-hospital-filter" id="billed-hospital-filter" oninput="enableDate()">
                            <option value="">Select Hospital</option>
                            <?php foreach($hcproviders as $option) : ?>
                            <option value="<?php echo $option['hp_id']; ?>"><?php echo $option['hp_name']; ?></option>
                            <?php endforeach; ?>
                    </select>
                </div>
            </div>

            
            <div class="col-lg-6 offset-1">
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text bg-dark text-white ls-1 ms-2">
                                <i class="mdi mdi-filter"></i>
                            </span>
                        </div>
                        <input type="date" class="form-control" name="start-date" id="start-date" oninput="validateDateRange()" placeholder="Start Date" disabled>

                        <div class="input-group-append">
                            <span class="input-group-text bg-dark text-white ls-1 ms-2">
                                <i class="mdi mdi-filter"></i>
                            </span>
                        </div>
                        <input type="date" class="form-control" name="end-date" id="end-date" oninput="validateDateRange()" placeholder="End Date" disabled>
                    </div>
                </div>
            </div>
        </div>
       
        <div class="card shadow" style="background-color:#f7e9d2">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-responsive" id="billedLoaTable">
                <thead style="background-color:#ded4c3">
                  <tr>
                    <th class="fw-bold">LOA No.</th>
                    <th class="fw-bold">Name</th>
                    <th class="fw-bold">LOA Type</th>
                    <!-- <th class="fw-bold">Healthcare Provider</th> -->
                    <th class="fw-bold">Coordinator Bill</th>
                    <th class="fw-bold">Healthcare Bill</th>
                    <th class="fw-bold">Variance</th>
                    <!-- <th class="fw-bold" style="width:150px">Actions</th> -->
                  </tr>
                </thead>
                <tbody id="billed-tbody">
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <?php include 'view_completed_loa_details.php'; ?>

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
  const baseUrl = "<?php echo base_url(); ?>";
  const fileName = `<?php echo strtotime(date('Y-m-d h:i:s')); ?>`;

  $(document).ready(function() {

    $("#start-date").flatpickr({
        dateFormat: 'Y-m-d',
    });

    $('#end-date').flatpickr({
        dateFormat: 'Y-m-d',
    });

    $('#end-date').change(function(){
        fetchBilledLoa();
    });

  });

    window.onload = (event) => {
        // fetchBilledLoa();
    }

    const fetchBilledLoa = () => {
        $.ajax({
            url: `${baseUrl}healthcare-coordinator/loa/billed/fetch`,
            type: "GET",
            dataType: 'json',
            data: function(data){
                data.filter = $('#billed-hospital-filter').val();
                data.start_date = $('#start-date').val();
                data.end_date = $('#end-date').val();
            },
            success: function(response) {
                let tbody = '';

                $.each(response, function(index, item){
                    if(item != ""){
                        tbody += '<tr><td>'+item.loa_no+'</td><td>'+item.first_name+' '+item.middle_name+' '+item.last_name+' '+item.suffix+'</td><td>'+item.loa_request_type+'</td><td>'+item.request_type+'</td><td>'+item.total_bill+'</td><td>'+item.total_deduction+'</td></tr>';
                    }else{
                        tbody += '<em>No Data Found!</em>';
                    }
                });

                $('#billed-tbody').html(tbody);
            }
        });
    }

    const enableDate = () => {
        const hp_filter = document.querySelector('#billed-hospital-filter');
        const start_date = document.querySelector('#start-date');
        const end_date = document.querySelector('#end-date');

        if(hp_filter != ''){
            start_date.removeAttribute('disabled');
            start_date.style.backgroundColor = '#ffff';
            end_date.removeAttribute('disabled');
            end_date.style.backgroundColor = '#ffff';
        }else{
            start_date.setAttribute('disabled', true);
            start_date.value = '';
            end_date.setAttribute('disabled', true);
            end_date.value = '';
        }

    }

    const validateDateRange = () => {
            const startDateInput = document.querySelector('#start-date');
            const endDateInput = document.querySelector('#end-date');
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);

            if (startDateInput.value === '' || endDateInput.value === '') {
                return; // Don't do anything if either input is empty
            }

            if (endDate < startDate) {
                // alert('End date must be greater than or equal to the start date');
                swal({
                    title: 'Failed',
                    text: 'End date must be greater than or equal to the start date',
                    // timer: 4000,
                    showConfirmButton: true,
                    type: 'error'
                });
                endDateInput.value = '';
                return;
            }          
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
          approved_on
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
        $('#work-related').html(work_related);
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