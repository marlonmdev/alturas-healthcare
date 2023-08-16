<div class="modal fade pt-4" id="viewMemberHcIdModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title ls-2">Uploaded Healthcard ID </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
               
                    <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
                    <span id="ful-name"></span>
                       <div class="text-center">
                            <img src="" style="width:300px;height:225px" id="front-id">
                            <img src="" style="width:300px;height:225px" id="back-id">
                       </div>
                    <div class="row pt-4">
                      <div class="col-sm-12 mb-sm-0 d-flex justify-content-end">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        <i class="mdi mdi-close-box"></i> Close
                        </button>
                      </div>
                    </div>
                 
                <br>
            </div>
        </div>
      </div>
      
     
     