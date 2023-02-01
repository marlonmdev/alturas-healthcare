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
                                <h6 class="my-0">Remaining Balance</h6>
                                <span class="text-info fw-bold ls-1">
                                &#8369;<?= number_format($remaining_balance, 2); ?>
                                </span>
                            </div>
                        </li>
                    </ul>

                    <!-- <form class="card p-2">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Promo code">
                            <button type="submit" class="btn btn-secondary">Redeem</button>
                        </div>
                    </form> -->
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
                                    <input type="number" class="form-control fw-bold" id="ct-qty-<?php echo $i; ?>" name="ct-qty" value="1" oninput="calculateTotal(`<?= $remaining_balance ?>`)" required>
                                    <div class="invalid-feedback">
                                        Quantity is required
                                    </div>
                                    </div>

                                    <div class="col-md-3">
                                    <label class="form-label ls-1">Cost</label>
                                    <input type="number" class="ct-inputs form-control fw-bold" id="ct-cost-<?php echo $i; ?>" name="ct-cost" placeholder="Enter Amount" value="0" oninput="calculateTotal(`<?= $remaining_balance ?>`)" required>
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
                            <div class="row my-3">
                                <div class="alert alert-warning alert-dismissible fade show" role="alert" id="excess-alert">
                                    <strong>Holy guacamole!</strong> You should check in on some of those fields below.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                                        <input type="text" class="form-control fw-bold ls-1" id="total-bill" name="total-payment" disabled>
                                    </div>
                                </div>
                                 <div class="col-lg-6 col-sm-12">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-danger text-white ls-1">
                                                Personal Charge <i class="mdi mdi-arrow-right-bold ms-1"></i> 
                                            </span>
                                        </div>
                                        <input type="text" class="form-control fw-bold ls-1" id="personal-charge" name="personal-charge" disabled>
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
                                <button class="btn btn-dark btn-lg ls-2" type="submit" disabled><i class="mdi mdi-file-check me-1"></i>Bill Now</button>
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
    function calculateTotal(remaining_balance) {
        const costInputs = document.querySelectorAll(".ct-inputs");
        const quantityInputs = document.querySelectorAll("input[name='ct-qty']");
        const totalBill = document.querySelector("#total-bill");
        const personalCharge = document.querySelector('#personal-charge');
        let total = 0;
        let charge = 0;
        
        for (let i = 0; i < costInputs.length; i++) {
            total += costInputs[i].value * quantityInputs[i].value;
            charge = total - remaining_balance;
        }

        totalBill.value = total;
        if(charge > 0){
            personalCharge.value = charge;
        }
    }
</script>