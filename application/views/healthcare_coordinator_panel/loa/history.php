<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">History of Billing</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-coordinator/bill/requests-list/billed" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">FINAL BILLING</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link " href="<?php echo base_url(); ?>healthcare-coordinator/bill/requests-list/for-charging" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">BILLING STATEMENT</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link active" href="<?php echo base_url(); ?>healthcare-coordinator/history" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">HISTORY</span>
            </a>
          </li>
        </ul>

        <div class="row">
          <div class="col-lg-6 d-flex justify-content-start align-items-center">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text bg-dark text-white"><i class="mdi mdi-filter"></i></span>
              </div>
              <select class="form-select fw-bold input-group-select" name="select-month" id="select-month">
                <option value="">Select Month</option>
              </select>
              <select class="form-select fw-bold input-group-select" name="select-year" id="select-year">
                <option value="">Select Year</option>
              </select>
            </div>
          </div>

          <div class="col-lg-6 d-flex justify-content-end align-items-center">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text bg-dark text-white"><i class="mdi mdi-filter"></i></span>
              </div>
              <select class="form-select fw-bold" name="matched-hospital-filter" id="matched-hospital-filter">
                <option value="">Select Hospital</option>
                <?php foreach($hcproviders as $option) : ?>
                  <option value="<?php echo $option['hp_id']; ?>"><?php echo $option['hp_name']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>

        <div class="card shadow">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-responsive" id="matchedLoaTable">
                <thead class="fs-6" style="background-color:#00538C">
                  <tr>
                    <th class="fw-bold" style="color: white;">NAME OF PATIENT</th>
                    <th class="fw-bold" style="color: white;">ACCOUNT #</th>
                    <th class="fw-bold" style="color: white;">CHEQUE NUMBER</th>
                    <th class="fw-bold" style="color: white;">CHEQUE DATE</th>
                    <th class="fw-bold" style="color: white;">BANK</th>
                    <th class="fw-bold" style="color: white;">STATUS</th>
                    <th class="fw-bold" style="color: white;">ACTION</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <?php include 'view_completed_loa_details.php'; ?>
      </div>
    </div>
    <?php include 'performed_loa_info_modal.php'; ?>
  </div>
  <?php include 'view_performed_consult_loa.php'; ?>
</div>

<style>
  .input-group-select {
    width: 100%;
    max-width: 200px; /* Adjust the maximum width as needed */
  }
</style>