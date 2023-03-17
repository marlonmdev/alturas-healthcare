<div class="page-wrapper">
	<div class="page-breadcrumb">
		<div class="row">
    	<div class="col-12 d-flex no-block align-items-center">
    		<h4 class="page-title ls-2">List of Record</h4>
    		<div class="ms-auto text-end">
        	<nav aria-label="breadcrumb">
        		<ol class="breadcrumb">
            	<li class="breadcrumb-item">Internal Audit Department</li>
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
						<table id="table" class="table table-striped">
							<thead>
								<tr>
									<th class="fw-bold">EMPLOYEE ID</th>
									<th class="fw-bold">EMPLOYEE NAME</th>
									<th class="fw-bold">REQUEST TYPE</th>
									<th class="fw-bold">TRANSACTION DATE</th>
									<th class="fw-bold">ACTION</th>
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
    $(document).ready(function() {
			$('#table').DataTable({
				processing: true,
				serverSide: true,
				order: [],
				ajax: {
					url: `${baseUrl}head-office-iad/table/billed/fetch`,
					type: "POST",
					data:{
						'token': '<?php echo $this->security->get_csrf_hash(); ?>'
					}
				},
				columnDefs: [{
					"targets": [4], 
					"orderable": false,
				}, ],
				responsive: true,
				fixedHeader: true,
			});
    });
</script>