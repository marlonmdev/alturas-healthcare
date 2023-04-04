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
      <div class="row">
          <div class="col-md-12">
            <div class="card shadow">
              <div class="card-body">

                <div class="container mb-4">
                  <div class="row">
                    <div class="text-center">
                      <i class="mdi mdi-checkbox-marked-circle-outline text-success" style="font-size: 5rem;"></i>
                    </div>
                    <div class="text-center">
                      <h3 class="ls-2">Billed Successfully!</h3>
                    </div>
                  </div>
                </div>

                <div class="mt-1" style="background:#F8F8F8;border:2px dashed #495579;padding:20px;margin:0 30px;">
                  <div class="container" id="printableDiv" style="padding:10px 50px;">
                    <div class="row text-left">
                      <h5 class="ls-1"> BILLING #: <?= $bill['billing_no'] ?></h5>
                    </div>
                    <div class="row mt-1">
                      <div class="col-6">
                        <ul class="list-unstyled">
                          <li class="text-secondary">
                            <span class="ls-1 fs-6">
                              Issued To: <?= $bill['first_name'].' '.$bill['middle_name'].' '.$bill['last_name'].' '.$bill['suffix'] ?>
                            </span>
                          </li>

                          <li class="text-secondary">
                            <span class="ls-1 fs-6">
                              Issued By: <?= $bill['billed_by'] ?>
                            </span>
                          </li>
                        </ul>
                      </div>

                      <div class="col-6">
                        <ul class="list-unstyled">
                              <li class="text-secondary">
                            <span class="ls-1 fs-6">
                              Issued On: <?= date('m/d/Y', strtotime($bill['billed_on'])) ?>
                            </span>
                          </li>

                          <li class="text-secondary"> 
                            <span class="ls-1 fs-6">
                              Healthcare Provider: <?= $bill['hp_name'] ?>
                            </span>
                          </li>
                        </ul>
                      </div>
                    </div>

                    <div class="row mx-1 justify-content-center">
                      <!-- Start of Medical Services Table -->
                      <?php 
                        if(!empty($services)): 
                      ?>
                        <h5 class="text-center ls-1">MEDICAL SERVICE/S</h5>
                        <table class="table table-sm">
                          <thead>
                            <tr class="border-secondary border-2 border-0 border-top border-bottom">
                              <th class="text-center fw-bold ls-2">Service</th>
                              <th class="text-center fw-bold ls-2">Quantity</th>
                              <th class="text-center fw-bold ls-2">Fee</th>
                              <th class="text-center fw-bold ls-2">Amount</th>
                            </tr>
                          </thead>

                          <tbody>
                            <?php foreach($services as $service): ?>
                              <tr>
                                <td class="text-center ls-1"><?= $service['service_name'] ?></td>
                                <td class="text-center ls-1"><?= $service['service_quantity'] ?></td>
                                <td class="text-center ls-1">
                                  &#8369;<?= number_format($service['service_fee'], 2) ?>
                                </td>
                                <td class="text-center ls-1">
                                  &#8369;<?= number_format($service['service_quantity'] * $service['service_fee'], 2) ?>
                                </td>
                              </tr>
                            <?php endforeach; ?>
                            <?php if($bill['total_services'] > 0): ?>
                              <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-center">
                                  <span class="text-secondary fs-6 fw-bold ls-1 me-2">Total:</span>
                                  <span class="text-secondary fw-bold fs-6 ls-1">
                                    <?= '&#8369;'.number_format($bill['total_services'], 2) ?>
                                  </span>
                                </td>
                              </tr>
                            <?php endif; ?>
                          </tbody>
                        </table>
                      <?php 
                        endif; 
                      ?>
                      <!-- End of Medical Services Table -->
                      
                      <!-- Start of Medications Table -->
                      <?php 
                        if(!empty($medications)): 
                      ?>
                        <h5 class="text-center ls-1">MEDICAL SUPPLIES AND MEDICATION/S</h5>
                        <table class="table table-sm">
                          <thead>
                            <tr class="border-secondary border-2 border-0 border-top border-bottom">
                              <th class="text-center fw-bold ls-2">Name</th>
                              <th class="text-center fw-bold ls-2">Quantity</th>
                              <th class="text-center fw-bold ls-2">Fee</th>
                              <th class="text-center fw-bold ls-2">Amount</th>
                            </tr>
                          </thead>

                          <tbody>
                            <?php foreach($medications as $medication): ?>
                              <tr>
                                <td class="text-center ls-1"><?= $medication['med_name'] ?></td>
                                <td class="text-center ls-1"><?= $medication['med_qty'] ?></td>
                                <td class="text-center ls-1">
                                  &#8369;<?= number_format($medication['med_fee'], 2) ?>
                                </td>
                                <td class="text-center ls-1">
                                  &#8369;<?= number_format($medication['med_qty'] * $medication['med_fee'], 2) ?>
                                </td>
                              </tr>
                            <?php endforeach; ?>
                              <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-center">
                                  <span class="text-secondary fs-6 fw-bold ls-1 me-2">Total:</span>
                                  <span class="text-secondary fw-bold fs-6 ls-1">
                                    <?= '&#8369;'.number_format($bill['total_medications'], 2) ?>
                                  </span>
                                </td>
                              </tr>
                          </tbody>
                        </table>
                      <?php 
                        endif; 
                      ?>
                      <!-- End of Medications Table -->

                      <!-- Start of Professonal Fees Table -->
                      <?php 
                        if(!empty($profees)): 
                      ?>
                        <h5 class="text-center ls-1">PROFESSIONAL FEE/S</h5>
                        <table class="table table-sm">
                          <thead>
                            <tr class="border-secondary border-2 border-0 border-top border-bottom">
                              <th class="text-center fw-bold ls-2">Doctor Name</th>
                              <th class="text-center fw-bold ls-2">Professional Fee</th>
                            </tr>
                          </thead>

                          <tbody>
                            <?php foreach($profees as $profee): ?>
                              <tr>
                                <td class="text-center ls-1"><?= $profee['doctor_name'] ?></td>
                                <td class="text-center ls-1">&#8369;<?= number_format($profee['pro_fee'], 2) ?></td>
                              </tr>
                            <?php endforeach; ?>
                              <tr>
                                <td></td>
                                <td class="text-center">
                                  <span class="text-secondary fs-6 fw-bold ls-1 me-2">Total:</span>
                                  <span class="text-secondary fw-bold fs-6 ls-1">
                                    <?= '&#8369;'.number_format($bill['total_pro_fees'], 2) ?>
                                  </span>
                                </td>
                              </tr>
                          </tbody>
                        </table>
                      <?php 
                        endif; 
                      ?>
                      <!-- End of Professional Fees Table -->

                      <!-- Start of Professonal Fees Table -->
                      <?php 
                        if(!empty($roomboards)): 
                      ?>
                        <h5 class="text-center ls-1">ROOM AND BOARDS</h5>
                        <table class="table table-sm">
                          <thead>
                            <tr class="border-secondary border-2 border-0 border-top border-bottom">
                              <th class="text-center fw-bold ls-2">Room Type</th>
                              <th class="text-center fw-bold ls-2">Room Price</th>
                            </tr>
                          </thead>

                          <tbody>
                            <?php foreach($roomboards as $roomboard): ?>
                              <tr>
                                <td class="text-center ls-1"><?= $roomboard['room_type'] ?></td>
                                <td class="text-center ls-1">&#8369;<?= number_format($roomboard['room_price'], 2) ?></td>
                              </tr>
                            <?php endforeach; ?>
                              <tr>
                                <td></td>
                                <td class="text-center">
                                  <span class="text-secondary fs-6 fw-bold ls-1 me-2">Total:</span>
                                  <span class="text-secondary fw-bold fs-6 ls-1">
                                    <?= '&#8369;'.number_format($bill['total_room_board'], 2) ?>
                                  </span>
                                </td>
                              </tr>
                          </tbody>
                        </table>
                      <?php 
                        endif; 
                      ?>
                      <!-- End of Professional Fees Table -->

                      <!-- Start of Billing Deductions Table -->
                      <?php 
                        if(!empty($deductions)): 
                      ?>
                        <h5 class="text-center ls-1">BILLING DEDUCTION/S</h5>
                        <table class="table table-sm">
                          <thead>
                            <tr class="border-secondary border-2 border-0 border-top border-bottom">
                              <th class="text-center fw-bold ls-2">Deduction Name</th>
                              <th class="text-center fw-bold ls-2">Deduction Amount</th>
                            </tr>
                          </thead>

                          <tbody>
                            <?php foreach($deductions as $deduction): ?>
                              <tr>
                                <td class="text-center ls-1"><?= $deduction['deduction_name'] ?></td>
                                <td class="text-center ls-1">
                                  &#8369;<?= number_format($deduction['deduction_amount'], 2) ?>
                                </td>
                              </tr>
                            <?php endforeach; ?>
                            <tr>
                              <td></td>
                              <td class="text-center">
                                <span class="text-secondary fs-6 fw-bold ls-1 me-2">Total:</span>
                                <span class="text-secondary fw-bold fs-6 ls-1">
                                  <?= '&#8369;'.number_format($bill['total_deduction'], 2) ?>
                                </span>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      <?php 
                        endif; 
                      ?>
                      <!-- End of Billing Deductions Table -->

                      <!-- Start of member MBL Table -->
                      <table>
                        <tr>
                          <td class="text-center">
                            <span class="text-secondary">Patient's Max Benefit Limit:</span>
                            <span class="text-secondary fw-bold fs-4 ls-1">
                              &#8369;<?= number_format($mbl['max_benefit_limit'], 2) ?>
                            </span>
                          </td>

                          <td class="text-center">
                            <span class="text-secondary">Before Remaining Balance:</span>
                            <span class="text-secondary fw-bold fs-4 ls-1">
                              &#8369;<?= number_format($bill['before_remaining_bal'], 2) ?>
                            </span>
                          </td>

                          <td class="text-center">
                            <span class="text-secondary">After Remaining Balance:</span>
                            <span class="text-cyan fw-bold fs-4 ls-1">
                              &#8369;<?= number_format($bill['after_remaining_bal'], 2) ?>
                            </span>
                          </td>
                        </tr>
                      </table>
                      <!-- End of member MBL Table -->

                      <!-- Start of Billing Summary Table -->
                      <table class="table table-sm table-bordered mb-1">
                        <tr class="border-2 border-secondary">
                          <td class="text-center">
                            <span class="text-secondary me-2">Total Bill:</span>
                            <span class="text-info fw-bold fs-4 ls-1">
                              <?= '&#8369;'.number_format($bill['total_bill'], 2) ?>
                            </span>
                          </td>

                          <td class="text-center">
                            <span class="text-secondary me-2">Total Deduction:</span>
                            <span class="text-info fw-bold fs-4 ls-1">
                              <?= '&#8369;'.number_format($bill['total_deduction'], 2) ?>
                            </span>
                          </td>

                          <td class="text-center">
                            <span class="text-secondary me-2">Net Bill:</span>
                            <span class="text-info fw-bold fs-4 ls-1">
                              <?= '&#8369;'.number_format($bill['net_bill'], 2) ?>
                            </span>
                          </td>
                        </tr>
                      </table>

                      <table>
                        <tr>
                          <td class="text-center">
                            <span class="text-secondary me-2">Company Charge:</span>
                            <span class="text-danger fw-bold fs-4 ls-1">
                              <?= '&#8369;'.number_format($bill['company_charge'], 2) ?>
                            </span>
                          </td>

                          <td>
                            <span class="text-secondary me-2">Personal Charge:</span>
                            <span class="text-danger fw-bold fs-4 ls-1">
                              <?= '&#8369;'.number_format($bill['personal_charge'], 2) ?>
                            </span>
                          </td>
                        </tr>
                      </table>       

                    </div>
                  </div>
                </div>

                <div class="container mt-4 mb-4">
                  <div class="row">
                    <div class="col-12 d-flex justify-content-end align-items-end">
                      <button class="btn btn-outline-info btn-lg ls-1 me-3" onclick="saveAsImage()">
                        <i class="mdi mdi-file-image"></i> Save as Image
                      </button>
                      <button class="btn btn-outline-danger btn-lg ls-1 me-2" onclick="printDiv('#printableDiv')">
                        <i class="mdi mdi-printer"></i> Print Receipt
                      </button>
                    </div>
                  </div>
                </div>
  
              </div>
            </div>
          </div>
      </div>
    </div>
</div>
<script>
  const baseUrl = `<?php echo base_url(); ?>`;
  const fileName = `<?php echo strtotime(date('Y-m-d h:i:s')); ?>`;

  const saveAsImage = () => {
    // Get the div element you want to save as an image
    const element = document.querySelector("#printableDiv");
    // Use html2canvas to take a screenshot of the element
    html2canvas(element)
      .then(function(canvas) {
        // Convert the canvas to an image data URL
        const imgData = canvas.toDataURL("image/png");
        // Create a temporary link element to download the image
        const link = document.createElement("a");
        link.download = `receipt_${fileName}.png`;
        link.href = imgData;

        // Click the link to download the image
        link.click();
      });
  }
  
  const printDiv = (layer) => {
    $(layer).printThis({
      importCSS: true,
      copyTagClasses: true,
      copyTagStyles: true,
      removeInline: false,
    });
  }
</script>