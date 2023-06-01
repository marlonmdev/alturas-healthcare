<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Start Bread crumb and right sidebar toggle -->
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
        <div class="mb-3">
            <a href="<?php echo base_url(); ?>healthcare-provider/billing" class="btn btn-outline-dark" data-bs-toggle="tooltip" title="Click to Go Back">
                <strong class="ls-2" style="vertical-align:middle">
                    <i class="mdi mdi-arrow-left-bold"></i> Go Back
                </strong>
            </a>
        </div>

        <div class="row">
            <div class="col-12 mb-3">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#loa-requests" type="button" role="tab" aria-controls="home" aria-selected="true"><strong>LOA Requests</strong></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link " id="profile-tab" data-bs-toggle="tab" data-bs-target="#noa-requests" type="button" role="tab" aria-controls="profile" aria-selected="false"><strong>NOA Requests</strong></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link " id="profile-tab" data-bs-toggle="tab" data-bs-target="#re_upload_history" type="button" role="tab" aria-controls="profile" aria-selected="false"><strong>Re-Upload Request</strong></button>
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
                                                <th class="fw-bold">#</th>
                                                <th class="fw-bold">Request Date</th>
                                                <th class="fw-bold">Status</th>
                                                <th class="fw-bold">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($loa_requests)) :
                                                foreach ($loa_requests as $loa) :
                                                    if($loa['status'] == 'Approved' || $loa['status'] == 'Completed'){
                                            ?>
                                                        <tr>
                                                            <td class="fw-bold">
                                                                <mark class="bg-primary text-white ls-1"><?= $loa['loa_no'] ?></mark>
                                                            </td>
                                                            <td class="fw-bold">
                                                                <?= date("m/d/Y", strtotime($loa['request_date'])) ?>
                                                            </td>
                                                            <td class="fw-bold">
                                                                <span class="badge rounded-pill bg-success ls-1">
                                                                    <?= $loa['status'] ?>
                                                                </span>
                                                            </td>
                                                            <td class="fw-bold">
                                                                <?php $loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt'); ?>

                                                                <a href="<?php echo base_url(); ?>healthcare-provider/billing/bill-loa/upload-pdf/<?= $loa_id ?>" class="text-danger" data-bs-toggle="tooltip" title="Upload PDF Billing"><i class="mdi mdi-upload fs-2"></i></a>

                                                                <a href="<?php echo base_url(); ?>healthcare-provider/billing/bill-loa/manual/<?= $loa_id ?>" class="text-info" data-bs-toggle="tooltip" title="Manual Billing"><i class="mdi mdi-keyboard-close fs-2"></i></a>
                                                            </td>
                                                        </tr>
                                                    <?php } else if($loa['status'] == 'Billed') { ?>
                                                        <tr>
                                                            <td class="fw-bold">
                                                                <mark class="bg-primary text-white ls-1"><?= $loa['loa_no'] ?></mark>
                                                            </td>
                                                            <td class="fw-bold">
                                                                <?= date("m/d/Y", strtotime($loa['request_date'])) ?>
                                                            </td>
                                                            <td class="fw-bold">
                                                                <span class="badge rounded-pill bg-cyan ls-1">
                                                                    <?= $loa['status'] ?>
                                                                </span>
                                                            </td>
                                                            <td class="fw-bold">
                                                                <a href="<?= base_url() ?>healthcare-provider/billing/loa/view-receipt/<?= $this->myhash->hasher($loa['loa_id'], 'encrypt') ?>" class="text-info fw-bold ls-1">
                                                                    View Receipt
                                                                </a>
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
                                                <th class="fw-bold">#</th>
                                                <th class="fw-bold">Request Date</th>
                                                <th class="fw-bold">Status</th>
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
                                                                <span class="badge rounded-pill bg-success ls-1">
                                                                    <?= $noa['status'] ?>
                                                                </span>
                                                            </td>
                                                            <td class="fw-bold">
                                                                <?php $noa_id = $this->myhash->hasher($noa['noa_id'], 'encrypt'); ?>

                                                                <a href="<?php echo base_url(); ?>healthcare-provider/billing/bill-noa/upload-pdf/<?= $noa_id ?>" class="text-danger" data-bs-toggle="tooltip" title="Upload PDF Billing"><i class="mdi mdi-upload fs-2"></i></a>

                                                                <a href="<?php echo base_url(); ?>healthcare-provider/billing/bill-noa/manual/<?= $noa_id ?>" class="text-info" data-bs-toggle="tooltip" title="Manual Billing"><i class="mdi mdi-keyboard-close fs-2"></i></a>
                                                            </td>
                                                        </tr>
                                                        <?php } else if($noa['status'] == 'Billed') { ?>
                                                        <tr>
                                                            <td class="fw-bold">
                                                                <mark class="bg-primary text-white ls-1"><?= $noa['noa_no'] ?></mark>
                                                            </td>
                                                            <td class="fw-bold">
                                                                <?= date("m/d/Y", strtotime($noa['request_date'])) ?>
                                                            </td>
                                                            <td class="fw-bold">
                                                                <span class="badge rounded-pill bg-cyan ls-1">
                                                                    <?= $noa['status'] ?>
                                                                </span>
                                                            </td>
                                                            <td class="fw-bold text-justify">
                                                                <a href="<?= base_url() ?>healthcare-provider/billing/noa/view-receipt/<?= $this->myhash->hasher($noa['noa_id'], 'encrypt') ?>" class="text-info fw-bold ls-1">
                                                                    View Receipt
                                                                </a>
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
                <!-- =====================Re Upload Requests Tab ========================= -->
                <div class="tab-pane fade show" id="re_upload_history" role="tabpanel">
                    <div class="card shadow">
                        <div class="container">
                            <div class="row px-4 py-4">
                                <div class="col-4">
                                    <!-- Member Profile Info -->
                                    <?php include "search_member_profile.php"; ?>                          
                                </div>
                                <div class="col-8">
                                    <table class="table table-hover" id="re_upload_request">
                                        <thead>
                                            <tr>
                                                <th class="fw-bold">#</th>
                                                <th class="fw-bold">Request Date</th>
                                                <th class="fw-bold">Status</th>
                                                <th class="fw-bold">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                if (!empty($re_upload_requests)) :
                                                    $type ="";
                                                    foreach ($re_upload_requests as $r_request) :
                                                        //isset($r_request['noa_no'])?$r_request['noa_no']:$r_request['loa_no']
                                                        if(isset($r_request['noa_no'])){
                                                            $type ="noa";
                                                        }
                                                        if(isset($r_request['loa_no'])){
                                                            $type ="loa";
                                                        }
                                               
                                            ?>
                                        
                                                        <tr>
                                                            <td class="fw-bold">
                                                                <mark class="bg-primary text-white ls-1"><?= isset($r_request['noa_no'])?$r_request['noa_no']:$r_request['loa_no']?></mark>
                                                            </td>
                                                            <td class="fw-bold">
                                                                <?= $r_request['request_date'] ?>
                                                            </td>
                                                            <td class="fw-bold">
                                                                <span class="badge rounded-pill bg-success ls-1">
                                                                    <?= $r_request['status'] ?>
                                                                </span>
                                                            </td>
                                                            <td class="fw-bold">
                                                                <?php $loa_noa_id = $this->myhash->hasher(isset($r_request['noa_id'])? $r_request['noa_id'] : $r_request['loa_id'], 'encrypt'); ?>
                                                                

                                                                <a href="<?php echo base_url(); ?>healthcare-provider/billing/bill-r_request/upload-pdf/<?= $loa_noa_id ?>/<?= $type ?>" class="text-danger" data-bs-toggle="tooltip" title="Upload PDF Billing"><i class="mdi mdi-upload fs-2"></i></a>

                                                                <a href="<?php echo base_url(); ?>healthcare-provider/billing/bill-r_request/manual/<?= $loa_noa_id ?>/<?= $type ?>" class="text-info" data-bs-toggle="tooltip" title="Manual Billing"><i class="mdi mdi-keyboard-close fs-2"></i></a>
                                                            </td>
                                                        </tr>
                                                       
                                            <?php
                                                       
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
   const request = <?php echo json_encode($re_upload_requests); ?>;
    $(document).ready(function() {
        $('#tableLoa').DataTable({
            searching: false,
            columnDefs: [{
                "targets": [2, 3], // numbering column
                "orderable": false, //set not orderable
            }],
        });

        $('#tableNoa').DataTable({
            searching: false,
            columnDefs: [{
                "targets": [2, 3], // numbering column
                "orderable": false, //set not orderable
            }],
        });
        $('#re_upload_request').DataTable({
            searching: false,
            columnDefs: [{
                "targets": [2, 3], // numbering column
                "orderable": false, //set not orderable
            }],
        });
        console.log("request",request);
    });
</script>