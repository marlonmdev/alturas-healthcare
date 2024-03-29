
  <!-- Start of Page Wrapper -->
  <div class="page-wrapper">
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title ls-2">MY ACCOUNT</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Member</li>
                <li class="breadcrumb-item active" aria-current="page">
                  Profile
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
      <div class="row">
        <div class="col-lg-12">

          <!-- Account page navigation-->
          <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item">
              <a
                class="nav-link active"
                data-toggle="tab"
                href="<?php echo base_url(); ?>member/profile"
                role="tab"
                ><span class="hidden-sm-up"></span>
                <span class="hidden-xs-down fs-5 font-bold">My Profile</span></a
              >
            </li>
            <li class="nav-item">
              <a
                class="nav-link"
                data-toggle="tab"
                href="<?php echo base_url(); ?>member/account-settings"
                role="tab"
                ><span class="hidden-sm-up"></span>
                <span class="hidden-xs-down fs-5 font-bold">Account Settings</span></a
              >
            </li>
          </ul>

          <div class="row gutters-sm">
            <div class="col-md-4 mb-3">
              <div class="card shadow">
                <div class="card-body pt-4">
                  <div class="d-flex flex-column align-items-center text-center">
                    
                    <?php if ($member['photo'] == '' || $member_photo_status == 'Not Found') { ?>
                      <?php if($member['gender'] == 'Male' || $member['gender'] == 'male'): ?>
                        <img src="<?= base_url() . 'assets/images/male_avatar.svg' ?>" alt="Member" class="img-responsive" width="150" height="auto">
                      <?php endif; ?>

                      <?php if($member['gender'] == 'Female' || $member['gender'] == 'female'): ?>
                        <img src="<?= base_url() . 'assets/images/female_avatar.svg' ?>" alt="Member" class="img-responsive" width="150" height="auto">
                      <?php endif; ?>
                    <?php } else { ?>
                      <img src="<?= base_url() . 'uploads/profile_pics/' . $member['photo'] ?>" alt="Member" class="rounded-circle img-responsive" width="200" height="auto">
                    <?php } ?>

                    <div class="mt-3">
                      <p class="mb-1"><strong><?= $member['business_unit']; ?></strong></p>
                      <p class="mb-1"><strong><?= $member['dept_name']; ?></strong></p>
                      <p class="text-success mb-1"><strong><?= $member['position']; ?></strong></p>
                      <p class="mb-1"><strong><?= $member['emp_type']; ?></strong></em>
                      <p class="text-muted font-size-sm"><span class="badge rounded-pill bg-success"><strong><?= $member['current_status']; ?></strong></span></p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card shadow mt-3">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="mb-0 text-secondary" style="font-weight:600;">Employee ID: </h6>
                    <span style="font-weight:600;" class="colored-label">
                      <?= $member['emp_id'] ?>
                    </span>
                  </li>

                  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="mb-0 text-secondary" style="font-weight:600;">Position Level: </h6>
                    <span style="font-weight:600;" class="colored-label">
                      <?= $member['position_level'] ?>
                    </span>
                  </li>

                  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="mb-0 text-secondary" style="font-weight:600;">Health Card No: </h6>
                    <span style="font-weight:600;" class="colored-label">
                      <?php
                      echo empty($member['health_card_no']) ? 'None' : $member['health_card_no'];
                      ?>
                    </span>
                  </li>

                  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="mb-0 text-secondary" style="font-weight:600;">Max Benefit Limit: </h6>
                    <span style="font-weight:600;" class="colored-label">
                      <?php
                      echo empty($mbl['max_benefit_limit']) ? 'None' : '&#8369;' . number_format($mbl['max_benefit_limit'], 2);
                      ?>
                    </span>
                  </li>

                  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="mb-0 text-secondary" style="font-weight:600;">Remaining Balance: </h6>
                    <span style="font-weight:600;" class="colored-label">
                      <?php
                      echo empty($mbl['remaining_balance']) ? 'None' : '&#8369;' . number_format($mbl['remaining_balance'], 2);
                      ?>
                    </span>
                  </li>
                </ul>
              </div>

            </div>
            <div class="col-md-8">
              <div class="card shadow mb-0">
                <div class="card-body pt-4">
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-2 text-secondary" style="font-weight:600;">Full Name:</h6>
                    </div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['first_name'] . ' ' . $member['middle_name'] . ' ' . $member['last_name'] . ' ' . $member['suffix'] ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0 text-secondary" style="font-weight:600;">Home Address:</h6>
                    </div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['home_address'] ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0 text-secondary" style="font-weight:600;">City Address:</h6>
                    </div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['city_address'] ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0 text-secondary" style="font-weight:600;">Date of Birth:</h6>
                    </div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= date("F d, Y", strtotime($member['date_of_birth'])) ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0 text-secondary" style="font-weight:600;">Age:</h6>
                    </div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?php
                      $dateOfBirth = $member['date_of_birth'];
                      $today = date("Y-m-d");
                      $diff = date_diff(date_create($dateOfBirth), date_create($today));
                      echo $diff->format('%y') . ' years old';
                      ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0 text-secondary" style="font-weight:600;">Civil Status:</h6>
                    </div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['civil_status'] ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0 text-secondary" style="font-weight:600;">Sex:</h6>
                    </div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['gender'] ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0 text-secondary" style="font-weight:600;">Contact Number:</h6>
                    </div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['contact_no'] ?>
                    </div>
                  </div>
                  <hr>
                  <?php
                  if ($member['spouse'] !== '') :
                  ?>
                    <div class="row">
                      <div class="col-sm-3">
                        <h6 class="mb-0 text-secondary" style="font-weight:600;">Spouse:</h6>
                      </div>
                      <div class="col-sm-9 colored-label" style="font-weight:600;">
                        <?= $member['spouse']; ?>
                      </div>
                    </div>
                    <hr>
                  <?php endif; ?>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0 text-secondary" style="font-weight:600;">Blood Type:</h6>
                    </div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['blood_type'] ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0 text-secondary" style="font-weight:600;">Height:</h6>
                    </div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['height'] ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0 text-secondary" style="font-weight:600;">Weight:</h6>
                    </div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['weight'] ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-5 mt-2">
                      <h6 class="mb-0 text-secondary" style="font-weight:600;">Contact Person's Name:</h6>
                    </div>
                    <div class="col-sm-7 mt-2 colored-label" style="font-weight:600;">
                      <?= $member['contact_person'] ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-5">
                      <h6 class="mb-0 text-secondary" style="font-weight:600;">Contact Person's Address:</h6>
                    </div>
                    <div class="col-sm-7 colored-label" style="font-weight:600;">
                      <?= $member['contact_person_addr'] ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-5">
                      <h6 class="mb-0 text-secondary" style="font-weight:600;">Contact Person's Number:</h6>
                    </div>
                    <div class="col-sm-7 colored-label" style="font-weight:600;">
                      <?= $member['contact_person_no'] ?>
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