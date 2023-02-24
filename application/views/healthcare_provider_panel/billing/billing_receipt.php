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

          <!-- Go Back to Previous Page -->
          <div class="col-12 mb-4 mt-0">
              <form method="POST" action="<?php echo base_url(); ?>healthcare-provider/billing/search-by-healthcard" id="search-form-1" class="needs-validation" novalidate>
                  <div class="input-group">
                      <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
                      <input type="hidden" name="healthcard_no" value="<?= $bill['health_card_no'] ?>">
                      <button type="submit" class="btn btn-outline-dark" data-bs-toggle="tooltip" title="Click to Go Back">
                          <strong class="ls-2" style="vertical-align:middle">
                              <i class="mdi mdi-arrow-left-bold"></i> Go Back
                          </strong>
                      </button>
                  </div>
              </form>
          </div>

          <div class="col-md-12">
            <div class="card shadow">
              <div class="card-body">
                <div class="mx-3 mt-3" style="background:#F8F8F8;border:2px dashed #495579;padding:20px;">
                  <div class="container" id="printableDiv" style="padding:40px 30px;">
                    <div class="row text-center">
                      <h3 class="ls-1"> BILLING # <i class="mdi mdi-arrow-right-bold"></i> <?= $bill['billing_no'] ?></h3>
                    </div>
                    <div class="row mt-3">
                      <div class="col-6">
                        <ul class="list-unstyled">
                          <li class="text-secondary">
                            <span class="ls-1 border-secondary border-2 border-bottom pb-1">
                              Issued To: <?= $bill['first_name'].' '.$bill['middle_name'].' '.$bill['last_name'].' '.$bill['suffix'] ?>
                            </span>
                          </li>

                          <li class="text-secondary mt-2">
                            <span class="ls-1 border-secondary border-2 border-bottom pb-1">
                              Issued By: <?= $bill['billed_by'] ?>
                            </span>
                          </li>
                        </ul>
                      </div>

                      <div class="col-6">
                        <ul class="list-unstyled">
                              <li class="text-secondary">
                            <span class="ls-1 border-secondary border-2 border-bottom pb-1">
                              Issued On: <?= date('m/d/Y', strtotime($bill['billed_on'])) ?>
                            </span>
                          </li>

                          <li class="text-secondary mt-2"> 
                            <span class="ls-1 border-secondary border-2 border-bottom pt-1 pb-1">
                              Healthcare Provider: <?= $bill['hp_name'] ?>
                            </span>
                          </li>
                        </ul>
                      </div>
                    </div>

                    <div class="row my-2 mx-1 justify-content-center">
                      <!-- Start of Medical Services Table -->
                      <?php 
                        if(!empty($services)): 
                      ?>
                        <h4 class="text-center ls-1">MEDICAL SERVICE/S</h4>
                        <table class="table">
                          <thead>
                            <tr class="border-secondary border-2 border-0 border-top border-bottom">
                              <th class="text-center fw-bold ls-2">Quantity</th>
                              <th class="text-center fw-bold ls-2">Service</th>
                              <th class="text-center fw-bold ls-2">Fee</th>
                              <th class="text-center fw-bold ls-2">Amount</th>
                            </tr>
                          </thead>

                          <tbody>
                            <?php foreach($services as $service): ?>
                              <tr>
                                <td class="text-center ls-1"><?= $service['service_quantity'] ?></td>
                                <td class="text-center ls-1"><?= $service['service_name'] ?></td>
                                <td class="text-center ls-1">
                                  &#8369;<?= number_format($service['service_fee'], 2) ?>
                                </td>
                                <td class="text-center ls-1">
                                  &#8369;<?= number_format($service['service_quantity'] * $service['service_fee'], 2) ?>
                                </td>
                              </tr>
                            <?php endforeach; ?>
                              <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-center">
                                  <span class="text-secondary fs-5 fw-bold ls-1 me-2">Total:</span>
                                  <span class="text-secondary fw-bold fs-5 ls-1">
                                    <?= '&#8369;'.number_format($bill['total_bill'], 2) ?>
                                  </span>
                                </td>
                              </tr>
                          </tbody>
                        </table>
                      <?php 
                        endif; 
                      ?>
                      <!-- End of Medical Services Table -->
                      <div class="mt-3"></div> 
                      <!-- Start of Billing Deductions Table -->
                      <?php 
                        if(!empty($deductions)): 
                      ?>
                        <h4 class="text-center ls-1">BILLING DEDUCTION/S</h4>
                        <table class="table">
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
                                <span class="text-secondary fs-5 fw-bold ls-1 me-2">Total:</span>
                                <span class="text-secondary fw-bold fs-5 ls-1">
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
                      <div class="mt-3"></div>
                       <!-- Start of member MBL Table -->
                      <table>
                        <tr class="">
                          <td class="text-center">
                            <span class="text-secondary me-2">Patient's Credit Limit:</span>
                            <span class="text-secondary fw-bold fs-4 ls-1">
                              &#8369;<?= number_format($mbl['max_benefit_limit'], 2) ?>
                            </span>
                          </td>

                          <td class="text-center">
                            <span class="text-secondary me-2">Patient's Remaining Balance:</span>
                            <span class="text-secondary fw-bold fs-4 ls-1">
                              &#8369;<?= number_format($bill['before_remaining_bal'], 2) ?>
                            </span>
                          </td>
                        </tr>
                      </table>
                      <!-- End of member MBL Table -->
                      <div class="mt-3"></div>
                      <!-- Start of Billing Summary Table -->
                      <table class="table table-bordered">
                        <tr class="border-2 border-secondary">
                          <td>
                            <span class="text-secondary me-2">Total Bill:</span>
                            <span class="text-danger fw-bold fs-4 ls-1">
                              <?= '&#8369;'.number_format($bill['total_bill'], 2) ?>
                            </span>
                          </td>

                          <td>
                            <span class="text-secondary me-2">Total Deduction:</span>
                            <span class="text-danger fw-bold fs-4 ls-1">
                              <?= '&#8369;'.number_format($bill['total_deduction'], 2) ?>
                            </span>
                          </td>

                          <td>
                            <span class="text-secondary me-2">Net Bill:</span>
                            <span class="text-info fw-bold fs-4 ls-1">
                              <?= '&#8369;'.number_format($bill['net_bill'], 2) ?>
                            </span>
                          </td>

                          <td>
                            <span class="text-secondary me-2">Personal Charge:</span>
                            <span class="text-info fw-bold fs-4 ls-1">
                              <?= '&#8369;'.number_format($bill['personal_charge'], 2) ?>
                            </span>
                          </td>
                        </tr>
                      </table>
                      <!-- End of Billing Summary Table -->                  
                    </div>
                  </div>
                </div>

                <div class="container mt-4 mb-4">
                  <div class="row">
                    <div class="col-12 d-flex justify-content-end align-items-end">
                      <button class="btn btn-outline-info btn-lg ls-1 me-3" onclick="saveAsImage()"><i class="mdi mdi-file-image"></i> Save as Image</button>
                      <button class="btn btn-outline-danger btn-lg ls-1" onclick="printDiv('#printableDiv')"><i class="mdi mdi-printer"></i> Print Receipt</button>
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