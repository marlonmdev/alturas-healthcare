<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <a href="<?php echo base_url(); ?>healthcare-coordinator/loa_controller/view_ledger" type="submit" class="btn btn-danger" data-bs-toggle="tooltip" title="Click to Go Back">
          <strong class="ls-2" style="vertical-align:middle"><i class="mdi mdi-arrow-left-bold"></i> Back</strong>
        </a>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">History of Charges</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
      	<div class="card shadow">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-responsive" id="ledgertable">
                <thead class="fs-5"style="background-color:#00538C">
                  <tr>
                    <th class="fw-bold" style="color: white;">NAME OF PATIENT</th>
                    <th class="fw-bold" style="color: white;">BILLING #</th>
                    <th class="fw-bold" style="color: white;">WORK RELATED</th>
                    <th class="fw-bold" style="color: white;">TRANSACTION DATE</th>
                    <th class="fw-bold" style="color: white;">NET BILL</th>
                    <th class="fw-bold" style="color: white;">COMPANY CHARGE</th>
                    <th class="fw-bold" style="color: white;">PERSONAL CHARGE</th>
                    <th class="fw-bold" style="color: white;">HEALTHCARE ADVANCE</th>
                    <th class="fw-bold" style="color: white;">TOTAL</th>
                    <th class="fw-bold" style="color: white;">REMARKS</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    foreach($billing as $key => $ledger){ 
                      // if($ledger['work_related'] == 'Yes'){
                      //   if($ledger['percentage'] == ''){
                      //     $label = 'Work Related';
                      //     $percent = '100';
                      //   }else{
                      //     $label = 'Work Related';
                      //     $percent = $ledger['percentage'];
                      //   }
                      // }else{
                      //   if($ledger['percentage'] == ''){
                      //     $label = 'Non Work-Related';
                      //     $percent = '100';
                      //   }else{
                      //     $ledger = 'Non Work-Related';
                      //     $percent = $ledger['percentage'];
                      //   }
                      // }
                      // $percent_custom = '<span>'.$percent.'% '.$label.'</span>';
                  ?>
                    <tr>
                      <td><?php echo $key === 0 ? $ledger['first_name'].' '.$ledger['middle_name'].' '.$ledger['last_name'] : ''; ?></td>
                      <td><?php echo $ledger['billing_no']; ?></td>
                      <!-- <td><?php echo $percent_custom; ?></td> -->
                      <td><?php echo $ledger['work_related']; ?></td>
                      <td></td>
                      <td><?php echo number_format($ledger['net_bill'], 2); ?></td>
                      <td><?php echo number_format($ledger['company_charge'],2); ?></td>
                      <td><?php echo number_format($ledger['personal_charge'],2); ?></td>
                      <td></td>
                      <td><?php echo number_format($ledger['company_charge'] + $ledger['personal_charge'], 2); ?></td>
                      <td><?php echo $ledger['status']; ?></td>
                    </tr>
                  <?php }?>
                  <tr>
                    <td colspan="8"></td>
                    <td colspan="1" style="text-align: right"><b style="font-size:15px">RUNNING BALANCE:</b></td>
                    <td colspan="4"><b style="font-size:15px">₱ <?php echo number_format($ledger['remaining_balance'],2); ?></b></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
