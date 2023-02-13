              <li class="sidebar-item">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>company-doctor/dashboard"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-view-dashboard text-warning"></i>
                  <span class="hide-menu ls-1">Dashboard</span>
                </a>
              </li>       

              <li class="sidebar-item">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>company-doctor/healthcare-providers"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-hospital-building text-warning"></i>
                  <span class="hide-menu ls-1">Healthcare Providers</span>
                </a>
              </li>

              <li class="sidebar-item 
              <?php echo ($this->uri->segment(2) == 'members' || $this->uri->segment(2) == 'member') ? 'selected' : ''; ?>
              ">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>company-doctor/members"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-account-multiple text-warning"></i>
                  <span class="hide-menu ls-1">Members</span>
                </a>
              </li>

              <li class="sidebar-item 
              <?php echo $this->uri->segment(2) == 'loa' ? 'selected' : ''; ?>
              ">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>company-doctor/loa/requests-list"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-file-document text-warning"></i>
                  <span class="hide-menu ls-1">LOA Requests</span>
                </a>
              </li>

              <li class="sidebar-item 
              <?php echo $this->uri->segment(2) == 'noa' ? 'selected' : ''; ?>
              ">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>company-doctor/noa/requests-list"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-file-chart text-warning"></i>
                  <span class="hide-menu ls-1">NOA Requests</span>
                </a>
              </li>   

              <li class="sidebar-item">
                 <!-- $account_settings_url is defined in the header.php file -->
                <a
                  class="sidebar-link"
                  href="<?php echo $account_settings_url; ?>"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-settings text-warning"></i>
                  <span class="hide-menu ls-1">Account Settings</span>
                </a>
              </li> 
              
              <li class="sidebar-item">
                <a
                  class="sidebar-link"
                  href="javascript:void(0)"
                  onclick="logout(`<?= base_url() ?>`)"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-power text-warning"></i>
                  <span class="hide-menu ls-1">Logout</span>
                </a>
              </li> 