      <div class="modal fade" id="registerRoomTypeModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title ls-2">REGISTER ROOM</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                  <form method="post" action="<?= base_url() ?>super-admin/setup/room-types/register/submit" id="registerRoomTypeForm">
                    <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">

                    <div class="row">
                      <div class="col-6 mb-1 mt-1">
                        <label class="colored-label fs-5"><i class="mdi mdi-asterisk text-danger"></i> Hospital </label>
                        <select class="form-select fw-bold" name="hospital-filter" id="hospital-filter" onchange="enableInputs()">
                          <option value="">Select Hospital</option>
                          <?php foreach($hospital as $hospitals) : ?>
                            <option value="<?php echo $hospitals['hp_id']; ?>"><?php echo $hospitals['hp_name']; ?></option>
                          <?php endforeach; ?>
                        </select>
                        <span id="hp-filter-error" class="text-danger"></span>
                      </div>

                      <div class="col-6 mb-1 mt-1">
                        <label class="colored-label fs-5"><i class="mdi mdi-asterisk text-danger"></i> Room Group</label>
                        <input type="text" class="form-control" name="room-group" id="room-group" readonly>
                        <span id="room-group-error" class="text-danger"></span>
                      </div>
                    </div>   

                    <div class="row">
                      <div class="col-6 mb-1 pt-2">
                        <label class="colored-label fs-5"><i class="mdi mdi-asterisk text-danger"></i> Room Type</label>
                        <input type="text" class="form-control" name="room-type" id="room-type" readonly>
                        <span id="room-type-error" class="text-danger"></span>
                      </div> 

                      <div class="col-6 mb-1 pt-2">
                        <label class="colored-label fs-5"> Room Type HMO Requirements</label>
                        <input type="text" class="form-control" name="room-hmo-req" id="room-hmo-req" readonly>
                        <span id="room-hmo-req-error" class="text-danger"></span>
                      </div> 
                    </div>

                    <div class="row">
                      <div class="col-8 mb-1 pt-2">
                        <label class="colored-label fs-5"><i class="mdi mdi-asterisk text-danger"></i> Room Number/s </label><em> *Separated by comma (ex. Rm. 12, 13, 14)</em>
                       <textarea class="form-control" name="room-num" id="room-num" rows="3" readonly></textarea>
                        <span id="room-num-error" class="text-danger"></span>
                      </div> 
                    
                      <div class="col-4 mb-1 pt-2">
                        <label class="colored-label fs-5"><i class="mdi mdi-asterisk text-danger"></i> Room Rate</label>
                        <input type="number" class="form-control" name="room-rate" id="room-rate" readonly>
                        <span id="room-rate-error" class="text-danger"></span>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-12 mb-sm-0 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary me-2">
                         <i class="mdi mdi-content-save"></i> REGISTER
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="close-btn">
                        <i class="mdi mdi-close-box"></i> CANCEL
                        </button>
                      </div>
                    </div>
                  </form>
                <br>
            </div>
        </div>
      </div>