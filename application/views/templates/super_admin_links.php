              <li class="sidebar-item">
                <a
                  class="sidebar-link sidebar-link"
                  href="<?php echo base_url(); ?>super-admin/dashboard"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-view-dashboard text-warning"></i>
                  <span class="hide-menu ls-1">Dashboard</span>
                </a>
              </li>       

              <li class="sidebar-item">
                <a
                  class="sidebar-link sidebar-link"
                  href="<?php echo base_url(); ?>super-admin/healthcare-providers"
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
                  class="sidebar-link sidebar-link"
                  href="<?php echo base_url(); ?>super-admin/members"
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
                  class="sidebar-link sidebar-link"
                  href="<?php echo base_url(); ?>super-admin/loa/requests-list"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-file-document text-warning"></i>
                  <span class="hide-menu ls-1">LOA</span>
                </a>
              </li>

              <li class="sidebar-item 
              <?php echo $this->uri->segment(2) == 'noa' ? 'selected' : ''; ?>
              ">
                <a
                  class="sidebar-link has-arrow"
                  href="javascript:void(0)"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-file-chart text-warning"></i>
                  <span class="hide-menu ls-1">NOA</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="<?php echo base_url(); ?>super-admin/noa/request-noa" class="sidebar-link"
                      ><i class="mdi mdi-note-plus"></i
                      ><span class="hide-menu ls-1">NOA Requisition</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="<?php echo base_url(); ?>super-admin/noa/requests-list" class="sidebar-link"
                      ><i class="mdi mdi-note-outline"></i
                      ><span class="hide-menu ls-1">NOA Request List</span>
                    </a>
                  </li>
                </ul>
              </li>   

              <li class="sidebar-item 
               <?php echo $this->uri->segment(2) == 'setup' ? 'selected' : ''; ?>
              ">
                <a
                  class="sidebar-link has-arrow"
                  href="javascript:void(0)"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-wrench text-warning"></i>
                  <span class="hide-menu ls-1">Setup</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="<?php echo base_url(); ?>super-admin/setup/healthcare-providers" class="sidebar-link"
                      ><i class="mdi mdi-hospital-building"></i
                      ><span class="hide-menu ls-1">HealthCare Providers</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="<?php echo base_url(); ?>super-admin/setup/company-doctors" class="sidebar-link"
                      ><i class="mdi mdi-account-star-variant"></i
                      ><span class="hide-menu ls-1">Company Doctors</span>
                    </a>
                  </li>
                   <li class="sidebar-item">
                    <a href="<?php echo base_url(); ?>super-admin/setup/cost-types" class="sidebar-link"
                      ><i class="mdi mdi-code-string"></i
                      ><span class="hide-menu ls-1">Cost Types</span>
                    </a>
                  </li>
                </ul>
              </li>   

              <li class="sidebar-item">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>super-admin/accounts"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-account-key text-warning"></i>
                  <span class="hide-menu ls-1">User Accounts</span>
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
                  href="javascript:void(0)" onclick="showDBBackupModal()"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-database text-warning"></i>
                  <span class="hide-menu ls-1">Database Backup</span>
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