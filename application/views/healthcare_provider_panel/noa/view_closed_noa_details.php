          <div class="modal fade" id="viewNoaModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title ls-2">NOA #: <span id="noa-no" class="text-primary"></span> <span id="noa-status"></span></h4>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                  </button>
                </div>
                <div class="modal-body">
                  <div class="container">
                    <div class="row text-center">
                      <h4><strong>NOA REQUEST DETAILS</strong></h4>
                    </div>
                    <div class="row">
                      <table class="table table-bordered table-hover table-responsive table-sm">
                        <tr>
                          <td>Approved By :</td>
                          <td id="approved-by"></td>
                        </tr>
                        <tr>
                          <td>Approved On :</td>
                          <td id="approved-on"></td>
                        </tr>
                        <tr>
                          <td>Member's Maximum Benefit Limit :</td>
                          <td>&#8369; <span id="member-mbl"></span></td>
                        </tr>
                        <tr>
                          <td>Member's Remaining MBL :</td>
                          <td>&#8369; <span id="remaining-mbl"></span></td>
                        </tr>
                        <tr>
                          <td>Full Name :</td>
                          <td id="full-name"></td>
                        </tr>
                        <tr>
                          <td>Date of Birth :</td>
                          <td id="date-of-birth"></td>
                        </tr>
                        <tr>
                          <td>Age :</td>
                          <td id="age"></td>
                        </tr>
                        <tr>
                          <td>Hospital :</td>
                          <td id="hospital-name"></td>
                        </tr>
                        <tr>
                          <td>Admission Date :</td>
                          <td id="admission-date"></td>
                        </tr>
                        <tr>
                          <td>Chief Complaint :</td>
                          <td id="chief-complaint"></td>
                        </tr>
                        <tr>
                          <td>Work-Related :</td>
                          <td id="work-related"></td>
                        </tr>
                        <tr>
                          <td>Requested On :</td>
                          <td id="request-date"></td>
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
          <!-- End of View NOA -->