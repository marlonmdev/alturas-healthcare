              <li class="sidebar-item">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>member/dashboard"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-view-dashboard"></i>
                  <span class="hide-menu ls-1">Dashboard</span>
                </a>
              </li>

              <li class="sidebar-item">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>member/hmo-policy"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-book-open-page-variant"></i>
                  <span class="hide-menu ls-1">Terms And Conditions</span>
                </a>
              </li>

              <li class="sidebar-item">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>member/healthcare-providers"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-hospital-building"></i>
                  <span class="hide-menu ls-1">Healthcare Providers</span>
                </a>
              </li>

              <li class="sidebar-item 
              <?php echo $this->uri->segment(2) == 'request-emergency-loa' ? 'selected' : ''; ?>
              ">
                <a
                  class="sidebar-link has-arrow"
                  href="javascript:void(0)"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-file-chart"></i>
                  <span class="hide-menu ls-1">Emergency LOA</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="<?php echo base_url(); ?>member/request-emergency-loa" class="sidebar-link"
                      ><i class="mdi mdi-note-plus"></i
                      ><span class="hide-menu ls-1">Request Emerg LOA</span>
                    </a>
                  </li>
                </ul>
              </li>

              <li class="sidebar-item 
              <?php echo $this->uri->segment(2) == 'requested-loa' ? 'selected' : ''; ?>
              ">
                <a
                  class="sidebar-link has-arrow"
                  href="javascript:void(0)"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-file-document"></i>
                  <span class="hide-menu ls-1">LOA</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="<?php echo base_url(); ?>member/request-loa" class="sidebar-link"
                      ><i class="mdi mdi-note-plus"></i
                      ><span class="hide-menu ls-1">Request LOA</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="<?php echo base_url(); ?>member/requested-loa/pending" class="sidebar-link"><i class="mdi mdi-note-outline"></i
                      ><span class="hide-menu ls-1">Requested LOA</span>
                    </a>
                  </li>
                </ul>
              </li>

              <li class="sidebar-item 
              <?php echo $this->uri->segment(2) == 'requested-noa' ? 'selected' : ''; ?>
              ">
                <a
                  class="sidebar-link has-arrow"
                  href="javascript:void(0)"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-file-chart"></i>
                  <span class="hide-menu ls-1">NOA</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="<?php echo base_url(); ?>member/request-noa" class="sidebar-link"
                      ><i class="mdi mdi-note-plus"></i
                      ><span class="hide-menu ls-1">Request NOA</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="<?php echo base_url(); ?>member/requested-noa/pending" class="sidebar-link"
                      ><i class="mdi mdi-note-outline"></i
                      ><span class="hide-menu ls-1">Requested NOA</span>
                    </a>
                  </li>
                </ul>
              </li>

              <li class="sidebar-item 
              <?php echo $this->uri->segment(2) == 'personal-charges' ? 'selected' : ''; ?>
              ">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>member/personal-charges"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-receipt"></i>
                  <span class="hide-menu ls-1">Personal Charges</span>
                </a>
              </li>

              <li class="sidebar-item 
              <?php echo $this->uri->segment(2) == 'mbl-history' ? 'selected' : ''; ?>
              ">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>member/mbl-history/loa"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-receipt"></i>
                  <span class="hide-menu ls-1">MBL History</span>
                </a>
              </li>
              
              <li class="sidebar-item">
                <a
                  class="sidebar-link has-arrow"
                  href="javascript:void(0)"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-account-box"></i>
                  <span class="hide-menu ls-1">My Account</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="<?php echo base_url() . 'member/profile' ?>" class="sidebar-link"
                      ><i class="mdi mdi-account"></i
                      ><span class="hide-menu ls-1">Profile</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <!-- $account_settings_url is defined in the header.php file -->
                    <a href="<?php echo $account_settings_url; ?>" class="sidebar-link"
                      ><i class="mdi mdi-settings"></i
                      ><span class="hide-menu ls-1">Account Settings</span>
                    </a>
                  </li>
                </ul>
              </li>
              
              <li class="sidebar-item">
                <a
                  class="sidebar-link"
                  href="javascript:void(0)"
                  onclick="logout(`<?= base_url() ?>`)"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-power"></i>
                  <span class="hide-menu ls-1">Logout</span>
                </a>
              </li> 