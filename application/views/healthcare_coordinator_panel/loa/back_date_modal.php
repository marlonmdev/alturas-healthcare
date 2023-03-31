    <!-- Start of LOA Disapprove Reason Modal  -->
    <div class="modal fade" id="backDateModal" tabindex="-1" data-bs-backdrop="static">
      <div class="modal-dialog modal-md">
          <div class="modal-content">
              <div class="modal-header bg-warning">
                <h5 class="modal-title text-white ls-1"><i class="mdi mdi-calendar-clock"></i> BACK DATE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
              </div>
              <div class="modal-body">
              
            <!-- Start of Form -->
            <form id="backDateForm">
                <input type="hidden" name="token" id="token" value="<?= $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="bd-loa-id" id="bd-loa-id">

                <div class="mb-3">
                  <label class="ls-1">Set Expiration Date</label>
                  <input type="date" class="form-control" name="back-date" id="back-date"  style="background:#ffff" placeholder="Select Date">
                  <em id="back-date-error" class="text-danger"></em>
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