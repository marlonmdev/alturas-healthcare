<!-- Start of Page Wrapper -->
<div class="page-wrapper">
        <!-- Bread crumb and right sidebar toggle -->
        <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block flex-column flex-sm-row align-items-left">
                <h4 class="page-title ls-2"> <i class="mdi mdi-file-document-box"></i> Non-Affiliated Hospital Billing</h4>
                <div class="ms-auto text-end order-first order-sm-last">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item">Head Office Accounting</li>
                    <li class="breadcrumb-item active" aria-current="page">
                    Non-Affiliated Hospital
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
                            href="<?php echo base_url(); ?>head-office-accounting/bill/non-accredited/billed-loa-noa"
                            role="tab"
                            ><span class="hidden-sm-up"></span>
                            <span class="hidden-xs-down fs-5 font-bold">Billed</span></a
                        >
                    </li>
                    <l class="nav-item">
                        <a
                            class="nav-link"
                            href="<?php echo base_url(); ?>head-office-accounting/billing-list/other-hosp/for-payment"
                            role="tab"
                            ><span class="hidden-sm-up"></span>
                            <span class="hidden-xs-down fs-5 font-bold">For Payment</span></a
                        >
                    </l i>
                    <li class="nav-item">
                       <a
                            class="nav-link"
                            href="<?php echo base_url(); ?>head-office-accounting/billing-list/other-hosp/paid-bill"
                            role="tab"
                            ><span class="hidden-sm-up"></span>
                            <span class="hidden-xs-down fs-5 font-bold">Paid Bill</span></a
                        >
                    </li>
                </ul>
            </div>
            <div  id="billing-container" style="display:">
                <div class="row pt-2 pb-2 gap-2">
                    <!-- <div class="col-lg-4 ps-5 pb-3 pt-1 pb-4">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-dark fw-bold">
                                Filter Hospital : 
                                </span>
                            </div>
                            <select class="form-select fw-bold" name="billed-hospital-filter" id="billed-hospital-filter" onchange="displayValue()">
                                <option value="">Select Hospital...</option>
                                <?php foreach($hc_provider as $hospital) : ?>
                                <option value="<?php echo $hospital['hp_id']; ?>"><?php echo $hospital['hp_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div> -->
                    <div class="col-lg-4 pt-1 offset-5">
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
                    <div class="col-md-2 pb-4 pt-1">
                        <button class="btn btn-info w-100" onclick="submitForPayment()" title="click to submit data for payment"><i class="mdi mdi-send"></i> For Payment </button>
                    </div>
                </div>
                <div class="card shadow">
                    <div class="row" id="printableDiv">
                        <div id="billing-table">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <i class="text-danger">( Click LOA/NOA number to view details )</i>
                                    <table class="table table-sm border" id="billedTable">
                                        <thead>
                                            <tr class="border-secondary border-2 border-0 border-top border-bottom">
                                                <th class="fw-bold ls-2"><strong>#</strong></th>
                                                <th class="fw-bold ls-2"><strong>Request Date</strong></th>
                                                <th class="fw-bold ls-2"><strong>Healthcare Provider</strong></th>
                                                <th class="fw-bold ls-2"><strong>Billing No</strong></th>
                                                <th class="fw-bold ls-2"><strong>LOA/NOA #</strong></th>
                                                <th class="fw-bold ls-2"><strong>Patient Name</strong></th>
                                                <th class="fw-bold ls-2"><strong>Business Unit</strong></th>
                                                <th class="fw-bold ls-2"><strong>Current MBL</strong></th>
                                                <th class="fw-bold ls-2"><strong>Percentage</strong></th>
                                                <th class="fw-bold ls-2"><strong>Hospital Bill</strong></th>
                                                <th class="fw-bold ls-2"><strong>Company Charge</strong></th>
                                                <th class="fw-bold ls-2"><strong>Personal Charge</strong></th>
                                                <th class="fw-bold ls-2"><strong>Remaining MBL</strong></th>
                                                <th class="fw-bold ls-2"><strong>SOA</strong></th>
                                            </tr>
                                        </thead>
                                        <tbody class="pt-2" id="billing-tbody" >
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="fw-bold">TOTAL</td>
                                                <td class="fw-bold text-end" id="total_bill"></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table><br>
                                    <br><br><br>
                                </div>
                            </div>
                        </div> 
                    </div>  
            <?php include 'view_pdf_bill_modal.php'; ?>
        </div>
     </div>
     <?php include 'adjust_h_advance_modal.php' ;?>
    </div>
  <?php include 'view_loa_noa_details_modal.php';?>

<script>
  const baseUrl = "<?php echo base_url(); ?>";

 $(document).ready(function(){
    let billedTable = $('#billedTable').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}head-office-accounting/bill/non-accredited-hosp/fetch`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: function(data) {
           data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
            // data.hp_id = $('#billed-hospital-filter').val();
            data.endDate = $('#end-date').val();
            data.startDate = $('#start-date').val();
        },
      },
      //Set column definition initialisation properties.
      columnDefs: [
            { targets: 7, className: 'text-end' },
            { targets: 9, className: 'text-end' },
            { targets: 10, className: 'text-end' },
            { targets: 11, className: 'text-end' },
            { targets: 12, className: 'text-end' },
            { targets: 13, className: 'text-center' },
        ],
      data: [],  // Empty data array
      deferRender: true,  // Enable deferred rendering
      info: false,
      paging: false,
      lengthChange: false,
      responsive: true,
      fixedHeader: true,
    });
    billedTable.on('draw.dt', function() {
        let columnIdx = 10;
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
                let numberString = matches[0].replace(/,/g, ''); // Replace all commas
                let floatValue = parseFloat(numberString);
                sum += floatValue;
            }
            });
        }
        $('#total_bill').html(sum.toLocaleString('PHP', { minimumFractionDigits: 2 }));
    });

    $('#adjustedAdvanceForm').submit(function(){
        $.ajax({
            url :`${baseUrl}head-office-accounting/bill/adjusted-advance/submit`,
            type : 'POST',
            data : $(this).serialize(),
            dataType : 'json',
            success : function(res){
                const {
                    status,message
                } = res;

                if(status == 'success'){
                    swal({
                        title: 'Success',
                        text: message,
                        timer: 5000,
                        showConfirmButton: false,
                        type: 'success'
                    });
                }else{
                    swal({
                        title: 'Error',
                        text: message,
                        timer: 5000,
                        showConfirmButton: false,
                        type: 'error'
                    });
                }
            }
        });
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
    // $('#billed-hospital-filter').change(function(){
    //     billedTable.draw();
    // });
    $('#charging-end-date').change(function(){
        chargingTable.draw();
    });
    $('#charging-start-date').change(function(){
        chargingTable.draw();
    });
    $('#charging-bu-filter').change(function(){
        chargingTable.draw();
    });
    $('#charging-hospital-filter').change(function(){
        chargingTable.draw();
    });

 }) ;

    
 const submitForPayment = () => {
    const start_date = document.querySelector('#start-date').value;
    const end_date = document.querySelector('#end-date').value;
    const total = document.querySelector('#total_bill');
    const spanValue = total.textContent;
    $.confirm({
                    title: '<strong>Confirmation!</strong>',
                    content: 'Are you sure? Please review before you proceed.',
                    type: 'blue',
                    buttons: {
                        confirm: {
                            text: 'Yes',
                            btnClass: 'btn-blue',
                            action: function(){

                                $.ajax({
                                    url: `${baseUrl}head-office-accounting/bill/submit-for-payment/other-hosp`,
                                    method: "POST",
                                    data: {
                                        'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
                                        'start_date' : start_date,
                                        'end_date' : end_date,
                                        'total_bill' : spanValue,
                                    },
                                    dataType: "json",
                                    success: function(response){
                                        const { 
                                            token,payment_no,status,message
                                        } = response;

                                        if(status == 'success'){
                                            swal({
                                                title: 'Success',
                                                text: message,
                                                timer: 3000,
                                                showConfirmButton: false,
                                                type: 'success'
                                            });
                                         
                                            if(payment_no != ''){
                                                printForPayment(payment_no);
                                            }
                                            setTimeout(function () {
                                                window.location.href = '<?php echo base_url();?>head-office-accounting/billing-list/other-hosp/for-payment';
                                            }, 2600);                                                                   

                                        }
                                        if(status == 'error'){
                                            swal({
                                                title: 'Error',
                                                text: message,
                                                timer: 3000,
                                                showConfirmButton: true,
                                                type: 'error'
                                            });
                                        }
                                    }
                                }); 
                            },
                        },
                        cancel: {
                            btnClass: 'btn-dark',
                            action: function() {
                                // close dialog
                            }
                        },
                    }
                });
 }

 window.onload = function() {
    getBusinessUnits();
    getBUnits();
 }

 const viewPDFBill = (pdf_bill,loa_noa_no) => {
      $('#viewPDFBillModal').modal('show');
      if(loa_noa_no != ''){
        $('#pdf-loa-no').html(loa_noa_no);
      }

        let pdfFile = `${baseUrl}uploads/hospital_receipt/${pdf_bill}`;
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

    const adjustHAdvance = (billing_no,loa_noa_no,fullname,advance,hospital,company) => {

        $('#adjustHAModal').modal('show');
        $('#member-fullname').html(fullname);
        $('#advance').html(advance);
        $('#loa-noa-no').html(loa_noa_no);
        $('#a-bill-no').val(billing_no);
        $('#hospital-bill').html(hospital);
        $('#company-charge').html(company);
    }

    const printForPayment = (payment_no) => {
        const start_date = document.querySelector('#start-date').value;
        const end_date = document.querySelector('#end-date').value;

       
            hp_ids = 'none';
        
          if(start_date == ''){
            start_dates = 'none';
          }else{
            start_dates = start_date;
          } if(end_date == ''){
            end_dates = 'none';
          }else{
            end_dates = end_date;
          } 
          type = 'nonaffiliated';

        const base_url = `${baseUrl}`;
        window.open(base_url + "printforpayment/pdfbilling/" + btoa(type) + "/" + btoa(hp_ids) + "/" + btoa(start_dates) + "/" + btoa(end_dates) + "/" + btoa(payment_no), '_blank');
    }

    const viewLOANOAdetails = (billing_id) => {
    $('#viewLOANOAdetailsModal').modal('show');
    $.ajax({
      url: `${baseUrl}head-office-accounting/biling/loa-noa-details/fetch/${billing_id}`,
      data: `<?php echo $this->security->get_csrf_hash(); ?>`,
      type: 'GET',
      success: function(response) {
        const res = JSON.parse(response);
        const {
          token,
          loa_noa_no,
          fullname,
          business_unit,
          hp_name,
          requested_on,
          approved_on,
          approved_by,
          request_type,
          percentage,
          services,
          admission_date,
          billed_on,
          billed_by,
          billing_no,
          net_bill,
          personal_charge,
          company_charge,
          cash_advance,
          total_payable,
          before_remaining_bal,
          after_remaining_bal,
          hospitalized_date
        } = res;

        if(request_type == 'Diagnostic Test'){
          $('#cost-types').show();
        }else{
          $('#cost-types').hide();
        }
        if(request_type == 'NOA'){
          $('#admitted-on').show();
        }else{
          $('#admitted-on').hide();
        }
        if(request_type == 'Emergency'){
          $('#hospitalized-on').show();
        }else{
          $('#hospitalized-on').hide();
        }
        $('#third-table').hide();
        $('#hospitalized-date').html(hospitalized_date);
        $('#noa-loa-no').html(loa_noa_no);
        $('#members-fullname').html(fullname);
        $('#member-bu').html(business_unit);
        $('#hc-provider').html(hp_name);
        $('#request-date').html(requested_on);
        $('#approved-on').html(approved_on);
        $('#approved-by').html(approved_by);
        $('#request-type').html(request_type);
        $('#percentage-is').html(percentage);
        $('#med-services').html(services);
        $('#admission-date').html(admission_date);
        $('#billed-on').html(billed_on);
        $('#billed-by').html(billed_by);
        $('#billing-no').html(billing_no);
        $('#net-bill').html(net_bill);
        $('#personal-charge').html('-'+ personal_charge);
        $('#company-charges').html(company_charge);
        $('#cash-advance').html(cash_advance);
        $('#total-payable').html(total_payable);
        $('#totals-payable').html(total_payable);
        $('#max-benefit').html(before_remaining_bal);
        $('#remaining-mbl').html(after_remaining_bal);
      }
    });
 }
</script>
   
</html>