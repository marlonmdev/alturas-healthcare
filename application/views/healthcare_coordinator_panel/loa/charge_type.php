        <div class="modal fade" id="viewChargeTypeModal" tabindex="-1" data-bs-backdrop="static">
          <div class="modal-dialog modal-md">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title ls-2">Set Charge Type <span id="loa-no" class="text-primary"></span> <span id="loa-status"></span></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
              </div>
              <div class="modal-body">
                <div class="container">
                  <form method="post" action="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/set-charge-type" id="formUpdateChargeType">

                    <div class="row mb-3">
                      <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
                      <input type="hidden" name="loa-id" id="loa-id">
                      <select class="form-select" name="charge-type" id="charge-type">
                        <option value="">Select Charge Type</option>
                        <option value="Company Charge">Charge to Company</option>
                        <option value="MBL Charge">Charge to MBL</option>
                      </select>
                      <em id="charge-type-error" class="text-danger"></em>
                    </div>

                    <div class="row mb-2">
                      <div class="col-12 text-center">
                        <button type="submit" class="btn btn-info me-2">Save</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                      </div>
                    </div>

                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- End of View LOA -->