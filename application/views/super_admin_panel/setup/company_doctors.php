
<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">Company Doctors</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Super Admin</li>
              <li class="breadcrumb-item active" aria-current="page">
                Setup Company Doctors
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
        <button type="button" class="btn btn-info btn-sm" onclick="showAddDoctorModal()"><i class="mdi mdi-plus-circle fs-4"></i> Add New</button>
        <br><br>

        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-fit" id="companyDoctorsTable">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Signature</th>
                    <th>Date Added</th>
                    <th>Date Updated</th>
                    <th>Actions</th>
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
  <?php include 'register_doctor_modal.php'; ?>
</div>
<?php include 'edit_doctor_modal.php'; ?>
<script>
  const baseUrl = `<?php echo base_url(); ?>`;
  $(document).ready(function() {
    $("#companyDoctorsTable").DataTable({
      ajax: {
        url: `${baseUrl}super-admin/setup/company-doctors/fetch`,
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

    $('#registerDoctorForm').submit(function(event) {
      event.preventDefault();
      let $data = new FormData($(this)[0]);
      $.ajax({
        type: "post",
        url: $(this).attr('action'),
        data: $data,
        dataType: "json",
        processData: false,
        contentType: false,
        success: function(response) {
          const {
            token,
            status,
            message,
            doctor_name_error,
            doctor_signature_error,
            doctor_username_error,
            doctor_password_error
          } = response;
          switch (status) {
            case 'error':
              if (doctor_name_error !== '') {
                $('#doctor-name-error').html(doctor_name_error);
                $('#doctor-name').addClass('is-invalid');
              } else {
                $('#doctor-name-error').html('');
                $('#doctor-name').removeClass('is-invalid');
              }

              if (doctor_signature_error !== '') {
                $('#doctor-signature-error').html(doctor_signature_error);
                $('#signature-wrapper').addClass('div-has-error');
              } else {
                $('#doctor-signature-error').html('');
                $('#signature-wrapper').removeClass('div-has-error');
              }

              if (doctor_username_error !== '') {
                $('#doctor-username-error').html(doctor_username_error);
                $('#doctor-username').addClass('is-invalid');
              } else {
                $('#doctor-username-error').html('');
                $('#doctor-username').removeClass('is-invalid');
              }

              if (doctor_password_error !== '') {
                $('#doctor-password-error').html(doctor_password_error);
                $('#doctor-password').addClass('is-invalid');
              } else {
                $('#doctor-password-error').html('');
                $('#doctor-password').removeClass('is-invalid');
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
              $('#registerDoctorModal').modal('hide');
              $("#companyDoctorsTable").DataTable().ajax.reload();
              break;
          }
        }

      })
    });

    $('#editDoctorForm').submit(function(event) {
      event.preventDefault();
      let $data = new FormData($(this)[0]);
      $.ajax({
        type: "post",
        url: $(this).attr('action'),
        data: $data,
        dataType: "json",
        processData: false,
        contentType: false,
        success: function(response) {
          const {
            token,
            status,
            message,
            doctor_name_error,
            edit_signature_error
          } = response;
          switch (status) {
            case 'error':
              if (doctor_name_error !== '') {
                $('#doc-name-error').html(doctor_name_error);
                $('#edit-doctor-name').addClass('is-invalid');
              } else {
                $('#doc-name-error').html('');
                $('#edit-doctor-name').removeClass('is-invalid');
              }

              if (edit_signature_error !== '') {
                $('#edit-signature-error').html(edit_signature_error);
                $('#edit-signature-wrapper').addClass('div-has-error');
              } else {
                $('#edit-signature-error').html('');
                $('#edit-signature-wrapper').removeClass('div-has-error');
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
              $(".dropify-clear").trigger("click");
              $('#editDoctorModal').modal('hide');
              $("#companyDoctorsTable").DataTable().ajax.reload();
              break;
          }
        }

      })
    });

    /* Changing the type of the input field from password to text and vice versa. */
    $('.main-password').find('.input-password').each(function(index, input) {
      var $input = $(input);
      $input.parent().find('.icon-view').click(function() {
        var change = "";
        if ($(this).find('i').hasClass('mdi-eye')) {
          $(this).find('i').removeClass('mdi-eye')
          $(this).find('i').addClass('mdi-eye-off')
          change = "text";
        } else {
          $(this).find('i').removeClass('mdi-eye-off')
          $(this).find('i').addClass('mdi-eye')
          change = "password";
        }
        var rep = $("<input type='" + change + "' />")
          .attr('id', $input.attr('id'))
          .attr('name', $input.attr('name'))
          .attr('class', $input.attr('class'))
          .val($input.val())
          .insertBefore($input);
        $input.remove();
        $input = rep;
      }).insertAfter($input);
    });


  });

  function viewImage(path) {
    let item = [{
      src: path, // path to image
      title: 'Doctors Signature' // If you skip it, there will display the original image name
    }];
    // define options (if needed)
    let options = {
      index: 0 // this option means you will start at first image
    };
    // Initialize the plugin
    let photoviewer = new PhotoViewer(item, options);
  }

  function showAddDoctorModal() {
    $("#registerDoctorForm")[0].reset();
    $("#registerDoctorModal").modal("show");
    $(".dropify-clear").trigger("click");
    $('#doctor-name-error').html('');
    $('#doctor-name').removeClass('is-invalid');
    $('#signature-error').html('');
    $('#signature-wrapper').removeClass('div-has-error');
  }

  function setDefaultPassword() {
    const password = document.querySelector("#doctor-password");
    password.value = '<?= $this->config->item('def_user_password') ?>';
  }

  function editCompanyDoctor(doctor_id) {
    $.ajax({
      url: `${baseUrl}super-admin/setup/company-doctors/edit/${doctor_id}`,
      type: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const {
          status,
          token,
          doctor_name,
          signature
        } = res;
        $('#editDoctorForm')[0].reset();
        $('#editDoctorModal').modal('show');
        $('#doctor-id').val(doctor_id);
        $('#edit-doctor-name').val(doctor_name);
        if (signature != '') {
          $('#signature-span').removeClass('d-none');
          $('#signature-img').attr('src', `${baseUrl}uploads/doctor_signatures/${signature}`);
        } else {
          $('#signature-span').addClass('d-none');
        }
      }
    });
  }

  function deleteCompanyDoctor(doctor_id) {
    $.confirm({
      title: '<strong>Confirm!</strong>',
      content: 'Are you sure to delete Company Doctor?',
      type: 'red',
      buttons: {
        confirm: {
          text: 'Yes',
          btnClass: 'btn-red',
          action: function() {
            $.ajax({
              type: 'GET',
              url: `${baseUrl}super-admin/setup/company-doctors/delete/${doctor_id}`,
              data: {
                doctor_id
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
                  $("#companyDoctorsTable").DataTable().ajax.reload();
                } else {
                  swal({
                    title: 'Failed',
                    text: message,
                    timer: 3000,
                    showConfirmButton: false,
                    type: 'error'
                  });
                  $("#companyDoctorsTable").DataTable().ajax.reload();
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
