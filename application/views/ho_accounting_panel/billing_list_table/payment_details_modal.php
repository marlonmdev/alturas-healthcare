<div class="modal fade" id="addPaymentModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-cyan">
                <h4 class="modal-title ls-2 text-white">Payment Details</h4>
                <button type="button" class="btn-close btn-light" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <div class="container">
                    <div class="row pb-2">
                        <div class="row mb-3 pt-2">
                            <div class="col-lg-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span><i class="mdi mdi-hospital-building fs-3 text-danger pe-2"></i></span>
                                    </div>
                                        <input class="form-control text-dark fw-bold ls-1 fs-6" placeholder="Hospital Name" name="hospital" id="hospital" readonly>
                                </div>
                            </div>
                        
                            <div class="col-lg-6 ps-2">
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <label class="text-danger ls-1 me-2">From : </label>
                                    </div>
                                        <input class="form-control text-dark fw-bold ls-1 fs-6" placeholder="Start Date" name="start_date" id="start_date" readonly>

                                    <div class="input-group-append ms-2">
                                        <label class="text-danger ls-1 me-2">To : </label>
                                    </div>
                                    <input class="form-control text-dark fw-bold ls-1 fs-6" placeholder="End Date" name="end_date" id="end_date" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-2">

                        <div class="row col-lg-4 pb-3 pt-2">
                            <label class=" text-dark fw-bold ms-2 fs-5">Hospital Bank Account: </label>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control text-dark fs-5" name="" id=""  required>
                        </div>

                        <div class="row col-lg-4 pb-3 pt-2">
                            <label class=" text-dark fw-bold ms-2 fs-5">Hospital Account Number: </label>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control text-dark fs-5" name="" id=""  required>
                        </div>

                        <div class="row col-lg-4 pb-3 pt-2">
                            <label class=" text-dark fw-bold ms-2 fs-5">AGC Bank Account: </label>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control text-dark fs-5" name="" id=""  required>
                        </div>
                        
                        <div class="row col-lg-4 pb-3 pt-2">
                            <label class=" text-dark fw-bold ms-2 fs-5">AGC Account Number: </label>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control text-dark fs-5" name="" id=""  required>
                        </div>
                       
                        <div class="row col-lg-4 pb-3 pt-2">
                            <label class=" text-dark fw-bold ms-2 fs-5">AGC Account Name: </label>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control text-dark fs-5" name="" id=""  required>
                        </div>

                        <div class="row col-lg-4 pb-3 pt-2">
                            <label class=" text-dark fw-bold ms-2 fs-5">Payment Date: </label>
                        </div>
                        <div class="col-lg-8">
                            <input type="date" class="form-control text-dark fs-5" name="" id=""  required>
                        </div>
                        
                        <div class="row col-lg-4 pb-3 pt-2">
                            <label class=" text-dark fw-bold ms-2 fs-5">Payment Type: </label>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control text-dark fs-5" name="" id=""  required>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success px-3 ls-2 fs-5"><i class="mdi mdi-send"></i> SUBMIT</button>
            </div>
        </div>
    </div>
</div>