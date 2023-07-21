<div class="modal fade" id="viewPersonalChargeModal" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#00538c">
        <h5 class="modal-title"  style="color:#fff">BILLING # : <span id="billing-no" class="text-warning"></span> <span id="noa-status"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          <div class="row text-center">
            <h4><strong>HEALTHCARE ADVANCE DETAILS</strong></h4>
          </div>
          <div class="row pt-2">
            <table class="table table-responsive table-striped border-secondary table-sm">
              <tr>
                <td>Date Request:</td>
                <td id="requested_on"></td>
              </tr>

              <tr>
                <td>Admission Date:</td>
                <td id="admission_date"></td>
              </tr>

              <tr>
                <td>Attending Doctors:</td>
                <td id="attending_doctors"></td>
              </tr>

              <tr>
                <td>Billing #:</td>
                <td id="billing_no"></td>
              </tr>

              <tr>
                <td>LOA/NOA No.</td>
                <td id="loa-noa-no"></td>
              </tr>

              <tr>
                <td>Healthcard #:</td>
                <td id="healthcard_no"></td>
              </tr>

              <tr>
                <td>Patient Name:</td>
                <td id="patient_name"></td>
              </tr>

              <tr>
                <td>Patient Address:</td>
                <td id="patient_address"></td>
              </tr>

              <tr>
                <td>Hospital Name:</td>
                <td id="hospital_name"></td>
              </tr>

              <tr>
                <td>Percentage:</td>
                <td id="percentage"></td>
              </tr>

              <tr>
                <td>Remaining MBL (* at the time of billing):</td>
                <td id="remaining-mbl"></td>
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
