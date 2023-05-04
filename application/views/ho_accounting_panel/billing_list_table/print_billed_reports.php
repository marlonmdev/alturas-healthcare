<!-- Start of Page Wrapper -->
<div class="page-wrapper">
        <!-- Bread crumb and right sidebar toggle -->
        <div class="page-breadcrumb">
            <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title ls-2"></h4>
                <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item">Head Office Accounting</li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Print Report
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
            <div class="col-lg-12">
                <ul class="nav nav-tabs mb-4" role="tablist"> 
                    <li class="nav-item">
                        <a
                            class="nav-link active"
                            href="<?php echo base_url(); ?>head-office-accounting/bill/billing-list/billed-loa-noa"
                            role="tab"
                            ><span class="hidden-sm-up"></span>
                            <span class="hidden-xs-down fs-5 font-bold">Billed</span></a
                        >
                    </li>
                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="<?php echo base_url(); ?>head-office-accounting/billing-list/closedm"
                            role="tab"
                            ><span class="hidden-sm-up"></span>
                            <span class="hidden-xs-down fs-5 font-bold">For Payment</span></a
                        >
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-lg-3 ps-5 pb-3 pt-1 pb-4">
                    <div class="input-group">
                        <input type="hidden" name="token" id="token" value="<?php echo $this->security->get_csrf_hash(); ?>">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-info text-white">
                            <i class="mdi mdi-filter"></i>
                            </span>
                        </div>
                        <select class="form-select fw-bold" name="report-filter" id="report-filter" onchange="viewContainer()">
                            <option value="">Please Select...</option>
                            <option value="billing">Print Summary Billing</option>
                            <option value="charging">Print Business Unit Charging</option>
                        </select>
                    </div>
                </div>
               
            </div>
            <div  id="billing-container" style="display:none">
                <div class="row pt-2">
                    <div class="col-lg-3 ps-5 pb-3 pt-1 pb-4">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-dark fw-bold">
                                Filter Hospital : 
                                </span>
                            </div>
                            <select class="form-select fw-bold" name="billed-hospital-filter" id="billed-hospital-filter">
                                <option value="">Select Hospital...</option>
                                <?php foreach($hc_provider as $hospital) : ?>
                                <option value="<?php echo $hospital['hp_id']; ?>"><?php echo $hospital['hp_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 pt-1">
                        <div class="input-group">
                            <div class="input-group-prepend ">
                                <span class="input-group-text text-dark fw-bold">
                                Business Unit : 
                                </span>
                            </div>
                            <select class="form-select fw-bold" name="billed-bu-filter" id="billed-bu-filter">
                                <option value="">Select Business Units...</option>
                                <?php foreach($business_unit as $bu) : ?>
                                <option value="<?php echo $bu['business_unit']; ?>"><?php echo $bu['business_unit']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <!-- <div class="col-lg-6 pt-1" id="bu-units-wrapper"> -->
                              <!-- business units will be appended here... -->
                            <!-- </div> -->

                        </div>
                    </div>
                    <div class="col-lg-4 pt-1 offset-1">
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text text-dark ls-1 ms-2">
                                        <i class="mdi mdi-calendar-range"></i>
                                    </span>
                                </div>
                                <input type="date" class="form-control" name="start_date" id="start-date" oninput="validateDateRange()" placeholder="Start Date">

                                <div class="input-group-append">
                                    <span class="input-group-text text-dark ls-1 ms-2">
                                        <i class="mdi mdi-calendar-range"></i>
                                    </span>
                                </div>
                                <input type="date" class="form-control" name="end-date" id="end-date" oninput="validateDateRange()" placeholder="End Date">
                            </div>
                        </div>
                    </div>
                    <div class="pt-1 offset-10">
                        <button class="btn btn-danger ls-1" onclick="printDiv('#printableDiv')"><i class="mdi mdi-printer"></i> Print </button>
                    </div>
                </div>
                    <div class="row" id="printableDiv" style="background:#ffff;padding:20px 40px;">
                        <div class="card shadow"  id="billing-table" style="display:noe">
                            <div class="card-body">
                             <!-- <?php if($month == '01'){
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
                                    }?>
                             <div class="text-center">
                                    <h4>ALTURAS HEALTHCARE SYSTEM</h4>
                                    <h4>Billing Summary Details</h4>
                                    <h5>For the Month of <?php echo $word_month; ?>, <?php echo $year; ?></h5>
                                    <?php  $prev_payment_no = null; ?>
                                    <?php foreach($payment_no as $pay) : 
                                        if ($pay['payment_no'] != $prev_payment_no) { // check if bill number is different from previous
                                            echo '<h5>' . $pay['payment_no'] . '</h5>'; // display bill number
                                            
                                            $prev_payment_no = $pay['payment_no']; // set current bill number as previous for next iteration
                                        }?>
                                        <input type="hidden" id="payment_no" value="<?php echo $pay['payment_no']; ?>">
                                    <?php endforeach; ?>
                                     -->
                                </div>
                                <div class="pt-2">
                                    <table class="table table-sm" id="billedTable">
                                        <thead>
                                            <tr class="border-secondary border-2 border-0 border-top border-bottom">
                                                <th class="text-center fw-bold ls-2"><strong>Billing No</strong></th>
                                                <th class="text-center fw-bold ls-2"><strong>LOA/NOA #</strong></th>
                                                <th class="text-center fw-bold ls-2"><strong>Employee Name</strong></th>
                                                <th class="text-center fw-bold ls-2"><strong>Business Unit</strong></th>
                                                <th class="text-center fw-bold ls-2"><strong>Remaining MBL</strong></th>
                                                <th class="text-center fw-bold ls-2"><strong>Hospital Bill</strong></th>
                                                <th class="text-center fw-bold ls-2"><strong>Percentage</strong></th>
                                                <th class="text-center fw-bold ls-2"><strong>Subtotal Payable</strong></th>
                                            </tr>
                                        </thead>
                                        <tbody class="pt-2 text-center" id="billing-tbody" >
                                 
                                        </tbody>
                                        <tfoot class="text-center">
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>       
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="fw-bold">TOTAL PAYABLE</td>
                                                <td class="fw-bold text-center ls-1"><span id="total_bill"></span></td>
                                            </tr>
                                        </tfoot>
                                    </table><br>
                                    <div class="row offset-1 pt-4 ps-5">
                                        <div class="col-4">
                                            <span>Prepared by : </span><br><br>
                                            <span class="text-decoration-underline fw-bold fs-5">__<?php echo $user; ?>__</span>
                                        </div>
                                        <div class="col-4">
                                            <span>Audited by : </span><br><br>
                                            <span class="">_______________________</span>
                                        </div>
                                        <div class="col-4">
                                            <span>Noted by : </span><br><br>
                                            <span class="">_______________________</span>
                                        </div>
                                    </div>
                                    <br><br><br>
                                </div>
                            </div>
                        </div> 
            <div  id="charging-container" style="display:none">
                <div class="row">
                    <div class="col-lg-4 ps-5">
                        <div class="input-group">
                            <div class="input-group-prepend ">
                                <span class="input-group-text text-dark fw-bold">
                                Business Unit : 
                                </span>
                            </div>
                            <select class="form-select fw-bold" name="charging-bu-filter" id="charging-bu-filter">
                                <option value="">Select Business Units...</option>
                                <?php foreach($business_unit as $bu) : ?>
                                <option value="<?php echo $bu['business_unit']; ?>"><?php echo $bu['business_unit']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <!-- <div class="col-lg-6 pt-1" id="business-units-wrapper"> -->
                              <!-- business units will be appended here... -->
                            <!-- </div> -->

                        </div>
                    </div>
                    <div class="col-lg-4 offset-1">
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text text-dark ls-1 ms-2">
                                        <i class="mdi mdi-calendar-range"></i>
                                    </span>
                                </div>
                                <input type="date" class="form-control" name="charging-start-date" id="charging-start-date" oninput="" placeholder="Start Date" oninput="validateDate()">

                                <div class="input-group-append">
                                    <span class="input-group-text text-dark ls-1 ms-2">
                                        <i class="mdi mdi-calendar-range"></i>
                                    </span>
                                </div>
                                <input type="date" class="form-control" name="charging-end-date" id="charging-end-date" oninput="" placeholder="End Date" oninput="validateDate()">
                            </div>
                        </div>
                        
                    </div>
                    <div class="pb-2 pt-4 offset-10">
                        <button class="btn btn-danger ls-1" onclick="printDivs('#printableDivs')"><i class="mdi mdi-printer"></i> Print </button>
                    </div>
                </div>
                <div class="row" id="printableDivs" style="background:#ffff;padding:20px 40px;">
                <div class="card shadow pt-2"  id="charging-table" style="display:nne">
                    <div class="card-body">
                        <table class="table table-sm" id="billedChargingTable">
                            <thead>
                                <tr>
                                    <th class="fw-bold">LOA No.</th>
                                    <th class="fw-bold">Name</th>
                                    <th class="fw-bold">Business Unit</th>
                                    <th class="fw-bold">Percentage</th>
                                    <th class="fw-bold">Total Net Bill</th>
                                    <th class="fw-bold">Company Charge</th>
                                    <th class="fw-bold">Personal Charge</th>
                                    <th class="fw-bold">Previous MBL</th>
                                    <th class="fw-bold">Remaining MBL</th>
                                </tr>
                            </thead>
                            <tbody class="text-center" id="billed-charging-tbody">
                            </tbody>
                            <tfoot class="text-center">
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>    
                                    <td class="fw-bold">TOTAL </td>
                                    <td class="fw-bold text-center ls-1"><span id="charging_total_bill"></span></td>
                                    <td class="fw-bold text-center ls-1"><span id="charging_total_company"></span></td>
                                    <td class="fw-bold text-center ls-1"><span id="charging_total_personal"></span></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table><br>
                        <div class="row offset-1 pt-4 ps-5">
                            <div class="col-4">
                                <span>Prepared by : </span><br><br>
                                <span class="text-decoration-underline fw-bold fs-5">__<?php echo $user; ?>__</span>
                            </div>
                            <div class="col-4">
                                <span>Audited by : </span><br><br>
                                <span class="">_______________________</span>
                            </div>
                            <div class="col-4">
                                <span>Noted by : </span><br><br>
                                <span class="">_______________________</span>
                            </div>
                        </div>
                        <br><br><br>
                    </div>
                </div>
            </div>   
        </div>
     </div>
    </div>
  
<script>
  const baseUrl = "<?php echo base_url(); ?>";
    const printDiv = (layer) => {
        if (!layer.isPrinted) { // check if the layer has not been printed yet
            $(layer).printThis({
                importCSS: true,
                copyTagClasses: true,
                copyTagStyles: true,
                removeInline: false,
            });
            layer.isPrinted = true; // set the isPrinted flag to true
        }
    };

    const printDivs = (layer) => {
    $(layer).printThis({
      importCSS: true,
      copyTagClasses: true,
      copyTagStyles: true,
      removeInline: false,
    });
  }
 $(document).ready(function(){
    let billedTable = $('#billedTable').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}head-office-accounting/bill/billed/fetch`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: function(data) {
           data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
            data.hp_id = $('#billed-hospital-filter').val();
            data.endDate = $('#end-date').val();
            data.startDate = $('#start-date').val();
            data.business_unit = $('#billed-bu-filter').val();
          
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

    billedTable.on('draw.dt', function() {
    let columnIdx = 7;
    let sum = 0;
    let rows = billedTable.rows().nodes();
    if ($('#billedTable').DataTable().data().length > 0) {
            // The table is not empty
            rows.each(function(index, row) {
                let rowData = billedTable.row(row).data();
                let columnValue = rowData[columnIdx];
                let pattern = /-?[\d,]+(\.\d+)?/g;
                let matches = columnValue.match(pattern);
                if (matches && matches.length > 0) {
                    let numberString = matches[0].replace(',', '');
                    let intValue = parseInt(numberString);
                    sum += intValue;
                }
            });
        }
        $('#total_bill').html(sum.toLocaleString('PHP', { minimumFractionDigits: 2 }));
    });

    let chargingTable = $('#billedChargingTable').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}head-office-accounting/bill/charging/fetch`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: function(data) {
           data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
            data.endDate = $('#charging-end-date').val();
            data.startDate = $('#charging-start-date').val();
            data.business_unit = $('#charging-bu-filter').val();
          
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

    chargingTable.on('draw.dt', function() {
    let columnIdx = 4;
    let sum = 0;
    let rows = chargingTable.rows().nodes();
    if ($('#billedChargingTable').DataTable().data().length > 0) {
            // The table is not empty
            rows.each(function(index, row) {
                let rowData = chargingTable.row(row).data();
                let columnValue = rowData[columnIdx];
                let pattern = /-?[\d,]+(\.\d+)?/g;
                let matches = columnValue.match(pattern);
                if (matches && matches.length > 0) {
                    let numberString = matches[0].replace(',', '');
                    let intValue = parseInt(numberString);
                    sum += intValue;
                }
            });
        }
        $('#charging_total_bill').html(sum.toLocaleString('PHP', { minimumFractionDigits: 2 }));
    });
    chargingTable.on('draw.dt', function() {
    let columnIdx = 5;
    let sum = 0;
    let rows = chargingTable.rows().nodes();
    if ($('#billedChargingTable').DataTable().data().length > 0) {
            // The table is not empty
            rows.each(function(index, row) {
                let rowData = chargingTable.row(row).data();
                let columnValue = rowData[columnIdx];
                let pattern = /-?[\d,]+(\.\d+)?/g;
                let matches = columnValue.match(pattern);
                if (matches && matches.length > 0) {
                    let numberString = matches[0].replace(',', '');
                    let intValue = parseInt(numberString);
                    sum += intValue;
                }
            });
        }
        $('#charging_total_company').html(sum.toLocaleString('PHP', { minimumFractionDigits: 2 }));
    });
    chargingTable.on('draw.dt', function() {
    let columnIdx = 6;
    let sum = 0;
    let rows = chargingTable.rows().nodes();
    if ($('#billedChargingTable').DataTable().data().length > 0) {
            // The table is not empty
            rows.each(function(index, row) {
                let rowData = chargingTable.row(row).data();
                let columnValue = rowData[columnIdx];
                let pattern = /-?[\d,]+(\.\d+)?/g;
                let matches = columnValue.match(pattern);
                if (matches && matches.length > 0) {
                    let numberString = matches[0].replace(',', '');
                    let intValue = parseInt(numberString);
                    sum += intValue;
                }
            });
        }
        $('#charging_total_personal').html(sum.toLocaleString('PHP', { minimumFractionDigits: 2 }));
    });


    $("#start-date").flatpickr({
        dateFormat: 'Y-m-d',
    });
    $("#end-date").flatpickr({
        dateFormat: 'Y-m-d',
    });
    
    $("#charging-start-date").flatpickr({
        dateFormat: 'Y-m-d',
    });
    
    $("#charging-end-date").flatpickr({
        dateFormat: 'Y-m-d',
    });

    $('#end-date').change(function(){
        billedTable.draw();
    });
    $('#start-date').change(function(){
        billedTable.draw();
    });
    $('#billed-hospital-filter').change(function(){
        billedTable.draw();
    });
    $('#billed-bu-filter').change(function(){
        billedTable.draw();
    });
    $('#charging-end-date').change(function(){
        chargingTable.draw();
    });
    $('#charging-start-date').change(function(){
        chargingTable.draw();
    });
    $('#charging-bu-filter').change(function(){
        chargingTable.draw();
    });

 }) ;

 window.onload = function() {
    getBusinessUnits();
    getBUnits();
 }

 const viewContainer = () => {
    const report_filter = document.querySelector('#report-filter');
    const billing_cont = document.querySelector('#billing-container');
    const billing_table = document.querySelector('#billing-table');
    const charging_table = document.querySelector('#charging-table');
    const charging_cont = document.querySelector('#charging-container');

    if (report_filter.value == 'billing') {
        billing_cont.style.display = 'block';
        billing_table.style.display = 'block';
        charging_table.style.display = 'none';
        charging_cont.style.display = 'none';
    } else {
        billing_cont.style.display = 'none';
        billing_table.style.display = 'none';
        charging_table.style.display = 'block';
        charging_cont.style.display = 'block';
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

    const validateDate = () => {
        const startDateInput = document.querySelector('#charging-start-date');
        const endDateInput = document.querySelector('#charging-end-date');
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

    const getBusinessUnits = () => {
        $.ajax({
              url: `${baseUrl}head-office-accounting/get-business-units`,
              type: "GET",
              dataType: "json",
              success:function(response){

                $('#business-units-wrapper').empty();                

                $('#business-units-wrapper').append(response);

                $(".chosen-select").chosen({
                  width: "100%",
                  no_results_text: "Oops, nothing found!"
                }); 
              }
          });
    }

    const getBUnits = () => {
        $.ajax({
              url: `${baseUrl}head-office-accounting/fetch-business-units`,
              type: "GET",
              dataType: "json",
              success:function(response){

                $('#bu-units-wrapper').empty();                

                $('#bu-units-wrapper').append(response);

                $(".chosen-select").chosen({
                  width: "100%",
                  no_results_text: "Oops, nothing found!"
                }); 
              }
          });
    }




</script>
   
</html>