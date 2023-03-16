<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">Room Types</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">
                Setup Room Types
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
      <div class="col-lg-">
        <div class="row pt-2 pb-2">
            <div class="col-lg-3">
                <button type="button" class="btn btn-info btn-sm" onclick="showAddRoomTypeModal()"><i class="mdi mdi-plus-circle fs-4"></i> Add New</button>
            </div>
            <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
        </div> <br>
       
        <div class="card shadow">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-fit" id="roomTypesTable">
                <thead>
                  <tr>
                    <th class="fw-bold">ROOM TYPE</th>
                    <th class="fw-bold">ROOM TYPE HMO REQ.</th>
                    <th class="fw-bold">ROOM NUMBER</th>
                    <th class="fw-bold">ROOM RATE</th>
                    <th class="fw-bold">DATE ADDED</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- End Row  -->  
      </div>
      <?php include 'register_room_type.php' ?>
    <!-- End Container fluid  -->
    </div>
  <!-- End Page wrapper  -->
  </div>
<!-- End Wrapper -->
<script>
     const baseUrl = `<?= base_url() ?>`;
    $(document).ready(function() {
        
        $('#roomTypesTable').DataTable({
                    processing: true, //Feature control the processing indicator.
                    serverSide: true, //Feature control DataTables' server-side processing mode.
                    order: [], //Initial no order.

                    // Load data for the table's content from an Ajax source
                    ajax: {
                        url: `${baseUrl}healthcare-coordinator/setup/room-types/fetch`,
                        type: "POST",
                        data: function(data) {
                            data.token     = '<?php echo $this->security->get_csrf_hash(); ?>';
                        },
                    },

                    //Set column definition initialisation properties.
                    columnDefs: [{
                        "targets": [], // 5th column / numbering column
                        "orderable": false, //set not orderable
                    }, ],
                    responsive: true,
                    fixedHeader: true,
                });

        $('#registerRoomTypeForm').submit(function(event) {
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
                    hospital_error,
                    room_type_error,
                    room_num_error,
                    room_rate_error
                } = response;
                switch (status) {
                    case 'error':
                    if (hospital_error !== '') {
                        $('#hp-filter-error').html(hospital_error);
                        $('#hospital-filter').addClass('is-invalid');
                    } else {
                        $('#hp-filter-error').html('');
                        $('#hospital-filter').removeClass('is-invalid');
                    }
                    if (room_type_error !== '') {
                        $('#room-type-error').html(room_type_error);
                        $('#room-type').addClass('is-invalid');
                    } else {
                        $('#room-type-error').html('');
                        $('#room-type').removeClass('is-invalid');
                    }
                    if (room_num_error !== '') {
                        $('#room-num-error').html(room_num_error);
                        $('#room-num').addClass('is-invalid');
                    } else {
                        $('#room-num-error').html('');
                        $('#room-num').removeClass('is-invalid');
                    }
                    if (room_rate_error !== '') {
                        $('#room-rate-error').html(room_rate_error);
                        $('#room-rate').addClass('is-invalid');
                    } else {
                        $('#room-rate-error').html('');
                        $('#room-rate').removeClass('is-invalid');
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
                    $('#registerRoomTypeForm')[0].reset();
                    $('#registerRoomTypeModal').modal('hide');
                    $("#roomTypesTable").DataTable().ajax.reload();
                    break;
                }
              }
          })
        });
    });

    const showAddRoomTypeModal = () => {
        $('#registerRoomTypeModal').modal('show');
    }
</script>