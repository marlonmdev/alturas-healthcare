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
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#loa-requests" type="button" role="tab" aria-controls="home" aria-selected="true"><strong>LOA Requests</strong></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link " id="profile-tab" data-bs-toggle="tab" data-bs-target="#noa-requests" type="button" role="tab" aria-controls="profile" aria-selected="false"><strong>NOA Requests</strong></button>
                    </li>
                </ul>
            </div>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="loa-requests" role="tabpanel">
                    <div class="card">
                        <div class="container">
                            <div class="row px-4 py-4">
                                <!-- Member Profile Info -->
                                <div class="col-4">
                                    <div class="d-flex flex-column align-items-center text-center">
                                        <?php if ($member['photo'] == '') { ?>
                                            <img src="<?= base_url() . 'assets/images/user.svg' ?>" alt="Member" class="rounded-circle img-responsive" width="150" height="auto">
                                        <?php } else { ?>
                                            <img src="<?= base_url() . 'uploads/profile_pics/' . $member['photo'] ?>" alt="Member" class="rounded-circle img-responsive" width="140" height="auto">
                                        <?php } ?>
                                        <div class="mt-3">
                                            <span class="fw-bold text-info text-uppercase fs-4">
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
                                </div>
                                <div class="col-8">
                                    <table class="table table striped table-hover" id="tableLoa">
                                        <thead>
                                            <tr>
                                                <th class="fw-bold">LOA Number</th>
                                                <th class="fw-bold">Request Date</th>
                                                <th class="fw-bold">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($loa_requests)) :
                                                foreach ($loa_requests as $loa) :
                                                    if($loa['status'] == 'Approved'){
                                            ?>
                                                        <tr>
                                                            <td class="fw-bold"><?= $loa['loa_no'] ?></td>
                                                            <td class="fw-bold">
                                                                <?= date("m/d/Y", strtotime($loa['request_date'])) ?>
                                                            </td>
                                                            <td class="fw-bold">
                                                                <div class="col">
                                                                    <form method="post" action="<?= base_url() ?>healthcare-provider/billing/bill-loa/<?= $this->myhash->hasher($loa['loa_id'], 'encrypt') ?>">
                                                                        <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">

                                                                        <input type="hidden" name="emp_id" value="<?= $loa['emp_id'] ?>">

                                                                        <input type="hidden" name="billing_no" value="<?= $billing_no ?>">
                                                                        <button type="submit" class="fw-bold" data-bs-toggle="tooltip" title="Click to Proceed" style="background-color: transparent;border:0;color:#4054F1;">Proceed to Billing</button>
                                                                    </form>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php } else if($loa['status'] == 'Closed') { ?>
                                                        <tr>
                                                            <td class="fw-bold"><?= $loa['loa_no'] ?></td>
                                                            <td class="fw-bold">
                                                                <?= date("m/d/Y", strtotime($loa['request_date'])) ?>
                                                            </td>
                                                            <td class="fw-bold">
                                                                <span class="badge bg-success px-2 ls-1">Billed</span>
                                                            </td>
                                                        </tr>
                                            <?php
                                                    }
                                                endforeach;
                                            endif;
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <div class="tab-pane fade  show" id="noa-requests" role="tabpanel">
                    <form action="<?php echo base_url(); ?>healthcare-provider/billing/billing-person/equipment" class="needs-validation" method="post" novalidate>
                        <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" name="member_id" value="<?= $member['member_id'] ?>">
                        <input type="hidden" name="hp_id" value="<?= $hp_name->hp_id ?>">
                        <input type="hidden" name="noa_select_id" id="noa_select_id" value="<?= $hp_name->hp_id ?>">
                        <div class="card">
                            <div class="container">
                                <div class="row px-4 py-4">
                                    <div class="col-4">
                                        <div class="d-flex flex-column align-items-center text-center">
                                            <?php if ($member['photo'] == '') { ?>
                                                <img src="<?= base_url() . 'assets/images/user.svg' ?>" alt="Member" class="rounded-circle img-responsive" width="150" height="auto">
                                            <?php } else { ?>
                                                <img src="<?= base_url() . 'uploads/profile_pics/' . $member['photo'] ?>" alt="Member" class="rounded-circle img-responsive" width="140" height="auto">
                                            <?php } ?>
                                            <div class="mt-3">
                                                <span class="fw-bold text-info text-uppercase fs-4">
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
                                    </div>
                                    <div class="col-8">
                                        <table class="table table-striped table-hover" id="tableNoa">
                                            <thead>
                                                <tr>
                                                    <th class="fw-bold">NOA Number</th>
                                                    <th class="fw-bold">Request Date</th>
                                                    <th class="fw-bold">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if (!empty($noa_requests)) :
                                                        foreach ($noa_requests as $noa) :
                                                            if($noa['status'] == 'Approved') {
                                                ?>
                                                            <tr>
                                                                <td class="fw-bold"><?= $noa['noa_no'] ?></td>
                                                                <td class="fw-bold">
                                                                    <?= date("m/d/Y", strtotime($noa['request_date'])) ?>
                                                                </td>
                                                                <td class="fw-bold">
                                                                    <div class="col">
                                                                        <button type="submit" onclick="document.getElementById('noa_select_id').value = '<?= $noa['noa_id'] ?>'" data-bs-toggle="tooltip" title="Click to Proceed" style="background-color: transparent;border:0;color:#4054F1;">
                                                                            <span class="fw-bold">
                                                                                Proceed to Billing
                                                                            </span>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php } else if($noa['status'] == 'Closed') { ?>
                                                            <tr>
                                                                <td class="fw-bold"><?= $loa['noa_no'] ?></td>
                                                                <td class="fw-bold">
                                                                    <?= date("m/d/Y", strtotime($noa['request_date'])) ?>
                                                                </td>
                                                                <td class="fw-bold">
                                                                    <span class="badge bg-success px-2 ls-1">Billed</span>
                                                                </td>
                                                            </tr>
                                                <?php
                                                            }
                                                        endforeach;
                                                    endif;
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#tableLoa').DataTable({
            searching: false,
            columnDefs: [{
                "targets": [2], // numbering column
                "orderable": false, //set not orderable
            }],
        });

        $('#tableNoa').DataTable({
            searching: false,
            columnDefs: [{
                "targets": [2], // numbering column
                "orderable": false, //set not orderable
            }],
        });
    });
</script>