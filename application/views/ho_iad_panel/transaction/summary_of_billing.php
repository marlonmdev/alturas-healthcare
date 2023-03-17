<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">Summary of Billing</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item">Internal Audit Department</li>
								<li class="breadcrumb-item active" aria-current="page">Billing</li>
							</ol>
            </nav>
          </div>
        </div>
    	</div>
    </div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 mb-3">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#sop" type="button" role="tab" aria-controls="home" aria-selected="true"><strong>Summary of Billing</strong></button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link " id="profile-tab" data-bs-toggle="tab" data-bs-target="#noa-requests" type="button" role="tab" aria-controls="profile" aria-selected="false"><strong>Payment Details</strong></button>
            </li>
          </ul>
        </div>

        <!-- ================== Summary of Billing Tabpane ==================== -->
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="sop" role="tabpanel">
            <div class="card shadow">
              <div class="container">
                <div class="row px-4 py-4">
                  <!-- Member Profile Info -->
									<div class="col-4">
										<?php include "search_member_profile.php"; ?>                             
									</div>
                  <div class="col-8">
                    <table class="table table-hover" id="sob">
                      <thead>
                        <tr>
													<th class="fw-bold">Billing #</th>
                          <th class="fw-bold">Payment #</th>
													<th class="fw-bold">Transaction Date</th>
													<th class="fw-bold">Request Type</th>
													<th class="fw-bold">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          if (!empty($billing)) :
                            foreach ($billing as $bill) :
                              if($bill['status'] == 'Paid'){
                        ?>
                                <tr>
                                  <td class="fw-bold">
                                    <mark class="bg-primary text-white ls-1"><?= $bill['billing_no'] ?></mark>
                                  </td>

                                  <td class="fw-bold">
                                    <mark class="bg-primary text-white ls-1"><?= $bill['payment_no'] ?></mark>
                                  </td>

                                  <td class="fw-bold">
                                    <?= date("m/d/Y", strtotime($bill['billed_on'])) ?>
                                  </td>

                                  <td class="fw-bold">
                                    <?= !empty($bill['loa_id']) ? 'LOA' : 'NOA '?>
                                  </td>

                                  <?php
                                    $req_type = !empty($bill['loa_id']) ? 'loa' : 'noa';
                                  ?>

                                  <td class="fw-bold">
                                    <form method="POST" action="<?= base_url() ?>head-office-iad/transaction/<?= $req_type ?>/view_receipt/<?= $this->myhash->hasher($bill['billing_id'], 'encrypt') ?>">
																			<input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">

																			<input type="hidden" name="emp_id" value="<?= $bill['emp_id'] ?>">

																			<button type="submit" class="fw-bold ls-1 text-danger border-0" data-bs-toggle="tooltip" title="Click to proceed to Billing" style="background-color: transparent;"> View Receipt</button>
                                    </form>
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
					<!-- ===================== Payment Details Tabpane ========================= -->
					<div class="tab-pane fade show" id="noa-requests" role="tabpanel">
						<div class="card shadow">
							<div class="container">
								<div class="row px-4 py-4">
									<!-- Member Profile Info -->
									<div class="col-4">
										<?php include "search_member_profile.php"; ?>                          
									</div>
									<div class="col-8">
										<table class="table table-hover" id="payment_details">
											<thead>
												<tr>
													<th class="fw-bold">Payment Details</th>
													<th class="fw-bold">Request Date</th>
													<th class="fw-bold">Status</th>
													<th class="fw-bold">Action</th>
												</tr>
											</thead>
											<tbody>
																							
											</tbody>
										</table>
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
<script>
  $(document).ready(function() {
    $('#sob').DataTable({
      searching: false,
      columnDefs: [{
        "targets": [2, 3], // numbering column
        "orderable": false, //set not orderable
      }],
    });
    $('#payment_details').DataTable({
			searching: false,
			columnDefs: [{
				"targets": [2, 3], // numbering column
				"orderable": false, //set not orderable
      }],
    });
  });
</script>