  
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
      <div class="col-lg-">
        <div class="row pt-2 pb-2">
            <div class="col-lg-3">
                <button type="button" class="btn btn-info btn-sm" onclick="showAddCostTypeModal()"><i class="mdi mdi-plus-circle fs-4"></i> Add New</button>
            </div>
            <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="col-lg-5 offset-4">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-info text-white">
                        <i class="mdi mdi-filter"></i>
                        </span>
                    </div>
                    <select class="form-select fw-bold" name="price-filter" id="price-filter">
                        <option value="">Select Price List</option>
                        <?php
                            // Remove duplicates from $price_group array
                            $unique_price_groups = array_unique(array_column($price_group, 'price_list_group'));
                            foreach($unique_price_groups as $group) :
                        ?>
                        <option value="<?php echo $group; ?>"><?php echo $group; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div> <br>
       
        <div class="card shadow">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-fit" id="costTypesTable">
                <thead>
                  <tr>
                    <th class="fw-bold">ITEM ID</th>
                    <th class="fw-bold">ITEM DESCRIPTION</th>
                    <th class="fw-bold">OP PRICE</th>
                    <th class="fw-bold">IP PRICE</th>
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
        <?php include 'register_cost_type.php'; ?>
    <!-- End Container fluid  -->
    </div>
  <!-- End Page wrapper  -->
  <?php include 'edit_cost_type.php'; ?>
  </div>
<!-- End Wrapper -->
<script>
  const baseUrl = `<?php echo base_url(); ?>`;

  $(document).ready(function() {
    
    let costTypeTable = $('#costTypesTable').DataTable({
        processing: true, //Feature control the processing indicator.
        serverSide: true, //Feature control DataTables' server-side processing mode.
        order: [], //Initial no order.

        // Load data for the table's content from an Ajax source
        ajax: {
            url: `${baseUrl}healthcare-coordinator/setup/cost-types/fetch`,
            type: "POST",
            data: function(data) {
                data.token     = '<?php echo $this->security->get_csrf_hash(); ?>';
                data.filter    = $('#price-filter').val();
            
            },
        },
        responsive: true,
        fixedHeader: true,
    });

    $('#price-filter').change(function(){
        costTypeTable.draw();
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
            price_list_error,
            cost_type_error,
            op_price_error,
            ip_price_error
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
              if (price_list_error !== '') {
                $('#price-list-error').html(price_list_error);
                $('#price-list').addClass('is-invalid');
              } else {
                $('#price-list-error').html('');
                $('#price-list').removeClass('is-invalid');
              }
              if (op_price_error !== '') {
                $('#op-price-error').html(op_price_error);
                $('#op-price').addClass('is-invalid');
              } else {
                $('#op-price-error').html('');
                $('#op-price').removeClass('is-invalid');
              }
              if (ip_price_error !== '') {
                $('#ip-price-error').html(ip_price_error);
                $('#ip-price').addClass('is-invalid');
              } else {
                $('#ip-price-error').html('');
                $('#ip-price').removeClass('is-invalid');
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

  // function editCostType(ctype_id) {
  //   $.ajax({
  //     url: `${baseUrl}healthcare-coordinator/setup/cost-types/edit/${ctype_id}`,
  //     type: "GET",
  //     success: function(response) {
  //       const res = JSON.parse(response);
  //       const {
  //         status,
  //         token,
  //         price_group,
  //         item_id,
  //         item_description,
  //         op_price,
  //         ip_price,
  //       } = res;
  //       $('#editCostTypeForm')[0].reset();
  //       $("#editCostTypeModal").modal("show");
  //       $('#ctype-id').val(ctype_id);
  //       $('#edit-item-id').val(item_id);
  //       $('#edit-cost-type').val(cost_type);
  //       $('#edit-price-cost').val(price_cost);
  //     }
  //   });
  // }

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