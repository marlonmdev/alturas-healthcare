  <!-- Start of Page Wrapper -->
  <div class="page-wrapper">
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title ls-2">Personal Charges</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Member</li>
                <li class="breadcrumb-item active" aria-current="page">
                  Unpaid Personal Charges
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- Start of Container fluid  -->
    <div class="container-fluid">
      <div class="row">

        <div class="col-lg-12">

          <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item">
              <a
                class="nav-link active"
                href="<?php echo base_url(); ?>member/personal-charges"
                role="tab"
                ><span class="hidden-sm-up"></span>
                <span class="hidden-xs-down fs-5 font-bold">Unpaid</span></a
              >
            </li>
            <li class="nav-item">
              <a
                class="nav-link"
                href="<?php echo base_url(); ?>member/personal-charges/paid"
                role="tab"
                ><span class="hidden-sm-up"></span>
                <span class="hidden-xs-down fs-5 font-bold">Paid</span></a
              >
            </li>
          </ul>

          <div class="card shadow">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped table-hover" id="memberPersonalCharges">
                  <thead>
                    <tr>
                      <th class="fw-bold">#</th>
                      <th class="fw-bold">Billing No.</th>
                      <th class="fw-bold">Charge Amount</th>
                      <th class="fw-bold">Added On</th>
                      <th class="fw-bold">Status</th>
                      <th class="fw-bold">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <?php include 'view_personal_charges.php'; ?>

        </div>
      <!-- End Row  -->
      </div>
    <!-- End Container fluid  -->
    </div>
  <!-- End Page wrapper  -->
  </div>
<!-- End Wrapper -->
</div>
<script>
  const baseUrl = "<?= base_url() ?>";
  $(document).ready(function() {
    $("#memberPersonalCharges").DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}member/personal-charges/unpaid/fetch`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: {
          'token': '<?php echo $this->security->get_csrf_hash(); ?>'
        }
      },

      //Set column definition initialisation properties.
      columnDefs: [{
        "targets": [4, 5], // numbering column
        "orderable": false, //set not orderable
      }, ],
      responsive: true,
      fixedHeader: true,
    });
  });

  function viewPChargeModal(pcharge_id) {
    // $.ajax({
    //   url: `${baseUrl}member/personal-charges/view/unpaid/${pcharge_id}`,
    //   type: "GET",
    //   success: function(response) {
    //     const res = JSON.parse(response);
    //     const base_url = window.location.origin;
    //     const {
    //       status,
    //       token,
    //       noa_no,
    //       first_name,
    //       middle_name,
    //       last_name,
    //       suffix,
    //       date_of_birth,
    //       age,
    //       hospital_name,
    //       health_card_no,
    //       requesting_company,
    //       admission_date,
    //       chief_complaint,
    //       request_date,
    //       req_status
    //     } = res;

        $("#viewPersonalChargeModal").modal("show");
        // switch (req_status) {
        //   case 'Unpaid':
        //     $('#noa-status').html('<strong class="text-warning">[' + req_status + ']</strong>');
        //     break;
        //   case 'Paid':
        //     $('#noa-status').html('<strong class="text-success">[' + req_status + ']</strong>');
        //     break;
        // }
        // $('#noa-no').html(noa_no);
        // $('#full-name').html(`${first_name} ${middle_name} ${last_name} ${suffix}`);
        // $('#date-of-birth').html(date_of_birth);
        // $('#age').html(age);
        // $('#hospital-name').html(hospital_name);
        // $('#admission-date').html(admission_date);
        // $('#chief-complaint').html(chief_complaint);
        // $('#request-date').html(request_date);
    //   }
    // });
  }
</script>