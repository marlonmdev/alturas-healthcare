
    <!-- Start of Page Wrapper -->
    <div class="page-wrapper">
      <!-- Bread crumb and right sidebar toggle -->
      <div class="page-breadcrumb">
        <div class="row">
          <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title ls-2">Print LOA</h4>
            <div class="ms-auto text-end">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item">Healthcare Coordinator</li>
                  <li class="breadcrumb-item active" aria-current="page">
                    Print LOA
                  </li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>
      <!-- End Bread crumb and right sidebar toggle -->
      <!-- Start of Container fluid  -->
      <div class="container-fluid">

        <div class="row mb-3">
          <div class="col-sm-12">
            <a class="btn btn-dark me-2" href="<?php echo base_url(); ?>company-doctor/loa/requests-list/approved">
              <i class="mdi mdi-arrow-left-bold"></i>
              Go Back
            </a>
            
            <button class="btn btn-info ls-1 me-2" onclick="saveAsImage()"><i class="mdi mdi-file-image"></i> Save as Image</button>

            <button class="btn btn-danger ls-1" onclick="printDiv('#printableDiv')"><i class="mdi mdi-printer"></i> Print LOA</button>
          </div>
        </div>

        <div class="card shadow px-3 py-3">
          <div class="row" id="printableDiv" style="background:#ffff;padding:20px 40px;">
            <div class=" col-xs-12">
              <div class="grid request">
                <div class="grid-body">
                  <div class="request-title">
                    <div class="row">
                      <div class="col-xs-12 d-flex justify-content-center align-items-center">
                        <img src="<?= base_url(); ?>assets/images/hmo-logo.png" alt="Alturas Healthcare Logo" height="100">
                        <span class="fw-bold fs-1 ls-1">Alturas Healthcare</span>
                      </div>
                    </div>
                  </div>
                  <div class="row">

                    <table>
                      <tr>
                        <td class="ls-1" style="padding-left:20px;">
                          <span class="fw-bold fs-3 ls-1">LETTER OF AUTHORIZATION</span><br>
                          <?php
                            $valid_until = $row['expiration_date'] ? date('F d, Y', strtotime($row['expiration_date'])) : '';
                          ?>
                          LOA Number : <strong><?= $row['loa_no'] ?></strong><br>
                          Approved On : <strong><?= date('F d, Y', strtotime($row['approved_on'])) ?></strong><br>
                          Healthcare Provider: <strong><?= $row['hp_name'] ?></strong>
                        </td>
                        <td style="display:flex;justify-content:end;margin-right:20px;">
                          <div id="qrcode"></div>
                        </td>
                      </tr>

                      <tr>
                        <td class="ls-1" style="padding:20px 20px 0 20px;text-align:justify" colspan="2">
                          We wish to authorize the following health care services for the account of Alturas Healthcare for our member, <strong><?= $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] ?></strong> with Healthcard No. <strong><?= $row['health_card_no'] ?></strong>. This authorization letter is valid until <strong><?= $valid_until ?></strong> only.
                        </td>
                      </tr>

                      <tr>
                        <td colspan="2" style="padding: 0 20px 0 20px;">
                          <div class="my-3" style="border:0.5px solid #585858;"></div>
                        </td>
                      </tr>


                      <tr>
                        <td class="ls-1" style="padding:0 0 0 20px;text-align:justify;vertical-align:baseline;" colspan="2">
                          <?php
                          echo '<h5>' . strtoupper($row['loa_request_type']) . '</h5>';
                          $selected_cost_types = explode(';', $row['med_services']);
                          $ct_array = array();
                          foreach ($cost_types as $cost_type) :
                            if (in_array($cost_type['ctype_id'], $selected_cost_types)) {
                              array_push($ct_array, $cost_type['item_description']);
                            }
                          endforeach;
                          $med_services = implode(', ', $ct_array);
                          if ($row['loa_request_type'] !== 'Consultation') {
                            echo '<i class="mdi mdi-chevron-right fs-4"></i>' . $med_services;
                          }
                          ?>
                        </td>
                      </tr>

                      <tr>
                        <td class="ls-1" style="padding:10px 0 0 20px;text-align:justify;vertical-align:baseline;" colspan="2">
                          <h5 class="ls-1">CHIEF COMPLAINT/DIAGNOSIS</h5>
                          <i class="mdi mdi-chevron-right fs-4"></i><?= $row['chief_complaint'] ?>
                        </td>
                      </tr>

                      <tr>
                        <td colspan="2" style="padding: 0 20px 0 20px;">
                          <div class="my-2" style="border:0.5px solid #585858;"></div>
                        </td>
                      </tr>

                      <tr>
                        <td class="ls-1" style="padding: 0 0 0 20px;text-align:justify;vertical-align:baseline;">
                          <i class="mdi mdi-information fs-4"></i> <strong>PATIENT DETAILS</strong><br>
                          Name: <?= $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] ?><br>
                          <!-- Start of Age Calculator -->
                          <?php
                          $birthDate = date("d-m-Y", strtotime($row['date_of_birth']));
                          $currentDate = date("d-m-Y");
                          $diff = date_diff(date_create($birthDate), date_create($currentDate));
                          $age = $diff->format("%y");
                          ?>
                          <!-- End of Age Calculator -->
                          Age: <?= $age ?> years old<br>
                          Date of Birth: <?= date("F d, Y", strtotime($row['date_of_birth'])) ?><br>
                          Home Address: <?= $row['home_address'] ?><br>
                          City Address: <?= $row['city_address'] ?><br>
                          Contact No.: <?= $row['contact_no'] ?><br>
                          Philhealth No.: <?= $row['philhealth_no'] ?>
                        </td>

                        <td class="ls-1" style="padding: 0 20px 0 0;vertical-align:baseline;">
                          <i class="mdi mdi-information fs-4"></i> <strong>CONTACT PERSON DETAILS</strong><br>
                          Name: <?= $row['contact_person'] ?><br>
                          Address: <?= $row['contact_person_addr'] ?><br>
                          Contact No.: <?= $row['contact_person_no'] ?>
                          <br><br>
                            <i class="mdi mdi-information fs-4"></i> <strong>PATIENT MBL DETAILS</strong><br>
                            Maximum Benefit Limit: <?= '&#8369;' . number_format($mbl['max_benefit_limit']) ?><br>
                            Used MBL: <?= '&#8369;' . number_format($mbl['used_mbl']) ?><br>
                            Remaining MBL: <?=  '&#8369;' . number_format($mbl['remaining_balance']) ?>
                        </td>
                      </tr>

                      <tr>
                        <td colspan="2" style="padding: 0 20px 0 20px;">
                          <div class="my-3" style="border:0.5px solid #585858;"></div>
                        </td>
                      </tr>

                      <tr>
                        <td class="ls-1" style="padding:0 0 0 20px;vertical-align:baseline;">
                          Requesting Physician: <br>
                          <i class="mdi mdi-chevron-right fs-4"></i><span class="fw-bold"><?= $req['doctor_name'] ?></span>
                        </td>

                        <td class="ls-1" style="padding:0 20px 0 0;text-align:justify;">
                          Attending Physician: <br>
                          <i class="mdi mdi-chevron-right fs-4"></i><span class="fw-bold"><?= $row['attending_physician'] ?></span>
                        </td>
                      </tr>

                      <tr>
                        <td style="padding: 0 0 0 10px;">
                          <svg id="barcode"></svg>
                        </td>

                        <td class="ls-1" style="padding: 0 20px 0 0;">
                          Approved By : <span class="fw-bold"><?= $doc['doctor_name'] ?></span>
                          <img src="<?= base_url() . "uploads/doctor_signatures/" . $doc['doctor_signature'] ?>" alt="Doctor's Signature" style="height:auto;width:170px;vertical-align:baseline;margin-left:-170px">
                          <br><small style="margin-left:105px;text-align:center;">Company Physician</small>
                        </td>
                      </tr>
                    </table>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <!-- End of Row -->
    </div>
    <!-- End Container fluid  -->
  </div>
<!-- End Page wrapper  -->
</div>

<script>
  const baseUrl = `<?php echo base_url(); ?>`;
  const fileName = `<?php echo strtotime(date('Y-m-d h:i:s')); ?>`;

  window.onload = function generateCodes() {
    $('#qrcode').html('');
    $('#barcode').html('');

    const healthcard_no = `<?php echo $row['health_card_no']; ?>`;

    new QRCode(document.getElementById("qrcode"), {
      text: healthcard_no,
      width: 100,
      height: 100,
    });
    
    JsBarcode("#barcode", healthcard_no, {
      displayValue: false,
      width: 1.5,
      height: 70,
    });
  }

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
        link.download = `loa_${fileName}.png`;
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