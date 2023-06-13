<li class="sidebar-item">
  <a
    class="sidebar-link"
    href="<?php echo base_url(); ?>hc-provider-front-desk/dashboard"
    aria-expanded="false" >
    <i class="mdi mdi-view-dashboard"></i>
    <span class="hide-menu ls-1">Dashboard</span>
   </a>
</li>

<li class="sidebar-item <?php echo $this->uri->segment(2) == "loa-requests" ? "selected" : ""; ?>" onclick="toggleSelected(this)">
  <a
    class="sidebar-link"
    href="<?php echo base_url(); ?>hc-provider-front-desk/loa-requests/pending"
    aria-expanded="false">
    <i class="mdi mdi-file-document"></i>
    <span class="hide-menu ls-1">LOA</span>
  </a>
</li>

<li class="sidebar-item <?php echo $this->uri->segment(2) == "noa-requests" ? "selected" : ""; ?>" onclick="toggleSelected(this)">
  <a
    class="sidebar-link"
    href="<?php echo base_url(); ?>hc-provider-front-desk/noa-requests/pending"
    aria-expanded="false">
    <i class="mdi mdi-file-chart"></i>
    <span class="hide-menu ls-1">NOA</span>
  </a>
</li>

<li id="hc-list-patient-tab" class="sidebar-item <?php echo $this->uri->segment(2) == 'patient' ? 'selected' : ""; ?>" onclick="toggleSelected(this)">
  <a class="sidebar-link sidebar-link" href="<?php echo base_url(); ?>hc-provider-front-desk/patient/design" aria-expanded="false">
    <i class="mdi mdi-account-multiple"></i>
    <span class="hide-menu ls-1">List of Patient</span>
  </a>
</li>

<li class="sidebar-item">
  <a
    class="sidebar-link"
    href="<?php echo $account_settings_url; ?>"
    aria-expanded="false">
    <i class="mdi mdi-settings"></i>
    <span class="hide-menu ls-1">Account Settings</span>
  </a>
</li>
              
<li class="sidebar-item">
  <a
    class="sidebar-link"
    href="javascript:void(0)"
    onclick="logout(`<?= base_url() ?>`)"
    aria-expanded="false">
    <i class="mdi mdi-power"></i>
    <span class="hide-menu ls-1">Logout</span>
  </a>
</li> 
<script>
 let lastSelectedItem = null;

function toggleSelected(item) {
  // Deselect all other items
  const list = item.parentNode;
  const items = list.getElementsByTagName("li");
  for (let i = 0; i < items.length; i++) {
    if (items[i] !== item) {
      items[i].classList.remove("selected");
    }
  }

  // Select the clicked item
  item.classList.add("selected");
  lastSelectedItem = item;
}
</script>