<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2"><i class="mdi mdi-format-float-none"></i> For Payment</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">For Payment Billing</li>
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
                    <th style="color: white;">CONSOLIDATED BILLING</th>
                    <th style="color: white;">DATE</th>
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
</div>
<script>
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

  const addPaymentDetails = (payment_no) => {
    $.ajax({
        url : '<?php echo base_url();?>head-office-accounting/bill/for-payment-details/fetch',
        method : 'GET',
        dataType : 'json',
        data : {
            'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
            'payment_no' : payment_no,
        },
        success: function(output) {
        let bill = output.billing;
        let total = output.total_payable;
        let hp_con = '';
        let hpNames = new Set();
        let hpIds = new Set();

        $.each(bill, function(index, item) {
            hpNames.add(item.hp_name);
            hpIds.add(item.hp_id);
        });

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
        }

    });
    

  }
</script>