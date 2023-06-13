<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
  <div class="page-breadcrumb">
      <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
      <h4 class="page-title ls-2"><i class="mdi mdi-format-float-none"></i> For Payment </h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item">Head Office Accounting</li>
                  <li class="breadcrumb-item active" aria-current="page">
                      Billed
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
    <div class="row">
      <div class="col-6 pb-2">
          <div class="input-group">
              <a href="<?php echo base_url(); ?>head-office-accounting/billing-list/for-payment" type="submit" class="btn btn-info" data-bs-toggle="tooltip" title="Click to Go Back">
                  <strong class="ls-2" style="vertical-align:middle">
                      <i class="mdi mdi-arrow-left-bold"></i> Go Back
                  </strong>
              </a>
          </div>
      </div>
    </div>
    <div class="row pt-3 pt-1">
      <div class="col-lg-12">
        <div class="row pt-2 pb-2">
            <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash() ?>">
            <input type="hidden" name="payment-no" id="payment-no" value="<?php echo $payment_no ?>">
            <div class="card shadow" style="background-color:">
            <?php 
              $formatedStartDate = date('F d, Y', strtotime($pay['startDate']));
              $formatedEndDate = date('F d, Y', strtotime($pay['endDate']));
              if($pay['startDate'] == '0000-00-00' || $pay['endDate'] == '0000-00-00'){
                $date = '';
              }else{
                $date = '<h6>From '.$formatedStartDate.' to '.$formatedEndDate.'</h6>';
              }
            ?>
                  <div class="text-center pt-4">
                        <h4>ALTURAS HEALTHCARE SYSTEM</h4>
                        <h4>For Payment Billing Summary</h4>
                        <h5><?php echo $pay['hp_name']; ?></h5>
                        <?php echo $date; ?>
                        <h6><?php echo $payment_no; ?></h6>
                  </div>
                <div class="card-body">
                  <input type="hidden" id="p-hp-id" value="<?php echo $pay['hp_id'];?>">
                  <input type="hidden" id="p-start-date" value="<?php echo $pay['startDate'];?>">
                  <input type="hidden" id="p-end-date" value="<?php echo $pay['endDate'];?>">

                  <div class=" table-responsive">
                    <table class="table table-hover table-responsive" id="billedLoaTable">
                      <thead style="background-color:#eddcb7">
                        <tr>
                            <th class="fw-bold ls-2"><strong>Billing No</strong></th>
                            <th class="fw-bold ls-2"><strong>LOA/NOA #</strong></th>
                            <th class="fw-bold ls-2"><strong>Patient Name</strong></th>
                            <th class="fw-bold ls-2"><strong>Business Unit</strong></th>
                            <th class="fw-bold ls-2"><strong>Current MBL</strong></th>
                            <th class="fw-bold ls-2"><strong>Percentage</strong></th>
                            <th class="fw-bold ls-2"><strong>Hospital Bill</strong></th>
                            <th class="fw-bold ls-2"><strong>Company Charge</strong></th>
                            <th class="fw-bold ls-2"><strong>Healthcare Advance</strong></th>
                            <th class="fw-bold ls-2"><strong>Total Payable</strong></th>
                            <th class="fw-bold ls-2"><strong>Personal Charge</strong></th>
                            <th class="fw-bold ls-2"><strong>Remaining MBL</strong></th>
                            <th class="fw-bold ls-2"><strong>SOA</strong></th>
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
                        <td></td>
                        <td class="fw-bold">TOTAL BILL </td>
                        <td><span class="text-danger fw-bold fs-5" id="mt-total-bill"></span></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tfoot>
                    </table>
                  </div>
                </div>
            </div>
            <div class="col pt-4 offset-10">
              <button class="btn btn-danger ls-1" onclick="printPayment()" title="click to print data"><i class="mdi mdi-printer"></i> Print </button>
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
     const payment_no = document.querySelector('#payment-no').value;

 $(document).ready(function(){
    
    let billedTable = $('#billedLoaTable').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}head-office-accounting/bill/monthly-payment/fetch`,
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
        let columnIdx = 9;
        let sum = 0;
        let rows = billedTable.rows().nodes();
        if ($('#billedLoaTable').DataTable().data().length > 0) {
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
        $('#mt-total-bill').html(sum.toLocaleString('PHP', { minimumFractionDigits: 2 }));
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

    const printPayment = () => {
      const hp_id = document.querySelector('#p-hp-id').value;
      const start_date = document.querySelector('#p-start-date').value;
       const end_date = document.querySelector('#p-end-date').value;

       var base_url = `${baseUrl}`;
        var win = window.open(base_url + "printpayment/pdfbilling/" + btoa(hp_id) + "/" + btoa(start_date) + "/" + btoa(end_date), '_blank');
    }


</script>