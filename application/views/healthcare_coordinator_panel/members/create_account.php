<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">AGC EMPLOYEE</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Healthcare Coordinator</li>
              <li class="breadcrumb-item active" aria-current="page">Create Account</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <ul class="nav nav-tabs mb-4" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" href="<?php echo base_url(); ?>healthcare-coordinator/members" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">CREATE ACCOUNT</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-coordinator/members/approved" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">UPLOAD HEALTHCARD ID</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>healthcare-coordinator/members/approved/uploaded-scanned-id-form" role="tab">
              <span class="hidden-sm-up"></span>
              <span class="hidden-xs-down fs-5 font-bold">HEALTHCARD ID</span>
            </a>
          </li>
        </ul>

        <div class="card shadow">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-responsive" id="membersPendingTable">
                <thead style="background-color:#00538C">
                  <tr>
                    <th class="fw-bold" style="color: white;">#</th>
                    <th class="fw-bold" style="color: white;">NAME OF EMPLOYEE</th>
                    <th class="fw-bold" style="color: white;">TYPE OF EMPLOYEE</th>
                    <th class="fw-bold" style="color: white;">STATUS</th>
                    <th class="fw-bold" style="color: white;">BUSINESS UNIT</th>
                    <th class="fw-bold" style="color: white;">DEPARTMENT</th>
                    <th class="fw-bold" style="color: white;">STATUS</th>
                    <th class="fw-bold" style="color: white;">ACTION</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Start of Create User Account Modal  -->
        <div class="modal fade" id="createMemberUserAccountModal" tabindex="-1" data-bs-backdrop="static">
          <div class="modal-dialog modal-md">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title text-secondary">CREATE USER ACCOUNT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              <div class="modal-body">
                <form method="post" action="<?php echo base_url(); ?>healthcare-coordinator/members/user-account/create" id="createMemberUserAccountForm">
                  <input type="hidden" name="token" id="token" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="hidden" name="emp-id" id="emp-id">
                  <div class="form-group row">
                    <div class="col-sm-12 mb-2">
                      <label>Health Card Number</label>
                      <input type="text" class="form-control has-data" name="healthcard-no" id="healthcard-no" readonly>
                      <span id="healthcard-no-error" class="text-danger"></span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-sm-12 mb-2">
                      <label>Username</label>
                      <input type="text" class="form-control has-data" name="username" id="username" readonly>
                      <span id="username-error" class="text-danger"></span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-sm-12 mb-3">
                      <label>Password</label>
                      <div class="main-password">
                        <input type="password" class="form-control input-password has-data" name="password" id="password" aria-label="password" readonly>
                        <a href="JavaScript:void(0);" class="icon-view"><i class="mdi mdi-eye" id="pwd-icon"></i></a>
                      </div>
                      <span id="password-error" class="text-danger"></span>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-12 mb-sm-0 d-flex justify-content-end">
                      <button type="submit" class="btn btn-primary">REGISTER</button>&nbsp;&nbsp;
                      <button type="button" class="btn btn-danger" data-bs-dismiss="modal">CANCEL</button>
                    </div>
                  </div>

                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>





<script>
    const baseUrl = '<?php echo base_url(); ?>';
    $(document).ready(function() {

        $('#membersPendingTable').DataTable({
            processing: true, //Feature control the processing indicator.
            serverSide: true, //Feature control DataTables' server-side processing mode.
            order: [], //Initial no order.

            // Load data for the table's content from an Ajax source
            ajax: {
                url: `${baseUrl}healthcare-coordinator/members/pending/fetch`,
                type: "POST",
                // passing the token as data so that requests will be allowed
                data: {
                    'token': '<?php echo $this->security->get_csrf_hash(); ?>'
                }
            },

            //Set column definition initialisation properties.
            columnDefs: [{
                "targets": [6, 7], // 6th and 7th column / numbering column
                "orderable": false, //set not orderable
            }, ],
            responsive: true,
            fixedHeader: true,
        });

        // Start of Admin Create User Accounts Submit
        $('#createMemberUserAccountForm').submit(function(event) {
            event.preventDefault();
            const nextPage = `${baseUrl}healthcare-coordinator/members/approved`;
            $.ajax({
                type: "post",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json",
                success: function(response) {
                    const {
                        token,
                        status,
                        message
                    } = response;

                    if (status === 'save-error') {
                        swal({
                            title: 'Failed',
                            text: message,
                            timer: 3000,
                            showConfirmButton: false,
                            type: 'error'
                        });
                    } else if (status === 'success') {
                        $('#password-error').html('');
                        $('#password').removeClass('is-invalid');
                        $('#createMemberUserAccountModal').modal('hide');
                        swal({
                            title: 'Success',
                            text: message,
                            timer: 3000,
                            showConfirmButton: false,
                            type: 'success'
                        });
                        setTimeout(function() {
                            window.location.href = nextPage;
                        }, 3200);
                    }
                }
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

    });
    // End of Document Ready function

    // Admin Members Show Create User Account Modal
    function showCreateUserAccount(emp_id, emp_no) {
        const emp_id_input = document.querySelector('#emp-id');
        const healthcard_no = document.querySelector('#healthcard-no');
        const username = document.querySelector('#username');
        const password = document.querySelector('#password');
        // const current_year = new Date().getFullYear();

        $('#createMemberUserAccountForm')[0].reset();
        $('#createMemberUserAccountModal').modal('show');

        healthcard_no.value = `ACN-${emp_no.toString()}`;
        username.value = emp_id.toString();
        password.value = "Acare2022";
        emp_id_input.value = emp_id;
    }
</script>