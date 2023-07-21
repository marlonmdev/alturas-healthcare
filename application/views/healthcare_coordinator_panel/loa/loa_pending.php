<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">PENDING REQUEST</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">Pending</li>
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
            <a class="nav-link active" href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list" role="tab">
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
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/disapproved" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">DISAPPROVED</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/completed" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">COMPLETED</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/rescheduled" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">REFERRAL</span>
            </a>
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
              <?php include 'charge_type.php'; ?>
            </div>
          </div>
        </div>
        <?php include 'view_pending_details.php'; ?>
      </div>
    </div>
  </div>

  <!-- Viewing Upload Reports Modal -->
  <div class="modal fade" id="viewUploadedReportsModal" tabindex="-1" data-bs-backdrop="static" style="height:100%">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4>Attached Reports</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input id="report-percentage" class="form-control" readonly>
          <div class="pt-3">
            <label class="fs-5">Uploaded Reports : <i><small class="text-danger">Click to view the file</small></i></label><br>
            <li>Spot Report : <a href="JavaScript:void(0)" data-bs-toggle="tooltip" onclick="viewSpotFile()" id="uploaded-spot-report"></a></li>
            <li>Incident Report : <a href="JavaScript:void(0)" data-bs-toggle="tooltip" onclick="viewIncidentFile()" id="uploaded-incident-report"></a></li>
            <li>Police Report : <a href="JavaScript:void(0)" data-bs-toggle="tooltip" onclick="viewPoliceFile()" id="uploaded-police-report"></a></li>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>
  <?php include 'view_pdf_file_modal.php';?>
</div>


<script>
  const baseUrl = `<?= base_url(); ?>`;
  const fileName = `<?php echo strtotime(date('Y-m-d h:i:s')); ?>`;
  $(document).ready(function() {

    let pendingTable = $('#pendingLoaTable').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}healthcare-coordinator/loa/requests-list/fetch`,
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

    $('#formUpdateChargeType').submit(function(event) {
      event.preventDefault();

      const ChargeForm = $('#formUpdateChargeType')[0];
      const formdata = new FormData(ChargeForm);
      $.ajax({
        type: "post",
        url: $(this).attr('action'),
        data: formdata,
        dataType: "json",
        processData: false,
        contentType: false,
        success: function(response) {
          const {
            token,
            status,
            message,
            charge_type_error,
          } = response;
          switch (status) {
            case 'error':
              // is-invalid class is a built in classname for errors in bootstrap
              if (charge_type_error !== '') {
                $('#charge-type-error').html(charge_type_error);
                $('#charge-type').addClass('is-invalid');
              } else {
                $('#charge-type-error').html('');
                $('#charge-type').removeClass('is-invalid');
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
              $("#pendingLoaTable").DataTable().ajax.reload();
              break;
            case 'upload-error':
              swal({
                title: 'Failed',
                text: message,
                timer: 3000,
                showConfirmButton: false,
                type: 'error'
              });
              $("#pendingLoaTable").DataTable().ajax.reload();
              break;
            case 'success':
              swal({
                title: 'Success',
                text: message,
                timer: 3000,
                showConfirmButton: false,
                type: 'success'
              });
              
              $('#viewChargeTypeModal').modal('hide');
              $("#pendingLoaTable").DataTable().ajax.reload();
              break;
          }
        },
      })
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

  function viewLoaInfo(loa_id) {
    $.ajax({
      url: `${baseUrl}healthcare-coordinator/loa/pending/view/${loa_id}`,
      type: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const {
          status,
          token,
          loa_no,
          request_date,
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
          chief_complaint,
          requesting_physician,
          attending_physician,
          rx_file,
          req_status,
          work_related,
          percentage
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
        
        let rstat = '';
        if(req_status == 'Pending'){
          req_stat = `<strong style="color:maroon">[${req_status}]</strong>`;
        }else{
          req_stat = `<strong style="color:maroon">[${req_status}]</strong>`;
        }

        $('#loa-no').html(loa_no);
        $('#loa-status').html(req_stat);
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
         if(work_related == ''){
          $('#percent_tr').hide();
         }else{
          $('#percent_tr').show();
          $('#percentage').html(wpercent+', '+nwpercent);
         }
      }
    });
  }

  const showTagChargeType = (loa_id) => {
    $("#viewChargeTypeModal").modal("show");
    $("#viewChargeTypeModal").find("form")[0].reset();
    $('#loa-id').val(loa_id);
    $('#charge-type').val('');
  }

  let pdfinput = "";
  const  previewFile = (pdf_input) => {
      pdfinput = pdf_input;
      let pdfFileInput = document.getElementById(pdf_input);
      let pdfFile = pdfFileInput.files[0];
      let reader = new FileReader();
      if(pdfFile){
          $('#viewFileModal').modal('show');
          $('#file-name-r').html('Attached File');
          $('#cancel').show();

          reader.onload = function(event) {
          let dataURL = event.target.result;
          let iframe = document.querySelector('#pdf-file-viewer');
          iframe.src = dataURL;
      };
          reader.readAsDataURL(pdfFile);
      }

  }

  const viewSpotFile = () => {
    const sport_report = document.querySelector('#uploaded-spot-report');
    const anchorText = sport_report.textContent;

    $('#viewFileModal').modal('show');
    $('#cancel').hide();
    $('#file-name-r').html('SPOT REPORT');

    let pdfFile = `${baseUrl}uploads/spot_reports/${anchorText}`;
    let fileExists = checkFileExists(pdfFile);

    if(fileExists){
    let xhr = new XMLHttpRequest();
    xhr.open('GET', pdfFile, true);
    xhr.responseType = 'blob';

    xhr.onload = function(e) {
        if (this.status == 200) {
        let blob = this.response;
        let reader = new FileReader();

        reader.onload = function(event) {
            let dataURL = event.target.result;
            let iframe = document.querySelector('#pdf-file-viewer');
            iframe.src = dataURL;
        };
        reader.readAsDataURL(blob);
        }
    };
    xhr.send();
    }
  }

  const viewIncidentFile = () => {
    const sport_report = document.querySelector('#uploaded-incident-report');
    const anchorText = sport_report.textContent;

    $('#viewFileModal').modal('show');
    $('#cancel').hide();
    $('#file-name-r').html('INCIDENT REPORT');

    let pdfFile = `${baseUrl}uploads/incident_reports/${anchorText}`;
    let fileExists = checkFileExists(pdfFile);

    if(fileExists){
    let xhr = new XMLHttpRequest();
    xhr.open('GET', pdfFile, true);
    xhr.responseType = 'blob';

    xhr.onload = function(e) {
        if (this.status == 200) {
        let blob = this.response;
        let reader = new FileReader();

        reader.onload = function(event) {
            let dataURL = event.target.result;
            let iframe = document.querySelector('#pdf-file-viewer');
            iframe.src = dataURL;
        };
        reader.readAsDataURL(blob);
        }
    };
    xhr.send();
    }
  }

  const viewPoliceFile = () => {
    const sport_report = document.querySelector('#uploaded-police-report');
    const anchorText = sport_report.textContent;

    $('#viewFileModal').modal('show');
    $('#cancel').hide();
    $('#file-name-r').html('POLICE REPORT');

    let pdfFile = `${baseUrl}uploads/police_reports/${anchorText}`;
    let fileExists = checkFileExists(pdfFile);

    if(fileExists){
    let xhr = new XMLHttpRequest();
    xhr.open('GET', pdfFile, true);
    xhr.responseType = 'blob';

    xhr.onload = function(e) {
        if (this.status == 200) {
        let blob = this.response;
        let reader = new FileReader();

        reader.onload = function(event) {
            let dataURL = event.target.result;
            let iframe = document.querySelector('#pdf-file-viewer');
            iframe.src = dataURL;
        };
        reader.readAsDataURL(blob);
        }
    };
    xhr.send();
    }
  }

    const checkFileExists = (fileUrl) => {
        let xhr = new XMLHttpRequest();
        xhr.open('HEAD', fileUrl, false);
        xhr.send();

        return xhr.status == "200" ? true: false;
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


  const cancelLoaRequest = (loa_id) => {
    $.confirm({
      title: '<strong>Confirm!</strong>',
      content: 'Are you sure to cancel LOA Request?',
      type: 'red',
      buttons: {
        confirm: {
          text: 'Yes',
          btnClass: 'btn-red',
          action: function() {
            $.ajax({
              type: 'GET',
              url: `${baseUrl}healthcare-coordinator/loa/requested-loa/cancel/${loa_id}`,
              data: {
                loa_id
              },
              dataType: "json",
              success: function(response) {
                const {
                  token,
                  status,
                  message
                } = response;
                if (status === 'success') {
                  swal({
                    title: 'Success',
                    text: message,
                    timer: 3000,
                    showConfirmButton: false,
                    type: 'success'
                  });
                  $("#pendingLoaTable").DataTable().ajax.reload();
                } else {
                  swal({
                    title: 'Failed',
                    text: message,
                    timer: 3000,
                    showConfirmButton: false,
                    type: 'error'
                  });
                  $("#pendingLoaTable").DataTable().ajax.reload();
                }
              }
            });
          }
        },
        cancel: {
          btnClass: 'btn-dark',
          action: function() {
            // close dialog
          }
        },
      }
    });
  }

  const viewReports = (loa_id, work_related, percentage, spot_report, incident_report, police_report) => {
    $('#viewUploadedReportsModal').modal('show');
    if(work_related == 'Yes'){ 
      if(percentage == ''){
        wpercent = '100% Work Related';
        nwpercent = '';
      }else{
        wpercent = percentage+'%  Work Related';
        result = 100 - parseFloat(percentage);
        if(percentage == '100'){
          nwpercent = '';
        }else{
          nwpercent = result+'% Non Work Related';
        } 
      }	
    }else if(work_related == 'No'){
      if(percentage == ''){
        wpercent = '';
        nwpercent = '100% Non Work Related';
      }else{
        nwpercent = percentage+'% Non Work Related';
        result = 100 - parseFloat(percentage);
        if(percentage == '100'){
          wpercent = '';
        }else{
          wpercent = result+'%  Work Related';
        } 
      }
    }
    $('#report-percentage').val(wpercent+', '+nwpercent);
    $('#uploaded-spot-report') .html(spot_report);
    $('#uploaded-incident-report').html(incident_report);
    $('#uploaded-police-report').html(police_report);
  }
</script>