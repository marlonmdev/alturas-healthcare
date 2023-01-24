    <div class="modal fade" id="registerHPModal" tabindex="-1" data-bs-backdrop="static">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title ls-2">REGISTER</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body">
            <form method="post" action="<?php echo base_url(); ?>super-admin/setup/healthcare-providers/register/submit" id="registerHPForm">
              <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash(); ?>">
              <div class="form-group row">
                <div class="col-sm-12 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Type</label>
                  <select class="form-select" name="hp-type" id="hp-type">
                    <option value="" selected>Select Type</option>
                    <option value="Hospital">Hospital</option>
                    <option value="Laboratory">Laboratory</option>
                    <option value="Clinic">Clinic</option>
                  </select>
                  <span id="hp-type-error" class="text-danger"></span>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-12 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Name</label>
                  <input type="text" class="form-control" name="hp-name" id="hp-name">
                  <span id="hp-name-error" class="text-danger"></span>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-12 mb-2">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Address</label>
                  <input type="text" class="form-control" name="hp-address" id="hp-address">
                  <span id="hp-address-error" class="text-danger"></span>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-12 mb-3">
                  <label class="colored-label"><i class="bx bx-health icon-red"></i> Contact Number</label>
                  <input type="text" class="form-control" name="hp-contact" id="hp-contact">
                  <span id="hp-contact-error" class="text-danger"></span>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-12 mb-sm-0 d-flex justify-content-end">
                  <button type="submit" class="btn btn-primary me-2"><i class="mdi mdi-content-save"></i> REGISTER</button>
                  <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="mdi mdi-close-box"></i> CANCEL
                  </button>
                </div>
              </div>
            </form>
            <br>
          </div>
        </div>
      </div>
