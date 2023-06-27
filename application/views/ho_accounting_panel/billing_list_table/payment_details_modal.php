<div class="modal fade" id="addPaymentModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-cyan">
                <h4 class="modal-title ls-2 text-dark">Payment No : <span name="p-payment-no" id="p-payment-no"></span></h4>
                <button type="button" class="btn-close btn-light" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        <form id="payment_details_form" enctype="multipart/form-data">
            <div class="modal-body">
             <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
             <input type="hidden" name="pd-payment-no" id="pd-payment-no">
                <div class="container">
                    <div class="row pb-2">
                        <div class="row mb-3 pt-2">
                            <div class="col-lg-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span><i class="mdi mdi-hospital-building fs-3 text-danger pe-2"></i></span>
                                    </div>
                                        <div id="hp-name-con">

                                        </div>
                                </div>
                            </div>
                        
                            <div class="col-lg-6">
                                <div class="input-group">
                                    <div class="input-group-prepend pt-2">
                                        <span class="text-danger fs-5 fw-bold pe-2">Total Payable : </span>
                                    </div>
                                        <input class="form-control text-dark fw-bold ls-1 fs-5" name="p-total-bill" id="p-total-bill" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="fw-bold fs-5 text-center">
                        <label>PAYMENT DETAILS</label>
                    </div>
                    <div class="row pt-2">

                        <div class="row col-lg-4 pb-3 pt-2">
                            <label class=" text-dark fw-bold ms-2 fs-5"><span class="text-danger">*</span> Account Number: </label>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control text-dark fs-5" name="acc-number" id="acc-number">
                            <span id="acc-number-error" class="text-danger"></span>
                        </div>

                        <div class="row col-lg-4 pb-3 pt-2">
                            <label class=" text-dark fw-bold ms-2 fs-5"><span class="text-danger">*</span> Account Name: </label>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control text-dark fs-5" name="acc-name" id="acc-name"  oninput="convertToUppercase(this)">
                            <span id="acc-name-error" class="text-danger"></span>
                        </div>

                        <div class="row col-lg-4 pb-3 pt-2">
                            <label class=" text-dark fw-bold ms-2 fs-5"><span class="text-danger">*</span> Check Number: </label>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control text-dark fs-5" name="check-number" id="check-number">
                            <span id="check-number-error" class="text-danger"></span>
                        </div>
                        
                        <div class="row col-lg-4 pb-3 pt-2">
                            <label class=" text-dark fw-bold ms-2 fs-5"><span class="text-danger">*</span> Check Date: </label>
                        </div>
                        <div class="col-lg-8">
                            <input type="date" class="form-control text-dark fs-5" name="check-date" id="check-date" placeholder="Enter Date" >
                            <span id="check-date-error" class="text-danger"></span>
                        </div>
                       
                        <div class="row col-lg-4 pb-3 pt-2">
                            <label class=" text-dark fw-bold ms-2 fs-5"><span class="text-danger">*</span> Bank: </label>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control text-dark fs-5" name="bank" id="bank" oninput="convertToUppercase(this)">
                            <span id="bank-error" class="text-danger"></span>
                        </div>

                        <div class="row col-lg-4 pb-3 pt-2">
                            <label class=" text-dark fw-bold ms-2 fs-5"><span class="text-danger">*</span> Amount Paid: </label>
                        </div>
                        <div class="col-lg-8">
                            <input type="number" step="any" class="form-control text-dark fs-5" name="amount-paid" id="amount-paid" oninput="validateNumberInputs()">
                            <span id="paid-error" class="text-danger"></span>
                        </div>

                        <div class="row col-lg-4 pb-3 pt-2">
                            <label class=" text-dark fw-bold ms-2 fs-5"><span class="text-danger">*</span> Supporting Document (CV): </label>
                        </div>
                        <div class="col-lg-8">
                            <input type="file" class="form-control text-dark fs-5" accept=".pdf, image/*" name="supporting-docu" id="supporting-docu" onchange="previewPdfFile('supporting-docu')">
                            <small class="text-danger">( Accepts images and pdf )</small>
                            <span id="file-error" class="text-danger"></span>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success px-3 ls-2 fs-5" id="submit-btn"><i class="mdi mdi-send"></i> SUBMIT</button>
            </div>
        </form>  
        </div>
    </div>
</div>
<script>
    const convertToUppercase = (input) => {
        input.value = input.value.toUpperCase();
    }

    const formatNumber = (input) => {
        // / Remove existing commas from the input value
        var value = input.value.replace(/,/g, '');

        // Parse the input value as a number
        var number = parseFloat(value);

        // Check if the input is a valid number
        if (!isNaN(number)) {
            // Format the number with commas
            var formattedNumber = number.toLocaleString();
            
            // Update the input value with the formatted number
            input.value = formattedNumber;
        } else {
            // If the input is not a valid number, set the value to empty string
            input.value = '';
        }
    }
</script>