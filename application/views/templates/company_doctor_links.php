              <li class="sidebar-item">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>company-doctor/dashboard"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-view-dashboard"></i>
                  <span class="hide-menu ls-1">Dashboard</span>
                </a>
              </li>       

              <li class="sidebar-item">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>company-doctor/healthcare-providers"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-hospital-building"></i>
                  <span class="hide-menu ls-1">Healthcare Providers</span>
                </a>
              </li>

              <li class="sidebar-item 
              <?php echo ($this->uri->segment(2) == 'members' || $this->uri->segment(2) == 'member') ? 'selected' : ''; ?>
              ">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>company-doctor/members"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-account-multiple"></i>
                  <span class="hide-menu ls-1">Members</span>
                </a>
              </li>

              <li class="sidebar-item <?php echo $this->uri->segment(2) == 'override' ? 'selected' : ''; ?>">
                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false" title="Request for Zero MBL Employee">
                  <i class=" mdi mdi-file-multiple"></i>
                  <span class="hide-menu ls-1">Request for LOA/NOA</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="JavaScript:void(0)" onclick="LOAManagersKey()" data-bs-toggle="tooltip"  class="sidebar-link"
                      ><i class="mdi mdi-note-plus"></i
                      ><span class="hide-menu ls-1">LOA Requisition</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="JavaScript:void(0)" onclick="NOAManagersKey()" data-bs-toggle="tooltip"  class="sidebar-link">
                      <i class="mdi mdi-shape-rectangle-plus"></i><span class="hide-menu ls-1">NOA Requisition</span>
                    </a>
                  </li>
                </ul>
              </li>

              <li class="sidebar-item 
              <?php echo $this->uri->segment(2) == 'loa' ? 'selected' : ''; ?>
              ">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>company-doctor/loa/requests-list"
                  aria-expanded="false"
                  >
                  <span class="position-relative">
                    <span id ="pending-loa-count" class="position-absolute translate-middle badge bg-danger rounded-circle"></span>
                    <i class="mdi mdi-file-document"></i>
                      <span class="hide-menu ls-1">LOA Requests</span>
                    </span>
                </a>
              </li>

              <li class="sidebar-item 
              <?php echo $this->uri->segment(2) == 'noa' ? 'selected' : ''; ?>
              ">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>company-doctor/noa/requests-list"
                  aria-expanded="false"
                  >
                  <span class="position-relative">
                    <span id ="pending-noa-count" class="position-absolute translate-middle badge bg-danger rounded-circle"></span>
                    <i class="mdi mdi-file-chart"></i>
                    <span class="hide-menu ls-1">NOA Requests</span>
                    </span>
                </a>
              </li>
             
              <li class="sidebar-item">
                 <!-- $account_settings_url is defined in the header.php file -->
                <a
                  class="sidebar-link"  
                  href="<?php echo $account_settings_url; ?>"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-settings"></i>
                  <span class="hide-menu ls-1">Account Settings</span>
                </a>
              </li> 
              
              <li class="sidebar-item">
                <a
                  class="sidebar-link"
                  href="javascript:void(0)"
                  onclick="logout(`<?= base_url() ?>`)"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-power"></i>
                  <span class="hide-menu ls-1">Logout</span>
                </a>
              </li> 
              <script>
                const baseurl = '<?php echo base_url(); ?>';
                  $(document).ready(function(){
                    
                    $.ajax({
                        url: `${baseurl}company-doctor/update/notification/fetch`,
                        type: "GET",
                        data: {token:'<?php echo $this->security->get_csrf_hash(); ?>'},
                        dataType: "json",
                        success:function(response){
                          if(response.pending_loa > 0){
                            $('#pending-loa-count').text(response.pending_loa);
                          }
                          if(response.pending_noa > 0){
                            $('#pending-noa-count').text(response.pending_noa);
                          }
                           
                        }
                    });
                  });
              </script>
