<!-- Start of Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
        <div class="row">
        <div class="col-12 d-flex no-block flex-column flex-sm-row align-items-left">
            <?php if($type == 'unpaid') {
                    $header = 'Receivables Details';
            }else {
                    $header = 'Paid Charges Details';
            }
                ?>
            <h4 class="page-title"><i class="mdi mdi-format-line-style"></i> <?php echo $header; ?> [ <span class="text-info fs-5"><?php echo $charging_no; ?></span> ]</h4>
            <div class="ms-auto text-end order-first order-sm-last">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item">Head Office Accounting</li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Business Unit Charging
                    </li>
                    </ol>
                </nav>
            </div>
        </div>
        </div>
    </div><hr>
    <!-- End Bread crumb and right sidebar toggle -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-2 pb-1">
                <div class="input-group">
                    <a href="JavaScript:void(0)" onclick="goback()" class="btn btn-dark" data-bs-toggle="tooltip" title="Click to Go Back">
                        <strong class="ls-2" style="vertical-align:middle">
                            <i class="mdi mdi-arrow-left-bold"></i> Go Back
                        </strong>
                    </a>
                </div>
            </div>
            <div class="col-lg-2 pt-1 offset-8">
                <button class="btn btn-danger btn-sm fs-5" type="button" id="print-btn" onclick="printBUCharging()" title="Click to Print PDF"><i class="mdi mdi-printer"></i> Print</button>
            </div>
        </div>
        <br>
        <form id="submitPaidForm" enctype="multipart/form-data">
            <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
            <input value="<?php echo $charging_no; ?>" type="hidden" id="charging-no" name="charging-no">
            <input value="<?php echo $business_unit; ?>" type="hidden" id="business-unit">
            <input value="<?php echo $type; ?>" type="hidden" id="type-data">
            <div class="card bg-light">
                <div class="card-body">
                    <?php echo $table; ?>
                </div>
            </div>
            <?php if($type == 'unpaid') { ?>
                <div class="ps-2 pe-2" id="paidFormSubmit">
                    <small class="text-danger">Please upload the supporting document ( Accept Images and PDF )</small>
                    <div class="row border ps-2 pe-2 pt-4 pb-2">
                        <div class="row col-lg-2 pb-3 pt-2 pe-2">
                            <label class=" text-dark fw-bold ms-2 fs-5"><span class="text-danger">*</span> Supporting Document : </label>
                        </div>
                        <div class="col-lg-5">
                            <input type="file" class="form-control text-dark fs-5" accept=".pdf, image/*" name="supporting-docu" id="supporting-docu" onchange="previewPdfFile('supporting-docu')" required>
                            <span id="file-error" class="text-danger"></span>
                        </div>
                        <div class="col-lg-2">
                            <button class="btn btn-success rounded-pill btn-lg fs-6" type="submit" id="submitTaggedPaid" title="Click to Tag as Paid"><i class="mdi mdi-send"></i> Submit</button>
                        </div>
                    </div>
               
            <?php }else { ?>
                <div class="ps-2 pe-2" id="paidFormSubmit">
                </div>
            <?php } ?>
            <?php include 'view_check_voucher.php'; ?>
        </form>
    </div> 
    <?php include 'view_charge_details.php'; ?>
</div>
<script>
    const baseUrl = '<?php echo base_url(); ?>';
    $(document).ready(function(){
        const form = document.querySelector('#submitPaidForm');

        $('#submitPaidForm').submit(function(event){
            event.preventDefault();

            if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }
            $.confirm({
                title: '<strong>Confirmation!</strong>',
                content: 'Are you sure? Please review before you proceed.',
                type: 'blue',
                buttons: {
                    confirm: {
                        text: 'Yes',
                        btnClass: 'btn-blue',
                        action: function(){
                            const paidForm = $('#submitPaidForm')[0];
                            const formdata = new FormData(paidForm);

                            $.ajax({
                                url: `${baseUrl}head-office-accounting/charging/bu-tag-as-paid`,
                                method: "POST",
                                data: formdata,
                                dataType: "json",
                                processData: false,
                                contentType: false,
                                success: function(response){
                                    const {
                                        token, charging_no, status, message, image_error
                                    } = response;

                                    if(status == 'validation-error'){
                                        if(image_error != ''){
                                            $("#file-error").html(image_error);
                                            $("#supporting-docu").addClass('is-invalid');
                                        }else{
                                            $("#file-error").html("");
                                            $("#supporting-docu").removeClass('is-invalid');
                                        }
                                    }else if(status == 'success'){
                                        swal({
                                            title: 'Success',
                                            text: message,
                                            timer: 5000,
                                            showConfirmButton: false,
                                            type: 'success'
                                        });
                                        printPaidBuCharge(charging_no);

                                        window.location.href = `${baseUrl}head-office-accounting/charging/business-unit/paid`;
                                    }else if(status == 'failed'){
                                        swal({
                                            title: 'Error',
                                            text: message,
                                            timer: 3000,
                                            showConfirmButton: true,
                                            type: 'error'
                                        });
                                    }
                                }
                            }); 
                        },
                    },
                    cancel: {
                        btnClass: 'btn-dark',
                        action: function() {
                            // close dialog
                        }
                    },
                }
            });
        });
    });

    const goback = () =>{
        window.history.back();
    }

    const printPaidBuCharge = (charging_no) => {
        var base_url = `${baseUrl}`;
        window.open(base_url + "printBUCharge/pdfPaidCharges/" + btoa(charging_no), '_blank');
    }

    const printBUCharging = () => {
        const charging_no = document.querySelector('#charging-no').value;
        const b_units = document.querySelector('#business-unit').value;
        const type = document.querySelector('#type-data').value;

        var base_url = `${baseUrl}`;
        window.open(base_url + "printBUCharge/pdfReceivables-Charging/" + btoa(b_units) + "/" + btoa(charging_no) + "/" + btoa(type), '_blank');
    }

    let pdfinput = "";
    const  previewPdfFile = (pdf_input) => {
        pdfinput = pdf_input;
        let pdfFileInput = document.getElementById(pdf_input);
        let pdfFile = pdfFileInput.files[0];
        let reader = new FileReader();
        if(pdfFile){
            $('#viewCVModal').modal('show');
            $('#header').hide();
            reader.onload = function(event) {
            let dataURL = event.target.result;
            let iframe = document.querySelector('#pdf-cv-viewer');
            iframe.src = dataURL;
        };
            reader.readAsDataURL(pdfFile);
        }
    }

    const viewChargeDetails = (billing_id) => {
        $.ajax({
            url: `${baseUrl}head-office-accounting/charging/view-details`,
            type: 'GET',
            data: {
                'token' : '<?php echo $this->security->get_csrf_hash(); ?>',
                'billing_id' : billing_id
            },
            success: function(data){
                const res = JSON.parse(data);
                const {
                    token, payment_no, billing_no, loa_noa_no, percentage, before_mbl, net_bill, company_charge, personal_charge, cash_advance, after_mbl, billed_on
                } = res;

                $('#viewDetailsModal').modal('show');
                $('#payment-no').html(payment_no);
                $('#billing-no').html(billing_no);
                $('#loa-noa-no').html(loa_noa_no);
                $('#percentage').html(percentage);
                $('#current-mbl').html(before_mbl);
                $('#hospital-bill').html(net_bill);
                $('#company-charge').html(company_charge);
                $('#personal-charge').html(personal_charge);
                $('#cash-advance').html(cash_advance);
                $('#remaining-mbl').html(after_mbl);
                $('#billed-on').html(billed_on);
            }
        });
    }

</script>