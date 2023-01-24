   
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
                  <li class="breadcrumb-item">Super Admin</li>
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
            <a class="btn btn-dark" href="<?php echo base_url(); ?>super-admin/loa/requests-list/approved">
              <i class="mdi mdi-arrow-left-bold"></i>
              Go Back
            </a>
            &nbsp;
            <a class="btn btn-danger" href="javascript:void(0)" onclick="printDiv('#printableDiv')">
              <i class="mdi mdi-printer"></i>
              Print LOA
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
                        <strong style="font-size:1.4rem;">LETTER OF AUTHORIZATION</strong><br>
                        <?php
                        $expires = strtotime('+2 weeks', strtotime($row['approved_on']));
                        $valid_until = date('F d, Y', $expires);
                        ?>
                        LOA Number : <strong><?= $row['loa_no'] ?></strong><br>
                        Approved On : <strong><?= date('F d, Y', strtotime($row['approved_on'])) ?></strong><br>
                        HealthCare Provider: <strong><?= $row['hp_name'] ?></strong>
                      </td>
                      <td style="display:flex;justify-content:end;margin-right:20px;">
                        <div id="qrcode"></div>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:40px 20px 0 20px;text-align:justify" colspan="2">
                        We wish to authorize the following health care services for the account of Alturas HealthCare for our member, <strong><?= $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] ?></strong> with Health Card No. <strong><?= $row['health_card_no'] ?></strong>. This authorization letter is valid until <strong><?= $valid_until ?></strong> only.
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2" style="padding: 0 20px 0 20px;">
                        <hr>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:0 0 0 20px;text-align:justify;vertical-align:baseline;">
                        <?php
                        echo '<h5>' . strtoupper($row['loa_request_type']) . '</h5>';
                        $selected_cost_types = explode(';', $row['med_services']);
                        $ct_array = array();
                        foreach ($cost_types as $cost_type) :
                          if (in_array($cost_type['ctype_id'], $selected_cost_types)) {
                            array_push($ct_array, $cost_type['cost_type']);
                          }
                        endforeach;
                        $med_services = implode(', ', $ct_array);
                        if ($row['loa_request_type'] !== 'Consultation') {
                          echo '<i class="bi bi-caret-right-fill"></i> ' . $med_services;
                        }
                        ?>
                      </td>
                      <td style="padding:0 20px 0 0;text-align:justify;vertical-align:baseline;">
                        <h5>CHIEF COMPLAINT/DIAGNOSIS</h5>
                        <i class="bi bi-caret-right-fill"></i> <?= $row['chief_complaint'] ?>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2" style="padding: 0 20px 0 20px;">
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
                      <td colspan="2" style="padding: 0 20px 0 20px;">
                        <hr>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:0 0 0 20px;vertical-align:baseline;">
                        Requesting Physician: <br>
                        <i class="bi bi-caret-right-fill"></i> <strong><?= $req['doctor_name'] ?></strong>
                      </td>
                      <td style="padding:0 20px 0 0;text-align:justify;">
                        Attending Physician: <br>
                        <i class="bi bi-caret-right-fill"></i> <strong><?= $row['attending_physician'] ?></strong>
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
  
      <!-- End of Row -->
      </div>
    <!-- End Container fluid  -->
    </div>
  <!-- End Page wrapper  -->
  </div>

  <script>
    const baseUrl = `<?php echo base_url(); ?>`;

    window.onload = function generateCodes() {
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

    function printDiv(layer) {
      $(layer).printThis({
        importCSS: true,
        copyTagClasses: true,
        copyTagStyles: true,
        removeInline: false,
        formValues: true,
      });
    }
  </script>