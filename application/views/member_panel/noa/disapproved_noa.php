<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">Requested NOA</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Member</li>
              <li class="breadcrumb-item active" aria-current="page">
                Disapproved NOA
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
              class="nav-link"
              href="<?php echo base_url(); ?>member/requested-noa/pending"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Pending</span></a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>member/requested-noa/approved"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Approved</span></a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link active"
              href="<?php echo base_url(); ?>member/requested-noa/disapproved"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Disapproved</span></a
            >
          </li>
            <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>member/requested-noa/closed"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Closed</span></a
            >
          </li>
        </ul>

        <div class="card shadow">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover" id="memberDisapprovedNoa">
                <thead>
                  <tr>
                    <th class="fw-bold">NOA No.</th>
                    <th class="fw-bold">Admission Date</th>
                    <th class="fw-bold">Hospital Name</th>
                    <th class="fw-bold">Request Date</th>
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

        <?php include 'view_disapproved_noa_details.php'; ?>

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
  const baseUrl = `<?php echo base_url(); ?>`;

  $(document).ready(function() {
    $("#memberDisapprovedNoa").DataTable({
      ajax: {
        url: `${baseUrl}member/requested-noa/disapproved/fetch`,
        dataSrc: function(data) {
          if (data == "") {
            return [];
          } else {
            return data.data;
          }
        }
      },
      order: [],
      responsive: true,
      fixedHeader: true,
      columnDefs: [{
        "targets": [4, 5], // 6th and 7th column / numbering column
        "orderable": false, //set not orderable
      }, ],
    });
  });

  const viewNoaInfoModal = (req_id) => {
    $.ajax({
      url: `${baseUrl}member/requested-noa/view/disapproved/${req_id}`,
      type: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const base_url = window.location.origin;
        const {
          status,
          token,
          noa_no,
          first_name,
          middle_name,
          last_name,
          suffix,
          date_of_birth,
          age,
          hospital_name,
          health_card_no,
          requesting_company,
          admission_date,
          chief_complaint,
          request_date,
          req_status,
          disapproved_by,
          disapprove_reason,
          disapproved_on
        } = res;

        $("#viewNoaModal").modal("show");

        switch (req_status) {
          case 'Pending':
            $('#noa-status').html('<strong class="text-warning">[' + req_status + ']</strong>');
            break;
          case 'Approved':
            $('#noa-status').html('<strong class="text-success">[' + req_status + ']</strong>');
            break;
          case 'Disapproved':
            $('#noa-status').html('<strong class="text-danger">[' + req_status + ']</strong>');
            break;
        }
        
        $('#noa-no').html(noa_no);
        $('#disapproved-by').html(disapproved_by);
        $('#disapproved-on').html(disapproved_on);
        $('#disapprove-reason').html(disapprove_reason);
        $('#full-name').html(`${first_name} ${middle_name} ${last_name} ${suffix}`);
        $('#date-of-birth').html(date_of_birth);
        $('#age').html(age);
        $('#hospital-name').html(hospital_name);
        $('#admission-date').html(admission_date);
        $('#chief-complaint').html(chief_complaint);
        $('#request-date').html(request_date);
      }
    });
  }
</script>