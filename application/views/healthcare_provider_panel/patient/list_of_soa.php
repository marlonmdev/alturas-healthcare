<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
  <div class="page-breadcrumb">
      <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
      <h4 class="page-title ls-2">List of SOA</h4>
          <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Provider</li>
              </ol>
          </nav>
          </div>
      </div>
      </div>
  </div>
  <!-- <hr> -->
  <!-- End Bread crumb and right sidebar toggle -->
  <!-- Start of Container fluid  -->
  <div class="container-fluid">
  <div class="row pt-2">
                    <div class="col-lg-4 ps-5 pb-3 pt-1 pb-4">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-dark fw-bold">
                                Filter : 
                                </span>
                            </div>
                            <select class="form-select fw-bold" name="filter" id="filter" onchange="displayValue()">
                                <option value="">Please Select...</option>
                                <option value="LOA">LOA</option>
                                <option value="NOA">NOA</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 pt-1 offset-">
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text text-dark ls-1 ms-2">
                                        <i class="mdi mdi-calendar-range"></i>
                                    </span>
                                </div>
                                <input type="date" class="form-control" name="start_date" id="start-date" oninput="validateDateRange()" placeholder="Start Date" onchange="displayValue()">

                                <div class="input-group-append">
                                    <span class="input-group-text text-dark ls-1 ms-2">
                                        <i class="mdi mdi-calendar-range"></i>
                                    </span>
                                </div>
                                <input type="date" class="form-control" name="end-date" id="end-date" oninput="validateDateRange()" placeholder="End Date" onchange="displayValue()">
                                  
                              </div>
                        </div>
              
                        <div class="col-lg-2 pt-1">
                        <div class="text-center">
                          <div class="input-group">
                            <input type="text" id="searchInput" class="form-control">
                            <div class="input-group-append">
                              <span class="input-group-text">
                                <i class="mdi mdi-magnify"></i>
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>

                        <div class="col-lg-2 pt-1">            
                            <button class="btn btn-danger ls-1" onclick="printDiv('#printableDiv')" title="click to print data"><i class="mdi mdi-printer"></i> Print</button>
                        </div>
                    </div>
                    
                
    <div class="row  pt-1">
      <div class="col-lg-12">
        <div class="row pt-2 pb-2">
            <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash() ?>">
            <!-- <input type="hidden" name="pd-payment-no" id="pd-payment-no" value="<?php echo $payment_no ?>"> -->
            <div class="card shadow" >
              <div id="printableDiv">
                <div class="text-center pt-4">
                      <h4>ALTURAS HEALTHCARE SYSTEM</h4>
                      <h4>List of SOA</h4>
                      <h4><?php echo $hp_name; ?></h4>
                </div>
                <div class="pt-3 pb-2">
                      <span class="fw-bold fs-5 ps-2 pt-1" id="b-date"></span>
                </div>
                <div class="card-body">
                  <div class="table" id="table">
                    <table class="table table-hover table-responsive" id="paidTable">
                      <thead style="background-color:#eddcb7">
         
                        <tr class="border-secondary border-2 border-0 border-top border-bottom">
                                                <th class="fw-bold ls-2"><strong>Billing No</strong></th>
                                                <th class="fw-bold ls-2"><strong>LOA/NOA #</strong></th>
                                                <th class="fw-bold ls-2"><strong>Patient Name</strong></th>
                                                <th class="fw-bold ls-2"><strong>Business Unit</strong></th>
                                                <th class="fw-bold ls-2"><strong>Current MBL</strong></th>
                                                <th class="fw-bold ls-2"><strong>Percentage</strong></th>
                                                <th class="fw-bold ls-2"><strong>Hospital Bill</strong></th>
                                                <th class="fw-bold ls-2"><strong>Personal Charge</strong></th>
                                                <th class="fw-bold ls-2"><strong>Company Charge</strong></th>
                                                <th class="fw-bold ls-2"><strong>Healthcare Advance</strong></th>
                                                <th class="fw-bold ls-2"><strong>Healthcare Advance Status</strong></th>
                                                <th class="fw-bold ls-2"><strong>Total Payable of AGC</strong></th>
                                            </tr>
                
                      </thead>
                      <tbody id="billed-tbody">
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
             
            </div>
           
      </div>
      <!-- End Row  -->  
      </div>
    <!-- End Container fluid  -->
    </div>
    <div id="pagination-container"></div>
    <?php include 'view_pdf_bill_modal.php'; ?>
  <!-- End Page wrapper  -->
  </div>
<!-- End Wrapper -->
<style>
@media print {
  #printableDiv {
    width: 100%;
    height: auto;
    margin: 0;
    padding: 0;
  }
}
</style>
<script>

  
const printDiv = (layer) => {
    $(layer).printThis({
        importCSS: true,
        copyTagClasses: true,
        copyTagStyles: true,
        removeInline: false,
    });
    };

     const baseUrl = "<?php echo base_url(); ?>";
    //  const payment_no = document.querySelector('#pd-payment-no').value;

 $(document).ready(function(){
    let billedTable = $('#paidTable').DataTable({
      lengthMenu: [5],
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}healthcare-provider/patient/fetch-lis-of-soa`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: function(data) {
            data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
            data.endDate = $('#end-date').val();
            data.startDate = $('#start-date').val();
            data.loa_noa = $('#filter').val(); 
            data.searchInput = $('#searchInput').val();
        },
      },
      //Set column definition initialisation properties.
      columnDefs: [{
        "orderable": false, //set not orderable
      }, ],
      data: [],  // Empty data array
      deferRender: true,  // Enable deferred rendering
      info: false,
      paging: true,
      filter: false,
      lengthChange: false,
      responsive: true,
      fixedHeader: true,
    });

    billedTable.on('draw.dt', function() {
    let columnIdx = 10;
    let sum = 0;
    let rows = billedTable.rows().nodes();
    if ($('#paidTable').DataTable().data().length > 0) {
            // The table is not empty
            rows.each(function(index, row) {
                let rowData = billedTable.row(row).data();
                let columnValue = rowData[columnIdx];
                let pattern = /-?[\d,]+(\.\d+)?/g;
                let matches = columnValue.match(pattern);
                if (matches && matches.length > 0) {
                    let numberString = matches[0].replace(',', '');
                    let intValue = parseInt(numberString);
                    sum += intValue;
                }
            });
        }
        $('#pd-total-bill').html(sum.toLocaleString('PHP', { minimumFractionDigits: 2 }));
    });

    $("#start-date").flatpickr({
        dateFormat: 'Y-m-d',
    });
    $("#end-date").flatpickr({
        dateFormat: 'Y-m-d',
    });

    $('#end-date').change(function(){
        billedTable.draw();
    });
    $('#start-date').change(function(){
        billedTable.draw();
    });
    $('#filter').change(function(){
      billedTable.draw();
    });
    $('#searchInput').on('keyup', function() {
      // billedTable.search(this.value).draw();
      billedTable.draw();
    });

 });

 const viewPDFsoa = (pdf_bill,noa_no,loa_no) => {
      $('#viewPDFBillModal').modal('show');
      if(noa_no != ''){
        $('#pdf-loa-no').html(noa_no);
      }else{
        $('#pdf-loa-no').html(loa_no);
      }

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

    const displayValue = () => {

    const startDate = new Date(document.querySelector('#start-date').value);
    const endDate = new Date(document.querySelector('#end-date').value);

    const options = { month: 'long', day: '2-digit', year: 'numeric' };
    const formattedStartDate = startDate.toLocaleDateString('en-US', options);
    const formattedEndDate = endDate.toLocaleDateString('en-US', options);

    const bDate = document.querySelector('#b-date');

    if(document.querySelector('#start-date').value || document.querySelector('#end-date').value != ''){
        bDate.textContent = 'Date : '+formattedStartDate + ' to ' + formattedEndDate;
    }else{
        bDate.textContent = '';
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
                showConfirmButton: true,
                type: 'error'
            });
            endDateInput.value = '';
            return;
        }          
    }

</script>