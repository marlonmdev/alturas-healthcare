  <!-- Start of Member Profile -->
  <div class="col-lg-12 d-none" id="mbr-profile-div">
    <div class="row gutters-sm">

      <div class="col-md-7 mb-3">
      <div class="card d-flex justify-content-evenly">
            <div class="row align-items-end">
              <div class="col-md-6">
        <div class="card shadow">
          <div class="card-body pt-4">
            <div class="d-flex flex-column align-items-center text-center">
              <img id="mbr-photo" alt="Member Photo" class="rounded-circle img-responsive" width="140" height="auto">
              <div class="mt-3">
                <p class="mb-1"><strong id="bus-unit"></strong></p>
                <p class="mb-1"><strong id="dept-name"></strong></p>
                <p class="text-success mb-1"><strong id="position"></strong></p>
                <p class="mb-1"><strong id="emp-type"></strong></em>
                <p class="text-muted font-size-sm"><span class="badge rounded-pill bg-success"><strong id="cur-status"></strong></span></p>
              </div>
            </div>
          </div>
        </div>
        </div>

        <div class="col-md-6">
                <div class="card shadow">
          <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
              <h6 class="mb-0 text-secondary" style="font-weight:600;">Position Level: </h6>
              <span style="font-weight:600;" class="colored-label" id="pos-level"></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
              <h6 class="mb-0 text-secondary" style="font-weight:600;">Health Card No: </h6>
              <span style="font-weight:600;" class="colored-label" id="hcard-no"></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
              <h6 class="mb-0 text-secondary" style="font-weight:600;">Max Benefit Limit: </h6>
              <span style="font-weight:600;" class="colored-label" id="mbr-mbl"></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
              <h6 class="mb-0 text-secondary" style="font-weight:600;">Remaining Balance: </h6>
              <span style="font-weight:600;" class="colored-label" id="mbr-rmg-bal"></span>
            </li>
          </ul>
        </div>
        </div>
        </div>
        </div>

        <!-- <h4 class="page-title ls-2 ">Patient History</h4> -->
            <!-- patient history Loa-->
            <!-- <div class="card shadow mt-2 p-2" >
              <table class="table table-hover table-responsive" id="loa_table">
                  <thead >
                    <tr>
                      <th > <span style="font-weight:600;" class="colored-label">LOA #</span></th>
                      <th > <span style="font-weight:600;" class="colored-label">NET BILL</span></th>
                      <th > <span style="font-weight:600;" class="colored-label">STATUS</span></th>
                      <th > <span style="font-weight:600;" class="colored-label">REQUEST DATE</span></th>
                      <th > <span style="font-weight:600;" class="colored-label">VIEW</span></th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
            </div> -->

            <!-- patient history noa-->
            <!-- <div class="card shadow mt-3 p-2">
            <table class="table table-hover table-responsive" id="noa_table">
                <thead>
                  <tr>
                      <th > <span style="font-weight:600;" class="colored-label">NOA #</span></th>
                      <th > <span style="font-weight:600;" class="colored-label">NET BILL</span></th>
                      <th > <span style="font-weight:600;" class="colored-label">STATUS</span></th>
                      <th > <span style="font-weight:600;" class="colored-label">REQUEST DATE</span></th>
                      <th > <span style="font-weight:600;" class="colored-label">VIEW</span></th>
                  </tr>
                </thead>
              	<tbody>
                </tbody>
              </table>
            </div> -->

      </div>
      <div class="col-md-5">
        <div class="card shadow mb-0">
          <div class="card-body pt-4">
            <div class="row">
              <div class="col-sm-3">
                <h6 class="mb-2 text-secondary" style="font-weight:600;">Full Name:</h6>
              </div>
              <div class="col-sm-9 colored-label" style="font-weight:600;" id="mbr-fullname">
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <h6 class="mb-0 text-secondary" style="font-weight:600;">Home Address:</h6>
              </div>
              <div class="col-sm-9 colored-label" style="font-weight:600;" id="home-addr">
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <h6 class="mb-0 text-secondary" style="font-weight:600;">City Address:</h6>
              </div>
              <div class="col-sm-9 colored-label" style="font-weight:600;" id="city-addr">
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <h6 class="mb-0 text-secondary" style="font-weight:600;">Date of Birth:</h6>
              </div>
              <div class="col-sm-9 colored-label" style="font-weight:600;" id="mbr-dob">
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <h6 class="mb-0 text-secondary" style="font-weight:600;">Age:</h6>
              </div>
              <div class="col-sm-9 colored-label" style="font-weight:600;" id="mbr-age">
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <h6 class="mb-0 text-secondary" style="font-weight:600;">Civil Status:</h6>
              </div>
              <div class="col-sm-9 colored-label" style="font-weight:600;" id="cvl-status">
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <h6 class="mb-0 text-secondary" style="font-weight:600;">Sex:</h6>
              </div>
              <div class="col-sm-9 colored-label" style="font-weight:600;" id="mbr-sex">
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <h6 class="mb-0 text-secondary" style="font-weight:600;">Contact Number:</h6>
              </div>
              <div class="col-sm-9 colored-label" style="font-weight:600;" id="contact-no">
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <h6 class="mb-0 text-secondary" style="font-weight:600;">Email Address:</h6>
              </div>
              <div class="col-sm-9 colored-label" style="font-weight:600;" id="email-ad">
              </div>
              <input type="hidden" id="s-emp-id">
            </div>
            <hr>
            <div class="row" id="spouse-div">
              <div class="col-sm-3">
                <h6 class="mb-0 text-secondary" style="font-weight:600;">Spouse:</h6>
              </div>
              <div class="col-sm-9 colored-label" style="font-weight:600;" id="mbr-spouse">
              </div>
            </div>
            <hr id="spouse-hr">
            <div class="row">
              <div class="col-sm-3">
                <h6 class="mb-0 text-secondary" style="font-weight:600;">Blood Type:</h6>
              </div>
              <div class="col-sm-9 colored-label" style="font-weight:600;" id="blood-type">
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <h6 class="mb-0 text-secondary" style="font-weight:600;">Height:</h6>
              </div>
              <div class="col-sm-9 colored-label" style="font-weight:600;" id="mbr-height">
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <h6 class="mb-0 text-secondary" style="font-weight:600;">Weight:</h6>
              </div>
              <div class="col-sm-9 colored-label" style="font-weight:600;" id="mbr-weight">
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-5 mt-2">
                <h6 class="mb-0 text-secondary" style="font-weight:600;">Contact Person's Name:</h6>
              </div>
              <div class="col-sm-7 mt-2 colored-label" style="font-weight:600;" id="cp-name">
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-5">
                <h6 class="mb-0 text-secondary" style="font-weight:600;">Contact Person's Address:</h6>
              </div>
              <div class="col-sm-7 colored-label" style="font-weight:600;" id="cp-addr">
                Pilar
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-5">
                <h6 class="mb-0 text-secondary" style="font-weight:600;">Contact Person's Number:</h6>
              </div>
              <div class="col-sm-7 colored-label" style="font-weight:600;" id="cp-contact">
                09817489174
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End of Member Profile -->