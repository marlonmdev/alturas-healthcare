<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
  <div class="page-breadcrumb">
      <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <?php if($payable['month'] == '01'){
				$month = 'January';
			}else if($payable['month'] == '02'){
				$month = 'February';
			}else if($payable['month'] == '03'){
				$month = 'March';
			}else if($payable['month'] == '04'){
				$month = 'April';
			}else if($payable['month'] == '05'){
				$month = 'May';
			}else if($payable['month'] == '06'){
				$row[] = $payable['hp_name'];
				$month = 'June';
			}else if($payable['month'] == '07'){
				$month = 'July';
			}else if($payable['month'] == '08'){
				$month = 'August';
			}else if($payable['month'] == '09'){
				$month = 'September';
			}else if($payable['month'] == '10'){
				$month = 'October';
			}else if($payable['month'] == '11'){
				$month = 'November';
			}else if($payable['month'] == '12'){
				$month = 'December';
			}
        ?>
        <h4 class="page-title ls-2">Consolidated Billing for the Month of <?php echo $month . ', ' . $payable['year']; ?> [Inpatient]</h4>
      <input type="hidden" id="payment-no" value="<?php echo $payable['payment_no']; ?>">
          <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">
                  Billed NOA
              </li>
              <li class="breadcrumb-item"><?php echo $payable['hp_name']; ?></li>
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
    <div class="row pt-2">
      <div class="col-lg-12">
        <div class="row pt-2 pb-2">
            <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash() ?>">
                
            <div class="card shadow" style="background-color:">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover table-responsive" id="billedLoaTable">
                    <thead style="background-color:#eddcb7">
                      <tr>
                        <th class="fw-bold">Billing No.</th>
                        <th class="fw-bold">Name</th>
                        <th class="fw-bold">Healthcare Bill</th>
                        <th class="fw-bold">View SOA</th>
                      </tr>
                    </thead>
                    <tbody id="billed-tbody">
                    </tbody>
                  </table>
                </div>
                <div class="row pt-4 pb-2">
                  <div class="col-lg-2 offset-7">
                    <label>Total Hospital Bill : </label>
                    <input name="total-hospital-bill" id="total-hospital-bill" class="form-control text-center fw-bold" value="0" readonly>
                  </div>
                </div>
              </div>
                <div class="col-12 offset-10 mb-4 mt-2">
                    <div class="input-group">
                        <a href="<?php echo base_url(); ?>healthcare-coordinator/bill/noa-requests/for-charging" type="submit" class="btn btn-info" data-bs-toggle="tooltip" title="Click to Go Back">
                            <strong class="ls-2" style="vertical-align:middle">
                                <i class="mdi mdi-arrow-left-bold"></i> Go Back
                            </strong>
                        </a>
                    </div>
                </div>
            </div>
      </div>
      <?php include 'view_pdf_bill_modal.php'; ?>
      <!-- End Row  -->  
      </div>
    <!-- End Container fluid  -->
    </div>
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
        url: `${baseUrl}healthcare-coordinator/noa/monthly-bill/fetch/${payment_no}`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: function(data) {
            data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
        }
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
 });

 window.onload = function() {
    getTotalBill();
 }

 const getTotalBill = () => {
      const hospital_bill = document.querySelector('#total-hospital-bill');
      const payment_no = document.querySelector('#payment-no').value;

      $.ajax({
          type: 'post',
          url: `${baseUrl}healthcare-coordinator/noa/matched/total-bill/fetch`,
          dataType: "json",
          data: {
              'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
              'payment_no' : payment_no,
          },
          success: function(response){
            hospital_bill.value = response.total_hospital_bill;
          },

      });
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


</script>