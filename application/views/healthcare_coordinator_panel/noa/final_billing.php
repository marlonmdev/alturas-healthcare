<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">Final Billing (Inpatient)</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">Final Billing</li>
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
            <a class="nav-link active" href="<?php echo base_url(); ?>healthcare-coordinator/bill/noa-requests/billed" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">FINAL BILLING</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-coordinator/bill/noa-requests/for_payment" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">FOR PAYMENT</span>
            </a>
          </li>
        </ul>

        <form id="billedTableLoaNoa" method="POST" action="<?php echo base_url(); ?>healthcare-coordinator/noa/matched-bill/submit">
          <input type="hidden" class="form-control" name="initial_status" id="initial_status" value="Payable">
          <div class="row pt-2 pb-2">
            <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash() ?>">
            <div class="col-lg-5 ps-5 pb-3 pt-1 pb-4">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text bg-dark text-white"><i class="mdi mdi-filter"></i></span>
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
                  <span class="input-group-text bg-dark text-white ls-1 ms-2"><i class="mdi mdi-filter"></i></span>
                </div>
                <input type="date" class="form-control" name="start-date" id="start-date" oninput="validateDateRange()" placeholder="Start Date" disabled>

                <div class="input-group-append">
                  <span class="input-group-text bg-dark text-white ls-1 ms-2"><i class="mdi mdi-filter"></i></span>
                </div>
                <input type="date" class="form-control" name="end-date" id="end-date" oninput="validateDateRange();enableProceedBtn()" placeholder="End Date" disabled>
              </div>
            </div>
          </div>
          
          <div class="card shadow" style="background-color:">
            <div class="card-body">
              <div class="">
                <table class="table table-hover table-responsive" id="billedLoaTable">
                  <thead style="background-color:#00538C">
                    <tr>
                      <th style="color: white">NOA NO.</th>
                      <th style="color: white">NAME OF PATIENT</th>
                      <th style="color: white">MBL REMAINING BALANCE</th>
                      <th style="color: white">WORK RELATED</th>
                      <th style="color: white">REQUEST DATE</th>
                      <th style="color: white">BILLED DATE</th>
                      <th style="color: white">COMPANY CHARGE</th>
                      <th style="color: white">PERSONAL CHARGE</th>
                      <th style="color: white">VIEW SOA</th>
                      <th style="color: white">HOSPITAL BILL</th>
                      <th style="color: white">STATUS</th>
                      <th style="color: white">ACTION</th>
                    </tr>
                  </thead>
                  <tbody id="billed-tbody">
                  </tbody>
                </table>
              </div>
              <div class="row pt-4">
                <div class="col-lg-2 offset-9">
                  <label>Total Hospital Bill : </label>
                  <input name="total-hospital-bill" id="total-hospital-bill" class="form-control text-center fw-bold" value="0" readonly>
                </div>
              </div>
            </div><br><br>
            <div class="offset-10 pt-2 pb-4">
              <button class="btn btn-info fw-bold fs-5 btn-lg" type="submit" id="proceed-btn" ><i class="mdi mdi-send"></i> Proceed</button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
  <?php include 'view_pdf_bill_modal.php'; ?>
</div>

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
  const baseUrl = "<?php echo base_url(); ?>";
  $(document).ready(function() {

    let billedTable = $('#billedLoaTable').DataTable({
      processing: true,
      serverSide: true,
      order: [],

      ajax: {
        url: `${baseUrl}healthcare-coordinator/noa/billed/final_billing`,
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

    let columnIdx = 7;
    let rows = billedTable.rows().nodes();

    rows.each(function(index, row) {
      let rowData = billedTable.row(row).data();
      let columnValue = rowData[columnIdx];

      if (columnValue > 100) {
        $('#proceed-btn').prop('disabled', true); // disable the button
      }else{
        $('#proceed-btn').prop('disabled', false); // enable the button
      }
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

  const enableProceedBtn = () => {
    const hp_name = document.querySelector('#billed-hospital-filter');
    const start_date = document.querySelector('#start-date');
    const end_date = document.querySelector('#end-date');
    const button = document.querySelector('#proceed-btn');

    if(end_date.value == ''){
      button.setAttribute('disabled', true);
    }else{
      button.removeAttribute('disabled');
    }
  }

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

  const getTotalBill = () => {
    const hospital_bill = document.querySelector('#total-hospital-bill');
    const hp_filter = document.querySelector('#billed-hospital-filter').value;
    const end_date = document.querySelector('#end-date').value;
    const start_date = document.querySelector('#start-date').value;
    const button = document.querySelector('#proceed-btn');

    $.ajax({
      type: 'post',
      url: `${baseUrl}healthcare-coordinator/noa/total-bill/fetch`,
      dataType: "json",
      data: {
        'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
        'hp_id' : hp_filter,
        'startDate' : start_date,
        'endDate' : end_date,
      },
      success: function(response){
      hospital_bill.value = response.total_hospital_bill;
      },
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
      return;
    }

    if (endDate < startDate) {
      swal({
        title: 'Failed',
        text: 'End date must be greater than or equal to the start date',
        showConfirmButton: true,
        type: 'error'
      });
      endDateInput.value = '';
      return;
    }          
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
</script>