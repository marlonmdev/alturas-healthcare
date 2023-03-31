    <!-- Start of LOA Disapprove Reason Modal  -->
    <div class="modal fade" id="managersKeyModal" tabindex="-1" data-bs-backdrop="static">
      <div class="modal-dialog modal-md">
          <div class="modal-content">
              <div class="modal-header bg-warning">
                <h5 class="modal-title text-white ls-1"><i class="mdi mdi-account-key"></i> MANAGER'S KEY</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
              </div>
              <div class="modal-body">
              
            <!-- Start of Form -->
            <form id="managersKeyForm" autocomplete="off">
                <input type="hidden" name="token" id="token" value="<?= $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="expired-loa-id" id="expired-loa-id">

                <div class="mb-3">
                  <label class="ls-1">Username</label>
                  <input type="text" class="form-control" name="mgr-username" id="mgr-username" placeholder="Enter Username" style="background-color:#ffff">
                  <em id="mgr-username-error" class="text-danger"></em>
                </div>
                  
                <div class="mb-4">
                  <label class="ls-1">Password</label>
                  <div class="main-password" style="width:100%">
                    <input type="password" class="form-control input-password" name="mgr-password" id="mgr-password" placeholder="Enter Password" style="background-color:#ffff">
                    <a href="JavaScript:void(0);" class="icon-view">
                      <i class="mdi mdi-eye"></i>
                    </a>
                  </div>
                  <em id="mgr-password-error" class="text-danger"></em>
                </div>
               

                <div class="row mt-2">
                    <div class="col-sm-12 d-flex justify-content-end">
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