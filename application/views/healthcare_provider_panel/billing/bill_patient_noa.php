<!-- Page wrapper  -->
 <div class="page-wrapper">
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
                    <form method="POST" action="<?php echo base_url(); ?>healthcare-provider/billing/search">
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

                            <div class="col-md-4 my-1">
                                <label class="form-label ls-1">NOA Number</label>
                                <input type="text" class="form-control text-danger fw-bold ls-1" id="noa-no" name="noa-no" value="<?= $noa_no ?>" readonly>
                            </div>
                        </div>

                        <div class="row pt-2">
                            <!-- <div class="col-md-2 my-1">
                                <label class="form-label ls-1">Work-Related</label>
                                <input type="text" class="form-control text-danger fw-bold ls-1" id="work-related" name="work-related" value="<?= $work_related ?>" readonly>
                            </div> -->

                            <div class="col-md-6 my-1">
                                <label class="form-label ls-1">Healthcare Provider</label>
                                <input type="text" class="form-control text-danger fw-bold ls-1" id="hcare-provider" name="hcare-provider" value="<?= $hcare_provider ?>" readonly>
                            </div>
                        </div>

                        <hr class="mt-4">

                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <!-- Start of NOA Billing Form -->
                        <form method="POST" id="formNoaBilling" class="needs-validation" novalidate>
                            <!-- start of hidden inputs -->
                            <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
                            <input type="hidden" name="noa-id" value="<?= $noa_id ?>">
                            <input type="hidden" name="emp-id" value="<?= $member['emp_id'] ?>">
                            <input type="hidden" name="remaining-balance" value="<?= $remaining_balance ?>">
                            <input type="hidden" name="work-related" value="<?= $work_related ?>">
                            <input type="hidden" name="medication-count" value="0" min="0" id="medication-count">
                            <input type="hidden" name="profee-count" value="0" min="0" id="profee-count">
                            <input type="hidden" name="deduction-count" id="deduction-count" value="0" min="0">
                            <input type="hidden" name="services-count" id="services-count" value="0" min="0">
                            <input type="hidden" name="total-services" value="0" min="0" id="total-services">
                            <input type="hidden" name="total-medications" value="0" min="0" id="total-medications">
                            <input type="hidden" name="total-profees" value="0" min="0" id="total-profees">   
                            <input type="hidden" name="total-roomboard" value="0" min="0" id="total-roomboard">   
                            <!-- end of hidden inputs -->

                            <h4 class="text-center text-secondary ls-2">MEDICAL SERVICES</h4>

                            <!-- services added to billing will append on this div -->
                            <div id="dynamic-services"></div>
                            <!-- this input value will the change based on the number of services added to billing -->
                        
                            <!-- Start of Cost Types Accordion -->
                            <div class="accordion accordion-flush" id="costTypeAccordion">
                                <div class="accordion-item align-middle" style="border:1px solid #f4f4f4;padding:0px" >
                                    <span class="accordion-header" id="flush-headingOne">
                                        <a href="javascript:void()" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#cost-types-div" aria-expanded="false">
                                            <i class="mdi mdi-hand-pointing-right text-success fs-1 align-end"></i>
                                            <span class="text-info text-decoration-underline fw-bold fs-4 ls-1">
                                                Click here to show and add Medical Services
                                            </span>
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
                                                                            <?= $ct['item_description']; ?>
                                                                        </td>
                                                                        <td class="d-flex justify-content-end align-items-end">
                                                                            <button class="btn btn-cyan text-white ls-1" id="btn-ctype-<?= $ct['ctype_id'] ?>" onclick="addService('<?= $ct['ctype_id'] ?>', '<?= $ct['item_description'] ?>', '<?= $ct['ip_price'] ?>','<?= $remaining_balance ?>')">
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

                            <hr class="mt-4">

                            <div class="row">
                                <div class="col-5">
                                    <h4 class="text-left ls-2 mt-2">
                                        MEDICAL SUPPLIES AND MEDICATIONS
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
                                <div class="col-12">
                                    <h4 class="text-left ls-2 mt-2">
                                        ROOM AND BOARD
                                    </h4>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <select name="room-board" id="room-board" class="form-select fw-bold ls-1" onchange="updateRoomPrice(`<?= $remaining_balance ?>`)" required>
                                            <option value="" selected>Select Room and Board...</option>
                                            <?php
                                                if (!empty($rooms)):
                                                    foreach ($rooms as $room) :
                                            ?>
                                                <option value="<?php echo $room['room_id'].','.$room['room_rate']; ?>"><?= $room['room_type'] ?></option>
                                            <?php 
                                                    endforeach;
                                                endif; 
                                            ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Room type and price is required
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text bg-dark text-white">&#8369;</span>
                                            <input type="text" class="price-inputs form-control fw-bold ls-1" id="room-rate" name="room-rate" placeholder="*Room Price" oninput="calculateNoaBilling(`<?= $remaining_balance ?>`)" readonly required>
                                            <span class="text-danger fw-bold room-board-msg ms-3" style="font-size:12px"></span>
                                        </div>
                                    </div>                                   
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

                                <!-- <div class="col-md-3">
                                    <label class="form-label ls-1">SSS</label> <span class="text-muted">(Optional)</span>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-success text-white">&#8369;</span>
                                        <input type="number" class="input-deduction form-control fw-bold" id="deduct-sss" name="sss-deduction" placeholder="Enter Amount" oninput="calculateNoaBilling(`<?= $remaining_balance ?>`)" min="0" readonly>
                                        <span class="text-danger fw-bold deduction-msg ms-3" style="font-size:12px"></span>
                                    </div>
                                </div> -->

                                <div class="col-md-3" style="margin-top:28px;">
                                    <button type="button" class="btn btn-info" id="btn-other-deduction" onclick="addOtherDeductionInputs(`<?= $remaining_balance ?>`)" disabled>
                                        <i class="mdi mdi-plus-circle"></i> Add Deduction
                                    </button>
                                </div>
                            </div>

                            <!-- other deductions will append on this div -->
                            <div id="dynamic-deduction"></div>

                            <hr class="mt-4">

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

                            <!-- <div class="row my-4">
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
                            </div> -->

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
                                    <button type="submit" class="btn btn-cyan text-white btn-lg ls-2" id="btn-bill" disabled>
                                        <i class="mdi mdi-file-check me-1"></i>Bill Now
                                    </button>
                                </div>
                            </div>

                        </form>
                        <!-- End of NOA Billing Form -->
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
    const rmBal = `<?php echo $remaining_balance; ?>`;

    $(document).ready(function() {
        $('#costTypesTable').DataTable();
    });

    let count = 0; // declaring the count variable outside the function will persist its value even after the function is called, allowing it to increment by one each time the function is called.
     
    // this is for Consultation LOA Requests
    const addService = (ctype_id, ctype_name, price, remaining_balance) => {
        const container = document.getElementById('dynamic-services');
        const services_count = document.querySelector('#services-count');
        count++;
        services_count.value = count;

        let html_code  = `<div class="row my-3 row-services" id="row${count}">`;

            /* cost type name input */
            html_code += `<div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label ls-1">Service Name</label>
                                <input type="text" name="ct-names[]" class="ct-names form-control fw-bold ls-1" value="${ctype_name}" readonly/>
                                <div class="invalid-feedback">
                                    Service Name is required
                                </div>
                            </div>                            
                         </div>`;

            /* cost type quantity input */
            html_code += `<div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label ls-1"><i class="mdi mdi-asterisk text-danger"></i>Quantity</label>
                                <input type="number" name="ct-qtys[]" class="ct-qtys form-control fw-bold ls-1" value="1" min="1" oninput="calculateNoaBilling(${remaining_balance})" required/>
                                <div class="invalid-feedback">
                                    Service Name is required
                                </div>
                            </div>     
                         </div>`;

            /* cost type fee input. */
            html_code += `<div class="col-md-3">
                                <label class="form-label ls-1"><i class="mdi mdi-asterisk text-danger"></i>Service Fee</label> 
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-dark text-white">&#8369;</span>
                                    <input type="number" name="ct-fees[]" class="ct-fees form-control fw-bold ls-1" placeholder="*Enter Amount" value="${price}" oninput="calculateNoaBilling(${remaining_balance})" required/>
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
        // set button to disable attribute to true on add services event
        document.querySelector(`#btn-ctype-${ctype_id}`).setAttribute('disabled', true);

        // call function to calculate billing
        calculateNoaBilling(rmBal);
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

    const updateRoomPrice = (remaining_balance) => {
        const room_selector = document.querySelector('#room-board').value;
        const room_rate = document.querySelector('#room-rate');
        let arr = room_selector.split(",");
        room_rate.value = arr[1];
        if(room_selector !== ''){
            room_rate.removeAttribute('readonly');
            calculateNoaBilling(remaining_balance);
        }else{
            room_rate.value = '';
            room_rate.setAttribute('readonly', true);
            calculateNoaBilling(remaining_balance);
        }

    }

    const calculateNoaBilling = (remaining_balance) => {
        let total_bill = 0;
        let total_deduction = 0;
        let net_bill = 0;
        // let company_charge_amount = 0;
        // let personal_charge_amount = 0;
        let philhealth_deduction = 0;
        let other_deduction = 0;

        const total_services_input = document.querySelector("#total-services");
        const total_meds_input = document.querySelector("#total-medications");
        const total_profees_input = document.querySelector("#total-profees");
        const total_roomboard_input = document.querySelector("#total-roomboard");
        const total_input = document.querySelector('#total-bill');
        const deduct_philhealth = document.querySelector('#deduct-philhealth');
        const deduction_input = document.querySelector('#total-deduction');
        const net_input = document.querySelector('#net-bill');
        // const company_charge = document.querySelector('#company-charge');
        // const personal_charge = document.querySelector('#personal-charge');
        const row_deduction = document.querySelectorAll('.row-deduction');
        const deduction_amount = document.querySelectorAll('.deduction-amount');
        const deduction_msg = document.querySelectorAll('.deduction-msg');
        const other_deduction_msg = document.querySelectorAll('.other-deduction-msg');
        const input_deduction = document.querySelectorAll('.input-deduction');
        // const work_related = document.querySelector('#work-related');
        const room_payment = document.querySelector("#room-rate");

        validateNumberInputs();

        // call other functions and store their return value
        total_services = calculateServices();
        total_medications = calculateMedications();
        total_pro_fees = calculateProfessionalFees();
        total_room_payment = room_payment.value === '' ? 0 : room_payment.value * 1;

        // total bill calculation
        total_bill = (total_services + total_medications + total_room_payment + total_pro_fees) * 1;

        enableButtonsandDeductions(total_bill);

        philhealth_deduction = deduct_philhealth.value > 0 ? deduct_philhealth.value : 0;

        other_deduction = calculateOtherDeductions();

        // total deduction calculation
        total_deduction = parseFloat(philhealth_deduction) + parseFloat(other_deduction);

       /* Calculating the net bill, personal charge amount and company charge amount. */
        if(total_deduction > 0){
            net_bill = total_bill - total_deduction;
            // if(work_related.value === 'Yes' || work_related.value === 'yes'){
            //     personal_charge_amount = 0;
            //     company_charge_amount = net_bill;
            // }else{
            //     personal_charge_amount = net_bill - remaining_balance;
            //     company_charge_amount = net_bill > remaining_balance ? remaining_balance : net_bill;
            // }
        }else{
            net_bill = total_bill;
            // if(work_related.value === 'Yes' || work_related.value === 'yes'){
            //     personal_charge_amount = 0;
            //     company_charge_amount = net_bill;
            // }else{
            //     personal_charge_amount = net_bill - remaining_balance;
            //     company_charge_amount = net_bill > remaining_balance ? remaining_balance : net_bill;
            // }
        }

        total_input.value = total_bill.toFixed(2);

        deduction_input.value = parseFloat(total_deduction).toFixed(2);

        net_input.value = parseFloat(net_bill).toFixed(2);

        total_services_input.value = parseFloat(total_services).toFixed(2);

        total_meds_input.value = parseFloat(total_medications).toFixed(2);

        total_profees_input.value = parseFloat(total_pro_fees).toFixed(2);

        total_roomboard_input.value = parseFloat(total_room_payment).toFixed(2);

        // company_charge.value = company_charge_amount > 0 ? parseFloat(company_charge_amount).toFixed(2) : 0;

        // personal_charge.value = personal_charge_amount > 0 ? parseFloat(personal_charge_amount).toFixed(2) : 0;

        /* Checking if the net bill is less than 0. If it is, it will add the class is-invalid and
        text-danger to the net bill input, deduction inputs, row deductions. */
        if(net_bill < 0){
            net_input.classList.add('is-invalid', 'text-danger');

            for(i = 0; i < input_deduction.length; i++ ){
                input_deduction[i].classList.add('is-invalid', 'text-danger');
                deduction_msg[i].innerHTML = "Deduction can't be greater than the Net Bill Amount!";
            }

            for(i = 0; i < row_deduction.length; i++ ){
                row_deduction[i].classList.add('is-invalid', 'text-danger');
                other_deduction_msg[i].innerHTML = "Deduction can't be greater than the Net Bill Amount!";
            }  
        }else{
            net_input.classList.remove('is-invalid', 'text-danger');
            
            for(i = 0; i < input_deduction.length; i++ ){
                input_deduction[i].classList.remove('is-invalid', 'text-danger');
                deduction_msg[i].innerHTML = " ";
            }

            for(i = 0; i < row_deduction.length; i++ ){
                row_deduction[i].classList.remove('is-invalid', 'text-danger');
                other_deduction_msg[i].innerHTML = " ";
            } 
        }
        
        // call to showPersonalChargeAlert function
        // showPersonalChargeAlert(personal_charge_amount);
    }

    /* Adding an event listener to all the number inputs on the page. When the user inputs a value, the
    code checks if the value is a number and if it is greater than 0. If it is not, the value is set
    to an empty string. */
    

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

    // const preventNotANumber = () => {
    //     const price_inputs = document.querySelectorAll(".price-inputs");

    //     for (let i = 0; i < price_inputs.length; i++) {
    //         price_inputs[i].addEventListener('input', function(event) {
    //             const input = event.target.value;
    //             const isValidNumber = !isNaN(input);
    //             if (!isValidNumber) {
    //                 event.target.value = ''; // Clear the textbox
    //             }
    //         });
    //     }
    // }


    // function to calculate the total of all selected services price/fee
    const calculateServices = () => {
        let total_services = 0;
        const cost_inputs = document.querySelectorAll(".ct-fees");
        const quantity_inputs = document.querySelectorAll(".ct-qtys");

        for (let i = 0; i < cost_inputs.length; i++) {
            total_services += cost_inputs[i].value * quantity_inputs[i].value;
        }

        return total_services;
    }

    // function to calculate the total of all the medications price/fee
    const calculateMedications = () => {
        let total_medications = 0;
        const med_qtys = document.querySelectorAll(".medication-qty");
        const med_costs = document.querySelectorAll(".medication-fee");

        for(let i = 0; i < med_costs.length; i++) {
            total_medications += med_costs[i].value * med_qtys[i].value;
        }

        return total_medications;
    }

    // function to calculate the total of all the professional fees
    const calculateProfessionalFees = () => {
        let total_prof_fees = 0;
        const profee_inputs = document.querySelectorAll(".profee-amount");

        for(let i = 0; i < profee_inputs.length; i++) {
            total_prof_fees += profee_inputs[i].value * 1;
        }

        return total_prof_fees;
    }

    // function to calculate the total of other deductions
    const calculateOtherDeductions = () => {
        let other_deduction = 0;
        const row_deduction = document.querySelectorAll('.row-deduction');
        const deduction_amount = document.querySelectorAll('.deduction-amount');

        for (let i = 0; i < row_deduction.length; i++) {
            other_deduction += deduction_amount[i].value * 1;
        }  

        return other_deduction;
    }


     let deduct_count = 0;
    /**
    * It adds a row of inputs to the form
    */
    const addOtherDeductionInputs = (remaining_balance) => {
        const container = document.querySelector('#dynamic-deduction');
        const deduction_count = document.querySelector('#deduction-count');
        deduct_count++;
        deduction_count.value = deduct_count;

        let html_code  = `<div class="row my-3 row-deduction pt-1" id="row${deduct_count}">`;

           /* deduction_name input */
            html_code += `<div class="col-md-3">
                            <label class="form-label ls-1"><i class="mdi mdi-asterisk text-danger"></i>Deduction Name</label> 
                            <input type="text" name="deduction-name[]" class="form-control fw-bold ls-1" placeholder="Enter Deduction Name" required/>
                            <div class="invalid-feedback">
                                Deduction name and amount is required
                            </div>
                         </div>`;

            /* deduction-amount input. */
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
                            <button type="button" data-id="${deduct_count}" class="btn btn-danger btn-md btn-remove" onclick="removeDeduction(this, ${remaining_balance})" data-bs-toggle="tooltip" title="Click to remove Deduction">
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
        deduct_count--;
        const btn_id = remove_btn.getAttribute('data-id');
        const deduction_count = document.querySelector('#deduction-count');

        // update deduction count hidden input value for reference
        deduction_count.value = deduct_count;

        document.querySelector(`#row${btn_id}`).remove();
        calculateNoaBilling(remaining_balance);
    }


        /**
    * It adds a row of inputs to the form
    */
    let med_count = 0; // declaring the count variable outside the function will persist its value even after the function is called, allowing it to increment by one each time the function is called.

     // this is for Diagnostic Test LOA Requests
    const addMedication  = (remaining_balance) => {
        const container = document.querySelector('#dynamic-medication');
        const medication_count = document.querySelector('#medication-count');
        med_count++;
        medication_count.value = med_count;

        let html_code  = `<div class="row my-3 row-medication" id="row${med_count}">`;

           /* Creating a new input field with the name deduction_name[] */
            html_code += `<div class="col-md-5">
                            <input type="text" name="medication-name[]" class="form-control fw-bold ls-1" placeholder="*Enter Medication Name" required/>
                            <div class="invalid-feedback">
                                Medication name, quantity and amount is required
                            </div>
                         </div>`;
            
            html_code += `<div class="col-md-2">
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-dark text-white">Qty</span>

                                    <input type="number" name="medication-qty[]" class="medication-qty form-control fw-bold ls-1" placeholder="*Medication Quantity" oninput="calculateNoaBilling(${remaining_balance})" value="1" min="1" required/>

                                    <span class="medication-msg text-danger fw-bold"></span>
                                </div>
                          </div>`;

            /* Creating a form input field with a name of deduction_amount[] and a class of
            deduction-amount. */
            html_code += `<div class="col-md-3">
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-dark text-white">&#8369;</span>

                                    <input type="text" name="medication-fee[]" class="medication-fee form-control fw-bold ls-1" placeholder="*Medication Amount" oninput="calculateNoaBilling(${remaining_balance})" required/>

                                    <span class="medication-msg text-danger fw-bold"></span>
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
    // this one is for the dynamic medications
    const removeMedication = (remove_btn, remaining_balance) => {
        med_count--;
        const btn_id = remove_btn.getAttribute('data-id');
        const medication_count = document.querySelector('#medication-count');
        // update deduction count hidden input value for reference
        medication_count.value = med_count;

        document.querySelector(`#row${btn_id}`).remove();
        calculateNoaBilling(remaining_balance);
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

        let html_code  = `<div class="row my-3 row-profee" id="row${prof_count}">`;

           /* Creating a new input field with the name deduction_name[] */
            html_code += `<div class="col-md-5">
                            <input type="text" name="prodoc-name[]" class="form-control fw-bold ls-1" placeholder="*Enter Doctor Name" required/>
                            <div class="invalid-feedback">
                                Doctor name, and Professional Fee is required
                            </div>
                         </div>`;

            /* Creating a form input field with a name of deduction_amount[] and a class of
            deduction-amount. */
            html_code += `<div class="col-md-3">
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-dark text-white">&#8369;</span>

                                    <input type="number" name="profee-amount[]" class="profee-amount form-control fw-bold ls-1" placeholder="*Professional Fee" oninput="calculateNoaBilling(${remaining_balance})" required/>

                                    <span class="profee-msg text-danger fw-bold"></span>
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
    // this one is for the dynamic professional fees
    const removeProFee = (remove_btn, remaining_balance) => {
        prof_count--;
        const btn_id = remove_btn.getAttribute('data-id');
        const profee_count = document.querySelector('#profee-count');
        // update deduction count hidden input value for reference
        profee_count.value = prof_count;

        document.querySelector(`#row${btn_id}`).remove();
        calculateNoaBilling(remaining_balance);
    }


    const enableButtonsandDeductions = (total_bill) =>{
        const input_philhealth = document.querySelector('#deduct-philhealth');
        const deduct_btn = document.querySelector('#btn-other-deduction');
        const bill_btn = document.querySelector('#btn-bill');

        if(total_bill > 0){
            input_philhealth.removeAttribute('readonly');
            deduct_btn.removeAttribute('disabled');
            bill_btn.removeAttribute('disabled');
        }else{
            input_philhealth.setAttribute('readonly', true);
            deduct_btn.setAttribute('disabled', true);
            bill_btn.setAttribute('disabled', true);
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
            let pcharge_amount = personal_charge_amount.toLocaleString('en-Ph', {
                style: 'currency',
                currency: 'PHP',
            });

            personalCharge.value = parseFloat(personal_charge_amount).toFixed(2);

            chargeAlertDiv.classList.remove('d-none');
            chargeAlertDiv.classList.add('d-block');
            chargeAmount.innerHTML = pcharge_amount;
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
            content: 'Are you sure? Please review before you proceed.',
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
                                    }, 300);

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