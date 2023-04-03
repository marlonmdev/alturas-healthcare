<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title ls-2">Upload SOA</h4>
                <div class="ms-auto text-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">Healthcare Provider</li>
                            <li class="breadcrumb-item active" aria-current="page">
                            Upload Image/PDF
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
        <form action="<?php echo base_url();?>healthcare-provider/billing/upload-soa-textfile" id="text-file-form" enctype="multipart/form-data">
        <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
            <div class="card">
                <div class="card-body shadow">
                    <div class="row">
                        <div class="col-lg-8 pb-2">
                            <label class="fw-bold fs-5 ls-1"><i class="mdi mdi-asterisk text-danger ms-1"></i> Upload Image/PDF</label>
                            <input  type="file" class="dropify" name="img-pdf" id="img-pdf" data-height="400" data-max-file-size="5M" accept=".jpg, .jpeg, .png, .pdf">
                            <br>
                            <em class="text-info fw-bold ls-1">Allowed file format (.jpg | .png | .jpeg | .pdf)</em>
                        </div>
                        <div class="col-lg-4 pt-5 pb-2">
                           <label class="form-label fs-5 ls-1"><i class="mdi mdi-asterisk text-danger ms-1"></i> Net Bill</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-cyan text-white">&#8369;</span>
                                <input type="number" class="form-control fw-bold ls-1" id="total-bill" name="total-bill">
                            </div>
                            <em class="text-danger ls-1" id="net-bill-error"></em>
                        </div>
                    </div>
                  

                    <div class="row mt-4">
                        <div class="col-md-6 offset-3">
                            <button type="submit" class="btn btn-success text-white btn-lg ls-2 me-2" id="upload-btn">
                                <i class="mdi mdi-upload me-1"></i>UPLOAD
                            </button>
                            <button type="button" class="btn btn-dark text-white btn-lg ls-2" id="clear-btn">CLEAR</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    const baseUrl = '<?php echo base_url(); ?>';
    $(document).ready(function(){

        $('#text-file-form').submit(function(event){
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
            $('#text-file-form')[0].reset();
        });

       
    });
   

</script>