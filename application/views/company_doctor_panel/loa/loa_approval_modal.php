<div class="modal fade" id="loaApprovalModal" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-secondary ls-1">APPROVE REQUEST</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form method="post" action="<?php echo base_url(); ?>company-doctor/loa/requests-list/approve-request" id="loaApproveForm">
          <input type="hidden" name="token" id="token" value="<?= $this->security->get_csrf_hash(); ?>">
          <input type="hidden" name="loa-id" id="appr-loa-id">

          <div class="form-group row">
            <div class="col-12 mb-4">
              <label class="colored-label ls-1">Set Date of Expiration:</label>
              <select class="form-select" name="expiration-type" id="expiration-type" onchange="showExpDateInput()" required>
                <option value="default" selected>Default [1 week]</option>
                <option value="2 weeks">2 weeks</option>
                <option value="3 weeks">3 weeks</option>
                <option value="4 weeks">4 weeks</option>
                <option value="custom">Custom</option>
              </select>
            </div> 

            <div class="col-12 mb-2 d-none" id="exp-date-div">
              <input class="form-control" type="text" name="expiration-date" id="expiration-date" placeholder="Select Date" style="background-color:#ffff" required>
              <em id="expiration-date-error" class="text-danger"></em>
            </div>
          </div>
               
          <div class="row mt-2">
            <div class="col-sm-12 mb-sm-0 d-flex justify-content-end">
              <button type="submit" class="btn btn-success me-2"><i class="mdi mdi-content-save"></i> APPROVE</button>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="mdi mdi-close-box"></i> CANCEL</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
