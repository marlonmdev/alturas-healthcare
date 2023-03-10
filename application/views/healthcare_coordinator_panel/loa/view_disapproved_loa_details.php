        <div class="modal fade" id="viewLoaModal" tabindex="-1" data-bs-backdrop="static">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <section id="printableDiv">
                <div class="modal-header">
                  <h4 class="modal-title ls-2">LOA #: <span id="loa-no" class="text-primary"></span> <span id="loa-status"></span></h4>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                  </button>
                </div>
                <div class="modal-body">
                  <div class="container">
                    <div class="row text-center">
                      <h4><strong>LOA REQUEST DETAILS</strong></h4>
                    </div>
                    <div class="row">
                      <table class="table table-bordered table-striped table-hover table-responsive table-sm">
                        <tr>
                          <td class="fw-bold ls-1">Disapproved By :</td>
                          <td class="fw-bold ls-1" id="disapproved-by"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Disapproved On :</td>
                          <td class="fw-bold ls-1" id="disapproved-on"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Reason for Disapproval :</td>
                          <td class="fw-bold ls-1" id="disapprove-reason"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Member's Maximum Benefit Limit :</td>
                          <td class="fw-bold ls-1">&#8369;<span id="member-mbl"></span></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Member's Remaining MBL :</td>
                          <td class="fw-bold ls-1">&#8369;<span id="remaining-mbl"></span></td>
                        </tr>
                        <tr class="d-none" id="work-related-info">
                            <td class="fw-bold ls-1">Work Related :</td>
                            <td class="fw-bold ls-1" id="work-related-val"></td>
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
                          <td class="fw-bold ls-1">Gender :</td>
                          <td class="fw-bold ls-1" id="gender"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Blood Type :</td>
                          <td class="fw-bold ls-1" id="blood-type"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Philhealth Number :</td>
                          <td class="fw-bold ls-1" id="philhealth-no"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Home Address :</td>
                          <td class="fw-bold ls-1" id="home-address"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">City Address :</td>
                          <td class="fw-bold ls-1" id="city-address"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Contact Number :</td>
                          <td class="fw-bold ls-1" id="contact-no"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Email Address :</td>
                          <td class="fw-bold ls-1" id="email"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Contact Person Name :</td>
                          <td class="fw-bold ls-1" id="contact-person"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Contact Person Address :</td>
                          <td class="fw-bold ls-1" id="contact-person-addr"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Contact Person Number :</td>
                          <td class="fw-bold ls-1" id="contact-person-no"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">HealthCare Provider :</td>
                          <td class="fw-bold ls-1" id="healthcare-provider"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">LOA Request Type :</td>
                          <td class="fw-bold ls-1" id="loa-request-type"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Services :</td>
                          <td class="fw-bold ls-1" id="loa-med-services"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Health Card Number :</td>
                          <td class="fw-bold ls-1" id="health-card-no"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Requesting Company :</td>
                          <td class="fw-bold ls-1" id="requesting-company"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Availment Request Date :</td>
                          <td class="fw-bold ls-1" id="request-date"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Chief Complaint :</td>
                          <td class="fw-bold ls-1" id="chief-complaint"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Requesting Physician :</td>
                          <td class="fw-bold ls-1" id="requesting-physician"></td>
                        </tr>
                        <tr>
                          <td class="fw-bold ls-1">Attending Physician :</td>
                          <td class="fw-bold ls-1" id="attending-physician"></td>
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
        <!-- End of View LOA -->