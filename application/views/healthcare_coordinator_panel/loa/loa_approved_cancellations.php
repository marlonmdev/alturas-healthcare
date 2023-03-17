<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
  <div class="page-breadcrumb">
      <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title ls-2">LOA Cancellation Requests</h4>
          <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">
                  LOA Cancellation Requests
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
              href="<?php echo base_url(); ?>healthcare-coordinator/loa/cancellation-requests"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Pending</span></a
              >
            </li>
            <li class="nav-item">
              <a
              class="nav-link active"
              href="<?php echo base_url(); ?>healthcare-coordinator/loa/cancellation-requests/approved"
              role="tab"
              ><span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">Approved</span></a
              >
            </li>
         
        </ul>

        <div class="card shadow">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-responsive" id="cancellationsLoaTable">
                <thead>
                  <tr>
                    <th class="fw-bold">LOA No.</th>
                    <th class="fw-bold">Requested by</th>
                    <th class="fw-bold">Reason</th>
                    <th class="fw-bold">Approved on</th>
                    <th class="fw-bold">Approved by</th>
                    <th class="fw-bold">Status</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <?php include 'view_reason_cancellation_modal.php' ?>
      <!-- End Row  -->  
      </div>
    <!-- End Container fluid  -->
    </div>
  <!-- End Page wrapper  -->
  </div>
<!-- End Wrapper -->
<script>
  const baseUrl = "<?php echo base_url(); ?>";

  $(document).ready(function() {

    $('#cancellationsLoaTable').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}healthcare-coordinator/loa/approved-cancellation/fetch`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: {
          'token': '<?php echo $this->security->get_csrf_hash(); ?>'
        }
      },

      //Set column definition initialisation properties.
      columnDefs: [{
        "targets": [3,5], // numbering column
        "orderable": false, //set not orderable
      }, ],
      responsive: true,
      fixedHeader: true,
    });

  });

  const viewReason = (reason) => {
    $('#viewReasonModal').modal('show');
    $('#reason').val(reason);
  }

  const confirmRequest = (lcancel_id) => {
    $.confirm({
            title: '<strong>Confirmation!</strong>',
            content: 'Are you sure you want to confirm?',
            type: 'blue',
            buttons: {
                confirm: {
                    text: 'Yes',
                    btnClass: 'btn-blue',
                    action: function() {
                        let url = `${baseUrl}healthcare-coordinator/loa/cancellation-requests/confirm/${lcancel_id}`;

                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: {
                                    'token': '<?php echo $this->security->get_csrf_hash(); ?>'
                                  },
                            dataType: "json",
                            success: function(response) {
                                const { token, status, message} = response;

                                if (status == 'success') {
                                  swal({
                                        title: 'Success',
                                        text: message,
                                        timer: 2600,
                                        showConfirmButton: false,
                                        type: 'success'
                                    });
                                    
                                } else if(status == 'error') {

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