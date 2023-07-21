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
    <i class="mdi mdi-account-multiple"></i>
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
<script>
 let lastSelectedItem = null;
 const baseurl = '<?php echo base_url(); ?>';

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

$(document).ready(function(){
  // update_notification('auto_update/notification/guarantee-letter',0);
  // update_notification('auto_update/notification/to-bill',1);
//   eventSource.addEventListener('message', function (event) {
//   const data = JSON.parse(event.data);
//   console.log('Received update:', data);
//   $('#letter-count').text(data);
//   // Update your notification UI with the received data
//   });
});

// Get the CSRF token from your application's source and assign it to a JavaScript variable
const csrfToken = '<?php echo $this->security->get_csrf_hash(); ?>';

// Create a new XMLHttpRequest to set the CSRF token in the header

 const update_notification = (route,index) =>{
  const xhr = new XMLHttpRequest();
    xhr.open('GET', baseurl + route, true);
    xhr.setRequestHeader('X-CSRF-Token', csrfToken);
    xhr.send();

    // After the above request, start the EventSource
    const eventSource = new EventSource(baseurl + route);
    eventSource.withCredentials = true; // Include credentials (cookies) in the request

    eventSource.onmessage = function (event) {
      // Handle incoming messages from the EventSource
      // console.log(event.data);
      if(index === 0){
        if(event.data > 0){
          $('#letter-count').text(event.data);
        }else{
          $('#letter-count').text("");
        }
      }

      if(index === 1){
        if(event.data > 0){
          $('#billing-count').text(event.data);
        }else{
          $('#billing-count').text("");
        }
        
      }
      
    }
 }
  
</script>