<li class="nav-item">
  <a class="nav-link
        <?php echo ($this->uri->segment(2) === 'dashboard') ? 'active' : '' ?>
        " href="<?= base_url(); ?>head-office-accounting/dashboard">
    <i class="bx bxs-group align-middle bx-icon"></i>
    <span>Dashboard</span>
  </a>
</li>
<li class="nav-item">
  <a class="nav-link  <?php echo ($this->uri->segment(2) === 'list') ? 'active' : '' ?>  collapsed" href="<?= base_url(); ?>head-office-accounting/list">
    <i class="bx bxs-receipt align-middle bx-icon"></i>
    <span>List</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link
        <?php echo ($this->uri->segment(2) === 'loa-request-list') ? 'active' : '' ?>
        " href="<?= base_url(); ?>head-office-accounting/loa-request-list">
    <i class="bx bxs-food-menu align-middle bx-icon"></i>
    <span>Loa</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link
        <?php echo ($this->uri->segment(2) === 'noa-request-list') ? 'active' : '' ?>
        " href="<?= base_url(); ?>head-office-accounting/noa-request-list">
    <i class="bx bxs-file align-middle bx-icon"></i>
    <span>Noa</span>
  </a>
</li>


<li class="nav-item">
  <a class="nav-link
        <?php echo ($this->uri->segment(3) === 'report-list') ? 'active' : '' ?>
        " href="<?= base_url(); ?>head-office-accounting/dashboard">
    <i class="bx bxs-bar-chart-alt-2 align-middle bx-icon"></i>
    <span>Reports</span>
  </a>
</li>


<hr>
<li class="nav-item">
  <a class="nav-link" href="<?= base_url(); ?>logout">
    <i class="bx bxs-log-out align-middle bx-icon"></i>
    <span>Log Out</span>
  </a>
</li>