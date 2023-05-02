<div class="modal fade" id="viewChargeTypeModal" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title ls-2">Charge Type</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container">

          <form method="post" action="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/set-charge-type" id="formUpdateChargeType">

            <div class="row mb-3">
              <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
              <input type="hidden" name="loa-id" id="loa-id">
              <select class="form-control chargetype fs-5" name="charge-type" id="charge-type">
                <option value="">Please Select...</option>
                <option value="Yes">Work related</option>
                <option value="No">Non-work related</option>
              </select>
              <span class="text-danger" id="charge-type-error"></span>

              <div class="mb-2 fs-5 ls-1">
                <label class="colored-label">Enter Percentage</label>
                <div class="input-group">
                  <input type="number" class="form-control" name="percentage" min="0" max="100" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="2">
                  <div class="input-group-append">
                    <span class="input-group-text">%</span>
                  </div>
                </div>
              </div>
            </div>
                    
            <div class="row mb-2">
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-info ls-1 me-2">SUBMIT</button>
                <button type="button" class="btn btn-danger ls-1" data-bs-dismiss="modal">CANCEL</button>
              </div>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>
