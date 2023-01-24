          <div class="modal fade" id="viewLoaModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
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
                      <table class="table table-bordered table-hover table-responsive table-sm">
                        <tr>
                          <td>Disapproved By :</td>
                          <td id="disapproved-by"></td>
                        </tr>
                        <tr>
                          <td>Disapproved On :</td>
                          <td id="disapproved-on"></td>
                        </tr>
                        <tr>
                          <td>Reason for Disapproval :</td>
                          <td id="disapprove-reason"></td>
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
                          <td>Gender :</td>
                          <td id="gender"></td>
                        </tr>
                        <tr>
                          <td>Blood Type :</td>
                          <td id="blood-type"></td>
                        </tr>
                        <tr>
                          <td>Philhealth Number :</td>
                          <td id="philhealth-no"></td>
                        </tr>
                        <tr>
                          <td>Home Address :</td>
                          <td id="home-address"></td>
                        </tr>
                        <tr>
                          <td>City Address :</td>
                          <td id="city-address"></td>
                        </tr>
                        <tr>
                          <td>Contact Number :</td>
                          <td id="contact-no"></td>
                        </tr>
                        <tr>
                          <td>Email Address :</td>
                          <td id="email"></td>
                        </tr>
                        <tr>
                          <td>Contact Person Name :</td>
                          <td id="contact-person"></td>
                        </tr>
                        <tr>
                          <td>Contact Person Address :</td>
                          <td id="contact-person-addr"></td>
                        </tr>
                        <tr>
                          <td>Contact Person Number :</td>
                          <td id="contact-person-no"></td>
                        </tr>
                        <tr>
                          <td>HealthCare Provider :</td>
                          <td id="healthcare-provider"></td>
                        </tr>
                        <tr>
                          <td>LOA Request Type :</td>
                          <td id="loa-request-type"></td>
                        </tr>
                        <tr>
                          <td>Services :</td>
                          <td id="loa-med-services"></td>
                        </tr>
                        <tr>
                          <td>Health Card Number :</td>
                          <td id="health-card-no"></td>
                        </tr>
                        <tr>
                          <td>Requesting Company :</td>
                          <td id="requesting-company"></td>
                        </tr>
                        <tr>
                          <td>Availment Request Date :</td>
                          <td id="request-date"></td>
                        </tr>
                        <tr>
                          <td>Chief Complaint :</td>
                          <td id="chief-complaint"></td>
                        </tr>
                        <tr>
                          <td>Requesting Physician :</td>
                          <td id="requesting-physician"></td>
                        </tr>
                        <tr>
                          <td>Attending Physician :</td>
                          <td id="attending-physician"></td>
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