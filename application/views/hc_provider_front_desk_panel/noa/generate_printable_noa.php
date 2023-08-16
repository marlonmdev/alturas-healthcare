
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
                  <li class="breadcrumb-item">Hc Provider Front Desk</li>
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
            <a class="btn btn-dark me-2" href="<?php echo base_url(); ?>hc-provider-front-desk/noa-requests/approved">
              <i class="mdi mdi-arrow-left-bold"></i>
              Go Back
            </a>
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
                        <img src="<?= base_url(); ?>assets/images/hmo-logo.png" alt="Alturas Healthcare Logo" height="85">
                        <span class="fw-bold fs-2 ls-1">Alturas Healthcare</span>
                      </div>
                    </div>
                  </div>
                  <div class="row">

                    <table>
                      <tr>
                        <td class="ls-1  fs-6" style="padding-left:20px;">
                          <span class="fw-bold fs-4 ls-1">NOTICE OF ADMISSION</span><br>
                          <?php
                            $valid_until = $row['expiration_date'] ? date('F d, Y', strtotime($row['expiration_date'])) : '';
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
                        <td class="ls-1 fs-6" style="padding:20px 20px 0 20px;text-align:justify" colspan="2">
                          We wish to authorize this Notice of Admission for the account of Alturas Healthcare for our member, <strong><?= $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] ?></strong> with Healthcard No. <strong><?= $row['health_card_no'] ?></strong>. This notice is valid until <strong><?= $valid_until ?></strong> only.
                        </td>
                      </tr>

                      <tr>
                        <td colspan="2" style="padding: 0 20px 0 20px;">
                          <div class="my-2" style="border:0.5px solid #585858;"></div>
                        </td>
                      </tr>

                      <tr>
                        <td  class="ls-1 fs-6 lh-1 col-6"style="padding:0 10px 0 20px;text-align:justify;">
                          <h6 class="lh-1">CHIEF COMPLAINT</h6>
                          <span><i class="mdi mdi-chevron-right fs-4"></i> <?= $row['chief_complaint'] ?></span>
                        </td>
                        <td  class="ls-1 fs-6 lh-1 col-6"style="padding:0 10px 0 20px;text-align:justify;">
                          <h6 class="lh-1">ROOM TYPE</h6>
                          <span><i class="mdi mdi-chevron-right fs-4"></i> <?= $room_type ?></span>
                        </td>
                      </tr>

                      <tr>
                        <td colspan="2" style="padding: 0 20px 0 20px;">
                          <div class="my-1" style="border:0.5px solid #585858;"></div>
                        </td>
                      </tr>

                      <tr>
                        <td class="ls-1 fs-6" style="padding: 0 0 0 20px;text-align:justify;vertical-align:baseline;">
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
                          Philhealth No.: <?= $row['philhealth_no'] ?><br>
                          Employee Physical ID No.: <?= $row['emp_no'] ?>
                        </td>

                        <td class="ls-1  fs-6" style="padding: 0 20px 0 0;vertical-align:baseline;">
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
                          <div class="my-2" style="border:0.5px solid #585858;"></div>
                        </td>
                      </tr>

                      <tr>
                        <td style="padding: 0 0 0 10px;">
                          <svg id="barcode"></svg>
                        </td>
                        <td class=" fs-6" style="padding: 0 20px 0 0;">
                          Approved By : <strong> <?= $doc['doctor_name'] ?></strong>
                          <img src="<?= base_url() . "uploads/doctor_signatures/" . $doc['doctor_signature'] ?>" alt="Doctor's Signature" style="height:auto;width:170px;vertical-align:baseline;margin-left:-170px">
                          <br><span style="margin-left:105px;text-align:center;">Company Physician</span>
                        </td>
                      </tr>

                      <tr>
                        <td colspan="2" style="padding: 0 10px 0 10px;">
                          <div class="my-2" style="border:0.2px solid #a3a2a2;"></div>
                        </td>
                      </tr>
                    </table>
                    <small class="ls-1 lh-1" style="padding:0 0 0 20px;vertical-align:baseline;color:#b3b4b5">
                        <div class="col-12 pe-3" style="display:flex;justify-content:center;margin-right:20px;">
                          <table class="text-center table table-bordered" style="color:#b3b4b5">
                              <th class="fw-bold py-1">Level</th>
                              <th class="fw-bold py-1"> Maximum Benefit Limit</th>
                              <th class="fw-bold py-1">Room and Board (Confinement)</th>
                              <tbody>
                                <tr>
                                  <td class="py-1">I-VI</td>
                                  <td class="py-1">30,000</td>
                                  <td class="py-1">Payward</td>
                                </tr>
                                <tr>
                                  <td class="py-1">VII-IX</td>
                                  <td class="py-1">50,000</td>
                                  <td class="py-1">Semi-private</td>
                                </tr>
                                <tr>
                                  <td class="py-1">X and Above</td>
                                  <td class="py-1">100,000</td>
                                  <td class="py-1">Regular Private </td>
                                </tr>
                              </tbody>
                          </table>
                        </div>
                        1.	An approved Notice of Admission (NOA) is required for in-patient members to avail the Alturas Healthcare Program.<br>
                        2.	Members can only request up to their MBL limit, and requests exceeding it will not be approved. However, work-related cases with 100% relevance will be granted.<br>
                        3.	The deduction from the Maximum Benefit Limit (MBL) follows a general rule where 'work-related' percentage is deducted first, followed by the 'not-work related' percentage.<br>
                        4.	Final billing will be integrated with Philhealth, SSS, EC claims, and other government benefits before applying the MBL.<br>
                        5.	Non-work-related expenses may be eligible for Healthcare Advances upon approval from the supervisor and incorporator.<br>
                        6.	Healthcare Advance requests have a validity of 7 days, and if not approved within that timeframe, they will be automatically disapproved.<br>
                       <span class="fw-bold"> Voluntary Room Upgrading: </span> Members occupying a room one category higher than their entitlement will be charged for the room, boarding, and any additional incremental costs.<br>
                       <span class="fw-bold">Involuntary Room Upgrading: </span> If a member must occupy a room one category higher due to non-availability (except suite room), the member covers the cost difference while AGC pays for professional fees and hospital bills. If a room becomes available, the member must transfer, or incremental charges (professional fees, room and board difference, hospital bills) will be billed. Incremental charges for voluntary room upgrading are the member's responsibility, except for Emergency Care. 
                    </small>
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
      const noa_no = `<?php echo $row['noa_no']; ?>`;

      new QRCode(document.getElementById("qrcode"), {
        text: noa_no,
        width: 100,
        height: 100,
      });
      
      JsBarcode("#barcode", healthcard_no, {
        displayValue: false,
        width: 1.5,
        height: 80,
      });
    }

  </script>