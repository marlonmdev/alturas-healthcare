<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Import Text File</title>
  <link rel="icon" href="<?= base_url(); ?>assets/images/hmo-logo.png">
  <link rel="stylesheet" href="<?= base_url() ?>assets/vendors/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/vendors/bootstrap-icons/bootstrap-icons.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/vendors/Toastr/build/toastr.min.css">
</head>
<style type="text/css">
  .btn {
    padding: 8px 16px;
  }

  .btn,
  input[type="file"] {
    border-radius: 5px;
  }
</style>

<body>
  <div class="container mt-4">
    <h2 style="background:#001253;color:#ffff;padding:20px;border-radius:5px;" class="text-center">Text File Import</h2>

    <div class="alert alert-success alert-dismissible fade show mt-4 d-none" id="successAlert" role="alert">
      <h3 class="text-center"><strong><i class="bi bi-check-circle-fill"></i> Success!</strong></h3>
      <h5 class="text-center" id="successMsg"></h5>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <!-- File upload form -->
    <div class="row d-flex justify-content-center align-items-center my-4" id="importForm">
      <form action="<?php echo base_url('import/txt/upload'); ?>" method="post" enctype="multipart/form-data" id="importTxtForm">
        <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
        <div class="col-md-8 offset-2">
          <div class="form-group input-group-lg">
            <input type="file" class="form-control" name="txtFile" id="txtFile" accept=".txt">
          </div>
        </div>
        <div class="col-md-12 d-flex justify-content-center mt-3">
          <button type="submit" class="btn btn-primary" id="importTxtBtn">
            <img src="<?php echo base_url(); ?>assets/images/preloader2.gif" class="d-none" width="30px" alt="Loader" id="loaderGif">
            <strong id="importBtnText">
              UPLOAD TEXT FILE
            </strong>
          </button>
        </div>
      </form>
    </div>

  </div>

</body>
<script src="<?= base_url() ?>assets/vendors/jquery/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/vendors/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() ?>assets/vendors/Toastr/build/toastr.min.js"></script>
<script>
  $(document).ready(function() {
    const baseUrl = "<?php echo base_url(); ?>";
    const successMsg = document.querySelector('#successMsg');
    const errorMsg = document.querySelector('errorMsg');

    $("#importTxtForm").submit(function(event) {
      event.preventDefault();
      let formData = new FormData(this);
      let txtFile = $("#txtFile")[0].files[0];
      let formUrl = $(this).attr('action');
      if (txtFile !== undefined) {
        showLoader();
        // ajax post request
        $.ajax({
          url: formUrl,
          type: 'post',
          data: formData,
          dataType: "json",
          processData: false,
          contentType: false,
          success: function(response) {
            $('#importTxtForm')[0].reset();
            hideLoader();
            if (response.status == "success") {
              $('#successAlert').removeClass('d-none');
              successMsg.innerHTML = response.message;
            } else {
              toastr.options = {
                closeButton: true,
                preventDuplicates: true,
              };
              toastr["error"](response.message);
            }
          },
        });
        // end of ajax post request
      } else {
        toastr.options = {
          closeButton: true,
          preventDuplicates: true,
        };
        toastr["error"]('Please select Text file to upload!');
      }
    });
  });

  let showLoader = () => {
    $("#importBtnText").html("IMPORTING TXT FILE");
    $("#loaderGif").removeClass("d-none");
  }

  let hideLoader = () => {
    $("#loaderGif").addClass("d-none");
    $("#importBtnText").html("IMPORT TEXT FILE");
  }
</script>

</html>