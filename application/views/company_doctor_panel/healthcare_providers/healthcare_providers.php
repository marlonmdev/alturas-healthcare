      <!-- Start of Page Wrapper -->
      <div class="page-wrapper">
        <!-- Bread crumb and right sidebar toggle -->
        <div class="page-breadcrumb">
          <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
              <h4 class="page-title ls-2">Healthcare Providers</h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">Company Doctor</li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Healthcare Providers
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
          <div class="row mt-2">

          <?php
            if (!empty($hospitals)) :
          ?>
            <div class="mb-3">
              <h2>Hospitals<i class="mdi mdi-arrow-right-bold"></i><strong class="text-danger"><?= $hospitals_count ?></strong></h2>
              <div class="border border-2 border-dark"></div>
            </div>
            <?php
            foreach ($hospitals as $hospital) :
            ?>
              <!-- Hospital List Loop -->
              <div class="col-md-6 col-lg-4 col-xlg-4">
                <div class="card card-hover bg-dark" style="min-height:180px">
                  <div class="box text-center">
                    <h1 class="font-light text-white">
                      <i class="mdi mdi-hospital-building fs-1 text-warning"></i>
                    </h1>
                    <h4 class="text-white"><?php echo $hospital['hp_name']; ?></h4>
                    <h6 class="text-danger"><?php echo $hospital['hp_address']; ?></h6>
                    <h6 class="text-warning">Contact #: <?php echo $hospital['hp_contact']; ?></h6>
                  </div>
                </div>
              </div>
              <!-- Hospital List Loop -->
          <?php
            endforeach;
          endif;
          ?>
        </div>

        <div class="row">
          <?php
          if (!empty($labs)) :
          ?>
            <div class="mb-3">
              <h2>Laboratories<i class="mdi mdi-arrow-right-bold"></i><strong class="text-danger"><?= $labs_count ?></strong></h2>
              <div class="border border-2 border-dark"></div>
            </div>
            <?php
            foreach ($labs as $lab) :
            ?>
              <!-- Laboratory List Loop -->
              <div class="col-md-6 col-lg-4 col-xlg-4">
                <div class="card card-hover bg-dark" style="min-height:180px">
                  <div class="box text-center">
                    <h1 class="font-light text-white">
                      <i class="mdi mdi-hospital-building fs-1 text-warning"></i>
                    </h1>
                    <h4 class="text-white"><?php echo $lab['hp_name']; ?></h4>
                    <h6 class="text-danger"><?php echo $lab['hp_address']; ?></h6>
                    <h6 class="text-warning">Contact #: <?php echo $lab['hp_contact']; ?></h6>
                  </div>
                </div>
              </div>
              <!-- Laboratory List Loop -->
          <?php
            endforeach;
          endif;
          ?>
        </div>

        <div class="row">
          <?php
          if (!empty($clinics)) :
          ?>
            <div class="mb-3">
              <h1>Clinics<i class="mdi mdi-arrow-right-bold"></i><strong class="text-danger"><?= $clinics_count ?></strong></h1>
              <div class="border border-2 border-dark"></div>
            </div>
            <?php
            foreach ($clinics as $clinic) :
            ?>
              <!-- Clinic List Loop -->
              <div class="col-md-6 col-lg-4 col-xlg-4">
                <div class="card card-hover bg-dark" style="min-height:180px">
                  <div class="box text-center">
                    <h1 class="font-light text-white">
                      <i class="mdi mdi-hospital-building fs-1 text-warning"></i>
                    </h1>
                    <h4 class="text-white"><?php echo $lab['hp_name']; ?></h4>
                    <h6 class="text-danger"><?php echo $lab['hp_address']; ?></h6>
                    <h6 class="text-warning">Contact #: <?php echo $lab['hp_contact']; ?></h6>
                  </div>
                </div>
              </div>
              <!-- Clinic List Loop -->
          <?php
            endforeach;
          endif;
          ?>

      </div>
      <!-- End Container fluid  -->
      </div>
    <!-- End Page wrapper  -->
    </div>
  <!-- End Wrapper -->
  </div>