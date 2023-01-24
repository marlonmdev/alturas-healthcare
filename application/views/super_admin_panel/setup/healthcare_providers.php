
<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">Healthcare Providers</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Super Admin</li>
              <li class="breadcrumb-item active" aria-current="page">
                Setup Healthcare Providers
              </li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
  <!-- End Bread crumb and right sidebar toggle -->
  <?php include 'view_healthcare_provider.php'; ?>
  <!-- Start of Container fluid  -->
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <button type="button" class="btn btn-info btn-sm" onclick="showAddHPModal()"><i class="mdi mdi-plus-circle fs-4"></i>
          Add New
        </button>
        <br><br>

        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-fit" id="healthCareProvidersTable">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Address</th>
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
      <?php include 'register_healthcare_provider.php'; ?>
    <!-- End Container fluid  -->
    </div>
  <!-- End Page wrapper  -->
  <?php include 'edit_healthcare_provider.php'; ?>
  </div>
<!-- End Wrapper -->

<script>
  const baseUrl = `<?php echo base_url(); ?>`;
  $(document).ready(function() {
    $("#healthCareProvidersTable").DataTable({
      ajax: {
        url: `${baseUrl}super-admin/setup/healthcare-providers/fetch`,
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

    $("#registerHPForm").submit(function(event) {
      event.preventDefault();
      $.ajax({
        type: "post",
        url: $(this).attr("action"),
        data: $(this).serialize(),
        dataType: "json",
        success: function(response) {
          const {
            token,
            status,
            message,
            type_error,
            name_error,
            address_error,
            contact_error,
          } = response;
          switch (status) {
            case 'error':
              if (type_error !== "") {
                $("#hp-type-error").html(type_error);
                $("#hp-type").addClass("is-invalid");
              } else {
                $("#hp-type-error").html("");
                $("#hp-type").removeClass("is-invalid");
              }

              if (name_error !== "") {
                $("#hp-name-error").html(name_error);
                $("#hp-name").addClass("is-invalid");
              } else {
                $("#hp-name-error").html("");
                $("#hp-name").removeClass("is-invalid");
              }

              if (address_error !== "") {
                $("#hp-address-error").html(address_error);
                $("#hp-address").addClass("is-invalid");
              } else {
                $("#hp-address-error").html("");
                $("#hp-address").removeClass("is-invalid");
              }

              if (contact_error !== "") {
                $("#hp-contact-error").html(contact_error);
                $("#hp-contact").addClass("is-invalid");
              } else {
                $("#hp-contact-error").html("");
                $("#hp-contact").removeClass("is-invalid");
              }
              break;
            case 'save-error':
              swal({
                title: "Failed",
                text: message,
                timer: 3000,
                showConfirmButton: false,
                type: "error",
              });
              break;
            case 'success':
              swal({
                title: "Success",
                text: message,
                timer: 3000,
                showConfirmButton: false,
                type: "success",
              });
              removeValidationErrors();
              $("#registerHPModal").modal("hide");
              $("#healthCareProvidersTable").DataTable().ajax.reload();
              break;
          }
        },
      });
    });

    $("#editHPForm").submit(function(event) {
      event.preventDefault();
      $.ajax({
        type: "post",
        url: $(this).attr("action"),
        data: $(this).serialize(),
        dataType: "json",
        success: function(response) {
          const {
            token,
            status,
            message,
            type_error,
            name_error,
            address_error,
            contact_error,
          } = response;
          switch (status) {
            case 'error':
              if (type_error !== "") {
                $("#edit-hp-type-error").html(type_error);
                $("#edit-hp-type").addClass("is-invalid");
              } else {
                $("#edit-hp-type-error").html("");
                $("#edit-hp-type").removeClass("is-invalid");
              }

              if (name_error !== "") {
                $("#edit-hp-name-error").html(name_error);
                $("#edit-hp-name").addClass("is-invalid");
              } else {
                $("#edit-hp-name-error").html("");
                $("#edit-hp-name").removeClass("is-invalid");
              }

              if (address_error !== "") {
                $("#edit-hp-address-error").html(address_error);
                $("#edit-hp-address").addClass("is-invalid");
              } else {
                $("#edit-hp-address-error").html("");
                $("#edit-hp-address").removeClass("is-invalid");
              }

              if (contact_error !== "") {
                $("#edit-hp-contact-error").html(contact_error);
                $("#edit-hp-contact").addClass("is-invalid");
              } else {
                $("#edit-hp-contact-error").html("");
                $("#edit-hp-contact").removeClass("is-invalid");
              }
              break;
            case 'save-error':
              swal({
                title: "Failed",
                text: message,
                timer: 3000,
                showConfirmButton: false,
                type: "error",
              });
              break;
            case 'success':
              swal({
                title: "Success",
                text: message,
                timer: 3000,
                showConfirmButton: false,
                type: "success",
              });
              removeEditValidationErrors();
              $("#editHPModal").modal("hide");
              $("#healthCareProvidersTable").DataTable().ajax.reload();
              break;
          }
        },
      });
    });


  }) // End of Document ready function

  function showAddHPModal() {
    $("#registerHPModal").modal("show");
    $("#registerHPForm")[0].reset();
    removeValidationErrors();
  }

  function removeValidationErrors() {
    $("#registerHPForm")[0].reset();
    $("#hp-type-error").html("");
    $("#hp-type").removeClass("is-invalid");
    $("#hp-name-error").html("");
    $("#hp-name").removeClass("is-invalid");
    $("#hp-address-error").html("");
    $("#hp-address").removeClass("is-invalid");
    $("#hp-contact-error").html("");
    $("#hp-contact").removeClass("is-invalid");
  }

  function removeEditValidationErrors() {
    $("#editHPForm")[0].reset();
    $("#edit-hp-type-error").html("");
    $("#edit-hp-type").removeClass("is-invalid");
    $("#edit-hp-name-error").html("");
    $("#edit-hp-name").removeClass("is-invalid");
    $("#edit-hp-address-error").html("");
    $("#edit-hp-address").removeClass("is-invalid");
    $("#edit-hp-contact-error").html("");
    $("#edit-hp-contact").removeClass("is-invalid");
  }


  function viewHealthCareProvider(hp_id) {
    $.ajax({
      url: `${baseUrl}super-admin/setup/healthcare-providers/view/${hp_id}`,
      type: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const {
          status,
          token,
          hp_id,
          hp_name,
          hp_type,
          hp_address,
          hp_contact,
          date_added,
          date_updated
        } = res;
        $("#viewHPModal").modal("show");
        $('#name').html(hp_name);
        $('#type').html(hp_type);
        $('#address').html(hp_address);
        $('#contact').html(hp_contact);
        $('#date-added').html(date_added);
        $('#date-updated').html(date_updated);
      }
    });
  }

  function editHealthCareProvider(hp_id) {
    $.ajax({
      url: `${baseUrl}super-admin/setup/healthcare-providers/edit/${hp_id}`,
      type: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const {
          status,
          token,
          hp_id,
          hp_type,
          hp_name,
          hp_address,
          hp_contact
        } = res;
        $('#editHPForm')[0].reset();
        $("#editHPModal").modal("show");
        $('#hp-id').val(hp_id);
        $('#edit-hp-type').val(hp_type);
        $('#edit-hp-name').val(hp_name);
        $('#edit-hp-address').val(hp_address);
        $('#edit-hp-contact').val(hp_contact);
      }
    });
  }

  function deleteHealthCareProvider(hp_id) {
    $.confirm({
      title: '<strong>Confirm!</strong>',
      content: 'Are you sure to delete HealthCare Provider?',
      type: 'red',
      buttons: {
        confirm: {
          text: 'Yes',
          btnClass: 'btn-red',
          action: function() {
            $.ajax({
              type: 'GET',
              url: `${baseUrl}super-admin/setup/healthcare-providers/delete/${hp_id}`,
              data: {
                hp_id
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
                  $("#healthCareProvidersTable").DataTable().ajax.reload();
                } else {
                  swal({
                    title: 'Failed',
                    text: message,
                    timer: 3000,
                    showConfirmButton: false,
                    type: 'error'
                  });
                  $("#healthCareProvidersTable").DataTable().ajax.reload();
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
