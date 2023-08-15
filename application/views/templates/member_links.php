              <li class="sidebar-item">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>member/dashboard"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-view-dashboard"></i>
                  <span class="hide-menu ls-1">Dashboard</span>
                </a>
              </li>

              <li class="sidebar-item">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>member/hmo-policy"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-book-open-page-variant"></i>
                  <span class="hide-menu ls-1">Terms And Conditions</span>
                </a>
              </li>

              <li class="sidebar-item">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>member/healthcare-providers"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-hospital-building"></i>
                  <span class="hide-menu ls-1">Healthcare Providers</span>
                </a>
              </li>

              <li class="sidebar-item 
              <?php echo $this->uri->segment(2) == 'request-emergency-loa' ? 'selected' : ''; ?>
              ">
              <a href="<?php echo base_url(); ?>member/request-emergency-loa" class="sidebar-link"
                      ><i class="mdi mdi-note-plus"></i
                      ><span class="hide-menu ls-1">EMERGENCY LOA</span>
                    </a>
              </li>

              <li class="sidebar-item 
              <?php echo $this->uri->segment(2) == 'requested-loa' ? 'selected' : ''; ?>
              " id="loa_link">
                <a
                  class="sidebar-link has-arrow" 
                  href="javascript:void(0)"
                  aria-expanded="false"
                  >
                  <span class="position-relative">
                    <span id ="resubmit-loa-count" class="position-absolute translate-middle badge bg-danger rounded-circle"></span>
                    <i class="mdi mdi-file-document"></i>
                      <span class="hide-menu ls-1">LOA</span>
                    </span>
                 
                </a>
                <ul aria-expanded="false" class="collapse first-level" id="doc-loa-sidebar-link">
                  <li class="sidebar-item">
                    <a href="<?php echo base_url(); ?>member/request-loa" class="sidebar-link"
                      ><i class="mdi mdi-note-plus"></i
                      ><span class="hide-menu ls-1">Request LOA</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="<?php echo base_url(); ?>member/requested-loa/pending" class="sidebar-link">
                      <span class="position-relative">
                    <span id ="resubmit-loa-count2" class="position-absolute translate-middle badge bg-danger rounded-circle"></span>
                    <i class="mdi mdi-note-outline"></i>
                      <span class="hide-menu ls-1">Requested LOA</span>
                    </span>
                    </a>
                  </li>
                </ul>
              </li>

              <li class="sidebar-item 
              <?php echo $this->uri->segment(2) == 'requested-noa' ? 'selected' : ''; ?>
              " id="noa_link">
                <a
                  class="sidebar-link has-arrow" 
                  href="javascript:void(0)"
                  aria-expanded="false"
                  >
                  <span class="position-relative">
                    <span id ="resubmit-noa-count" class="position-absolute translate-middle badge bg-danger rounded-circle"></span>
                    <i class="mdi mdi-file-document"></i>
                      <span class="hide-menu ls-1">NOA</span>
                    </span>
                </a>
                <ul aria-expanded="false" class="collapse first-level" id="doc-noa-sidebar-link">
                  <li class="sidebar-item">
                    <a href="<?php echo base_url(); ?>member/request-noa" class="sidebar-link"
                      ><i class="mdi mdi-note-plus"></i
                      ><span class="hide-menu ls-1">Request NOA</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="<?php echo base_url(); ?>member/requested-noa/pending" class="sidebar-link"
                      >
                      <span class="position-relative">
                        <span id ="resubmit-noa-count2" class="position-absolute translate-middle badge bg-danger rounded-circle"></span>
                        <i class="mdi mdi-note-outline"></i>
                          <span class="hide-menu ls-1">Requested NOA</span>
                        </span>
                    </a>
                  </li>
                </ul>
              </li>

              <li class="sidebar-item 
              <?php echo $this->uri->segment(2) == 'personal-charges' ? 'selected' : ''; ?>
              ">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>member/personal-charges"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-receipt"></i>
                  <span class="hide-menu ls-1">Personal Charges</span>
                </a>
              </li>

              <li class="sidebar-item 
              <?php echo $this->uri->segment(2) == 'mbl-history' ? 'selected' : ''; ?>
              ">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>member/mbl-history/loa"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-file-restore"></i>
                  <span class="hide-menu ls-1">MBL History</span>
                </a>
              </li>

              <li class="sidebar-item 
              <?php echo $this->uri->segment(2) == 'mbl-ledger' ? 'selected' : ''; ?>
              ">
                <a
                  class="sidebar-link"
                  href="<?php echo base_url(); ?>member/mbl-ledger/loa-noa"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-file-check"></i>
                  <span class="hide-menu ls-1">MBL Ledger</span>
                </a>
              </li>
              
              <li class="sidebar-item">
                <a
                  class="sidebar-link has-arrow"
                  href="javascript:void(0)"
                  aria-expanded="false"
                  >
                  <i class="mdi mdi-account-box"></i>
                  <span class="hide-menu ls-1">My Account</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="<?php echo base_url() . 'member/profile' ?>" class="sidebar-link"
                      ><i class="mdi mdi-account"></i
                      ><span class="hide-menu ls-1">Profile</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <!-- $account_settings_url is defined in the header.php file -->
                    <a href="<?php echo $account_settings_url; ?>" class="sidebar-link"
                      ><i class="mdi mdi-settings"></i
                      ><span class="hide-menu ls-1">Account Settings</span>
                    </a>
                  </li>
                </ul>
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
                const emp_id_notify = '<?php echo $this->session->userdata('emp_id'); ?>';
                  $(document).ready(function(){


                    if($('#doc-loa-sidebar-link').is(':hidden')){
                      $('#resubmit-loa-count').prop('hidden',false);
                    }else{
                      $('#resubmit-loa-count').prop('hidden',true);
                    }
                    if($('#doc-noa-sidebar-link').is(':hidden')){
                      $('#resubmit-noa-count').prop('hidden',false);
                    }else{
                      $('#resubmit-noa-count').prop('hidden',true);
                    }

                  $('#loa_link').on('click',function(){
                    if($('#doc-loa-sidebar-link').is(':hidden')){
                      $('#resubmit-loa-count').prop('hidden',false);
                    }else{
                      $('#resubmit-loa-count').prop('hidden',true);
                    }
                  });

                  $('#noa_link').on('click',function(){
                    if($('#doc-noa-sidebar-link').is(':hidden')){
                      $('#resubmit-noa-count').prop('hidden',false);
                    }else{
                      $('#resubmit-noa-count').prop('hidden',true);
                    }
                  });
                    
                    // console.log('emp id ',emp_id);
                    $.ajax({
                        url: `${baseurl}member/resubmit/notification/fetch/${emp_id_notify}`,  
                        type: "GET",
                        data: {token:'<?php echo $this->security->get_csrf_hash(); ?>'},
                        dataType: "json",
                        success:function(response){
                          console.log('response',response.resubmit_noa);
                          if(response.resubmit_loa){
                            $('#resubmit-loa-count').text(response.resubmit_loa);
                            $('#resubmit-loa-count2').text(response.resubmit_loa);
                          }
                          if(response.resubmit_noa){
                            $('#resubmit-noa-count').text(response.resubmit_noa);
                            $('#resubmit-noa-count2').text(response.resubmit_noa);
                          }
                        }
                    });
                  });
              </script>