<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">Members</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Company Doctor</li>
              <li class="breadcrumb-item active" aria-current="page">Member Profile</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 mb-3 mt-0">
        <a class="btn btn-dark btn-md text-white" href="javascript:void(0)" onclick="window.history.back()" data-bs-toggle="tooltip" title="Click to Go Back"><strong class="ls-2" style="vertical-align:middle"><i class="mdi mdi-arrow-left-bold"></i> Go Back</strong></a>
      </div>
      <div class="col-lg-12">
        <div class="row gutters-sm">
          <div class="col-md-7 mb-3">
          <div class="card d-flex justify-content-evenly">
            <div class="row align-items-end">
              <div class="col-md-6">
                <div class="card shadow">
                <div class="card-body pt-4">
                <div class="d-flex flex-column align-items-center text-center">
                  <?php if ($member['photo'] == '') { ?>
                    <img src="<?= base_url() . 'assets/images/user.svg' ?>" alt="Member" class="rounded-circle img-responsive" width="150" height="auto">
                  <?php } else { ?>
                    <img src="<?= base_url() . 'uploads/profile_pics/' . $member['photo'] ?>" alt="Member" class="rounded-circle img-responsive" width="200" height="auto">
                  <?php } ?>
									<div class="mt-3">
										<p class="mb-1"><strong><?= $member['business_unit'] ?></strong></p>
										<p class="mb-1"><strong><?= $member['dept_name'] ?></strong></p>
										<p class="text-success mb-1"><strong><?= $member['position'] ?></strong></p>
										<p class="mb-1"><strong><?= $member['emp_type'] ?></strong></em>
										<p class="text-muted font-size-sm"><span class="badge rounded-pill bg-success"><strong><?= $member['current_status'] ?></strong></span></p>
									</div>
                </div>
              </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card shadow">
                <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                  <h6 class="mb-0 text-secondary" style="font-weight:600;">Position Level: </h6>
                  <span style="font-weight:600;" class="colored-label"><?= $member['position_level'] ?></span>
                </li>
                
                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                  <h6 class="mb-0 text-secondary" style="font-weight:600;">Employee No: </h6>
                  <span style="font-weight:600;" class="colored-label"><?php echo $member['emp_id'] ?: 'None'; ?></span>
                </li>

                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                  <h6 class="mb-0 text-secondary" style="font-weight:600;">Health Card No: </h6>
                  <span style="font-weight:600;" class="colored-label"><?php echo $member['health_card_no'] ?: 'None'; ?></span>
                </li>

                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                  <h6 class="mb-0 text-secondary" style="font-weight:600;">Max Benefit Limit: </h6>
                  <span style="font-weight:600;" class="colored-label">
                    <?php
                    echo empty($mbl['max_benefit_limit']) ? 'None' : '&#8369;' . number_format($mbl['max_benefit_limit'], 2);
                    ?>
                  </span>
                </li>

                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                  <h6 class="mb-0 text-secondary" style="font-weight:600;">Remaining Balance: </h6>
                  <span style="font-weight:600;" class="colored-label">
                    <?php
                    echo empty($mbl['remaining_balance']) ? 'None' : '&#8369;' . number_format($mbl['remaining_balance'], 2);
                    ?>
                  </span>
                </li>
                
              </ul>
                </div>
              </div>
            </div>
          </div>
            <!-- <div class="card d-flex justify-content-evenly">
            <div class="card shadow">
             
            </div>

            <div class="card shadow">
         
            </div>
            </div> -->
            <h4 class="page-title ls-2 ">Patient History</h4>
            <!-- patient history Loa-->
            <div class="card shadow mt-2 p-2" >
              <table class="table table-hover table-responsive" id="loa_table">
                  <thead >
                    <tr>
                      <th > <span style="font-weight:600;" class="colored-label">LOA #</span></th>
                      <th > <span style="font-weight:600;" class="colored-label">NET BILL</span></th>
                      <th > <span style="font-weight:600;" class="colored-label">STATUS</span></th>
                      <th > <span style="font-weight:600;" class="colored-label">REQUEST DATE</span></th>
                      <th > <span style="font-weight:600;" class="colored-label">VIEW</span></th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
            </div>

            <!-- patient history noa-->
            <div class="card shadow mt-3 p-2">
            <table class="table table-hover table-responsive" id="noa_table">
                <thead>
                  <tr>
                      <th > <span style="font-weight:600;" class="colored-label">NOA #</span></th>
                      <th > <span style="font-weight:600;" class="colored-label">NET BILL</span></th>
                      <th > <span style="font-weight:600;" class="colored-label">STATUS</span></th>
                      <th > <span style="font-weight:600;" class="colored-label">REQUEST DATE</span></th>
                      <th > <span style="font-weight:600;" class="colored-label">VIEW</span></th>
                  </tr>
                </thead>
              	<tbody>
                </tbody>
              </table>
            </div>
            <!-- <span style="font-weight:600;" class="colored-label ps-3">Patient History</span> -->
            <!-- patient history Loa-->
            <!-- <div class="card shadow mt-2" >
            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap border-0"> -->
                  <!-- <h6 class="mb-0 text-secondary" style="font-weight:600;">LOA </h6> -->
                  <!-- <span style="font-weight:600;" class="colored-label">LOA #</span>
                  <span style="font-weight:600;" class="colored-label">Status</span>
                </li>
              <ul class="list-group list-group-flush" style="overflow-y: auto; max-height: 250px;">

                <?php foreach ($loa as $l) : ?>
                  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap" >
                    <h6 class="mb-0 text-primary custom-text" style="font-weight:600; cursor: pointer;" onclick="viewloa('<?= $l->loa_no?>')"><?= $l->loa_no?></h6>
                    <span style="font-weight:600;" class="colored-label"><?= $l->status?></span>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div> -->

            <!-- patient history noa-->
            <!-- <div class="card shadow mt-3">
            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap border-0"> -->
                  <!-- <h6 class="mb-0 text-secondary" style="font-weight:600;">LOA </h6> -->
                  <!-- <span style="font-weight:600;" class="colored-label">NOA #</span>
                  <span style="font-weight:600;" class="colored-label">Status</span>
                </li>
              <ul class="list-group list-group-flush"  style="overflow-y: auto; max-height: 250px;">
                
                
                <?php foreach ($noa as $n) : ?>
                  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap" >
                    <h6 class="mb-0 text-primary custom-text" style="font-weight:600; cursor: pointer;" onclick="viewnoa('<?= $n->noa_no?>')"><?= $n->noa_no?></h6>
                    <span style="font-weight:600;" class="colored-label"><?= $n->status?></span>
                  </li>
                <?php endforeach; ?>
                
              </ul>
            </div> -->

          </div>

          <div class="col-md-5">
            <div class="card shadow mb-0">
              <div class="card-body pt-4">
                <div class="row">
                  <div class="col-sm-3"><h6 class="mb-2 text-secondary" style="font-weight:600;">Full Name:</h6></div>
                  <div class="col-sm-9 colored-label" style="font-weight:600;">
                    <?= $member['first_name'] . ' ' . $member['middle_name'] . ' ' . $member['last_name'] . ' ' . $member['suffix']; ?>
                  </div>
                </div><hr>

                <div class="row">
                  <div class="col-sm-3"><h6 class="mb-0 text-secondary" style="font-weight:600;">Home Address:</h6></div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['home_address']; ?>
                    </div>
                  </div><hr>
                  <div class="row">
                    <div class="col-sm-3"><h6 class="mb-0 text-secondary" style="font-weight:600;">City Address:</h6></div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['city_address']; ?>
                    </div>
                  </div><hr>
                  <div class="row">
                    <div class="col-sm-3"><h6 class="mb-0 text-secondary" style="font-weight:600;">Date of Birth:</h6></div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= date("F d, Y", strtotime($member['date_of_birth'])) ?>
                    </div>
                  </div><hr>
                  <div class="row">
                    <div class="col-sm-3"><h6 class="mb-0 text-secondary" style="font-weight:600;">Age:</h6></div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?php
                      $dateOfBirth = $member['date_of_birth'];
                      $today = date("Y-m-d");
                      $diff = date_diff(date_create($dateOfBirth), date_create($today));
                      echo $diff->format('%y') . ' years old';
                      ?>
                    </div>
                  </div><hr>
                  <div class="row">
                    <div class="col-sm-3"><h6 class="mb-0 text-secondary" style="font-weight:600;">Civil Status:</h6></div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['civil_status']; ?>
                    </div>
                  </div><hr>
                  <div class="row">
                    <div class="col-sm-3"><h6 class="mb-0 text-secondary" style="font-weight:600;">Sex:</h6></div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['gender']; ?>
                    </div>
                  </div><hr>
                  <div class="row">
                    <div class="col-sm-3"><h6 class="mb-0 text-secondary" style="font-weight:600;">Contact Number:</h6></div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['contact_no']; ?>
                    </div>
                  </div><hr>
                  <div class="row">
                    <div class="col-sm-3"><h6 class="mb-0 text-secondary" style="font-weight:600;">Email Address:</h6></div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['email']; ?>
                    </div>
                  </div><hr>
                  <?php
                  	if ($member['spouse'] !== '') :
                  ?>
                  <div class="row">
                    <div class="col-sm-3"><h6 class="mb-0 text-secondary" style="font-weight:600;">Spouse:</h6></div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['spouse']; ?>
                    </div>
                  </div><hr>
                  <?php endif; ?>
                  <div class="row">
                    <div class="col-sm-3"><h6 class="mb-0 text-secondary" style="font-weight:600;">Blood Type:</h6></div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['blood_type']; ?>
                    </div>
                  </div><hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0 text-secondary" style="font-weight:600;">Height:</h6>
                    </div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['height']; ?>
                    </div>
                  </div><hr>
                  <div class="row">
                    <div class="col-sm-3"><h6 class="mb-0 text-secondary" style="font-weight:600;">Weight:</h6></div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['weight']; ?>
                    </div>
                  </div><hr>
                  <div class="row">
                    <div class="col-sm-5 mt-2"><h6 class="mb-0 text-secondary" style="font-weight:600;">Contact Person's Name:</h6></div>
                    <div class="col-sm-7 mt-2 colored-label" style="font-weight:600;">
                      <?= $member['contact_person']; ?>
                    </div>
                  </div><hr>
                  <div class="row">
                    <div class="col-sm-5"><h6 class="mb-0 text-secondary" style="font-weight:600;">Contact Person's Address:</h6></div>
                    <div class="col-sm-7 colored-label" style="font-weight:600;">
                      <?= $member['contact_person_addr']; ?>
                    </div>
                  </div><hr>
                  <div class="row">
                    <div class="col-sm-5"><h6 class="mb-0 text-secondary" style="font-weight:600;">Contact Person's Number:</h6></div>
                    <div class="col-sm-7 colored-label" style="font-weight:600;">
                      <?= $member['contact_person_no']; ?>
                    </div>
                  </div>
                  
                  <?php include 'view_loa_history.php'; ?>
                  <?php include 'view_noa_history.php'; ?>
                  <?php include 'view_pdf_bill_modal.php'; ?>
                  
                </div>

              </div>
            </div>
          </div>
        </div>
    	</div>
  	</div>
	</div>
</div>
<!-- <style>
  .custom-text:hover {
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
  }
</style> -->
 <script>
  // const viewloa = (loa_no) =>{
  //     console.log(loa_no);
  //     }
  //     const viewnoa = (noa_no) =>{
  //       console.log(noa_no);
  //     }

      const base_url = '<?php echo base_url();?>';
      const emp_id = '<?= $member['emp_id']?>';
      const hp_id = '<?= $hp_id?>';
      
      // function viewLoaInfo(loa_id){
      //   $("#viewLoaModal").modal("show");
      // }
      // function viewNoaInfo(Noa_id){
      //   $("#viewLoaModal").modal("show");
      // }
          $(document).ready(function(){

          $('#viewLoaModal').on('hidden.bs.modal', function() {

          $('#services').empty(); // Remove all list items from the list
          $('#documents').empty(); 
          $('#loa_details_1').empty(); 
          $('#loa_details_2').empty(); 
          $('#physician').empty(); 
          // Additional reset logic if needed
        });

        $('#viewNoaModal').on('hidden.bs.modal', function() {
          $('#services-noa').empty(); // Remove all list items from the list
          $('#documents-noa').empty(); 
          $('#noa_details_1').empty(); 
          $('#noa_details_2').empty(); 
          $('#physician-noa').empty(); 
          // Additional reset logic if needed
        });
              $('#loa_table').DataTable({ 
              lengthMenu: [5, 10, 25, 50],
              processing: true,
              serverSide: true,
              order: [],

              ajax: {
                url: `${base_url}healthcare-provider/patient/fetch_all_patient_loa`,
                type: "POST",
                data: { 'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
                        'emp_id' :  emp_id,
                        'hp_id' :  hp_id}
              },

              // columnDefs: [{ 
              //   "targets": [6], // 6th and 7th column / numbering column
              //   "orderable": false,
              // },
              // ],
              responsive: true,
              fixedHeader: true,
            });   
         
          
            $('#noa_table').DataTable({ 
            lengthMenu: [5, 10, 25, 50],
            processing: true,
            serverSide: true,
            order: [],

            ajax: {
              url: `${base_url}healthcare-provider/patient/fetch_all_patient_noa`,
              type: "POST",
              data: { 'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
                      'emp_id' :  emp_id,
                      'hp_id' :  hp_id}
            },

            // columnDefs: [{ 
            //   "targets": [6], // 6th and 7th column / numbering column
            //   "orderable": false,
            // },
            // ],
            responsive: true,
            fixedHeader: true,
            });   

          });
          
          function viewImage(file,type) {
        let src = '';
        if(type == 'abstract'){
            src = `${base_url}uploads/medical_abstract/${file}`;
        }
        if(type == 'prescription'){
            src = `${base_url}uploads/prescription/${file}`;
        }
        if(type == 'rx'){
            src = `${base_url}uploads/loa_attachments/${file}`;
        }
        let item = [{
            src: src , // path to image
            title: 'Attached RX File' // If you skip it, there will display the original image name
        }];
        // define options (if needed)
        let options = {
            index: 0 // this option means you will start at first image
        };
        // Initialize the plugin
        let photoviewer = new PhotoViewer(item, options);
    }

    const viewPDFBill = (pdf_bill,loa_no,type) => {
      $('#viewPDFBillModal').modal('show');
      $('#pdf-loa-no').html(loa_no);

        let pdfFile = "";
        let fileExists = checkFileExists(pdfFile);
        if(type == "pdf_bill"){
          pdfFile = `${base_url}uploads/pdf_bills/${pdf_bill}`;
        }
        if(type == "diagnosis"){
          pdfFile = `${base_url}uploads/final_diagnosis/${pdf_bill}`;
        }
    
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
                let iframe = document.querySelector('#pdf-viewer');
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

    function viewLoaHistoryInfo(loa_no) {
        $.ajax({
        url: `${base_url}healthcare-provider/patient_history/loa/${loa_no}`,
        type: "GET",
        success: function(response) {
            const res = JSON.parse(response);
            const base_url = window.location.origin;
            // Object Destructuring
            const { status, token, loa_no, member_mbl, remaining_mbl, first_name, middle_name,
            last_name, suffix, date_of_birth, age, gender, philhealth_no, blood_type, contact_no,
            home_address, city_address, email, contact_person, contact_person_addr, contact_person_no,
            healthcare_provider, loa_request_type, med_services, health_card_no, requesting_company,
            request_date, complaint, requesting_physician, attending_physician, rx_file,pdf_bill,
            req_status, work_related, approved_by, approved_on,expiration,billed_on,paid_on,net_bill,paid_amount,
            disapproved_on,date_perform,attending_doctors,disapprove_reason,complaints,disapproved_by
            } = res;

            $("#viewLoaModal").modal("show");
            $("#p-disaproved").hide();
            $("#p-documents").hide();
            $("#p-physician").hide();
           
            // const med_serv = med_services !== '' ? med_services : 'None';
            // const at_physician = attending_physician !== '' ? attending_physician : 'None';
            $('#loa_details_1').append(`<h6>LOA #: <strong><span class="text-primary">${loa_no}</span></strong></h6>`); 
            // $('#loa-no').html(loa_no);
            $('#status').html(`<strong class="text-success">[${req_status}]</strong>`);
            $('#complaint').text(complaints);
            // $('#approved-date').html(approved_on);
            // $('#expire').html(expiration);
            switch(req_status){
                case 'Pending':
                    $('#loa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`);
                    if(rx_file.length){$("#p-documents").show();}
                break;
                case 'Approved':
                    $('#loa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`); 
                    $('#loa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>EXPIRATION DATE: <strong><span class="text-primary">${expiration}</span></strong></h6>`); 
                    if(rx_file.length){$("#p-documents").show();}
                    if(attending_doctors.length != 0 || attending_physician.length != 0){ $("#p-physician").show();}
                break;
                case 'Disapproved':
                    $('#loa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>DISAPPROVED DATE: <strong><span class="text-primary">${disapproved_on}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>DISAPPROVED BY: <strong><span class="text-primary">${disapproved_by}</span></strong></h6>`); 
                    $("#p-disaproved").show();
                    $('#disaproved').text(disapprove_reason);
                    if(rx_file.length){$("#p-documents").show();}
                break;
                case 'Completed':
                    $('#loa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`); 
                    $('#loa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>PERFORMED DATE: <strong><span class="text-primary">${date_perform}</span></strong></h6>`);
                    $("#p-documents").show();
                    $("#p-physician").show(); 
                break;
                case 'Reffered':
                    $('#loa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`);  
                    $('#loa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`);
                    $('#loa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>EXPIRATION DATE: <strong><span class="text-primary">${expiration}</span></strong></h6>`); 
                    if(rx_file.length){$("#p-documents").show();}
                    if(attending_doctors.length != 0 || attending_physician.length != 0){ $("#p-physician").show();}
                break;
                case 'Expired':
                    $('#loa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`);
                    $('#loa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>EXPIRATION DATE: <strong><span class="text-primary">${expiration}</span></strong></h6>`); 
                    if(rx_file.length){$("#p-documents").show();}
                break;
                case 'Billed' || 'Payment' || 'Payable':
                    $('#loa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`); 
                    $('#loa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>PERFORMED DATE: <strong><span class="text-primary">${date_perform}</span></strong></h6>`); 
                    $('#loa_details_1').append(`<h6>BILLED DATE: <strong><span class="text-primary">${billed_on}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>NET BILL: <strong><span class="text-primary">${net_bill}</span></strong></h6>`); 
                    $("#p-documents").show();
                    $("#p-physician").show();
                break;
                case 'Paid':
                  $('#loa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`); 
                    $('#loa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>PERFORMED DATE: <strong><span class="text-primary">${date_perform}</span></strong></h6>`); 
                    $('#loa_details_1').append(`<h6>BILLED DATE: <strong><span class="text-primary">${billed_on}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>NET BILL: <strong><span class="text-primary">${net_bill}</span></strong></h6>`); 
                    $('#loa_details_1').append(`<h6>PAID AMOUNT: <strong><span class="text-primary">${paid_amount}</span></strong></h6>`); 
                    $('#loa_details_2').append(`<h6>DATE PAID: <strong><span class="text-primary">${paid_on}</span></strong></h6>`); 
                    $("#p-documents").show();
                    $("#p-physician").show();
                break;
                
            }
            
            // console.log("attending_physician",attending_physician.length);
            // console.log("attending_doctors",attending_doctors.length);
            if(rx_file.length){
              $('#documents').append('<li id="rx-file"><span class="mdi mdi-file"></span><a href="#" onclick="viewImage(\''+rx_file+'\',\''+'rx'+'\')">Rx File</a></li>');
            }
            if(pdf_bill.length){
              $('#documents').append('<li id="soa"><span class="mdi mdi-file-pdf"></span><a href="#" onclick="viewPDFBill(\''+pdf_bill+'\',\''+loa_no+'\',\''+'pdf_bill'+'\')">Statement of Account (SOA)</a></li>');
            }

            $.each(med_services, function(index, item) {
                console.log(item);
                $('#services').append('<li>' + item + '</li>');
            });

            if(attending_physician.length){
              //console.log("physician",attending_physician);
              $.each(attending_physician, function(index, item) {
                if(item.length > 1){
                  $('#physician').append('<li>' + item + '</li>');
                }
            });
            }
            
            if(attending_doctors.length){
              $.each(attending_doctors, function(index, item) {
                if(item.length > 1){
                  $('#physician').append('<li>' +'Dr. '+ item + '</li>');
                }
                
            });
              
            }
            
          }

        });

    }
    function viewNoaHistoryInfo(noa_id) {
        $.ajax({
        url: `${base_url}healthcare-provider/patient_history/noa/${noa_id}`,
        type: "GET",
        success: function(response) {
            const res = JSON.parse(response);
            // console.log(response);
            const base_url = window.location.origin;
            // Object Destructuring
            const { status, token, noa_no, member_mbl, remaining_mbl, first_name, middle_name,
            last_name, suffix, date_of_birth, age, gender, philhealth_no, blood_type, contact_no,
            home_address, city_address, email, contact_person, contact_person_addr, contact_person_no,
            healthcare_provider, loa_request_type, med_services, health_card_no, requesting_company,
            request_date,complaints, requesting_physician, attending_physician, pdf_bill,final_diagnosis,medical_abstract,
            req_status, work_related, approved_by, approved_on,billed_on,paid_on,net_bill,paid_amount,expiration,prescription,
            disapproved_on,attending_doctors,disapprove_reason,disapproved_by
            } = res;
            // console.log("complaints",complaints);
            $("#viewNoaModal").modal("show");
            $("#p_disaproved").hide();
            $("#p_documents").hide();
            $("#p_physician").hide();
            // const med_serv = med_services !== '' ? med_services : 'None';
            // const at_physician = attending_physician !== '' ? attending_physician : 'None';
            $('#noa_details_1').append(`<h6>NOA #: <strong><span class="text-primary">${noa_no}</span></strong></h6>`); 
            // $('#loa-no').html(loa_no);
            $('#nstatus').html(`<strong class="text-success">[${req_status}]</strong>`);
            // $('#noa-no').html(noa_no);
            // $('#status-noa').html(`<strong class="text-success">[${req_status}]</strong>`);
            // $('#approved-date-noa').html(approved_on);
            // $('#expire-noa').html(approved_on);
            $('#complaint-noa').text(complaints);
            // console.log(disapprove_reason);
            switch(req_status){
                case 'Pending':
                    $('#noa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                break;
                case 'Approved':
                    $('#noa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`); 
                    $('#noa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>EXPIRATION DATE: <strong><span class="text-primary">${expiration}</span></strong></h6>`); 
                break;
                case 'Disapproved':
                    $('#noa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>DISAPPROVED DATE: <strong><span class="text-primary">${disapproved_on}</span></strong></h6>`);
                    $('#noa_details_2').append(`<h6>DISAPPROVED BY: <strong><span class="text-primary">${disapproved_by}</span></strong></h6>`);
                    $("#p_disaproved").show();
                    $('#disaproved-noa').text(disapprove_reason);
                break;
                case 'Expired':
                    $('#noa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`); 
                    $('#noa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>EXPIRED DATE: <strong><span class="text-primary">${expiration}</span></strong></h6>`); 
                break;
                case 'Billed' || 'Payment' || 'Payable':
                    $('#noa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`); 
                    $('#noa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>BILLED DATE: <strong><span class="text-primary">${billed_on}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>NET BILL: <strong><span class="text-primary">${net_bill}</span></strong></h6>`); 
                    $("#p_documents").show();
                    $("#p_physician").show();
                break;
                case 'Paid':
                    $('#noa_details_1').append(`<h6>REQUEST DATE: <strong><span class="text-primary">${request_date}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>APPROVED DATE: <strong><span class="text-primary">${approved_on}</span></strong></h6>`); 
                    $('#noa_details_1').append(`<h6>APPROVED BY: <strong><span class="text-primary">${approved_by}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>BILLED DATE: <strong><span class="text-primary">${billed_on}</span></strong></h6>`); 
                    $('#noa_details_1').append(`<h6>NET BILL: <strong><span class="text-primary">${net_bill}</span></strong></h6>`); 
                    $('#noa_details_2').append(`<h6>DATE PAID: <strong><span class="text-primary">${paid_on}</span></strong></h6>`); 
                    $('#noa_details_1').append(`<h6>PAID AMOUNT: <strong><span class="text-primary">${paid_amount}</span></strong></h6>`); 
                    $("#p_documents").show();
                    $("#p_physician").show();
                break;
                
            }
            
          
            if(pdf_bill.length){
              $('#documents-noa').append('<li id="soa"><span class="mdi mdi-file-pdf"></span><a href="#" onclick="viewPDFBill(\''+pdf_bill+'\',\''+noa_no+'\',\''+'pdf_bill'+'\')">Statement of Account (SOA)</a></li>');
            }
            if(final_diagnosis.length){
              $('#documents-noa').append('<li id="soa"><span class="mdi mdi-file-pdf"></span><a href="#" onclick="viewPDFBill(\''+final_diagnosis+'\',\''+noa_no+'\',\''+'diagnosis'+'\')">Final Diagnosis</a></li>');
            }
            if(medical_abstract.length){
              $('#documents-noa').append('<li id="rx-file"><span class="mdi mdi-file"></span><a href="#" onclick="viewImage(\''+medical_abstract+'\',\''+'abstract'+'\')">Medical Abstract File</a></li>');
            }
            if(prescription.length){
              $('#documents-noa').append('<li id="rx-file"><span class="mdi mdi-file"></span><a href="#" onclick="viewImage(\''+prescription+'\',\''+'prescription'+'\')">Prescription File</a></li>');
            }

            if(attending_doctors.length){
              $.each(attending_doctors, function(index, item) {
                if(item.length > 1){
                  $('#physician-noa').append('<li>' +'Dr. '+ item + '</li>');
                }
                
              });
            }
            // $('#work-related').html(work_related);
          }

        });

    }
         
 </script>
  

