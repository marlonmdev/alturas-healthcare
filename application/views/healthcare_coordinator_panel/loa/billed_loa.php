<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
  <div class="page-breadcrumb">
      <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title ls-2">Outpatient Billing</h4>
          <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">
                  Billed LOA
              </li>
              </ol>
          </nav>
          </div>
      </div>
      </div>
  </div>
  <!-- End Bread crumb and right sidebar toggle -->
  <!-- Start of Container fluid  -->
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <ul class="nav nav-tabs mb-4" role="tablist">
          <li class="nav-item">
            <a
              class="nav-link active"
              href="<?php echo base_url(); ?>healthcare-coordinator/bill/requests-list/billed"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Billed LOA</span></a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>healthcare-coordinator/bill/requests-list/for-charging"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">For Payment LOA</span></a
            >
          </li>
        </ul>
        <form method="POST" action="<?php echo base_url(); ?>healthcare-coordinator/loa/matched-bill/submit">
        <div class="row pt-2 pb-2">
              <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash() ?>">
                <div class="col-lg-5 ps-5 pb-3 pt-1 pb-4">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-dark text-white">
                            <i class="mdi mdi-filter"></i>
                            </span>
                        </div>
                        <select class="form-select fw-bold" name="billed-hospital-filter" id="billed-hospital-filter" oninput="enableDate()">
                                <option value="">Select Hospital</option>
                                <?php foreach($hcproviders as $option) : ?>
                                <option value="<?php echo $option['hp_id']; ?>"><?php echo $option['hp_name']; ?></option>
                                <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                
                <div class="col-lg-6 offset-1">
                      <div class="input-group">
                          <div class="input-group-append">
                              <span class="input-group-text bg-dark text-white ls-1 ms-2">
                                  <i class="mdi mdi-filter"></i>
                              </span>
                          </div>
                          <input type="date" class="form-control" name="start-date" id="start-date" oninput="validateDateRange()" placeholder="Start Date" disabled>

                          <div class="input-group-append">
                              <span class="input-group-text bg-dark text-white ls-1 ms-2">
                                  <i class="mdi mdi-filter"></i>
                              </span>
                          </div>
                          <input type="date" class="form-control" name="end-date" id="end-date" oninput="validateDateRange();enableProceedBtn()" placeholder="End Date" disabled>
                      </div>
                  </div>
                </div>
          
            <div class="card shadow" style="background-color:">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover table-responsive" id="billedLoaTable">
                    <thead style="background-color:#ded4c3">
                      <tr>
                        <th class="fw-bold">LOA No.</th>
                        <th class="fw-bold">Name</th>
                        <th class="fw-bold">LOA Type</th>
                        <th class="fw-bold">Coordinator Bill</th>
                        <th class="fw-bold">View Bill</th>
                        <th class="fw-bold">Hospital Bill</th>
                        <th class="fw-bold">View SOA</th>
                        <th class="fw-bold">Variance</th> 
                      </tr>
                    </thead>
                    <tbody id="billed-tbody">
                    </tbody>
                  </table>
                </div>
                <div class="row pt-4">
                  <div class="col-lg-2 offset-6">
                    <label>Total Coordinator Bill : </label>
                    <input name="total-coordinator-bill" id="total-coordinator-bill" class="form-control text-center fw-bold" value="0" readonly>
                  </div>
                  <div class="col-lg-2">
                    <label>Total Hospital Bill : </label>
                    <input name="total-hospital-bill" id="total-hospital-bill" class="form-control text-center fw-bold" value="0" readonly>
                  </div>
                  <div class="col-lg-2">
                    <label>Total Variance : </label>
                    <input name="total-variance" id="total-variance" class="form-control text-center fw-bold" value="0" readonly>
                  </div>
                </div>
              </div>
              <div class="offset-10 pt-2 pb-4">
                  <button class="btn btn-info fw-bold fs-5 btn-lg" type="submit" id="proceed-btn" disabled><i class="mdi mdi-send"></i> Proceed</button>
              </div>
              <?php include 'view_coordinator_bill_modal.php'; ?>
            </div>
      </div>
      </form>
      <!-- End Row  -->  
      </div>
    <!-- End Container fluid  -->
    </div>
  <!-- End Page wrapper  -->
  </div>
  <?php include 'view_pdf_bill_modal.php'; ?>
<!-- End Wrapper -->

<script>
  const baseUrl = "<?php echo base_url(); ?>";

  $(document).ready(function() {

    $('#billedLoaTable').submit(function(){
      $.ajax({
        url: $(this).attr('action'),
        data: {
          'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
          'hp_id' : $('#billed-hospital-filter').val(),
          'startDate' : $('#start-date').val(),
          'endDate' : $('#end-date').val(),
        },
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            const {
                token,status,message
            } = response;

            switch(status){
                
                case 'failed':
                    swal({
                        title: 'Error',
                        text: message,
                        timer: 3000,
                        showConfirmButton: true,
                        type: 'error'
                    });
                break;

                case 'success':
                    swal({
                        title: 'Success',
                        text: message,
                        timer: 3000,
                        showConfirmButton: false,
                        type: 'success'
                    });
                    setTimeout(function () {
                        window.location.href = '<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/for-charging';
                    }, 2600);
                    
                break;
            }
        }

      });
    });

    let billedTable = $('#billedLoaTable').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}healthcare-coordinator/loa/billed/fetch`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: function(data) {
            data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
            data.filter = $('#billed-hospital-filter').val();
            data.endDate = $('#end-date').val();
            data.startDate = $('#start-date').val();
          
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

    let columnIdx = 7;
    let rows = billedTable.rows().nodes();

    rows.each(function(index, row) {
        let rowData = billedTable.row(row).data();
        let columnValue = rowData[columnIdx];

        if (columnValue > 100) {
        $('#proceed-btn').prop('disabled', true); // disable the button
    } else {
        $('#proceed-btn').prop('disabled', false); // enable the button
    }
    });

    $('#billed-hospital-filter').change(function(){
      billedTable.draw();
      getTotalBill();
    });

    $('#start-date').change(function(){
      billedTable.draw();
      getTotalBill();
    });

    $('#end-date').change(function(){
      billedTable.draw();
      getTotalBill();
    });

    $("#start-date").flatpickr({
        dateFormat: 'Y-m-d',
    });

    $('#end-date').flatpickr({
        dateFormat: 'Y-m-d',
    });

  });

    const enableProceedBtn = () => {
      const hp_name = document.querySelector('#billed-hospital-filter');
      const start_date = document.querySelector('#start-date');
      const end_date = document.querySelector('#end-date');
      const button = document.querySelector('#proceed-btn');

      if(end_date.value == ''){
        button.setAttribute('disabled', true);
      }else{
        button.removeAttribute('disabled');
      }
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

    const getTotalBill = () => {
      const coordinator_bill = document.querySelector('#total-coordinator-bill');
      const hospital_bill = document.querySelector('#total-hospital-bill');
      const variance = document.querySelector('#total-variance');
      const hp_filter = document.querySelector('#billed-hospital-filter').value;
      const end_date = document.querySelector('#end-date').value;
      const start_date = document.querySelector('#start-date').value;
      const button = document.querySelector('#proceed-btn');

      $.ajax({
          type: 'post',
          url: `${baseUrl}healthcare-coordinator/loa/total-bill/fetch`,
          dataType: "json",
          data: {
              'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
              'hp_id' : hp_filter,
              'startDate' : start_date,
              'endDate' : end_date,
          },
          success: function(response){
            hospital_bill.value = response.total_hospital_bill;
            coordinator_bill.value = response.total_coordinator_bill;
            variance.value = response.total_variance;
          },

      });
    }

    const enableDate = () => {
        const hp_filter = document.querySelector('#billed-hospital-filter');
        const start_date = document.querySelector('#start-date');
        const end_date = document.querySelector('#end-date');

        if(hp_filter != ''){
            start_date.removeAttribute('disabled');
            start_date.style.backgroundColor = '#ffff';
            end_date.removeAttribute('disabled');
            end_date.style.backgroundColor = '#ffff';
        }else{
            start_date.setAttribute('disabled', true);
            start_date.value = '';
            end_date.setAttribute('disabled', true);
            end_date.value = '';
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
                    // timer: 4000,
                    showConfirmButton: true,
                    type: 'error'
                });
                endDateInput.value = '';
                return;
            }          
        }

</script>