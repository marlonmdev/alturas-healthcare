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
              <li class="breadcrumb-item active" aria-current="page">Consultation Detailed SOA</li>
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
                
    <form id="performedLoaInfo" method="post" action="<?php echo base_url();?>healthcare-coordinator/loa/billed/submit_consultation" class="needs-validation" novalidate>
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

      <?php if (!empty($benefits)): ?>
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
      <?php endif; ?>

      <?php
        $benefits_total = 0; // Initialize the variable here
        foreach ($benefits as $data) {
          $benefits_total += floatval(str_replace(',', '', $data['benefits_amount']));
        }
      ?>

      <div class="row">
        <div class="col-4">
          <label>Hospital Charges :</label>
          <input class="form-control text-danger fw-bold" name="hospital_charge" value="₱<?php  echo number_format ($total,2); ?>"  readonly>
        </div>

        <div class="col-4">
          <label>Total Deduction :</label>
          <input class="form-control text-danger fw-bold" name="total_deduction" value="₱<?php  echo number_format ($benefits_total,2); ?>" readonly>
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
                window.location.href = '<?php echo base_url(); ?>healthcare-coordinator/bill/requests-list/billed';
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
                    <div class="invalid-feedback">Deduction name and amount is required</div>
                  </div>`;

    /* Creating a form input field with a name of deduction_amount[] and a class of deduction-amount. */
    html_code += `<div class="col-md-3">
                    <div class="input-group mb-3">
                      <span class="input-group-text bg-success text-white">&#8369;</span>
                      <input type="number" name="deduction-amount[]" class="deduction-amount form-control fw-bold ls-1 text-info" placeholder="*Deduction Amount" oninput="calculateDiagnosticTestBilling()" required/>
                      <span class="other-deduction-msg text-danger fw-bold"></span>
                    </div>
                  </div>`;
      
    /* Adding a remove button to the html code. */
    html_code += `<div class="col-md-3">
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
                    <input type="text" name="charge-name[]" class="form-control fw-bold ls-1 text-info" placeholder="*Enter Charge Name" required/>
                    <div class="invalid-feedback">Deduction name and amount is required</div>
                  </div>`;

    html_code += `<div class="col-md-4">
                    <div class="input-group mb-3">
                      <span class="input-group-text bg-success text-white">&#8369;</span>
                      <input type="number" name="charge-amount[]" class="charge-amount form-control fw-bold ls-1 text-info" placeholder="*Charge Amount" oninput="calculateDiagnosticTestBilling()" required/>
                      <span class="other-deduction-msg text-danger fw-bold"></span>
                    </div>
                  </div>`;
        
    html_code += `<div class="col-md-3">
                    <button type="button" data-id="${count}" class="btn btn-danger btn-md btn-remove text-info" onclick="removeDeduction(this)" data-bs-toggle="tooltip" title="Click to remove Deduction"><i class="mdi mdi-close"></i></button>
                  </div>`;

    html_code += `</div>`;

    $('#dynamic-fee').append(html_code);
  }

  // const calculateDiagnosticTestBilling = () => {
  //   let total_deductions = 0;
  //   let net_bill_amount = 0;
  //   const deduct_philhealth = document.querySelector('#deduct-philhealth');
  //   const total_bill = document.querySelector('#total-bill');
  //   const net_bill = document.querySelector('#net-bill');
  //   const input_total_deduction = document.querySelector('#total-deduction');

  //   total_services = parseFloat(totalServices()) || 0; // Ensure the value is a number
  //   let total_charge = parseFloat(calculateCharge()) || 0; 
  //   final_services = total_charge + total_services;
  //   total_bill.value = parseFloat(final_services).toFixed(2); // No need for parseFloat() since final_services is already a number

  //   philhealth = deduct_philhealth.value > 0 ? parseFloat(deduct_philhealth.value) : 0; // Ensure the value is a number
  //   other_deduction = calculateOtherDeductions();

  //   total_deductions = parseFloat(philhealth) + parseFloat(other_deduction);
  //   net_bill_amount = parseFloat(final_services) - parseFloat(total_deductions);

  //   input_total_deduction.value = total_deductions.toFixed(2); // No need for parseFloat() since total_deductions is already a number
  //   net_bill.value = net_bill_amount.toFixed(2); // No need for parseFloat() since net_bill_amount is already a number
  // }

  const calculateDiagnosticTestBilling = () => {
    let total_deductions = 0;
    let net_bill_amount = 0;
    const deduct_philhealth = document.querySelector('#deduct-philhealth');
    const total_bill = document.querySelector('#total-bill');
    const net_bill = document.querySelector('#net-bill');
    const input_total_deduction = document.querySelector('#total-deduction');

    total_services = parseFloat(totalServices()) || 0; // Ensure the value is a number
    let total_charge = parseFloat(calculateCharge()) || 0; 
    final_services = total_charge + total_services;
    total_bill.value = numberWithCommas(final_services.toFixed(2)); // Format the number with commas

    philhealth = deduct_philhealth.value.replace(/,/g, '') > 0 ? parseFloat(deduct_philhealth.value.replace(/,/g, '')) : 0; // Ensure the value is a number and remove commas
    other_deduction = calculateOtherDeductions();

    total_deductions = parseFloat(philhealth) + parseFloat(other_deduction);
    net_bill_amount = parseFloat(final_services) - parseFloat(total_deductions);

    input_total_deduction.value = numberWithCommas(total_deductions.toFixed(2)); // Format the number with commas
    net_bill.value = numberWithCommas(net_bill_amount.toFixed(2)); // Format the number with commas
  }

  function numberWithCommas(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
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
    total_services += services_fee.value;
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

  const calculateCharge = () => {
    let total_charge = 0;
    const row_charge = document.querySelectorAll('.row-deduction1');
    const charge_amount = document.querySelectorAll('.charge-amount');

    for (let i = 0; i < row_charge.length; i++) {
      total_charge += parseFloat(charge_amount[i].value) || 0; // Ensure the value is a number
    }
    return total_charge;
  };

</script>