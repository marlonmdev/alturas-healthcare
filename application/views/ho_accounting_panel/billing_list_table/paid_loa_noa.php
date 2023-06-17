<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2"><i class="mdi mdi-checkbox-multiple-marked"></i> Paid Bill</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Head Office Accounting</li>
              <li class="breadcrumb-item active" aria-current="page">Paid</li>
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
                    <a
                        class="nav-link"
                        href="<?php echo base_url(); ?>head-office-accounting/bill/billing-list/billed-loa-noa"
                        role="tab"
                        ><span class="hidden-sm-up"></span>
                        <span class="hidden-xs-down fs-5 font-bold">Billed</span></a
                    >
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        href="<?php echo base_url(); ?>head-office-accounting/billing-list/for-payment"
                        role="tab"
                        ><span class="hidden-sm-up"></span>
                        <span class="hidden-xs-down fs-5 font-bold">For Payment</span></a
                    >
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link active"
                        href="<?php echo base_url(); ?>head-office-accounting/billing-list/paid-bill"
                        role="tab"
                        ><span class="hidden-sm-up"></span>
                        <span class="hidden-xs-down fs-5 font-bold">Paid Bill</span></a
                    >
                </li>
            </ul>
        </div>

        <div class="card shadow">
          <div class="card-body">
            <div class="">
              <table class="table table-hover table-responsive" id="matchedLoaTable">
                <thead style="background-color:#00538C">
                  <tr>
                    <th style="color: white;">PAID BILLING</th>
                    <th style="color: white;">DATE BILLED</th>
                    <th style="color: white;">HEALTHCARE PROVIDER</th>
                    <th style="color: white;">PAID ON</th>
                    <th style="color: white;">STATUS</th>
                    <th style="color: white;">ACTION</th>
                  </tr>
                </thead>
                <tbody class="fs-5">
                </tbody>
              </table>
            </div>
            <?php include 'view_check_voucher.php';?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  const baseUrl = '<?php echo base_url();?>'
  $(document).ready(function() {
    let matchedTable = $('#matchedLoaTable').DataTable({
      processing: true,
      serverSide: true,
      order: [],

      // Load data for the table's content from an Ajax source  
      ajax: {
        url: '<?php echo base_url();?>head-office-accounting/bill/paid-bill/fetch',
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: function(data) {
          data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
        //   data.filter = $('#matched-hospital-filter').val();
        }
      },

      //Set column definition initialisation properties.
      columnDefs: [{
        "targets": [], // numbering column
        "orderable": false, //set not orderable
      }, ],
      info: false,
      paging: false,
      filter: false,
      lengthChange: false,
      responsive: true,
      fixedHeader: true,
    });
  });

  const viewCheckVoucher = (pdf_check_v) => {
        $('#viewCVModal').modal('show');
        $('#cancel').hide();
        let pdfFile = `${baseUrl}uploads/paymentDetails/${pdf_check_v}`;
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
                let iframe = document.querySelector('#pdf-cv-viewer');
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