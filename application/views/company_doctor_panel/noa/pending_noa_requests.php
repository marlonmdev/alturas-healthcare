
<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">PENDING REQUEST</h4>
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
  <!-- End Bread crumb and right sidebar toggle -->
  <!-- Start of Container fluid  -->
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">

        <ul class="nav nav-tabs mb-4" role="tablist">
          <li class="nav-item">
            <a
              class="nav-link active"
              href="<?php echo base_url(); ?>company-doctor/noa/requests-list"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">PENDING</span></a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>company-doctor/noa/requests-list/approved"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">APPROVED</span></a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>company-doctor/noa/requests-list/disapproved"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">DISAPPROVED</span></a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>company-doctor/noa/requests-list/billed"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">BILLED</span></a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>company-doctor/noa/requests-list/paid"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">PAID</span></a
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
              <table class="table table-hover" id="pendingNoaTable">
                <thead style="background-color:#00538C">
                  <tr>
                    <th class="fw-bold" style="color: white">NOA NO.</th>
                    <th class="fw-bold" style="color: white">PATIENT NAME</th>
                    <th class="fw-bold" style="color: white">DATE OF ADMISSION</th>
                    <th class="fw-bold" style="color: white">NAME OF HOSPITAL</th>
                    <th class="fw-bold" style="color: white">DATE OF REQUEST</th>
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
          <?php include 'view_noa_details.php'; ?>
      </div>
        <?php include 'noa_approval_modal.php'; ?>
      <!-- End Row  -->  
      </div>
      <?php include 'noa_disapprove_reason.php'; ?>
    <!-- End Container fluid  -->
    </div>
  <!-- End Page wrapper  -->
  </div>
<!-- End Wrapper -->
  <div class="modal fade" id="viewUploadedReportsModal" tabindex="-1" data-bs-backdrop="static" style="height:100%">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Attached Reports</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <input id="report-percentage" class="form-control" readonly>
                <div class="pt-3">
                  
                  <label class="fs-5">Uploaded Reports : <i><small class="text-danger">Click to view the file</small></i></label><br>
                  <li>Spot Report : <a href="JavaScript:void(0)" data-bs-toggle="tooltip" onclick="viewSpotFile()" id="uploaded-spot-report"></a></li>
                  <li>Incident Report : <a href="JavaScript:void(0)" data-bs-toggle="tooltip" onclick="viewIncidentFile()" id="uploaded-incident-report"></a></li>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
  </div>

  <?php include 'view_pdf_file_modal.php';?>

<script>
  const baseUrl = `<?php echo base_url(); ?>`;
  const fileName = `<?php echo strtotime(date('Y-m-d h:i:s')); ?>`;

  $(document).ready(function() {

    let pendingTable = $('#pendingNoaTable').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}company-doctor/noa/requests-list/fetch`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: function(data) {
            data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
            data.filter = $('#pending-hospital-filter').val();
        }
      },

      //Set column definition initialisation properties.
      columnDefs: [{
        "targets": [5, 6], // numbering column
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
        link.download = `noa_${fileName}.png`;
        link.href = imgData;

        // Click the link to download the image
        link.click();
      });
  }

  const viewNoaInfo = (req_id) => {
    $.ajax({
      url: `${baseUrl}company-doctor/noa/requests-list/view/${req_id}`,
      type: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const baseUrl = window.location.origin;
        const {
          status,
          token,
          noa_no,
          member_mbl,
          remaining_mbl,
          first_name,
          middle_name,
          last_name,
          suffix,
          date_of_birth,
          age,
          hospital_name,
          health_card_no,
          requesting_company,
          admission_date,
          chief_complaint,
          work_related,
          request_date,
          req_status,
          percentage
        } = res;

        $("#viewNoaModal").modal("show");

        let rstat = '';
        if(req_status == 'Pending'){
          req_stat = `<strong class="text-warning">[${req_status}]</strong>`;
        }else{
          req_stat = `<strong class="text-cyan">[${req_status}]</strong>`;
        }
        
        $('#noa-no').html(noa_no);
        $('#noa-status').html(req_stat);
        $('#member-mbl').html(member_mbl);
        $('#remaining-mbl').html(remaining_mbl);
        $('#full-name').html(`${first_name} ${middle_name} ${last_name} ${suffix}`);
        $('#date-of-birth').html(date_of_birth);
        $('#age').html(age);
        $('#hospital-name').html(hospital_name);
        $('#admission-date').html(admission_date);
        $('#chief-complaint').html(chief_complaint);
        $('#request-date').html(request_date);
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

  const approveNoaRequest = (noa_id) => {
    const nextPage = `${baseUrl}company-doctor/noa/requests-list/approved`;

    $('#noaApprovalModal').modal('show');
    $('#appr-noa-id').val(noa_id);
  }

  // const approveNoaRequest = (noa_id) => {
  //   const next_page = `${baseUrl}company-doctor/noa/requests-list/approved`;
  //   // $.confirm is a convention of a Jquery Confirm plugin 
  //   $.confirm({
  //     title: '<strong>Confirm!</strong>',
  //     content: 'Are you sure to Approve NOA Request?',
  //     type: 'green',
  //     buttons: {
  //       confirm: {
  //         text: 'Yes',
  //         btnClass: 'btn-green',
  //         action: function() {
  //           $.ajax({
  //             type: 'GET',
  //             url: `${baseUrl}company-doctor/noa/requests-list/approve/${noa_id}`,
  //             data: {
  //               noa_id: noa_id
  //             },
  //             dataType: "json",
  //             success: function(response) {
  //               const {
  //                 token,
  //                 status,
  //                 message
  //               } = response;
  //               if (status === 'success') {
  //                 swal({
  //                   title: 'Success',
  //                   text: message,
  //                   timer: 3000,
  //                   showConfirmButton: false,
  //                   type: 'success'
  //                 });

  //                 setTimeout(function() {
  //                   window.location.href = next_page;
  //                 }, 3200);

  //               } else {
  //                 swal({
  //                   title: 'Failed',
  //                   text: message,
  //                   timer: 3000,
  //                   showConfirmButton: false,
  //                   type: 'error'
  //                 });
  //               }
  //             }
  //           });
  //         }
  //       },
  //       cancel: {
  //         btnClass: 'btn-dark',
  //         action: function() {
  //           // close dialog
  //         }
  //       },

  //     }
  //   });
  // }


  const showExpDateInput = () => {
    const exp_type = $('#expiration-type').val();
    if(exp_type === 'custom'){
      $('#exp-date-div').removeClass('d-none');
    }else{
      $('#exp-date-div').addClass('d-none');
    }
  }

  const disapproveNoaRequest = (noa_id) => {
    $('#noaDisapproveForm')[0].reset();
    $('#noaDisapprovedReasonModal').modal('show');
    $('#disapprove-reason-error').html('');
    $('#disapprove-reason').removeClass('is-invalid');
    $("#noaDisapproveForm").attr("action", `${baseUrl}company-doctor/noa/requests-list/disapprove/${noa_id}`);
  }

  $(document).ready(function() {

    $('#noaApproveForm').submit(function(event) {
      const nextPage = `${baseUrl}company-doctor/noa/requests-list/approved`;
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
              
              $('#noaApprovalModal').modal('hide');
              setTimeout(function() {
                window.location.href = nextPage;
              }, 3200);
              break;
          }
        }
      });
    });

    $('#noaDisapproveForm').submit(function(event) {
      const nextPage = `${baseUrl}company-doctor/noa/requests-list/disapproved`;
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
              $('#noaDisapprovedReasonModal').modal('hide');
              setTimeout(function() {
                window.location.href = nextPage;
              }, 3200);
              break;
          }
        }
      });
    });
  });

  const viewReports = (loa_id, work_related, percentage, spot_report, incident_report) => {
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
  }

  const viewSpotFile = () => {
    const sport_report = document.querySelector('#uploaded-spot-report');
    const anchorText = sport_report.textContent;

      $('#viewFileModal').modal('show');
      $('#cancel').hide();
      $('#file-name-r').html('Uploaded Spot Report');

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
      $('#file-name-r').html('Uploaded Incident Report');

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

    const checkFileExists = (fileUrl) => {
        let xhr = new XMLHttpRequest();
        xhr.open('HEAD', fileUrl, false);
        xhr.send();

        return xhr.status == "200" ? true: false;
    }
</script>