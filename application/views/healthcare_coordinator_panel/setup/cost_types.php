  
<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">Cost Types</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">
                Setup Cost Types
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
        <button type="button" class="btn btn-info btn-sm" onclick="showAddCostTypeModal()"><i class="mdi mdi-plus-circle fs-4"></i> Add New</button>
        <br><br>

        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-fit" id="costTypesTable">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Cost Type</th>
                    <th>Date Added</th>
                    <th>Date Updated</th>
                    <th>Actions</th>
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
        <?php include 'register_cost_type.php'; ?>
    <!-- End Container fluid  -->
    </div>
  <!-- End Page wrapper  -->
  <?php include 'edit_cost_type.php'; ?>
  </div>
<!-- End Wrapper -->
<script>
  const baseUrl = `<?= base_url() ?>`;
  $(document).ready(function() {

    $("#costTypesTable").DataTable({
      ajax: {
        url: `${baseUrl}healthcare-coordinator/setup/cost-types/fetch`,
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
      //Set column definition initialisation properties.
      columnDefs: [{
        "targets": [4], // numbering column
        "orderable": false, //set not orderable
      }, ],
    });

    $('#registerCostTypeForm').submit(function(event) {
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
            cost_type_error
          } = response;
          switch (status) {
            case 'error':
              if (cost_type_error !== '') {
                $('#cost-type-error').html(cost_type_error);
                $('#cost-type').addClass('is-invalid');
              } else {
                $('#cost-type-error').html('');
                $('#cost-type').removeClass('is-invalid');
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
              $('#registerCostTypeModal').modal('hide');
              $("#costTypesTable").DataTable().ajax.reload();
              break;
          }
        }

      })
    });

    $('#editCostTypeForm').submit(function(event) {
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
            cost_type_error
          } = response;
          switch (status) {
            case 'error':
              if (cost_type_error !== '') {
                $('#edit-cost-type-error').html(cost_type_error);
                $('#edit-cost-type').addClass('is-invalid');
              } else {
                $('#edt-cost-type-error').html('');
                $('#edit-cost-type').removeClass('is-invalid');
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
              $('#editCostTypeModal').modal('hide');
              $("#costTypesTable").DataTable().ajax.reload();
              break;
          }
        }

      })
    });
  });

  function showAddCostTypeModal() {
    $("#registerCostTypeModal").modal("show");
    $("#registerCostTypeForm")[0].reset();
    $('#cost-type-error').html('');
    $('#cost-type').removeClass('is-invalid');
  }

  function editCostType(ctype_id) {
    $.ajax({
      url: `${baseUrl}healthcare-coordinator/setup/cost-types/edit/${ctype_id}`,
      type: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const {
          status,
          token,
          cost_type
        } = res;
        $('#editCostTypeForm')[0].reset();
        $("#editCostTypeModal").modal("show");
        $('#ctype-id').val(ctype_id);
        $('#edit-cost-type').val(cost_type);
      }
    });
  }

  function deleteCostType(ctype_id) {
    $.confirm({
      title: '<strong>Confirm!</strong>',
      content: 'Are you sure to delete Cost Type?',
      type: 'red',
      buttons: {
        confirm: {
          text: 'Yes',
          btnClass: 'btn-red',
          action: function() {
            $.ajax({
              type: 'GET',
              url: `${baseUrl}healthcare-coordinator/setup/cost-types/delete/${ctype_id}`,
              data: {
                ctype_id
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
                  $("#costTypesTable").DataTable().ajax.reload();
                } else {
                  swal({
                    title: 'Failed',
                    text: message,
                    timer: 3000,
                    showConfirmButton: false,
                    type: 'error'
                  });
                  $("#costTypesTable").DataTable().ajax.reload();
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