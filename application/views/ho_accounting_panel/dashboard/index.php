      <!-- Start of Page wrapper  -->
      <div class="page-wrapper">
        <!-- Bread crumb and right sidebar toggle -->
        <div class="page-breadcrumb">
          <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
              <h4 class="page-title"><i class="mdi mdi-view-dashboard"></i> Dashboard</h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">Head office Accounting</li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Dashboard
                    </li>
                  </ol>
                </nav>
              </div>
            </div>
          </div>
        </div>
        <!-- End Bread crumb and right sidebar toggle -->

        <!-- Start of Container fluid  -->
        <div class="container-fluid">
          <div class="row ps-3">
            <div class="row shadow">
              <div class="col-lg-3 col-sm-6">
                <div class="card-box bg-cyan">
                  <div class="inner">
                    <h3><?php echo $billed_count; ?></h3>
                    <p>Billed</p>
                  </div>
                  <div class="icon">
                    <i class="mdi mdi-file-check" aria-hidden="true"></i>
                  </div>
                  <a href="<?php echo base_url() ?>head-office-accounting/bill/billing-list/billed-loa-noa" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>

              <div class="col-lg-3 col-sm-6">
                <div class="card-box bg-green">
                  <div class="inner">
                    <h3><i class="mdi mdi-view-dashboard"></i></h3>
                    <p>Payment History</p>
                  </div>
                  <div class="icon">
                    <i class="mdi mdi-file-document" aria-hidden="true"></i>
                  </div>
                  <a href="<?php echo base_url() ?>head-office-accounting/payment_history" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>

              <div class="col-lg-3 col-sm-6">
                <div class="card-box bg-orange">
                  <div class="inner">
                    <h3><i class="mdi mdi-view-dashboard"></i></h3>
                    <p>Business Unit Charging</p>
                  </div>
                  <div class="icon">
                    <i class="mdi mdi-file" aria-hidden="true"></i>
                  </div>
                  <a href="<?php echo base_url() ?>head-office-accounting/charging/business-unit" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>
              
              <div class="col-lg-3 col-sm-6">
                <div class="card-box bg-red">
                  <div class="inner">
                    <h3><?php echo $noa_count; ?></h3>
                    <p>Approved NOA Requests</p>
                  </div>
                  <div class="icon">
                    <i class="mdi mdi-file-chart" aria-hidden="true"></i>
                  </div>
                  <a href="<?php echo base_url() ?>head-office-accounting/noa-request-list/noa-approved" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>
            </div>

          </div>
        <!-- End Container fluid  -->
        </div>
      <!-- End Page wrapper  -->
      </div>
    <!-- End Wrapper -->
    </div>