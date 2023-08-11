<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block flex-column flex-sm-row align-items-left">
        <h4 class="page-title ls-2"><i class="mdi mdi-format-float-none"></i> For Payment</h4>
        <div class="ms-auto text-end order-first order-sm-last">
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
                        class="nav-link active"
                        href="<?php echo base_url(); ?>head-office-accounting/billing-list/other-hosp/for-payment"
                        role="tab"
                        ><span class="hidden-sm-up"></span>
                        <span class="hidden-xs-down fs-5 font-bold">For Payment</span></a
                    >
                </li>
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

        <!-- <div class="col-lg-5 ps-5 pb-3 offset-7 pt-1 pb-4">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text bg-dark text-white"><i class="mdi mdi-filter"></i></span>
            </div>
            <select class="form-select fw-bold" name="matched-hospital-filter" id="matched-hospital-filter">
              <option value="">Select Hospital</option>
              <?php foreach($hc_provider as $option) : ?>
                <option value="<?php echo $option['hp_id']; ?>"><?php echo $option['hp_name']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div> -->
        <div class="col-md-1 pb-3 pt-1 offset-10">
            <button class="btn btn-danger w-100" onclick="printForPaymentBill()" title="click to print data"><i class="mdi mdi-send"></i> Print </button>
        </div>
        <div class="card shadow">
          <div class="card-body">
            <div class="">
              <table class="table table-sm table-hover table-responsive" id="matchedLoaTable">
                <thead>
                  <tr class="border-secondary border-2 border-0 border-top border-bottom">
                      <th class="fw-bold"><strong>#</strong></th>
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
          </div>
        </div>
        <?php include 'reimbursement_modal.php'; ?>
      </div>
    </div>
  </div>
  <?php include 'view_check_voucher.php';?>
</div>
<?php include 'view_loa_noa_details_modal.php';?>

<script>
  const baseUrl = '<?php echo base_url(); ?>';
 function redirectPage(route, seconds){
          setTimeout(() => {
          window.location.href = route;
          }, seconds);
  }

  $(document).ready(function() {
    let matchedTable = $('#matchedLoaTable').DataTable({
      processing: true,
      serverSide: true,
      order: [],

      // Load data for the table's content from an Ajax source  
      ajax: {
        url: '<?php echo base_url();?>head-office-accounting/bill/other-hosp/for-payment/fetch',
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: function(data) {
          data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
        //   data.filter = $('#matched-hospital-filter').val();
        }
      },

      //Set column definition initialisation properties.
      columnDefs: [
            { targets: 6, className: 'text-end' },
            { targets: 7, className: 'text-center' },
        ],
      info: false,
      paging: false,
      filter: false,
      lengthChange: false,
      responsive: true,
      fixedHeader: true,
    });

    const form = document.querySelector('#payment_details_form');
    $("#payment_details_form").submit(function(event){
            event.preventDefault();

        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        $.confirm({
            title: '<strong>Confirmation!</strong>',
            content: 'Are you sure? Please review before you proceed.',
            type: 'blue',
            buttons: {
                confirm: {
                    text: 'Yes',
                    btnClass: 'btn-blue',
                    action: function(){
                        const paymentDetailsForm = $('#payment_details_form')[0];
                        const formdata = new FormData(paymentDetailsForm);
                        const type = 'nonaccredited';
                        $.ajax({
                            url: `${baseUrl}head-office-accounting/billing-list/billed/payment-details/${type}`,
                            method: "POST",
                            data: formdata,
                            dataType: "json",
                            processData: false,
                            contentType: false,
                            success: function(response){
                                const {
                                    token, status, message, acc_num_error, acc_name_error, check_num_error, check_date_error, bank_error,paid_error,image_error
                                } = response;

                                if(status == 'validation-error'){
                                    if(acc_num_error != ''){
                                        $("#acc-number-error").html(acc_num_error);
                                        $("#acc-number").addClass('is-invalid');
                                    }else{
                                        $("#acc-number-error").html("");
                                        $("#acc-number").removeClass('is-invalid');
                                    }

                                    if(acc_name_error != ''){
                                        $("#acc-name-error").html(acc_name_error);
                                        $("#acc-name").addClass('is-invalid');
                                    }else{
                                        $("#acc-name-error").html("");
                                        $("#acc-name").removeClass('is-invalid');
                                    }

                                    if(check_num_error != ''){
                                        $("#check-number-error").html(check_num_error);
                                        $("#check-number").addClass('is-invalid');
                                    }else{
                                        $("#check-number-error").html("");
                                        $("#check-number").removeClass('is-invalid');
                                    }

                                    if(check_date_error != ''){
                                        $("#check-date-error").html(check_date_error);
                                        $("#check-date").addClass('is-invalid');
                                    }else{
                                        $("#check-date-error").html("");
                                        $("#check-date").removeClass('is-invalid');
                                    }

                                    if(bank_error != ''){
                                        $("#bank-error").html(bank_error);
                                        $("#bank").addClass('is-invalid');
                                    }else{
                                        $("#bank-error").html("");
                                        $("#bank").removeClass('is-invalid');
                                    }

                                    if(paid_error != ''){
                                        $("#paid-error").html(paid_error);
                                        $("#amount-paid").addClass('is-invalid');
                                    }else{
                                        $("#paid-error").html("");
                                        $("#amount-paid").removeClass('is-invalid');
                                    }

                                    if(image_error != ''){
                                        $("#file-error").html(image_error);
                                        $("#supporting-docu").addClass('is-invalid');
                                    }else{
                                        $("#file-error").html("");
                                        $("#supporting-docu").removeClass('is-invalid');
                                    }
                                }
                                if(status == 'success'){
                                    let page = '<?php echo base_url()?>head-office-accounting/billing-list/other-hosp/paid-bill';
                                    $("#payment_details_form")[0].reset();
                                    $('#addPaymentModal').modal('hide');
                                    swal({
                                        title: 'Success',
                                        text: message,
                                        timer: 3000,
                                        showConfirmButton: false,
                                        type: 'success'
                                    });

                                        redirectPage(page, 3000);
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
                
    });

    $('#matched-hospital-filter').change(function(){
      matchedTable.draw();
    });

    $("#check-date").flatpickr({
        dateFormat: 'Y-m-d',
    });
  });

  const tagAsDoneReimburse = (billing_id,payment_no,company_charge,hp_id) => {
      $('#submitReimbursementModal').modal('show');
      $('#p-payment-no').html(payment_no);
      $('#p-total-bill').val(company_charge);
      $('#pd-billing-id').val(billing_id);
      $('#pd-hp-id').val(hp_id);
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

  // const printPaidBill = (payment_no) => {

  //   var base_url = `${baseUrl}`;
  //   window.open(base_url + "printpaidbill/pdfbilling/" + btoa(payment_no), '_blank');
  // }

  const printForPaymentBill = () => {

    var base_url = `${baseUrl}`;
    window.open(base_url + "printPaymentbill/pdfbilling");
  }

  const addPaymentDetails = (payment_no,hp_id) => {
    $.ajax({
        url : '<?php echo base_url();?>head-office-accounting/bill/for-payment-details/fetch',
        method : 'GET',
        dataType : 'json',
        data : {
            'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
            'payment_no' : payment_no,
            'hp_id' : hp_id
        },
        success: function(output) {
        let bill = output.billing;
        let total = output.total_payable;
        let bank = output.bank;
        let hp_con = '';
        let bank_acc = '';
        let hpNames = new Set();
        let hpIds = new Set();

        $.each(bill, function(index, item) {
            hpNames.add(item.hp_name);
            hpIds.add(item.hp_id);
        });

            bank_acc += '<select class="form-select" name="bank-name" id="bank-name" onchange="getAccountNum()">'+
                            '<option value="">Please Select</option>';
        $.each(bank, function(index, item) {
            bank_acc += '<option value="'+item.bank_id+'">'+item.bank_name+'</option>';
                       
        });
            bank_acc +=  '</select>';

        if (hpNames.size === 1 && hpIds.size === 1) {
            hp_con += '<input class="form-control text-dark fw-bold ls-1 fs-6" name="hospital_filtered" id="hospital_filtered" value="'+hpNames.values().next().value+'" readonly>'+
            '<input type="hidden" name="pd-hp-id" id="pd-hp-id" value="'+hpIds.values().next().value+'"></input>';
        } else {
            $.each(bill, function(index, item) {
            hp_con += '<input class="form-control text-dark fw-bold ls-1 fs-6" name="hospital_filtered" id="hospital_filtered" value="'+item.hp_name+'" readonly>'+
                '<input type="hidden" name="pd-hp-id" id="pd-hp-id" value="'+item.hp_id+'"></input>';
            });
        }
        $('#addPaymentModal').modal('show');
        var form = $('#addPaymentModal').find('form')[0];
        form.reset();
        $('#hp-name-con').html(hp_con);
        $('#p-payment-no').html(payment_no);
        $('#pd-payment-no').val(payment_no);
        $('#p-total-bill').val(total);
        $('#bank-options').html(bank_acc);
        }

    });
  }

  const getAccountNum = () => {
    const bank_id = document.querySelector('#bank-name').value;
    $.ajax({
      url: `${baseUrl}head-office-accounting/billing-list/billed/fetch-bank-number`,
      type: 'GET',
      data: {
        'token' : '<?php echo $this->security->get_csrf_hash();?>',
        'bank_id' : bank_id
      },
      dataType : 'json',
      success: function(data){
        let account_name = data.account_name;
        let acc_number = data.account_num;

        $('#acc-name').val(account_name);
        $('#acc-number').val(acc_number);
      },

    });
  }

  let pdfinput = "";
    const  previewPdfFile = (pdf_input) => {
        pdfinput = pdf_input;
        let pdfFileInput = document.getElementById(pdf_input);
        let pdfFile = pdfFileInput.files[0];
        let reader = new FileReader();
        if(pdfFile){
            $('#viewCVModal').modal('show');
            reader.onload = function(event) {
            let dataURL = event.target.result;
            let iframe = document.querySelector('#pdf-cv-viewer');
            iframe.src = dataURL;
        };
            reader.readAsDataURL(pdfFile);
        }

    };

    const validateNumberInputs = () => {
        const number_input = document.querySelector('#amount-paid');
        number_input.addEventListener('input', function(event) {
            if (this.value < 0) {
                this.value = '';
            }
        });
    }

</script>