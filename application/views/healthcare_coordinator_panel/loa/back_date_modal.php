<div class="modal fade" id="backDateModal" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title ls-2"><i class="mdi mdi-calendar-clock"></i>BACK DATE: [<span class="loa_no" id="bd-loa-no" class="text-primary"></span>]</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
      </div>

      <div class="modal-body">
        <form id="backDateForm">
          <input type="hidden" name="token" id="token" value="<?= $this->security->get_csrf_hash(); ?>">
          <input type="hidden" name="loa-id" id="bd-loa-id">

          <div class="mb-3">
            <label class="ls-1">Set Expiration Date</label>
            <input type="date" class="form-control" name="expiry-date" id="expiry-date"  style="background:#ffff" placeholder="Select Date">
            <em id="expiry-date-error" class="text-danger"></em>
          </div>               

          <div class="row mt-2">
            <div class="col-sm-12 d-flex justify-content-end">
              <button type="submit" class="btn btn-success me-2"><i class="mdi mdi-send"></i> SUBMIT</button>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="mdi mdi-close-box"></i> CANCEL</button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<style type="text/css">
  .modal-header{
    background-color:#00538c;
    color:#fff
  }
  .loa_no{
    color:orange
  }
</style>