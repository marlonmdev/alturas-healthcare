<!-- Page wrapper  -->
 <div class="page-wrapper">
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title ls-2">Billing</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Healthcare Provider</li>
                <li class="breadcrumb-item active" aria-current="page">
                  LOA Billing
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- Container fluid  -->
    <div class="container-fluid">
        <div class="card shadow py-4 px-4">

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
                <!-- Start of common infos between LOA Diagnostic Test and Consultation Request Type -->
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

                        <div class="col-md-4 my-1">
                            <label class="form-label ls-1">LOA Number</label>
                            <input type="text" class="form-control text-danger fw-bold ls-1" id="loa-no" name="loa-no" value="<?= $loa_no ?>" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 my-1">
                            <label class="form-label ls-1">LOA Request Type</label>
                            <input type="text" class="form-control text-danger fw-bold ls-1" id="request-type" name="request-type" value="<?= $request_type ?>" readonly>
                        </div>

                        <div class="col-md-2 my-1">
                            <label class="form-label ls-1">Work-Related</label>
                            <input type="text" class="form-control text-danger fw-bold ls-1" id="work-related" name="work-related" value="<?= $work_related ?>" readonly>
                        </div>


                        <div class="col-md-7 my-1">
                            <label class="form-label ls-1">Healthcare Provider</label>
                            <input type="text" class="form-control text-danger fw-bold ls-1" id="hcare-provider" name="hcare-provider" value="<?= $hcare_provider ?>" readonly>
                        </div>
                    </div>

                    <hr class="mt-4">

                    <div class="row">
                        <h4 class="text-left ls-2">
                            AVAILED SERVICES
                        </h4>
                    </div>
                </div>
                <!-- End of common infos between LOA Diagnostic Test and Consultation Request Type -->

                <!-- Start of Diagnostic LOA Request Billing -->
                <div class="col-12">
                    <!-- Start of form Diagnostic Test -->
                    <form method="POST" id="formDiagnosticBilling" class="needs-validation" novalidate>
                        <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" name="loa-id" value="<?= $loa_id ?>">
                        <input type="hidden" name="emp-id" value="<?= $member['emp_id'] ?>">
                        <input type="hidden" name="remaining-balance" value="<?= $remaining_balance ?>">
                        <input type="hidden" name="work-related" value="<?= $work_related ?>">
                        <input type="hidden" name="medication-count" value="0" min="0" id="medication-count">
                        <input type="hidden" name="profee-count" value="0" min="0" id="profee-count">
                        <input type="hidden" name="deduction-count" value="0" min="0" id="deduction-count">

                        <?php 
                            /* Exploding the string into an array and then checking if the array
                            contains the value. */
                            $selectedOptions = explode(';', $loa['med_services']);
                            foreach ($cost_types as $cost_type) :
                                if (in_array($cost_type['ctype_id'], $selectedOptions)) :
                        ?>
                            <input type="hidden" name="ctype_id" value="<?php echo $cost_type['ctype_id']; ?>">
                            <div class="row mt-2">
                                <div class="col-md-7">
                                    <label class="form-label ls-1">Cost Type</label>
                                    <input type="text" class="form-control text-info fw-bold ls-1" name="ct-name[]" value="<?php echo $cost_type['item_description']; ?>" readonly>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label ls-1"><i class="mdi mdi-asterisk text-danger"></i>Quantity</label>
                                    <input type="number" class="ct-qty form-control fw-bold" name="ct-qty[]" oninput="calculateDiagnosticTestBilling(`<?= $remaining_balance ?>`)" value="1" min="1" required>
                                    <div class="invalid-feedback">
                                        Quantity is required
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label ls-1"><i class="mdi mdi-asterisk text-danger"></i>Service Fee</label> 

                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-dark text-white">&#8369;</span>

                                        <input type="number" class="ct-fee form-control fw-bold ls-1" name="ct-fee[]" value="<?php echo $cost_type['op_price']; ?>" placeholder="Enter Amount" oninput="calculateDiagnosticTestBilling(`<?= $remaining_balance ?>`)" min="0" required>
                                        <div class="invalid-feedback">
                                            Service Cost is required.
                                        </div>
                                    </div>
                                </div>

                            </div>
                        <?php 
                                endif;
                            endforeach;
                        ?>

                        <hr class="mt-4">

                        <div class="row">
                            <div class="col-3">
                                <h4 class="text-left ls-2 mt-2">
                                    MEDICATIONS
                                </h4>
                            </div>
                            <div class="col-4">
                                <button type="button" class="btn btn-info" onclick="addMedication(`<?= $remaining_balance ?>`)">
                                <i class="mdi mdi-plus-circle"></i> Add New</button>
                            </div>
                        </div>
                        
                         <!-- dynamic inputs will append on this div -->
                        <div id="dynamic-medication"></div>

                        <hr class="mt-4">

                        <div class="row">
                            <div class="col-3">
                                <h4 class="text-left ls-2 mt-2">
                                    PROFESSIONAL FEES
                                </h4>
                            </div>
                            <div class="col-4">
                                <button type="button" class="btn btn-info" onclick="addProfessionalFee(`<?= $remaining_balance ?>`)">
                                <i class="mdi mdi-plus-circle"></i> Add New</button>
                            </div>
                        </div>
                        
                        <!-- dynamic inputs will append on this div -->
                        <div id="dynamic-profee"></div>

                        <hr class="mt-4">

                        <div class="row">
                            <div class="col-3">
                                <h4 class="text-left ls-2 mt-2">
                                    BILLING DEDUCTIONS
                                </h4>
                            </div>
                            <div class="col-4">
                                <button type="button" class="btn btn-info" id="btn-other-deduction" onclick="addOtherDeductionInputs(`<?= $remaining_balance ?>`)" disabled> <i class="mdi mdi-plus-circle"></i> Add New</button>
                            </div>
                          
                        </div>
                        <div class="row my-2">

                            <div class="col-md-3">
                                <label class="form-label ls-1">PhilHealth</label> <span class="text-muted">(optional)</span>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-success text-white">&#8369;</span>

                                    <input type="number" class="input-deduction form-control fw-bold ls-1" id="deduct-philhealth" name="philhealth-deduction" placeholder="Deduction Amount" oninput="calculateDiagnosticTestBilling(`<?= $remaining_balance ?>`)" min="0" readonly>

                                    <span class="text-danger fw-bold deduction-msg"></span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label ls-1">SSS</label> <span class="text-muted">(optional)</span>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-success text-white">&#8369;</span>

                                    <input type="number" class="input-deduction form-control fw-bold ls-1" id="deduct-sss" name="sss-deduction" placeholder="Deduction Amount" oninput="calculateDiagnosticTestBilling(`<?= $remaining_balance ?>`)" min="0" readonly>

                                    <span class="text-danger fw-bold deduction-msg"></span>
                                </div>
                            </div>
                            
                            <!-- <div class="col-md-3" style="margin-top:28px;">
                                <button type="button" class="btn btn-info" id="btn-other-deduction" onclick="addOtherDeductionInputs(`< $remaining_balance ?>`)" disabled>
                                    <i class="mdi mdi-plus-circle"></i> Add Deduction
                                </button>
                            </div> -->
                            
                        </div>
                        
                        <!-- dynamic inputs will append on this div -->
                        <div id="dynamic-deduction"></div>
                        
                        <hr class="my-4">
                        
                        <!-- this is a custom alert to show if the net bill exceeds the member's remaining balance -->
                        <?php include 'personal_charge_alert.php'; ?>

                        <div class="row my-4">
                            <div class="col-md-4">
                                <label class="form-label ls-1">Total Bill</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-cyan text-white">&#8369;</span>
                                    <input type="text" class="form-control fw-bold ls-1" id="total-bill" name="total-bill" value="" readonly>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label ls-1">Total Deduction</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-cyan text-white">&#8369;</span>
                                    <input type="text" class="form-control fw-bold ls-1" id="total-deduction" name="total-deduction" value="" readonly>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label ls-1">Net Bill</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-cyan text-white">&#8369;</span>
                                    <input type="text" class="form-control fw-bold ls-1" id="net-bill" name="net-bill" value="" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row my-4">
                            <div class="col-md-3">
                                <label class="form-label ls-1">Patient's MBL</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-info text-white">&#8369;</span>
                                    <input type="text" class="form-control fw-bold ls-1" id="patient-mbl" name="patient-mbl" value="<?= number_format($member_mbl, 2) ?>" readonly>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label ls-1">Patient's Remaining MBL</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-info text-white">&#8369;</span>
                                    <input type="text" class="form-control fw-bold ls-1" id="remaining-mbl" name="remaining-mbl" value="<?= number_format($remaining_balance, 2) ?>" readonly>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label ls-1">Company Charge</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-danger text-white">&#8369;</span>
                                    <input type="text" class="form-control fw-bold ls-1" id="company-charge" name="company-charge" value="" readonly>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label ls-1">Personal Charge</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-danger text-white">&#8369;</span>
                                    <input type="text" class="form-control fw-bold ls-1" id="personal-charge" name="personal-charge" value="" readonly>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row">
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
                                <button type="submit" class="btn btn-cyan text-white btn-lg ls-2" id="btn-bill" disabled>
                                    <i class="mdi mdi-file-check me-1"></i>Bill Now
                                </button>
                            </div>
                        </div>
                    </form>
                    <!-- End of form Diagnostic Test -->
                </div>
                <!-- End of Diagnostic LOA Request Billing -->
            </div>
        </div>        
    </div>
</div>
<script>
    const baseUrl = `<?php echo base_url(); ?>`;
    const rmg_bal = `<?php echo $remaining_balance; ?>`;

    // function to be called if LOA Request Type is Diagnostic Test
    const calculateDiagnosticTestBilling = (remaining_balance) => {

        let total_bill = 0;
        let total_deduction = 0;
        let company_charge_amount = 0;
        let personal_charge_amount = 0;
        let net_total = 0;
        let philhealth_deduction = 0;
        let sss_deduction = 0;
        let other_deduction = 0;

        const cost_inputs = document.querySelectorAll(".ct-fee");
        const quantity_inputs = document.querySelectorAll(".ct-qty");
        const total_input = document.querySelector("#total-bill");
        const net_bill = document.querySelector("#net-bill");
        const company_charge = document.querySelector('#company-charge');
        const deduct_philhealth = document.querySelector("#deduct-philhealth");
        const deduct_sss = document.querySelector("#deduct-sss");
        const deduction_input = document.querySelector("#total-deduction");
        const input_deduction = document.querySelectorAll('.input-deduction');
        const deduction_msg = document.querySelectorAll('.deduction-msg');
        const other_deduction_msg = document.querySelectorAll('.other-deduction-msg');
        const row_deduction = document.querySelectorAll('.row-deduction');
        const deduction_amount = document.querySelectorAll('.deduction-amount');
        const work_related = document.querySelector('#work-related');

        // calling this function to prevent negative inputs
        validateNumberInputs();

        /* Calculating the total bill and the charge amount. */
        for (let i = 0; i < cost_inputs.length; i++) {
            total_bill += cost_inputs[i].value * quantity_inputs[i].value;
            personal_charge_amount = total_bill - remaining_balance;
            company_charge_amount = total_bill > remaining_balance ? remaining_balance : total_bill;
        }

        // Compute Deductions
        philhealth_deduction = deduct_philhealth.value > 0 ? deduct_philhealth.value : 0;
        sss_deduction = deduct_sss.value > 0 ? deduct_sss.value : 0;

        // Calculate other deduction total
        if(row_deduction.length > 0){
            for (let i = 0; i < row_deduction.length; i++) {
                other_deduction += deduction_amount[i].value * 1;
            }
        }               

        /* Calculating the total deduction, net total and charge amount. */
        total_deduction = parseFloat(philhealth_deduction) + parseFloat(sss_deduction) + parseFloat(other_deduction);

        // Calculation of Net Bill and Charge amount based on total deduction
        if(total_deduction > 0) {
            net_total = total_bill - total_deduction;
            if(work_related.value === 'Yes'){
                personal_charge_amount = 0;
                company_charge_amount = net_total;
            }else{
                personal_charge_amount = net_total - remaining_balance;
                company_charge_amount = net_total > remaining_balance ? remaining_balance : net_total;
            }
        }else{
            net_total = total_bill;
            if(work_related.value === 'Yes'){
                personal_charge_amount = 0;
                company_charge_amount = net_total;
            }else{
                personal_charge_amount = net_total - remaining_balance;
                company_charge_amount = net_total > remaining_balance ? remaining_balance : net_total;
            }
        }

       /* Checking if the net total is less than 0. If it is, it will add the class is-invalid and
       text-danger to the net_bill. It will also add the class is-invalid and text-danger to the
       input_deduction and deduction_msg. It will also add the class is-invalid and text-danger to
       the deduction_amount and other_deduction_msg. */
        if(net_total < 0) {
            net_bill.classList.add('is-invalid', 'text-danger');

            // philhealth and sss deduction
            for (let i = 0; i < input_deduction.length; i++) {
                input_deduction[i].classList.add('is-invalid', 'text-danger');
                deduction_msg[i].innerHTML = "Deductions can't be greater than the Net Bill Amount";
            }

            // other dynamic deductions
            for (let i = 0; i < row_deduction.length; i++) {
                deduction_amount[i].classList.add('is-invalid', 'text-danger');
                other_deduction_msg[i].innerHTML = "Deductions can't be greater than the Net Bill Amount";
            }
        }else{
            net_bill.classList.remove('is-invalid', 'text-danger');
            // remove error signs and messages philhealth and sss deduction
            for (let i = 0; i < input_deduction.length; i++) {
                input_deduction[i].classList.remove('is-invalid', 'text-danger');
                deduction_msg[i].innerHTML = "";
            }

            // remove error signs and messages of other dynamic deductions
            for (let i = 0; i < row_deduction.length; i++) {
                deduction_amount[i].classList.remove('is-invalid', 'text-danger');
                other_deduction_msg[i].innerHTML = "";
            }
        }
        
        /* Checking if the total bill is 0, if it is, it will remove the deductions and set the total
        bill, total deduction and net bill to 0. */
        if(total_bill == 0){
            // philhealth and sss deduction
            for (let i = 0; i < input_deduction.length; i++) {
                input_deduction[i].value = '';
                input_deduction[i].classList.remove('is-invalid', 'text-danger');
                deduction_msg[i].innerHTML = "";
            }

            // other dynamic deductions
            for (let i = 0; i < row_deduction.length; i++) {
                row_deduction[i].remove();
            }

            total_input.value = 0;
            deduction_input.value = 0;
            net_bill.classList.remove('is-invalid', 'text-danger');
            net_bill.value = 0;
            company_charge.value = 0;
        }else{
            // set the net total as the value of total bill input
            total_input.value = parseFloat(total_bill).toFixed(2);
            deduction_input.value = parseFloat(total_deduction).toFixed(2);
            net_bill.value = parseFloat(net_total).toFixed(2);
            company_charge.value = parseFloat(company_charge_amount).toFixed(2);
        }

        // Calling other functions
        enableButtonsAndDeductions(total_bill);
        showPersonalChargeAlert(personal_charge_amount);
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

    // function to be called to enable or disable deduction inputs and bill button
    const enableButtonsAndDeductions = (total_bill) => {
        const btnBill = document.querySelector('#btn-bill');
        const btnAddDeduction = document.querySelector('#btn-other-deduction');
        const philhealth_deduction = document.querySelector("#deduct-philhealth");
        const sss_deduction = document.querySelector("#deduct-sss");

       /* Checking if the total bill is greater than 0. If it is, it will remove the disabled attribute
       from the buttons and the readonly attribute from the deductions. If it is not, it will add
       the disabled attribute to the buttons and the readonly attribute to the deductions. */
        if(total_bill > 0){
            btnBill.removeAttribute('disabled');
            btnAddDeduction.removeAttribute('disabled');
            philhealth_deduction.removeAttribute('readonly');
            sss_deduction.removeAttribute('readonly');
        }else{
            btnBill.setAttribute('disabled', true);
            btnAddDeduction.setAttribute('disabled', true);
            philhealth_deduction.setAttribute('readonly', true);
            sss_deduction.setAttribute('readonly', true);
        }
    }

     // function to be called to show patient's Personal Charge Alert if it Net Bill exceeds patient's remaining MBL balance
    const showPersonalChargeAlert = (personal_charge_amount) => {
        const personalCharge = document.querySelector('#personal-charge');
        // the ids of the html elements below are found in personal-charge_alert.php
        const chargeAlertDiv = document.querySelector('#charge-alert-div');
        const chargeAmount = document.querySelector('#charge-amount');

        /* Calculating the charge amount based on the amount of the transaction. */
        if(personal_charge_amount > 0){
            /* Converting the charge_amount to a Peso currency format. */
            let pcharge_amount = personal_charge_amount.toLocaleString('en-US', {
                style: 'currency',
                currency: 'PHP',
            });

            personalCharge.value = personal_charge_amount.toLocaleString('en-US', { minimumFractionDigits: 2 });
            chargeAlertDiv.classList.remove('d-none');
            chargeAlertDiv.classList.add('d-block');
            chargeAmount.innerHTML = pcharge_amount;
        }else{
            personalCharge.value = 0;
            chargeAlertDiv.classList.remove('d-block');
            chargeAlertDiv.classList.add('d-none');
        }
    }

    /**
    * It adds a row of inputs to the form
    */
    let count = 0; // declaring the count variable outside the function will persist its value even after the function is called, allowing it to increment by one each time the function is called.

     // this is for Diagnostic Test LOA Requests
    const addOtherDeductionInputs = (remaining_balance) => {
        const container = document.querySelector('#dynamic-deduction');
        const deduction_count = document.querySelector('#deduction-count');
        count++;
        deduction_count.value = count;

        let html_code  = `<div class="row my-3 row-deduction" id="row${count}">`;

           /* Creating a new input field with the name deduction_name[] */
            html_code += `<div class="col-md-3">
                            <input type="text" name="deduction-name[]" class="form-control fw-bold ls-1" placeholder="*Enter Deduction Name" required/>
                            <div class="invalid-feedback">
                                Deduction name and amount is required
                            </div>
                         </div>`;

            /* Creating a form input field with a name of deduction_amount[] and a class of
            deduction-amount. */
            html_code += `<div class="col-md-3">
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-success text-white">&#8369;</span>

                                    <input type="number" name="deduction-amount[]" class="deduction-amount form-control fw-bold ls-1" placeholder="*Deduction Amount" oninput="calculateDiagnosticTestBilling(${remaining_balance})" required/>

                                    <span class="other-deduction-msg text-danger fw-bold"></span>
                                </div>
                          </div>`;
            
           /* Adding a remove button to the html code. */
            html_code += `<div class="col-md-3">
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
    // this one is for the dynamic deductions
    const removeDeduction = (remove_btn, remaining_balance) => {
        count--;
        const btn_id = remove_btn.getAttribute('data-id');
        const deduction_count = document.querySelector('#deduction-count');
        // update deduction count hidden input value for reference
        deduction_count.value = count;

        document.querySelector(`#row${btn_id}`).remove();
        calculateDiagnosticTestBilling(remaining_balance);
    }


    /**
    * It adds a row of inputs to the form
    */
    let med_count = 0; // declaring the count variable outside the function will persist its value even after the function is called, allowing it to increment by one each time the function is called.

     // this is for Diagnostic Test LOA Requests
    const addMedication  = (remaining_balance) => {
        const container = document.querySelector('#dynamic-deduction');
        const deduction_count = document.querySelector('#deduction-count');
        count++;
        deduction_count.value = med_count;

        let html_code  = `<div class="row my-3 row-deduction" id="row${med_count}">`;

           /* Creating a new input field with the name deduction_name[] */
            html_code += `<div class="col-md-5">
                            <input type="text" name="medication-name[]" class="form-control fw-bold ls-1" placeholder="*Enter Medication Name" required/>
                            <div class="invalid-feedback">
                                Medication name, quantity and amount is required
                            </div>
                         </div>`;
            
            html_code += `<div class="col-md-2">
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-success text-white">Qty</span>

                                    <input type="number" name="medication-qty[]" class="medication-qty form-control fw-bold ls-1" placeholder="*Medication Quantity" oninput="calculateDiagnosticTestBilling(${remaining_balance})" value="1" min="1" required/>

                                    <span class="other-deduction-msg text-danger fw-bold"></span>
                                </div>
                          </div>`;

            /* Creating a form input field with a name of deduction_amount[] and a class of
            deduction-amount. */
            html_code += `<div class="col-md-3">
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-success text-white">&#8369;</span>

                                    <input type="number" name="medication-amount[]" class="medication-amount form-control fw-bold ls-1" placeholder="*Medication Amount" oninput="calculateDiagnosticTestBilling(${remaining_balance})" required/>

                                    <span class="other-deduction-msg text-danger fw-bold"></span>
                                </div>
                          </div>`;
            
           /* Adding a remove button to the html code. */
            html_code += `<div class="col-md-2">
                            <button type="button" data-id="${med_count}" class="btn btn-danger btn-md btn-remove" onclick="removeMedication(this, ${remaining_balance})" data-bs-toggle="tooltip" title="Click to remove Medication">
                                <i class="mdi mdi-close"></i>
                            </button>
                         </div>`;

            html_code += `</div>`;
        // // $('#dynamic-deduction').append(html_code); => this is a jquery syntax, below is vanilla js way. You can either use this one or the one below
        document.querySelector("#dynamic-medication").insertAdjacentHTML("beforeend", html_code);
    }

    /**
    * It removes a row and then calls a function to recalculate the total.
    */
    // this one is for the dynamic deductions
    const removeMedication = (remove_btn, remaining_balance) => {
        med_count--;
        const btn_id = remove_btn.getAttribute('data-id');
        const medication_count = document.querySelector('#medication-count');
        // update deduction count hidden input value for reference
        medication_count.value = med_count;

        document.querySelector(`#row${btn_id}`).remove();
        calculateDiagnosticTestBilling(remaining_balance);
    }


     /**
    * It adds a row of inputs to the form
    */
    let prof_count = 0; // declaring the count variable outside the function will persist its value even after the function is called, allowing it to increment by one each time the function is called.

     // this is for Diagnostic Test LOA Requests
    const addProfessionalFee  = (remaining_balance) => {
        const container = document.querySelector('#dynamic-profee');
        const profee_count = document.querySelector('#profee-count');
        prof_count++;
        profee_count.value = prof_count;

        let html_code  = `<div class="row my-3 row-deduction" id="row${prof_count}">`;

           /* Creating a new input field with the name deduction_name[] */
            html_code += `<div class="col-md-5">
                            <input type="text" name="profdoc-name[]" class="form-control fw-bold ls-1" placeholder="*Enter Doctor Name" required/>
                            <div class="invalid-feedback">
                                Doctor name, and Professional Fee is required
                            </div>
                         </div>`;

            /* Creating a form input field with a name of deduction_amount[] and a class of
            deduction-amount. */
            html_code += `<div class="col-md-3">
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-success text-white">&#8369;</span>

                                    <input type="number" name="profee-amount[]" class="profee-amount form-control fw-bold ls-1" placeholder="*Professional Fee" oninput="calculateDiagnosticTestBilling(${remaining_balance})" required/>

                                    <span class="other-deduction-msg text-danger fw-bold"></span>
                                </div>
                          </div>`;
            
           /* Adding a remove button to the html code. */
            html_code += `<div class="col-md-2">
                            <button type="button" data-id="${prof_count}" class="btn btn-danger btn-md btn-remove" onclick="removeProFee(this, ${remaining_balance})" data-bs-toggle="tooltip" title="Click to remove Professional Fee">
                                <i class="mdi mdi-close"></i>
                            </button>
                         </div>`;

            html_code += `</div>`;
        // $('#dynamic-deduction').append(html_code); => this is a jquery syntax, below is vanilla js way. You can either use this one or the one below
        document.querySelector("#dynamic-profee").insertAdjacentHTML("beforeend", html_code);
    }

    /**
    * It removes a row and then calls a function to recalculate the total.
    */
    // this one is for the dynamic deductions
    const removeProFee = (remove_btn, remaining_balance) => {
        prof_count--;
        const btn_id = remove_btn.getAttribute('data-id');
        const profee_count = document.querySelector('#profee-count');
        // update deduction count hidden input value for reference
        profee_count.value = prof_count;

        document.querySelector(`#row${btn_id}`).remove();
        calculateDiagnosticTestBilling(remaining_balance);
    }


    const loa_id = `<?php echo $loa_id; ?>`;
    const form = document.querySelector('#formDiagnosticBilling');

    // function to handle diagnostic test form submission
    $('#formDiagnosticBilling').submit(function(event) {
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
            content: '<strong class="fs-4">Are you sure? Please review before you proceed.</strong><br> <p class="fs-5">Once it has been sent, you cannot undo it.</p>',
            type: 'blue',
            buttons: {
                confirm: {
                    text: 'Yes',
                    btnClass: 'btn-blue',
                    action: function() {
                        let url = `${baseUrl}healthcare-provider/billing/bill-loa/diagnostic-test/submit/${loa_id}`;
                        let data = $('#formDiagnosticBilling').serialize();

                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: data,
                            dataType: "json",
                            success: function(response) {
                                const { token, status, message, billing_id } = response;

                                if (status == 'success') {
    
                                    setTimeout(function () {
                                        window.location.href = `${baseUrl}healthcare-provider/billing/bill-loa/diagnostic-test/success/${billing_id}`;
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
    // end of loa diagnostic test form submission

    // run a function
    document.addEventListener('DOMContentLoaded', calculateDiagnosticTestBilling(rmg_bal));

</script>