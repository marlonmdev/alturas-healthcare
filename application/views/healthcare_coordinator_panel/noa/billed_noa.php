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
              <span class="hidden-xs-down fs-5 font-bold">BILLED NOA</span>
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
                      <th style="color: white">VIEW SOA</th>
                      <th style="color: white">HOSPITAL BILL</th>
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








<script>
  const baseUrl = "<?php echo base_url(); ?>";
  $(document).ready(function() {

    let billedTable = $('#billedLoaTable').DataTable({
      processing: true,
      serverSide: true,
      order: [],

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}healthcare-coordinator/noa/billed/final_billing`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: function(data) {
          data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
          data.filter = $('#billed-hospital-filter').val();
          data.endDate = $('#end-date').val();
          data.startDate = $('#start-date').val();
        }
      },

      //Set column definition initialisation properties.
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

</script>