<li class="sidebar-item">
  <a class="sidebar-link"
    href="<?php echo base_url(); ?>head-office-iad/dashboard"
    aria-expanded="false">
    <i class="mdi mdi-view-dashboard"></i>
    <span class="hide-menu ls-1">Dashboard</span>
  </a>
</li>

<li class="sidebar-item <?php echo $this->uri->segment(2) == 'billing' ? 'selected' : ''; ?>">
  <a class="sidebar-link"
    href="<?php echo base_url(); ?>head-office-iad/transaction/search"
    aria-expanded="false">
    <i class="mdi mdi-receipt"></i>
    <span class="hide-menu ls-1">Payment History</span>
  </a>
</li>

<li class="sidebar-item <?php echo $this->uri->segment(2) == 'billing' ? 'selected' : ''; ?>">
  <a class="sidebar-link sidebar-link" href="<?php echo base_url(); ?>head-office-iad/transaction/members" aria-expanded="false">
    <i class="mdi mdi-account-multiple"></i>
    <span class="hide-menu ls-1">Employee Member</span>
  </a>
</li>

<li class="sidebar-item <?php echo $this->uri->segment(2) == 'billing' ? 'selected' : ''; ?>">
  <a class="sidebar-link sidebar-link" href="<?php echo base_url(); ?>head-office-iad/transaction/account_setting" aria-expanded="false">
    <i class="mdi mdi-account-settings-variant"></i>
    <span class="hide-menu ls-1">Account Setting</span>
  </a>
</li>
                           
<li class="sidebar-item">
  <a class="sidebar-link"
    href="javascript:void(0)"
    onclick="logout(`<?= base_url() ?>`)"
    aria-expanded="false">
    <i class="mdi mdi-power"></i>
    <span class="hide-menu ls-1">Logout</span>
  </a>
</li> 