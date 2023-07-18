    <!-- Start of NOA Disapprove Reason Modal  -->
    <div class="modal fade" id="noaDisapprovedReasonModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-secondary ls-2">DISAPPROVE NOA</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">

                    <!-- Start of Form -->
                    <form method="post" id="noaDisapproveForm">
                        <input type="hidden" name="token" id="token" value="<?= $this->security->get_csrf_hash(); ?>">
                        <div class="form-group row">
                            <div class="col-sm-12 mb-2">
                                <label class="colored-label"><i class="bx bx-health icon-red"></i> Reason for Disapproval:</label>
                                <textarea class="form-control" name="disapprove-reason" id="disapprove-reason" cols="30" rows="6"></textarea>
                                <em id="disapprove-reason-error" class="text-danger"></em>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-sm-12 mb-sm-0 d-flex justify-content-end">
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
        <!-- End of NOA Disapprove Reason Modal -->