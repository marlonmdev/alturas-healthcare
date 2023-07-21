          <div class="modal fade" id="viewNoaModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <section id="printableDiv">
                  <div class="modal-header">
                    <h4 class="modal-title ls-2">NOA #: <span id="noa-no" class="text-primary"></span> <span id="noa-status"></span></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="container">
                      <div class="row text-center">
                        <h4><strong>PATIENT DETAILS</strong></h4>
                      </div>
                      <div class="row">
                        <table class="table table-bordered table-striped table-hover table-responsive table-sm">
                          <tr>
                            <td class="fw-bold ls-1">Requested On :</td>
                            <td class="fw-bold ls-1" id="request-date"></td>
                          </tr>
                          <tr>
                            <td class="fw-bold ls-1">Member's Maximum Benefit Limit :</td>
                            <td class="fw-bold ls-1">&#8369;<span id="member-mbl"></span></td>
                          </tr>
                          <tr>
                            <td class="fw-bold ls-1">Member's Remaining MBL :</td>
                            <td class="fw-bold ls-1">&#8369;<span id="remaining-mbl"></span></td>
                          </tr>
                          <tr id="percent">
                            <td class="fw-bold ls-1">Percentage :</td>
                            <td class="fw-bold ls-1" id="percentage"></td>
                          </tr>
                          <tr>
                            <td class="fw-bold ls-1">Full Name :</td>
                            <td class="fw-bold ls-1" id="full-name"></td>
                          </tr>
                          <tr>
                            <td class="fw-bold ls-1">Date of Birth :</td>
                            <td class="fw-bold ls-1" id="date-of-birth"></td>
                          </tr>
                          <tr>
                            <td class="fw-bold ls-1">Age :</td>
                            <td class="fw-bold ls-1" id="age"></td>
                          </tr>
                          <tr>
                            <td class="fw-bold ls-1">Hospital :</td>
                            <td class="fw-bold ls-1" id="hospital-name"></td>
                          </tr>
                          <tr>
                            <td class="fw-bold ls-1">Admission Date :</td>
                            <td class="fw-bold ls-1" id="admission-date"></td>
                          </tr>
                          <tr>
                            <td class="fw-bold ls-1">Chief Complaint :</td>
                            <td class="fw-bold ls-1" id="chief-complaint"></td>
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
          <!-- End of View NOA -->