
    <!-- Start of Page Wrapper -->
    <div class="page-wrapper">
      <!-- Bread crumb and right sidebar toggle -->
      <div class="page-breadcrumb">
        <div class="row">
          <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title ls-2">Print NOA</h4>
            <div class="ms-auto text-end">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item">Healthcare Coordinator</li>
                  <li class="breadcrumb-item active" aria-current="page">
                    Print NOA
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
            <a class="btn btn-dark me-2" href="<?php echo base_url(); ?>healthcare-coordinator/noa/requests-list/approved">
              <i class="mdi mdi-arrow-left-bold"></i>
              Go Back
            </a>

            <button class="btn btn-info ls-1 me-2" onclick="saveAsImage()"><i class="mdi mdi-file-image"></i> Save as Image</button>

            <button class="btn btn-danger ls-1" onclick="printDiv('#printableDiv')"><i class="mdi mdi-printer"></i> Print NOA</button>
          </div>
        </div>

        <div class="card shadow py-3 px-3">
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
                          <span class="fw-bold fs-3 ls-1">NOTICE OF ADMISSION</span><br>
                          <?php
                            $expires = strtotime('+1 week', strtotime($row['approved_on']));
                            $valid_until = date('F d, Y', $expires);
                          ?>
                          NOA Number : <strong><?= $row['noa_no'] ?></strong><br>
                          Approved On : <strong><?= date('F d, Y', strtotime($row['approved_on'])) ?></strong><br>
                          Healthcare Provider: <strong><?= $row['hp_name'] ?></strong>
                        </td>

                        <td style="display:flex;justify-content:end;margin-right:20px;">
                          <div id="qrcode"></div>
                        </td>
                      </tr>

                      <tr>
                        <td class="ls-1" style="padding:40px 20px 0 20px;text-align:justify" colspan="2">
                          We wish to authorize this Notice of Admission for the account of Alturas Healthcare for our member, <strong><?= $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] ?></strong> with Healthcard No. <strong><?= $row['health_card_no'] ?></strong>. This notice is valid until <strong><?= $valid_until ?></strong> only.
                        </td>
                      </tr>

                      <tr>
                        <td colspan="2" style="padding: 0 20px 0 20px;">
                          <div class="my-3" style="border:0.5px solid #585858;"></div>
                        </td>
                      </tr>

                      <tr>
                        <td  class="ls-1"style="padding:0 20px 0 20px;text-align:center;" colspan="2">
                          <h5>CHIEF COMPLAINT/DIAGNOSIS</h5>
                          <span><?= $row['chief_complaint'] ?></span>
                        </td>
                      </tr>

                      <tr>
                        <td colspan="2" style="padding: 0 20px 0 20px;">
                          <div class="my-3" style="border:0.5px solid #585858;"></div>
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
                        <td style="padding: 0 0 0 10px;">
                          <svg id="barcode"></svg>
                        </td>
                        <td style="padding: 0 20px 0 0;">
                          Approved By : <strong> <?= $doc['doctor_name'] ?></strong>
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
        <!-- End Container fluid  -->
      </div>
    <!-- End Page wrapper  -->
    </div>
  <!-- End Wrapper -->
  </div>

  <script>
    const baseUrl = "<?php echo base_url(); ?>";
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
        height: 80,
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
          link.download = `noa_${fileName}.png`;
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