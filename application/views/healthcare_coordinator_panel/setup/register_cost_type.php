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
                      <div class="col-sm-12 mb-2 mt-2">
                        <label class="colored-label"><i class="bx bx-health icon-red"></i> Hospital </label>
                        <select class="form-select fw-bold" name="hospital-filter" id="hospital-filter" onchange="enableInputs()">
                                <option value="">Select Hospital</option>
                                <?php foreach($hospital as $hospitals) : ?>
                                <option value="<?php echo $hospitals['hp_id']; ?>"><?php echo $hospitals['hp_name']; ?></option>
                                <?php endforeach; ?>
                        </select>
                      </div>

                      <!-- <div class="col-sm-12 mb-2 mt-2">
                        <label class="colored-label"><i class="bx bx-health icon-red"></i> Price List Category</label>
                        <select class="form-select fw-bold" name="price-filter" id="price-filter">
                            <option value="">Select Price Category</option>
                            <?php
                                // Remove duplicates from $price_group array
                                $unique_price_groups = array_unique(array_column($price_group, 'price_list_group'));
                                foreach($unique_price_groups as $group) :
                            ?>
                            <option value="<?php echo $group; ?>"><?php echo $group; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span id="price-list-error" class="text-danger"></span>
                      </div>  -->
                      <div class="col-sm-12 mb-2 mt-2">
                          <label class="colored-label"><i class="bx bx-health icon-red"></i> Price List Category</label>
                          <select class="form-select fw-bold" name="price-filter" id="price-filter" onchange="showOtherInputDiv();enableInputs();">
                            <option value="">Select Price Category</option>
                            <?php
                            // Remove duplicates from $price_group array
                            $unique_price_groups = array_unique(array_column($price_group, 'price_list_group'));
                            foreach($unique_price_groups as $group) :
                            ?>
                            <option value="<?php echo $group; ?>"><?php echo $group; ?></option>
                            <?php endforeach; ?>
                            <option value="other_category">Other</option> <!-- Add an "Other" option -->
                          </select>
                          <div id="other-input-container" class="d-none pt-2"> <!-- Hide the text input by default -->
                            <input type="text" class="form-control fw-bold" name="other-price-filter" id="other-price-filter" placeholder="Enter other category">
                          </div>
                          <span id="price-list-error" class="text-danger"></span>
                      </div>

                      <div class="col-sm-12 mb-2 mt-2">
                        <label class="colored-label"><i class="bx bx-health icon-red"></i> Item ID</label>
                        <input type="text" class="form-control" name="item-id" id="item-id" required disabled>
                        <span id="id-type-error" class="text-danger"></span>
                      </div> 

                      <div class="col-sm-12 mb-2 mt-2">
                        <label class="colored-label"><i class="bx bx-health icon-red"></i> Item Description</label>
                        <input type="text" class="form-control" name="cost-type" id="cost-type" required disabled>
                        <span id="cost-type-error" class="text-danger"></span>
                      </div> 
                    
                      <div class="col-sm-12 mb-2 mt-2">
                        <label class="colored-label"><i class="bx bx-health icon-red"></i> Outpatient Price</label>
                        <input type="number" class="form-control" name="op-price" id="op-price" required disabled>
                        <span id="op-price-error" class="text-danger"></span>
                      </div>

                      <div class="col-sm-12 mb-2 mt-2">
                        <label class="colored-label"><i class="bx bx-health icon-red"></i> Inpatient Price</label>
                        <input type="number" class="form-control" name="ip-price" id="ip-price" required disabled>
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
        <script>
        const showOtherInputDiv = () => {
            const priceFilter = document.querySelector('#price-filter');
            const otherInputContainer = document.querySelector('#other-input-container');

            if (priceFilter.value === 'other_category') {
              otherInputContainer.classList.remove('d-none');
              otherInputContainer.classList.add('d-block');
            } else {  
              otherInputContainer.classList.remove('d-block');
              otherInputContainer.classList.add('d-none');
            }
        }

        const enableInputs = () => {
          const price_filter = document.querySelector('#price-filter');
          const hp_filter = document.querySelector('#hospital-filter');
          const item_id = document.querySelector('#item-id');
          const cost_type = document.querySelector('#cost-type');
          const op_price = document.querySelector('#op-price');
          const ip_price = document.querySelector('#ip-price');

          if(price_filter.value != '' && hp_filter.value != ''){
            item_id.removeAttribute('disabled');
            cost_type.removeAttribute('disabled');
            op_price.removeAttribute('disabled');
            ip_price.removeAttribute('disabled');
          }else{
            item_id.setAttribute('disabled', true);
            cost_type.setAttribute('disabled', true);
            op_price.setAttribute('disabled', true);
            ip_price.setAttribute('disabled', true);
          }
        }
      </script>
      </div>
     