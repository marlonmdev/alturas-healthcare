<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">EMPLOYEE INFORMATION</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">
                Patient Profile
              </li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row">

      <div class="col-lg-12 mb-3 mt-0">
        <a class="btn btn-dark btn-md text-white" href="javascript:void(0)" onclick="window.history.back()" data-bs-toggle="tooltip" title="Click to Go Back"><strong class="ls-2" style="vertical-align:middle"><i class="mdi mdi-arrow-left-bold"></i> Go Back</strong></a>
      </div>

      <div class="col-lg-12">
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
                  <span style="font-weight:600;" class="colored-label"><?= $member['emp_id'] ?></span>
                </li>

                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                  <h6 class="mb-0 text-secondary" style="font-weight:600;">Position Level: </h6>
                  <span style="font-weight:600;" class="colored-label"><?= $member['position_level'] ?></span>
                </li>

                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                  <h6 class="mb-0 text-secondary" style="font-weight:600;">Health Card No: </h6>
                  <span style="font-weight:600;" class="colored-label"><?php echo empty($member['health_card_no']) ? 'None' : $member['health_card_no'];?></span>
                </li>

                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                  <h6 class="mb-0 text-secondary" style="font-weight:600;">Max Benefit Limit: </h6>
                  <span style="font-weight:600;" class="colored-label"><?php echo empty($mbl['max_benefit_limit']) ? 'None' : '&#8369;' . number_format($mbl['max_benefit_limit'], 2);?>
                  </span>
                </li>

                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                  <h6 class="mb-0 text-secondary" style="font-weight:600;">Remaining Balance: </h6>
                  <span style="font-weight:600;" class="colored-label"><?php echo empty($mbl['remaining_balance']) ? 'None' : '&#8369;' . number_format($mbl['remaining_balance'], 2);?></span>
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
                </div><hr>

                <div class="row">
                  <div class="col-sm-3">
                    <h6 class="mb-0 text-secondary" style="font-weight:600;">Home Address:</h6>
                  </div>
                  <div class="col-sm-9 colored-label" style="font-weight:600;">
                    <?= $member['home_address'] != '' ? $member['home_address'] : 'None' ?>
                  </div>
                </div><hr>

                <div class="row">
                  <div class="col-sm-3">
                    <h6 class="mb-0 text-secondary" style="font-weight:600;">City Address:</h6>
                  </div>
                  <div class="col-sm-9 colored-label" style="font-weight:600;">
                    <?= $member['city_address'] != '' ? $member['city_address'] : 'None' ?>
                  </div>
                </div><hr>

                <div class="row">
                  <div class="col-sm-3">
                    <h6 class="mb-0 text-secondary" style="font-weight:600;">Date of Birth:</h6>
                  </div>
                  <div class="col-sm-9 colored-label" style="font-weight:600;">
                    <?= date("F d, Y", strtotime($member['date_of_birth'])) ?>
                  </div>
                </div><hr>

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
                </div><hr>

                <div class="row">
                  <div class="col-sm-3">
                    <h6 class="mb-0 text-secondary" style="font-weight:600;">Civil Status:</h6>
                  </div>
                  <div class="col-sm-9 colored-label" style="font-weight:600;">
                    <?= $member['civil_status'] != '' ? $member['civil_status'] : 'None' ?>
                  </div>
                </div><hr>

                <div class="row">
                  <div class="col-sm-3">
                    <h6 class="mb-0 text-secondary" style="font-weight:600;">Sex:</h6>
                  </div>
                  <div class="col-sm-9 colored-label" style="font-weight:600;">
                    <?= $member['gender'] != '' ? $member['gender'] : 'None' ?>
                  </div>
                </div><hr>

                <div class="row">
                  <div class="col-sm-3">
                    <h6 class="mb-0 text-secondary" style="font-weight:600;">Contact Number:</h6>
                  </div>
                  <div class="col-sm-9 colored-label" style="font-weight:600;">
                    <?= $member['contact_no'] != '' ? $member['contact_no'] : 'None' ?>
                  </div>
                </div><hr>

                <div class="row">
                  <div class="col-sm-3">
                    <h6 class="mb-0 text-secondary" style="font-weight:600;">Email Address:</h6>
                  </div>
                  <div class="col-sm-9 colored-label" style="font-weight:600;">
                    <?= $member['email'] != '' ? $member['email'] : 'None' ?>
                  </div>
                </div><hr>

                <?php if ($member['spouse'] !== '') :?>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0 text-secondary" style="font-weight:600;">Spouse:</h6>
                    </div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['spouse'] != '' ? $member['spouse'] : 'None' ?>
                    </div>
                  </div><hr>
                <?php endif; ?>

                <div class="row">
                  <div class="col-sm-3">
                    <h6 class="mb-0 text-secondary" style="font-weight:600;">Blood Type:</h6>
                  </div>
                  <div class="col-sm-9 colored-label" style="font-weight:600;">
                    <?= $member['blood_type'] != '' ? $member['blood_type'] : 'None' ?>
                  </div>
                </div><hr>

                <div class="row">
                  <div class="col-sm-3">
                    <h6 class="mb-0 text-secondary" style="font-weight:600;">Height:</h6>
                  </div>
                  <div class="col-sm-9 colored-label" style="font-weight:600;">
                    <?= $member['height'] != '' ? $member['height'] : 'None' ?>
                  </div>
                </div><hr>

                <div class="row">
                  <div class="col-sm-3">
                    <h6 class="mb-0 text-secondary" style="font-weight:600;">Weight:</h6>
                  </div>
                  <div class="col-sm-9 colored-label" style="font-weight:600;">
                    <?= $member['weight'] != '' ? $member['weight'] : 'None' ?>
                  </div>
                </div><hr>

                <div class="row">
                  <div class="col-sm-5 mt-2">
                    <h6 class="mb-0 text-secondary" style="font-weight:600;">Contact Person's Name:</h6>
                  </div>
                  <div class="col-sm-7 mt-2 colored-label" style="font-weight:600;">
                    <?= $member['contact_person'] != '' ? $member['contact_person'] : 'None' ?>
                  </div>
                </div><hr>

                <div class="row">
                  <div class="col-sm-5">
                    <h6 class="mb-0 text-secondary" style="font-weight:600;">Contact Person's Address:</h6>
                  </div>
                  <div class="col-sm-7 colored-label" style="font-weight:600;">
                    <?= $member['contact_person_addr'] != '' ? $member['contact_person_addr'] : 'None' ?>
                  </div>
                </div><hr>

                <div class="row">
                  <div class="col-sm-5">
                    <h6 class="mb-0 text-secondary" style="font-weight:600;">Contact Person's Number:</h6>
                  </div>
                  <div class="col-sm-7 colored-label" style="font-weight:600;">
                    <?= $member['contact_person_no'] ?>
                    <?= $member['contact_person_no'] != '' ? $member['contact_person_no'] : 'None' ?>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>