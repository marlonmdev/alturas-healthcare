      <div class="modal fade" id="registerCostTypeModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title ls-2">REGISTER</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                  <form method="post" action="<?= base_url() ?>super-admin/setup/cost-types/register/submit" id="registerCostTypeForm">
                    <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">

                    <div class="form-group row">
                      <div class="col-sm-12 mb-2 mt-2">
                        <label class="colored-label fs-5"><i class="mdi mdi-asterisk text-danger"></i> Hospital </label>
                        <select class="form-select fw-bold" name="hospital-filter-add" id="hospital-filter-add" onchange="enableInputs()">
                          <option value="">Select Hospital</option>
                          <?php foreach($hospital as $hospitals) : ?>
                            <option value="<?php echo $hospitals['hp_id']; ?>"><?php echo $hospitals['hp_name']; ?></option>
                          <?php endforeach; ?>
                        </select>
                        <span id="hp-filter-error" class="text-danger"></span>
                      </div>

                      <div class="col-sm-12 mb-2 mt-2">
                          <label class="colored-label fs-5"><i class="mdi mdi-asterisk text-danger"></i> Price List Category</label>
                          <select class="form-select fw-bold" name="price-filter-add" id="price-filter-add" onchange="showOtherInputDiv();enableInputs()">
                            <option value="">Select Price Category</option>
                            <?php
                            // Remove duplicates from $price_group array
                            $unique_price_groups = array_unique(array_column($price_group, 'price_list_group'));
                            foreach($unique_price_groups as $group) :
                            ?>
                            <option value="<?php echo $group; ?>"><?php echo $group; ?></option>
                            <?php endforeach; ?>
                            <option value="other">Other</option> <!-- Add an "Other" option -->
                          </select>
                          <span id="price-filter-error" class="text-danger"></span>
                          <div id="other-input-container" class="pt-2"> <!-- Hide the text input by default -->
                            <input type="text" class="form-control fw-bold" name="other-price-filter" id="other-price-filter" placeholder="Enter other category">
                            <span id="other-price-error" class="text-danger"></span>
                          </div>
                          
                      </div>

                      <div class="col-sm-12 mb-2 mt-2">
                        <label class="colored-label fs-5"><i class="mdi mdi-asterisk text-danger"></i> Item ID</label>
                        <input type="text" class="form-control" name="item-id" id="item-id" readonly>
                        <span id="id-type-error" class="text-danger"></span>
                      </div> 

                      <div class="col-sm-12 mb-2 mt-2">
                        <label class="colored-label fs-5"><i class="mdi mdi-asterisk text-danger"></i> Item Description</label>
                        <input type="text" class="form-control" name="cost-type" id="cost-type" readonly>
                        <span id="cost-type-error" class="text-danger"></span>
                      </div> 
                    
                      <div class="col-sm-12 mb-2 mt-2">
                        <label class="colored-label fs-5"><i class="mdi mdi-asterisk text-danger"></i> Outpatient Price</label>
                        <input type="number" class="form-control" name="op-price" id="op-price" readonly>
                        <span id="op-price-error" class="text-danger"></span>
                      </div>

                      <div class="col-sm-12 mb-2 mt-2">
                        <label class="colored-label fs-5"><i class="mdi mdi-asterisk text-danger"></i> Inpatient Price</label>
                        <input type="number" class="form-control" name="ip-price" id="ip-price" readonly>
                        <span id="ip-price-error" class="text-danger"></span>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-12 mb-sm-0 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary me-2 btn-floating">
                         <i class="mdi mdi-content-save"></i> REGISTER
                        </button>
                        <button type="button" class="btn btn-danger btn-floating" data-bs-dismiss="modal">
                        <i class="mdi mdi-close-box"></i> CANCEL
                        </button>
                      </div>
                    </div>
                  </form>
                <br>
            </div>
        </div>
        <script></script>
      </div>
     