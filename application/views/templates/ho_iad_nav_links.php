<li class="sidebar-item">
  <a class="sidebar-link"
    href="<?php echo base_url(); ?>head-office-iad/dashboard"
    aria-expanded="false">
    <i class="mdi mdi-view-dashboard"></i>
    <span class="hide-menu ls-1">Dashboard</span>
  </a>
</li>

<li class="sidebar-item">
  <a class="sidebar-link"
    href="<?php echo base_url(); ?>member/hmo-policy"
    aria-expanded="false">
    <i class="mdi mdi-book-open-page-variant"></i>
    <span class="hide-menu ls-1">Healthcare Policy</span>
  </a>
</li>

<li class="sidebar-item">
  <a class="sidebar-link"
    href="<?php echo base_url(); ?>member/healthcare-providers"
    aria-expanded="false">
    <i class="mdi mdi-hospital-building"></i>
    <span class="hide-menu ls-1">Healthcare Providers</span>
  </a>
</li>

<li class="sidebar-item <?php echo $this->uri->segment(2) == 'billing' ? 'selected' : ''; ?>">
  <a class="sidebar-link"
    href="<?php echo base_url(); ?>head-office-iad/billing/table"
    aria-expanded="false">
    <i class="mdi mdi-receipt"></i>
    <span class="hide-menu ls-1">Billing</span>
  </a>
</li>
              
<li class="sidebar-item">
  <a class="sidebar-link has-arrow"
    href="javascript:void(0)"
    aria-expanded="false">
    <i class="mdi mdi-account-box"></i>
    <span class="hide-menu ls-1">My Account</span>
  </a>
  <ul aria-expanded="false" class="collapse first-level">
    <li class="sidebar-item">
      <a href="<?php echo base_url() . 'member/profile' ?>" class="sidebar-link">
        <i class="mdi mdi-account"></i>
        <span class="hide-menu ls-1">Profile</span>
      </a>
    </li>
    <li class="sidebar-item">
      <a href="<?php echo $account_settings_url; ?>" class="sidebar-link">
        <i class="mdi mdi-settings"></i>
        <span class="hide-menu ls-1">Account Settings</span>
      </a>
    </li>
  </ul>
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