<div class="modal fade pt-4" id="insertHcIdModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title ls-2">UPLOAD ID </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                  <form method="post" action="<?= base_url() ?>healthcare-coordinator/members/approved/insert-hc-id" id="insertScannedIdForm" enctype="multipart/form-data">
                    <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
                    <input type="hidden" name="emp-id" id="emp-id">
                    <div class="form-group row pb-2">
                        <div class="col-sm-12 mb-2 mt-2">
                            <label class="colored-label fs-5 pb-1"><i class="bx bx-health icon-red"></i> Upload Scanned Healthcard ID </label><br>
                            <input class="form-control fs-5" type="file" name="scanned-id[]" id="scanned-id" accept=".jpg, .jpeg, .png, .gif" multiple>
                            <em class="text-dark pb-1 ps-1 colored-label">[ Image must not exceeds 5MB ] </em>
                            <span id="scanned-id-error" class="text-danger"></span>
                        </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-12 mb-sm-0 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary me-2">
                         <i class="mdi mdi-content-save"></i> UPLOAD
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
      <script>
        $(document).ready(function() {
          $('#scanned-id').change(function() {
            if(this.files.length > 2) {
              alert('Please select only two images.');
              $(this).val('');
              return false;
            }
          });
        });
      </script>
     
     