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
                                                <th scope="col">Request Type</th>
                                                <th scope="col">Request Date</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($userLoaList)) {
                                                foreach ($userLoaList as $loalist) :
                                            ?>
                                                    <tr>
                                                        <td>LOA</td>
                                                        <td><?= date("m/d/Y", strtotime($loalist->request_date)) ?>    </td>
                                                        <?php if ($loalist->status == 'Approved') { ?>
                                                            <td>
                                                                <div class="col">
                                                                    <a href="<?= base_url(); ?>healthcare-provider/billing/billing-person/bill-loa/<?= $loalist->emp_id ?>/<?= $loalist->loa_id ?>/<?= $billing_number ?>" data-bs-toggle="tooltip" title="Click to Proceed">&nbsp;&nbsp;Proceed to Billing</a>
                                                                </div>
                                                            </td>
                                                        <?php } else { ?>
                                                            <td><span class="badge rounded-pill bg-success">Done</span></td>
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
                                                        <td>NOA</td>
                                                        <td><?= date("m/d/Y", strtotime($noalist->request_date)) ?></td>
                                                        <?php if ($noalist->status == 'Approved') { ?>
                                                            <td>
                                                                <div class="col">
                                                                    <button type="submit" onclick="document.getElementById('noa_select_id').value = '<?= $noalist->noa_id ?>'" data-bs-toggle="tooltip" title="Click to Proceed" style="background-color: transparent;border:0;color:#4054F1;">Proceed to Billing</button>
                                                                </div>
                                                            </td>
                                                        <?php } else { ?>
                                                            <td><span class="badge rounded-pill bg-success">Done</span></td>
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
                                                <p class="mb-1"><strong><?= $member->business_unit ?></strong></p>
                                                <p class="mb-1"><strong><?= $member->dept_name ?></strong></p>
                                                <p class="text-success mb-1"><strong><?= $member->position ?></strong></p>
                                                <p class="mb-1"><strong><?= $member->emp_type ?></strong></em>
                                                <p class="text-muted font-size-sm"><span class="badge rounded-pill bg-success"><strong><?= $member->current_status ?></strong></span></p>
                                            </div>
                                        </div>                                
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="row mx-3 my-2">
                                            <div class="col-sm">
                                                <div class="mb-2">
                                                    <label for="inputFirstName" class="form-label"><b>Member Name</b></label>
                                                    <input type="text" name="first_name" hidden value="<?= $member->first_name ?>">
                                                    <input type="text" name="last_name" hidden value="<?= $member->last_name ?>">
                                                    <input type="text" readonly class="form-control" name="full_name" value="<?= $member->first_name . ' ' . $member->middle_name . ' ' . $member->last_name ?>" aria-describedby="emailHelp">
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="mb-2">
                                                    <label class="form-label"><b>Healthcard Number</b></label>
                                                    <input type="text" class="form-control" name="health_card_no" readonly value="<?= $member->health_card_no ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mx-3 my-2">
                                            <div class="col-sm">

                                                <div class="mb-2">
                                                    <label class="form-label"><strong>Employer Name</strong></label>
                                                    <input type="text" name="company" class="form-control" readonly value="<?= $member->company ?>" aria-describedby="emailHelp">
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
                                                    <label class="form-label"><strong>Remaining MBL</strong></label>
                                                    <input type="text" class="form-control" style="color:red" name="remaining_balance" value="<?= '&#8369;'. number_format($memberMBL->remaining_balance, 2) ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="mb-2">
                                                    <label class="form-label"><strong> Billing Number</strong></label>
                                                    <input type="text" name="billing_number" class="form-control" style="color:red" value="<?= $billing_number ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mx-3 my-2">
                                            <div class="col-sm">
                                                <div class="mb-2">
                                                    <label class="form-label"><strong>Healthcare Provider</strong></label>
                                                    <input type="text" class="form-control" name="hospital_name"  value="<?= $hospital->hp_name ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="mb-2">
                                                <label for="inputLastName" class="form-label"><strong>Date of Service</strong></label>
                                                    <input type="date" class="form-control" name="date_service" value="<?php echo date("Y-m-d"); ?>" readonly>
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