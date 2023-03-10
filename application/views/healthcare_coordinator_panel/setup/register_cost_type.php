<div class="modal fade" id="registerCostTypeModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title ls-2">REGISTER</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                  <form method="post" action="<?= base_url() ?>healthcare-coordinator/setup/cost-types/register/submit" id="registerRoomTypeForm">
                    <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">

                    <div class="form-group row">
                      <div class="col-sm-12 mb-2">
                        <label class="colored-label"><i class="bx bx-health icon-red"></i> Price List</label>
                        <input type="text" class="form-control" name="price-list" id="price-list" required>
                        <span id="price-list-error" class="text-danger"></span>
                      </div> 

                      <div class="col-sm-12 mb-2">
                        <label class="colored-label"><i class="bx bx-health icon-red"></i> Item ID</label>
                        <input type="text" class="form-control" name="item-id" id="item-id" required>
                        <span id="id-type-error" class="text-danger"></span>
                      </div> 

                      <div class="col-sm-12 mb-2">
                        <label class="colored-label"><i class="bx bx-health icon-red"></i> Item Description</label>
                        <input type="text" class="form-control" name="cost-type" id="cost-type" required>
                        <span id="cost-type-error" class="text-danger"></span>
                      </div> 
                    
                      <div class="col-sm-12 mb-2">
                        <label class="colored-label"><i class="bx bx-health icon-red"></i> Outpatient Price</label>
                        <input type="number" class="form-control" name="op-price" id="op-price" required>
                        <span id="op-price-error" class="text-danger"></span>
                      </div>

                      <div class="col-sm-12 mb-2">
                        <label class="colored-label"><i class="bx bx-health icon-red"></i> Inpatient Price</label>
                        <input type="number" class="form-control" name="ip-price" id="ip-price" required>
                        <span id="ip-price-error" class="text-danger"></span>
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