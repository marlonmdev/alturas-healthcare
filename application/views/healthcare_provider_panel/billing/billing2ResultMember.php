  <!-- Page wrapper  -->
  <div class="page-wrapper">
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title ls-2">Billing</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Healthcare Provider</li>
                <li class="breadcrumb-item active" aria-current="page">
                  Billing
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- Container fluid  -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true"><strong>Profile</strong></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link " id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false"><strong>Record</strong></button>
                    </li>
                </ul>
            </div>
        

            <form action="<?php echo base_url(); ?>healthcare-provider/billing/billing-person/equipment" class="needs-validation" method="post" novalidate>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="home-tab">
                        <div class="card">
                            <div class="container">
                                <div class="row px-4 py-4">
                                    <table class="table table striped table-hover">
                                        <thead>
                                            <tr>
                                                <th class="fw-bold">Request Type</th>
                                                <th class="fw-bold">Request Date</th>
                                                <th class="fw-bold">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($userLoaList)) {
                                                foreach ($userLoaList as $loalist) :
                                            ?>
                                                    <tr>
                                                        <td class="fw-bold">LOA</td>
                                                        <td class="fw-bold">
                                                            <?= date("m/d/Y", strtotime($loalist->request_date)) ?>
                                                        </td>
                                                        <?php if ($loalist->status == 'Approved') { ?>
                                                            <td class="fw-bold">
                                                                <div class="col">
                                                                    <a href="<?= base_url() ?>healthcare-provider/billing/billing-person/bill-loa/<?= $loalist->emp_id ?>/<?= $loalist->loa_id ?>/<?= $billing_number ?>" data-bs-toggle="tooltip" title="Click to Proceed">&nbsp;&nbsp;Proceed to Billing</a>
                                                                </div>
                                                            </td>
                                                        <?php } else { ?>
                                                            <td><span class="badge bg-success">Done</span></td>
                                                        <?php } ?>

                                                    </tr>
                                            <?php
                                                endforeach;
                                            }
                                            ?>
                                            <?php
                                            if (!empty($userNoaList)) {
                                                foreach ($userNoaList as $noalist) :
                                            ?>

                                                    <tr>
                                                        <td class="fw-bold">NOA</td>
                                                        <td class="fw-bold">
                                                            <?= date("m/d/Y", strtotime($noalist->request_date)) ?>
                                                        </td>
                                                        <?php if ($noalist->status == 'Approved') { ?>
                                                            <td class="fw-bold">
                                                                <div class="col">
                                                                    <button type="submit" onclick="document.getElementById('noa_select_id').value = '<?= $noalist->noa_id ?>'" data-bs-toggle="tooltip" title="Click to Proceed" style="background-color: transparent;border:0;color:#4054F1;">
                                                                        <span class="fw-bold">
                                                                            Proceed to Billing
                                                                        </span>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        <?php } else { ?>
                                                            <td><span class="badge bg-success py-2">Done</span></td>
                                                        <?php } ?>

                                                    </tr>
                                            <?php
                                                endforeach;
                                            }
                                            ?>

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="tab-pane fade  show active" id="home" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="card">
                            <div class="cantainer">
                                <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
                                <input type="hidden" name="member_id" value="<?= $member->member_id ?>">
                                <input type="hidden" name="hp_id" value="<?= $hospital->hp_id ?>">
                                <input type="hidden" name="noa_select_id" id="noa_select_id" value="<?= $hospital->hp_id ?>">
                                <div class="row px-4 py-4">
                                    <div class="col-lg-4">
                                        <div class="d-flex flex-column align-items-center text-center">
                                            <?php if ($member->photo == '') { ?>
                                            <img src="<?= base_url() . 'assets/images/user.svg' ?>" alt="Member" class="rounded-circle img-responsive" width="150" height="auto">
                                            <?php } else { ?>
                                            <img src="<?= base_url() . 'uploads/profile_pics/' . $member->photo ?>" alt="Member" class="rounded-circle img-responsive" width="200" height="auto">
                                            <?php } ?>
                                            <div class="mt-3">
                                                <p class="fw-bold mb-1"><?= $member->business_unit ?></p>
                                                <p class="fw-bold mb-1"><?= $member->dept_name ?></p>
                                                <p class="text-success fw-bold mb-1"><?= $member->position ?></p>
                                                <p class="fw-bold mb-1"><?= $member->emp_type ?></em>
                                                <p class="text-muted font-size-sm"><span class="badge rounded-pill bg-success fw-bold"><?= $member->current_status ?></span></p>
                                            </div>
                                        </div>                                
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="row mx-3 my-2">
                                            <div class="col-sm">
                                                <div class="mb-2">
                                                    <label class="fw-bold">Member Name</label>
                                                    <input type="text" name="first_name" hidden value="<?= $member->first_name ?>">
                                                    <input type="text" name="last_name" hidden value="<?= $member->last_name ?>">
                                                    <input type="text" readonly class="form-control fw-bold name="full_name" value="<?= $member->first_name . ' ' . $member->middle_name . ' ' . $member->last_name ?>">
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="mb-2">
                                                    <label class="fw-bold">Healthcard Number</label>
                                                    <input type="text" class="form-control fw-bold" name="health_card_no" readonly value="<?= $member->health_card_no ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mx-3 my-2">
                                            <div class="col-sm">

                                                <div class="mb-2">
                                                    <label class="fw-bold">Employer Name</label>
                                                    <input type="text" name="company" class="form-control fw-bold" readonly value="<?= $member->company ?>">
                                                </div>
                                            </div>
                                            <!-- <div class="col-sm">
                                                <div class="mb-2">
                                                    <label class="form-label"><strong>Type of Member</strong></label>
                                                    <input type="text" class="form-control" name="emp_type" readonly value="<?= $member->emp_type ?>" aria-describedby="emailHelp">
                                                </div>
                                            </div> -->

                                        </div>
                                        <div class="row mx-3 my-2">
                                            <div class="col-sm">
                                                <div class="mb-2">
                                                    <label class="fw-bold">Remaining MBL</label>
                                                    <input type="text" class="form-control text-info fw-bold" name="remaining_balance" value="<?= '&#8369;'. number_format($memberMBL->remaining_balance, 2) ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="mb-2">
                                                    <label class="fw-bold">Billing Number</label>
                                                    <input type="text" name="billing_number" class="form-control text-info fw-bold" value="<?= $billing_number ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mx-3 my-2">
                                            <div class="col-sm">
                                                <div class="mb-2">
                                                    <label class="fw-bold">Healthcare Provider</label>
                                                    <input type="text" class="form-control fw-bold" name="hospital_name"  value="<?= $hospital->hp_name ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="mb-2">
                                                <label class="fw-bold">Date of Service</label>
                                                    <input type="date" class="form-control fw-bold" name="date_service" value="<?php echo date("Y-m-d"); ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>