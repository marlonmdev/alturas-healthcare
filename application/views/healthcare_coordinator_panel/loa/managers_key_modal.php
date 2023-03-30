    <!-- Start of LOA Disapprove Reason Modal  -->
    <div class="modal fade" id="managersKeyModal" tabindex="-1" data-bs-backdrop="static">
      <div class="modal-dialog modal-md">
          <div class="modal-content">
              <div class="modal-header bg-secondary">
                <h5 class="modal-title text-white ls-1"><i class="mdi mdi-account-key"></i> MANAGER'S KEY</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
              </div>
              <div class="modal-body">
              
            <!-- Start of Form -->
            <form method="post" action="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/expired-backdate" id="managersKeyForm">
                <input type="hidden" name="token" id="token" value="<?= $this->security->get_csrf_hash(); ?>">
                <div class="form-group row">
                  <div class="input-group mt-2 mb-4">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-info text-white">
                        <i class="mdi mdi-account"></i>
                        </span>
                    </div>

                    <!-- <div class="col-12 mb-4"> -->
                      <!-- <label class="colored-label ls-1">Username:</label> -->
                      <input class="form-control" type="text" name="mgr-username" id="mgr-username" placeholder="Enter Username" style="background-color:#ffff" required>
                      <em id="mgr-username-error" class="text-danger"></em>
                    <!-- </div>  -->
                  </div>

                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-info text-white">
                        <i class="mdi mdi-lock"></i>
                        </span>
                    </div>

                    <!-- <div class="col-12 mb-2"> -->
                      <!-- <label class="colored-label ls-1">Password:</label> -->
                      <input class="form-control" type="password" name="mgr-password" id="mgr-password" placeholder="Enter Password" style="background-color:#ffff" required>
                      <em id="mgr-password-error" class="text-danger"></em>
                    <!-- </div> -->
                  </div>
                </div>
               

                <div class="row mt-2">
                    <div class="col-sm-12 mb-sm-0 d-flex justify-content-end">
                    <button type="submit" class="btn btn-info me-2">
                    <i class="mdi mdi-send"></i> SUBMIT
                    </button>
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                    <i class="mdi mdi-close-box"></i> CANCEL
                    </button>
                    </div>
                </div>
            </form>
            <!-- End of Form -->
            <br>
          </div>
      </div>
  </div>
  <!-- End of LOA Disapprove Reason Modal -->