<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2"><i class="mdi mdi-file-document-box"></i> Summary of Billing</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item">Internal Audit Department</li>
							<li class="breadcrumb-item active" aria-current="page">SOA</li>
						</ol>
          </nav>
        </div>
    	</div>
    </div>
    <div class="container-fluid"><br>
      <div class="row">
        <div class="col-12 mb-3">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#summary_of_billing" type="button" role="tab" aria-controls="home" aria-selected="true"><strong>Summary of Billing</strong></button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link " id="profile-tab" data-bs-toggle="tab" data-bs-target="#payment_details" type="button" role="tab" aria-controls="profile" aria-selected="false"><strong>Payment Details</strong></button>
            </li>
          </ul>
        </div>

        <!-- ================== Summary of Billing Tabpane ==================== -->
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="summary_of_billing" role="tabpanel">
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
													<th class="fw-bold">Transaction Date</th>
													<th class="fw-bold">Request Type</th>
                          <th class="fw-bold">Total Billing  <br><small class="text-danger">(to company)</small></th>
													<th class="fw-bold">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          if (!empty($billing)) :
                            foreach ($billing as $bill) :
                              if($bill['status'] == 'Billed' || $bill['status'] == 'Payable' || $bill['status'] == 'Payment'){
                        ?>
                          <tr>
                            <td class="fw-bold"><mark class="bg-primary text-white ls-1"><?= $bill['billing_no'] ?></mark></td>
                            <td class="fw-bold"><?= date("m/d/Y", strtotime($bill['billed_on'])) ?></td>
                            <td class="fw-bold"><?= !empty($bill['loa_id']) ? 'LOA' : 'NOA '?></td>
                            <td class="fw-bold"> <?= '&#8369;'.number_format(floatval($bill['company_charge'] + $bill['cash_advance']), 2) ?></td>

                            <?php
                              $req_type = !empty($bill['loa_id']) ? 'loa' : 'noa';
                            ?>

                            <td class="fw-bold">
                              <form method="POST" action="<?= base_url() ?>head-office-iad/transaction/<?= $req_type ?>/view_receipt/<?= $this->myhash->hasher($bill['billing_id'], 'encrypt') ?>">
																<input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">

																<input type="hidden" name="emp_id" value="<?= $bill['emp_id'] ?>">

																<button type="submit" class="fw-bold ls-1 text-danger border-0" data-bs-toggle="tooltip" title="Click to view Billing" style="background-color: transparent;"> View Receipt</button>
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
					<div class="tab-pane fade show" id="payment_details" role="tabpanel">
						<div class="card shadow">
							<div class="container">
								<div class="row px-4 py-4">
									<!-- Member Profile Info -->
									<div class="col-4">
										<?php include "search_member_profile.php"; ?>                          
									</div>
									<div class="col-8">
										<table class="table table-hover" id="pd">
											<thead>
												<tr>
													<th class="fw-bold">Payment #</th>
													<th class="fw-bold">Transaction Date</th>
													<th class="fw-bold">Request Type</th>
                          <th class="fw-bold">Total Paid <br><small class="text-danger">(by company)</small></th>
													<th class="fw-bold">Action</th>
												</tr>
											</thead>
											<tbody>
                        <?php
                          if (!empty($billing)){
                            foreach ($billing as $bill){
                              if($bill['status'] == 'Paid'){
                            
                        ?>
                          <tr>
                            <td class="fw-bold"><mark class="bg-primary text-white ls-1"><?= $bill['billing_no'] ?></mark></td>
                            <td class="fw-bold"><?= date("m/d/Y", strtotime($bill['billed_on'])) ?></td>
                            <td class="fw-bold"><?= !empty($bill['loa_id']) ? 'LOA' : 'NOA '?></td>
                            <td class="fw-bold"> <?= '&#8369;'.number_format(floatval($bill['company_charge'] + $bill['cash_advance']), 2) ?></td>
                            <?php
                              $req_type = !empty($bill['loa_id']) ? 'loa' : 'noa';
                            ?>
                            <td class="fw-bold">
                              <form method="POST" action="<?= base_url() ?>head-office-iad/transaction/<?= $req_type ?>/view_payment_details/<?= $this->myhash->hasher($bill['payment_no'], 'encrypt') ?>">
                                <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">

                                <input type="hidden" name="emp_id" value="<?= $bill['emp_id'] ?>">

                                <?php if(floatval($bill['company_charge'] + $bill['cash_advance']) != 0) { ?>
                                
                                <a href="JavaScript:void(0)" onclick="viewPaymentInfo(<?= '\''. $bill['billing_id'] .'\'' ?>)" class="fw-bold ls-1 text-danger border-0" data-bs-toggle="tooltip" title="Click to view Payment Details" style="background-color: transparent;"> View Details</a>
                                  <?php }else{?>
                                    <span> Patient's Charge</span>
                                  <?php }?>
                              </form>
                            </td>
                          </tr>
                        <?php }}} ?>																							
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
          <?php include 'payment_details.php'; ?>
      	</div>
			</div>
  	</div>
	</div>
</div>

<script>
  const baseUrl = "<?php echo base_url(); ?>";

  $(document).ready(function() {
    $('#sob').DataTable({
      searching: false,
      columnDefs: [{
        "targets": [2, 3],
        "orderable": false,
      }],
    });
    $('#pd').DataTable({
			searching: false,
			columnDefs: [{
				"targets": [2, 3],
				"orderable": false,
      }],
    });
  });

  const viewPaymentInfo = (billing_id) => {
    $.ajax({
      type: 'GET',
      url: `${baseUrl}head-office-iad/transaction/payment-details/${billing_id}`,
      success: function(response){
        const res = JSON.parse(response);
        const {
          token,
          payment_no,
          account_no,
          account_name,
          check_no,
          check_date,
          bank,
          amount_paid,
          type_request
        } = res;

        $('#viewPaymentModal').modal('show');
                    
        $('#payment-num').val(payment_no);
        $('#acc-number').val(account_no);
        $('#acc-name').val(account_name);
        $('#check-number').val(check_no);
        $('#check-date').val(check_date);
        $('#bank').val(bank);
        $('#amount-paid').val(amount_paid);
        $('#type-of-request').val(type_request);
      }
    });
  }
</script>

