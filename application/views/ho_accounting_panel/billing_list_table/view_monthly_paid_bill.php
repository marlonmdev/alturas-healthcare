<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
  <div class="page-breadcrumb">
      <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <?php if($month == '01'){
				$word_month = 'January';
			}else if($month == '02'){
				$word_month = 'February';
			}else if($month == '03'){
				$word_month = 'March';
			}else if($month == '04'){
				$word_month = 'April';
			}else if($month == '05'){
				$word_month = 'May';
			}else if($month == '06'){
				$word_month = 'June';
			}else if($month == '07'){
				$word_month = 'July';
			}else if($month == '08'){
				$word_month = 'August';
			}else if($month == '09'){
				$word_month = 'September';
			}else if($month == '10'){
				$word_month = 'October';
			}else if($month == '11'){
				$word_month = 'November';
			}else if($month == '12'){
				$word_month = 'December';
			}
        ?>
        <h4 class="page-title ls-2">Paid Bill for the Month of <?php echo $word_month . ', ' . $year; ?></h4>
        <input type="hidden" id="m-month" value="<?php echo $month; ?>">
        <input type="hidden" id="m-word-month" value="<?php echo $word_month; ?>">
        <input type="hidden" id="m-year" value="<?php echo $year; ?>">
        <input type="hidden" id="m-hp-id" value="<?php echo  $hc_provider['hp_id']; ?>">
        <input type="hidden" id="m-hospital-name" value="<?php echo $hc_provider['hp_name']; ?>">
          <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
              <li class="breadcrumb-item">Head Office Accounting</li>
              <li class="breadcrumb-item active" aria-current="page">
                  Paid
              </li>
              <li class="breadcrumb-item"><?php echo $hc_provider['hp_name']; ?></li>
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
            <a href="<?php echo base_url(); ?>head-office-accounting/bill/billing-list/paid-loa-noa" type="submit" class="btn btn-info" data-bs-toggle="tooltip" title="Click to Go Back">
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
                
            <div class="card shadow" style="background-color:">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover table-responsive" id="billedLoaTable">
                    <thead style="background-color:#eddcb7">
                      <tr>
                        <th class="fw-bold">Billing No.</th>
                        <th class="fw-bold">LOA/NOA #</th>
                        <th class="fw-bold">Name</th>
                        <th class="fw-bold">Healthcare Bill</th>
                        <th class="fw-bold">Status</th>
                        <th class="fw-bold">View SOA</th>
                      </tr>
                    </thead>
                    <tbody id="billed-tbody">
                    </tbody>
                  </table>
                </div>

                <div class="row col-lg-4 offset-6 pt-4 pb-3">
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text text-dark fw-bold ls-1 fs-6">TOTAL PAID BILL: </span>
                        </div>
                        <input type="text" class="form-control ps-3 text-danger fw-bold fs-6" value="0" name="total-hospital-bill" id="total-hospital-bill" readonly>
                       
                    </div>
                </div>
              </div>
            </div>
      </div>
      <?php include 'view_pdf_bill_modal.php'; ?>
      <!-- End Row  -->  
      </div>
      <?php include 'payment_details_modal.php'; ?>
    <!-- End Container fluid  -->
    </div>

  <!-- End Page wrapper  -->
  </div>
<!-- End Wrapper -->

<script>
     const baseUrl = "<?php echo base_url(); ?>";
     const hp_id = document.querySelector('#m-hp-id').value;
     const month = document.querySelector('#m-month').value;
     const year = document.querySelector('#m-year').value;
     const hospital_bill = document.querySelector('#total-hospital-bill');

 $(document).ready(function(){
    
    let billedTable = $('#billedLoaTable').DataTable({
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
            data.hp_id = hp_id;
            data.month = month;
            data.year = year;
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

 });

 window.onload = function() {
    getTotalBill();
 }

  const getTotalBill = () => {
      $.ajax({
          type: 'post',
          url: `${baseUrl}head-office-accounting/bill/paid/total-bill/fetch`,
          dataType: "json",
          data: {
              'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
              'hp_id' : hp_id,
              'month' : month,
              'year' : year,
          },
          success: function(response){
            hospital_bill.value = response.total_bill;
          },

      });
    }

    const viewPDFBill = (pdf_bill,noa_no,loa_no) => {
      $('#viewPDFBillModal').modal('show');
      if(loa_no != ''){
        $('#pdf-loa-no').html(loa_no);
      }else if(noa_no != ''){
        $('#pdf-loa-no').html(noa_no);
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


</script>