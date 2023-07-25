<div class="page-wrapper">
  <div class="container-fluid offset-3"><br><br><br>
    <div class="col-lg-6">
      <div class="card shadow">
        <div class="card-header text-white fw-bold fs-5 ls-2" style="background-color:#002244"><i class="mdi mdi-account-key text-warning"></i> MANAGER'S KEY</div>
        <div class="card-body">
          <form id="managersKeyForm" autocomplete="off">
            <input type="hidden" name="token" id="token" value="<?= $this->security->get_csrf_hash(); ?>">
            <input type="hidden" name="expired-loa-id" id="expired-loa-id">
            <input type="hidden" name="expired-loa-no" id="expired-loa-no">

            <div class="text-center">
              <strong id="msg-error" class="text-danger ls-1 mx-1"></strong>
            </div>

            <div class="mb-3">
              <label class="mb-1">Username</label>
              <div class="main-password">
                <input type="text" class="form-control" name="mgr-username" id="mgr-username">
                <a href="JavaScript:void(0);" class="icon-view"><i class="mdi mdi-account"></i></a>
              </div>
              <em id="mgr-username-error" class="text-danger"></em>
            </div>

            <div class="mb-3">
              <label class="mb-1">Password</label>
              <div class="main-password">
                <input type="password" class="form-control input-password" name="mgr-password" id="mgr-password">
                <a href="JavaScript:void(0);" class="icon-view"><i class="mdi mdi-eye"></i></a>
              </div>
              <em id="mgr-password-error" class="text-danger"></em>
            </div>

            <div class="mb-3 offset-3">
              <a href="#" onclick="window.history.back()" class="btn btn-danger"><i class="mdi mdi-arrow-left-bold"></i> BACK</a>
              <button type="submit" class="btn btn-info me-2"><i class="mdi mdi-login"></i> Login</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const baseUrl = "<?php echo base_url(); ?>";
  const fileName = `<?php echo strtotime(date('Y-m-d h:i:s')); ?>`;
  const pathname = window.location.pathname; // Get the pathname
  const segments = pathname.split('/'); // Split the pathname into segments
  const lastSegment = segments[segments.length - 1]; // Get the last segment

  $(document).ready(function() {
    $('#managersKeyForm').submit(function(event){
      event.preventDefault();
      $.ajax({
        type: "post",
        url: `${baseUrl}healthcare-coordinator/emergency_loa/emergency_managers_key`,
        data: $(this).serialize(),
        dataType: "json",
        success: function (res) {
          const { status, message, mgr_username_error, mgr_password_error, company_doctor } = res;
          console.log(res);
          if (status == "error") {
            if (mgr_username_error !== '') {
              $('#mgr-username-error').html(mgr_username_error);
              $('#mgr-username').addClass('is-invalid');
            }else{
              $('#mgr-username-error').html('');
              $('#mgr-username').removeClass('is-invalid');
            }

            if (mgr_password_error !== '') {
              $('#mgr-password-error').html(mgr_password_error);
              $('#mgr-password').addClass('is-invalid');
            }else{
              $('#mgr-password-error').html('');
              $('#mgr-password').removeClass('is-invalid');
            }

            if (message !== '') {
              $('#msg-error').html(message);
              $('#mgr-username').addClass('is-invalid');
              $('#mgr-password').addClass('is-invalid');
            } else {
              $('#msg-error').html('');
              $('#mgr-username').removeClass('is-invalid');
              $('#mgr-password').removeClass('is-invalid');
            }

          }else{
            window.location.href = `${baseUrl}healthcare-coordinator/emergency_loa/emergency_form/${lastSegment}`;
          }
        },
      });
    });
  });
  
</script>