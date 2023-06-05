
    <!-- Bootstrap tether Core JavaScript -->
    <script src="<?php echo base_url(); ?>assets/matrixDashboard/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/matrixDashboard/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <!--Menu sidebar -->
    <script src="<?php echo base_url(); ?>assets/matrixDashboard/dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="<?php echo base_url(); ?>assets/matrixDashboard/dist/js/custom.min.js"></script>
    
    <!-- Other Vendor JavaScript -->
    <script src="<?php echo base_url(); ?>assets/vendors/dataTables/datatables.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendors/dropify/js/dropify.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendors/sweetalert2/sweetalert2.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendors/jquery-confirm/dist/jquery-confirm.min.js"></script>

    <script src="<?php echo base_url(); ?>assets/vendors/tagify/tagify.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendors/tagify/tagify.polyfills.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendors/flatpickr/flatpickr.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendors/quaggaJS/dist/quagga.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendors/pdfjs/build/pdf.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendors/pdfjs/build/pdf.worker.js"></script>

    <!-- Custom Scripts -->
    <script src="<?php echo base_url(); ?>assets/js/plugins.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/utilities.js"></script>

    <script src="<?php echo base_url(); ?>assets/js/canvasjs.min.js"></script>
  </body>
  <?php include 'managers_key_modal.php'; ?>

</html>
<script>
  const baseurl = "<?php echo base_url(); ?>";
  $(document).ready(function(){
    $('#managersKeyFormMBL').submit(function(event){
      event.preventDefault();
      $.ajax({
        type: "post",
        url: `${baseurl}healthcare-coordinator/reset-mbl/managers-key/check`,
        data: $(this).serialize(),
        dataType: "json",
        success: function (res) {
            const { status, message, mgr_username_error, mgr_password_error, company_doctor } = res;

            if (status == "error") {
              if (mgr_username_error !== '') {
                $('#mgr-username-error-mbl').html(mgr_username_error);
                $('#mgr-username-mbl').addClass('is-invalid');
              } else {
                $('#mgr-username-error-mbl').html('');
                $('#mgr-username-mbl').removeClass('is-invalid');
              }

              if (mgr_password_error !== '') {
                $('#mgr-password-error-mbl').html(mgr_password_error);
                $('#mgr-password-mbl').addClass('is-invalid');
              } else {
                $('#mgr-password-error-mbl').html('');
                $('#mgr-password-mbl').removeClass('is-invalid');
              }

              if (message !== '') {
                $('#msg-error-mbl').html(message);
                $('#mgr-username-mbl').addClass('is-invalid');
                $('#mgr-password-mbl').addClass('is-invalid');
              } else {
                $('#msg-error-mbl').html('');
                $('#mgr-username-mbl').removeClass('is-invalid');
                $('#mgr-password-mbl').removeClass('is-invalid');
              }

            } else {
              $('#managersKeyMBLModal').modal('hide');
              window.location.href = `${baseurl}healthcare-coordinator/setup/reset-mbl/${company_doctor}`;
            }
        },
      });
    });
  });
  

</script>
<script>
 const showManagersKeyMBLModal = () => {
    $('#managersKeyMBLModal').modal('show');
    $('#managersKeyFormMBL')[0].reset();
    $('#mgr-username-mbl').removeClass('is-invalid');
    $('#mgr-password-mbl').removeClass('is-invalid');
    $('#mgr-username-error-mbl').html('');
    $('#mgr-password-error-mbl').html('');
    $('#msg-error-mbl').html('');
  }
  </script>
