<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2"><i class="mdi mdi-checkbox-multiple-marked"></i> Paid Bill</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Head Office Accounting</li>
              <li class="breadcrumb-item active" aria-current="page">Non-Affiliated Hospital</li>
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
                        href="<?php echo base_url(); ?>head-office-accounting/bill/non-accredited/billed-loa-noa"
                        role="tab"
                        ><span class="hidden-sm-up"></span>
                        <span class="hidden-xs-down fs-5 font-bold">Billed</span></a
                    >
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        href="<?php echo base_url(); ?>head-office-accounting/billing-list/other-hosp/for-payment"
                        role="tab"
                        ><span class="hidden-sm-up"></span>
                        <span class="hidden-xs-down fs-5 font-bold">For Payment</span></a
                    >
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link active"
                        href="<?php echo base_url(); ?>head-office-accounting/billing-list/other-hosp/paid-bill"
                        role="tab"
                        ><span class="hidden-sm-up"></span>
                        <span class="hidden-xs-down fs-5 font-bold">Paid Bill</span></a
                    >
                </li>
            </ul>
        </div>
       <div class="row">
          <div class="col-lg-4 pt-1 offset-5 pb-4">
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
          <div class="col-md-1 pb-3 pt-1">
              <button class="btn btn-danger w-100" onclick="printPaidBill()" title="click to print data"><i class="mdi mdi-send"></i> Print </button>
          </div>
       </div>
        <div class="card shadow">
          <div class="card-body">
            <div class="">
              <table class="table table-sm table-hover table-responsive" id="matchedLoaTable">
                <thead>
                  <tr class="border-secondary border-2 border-0 border-top border-bottom">
                      <th class="fw-bold"><strong>#</strong></th>
                      <th class="fw-bold"><strong>Request Date</strong></th>
                      <th class="fw-bold"><strong>Healthcare Provider</strong></th>
                      <th class="fw-bold"><strong>Billing No</strong></th>
                      <th class="fw-bold"><strong>LOA/NOA #</strong></th>
                      <th class="fw-bold"><strong>Patient Name</strong></th>
                      <th class="fw-bold"><strong>Business Unit</strong></th>
                      <th class="fw-bold"><strong>Company Charge</strong></th>
                      <th class="fw-bold"><strong>Action</strong></th>
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
      <?php include 'view_loa_noa_details_modal.php';?>
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
        url: '<?php echo base_url();?>head-office-accounting/bill/other-hosp/paid-bill/fetch',
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: function(data) {
          data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
          data.endDate = $('#end-date').val();
          data.startDate = $('#start-date').val();
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

    $('#end-date').change(function(){
        matchedTable.draw();
    });
    $('#start-date').change(function(){
        matchedTable.draw();
    });
    $("#start-date").flatpickr({
        dateFormat: 'Y-m-d',
    });
    $("#end-date").flatpickr({
        dateFormat: 'Y-m-d',
    });
  });

  const printPaidBill = () => {
    const start_date = document.querySelector('#start-date').value;
    const end_date = document.querySelector('#end-date').value;

      if(start_date == ''){
        start_dates = 'none';
      }else{
        start_dates = start_date;
      } if(end_date == ''){
        end_dates = 'none';
      }else{
        end_dates = end_date;
      }

    const base_url = `${baseUrl}`;
    window.open(base_url + "printPaidBill/pdfbilling/" + btoa(start_dates) + "/" + btoa(end_dates), '_blank');
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

  const viewReimburseCV = (supporting_file) => {
    $('#viewCVModal').modal('show');
        $('#cancel').hide();
        let pdfFile = `${baseUrl}uploads/paymentDetails/${supporting_file}`;
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
          hospitalized_date,
          is_manual
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
        if(is_manual == 1){
            $('#request-type').html('Reimbursement');
        }else{
            $('#request-type').html(request_type);
        }
        if(services != ''){
            $('#cost-types').show();
        }else{
            $('#cost-types').hide();
        }
        
        $('#hospitalized-date').html(hospitalized_date);
        $('#noa-loa-no').html(loa_noa_no);
        $('#members-fullname').html(fullname);
        $('#member-bu').html(business_unit);
        $('#hc-provider').html(hp_name);
        $('#request-date').html(requested_on);
        $('#approved-on').html(approved_on);
        $('#approved-by').html(approved_by);
        $('#percentage-is').html(percentage);
        $('#med-services').html(services);
        $('#admission-date').html(admission_date);
        $('#billed-on').html(billed_on);
        $('#billed-by').html(billed_by);
        $('#billing-no').html(billing_no);
        $('#hp-bill').html(net_bill);
        $('#personal-chrg-bill').html(personal_charge);
        $('#company-chrg-bill').html(company_charge);
        $('#current-mbl').html(before_remaining_bal);
        $('#remaining-mbl').html(after_remaining_bal);
      }
    });
 }


</script>