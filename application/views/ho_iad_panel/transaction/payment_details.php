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

          <div class="row pt-2">
            <div class="row col-lg-4 pb-3 pt-2">
              <label class=" text-dark fw-bold ms-2 fs-5">Payment Number :</label>
            </div>
            <div class="col-lg-8">
              <input type="text" class="form-control text-dark fs-5" id="payment-num" readonly>
            </div>
            <div class="row col-lg-4 pb-3 pt-2">
              <label class=" text-dark fw-bold ms-2 fs-5">Account Number :</label>
            </div>
            <div class="col-lg-8">
              <input type="text" class="form-control text-dark fs-5" id="acc-number" readonly>
            </div>
            <div class="row col-lg-4 pb-3 pt-2">
              <label class=" text-dark fw-bold ms-2 fs-5">Account Name :</label>
            </div>
            <div class="col-lg-8">
              <input type="text" class="form-control text-dark fs-5" id="acc-name" readonly>
            </div>
            <div class="row col-lg-4 pb-3 pt-2">
              <label class=" text-dark fw-bold ms-2 fs-5">Check Number :</label>
            </div>
            <div class="col-lg-8">
              <input type="text" class="form-control text-dark fs-5" id="check-number" readonly>
            </div>    
            <div class="row col-lg-4 pb-3 pt-2">
              <label class=" text-dark fw-bold ms-2 fs-5">Check Date :</label>
            </div>
            <div class="col-lg-8">
              <input type="text" class="form-control text-dark fs-5" id="check-date" placeholder="Enter Date"  readonly>
            </div>
            <div class="row col-lg-4 pb-3 pt-2">
              <label class=" text-dark fw-bold ms-2 fs-5">Bank :</label>
            </div>
            <div class="col-lg-8">
              <input type="text" class="form-control text-dark fs-5" id="bank" readonly>
            </div>
            <div class="row col-lg-4 pb-3 pt-2">
              <label class=" text-dark fw-bold ms-2 fs-5">Check Amount :</label>
            </div>
            <div class="col-lg-8">
              <input type="number" class="form-control text-dark fs-5" id="amount-paid" readonly>
            </div>
            <div class="row col-lg-4 pb-3 pt-2">
              <label class=" text-dark fw-bold ms-2 fs-5">Type of Request :</label>
            </div>
            <div class="col-lg-8">
              <input type="text" class="form-control text-dark fs-5" id="type-of-request" readonly>
            </div>
          </div>

          <div class="modal-footer">
		        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">x Close</button>
		      </div>

        </div>
      </div> 
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
