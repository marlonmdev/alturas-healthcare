<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block flex-column flex-sm-row align-items-left">
        <h4 class="page-title ls-2"><i class="mdi mdi-format-float-none"></i> For Payment</h4>
        <div class="ms-auto text-end order-first order-sm-last">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Head Office Accounting</li>
              <li class="breadcrumb-item active" aria-current="page">Accredited Hospital</li>
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
                        class="nav-link active"
                        href="<?php echo base_url(); ?>head-office-accounting/billing-list/for-payment"
                        role="tab"
                        ><span class="hidden-sm-up"></span>
                        <span class="hidden-xs-down fs-5 font-bold">For Payment</span></a
                    >
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        href="<?php echo base_url(); ?>head-office-accounting/billing-list/paid-bill"
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
        <div class="card shadow">
          <div class="card-body">
            <div class="">
              <table class="table table-hover table-responsive" id="matchedLoaTable">
                <thead style="background-color:#00538C">
                  <tr>
                    <th style="color: white;">TRANSACTION DATE</th>
                    <th style="color: white;">CONSOLIDATED BILLING</th>
                    <th style="color: white;">COVERED DATE</th>
                    <th style="color: white;">HEALTHCARE PROVIDER</th>
                    <th style="color: white;">STATUS</th>
                    <th style="color: white;">ACTION</th>
                  </tr>
                </thead>
                <tbody class="fs-5">
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php include 'payment_details_modal.php'; ?>
  </div>
  <?php include 'view_check_voucher.php';?>
</div>
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
        url: '<?php echo base_url();?>head-office-accounting/bill/for-payment/fetch',
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

                        $.ajax({
                            url: "<?php echo base_url();?>head-office-accounting/billing-list/billed/payment-details",
                            method: "POST",
                            data: formdata,
                            dataType: "json",
                            processData: false,
                            contentType: false,
                            success: function(response){
                                const {
                                    token, status, payment_no, message, acc_num_error, acc_name_error, check_num_error, check_date_error, bank_error,paid_error,image_error
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
                                    let page = '<?php echo base_url()?>head-office-accounting/billing-list/paid-bill';
                                    $("#payment_details_form")[0].reset();
                                    $('#addPaymentModal').modal('hide');
                                    swal({
                                        title: 'Success',
                                        text: message,
                                        timer: 3000,
                                        showConfirmButton: false,
                                        type: 'success'
                                    });

                                    if(payment_no != ''){
                                        printPaidBill(payment_no);

                                    }
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

  const printPaidBill = (payment_no) => {

    var base_url = `${baseUrl}`;
    window.open(base_url + "printpaidbill/pdfbilling/" + btoa(payment_no), '_blank');
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