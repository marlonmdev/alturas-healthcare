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
                            Bill Services
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
                        <input type="text" class="form-control text-danger fw-bold ls-1" name="patient-name" value="<?= $members['first_name'].' '. $members['middle_name'].' '.$members['last_name'] ?>" readonly>
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

                    <div class="col-md-6 my-1">
                        <label class="form-label ls-1">Healthcare Provider</label>
                        <input type="text" class="form-control text-danger fw-bold ls-1" id="hcare-provider" name="hcare-provider" value="<?= $hcare_provider ?>" readonly>
                    </div>
                </div>

                <hr class="mt-4">

                <div class="row pb-2">
                    <h4 class="text-center ls-2">
                        <i class="mdi mdi-arrow-down-bold-circle"></i> Medical Services <i class="mdi mdi-arrow-down-bold-circle"></i>
                    </h4>
            </div>
        </div>
            <div class="col-12 parent_element">
                <form method="post" id="form-med-services" class="needs-validation" action="<?php echo base_url(); ?>healthcare-provider/billing/billing-person/finalBilling" onsubmit="submitEquipment()">
                    <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="deduction-count" value="0" min="0" id="deduction-count">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tbl-charges" class="table table-hover">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="fw-bold">Cost Type</th>
                                            <th class="fw-bold">Quantity</th>
                                            <th class="fw-bold">Fee</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">
                                    </tbody>
                                </table>
                                <!-- <div class="d-flex justify-content-center">
                                    <button type="submit" disabled id="submit-id" class="btn btn-info">
                                        <i class="mdi mdi-content-save me-1"></i>Apply
                                    </button>
                                </div>  -->
                            </div>
                        </div>
                    </div>                    
                </form>

                <div class="accordion accordion-flush border border-secondary" id="policyAccordionFlush">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#policy-ch-1" aria-expanded="false" aria-controls="policy-ch-1">
                                <strong class="text-success fs-5">Click Here to add Medical Services<i class="mdi mdi-cursor-default-outline text-dark ms-2"></i></strong>
                            </button>
                        </h2>
                        <div id="policy-ch-1" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#policyAccordionFlush">
                            <div class="d-flex justify-content-center mx-5 pt-2">
                                <input type="text" id="myInput" onkeyup="searchFunction()" placeholder="Search Medical Services...">
                            </div>
                            <div class="accordion-body">
                                <div class="card ps-2 pe-2">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="myTable" class="table table-hover">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th class="fw-bold" style="width:700px">Name</th>
                                                        <th class="fw-bold" style="width:200px">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($cost_type)):
                                                        foreach ($cost_type as $ct) :
                                                    ?>
                                                            <tr>
                                                                <td class="fw-bold">
                                                                    <?= $ct['cost_type']; ?>
                                                                </td>
                                                                <td>
                                                                    <button class="btn btn-success" id="btn<?= $ct['ctype_id'] ?>" onclick="addService('<?= $ct['ctype_id'] ?>',' <?= $ct['cost_type'] ?>')">
                                                                        <i class="mdi mdi-plus-circle"></i> Add
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
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="mt-4">

                <div class="row">
                    <h4 class="text-center fw-bold ls-2">
                        <i class="mdi mdi-arrow-down-bold-circle"></i> Billing Deductions <i class="mdi mdi-arrow-down-bold-circle"></i>
                    </h4>
                </div>
                <div class="row my-2">

                    <div class="col-md-3">
                        <label class="form-label ls-1">PhilHealth <span class="text-info fw-bold fst-italic">(Optional)</span></label>
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-success text-white">&#8369;</span>
                            <input type="number" class="input-deduction form-control fw-bold" id="deduct-philhealth" name="philhealth-deduction" placeholder="Enter Amount" oninput="CalculateNOABilling(`<?= $remaining_balance ?>`)" min="0" readonly>
                            <span class="text-danger fw-bold deduction-msg ms-3" style="font-size:12px"></span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label ls-1">SSS <span class="text-info fw-bold fst-italic">(Optional)</span></label>
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-success text-white">&#8369;</span>
                            <input type="number" class="input-deduction form-control fw-bold" id="deduct-sss" name="sss-deduction" placeholder="Enter Amount" oninput="CalculateNOABilling(`<?= $remaining_balance ?>`)" min="0" readonly>
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
                        <input type="text" class="form-control text-success fw-bold ls-1" id="net-bill" name="net-bill" value="0" readonly>
                    </div>

                    <div class="col-lg-3">
                        <label class="form-label ls-1">Personal Charge</label>
                        <input type="text" class="form-control text-cyan fw-bold ls-1" id="personal-charge" name="personal-charge" value="0" readonly>
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
                    
                    <hr class="mt-4">

                    <div class="row mt-3">
                        <div class="col-md-12 d-flex offset-8">
                            <button type="submit" class="btn btn-danger btn-lg ls-2" id="btn-bill" disabled>
                                <i class="mdi mdi-file-check me-1"></i>Bill Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a href="#" class="scroll-to-top fs-3" title="Scroll to top"><i class="mdi mdi-arrow-up-drop-circle-outline"></i></a>
</div>

<style>
    #myInput {
        width: 50%;
        font-size: 16px;
        padding: 10px;
        border: 1px solid #f1f1f1;
        margin-bottom: 20px;
        border-radius: 5px;
    }
    .accordion-body {
        max-height: 300px;
        overflow-y: auto;
    }
    .scroll-to-top {
        position: fixed;
        bottom: 20px;
        right: 20px;
        /* display: none; */
    }

    .scroll-to-top.show {
        display: inline-block;
    }

    .scroll-to-top i {
        font-size: 20px;
    }

</style>

<script>
    $(document).ready(function() {
        $('.accordion').on('shown.bs.collapse', function (e) {
            var accordionBody = $(e.target).find('.accordion-body');
            $('html, body').animate({scrollTop: accordionBody.offset().top - accordionBody.outerHeight()}, 800);
        });
        
        $(window).scroll(function() {
            if ($(this).scrollTop() > 100) {
            $('.scroll-to-top').addClass('show');
            } else {
            $('.scroll-to-top').removeClass('show');
            }
        });

        $('.scroll-to-top').click(function(event) {
            event.preventDefault();
            $('html, body').animate({scrollTop: 0}, 900);
        });

        $('.inputCT').click(function(){
            $(this).closest('.parent_element').find('.accordion').slideUp();
        });

    });


    var numberOfRow = 0;
    var chargesEquip = [];
    var readySubmit = false;
    const baseUrlSubmit = "<?php echo base_url() ?>healthcare-provider/reports/report-list/ajax/addEquipments";
    const billingNumber = "<?php echo $member['billing_number'] ?>";
    const memberId = "<?php echo $member['member_id'] ?>";

    function submitEquipment() {

        $(".inputCT").each(function() {
            var input = $(this); // This is the jquery object of the input, do what you will

            if (!input.val()) {
                input.addClass("is-invalid")
            } else {
                input.removeClass("is-invalid");
                var idCostType = input.attr('id').substring(2);
                var finalIdCostType = "ct" + idCostType;
                var ctName = document.getElementById(finalIdCostType).innerText;

                console.log(baseUrlSubmit)
                $.ajax({
                    type: "post",
                    url: baseUrlSubmit,
                    data: {
                        token: "<?php echo $this->security->get_csrf_hash() ?>",
                        ctype_id: idCostType,
                        cost_type: ctName,
                        billingNumber: billingNumber,
                        emp_id: memberId,
                        amount: input.val()
                    },
                    dataType: "json",
                    success: function(res) {
                        console.log(res)
                    }
                })
                console.log({
                    token: "<?php echo $this->security->get_csrf_hash() ?>",
                    ctype_id: idCostType,
                    cost_type: ctName,
                    billingNumber: billingNumber,
                    emp_id: memberId,
                    amount: input.val()
                })
                chargesEquip.push({
                    token: "<?php echo $this->security->get_csrf_hash() ?>",
                    ctype_id: idCostType,
                    cost_type: ctName,
                    billingNumber: billingNumber,
                    emp_id: memberId,
                    amount: input.val()
                });
            }
        });
        $("#equipment_cost").val(JSON.stringify(chargesEquip));
    }


    function enableInput(x) {
        if (document.getElementById('cb' + x).checked) {
            $("." + x).prop("disabled", false);
        } else {
            $("." + x).prop("disabled", true);
            $("." + x).val(0);
        }
    }

    function addService(id, name) {
        numberOfRow++;

        var trId = "tr" + id;
        var finalTrId = trId.replace(/\s/g, '');

        var inId = "in" + id;
        var finalInId = inId.replace(/\s/g, '');

        var ctName = "ct" + id;
        var finalctName = ctName.replace(/\s/g, '');
        const baseUrl = "<?php echo base_url(); ?>healthcare-provider/reports/report-list/ajax/addEquip/" + id;
        console.log(baseUrl.replace(/\s/g, ''))
        $.ajax({
            type: "GET",
            url: baseUrl.replace(/\s/g, ''),
            dataType: "json",
            success: function(response) {
                $("#tbl-charges > tbody").append(
                    '<tr id="' + finalTrId + '" class="otherInput' + numberOfRow + '">\
                        <td class="fw-bold" style="width:50%">\
                            <span id="' + finalctName + '">' + name + '</span>\
                        </td>\
                        <td class="fw-bold" style="width:20%">\
                            <input type="number" id="' + finalInId + '" class="inputCT ct_qty form-control" value="1" min="1" oninput="CalculateNOABilling(`<?= $remaining_balance ?>`)" required>\
                        </td>\
                        <td class="fw-bold" style="width:30%">\
                            <div class="input-group">\
                                <span class="input-group-text bg-success text-white">&#8369;</span>\
                                <input type="number" id="' + finalInId + '" class="inputCT ct_fee form-control" oninput="CalculateNOABilling(`<?= $remaining_balance ?>`)" required>\
                            </div>\
                        </td>\
                        <td>\
                            <button onclick="removeService(' + id + ')" class="btn btn-danger" data-bs-toggle="tooltip" title="Click to Remove"><i class="mdi mdi-close "></i></button>\
                        </td>\
                    </tr>');
                var stringId = "#btn" + id;
                var stringRemove = stringId.replace(/\s/g, '');
                $("#submit-id").attr("disabled", false)
                $(stringRemove).attr("disabled", true);
            },
            error: function(data) {
                console.log("correct")
                console.log(data);
            }
        });
    }

    function removeService(id) {
        var idRemove = "#tr" + id
        numberOfRow--;
        //var finalidRemove = idRemove.replace(/\s/g, '')
        var stringId = "#btn" + id;
        var stringRemove = stringId.replace(/\s/g, '');
        $(stringRemove).attr("disabled", false);
        $(idRemove).remove();
        if (numberOfRow == 0) {
            $("#submit-id").attr("disabled", true)
        }
        CalculateNOABilling();
    }


    $(function() {
        $("#addRow").click(function(x) {
            var row = $('<tr class="otherInput' + number + '">' +
                '<td>Other</td><td><input type="text"  required></td><td></td></tr>');
            $("#tbl-charges > tbody").append(row);
            number++;

            $(".inputCT").each(function() {
                var input = $(this); // This is the jquery object of the input, do what you will
                if (input.val()) {
                    chargesEquip.push({
                        id: input.attr('id'),
                        name: "Test",
                        cost: input.val()
                    })
                }
            });
            console.log(chargesEquip)
        });
    });

    $(function() {
        $("#deleteRow").click(function() {
            number--;
            $(".otherInput" + number).remove();
        });
    });


    function searchFunction() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
               }
            }
        }
    }     
          
    const CalculateNOABilling = (remaining_balance) => {
        let totalBill = 0;
        let total_deduction = 0;
        let net_bill = 0;
        let charge_amount = 0;
        let philhealth_deduction = 0;
        let sss_deduction = 0;
        let other_deduction = 0;
        validateNumberInputs();

        const cost_inputs = document.querySelectorAll('.ct_fee');
        const quantity_inputs = document.querySelectorAll('.ct_qty');
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
    let count = 0; // declaring the count variable outside the function will persist its value even after the function is called, allowing it to increment by one each time the function is called.

     // this is for Diagnostic Test LOA Requests
    const addOtherDeductionInputs = (remaining_balance) => {
        const container = document.querySelector('#dynamic-deduction');
        const deduction_count = document.querySelector('#deduction-count');
        count++;
        deduction_count.value = count;

        let html_code  = `<div class="row my-3 row-deduction pt-1" id="row${count}">`;

           /* Creating a new input field with the name deduction_name[] */
            html_code += `<div class="col-md-3">
                            <input type="text" name="deduction-name[]" class="form-control fw-bold ls-1" placeholder="Enter Deduction Name" required/>
                            <div class="invalid-feedback">
                                Deduction name and amount is required
                            </div>
                         </div>`;

            /* Creating a form input field with a name of deduction_amount[] and a class of
            deduction-amount. */
            html_code += `<div class="col-md-3">
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-success text-white">&#8369;</span>

                                    <input type="number" name="deduction-amount[]" class="deduction-amount form-control fw-bold ls-1" placeholder="Deduction Amount" oninput="CalculateNOABilling(${remaining_balance})" required/>

                                    <span class="other-deduction-msg text-danger fw-bold ms-3" style="font-size:12px"></span>
                                </div>
                          </div>`;

           /* Adding a remove button to the html code. */
            html_code += `<div class="col-md-3">
                            <button type="button" data-id="${count}" class="btn btn-danger btn-md btn-remove" onclick="removeRow(this, ${remaining_balance})" data-bs-toggle="tooltip" title="Click to remove Deduction">
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
    const removeRow = (remove_btn, remaining_balance) => {
        count--;
        const btn_id = remove_btn.getAttribute('data-id');
        const deduction_count = document.querySelector('#deduction-count');
        // update deduction count hidden input value for reference
        deduction_count.value = count;

        document.querySelector(`#row${btn_id}`).remove();
        CalculateNOABilling(remaining_balance);
    }

    const enableButtonandDeduction = (totalBill) =>{
        const input_philhealth = document.querySelector('#deduct-philhealth');
        const input_sss = document.querySelector('#deduct-sss');
        const deduct_btn = document.querySelector('#btn-other-deduction');

        if(totalBill > 0){
            input_philhealth.removeAttribute('readonly');
            input_sss.removeAttribute('readonly');
            deduct_btn.removeAttribute('disabled');
        }else{
            input_philhealth.setAttribute('readonly', true);
            input_sss.setAttribute('readonly', true);
            deduct_btn.setAttribute('disabled', true);
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


</script>