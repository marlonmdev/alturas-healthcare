<div class="modal fade" id="viewNoaModal" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <section id="printableDiv">
        <div class="modal-header">
          <h4 class="modal-title ls-2">NOA #: <span id="noa-no" class="text-primary"></span> <span id="noa-status"></span></h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="container">
            <div class="row text-center">
              <h4><strong>PATIENT DETAILS</strong></h4>
            </div>
            <div class="row">
              <table class="table table-bordered table-striped table-hover table-responsive table-sm">
                <tr>
                  <td class="ls-1 fw-bold">Requested On :</td>
                  <td class="ls-1 fw-bold" id="request-date"></td>
                </tr>
                <tr>
                  <td class="ls-1 fw-bold">Approved By :</td>
                  <td class="ls-1 fw-bold" id="approved-by"></td>
                </tr>
                <tr>
                  <td class="ls-1 fw-bold">Approved On :</td>
                  <td class="ls-1 fw-bold" id="approved-on"></td>
                </tr>
                <tr>
                  <td class="fw-bold ls-1">Percentage :</td>
                  <td class="fw-bold ls-1" id="percentage"></td>
                </tr>
                <tr>
                  <td class="ls-1 fw-bold">Full Name :</td>
                  <td class="ls-1 fw-bold" id="full-name"></td>
                </tr>
                <tr>
                  <td class="ls-1 fw-bold">Date of Birth :</td>
                  <td class="ls-1 fw-bold" id="date-of-birth"></td>
                </tr>
                <tr>
                  <td class="ls-1 fw-bold">Age :</td>
                  <td class="ls-1 fw-bold" id="age"></td>
                </tr>
                <tr>
                  <td class="ls-1 fw-bold">Hospital :</td>
                  <td class="ls-1 fw-bold" id="hospital-name"></td>
                </tr>
                <tr>
                  <td class="ls-1 fw-bold">Admission Date :</td>
                  <td class="ls-1 fw-bold" id="admission-date"></td>
                </tr>
                <tr>
                  <td class="ls-1 fw-bold">Chief Complaint :</td>
                  <td class="ls-1 fw-bold" id="chief-complaint"></td>
                </tr>
                <tr>
                  <td class="ls-1 fw-bold">Work-Related :</td>
                  <td class="ls-1 fw-bold" id="work-related"></td>
                </tr>
                <tr>
                  <td class="fw-bold ls-1">Services :</td>
                  <td class="fw-bold ls-1" id="med-services-list"></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </section>
      <div class="modal-footer">
        <button class="btn btn-dark ls-1 me-2" onclick="saveAsImage()"><i class="mdi mdi-file-image"></i> Save as Image</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>