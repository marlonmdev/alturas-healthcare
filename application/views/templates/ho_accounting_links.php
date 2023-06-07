<li class="sidebar-item">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>head-office-accounting/dashboard"
                  aria-expanded="false"
                  ><i class="mdi mdi-view-dashboard"></i
                  ><span class="hide-menu ls-1">Dashboard</span>
                </a>
              </li>

              <li class="sidebar-item">
              <!-- <?php echo ($this->uri->segment(2) == 'bill') ? 'selected' : ''; ?> -->
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>head-office-accounting/bill/billing-list/billed-loa-noa"
                  aria-expanded="false"
                  ><i class="mdi mdi-file-check"></i
                  ><span class="hide-menu ls-1">Billing List</span>
                </a>
              </li>

              <li class="sidebar-item">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>head-office-accounting/payment_history"
                  aria-expanded="false"
                  ><i class="mdi mdi-receipt"></i
                  ><span class="hide-menu ls-1">Payment History</span>
                </a>
              </li>

              <li class="sidebar-item">
                <?php echo ($this->uri->segment(3) == 'charging') ? 'selected' : '' ; ?>
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>head-office-accounting/charging/business-unit"
                  aria-expanded="false"
                  ><i class="mdi mdi-file-multiple"></i
                  ><span class="hide-menu ls-1">Business Unit Charging</span>
                </a>
              </li>

              <li class="sidebar-item">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>head-office-accounting/loa-request-list/loa-approved"
                  aria-expanded="false"
                  ><i class="mdi mdi-file-document"></i
                  ><span class="hide-menu ls-1">LOA</span>
                </a>
              </li>

               <li class="sidebar-item">
                <?php echo ($this->uri->segment(3) == 'noa-request-list') ? 'selected' : '' ; ?>
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>head-office-accounting/noa-request-list/noa-approved"
                  aria-expanded="false"
                  ><i class="mdi mdi-file"></i
                  ><span class="hide-menu ls-1">NOA</span>
                </a>
              </li>
<!-- 
              <li class="sidebar-item">
                <?php echo ($this->uri->segment(3) == 'reports') ? 'selected' : '' ; ?>
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>head-office-accounting/reports"
                  aria-expanded="false"
                  ><i class="mdi mdi-file-chart"></i
                  ><span class="hide-menu ls-1">Reports</span>
                </a>
              </li> -->

              <li class="sidebar-item">
                <a
                  class="sidebar-link"
                  href="<?php echo $account_settings_url; ?>"
                  aria-expanded="false"
                  ><i class="mdi mdi-settings"></i
                  ><span class="hide-menu ls-1">Account Settings</span>
                </a>
              </li>
              
              <li class="sidebar-item">
                <a
                  class="sidebar-link"
                  href="javascript:void(0)"
                  onclick="logout(`<?= base_url() ?>`)"
                  aria-expanded="false"
                  ><i class="mdi mdi-power"></i
                  ><span class="hide-menu ls-1">Logout</span>
                </a>
              </li> 