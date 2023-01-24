      <!-- <li class="nav-item">
        <a class="nav-link
        <?php echo ($this->uri->segment(2) === 'dashboard') ? 'active' : '' ?>
        " href="<?= base_url(); ?>healthcare-provider/dashboard">
          <i class="bx bxs-dashboard align-middle bx-icon"></i>
          <span>Dashboard</span>
        </a>
      </li> -->

      <!-- <li class="nav-item">
        <a class="nav-link
        <?php echo ($this->uri->segment(2) === 'clinics-hospitals') ? 'active' : '' ?>
        " href="<?php echo base_url(); ?>healthcare-provider/clinics-hospitals">
          <i class="bx bxs-clinic align-middle bx-icon"></i>
          <span>Clinics/Hospitals</span>
        </a>
      </li> -->

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

        <!-- <li class="nav-item">
        <a class="nav-link  <?php echo ($this->uri->segment(2) === 'soa') ? 'active' : '' ?> collapsed" data-bs-target="#cost-item-nav" data-bs-toggle="collapse" href="#">
          <i class="bx bxs-spreadsheet align-middle bx-icon"></i><span>SOA</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="cost-item-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="<?= base_url(); ?>healthcare-provider/soa/create-soa" class="<?php echo ($this->uri->segment(3) === 'create-soa') ? 'active' : '' ?>">
              <i class="bx bxs-right-arrow-alt bx-icon"></i>
              <span>Create SOA</span>
            </a>
          </li>
          <li>
            <a href="<?= base_url(); ?>healthcare-provider/soa/reprint-soa" class="<?php echo ($this->uri->segment(3) === 'reprint-soa') ? 'active' : '' ?>">
              <i class="bx bxs-right-arrow-alt bx-icon"></i>
              <span>Re-print SOA</span>
            </a>
          </li>
        </ul>
      </li> -->


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



      <!-- <li class="nav-item">
        <a class="nav-link 
        <?php echo $this->uri->segment(2) === 'cost-item' ? 'active' : '' ?> 
        collapsed" data-bs-target="#hcl-nav" data-bs-toggle="collapse" href="#">
          <i class="bx bxs-coin align-middle bx-icon"></i>
          <span>Cost Item</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="hcl-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="<?= base_url(); ?>healthcare-provider/cost-item/cost-item-requisistion" class="
              <?php echo $this->uri->segment(3) === 'cost-item-requisistion' ? 'active' : '' ?> 
              ">
              <i class="bx bxs-right-arrow-alt bx-icon"></i>
              <span>Cost Item Requisistion</span>
            </a>
          </li>

          <li>
            <a href="<?= base_url(); ?>healthcare-provider/cost-item/cost-item-requisistion-list/pending" class="
              <?php echo $this->uri->segment(3) === 'cost-item-requisistion-list' ? 'active' : '' ?> 
              ">
              <i class="bx bxs-right-arrow-alt bx-icon"></i>
              <span>Cost Item Requisistion List</span>
            </a>
          </li>
        </ul>
      </li>
-->

      <li class="nav-item">
        <a class="nav-link
        <?php echo ($this->uri->segment(3) === 'report-list') ? 'active' : '' ?>
        " href="<?= base_url(); ?>healthcare-provider/reports/report-list">
          <i class="bx bxs-bar-chart-alt-2 align-middle bx-icon"></i>
          <span>Reports</span>
        </a>
      </li>