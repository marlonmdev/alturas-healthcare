<div class="modal fade" id="viewPaymentModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-cyan">
                <h4 class="modal-title ls-2 text-white">Payment Details</h4>
                <button type="button" class="btn-close btn-light" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        
            <div class="modal-body">
             <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
                <div class="container">
                    <div class="row pb-2">
                        <div class="row mb-3 pt-2">
                            <div class="col-lg-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span><i class="mdi mdi-hospital-building fs-3 text-danger pe-2"></i></span>
                                    </div>
                                        <input class="form-control text-dark fw-bold ls-1 fs-6" placeholder="" name="hospital_filtered" id="hospital_filtered" readonly>
                                </div>
                            </div>
                        
                            <div class="col-lg-6 ps-2">
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <label class="text-danger ls-1 me-2 pt-2">Posted On : </label>
                                    </div>
                                        <input class="form-control text-dark fw-bold ls-1 fs-6" placeholder="" name="start_date" id="start_date" readonly>

                                    <!-- <div class="input-group-append ms-2">
                                        <label class="text-danger ls-1 me-2 pt-2">To : </label>
                                    </div>
                                    <input class="form-control text-dark fw-bold ls-1 fs-6" placeholder="" name="end_date" id="end_date" readonly> -->
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row pt-2">

                        <div class="row col-lg-4 pb-3 pt-2">
                            <label class=" text-dark fw-bold ms-2 fs-5">Payment Number: </label>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control text-dark fs-5" name="payment-num" id="payment-num" readonly>
                        </div>
                        <div class="row col-lg-4 pb-3 pt-2">
                            <label class=" text-dark fw-bold ms-2 fs-5">Account Number: </label>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control text-dark fs-5" name="acc-number" id="acc-number" readonly>
                        </div>

                        <div class="row col-lg-4 pb-3 pt-2">
                            <label class=" text-dark fw-bold ms-2 fs-5">Account Name: </label>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control text-dark fs-5" name="acc-name" id="acc-name" readonly>
                        </div>

                        <div class="row col-lg-4 pb-3 pt-2">
                            <label class=" text-dark fw-bold ms-2 fs-5">Check Number: </label>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control text-dark fs-5" name="check-number" id="check-number" readonly>
                        </div>
                        
                        <div class="row col-lg-4 pb-3 pt-2">
                            <label class=" text-dark fw-bold ms-2 fs-5">Check Date: </label>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control text-dark fs-5" name="check-date" id="check-date" placeholder="Enter Date"  readonly>
                        </div>
                       
                        <div class="row col-lg-4 pb-3 pt-2">
                            <label class=" text-dark fw-bold ms-2 fs-5">Bank: </label>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control text-dark fs-5" name="bank" id="bank" readonly>
                        </div>

                        <div class="row col-lg-4 pb-3 pt-2">
                            <label class=" text-dark fw-bold ms-2 fs-5">Amount Paid: </label>
                        </div>
                        <div class="col-lg-8">
                            <input type="number" class="form-control text-dark fs-5" name="amount-paid" id="amount-paid" readonly>
                        </div>

                        <div class="row col-lg-4 pb-3 pt-2">
                            <label class=" text-dark fw-bold ms-2 fs-5">Billing Date: </label>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control text-dark fs-5" name="c-billed-date" id="c-billed-date" readonly>
                        </div>

                        <div class="row col-lg-4 pb-3 pt-2">
                            <label class=" text-dark fw-bold ms-2 fs-5">Covered LOA & NOA Number: </label>
                        </div>
                        <div class="col-lg-8">
                            <textarea class="form-control text-dark fs-5" id="textbox" value="" readonly></textarea>
                        </div>

                        <div class="col-lg-8 pt-2">
                            <label class="fw-bold text-dark fs-5">Check Voucher Image : </label>
                            <img src="" id="supporting-docu" style="width: 750px;height: 250px;">
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>   
        </div>
    </div>
    <style type="text/css">
		#textbox {
			width: 500px;
			height: 100px;
			overflow-y: scroll;
			resize: none;
			border: 1px solid #ccc;
			padding: 5px;
		}
	</style>
</div>