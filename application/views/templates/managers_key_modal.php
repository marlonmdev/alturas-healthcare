<div class="modal fade" id="LOAMngKeyModal" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header bg-cyan">
        <h5 class="modal-title text-white ls-1"><i class="mdi mdi-account-key"></i> ENTER MANAGER'S KEY TO PROCEED</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="managersKeyReqLOANOAForm" autocomplete="off">
          <span class="text-danger">This requisition is for Members with Zero MBL and Company Related situations.</span>
          <input type="hidden" name="token" id="token" value="<?= $this->security->get_csrf_hash(); ?>">
          <input type="hidden" id="req-type-key">
          <div class="text-center">
            <strong id="msg-error-req-loa" class="text-danger ls-1 mx-1"></strong>
          </div>
          <div class="mb-3">
            <label class="ls-1">Username</label>
            <input type="text" class="form-control" name="mgr-username-req-loa" id="mgr-username-req-loa" placeholder="Enter Username">
            <em id="mgr-username-error-req-loa" class="text-danger"></em>
          </div>

          <div class="mb-4">
            <label class="ls-1">Password</label>
            <input type="password" class="form-control input-password" name="mgr-password-req-loa" id="mgr-password-req-loa" placeholder="Enter Password">
            <em id="mgr-password-error-req-loa" class="text-danger"></em>
          </div>              

          <div class="row mt-2">
            <div class="col-sm-12 d-flex justify-content-end">
              <button type="submit" class="btn btn-cyan me-2"><i class="mdi mdi-send"></i> SUBMIT</button>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="mdi mdi-close-box"></i> CANCEL</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>