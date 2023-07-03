<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <a href="<?php echo base_url(); ?>healthcare-coordinator/bill/requests-list/billed" type="submit" class="btn btn-outline-dark" data-bs-toggle="tooltip" title="Click to Go Back">
          <strong class="ls-2" style="vertical-align:middle"><i class="mdi mdi-arrow-left-bold"></i> Back</strong>
        </a>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">Diagnostic SOA</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="col-12  pt-2">
      <h5 style="text-align:center;color:black;font-size:15px;letter-spacing:4px;text-decoration:underline">DETAILED STATEMENT OF ACCOUNT</h5>
    </div>
         
    <form id="performedLoaInfo" method="post" class="needs-validation" novalidate>
      <hr>
      <div class="row">
        <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash() ?>">

        <div class="col-lg-4">
          <label class="fw-bold">Patient Name : </label>
          <input class="form-control" name="member-name" value="<?php echo $full_name ?>" readonly>
          <input type="hidden" name="emp-id" value="<?php echo $emp_id ?>">
        </div>

        <div class="col-lg-4">
          <label class="fw-bold">Patient Address : </label>
          <input class="form-control" name="hc-provider" value="<?php echo $home_address ?>" readonly>
        </div>

        <?php
          $date_of_birth = $loa['date_of_birth'];
          // Calculate the age based on the birthdate
          $birthdate = new DateTime($date_of_birth);
          $currentDate = new DateTime();
          $age = $birthdate->diff($currentDate)->y;


          echo '<div class="col-lg-4">
                  <label class="fw-bold">Age : </label>
                  <input class="form-control" name="member-name" value="'.$age.'" readonly>
                </div>';
        ?>

        <div class="col-lg-4">
          <label class="fw-bold">Healthcard Number : </label>
          <input class="form-control" name="healthcard-no" value="<?php echo $health_card_no ?>" readonly>
        </div>

        <div class="col-lg-4">
          <label class="fw-bold">LOA Number : </label>
          <input class="form-control" name="loa-no" value="<?php echo $loa_no ?>" readonly>
          <input type="hidden" name="loa-id" value="<?php echo $loa_id ?>">
        </div>

         <div class="col-lg-4">
          <label class="fw-bold">Work-Related : </label>
          <input class="form-control" name="work-related" value="<?php echo $work_related ?> (<?php echo $percentage ?>%)" readonly>
        </div> 

        <div class="col-lg-4">
          <label class="fw-bold">Healthcare Provider : </label>
          <input class="form-control" name="hc-provider" value="<?php echo $hc_provider ?>" readonly>
          <input type="hidden" name="hp-id" value="<?php echo $hp_id ?>">
        </div>

        <div class="col-lg-4">
          <label class="fw-bold">Type of Request : </label>
          <input class="form-control" name="request-type" value="<?php echo $request_type ?>" readonly>
        </div>

        <div class="col-lg-4">
          <label class="fw-bold">Billed Date : </label>
          <input class="form-control" name="healthcard-no" value="<?php echo date('m/d/Y', strtotime($loa['billed_on'])); ?>" readonly>
        </div>   
      </div>
      <hr>


      <div class="col-4">
        <button type="button" class="btn btn-info" id="patient_information">
          <i class="mdi mdi-eye"></i> Patient Information
        </button>
      </div>
     

      <table class="table table-bordered table-striped table-hover table-responsive table-sm">
        <thead style="background-color:#00538c;text-align:center">
          <tr>
            <th style="color:#fff">DATE</th>
            <th style="color:#fff">NAME</th>
            <th style="color:#fff">DESCRIPTION</th>
            <th style="color:#fff">QUANTITY</th>
            <th style="color:#fff">UNIT PRICE</th>
            <th style="color:#fff">AMOUNT</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $total = 0;
            $displayedLabels = array();

            foreach ($itemized_bill as $data) {
              $total += floatval(str_replace(',', '', $data['amount']));

              if (!in_array($data['labels'], $displayedLabels)) {
                $displayedLabels[] = $data['labels'];
              ?>
                <tr>
                  <td style="text-align:center"><?php echo $data['date'] ?></td>
                  <td><?php echo $data['labels'] ?></td>
                  <td><?php echo $data['discription'] ?></td>
                  <td style="text-align:center"><?php echo $data['qty'] ?></td>
                  <td style="text-align:center"><?php echo $data['unit_price'] ?></td>
                  <td style="text-align:center"><?php echo $data['amount'] ?></td>
                </tr>
              <?php } else { ?>
                <tr>
                  <td style="text-align:center"><?php echo $data['date'] ?></td>
                  <td></td>
                  <td><?php echo $data['discription'] ?></td>
                  <td style="text-align:center"><?php echo $data['qty'] ?></td>
                  <td style="text-align:center"><?php echo $data['unit_price'] ?></td>
                  <td style="text-align:center"><?php echo $data['amount'] ?></td>
                </tr>
          <?php }} ?>
        </tbody>
      </table>
      <hr>

      <table class="table table-bordered table-striped table-hover table-responsive table-sm">
        <thead style="background-color:#00538c;text-align:center">
          <tr>
            <th style="color:#fff">NAME OF BENEFITS</th>
            <th style="color:#fff">AMOUNT</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $benefits_total=0;
            foreach ($benefits as $data) {
              $benefits_total += floatval(str_replace(',', '', $data['benefits_amount']));
          ?>
            <tr>
              <td><?php echo $data['benefits_name'] ?></td>
              <td><?php echo $data['benefits_amount'] ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
      <hr>

      <div class="row">
        <div class="col-4">
          <label>Hospital Charges :</label>
          <input class="form-control text-danger fw-bold" name="hospital_charge" value="₱<?php  echo number_format ($total,2); ?>"  readonly>
        </div>

        <div class="col-4">
          <label>Total Deduction :</label>
          <input class="form-control text-danger fw-bold" name="less_benefits" value="₱<?php  echo number_format ($benefits_total,2); ?>" readonly>
        </div>

        <div class="col-4">
          <label>Net Bill</label>
          <input class="form-control text-danger fw-bold" name="net_bill" value="₱<?php  echo number_format ($total-$benefits_total,2); ?>"  readonly>
        </div>    
      </div>

      <div class="row pt-3 mb-3">
        <div class="col-4">
          <label>Patient's MBL </label>
          <input class="form-control text-danger fw-bold" name="patient-mbl" id="patient-mbl" value="&#8369; <?php echo $max_benefit_limit ?>" readonly>
        </div>

        <div class="col-4">
          <label>Patient's Remaining MBL</label>
          <input class="form-control text-danger fw-bold" name="remaining-mbl" id="remaining-mbl" value="&#8369; <?php echo $remaining_balance ?>" readonly>
        </div>
      </div><br><hr>

      <div class="offset-10 pt-3">
        <button class="btn btn-success fw-bold fs-4" type="submit" name="submit-btn" id="submit-btn"><i class="mdi mdi-near-me"></i> Submit</button>
      </div> 

    </form>
  </div>
</div>

<!-- Patient Information -->
<div class="modal fade pt-4" id="patientinformation" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#00538c">
        <h4 class="modal-title ls-2" style="color:#fff"><i class="mdi mdi-library-books" style="color:#80ff00"></i>PATIENT INFORMATION</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
        <input type="hidden" name="emp-id" id="emp-id">
        <input type="hidden" name="billing-id" id="billing-id">

        <div class="row"> 
          <div class="col-lg-6">
            <label class="fw-bold">Billed Date : </label>
            <input class="form-control" name="member-name" value="<?php echo date('m/d/Y', strtotime($billed_on)); ?>" readonly>
          </div>

          <div class="col-lg-6">
            <label class="fw-bold">Request Date : </label>
            <input class="form-control" name="member-name" value="<?php echo date('m/d/Y', strtotime($request_date)); ?>" readonly>
          </div>

          <div class="col-lg-6 pt-4">
            <label class="fw-bold">Patient Name : </label>
            <input class="form-control" name="member-name" value="<?php echo $full_name ?>" readonly>
          </div>

          <div class="col-lg-6 pt-4">
            <label class="fw-bold">Patient Address : </label>
            <input class="form-control" name="member-name" value="<?php echo $home_address ?>" readonly>
          </div>

          <div class="col-lg-6 pt-4">
            <label class="fw-bold">Birthdate : </label>
            <input class="form-control" name="member-name" value="<?php echo date('F d, Y', strtotime($date_of_birth)); ?>" readonly>
          </div>

          <?php
            $date_of_birth = $loa['date_of_birth'];
            // Calculate the age based on the birthdate
            $birthdate = new DateTime($date_of_birth);
            $currentDate = new DateTime();
            $age = $birthdate->diff($currentDate)->y;


            echo '<div class="col-lg-6 pt-4">
                    <label class="fw-bold">Age : </label>
                    <input class="form-control" name="member-name" value="'.$age.'" readonly>
                  </div>';
          ?>

          <div class="col-lg-6 pt-4">
            <label class="fw-bold">Philhealth # : </label>
            <input class="form-control" name="member-name" value="<?php echo $philhealth_no ?>" readonly>
          </div>

          <div class="col-lg-6 pt-4">
            <label class="fw-bold">Healthcard # : </label>
            <input class="form-control" name="member-name" value="<?php echo $health_card_no ?>" readonly>
          </div>

          <div class="col-lg-6 pt-4">
            <label class="fw-bold">Healthcare Provider : </label>
            <input class="form-control" name="member-name" value="<?php echo $hc_provider ?>" readonly>
          </div>

          <div class="col-lg-6 pt-4">
            <label class="fw-bold">Type of Request : </label>
            <input class="form-control" name="member-name" value="<?php echo $request_type ?>" readonly>
          </div>

          <div class="col-lg-12 pt-4">
            <label class="fw-bold">Chief Complaint : </label>
            <textarea class="form-control"><?php echo $chief_complaint ?></textarea>
          </div>

          <?php 
            $selectedOptions = explode(';', $loa['med_services']);
            foreach ($cost_types as $cost_type) :
              if (in_array($cost_type['ctype_id'], $selectedOptions)) :
          ?>

          <div class="row pb-4">
            <div class="col-lg-6">
              <input type="hidden" name="ctype-id[]" value="<?php echo $cost_type['ctype_id'] ?>">
              <label class="fw-bold pt-2">Medical Services : </label>
              <input class="form-control fw-bold text-info" name="med-services[]" value="<?php echo $cost_type['item_description'] ?>" readonly>
            </div>

            <div class="col-lg-3">
              <label class="fw-bold pt-2">Service Fee : </label>
              <input class="form-control fw-bold ct-fee text-info" name="service-fee[]" value="<?php echo $cost_type['op_price'] ?>" readonly>
            </div>

            <div class="col-lg-3">
              <label class="fw-bold pt-2">Quantity : </label>
              <input class="form-control fw-bold ct-qty text-info" type="number" name="quantity[]" value="1" min="1" oninput="calculateDiagnosticTestBilling(`<?php echo $remaining_balance ?>`)" readonly>
            </div>
          </div>
        <?php  endif;
          endforeach;
        ?>

        </div>
                        
          

          <div class="modal-footer">
            <div class="col-sm-12 mb-sm-0 d-flex justify-content-end">
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="mdi mdi-close-box"></i> CANCEL</button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
<!-- End -->



<script>
  const form = document.querySelector('#performedLoaInfo');
  $(document).ready(function(){

    $('#performedLoaInfo').submit(function(event){
      event.preventDefault();

      if(!form.checkValidity()){
        form.classLIst.add('was-validated');
        return;
      }

      let url = '<?php echo base_url();?>healthcare-coordinator/loa/billed/submit_diagnostic';
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
                window.location.href = '<?php echo base_url(); ?>healthcare-coordinator/bill/requests-list/billed';
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

    $("#patient_information").click(PatientInfo);
  });

  const enableInput = () => {
    const date = document.querySelector('#date');
    const physician = document.querySelector('#physician');
    const status = document.querySelector('#status');

    if(status.value == 'Performed') {
      date.removeAttribute('readonly');
      physician.removeAttribute('readonly');
    }else{
      date.setAttribute('readonly', true);
      physician.setAttribute('readonly', true);
    }
  }

  let count = 0;
  const addNewDeduction = () => {
    const container = document.querySelector('#dynamic-deduction');
    const deduction_count = document.querySelector('#deduction-count');
    count ++;
    deduction_count.value = count;
    let html_code  = `<div class="row row-deduction" id="row${count}">`;

    /* Creating a new input field with the name deduction_name[] */
    html_code += `<div class="col-md-4">
                    <input type="text" name="deduction-name[]" class="form-control fw-bold ls-1" placeholder="*Enter Deduction Name" required/>
                    <div class="invalid-feedback">Deduction name and amount is required</div>
                  </div>`;

    /* Creating a form input field with a name of deduction_amount[] and a class of deduction-amount. */
    html_code += `<div class="col-md-4">
                    <div class="input-group mb-3">
                      <span class="input-group-text bg-success text-white">&#8369;</span>
                      <input type="number" name="deduction-amount[]" class="deduction-amount form-control fw-bold ls-1" placeholder="*Deduction Amount" oninput="calculateDiagnosticTestBilling()" required/>
                      <span class="other-deduction-msg text-danger fw-bold"></span>
                    </div>
                  </div>`;
        
    /* Adding a remove button to the html code. */
    html_code += `<div class="col-md-4">
                    <button type="button" data-id="${count}" class="btn btn-danger btn-md btn-remove" onclick="removeDeduction(this)" data-bs-toggle="tooltip" title="Click to remove Deduction"><i class="mdi mdi-close"></i></button>
                  </div>`;

    html_code += `</div>`;

    $('#dynamic-deduction').append(html_code);
  }

  const addfee = () => {
    const container = document.querySelector('#dynamic-fee');
    const deduction_count = document.querySelector('#fee-count');
    count ++;
    deduction_count.value = count;
    let html_code  = `<div class="row row-deduction1" id="row${count}">`;

    html_code += `<div class="col-md-4">
                    <input type="text" name="charge-name[]" class="form-control fw-bold ls-1" placeholder="*Enter Charge Name" required/>
                    <div class="invalid-feedback">Deduction name and amount is required</div>
                  </div>`;

    html_code += `<div class="col-md-4">
                    <div class="input-group mb-3">
                      <span class="input-group-text bg-success text-white">&#8369;</span>
                      <input type="number" name="charge-amount[]" class="charge-amount form-control fw-bold ls-1" placeholder="*Charge Amount" oninput="calculateDiagnosticTestBilling()" required/>
                      <span class="other-deduction-msg text-danger fw-bold"></span>
                    </div>
                  </div>`;
        
    html_code += `<div class="col-md-3">
                    <button type="button" data-id="${count}" class="btn btn-danger btn-md btn-remove" onclick="removeDeduction(this)" data-bs-toggle="tooltip" title="Click to remove Deduction"><i class="mdi mdi-close"></i></button>
                  </div>`;

    html_code += `</div>`;

    $('#dynamic-fee').append(html_code);
  }

  window.onload = function() {
    calculateDiagnosticTestBilling();
  };

  const calculateDiagnosticTestBilling = (remaining_balance) => {
    let total_deductions = 0;
    let net_bill_amount = 0;
    let company_charge = 0;
    let personal_charge = 0;
    let final_services = 0;
    const deduct_philhealth = document.querySelector('#deduct-philhealth');
    const total_bill = document.querySelector('#total-bill');
    const net_bill = document.querySelector('#net-bill');
    const input_total_deduction = document.querySelector('#total-deduction');
    const medicines = document.querySelector('#medicines');

    total_services = totalServices();
    let other_deduction1 = calculatebenefits();
    let charge = totalcharge();
    final_services = charge+other_deduction1+total_services + parseFloat(medicines.value);
    total_bill.value = parseFloat(final_services).toFixed(2);
    
    let other_deduction = calculateOtherDeductions();
    let benefits_deduction = totalbenefits(); // Get the total benefits
    total_deductions =other_deduction + benefits_deduction; // Add benefits to the total deductions

    net_bill_amount = parseFloat(final_services) - parseFloat(total_deductions);

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
    const services_fee = document.querySelectorAll('.ct-fee');
    const quantity = document.querySelectorAll('.ct-qty');

    for(let i = 0; i < services_fee.length; i++) {
      total_services += services_fee[i].value * quantity[i].value;
    }
    return total_services;
  }


  const totalbenefits = () => {
    let total_benefits = 0;
    const benefits = document.querySelectorAll('.b_amount');

    for (let i = 0; i < benefits.length; i++) {
      total_benefits += parseFloat(benefits[i].value);
    }
    
    return total_benefits;
  }

  const totalcharge = () => {
    let total_charge = 0;
    const charge = document.querySelectorAll('.c_amount');

    for (let i = 0; i < charge.length; i++) {
      total_charge += parseFloat(charge[i].value);
    }
    
    return total_charge;
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

  const calculatebenefits = () => {
    let other_deduction1 = 0;
    const row_deduction = document.querySelectorAll('.row-deduction1');
    const deduction_amount = document.querySelectorAll('.charge-amount');

    for (let i = 0; i < row_deduction.length; i++) {
      other_deduction1 += deduction_amount[i].value * 1;
    }  
    return other_deduction1;
  }

  function PatientInfo($billing_id) {
    $("#patientinformation").modal("show");
    $('#billing-id').val(billing_id);
  }
</script>
