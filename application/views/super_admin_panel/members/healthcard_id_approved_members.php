  <!-- Start of Page Wrapper -->
  <div class="page-wrapper">
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title ls-2"><i class="mdi mdi-account-multiple"></i> Members</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Super Admin</li>
                <li class="breadcrumb-item active" aria-current="page">
                  Healthcard IDs
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
                        class="nav-link"
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
                        <span class="hidden-xs-down fs-5 font-bold">Approved Members</span></a
                    >
                    </li>
                    <li class="nav-item">
                    <a
                        class="nav-link active"
                        href="<?php echo base_url(); ?>super-admin/members/approved/healthcard-id"
                        role="tab"
                        ><span class="hidden-sm-up"></span>
                        <span class="hidden-xs-down fs-5 font-bold">Healthcard IDs</span></a
                    >
                    </li>
                </ul>
                
                <div class="card shadow">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-responsive" id="membersApprovedTable">
                                <thead>
                                    <tr>
                                        <th class="fw-bold">#</th>
                                        <th class="fw-bold">Name</th>
                                        <th class="fw-bold">EmpType</th>
                                        <th class="fw-bold">Status</th>
                                        <th class="fw-bold">Business Unit</th>
                                        <th class="fw-bold">Department</th>
                                        <th class="fw-bold">ID Status</th>
                                        <th class="fw-bold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
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
                                <form method="post" action="<?php echo base_url(); ?>super-admin/members/update/profile-picture" id="updateProfilePicForm" enctype="multipart/form-data">
                                    <input type="hidden" name="token" id="token" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                    <input type="hidden" name="member-id" id="member-id">
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
                </div>
            </div>
            <?php include 'view_healthcard_id.php'; ?>
      
        <!-- End Row  -->  
      </div>
    <!-- End Container fluid  -->
    </div>
    <!-- End Page wrapper  -->
</div>
<script>
    const baseUrl = `<?php echo base_url(); ?>`;
    $(document).ready(function() {

        $('#membersApprovedTable').DataTable({
            processing: true, //Feature control the processing indicator.
            serverSide: true, //Feature control DataTables' server-side processing mode.
            order: [], //Initial no order.

            // Load data for the table's content from an Ajax source
            ajax: {
                url: `${baseUrl}super-admin/members/approved/healthcard-id/fetch`,
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

    });

    const viewMemberID = (emp_id) => {
        $.ajax({
            type: "GET",
            url: `${baseUrl}super-admin/members/helthcard/view-id/${emp_id}`,
            dataType: 'json',
            data: {
                    'token': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
            success: function(response){
                const { token,front_id, back_id }= response;

                $('#viewMemberHcIdModal').modal('show');
                $('#front-id').attr('src', front_id);
                $('#back-id').attr('src', back_id);
            }  
        });
    }
    
</script>