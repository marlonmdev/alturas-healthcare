<div class="modal fade" id="editCostTypeModal" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title ls-2">EDIT NEW PRICE</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="<?= base_url() ?>healthcare-coordinator/setup/cost-types/update" id="editCostTypeForm">
          <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
          <input type="hidden" name="ctype-id" id="ctype-id">
          <div class="form-group row">
            <div class="col-sm-12 mb-2">
              <label class="colored-label"><i class="bx bx-health icon-red"></i> Item ID :</label>
              <input type="text" class="form-control" name="item_id" id="item_id" readonly>
              <span id="item_id_error" class="text-danger"></span>
            </div>

            <div class="col-sm-12 mb-2">
              <label class="colored-label"><i class="bx bx-health icon-red"></i> Item Description :</label>
              <input type="text" class="form-control" name="item_description" id="item_description" readonly>
              <span id="item_description_error" class="text-danger"></span>
            </div> 

            <div class="col-sm-12 mb-2">
              <label class="colored-label"><i class="bx bx-health icon-red"></i> OutPatient Price :</label>
              <input type="text" class="form-control" name="old_outpatient_price" id="old_outpatient_price" readonly>
              <span id="old_outpatient_price_error" class="text-danger"></span>
            </div>

            <div class="col-sm-12 mb-2">
              <label class="colored-label"><i class="bx bx-health icon-red"></i> InPatient Price :</label>
              <input type="text" class="form-control" name="old_inpatient_price" id="old_inpatient_price" readonly>
              <span id="old_inpatient_price_error" class="text-danger"></span>
            </div> 

            <div class="col-sm-12 mb-2">
              <label class="colored-label"><i class="bx bx-health icon-red"></i>Enter New OutPatient Price :</label>
              <input type="text" class="form-control" name="new_outpatient_price" id="new_outpatient_price">
              <span id="new_outpatient_price_error" class="text-danger"></span>
            </div>

            <div class="col-sm-12 mb-2">
              <label class="colored-label"><i class="bx bx-health icon-red"></i>Enter New InPatient Price :</label>
              <input type="text" class="form-control" name="new_inpatient_price" id="new_inpatient_price">
              <span id="new_inpatient_price_error" class="text-danger"></span>
            </div> 
          </div>

          <div class="row">
            <div class="col-sm-12 mb-sm-0 d-flex justify-content-end">
              <button type="submit" class="btn btn-success me-2">
              <i class="mdi mdi-content-save-settings"></i> UPDATE
              </button>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                <i class="mdi mdi-close-box"></i> CANCEL
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>