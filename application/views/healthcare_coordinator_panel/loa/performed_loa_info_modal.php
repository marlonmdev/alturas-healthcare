<div class="modal fade" id="pfLoaInfoModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ls-2"><span class="fs-4 fw-bold">[<span class="fw-bold fs-4 text-danger" id="pf-loa-no"></span>]</span></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="container" id="pf-table">
                    <table class="table table-bordered table-striped table-hover table-responsive">
                        <thead class="fw-bold fs-6">
                            <tr>
                                <th class="fw-bold">Medical Services</th>
                                <th class="fw-bold">Status</th>
                                <th class="fw-bold">Date & Time Performed</th>
                                <th class="fw-bold">Physician</th>
                                <th class="fw-bold">Reschedule on</th>
                                <th class="fw-bold">Reason for Cancellation</th>
                            </tr>
                        </thead>    
                        <tbody id="pf-tbody">
                           
                        </tbody>
                    </table>
                    
                    <div class="row mb-2">
                        <div class="col-12">
                        <button type="button" class="btn btn-danger ls-1 offset-11" data-bs-dismiss="modal">CANCEL</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End of View LOA -->
