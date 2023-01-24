<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">User Accounts</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">
                User Accounts
              </li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
  <!-- End Bread crumb and right sidebar toggle -->
  <?php include 'register_user_account_modal.php' ?>
  <!-- Start of Container fluid  -->
  <div class="container-fluid">
    <div class="row">
      <?php include 'edit_user_account_modal.php' ?>
      <div class="col-lg-12">
        <div class="row">
          <div class="col-lg-3 col-md-4">
            <button type="button" class="btn btn-info btn-sm" onclick="showAddUserModal()"><i class="mdi mdi-plus-circle fs-4"></i> Add User</button>
          </div>
          <div class="col-lg-6 col-md-4"></div>

          <div class="col-lg-3 col-md-4">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text bg-info text-white">
                  <i class="mdi mdi-filter"></i>
                </span>
              </div>
              <select class="form-select" id="role-filter">
                <option value="">Filter User Role Here</option>
                <option value="member">Member</option>
                <option value="healthcare-coordinator">Healthcare Coordinator</option>
                <option value="healthcare-provider">Healthcare Provider</option>
                <option value="company-doctor">Company Doctor</option>
                <option value="super-admin">Super Admin</option>
                <option value="head-office-accounting">Head Office Accounting</option>
              </select>
            </div>
          </div>
        </div>
        <br>

        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-responsive" id="userAccountsTable">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Username</th>
                    <th>Change Status</th>
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
      </div>

      <!-- End Row  -->
      </div>
    <!-- End Container fluid  -->
    <?php include 'user_account_details_modal.php'; ?>
    </div>
  <!-- End Page wrapper  -->
  </div>
<!-- End Wrapper -->

<script>
  const baseUrl = `<?= base_url() ?>`;
  $(document).ready(function() {

    let userTable = $('#userAccountsTable').DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: `${baseUrl}healthcare-coordinator/accounts/fetch`,
        type: "POST",
        // passing the token as data so that requests will be allowed
        data: function(data) {
          data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
          data.filter = $('#role-filter').val();
        }
      },

      //Set column definition initialisation properties.
      columnDefs: [{
        "targets": [4, 5, 6], //4th, 5th, and 6th column / numbering column
        "orderable": false, //set not orderable
      }, ],
      responsive: true,
      fixedHeader: true,
    });

    $('#role-filter').change(function(){
      userTable.draw();
    });

    // insert user account event on submit
    /* The below code is a jQuery code that is used to submit the form data to the server. */
    $("#registerAccountForm").submit(function(event) {
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
            full_name_error,
            user_role_error,
            dsg_hcare_prov_error,
            username_error,
            password_error,
          } = response;
          switch (status) {
            case 'error':
              // is-invalid class is a built in classname for errors in bootstrap
              if (full_name_error !== "") {
                $("#full-name-error").html(full_name_error);
                $("#full-name").addClass("is-invalid");
              } else {
                $("#full-name-error").html("");
                $("#full-name").removeClass("is-invalid");
              }

              if (user_role_error !== "") {
                $("#user-role-error").html(user_role_error);
                $("#user-role").addClass("is-invalid");
              } else {
                $("#user-role-error").html("");
                $("#user-role").removeClass("is-invalid");
              }

              if (dsg_hcare_prov_error !== "") {
                $("#dsg-hcare-prov-error").html(dsg_hcare_prov_error);
                $("#dsg-hcare-prov").addClass("is-invalid");
              } else {
                $("#dsg-hcare-prov-error").html("");
                $("#dsg-hcare-prov").removeClass("is-invalid");
              }

              if (username_error !== "") {
                $("#username-error").html(username_error);
                $("#username").addClass("is-invalid");
              } else {
                $("#username-error").html("");
                $("#username").removeClass("is-invalid");
              }

              if (password_error !== "") {
                $("#password-error").html(password_error);
                $("#password").addClass("is-invalid");
              } else {
                $("#password-error").html("");
                $("#password").removeClass("is-invalid");
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
              $("#addUserAccountModal").modal("hide");
              $("#userAccountsTable").DataTable().ajax.reload();
              break;
          }
        },
      });
    });


    // update user account event on submit
    /* The below code is a jQuery code that is used to submit the form data to the server. */
    $("#editAccountForm").submit(function(event) {
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
            full_name_error,
            user_role_error,
            dsg_hcare_prov_error,
            username_error,
          } = response;
          switch (status) {
            case 'error':
              // is-invalid class is a built in classname for errors in bootstrap
              if (full_name_error !== "") {
                $("#edit-full-name-error").html(full_name_error);
                $("#edit-full-name").addClass("is-invalid");
              } else {
                $("#edit-full-name-error").html("");
                $("#edit-full-name").removeClass("is-invalid");
              }

              if (user_role_error !== "") {
                $("#edit-user-role-error").html(user_role_error);
                $("#edit-user-role").addClass("is-invalid");
              } else {
                $("#edit-user-role-error").html("");
                $("#edit-user-role").removeClass("is-invalid");
              }

              if (dsg_hcare_prov_error !== "") {
                $("#edit-dsg-hcare-prov-error").html(dsg_hcare_prov_error);
                $("#edit-dsg-hcare-prov").addClass("is-invalid");
              } else {
                $("#edit-dsg-hcare-prov-error").html("");
                $("#edit-dsg-hcare-prov").removeClass("is-invalid");
              }

              if (username_error !== "") {
                $("#edit-username-error").html(username_error);
                $("#edit-username").addClass("is-invalid");
              } else {
                $("#edit-username-error").html("");
                $("#edit-username").removeClass("is-invalid");
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
              $("#editUserAccountModal").modal("hide");
              $("#userAccountsTable").DataTable().ajax.reload();
              break;
          }
        },
      });
    });


    $("#changeUserPasswordForm").submit(function(event) {
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
            new_password_error,
            confirm_password_error,
          } = response;
          switch (status) {
            case 'error':
              // is-invalid class is a built in classname for errors in bootstrap
              if (new_password_error !== "") {
                $("#new-password-error").html(new_password_error);
                $("#new-password").addClass("is-invalid");
              } else {
                $("#new-password-error").html("");
                $("#new-password").removeClass("is-invalid");
              }

              if (confirm_password_error !== "") {
                $("#confirm-password-error").html(confirm_password_error);
                $("#confirm-password").addClass("is-invalid");
              } else {
                $("#confirm-password-error").html("");
                $("#confirm-password").removeClass("is-invalid");
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
              $("#changeUserPasswordModal").modal("hide");
              $("#userAccountsTable").DataTable().ajax.reload();
              break;
          }
        },
      });
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

  }); // End of document ready

  function showAddUserModal() {
    $('#registerAccountForm')[0].reset();
    removeValidationErrors();
    $("#addUserAccountModal").modal("show");
  }


  function removeValidationErrors() {
    /* The below code is removing the error messages and the red border from the input fields. */
    $('#registerAccountForm')[0].reset();
    $('#registerAccountForm').find('input').removeClass('is-invalid');
    $('#registerAccountForm').find('select').removeClass('is-invalid');
    $('#registerAccountForm').find('em.text-danger').html('');
    $('#registerAccountForm').find('#pwd-icon').removeClass('mdi-eye-off');
    $('#registerAccountForm').find('#pwd-icon').addClass('mdi-eye');
  }

  /* The below code is checking the value of the user role input and if the value is equal to
  "healthcare-provider" then it will display the div with the id of "dsgHcareProvDiv" and if the
  value is not equal to "healthcare-provider" then it will hide the div with the id of
  "dsgHcareProvDiv". */
  function showHcareProv() {
    const role_input = document.querySelector('#user-role').value;
    const username = document.querySelector('#username');
    const password = document.querySelector('#password');
    const dsg_hcare_prov = document.querySelector('#dsgHcareProvDiv');
    if (role_input === '') {
      dsg_hcare_prov.className = 'd-none';
    } else if (role_input === "healthcare-provider") {
      document.querySelector('#full-name').value = '';
      document.querySelector('#username').value = '';
      document.querySelector('#password').value = '';
      dsg_hcare_prov.className = 'row mb-3 d-block';
    } else {
      dsg_hcare_prov.className = 'd-none';
      username.value = '';
      password.value = '';
    }
  }

  // for Edit User Account Modal
  function showEditHcareProv() {
    /* The below code is checking the value of the role input field and if the value is equal to
    "healthcare-provider" then the div with the id of "editDsgHcareProvDiv" will be shown. */
    const role_input = document.querySelector('#edit-user-role').value;
    const dsg_hcare_prov = document.querySelector('#editDsgHcareProvDiv');
    if (role_input === "") {
      dsg_hcare_prov.className = "d-none";
    } else if (role_input === "healthcare-provider") {
      dsg_hcare_prov.className = "row mb-3 d-block";
    } else {
      dsg_hcare_prov.className = "d-none";
    }
  }

  function searchMember() {
    /* A function that is called when the user types in the search input. */
    const token = document.querySelector('#token').value;
    const search_input = document.querySelector('#input-search-member');
    const result_div = document.querySelector('#search-results');
    var search = search_input.value;
    if (search !== '') {
      load_data(token, search);
    } else {
      result_div.innerHTML = '';
    }
  }


  function load_data(token, search) {
    /* The below code is sending a POST request to the server with the token and search data. */
    $.ajax({
      url: `${baseUrl}healthcare-coordinator/member/search`,
      method: "POST",
      data: {
        token: token,
        search: search
      },
      success: function(data) {
        $('#search-results').removeClass('d-none');
        $('#search-results').html(data);
      }
    });
  }

  function getMemberValues(emp_id) {
    /* The below code is ajax request to the backend. */
    $.ajax({
      url: `${baseUrl}healthcare-coordinator/accounts/member/search/${emp_id}`,
      method: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const {
          token,
          member_id,
          position_level,
          first_name,
          middle_name,
          last_name,
          suffix,
          date_regularized
        } = res;
        $('#search-results').addClass('d-none');
        $('#input-search-member').val('');
        $('#emp-id').val(emp_id);
        $('#full-name').val(`${first_name} ${middle_name} ${last_name} ${suffix}`);
        $('#username').val(generateRandomString(8));
        $('#date-regularized').val(date_regularized);
      }
    });
  }

  function generateRandomCredentials() {
    const username = document.querySelector("#username");
    const password = document.querySelector("#password");
    const current_month = <?= date('m') ?>;
    const current_day = <?= date('d') ?>;
    const current_year = new Date().getFullYear();
    username.value = `${generateRandomString(4)}${current_month}${current_day}`;
    password.value = `Ahc${generateRandomString(5)}`;
  }

  function setDefaultPassword() {
    const password = document.querySelector("#password");
    password.value = '<?= $this->config->item('def_user_password') ?>';
  }

  // for Edit User Account Modal
  function generateUsername() {
    const username = document.querySelector("#edit-username");
    username.value = generateRandomString(8);
  }

  function viewUserAccount(user_id) {
    /* Fetching the user details from the database and displaying it in the modal. */
    $.ajax({
      url: `${baseUrl}healthcare-coordinator/accounts/view/${user_id}`,
      type: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const {
          status,
          token,
          photo,
          emp_id,
          full_name,
          user_role,
          dsg_hcare_prov,
          username,
          created_on,
          updated_on
        } = res;
        $("#viewUserAccountModal").modal("show");
        $('#view-emp-id').html(emp_id);
        $('#view-full-name').html(full_name);
        $('#view-user-role').html(user_role);
        $('#view-dsg-hcare-prov').html(dsg_hcare_prov);
        $('#view-username').html(username);
        $('#view-added-on').html(created_on);
        if (photo == '') {
          $('#view-user-img').attr('src', `${baseUrl}assets/images/default.png`);
        } else {
          $('#view-user-img').attr('src', `${baseUrl}uploads/profile_pics/${photo}`);
        }
      }
    });
  }

  // for Edit User Account Modal
  /* The below code is fetching the user details from the database and displaying it in the modal. */
  function editUserAccount(user_id) {
    $.ajax({
      url: `${baseUrl}healthcare-coordinator/accounts/edit/${user_id}`,
      type: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const div = document.querySelector('#editDsgHcareProvDiv');
        const {
          status,
          token,
          user_id,
          full_name,
          user_role,
          dsg_hcare_prov,
          created_on,
          updated_on
        } = res;
        $("#editUserAccountModal").modal("show");
        if (user_role === "") {
          div.className = "d-none";
        } else if (user_role === "healthcare-provider") {
          div.className = "row mb-3 d-block";
        } else {
          div.className = "d-none";
        }
        $('#user-id').val(user_id);
        $('#edit-full-name').val(full_name);
        $('#edit-user-role').val(user_role);
        $('#edit-dsg-hcare-prov').val(dsg_hcare_prov);
      }
    });
  }

  function changeUserAccountStatus(user_id) {
    /* Changing the status of the user account. */
    $.ajax({
      type: 'GET',
      url: `${baseUrl}healthcare-coordinator/accounts/change-status/${user_id}`,
      data: {
        user_id
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
          $("#userAccountsTable").DataTable().ajax.reload();
        } else {
          swal({
            title: 'Failed',
            text: message,
            timer: 3000,
            showConfirmButton: false,
            type: 'error'
          });
          $("#userAccountsTable").DataTable().ajax.reload();
        }
      }
    });
  }

  function resetUserPassword(user_id) {
    const def_password = '<?php echo $this->config->item('def_user_password'); ?>';
    $.confirm({
      title: '<strong>Confirm!</strong>',
      content: `Are you sure to reset password? The default password is: ${def_password}`,
      type: 'orange',
      buttons: {
        confirm: {
          text: 'Yes',
          btnClass: 'btn-orange',
          action: function() {
            $.ajax({
              type: 'GET',
              url: `${baseUrl}healthcare-coordinator/accounts/reset-password/${user_id}`,
              data: {
                user_id
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
                  $("#userAccountsTable").DataTable().ajax.reload();
                } else {
                  swal({
                    title: 'Failed',
                    text: message,
                    timer: 3000,
                    showConfirmButton: false,
                    type: 'error'
                  });
                  $("#userAccountsTable").DataTable().ajax.reload();
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

  function deleteUserAccount(user_id) {
    $.confirm({
      title: '<strong>Confirm!</strong>',
      content: 'Are you sure to delete?',
      type: 'red',
      buttons: {
        confirm: {
          text: 'Yes',
          btnClass: 'btn-red',
          action: function() {
            $.ajax({
              type: 'GET',
              url: `${baseUrl}healthcare-coordinator/accounts/delete/${user_id}`,
              data: {
                user_id
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
                  $("#userAccountsTable").DataTable().ajax.reload();
                } else {
                  swal({
                    title: 'Failed',
                    text: message,
                    timer: 3000,
                    showConfirmButton: false,
                    type: 'error'
                  });
                  $("#userAccountsTable").DataTable().ajax.reload();
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
