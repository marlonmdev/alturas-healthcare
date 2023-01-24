  <!-- Start of Page Wrapper -->
  <div class="page-wrapper">
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title ls-2">Members</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Super Admin</li>
                <li class="breadcrumb-item active" aria-current="page">
                  Pending
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

                <ul class="nav nav-tabs mb-4" role="tablist">
                    <li class="nav-item">
                    <a
                        class="nav-link active"
                        href="<?php echo base_url(); ?>super-admin/members"
                        role="tab"
                        ><span class="hidden-sm-up"></span>
                        <span class="hidden-xs-down fs-5 font-bold">Pending</span></a
                    >
                    </li>
                    <li class="nav-item">
                    <a
                        class="nav-link"
                        href="<?php echo base_url(); ?>super-admin/members/approved"
                        role="tab"
                        ><span class="hidden-sm-up"></span>
                        <span class="hidden-xs-down fs-5 font-bold">Approved</span></a
                    >
                    </li>
                </ul>
                
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-responsive" id="membersPendingTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>EmpType</th>
                                        <th>Status</th>
                                        <th>Business Unit</th>
                                        <th>Department</th>
                                        <th>Approval Status</th>
                                        <th>Actions</th>
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
                                <h4 class="modal-title ls-2">Create Member User Account</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                </button>
                            </div>
                            <div class="modal-body">

                                <!-- Start of Form -->
                                <form method="post" action="<?php echo base_url(); ?>super-admin/members/user-account/create" id="createMemberUserAccountForm">
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
                                                <a href="JavaScript:void(0);" class="icon-view">
                                                    <i class="mdi mdi-eye" id="pwd-icon"></i>
                                                </a>
                                            </div>
                                            <span id="password-error" class="text-danger"></span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12 mb-sm-0 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary me-2">
                                                <i class="mdi mdi-content-save"></i> REGISTER
                                            </button>
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                                <i class="mdi mdi-close-box"></i> CANCEL
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <!-- End of Form -->
                                <br>
                            </div>
                        </div>
                    </div>
                    <!-- End of Create User Account Modal -->
                </div>
            </div>
   
        <!-- End Row  -->  
      </div>
    <!-- End Container fluid  -->
    </div>


    <!-- Start of Profile Picture Modal  -->
    <div class="modal fade" id="profilePicModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title ls-2">Update Profile Picture</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">

                    <!-- Start of Form -->
                    <form method="post" action="<?php echo base_url(); ?>super-admin/members/pending/update/profile-picture" id="updateProfilePicForm" enctype="multipart/form-data">
                        <input type="hidden" name="token" id="token" value="<?php echo $this->security->get_csrf_hash(); ?>">
                        <input type="hidden" name="app-id" id="app-id">
                        <input type="hidden" name="db-photo" id="db-photo">
                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex justify-content-center">
                                <img id="photo" class="rounded-circle img-responsive" width="150" alt="Member">
                            </div>

                            <h4 class="text-center mt-3" id="member-name">Name</h4>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 mb-2">
                                <label><strong>Select Profile Picture</strong></label>
                                <input type="file" class="form-control" name="profile-pic" id="profile-pic" accept=".jpg, .jpeg, .png">
                                <em id="profile-pic-error" class="text-danger"></em>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-sm-12 mb-sm-0 d-flex justify-content-end">
                                <button type="submit" class="btn btn-success">
                                    <i class="mdi mdi-content-save-settings"></i> UPDATE
                                </button>
                                &nbsp;&nbsp;
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                    <i class="mdi mdi-close-box"></i> CANCEL
                                </button>
                            </div>
                        </div>
                    </form>
                    <!-- End of Form -->
                    <br>
                </div>
            </div>
        </div>
        <!-- End of Profile Picture Modal -->
<!-- End Page wrapper  -->
</div>
<script>
    const baseUrl = `<?php echo base_url(); ?>`;
    $(document).ready(function() {

        $('#membersPendingTable').DataTable({
            processing: true, //Feature control the processing indicator.
            serverSide: true, //Feature control DataTables' server-side processing mode.
            order: [], //Initial no order.

            // Load data for the table's content from an Ajax source
            ajax: {
                url: `${baseUrl}super-admin/members/pending/fetch`,
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
            const nextPage = `${baseUrl}super-admin/members/approved`;
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

        $('#updateProfilePicForm').submit(function(event) {
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
                        profile_pic_error,
                    } = response;
                    switch (status) {
                        case 'error':
                            // is-invalid class is a built in classname for errors in bootstrap
                            if (profile_pic_error !== '') {
                                $('#profile-pic-error').html(profile_pic_error);
                                $('#profile-pic').addClass('is-invalid');
                            } else {
                                $('#profile-pic-error').html('');
                                $('#profile-pic').removeClass('is-invalid');
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
                            $('#profilePicModal').modal('hide');
                            $("#membersPendingTable").DataTable().ajax.reload();
                            break;
                    }
                },
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
    // End of Document Ready function

    // Admin Members Show Create User Account Modal
    function showCreateUserAccount(emp_no, emp_year) {
        const emp_id_input = document.querySelector('#emp-id');
        const healthcard_no = document.querySelector('#healthcard-no');
        const username = document.querySelector('#username');
        const password = document.querySelector('#password');
        const current_year = new Date().getFullYear();
        $('#createMemberUserAccountForm')[0].reset();
        $('#createMemberUserAccountModal').modal('show');
        const emp_id = emp_no.toString() + '-' + emp_year.toString();
        healthcard_no.value = 'ACN' + '-' + current_year + '-' + emp_no.toString();
        username.value = emp_id;
        password.value = generateRandomString(4) + current_year;
        emp_id_input.value = emp_id;
    }

    function showUpdateProfilePhoto(app_id, full_name, photo) {
        const baseUrl = '<?php echo base_url(); ?>';
        $('#updateProfilePicForm')[0].reset();

        $('#profile-pic').removeClass('is-invalid');
        $('#profile-pic-error').html('');
        if (photo == '') {
            $('#photo').attr('src', `${baseUrl}assets/images/user.svg`);
        } else {
            $('#photo').attr('src', `${baseUrl}uploads/profile_pics/${photo}`);
        }
        $('#profilePicModal').modal('show');
        $('#app-id').val(app_id);
        $('#db-photo').val(photo);
        $('#member-name').html(full_name);
    }
</script>