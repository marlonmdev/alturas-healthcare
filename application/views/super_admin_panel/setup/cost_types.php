
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
              <li class="breadcrumb-item">Super Admin</li>
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

        <div class="card shadow">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-fit" id="costTypesTable">
                <thead>
                  <tr>
                    <th class="fw-bold">#</th>
                    <th class="fw-bold">Cost Type</th>
                    <th class="fw-bold">Date Added</th>
                    <th class="fw-bold">Date Updated</th>
                    <th class="fw-bold">Actions</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
  <?php include 'register_cost_type.php'; ?>
</div>
<?php include 'edit_cost_type.php'; ?>
<script>
  const baseUrl = `<?php echo base_url(); ?>`;
  $(document).ready(function() {
    $("#costTypesTable").DataTable({
      ajax: {
        url: `${baseUrl}super-admin/setup/cost-types/fetch`,
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
            hp_id_error,
            price_list_error,
            cost_type_error,
            op_price_error,
            ip_price_error
          } = response;
          switch (status) {
            case 'error':
              if(hp_id_error != ''){
                $('#hp-filter-error').html(hp_id_error);
                $('#hospital-filter-add').addClass('is-invalid');
              }else{
                $('#hp-filter-error').html('');
                $('#hospital-filter-add').removeClass('is-invalid');
              }
              if (price_list_error !== '') {
                $('#price-filter-error').html(price_list_error);
                $('#price-filter-add').addClass('is-invalid');
              } else {
                $('#price-filter-error').html('');
                $('#price-filter-add').removeClass('is-invalid');
              }
              if (cost_type_error !== '') {
                $('#cost-type-error').html(cost_type_error);
                $('#cost-type').addClass('is-invalid');
              } else {
                $('#cost-type-error').html('');
                $('#cost-type').removeClass('is-invalid');
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


    // $('#registerCostTypeForm').submit(function(event) {
    //   event.preventDefault();
    //   $.ajax({
    //     type: "post",
    //     url: $(this).attr('action'),
    //     data: $(this).serialize(),
    //     dataType: "json",
    //     success: function(response) {
    //       const {
    //         token,
    //         status,
    //         message,
    //         cost_type_error
    //       } = response;
    //       switch (status) {
    //         case 'error':
    //           if (cost_type_error !== '') {
    //             $('#cost-type-error').html(cost_type_error);
    //             $('#cost-type').addClass('is-invalid');
    //           } else {
    //             $('#cost-type-error').html('');
    //             $('#cost-type').removeClass('is-invalid');
    //           }
    //           break;
    //         case 'save-error':
    //           swal({
    //             title: 'Failed',
    //             text: message,
    //             timer: 3000,
    //             showConfirmButton: false,
    //             type: 'error'
    //           });
    //           break;
    //         case 'success':
    //           swal({
    //             title: 'Success',
    //             text: message,
    //             timer: 3000,
    //             showConfirmButton: false,
    //             type: 'success'
    //           });
    //           $('#registerCostTypeModal').modal('hide');
    //           $("#costTypesTable").DataTable().ajax.reload();
    //           break;
    //       }
    //     }

    //   })
    // });

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
    $('#ip-price-error').html('');
    $('#ip-price').removeClass('is-invalid');
    $('#op-price-error').html('');
    $('#op-price').removeClass('is-invalid');
    $('#cost-type-error').html('');
    $('#cost-type').removeClass('is-invalid');
    $('#price-filter-error').html('');
    $('#price-filter-add').removeClass('is-invalid');
    $('#hp-filter-error').html('');
    $('#hospital-filter-add').removeClass('is-invalid');
    $('#other-input-container').hide();
  }

  const showOtherInputDiv = () => {
        const otherInputContainer = $('#other-input-container');

        if ($('#price-filter-add').val() == 'other') {
          otherInputContainer.show();
          $('#other-price-filter').attr('required', true);
        } else {  
          otherInputContainer.hide();
          $('#other-price-filter').removeAttr('required');
        }
    }

    const enableInputs = () => {
      const price_filter = $('#price-filter-add');
      const hp_filter = $('#hospital-filter-add');
      const item_id = $('#item-id');
      const cost_type = $('#cost-type');
      const op_price = $('#op-price');
      const ip_price = $('#ip-price');

      if(price_filter.val() != '' && hp_filter.val() != ''){
        item_id.removeAttr('readonly');
        cost_type.removeAttr('readonly');
        op_price.removeAttr('readonly');
        ip_price.removeAttr('readonly');
      }else{
        item_id.attr('readonly', true);
        cost_type.attr('readonly', true);
        op_price.attr('readonly', true);
        ip_price.attr('readonly', true);
      }
    }

  const editCostType = (ctype_id) => {
    $.ajax({
      url: `${baseUrl}super-admin/setup/cost-types/edit/${ctype_id}`,
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

  const deleteCostType = (ctype_id) => {
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
              url: `${baseUrl}super-admin/setup/cost-types/delete/${ctype_id}`,
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
