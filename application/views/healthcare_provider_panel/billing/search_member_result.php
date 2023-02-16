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
            <div class="col-12 mb-3">
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
                <!-- ================== Patient's LOA Requests Tab ==================== -->
                <div class="tab-pane fade show active" id="loa-requests" role="tabpanel">
                    <div class="card shadow">
                        <div class="container">
                            <div class="row px-4 py-4">
                                <div class="col-4">
                                    <!-- Member Profile Info -->
                                    <?php include "search_member_profile.php"; ?>                             
                                </div>
                                <div class="col-8">
                                    <table class="table table-hover" id="tableLoa">
                                        <thead>
                                            <tr>
                                                <th class="fw-bold">LOA No.</th>
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
                                                            <td class="fw-bold">
                                                                <mark class="bg-primary text-white ls-1"><?= $loa['loa_no'] ?></mark>
                                                            </td>
                                                            <td class="fw-bold">
                                                                <?= date("m/d/Y", strtotime($loa['request_date'])) ?>
                                                            </td>
                                                            <td class="fw-bold">
                                                                <form method="POST" action="<?= base_url() ?>healthcare-provider/billing/bill-loa/<?= $this->myhash->hasher($loa['loa_id'], 'encrypt') ?>">

                                                                    <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">

                                                                    <input type="hidden" name="emp_id" value="<?= $loa['emp_id'] ?>">

                                                                    <button type="submit" class="fw-bold ls-1 text-danger border-0" data-bs-toggle="tooltip" title="Click to Proceed" style="background-color: transparent;">
                                                                    Proceed to Billing <i class="mdi mdi-chevron-double-right fs-2" style="vertical-align:middle;"></i>
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    <?php } else if($loa['status'] == 'Closed') { ?>
                                                        <tr>
                                                            <td class="fw-bold">
                                                                <?= $loa['loa_no'] ?>
                                                            </td>
                                                            <td class="fw-bold">
                                                                <?= date("m/d/Y", strtotime($loa['request_date'])) ?>
                                                            </td>
                                                            <td class="fw-bold">
                                                                <span class="badge bg-success px-2 ls-1">
                                                                    Billed
                                                                </span>
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
                <!-- ===================== Patient's NOA Requests Tab ========================= -->
                <div class="tab-pane fade show" id="noa-requests" role="tabpanel">
                    <div class="card shadow">
                        <div class="container">
                            <div class="row px-4 py-4">
                                <div class="col-4">
                                    <!-- Member Profile Info -->
                                    <?php include "search_member_profile.php"; ?>                          
                                </div>
                                <div class="col-8">
                                    <table class="table table-hover" id="tableNoa">
                                        <thead>
                                            <tr>
                                                <th class="fw-bold">NOA No.</th>
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
                                                            <td class="fw-bold">
                                                                <mark class="bg-primary text-white ls-1"><?= $noa['noa_no'] ?></mark>
                                                            </td>
                                                            <td class="fw-bold">
                                                                <?= date("m/d/Y", strtotime($noa['request_date'])) ?>
                                                            </td>
                                                            <td class="fw-bold">
                                                                <form method="POST" action="<?= base_url() ?>healthcare-provider/billing/bill-noa/<?= $this->myhash->hasher($noa['noa_id'], 'encrypt') ?>">

                                                                    <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">

                                                                    <input type="hidden" name="emp_id" value="<?= $noa['emp_id'] ?>">

                                                                    <button type="submit" class="fw-bold ls-1 text-danger border-0" data-bs-toggle="tooltip" title="Click to Proceed" style="background-color: transparent;">
                                                                    Proceed to Billing <i class="mdi mdi-chevron-double-right fs-2" style="vertical-align:middle;"></i>
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                        <?php } else if($noa['status'] == 'Closed') { ?>
                                                        <tr>
                                                            <td class="fw-bold">
                                                                <?= $loa['noa_no'] ?>
                                                            </td>
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