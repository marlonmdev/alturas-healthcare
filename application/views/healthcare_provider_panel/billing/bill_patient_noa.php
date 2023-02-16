<!-- Page wrapper  -->
 <div class="page-wrapper">
    <!-- internal scripts -->
    <!-- <script src="<?php echo base_url(); ?>assets/js/lone/axios.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lone/vue3.js"></script> -->
    <script src="<?php echo base_url(); ?>assets/js/lone/sweetalert2v11.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lone/jqueryv3.js"></script>
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title ls-2">Bill Services</h4>
                <div class="ms-auto text-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">Healthcare Provider</li>
                            <li class="breadcrumb-item active" aria-current="page">
                            NOA Billing
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- End Bread crumb and right sidebar toggle -->

    <!-- Container fluid  -->
    <div class="container-fluid" id="container-div">  
        <div class="card shadow">
            <div class="card-body">

                <!-- Go Back to Previous Page -->
                <div class="col-12 mb-4 mt-0">
                    <form method="POST" action="<?php echo base_url(); ?>healthcare-provider/billing/search-by-healthcard" id="search-form-1" class="needs-validation" novalidate>
                        <div class="input-group">
                            <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
                            <input type="hidden" name="healthcard_no" value="<?= $healthcard_no ?>">
                            <button type="submit" class="btn btn-outline-dark" data-bs-toggle="tooltip" title="Click to Go Back">
                                <strong class="ls-2" style="vertical-align:middle">
                                    <i class="mdi mdi-arrow-left-bold"></i> Go Back
                                </strong>
                            </button>
                        </div>
                    </form>
                </div>
            
                <div class="row">
                    <div class="col-12">
                        <div class="row mb-1">
                            <div class="col-md-4">
                                <label class="form-label ls-1">Patient's Name</label>
                                <input type="text" class="form-control text-danger fw-bold ls-1" name="patient-name" value="<?= $member['first_name'].' '. $member['middle_name'].' '.$member['last_name'] ?>" readonly>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label ls-1">Healthcard Number</label>
                                <input type="text" class="form-control text-danger fw-bold ls-1" name="healthcard-no" value="<?= $healthcard_no ?>" readonly>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label ls-1">Patient's MBL</label>
                                <input type="text" class="form-control text-danger fw-bold ls-1" name="patient-mbl" value="<?= '&#8369;'.number_format($member_mbl, 2) ?>" readonly>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label ls-1">Remaining MBL</label>
                                <input type="text" class="form-control text-danger fw-bold ls-1" id="request-type" name="request-type" value="<?= '&#8369;'.number_format($remaining_balance, 2) ?>" readonly>
                            </div>
                        </div>

                        <div class="row pt-2">
                            <div class="col-md-3 my-1">
                                <label class="form-label ls-1">NOA Number</label>
                                <input type="text" class="form-control text-danger fw-bold ls-1" id="noa-no" name="noa-no" value="<?= $noa_no ?>" readonly>
                            </div>

                            <div class="col-md-5 my-1">
                                <label class="form-label ls-1">Healthcare Provider</label>
                                <input type="text" class="form-control text-danger fw-bold ls-1" id="hcare-provider" name="hcare-provider" value="<?= $hcare_provider ?>" readonly>
                            </div>
                        </div>

                        <hr class="mt-4">

                        <div class="row pb-2">
                            <h4 class="text-center text-secondary ls-2">
                                 MEDICAL SERVICES
                            </h4>
                            <div id="dynamic-services"></div>
                            <input type="hidden" id="services-count" name="services-count" value="0" min="0">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 parent_element">
                        
                        <!-- Start of Cost Types Accordion -->
                        <div class="accordion accordion-flush" id="costTypeAccordion">
                            <div class="accordion-item">
                                <span class="accordion-header" id="flush-headingOne">
                                    <a href="javascript:void()" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#cost-types-div" aria-expanded="false">
                                        <i class="mdi mdi-hand-pointing-right text-success fs-1 align-end"></i>
                                        <em class="text-info text-decoration-underline fw-bold fs-4 ls-1">
                                            Click here to show and add Medical Services
                                        </em>
                                    </a>
                                </span>

                                <div id="cost-types-div" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#costTypeAccordion">

                                    <div class="accordion-body">

                                        <div class="card px-3">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="costTypesTable" class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="fw-bold ls-1">SERVICES</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>                                                        
                                                        <?php
                                                        if (!empty($cost_types)):
                                                            foreach ($cost_types as $ct) :
                                                        ?>
                                                                <tr>
                                                                    <td class="fw-bold">
                                                                        <?= $ct['cost_type']; ?>
                                                                    </td>
                                                                    <td class="d-flex justify-content-end align-items-end">
                                                                        <button class="btn btn-cyan text-white" id="btn-ctype-<?= $ct['ctype_id'] ?>" onclick="addService('<?= $ct['ctype_id'] ?>','<?= $ct['cost_type'] ?>','<?= $remaining_balance ?>')">
                                                                            <i class="mdi mdi-plus-circle"></i> Add to Billing
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                        <?php
                                                            endforeach;
                                                        endif;
                                                        ?>
                                                       </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End of Card -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End of Cost Types Accordion -->

                        <form method="POST" id="formNoaBilling" class="needs-validation" novalidate>
                            <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
                            <input type="hidden" name="noa-id" value="<?= $noa_id ?>">
                            <input type="hidden" name="emp-id" value="<?= $member['emp_id'] ?>">
                            <input type="hidden" name="deduction-count" value="0" min="0" id="deduction-count">
                            <input type="hidden" name="selected-cost-types-count" value="0" min="0" id="deduction-count">


                           
                        </form>                    

                        <!-- <hr class="mt-4"> -->

                        <!-- this is a custom alert to show if the net bill exceeds the member's remaining balance -->
                        <?php include 'personal_charge_alert.php'; ?>

                        <div class="row mt-4">
                            <h4 class="text-center text-secondary fw-bold ls-2">
                                BILLING DEDUCTIONS
                            </h4>
                        </div>

                        <div class="row my-2">

                            <div class="col-md-3">
                                <label class="form-label ls-1">PhilHealth</label> <span class="text-muted">(Optional)</span>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-success text-white">&#8369;</span>
                                    <input type="number" class="input-deduction form-control fw-bold" id="deduct-philhealth" name="philhealth-deduction" placeholder="Enter Amount" oninput="calculateNoaBilling(`<?= $remaining_balance ?>`)" min="0" readonly>
                                    <span class="text-danger fw-bold deduction-msg ms-3" style="font-size:12px"></span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label ls-1">SSS</label> <span class="text-muted">(Optional)</span>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-success text-white">&#8369;</span>
                                    <input type="number" class="input-deduction form-control fw-bold" id="deduct-sss" name="sss-deduction" placeholder="Enter Amount" oninput="calculateNoaBilling(`<?= $remaining_balance ?>`)" min="0" readonly>
                                    <span class="text-danger fw-bold deduction-msg ms-3" style="font-size:12px"></span>
                                </div>
                            </div>

                            <div class="col-md-3" style="margin-top:28px;">
                                <button type="button" class="btn btn-info" id="btn-other-deduction" onclick="addOtherDeductionInputs(`<?= $remaining_balance ?>`)" disabled>
                                    <i class="mdi mdi-plus-circle"></i> Add Deduction
                                </button>
                            </div>
                        </div>

                        <div id="dynamic-deduction"></div>

                        <hr class="mt-4">

                        <div class="row my-4">
                            
                            <div class="col-md-3">
                                <label class="form-label ls-1">Total Bill</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-cyan text-white">&#8369;</span>
                                    <input type="text" class="form-control fw-bold ls-1" id="total-bill" name="total-bill" value="" readonly>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label ls-1">Total Deduction</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-cyan text-white">&#8369;</span>
                                    <input type="text" class="form-control fw-bold ls-1" id="total-deduction" name="total-deduction" value="" readonly>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label ls-1">Net Bill</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-cyan text-white">&#8369;</span>
                                    <input type="text" class="form-control fw-bold ls-1" id="net-bill" name="net-bill" value="" readonly>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label ls-1">Personal Charge</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-cyan text-white">&#8369;</span>
                                    <input type="text" class="form-control fw-bold ls-1" id="personal-charge" name="personal-charge" value="" readonly>
                                </div>
                            </div>

                        </div>

                        <hr class="mt-4">

                        <div class="row pt-3">
                            <div class="col-md-4">
                                <label class="form-label ls-1">Billing Number</label>
                                <input type="text" class="form-control text-danger fw-bold ls-1" id="billing-no" name="billing-no" value="<?= $billing_no ?>" readonly>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label ls-1">Billing Date</label>
                                <input type="text" class="form-control text-danger fw-bold ls-1" id="billing-date" name="billing-date" value="<?= date('m/d/Y') ?>" readonly>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label ls-1">Billed By</label>
                                <input type="text" class="form-control text-danger fw-bold ls-1" id="billed-by" name="billed-by" value="<?= $billed_by ?>" readonly>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12 d-flex justify-content-center align-items-center">
                                <button type="submit" class="btn btn-danger btn-lg ls-2" id="btn-bill" disabled>
                                    <i class="mdi mdi-file-check me-1"></i>Bill Now
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Scroll to top -->
                <div class="d-flex justify-content-end align-items-end">
                    <a href="#container-div" class="scroll-to-top pe-1" title="Scroll to top">
                        <i class="mdi mdi-arrow-up-bold-circle-outline text-dark fs-1"></i>
                    </a>
                </div>

            </div>
    
        </div>
        <!-- End of card div -->
    </div>
</div>

<script>
    const baseUrl = `<?php echo base_url(); ?>`;

    $(document).ready(function() {
        $('#costTypesTable').DataTable();
    });

    let count = 0; // declaring the count variable outside the function will persist its value even after the function is called, allowing it to increment by one each time the function is called.

    // this is for Consultation LOA Requests
    const addService = (ctype_id, ctype_name, remaining_balance) => {
        const container = document.getElementById('dynamic-services');
        const services_count = document.querySelector('#services-count');
        count++;
        services_count.value = count;

        let html_code  = `<div class="row my-3 row-services" id="row${count}">`;

            /* Creating a new input field with the name service_name[] */
            html_code += `<div class="col-md-6">
                            <div class="form-group">
                                <label for="ct-name">Service Name</label>
                                <input type="text" class="ct-name form-control fw-bold ls-1" id="service-name" name="ct-name[]" value="${ctype_name}" readonly/>
                                <div class="invalid-feedback">
                                    Service Name is required
                                </div>
                            </div>                            
                         </div>`;

            /* Creating a new input field with the name service_name[] */
            html_code += `<div class="col-md-2">
                            <div class="form-group">
                                <label for="service-qty"><i class="mdi mdi-asterisk text-danger"></i>Quantity</label>
                                <input type="number" name="ct-qty[]" class="ct-qty form-control fw-bold ls-1" value="1" min="1"  oninput="calculateNoaBilling(${remaining_balance})" required/>
                                <div class="invalid-feedback">
                                    Service Name is required
                                </div>
                            </div>     
                         </div>`;

            /* Creating a form input field with a name of service_fee[] and a class of
            service-amount. */

            html_code += `<div class="col-md-3">
                                <label class="form-label ls-1"><i class="mdi mdi-asterisk text-danger"></i>Service Fee</label> 
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-dark text-white">&#8369;</span>
                                    <input type="number" name="ct-fee[]" class="ct-fee form-control fw-bold ls-1" placeholder="*Enter Amount" oninput="calculateNoaBilling(${remaining_balance})" required/>

                                    <div class="invalid-feedback">
                                        Service Fee is required
                                    </div>
                                </div>
                          </div>`;

            
           /* Adding a remove button to the html code. */
            html_code += `<div class="col-md-1" style="margin-top:28px;">
                            <button type="button" data-id="${count}" class="btn btn-danger btn-md btn-remove align-baseline" onclick="removeService(this, ${ctype_id}, ${remaining_balance})" data-bs-toggle="tooltip" title="Click to remove Deduction">
                                <i class="mdi mdi-close"></i>
                            </button>
                         </div>`;

            html_code += `</div>`;
        // // $('#dynamic-deduction').append(html_code); => this is a jquery syntax, below is vanilla js way. You can either use this one or the below code
        document.querySelector("#dynamic-services").insertAdjacentHTML("beforeend", html_code);
        // set button to disable attribure to true on add services event
        document.querySelector(`#btn-ctype-${ctype_id}`).setAttribute('disabled', true);
    }


    /**
    * It removes a row and then calls a function to recalculate the Billing Total.
    */
    const removeService = (remove_btn, ctype_id, remaining_balance) => {
        count--;
        const btn_id = remove_btn.getAttribute('data-id');
        const services_count = document.querySelector('#services-count');
        // update deduction count hidden input value for reference
        services_count.value = count;

        document.querySelector(`#row${btn_id}`).remove();
        // set button to disable attribute to false on add services event
        document.querySelector(`#btn-ctype-${ctype_id}`).removeAttribute('disabled');

        calculateNoaBilling(remaining_balance);
    }

    const calculateNoaBilling = (remaining_balance) => {
        let totalBill = 0;
        let total_deduction = 0;
        let net_bill = 0;
        let charge_amount = 0;
        let philhealth_deduction = 0;
        let sss_deduction = 0;
        let other_deduction = 0;
        validateNumberInputs();

        const cost_inputs = document.querySelectorAll('.ct-fee');
        const quantity_inputs = document.querySelectorAll('.ct-qty');
        const total_bill = document.querySelector('#total-bill');
        const deduct_philhealth = document.querySelector('#deduct-philhealth');
        const deduct_sss = document.querySelector('#deduct-sss');
        const totalDeduction = document.querySelector('#total-deduction');
        const netBill = document.querySelector('#net-bill');
        const personal_charge = document.querySelector('#personal-charge');
        const row_deduction = document.querySelectorAll('.row-deduction');
        const deduction_amount = document.querySelectorAll('.deduction-amount');
        const deduction_msg = document.querySelectorAll('.deduction-msg');
        const other_deduction_msg = document.querySelectorAll('.other-deduction-msg');
        const input_deduction = document.querySelectorAll('.input-deduction');

        for(i = 0;i < cost_inputs.length;i++ ){
            totalBill += cost_inputs[i].value * quantity_inputs[i].value;
        }

        total_bill.value = totalBill.toFixed(2);
        enableButtonandDeduction(totalBill);
        
        // Calculate other deduction total
        if(row_deduction.length > 0){
            for (let i = 0; i < row_deduction.length; i++) {
                other_deduction += deduction_amount[i].value * 1;
            }
        }

        philhealth_deduction = deduct_philhealth.value > 0 ? deduct_philhealth.value : 0;
        sss_deduction = deduct_sss.value > 0 ? deduct_sss.value : 0;

        total_deduction = parseFloat(philhealth_deduction) + parseFloat(sss_deduction) + parseFloat(other_deduction);
        net_bill = totalBill - total_deduction;

        charge_amount = net_bill - remaining_balance;

        if(charge_amount > 0){
            personal_charge.value = charge_amount.toFixed(2);
        }else{
            personal_charge.value = 0;
        }

        totalDeduction.value = total_deduction.toFixed(2);
        netBill.value = net_bill.toFixed(2);

        if(net_bill < 0){
            netBill.classList.add('is-invalid', 'text-danger');

            for(i = 0; i < input_deduction.length; i++ ){
                input_deduction[i].classList.add('is-invalid', 'text-danger');
                deduction_msg[i].innerHTML = "Deduction can't be greater than the Net Bill Amount!";
            }

            for(i = 0; i < row_deduction.length; i++ ){
                row_deduction[i].classList.add('is-invalid', 'text-danger');
                other_deduction_msg[i].innerHTML = "Deduction can't be greater than the Net Bill Amount!";
            }  
        }else{
            for(i = 0; i < input_deduction.length; i++ ){
            input_deduction[i].classList.remove('is-invalid', 'text-danger');
            deduction_msg[i].innerHTML = " ";
            }

            for(i = 0; i < row_deduction.length; i++ ){
            row_deduction[i].classList.remove('is-invalid', 'text-danger');
            other_deduction_msg[i].innerHTML = " ";
            } 
        }

        
        showPersonalChargeAlert(charge_amount);
    }

    const validateNumberInputs = () => {
        const number_inputs = document.querySelectorAll("input[type='number']");
        for (let i = 0; i < number_inputs.length; i++) {
            number_inputs[i].addEventListener("input", function(event) {
                if (isNaN(this.value) || this.value < 0) {
                   this.value = "";
                }
            });
        }
    }

    /**
    * It adds a row of inputs to the form
    */
    const addOtherDeductionInputs = (remaining_balance) => {
        const container = document.querySelector('#dynamic-deduction');
        const deduction_count = document.querySelector('#deduction-count');
        count++;
        deduction_count.value = count;

        let html_code  = `<div class="row my-3 row-deduction pt-1" id="row${count}">`;

           /* Creating a new input field with the name deduction_name[] */
            html_code += `<div class="col-md-3">
                            <label class="form-label ls-1"><i class="mdi mdi-asterisk text-danger"></i>Deduction Name</label> 
                            <input type="text" name="deduction-name[]" class="form-control fw-bold ls-1" placeholder="Enter Deduction Name" required/>
                            <div class="invalid-feedback">
                                Deduction name and amount is required
                            </div>
                         </div>`;

            /* Creating a form input field with a name of deduction_amount[] and a class of
            deduction-amount. */
            html_code += `<div class="col-md-3">
                                <label class="form-label ls-1"><i class="mdi mdi-asterisk text-danger"></i>Deduction Amount</label> 
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-success text-white">&#8369;</span>

                                    <input type="number" name="deduction-amount[]" class="deduction-amount form-control fw-bold ls-1" placeholder="Deduction Amount" oninput="calculateNoaBilling(${remaining_balance})" required/>

                                    <span class="other-deduction-msg text-danger fw-bold ms-3" style="font-size:12px"></span>
                                </div>
                          </div>`;

           /* Adding a remove button to the html code. */
            html_code += `<div class="col-md-3" style="margin-top:28px;">
                            <button type="button" data-id="${count}" class="btn btn-danger btn-md btn-remove" onclick="removeDeduction(this, ${remaining_balance})" data-bs-toggle="tooltip" title="Click to remove Deduction">
                                <i class="mdi mdi-close"></i>
                            </button>
                         </div>`;

            html_code += `</div>`;
        // // $('#dynamic-deduction').append(html_code); => this is a jquery syntax, below is vanilla js way. You can either use this one or the one below
        document.querySelector("#dynamic-deduction").insertAdjacentHTML("beforeend", html_code);
    }
      /**
    * It removes a row and then calls a function to recalculate the total.
    */
    const removeDeduction = (remove_btn, remaining_balance) => {
        count--;
        const btn_id = remove_btn.getAttribute('data-id');
        const deduction_count = document.querySelector('#deduction-count');
        // update deduction count hidden input value for reference
        deduction_count.value = count;

        document.querySelector(`#row${btn_id}`).remove();
        calculateNoaBilling(remaining_balance);
    }

    const enableButtonandDeduction = (totalBill) =>{
        const input_philhealth = document.querySelector('#deduct-philhealth');
        const input_sss = document.querySelector('#deduct-sss');
        const deduct_btn = document.querySelector('#btn-other-deduction');
        const bill_btn = document.querySelector('#btn-bill');

        if(totalBill > 0){
            input_philhealth.removeAttribute('readonly');
            input_sss.removeAttribute('readonly');
            deduct_btn.removeAttribute('disabled');
            bill_btn.removeAttribute('disabled');
        }else{
            input_philhealth.setAttribute('readonly', true);
            input_sss.setAttribute('readonly', true);
            deduct_btn.setAttribute('disabled', true);
            bill_btn.setAttribute('disabled', true);
        }
    }

    // function to be called to show patient's Personal Charge Alert if it Net Bill exceeds patient's remaining MBL balance
    const showPersonalChargeAlert = (charge_amount) => {
        const personalCharge = document.querySelector('#personal-charge');
        // the ids of the html elements below are found in personal-charge_alert.php
        const chargeAlertDiv = document.querySelector('#charge-alert-div');
        const chargeAmount = document.querySelector('#charge-amount');

        /* Calculating the charge amount based on the amount of the transaction. */
        if(charge_amount > 0){
            /* Converting the charge_amount to a Peso currency format. */
            let personal_charge_amount = charge_amount.toLocaleString('en-Ph', {
                style: 'currency',
                currency: 'PHP',
            });
            personalCharge.value = charge_amount.toFixed(2);
            chargeAlertDiv.classList.remove('d-none');
            chargeAlertDiv.classList.add('d-block');
            chargeAmount.innerHTML = personal_charge_amount;
        }else{
            personalCharge.value = 0;
            chargeAlertDiv.classList.remove('d-block');
            chargeAlertDiv.classList.add('d-none');
        }
    }

    const noa_id = `<?php echo $noa_id; ?>`;
    const form = document.querySelector('#formNoaBilling');
    // function to handle consulation form submission
    $('#formNoaBilling').submit(function(event) {
        event.preventDefault();

        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        if($('#net-bill').val() < 0) {
            form.classList.add('was-validated');
            return;
        }

        // show confirm dialog if the form has passed the submit validation check
        $.confirm({
            title: '<strong>Confirmation!</strong>',
            content: 'Are you sure, please review before you proceed?',
            type: 'blue',
            buttons: {
                confirm: {
                    text: 'Yes',
                    btnClass: 'btn-blue',
                    action: function() {
                        let url = `${baseUrl}healthcare-provider/billing/bill-noa/submit/${noa_id}`;
                        let data = $('#formNoaBilling').serialize();

                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: data,
                            dataType: "json",
                            success: function(response) {
                                const { token, status, message, billing_id } = response;

                                if (status == 'success') {
                                    
                                    setTimeout(function () {
                                        window.location.href = `${baseUrl}healthcare-provider/billing/bill-noa/success/${billing_id}`;
                                    }, 500);

                                } else if(status == 'error') {

                                    swal({
                                        title: 'Failed',
                                        text: message,
                                        timer: 3000,
                                        showConfirmButton: false,
                                        type: 'error'
                                    });

                                }
                            }
                        });
                    }
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
    // end of loa consultation form submission

    // function searchFunction() {
    //     var input, filter, table, tr, td, i, txtValue;
    //     input = document.getElementById("myInput");
    //     filter = input.value.toUpperCase();
    //     table = document.getElementById("myTable");
    //     tr = table.getElementsByTagName("tr");
    //     for (i = 0; i < tr.length; i++) {
    //         td = tr[i].getElementsByTagName("td")[0];
    //         if (td) {
    //             txtValue = td.textContent || td.innerText;
    //             if (txtValue.toUpperCase().indexOf(filter) > -1) {
    //                 tr[i].style.display = "";
    //             } else {
    //                 tr[i].style.display = "none";
    //            }
    //         }
    //     }
    // }     

</script>