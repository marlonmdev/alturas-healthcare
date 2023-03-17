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
    <span class="fw-bold text-info text-uppercase fs-4" name="full_name">
      <?= $member['first_name'].' '.$member['middle_name'].' '. $member['last_name'].' '.$member['suffix'] ?>
    </span>
    <p class="fw-bold fs-5 mb-1">
      Member Since : <span class="text-danger"><?= date('F d, Y', strtotime($member['date_approved'])) ?></span>
    </p>
    <p class="fw-bold fs-5 mb-1">
      Healthcard No. : <span class="text-danger"><?= $member['health_card_no'] ?></span>
    </p>
    <p class="text-muted fs-5 ls-2">
      <span class="badge rounded-pill bg-success fw-bold"><?= $member['current_status'] ?></span>
    </p>
  </div>
</div>