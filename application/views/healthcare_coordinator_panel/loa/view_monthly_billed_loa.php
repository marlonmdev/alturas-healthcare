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
<<<<<<< HEAD

        <h4 class="page-title ls-2">Consolidated Billing for the Month of <?php echo $month . ', ' . $payable['year']; ?>.</h4>
        <input type="hidden" id="payment-no" value="<?php echo $payable['payment_no']; ?>">
=======
        <h4 class="page-title ls-2">Consolidated Billing for the Month of <?php echo $month . ', ' . $payable['year']; ?> [Outpatient]</h4>
      <input type="hidden" id="bill-no" value="<?php echo $payable['bill_no']; ?>">
>>>>>>> 9db59a518b04c989b30b58c89e4bbd46fcac24c9
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Healthcare Coordinator</li>
                <li class="breadcrumb-item active" aria-current="page">History</li>
                <li class="breadcrumb-item"><?php echo $payable['hp_name']; ?></li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row pt-2">
        <div class="col-12 offset-11 mb-4 mt-2">
          <div class="input-group">
            <a href="<?php echo base_url(); ?>healthcare-coordinator/bill/requests-list/for-charging" type="submit" class="btn btn-info" data-bs-toggle="tooltip" title="Click to Go Back">
              <strong class="ls-2" style="vertical-align:middle"><i class="mdi mdi-arrow-left-bold"></i> Back</strong>
            </a>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="row pt-2 pb-2">
            <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash() ?>">
                
            <div class="card shadow" style="background-color:">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover table-responsive" id="billedLoaTable">
                    <thead style="background-color:#00538C">
                      <tr>
<<<<<<< HEAD
                        <th class="fw-bold" style="color: white;">Billing NO.</th>
                        <th class="fw-bold" style="color: white;">PATIENT NAME</th>
                        <th class="fw-bold" style="color: white;">TYPE OF REQUEST</th>
                        <th class="fw-bold" style="color: white;">COORDINATOR BILL</th>
                        <th class="fw-bold" style="color: white;">VIEW</th>
                        <th class="fw-bold" style="color: white;">HEALTHCARE BILL</th>
                        <th class="fw-bold" style="color: white;">VIEW</th>
=======
                        <th class="fw-bold">Billing No.</th>
                        <th class="fw-bold">Name</th>
                        <th class="fw-bold">Business Unit</th>
                        <th class="fw-bold">LOA Type</th>
                        <th class="fw-bold">Coordinator Bill</th>
                        <th class="fw-bold">View Bill</th>
                        <th class="fw-bold">Healthcare Bill</th>
                        <th class="fw-bold">View SOA</th>
>>>>>>> 9db59a518b04c989b30b58c89e4bbd46fcac24c9
                      </tr>
                    </thead>
                    <tbody id="billed-tbody">
                    </tbody>
                  </table>
                </div>
                <div class="row pt-4 pb-2">
                  <div class="col-lg-2 offset-7">
                    <label>Total Coordinator Bill : </label>
                    <input name="total-coordinator-bill" id="total-coordinator-bill" class="form-control text-center fw-bold" value="0" readonly>
                  </div>
                  <div class="col-lg-2 ">
                    <label>Total Hospital Bill : </label>
                    <input name="total-hospital-bill" id="total-hospital-bill" class="form-control text-center fw-bold" value="0" readonly>
                  </div>
                </div>
              </div>
              
            </div>
            <?php include 'view_completed_loa_details.php'; ?>
          </div>
          <?php include 'view_pdf_bill_modal.php'; ?>
        </div>
        <?php include 'view_coordinator_bill_modal.php'; ?>
      </div>
    </div>
  </div>
</div>


<script>
     const baseUrl = "<?php echo base_url(); ?>";
     const bill_no = document.querySelector('#bill-no').value;
    
 $(document).ready(function(){
    
    let billedTable = $('#billedLoaTable').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}healthcare-coordinator/loa/monthly-bill/fetch/${bill_no}`,
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
      const coordinator_bill = document.querySelector('#total-coordinator-bill');
      const hospital_bill = document.querySelector('#total-hospital-bill');
      const bill_no = document.querySelector('#bill-no').value;

      $.ajax({
          type: 'post',
          url: `${baseUrl}healthcare-coordinator/loa/matched/total-bill/fetch`,
          dataType: "json",
          data: {
              'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
              'bill_no' : bill_no,
          },
          success: function(response){
            hospital_bill.value = response.total_hospital_bill;
            coordinator_bill.value = response.total_coordinator_bill;
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