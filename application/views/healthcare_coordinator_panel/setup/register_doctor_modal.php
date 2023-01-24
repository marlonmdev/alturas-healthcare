      <div class="modal fade" id="registerDoctorModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title ls-2">REGISTER</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
              </button>
            </div>
            <div class="modal-body">
              <form method="post" action="<?= base_url() ?>healthcare-coordinator/setup/company-doctors/register/submit" id="registerDoctorForm" enctype="multipart/form-data">
                <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group mb-2">
                      <label class="colored-label"><i class="bx bx-health icon-red"></i> Doctor's Name</label>
                      <input type="text" class="form-control" name="doctor-name" id="doctor-name">
                      <em id="doctor-name-error" class="text-danger"></em>
                    </div>
                    <div class="form-group mb-2">
                      <label class="colored-label mb-1"><i class="bx bx-health icon-red"></i> Username</label>
                      <input type="text" class="form-control" name="doctor-username" id="doctor-username">
                      <em id="doctor-username-error" class="text-danger"></em>
                    </div>
                    <div class="form-group  d-flex justify-content-end mb-2">
                      <button type="button" class="btn btn-success text-white ls-1" onclick="setDefaultPassword()" data-bs-toggle="tooltip" title="Use Default Password"><i class="bx bxs-key"></i> Set Password</button>
                    </div>
                    <div class="form-group">
                      <label class="colored-label mb-1"><i class="bx bx-health icon-red"></i> Password</label>
                      <div class="main-password">
                        <input type="password" class="form-control input-password" name="doctor-password" id="doctor-password" aria-label="password">
                        <a href="JavaScript:void(0);" class="icon-view">
                          <i class="mdi mdi-eye" id="pwd-icon"></i>
                        </a>
                      </div>
                      <em id="doctor-password-error" class="text-danger"></em>
                    </div>
                  </div>
                  <div class="col-sm-6 mb-2">
                    <label class="colored-label"><i class="bx bx-health icon-red"></i> Doctor's Signature</label>
                    <div id="signature-wrapper">
                      <input type="file" class="dropify" name="doctor-signature" id="doctor-signature" data-height="230" data-max-file-size="3M" accept=".jpg, .jpeg, .png">
                    </div>
                    <em id="doctor-signature-error" class="text-danger"></em>
                  </div>
                </div>

                <div class="row mt-3">
                  <div class="col-sm-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary me-2">
                      <i class="mdi mdi-content-save"></i> REGISTER
                    </button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                      <i class="mdi mdi-close-box"></i> CANCEL
                    </button>
                  </div>
                </div>
              </form>
              <br>
            </div>
          </div>
        </div>