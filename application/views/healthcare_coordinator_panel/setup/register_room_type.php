<div class="modal fade" id="registerRoomTypeModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title ls-2">REGISTER</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                  <form method="post" action="<?= base_url() ?>healthcare-coordinator/setup/room-types/register/submit" id="registerRoomTypeForm">
                    <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">

                    <div class="form-group row">
                      <div class="col-sm-12 mb-2 mt-2">
                        <label class="colored-label fs-5"><i class="bx bx-health icon-red"></i> Hospital </label>
                        <select class="form-select fw-bold" name="hospital-filter" id="hospital-filter" onchange="">
                                <option value="">Select Hospital</option>
                                <?php foreach($hospital as $hospitals) : ?>
                                <option value="<?php echo $hospitals['hp_id']; ?>"><?php echo $hospitals['hp_name']; ?></option>
                                <?php endforeach; ?>
                        </select>
                        <span id="hp-filter-error" class="text-danger"></span>
                      </div>

                      <div class="col-sm-12 mb-2 pt-2">
                        <label class="colored-label fs-5"><i class="bx bx-health icon-red"></i> Room Type</label>
                        <input type="text" class="form-control" name="room-type" id="room-type">
                        <span id="room-type-error" class="text-danger"></span>
                      </div> 

                      <div class="col-sm-12 mb-2 pt-2">
                        <label class="colored-label fs-5"><i class="bx bx-health icon-red"></i> Room Type HMO Requirements</label>
                        <input type="text" class="form-control" name="room-hmo-req" id="room-hmo-req">
                        <span id="room-hmo-req-error" class="text-danger"></span>
                      </div> 

                      <div class="col-sm-12 mb-2 pt-2">
                        <label class="colored-label fs-5"><i class="bx bx-health icon-red"></i> Room Number</label>
                        <input type="text" class="form-control" name="room-num" id="room-num">
                        <span id="room-num-error" class="text-danger"></span>
                      </div> 
                    
                      <div class="col-sm-12 mb-2 pt-2">
                        <label class="colored-label fs-5"><i class="bx bx-health icon-red"></i> Room Rate</label>
                        <input type="number" class="form-control" name="room-rate" id="room-rate">
                        <span id="room-rate-error" class="text-danger"></span>
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