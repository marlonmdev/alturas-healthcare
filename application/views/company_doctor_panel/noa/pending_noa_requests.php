
<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">NOA Requests</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Company Doctor</li>
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
              href="<?php echo base_url(); ?>ccompany-doctor/noa/requests-list"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Pending</span></a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>company-doctor/noa/requests-list/approved"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Approved</span></a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>company-doctor/noa/requests-list/disapproved"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Disapproved</span></a
            >
          </li>
            <li class="nav-item">
            <a
              class="nav-link"
              href="<?php echo base_url(); ?>company-doctor/noa/requests-list/closed"
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
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <?php include 'view_noa_details.php'; ?>
        <?php include 'noa_approve_modal.php'; ?>

      </div>
      <!-- End Row  -->  
      </div>
      <?php include 'noa_disapprove_reason.php'; ?>
    <!-- End Container fluid  -->
    </div>
  <!-- End Page wrapper  -->
  </div>
<!-- End Wrapper -->

<script>
  const baseUrl = "<?php echo base_url(); ?>";
  $(document).ready(function() {

    $('#pendingNoaTable').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}company-doctor/noa/requests-list/fetch`,
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

  function viewNoaInfo(req_id) {
    $.ajax({
      url: `${baseUrl}company-doctor/noa/requests-list/view/${req_id}`,
      type: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const baseUrl = window.location.origin;
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

  // function approveNoaRequest(noa_id) {
  //   const next_page = `${baseUrl}company-doctor/noa/requests-list/approved`;
  //   // $.confirm is a convention of a Jquery Confirm plugin 
  //   $.confirm({
  //     title: '<strong>Confirm!</strong>',
  //     content: 'Are you sure to Approve NOA Request?',
  //     type: 'green',
  //     buttons: {
  //       confirm: {
  //         text: 'Yes',
  //         btnClass: 'btn-green',
  //         action: function() {
  //           $.ajax({
  //             type: 'GET',
  //             url: `${baseUrl}company-doctor/noa/requests-list/approve/${noa_id}`,
  //             data: {
  //               noa_id: noa_id
  //             },
  //             dataType: "json",
  //             success: function(response) {
  //               const {
  //                 token,
  //                 status,
  //                 message
  //               } = response;
  //               if (status === 'success') {
  //                 swal({
  //                   title: 'Success',
  //                   text: message,
  //                   timer: 3000,
  //                   showConfirmButton: false,
  //                   type: 'success'
  //                 });

  //                 setTimeout(function() {
  //                   window.location.href = next_page;
  //                 }, 3200);

  //               } else {
  //                 swal({
  //                   title: 'Failed',
  //                   text: message,
  //                   timer: 3000,
  //                   showConfirmButton: false,
  //                   type: 'error'
  //                 });
  //               }
  //             }
  //           });
  //         }
  //       },
  //       cancel: {
  //         btnClass: 'btn-dark',
  //         action: function() {
  //           // close dialog
  //         }
  //       },

  //     }
  //   });
  // }

  function approveNoaRequest(noa_id) {
    $('#noaApproveForm')[0].reset();
    $('#noaApprovedModal').modal('show');
    $('#work-related-error').html('');
    $('#work-related').removeClass('is-invalid');
    $('#option1').removeClass('is-invalid');
    $('#option2').removeClass('is-invalid');
    $("#noaApproveForm").attr("action", `${baseUrl}company-doctor/noa/requests-list/approve/${noa_id}`);
  }

  function disapproveNoaRequest(noa_id) {
    $('#noaDisapproveForm')[0].reset();
    $('#noaDisapprovedReasonModal').modal('show');
    $('#disapprove-reason-error').html('');
    $('#disapprove-reason').removeClass('is-invalid');
    $("#noaDisapproveForm").attr("action", `${baseUrl}company-doctor/noa/requests-list/disapprove/${noa_id}`);
  }

  $(document).ready(function() {
    $('#noaApproveForm').submit(function(event) {
      const nextPage = `${baseUrl}company-doctor/noa/requests-list/approved`;
      event.preventDefault();
      $.ajax({
        type: "post",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType: "json",
        success: function(response) {
          const {
            token,
            status,
            message,
            work_related_error
          } = response;
          switch (status) {
            case 'error':
              // is-invalid class is a built in classname for errors in bootstrap
              if (work_related_error !== '') {
                $('#work-related-error').html(work_related_error);
                $('#option1').addClass('is-invalid');
                $('#option2').addClass('is-invalid');
              } else {
                $('#work-related-error').html('');
                $('#option1').removeClass('is-invalid');
                $('#option2').removeClass('is-invalid');
              }
              break;
            case 'save-error':
              swal({
                title: 'Failed',
                text: message,
                timer: 3000,
                showConfirmButton: false,
                type: 'error'
              });
              break;
            case 'success':
              swal({
                title: 'Success',
                text: message,
                timer: 3000,
                showConfirmButton: false,
                type: 'success'
              });
              $('#noaDisapprovedReasonModal').modal('hide');
              setTimeout(function() {
                window.location.href = nextPage;
              }, 3200);
              break;
          }
        }
      });
    });


    $('#noaDisapproveForm').submit(function(event) {
      const nextPage = `${baseUrl}company-doctor/noa/requests-list/disapproved`;
      event.preventDefault();
      $.ajax({
        type: "post",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType: "json",
        success: function(response) {
          const {
            token,
            status,
            message,
            disapprove_reason_error
          } = response;
          switch (status) {
            case 'error':
              // is-invalid class is a built in classname for errors in bootstrap
              if (disapprove_reason_error !== '') {
                $('#disapprove-reason-error').html(disapprove_reason_error);
                $('#disapprove-reason').addClass('is-invalid');
              } else {
                $('#disapprove-reason-error').html('');
                $('#disapprove-reason').removeClass('is-invalid');
              }
              break;
            case 'save-error':
              swal({
                title: 'Failed',
                text: message,
                timer: 3000,
                showConfirmButton: false,
                type: 'error'
              });
              break;
            case 'success':
              swal({
                title: 'Success',
                text: message,
                timer: 3000,
                showConfirmButton: false,
                type: 'success'
              });
              $('#noaDisapprovedReasonModal').modal('hide');
              setTimeout(function() {
                window.location.href = nextPage;
              }, 3200);
              break;
          }
        }
      });
    });
  });
</script>