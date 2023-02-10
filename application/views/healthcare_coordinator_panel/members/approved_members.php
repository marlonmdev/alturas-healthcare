
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
                <li class="breadcrumb-item">Healthcare Coordinator</li>
                <li class="breadcrumb-item active" aria-current="page">
                  Approved
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
                        href="<?php echo base_url(); ?>healthcare-coordinator/members"
                        role="tab"
                        ><span class="hidden-sm-up"></span>
                        <span class="hidden-xs-down fs-5 font-bold">Pending</span></a
                    >
                    </li>
                    <li class="nav-item">
                    <a
                        class="nav-link active"
                        href="<?php echo base_url(); ?>healthcare-coordinator/members/approved"
                        role="tab"
                        ><span class="hidden-sm-up"></span>
                        <span class="hidden-xs-down fs-5 font-bold">Approved</span></a
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
                                        <th class="fw-bold">Approval Status</th>
                                        <th class="fw-bold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                </div>
            </div>
       <!-- End Row  -->  
      </div>
    <!-- End Container fluid  -->
    </div>
  <!-- End Page wrapper  -->
  </div>
<!-- End Wrapper -->
</div>
<script>
    const baseUrl = '<?php echo base_url(); ?>';
    $(document).ready(function() {

        $('#membersApprovedTable').DataTable({
            processing: true, //Feature control the processing indicator.
            serverSide: true, //Feature control DataTables' server-side processing mode.
            order: [], //Initial no order.

            // Load data for the table's content from an Ajax source
            ajax: {
                url: `${baseUrl}healthcare-coordinator/members/approved/fetch`,
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

    })
</script>