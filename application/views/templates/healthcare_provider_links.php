<li class="sidebar-item">
  <a
    class="sidebar-link"
    href="<?php echo base_url(); ?>healthcare-provider/dashboard"
    aria-expanded="false" >
    <i class="mdi mdi-view-dashboard"></i>
    <span class="hide-menu ls-1">Dashboard</span>
   </a>
</li>

<li class="sidebar-item <?php echo $this->uri->segment(2) == "loa-requests" ? "selected" : ""; ?>" onclick="toggleSelected(this)">
  <a
    class="sidebar-link"
    href="<?php echo base_url(); ?>healthcare-provider/loa-requests/pending"
    aria-expanded="false">
    <i class="mdi mdi-file-document"></i>
    <span class="hide-menu ls-1">LOA</span>
  </a>
</li>

<li class="sidebar-item <?php echo $this->uri->segment(2) == "noa-requests" ? "selected" : ""; ?>" onclick="toggleSelected(this)">
  <a
    class="sidebar-link"
    href="<?php echo base_url(); ?>healthcare-provider/noa-requests/pending"
    aria-expanded="false">
    <i class="mdi mdi-file-chart"></i>
    <span class="hide-menu ls-1">NOA</span>
  </a>
</li>

<li id="hc-billing-tab" class="sidebar-item <?php echo $this->uri->segment(2) == "billing" ? "selected" : ""; ?>" onclick="toggleSelected(this)">
  <a
    class="sidebar-link has-arrow"
    href="javascript:void(0)"
    aria-expanded="false">
    <span class="position-relative">
    <span id ="billing-count" class="position-absolute translate-middle badge bg-danger rounded-circle"></span>
    <i class="mdi mdi-file-check"></i>
    <span class="hide-menu ls-1">Billing</span>
    </span>
  </a> 
  <ul aria-expanded="false" class="collapse first-level">
    <li class="sidebar-item">
      <a href="<?php echo base_url(); ?>healthcare-provider/billing" class="sidebar-link">
        <i class="mdi mdi-receipt"></i>
        <span class="hide-menu ls-1">Search for Billing</span>
      </a>
    </li>
    <li class="sidebar-item">
      <a href="<?php echo base_url(); ?>healthcare-provider/billing/payment_list" class="sidebar-link">
        <i class="mdi mdi-upload"></i>
        <span class="hide-menu ls-1">Payment List</span>
      </a>
    </li>
  </ul>
</li>

<li id="hc-list-soa-tab" class="sidebar-item <?php echo $this->uri->segment(2) == 'patient_soa' ? 'selected' : ""; ?>" onclick="toggleSelected(this)">
  <a class="sidebar-link sidebar-link" href="<?php echo base_url(); ?>healthcare-provider/patient_soa/soa-list" aria-expanded="false">
  <span class="position-relative">
  <span id ="letter-count" class="position-absolute translate-middle badge bg-danger rounded-circle"></span>
    <i class="mdi mdi-file-multiple"></i>
    <span class="hide-menu ls-1">List of Soa</span>
  </span>
  </a>
</li>

<li id="hc-list-patient-tab" class="sidebar-item <?php echo $this->uri->segment(2) == 'patient' ? 'selected' : ""; ?>" onclick="toggleSelected(this)">
  <a class="sidebar-link sidebar-link" href="<?php echo base_url(); ?>healthcare-provider/patient/design" aria-expanded="false">
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
<!-- 
<style>
  .badge-animation {
    animation: pulse 1.5s infinite;
  }

  @keyframes pulse {
    0%, 100% {
      transform: scale(1);
    }
    50% {
      transform: scale(1.2);
    }
  }
</style> -->
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

// const eventSource = new EventSource(baseurl+'auto_update/notification');
const baseurl = '<?php echo base_url(); ?>';
$(document).ready(function(){
  
  $.ajax({
      url: `${baseurl}healthcare-provider/update/notification/fetch`,
      type: "GET",
      data: {token:'<?php echo $this->security->get_csrf_hash(); ?>'},
      dataType: "json",
      success:function(response){
         $('#billing-count').text(response.patient);
         $('#letter-count').text(response.guarantee);
      }
  });
});

// Get the CSRF token from your application's source and assign it to a JavaScript variable
// const csrfToken = '<?php echo $this->security->get_csrf_hash(); ?>';

// // Create a new XMLHttpRequest to set the CSRF token in the header

//  const update_notification = (route,) =>{
//     $.ajax({
//         url: baseurl+"healthcare-provider/update/notification/fetch",
//         type:'POST',
//         success: function(res){
//           console.log('result',res);
//         }
//     });
//  }
  
</script>