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
        <form action="<?php echo base_url();?>healthcare-provider/billing/bill-loa/upload-pdf/submit" id="pdfBillingForm" enctype="multipart/form-data">
            <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
            <input type="hidden" name="loa-id" id="bill-loa-id" value="<?= $loa_id ?>">
            <div class="card">
                <div class="card-body shadow">
                    <div class="row">
                        <div class="col-lg-5">
                            <label class="fw-bold fs-5 ls-1"><i class="mdi mdi-asterisk text-danger ms-1"></i> Select PDF File</label>
                            <input type="file" class="form-control" name="pdf_file" id="pdf_file" accept="application/pdf" onchange="previewPdfFile()">

                            <label class="form-label fs-5 mt-4 ls-1"><i class="mdi mdi-asterisk text-danger ms-1"></i> Net Bill</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-cyan text-white">&#8369;</span>
                                <input type="number" class="form-control fw-bold ls-1" id="total-bill" name="total-bill" placeholder="Enter Net Bill">
                            </div>
                            <em class="text-danger ls-1" id="net-bill-error"></em>

                            <div class="d-flex justify-content-end align-items-end">
                                <button type="submit" class="btn btn-info text-white btn-lg ls-2 me-2" id="upload-btn">
                                    <i class="mdi mdi-upload me-1"></i>UPLOAD
                                </button>
                                <button type="button" class="btn btn-dark text-white btn-lg ls-2" id="clear-btn">CLEAR</button>
                            </div>
                        </div>

                        <div class="col-lg-7">
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
        let pdfFileInput = document.getElementById('pdf_file');
        let pdfPreview = document.getElementById('pdf_preview');
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
            var formData = new FormData(this);
            
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                dataType: "json",
                enctype: 'multipart/form-data',
	            async: true,
	            cache: false,
                processData: false,
                contentType: false,
                success: function(response){
                    const {
                        token,
                        status,
                        message
                    } = response;

                    if(status == 'success'){
                        swal({
                            title: 'Success',
                            text: message,
                            timer: 3000,
                            showConfirmButton: false,
                            type: 'success'
                        });
                        $('#text-file-form')[0].reset();
                        
                    } else if(status == 'error-format'){
                        swal({
                            title: 'Failed',
                            text: message,
                            timer: 3000,
                            showConfirmButton: false,
                            type: 'error'
                        });
                    }else if(status == 'error-delimiter'){
                        swal({
                            title: 'Failed',
                            text: message,
                            timer: 3000,
                            showConfirmButton: false,
                            type: 'error'
                        });
                    }else if(status == 'error'){
                        swal({
                            title: 'Failed',
                            text: message,
                            timer: 3000,
                            showConfirmButton: false,
                            type: 'error'
                        });
                    }else if(status == 'empty'){
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
            $('#pdfBillingForm')[0].reset();
        });

       
    });
   

</script>