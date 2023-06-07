<!-- Start of Page Wrapper -->
<div class="page-wrapper">
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title ls-2"><i class="mdi mdi-format-list-bulleted"></i> NOA Requests</h4>
            <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Head Office Accounting</li>
                    <li class="breadcrumb-item active" aria-current="page">
                    Paid NOA
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
                    href="<?php echo base_url(); ?>head-office-accounting/noa-request-list/noa-approved"
                    role="tab">
                        <span class="hidden-sm-up"></span>
                        <span class="hidden-xs-down fs-5 font-bold">Approved</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a
                    class="nav-link"
                    href="<?php echo base_url(); ?>head-office-accounting/noa-request-list/noa-billed"
                    role="tab">
                        <span class="hidden-sm-up"></span>
                        <span class="hidden-xs-down fs-5 font-bold">Billed</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a
                    class="nav-link active"
                    href="<?php echo base_url(); ?>head-office-accounting/noa-request-list/noa-paid"
                    role="tab">
                        <span class="hidden-sm-up"></span>
                        <span class="hidden-xs-down fs-5 font-bold">Paid</span>
                    </a>
                </li>
            </ul>
            <div class="col-lg-5 ps-5 pb-3">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-dark text-white">
                        <i class="mdi mdi-filter"></i>
                        </span>
                    </div>
                    <select class="form-select fw-bold" name="hospital-filter" id="hospital-filter">
                            <option value="">Select Hospital</option>
                            <?php foreach($hc_provider as $option) : ?>
                            <option value="<?php echo $option['hp_id']; ?>"><?php echo $option['hp_name']; ?></option>
                            <?php endforeach; ?>
                    </select>
                </div>
            </div>

             <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">  
                        <?php include 'view_paid_noa_details.php'; ?>
                        <table id="approvedNoaTable" class="table table-striped" style="width:100%">
                            <thead style="background-color:#00538C">
                                <tr>
                                    <th class="text-white">NOA No.</th>
                                    <th class="text-white">Name</th>
                                    <th class="text-white">Hosptial Name</th>
                                    <th class="text-white">Admission Date</th>
                                    <th class="text-white">Request Date</th>
                                    <th class="text-white">Status</th>
                                    <th class="text-white">Actions</th>
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
    const baseUrl = "<?php echo base_url(); ?>";
    $(document).ready(function() {

        let noaTable =  $('#approvedNoaTable').DataTable({
        processing: true, //Feature control the processing indicator.
        serverSide: true, //Feature control DataTables' server-side processing mode.
        order: [], //Initial no order.

        // Load data for the table's content from an Ajax source
        ajax: {
            url: `${baseUrl}head-office-accounting/noa-request-list/noa-paid/fetch`,
            type: "POST",
            // passing the token as data so that requests will be allowed
            data: function(data) {
                data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
                data.filter = $('#hospital-filter').val();
            }
        },

        //Set column definition initialisation properties.
        columnDefs: [{
            "targets": [5, 6], // numbering column
            "orderable": false, //set not orderable
        }, ],
        responsive: true,
        fixedHeader: true,
        });

        $('#hospital-filter').change(function(){
            noaTable.draw();
        });

    });

    function viewApprovedNoaInfo(noa_id) {
    $.ajax({
      url: `${baseUrl}head-office-accounting/noa-request-list/noa-approved/view/${noa_id}`,
      type: "GET",
      success: function(response) {
        const res = JSON.parse(response);
        const base_url = window.location.origin;
        const {
          status,
          token,
          noa_no,
          approved_by,
          approved_on,
          member_mbl,
          remaining_mbl,
          first_name,
          middle_name,
          last_name,
          suffix,
          date_of_birth,
          age,
          hospital_name,
          health_card_no,
          requesting_company,
          admission_date,
          chief_complaint,
          work_related,
          percentage,
          request_date,
          req_status,
          billed_on,
          paid_on
        } = res;

        $("#viewNoaModal").modal("show");
        $('#noa-status').html(`<strong class="text-success">[${req_status}]</strong>`);
        $('#noa-no').html(noa_no);
        $('#approved-by').html(approved_by);
        $('#approved-on').html(approved_on);
        $('#billed-on').html(billed_on);
        $('#paid-on').html(paid_on);
        $('#member-mbl').html(member_mbl);
        $('#remaining-mbl').html(remaining_mbl);
        $('#full-name').html(`${first_name} ${middle_name} ${last_name} ${suffix}`);
        $('#date-of-birth').html(date_of_birth);
        $('#age').html(age);
        $('#hospital-name').html(hospital_name);
        $('#admission-date').html(admission_date);
        $('#chief-complaint').html(chief_complaint);
        $('#request-date').html(request_date);
        if(work_related == 'Yes'){ 
					if(percentage == ''){
					  wpercent = '100% W-R';
					  nwpercent = '';
					}else{
					   wpercent = percentage+'%  W-R';
					   result = 100 - parseFloat(percentage);
					   if(percentage == '100'){
						   nwpercent = '';
					   }else{
						   nwpercent = result+'% Non W-R';
					   }
					  
					}	
			   }else if(work_related == 'No'){
				   if(percentage == ''){
					   wpercent = '';
					   nwpercent = '100% Non W-R';
					}else{
					   nwpercent = percentage+'% Non W-R';
					   result = 100 - parseFloat(percentage);
					   if(percentage == '100'){
						   wpercent = '';
					   }else{
						   wpercent = result+'%  W-R';
					   }
					 
					}
			   }
        $('#percentage').html(wpercent+', '+nwpercent);
      }
    });
  }
</script>
