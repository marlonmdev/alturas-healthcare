<div class="modal fade pt-4" id="insertHcIdModal" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title ls-2">Upload Healthcard ID </h4><em class="text-danger colored-label pb-2fs-5">[ Image must not exceeds 5MB ] </em>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        <form method="post" action="<?= base_url() ?>healthcare-coordinator/members/approved/insert-hc-id" id="insertScannedIdForm" enctype="multipart/form-data">
          <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
          <input type="hidden" name="emp-id" id="emp-id">
                        
          <div class="col-lg-8 pt-1">
            <input type="hidden" class="form-control text-danger fs-5 fw-bold" name="emp-name" id="emp-name" placeholder="Employee Name"readonly>
          </div>
                        
          <div class="row pt-5">
            <div class="col-lg-5 offset-1">
              <label class="colored-label fs-5 fw-bold">Front : </label>
              <input type="file" class="dropify" name="front-id" id="front-id" accept=".jpg, .jpeg, .png, .gif" data-max-file-size="5M">
              <span id="front-id-error" class="text-danger"></span>
            </div>
            <div class="col-lg-5">
              <label class="colored-label fs-5 fw-bold">Back : </label>
              <input type="file" class="dropify" name="back-id" id="back-id" accept=".jpg, .jpeg, .png, .gif" data-max-file-size="5M">
              <span id="back-id-error" class="text-danger"></span>
            </div>
          </div><br>

          <div class="row pt-3">
            <div class="col-sm-12 mb-sm-0 d-flex justify-content-end">
              <button type="submit" class="btn btn-primary me-2"><i class="mdi mdi-content-save"></i> UPLOAD</button>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="mdi mdi-close-box"></i> CANCEL</button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
     
     
     