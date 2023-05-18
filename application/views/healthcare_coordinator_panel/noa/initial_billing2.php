<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <a href="<?php echo base_url(); ?>healthcare-coordinator/bill/noa-requests/billed/initial" type="submit" class="btn btn-info" data-bs-toggle="tooltip" title="Click to Go Back">
          <strong class="ls-2" style="vertical-align:middle"><i class="mdi mdi-arrow-left-bold"></i> Back</strong>
        </a>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">Initial Billing</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row">
      <div class="col-12 offset-11 mb-4 mt-2">
        <div class="input-group">
          
        </div>
      </div>
      <div class="col-lg-12">
      	<div class="card shadow">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-responsive" id="ledgertable">
                <thead class="fs-5"style="background-color:#00538C">
                  <tr>
                    <th class="fw-bold" style="color: white;">NAME OF PATIENT</th>
                    <th class="fw-bold" style="color: white;">REMAINING BALANCE</th>
                    <th class="fw-bold" style="color: white;">BILLING #</th>
                    <th class="fw-bold" style="color: white;">DATE UPLOAD</th>
                    <th class="fw-bold" style="color: white;">VIEW SOA</th>
                    <th class="fw-bold" style="color: white;">HOSPITAL BILL</th> 
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $totalBill = 0;
                    foreach($billing as $key => $ledger){ 
                      $totalBill += $ledger['initial_bill'];
                  ?>
                    <tr>
                      <td><?php echo $key === 0 ? $ledger['first_name'].' '.$ledger['middle_name'].' '.$ledger['last_name'] : ''; ?></td>
                      <td><?php echo $key === 0 ? '₱' . number_format($ledger['remaining_balance'], 2) : ''; ?></td>
                      <td><?php echo $ledger['billing_no']; ?></td>
                      <td><?php echo date('F d, Y', strtotime($ledger['date_uploaded'])); ?></td>
                      <td>
                        <a href="JavaScript:void(0)" onclick="viewPDFBill('<?php echo $ledger['pdf_bill']; ?>', '<?php echo $ledger['noa_no']; ?>')" data-bs-toggle="tooltip" title="View SOA">
                          <i class="mdi mdi-file-pdf fs-2 text-danger"></i>
                        </a>
                      </td>
                      <td><?php echo number_format($ledger['initial_bill'], 2); ?></td>
                    </tr>
                  <?php }?>
                  <tr>
                    <td colspan="4"></td>
                    <td colspan="1" style="text-align: right"><b style="font-size:15px">TOTAL BILL :</b></td>
                    <td colspan="4"><b style="font-size:15px">₱ <?php echo number_format($totalBill, 2); ?></b></td>
                  </tr>
                </tbody> 
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include 'view_pdf_bill_modal.php'; ?>
</div>

<script type="text/javascript">
  const baseUrl = "<?php echo base_url()?>";
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