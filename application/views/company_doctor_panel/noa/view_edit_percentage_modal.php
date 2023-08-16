<div class="modal fade" id="edit_percentage_Modal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-m">
              <div class="modal-content">
                <section id="printableDiv">
                  <div class="modal-header">
                    <h4 class="modal-title ls-2">NOA request #:<span id="noa_no" class="text-primary"></span></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                  </div>
                  <div class="modal-body">
                    <form id="edit-percentage-form">
                      <input type="text" name="token" value="<?= $this->security->get_csrf_hash() ?>" hidden>
                      <input type="text" name="edit_noa_id" id="edit_noa_id" hidden>
                    <div class="row justify-content-evenly">
                        <div class="col-6">
                         <input type="text" class="form-control" value="Work Related" readonly>
                        </div>
                        <div class="col-6">
                        <div class="input-group">
                            <input type="text" class="form-control" id="percentage_edit" name="percentage_edit" min="0" max="100" maxlength="3" required>
                            <div class="input-group-append">
                              <span class="input-group-text">%</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </form>
                     
                  </div>
                </section>
                <div class="modal-footer">
                
                    <button type="button" class="btn btn-primary" id="edit_submit">Submit</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
               
                </div>
              </div>
            </div>
          </div>
          <!-- End of View LOA -->