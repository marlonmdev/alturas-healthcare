<li class="sidebar-item">
  <a class="sidebar-link"
    href="<?php echo base_url(); ?>head-office-iad/dashboard"
    aria-expanded="false">
    <i class="mdi mdi-view-dashboard"></i>
    <span class="hide-menu ls-1">Dashboard</span>
  </a>
</li>

<li class="sidebar-item <?php echo $this->uri->segment(3) == 'billing' ? 'selected' : ''; ?>">
  <a class="sidebar-link"
    href="<?php echo base_url(); ?>head-office-iad/biling/audit"
    aria-expanded="false">
    <i class="mdi mdi-file-check"></i>
    <span class="hide-menu ls-1">Billing</span>
  </a>
</li>

<li class="sidebar-item <?php echo $this->uri->segment(3) == 'charges' ? 'selected' : ''; ?>">
  <a class="sidebar-link"
    href="<?php echo base_url(); ?>head-office-iad/charges/bu-charges"
    aria-expanded="false">
    <i class="mdi mdi-file-document-box"></i>
    <span class="hide-menu ls-1">Business Unit Charging</span>
  </a>
</li>

<li class="sidebar-item <?php echo $this->uri->segment(3) == 'history' ? 'selected' : ''; ?>">
  <a class="sidebar-link"
    href="<?php echo base_url(); ?>head-office-iad/transaction/history"
    aria-expanded="false">
    <i class="mdi mdi-receipt"></i>
    <span class="hide-menu ls-1">Payment History</span>
  </a>
</li>

<li class="sidebar-item <?php echo ($this->uri->segment(3) == 'ledger') ? 'selected' : '' ; ?>">
  <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
    <i class="mdi mdi-book-open-page-variant ls-1"></i>Ledger
    
  </a>
  <ul aria-expanded="false" class="collapse first-level">
    <li class="sidebar-item">
      <a href="<?php echo base_url(); ?>head-office-iad/ledger" class="sidebar-link"
        ><i class="mdi mdi-note"></i
        ><span class="hide-menu ls-1">Paid Bill</span>
      </a>
    </li>
    <li class="sidebar-item">
      <a href="<?php echo base_url(); ?>head-office-iad/ledger/mbl" class="sidebar-link">
        <i class="mdi mdi-note-outline ls-1"></i>Max Benefit Limit
      </a>
    </li>
  </ul>
</li>

<li class="sidebar-item <?php echo $this->uri->segment(3) == 'account_setting' ? 'selected' : ''; ?>">
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