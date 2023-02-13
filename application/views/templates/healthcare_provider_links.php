              <li class="sidebar-item">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>healthcare-provider/dashboard"
                  aria-expanded="false"
                  >
                    <i class="mdi mdi-view-dashboard text-warning"></i>
                    <span class="hide-menu ls-1">Dashboard</span>
                </a>
              </li>

              <li class="sidebar-item
              <?php echo $this->uri->segment(2) == "billing" ? "selected" : ""; ?>
              ">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>healthcare-provider/billing"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-receipt text-warning"></i>
                  <span class="hide-menu ls-1">Billing</span>
                </a>
              </li>

               <li class="sidebar-item
                <?php echo $this->uri->segment(2) == "loa-requests" ? "selected" : ""; ?>
               ">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>healthcare-provider/loa-requests/pending"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-file-document text-warning"></i>
                  <span class="hide-menu ls-1">LOA</span>
                </a>
              </li>

               <li class="sidebar-item
                <?php echo $this->uri->segment(2) == "noa-requests" ? "selected" : ""; ?>
               ">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>healthcare-provider/noa-requests/pending"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-file-chart text-warning"></i>
                  <span class="hide-menu ls-1">NOA</span>
                </a>
              </li>

              <li class="sidebar-item">
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