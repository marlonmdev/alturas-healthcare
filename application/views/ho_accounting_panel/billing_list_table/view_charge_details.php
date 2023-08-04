<div class="modal fade" id="viewDetailsModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Payment No. : <span id="payment-no" class="text-primary"></span> <span id="noa-status"></span></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                  </button>
                </div>
                <div class="modal-body">
                  <div class="container">
                    <div class="row text-center">
                      <h4><strong>CHARGE DETAILS</strong></h4>
                    </div>
                    <div class="row pt-2">
                      <table class="table table-responsive table-striped border-secondary table-sm">
                        <tr>
                          <td>Billing No.</td>
                          <td id="billing-no"></td>
                        </tr>
                        <tr>
                          <td>LOA/NOA No.</td>
                          <td id="loa-noa-no"></td>
                        </tr>
                        <tr>
                          <td>Percentage</td>
                          <td id="percentage"></td>
                        </tr>
                        <tr>
                          <td>Current MBL</td>
                          <td id="current-mbl"></td>
                        </tr>
                        <tr>
                          <td>Total Hospital Bill</td>
                          <td id="hospital-bill"></td>
                        </tr>
                        <tr>
                          <td>Company Charge</td>
                          <td id="company-charge"></td>
                        </tr>
                        <tr>
                          <td>Personal Charge</td>
                          <td id="personal-charge"></td>
                        </tr>
                        <tr>
                          <td>Cash Advance</td>
                          <td id="cash-advance"></td>
                        </tr>
                        <tr>
                          <td>Remaining MBL (* as of billing):</td>
                          <td id="remaining-mbl"></td>
                        </tr>
                        <tr>
                          <td>Billed On :</td>
                          <td id="billed-on"></td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
          <!-- End of View LOA -->