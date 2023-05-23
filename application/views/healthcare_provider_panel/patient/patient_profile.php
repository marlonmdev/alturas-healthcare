<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">Members</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Company Doctor</li>
              <li class="breadcrumb-item active" aria-current="page">Member Profile</li>
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
          <div class="col-md-7 mb-3">
          <div class="card d-flex justify-content-evenly">
            <div class="row align-items-end">
              <div class="col-md-6">
                <div class="card shadow">
                <div class="card-body pt-4">
                <div class="d-flex flex-column align-items-center text-center">
                  <?php if ($member['photo'] == '') { ?>
                    <img src="<?= base_url() . 'assets/images/user.svg' ?>" alt="Member" class="rounded-circle img-responsive" width="150" height="auto">
                  <?php } else { ?>
                    <img src="<?= base_url() . 'uploads/profile_pics/' . $member['photo'] ?>" alt="Member" class="rounded-circle img-responsive" width="200" height="auto">
                  <?php } ?>
									<div class="mt-3">
										<p class="mb-1"><strong><?= $member['business_unit'] ?></strong></p>
										<p class="mb-1"><strong><?= $member['dept_name'] ?></strong></p>
										<p class="text-success mb-1"><strong><?= $member['position'] ?></strong></p>
										<p class="mb-1"><strong><?= $member['emp_type'] ?></strong></em>
										<p class="text-muted font-size-sm"><span class="badge rounded-pill bg-success"><strong><?= $member['current_status'] ?></strong></span></p>
									</div>
                </div>
              </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card shadow">
                <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                  <h6 class="mb-0 text-secondary" style="font-weight:600;">Position Level: </h6>
                  <span style="font-weight:600;" class="colored-label"><?= $member['position_level'] ?></span>
                </li>
                
                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                  <h6 class="mb-0 text-secondary" style="font-weight:600;">Employee No: </h6>
                  <span style="font-weight:600;" class="colored-label"><?php echo $member['emp_id'] ?: 'None'; ?></span>
                </li>

                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                  <h6 class="mb-0 text-secondary" style="font-weight:600;">Health Card No: </h6>
                  <span style="font-weight:600;" class="colored-label"><?php echo $member['health_card_no'] ?: 'None'; ?></span>
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
            </div>
          </div>
            <!-- <div class="card d-flex justify-content-evenly">
            <div class="card shadow">
             
            </div>

            <div class="card shadow">
         
            </div>
            </div> -->
            <h4 class="page-title ls-2">Patient History</h4>
            <!-- patient history Loa-->
            <div class="card shadow mt-2" >
              <table class="table table-hover table-responsive" id="loa_table">
                  <thead >
                    <tr>
                      <th > <span style="font-weight:600;" class="colored-label">LOA #</span></th>
                      <th > <span style="font-weight:600;" class="colored-label">AMOUT</span></th>
                      <th > <span style="font-weight:600;" class="colored-label">STATUS</span></th>
                      <th > <span style="font-weight:600;" class="colored-label">DATE</span></th>
                      <th > <span style="font-weight:600;" class="colored-label">VIEW</span></th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
            </div>

            <!-- patient history noa-->
            <div class="card shadow mt-3">
            <table class="table table-hover table-responsive" id="noa_table">
                <thead>
                  <tr>
                      <th > <span style="font-weight:600;" class="colored-label">NOA #</span></th>
                      <th > <span style="font-weight:600;" class="colored-label">AMOUT</span></th>
                      <th > <span style="font-weight:600;" class="colored-label">STATUS</span></th>
                      <th > <span style="font-weight:600;" class="colored-label">DATE</span></th>
                      <th > <span style="font-weight:600;" class="colored-label">VIEW</span></th>
                  </tr>
                </thead>
              	<tbody>
                </tbody>
              </table>
            </div>
            <!-- <span style="font-weight:600;" class="colored-label ps-3">Patient History</span> -->
            <!-- patient history Loa-->
            <!-- <div class="card shadow mt-2" >
            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap border-0"> -->
                  <!-- <h6 class="mb-0 text-secondary" style="font-weight:600;">LOA </h6> -->
                  <!-- <span style="font-weight:600;" class="colored-label">LOA #</span>
                  <span style="font-weight:600;" class="colored-label">Status</span>
                </li>
              <ul class="list-group list-group-flush" style="overflow-y: auto; max-height: 250px;">

                <?php foreach ($loa as $l) : ?>
                  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap" >
                    <h6 class="mb-0 text-primary custom-text" style="font-weight:600; cursor: pointer;" onclick="viewloa('<?= $l->loa_no?>')"><?= $l->loa_no?></h6>
                    <span style="font-weight:600;" class="colored-label"><?= $l->status?></span>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div> -->

            <!-- patient history noa-->
            <!-- <div class="card shadow mt-3">
            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap border-0"> -->
                  <!-- <h6 class="mb-0 text-secondary" style="font-weight:600;">LOA </h6> -->
                  <!-- <span style="font-weight:600;" class="colored-label">NOA #</span>
                  <span style="font-weight:600;" class="colored-label">Status</span>
                </li>
              <ul class="list-group list-group-flush"  style="overflow-y: auto; max-height: 250px;">
                
                
                <?php foreach ($noa as $n) : ?>
                  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap" >
                    <h6 class="mb-0 text-primary custom-text" style="font-weight:600; cursor: pointer;" onclick="viewnoa('<?= $n->noa_no?>')"><?= $n->noa_no?></h6>
                    <span style="font-weight:600;" class="colored-label"><?= $n->status?></span>
                  </li>
                <?php endforeach; ?>
                
              </ul>
            </div> -->

          </div>

          <div class="col-md-5">
            <div class="card shadow mb-0">
              <div class="card-body pt-4">
                <div class="row">
                  <div class="col-sm-3"><h6 class="mb-2 text-secondary" style="font-weight:600;">Full Name:</h6></div>
                  <div class="col-sm-9 colored-label" style="font-weight:600;">
                    <?= $member['first_name'] . ' ' . $member['middle_name'] . ' ' . $member['last_name'] . ' ' . $member['suffix']; ?>
                  </div>
                </div><hr>

                <div class="row">
                  <div class="col-sm-3"><h6 class="mb-0 text-secondary" style="font-weight:600;">Home Address:</h6></div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['home_address']; ?>
                    </div>
                  </div><hr>
                  <div class="row">
                    <div class="col-sm-3"><h6 class="mb-0 text-secondary" style="font-weight:600;">City Address:</h6></div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['city_address']; ?>
                    </div>
                  </div><hr>
                  <div class="row">
                    <div class="col-sm-3"><h6 class="mb-0 text-secondary" style="font-weight:600;">Date of Birth:</h6></div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= date("F d, Y", strtotime($member['date_of_birth'])) ?>
                    </div>
                  </div><hr>
                  <div class="row">
                    <div class="col-sm-3"><h6 class="mb-0 text-secondary" style="font-weight:600;">Age:</h6></div>
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
                    <div class="col-sm-3"><h6 class="mb-0 text-secondary" style="font-weight:600;">Civil Status:</h6></div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['civil_status']; ?>
                    </div>
                  </div><hr>
                  <div class="row">
                    <div class="col-sm-3"><h6 class="mb-0 text-secondary" style="font-weight:600;">Sex:</h6></div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['gender']; ?>
                    </div>
                  </div><hr>
                  <div class="row">
                    <div class="col-sm-3"><h6 class="mb-0 text-secondary" style="font-weight:600;">Contact Number:</h6></div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['contact_no']; ?>
                    </div>
                  </div><hr>
                  <div class="row">
                    <div class="col-sm-3"><h6 class="mb-0 text-secondary" style="font-weight:600;">Email Address:</h6></div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['email']; ?>
                    </div>
                  </div><hr>
                  <?php
                  	if ($member['spouse'] !== '') :
                  ?>
                  <div class="row">
                    <div class="col-sm-3"><h6 class="mb-0 text-secondary" style="font-weight:600;">Spouse:</h6></div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['spouse']; ?>
                    </div>
                  </div><hr>
                  <?php endif; ?>
                  <div class="row">
                    <div class="col-sm-3"><h6 class="mb-0 text-secondary" style="font-weight:600;">Blood Type:</h6></div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['blood_type']; ?>
                    </div>
                  </div><hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0 text-secondary" style="font-weight:600;">Height:</h6>
                    </div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['height']; ?>
                    </div>
                  </div><hr>
                  <div class="row">
                    <div class="col-sm-3"><h6 class="mb-0 text-secondary" style="font-weight:600;">Weight:</h6></div>
                    <div class="col-sm-9 colored-label" style="font-weight:600;">
                      <?= $member['weight']; ?>
                    </div>
                  </div><hr>
                  <div class="row">
                    <div class="col-sm-5 mt-2"><h6 class="mb-0 text-secondary" style="font-weight:600;">Contact Person's Name:</h6></div>
                    <div class="col-sm-7 mt-2 colored-label" style="font-weight:600;">
                      <?= $member['contact_person']; ?>
                    </div>
                  </div><hr>
                  <div class="row">
                    <div class="col-sm-5"><h6 class="mb-0 text-secondary" style="font-weight:600;">Contact Person's Address:</h6></div>
                    <div class="col-sm-7 colored-label" style="font-weight:600;">
                      <?= $member['contact_person_addr']; ?>
                    </div>
                  </div><hr>
                  <div class="row">
                    <div class="col-sm-5"><h6 class="mb-0 text-secondary" style="font-weight:600;">Contact Person's Number:</h6></div>
                    <div class="col-sm-7 colored-label" style="font-weight:600;">
                      <?= $member['contact_person_no']; ?>
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
</div>
<!-- <style>
  .custom-text:hover {
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
  }
</style> -->
 <script>
  const viewloa = (loa_no) =>{
      console.log(loa_no);
      }
      const viewnoa = (noa_no) =>{
        console.log(noa_no);
      }

      const baseurl = '<?php echo base_url();?>';
      const emp_id = '<?= $member['emp_id']?>';
      const hp_id = '<?= $hp_id?>';
      
          $(document).ready(function(){
              $('#loa_table').DataTable({ 
              processing: true,
              serverSide: true,
              order: [],

              ajax: {
                url: `${baseurl}healthcare-provider/patient/fetch_all_patient_loa`,
                type: "POST",
                data: { 'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
                        'emp_id' :  emp_id,
                        'hp_id' :  hp_id}
              },

              // columnDefs: [{ 
              //   "targets": [6], // 6th and 7th column / numbering column
              //   "orderable": false,
              // },
              // ],
              responsive: true,
              fixedHeader: true,
            });   
         
          
            $('#noa_table').DataTable({ 
            processing: true,
            serverSide: true,
            order: [],

            ajax: {
              url: `${baseurl}healthcare-provider/patient/fetch_all_patient_noa`,
              type: "POST",
              data: { 'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
                      'emp_id' :  emp_id,
                      'hp_id' :  hp_id}
            },

            // columnDefs: [{ 
            //   "targets": [6], // 6th and 7th column / numbering column
            //   "orderable": false,
            // },
            // ],
            responsive: true,
            fixedHeader: true,
            });   

          });
          
         
 </script>
  

