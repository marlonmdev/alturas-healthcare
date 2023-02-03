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
        <div class="card py-4 px-4">

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

                        <div class="col-md-2">
                            <label class="form-label ls-1">Patient's MBL</label>
                            <input type="text" class="form-control text-danger fw-bold ls-1" name="patient-mbl" value="<?= '&#8369;'.number_format($member_mbl, 2) ?>" readonly>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label ls-1">Remaining MBL</label>
                            <input type="text" class="form-control text-danger fw-bold ls-1" id="request-type" name="request-type" value="<?= '&#8369;'.number_format($member_mbl, 2) ?>" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 my-1">
                            <label class="form-label ls-1">LOA Number</label>
                            <input type="text" class="form-control text-danger fw-bold ls-1" id="loa-no" name="loa-no" value="<?= $loa_no ?>" readonly>
                        </div>

                        <div class="col-md-3 my-1">
                            <label class="form-label ls-1">LOA Request Type</label>
                            <input type="text" class="form-control text-danger fw-bold ls-1" id="request-type" name="request-type" value="<?= $request_type ?>" readonly>
                        </div>

                        <div class="col-md-6 my-1">
                            <label class="form-label ls-1">Healthcare Provider</label>
                            <input type="text" class="form-control text-danger fw-bold ls-1" id="hcare-provider" name="hcare-provider" value="<?= $hcare_provider ?>" readonly>
                        </div>
                    </div>

                    <hr class="mt-4">

                    <div class="row">
                        <h4 class="text-center ls-2">
                            <i class="mdi mdi-arrow-down-bold-circle"></i> Availed Services <i class="mdi mdi-arrow-down-bold-circle"></i>
                        </h4>
                    </div>
                </div>
                <!-- End of common infos between LOA Diagnostic Test and Consultation Request Type -->

                <!-- Start of Diagnostic LOA Request Billing -->
                <?php if($request_type == 'Diagnostic Test') : ?>
                    <div class="col-12">

                        <!-- Start of form Diagnostic Test -->
                        <form method="POST" id="form-diagnostic" class="needs-validation" novalidate>
                            <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
                            <input type="hidden" name="loa_id" value="<?= $loa_id ?>">
                            <input type="hidden" name="emp_id" value="<?= $member['emp_id'] ?>">

                            <?php 
                                $selectedOptions = explode(';', $loa['med_services']);
                                foreach ($cost_types as $cost_type) :
                                 if (in_array($cost_type['ctype_id'], $selectedOptions)) :
                            ?>
                                <input type="hidden" name="ctype_id" value="<?php echo $cost_type['ctype_id']; ?>">
                                <div class="row mt-2">
                                    <div class="col-md-7">
                                        <label class="form-label ls-1">Cost Type</label>
                                        <input type="text" class="form-control text-info fw-bold ls-1" name="ct-name[]" value="<?php echo $cost_type['cost_type']; ?>" readonly>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label ls-1">Quantity</label>
                                        <input type="number" class="ct-qty form-control fw-bold" name="ct-qty[]" oninput="calculateDiagnosticTestBilling(`<?= $remaining_balance ?>`)" value="1" min="1" required>
                                        <div class="invalid-feedback">
                                            Quantity is required
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label ls-1">Cost</label>
                                        <input type="number" class="ct-cost form-control fw-bold" name="ct-cost[]" placeholder="Enter Amount" oninput="calculateDiagnosticTestBilling(`<?= $remaining_balance ?>`)" min="0" required>
                                        <div class="invalid-feedback">
                                            Service Cost is required.
                                        </div>
                                    </div>
                                </div>
                            <?php 
                                    endif;
                                endforeach;
                            ?>

                            <hr class="mt-4">

                            <div class="row">
                                <h4 class="text-center ls-2">
                                    <i class="mdi mdi-arrow-down-bold-circle"></i> Billing Deductions <i class="mdi mdi-arrow-down-bold-circle"></i>
                                </h4>
                            </div>
                            <div class="row my-2">
                                <div class="col-md-3">
                                    <label class="form-label ls-1">PhilHealth</label>
                                    <input type="number" class="form-control fw-bold" id="deduct-philhealth" name="philhealth-deduction" placeholder="Enter Amount" oninput="calculateDiagnosticTestBilling(`<?= $remaining_balance ?>`)" min="0" readonly>
                                    <span class="text-danger deduction-msg"></span>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label ls-1">SSS</label>
                                    <input type="number" class="form-control fw-bold" id="deduct-sss" name="sss-deduction" placeholder="Enter Amount" oninput="calculateDiagnosticTestBilling(`<?= $remaining_balance ?>`)" min="0" readonly>
                                    <span class="text-danger deduction-msg"></span>
                                </div>
                                <div class="col-md-3 d-flex justify-content-start align-items-end">
                                    <button type="button" class="btn btn-info" id="add-other-deduction"><i class="mdi mdi-plus-circle"></i> Other Deduction</button>
                                </div>
                            </div>
                            
                            <hr class="my-4">

                            <?php include 'personal_charge_alert.php'; ?>

                            <div class="row my-4">
                                <div class="col-md-3">
                                    <label class="form-label ls-1">Total Bill</label>
                                    <input type="text" class="form-control fw-bold ls-1" id="total-bill" name="total-bill" value="0" readonly>
                                </div>

                                <div class="col-lg-3">
                                    <label class="form-label ls-1">Total Deduction</label>
                                    <input type="text" class="form-control fw-bold ls-1" id="total-deduction" name="total-deduction" value="0" readonly>
                                </div>

                                <div class="col-lg-3">
                                    <label class="form-label ls-1">Net Bill</label>
                                    <input type="text" class="form-control fw-bold ls-1" id="net-bill" name="net-bill" value="0" readonly>
                                </div>

                                <div class="col-lg-3">
                                    <label class="form-label ls-1">Personal Charge</label>
                                    <input type="text" class="form-control fw-bold ls-1" id="personal-charge" name="personal-charge" value="0" readonly>
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
                                    <button type="submit" class="btn btn-dark btn-lg ls-2" id="btn-bill" disabled>
                                        <i class="mdi mdi-file-check me-1"></i>Bill Now
                                    </button>
                                </div>
                            </div>
                        </form>
                        <!-- End of form Diagnostic Test -->
                    </div>
                <?php endif; ?>
                <!-- End of Diagnostic LOA Request Billing -->
                
                <!-- Start of Consultation LOA Request Billing -->
                <?php if($request_type == 'Consultation') : ?>
                    <div class="col-12">
                        <!-- Start of form Consultation -->
                        <form method="POST" id="form-consultation" class="needs-validation" novalidate>
                            <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
                            <input type="hidden" name="loa_id" value="<?= $loa_id ?>">
                            <input type="hidden" name="emp_id" value="<?= $member['emp_id'] ?>">
                            
                            <div class="row">
                                <div class="col-md-7">
                                    <label class="form-label ls-1">Consultation</label>
                                    <input type="text" class="form-control" id="consultation" name="consultation" value="Consultation" readonly>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label ls-1">Quantity</label>
                                    <input type="number" class="form-control fw-bold" id="consult-quantity" name="consult-quantity" oninput="calculateConsultationBilling(`<?= $remaining_balance ?>`)" min="1" value="1" required>
                                    <div class="invalid-feedback">
                                        Quantity is required
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label ls-1">Cost</label>
                                    <input type="number" class="form-control fw-bold" id="consult-cost" name="consult-cost" placeholder="Enter Amount" oninput="calculateConsultationBilling(`<?= $remaining_balance ?>`)" min="0" required>
                                    <div class="invalid-feedback">
                                        Service Cost is required.
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">
                             
                            <div class="row">
                                <h4 class="text-center ls-2">
                                    <i class="mdi mdi-arrow-down-bold-circle"></i> Billing Deductions <i class="mdi mdi-arrow-down-bold-circle"></i>
                                </h4>
                            </div>
                            <div class="row my-2">
                                <div class="col-md-3">
                                    <label class="form-label ls-1">PhilHealth</label>
                                    <input type="number" class="form-control fw-bold" id="deduct-philhealth" name="philhealth-deduction" placeholder="Enter Amount" oninput="calculateConsultationBilling(`<?= $remaining_balance ?>`)" min="0" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label ls-1">SSS</label>
                                    <input type="number" class="form-control fw-bold" id="deduct-sss" name="sss-deduction" placeholder="Enter Amount" oninput="calculateConsultationBilling(`<?= $remaining_balance ?>`)" min="0" readonly>
                                </div>
                                <div class="col-md-3 d-flex justify-content-start align-items-end">
                                    <button type="button" class="btn btn-info" id="add-other-deduction"><i class="mdi mdi-plus-circle"></i> Other Deduction</button>
                                </div>
                            </div>
                            
                            <hr class="my-4">

                            <?php include 'personal_charge_alert.php'; ?>

                            <div class="row my-4">
                                <div class="col-md-3">
                                    <label class="form-label ls-1">Total Bill</label>
                                    <input type="text" class="form-control fw-bold ls-1" id="total-bill" name="total-bill" value="0" readonly>
                                </div>

                                <div class="col-lg-3">
                                    <label class="form-label ls-1">Total Deduction</label>
                                    <input type="text" class="form-control fw-bold ls-1" id="total-deduction" name="total-deduction" value="0" readonly>
                                </div>

                                <div class="col-lg-3">
                                    <label class="form-label ls-1">Net Bill</label>
                                    <input type="text" class="form-control fw-bold ls-1" id="net-bill" name="net-bill" value="0" readonly>
                                </div>

                                <div class="col-lg-3">
                                    <label class="form-label ls-1">Personal Charge</label>
                                    <input type="text" class="form-control fw-bold ls-1" id="personal-charge" name="personal-charge" value="0" readonly>
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
                                    <button type="submit" class="btn btn-dark btn-lg ls-2" id="btn-bill" disabled>
                                        <i class="mdi mdi-file-check me-1"></i>Bill Now
                                    </button>
                                </div>
                            </div>
                        </form>
                        <!-- End of form Consultation -->
                    </div>
                <?php endif; ?>
                <!-- End of Consulation LOA Request Billing -->
            </div>
        </div>        
    </div>
</div>
<script>
    // function to be called if LOA Request Type is Diagnostic Test
    function calculateDiagnosticTestBilling(remaining_balance) {
        let total_bill = 0;
        let total_deduction = 0;
        let charge_amount = 0;
        let net_total = 0;
        let philhealth_deduction = 0;
        let sss_deduction = 0;

        const cost_inputs = document.querySelectorAll(".ct-cost");
        const quantity_inputs = document.querySelectorAll(".ct-qty");
        const total_input = document.querySelector("#total-bill");
        const net_bill = document.querySelector("#net-bill");
        const deduct_philhealth = document.querySelector("#deduct-philhealth");
        const deduct_sss = document.querySelector("#deduct-sss");
        const deduction_input = document.querySelector("#total-deduction");
        const deduction_msg = document.querySelectorAll('.deduction-msg');
        
        for (let i = 0; i < cost_inputs.length; i++) {
            /* Calls the function that prevents the user from entering any characters that exists in the block characcters array. */
            blockCharacters(cost_inputs[i]);
            blockCharacters(quantity_inputs[i]);

            total_bill += cost_inputs[i].value * quantity_inputs[i].value;
            charge_amount = total_bill - remaining_balance;
        }

        // Compute Deductions
        philhealth_deduction = deduct_philhealth.value > 0 ? deduct_philhealth.value : 0;
        sss_deduction = deduct_sss.value > 0 ? deduct_sss.value : 0;

        total_deduction = parseFloat(philhealth_deduction) + parseFloat(sss_deduction);

        if(total_deduction > 0) {
            net_total = total_bill - total_deduction;
            charge_amount = net_total - remaining_balance;
        }else{
            net_total = total_bill;
            charge_amount = net_total - remaining_balance;
        }

        if(net_bill.value < 0) {
            deduct_philhealth.classList.add('is-invalid');
            deduct_sss.classList.add('is-invalid');
            net_bill.classList.add('is-invalid');

            for (let i = 0; i < deduction_msg.length; i++) {
                deduction_msg[i].innerHTML = "Deductions can't be greater than the Net Bill Amount";
            }
        }else{
            deduct_philhealth.classList.remove('is-invalid');
            deduct_sss.classList.remove('is-invalid');
            net_bill.classList.remove('is-invalid');

           for (let i = 0; i < deduction_msg.length; i++) {
                deduction_msg[i].innerHTML = "";
            }
        }

        // set the net total as the value of total bill input
        total_input.value = total_bill.toFixed(2);
        deduction_input.value = total_deduction.toFixed(2);
        net_bill.value = net_total.toFixed(2);

        // Calling other functions
        enableBillButtonAndDeductions(total_bill);
        showPersonalChargeNotification(charge_amount);
        blockCharacters(philhealth_deduction);
        blockCharacters(sss_deduction);
    }

    // function to be called to enable or disable deduction inputs and bill button
    function enableBillButtonAndDeductions(total_bill){
        const btnBill = document.querySelector('#btn-bill');
        const philhealth_deduction = document.querySelector("#deduct-philhealth");
        const sss_deduction = document.querySelector("#deduct-sss");

        if(total_bill > 0){
            btnBill.removeAttribute('disabled');
            philhealth_deduction.removeAttribute('readonly');
            sss_deduction.removeAttribute('readonly');
        }else{
            btnBill.setAttribute('disabled', true);
            philhealth_deduction.setAttribute('readonly', true);
            sss_deduction.setAttribute('readonly', true);
        }
    }

     // function to be called to show patient's Personal Charge Alert if it Net Bill exceeds patient's remaining MBL balance
    function showPersonalChargeNotification(charge_amount){
        const personalCharge = document.querySelector('#personal-charge');
        // the ids of the html elements below are found in personal-charge_alert.php
        const chargeAlertDiv = document.querySelector('#charge-alert-div');
        const chargeAmount = document.querySelector('#charge-amount');

        if(charge_amount > 0){
            personalCharge.value = charge_amount.toFixed(2);
            chargeAlertDiv.classList.remove('d-none');
            chargeAlertDiv.classList.add('d-block');
            chargeAmount.innerHTML = charge_amount.toFixed(2);
        }else{
            personalCharge.value = 0;
            chargeAlertDiv.classList.remove('d-block');
            chargeAlertDiv.classList.add('d-none');
        }
    }

    // function to be called if LOA Request Type is Consultation
    function calculateConsultationBilling(remaining_balance){
        let total_bill = 0;
        let total_deduction = 0;
        let charge_amount = 0;
        let net_total = 0;
        let philhealth_deduction = 0;
        let sss_deduction = 0;

        const consult_qty = document.querySelector("#consult-quantity");
        const consult_cost = document.querySelector("#consult-cost");
        const total_input = document.querySelector("#total-bill");
        const net_bill = document.querySelector("#net-bill");
        const deduct_philhealth = document.querySelector("#deduct-philhealth");
        const deduct_sss = document.querySelector("#deduct-sss");
        const deduction_input = document.querySelector("#total-deduction");

        // Calculate Total Billing 
        total_bill = consult_qty.value * consult_cost.value;

        // Compute Deductions
        philhealth_deduction = deduct_philhealth.value > 0 ? deduct_philhealth.value : 0;
        sss_deduction = deduct_sss.value > 0 ? deduct_sss.value : 0;

        total_deduction = parseFloat(philhealth_deduction) + parseFloat(sss_deduction);

        if(total_deduction > 0) {
            net_total = total_bill - total_deduction;
            charge_amount = net_total - remaining_balance;
        }else{
            net_total = total_bill;
            charge_amount = net_total - remaining_balance;
        }

        // set the net total as the value of total bill input
        total_input.value = total_bill.toFixed(2);
        deduction_input.value = total_deduction.toFixed(2);
        net_bill.value = net_total.toFixed(2);

        // Call the other functions to execute
        blockCharacters(consult_qty);
        blockCharacters(consult_cost);
        showPersonalChargeNotification(charge_amount);
        enableBillButtonAndDeductions(total_bill);
    }

    // function to be called to banned some character on number inputs
    function blockCharacters(input){
        const block_chars = ['-', '+'];
        /* Preventing the user from entering any characters that are on the block_chars array. */
        input.onkeypress = function(event) {
            let char = String.fromCharCode(event.which);
            if (block_chars.indexOf(char) >= 0) {
                return false;
            }
                return true;
        };
    }
</script>