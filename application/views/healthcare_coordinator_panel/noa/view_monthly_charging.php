<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <?php
          if($payable['month'] == '01'){
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
        <a href="<?php echo base_url(); ?>healthcare-coordinator/bill/noa-requests/for_payment" type="submit" class="btn btn-danger" data-bs-toggle="tooltip" title="Click to Go Back"><strong class="ls-2" style="vertical-align:middle"><i class="mdi mdi-arrow-left-bold"></i> Back</strong></a>
        <input type="hidden" id="bill-no" value="<?php echo $payable['bill_no']; ?>">
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Month of</li>
              <li class="breadcrumb-item active" aria-current="page"><?php echo $month . ', ' . $payable['year']; ?></li>
              <li class="breadcrumb-item"><?php echo $payable['hp_name']; ?></li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div><hr>

  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="row pt-2 pb-2">
          <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash() ?>">
          <table class="table-responsive table-hover" id="billedLoaTable">
            <thead style="background-color:#ADD8E6">
              <tr>
                <th style="color:black;font-size:10px">BILLING #</th>
                <th style="color:black;font-size:10px">NOA NO.</th>
                <th style="color:black;font-size:10px">PATIENT NAME</th>
                <th style="color:black;font-size:10px">BUSINESS UNIT</th>
                <th style="color:black;font-size:10px">PERCENTAGE</th>
                <th style="color:black;font-size:10px">TYPE OF REQUEST</th>
                <th style="color:black;font-size:10px">COMPANY CHARGE</th>
                <th style="color:black;font-size:10px">PERSONAL CHARGE</th>
                <th style="color:black;font-size:10px">CASH ADVANCE</th>
                <th style="color:black;font-size:10px">HOSPITAL BILL</th>
                <th style="color:black;font-size:10px">SUMMARY SOA</th>
                <th style="color:black;font-size:10px">DETAILED SOA</th>
              </tr>
            </thead>
            <tbody id="billed-tbody" style="color:black;font-size:10px">
            </tbody>
          </table>
        </div>
        <div class="row pt-2 pb-2 offset-8">
          <div class="col-lg-5">
            <label>Total Hospital Bill :</label>
          </div>
          <div class="col-lg-4">
            <input name="total-hospital-bill" id="total-hospital-bill" class="form-control text-center fw-bold" value="0" readonly>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include 'view_pdf_bill_modal.php'; ?>
</div>

<script>
  const baseUrl = "<?php echo base_url(); ?>";
  const bill_no = document.querySelector('#bill-no').value;
    
  // $(document).ready(function(){
    
  //   let billedTable = $('#billedLoaTable').DataTable({
  //     processing: true,
  //     serverSide: true,
  //     order: [],

  //     // Load data for the table's content from an Ajax source
  //     ajax: {
  //       url: `${baseUrl}healthcare-coordinator/noa/monthly-bill/charging/${bill_no}`,
  //       type: "POST",
  //       // passing the token as data so that requests will be allowed
  //       data: function(data) {
  //           data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
  //       }
  //     },
  //     //Set column definition initialisation properties.
  //     columnDefs: [{
  //       "orderable": false, //set not orderable
  //     }, ],
  //     data: [],  // Empty data array
  //     deferRender: true,  // Enable deferred rendering
  //     info: false,
  //     paging: false,
  //     filter: false,
  //     lengthChange: false,
  //     responsive: true,
  //     fixedHeader: true,
  //   });

  //   billedTable.on('draw.dt', function() {
  //     let columnId = 9;
  //     let sum = 0;
  //     let rowss = billedTable.rows().nodes();

  //     if ($('#billedLoaTable').DataTable().data().length > 0) {
  //       // The table is not empty
  //       rowss.each(function(index, row) {
  //         let rowData = billedTable.row(row).data();
  //         let columnValue = rowData[columnId];
  //         let pattern = /-?[\d,]+(\.\d+)?/g;
  //         let matches = columnValue.match(pattern);

  //         if (matches && matches.length > 0) {
  //           let numberString = matches[0].replace(/,/g, ''); // Replace all commas
  //           let floatValue = parseFloat(numberString);
  //           sum += floatValue;
  //         }
  //       });
  //     }
  //     $('#total-hospital-bill').html(sum.toLocaleString('PHP', { minimumFractionDigits: 2 }));
  //   });
  // });

  $(document).ready(function() {
    let billedTable = $('#billedLoaTable').DataTable({
        processing: true,
        serverSide: true,
        order: [],

        // Load data for the table's content from an Ajax source
        ajax: {
            url: `${baseUrl}healthcare-coordinator/noa/monthly-bill/charging/${bill_no}`,
            type: "POST",
            // passing the token as data so that requests will be allowed
            data: function(data) {
                data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
            }
        },
        // Set column definition initialisation properties.
        columnDefs: [{
            "orderable": false, // set not orderable
        }, ],
        data: [], // Empty data array
        deferRender: true, // Enable deferred rendering
        info: false,
        paging: false,
        filter: false,
        lengthChange: false,
        responsive: true,
        fixedHeader: true,
    });

    billedTable.on('draw.dt', function() {
        let columnId = 9; // Column index for "HOSPITAL BILL"
        let sum = 0;
        let rowss = billedTable.rows().nodes();

        if ($('#billedLoaTable').DataTable().data().length > 0) {
            // The table is not empty
            rowss.each(function(index, row) {
                let rowData = billedTable.row(row).data();
                let columnValue = rowData[columnId];
                let pattern = /-?[\d,]+(\.\d+)?/g;
                let matches = columnValue.match(pattern);

                if (matches && matches.length > 0) {
                    let numberString = matches[0].replace(/,/g, ''); // Replace all commas
                    let floatValue = parseFloat(numberString);
                    sum += floatValue;
                }
            });
        }
        $('#total-hospital-bill').val(sum.toLocaleString('PHP', { minimumFractionDigits: 2 }));
    });
});

  const viewPDFBill = (pdf_bill,noa_no) => {
    $('#viewPDFBillModal').modal('show');
    $('#pdf-loa-no').html(noa_no);
    // console.log('pdf',pdf_bill);
    //  console.log('noa_no',noa_no);
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

  const viewDetailedPDFBill = (pdf_bill,noa_no) => {
    $('#viewPDFBillModal').modal('show');
    $('#pdf-loa-no').html(noa_no);

    let pdfFile = `${baseUrl}uploads/itemize_bills/${pdf_bill}`;
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

  function viewImage(path) {
    let item = [{
      src: path, // path to image
      title: 'Hospital Receipt' // If you skip it, there will display the original image name
    }];
    // define options (if needed)
    let options = {
      index: 0 // this option means you will start at first image
    };
    // Initialize the plugin
    let photoviewer = new PhotoViewer(item, options);
  }
</script>

<style>
  .table-responsive {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
    border-bottom: 1px solid #ddd;
  }

  th, td {
    text-align: left;
    padding: 3px;
  }

  tr:nth-child(even){background-color: #f2f2f2}
</style>