
      <!-- Page wrapper  -->
      <div class="page-wrapper">
        <!-- Bread crumb and right sidebar toggle -->
        <div class="page-breadcrumb">
          <div class="row">
            <div class="col-12 d-flex no-block flex-column flex-sm-row align-items-left">
              <h4 class="page-title ls-2">CHANGE ACCOUNT</h4>
              <div class="ms-auto text-end order-first order-sm-last">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">Member</li>
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
            <div class="col-lg-12">
              <!-- Account page navigation-->
              <ul class="nav nav-tabs mb-4" role="tablist">
                <li class="nav-item">
                  <a
                    class="nav-link"
                    data-toggle="tab"
                    href="<?php echo base_url(); ?>member/profile"
                    role="tab"
                    ><span class="hidden-sm-up"></span>
                    <span class="hidden-xs-down fs-5 font-bold">My Profile</span></a
                  >
                </li>
                <li class="nav-item">
                  <a
                    class="nav-link active"
                    data-toggle="tab"
                    href="<?php echo base_url(); ?>member/account-settings"
                    role="tab"
                    ><span class="hidden-sm-up"></span>
                    <span class="hidden-xs-down fs-5 font-bold">Account Settings</span></a
                  >
                </li>
              </ul>

              <div class="row">
                <div class="col-lg-6">
                  <!-- Change password card-->
                  <div class="card shadow">
                    <div class="card-header bg-secondary text-white ls-2 fs-5"><strong>Change Password</strong></div>
                    <div class="card-body">
                      <form method="post" action="<?= base_url() ?>member/account-settings/password/update" class="mt-2" id="passwordUpdateForm">
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
                  <div class="card shadow">
                    <div class="card-header bg-secondary text-white ls-2 fs-5"><strong>Change Username</strong></div>
                    <div class="card-body">
                      <form method="post" action="<?= base_url() ?>member/account-settings/username/update" class="mt-2" id="usernameUpdateForm">
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
            </div>
          </div>
        <!-- End Container fluid  -->
        </div>
      <!-- End Page wrapper  -->
      </div>
    <!-- End Wrapper -->
    </div>
  <script>
    const baseUrl = `<?php echo base_url(); ?>`;

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
  const clearPasswordValidationErrors = () => {
    $('#passwordUpdateForm')[0].reset();
    $('#passwordUpdateForm').find('input').removeClass('is-invalid');
    $('#passwordUpdateForm').find('input').removeClass('is-valid');
    $('#passwordUpdateForm').find('em.text-danger').html('');
  }

  /**
 * It clears the form, removes the validation classes, and removes the validation error messages.
 */
  const clearUsernameValidationErrors = () => {
    $('#usernameUpdateForm')[0].reset();
    $('#usernameUpdateForm').find('input').removeClass('is-invalid');
    $('#usernameUpdateForm').find('input').removeClass('is-valid');
    $('#usernameUpdateForm').find('em.text-danger').html('');
  }

  </script>


