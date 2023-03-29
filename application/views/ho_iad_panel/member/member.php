<div class="page-wrapper">
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title ls-2">List of Records</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item">Internal Audit Department</li>
							<li class="breadcrumb-item active" aria-current="page">Employee Members</li>
						</ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">  
        <div class="card shadow">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-responsive" id="membersTable">
                <thead>
                  <tr>
										<th class="fw-bold">ID No.</th>
										<th class="fw-bold">Employee Name</th>
										<th class="fw-bold">Employee Type</th>
										<th class="fw-bold">Status</th>
										<th class="fw-bold">Business Unit</th>
										<th class="fw-bold">Department</th>
										<th class="fw-bold">Action</th>
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
  </div>
</div>

<script>
  const baseUrl = `<?php echo base_url(); ?>`;
  $(document).ready(function () {
    $('#membersTable').DataTable({ 
			processing: true,
			serverSide: true,
			order: [],

			ajax: {
				url: `${baseUrl}head-office-iad/transaction/fetch_all_members`,
				type: "POST",
				data: { 'token' : '<?php echo $this->security->get_csrf_hash(); ?>' }
			},

      columnDefs: [{ 
				"targets": [6], // 6th and 7th column / numbering column
				"orderable": false,
      },
      ],
      responsive: true,
      fixedHeader: true,
    });      
  });
</script>