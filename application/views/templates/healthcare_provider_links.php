              <li class="sidebar-item">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>healthcare-provider/dashboard"
                  aria-expanded="false"
                  ><i class="mdi mdi-view-dashboard"></i
                  ><span class="hide-menu">Dashboard</span>
                </a>
              </li>

              <li class="sidebar-item
              <?php echo $this->uri->segment(2) == "billing" ? "selected" : ""; ?>
              ">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>healthcare-provider/billing/billing-person"
                  aria-expanded="false"
                  ><i class="mdi mdi-receipt"></i
                  ><span class="hide-menu">Billing</span>
                </a>
              </li>

               <li class="sidebar-item">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>healthcare-provider/loa-request-list/loa-pending"
                  aria-expanded="false"
                  ><i class="mdi mdi-file-document"></i
                  ><span class="hide-menu">LOA</span>
                </a>
              </li>

               <li class="sidebar-item">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>healthcare-provider/noa-request-list/noa-pending"
                  aria-expanded="false"
                  ><i class="mdi mdi-file-chart"></i
                  ><span class="hide-menu">NOA</span>
                </a>
              </li>

              <li class="sidebar-item">
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
                  href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#logoutModal"
                  aria-expanded="false"
                  ><i class="mdi mdi-power"></i
                  ><span class="hide-menu">Logout</span>
                </a>
              </li> 