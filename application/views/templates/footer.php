
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
    <button id="scrollButton"></button>
  </body>
  

</html>
<style>
    #scrollButton {
      display: none;
      position: fixed;
      bottom: 20px;
      right: 20px;
      padding: 10px;
      background-color: #ccc;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
  </style>
 <script>
    // Create the scroll button
    var scrollButton = document.createElement('button');
    scrollButton.id = 'scrollButton';

    // Create the icon element
    var iconElement = document.createElement('i');
    iconElement.className = 'mdi mdi-chevron-double-up';

    // Append the icon element to the scroll button
    scrollButton.appendChild(iconElement);
    scrollButton.title = 'Scroll back to top';
    document.body.appendChild(scrollButton);

    // Show/hide the scroll button based on the user's scroll position
    window.addEventListener('scroll', function() {
      if (window.pageYOffset > 100) {
        scrollButton.style.display = 'block';
      } else {
        scrollButton.style.display = 'none';
      }
    });

    // Scroll to the top when the button is clicked
    scrollButton.addEventListener('click', function() {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });

   
  </script>