<!-- Start of Page Wrapper -->
<div class="page-wrapper">
	<!-- Bread crumb and right sidebar toggle -->
	<div class="page-breadcrumb">
		<div class="row">
    	<div class="col-12 d-flex no-block align-items-center">
    		<h4 class="page-title ls-2">Employee Record</h4>
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
	<!-- End Bread crumb and right sidebar toggle -->
	<!-- Start of Container fluid  -->
	<div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
				<div class="card shadow">
					<div class="card-body">
						<div class="table-responsive">
							<!-- < include 'view_approved_loa_details.php'; ?> -->
							<table id="billedLoaTable" class="table table-striped">
								<thead>
									<tr>
										<th class="fw-bold">NAME</th>
										<th class="fw-bold">BILLING #</th>
										<th class="fw-bold">TRANSACTION DATE</th>
										<th class="fw-bold">REQUEST TYPE</th>
										<th class="fw-bold">ACTION</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										if(!empty($list)):
											foreach($lists as $list):	
									?>
										<tr>
											<td>
												<?= $list['first_name'].' '.$list['middle_name'].' '.$list['last_name'] ?>
											</td>
											<td>
												<?= $list['billing_no'] ?>
											</td>
											<td>
												<?= $list['billed_on'] ?>
											</td>
											<td>
												<?= $list['loa_id'] != '' ? 'LOA' : 'NOA' ?>
											</td>
											<td>
												<a class="text-info fw-bold ls-1" href="<?= base_url() . 'head-office-iad/record/receipt/'. $list['billing_no'] ?>" data-bs-toggle="tooltip"><u>View Receipt</u></a>
											</td>
										</tr>
									<?php 
											endforeach;
										else:
									?>
										<tr>
											<td class="text-center" colspan="5">
												<p class="fs-4 fw-bold ls-1">No Data Found...</p>
											</td>
										</tr>
									<?php 
										endif;
									?>
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
    // $(document).ready(function() {
		// 	$('#billedLoaTable').DataTable({
		// 		processing: true, //Feature control the processing indicator.
		// 		serverSide: true, //Feature control DataTables' server-side processing mode.
		// 		order: [], //Initial no order.

		// 		// Load data for the table's content from an Ajax source
		// 		ajax: {
		// 			url: `${baseUrl}head-office-iad/record/`,
		// 			type: "POST",
		// 			// passing the token as data so that requests will be allowed
		// 			data:{
		// 				'token': '<echo $this->security->get_csrf_hash(); ?>'
		// 			}
		// 		},

		// 		//Set column definition initialisation properties.
		// 		columnDefs: [{
		// 			"targets": [4], // numbering column
		// 			"orderable": false, //set not orderable
		// 		}, ],
		// 		responsive: true,
		// 		fixedHeader: true,
		// 	});
    // });

    const viewImage = (path) => {
      let item = [{
        src: path, // path to image
        title: 'Attached RX File' // If you skip it, there will display the original image name
      }];
      // define options (if needed)
      let options = {
      	index: 0 // this option means you will start at first image
      };
      // Initialize the plugin
      let photoviewer = new PhotoViewer(item, options);
    }
</script>