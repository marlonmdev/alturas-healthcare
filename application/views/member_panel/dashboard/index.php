
      <!-- Start of Page Wrapper -->
      <div class="page-wrapper">
        <!-- Bread crumb and right sidebar toggle -->
        <div class="page-breadcrumb">
          <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
              <h4 class="page-title ls-2">Dashboard</h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">Member</li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Dashboard
                    </li>
                  </ol>
                </nav>
              </div>
            </div>
          </div>
        </div>
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- Start of Container fluid  -->
        <div class="container-fluid">
          <div class="row mb-2">

            <div class="col-lg-3 col-sm-6">
              <div class="card-box bg-blue">
                <div class="inner">  
                  <h3><?php echo $pending_loa_count; ?></h3>
                  <p>Pending LOA</p>
                </div>
                <div class="icon">
                  <i class="mdi mdi-hospital-building" aria-hidden="true"></i>
                </div>
                <a href="<?php echo base_url(); ?>member/requested-loa/pending" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <div class="col-lg-3 col-sm-6">
              <div class="card-box bg-green">
                <div class="inner">
                  <h3><?php echo $pending_noa_count; ?></h3>
                  <p>Pending NOA</p>
                </div>
                <div class="icon">
                  <i class="mdi mdi-file-document" aria-hidden="true"></i>
                </div>
                <a href="<?php echo base_url(); ?>member/requested-noa/pending" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <div class="col-lg-3 col-sm-6">
              <div class="card-box bg-orange">
                <div class="inner">
                  <h3>&#8369;<?php echo number_format($mbl['max_benefit_limit'], 2); ?></h3>
                  <p>Maximum Benefit Limit</p>
                </div>
                <div class="icon">
                  <i class="mdi mdi-wallet" aria-hidden="true"></i>
                </div>
              </div>
            </div>
            
            <div class="col-lg-3 col-sm-6">
              <div class="card-box bg-red">
                <div class="inner">
                  <h3>&#8369;<?php echo number_format($mbl['remaining_balance'], 2); ?></h3>
                  <p>Remaining Balance</p>
                </div>
                <div class="icon">
                  <i class="mdi mdi-coin" aria-hidden="true"></i>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="border border-2 border-secondary"></div>
              <h4 class="page-title ls-2 mt-3 mb-4">Doctor's Availability</h4>
              <div class="row">
                <?php if (!empty($doctors)) : ?>
                  <?php foreach ($doctors as $doc) : ?>
                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6 mb-3">
                      <div class="p-30 text-white text-center shadow">
                        <img src="<?php echo base_url(); ?>assets/images/company-doctor.svg" class="card-img-top img-responsive mb-3" alt="User Image" style="width:80px;height:auto;">
                  
                        <h5 class="text-dark mb-0 mt-1">
                          <?php echo $doc['doctor_name']; ?>
                        </h5>
                        <strong style="letter-spacing:2px">
                          <?php echo ($doc['online'] == 1) ? '<span class="text-success">Online</span>' : '<span class="text-warning">Offline</span>'; ?>
                        </strong>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
            </div>
           <!-- End Row  -->  
          </div>
        <!-- End Container fluid  -->
        </div>
      <!-- End Page wrapper  -->
      </div>
    <!-- End Wrapper -->
    </div>

 <!-- Modal -->
 <div class="modal fade show animate__animated animate__fadeOut" id="termModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">

<!-- <div class="modal fade show" id="termModal" tabindex="-1" data-bs-backdrop="static" aria-modal="true" role="dialog" style="padding-right: 17px; display: block;"></div> -->
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Terms and Conditions</h3>
                </div>
                <div class="modal-body">
                <p>These terms and conditions govern your use of the medical benefits provided by Alturas Group of Companies. By enrolling in the medical benefits program provided by the Company, you agree to be bound by this Agreement.</p>
                <p>1. Medical Benefits:</p>
                <p>The Company provides medical benefits to its employees as a part of its compensation package. The medical benefits provided by the Company are intended to cover a portion of the medical expenses incurred by the employees and their dependents.</p>
                <p>2. Annual Medical Budget:</p>
                <p>The Company sets aside a certain amount of money each year for the medical benefits program. This amount is called the "Annual Medical Budget." Once the Annual Medical Budget has been fully consumed, the Company is no longer responsible for covering any additional medical expenses incurred by the employees or their dependents.</p>
                <p>3. Employee Obligation:</p>
                <p>As a participant in the medical benefits program, you are obligated to pay for any medical expenses that exceed the Annual Medical Budget. This includes expenses for yourself and any eligible dependents covered under the medical benefits program. Failure to pay for these expenses may result in the termination of your employment with the Company.</p>
                <p>4. Exclusions:</p>
                <p>The Company is not responsible for covering any medical expenses that are excluded from the medical benefits program. These exclusions may include, but are not limited to, cosmetic procedures, experimental treatments, and non-prescription medications.</p>
                <p>5. Changes to the Agreement:</p>
                <p>The Company reserves the right to change the terms of this Agreement at any time. Any changes to the Agreement will be communicated to you in writing. Your continued participation in the medical benefits program after such communication constitutes your acceptance of the revised terms.</p>
                <p>6. Termination:</p>
                <p>The Company reserves the right to terminate the medical benefits program at any time. In the event of such termination, the Company will not be responsible for covering any medical expenses incurred by employees or their dependents after the termination date.</p>
                <p>By enrolling in the medical benefits program provided by the Company, you acknowledge that you have read, understood, and agree to be bound by these terms and conditions.</p>
                    <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="agreement">
                    <label class="form-check-label" for="defaultCheck1">
                        I agree to the terms and conditions
                    </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="agreed">Agree</button>
                </div>
                </div>
            </div>
            </div>
    <script>
      const baseUrl = `<?php echo base_url(); ?>`;
      $(document).ready(()=>{
        $('#agreed').prop('disabled', true);
        read_tnc();
          // add a change event listener to the agreement checkbox
          $('#agreement').on('change', function() {
              // enable/disable the agreed button based on the checked state of the agreement checkbox
              $('#agreed').prop('disabled', !$(this).prop('checked'));
              // enable/disable the login button based on the checked state of the agreement checkbox
          }); 
          $('#agreed').on('click',function(){
              update_read_tnc();
          });
      });
      
     
      const update_read_tnc = () => {
            $.ajax({
                url: `${baseUrl}update-member-tnc`,
                type: "post",
                data: {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',
                    },
                dataType: "json",
                success: function (res) {
                   
                },
            });
        }
        

          const read_tnc = () => {
          $.ajax({
              url: `${baseUrl}read-member-tnc`,
              type: "post",
              data: {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',
                    },
              dataType: "json",
              success: function (res) {
                 if(res.modal_display ==  true){
                  $('#termModal').modal('show');
                 }else{
                  $('#termModal').modal('hide');
                 }
                 if(res.csrf_hash){
                  crsf_token = res.csrf_hash;
                }
                console.log("success");
              },
              error: function (e) {
                console.log("error");
              }
              
          });
      }


    </script>