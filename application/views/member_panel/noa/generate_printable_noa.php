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
                  <li class="breadcrumb-item">Member</li>
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
            <a class="btn btn-dark" href="<?php echo base_url(); ?>member/requested-noa/approved">
              <i class="mdi mdi-arrow-left-bold"></i>
              Go Back
            </a>
            &nbsp;
            <a class="btn btn-danger" href="javascript:void(0)" onclick="printDiv('#printableDiv')">
              <i class="mdi mdi-printer"></i>
              Print NOA
            </a>
          </div>
        </div>
        <div class="row" id="printableDiv" style="background:#ffff;border-radius:5px;margin:.1em;padding:1rem;">
          <div class=" col-xs-12">
            <div class="grid request">
              <div class="grid-body">
                <div class="request-title">
                  <div class="row">
                    <div class="col-xs-12 d-flex justify-content-center align-items-center">
                      <img src="<?= base_url(); ?>assets/images/hmo-logo.png" alt="Alturas Healthcare Logo" height="100">
                      <strong style="font-size:2rem;">Alturas Healthcare</strong>
                    </div>
                  </div>
                </div>
                <div class="row">

                  <table>
                    <tr>
                      <td style="padding-left:20px;">
                        <strong style="font-size:1.4rem;">NOTICE OF ADMISSION</strong><br>
                        <?php
                        $expires = strtotime('+2 weeks', strtotime($row['approved_on']));
                        $valid_until = date('F d, Y', $expires);
                        ?>
                        NOA Number : <strong><?= $row['noa_no'] ?></strong><br>
                        Approved On : <strong><?= date('F d, Y', strtotime($row['approved_on'])) ?></strong><br>
                        HealthCare Provider: <strong><?= $row['hp_name'] ?></strong>
                      </td>
                      <td style="display:flex;justify-content:end;margin-right:20px;">
                        <div id="qrcode"></div>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:40px 20px 0 20px;text-align:justify" colspan="2">
                        We wish to authorize this Notice of Admission for the account of Alturas HealthCare for our member, <strong><?= $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] ?></strong> with Health Card No. <strong><?= $row['health_card_no'] ?></strong>. This notice is valid until <strong><?= $valid_until ?></strong> only.
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:0 20px 0 20px;text-align:center;" colspan="2">
                        <hr>
                        <h5>CHIEF COMPLAINT/DIAGNOSIS</h5>
                        <span><?= $row['chief_complaint'] ?></span>
                        <hr>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding: 0 0 0 20px;text-align:justify">
                        <i class="bi bi-info-circle-fill"></i> <strong>Patient Details:</strong><br>
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
                      <td style="padding: 0 20px 0 0;vertical-align:baseline;">
                        <i class="bi bi-info-circle-fill"></i> <strong>Contact Person Details:</strong><br>
                        Name: <?= $row['contact_person'] ?><br>
                        Address: <?= $row['contact_person_addr'] ?><br>
                        Contact No.: <?= $row['contact_person_no'] ?>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2" style="padding:0 20px 0 20px;">
                        <hr>
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
      <!-- End Container fluid  -->
      </div>
    <!-- End Page wrapper  -->
    </div>
  <!-- End Wrapper -->
  </div>

  <script>
    const baseUrl = "<?php echo base_url(); ?>";

    onload = () => {
      $('#qrcode').html('');
      $('#barcode').html('');
      const emp_id = '<?= $row['emp_id'] ?>';

      new QRCode(document.getElementById("qrcode"), {
        text: emp_id,
        width: 130,
        height: 130,
      });

      JsBarcode("#barcode", emp_id, {
        displayValue: false,
        width: 2,
        height: 80,
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