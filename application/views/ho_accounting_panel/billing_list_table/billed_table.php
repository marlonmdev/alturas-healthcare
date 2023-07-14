
<!-- Start of Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
        <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Billing List</h4>
            <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item">Head Office Accounting</li>
                <li class="breadcrumb-item active" aria-current="page">
                    Billed
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
        <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
        <div class="row">
            <div class="col-lg-12">
                <ul class="nav nav-tabs mb-4" role="tablist"> 
                   
                    <li class="nav-item">
                        <a
                            class="nav-link active"
                            href="<?php echo base_url(); ?>head-office-accounting/billing-list/billed"
                            role="tab"
                            ><span class="hidden-sm-up"></span>
                            <span class="hidden-xs-down fs-5 font-bold">Billed1</span></a
                        >
                    </li>
                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="<?php echo base_url(); ?>head-office-accounting/billing-list/closed"
                            role="tab"
                            ><span class="hidden-sm-up"></span>
                            <span class="hidden-xs-down fs-5 font-bold">Closed1</span></a
                        >
                    </li>
                </ul>
            </div>
            <div class="row mb-3 pt-2">
                <div class="col-lg-5">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-dark text-white">
                            <i class="mdi mdi-filter"></i>
                            </span>
                        </div>
                        <select class="form-select fw-bold" name="hospital-filter" id="hospital-filter" oninput="enableDate()">
                                <option value="">Select Hospital</option>
                                <?php foreach($options as $option) : ?>
                                <option value="<?php echo $option['hp_id']; ?>"><?php echo $option['hp_name']; ?></option>
                                <?php endforeach; ?>
                        </select>
                        <input type="hidden" id="hospital-name">
                    </div>
                </div>
                
                <div class="col-lg-6 offset-1">
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class="input-group-text bg-dark text-white ls-1 ms-2">
                                    <i class="mdi mdi-filter"></i>
                                </span>
                            </div>
                            <input type="date" class="form-control" name="start_date" id="start-date" oninput="validateDateRange()" placeholder="Start Date" disabled>

                            <div class="input-group-append">
                                <span class="input-group-text bg-dark text-white ls-1 ms-2">
                                    <i class="mdi mdi-filter"></i>
                                </span>
                            </div>
                            <input type="date" class="form-control" name="end-date" id="end-date" oninput="validateDateRange()" placeholder="End Date" disabled>
                        
                        </div>
                        
                    </div>
                </div>
            </div><br>
           
                <div class="card shadow">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="billedTable">
                                <thead>
                                    <tr>
                                        <td class="fw-bold">Billing #</td>
                                        <td class="fw-bold">Patient Name</td>
                                        <td class="fw-bold">Request Type</td>
                                        <td class="fw-bold">Billed on</td>
                                        <td class="fw-bold">Company Charge</td>
                                        <td class="fw-bold">Action</td>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <?php include 'payment_details_modal.php' ?>
                
                <div class="row col-lg-7 offset-5 pt-3 pb-3">
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text bg-dark text-white ls-1 fs-5">TOTAL PAYABLE: </span>
                        </div>
                        <input type="text" class="form-control fw-bold text-danger fs-4 ls-1" id="total_charge" value="" readonly>
                        
                        <div class="input-group-append" id="payment">
                            <a href="JavaScript:void(0)" onclick="add_payment()" class="input-group-text bg-cyan text-white ms-2 px-3 fs-5 ls-1 ps-1" id="add-payment-btn"><i class="mdi mdi-plus fs-4"></i> Add Payment Details </a>
                        </div>
                    </div>
                </div>
                <hr class="pt-2">
            </div>
        </div> 
    </div>
   
    <style>
        .dropdown-item:hover {
            background-color: #5f86fa;
        }
    </style>

    <script>
        function redirectPage(route, seconds){
                setTimeout(() => {
                window.location.href = route;
                }, seconds);
        }

        const baseUrl = "<?php echo base_url(); ?>";
        
        $(document).ready(function(){

            let billingTable = $('#billedTable').DataTable({
                processing: true, //Feature control the processing indicator.
                serverSide: true, //Feature control DataTables' server-side processing mode.
                order: [], //Initial no order.

                // Load data for the table's content from an Ajax source
                ajax: {
                    url: `${baseUrl}head-office-accounting/billing-list/billed/fetch`,
                    type: "POST",
                    data: function(data) {
                        data.token     = '<?php echo $this->security->get_csrf_hash(); ?>';
                        data.filter    = $('#hospital-filter').val();
                        data.startDate = $('#start-date').val();
                        data.endDate   = $('#end-date').val();
                    },
                
                },

                //Set column definition initialisation properties.
                columnDefs: [{
                    "targets": [5], // 5th column / numbering column
                    "orderable": false, //set not orderable
                }, ],
                responsive: true,
                fixedHeader: true,
            });

            $('#hospital-filter').change(function(){
                billingTable.draw();
                // get_total_company_charge();
                $.ajax({
                    type: 'post',
                    url: `${baseUrl}head-office-accounting/billing-list/billed/hp_name`,
                    data: {
                        'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
                        'hp_id' : $('#hospital-filter').val(),
                    },
                    dataType: "json",
                    success: function(response){
                        $('#hospital-name').val(response.hp_name);
                    },

                });

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
                                            let page = '<?php echo base_url()?>head-office-accounting/billing-list/closed';
                                            $("#payment_details_form")[0].reset();
                                            swal({
                                                title: 'Success',
                                                text: message,
                                                timer: 3000,
                                                showConfirmButton: false,
                                                type: 'success'
                                            });
                                                redirectPage(page, 200);
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

            $('#start-date').change(function(){
                billingTable.draw();
                // get_total_company_charge();
            });

            $('#end-date').change(function(){
                billingTable.draw();
                get_total_company_charge();
            });

            $("#start-date").flatpickr({
                // dateFormat: 'm-d-Y',
            });

            $('#end-date').flatpickr({
                // dateFormat: 'm-d-Y',
            });

            $("#check-date").flatpickr({
                // dateFormat: 'm-d-Y',
            });

        });

       const get_total_company_charge = () => {
            const hp_filter = document.querySelector('#hospital-filter').value;
            const start_date = document.querySelector('#start-date').value;
            const end_date = document.querySelector('#end-date').value;
            const total_charge = document.querySelector('#total_charge');

                $.ajax({
                    type: 'post',
                    url: `${baseUrl}head-office-accounting/billing-list/billed/sum`,
                    dataType: "json",
                    data: {
                        'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
                        'hp_id' : hp_filter,
                        'startDate' : start_date,
                        'endDate' : end_date,
                    },
                    success: function(response){
                        total_charge.value = response.total_company_charge
                    },

                });
       }

        const enableDate = () => {
            const hp_filter = document.querySelector('#hospital-filter');
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

        const add_payment = () => {
            const hospital_name = document.querySelector('#hospital-name').value;
            const hp_id = document.querySelector('#hospital-filter').value;
            const start_date = document.querySelector('#start-date').value;
            const end_date = document.querySelector('#end-date').value;
            const total_charge = document.querySelector('#total_charge').value;
            const parsed_total = parseFloat(total_charge.replace(/,/g, ''));
            
            if(total_charge != ''){
                $('#addPaymentModal').modal('show');
            }else{
                var modal = document.getElementById("addPaymentModal");
                modal.removeAttribute("data-bs-toggle");
            }
            
            // check_date.style.backgroundColor = '#ffff';
            $('#hospital_filtered').val(hospital_name);
            $('#start_date').val(start_date);
            $('#end_date').val(end_date );
            $('#total-company-charge').val(parsed_total);
            $('#hp_id').val(hp_id);
        }
    </script>
           