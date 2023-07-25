<div class="modal fade" id="pfLoaInfoModal" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title ls-2"><span class="fs-4 fw-bold">LOA #: <span style="color:orange" id="pf-loa-no"></span></span></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container" id="pf-table">
          <table class="table table-bordered table-striped table-hover table-responsive">
            <thead class="fw-bold fs-6" style="background-color:#CE9421">
              <tr>
                <th class="fw-bold" style="color: black;font-size:12px">MEDICAL SERVICES</th>
                <th class="fw-bold" style="color: black;font-size:12px">STATUS</th>
                <th class="fw-bold" style="color: black;font-size:12px">DATE & TIME PERFORMED</th>
                <th class="fw-bold" style="color: black;font-size:12px">PHYSICIAN</th>
                <th class="fw-bold" style="color: black;font-size:12px">REASON FOR CANCELLATION</th>
              </tr>
            </thead>    
            <tbody id="pf-tbody" style="color: white;font-size:12px">
            </tbody>
          </table>
                    
          <div class="row mb-2">
            <div class="col-12"><button type="button" class="btn btn-danger ls-1 offset-11" data-bs-dismiss="modal">CANCEL</button></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

