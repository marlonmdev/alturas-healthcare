<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
  <div class="page-breadcrumb">
      <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
      <h4 class="page-title ls-2"><i class="mdi mdi-checkbox-multiple-marked"></i> Payment No : <?php echo $payment_no; ?></h4>
          <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
              <li class="breadcrumb-item">Head Office Accounting</li>
              <li class="breadcrumb-item active" aria-current="page">
                  Paid
              </li>
              </ol>
          </nav>
          </div>
      </div>
      </div>
  </div>
  <hr>
  <!-- End Bread crumb and right sidebar toggle -->
  <!-- Start of Container fluid  -->
  <div class="container-fluid">
    <div class="col-12 pb-2">
        <div class="input-group">
            <a href="<?php echo base_url(); ?>head-office-accounting/billing-list/paid-bill" type="submit" class="btn btn-info" data-bs-toggle="tooltip" title="Click to Go Back">
                <strong class="ls-2" style="vertical-align:middle">
                    <i class="mdi mdi-arrow-left-bold"></i> Go Back
                </strong>
            </a>
        </div>
    </div>
    <div class="row pt-3 pt-1">
      <div class="col-lg-12">
        <div class="row pt-2 pb-2">
            <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash() ?>">
            <input type="hidden" name="pd-payment-no" id="pd-payment-no" value="<?php echo $payment_no ?>">
            <div class="card shadow" style="background-color:">
              <div id="printableDiv">
                <div class="text-center pt-4">
                      <h4>ALTURAS HEALTHCARE SYSTEM</h4>
                      <h4>Paid Billing Summary</h4>
                      <h5><?php echo $pay['hp_name']; ?></h5>
                      <h6><?php echo date('F d, Y', strtotime($pay['startDate'])).' to '.date('F d, Y', strtotime($pay['endDate']))?></h6>
                      <h6><?php echo $payment_no; ?></h6>
                </div>
                <div class="card-body">
                  <input type="hidden" id="p-hp-id" value="<?php echo $pay['hp_id'];?>">
                  <input type="hidden" id="p-start-date" value="<?php echo $pay['startDate'];?>">
                  <input type="hidden" id="p-end-date" value="<?php echo $pay['endDate'];?>">

                  <div class="table">
                    <table class="table table-hover table-responsive" id="paidTable">
                      <thead style="background-color:#eddcb7">
                        <tr>
                          <th class="fw-bold">Billing No.</th>
                          <th class="fw-bold">LOA/NOA #</th>
                          <th class="fw-bold">Patient Name</th>
                          <th class="fw-bold">Remaining MBL</th>
                          <th class="fw-bold">Percentage</th>
                          <th class="fw-bold">Hospital Bill</th>
                          <th class="fw-bold">Company Charge</th>
                          <th class="fw-bold">Healthcare Advance</th>
                          <th class="fw-bold">Total Paid Bill</th>
                          <th class="fw-bold">Personal Charge</th>
                          <th class="fw-bold">Status</th>
                          <th class="fw-bold">View SOA</th>
                        </tr>
                      </thead>
                      <tbody id="billed-tbody">
                      </tbody>
                      <tfoot>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="fw-bold">TOTAL BILL </td>
                        <td><span class="text-danger fw-bold fs-5" id="pd-total-bill"></span></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="col pt-4 offset-10">
              <button class="btn btn-danger ls-1" onclick="printPaidBill()" title="click to print data"><i class="mdi mdi-printer"></i> Print </button>
            </div>
      </div>
      <!-- End Row  -->  
      </div>
    <!-- End Container fluid  -->
    </div>
    <?php include 'view_pdf_bill_modal.php'; ?>
  <!-- End Page wrapper  -->
  </div>
<!-- End Wrapper -->

<script>
     const baseUrl = "<?php echo base_url(); ?>";
     const payment_no = document.querySelector('#pd-payment-no').value;

 $(document).ready(function(){
    
    let billedTable = $('#paidTable').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}head-office-accounting/bill/monthly-paid/fetch`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: function(data) {
            data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
            data.payment_no = payment_no;
        },
      },
      //Set column definition initialisation properties.
      columnDefs: [{
        "orderable": false, //set not orderable
      }, ],
      data: [],  // Empty data array
      deferRender: true,  // Enable deferred rendering
      info: false,
      paging: false,
      filter: false,
      lengthChange: false,
      responsive: true,
      fixedHeader: true,
    });

    billedTable.on('draw.dt', function() {
    let columnIdx = 8;
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
                let floatValue = parseFloat(numberString);
                sum += floatValue;
            }
        });
    }
    $('#pd-total-bill').html(sum.toLocaleString('PHP', { minimumFractionDigits: 2 }));
});

 });

 const viewPDFBill = (pdf_bill,noa_no,loa_no) => {
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

    const printPaidBill = () => {
      const hp_id = document.querySelector('#p-hp-id').value;
      const start_date = document.querySelector('#p-start-date').value;
       const end_date = document.querySelector('#p-end-date').value;

       var base_url = `${baseUrl}`;
        var win = window.open(base_url + "printpaid/pdfbilling/" + btoa(hp_id) + "/" + btoa(start_date) + "/" + btoa(end_date), '_blank');
    }


</script>