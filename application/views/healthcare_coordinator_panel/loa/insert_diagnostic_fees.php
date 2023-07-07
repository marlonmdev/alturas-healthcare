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
              <li class="breadcrumb-item active" aria-current="page">Diagnostic Detailed SOA</li>
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

      <div class="row">
        <div class="col-lg-3"><button type="button" class="btn btn-info" id="patient_information"><i class="mdi mdi-eye" style="color:#80ff00"></i> Patient Information</button></div>
        <div class="col-lg-3 offset-6"><button type="button" class="btn btn-info" id="summary" ><i class="mdi mdi-file-pdf text-danger"></i> Summary of SOA (Pdf)</button></div>
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
  <!-- <?php include 'view_pdf_bill_modal.php'; ?> -->
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

<!-- PDF SOA -->
<div class="modal fade" id="viewPDFBillModal" tabindex="-1" data-bs-backdrop="static" style="height:100%">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#00538c">
        <span class="fw-bold fs-4" style="color:#fff"><i class="mdi mdi-file-pdf text-danger"></i> Summary of Statement of Account</span>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="col-lg-6" style="width:100%;height:100%;">
          <iframe id="pdf-viewer" style="width:100%;height:580px;"></iframe><br>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End -->




<script>
  const form = document.querySelector('#performedLoaInfo');
  const baseurl = `<?php echo base_url();?>`;
  const pdff = `<?php echo $pdf_bill ?>`;
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
      
    $("#summary").click(function() {
      viewPDFBill();
    });
  });

  const viewPDFBill = () => {
    $('#viewPDFBillModal').modal('show');
    let pdfFile = `${baseurl}uploads/pdf_bills/${pdff}`;
    let fileExists = checkFileExists(pdfFile);
    // console.log("pdfFilename",pdfFile);
    if(fileExists){
      let xhr = new XMLHttpRequest();
      xhr.open('GET', pdfFile, true);
      xhr.responseType = 'blob';

      xhr.onload = function(e) {
        if (this.status == 200) {
          let blob = this.response;
          let reader = new FileReader();

          reader.onload = function(event) {
            let dataURL = event.target.result;
            let iframe = document.querySelector('#pdf-viewer');
            iframe.src = dataURL;
          };
          reader.readAsDataURL(blob);
        }
      };
      xhr.send();
    }
  }

  const checkFileExists = (fileUrl) => {
    let xhr = new XMLHttpRequest();
    xhr.open('HEAD', fileUrl, false);
    xhr.send();

    return xhr.status == "200" ? true: false;
  }


  function PatientInfo($billing_id) {
    $("#patientinformation").modal("show");
    $('#billing-id').val(billing_id);
  }


</script>

