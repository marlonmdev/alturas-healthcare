<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">Room Types</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">Setup Room Types</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
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
                    <th class="fw-bold">#</th>
                    <th class="fw-bold">Healthcare Provider</th>
                    <th class="fw-bold">Room Type</th>
                    <th class="fw-bold">Room No.</th>
                    <th class="fw-bold">Room Rate</th>
                    <th class="fw-bold">Date Added</th>
                    <th class="fw-bold">Actions</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php include 'register_room_type.php' ?>
  </div>
  <?php include 'edit_room_type.php' ?>
</div>


<script>
  const baseUrl = `<?= base_url() ?>`;
  $(document).ready(function() { 
    $('#roomTypesTable').DataTable({
      processing: true,
      serverSide: true,
      order: [],

      ajax: {
        url: `${baseUrl}healthcare-coordinator/setup/room-types/fetch`,
        type: "POST",
        data: function(data) {
          data.token     = '<?php echo $this->security->get_csrf_hash(); ?>';
        },
      },

      columnDefs: [{
        "targets": [6],
        "orderable": false,
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
              }else {
                $('#hp-filter-error').html('');
                $('#hospital-filter').removeClass('is-invalid');
              }if (room_type_error !== '') {
                $('#room-type-error').html(room_type_error);
                $('#room-type').addClass('is-invalid');
              }else {
                $('#room-type-error').html('');
                $('#room-type').removeClass('is-invalid');
              }if (room_num_error !== '') {
                $('#room-num-error').html(room_num_error);
                $('#room-num').addClass('is-invalid');
              }else {
                $('#room-num-error').html('');
                $('#room-num').removeClass('is-invalid');
              }if (room_rate_error !== '') {
                $('#room-rate-error').html(room_rate_error);
                $('#room-rate').addClass('is-invalid');
              }else {
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
                timer: 2600,
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

    $('#editRoomTypeForm').submit(function(event) {
      event.preventDefault();
      $.ajax({
        type: "post",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType: "json",
        success: function(response){
          const {
            token,
            status,
            message,
            hcare_provider_error,
            room_type_error,
            room_num_error,
            room_rate_error
          } = response;
          switch (status) {
            case 'error':
              if (hcare_provider_error !== '') {
                $('#edit-hp-filter-error').html(hcare_provider_error);
                $('#edit-hospital-filter').addClass('is-invalid');
              }else {
                $('#edit-hp-filter-error').html('');
                $('#edit-hospital-filter').removeClass('is-invalid');
              }if (room_type_error !== '') {
                $('#edit-room-type-error').html(room_type_error);
                $('#edit-room-type').addClass('is-invalid');
              }else {
                $('#edit-room-type-error').html('');
                $('#edit-room-type').removeClass('is-invalid');
              }if (room_num_error !== '') {
                $('#edit-room-num-error').html(room_num_error);
                $('#edit-room-num').addClass('is-invalid');
              }else {
                $('#edit-room-num-error').html('');
                $('#edit-room-num').removeClass('is-invalid');
              }if (room_rate_error !== '') {
                $('#edit-room-rate-error').html(room_rate_error);
                $('#edit-room-rate').addClass('is-invalid');
              }else {
                $('#edit-room-rate-error').html('');
                $('#edit-room-rate').removeClass('is-invalid');
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
              $('#editRoomTypeModal').modal('hide');
              $("#roomTypesTable").DataTable().ajax.reload();
            break;
          }
        }
      })
    });
  });

  const showAddRoomTypeModal = () => {
    $('#registerRoomTypeModal').modal('show');

    $('#registerRoomTypeForm')[0].reset();
    $('#room-rate-error').html('');
    $('#room-rate').removeClass('is-invalid');
    $('#room-num-error').html('');
    $('#room-num').removeClass('is-invalid');
    $('#room-type').removeClass('is-invalid');
    $('#room-type-error').html('');
    $('#hospital-filter').removeClass('is-invalid');
    $('#hp-filter-error').html('');
  }

  const enableInputs = () => {
    const room_type = document.querySelector('#room-type');
    const room_hmo_req = document.querySelector('#room-hmo-req');
    const room_num = document.querySelector('#room-num');
    const room_rate = document.querySelector('#room-rate');
    const hospital = document.querySelector('#hospital-filter');

    if(hospital != ''){
      room_type.removeAttribute('readonly');
      room_hmo_req.removeAttribute('readonly');
      room_num.removeAttribute('readonly');
      room_rate.removeAttribute('readonly');
    }else{  
      room_type.setAttribute('readonly', true);
      room_hmo_req.setAttribute('readonly', true);
      room_num.setAttribute('readonly', true);
      room_rate.setAttribute('readonly', true);
    }
  }

  const editRoomType = (room_id) => {
    $.ajax({
      url: `${baseUrl}healthcare-coordinator/setup/room-types/edit/${room_id}`,
      type: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const {
          status,
          token,
          hp_id,
          rt_hmo_req,
          room_type,
          room_number,
          room_rate,
        } = res;
        $('#editRoomTypeForm')[0].reset();
        $("#editRoomTypeModal").modal("show");

        $('#edit-hospital-filter').removeClass('is-invalid');
        $('#edit-hp-filter-error').html('');
        $('#edit-room-type').removeClass('is-invalid');
        $('#edit-room-type-error').html('');
        $('#edit-room-num-error').html('');
        $('#edit-room-num').removeClass('is-invalid');
        $('#room-rate-error').html('');
        $('#edit-room-rate').removeClass('is-invalid');

        $('#edit-room-id').val(room_id);
        $('#edit-hospital-filter').val(hp_id);
        $('#edit-room-hmo-req').val(rt_hmo_req);
        $('#edit-room-type').val(room_type);
        $('#edit-room-num').html(room_number);
        $('#edit-room-rate').val(room_rate);
      }
    });
  }

  const deleteRoomType = (room_id) => {
    $.confirm({
      title: '<strong>Confirm!</strong>',
      content: 'Are you sure to delete Room?',
      type: 'red',
      buttons: {
        confirm: {
          text: 'Yes',
          btnClass: 'btn-red',
          action: function() {
            $.ajax({
              type: 'GET',
              url: `${baseUrl}healthcare-coordinator/setup/room-types/delete/${room_id}`,
              data: {
                room_id
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
                  $("#roomTypesTable").DataTable().ajax.reload();
                }else {
                  swal({
                    title: 'Failed',
                    text: message,
                    timer: 3000,
                    showConfirmButton: false,
                    type: 'error'
                  });
                  $("#roomTypesTable").DataTable().ajax.reload();
                }
              }
            });
          }
        },
        cancel: {
          btnClass: 'btn-dark',
          action: function() {
          }
        },
      }
    });
  }
</script>