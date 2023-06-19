<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">HMO MEMBER FILES</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Company Doctor</li>
              <li class="breadcrumb-item active" aria-current="page">Member's Files</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
 
  <div class="container-fluid">
  <div class="row">
      <div class="col-6 pb-2">
          <div class="input-group">
              <a href="<?php echo base_url(); ?>company-doctor/members" type="submit" class="btn btn-info" data-bs-toggle="tooltip" title="Click to Go Back">
                  <strong class="ls-2" style="vertical-align:middle">
                      <i class="mdi mdi-arrow-left-bold"></i> Go Back
                  </strong>
              </a>
          </div>
      </div>
    </div>
    <div class="row pt-2">
      <div class="col-lg-12">
        <div class="card shadow">
          <div class="card-body">
                <span class="fs-5">Member's Fullname : <span class="fw-bold fs-4"><?php echo $member['first_name'].' '. $member['middle_name'].' '.$member['last_name'].' '.$member['suffix'];?></span></span><br>
                <span class="fs-5 pt-1">Business Unit : <span class="fw-bold fs-4"><?php echo $member['business_unit'];?></span></span>
          </div>
          <div class="row" style="justify-content:center">
            <?php 
                $emp_id = $this->myhash->hasher($member['emp_id'], 'encrypt');
                $member_id = $this->myhash->hasher($member['member_id'], 'encrypt');
            ?>
            <div class="col-md-2 ps-3 pt-3 pb-4">
                <a href="<?php echo base_url(); ?>company-doctor/members/incident-spot-reports/<?php echo $emp_id;?>/<?php echo $member_id;?>" class="btn btn-primary btn-lg bg-light border border-light text-dark">
                    <img src="<?php echo base_url(); ?>assets/images/blank-yellow-folder.png" alt="Button Image" class="img-fluid" width="200" height="auto">
                   <span class="fs-5">Incident/Spot Reports</span>
                </a>
            </div>
            <div class="col-md-2 ps-3 pt-3 pb-4">
                <a href="<?php echo base_url(); ?>company-doctor/members/final-diagnosis/<?php echo $emp_id;?>/<?php echo $member_id;?>" class="btn btn-primary btn-lg bg-light border border-light text-dark">
                    <img src="<?php echo base_url(); ?>assets/images/blank-yellow-folder.png" alt="Button Image" class="img-fluid" width="200" height="auto">
                   <span class="fs-5">Diagnosis/Operation</span>
                </a>
            </div>
            <div class="col-md-2 ps-3 pt-3 pb-4">
                <a href="<?php echo base_url(); ?>company-doctor/members/medical-abstract/<?php echo $emp_id;?>/<?php echo $member_id;?>" class="btn btn-primary btn-lg bg-light border border-light text-dark">
                    <img src="<?php echo base_url(); ?>assets/images/blank-yellow-folder.png" alt="Button Image" class="img-fluid" width="200" height="auto">
                   Medical Abstract
                </a>
            </div>
            <div class="col-md-2 ps-3 pt-3 pb-4">
                <a href="<?php echo base_url(); ?>company-doctor/members/take-home-meds/<?php echo $emp_id;?>/<?php echo $member_id;?>" class="btn btn-primary btn-lg bg-light border border-light text-dark">
                    <img src="<?php echo base_url(); ?>assets/images/blank-yellow-folder.png" alt="Button Image" class="img-fluid" width="200" height="auto">
                   Take Home Meds
                </a>
            </div>
            <div class="col-md-2 ps-3 pt-3 pb-4">
                <a href="<?php echo base_url(); ?>company-doctor/members/soa/<?php echo $emp_id;?>/<?php echo $member_id;?>" class="btn btn-primary btn-lg bg-light border border-light text-dark">
                    <img src="<?php echo base_url(); ?>assets/images/blank-yellow-folder.png" alt="Button Image" class="img-fluid" width="200" height="auto">
                   SOAs
                </a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

  <script>

  </script>