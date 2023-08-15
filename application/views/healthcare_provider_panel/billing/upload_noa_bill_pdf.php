<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title ls-2">Upload PDF Billing</h4>
                <div class="ms-auto text-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">Healthcare Provider</li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Upload PDF
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- End Bread crumb and right sidebar toggle -->
    <hr>
    <div class="container-fluid" id="container-div">
        <!-- Go Back to Previous Page -->
        <div class="col-12 mb-4 mt-0">
            <form method="POST" id="go-back" action="<?php echo base_url(); ?>healthcare-provider/billing/search">
                <div class="input-group">
                    <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="healthcard_no" value="<?= $healthcard_no ?>">
                    <button type="submit" class="btn btn-outline-dark" data-bs-toggle="tooltip"
                        title="Click to Go Back">
                        <strong class="ls-2" style="vertical-align:middle">
                            <i class="mdi mdi-arrow-left-bold"></i> Go Back
                        </strong>
                    </button>
                </div>
            </form>
        </div>

        <div class="row">

            <div class="col-12 mb-3">
                <ul class="nav nav-tabs mb-4" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active"
                            href="<?php echo base_url(); ?>healthcare-provider/billing/bill-noa/upload-pdf/<?= $noa_id ?>"
                            role="tab">
                            <span class="hidden-sm-up"></span>
                            <span class="hidden-xs-down fs-5 font-bold">Final Bill</span>
                        </a>
                    </li>
                    <li class="nav-item" id="initial_bill" hidden>
                        <a class="nav-link"
                            href="<?php echo base_url(); ?>healthcare-provider/billing/initial-bill-noa/upload-pdf/<?= $noa_id ?>"
                            role="tab">
                            <span class="hidden-sm-up"></span>
                            <span class="hidden-xs-down fs-5 font-bold">Initial Bill</span>
                        </a>
                    </li>

                </ul>

                <div class="row">

                    <div class="col-lg-4">
                    </div>
                    <div class="col-lg-4">
                        <!-- <label class="form-label fs-5 ls-1">Remaining MBL Balance</label> -->
                        <div class="input-group">
                            <label class="form-label fs-5 ls-1 pt-1">MBL Balance</label>
                            <span class="input-group-text bg-cyan text-white ms-2">&#8369;</span>
                            <input type="text" class="form-control fw-bold ls-1" id="remaining-balance"
                                name="remaining-balance" value="<?= number_format($remaining_balance,2) ?>" readonly>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- <label class="form-label fs-5 ls-1" id="net_bill_label">Final Bill</label> -->
                        <div class="input-group">
                            <label class="form-label fs-5 ls-1 pt-1" id="net_bill_label">Final Bill</label>
                            <span class="input-group-text bg-cyan text-white ms-2">&#8369;</span>
                            <input type="text" class="form-control fw-bold ls-1" id="net-bill" name="net-bill"
                                value="0.00" readonly>
                        </div>
                    </div>

                    <!-- Move the above two divs to the end -->
                </div>


            </div>
            <!-- Content for the "final_bill" tab pane -->
            <form action="<?php echo base_url();?>healthcare-provider/billing/bill-noa/upload-pdf/<?= $noa_id ?>/submit"
                id="pdfBillingForm" enctype="multipart/form-data" class="needs-validation" novalidate>
                <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
                <input type="hidden" name="billing-no" value="<?= $billing_no ?>">
                <!-- <input type="text" class="form-control fw-bold ls-1" id="net-bill" name="net-bill" value="0.00" hidden> -->
                <div class="card">
                    <div class="card-body shadow">
                        <div class="row mt-3">
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-secondary fs-5 ls-1">
                                                Patient's Name: <span class="text-info"><?= $patient_name ?></span>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-secondary fs-5 ls-1">
                                                NOA No. : <span class="text-info"><?= $noa_no ?></span>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-secondary fs-5 ls-1">
                                                Billing No. : <span class="text-info"><?= $billing_no ?></span>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row pt-3">
                            <div class="col-lg-6">
                                <label class="fw-bold fs-5 ls-1" id="initial_btn_label">
                                    <i class="mdi mdi-asterisk text-danger ms-1"></i> Upload final Billing
                                </label>
                                <input type="file" class="form-control" name="pdf-file" id="pdf-file"
                                    accept="application/pdf" onchange="previewPdfFile('pdf-file')" required>
                                <div class="invalid-feedback fs-6">
                                    PDF File is required
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label class="fw-bold fs-5 ls-1" id="">
                                    <i class="mdi mdi-asterisk text-danger ms-1"></i> Upload Itemized Billing
                                </label>
                                <input type="file" class="form-control" name="itemize-pdf-file" id="itemize-pdf-file"
                                    accept="application/pdf" onchange="previewPdfFile('itemize-pdf-file')" required>
                                <div class="invalid-feedback fs-6">
                                    PDF File is required
                                </div>
                            </div>
                        </div>

                        <div class="row  pt-3" id="final_diagnosis">
                            <div class="col-lg-6">
                                <label class="fw-bold fs-5 ls-1" id="initial_btn_label">
                                    <i class="mdi mdi-asterisk text-danger ms-1"></i> Upload Final Diagnosis/Operation
                                </label>
                                <input type="file" class="form-control" name="Final-Diagnosis" id="Final-Diagnosis"
                                    accept="application/pdf" onchange="previewPdfFile('Final-Diagnosis')">
                                <div class="invalid-feedback fs-6">
                                    PDF File is required
                                </div>
                            </div>

                            <div class="col-lg-6 ">
                                <label class="fw-bold fs-5 ls-1" id="initial_btn_label">
                                    Upload Medical Abstract(Optional)
                                </label>
                                <input type="file" class="form-control" name="Medical-Abstract" id="Medical-Abstract"
                                    accept="image/jpeg,image/png" onchange="previewPdfFile('Medical-Abstract')">

                            </div>

                            <div class="col-lg-6 mt-3" >
                                <label class="fw-bold fs-5 ls-1" id="initial_btn_label">
                                    <input type="checkbox" id="take-home-checkbox"> Upload Prescription Image
                                </label>
                                <input type="file" class="form-control" name="Prescription" id="Prescription"
                                    accept="image/jpeg,image/png" >
                                <div class="invalid-feedback fs-6">
                                    Prescription Image is required
                                </div>
                            </div>

                            <!-- <div class="col-lg-6 mt-3" id="med-services-div">
                                <label class="fw-bold fs-5 ls-1" id="initial_btn_label">
                                    Select Take Home Medicines
                                </label>
                                <div id="med-services-wrapper"></div>
                                <em id="med-services-error" class="text-danger"></em>
                            </div> -->

                            <div class="col-lg-6 mt-3" id="med-services-div">
                                <label class="colored-label"><i class="mdi mdi-asterisk text-danger"></i> Select Take Home Medicines <small class="text-danger"> *Note: Press Tab or Enter to Add More
                                Medicines</small></label>
                                <input class="custom-input" id="med-services" name="med-services"
                                    placeholder="Type and press Enter|Tab">
                                <em id="med-services-error" class="text-danger"></em>
                            </div>

                        </div>


                        <div class="row mt-3">
                            <div class="d-flex justify-content-center align-items-center mt-2">
                                <button type="submit" class="btn btn-info text-white btn-lg ls-2 me-3" id="upload-btn">
                                    <i class="mdi mdi-upload me-1"></i>UPLOAD
                                </button>
                                <button type="button" class="btn btn-dark text-white btn-lg ls-2"
                                    id="clear-btn">CLEAR</button>
                            </div>
                        </div>

                    </div>

                </div>
            </form>
        </div>

        <?php include 'view_pdf_bill_modal.php'; ?>

    </div>
</div>
<style>
    .custom-input {
  width: 100%;
}
</style>
<script>
const baseUrl = `<?php echo base_url(); ?>`;
const admission_date = '<?= isset($admission_date) ? $admission_date : null ?>';
var re_upload = '<?= isset($re_upload)?$re_upload : false?>';
var prev_billing = '<?= isset($prev_billing) ? $prev_billing : null ?>';
var noa_no = '<?= $noa_no ?>';
var noa_id = "<?php echo $noa_id; ?>";

var patient_name = "<?= $patient_name ?>";
// console.log("admission_date",admission_date);
const mbl = parseFloat($('#remaining-balance').val().replace(/,/g, ''));
let net_bill = 0;
let pdfPreview = document.getElementById('pdf-viewer');
const form = document.querySelector('#pdfBillingForm');
let hospital_charges = "";
let att_doc_list = '';
let attending_doctors = {};

let json_final_charges = {};
let benefits_deductions = {};
let pdfinput = "";


$(document).ready(function() {

    if (re_upload) {
        $('#final_diagnosis').hide();
        $('#Final-Diagnosis').prop('required', false);
        $('#initial_bill').prop('hidden', true);
    } else {
        $('#Final-Diagnosis').prop('required', true);
        $('#initial_bill').prop('hidden', false);
    }

    $('#Prescription').hide();
    $('#med-services-div').hide();

    $('#clear-btn').on('click', function() {
        $('#pdfBillingForm')[0].reset();
    });

    $('#pdfBillingForm').on('reset', function() {
        $('#upload-btn').prop('disabled', false);
    });

    $('#cancel,#ccancel').on('click', function() {
        $('#net-bill').val('0.00');
        $('#' + pdfinput).val('');
    });
    // $('#Operation').prop("disabled",true);
    // $('#med-services-div').hide();
    // $.ajax({
    //     url: `${baseUrl}healthcare-provider/patient/get_takehome_meds`,
    //     type: "GET",
    //     data: {
    //         token: '<?php echo $this->security->get_csrf_hash(); ?>'
    //     },
    //     dataType: "json",
    //     success: function(response) {
    //         $('#med-services-wrapper').empty();
    //         $('#med-services-wrapper').append(response);
    //         $(".chosen-select").chosen({
    //             width: "100%",
    //             no_results_text: "Oops, nothing found!"
    //         });
    //     }
    // });

    take_home_meds();

    $('#take-home-checkbox').on('change', function() {
        if (this.checked) {
            $('#med-services').prop('disabled', false);
            $('#med-services').prop('required', true);
            $('#Prescription').prop('required', true);
            $('#Prescription').prop('disabled', false);
            $('#med-services-div').show();
            $('#Prescription').show();
        } else {
            $('#med-services').prop('disabled', true);
            $('#med-services').prop('required', false);
            $('#Prescription').prop('required', false);
            $('#Prescription').prop('disabled', true);
            $('#med-services-div').hide();
            $('#Prescription').hide();
        }
    });

    // $('#viewPDFBillModal').on('hidden.bs.modal', function (e) {

    //     if(!is_valid_noa || !is_valid_netbill || !is_valid_name){
    //         // window.location.reload();
    //         // $('#pdfBillingForm')[0].reset();
    //         // $('#initialpdfBillingForm')[0].reset();
    //     }
    //     // console.log("is_valid_name",is_valid_name);
    //     // console.log("is_valid_noa",is_valid_noa);
    //     // console.log("is_valid_netbill",is_valid_netbill);
    //     is_valid_name = true;
    //     is_valid_noa = true;
    //     is_valid_netbill = true;
    // });


    //submit the form
    $('#pdfBillingForm').submit(function(event) {
        event.preventDefault();
        console.log('medicines', $('#med-services').val());
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        let formData = new FormData($(this)[0]);
        formData.append('hospital_bill_data', hospital_charges);
        formData.append('attending_doctors', attending_doctors);
        formData.append('att_doc_list', att_doc_list);
        formData.append('json_final_charges', json_final_charges);
        formData.append('benefits_deductions', benefits_deductions);

        formData.append('net_bill', $('#net-bill').val());
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function(response) {
                const {
                    token,
                    status,
                    message,
                    billing_id
                } = response;

                if (status == 'success') {

                    swal({
                        title: 'Success',
                        text: 'Final Bill Uploaded Successfully...',
                        timer: 1000,
                        showConfirmButton: false,
                        type: 'success'
                    }).then(function() {
                        // window.location.replace(`${baseUrl}healthcare-provider/billing`);
                        // window.location.href = `${baseUrl}healthcare-provider/billing`;
                        window.history.replaceState({}, document.title,
                            `${baseUrl}healthcare-provider/billing`);
                        $('#go-back').submit();
                        // window.location.href = `${baseUrl}healthcare-provider/billing/bill-noa/upload-pdf/${billing_id}/success`;
                        //window.location.href = `${baseUrl}healthcare-provider/billing/search`;
                    });

                } else {
                    swal({
                        title: 'Failed',
                        text: message,
                        timer: 3000,
                        showConfirmButton: false,
                        type: 'error'
                    });
                }
            }
        });

    });
    ['pdf-file', 'itemize-pdf-file'].forEach((pdfid) => handlePDFChange(pdfid));
});

async function processPDF(pdfFileInput) {
    return new Promise((resolve, reject) => {
        let reader = new FileReader();

        reader.onload = async function() {
            let typedarray = new Uint8Array(this.result);
            try {
                let pdf = await pdfjsLib.getDocument(typedarray).promise;
                let numPages = pdf.numPages;
                let promises = [];

                console.log("number of pages", numPages);

                for (let page = 1; page <= numPages; page++) {
                    let currentPage = await pdf.getPage(page);
                    let textContent = await currentPage.getTextContent();

                    const sortedItems = textContent.items
                        .map(function(item) {
                            return {
                                text: item.str.toLowerCase(),
                                x: item.transform[4],
                                y: item.transform[5]
                            };
                        })
                        .sort(function(a, b) {
                            if (Math.abs(a.y - b.y) < 5) {
                                return a.x - b.x;
                            } else {
                                return b.y - a.y;
                            }
                        })
                        .reduce(function(groups, item) {
                            const lastGroup = groups[groups.length - 1];
                            if (lastGroup && Math.abs(lastGroup.y - item.y) < 5) {
                                lastGroup.text += ' ' + item.text;
                            } else {
                                groups.push({
                                    text: item.text,
                                    x: item.x,
                                    y: item.y
                                });
                            }
                            return groups;
                        }, []);



                    promises.push(sortedItems);
                }
                resolve(promises); // Resolve the promise with the collected results
            } catch (error) {
                reject(error); // Reject the promise with the encountered error
            }
        };

        if (pdfFileInput.files[0]) {
            reader.readAsArrayBuffer(pdfFileInput.files[0]);
        }
    });
}

async function handlePDFChange(pdfid) {
    let pdfFileInput = document.getElementById(pdfid);

    pdfFileInput.addEventListener('change', async function() {
        try {
            let promises = await processPDF(pdfFileInput);
            let finalItems = promises.flat();
            console.log(finalItems);

            let finalResult = finalItems.reduce(function(result, item) {
                const pattern = /\.{2,}(?!\.)/g;
                return (result = result + '\n' + item.text.replace(pattern, ''));
            }, '').trim();
            // Add conditions for extracted text
            //validate loa 
            const valid_noa = /admission\s{1,}no:/i;
            if (!finalResult.match(valid_noa)) {
                is_valid_noa = false;
                $('#upload-btn').prop('disabled', true);
                setTimeout(function() {
                    $.alert({
                        title: `<h3 style='font-weight: bold; color: #dc3545; margin-top: 0;'>ERROR</h3>`,
                        content: `<div style='font-size: 16px; color: #333;'>We sincerely apologize for any inconvenience caused and would like to inform you that the uploaded PDF does not meet the criteria of being an ${(pdfid !== 'pdf-file'?'itemized bill of':'')} NOA (Notice of Admission). We highly value your understanding in this matter and extend our heartfelt gratitude for your unwavering cooperation.</div>`,
                        type: "red",
                        buttons: {
                            ok: {
                                text: "OK",
                                btnClass: "btn-danger",
                                action: function() {
                                    (pdfid === 'pdf-file') ? $('#pdf-file').val(''):
                                        $('#itemize-pdf-file').val('')
                                }
                                // window.location.reload();
                            },
                        },
                    });
                }, 1000); // Delay of 2000 milliseconds (2 seconds)
            } else {
                const patient_pattern = /patient name:\s(.*?)\admission/si;
                const matches_3 = finalResult.match(patient_pattern);
                const result_3 = matches_3 ? matches_3[1] : null;
                console.log("final result", finalResult);
                console.log("patient name", result_3);
                console.log('final text', final_text(finalResult));

                if (patient_name.length) {
                    is_valid_noa = true;
                    const names = patient_name.toLowerCase().split(' ').filter(Boolean);

                    let removedElement = "";

                    if (names[names.length - 1] === ".jr") {
                        removedElement = names.splice(names.length - 2, 1);
                    } else {
                        removedElement = names.splice(names.length - 1, 1);
                    }
                    const mem_name = removedElement + ", " + names.join(' ');

                    if (!validate_name(result_3, mem_name)) {
                        is_valid_name = false;
                        $('#upload-btn').prop('disabled', true);

                        $.alert({
                            title: `<h3 style='font-weight: bold; color: #dc3545; margin-top: 0;'>Error</h3>`,
                            content: `<div style='font-size: 16px; color: #333;'>The uploaded PDF bill does not match the member's name. Please ensure that you have uploaded the correct PDF bill.</div>`,
                            type: "red",
                            buttons: {
                                ok: {
                                    text: "OK",
                                    btnClass: "btn-danger",
                                    action: function() {
                                        (pdfid === 'pdf-file') ? $('#pdf-file').val(''): $(
                                            '#itemize-pdf-file').val('')
                                    }
                                },
                            },
                        });
                    } else {
                        is_valid_name = true;

                        //   if(pdfid === 'pdf-file'){
                        if (pdfid === 'pdf-file') {
                            var itemsPattern = /\s+date\s+description\s+qty\s+unit price\s+amount/;
                            const regex = /please pay for this amount\s*\.*\s*([\d,\.]+)/i;
                            const hosp_plan = /hospitalization plan:\s(.*?)\sage/si;
                            const hpmatch = finalResult.match(hosp_plan);
                            const match = finalResult.match(regex);
                            let subtotalValue = 0;
                            console.log("match", hpmatch[1]);

                            if (match && !itemsPattern.test(finalResult)) {
                                const doc_pattern = /hospital charges(.*?)please pay for this amount/si;
                                const matches_2 = finalResult.match(doc_pattern);
                                const result_2 = matches_2 ? matches_2[1] : null;
                                hospital_charges = result_2;

                                if (hpmatch[1].replace(/\s/g, "") !== 'self-pay') {
                                    benefits_deductions = JSON.stringify(get_ph_deduction(final_text(
                                        finalResult)));
                                } else {
                                    benefits_deductions = JSON.stringify(get_selfpay_deduction(
                                        final_text(finalResult)));
                                }

                                //   attending_doctors = get_doctors(final_text(finalResult));
                                attending_doctors = JSON.stringify(get_doctors(final_text(
                                    finalResult)));
                                console.log("doctors", attending_doctors);
                                console.log("hospital charges", hospital_charges);
                                console.log("JSON deduction", benefits_deductions);
                                subtotalValue = parseFloat(match[1].replace(/,/g, ""));
                                net_bill = subtotalValue;
                                //   console.log('match netbill',match[1]);
                                document.getElementsByName("net-bill")[0].value = match[1];
                                is_valid_netbill = true;
                                $('#upload-btn').prop('disabled', false);

                                if (parseFloat(net_bill) > mbl) {
                                    // $('#upload-btn').prop('disabled',true);
                                    setTimeout(function() {
                                        $.alert({
                                            title: `<h3 style='font-weight: bold; color: #dc3545; margin-top: 0;'>Warning</h3>`,
                                            content: "<div style='font-size: 16px; color: #333;'>The uploaded PDF Bill exceeds the patient's MBL balance.</div>",
                                            type: "red",
                                            buttons: {
                                                ok: {
                                                    text: "OK",
                                                    btnClass: "btn-danger",
                                                    // window.location.reload();
                                                },
                                            },
                                        });
                                    }, 1000); // Delay of 2000 milliseconds (2 seconds)
                                }

                            } else {

                                console.log("please pay for this amount is not found");
                                is_valid_netbill = false;
                                $('#upload-btn').prop('disabled', true);
                                $.alert({
                                    title: `<h3 style='font-weight: bold; color: #dc3545; margin-top: 0;'>Error</h3>`,
                                    content: "<div style='font-size: 16px; color: #333;'>We apologize for the inconvenience, but it appears that there was an issue with the uploaded PDF. Please review the PDF file and try again.</div>",
                                    type: "red",
                                    buttons: {
                                        ok: {
                                            text: "OK",
                                            btnClass: "btn-danger",
                                            action: function() {
                                                (pdfid === 'pdf-file') ? $('#pdf-file').val(
                                                    ''): $('#itemize-pdf-file').val('')
                                            }
                                        },
                                    },
                                });
                            }
                        } else {
                            var itemPattern = /\s+date\s+description\s+qty\s+unit price\s+amount/;

                            if (itemPattern.test(finalResult)) {
                                $('#upload-btn').prop('disabled', false);
                                get_all_item(final_text(finalResult));
                                json_final_charges = JSON.stringify(get_all_item(final_text(
                                    finalResult)));
                                console.log("JSON item", json_final_charges);

                            } else {
                                $('#upload-btn').prop('disabled', true);
                                $.alert({
                                    title: `<h3 style='font-weight: bold; color: #dc3545; margin-top: 0;'>Error</h3>`,
                                    content: "<div style='font-size: 16px; color: #333;'>We apologize for the inconvenience, but it appears that the uploaded pdf is not an itemized bill. Please review the PDF file and try again.</div>",
                                    type: "red",
                                    buttons: {
                                        ok: {
                                            text: "OK",
                                            btnClass: "btn-danger",
                                            action: function() {
                                                (pdfid === 'pdf-file') ? $('#pdf-file').val(
                                                    ''): $('#itemize-pdf-file').val('')
                                            }
                                        },
                                    },
                                });
                            }

                        }
                    }
                }
            }

            console.log("netbill", net_bill);
            console.log("mbl", mbl);
        } catch (error) {
            $.alert({
                title: `<h3 style='font-weight: bold; color: #dc3545; margin-top: 0;'>PDF Error</h3>`,
                content: "<div style='font-size: 16px; color: #333;'>Sorry, we encountered an issue with the uploaded PDF. Please check the file and try again. For any assistance, please don't hesitate to get in touch with our dedicated support team.</div>",
                type: "red",
                buttons: {
                    ok: {
                        text: "OK",
                        btnClass: "btn-danger",
                        action: function() {
                            (pdfid === 'pdf-file') ? $('#pdf-file').val(''): $(
                                '#itemize-pdf-file').val('')
                        }
                    },
                },
            });
        }
    });
}


const previewPdfFile = (pdf_input) => {
    pdfinput = pdf_input;
    let pdfFileInput = document.getElementById(pdf_input);
    let pdfFile = pdfFileInput.files[0];
    let reader = new FileReader();
    if (pdfFile) {
        $('#viewPDFBillModal').modal('show');
        if (pdfinput === "pdf-file" || pdfinput === "pdf-file-initial") {
            $('#billing_no').text('<?=$billing_no?>');
            $('#billing_no_holder').show();
        } else {
            $('#billing_no_holder').hide();
        }
        reader.onload = function(event) {
            let dataURL = event.target.result;
            let iframe = document.querySelector('#pdf-viewer');
            iframe.src = dataURL;
        };
        reader.readAsDataURL(pdfFile);
    }

};
const get_doctors = (lines) => {
    let include = false;
    let include2 = true;

    const liness = lines.split("\n");
    const filteredLines = liness.filter((line) => {
        if (include) {
            return true;
        }
        if (/\btotal\b/i.test(line)) {
            include = true;
        }
        return false;
    });

    const result = filteredLines.join("\n");

    if (result) {
        const lin = result.split("\n");

        const doctors = lin.filter(line => {
            if (include2) {
                if (/\bsubtotal\b/i.test(line)) {
                    include2 = false;
                    return false;
                }
                return true;
            }
            return false;
        });

        const doc = doctors.join("\n");
        return get_pro_fee(doc);
        const excludedTerms = ["gross", "discount", "vat", "professional fee"];

        const pattern = new RegExp("\\b(" + excludedTerms.join("|") + "|\\d{1,3}(?:,\\d{3})*(?:\\.\\d+)?)\\b",
            "gi");
        const excludedDoc = doc.replace(pattern, "");

        const pattern1 = /\n(\S+)/g;
        const modifiedDoc1 = excludedDoc.replace(pattern1, ' $1');

        const pattern2 = /^(.*\S)(\s*)$/gm;
        const modifiedDoc2 = modifiedDoc1.replace(pattern2, '$1;$2');

        att_doc_list = modifiedDoc2.replace(/\s+/g, ' ');
    }

};

const viewPDFBill = (pdf_bill, noa_no) => {
    $('#viewPDFBillModal').modal('show');
    $('#billing_no').html(noa_no);

    let pdfFile = `${baseUrl}uploads/pdf_bills/${pdf_bill}`;
    let fileExists = checkFileExists(pdfFile);
    console.log(pdf_bill);
    if (fileExists) {
        let xhr = new XMLHttpRequest();
        xhr.open('GET', pdfFile, true);
        xhr.responseType = 'blob';

        xhr.onload = function(e) {
            if (this.status == 200) {
                let blob = this.response;
                let reader = new FileReader();

                reader.onload = function(event) {
                    let dataURL = event.target.result;
                    let iframe = document.querySelector('#pdf-viewer');
                    iframe.src = dataURL;
                };
                reader.readAsDataURL(blob);
            }
        };
        xhr.send();
    }
}

const checkFileExists = (fileUrl) => {
    let xhr = new XMLHttpRequest();
    xhr.open('HEAD', fileUrl, false);
    xhr.send();

    return xhr.status == "200" ? true : false;
}

const showMedServices = () => {
    const medServices = document.querySelector('#med-services-div');

    if (loaType === "Consultation" || loaType === "") {
        medServices.className = "d-none";
        fileAttachment.className = "d-none";
    } else if (loaType === "Diagnostic Test") {
        medServices.className = "col-lg-7 col-sm-12 mb-2 d-block";
        fileAttachment.className = "form-group row d-block";
    }
}

const validateDateRange = () => {

    const billed_date = document.querySelector('#initial-date');
    const endDateInput = Date('m-y-d');
    const b_Date = new Date(billed_date.value);
    const year = b_Date.getFullYear();
    const month = String(b_Date.getMonth() + 1).padStart(2, '0');
    const day = String(b_Date.getDate()).padStart(2, '0');

    const final_date = year + month + day;
    // console.log('final date',final_date-admission_date);
    if (initial_net_bill_date) {
        console.log(final_date - initial_net_bill_date);
        if (final_date - initial_net_bill_date == 1) {
            return; // Don't do anything if either input is empty
        } else {
            // alert('End date must be greater than or equal to the start date');
            swal({
                title: 'Failed',
                text: 'Please be aware that the selected date may be either before or one day after the admission date. Take this into consideration when choosing the date.',
                showConfirmButton: true,
                type: 'error'
            });
            billed_date.value = '';
            return;
        }
    } else {
        console.log(final_date - admission_date);
        if (final_date - admission_date == 0) {

            return; // Don't do anything if either input is empty
        } else {
            // alert('End date must be greater than or equal to the start date');
            swal({
                title: 'Failed',
                text: 'Please be aware that the selected date may be either before or one day after the admission date. Take this into consideration when choosing the date.',
                showConfirmButton: true,
                type: 'error'
            });
            billed_date.value = '';
            return;
        }
    }



}

const final_text = (text) => {

    var searchTerms = [
        "ramiro community hospital",
        "0139 c. gallares street",
        "tel. no(s):",
        "patient name:",
        "hospitalization plan:",
        "attending doctor(s):",
        "patient address:",
        "room no.:",
        "date   description",
        "labesores, marian cacayan",
        "billing clerk",
        "please pay for",
        "important:",
        "statement",
        "hospital reserves",
        "incurred",
        "(philhealth and/or hmo)",
        "possible",
        "days upon receipt",
        "notice",
        "billed",
        "clave",
        "member",
        "page",
        "particulars",
        // Add more search terms as needed
    ];

    var lines = text.split("\n");
    var modifiedLines = lines.filter(function(line) {
        for (var i = 0; i < searchTerms.length; i++) {
            if (line.includes(searchTerms[i])) {
                return false;
            }
        }
        return true;
    });

    var modifiedText = modifiedLines.join("\n");
    return modifiedText;
    // console.log("final text",modifiedText);
}

const get_all_item = (result) => {
    const line1 = result.split("\n"); // Split input into an array of lines
    const data1 = line1.map(line => line.split(/\s{3,}/));
    console.log(data1);
    let include = true;

    const lin = result.split("\n");

    const texts = lin.filter(line => {

        if (include) {

            if (/\btotal\b/i.test(line)) {
                return false;
            }
            return true;

        }

        return false;
    });

    const text = texts.join("\n");
    let pushedArrays = [];
    const liness = text.split("\n"); // Split input into an array of lines
    const data = liness.map(line => line.split(/\s{3,}/)); // Split each line into an array of values
    let x = 0;

    let id_length_1 = 0;
    let id_length_5 = 0;
    const outputArray = data.map((arr, index) => {
        const currentLength = arr.length;
        const nextLength = index + 1 < data.length ? data[index + 1].length : 0;

        if (index === 0) {

            id_length_1 = index;
            return arr;
        } else {

            let appendedArray = [];

            if (currentLength === 1) {
                id_length_1 = index;
            }


            if (currentLength === 5) {
                appendedArray = [data[id_length_1][0], ...arr];
                id_length_5 = index;

            } else if (currentLength === 4) {
                appendedArray = [data[id_length_1][0], data[id_length_5][0], ...arr];

            } else {
                appendedArray = arr;
            }
            return appendedArray;

        }

    });
    // console.log("medicine",outputArray.filter(arr => arr.length !== 1));
    return outputArray.filter(arr => arr.length !== 1);
}

const get_pro_fee = (doc) => {
    const excludedTerms = ["gross", "discount", "vat", "professional fee"];
    const pattern = new RegExp("\\b(" + excludedTerms.join("|") + ")\\b", "gi");
    const excludedDoc = doc.replace(pattern, "");
    const lines = excludedDoc.split('\n');
    const data = lines.map(line => line.split(/\s{2,}/));
    const filter_data = data.map((arr) => arr.filter((element) => element !== ''));
    let id_length_1 = 0;
    let id_length_5 = 0;
    const outputArray = filter_data.map((arr, index) => {
        const currentLength = arr.length;

        if (index === 0) {
            id_length_1 = index;
            if (currentLength) {
                return arr;
            }

        } else {

            let appendedArray = [];
            let doc_name = [];

            if (currentLength === 5) {
                id_length_5 = index;
                appendedArray = [filter_data[index][0], filter_data[index][currentLength - 1]];
            } else if (currentLength === 1) {
                doc_name = filter_data[id_length_5][0] + " " + filter_data[index];
                return appendedArray = [doc_name, filter_data[id_length_5][4]];
            } else {
                appendedArray = arr;
            }

            if (index + 1 !== filter_data.length) {
                if (filter_data[index + 1].length !== 1) {
                    return appendedArray;
                }
            } else {
                return appendedArray;
            }

        }

    });
    console.log('outputArray', outputArray);
    return outputArray.filter(arr => arr !== undefined);
}

//   const get_deduction = (text) => {

//         // var lines = text.split("\n");
//         const final_text = text.replace(/[()]/g, '');
//         let include = false;
//         const regex = /subtotal([\s\S]*?)total/gi;
//         const matches = text.match(regex);
//         let data = [];
//         var deductions = matches.join("\n").split("\n");
//         console.log("matches",deductions);
//         const texts = deductions.filter(line => {
//         if (/\bsubtotal\b/i.test(line)) {
//             include = true;
//             return false;
//         }
//         if (/\btotal\b/i.test(line)) {
//             include = false;
//             return false;
//         }

//         if (include) {
//             return true;
//         }

//         return false;
//         });

//         // data = texts.map(line => line.split(/\s{3,}/));
//         data = texts.map(line => {
//             const appendedLine = line.replace(/(\d{1,3}(?:\s*,\s*\d{3})*(?:\.\d+)?)/g, ';$1').replace(/\s/g, '');
//             const splitLine = appendedLine.split(';').filter(item => item.trim() !== '');
//             return splitLine;
//         });

//         const outputArray = data.map((arr, index) => {
//         if(arr.length === 1){
//             return [...arr, '0'];
//         }else{
//             return [...arr];
//         }
//         });
//         console.log("deductions",outputArray);
//         return outputArray;
//     };
const get_ph_deduction = (text) => {

    // var lines = text.split("\n");
    const final_text = text.replace(/(\([\d,.]+\))/g, match => match.replace(/[()]/g, ''));
    console.log('final deduction', final_text);
    let include = false;
    const regex = /subtotal([\s\S]*?)total/gi;
    const matches = final_text.match(regex);

    const payregex = /payment([\s\S]*?)subtotal/gi;
    const paymatches = final_text.match(payregex);
    let data = [];
    let ptexts = [];

    if (paymatches !== null) {
        var paydeductions = paymatches.join("\n").split("\n");
        console.log("payment matches", deductions);

        ptexts = paydeductions.filter(line => {

            if (/\bpayment\b/i.test(line)) {
                include = true;
            }

            if (/\bsubtotal\b/i.test(line)) {
                include = false;
                return false;
            }

            if (include) {
                return true;
            }

            return false;

        });
    }
    if (matches !== null) {
        var deductions = matches.join("\n").split("\n");
        console.log("ph matches", deductions);
        const texts = deductions.filter(line => {

            if (/\bsubtotal\b/i.test(line)) {
                include = true;
                return false;
            }

            if (/\btotal\b/i.test(line)) {
                include = false;
                return false;
            }

            if (include) {
                return true;
            }

            return false;
        });

        // data = texts.map(line => line.split(/\s{2,}/));
        data = texts.map(line => {
            const appendedLine = line.replace(/(\d{1,3}(?:\s*,\s*\d{3})*(?:\.\d+)?)/g, ';$1').replace(/\s/g,
                '');
            const splitLine = appendedLine.split(';').filter(item => item.trim() !== '');
            return splitLine;
        });

        const pdata = ptexts.map(line => {
            const amount = line.split(/\s{2,}/).pop();
            return amount;
        });

        console.log('pdata', pdata);
        let ph_counter = 0;
        const outputArray = data.map((arr, index) => {
            if (arr.length === 1) {
                if (paymatches !== null) {
                    const py_amount = pdata[ph_counter];
                    ph_counter++;
                    return [...arr, py_amount];
                }
            } else {
                return [...arr];
            }
        });

        console.log("ph deductions", outputArray);
        return (outputArray.length > 1) ? outputArray : [];
    }
    return null;
};

const get_selfpay_deduction = (text) => {

    // var lines = text.split("\n");
    const final_text = text.replace(/(\([\d,.]+\))/g, match => match.replace(/[()]/g, ''));
    console.log('final deduction', final_text);
    let include = false;
    const regex = /subtotal([\s\S]*?)total/gi;
    const matches = final_text.match(regex);

    let data = [];
    if (matches !== null) {
        var deductions = matches.join("\n").split("\n");
        // console.log("se",deductions);
        const texts = deductions.filter(line => {

            if (/\bsubtotal\b/i.test(line)) {
                include = true;
                return false;
            }

            if (/\btotal\b/i.test(line)) {
                include = false;
                // return false;
            }

            if (include) {
                return true;
            }

            return false;
        });
        console.log('self text', texts);
        // data = texts.map(line => line.split(/\s{2,}/));
        data = texts.map(line => {
            const appendedLine = line.replace(/(\d{1,3}(?:\s*,\s*\d{3})*(?:\.\d+)?)/g, ';$1').replace(/\s/g,
                '');
            const splitLine = appendedLine.split(';').filter(item => item.trim() !== '');
            return splitLine;
        });

        const outputArray = data.map((arr, index) => {
            if (arr.length > 1) {
                return [...arr];
            }
        });

        console.log("self pay deductions", (outputArray.length > 1) ? outputArray : []);
        return (outputArray.length > 1) ? outputArray : [];
    }
    return null;
};

function validate_name(patient, member) {
    // Remove leading and trailing spaces
    var trimmedStr1 = patient.replace(/\s/g, "");
    var trimmedStr2 = member.replace(/\s/g, "");
    console.log("trimmedStr1", trimmedStr1);
    console.log("trimmedStr2", trimmedStr2);
    // Compare the strings 
    return trimmedStr1 === trimmedStr2;
}

const take_home_meds = () => {
    const intput_service = [];
    const input = document.getElementById('med-services');
    const token = `<?php echo $this->security->get_csrf_hash(); ?>`;
    $.ajax({
        url: `${baseUrl}healthcare-provider/patient/get_takehome_meds`,
        type: 'GET',
        dataType: 'json',
        data: {
            token: token
        },
        success: function(response) {
            console.log(response); // Check the response in the console

            response.forEach(function(item) {
                // Build the tag text, including the description and price
                const tagText = `${item.ctyp_description} - ₱${item.meds_price}`;
                // Optionally, you can directly add the tag to Tagify using addTags method
                const tagData = {
                    value: tagText,
                    tagid: item.med_no,
                    // Use tagText as the visible text
                    // data: {
                    //   price: item.ctyp_price,
                    //   // You can add any other additional data you need here
                    // },
                };
                intput_service.push(tagData);
            });

            // Initialize Tagify with the intput_service array containing both tagData and tag text
            // console.log('tagifiy',tagify);
            // if (tagify) {
            //     tagify.settings.whitelist = intput_service;
            //     tagify.settings.enforceWhitelist = true;
            //     // tagify.dropdown.show.call(tagify, ''); // Refresh the dropdown to reflect the new whitelist
            // } else {
                tagify = new Tagify(input, {
                    whitelist: intput_service,
                    enforceWhitelist: true,
                });
            // +}

            tagify.on('change', function() {
                const selectedTags = tagify.value.map((tag) => {
                    return {
                        value: tag.value,
                        tagid: tag.tagid,
                        // Use __tagifyTagData.tagText to get the visible text
                        // data: tag.data.price, // Use __tagifyTagData.data to get additional data
                    };
                });
                console.log('selected tag', selectedTags);
            });

        },
        error: function(xhr, status, error) {
            console.error('Ajax request failed:', error);
        },
    });
}
</script>