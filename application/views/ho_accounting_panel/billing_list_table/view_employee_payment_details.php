<div class="modal fade" id="paymentDetails" tabindex="-1" data-bs-backdrop="static">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title ls-2">Payment #: <span id="payment-no" class="text-primary"></span> <span id="status"></span></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
              </div>
              <div class="modal-body">
                <div class="container">
                  <div class="row text-center">
                    <h4><strong>PAYMENT DETAILS</strong></h4>
                  </div>
                  <div class="row">
                    <table class="table table-bordered table-striped table-hover table-responsive table-sm">
                      <tr>
                        <td class="fw-bold ls-1">Billing Number :</td>
                        <td class="fw-bold ls-1" id="billing-num"></td>
                      </tr>
                      <tr>
                        <td class="fw-bold ls-1">Healthcare Provider :</td>
                        <td class="fw-bold ls-1" id="hp-name"></td>
                      </tr>
                      <tr>
                        <td class="fw-bold ls-1">Full Name :</td>
                        <td class="fw-bold ls-1" id="full-name"></td>
                      </tr>
                      <tr>
                        <td class="fw-bold ls-1">Request Type :</td>
                        <td class="fw-bold ls-1" id="req-type"></td>
                      </tr>
                      <tr>
                        <td class="fw-bold ls-1">Billed on:</td>
                        <td class="fw-bold ls-1" id="billed-on"></td>
                      </tr>
                      <tr>
                        <td class="fw-bold ls-1">Company Charge :</td>
                        <td class="fw-bold ls-1">&#8369; <span id="company-charge"></span></td>
                      </tr>
                      <tr>
                        <td class="fw-bold ls-1">Check Date :</td>
                        <td class="fw-bold ls-1" id="check-date"></td>
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