<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <!-- <h4 class="page-title ls-2">CHECKING OF BILLING</h4> -->
        <div class="ms-auto text-end">
          <!-- <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">Checking</li>
            </ol>
          </nav> -->
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <ul class="nav nav-tabs mb-4" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" href="<?php echo base_url(); ?>healthcare-coordinator/bill/requests-list/billed" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">FINAL BILLING</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-coordinator/bill/requests-list/for-charging" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">FOR PAYMENT</span>
            </a>
          </li>
        </ul>

        <form id="billedForm" method="POST" action="<?php echo base_url(); ?>healthcare-coordinator/loa/billed/submit_final_billing">
          <input type="hidden" class="form-control" name="status" id="status" value="Payable">
          <div class="row pt-2 pb-2">
            <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash() ?>">
            <div class="col-lg-5 ps-5 pb-3 pt-1 pb-4">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text bg-dark text-white">
                    <i class="mdi mdi-filter"></i>
                  </span>
                </div>
                <select class="form-select fw-bold" name="billed-hospital-filter" id="billed-hospital-filter" oninput="enableDate()" onchange="checkHospitalSelection()">
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
                  <span class="input-group-text bg-dark text-white ls-1 ms-2"><i class="mdi mdi-filter"></i></span>
                </div>
                <input type="date" class="form-control" name="start-date" id="start-date" oninput="validateDateRange();" placeholder="Start Date" disabled>
                <div class="input-group-append">
                  <span class="input-group-text bg-dark text-white ls-1 ms-2"><i class="mdi mdi-filter"></i></span>
                </div>
                <input type="date" class="form-control" name="end-date" id="end-date" oninput="validateDateRange();" placeholder="End Date" disabled>
              </div>
            </div>

          
            <div class="card shadow" style="background-color:">
              <div class="card-body">
                <div class="">
                  <table class="table table-hover table-responsive" id="billedLoaTable">
                    <thead style="background-color:#00538C">
                      <tr>
                        <th style="color: white">LOA NO.</th>
                        <th style="color: white">NAME OF PATIENT</th>
                        <th style="color: white">TYPE OF REQUEST</th>
                        <th style="color: white">REQUEST DATE</th>
                        <th style="color: white">HOSPITAL BILL</th>
                        <th style="color: white">VIEW BILL</th>
                        <th style="color: white">STATUS</th> 
                        <th style="color: white">ACTION</th> 
                      </tr>
                    </thead>
                    <tbody id="billed-tbody">
                    </tbody>
                  </table>
                </div>

                <div class="row pt-4">
                  <div class="col-lg-2 offset-10">
                    <input name="total-hospital-bill" id="total-hospital-bill" class="form-control text-align:left fw-bold" value="0"  oninput="checkTotalHospitalBill()" readonly>
                  </div>
                </div>
              </div>
              <div class="offset-10 pt-2 pb-4">
                <button class="btn btn-info fw-bold fs-5 btn-lg" type="submit" id="proceed-btn" disabled><i class="mdi mdi-send"></i> Proceed</button>
              </div>
              <?php include 'view_coordinator_bill_modal.php'; ?>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
  <?php include 'view_pdf_bill_modal.php'; ?>
</div>

<!-- MANAGER KEY MODAL -->
<div class="modal fade" id="managersKeyModal" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header bg-cyan">
        <h5 class="modal-title text-white ls-1"><i class="mdi mdi-account-key"></i> MANAGER'S KEY</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="managersKeyForm" autocomplete="off">
          <input type="hidden" name="token" id="token" value="<?= $this->security->get_csrf_hash(); ?>">
          <input type="hidden" name="expired-loa-id" id="expired-loa-id">
          <input type="hidden" name="expired-loa-no" id="expired-loa-no">

          <div class="text-center">
            <strong id="msg-error" class="text-danger ls-1 mx-1"></strong>
          </div>

          <div class="mb-3">
            <label class="ls-1">Username</label>
            <input type="text" class="form-control" name="mgr-username" id="mgr-username" placeholder="Enter Username">
            <em id="mgr-username-error" class="text-danger"></em>
          </div>

          <div class="mb-4">
            <label class="ls-1">Password</label>
            <input type="password" class="form-control input-password" name="mgr-password" id="mgr-password" placeholder="Enter Password">
            <em id="mgr-password-error" class="text-danger"></em>
          </div>              

          <div class="row mt-2">
            <div class="col-sm-12 d-flex justify-content-end">
              <button type="submit" class="btn btn-cyan me-2"><i class="mdi mdi-send"></i> SUBMIT</button>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="mdi mdi-close-box"></i> CANCEL</button>
            </div>
          </div>
          
        </form>
      </div>
    </div>
  </div>
</div>
<!-- END -->


<!-- Adjustment MOdal -->
<div class="modal fade" id="backDateModal" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title ls-2"><i class="mdi mdi-arrow-up-bold-circle"></i>Re-Upload: [<span class="loa_no" id="bd-loa-no" class="text-primary"></span>]</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
      </div>

      <div class="modal-body">
        <form id="backDateForm">
          <input type="hidden" name="token" id="token" value="<?= $this->security->get_csrf_hash(); ?>">
          <input type="hidden" name="loa-id" id="bd-loa-id">

          <div class="mb-3">
            <label class="ls-1">Reason for Adjustment</label>
            <textarea  class="form-control" name="reason_adjustment" id="reason_adjustment" cols="30" rows="6"></textarea>
            <em id="reason_adjustment_error" class="text-danger"></em>
          </div>               

          <div class="row mt-2">
            <div class="col-sm-12 d-flex justify-content-end">
              <button type="submit" class="btn btn-success me-2"><i class="mdi mdi-send"></i> SUBMIT</button>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="mdi mdi-close-box"></i> CANCEL</button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
<!-- End -->

<!-- Guarantee Letter -->
<div class="modal fade pt-4" id="GuaranteeLetter" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title ls-2">GUARANTEE LETTER</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        <form method="post" action="<?php echo base_url(); ?>healthcare-coordinator/loa/billed/submit_letter" id="Letter" enctype="multipart/form-data">
          <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
          <input type="hidden" name="emp-id" id="emp-id">
          <input type="hidden" name="billing-id" id="billing-id">
                        
          <div class="col-lg-8 pt-1">
            <input type="hidden" class="form-control text-danger fs-5 fw-bold" name="emp-name" id="emp-name" placeholder="Employee Name" readonly>
          </div>
                        
          <div class="row pt-5">
            <div class="col-lg-10 offset-1">
              <div class="form-group">
                <label for="letter" style="font-size: 20px">Upload File:</label>
                <input type="file" class="form-control-file dropify" name="letter" id="letter" accept=".jpg, .jpeg, .png, .gif, .pdf" data-max-file-size="5M" onchange="showPreview(this)">
              </div>
              <div style="font-size: 20px; text-align:center" id="image-preview" class="mb-3"></div>
              <p style="font-size: 20px; text-align:center" id="pdf-preview" class="mb-0"></p>
              <span id="letter_error" class="text-danger"></span>
            </div>
          </div><br>

          <div class="row pt-3">
            <div class="col-sm-12 mb-sm-0 d-flex justify-content-end">
              <button type="submit" class="btn btn-primary me-2"><i class="mdi mdi-content-save"></i> UPLOAD</button>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="mdi mdi-close-box"></i> CANCEL</button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
<!-- End -->

  

<script>
  const baseUrl = "<?php echo base_url()?>";
  $(document).ready(function() {

    let billedTable = $('#billedLoaTable').DataTable({
      processing: true,
      serverSide: true,
      order: [],

      ajax: {
        url: `${baseUrl}healthcare-coordinator/loa/billed/datatable_final_billing`,
        type: "POST",
        data: function(data) {
          data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
          data.filter = $('#billed-hospital-filter').val();
          data.endDate = $('#end-date').val();
          data.startDate = $('#start-date').val();
        }
      },

      columnDefs: [{
        "orderable": false,
      }, ],
      data: [],
      deferRender: true,
      info: false,
      paging: false,
      filter: false,
      lengthChange: false,
      responsive: true,
      fixedHeader: true,
    });

    $('#billed-hospital-filter').change(function(){
      billedTable.draw();
      getTotalBill();
    });

    $('#start-date').change(function(){
      billedTable.draw();
      getTotalBill();
    });

    $('#end-date').change(function(){
      billedTable.draw();
      getTotalBill();
    });

    $("#start-date").flatpickr({
        dateFormat: 'Y-m-d',
    });

    $('#end-date').flatpickr({
        dateFormat: 'Y-m-d',
    });

    //Manager key
    $('#managersKeyForm').submit(function(event) {
      event.preventDefault();

      // Serialize form data
      var formData = $(this).serialize();

      // Make the AJAX request
      $.ajax({
        type: 'POST',
        url: `${baseUrl}healthcare-coordinator/managers-key/check`,
        data: formData,
        dataType: 'json',
        success: function(res) {
          const {
            status,
            message,
            mgr_username_error,
            mgr_password_error,
            loa_id,
            loa_no
          } = res;

          if (status == 'error') {
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
            $("#managersKeyModal").modal("hide");
            showBackDateForm(loa_id, loa_no);
          }
        },
        error: function(xhr, status, error) {
          // Handle the error if the AJAX request fails
          console.error(xhr.responseText);
        }
      });
    });
    //End

    //Reason for Adjustment
    $('#backDateForm').submit(function(event){
      event.preventDefault();
      $.ajax({
        type: "post",
        url: `${baseUrl}healthcare-coordinator/loa/reason_adjustment`,
        data: $(this).serialize(),
        dataType: "json",
        success: function (res) {
          const { status, message,expiry_date_error } = res;

          switch (status) {
            case 'error':
              // is-invalid class is a built in classname for errors in bootstrap
              if (expiry_date_error !== '') {
                $('#reason_adjustment_error').html(expiry_date_error);
                $('#reason_adjustment').addClass('is-invalid');
              } else {
                $('#reason_adjustment_error').html('');
                $('#reason_adjustment').removeClass('is-invalid');
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
              
              $("#backDateModal").modal("hide");
              $("#billedLoaTable").DataTable().ajax.reload();
            break;
          }
        },
      });
    });
    //End

    //Submit Guarantee Letter
    $('#Letter').submit(function(event) {
      event.preventDefault();

      const LetterForm = $('#Letter')[0];
      const formdata = new FormData(LetterForm);
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
            
            break;
            case 'success':
              swal({
                title: 'Success',
                text: message,
                timer: 3000,
                showConfirmButton: false,
                type: 'success'
              });
            break;
          }
        },
      })
    });
    //End
  });

    
  const viewPDFBill = (pdf_bill,loa_no) => {
    $('#viewPDFBillModal').modal('show');
    $('#pdf-loa-no').html(loa_no);

    let pdfFile = `${baseUrl}uploads/pdf_bills/${pdf_bill}`;
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

  const viewCoordinatorBill = (loa_id) =>{
    $('#viewCoordinatorBillModal').modal('show');
    $.ajax({
      url: `${baseUrl}healthcare-coordinator/loa/billing/fetch/${loa_id}`,
      type: 'GET',
      dataType: 'json',
      success: function(data){
        let bill = data.bill;
        let service = data.service;
        let deduction = data.deduction;
        let charge = data.charge;
        let deduction_table = '';
        let service_table = '';
        let fullname = '';
        let hp_name = '';

        fullname += bill.first_name+' '+bill.middle_name+' '+bill.last_name+' '+bill.suffix;
        hp_name += bill.hp_name;
          
        if(bill.request_type == 'Consultation'){
          let services = parseFloat(bill.service_fee);
          service_table +='<tr>' +
                              '<td class="text-center ls-1">Consultation Fee</td>' +
                              '<td class="text-center ls-1">'+services.toLocaleString('PHP', { minimumFractionDigits: 2 })+'</td>' +
                          '</tr>';

          $.each(charge, function(index, item){
            let charge_amount = parseFloat(item.charge_amount);
            service_table +='<tr>'+
                              '<td class="text-center ls-1">'+item.charge_name+'</td>' +
                              '<td class="text-center ls-1">'+charge_amount.toLocaleString('PHP', { minimumFractionDigits: 2 })+'</td>' +
                            '</tr>';
          });
        }else{
          $.each(service, function(index, item){
            let op_price = parseFloat(item.op_price);
            service_table +='<tr>' +
                              '<td class="text-center ls-1">'+item.item_description+'</td>' +
                              '<td class="text-center ls-1">'+op_price.toLocaleString('PHP', { minimumFractionDigits: 2 })+'</td>' +
                            '</tr>' ;
          });
          $.each(charge, function(index, item){
            let charge_amount = parseFloat(item.charge_amount);
            service_table +='<tr>'+
                              '<td class="text-center ls-1">'+item.charge_name+'</td>' +
                              '<td class="text-center ls-1">'+charge_amount.toLocaleString('PHP', { minimumFractionDigits: 2 })+'</td>' +
                            '</tr>';
          });
          let services = parseFloat(bill.medicines);
          service_table +='<tr>' +
                              '<td class="text-center ls-1">Medicine/s</td>' +
                              '<td class="text-center ls-1">'+services.toLocaleString('PHP', { minimumFractionDigits: 2 })+'</td>' +
                          '</tr>';
        }
         
        let total_services = parseFloat(bill.total_services);
        if(parseFloat(bill.medicines) != ''){
          service_table +=  '<tr>' +
                                // '<tr><td></td><td></td></tr>' +
                                // '<td class="text-center ls-1">Medicines</td>' +
                                // '<td class="text-center ls-1">'+parseFloat(bill.medicines).toLocaleString('PHP', { minimumFractionDigits: 2 })+'</td>' +
                            '</tr>';
        }

        service_table +=  '<tr>' +
                              '<td></td>' +
                              '<td class="text-center">' +
                                '<span class="text-dark fs-6 fw-bold ls-1 me-2">Total: '+total_services.toLocaleString('PHP', { minimumFractionDigits: 2 })+'</span>' +
                              '</td>' +
                            '</tr>';

        $.each(deduction, function(index, item){
          let deduction_amount = parseFloat(item.deduction_amount);
          deduction_table += '<tr>'+
                                '<td class="text-center ls-1">'+item.deduction_name+'</td>' +
                                '<td class="text-center ls-1">'+deduction_amount.toLocaleString('PHP', { minimumFractionDigits: 2 })+'</td>' +
                              '</tr>';
        });

        let total_deductions = parseFloat(bill.total_deductions);
        let total_net_bill = parseFloat(bill.total_net_bill);
        deduction_table += ' <tr>'+
                                '<td></td>' +
                                '<td class="text-center">' +
                                  '<span class="text-dark fs-6 fw-bold ls-1 me-2">Total: '+total_deductions.toLocaleString('PHP', { minimumFractionDigits: 2 })+'</span>' +
                                '</td>' +
                            '</tr>'+
                            '<tr>' +
                              '<td></td>' +
                              '<td>' +
                                '<span class="text-danger fs-6 fw-bold ls-1 me-2">Total Net Bill: '+total_net_bill.toLocaleString('PHP', { minimumFractionDigits: 2 })+'</span>' +
                              '</td>' +
                            '</tr>';

        $('#deduction-table').html(deduction_table);
        $('#service-table').html(service_table);
        $('#bill-fullname').html(fullname);
        $('#bill-hp-name').html(hp_name);
        $('#bill-loa-no').html(bill.loa_no);
           
      }
    });
  }

  const getTotalBill = () => {
    const coordinator_bill = document.querySelector('#total-coordinator-bill');
    const hospital_bill = document.querySelector('#total-hospital-bill');
    const variance = document.querySelector('#total-variance');
    const hp_filter = document.querySelector('#billed-hospital-filter').value;
    const end_date = document.querySelector('#end-date').value;
    const start_date = document.querySelector('#start-date').value;
    const button = document.querySelector('#proceed-btn');

    $.ajax({
      type: 'post',
      url: `${baseUrl}healthcare-coordinator/loa/total-bill/fetch`,
      dataType: "json",
      data: {
        'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
        'hp_id' : hp_filter,
        'startDate' : start_date,
        'endDate' : end_date,
      },
      success: function(response){
        hospital_bill.value = response.total_hospital_bill;
        coordinator_bill.value = response.total_coordinator_bill;
        variance.value = response.total_variance;
      },
    });
  }

  const backDate = (loa_id, loa_no) => {
    $('#managersKeyModal').modal('show');
    $('#expired-loa-id').val(loa_id);
    $('#expired-loa-no').val(loa_no);
    $('#mgr-username').val('');
    $('#mgr-username').removeClass('is-invalid');
    $('#mgr-username-error').html('');
    $('#mgr-password').val('');
    $('#mgr-password').removeClass('is-invalid');
    $('#mgr-password-error').html('');
  }

  const showBackDateForm = (loa_id, loa_no) => {
    $("#backDateModal").modal("show");
    $('#bd-loa-id').val(loa_id);
    $('#bd-loa-no').html(loa_no);
  }

  function GuaranteeLetter(billing_id) {
    $("#GuaranteeLetter").modal("show");
    $('#billing-id').val(billing_id);
  }


  function showPreview(input) {
    const preview = document.getElementById('preview');
    const imagePreview = document.getElementById('image-preview');
    const pdfPreview = document.getElementById('pdf-preview');

    if (input.files && input.files[0]) {
      const file = input.files[0];
      const reader = new FileReader();

      reader.onload = function(e) {
        if (file.type.startsWith('image')) {
          // Display Image preview
          const imageUrl=URL.createObjectURL(file);
          imagePreview.innerHTML=`<a href="${imageUrl}" target="_blank">View Image</a>`;
          imagePreview.style.display = 'block';
          pdfPreview.style.display = 'none';
        } else if (file.type === 'application/pdf') {
          // Display PDF preview
          const pdfUrl = URL.createObjectURL(file);
          pdfPreview.innerHTML = `<a href="${pdfUrl}" target="_blank">View PDF</a>`;
          pdfPreview.style.display = 'block';
          imagePreview.style.display = 'none';
        }
      };

      reader.readAsDataURL(file);
      preview.style.display = 'block';
    } else {
      preview.style.display = 'none';
    }
  }

  function enableDate() {
    var hospitalSelect = document.getElementById("billed-hospital-filter");
    var startDateInput = document.getElementById("start-date");
    var endDateInput = document.getElementById("end-date");
    var proceedButton = document.getElementById("proceed-btn");

    // Enable/disable date inputs based on hospital selection
    if (hospitalSelect.value !== "") {
      startDateInput.disabled = false;
      endDateInput.disabled = false;
    } else {
      startDateInput.disabled = true;
      endDateInput.disabled = true;
      startDateInput.value = "";
      endDateInput.value = "";
    }

    // Disable "Proceed" button if any required field is empty
    proceedButton.disabled = !isValidForm();
  }

  function validateDateRange() {
    var startDateInput = document.getElementById("start-date");
    var endDateInput = document.getElementById("end-date");
    var proceedButton = document.getElementById("proceed-btn");

    // Validate date range and enable/disable "Proceed" button accordingly
    if (startDateInput.value !== "" && endDateInput.value !== "") {
      // Perform date range validation here if needed

      // Enable "Proceed" button if the date range is valid
      proceedButton.disabled = !isValidForm();
    }
  }

  function checkHospitalSelection() {
    var proceedButton = document.getElementById("proceed-btn");
    proceedButton.disabled = !isValidForm();
  }

  function checkTotalHospitalBill() {
    var totalHospitalBillInput = document.getElementById("total-hospital-bill");
    var proceedButton = document.getElementById("proceed-btn");

    // Enable/disable "Proceed" button based on total hospital bill value
    if (totalHospitalBillInput.value === "0") {
      proceedButton.disabled = !isValidForm();
    }
  }

  function isValidForm() {
    var hospitalSelect = document.getElementById("billed-hospital-filter");
    var startDateInput = document.getElementById("start-date");
    var endDateInput = document.getElementById("end-date");

    // Check if all required fields are filled
    return (
      hospitalSelect.value !== "" &&
      startDateInput.value !== "" &&
      endDateInput.value !== ""
    );
  }

</script>
