<div class="modal fade" id="viewCheckDetailsModal" tabindex="-1" data-bs-backdrop="static" style="height:100%">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <span class="fw-bold fs-4">For the Month of : [ <span class="text-info fw-bold" id="c-month"></span> <span class="text-info fw-bold" id="c-year"></span> ]</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
            <input type="hidden" name="token" value="<?php echo $this->security->get_csrf_hash() ?>">
                <div class="row">
                    <ul class="list-unstyled">
                        <li class="text-secondary">
                            <span class="ls-1 fs-5">
                            HealthCare Provider: <span class="fw-bold text-dark fs-5" id="c-hp-name"></span>
                            </span>
                        </li>
                        <li class="text-secondary">
                            <span class="ls-1 fs-5">
                            Paid on : <span class="fw-bold text-dark fs-5" id="c-paid-on"></span>
                            </span>
                        </li>
                    </ul>
                </div>
                <div class="row justify-content-center pt-2">
                    <h6 class="text-center ls-1">CHECK DETAILS</h6>
                    <table class="table table-sm">
                        <thead>
                        </thead>

                        <tbody id="check-table" class="justify-content-start">
                           
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>