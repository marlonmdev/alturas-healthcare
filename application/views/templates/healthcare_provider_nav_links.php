<li class="nav-item">
  <a class="nav-link
        <?php echo ($this->uri->segment(2) === 'dashboard') ? 'active' : '' ?>
        " href="<?= base_url(); ?>healthcare-provider/dashboard">
    <i class="bx bxs-group align-middle bx-icon"></i>
    <span>Members</span>
  </a>
</li>
<li class="nav-item">
  <a class="nav-link  <?php echo ($this->uri->segment(3) === 'billing-person') ? 'active' : '' ?>  collapsed" href="<?= base_url(); ?>healthcare-provider/billing/billing-person">
    <i class="bx bxs-receipt align-middle bx-icon"></i>
    <span>Billing</span>
  </a>

</li>

<li class="nav-item">
  <a class="nav-link
        <?php echo ($this->uri->segment(2) === 'loa-request-list') ? 'active' : '' ?>
        " href="<?= base_url(); ?>healthcare-provider/loa-request-list/loa-pending">
    <i class="bx bxs-food-menu align-middle bx-icon"></i>
    <span>Loa</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link
        <?php echo ($this->uri->segment(2) === 'noa-request-list') ? 'active' : '' ?>
        " href="<?= base_url(); ?>healthcare-provider/noa-request-list/noa-pending">
    <i class="bx bxs-file align-middle bx-icon"></i>
    <span>Noa</span>
  </a>
</li>


<li class="nav-item">
  <a class="nav-link
        <?php echo ($this->uri->segment(3) === 'report-list') ? 'active' : '' ?>
        " href="<?= base_url(); ?>healthcare-provider/reports/report-list">
    <i class="bx bxs-bar-chart-alt-2 align-middle bx-icon"></i>
    <span>Overview</span>
  </a>
</li>

<hr>
<li class="nav-item">
  <a class="nav-link" href="<?= base_url(); ?>logout">
    <i class="bx bxs-log-out align-middle bx-icon"></i>
    <span>Log Out</span>
  </a>
</li>