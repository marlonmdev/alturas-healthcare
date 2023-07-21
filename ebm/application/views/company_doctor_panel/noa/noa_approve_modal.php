    <!-- Start of NOA Disapprove Reason Modal  -->
    <div class="modal fade" id="noaApprovedModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-secondary ls-2">APPROVE NOA</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">

                    <!-- Start of Form -->
                    <form method="post" id="noaApproveForm">
                        <input type="hidden" name="token" id="token" value="<?= $this->security->get_csrf_hash(); ?>">
                        <div class="form-group row text-center">
                            <div class="col-sm-12 mb-2">
                                <label class="colored-label fs-4 ls-1"><i class="bx bx-health icon-red"></i> Is it work related?</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="work-related" id="option1" value="Yes">
                                    <label class="form-check-label ls-1" for="option1">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="work-related" id="option2" value="No">
                                    <label class="form-check-label ls-1" for="option2">No</label>
                                </div>
                                <em id="work-related-error" class="text-danger"></em>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-sm-12 mb-sm-0 d-flex justify-content-center">
                                <button type="submit" class="btn btn-danger me-2">
                                    <i class="mdi mdi-content-save"></i> SUBMIT
                                </button>
                                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                                    <i class="mdi mdi-close-box"></i> CANCEL
                                </button>
                            </div>
                        </div>
                    </form>
                    <!-- End of Form -->
                    <br>
                </div>
            </div>
        </div>
        <!-- End of NOA Approve Reason Modal -->