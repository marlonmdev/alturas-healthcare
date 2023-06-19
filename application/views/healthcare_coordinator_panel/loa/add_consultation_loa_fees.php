<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <a href="#" onclick="goBack()" type="submit" class="btn btn-outline-dark" data-bs-toggle="tooltip" title="Click to Go Back">
          <strong class="ls-2" style="vertical-align:middle"><i class="mdi mdi-arrow-left-bold"></i> Back</strong>
        </a>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">Consultation Fee</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <hr style="color:red">
      <div class="col-12">
        <div class="text-center mb-4 mt-0"><h4 class="page-title ls-2" style="letter-spacing:10px">ADD SERVICE FEE</h4></div>
      </div>
    <hr style="color:red">
                
    <form id="performedLoaInfo" method="post" action="<?php echo base_url();?>healthcare-coordinator/loa/performed-loa-info/submit" class="needs-validation" novalidate>
      <div class="row">
        <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash() ?>">
        <div class="col-lg-4">
          <label class="fw-bold">Member's Name : </label>
          <input class="form-control fw-bold text-info" name="member-name" value="<?php echo $full_name ?>" readonly>
          <input type="hidden" name="emp-id" value="<?php echo $emp_id ?>">
        </div>

        <div class="col-lg-4">
          <label class="fw-bold">Healthcard Number : </label>
          <input class="form-control fw-bold text-info" name="healthcard-no" value="<?php echo $health_card_no ?>" readonly>
        </div>

        <div class="col-lg-4">
          <label class="fw-bold">LOA Number : </label>
          <input class="form-control fw-bold text-info" name="loa-no" value="<?php echo $loa_no ?>" readonly>
          <input type="hidden" name="loa-id" value="<?php echo $loa_id ?>">
        </div>

        <div class="col-lg-4 pt-3">
          <label class="fw-bold">Healthcare Provider : </label>
          <input class="form-control fw-bold text-info" name="hc-provider" value="<?php echo $hc_provider ?>" readonly>
          <input type="hidden" name="hp-id" value="<?php echo $hp_id ?>">
        </div>

        <div class="col-lg-4 pt-3">
          <label class="fw-bold">Work-Related : </label>
          <input class="form-control fw-bold text-info" name="work-related" value="<?php echo $work_related ?>" readonly>
        </div>        
      </div><hr>

      <div class="row pb-2">
        <div class="col-lg-4 pt-3">
          <label class="fw-bold">LOA Request Type : </label>
          <input class="form-control fw-bold text-info" name="request-type" value="<?php echo $request_type ?>" readonly>
        </div>
        <div class="col-lg-2 pt-3">
          <label class="fw-bold">Quantity : </label>
          <input class="form-control fw-bold text-info" type="number" name="quantity" id="quantity" value="1" min="1" oninput="calculateDiagnosticTestBilling()">
        </div>
        <div class="col-lg-2 pt-3">
          <label class="fw-bold">Service Fee : </label>
          <input class="form-control fw-bold text-info" name="service-fee" id="service-fee" type="number" oninput="calculateDiagnosticTestBilling()" required>
        </div>
      </div><hr>

      <input type="hidden" name="deduction-count" id="deduction-count">
      <div class="row">
        <div class="col-3  pt-2">
          <h4 class="text-left text-danger">BILLING DEDUCTIONS</h4>
        </div>     
      </div>

      
      <div class="row pt-3">
        <div class="col-md-3">
          <label class="form-label ls-1">PhilHealth</label> <span class="text-muted">(optional)</span>
          <div class="input-group mb-3">
            <span class="input-group-text bg-success text-white">&#8369;</span>
            <input type="number" class="input-deduction form-control fw-bold ls-1 text-info" id="deduct-philhealth" name="philhealth-deduction" placeholder="Deduction Amount" oninput="calculateDiagnosticTestBilling(`<?php echo $remaining_balance ?>`)" min="0">
            <span class="text-danger fw-bold deduction-msg"></span>
          </div>
        </div>

        <div class="col-4 pt-4">
          <button type="button" class="btn btn-info" id="btn-other-deduction" onclick="addNewDeduction()"><i class="mdi mdi-plus-circle"></i> Add New</button>
        </div>    
      </div>
      <div id="dynamic-deduction"></div><hr>

      <div class="row">
        <div class="col-4">
          <label>Total Bill</label>
          <input class="form-control text-danger fw-bold" name="total-bill" id="total-bill" value="&#8369;"  readonly>
        </div>

        <div class="col-4">
          <label>Total Deduction</label>
          <input class="form-control text-danger fw-bold" name="total-deduction" id="total-deduction"  readonly>
        </div>

        <div class="col-4">
          <label>Net Bill</label>
          <input class="form-control text-danger fw-bold" name="net-bill" id="net-bill" value="&#8369;"  readonly>
        </div>
      </div>

      <div class="row pt-3">
        <div class="col-4">
          <label>Patient's MBL </label>
          <input class="form-control text-danger fw-bold" name="patient-mbl" id="patient-mbl" value="&#8369; <?php echo $max_benefit_limit ?>" readonly>
        </div>

        <div class="col-4">
          <label>Patient's Remaining MBL</label>
          <input class="form-control text-danger fw-bold" name="remaining-mbl" id="remaining-mbl" value="&#8369; <?php echo $remaining_balance ?>" readonly>
        </div>
      </div><hr>

      <div class="offset-10 pt-3">
        <button class="btn btn-success fw-bold fs-4" type="submit" name="submit-btn" id="submit-btn"><i class="mdi mdi-near-me"></i> Submit</button>
      </div>

    </form>
  </div>
</div>






<script>
    const form = document.querySelector('#performedLoaInfo');
    $(document).ready(function(){
        
        $('#performedLoaInfo').submit(function(event){
            event.preventDefault();

            if(!form.checkValidity()){
                form.classLIst.add('was-validated');
                return;
            }

            let url = $(this).attr('action');
            let data = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'json',
                success: function(response) {
                    const {
                        token,status,message
                    } = response;

                    switch(status){
                       
                        case 'failed':
                            swal({
                                title: 'Error',
                                text: message,
                                timer: 3000,
                                showConfirmButton: true,
                                type: 'error'
                            });
                        break;

                        case 'success':
                            swal({
                                title: 'Success',
                                text: message,
                                timer: 3000,
                                showConfirmButton: false,
                                type: 'success'
                            });
                            setTimeout(function () {
                                window.location.href = '<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/completed';
                            }, 2600);
                            
                        break;

                        case 'complete-success':
                            swal({
                                title: 'Success',
                                text: message,
                                timer: 3000,
                                showConfirmButton: false,
                                type: 'success'
                            });
                            $('#performedLoaConsultInfo')[0].reset();
                            setTimeout(function () {
                                window.location.href = '<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/completed';
                            }, 2600);
                            
                        break;
                    }
                }
            });
        });

        $(".input-date").flatpickr({
            enableTime: true,
            dateFormat: 'm-d-Y H:i',
        });

    });

    window.onload = function() {
        calculateDiagnosticTestBilling();
    }

    let count = 0;
    const addNewDeduction = () => {
        const container = document.querySelector('#dynamic-deduction');
        const deduction_count = document.querySelector('#deduction-count');
        count ++;
        deduction_count.value = count;

        let html_code  = `<div class="row row-deduction" id="row${count}">`;

        /* Creating a new input field with the name deduction_name[] */
        html_code += `<div class="col-md-3">
                        <input type="text" name="deduction-name[]" class="form-control fw-bold ls-1 text-info" placeholder="*Enter Deduction Name" required/>
                        <div class="invalid-feedback">
                            Deduction name and amount is required
                        </div>
                    </div>`;

        /* Creating a form input field with a name of deduction_amount[] and a class of
        deduction-amount. */
        html_code += `<div class="col-md-3">
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-success text-white">&#8369;</span>

                                <input type="number" name="deduction-amount[]" class="deduction-amount form-control fw-bold ls-1 text-info" placeholder="*Deduction Amount" oninput="calculateDiagnosticTestBilling()" required/>

                                <span class="other-deduction-msg text-danger fw-bold"></span>
                            </div>
                    </div>`;
        
        /* Adding a remove button to the html code. */
        html_code += `<div class="col-md-3">
                        <button type="button" data-id="${count}" class="btn btn-danger btn-md btn-remove" onclick="removeDeduction(this)" data-bs-toggle="tooltip" title="Click to remove Deduction">
                            <i class="mdi mdi-close"></i>
                        </button>
                    </div>`;

        html_code += `</div>`;

        $('#dynamic-deduction').append(html_code);
    }

    const calculateDiagnosticTestBilling = (remaining_balance) => {
        let total_deductions = 0;
        let net_bill_amount = 0;
        const deduct_philhealth = document.querySelector('#deduct-philhealth');
        const total_bill = document.querySelector('#total-bill');
        const net_bill = document.querySelector('#net-bill');
        const input_total_deduction = document.querySelector('#total-deduction');

        total_services = totalServices();
        total_bill.value = parseFloat(total_services).toFixed(2);
        
        philhealth = deduct_philhealth.value > 0 ? deduct_philhealth.value : 0 ;
        other_deduction = calculateOtherDeductions();

        total_deductions = parseFloat(philhealth) + parseFloat(other_deduction);
        net_bill_amount = parseFloat(total_services) - parseFloat(total_deductions);

        input_total_deduction.value = parseFloat(total_deductions).toFixed(2);
        net_bill.value = parseFloat(net_bill_amount).toFixed(2);
    }

    const removeDeduction = (remove_btn) => {
        count--;
        const btn_id = remove_btn.getAttribute('data-id');
        const deduction_count = document.querySelector('#deduction-count');
        // update deduction count hidden input value for reference
        deduction_count.value = count;

        document.querySelector(`#row${btn_id}`).remove();
        calculateDiagnosticTestBilling();
    }

    const totalServices = () => {
        let total_services = 0;
        const services_fee = document.querySelector('#service-fee');
        const quantity = document.querySelector('#quantity');

        total_services += services_fee.value * quantity.value;
   
        return total_services;
    }

    const calculateOtherDeductions = () => {
        let other_deduction = 0;
        const row_deduction = document.querySelectorAll('.row-deduction');
        const deduction_amount = document.querySelectorAll('.deduction-amount');

        for (let i = 0; i < row_deduction.length; i++) {
            other_deduction += deduction_amount[i].value * 1;
        }  

        return other_deduction;
    }

    const goBack = () => {
      window.history.back();
    }
</script>
