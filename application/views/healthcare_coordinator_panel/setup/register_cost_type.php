      <div class="modal fade" id="registerCostTypeModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title ls-2">REGISTER</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                  <form method="post" action="<?= base_url() ?>healthcare-coordinator/setup/cost-types/register/submit" id="registerCostTypeForm">
                    <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="form-group row">
                      <div class="col-sm-12 mb-2">
                        <label class="colored-label"><i class="bx bx-health icon-red"></i> Cost Type</label>
                        <input type="text" class="form-control" name="cost-type" id="cost-type">
                        <span id="cost-type-error" class="text-danger"></span>
                      </div> 
                    </div>

                    <div class="row">
                      <div class="col-sm-12 mb-sm-0 d-flex justify-content-end">
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