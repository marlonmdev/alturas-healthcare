<div class="modal fade" id="viewLoaChargingModal" tabindex="-1" data-bs-backdrop="static">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <form method="POST" id="confirmChargingForm">
                  <div class="modal-header">
                    <input name="token" type="hidden" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input id="matched-loa-id" name="matched-loa-id" type="hidden">
                    <input id="matched-emp-id" name="matched-emp-id" type="hidden">
                    <h4 class="modal-title ls-2">LOA #: <span id="matched-billed-loa-no" class="text-primary"></span> [<span class="text-success" id="matched-loa-status"></span>]</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                  </div>
                  <div class="modal-body">
                      <div class="container" id="matched-container">
                      </div>
                      
                      <div class="row pt-3 pe-2 ps-2">
                          <div class="col-lg-6">
                              <label class="fw-bold">Company Charge: </label>
                              <input class="form-control text-danger fw-bold" id="m-company-charge" name="m-company-charge" readonly>
                          </div>
                        
                          <div class="col-lg-6">
                              <label class="fw-bold">Personal Charge: </label>
                              <input class="form-control text-danger fw-bold" id="m-personal-charge" name="m-personal-charge" readonly>
                          </div>
                      </div>

                      <div class="row pe-2 ps-2">
                          <div class="col-lg-6 pt-3">
                            <label class="fw-bold"> Remaining MBL (Before Charging): </label>
                            <input class="form-control text-danger" id="before-remaining-mbl" name="before-remaining-mbl" readonly>
                          </div>
                          <div class="col-lg-6 pt-3">
                            <label class="fw-bold"> Remaining MBL (After Charging): </label>
                            <input class="form-control text-danger" id="after-remaining-mbl" name="after-remaining-mbl" readonly>
                          </div>
                      </div>
                  </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-info" onclick="submitCharging()">Confirm Charging</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!-- End of View LOA -->