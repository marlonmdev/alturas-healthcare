
      <!-- Start of Page Wrapper -->
      <div class="page-wrapper">
        <!-- Bread crumb and right sidebar toggle -->
        <div class="page-breadcrumb">
          <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
              <h4 class="page-title ls-2">Dashboard</h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">Healthcare Coordinator</li>
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
                  <h3><?php echo $hcare_prov_count; ?></h3>
                  <p>Healthcare Providers</p>
                </div>
                <div class="icon">
                  <i class="mdi mdi-hospital-building" aria-hidden="true"></i>
                </div>
                <a href="<?php echo base_url(); ?>healthcare-coordinator/healthcare-providers" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <div class="col-lg-3 col-sm-6">
              <div class="card-box bg-green">
                <div class="inner">
                  <h3><?php echo $members_count; ?></h3>
                  <p>Total Members</p>
                </div>
                <div class="icon">
                  <i class="mdi mdi-account-multiple" aria-hidden="true"></i>
                </div>
                <a href="<?php echo base_url(); ?>healthcare-coordinator/members/approved" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <div class="col-lg-3 col-sm-6">
              <div class="card-box bg-orange">
                <div class="inner">
                  <h3><?php echo $pending_loa_count; ?></h3>
                  <p>Pending LOA Requests</p>
                </div>
                <div class="icon">
                  <i class="mdi mdi-file-document" aria-hidden="true"></i>
                </div>
                <a href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            
            <div class="col-lg-3 col-sm-6">
              <div class="card-box bg-red">
                <div class="inner">
                  <h3><?php echo $pending_noa_count; ?></h3>
                  <p>Pending NOA Requests</p>
                </div>
                <div class="icon">
                  <i class="mdi mdi-file-chart" aria-hidden="true"></i>
                </div>
                <a href="<?php echo base_url(); ?>healthcare-coordinator/noa/requests-list" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>

          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="border border-2 border-secondary"></div>
              <h4 class="page-title ls-2 mt-3 mb-4">Doctor's Availability</h4>
              <div class="row">
                <?php if (!empty($doctors)) : ?>
                  <?php foreach ($doctors as $doc) : ?>
                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6 mb-3">
                      <div class="bg-light p-30 text-white text-center shadow">
                        <img src="<?php echo base_url(); ?>assets/images/company-doctor.svg" class="card-img-top img-responsive mb-3" alt="User Image" style="width:80px;height:auto;">
                  
                        <h5 class="text-dark mb-0 mt-1">
                          <?php echo $doc['doctor_name']; ?>
                        </h5>
                        <strong style="letter-spacing:2px">
                          <?php echo ($doc['online'] == 1) ? '<span class="text-success">Online</span>' : '<span class="text-warning">Offline</span>'; ?>
                        </strong>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
            </div>
           <!-- End Row  -->  
          </div>
        <!-- End Container fluid  -->
        </div>
      <!-- End Page wrapper  -->
      </div>
    <!-- End Wrapper -->
    </div>
