<!DOCTYPE html>
<html dir="ltr" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="robots" content="noindex,nofollow" />
    <title>Alturas Healthcare</title>
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>assets/images/hmo-logo.png"/>
    <!-- Start Custom CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/matrixDashboard/dist/css/style.min.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/matrixDashboard/dist/css/cards.css" />
    <!-- End of Custom CSS -->
    <!-- Start Vendors CSS -->  
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendors/dataTables/datatables.min.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendors/dropify/css/dropify.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendors/sweetalert2/sweetalert2.min.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendors/jquery-confirm/dist/jquery-confirm.min.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendors/Toastr/build/toastr.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendors/chosen/chosen.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendors/tagify/tagify.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendors/photoviewer/dist/photoviewer.min.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendors/flatpickr/flatpickr.min.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendors/flatpickr/themes/confetti.css" />
    <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendors/multi-select-tag-main/src/css/multi-select-tag.css" /> -->
    <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendors/pdfjs/web/viewer.css" /> -->
    <!-- End of Vendors CSS -->

    <!-- Script Tags -->
    <!-- <script src="<?php echo base_url(); ?>assets/vendors/multi-select-tag-main/src/js/multi-select-tag.js" ></script> -->
    <script src="<?php echo base_url(); ?>assets/vendors/jquery/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendors/chosen/chosen.jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendors/qrcodejs/qrcode.min.js" defer></script>
    <script src="<?php echo base_url(); ?>assets/vendors/jsbarcode/jsBarcode.all.min.js" defer></script>
    <script src="<?php echo base_url(); ?>assets/vendors/printThis/printThis.js" defer></script>
    <script src="<?php echo base_url(); ?>assets/vendors/html2canvas/html2canvas.js" defer></script>
    <script src="<?php echo base_url(); ?>assets/vendors/photoviewer/dist/photoviewer.min.js" defer></script>
    <script src="<?php echo base_url(); ?>assets/vendors/Toastr/build/toastr.min.js" defer></script>
  </head>
  
  <body>
    <!-- Start of Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModal" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title ls-2" id="logoutModal"><strong>Logout Confirmation</strong></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body text-center fs-4">Are you sure you want to logout?</div>
          <div class="modal-footer">
            <a class="btn btn-danger" href="<?php echo base_url(); ?>logout">Logout</a>
            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
    <!-- End of Logout Modal-->

    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full" data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full"> 

      <!-- Start of DB Backup Modal-->
      <div class="modal fade" id="dbBackupModal" tabindex="-1" role="dialog" aria-labelledby="dbBackupModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title ls-2" id="dbBackupModal"><strong>Database Backup</strong></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
              </button>
            </div>
            <div class="modal-body text-center fs-4">
              <form method="POST" action="<?php echo base_url(); ?>super-admin/database-backup" id="dbBackupForm">
                <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
                <div class="form-group mb-3">
                  <input type="text" class="form-control" name="backup-name" id="backup-name" placeholder="Enter File Name" required>
                </div>

                <button type="submit" class="btn btn-primary me-1"><i class="mdi mdi-content-save"></i> Submit</button>
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal"><i class="mdi mdi-close-box"></i> Close</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- End of DB Backup Modal-->

      <!-- Topbar header -->
      <header class="topbar" data-navbarbg="skin5">
        <nav class="navbar top-navbar navbar-expand-md navbar-dark">
          <div class="navbar-header">
            <!-- Logo -->
            <?php $homepage = base_url().$user_role.'/dashboard'; ?> 
            <a class="navbar-brand" href="<?= $homepage ?>">
              <!-- Logo icon -->
              <b class="">
                <img
                  src="<?php echo base_url(); ?>assets/images/HC_logo.png"
                  alt="Logo"
                  class="light-logo"
                  width="90"
                  height="auto"
                />
              </b>
            </a>
            <!-- End Logo -->
            
            <!-- Toggle which is visible on mobile only -->
            <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
          </div>
          <!-- End Logo -->

          <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
            <!-- toggle and nav items -->
            <ul class="navbar-nav float-start me-auto">
              <li class="nav-item d-none d-md-block">
                <a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)" data-sidebartype="mini-sidebar">
                  <i class="mdi mdi-menu font-24"></i>
                </a>
              </li>

             
            </ul>
            <!-- Right side toggle and nav items -->
            <ul class="navbar-nav float-end">
           

              <li class="nav-item dropdown">
                <strong class="nav-link ls-1">
                  <?php
                    $role = str_replace("-", " ", $user_role); 
                    echo $this->session->userdata('fullname').' <span class="text-warning">[ '.ucwords($role).' ]</span>';
                  ?>
                </strong>
              </li>

              <!-- User profile -->
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <img src="<?php echo base_url(); ?>assets/images/default.png" alt="user" class="rounded-circle" width="31"/>
                </a>

                <ul class="dropdown-menu dropdown-menu-end user-dd animated" aria-labelledby="navbarDropdown">
                  <!-- My Profile Link -->
                  <?php if ($this->uri->segment(1) == 'member') : ?>
                    <a class="dropdown-item" href="<?php echo base_url() . 'member/profile' ?>">
                      <i class="mdi mdi-account me-1 ms-1"></i> My Profile
                    </a>
                    <div class="dropdown-divider"></div>
                  <?php endif; ?>
                  <!-- Account Setting Link -->
                  <?php
                    $position = $this->uri->segment(1);
                    $account_settings_url = base_url() . '' . $position . '/account-settings';
                  ?>
                  
                  <a class="dropdown-item" href="<?php echo $account_settings_url; ?>"
                    ><i class="mdi mdi-settings me-1 ms-1"></i> Account
                    Setting</a
                  >
                  <div class="dropdown-divider"></div>
                  <!-- Logout Link -->
                  <a class="dropdown-item" href="javascript:void(0)"
                    onclick="logout(`<?= base_url() ?>`)">
                     <i class="mdi mdi-power me-1 ms-1"></i> Logout
                  </a>
                </ul>
              </li>
              <!-- User profile and search -->
            </ul>
          </div>
        </nav>
      </header>
      <!-- End Topbar header -->
      <!-- Left Sidebar  -->
      <aside class="left-sidebar" data-sidebarbg="skin5">
        <!-- Sidebar scroll-->
        <div class="scroll-sidebar">
          <!-- Sidebar navigation-->
          <nav class="sidebar-nav">
            <ul id="sidebarnav" class="pt-3">
                <?php
                  $position = $this->uri->segment(1);
                  switch ($position) {
                    case "member":
                      include "member_links.php";
                      break;
                    case "healthcare-coordinator":
                      include "healthcare_coordinator_links.php";
                      break;
                    case "company-doctor":
                      include "company_doctor_links.php";
                      break;
                    case "super-admin":
                      include "super_admin_links.php";
                      break;
                    case "healthcare-provider":
                      include "healthcare_provider_links.php";
                      break;
                    case "hc-provider-front-desk":
                      include "hc_provider_front_desk_links.php";
                      break;
                    case "hc-provider-accounting":
                      include "hc_provider_accounting_links.php";
                      break;
                    case "head-office-accounting":
                      include "ho_accounting_links.php";
                      break;
                    case "head-office-iad":
                      include "ho_iad_nav_links.php";
                      break;
                  }
                ?>
            </ul>
          </nav>
          <!-- End Sidebar navigation -->
        </div>
        <!-- End Sidebar scroll-->
      </aside>
      <!-- End Left Sidebar  -->
     
