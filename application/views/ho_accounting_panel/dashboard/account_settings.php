
  <!-- Page wrapper  -->
  <div class="page-wrapper">
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title ls-2">Account Settings</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Head Office Accounting</li>
                <li class="breadcrumb-item active" aria-current="page">
                  Account Settings
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- Container fluid  -->
    <div class="container-fluid">
        <div class="row">
          <div class="col-lg-6">
            <!-- Change password card-->
            <div class="card">
              <div class="card-header fs-5 font-bold ls-1">Change Password</div>
              <div class="card-body">
                <form method="post" action="<?php echo base_url(); ?>head-office-accounting/account-settings/password/update" class="mt-2" id="passwordUpdateForm">
                  <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
                  <div class="mb-3">
                    <label class="mb-1">Current Password</label>
                    <div class="main-password">
                      <input type="password" class="form-control input-password" name="current-password" id="current-password" aria-label="current-password">
                      <a href="JavaScript:void(0);" class="icon-view">
                        <i class="mdi mdi-eye"></i>
                      </a>
                    </div>
                    <em class="text-danger" id="current-password-error"></em>
                  </div>
                  <div class="mb-3">
                    <label class="mb-1">New Password</label>
                    <div class="main-password">
                      <input type="password" class="form-control input-password" name="new-password" id="new-password" aria-label="new-password">
                      <a href="JavaScript:void(0);" class="icon-view">
                        <i class="mdi mdi-eye"></i>
                      </a>
                    </div>
                    <em class="text-danger" id="new-password-error"></em>
                  </div>
                  <div class="mb-3">
                    <label class="mb-1">Confirm Password</label>
                    <div class="main-password">
                      <input type="password" class="form-control input-password" name="confirm-password" id="confirm-password" aria-label="confirm-password">
                      <a href="JavaScript:void(0);" class="icon-view">
                        <i class="mdi mdi-eye"></i>
                      </a>
                    </div>
                    <em class="text-danger" id="confirm-password-error"></em>
                  </div>
                  <button type="submit" class="btn btn-success me-1"><i class="mdi mdi-content-save-settings"></i> UPDATE</button>
                  <button type="reset" class="btn btn-danger"><i class="mdi mdi-refresh"></i> RESET</button>
                </form>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <!-- Change username card-->
            <div class="card">
              <div class="card-header fs-5 font-bold ls-1">Change Username</div>
              <div class="card-body">
                <form method="post" action="<?php echo base_url(); ?>head-office-accounting/account-settings/username/update" class="mt-2" id="usernameUpdateForm">
                  <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
                  <div class="mb-3">
                    <label class="mb-1">Current Username</label>
                    <input type="text" class="form-control" name="current-username" id="current-username">
                    <em class="text-danger" id="current-username-error"></em>
                  </div>
                  <div class="mb-3">
                    <label class="mb-1">New Username</label>
                    <input type="text" class="form-control" name="new-username" id="new-username">
                    <em class="text-danger" id="new-username-error"></em>
                  </div>
                  <div class="mb-3">
                    <label class="mb-1">Confirm Username</label>
                    <input type="text" class="form-control" name="confirm-username" id="confirm-username">
                    <em class="text-danger" id="confirm-username-error"></em>
                  </div>
                  <button type="submit" class="btn btn-success me-1"><i class="mdi mdi-content-save-settings"></i> UPDATE</button>
                  <button type="reset" class="btn btn-danger"><i class="mdi mdi-refresh"></i> RESET</button>
                </form>
              </div>
            </div>
          </div>

        </div>
      <!-- End Container fluid  -->
      </div>
    <!-- End Page wrapper  -->
    </div>
  <!-- End Wrapper -->
  </div>
<script>
  const baseUrl = '<?= base_url() ?>';
  $(document).ready(function() {
    /* The below code is a jQuery code that is used to submit a form using ajax. */
    $('#passwordUpdateForm').submit(function(event) {
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
            status,
            message,
            current_password_error,
            new_password_error,
            confirm_password_error
          } = response;

          if (status === 'error') {
            // is-invalid class is a built in classname for errors in bootstrap
            if (current_password_error !== '') {
              $('#current-password-error').html(current_password_error);
              $('#current-password').addClass('is-invalid');
            } else {
              $('#current-password-error').html('');
              $('#current-password').removeClass('is-invalid');
              $('#current-password').addClass('is-valid');
            }

            if (new_password_error !== '') {
              $('#new-password-error').html(new_password_error);
              $('#new-password').addClass('is-invalid');
            } else {
              $('#new-password-error').html('');
              $('#new-password').removeClass('is-invalid');
              $('#new-password').addClass('is-valid');
            }

            if (confirm_password_error !== '') {
              $('#confirm-password-error').html(confirm_password_error);
              $('#confirm-password').addClass('is-invalid');
            } else {
              $('#confirm-password-error').html('');
              $('#confirm-password').removeClass('is-invalid');
              $('#confirm-password').addClass('is-valid');
            }

          } else if (status === 'save-error') {
            swal({
              title: 'Failed',
              text: message,
              timer: 3000,
              showConfirmButton: false,
              type: 'error'
            });
          } else if (status === 'success') {
            swal({
              title: 'Success',
              text: message,
              timer: 3000,
              showConfirmButton: false,
              type: 'success'
            });
            clearPasswordValidationErrors();
          }
        },
      });
    });


    /* The below code is a jQuery code that is used to submit a form using ajax. */
    $('#usernameUpdateForm').submit(function(event) {
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
            status,
            message,
            current_username_error,
            new_username_error,
            confirm_username_error
          } = response;

          if (status === 'error') {
            // is-invalid class is a built in classname for errors in bootstrap
            if (current_username_error !== '') {
              $('#current-username-error').html(current_username_error);
              $('#current-username').addClass('is-invalid');
            } else {
              $('#current-username-error').html('');
              $('#current-username').removeClass('is-invalid');
              $('#current-username').addClass('is-valid');
            }

            if (new_username_error !== '') {
              $('#new-username-error').html(new_username_error);
              $('#new-username').addClass('is-invalid');
            } else {
              $('#new-username-error').html('');
              $('#new-username').removeClass('is-invalid');
              $('#new-username').addClass('is-valid');
            }

            if (confirm_username_error !== '') {
              $('#confirm-username-error').html(confirm_username_error);
              $('#confirm-username').addClass('is-invalid');
            } else {
              $('#confirm-username-error').html('');
              $('#confirm-username').removeClass('is-invalid');
              $('#confirm-username').addClass('is-valid');
            }

          } else if (status === 'save-error') {
            swal({
              title: 'Failed',
              text: message,
              timer: 3000,
              showConfirmButton: false,
              type: 'error'
            });
          } else if (status === 'success') {
            swal({
              title: 'Success',
              text: message,
              timer: 3000,
              showConfirmButton: false,
              type: 'success'
            });
            clearUsernameValidationErrors();
          }
        },
      });
    });


    /* Changing the type of the input field from password to text and vice versa. */
    $('.main-password').find('.input-password').each(function(index, input) {
      var $input = $(input);
      $input.parent().find('.icon-view').click(function() {
        var change = "";
        if ($(this).find('i').hasClass('mdi mdi-eye')) {
          $(this).find('i').removeClass('mdi mdi-eye')
          $(this).find('i').addClass('mdi mdi-eye-off')
          change = "text";
        } else {
          $(this).find('i').removeClass('mdi mdi-eye-off')
          $(this).find('i').addClass('mdi mdi-eye')
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


/**
   * It resets the form, removes the invalid and valid classes from the inputs, and removes the error
   * messages.
   */
  function clearPasswordValidationErrors() {
    $('#passwordUpdateForm')[0].reset();
    $('#passwordUpdateForm').find('input').removeClass('is-invalid');
    $('#passwordUpdateForm').find('input').removeClass('is-valid');
    $('#passwordUpdateForm').find('em.text-danger').html('');
  }

/**
 * It clears the form, removes the validation classes, and removes the validation error messages.
 */
  function clearUsernameValidationErrors(){
    $('#usernameUpdateForm')[0].reset();
    $('#usernameUpdateForm').find('input').removeClass('is-invalid');
    $('#usernameUpdateForm').find('input').removeClass('is-valid');
    $('#usernameUpdateForm').find('em.text-danger').html('');
  }

  /**
   * If the type of the current password, new password, and confirm password are all equal to
   * "password", then change the type of the confirm password, new password, and current password to
   * "text". Otherwise, change the type of the confirm password, new password, and current password to
   * "password".
   */
  function showPasswords() {
    const current_password = document.querySelector("#current-password");
    const new_password = document.querySelector("#new-password");
    const confirm_password = document.querySelector("#confirm-password");
    if (current_password.type === "password" && new_password.type === "password" && confirm_password.type === "password") {
      confirm_password.type = new_password.type = current_password.type = "text";
    } else {
      confirm_password.type = new_password.type = current_password.type = "password";
    }
  }
</script>