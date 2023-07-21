
      <!-- Start of Page wrapper  -->
      <div class="page-wrapper">
        <!-- Bread crumb and right sidebar toggle -->
        <div class="page-breadcrumb">
          <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
              <h3 class="page-title"><i class="mdi mdi-view-quilt"></i> Dashboard</h3>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">Company Doctor</li>
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
          <div class="row mb-2">

            <div class="col-lg-3 col-sm-6">
              <div class="card-box bg-blue">
                <div class="inner">
                  <h3><?= $hcare_prov_count ?></h3>
                  <p>Healthcare Providers</p>
                </div>
                <div class="icon">
                  <i class="mdi mdi-hospital-building" aria-hidden="true"></i>
                </div>
                <a href="<?= base_url() ?>company-doctor/healthcare-providers" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <div class="col-lg-3 col-sm-6">
              <div class="card-box bg-green">
                <div class="inner">
                  <h3><?= $members_count; ?></h3>
                  <p>Total Members</p>
                </div>
                <div class="icon">
                  <i class="mdi mdi-account-multiple" aria-hidden="true"></i>
                </div>
                <a href="<?= base_url() ?>company-doctor/members" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <div class="col-lg-3 col-sm-6">
              <div class="card-box bg-orange">
                <div class="inner">
                  <h3><?= $pending_loa_count ?></h3>
                  <p>Pending LOA Requests</p>
                </div>
                <div class="icon">
                  <i class="mdi mdi-file-document" aria-hidden="true"></i>
                </div>
                <a href="<?= base_url() ?>company-doctor/loa/requests-list" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            
            <div class="col-lg-3 col-sm-6">
              <div class="card-box bg-red">
                <div class="inner">
                  <h3><?= $pending_noa_count ?></h3>
                  <p>Pending NOA Requests</p>
                </div>
                <div class="icon">
                  <i class="mdi mdi-file-chart" aria-hidden="true"></i>
                </div>
                <a href="<?= base_url() ?>company-doctor/noa/requests-list" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
          <!-- End of Row  -->
          </div>

        <!-- End Container fluid  -->
        </div>
      <!-- End Page wrapper  -->
      </div>
    <!-- End Wrapper -->
    </div>
