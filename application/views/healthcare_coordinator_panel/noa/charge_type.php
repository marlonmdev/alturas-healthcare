        <div class="modal fade" id="viewChargeTypeModal" tabindex="-1" data-bs-backdrop="static">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title ls-2">Charge Type</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
              </div>
              <div class="modal-body">
                <div class="container">
                  <form method="post" action="<?php echo base_url(); ?>healthcare-coordinator/noa/requests-list/set-charge-type" id="formUpdateChargeType">

                    <div class="row mb-3">
                      <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
                      <input type="hidden" name="noa-id" id="noa-id">
                      <select class="form-select ls-1" name="charge-type" id="charge-type">
                        <option value="">Please select...</option>
                        <option value="Yes">Work-related</option>
                        <option value="No">Nonwork-related</option>
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