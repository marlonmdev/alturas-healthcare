<li class="sidebar-item">
  <a class="sidebar-link sidebar-link" href="<?php echo base_url(); ?>healthcare-coordinator/dashboard" aria-expanded="false">
    <i class="mdi mdi-view-dashboard"></i>
    <span class="hide-menu ls-1">Dashboard</span>
  </a>
</li>       

<li class="sidebar-item">
  <a class="sidebar-link sidebar-link" href="<?php echo base_url(); ?>healthcare-coordinator/healthcare-providers" aria-expanded="false">
    <i class="icon mdi mdi-hospital-building"></i>
    <span class="hide-menu ls-1">Healthcare Providers</span>
  </a>
</li>

<li class="sidebar-item <?php echo ($this->uri->segment(2) == 'members' || $this->uri->segment(2) == 'member') ? 'selected' : ''; ?>">
  <a class="sidebar-link sidebar-link" href="<?php echo base_url(); ?>healthcare-coordinator/members" aria-expanded="false">
    <i class="mdi mdi-account-multiple"></i>
    <span class="hide-menu ls-1">Members</span>
  </a>
</li>

<li class="sidebar-item <?php echo $this->uri->segment(2) == 'loa' ? 'selected' : ''; ?>">
  <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
    <i class="mdi mdi-note-plus"></i>Letter of Authorization
    <?php
       $total = $bar + $bar1 + $bar2 + $bar3 + $bar4;
       if ($total > 0) {
         echo '<sup style="background-color: red; color: white; border-radius: 50%; padding: 7px">' . $total . '</sup>';
       }
    ?>
  </a>
  <ul aria-expanded="false" class="collapse first-level">
    <li class="sidebar-item">
      <a href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list" class="sidebar-link">
        <i class="mdi mdi-thumb-up"><span class="mdi mdi-thumb-down"></span></i> Request List
        <?php
           $total = $bar + $bar1 + $bar2 + $bar3 + $bar4;
           if ($total > 0) {
             echo '<sup style="background-color: red; color: white; border-radius: 50%; padding: 7px">' . $total . '</sup>';
           }
        ?>
      </a>
    </li>

    <li class="sidebar-item">
      <a href="<?php echo base_url(); ?>healthcare-coordinator/emergency_loa/search_emergency" class="sidebar-link">
        <i class="mdi mdi-note-plus"></i>
        <span class="hide-menu ls-1">Emergency Form</span>
      </a>
    </li>

    <li class="sidebar-item">
      <a href="<?php echo base_url(); ?>healthcare-coordinator/loa/managers_key" class="sidebar-link"
        ><i class="mdi mdi-note-plus"></i
        ><span class="hide-menu ls-1">Diagnostic Form</span>
      </a>
    </li>
  </ul>
</li>

<li class="sidebar-item <?php echo $this->uri->segment(2) == 'noa' ? 'selected' : ''; ?>">
  <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
    <i class="mdi mdi-hospital"></i>Notice of Admission
    <?php
      $total2 = $bar5 + $bar6;
      if ($total2 > 0) {
        echo '<sup style="background-color: red; color: white; border-radius: 50%; padding: 8px">' . $total2 . '</sup>';
      }
    ?>
  </a>
  <ul aria-expanded="false" class="collapse first-level">
    <li class="sidebar-item">
      <a href="<?php echo base_url(); ?>healthcare-coordinator/noa/requests-list" class="sidebar-link">
        <i class="mdi mdi-thumb-up"><span class="mdi mdi-thumb-down"></span></i> Request List
        <?php
          $total2 = $bar5 + $bar6;
          if ($total2 > 0) {
            echo '<sup style="background-color: red; color: white; border-radius: 50%; padding: 8px">' . $total2 . '</sup>';
          }
        ?>
      </a>
    </li>
    <li class="sidebar-item">
      <a href="<?php echo base_url(); ?>healthcare-coordinator/noa/search_noa" class="sidebar-link"
        ><i class="mdi mdi-note-plus"></i
        ><span class="hide-menu ls-1">Admission Form</span>
      </a>
    </li>  
  </ul>
</li>

<li class="sidebar-item <?php echo $this->uri->segment(2) == 'personal-charges' ? 'selected' : ''; ?>">
  <a class="sidebar-link" href="<?php echo base_url(); ?>healthcare-coordinator/healthcare_advance/view_healthcare_advance_pending" aria-expanded="false">
    <i class="mdi mdi-cash-multiple"></i>
    <span class="hide-menu ls-1">Healthcare Advance</span>
  </a>
</li>

<li class="sidebar-item <?php echo $this->uri->segment(2) == 'bill' ? 'selected' : ''; ?>">
  <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
    <i class="mdi mdi-file-check"></i>Billing
    <?php
      $total = $bar_Billed + $bar_Initial + $bar_Billed2;
      if ($total > 0) {
        echo '<sup style="background-color: red; color: white; border-radius: 50%; padding: 7px">'.$total.'</sup>';
      }
    ?>
  </a>

  <ul aria-expanded="false" class="collapse first-level">
    <li class="sidebar-item">
      <a href="<?php echo base_url(); ?>healthcare-coordinator/bill/requests-list/billed" class="sidebar-link">
        <i class="mdi mdi-note-outline"></i>Billed LOA 
        <?php
          if ($bar_Billed > 0) {
            echo '<sup style="background-color: red; color: white; border-radius: 50%; padding: 7px">'.$bar_Billed.'</sup>';
          }
        ?>
      </a>
    </li>
    <li class="sidebar-item">
      <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
        <i class="mdi mdi-file-document-box"></i>Billed NOA
        <?php
          $total = $bar_Initial + $bar_Billed2;
          if ($total > 0) {
            echo '<sup style="background-color: red; color: white; border-radius: 50%; padding: 8px">' . $total . '</sup>';
          }
        ?>
      </a>
      <ul aria-expanded="false" class="collapse second-level ps-4">
        <li class="sidebar-item">
          <a href="<?php echo base_url(); ?>healthcare-coordinator/bill/noa-requests/billed/initial" class="sidebar-link">
            <i class="mdi mdi-file-multiple"></i>Initial Billing
            <?php
              if ($bar_Initial > 0) {
                echo '<sup style="background-color: red; color: white; border-radius: 50%; padding: 7px">'.$bar_Initial.'</sup>';
              }
            ?>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="<?php echo base_url(); ?>healthcare-coordinator/bill/noa-requests/billed" class="sidebar-link">
            <i class="mdi mdi-file-document"></i>Final Billing
            <?php
              if ($bar_Billed2 > 0) {
                echo '<sup style="background-color: red; color: white; border-radius: 50%; padding: 7px">'.$bar_Billed2.'</sup>';
              }
            ?>
          </a>
        </li>
      </ul>
    </li>
  </ul>
</li> 

<li class="sidebar-item">
  <a class="sidebar-link sidebar-link" href="<?php echo base_url(); ?>healthcare-coordinator/loa_controller/view_ledger" aria-expanded="false">
    <i class="mdi mdi-book-open-page-variant"></i>
    <span class="hide-menu ls-1">Ledger</span>
  </a>
</li>

<li class="sidebar-item <?php echo $this->uri->segment(2) == 'setup' ? 'selected' : ''; ?>">
  <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
    <i class="mdi mdi-wrench"></i>
    <span class="hide-menu ls-1">Setup</span>
  </a>

  <ul aria-expanded="false" class="collapse first-level">

    <li class="sidebar-item">
      <a href="<?php echo base_url(); ?>healthcare-coordinator/setup/healthcare-providers" class="sidebar-link">
        <i class="mdi mdi-hospital-building"></i>
        <span class="hide-menu ls-1">Healthcare Providers</span>
      </a>
    </li>

    <li class="sidebar-item">
      <a href="<?php echo base_url(); ?>healthcare-coordinator/setup/company-doctors" class="sidebar-link">
        <i class="mdi mdi-account-star-variant"></i>
        <span class="hide-menu ls-1">Company Doctors</span>
      </a>
    </li>

    <li class="sidebar-item">
      <a href="<?php echo base_url(); ?>healthcare-coordinator/setup/cost-types" class="sidebar-link">
        <i class="mdi mdi-code-string"></i>
        <span class="hide-menu ls-1">Cost Types</span>
      </a>
    </li>

    <li class="sidebar-item">
      <a href="<?php echo base_url(); ?>healthcare-coordinator/setup/room-types" class="sidebar-link">
        <i class="mdi mdi-houzz-box"></i>
        <span class="hide-menu ls-1">Room Types</span>
      </a>
    </li>
  </ul>
</li>   

<li class="sidebar-item">
  <a class="sidebar-link" href="<?php echo base_url(); ?>healthcare-coordinator/accounts" aria-expanded="false">
    <i class="mdi mdi-account-key"></i>
    <span class="hide-menu ls-1">User Accounts</span>
  </a>
</li> 

<li class="sidebar-item">
  <a class="sidebar-link" href="<?php echo $account_settings_url; ?>" aria-expanded="false">
    <i class="mdi mdi-settings"></i>
    <span class="hide-menu ls-1">Account Settings</span>
  </a>
</li> 
              
<li class="sidebar-item">
  <a class="sidebar-link" href="javascript:void(0)" onclick="logout(`<?= base_url() ?>`)" aria-expanded="false">
    <i class="mdi mdi-power" style="color:red"></i>
    <span class="hide-menu ls-1">Logout</span>
  </a>
</li> 

<style type="text/css">
  #main-wrapper .left-sidebar[data-sidebarbg="skin5"],
  #main-wrapper .left-sidebar[data-sidebarbg="skin5"] ul {
    background: #003153;
    /*color:#fff*/
  }
  .sidebar-nav ul .sidebar-item.selected > .sidebar-link {
    background: #003153;
    color:#fff
  }
  .sidebar-nav > #sidebarnav > .sidebar-item > .sidebar-link:hover {
    background-color: #074C6C;
    color:#fff
  }
 /* .sidebar-nav ul .sidebar-item .sidebar-link {
    color: #fff;
    padding: 14px 15px;
    display: flex;
    white-space: nowrap;
    align-items: center;
    line-height: 25px;
    opacity: 0.9;
  } */

</style> 
