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
            <input type="hidden" name="loa-id" id="bill-loa-id" value="<?= $noa_id ?>">
            <div class="card">
                <div class="card-body shadow">
                    <div class="row my-2">
                        <label class="fw-bold text-secondary fs-4 ls-1">
                           NOA Number : <span class="text-info"><?= $noa_no ?></span>
                        </label>
                    </div>
                    <div class="row">
                        <div class="col-lg-7">
                            <label class="fw-bold fs-5 ls-1">
                                <i class="mdi mdi-asterisk text-danger ms-1"></i> Select PDF File
                            </label>
                            <input type="file" class="form-control" name="pdf-file" id="pdf-file" accept="application/pdf" onchange="previewPdfFile()" required>
                            <div class="invalid-feedback fs-5 ls-1">
                                PDF File is required
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <label class="form-label fs-5 ls-1">
                                <i class="mdi mdi-asterisk text-danger ms-1"></i> Net Bill
                            </label>
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-cyan text-white">&#8369;</span>
                                <input type="number" class="form-control fw-bold ls-1" id="net-bill" name="net-bill" placeholder="Enter Net Bill" required>
                                <div class="invalid-feedback fs-5 ls-1">
                                    Net Bill is required
                                </div>
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
                           <div class="mt-3" id="pdf_preview"></div>
                        </div>
                    </div>
                  
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    const baseUrl = '<?php echo base_url(); ?>';

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


    $(document).ready(function(){

        $('#pdfBillingForm').submit(function(event){
            event.preventDefault();
            let formData = new FormData(this);
            
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                dataType: "json",
                processData: false,
                contentType: false,
                success: function(response){
                    const { token, status, message, pdf_file_error, net_bill_error } = response;

                    if(status == 'error'){
                        if (pdf_file_error !== '') {
                            $('#pdf-file-error').html(pdf_file_error);
                            $('#pdf-file').addClass('is-invalid');
                        } else {
                            $('#pdf-file-error').html('');
                            $('#pdf-file').removeClass('is-invalid');
                        }

                        if (net_bill_error !== '') {
                            $('#net-bill-error').html(net_bill_error);
                            $('#net-bill').addClass('is-invalid');
                        } else {
                            $('#net-bill-error').html('');
                            $('#net-bill').removeClass('is-invalid');
                        }
                    } else if(status == 'success'){
                        swal({
                            title: 'Success',
                            text: message,
                            timer: 3000,
                            showConfirmButton: false,
                            type: 'success'
                        });
                    }else if(status == 'save-error'){
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

       
    });
   

</script>