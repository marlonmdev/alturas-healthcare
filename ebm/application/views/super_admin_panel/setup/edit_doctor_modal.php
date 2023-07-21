      <div class="modal fade" id="editDoctorModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title ls-2">EDIT</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
              </button>
            </div>
            <div class="modal-body">
              <form method="post" action="<?= base_url() ?>super-admin/setup/company-doctors/update" id="editDoctorForm" enctype="multipart/form-data">
                <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
                <input type="hidden" name="doctor-id" id="doctor-id">
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group mb-3">
                      <label class="colored-label"><i class="bx bx-health icon-red"></i> Doctor's Name</label>
                      <input type="text" class="form-control" name="doctor-name" id="edit-doctor-name">
                      <em id="doc-name-error" class="text-danger"></em>
                    </div>
                    <div class="form-group">
                      <span id="signature-span" class="d-none">
                        <label class="colored-label">Current Signature</label><br>
                        <img src="" id="signature-img" alt="Signature" width="250" height="auto">
                      </span>
                    </div>
                  </div>
                  <div class="col-sm-6 mb-3">
                    <label class="colored-label"><i class="bx bx-health icon-red"></i> Update Signature</label>
                    <div id="edit-signature-wrapper">
                      <input type="file" class="dropify" name="edit-signature" id="edit-signature" data-height="230" data-max-file-size="3M" data-default-file="" accept=".jpg, .jpeg, .png">
                    </div>
                    <em id="edit-signature-error" class="text-danger"></em>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-success me-2"><i class="mdi mdi-content-save-settings"></i> UPDATE
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
