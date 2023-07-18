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
            <a class="nav-link active" href="<?php echo base_url(); ?>healthcare-coordinator/noa/requests-list" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">PENDING</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-coordinator/noa/requests-list/approved" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">APPROVED</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-coordinator/noa/requests-list/disapproved" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">DISAPPROVED</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-coordinator/noa/requests-list/expired" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">EXPIRED</span>
            </a>
          </li>

          <!-- <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-coordinator/noa/requests-list/completed" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">COMPLETED</span>
            </a>
          </li> -->
        </ul>

        <?php include 'charge_type.php'; ?>

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
              <table class="table table-hover" id="pendingNoaTable">
                <thead style="background-color:#00538C">
                  <tr>
                    <th class="fw-bold" style="color: white">NOA NO.</th>
                    <th class="fw-bold" style="color: white">NAME OF PATIENT</th>
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

        <!-- View NOA Details -->
        <div class="modal fade" id="viewNoaModal" tabindex="-1" data-bs-backdrop="static">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <section id="printableDiv">
                <div class="modal-header">
                  <h4 class="modal-title ls-2">NOA #: <span id="noa_no"></span> <span id="noa_status"></span></h4>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                  </button>
                </div>
                <div class="modal-body">
                  <div class="container">
                    <div class="row text-center">
                      <h4><strong>PATIENT DETAILS</strong></h4>
                    </div>
                    <div class="row">
                      <table class="table table-bordered table-striped table-hover table-responsive table-sm">
                        <tr>
                          <td class="fw-bold ls-1">Requested On :</td>
                          <td class="fw-bold ls-1" id="request_date"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Admission Date :</td>
                          <td class="fw-bold ls-1" id="admission_date"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Maximum Benefit Limit :</td>
                          <td class="fw-bold ls-1">&#8369;<span id="mbl"></span></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Remaining MBL :</td>
                          <td class="fw-bold ls-1">&#8369;<span id="remaining_mbl"></span></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Healthcard No. :</td>
                          <td class="fw-bold ls-1" id="healthcard_no"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Full Name :</td>
                          <td class="fw-bold ls-1" id="full_name"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Date of Birth :</td>
                          <td class="fw-bold ls-1" id="date_of_birth"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Age :</td>
                          <td class="fw-bold ls-1" id="age"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Gender :</td>
                          <td class="fw-bold ls-1" id="gender"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Blood Type :</td>
                          <td class="fw-bold ls-1" id="blood_type"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Philhealth No. :</td>
                          <td class="fw-bold ls-1" id="philhealth_no"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Home Address :</td>
                          <td class="fw-bold ls-1" id="home_address"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">City Address :</td>
                          <td class="fw-bold ls-1" id="city_address"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Contact Number :</td>
                          <td class="fw-bold ls-1" id="contact_no"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Email Address :</td>
                          <td class="fw-bold ls-1" id="email_address"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Contact Person Name :</td>
                          <td class="fw-bold ls-1" id="contact_person_name"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Contact Person Address :</td>
                          <td class="fw-bold ls-1" id="contact_person_address"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Contact Person Number :</td>
                          <td class="fw-bold ls-1" id="contact_person_number"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Healthcare Provider :</td>
                          <td class="fw-bold ls-1" id="healthcare_provider"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Chief Complaint :</td>
                          <td class="fw-bold ls-1" id="chief_complaint"></td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
                <tr>
                  <td class="fw-bold ls-1"></td>
                  <td class="fw-bold ls-1" id=""></td>
                </tr>
              </section>
              <div class="modal-footer">
                <button class="btn btn-dark ls-1 me-2" onclick="saveAsImage()"><i class="mdi mdi-file-image"></i> Save as Image</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <!-- End of View NOA -->

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
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
              </div>
            </div>
          </div>
        </div>
        <!-- End Row  -->  
      </div>
      <?php include 'view_pdf_file_modal.php';?>
    </div>
  </div>
</div>


<style type="text/css">
  .modal-header{
    background-color:#00538c;
    color:#fff
  }
  #noa-no{
    color:orange
  }
</style>



<script>
  const baseUrl = `<?php echo base_url(); ?>`;
  const fileName = `<?php echo strtotime(date('Y-m-d h:i:s')); ?>`;

  $(document).ready(function() {

    let pendingTable = $('#pendingNoaTable').DataTable({
      processing: true,
      serverSide: true,
      order: [],

      ajax: {
        url: `${baseUrl}healthcare-coordinator/noa/requests-list/fetch`,
        type: "POST",
        data: function(data) {
          data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
          data.filter = $('#pending-hospital-filter').val();
        }
      },

      columnDefs: [{
        "targets": [5, 6],
        "orderable": false,
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
              $("#pendingNoaTable").DataTable().ajax.reload();
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
              $("#pendingNoaTable").DataTable().ajax.reload();
            break;
          }
        },
      })
    });
  });

  const saveAsImage = () => {
    const element = document.querySelector("#printableDiv");
    html2canvas(element)
    .then(function(canvas) {
      const imgData = canvas.toDataURL("image/png");
      const link = document.createElement("a");
      link.download = `noa_${fileName}.png`;
      link.href = imgData;
      link.click();
    });
  }

  const showTagChargeType = (noa_id) => {
    $("#viewChargeTypeModal").modal("show");
    $("#viewChargeTypeModal").find("form")[0].reset();
    $('#noa-id').val(noa_id);
    $('#charge-type').val('');
  }

  function viewNoaInfo(noa_id) {
    $.ajax({
      url: `${baseUrl}healthcare-coordinator/noa/pending/view/${noa_id}`,
      type: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const {
          status,token,noa_no,request_date,admission_date,mbl,remaining_mbl,health_card_no,requesting_company,first_name,middle_name,last_name,suffix,date_of_birth,age,gender,blood_type,philhealth_no,home_address,city_address,contact_no,email,contact_person,contact_person_addr,contact_person_no,hospital_name,chief_complaint,req_status,
        } = res;

        $("#viewNoaModal").modal("show");

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


        let rstat = '';
        if(req_status == 'Pending'){
          req_stat = `<strong style="color:maroon">[${req_status}]</strong>`;
        }else{
          req_stat = `<strong class="text-cyan">[${req_status}]</strong>`;
        }

        $('#noa_no').html(noa_no);
        $('#noa_status').html(req_stat);
        $('#request_date').html(request_date);
        $('#admission_date').html(admission_date);
        $('#mbl').html(mbl);
        $('#remaining_mbl').html(remaining_mbl);
        $('#healthcard_no').html(health_card_no);
        $('#full_name').html(`${first_name} ${middle_name} ${last_name} ${suffix}`);
        $('#date_of_birth').html(dob);
        $('#age').html(ag);
        $('#gender').html(gndr);
        $('#blood_type').html(bt);
        $('#philhealth_no').html(pn);
        $('#home_address').html(ha);
        $('#city_address').html(ca);
        $('#contact_no').html(cn);
        $('#email_address').html(em);
        $('#contact_person_name').html(cp);
        $('#contact_person_address').html(cpa);
        $('#contact_person_number').html(cpn);
        $('#healthcare_provider').html(hospital_name);
        $('#chief_complaint').html(chief_complaint);
      }
    });
  }

  function cancelNoaRequest(noa_id) {
    $.confirm({
      title: '<strong>Confirm!</strong>',
      content: 'Are you sure to delete NOA Request?',
      type: 'red',
      buttons: {
        confirm: {
          text: 'Yes',
          btnClass: 'btn-red',
          action: function() {
            $.ajax({
              type: 'GET',
              url: `${baseUrl}healthcare-coordinator/noa/requested-noa/cancel/${noa_id}`,
              data: {
                noa_id
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
                  $("#pendingNoaTable").DataTable().ajax.reload();
                } else {
                  swal({
                    title: 'Failed',
                    text: message,
                    timer: 3000,
                    showConfirmButton: false,
                    type: 'error'
                  });
                  $("#pendingNoaTable").DataTable().ajax.reload();
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

<style type="text/css">
  #noa_no{
    color:orange
  }
</style>