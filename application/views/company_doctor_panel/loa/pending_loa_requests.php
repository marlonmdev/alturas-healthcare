<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2"><i class="mdi mdi-file-multiple"></i> PENDING REQUEST</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Company Doctor</li>
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
            <a class="nav-link active" href="<?php echo base_url(); ?>company-doctor/loa/requests-list" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">PENDING</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>company-doctor/loa/requests-list/approved" role="tab">
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
            <a class="nav-link"  href="<?php echo base_url(); ?>company-doctor/loa/requests-list/completed" role="tab">
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
          
          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>company-doctor/loa/requests-list/billed" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">BILLED</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>company-doctor/loa/requests-list/paid" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">PAID</span>
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
                    <th class="fw-bold" style="color: white">LOA NO.</th>
                    <th class="fw-bold" style="color: white">NAME OF PATIENT</th>
                    <th class="fw-bold" style="color: white">TYPE OF REQUEST</th>
                    <th class="fw-bold" style="color: white">HEALTHCARE PROVIDER</th>
                    <th class="fw-bold" style="color: white">RX FILE</th>
                    <th class="fw-bold" style="color: white">SOA</th>
                    <th class="fw-bold" style="color: white">DATE OF REQUEST</th>
                    <th class="fw-bold" style="color: white">ESTIMATED TOTAL FEE</th>
                    <th class="fw-bold" style="color: white">PERCENTAGE</th>
                    <th class="fw-bold" style="color: white">PREVIOUS MBL</th>
                    <th class="fw-bold" style="color: white">REMAINING MBL</th>
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
        <?php include 'view_loa_details.php'; ?>
        <?php include 'loa_disapprove_reason.php'; ?>
      </div>
    <?php include 'loa_approval_modal.php'; ?>
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
<?php include 'view_pdf_file_modal.php';?>
</div>

<script>
  const baseUrl = `<?php echo base_url(); ?>`;
  const fileName = `<?php echo strtotime(date('Y-m-d h:i:s')); ?>`;

  $(document).ready(function() {
    let pendingTable =  $('#pendingLoaTable').DataTable({
      processing: true,
      serverSide: true,
      order: [],

      ajax: {
        url: `${baseUrl}company-doctor/loa/requests-list/fetch`,
        type: "POST",
        data: function(data) {
          data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
          data.filter = $('#pending-hospital-filter').val();
        }
      },

      columnDefs: [{
        "targets": [], // numbering column
        "orderable": false,
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
      minDate: tomorrow
    });
  });

  //SAVE IMAGE 
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
  //END

  //VIEW IMAGE 
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
  //END

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
          remaining_mbl,
          hospitalized_date
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

        if(loa_request_type == 'Emergency'){
          $('#hospitalized').show();
        }else{
          $('#hospitalized').hide();
        }
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
        $('#hospitalized-date').html(hospitalized_date);
        if(work_related != ''){
          $('#percent').show();
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
        }else{
          $('#percent').hide();
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
    $('#loaDisapproveForm')[0].reset();
    $('#loaDisapprovedReasonModal').modal('show');
    $('#disapprove-reason-error').html('');
    $('#disapprove-reason').removeClass('is-invalid');
    $("#loaDisapproveForm").attr("action", `${baseUrl}company-doctor/loa/requests-list/disapprove/${loa_id}`);
    // $('#loa-id').val(loa_id);
  }

  $(document).ready(function() {

    $('#loaApproveForm').submit(function(event) {
      
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
            expiration_date_error,
            next_page
          } = response;
          const nextPage = (next_page === 'Approved')?`${baseUrl}company-doctor/loa/requests-list/approved`:`${baseUrl}company-doctor/loa/requests-list/billed`;
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
</script>