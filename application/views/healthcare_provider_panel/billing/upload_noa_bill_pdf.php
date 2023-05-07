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
            <form method="POST" action="<?php echo base_url(); ?>healthcare-provider/billing/search">
                <div class="input-group">
                    <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="healthcard_no" value="<?= $healthcard_no ?>">
                    <button type="submit" class="btn btn-outline-dark" data-bs-toggle="tooltip" title="Click to Go Back">
                        <strong class="ls-2" style="vertical-align:middle">
                            <i class="mdi mdi-arrow-left-bold"></i> Go Back
                        </strong>
                    </button>
                </div>
            </form>
        </div>

        <form action="<?php echo base_url();?>healthcare-provider/billing/bill-noa/upload-pdf/<?= $noa_id ?>/submit" id="pdfBillingForm" enctype="multipart/form-data" class="needs-validation" novalidate>
            <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
            <input type="hidden" name="billing-no" value="<?= $billing_no ?>">
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

                    <div class="row">
                        <div class="col-lg-7">
                            <label class="fw-bold fs-5 ls-1">
                                <i class="mdi mdi-asterisk text-danger ms-1"></i> Select PDF File
                            </label>
                            <input type="file" class="form-control" name="pdf-file" id="pdf-file" accept="application/pdf" onchange="previewPdfFile()" required>
                            <div class="invalid-feedback fs-6">
                                PDF File is required
                            </div>
                        </div>

                        <div class="col-lg-5">
                        <label class="form-label fs-5 ls-1">Remaining MBL Balance</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-cyan text-white">&#8369;</span>
                                <input type="number" class="form-control fw-bold ls-1" id="remaining-balance" name="remaining-balance" value="<?= $remaining_balance ?>"  disabled>
                                <input type="number" class="form-control fw-bold ls-1" id="net-bill" name="net-bill"  hidden required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="d-flex justify-content-center align-items-center mt-2">
                            <button type="submit" class="btn btn-info text-white btn-lg ls-2 me-3" id="upload-btn">
                                <i class="mdi mdi-upload me-1"></i>UPLOAD
                            </button>
                            <button type="button" class="btn btn-dark text-white btn-lg ls-2" id="clear-btn">CLEAR</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                           <div class="mt-3" id="pdf-preview"></div>
                        </div>
                    </div>
                  
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    const baseUrl = `<?php echo base_url(); ?>`;

    const previewPdfFile = () => {
        let pdfFileInput = document.getElementById('pdf-file');
        let pdfPreview = document.getElementById('pdf-preview');
        let pdfFile = pdfFileInput.files[0];

        if (pdfFile.type === 'application/pdf') {
            let reader = new FileReader();
            reader.onload = function () {
                let pdfObject = "<object data='" + reader.result + "' type='application/pdf' width='100%' height='600px'>";
                pdfObject += "</object>";
                pdfPreview.innerHTML = pdfObject;
            }
            reader.readAsDataURL(pdfFile);
        } else {
            pdfPreview.innerHTML = "Please select a PDF file.";
        }
    }

    const form = document.querySelector('#pdfBillingForm');

    $(document).ready(function(){

        $('#pdfBillingForm').submit(function(event){
            event.preventDefault();

            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            let formData = new FormData($(this)[0]);
            
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                dataType: "json",
                processData: false,
                contentType: false,
                success: function(response){
                    const { token, status, message, billing_id } = response;

                    if(status == 'success'){
                        // swal({
                        //    /// healthcare-provider/billing/bill-noa/upload-pdf/(:any)/success
                        //     title: 'Success',
                        //     text: message,
                        //     timer: 3000,
                        //     showConfirmButton: false,
                        //     type: 'success'
                        // });
                        
                        // setTimeout(function() {
                        //     window.location.href = `${baseUrl}healthcare-provider/noa-requests/billed`;
                        // }, 3000);

                        setTimeout(function() {
                            window.location.href = `${baseUrl}healthcare-provider/billing/bill-noa/upload-pdf/${billing_id}/success`;
                        }, 300);
                    }else{
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

        $('#clear-btn').on('click', function(){
            let pdfPreview = document.getElementById('pdf_preview');
            $('#pdfBillingForm')[0].reset();
            pdfPreview.innerHTML = '';
        });

        //extract pdf text 
        let pdfFileInput = document.getElementById('pdf-file');

        pdfFileInput.addEventListener('change', function() {
        let reader = new FileReader();
        reader.onload = function() {
            let typedarray = new Uint8Array(this.result);
            pdfjsLib.getDocument(typedarray).promise.then(function(pdf) {
            let numPages = pdf.numPages;
            let pageNum = 1;
            pdf.getPage(pageNum).then(function(page) {
                page.getTextContent().then(function(textContent) {
                let sortedItems = textContent.items.map(function(item) {
                    return {text: item.str.toLowerCase(), y: item.transform[5]};
                }).sort(function(a, b) {
                    return b.y - a.y;
                });
                let text = sortedItems.map(function(item) {
                    return item.text;
                }).join('');
                console.log(text);

                // let result = text1.replace(/subtotal\s*[\.]*\s*[\w\s]*\s*\(([\d,\.]+)\)/, "$1"); 
                const regex = /subtotal\s*\.*\s*\(([\d,\.]+)\)/i;
                // const regex = /subtotal\s*\.{26}\s*\(([\d,\.]+)\)/i;
                    const match = text.match(regex);
                    console.log("match",match);
                    if (match) {
                    const subtotalValue = parseFloat(match[1].replace(/,/g, ""));
                    document.getElementsByName("net-bill")[0].value = subtotalValue;
                    console.log(subtotalValue);
                    } else {
                    console.log("Subtotal value not found");
                    }
                }); 
            });
            }, function(error) {
            console.error(error);
            });
        };
        reader.readAsArrayBuffer(this.files[0]);
        });
       
    });
   

</script>