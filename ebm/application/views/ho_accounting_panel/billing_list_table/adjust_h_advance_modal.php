<!-- modal -->
<div class="modal fade" id="adjustHAModal" tabindex="-1" data-bs-backdrop="static" style="height:100%">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <span class="text-white fs-4">Healthcare Advance Adjustment</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form id="adjustedAdvanceForm">
            <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
                <div class="modal-body">
                    <div class="row">
                        <span class="fs-5">Member Fullname : <span id="member-fullname" class="fw-bold ps-1"></span></span>
                        <span class="fs-5 pt-1">LOA/NOA No. : <span class="fw-bold fs-5 ps-1" id="loa-noa-no"></span></span>
                        <input id="a-bill-no" name="bill-no" type="hidden">
                    </div>
                    <hr>
                    <div class="row pt-3">
                        <div class="col-4">
                            <span class="fw-bold fs-5">Hospital Bill : <span id="hospital-bill" class="text-dark fw-bold ps-1"></span></span>
                        </div>
                        <div class="col-4">
                            <span class="fw-bold fs-5">Company Charge : <span id="company-charge" class="text-dark fw-bold ps-1"></span></span>
                        </div>
                    </div>
                    <div class="row pt-5 pb-4">
                        <div class="col-4">
                            <span class="fw-bold fs-5">Healthcare Advance : <span id="advance" class="text-danger ps-1"></span></span>
                        </div>
                        <div class="col-4 offset-2">
                            <span class="fw-bold fs-5">Adjust Healthcare Advance : </span>
                            <input class="form-control" name="new-setup-advance" type="number" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success ls-2 fs-5"><i class="mdi mdi-send"></i> SUBMIT</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end modal -->