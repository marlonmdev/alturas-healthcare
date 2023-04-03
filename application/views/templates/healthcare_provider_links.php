<li class="sidebar-item">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>healthcare-provider/dashboard"
                  aria-expanded="false"
                  >
                    <i class="mdi mdi-view-dashboard"></i>
                    <span class="hide-menu ls-1">Dashboard</span>
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
                  <i class="mdi mdi-file-document"></i>
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
                  <i class="mdi mdi-file-chart"></i>
                  <span class="hide-menu ls-1">NOA</span>
                </a>
              </li>

              <li class="sidebar-item
              <?php echo $this->uri->segment(2) == "billing" ? "selected" : ""; ?>
              ">
                <a
                  class="sidebar-link has-arrow"
                  href="javascript:void(0)"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-file-check"></i>
                  <span class="hide-menu ls-1">Billing</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="<?php echo base_url(); ?>healthcare-provider/billing" class="sidebar-link"
                      ><i class="mdi mdi-receipt"></i
                      ><span class="hide-menu ls-1">Manual Input</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="<?php echo base_url(); ?>healthcare-provider/billing/upload-texfile" class="sidebar-link"
                      ><i class="mdi mdi-upload"></i
                      ><span class="hide-menu ls-1">Upload Textfile</span>
                    </a>
                  </li>
                </ul>
              </li>

              <li class="sidebar-item">
                <a
                  class="sidebar-link"
                  href="<?php echo $account_settings_url; ?>"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-settings"></i>
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
                  <i class="mdi mdi-power"></i>
                  <span class="hide-menu ls-1">Logout</span>
                </a>
              </li> 