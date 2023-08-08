<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">

        <a href="<?php echo base_url(); ?>healthcare-coordinator/bill/requests-list/for-charging" type="submit" class="btn btn-danger" data-bs-toggle="tooltip" title="Click to Go Back">
          <strong class="ls-2" style="vertical-align:middle"><i class="mdi mdi-arrow-left-bold"></i> Back</strong>
        </a>

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

        <input type="hidden" id="payment-no" value="<?php echo $payable['payment_no']; ?>">
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
  </div>

  <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash() ?>">
 <!--  <div class="card shadow"> -->
  <div class="card-body pt-0">
    <!-- <div class="table-responsive"> -->
      <table class="table table-hover table-responsive" id="billedLoaTable">
        <thead style="background-color:#ADD8E6">
          <tr>
            <th class="fw-bold" style="color: black;font-size:9px">BILLING #</th>
            <th class="fw-bold" style="color: black;font-size:9px">LOA #</th>
            <th class="fw-bold" style="color: black;font-size:9px">PATIENT NAME</th>
            <th class="fw-bold" style="color: black;font-size:9px">BUSINESS UNIT</th>
            <th class="fw-bold" style="color: black;font-size:9px">PERCENTAGE</th>
            <th class="fw-bold" style="color: black;font-size:9px">TYPE OF REQUEST</th>
            <th class="fw-bold" style="color: black;font-size:9px">COMPANY CHARGE</th>
            <th class="fw-bold" style="color: black;font-size:9px">PERSONAL CHARGE</th>
            <th class="fw-bold" style="color: black;font-size:9px">HEALTHCARE ADVANCE</th>
            <th class="fw-bold" style="color: black;font-size:9px">HOSPITAL BILL</th>
            <th class="fw-bold" style="color: black;font-size:9px">SUMMARY SOA</th>
            <th class="fw-bold" style="color: black;font-size:9px">DETAILED SOA</th>
          </tr>
        </thead>
        <tbody id="billed-tbody" style="font-size: 10px"></tbody>
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
  <?php include 'view_completed_loa_details.php'; ?>
  <?php include 'view_pdf_bill_modal.php'; ?>
  <?php include 'view_coordinator_bill_modal.php'; ?>
</div>

<!-- Patient Record -->
<!-- <div class="modal fade pt-4" id="patient_record_diagnostic" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title ls-2">PATIENT INFORMATION [<span id="patient_no" class="text-warning"></span>]</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
        <input type="hidden" name="emp-id" id="emp-id">
        <input type="hidden" name="billing-id" id="billing-id">

                      
        <div class="row">
          <div class="col-lg-4">
            <label class="fw-bold">Patient Name : </label>
            <input class="form-control" id="patient_name" readonly>
          </div>

          <div class="col-lg-4">
            <label class="fw-bold">Patient Address : </label>
            <input class="form-control" id="patient_address" readonly>
          </div>

          <div class="col-lg-4">
            <label class="fw-bold">Age : </label>
            <input class="form-control" id="patient_age" readonly>
          </div>

          <div class="col-lg-4">
            <label class="fw-bold">Percentage : </label>
            <input class="form-control" id="percentage" readonly>
          </div>

          <div class="col-lg-4">
            <label class="fw-bold">Hospital Provider : </label>
            <input class="form-control" id="hospital_provider" readonly>
          </div>

          <div class="col-lg-4">
            <label class="fw-bold">Type of Request : </label>
            <input class="form-control" id="type_of_request" readonly>
          </div>

          <div class="col-lg-12 pt-4">
            <table class="table table-bordered table-striped table-hover table-responsive table-sm ">
              <thead style="background-color:#00538c;text-align:center">
                <tr>
                  <th style="color:#fff">DATE</th>
                  <th style="color:#fff">NAME</th>
                  <th style="color:#fff">DESCRIPTION</th>
                  <th style="color:#fff">QUANTITY</th>
                  <th style="color:#fff">UNIT PRICE</th>
                  <th style="color:#fff">AMOUNT</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>  
        </div><br>

        <div class="row pt-3">
          <div class="col-sm-12 mb-sm-0 d-flex justify-content-end">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="mdi mdi-close-box"></i> CANCEL</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> -->
<!-- End -->






<script>
  const baseUrl = "<?php echo base_url(); ?>";
  const bill_no = document.querySelector('#bill-no').value;
    
  $(document).ready(function(){
    
    let billedTable = $('#billedLoaTable').DataTable({
      processing: true,
      serverSide: true,
      order: [],

      ajax: {
        url: `${baseUrl}healthcare-coordinator/loa/monthly-bill/fetch/${bill_no}`,
        type: "POST",
        data: function(data) {
          data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
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

    billedTable.on('draw.dt', function() {
      let columnId = 6;
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


  const viewPDFBill = (pdf_bill,loa_no) => {
    $('#viewPDFBillModal').modal('show');
    $('#pdf-loa-no').html(loa_no);
    console.log('pdf',pdf_bill);
     console.log('loa_no',loa_no);
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

  const viewDetailedPDFBill = (pdf_bill,loa_no) => {
    $('#viewPDFBillModal').modal('show');
    $('#pdf-loa-no').html(loa_no);

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

  const viewCoordinatorBill = (loa_id) => {
    $('#viewCoordinatorBillModal').modal('show');
    $.ajax({
      url: `${baseUrl}healthcare-coordinator/loa/billing/fetch/${loa_id}`,
      type: 'GET',
      dataType: 'json',
      success: function(data){
        let bill = data.bill;
        let service = data.service;
        let deduction = data.deduction;

        let deduction_table = '';
        let service_table = '';
        let fullname = '';
        let hp_name = '';

        fullname += bill.first_name+' '+bill.middle_name+' '+bill.last_name+' '+bill.suffix;
        hp_name += bill.hp_name;
    
        $.each(service, function(index, item){
          let op_price = parseFloat(item.op_price);
            
          service_table += ' <tr> ' +
                                '<td class="text-center ls-1">'+item.item_description+'</td>' +
                                '<td class="text-center ls-1">'+op_price.toLocaleString('PHP', { minimumFractionDigits: 2 })+'</td>' +
                            '</tr>' ;
        });

        let total_services = parseFloat(bill.total_services);
        if(parseFloat(bill.medicines) != ''){
          service_table +=  '<tr>' +
                                '<tr><td></td><td></td></tr>' +
                                '<td class="text-center ls-1">Medicines</td>' +
                                '<td class="text-center ls-1">'+parseFloat(bill.medicines).toLocaleString('PHP', { minimumFractionDigits: 2 })+'</td>' +
                            '</tr>';
        }
        service_table +=  '<tr>' +
                                '<td></td>' +
                                '<td class="text-center">' +
                                    '<span class="text-dark fs-6 fw-bold ls-1 me-2">Total: '+total_services.toLocaleString('PHP', { minimumFractionDigits: 2 })+'</span>' +
                                '</td>' +
                          '</tr>';

        $.each(deduction, function(index, item){
          let deduction_amount = parseFloat(item.deduction_amount);

          deduction_table += '<tr>'+
                                '<td class="text-center ls-1">'+item.deduction_name+'</td>' +
                                '<td class="text-center ls-1">'+deduction_amount.toLocaleString('PHP', { minimumFractionDigits: 2 })+'</td>' +
                              '</tr>';
        });

        let total_deductions = parseFloat(bill.total_deductions);
        let total_net_bill = parseFloat(bill.total_net_bill);
        deduction_table += ' <tr>'+
                                  '<td></td>' +
                                  '<td class="text-center">' +
                                      '<span class="text-dark fs-6 fw-bold ls-1 me-2">Total: '+total_deductions.toLocaleString('PHP', { minimumFractionDigits: 2 })+'</span>' +
                                  '</td>' +
                            '</tr>'+
                            '<tr>' +
                                  '<td></td>' +
                                  '<td>' +
                                      '<span class="text-danger fs-6 fw-bold ls-1 me-2">Total Net Bill: '+total_net_bill.toLocaleString('PHP', { minimumFractionDigits: 2 })+'</span>' +
                                  '</td>' +
                            '</tr>';

        $('#deduction-table').html(deduction_table);
        $('#service-table').html(service_table);
        $('#bill-fullname').html(fullname);
        $('#bill-hp-name').html(hp_name);
        $('#bill-loa-no').html(bill.loa_no);
           
      }
    });
  }

</script>