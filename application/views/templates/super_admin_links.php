              <li class="sidebar-item">
                <a
                  class="sidebar-link sidebar-link"
                  href="<?php echo base_url(); ?>super-admin/dashboard"
                  aria-expanded="false"
                  ><i class="mdi mdi-view-dashboard"></i
                  ><span class="hide-menu">Dashboard</span>
                </a>
              </li>       

              <li class="sidebar-item">
                <a
                  class="sidebar-link sidebar-link"
                  href="<?php echo base_url(); ?>super-admin/healthcare-providers"
                  aria-expanded="false"
                  ><i class="mdi mdi-hospital-building"></i
                  ><span class="hide-menu">Healthcare Providers</span>
                </a>
              </li>

              <li class="sidebar-item 
              <?php echo ($this->uri->segment(2) == 'members' || $this->uri->segment(2) == 'member') ? 'selected' : ''; ?>
              ">
                <a
                  class="sidebar-link sidebar-link"
                  href="<?php echo base_url(); ?>super-admin/members"
                  aria-expanded="false"
                  ><i class="mdi mdi-account-multiple"></i
                  ><span class="hide-menu">Members</span>
                </a>
              </li>

              <li class="sidebar-item
              <?php echo $this->uri->segment(2) == 'loa' ? 'selected' : ''; ?>
              ">
                <a
                  class="sidebar-link sidebar-link"
                  href="<?php echo base_url(); ?>super-admin/loa/requests-list"
                  aria-expanded="false"
                  ><i class="mdi mdi-file-document"></i
                  ><span class="hide-menu">LOA</span>
                </a>
              </li>

              <li class="sidebar-item 
              <?php echo $this->uri->segment(2) == 'noa' ? 'selected' : ''; ?>
              ">
                <a
                  class="sidebar-link has-arrow"
                  href="javascript:void(0)"
                  aria-expanded="false"
                  ><i class="mdi mdi-file-chart"></i
                  ><span class="hide-menu">NOA</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="<?php echo base_url(); ?>super-admin/noa/request-noa" class="sidebar-link"
                      ><i class="mdi mdi-note-plus"></i
                      ><span class="hide-menu">NOA Requisition</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="<?php echo base_url(); ?>super-admin/noa/requests-list" class="sidebar-link"
                      ><i class="mdi mdi-note-outline"></i
                      ><span class="hide-menu">NOA Request List</span>
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
                  ><i class="mdi mdi-wrench"></i
                  ><span class="hide-menu">Setup</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="<?php echo base_url(); ?>super-admin/setup/healthcare-providers" class="sidebar-link"
                      ><i class="mdi mdi-hospital-building"></i
                      ><span class="hide-menu">HealthCare Providers</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="<?php echo base_url(); ?>super-admin/setup/company-doctors" class="sidebar-link"
                      ><i class="mdi mdi-account-star-variant"></i
                      ><span class="hide-menu">Company Doctors</span>
                    </a>
                  </li>
                   <li class="sidebar-item">
                    <a href="<?php echo base_url(); ?>super-admin/setup/cost-types" class="sidebar-link"
                      ><i class="mdi mdi-code-string"></i
                      ><span class="hide-menu">Cost Types</span>
                    </a>
                  </li>
                </ul>
              </li>   

              <li class="sidebar-item">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>super-admin/accounts"
                  aria-expanded="false"
                  ><i class="mdi mdi-account-key"></i
                  ><span class="hide-menu">User Accounts</span>
                </a>
              </li> 

              <li class="sidebar-item">
                 <!-- $account_settings_url is defined in the header.php file -->
                <a
                  class="sidebar-link"
                  href="<?php echo $account_settings_url; ?>"
                  aria-expanded="false"
                  ><i class="mdi mdi-settings"></i
                  ><span class="hide-menu">Account Settings</span>
                </a>
              </li> 

              <li class="sidebar-item">
                <a
                  class="sidebar-link"
                  href="javascript:void(0)" onclick="showDBBackupModal()"
                  aria-expanded="false"
                  ><i class="mdi mdi-database"></i
                  ><span class="hide-menu">Database Backup</span>
                </a>
              </li> 
              
              <li class="sidebar-item">
                <a
                  class="sidebar-link"
                  href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#logoutModal"
                  aria-expanded="false"
                  ><i class="mdi mdi-power"></i
                  ><span class="hide-menu">Logout</span>
                </a>
              </li> 