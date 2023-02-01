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

            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <h4 class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-danger ls-2">Patient Details</span>
                    </h4>
                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div>
                                <h6 class="my-0 text-secondary ls-1">Patient Name</h6>
                                <span class="text-info fw-bold ls-1">
                                    <?php echo $member['first_name'].' '. $member['middle_name'].' '.$member['last_name']; ?>
                                </span>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div>
                                <h6 class="my-0 text-secondary ls-1">Healthcard No.</h6>
                                <span class="text-info fw-bold ls-1">
                                    <?php echo $healthcard_no; ?>
                                </span>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div>
                                <h6 class="my-0 text-secondary ls-1">Maximum Benefit Limit</h6>
                                <span class="text-info fw-bold ls-1">
                                    &#8369;<?= number_format($member_mbl, 2); ?>
                                </span>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between bg-light">
                            <div class="text-secondary ls-1">
                                <h6 class="my-0">Remaining MBL Balance</h6>
                                <span class="text-info fw-bold ls-1">
                                &#8369;<?= number_format($remaining_balance, 2); ?>
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>
                <?php if($request_type == 'Diagnostic Test') : ?>
                    <div class="col-md-8 col-lg-8">
                        <h4 class="mb-3 ls-1">LOA Request Type: <span class="text-danger"><?= $request_type ?></span></h4>
                        <form class="needs-validation" novalidate>
                            <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
                            <input type="hidden" name="loa_id" value="<?= $loa_id ?>">

                            <?php 
                                $selectedOptions = explode(';', $loa['med_services']);
                                $i = 1;
                                foreach ($cost_types as $cost_type) :
                                if (in_array($cost_type['ctype_id'], $selectedOptions)) :
                            ?>
                                <div class="row mt-2">
                                    <div class="col-md-7">
                                        <label class="form-label ls-1">Cost Type</label>
                                        <input type="text" class="form-control text-info fw-bold ls-1" id="ct-name-<?php echo $i; ?>" name="cost-type" value="<?php echo $cost_type['cost_type']; ?>" readonly>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label ls-1">Quantity</label>
                                        <input type="number" class="form-control fw-bold" id="ct-qty-<?php echo $i; ?>" name="ct-qty" oninput="calculateTotalBilling(`<?= $remaining_balance ?>`)" value="1" min="1" required>
                                        <div class="invalid-feedback">
                                            Quantity is required
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label ls-1">Cost</label>
                                        <input type="number" class="ct-inputs form-control fw-bold" id="ct-cost-<?php echo $i; ?>" name="ct-cost" placeholder="Enter Amount" oninput="calculateTotalBilling(`<?= $remaining_balance ?>`)" min="0" required>
                                        <div class="invalid-feedback">
                                            Service Cost is required.
                                        </div>
                                    </div>
                                </div>
                            <?php 
                                    $i += 1;
                                endif;
                                endforeach;
                            ?>
                            <div class="row my-3 d-none" id="charge-alert-div">
                                <div class="col-12">
                                    <div class="alert alert-cyan text-center" role="alert">
                                        <strong class="ls-1">The Total Bill exceeds the patient's Remaining MBL Balance. The <span class="text-danger fs-4">&#8369;</span><span class="text-danger fs-4" id="charge-amount"></span> excess amount will be added to his/her Personal Charges.
                                        </strong>
                                    </div>
                                </div>
                            </div>

                            <div class="row my-4">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-danger text-white ls-1">
                                                Total Bill <i class="mdi mdi-arrow-right-bold ms-1"></i> 
                                            </span>
                                        </div>
                                        <input type="text" class="form-control fw-bold ls-1" id="total-bill" name="total-payment" value="0" readonly>
                                    </div>
                                </div>
                                 <div class="col-lg-6 col-sm-12">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-danger text-white ls-1">
                                                Personal Charge <i class="mdi mdi-arrow-right-bold ms-1"></i> 
                                            </span>
                                        </div>
                                        <input type="text" class="form-control fw-bold ls-1" id="personal-charge" name="personal-charge" value="0" readonly>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label ls-1">LOA Number</label>
                                    <input type="text" class="form-control text-danger fw-bold ls-1" id="loa-no" name="loa-no" value="<?= $loa_no ?>" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label ls-1">Billing Number</label>
                                    <input type="text" class="form-control text-danger fw-bold ls-1" id="billing-no" name="billing-no" value="<?= $billing_no ?>" readonly>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label class="form-label ls-1">Healthcare Provider</label>
                                    <input type="text" class="form-control text-info fw-bold ls-1" id="hcare-provider" name="hcare-provider" value="<?= $hcare_provider ?>" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label ls-1">Billed By</label>
                                    <input type="text" class="form-control text-info fw-bold ls-1" id="billed-by" name="billed-by" value="<?= $billed_by ?>" readonly>
                                </div>
                            
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-dark btn-lg ls-2" id="btn-bill" disabled><i class="mdi mdi-file-check me-1"></i>Bill Now</button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
                
                <?php if($request_type == 'Consultation') : ?>
                    <div class="col-md-8 col-lg-8">
                        <h4 class="mb-3 ls-1">LOA Request Type: <span class="text-danger"><?= $request_type ?></span></h4>
                        <form class="needs-validation" novalidate>
                            <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
                            <div class="row">
                                <div class="col-md-7">
                                    <label class="form-label ls-1">Consultation</label>
                                    <input type="text" class="form-control" id="consultation" name="consultation" value="Consultation" readonly>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label ls-1">Quantity</label>
                                    <input type="number" class="form-control fw-bold" id="ct-quantity" name="ct-quantity" value="1" required>
                                    <div class="invalid-feedback">
                                        Quantity is required
                                    </div>
                                </div>

                                <div class="col-md-3">
                                <label class="form-label ls-1">Cost</label>
                                <input type="number" class="form-control fw-bold" id="ct-cost" name="ct-cost" value="0" required>
                                <div class="invalid-feedback">
                                    Service Cost is required.
                                </div>
                                </div>
                            </div>

                            <div class="row my-4">
                                <div class="col-6 offset-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-danger text-white ls-1">
                                                Total Bill <i class="mdi mdi-arrow-right-bold ms-1"></i> 
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" id="total-payment" name="total-payment" disabled>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label ls-1">LOA Number</label>
                                    <input type="text" class="form-control text-danger fw-bold ls-1" id="loa-no" name="loa-no" value="<?= $loa_no ?>" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label ls-1">Billing Number</label>
                                    <input type="text" class="form-control text-danger fw-bold ls-1" id="billing-no" name="billing-no" value="<?= $billing_no ?>" readonly>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label class="form-label ls-1">Healthcare Provider</label>
                                    <input type="text" class="form-control text-info fw-bold ls-1" id="hcare-provider" name="hcare-provider" value="<?= $hcare_provider ?>" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label ls-1">Billed By</label>
                                    <input type="text" class="form-control text-info fw-bold ls-1" id="billed-by" name="billed-by" value="<?= $billed_by ?>" readonly>
                                </div>
                            
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-end">
                                <button class="btn btn-dark btn-lg ls-2" type="submit" disabled>Review Billing</button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>

            </div>
        </div>        
    </div>
</div>
<script>
    function calculateTotalBilling(remaining_balance) {
        let total_bill = 0;
        let charge_amount = 0;
        let block_chars = ['-', '+'];
        const cost_inputs = document.querySelectorAll(".ct-inputs");
        const quantity_inputs = document.querySelectorAll("input[name='ct-qty']");
        const total_input = document.querySelector("#total-bill");
        
        for (let i = 0; i < cost_inputs.length; i++) {

            /* Preventing the user from entering any characters that are on the block_chars array. */
            cost_inputs[i].onkeypress = function(event) {
                let char = String.fromCharCode(event.which);
                if (block_chars.indexOf(char) >= 0) {
                    return false;
                }
                    return true;
            };

            /* Preventing the user from entering any characters that are on the block_chars array. */
            quantity_inputs[i].onkeypress = function(event) {
                let char = String.fromCharCode(event.which);
                if (block_chars.indexOf(char) >= 0) {
                    return false;
                }
                    return true;
            };

            total_bill += cost_inputs[i].value * quantity_inputs[i].value;
            charge_amount = total_bill - remaining_balance;
        }

        total_input.value = total_bill.toFixed(2);
        // Calling other functions
        enableBillButton(total_bill);
        computePersonalCharge(charge_amount);
    }

    function enableBillButton(total_bill){
        const btnBill = document.querySelector('#btn-bill');

        if(total_bill > 0){
            btnBill.removeAttribute('disabled');
        }else{
            btnBill.setAttribute('disabled', true);
        }
    }

    function computePersonalCharge(charge_amount){
        const personalCharge = document.querySelector('#personal-charge');
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
</script>