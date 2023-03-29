      <div class="modal fade" id="editRoomTypeModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title ls-2">EDIT ROOM</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                  <form method="post" action="<?= base_url() ?>super-admin/setup/room-types/update" id="editRoomTypeForm">
                    <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
                    <input type="hidden" name="room-id" id="edit-room-id">

                    <div class="row">
                      <div class="col-6 mb-1 mt-1">
                        <label class="colored-label fs-5"><i class="mdi mdi-asterisk text-danger"></i> Hospital </label>
                        <select class="form-select fw-bold" name="hospital-filter" id="edit-hospital-filter" onchange="enableInputs()">
                          <option value="">Select Hospital</option>
                          <?php foreach($hospital as $hospitals) : ?>
                            <option value="<?php echo $hospitals['hp_id']; ?>"><?php echo $hospitals['hp_name']; ?></option>
                          <?php endforeach; ?>
                        </select>
                        <span id="edit-hp-filter-error" class="text-danger"></span>
                      </div>

                      <div class="col-6 mb-1 mt-1">
                        <label class="colored-label fs-5"><i class="mdi mdi-asterisk text-danger"></i> Room Type HMO Requirements</label>
                        <input type="text" class="form-control" name="room-hmo-req" id="edit-room-hmo-req">
                        <span id="edit-room-hmo-req-error" class="text-danger"></span>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-6 mb-1 pt-2">
                        <label class="colored-label fs-5"><i class="mdi mdi-asterisk text-danger"></i> Room Type</label>
                        <input type="text" class="form-control" name="room-type" id="edit-room-type">
                        <span id="edit-room-type-error" class="text-danger"></span>
                      </div> 

                      <div class="col-6 mb-1 pt-2">
                        <label class="colored-label fs-5"><i class="mdi mdi-asterisk text-danger"></i> Room Rate</label>
                        <input type="number" class="form-control" name="room-rate" id="edit-room-rate">
                        <span id="edit-room-rate-error" class="text-danger"></span>
                      </div> 
                    </div>

                    <div class="row">
                      <div class="col-8 mb-1 pt-2">
                        <label class="colored-label fs-5"><i class="mdi mdi-asterisk text-danger"></i> Room Number/s </label><em> *Separated by comma (ex. Rm. 12, Rm. 13)</em>
                        <textarea class="form-control" name="room-num" id="edit-room-num" rows="3"></textarea>
                        <span id="edit-room-num-error" class="text-danger"></span>
                      </div> 
                    
                      <!-- <div class="col-4 mb-1 pt-2">
                        <label class="colored-label fs-5"><i class="mdi mdi-asterisk text-danger"></i> Room Rate</label>
                        <input type="number" class="form-control" name="room-rate" id="edit-room-rate">
                        <span id="edit-room-rate-error" class="text-danger"></span>
                      </div> -->
                    </div>

                    <div class="row">
                      <div class="col-sm-12 mb-sm-0 d-flex justify-content-end">
                        <button type="submit" class="btn btn-success me-2">
                         <i class="mdi mdi-content-save"></i> UPDATE
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