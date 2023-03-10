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
                            Upload Textfile
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
                    <div class="col-lg-5 pb-4 ps-3" id="file-form">
                        <label class="fw-bold pt-3 pb-3 fs-5"><i class="mdi mdi-asterisk text-danger ms-1"></i> Upload SOA Textfile : </label>
                        <input class="form-control fs-5" type="file" name="textfile" id="textfile" accept="">
                        <em class="text-danger">Allowed file format (.text & .csv)</em>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6 offset-3">
                            <button type="submit" class="btn btn-cyan text-white btn-lg ls-2" id="upload-btn">
                                <i class="mdi mdi-file-check me-1"></i>Upload
                            </button>
                            <button class="btn btn-secondary text-white btn-lg ls-2" id="clear-btn">Clear</button>
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