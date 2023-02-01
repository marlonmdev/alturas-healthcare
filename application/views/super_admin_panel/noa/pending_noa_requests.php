
<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">Notice of Admission</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Super Admin</li>
              <li class="breadcrumb-item active" aria-current="page">
                Pending NOA
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
              href="<?php echo base_url(); ?>super-admin/noa/requests-list"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Pending</span></a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>super-admin/noa/requests-list/approved"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Approved</span></a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>super-admin/noa/requests-list/disapproved"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Disapproved</span></a
            >
          </li>
            <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>super-admin/noa/requests-list/closed"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Closed</span></a
            >
          </li>
        </ul>

        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover" id="pendingNoaTable">
                <thead>
                  <tr>
                    <th>NOA No.</th>
                    <th>Name</th>
                    <th>Admission Date</th>
                    <th>Hospital Name</th>
                    <th>Date Requested</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- View NOA Details -->
        <div class="modal fade" id="viewNoaModal" tabindex="-1" data-bs-backdrop="static">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title ls-2">NOA #: <span id="noa-no" class="text-primary"></span> <span id="noa-status"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
              </div>
              <div class="modal-body">
                <div class="container">
                  <div class="row text-center">
                    <h4><strong>NOA REQUEST DETAILS</strong></h4>
                  </div>
                  <div class="row">
                    <table class="table table-bordered table-striped table-hover table-responsive table-sm">
                      <tr>
                        <td class="fw-bold ls-1">Member's Maximum Benefit Limit :</td>
                        <td class="fw-bold ls-1">&#8369;<span id="member-mbl"></span></td>
                      </tr>
                      <tr>
                        <td class="fw-bold ls-1">Member's Remaining MBL :</td>
                        <td class="fw-bold ls-1">&#8369;<span id="remaining-mbl"></span></td>
                      </tr>
                      <tr>
                        <td class="fw-bold ls-1">Full Name :</td>
                        <td class="fw-bold ls-1" id="full-name"></td>
                      </tr>
                      <tr>
                        <td class="fw-bold ls-1">Date of Birth :</td>
                        <td class="fw-bold ls-1" id="date-of-birth"></td>
                      </tr>
                      <tr>
                        <td class="fw-bold ls-1">Age :</td>
                        <td class="fw-bold ls-1" id="age"></td>
                      </tr>
                      <tr>
                        <td class="fw-bold ls-1">Hospital :</td>
                        <td class="fw-bold ls-1" id="hospital-name"></td>
                      </tr>
                      <tr>
                        <td class="fw-bold ls-1">Admission Date :</td>
                        <td class="fw-bold ls-1" id="admission-date"></td>
                      </tr>
                      <tr>
                        <td class="fw-bold ls-1">Chief Complaint :</td>
                        <td class="fw-bold ls-1" id="chief-complaint"></td>
                      </tr>
                      <tr>
                        <td class="fw-bold ls-1">Requested On :</td>
                        <td class="fw-bold ls-1" id="request-date"></td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <!-- End of View NOA -->
      </div>
    </div>
  </div>
</div>
<script>
  const baseUrl = "<?php echo base_url(); ?>";
  $(document).ready(function() {

    $('#pendingNoaTable').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}super-admin/noa/requests-list/fetch`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: {
          'token': '<?php echo $this->security->get_csrf_hash(); ?>'
        }
      },

      //Set column definition initialisation properties.
      columnDefs: [{
        "targets": [5, 6], // numbering column
        "orderable": false, //set not orderable
      }, ],
      responsive: true,
      fixedHeader: true,
    });

  });

  function viewNoaInfo(noa_id) {
    $.ajax({
      url: `${baseUrl}super-admin/noa/pending/view/${noa_id}`,
      type: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const {
          status,
          token,
          noa_no,
          member_mbl,
          remaining_mbl,
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
          req_status
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
        $('#member-mbl').html(member_mbl);
        $('#remaining-mbl').html(remaining_mbl);
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

  function cancelNoaRequest(noa_id) {
    $.confirm({
      title: '<strong>Confirm!</strong>',
      content: 'Are you sure to delete NOA Request?',
      type: 'red',
      buttons: {
        confirm: {
          text: 'Yes',
          btnClass: 'btn-red',
          action: function() {
            $.ajax({
              type: 'GET',
              url: `${baseUrl}super-admin/noa/requested-noa/cancel/${noa_id}`,
              data: {
                noa_id
              },
              dataType: "json",
              success: function(response) {
                const {
                  token,
                  status,
                  message
                } = response;
                if (status === 'success') {
                  swal({
                    title: 'Success',
                    text: message,
                    timer: 3000,
                    showConfirmButton: false,
                    type: 'success'
                  });
                  $("#pendingNoaTable").DataTable().ajax.reload();
                } else {
                  swal({
                    title: 'Failed',
                    text: message,
                    timer: 3000,
                    showConfirmButton: false,
                    type: 'error'
                  });
                  $("#pendingNoaTable").DataTable().ajax.reload();
                }
              }
            });
          }
        },
        cancel: {
          btnClass: 'btn-dark',
          action: function() {
            // close dialog
          }
        },
      }
    });
  }
</script>
