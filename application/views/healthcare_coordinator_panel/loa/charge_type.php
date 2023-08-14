<!-- Modal for Affiliated hospital -->
<div class="modal fade" id="viewChargeTypeModal" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title ls-2">Charge Type</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container">
          <form method="post" action="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/set-charge-type" id="formUpdateChargeType">
            <div class="row mb-4">
              <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
              <input type="hidden" name="loa-id" id="loa-id">
              <div class="col-6 pt-3">
                <label class="colored-label fs-5">Select Type :</label>
                <select class="form-select chargetype fs-5" name="charge-type" id="charge-type">
                  <option value="">Select...</option>
                  <option value="Yes">Work related</option>
                  <option value="No">Non-work related</option>
                </select>
                <span class="text-danger" id="charge-type-error"></span>
              </div>
             

              <div class="mb-2 pt-3 fs-5 ls-1 col-6">
                <label class="colored-label">Enter Percentage :</label>
                <div class="input-group">
                  <input type="number" class="form-control" name="percentage" id="percentage" min="0" max="100" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="2">
                  <div class="input-group-append">
                    <span class="input-group-text">%</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col pb-3">
              <label class="fs-5 colored-label">Spot Report :</label>
              <input type="file" accept=".pdf, image/*" class="form-control" name="spot-report"  id="spot-report" onchange="previewFile('spot-report')">
              <span id="uploaded-spot-report-link">Last file you saved : <a class="text-info" href="JavaScript:void(0)" data-bs-toggle="tooltip" onclick="viewSpotFile()" id="uploaded-spot-report"></a></span>
            </div>  

            <div class="col pb-3">
              <label class="fs-5 colored-label">Incident Report :</label>
              <input type="file" accept=".pdf, image/*" class="form-control" name="incident-report" id="incident-report" onchange="previewFile('incident-report')">
              <span id="uploaded-incident-report-link">Last file you saved : <a class="text-info" href="JavaScript:void(0)" data-bs-toggle="tooltip" onclick="viewIncidentFile()" id="uploaded-incident-report"></a></span>
            </div>

            <div class="col pb-3">
              <label class="fs-5 colored-label">Police Report :</label>
              <input type="file" accept=".pdf, image/*" class="form-control" name="police-report" id="police-report" onchange="previewFile('police-report')">
              <span id="uploaded-police-report-link">Last file you saved : <a class="text-info" href="JavaScript:void(0)" data-bs-toggle="tooltip" onclick="viewPoliceFile()" id="uploaded-police-report"></a></span>
            </div>

            <div class="row mb-2 pt-3">
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-info ls-1 me-2"><i class="mdi mdi-send"></i> SUBMIT</button>
                <button type="button" class="btn btn-danger ls-1" data-bs-dismiss="modal"><i class="mdi mdi-close"></i> CLOSE</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End -->

<!-- Charge Type modal for not affiliated hospital -->
<div class="modal fade" id="charge_type_modal_not_affiliated" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title ls-2">Charge Type</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container">
          <form method="post" action="<?php echo base_url(); ?>healthcare-coordinator/loa/pending/submit_charge_type_not_affiliated" id="UpdateChargeTypeNotAffiliated">
            <div class="row mb-4">
              <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
              <input type="hidden" name="loa_id" id="loa_id">
              <div class="col-6 pt-3">
                <label class="colored-label fs-5">Select Type :</label>
                <select class="form-select chargetype fs-5" name="charge-type" id="charge-type">
                  <option value="">Select...</option>
                  <option value="Yes">Work related</option>
                  <option value="No">Non-work related</option>
                </select>
                <span class="text-danger" id="charge-type-error"></span>
              </div>
             

              <div class="mb-2 pt-3 fs-5 ls-1 col-6">
                <label class="colored-label">Enter Percentage :</label>
                <div class="input-group">
                  <input type="number" class="form-control" name="percentage" id="percentage1" min="0" max="100" step="0.01" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="2">
                  <div class="input-group-append">
                    <span class="input-group-text">%</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="col pb-3">
              <label class="fs-5 colored-label">Spot Report :</label>
              <input type="file" accept=".pdf, image/*" class="form-control" name="spot-report"  id="spot-report" onchange="previewFile('spot-report')">
              <span id="uploaded-spot-report-link1">Last file you saved : <a class="text-info" href="JavaScript:void(0)" data-bs-toggle="tooltip" onclick="viewSpotFile1()" id="uploaded-spot-report1"></a></span>
            </div>  

            <div class="col pb-3">
              <label class="fs-5 colored-label">Incident Report :</label>
              <input type="file" accept=".pdf, image/*" class="form-control" name="incident-report" id="incident-report" onchange="previewFile('incident-report')">
              <span id="uploaded-incident-report-link1">Last file you saved : <a class="text-info" href="JavaScript:void(0)" data-bs-toggle="tooltip" onclick="viewIncidentFile1()" id="uploaded-incident-report1"></a></span>
            </div>

            <div class="col pb-3">
              <label class="fs-5 colored-label">Police Report :</label>
              <input type="file" accept=".pdf, image/*" class="form-control" name="police-report" id="police-report" onchange="previewFile('police-report')">
              <span id="uploaded-police-report-link1">Last file you saved : <a class="text-info" href="JavaScript:void(0)" data-bs-toggle="tooltip" onclick="viewPoliceFile1()" id="uploaded-police-report1"></a></span>
            </div>

            <div class="col pt-3 pb-3">
              <label class="fs-5 colored-label">Hospital Bill :</label>
              <input class="form-control" id="hospital_bill" name="hospital_bill" oninput="formatHospitalBill(this)">
            </div>

            <div class="col pt-3 pb-3">
              <label class="fs-5 colored-label">Hospital Receipt :</label>
              <img href="JavaScript:void(0)" data-bs-toggle="tooltip" onclick="viewHospitalReceiptFile('{{ hospital_receipt }}')" id="uploaded-hospital-receipt" width="100%" height="100%" >
            </div>


            <div class="row mb-2 pt-3">
              <div class="col-12 text-center">
                <button type="button" id="send-back-button" class="btn btn-success ls-1 me-2"><i class="mdi mdi-replay"></i> SEND BACK</button>
                <button type="submit" class="btn btn-info ls-1 me-2"><i class="mdi mdi-send"></i> SUBMIT</button>
                <button type="button" class="btn btn-danger ls-1" data-bs-dismiss="modal"><i class="mdi mdi-close"></i> CLOSE</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End -->

<!-- Send Back -->
<div class="modal fade" id="sendbackmodal" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#00538c">
        <h5 class="title" style="color:#fff">HOSPITAL RECEIPT</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form method="post" id="resubmitform">
          <input type="hidden" name="token" id="token" value="<?= $this->security->get_csrf_hash(); ?>">
          <input type="text" name="loa_id" id="loaid">
          <input type="hidden" class="form-control fw-bold ls-2" name="loa_no" id="cur-loa-no" readonly>

          <div class="row form-group mt-2">
            <div class="col-sm-12 mb-2">
              <label class="colored-label ls-1">Reason for Re-submit:</label>
              <textarea  class="form-control" name="resubmit" id="resubmit" cols="30" rows="6"></textarea>
              <em id="resubmit-error" class="text-danger"></em>
            </div> 
          </div>

          <div class="row mt-2">
            <div class="col-sm-12 mb-sm-0 d-flex justify-content-end">
              <button type="submit" class="btn btn-info me-2"><i class="mdi mdi-send"></i> SUBMIT</button>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="mdi mdi-close"></i> CLOSE</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End -->


<style type="text/css">
  .modal-header{
    background-color:#00538C;
    color:#fff
  }
  .modal {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  overflow: auto; /* Ensure scrolling when content exceeds modal height */
}
</style>
