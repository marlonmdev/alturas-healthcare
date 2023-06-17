<!-- Start of Page Wrapper -->
<div class="page-wrapper">
<!-- Bread crumb and right sidebar toggle -->
<div class="page-breadcrumb">
<div class="row">
    <div class="col-12 d-flex no-block align-items-center">
    <h4 class="page-title ls-2"><i class="mdi mdi-format-list-bulleted"></i> LOA Requests</h4>
    <div class="ms-auto text-end">
        <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Head Office Accounting</li>
            <li class="breadcrumb-item active" aria-current="page">
            Billed LOA
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
                    href="<?php echo base_url(); ?>head-office-accounting/loa-request-list/loa-approved"
                    role="tab"
                    ><span class="hidden-sm-up"></span>
                    <span class="hidden-xs-down fs-5 font-bold">Approved</span></a
                    >
                </li>
               
                <li class="nav-item">
                    <a
                    class="nav-link"
                    href="<?php echo base_url(); ?>head-office-accounting/loa-request-list/loa-completed"
                    role="tab"
                    ><span class="hidden-sm-up"></span>
                    <span class="hidden-xs-down fs-5 font-bold">Completed</span></a
                    >
                </li>

                <li class="nav-item">
                    <a
                    class="nav-link active"
                    href="<?php echo base_url(); ?>head-office-accounting/loa-request-list/loa-billed"
                    role="tab"
                    ><span class="hidden-sm-up"></span>
                    <span class="hidden-xs-down fs-5 font-bold">Billed</span></a
                    >
                </li>

                <li class="nav-item">
                    <a
                    class="nav-link"
                    href="<?php echo base_url(); ?>head-office-accounting/loa-request-list/loa-paid"
                    role="tab"
                    ><span class="hidden-sm-up"></span>
                    <span class="hidden-xs-down fs-5 font-bold">Paid</span></a
                    >
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
                        <?php include 'view_billed_loa_details.php'; ?>
                        <table id="approvedLoaTable" class="table table-striped">
                            <thead style="background-color:#00538C">
                                <tr>
                                    <th class="text-white">LOA No.</th>
                                    <th class="text-white">Name</th>
                                    <th class="text-white">LOA Type</th>
                                    <th class="text-white">Service/s</th>
                                    <th class="text-white">RX File</th>
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
<script>
    const baseUrl = `<?php echo base_url(); ?>`;
    $(document).ready(function() {

        let loaTable = $('#approvedLoaTable').DataTable({
            processing: true, //Feature control the processing indicator.
            serverSide: true, //Feature control DataTables' server-side processing mode.
            order: [], //Initial no order.

            // Load data for the table's content from an Ajax source
            ajax: {
                url: `${baseUrl}head-office-accounting/loa-request-list/loa-billed/fetch`,
                type: "POST",
                // passing the token as data so that requests will be allowed
                data: function(data) {
                data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
                data.filter = $('#hospital-filter').val();
                }
            },

            //Set column definition initialisation properties.
            columnDefs: [{
                "targets": [3, 4, 6, 7], // numbering column
                "orderable": false, //set not orderable
            }, ],
            responsive: true,
            fixedHeader: true,
        });

        $('#hospital-filter').change(function(){
            loaTable.draw();
        });

    });

    function viewApprovedLoaInfo(loa_id) {
        $.ajax({
        url: `${baseUrl}head-office-accounting/loa-request-list/loa-approved/view/${loa_id}`,
        type: "GET",
        success: function(response) {
            const res = JSON.parse(response);
            const base_url = window.location.origin;
            const {
            status,
            token,
            loa_no,
            member_mbl,
            remaining_mbl,
            first_name,
            middle_name,
            last_name,
            suffix,
            date_of_birth,
            age,
            gender,
            philhealth_no,
            blood_type,
            contact_no,
            home_address,
            city_address,
            email,
            contact_person,
            contact_person_addr,
            contact_person_no,
            healthcare_provider,
            loa_request_type,
            med_services,
            health_card_no,
            requesting_company,
            request_date,
            chief_complaint,
            requesting_physician,
            attending_physician,
            rx_file,
            req_status,
            work_related,
            percentage,
            approved_by,
            approved_on,
            billed_on
            } = res;

            $("#viewLoaModal").modal("show");
            const med_serv = med_services !== '' ? med_services : 'None';
            const at_physician = attending_physician !== '' ? attending_physician : 'None';
            $('#loa-no').html(loa_no);
            $('#loa-status').html(`<strong class="text-success">[Billed]</strong>`);
            $('#approved-by').html(approved_by);
            $('#approved-on').html(approved_on);
            $('#billed-on').html(billed_on);
            $('#member-mbl').html(member_mbl);
            $('#remaining-mbl').html(remaining_mbl);
            $('#full-name').html(`${first_name} ${middle_name} ${last_name} ${suffix}`);
            $('#date-of-birth').html(date_of_birth);
            $('#age').html(age);
            $('#gender').html(gender);
            $('#philhealth-no').html(philhealth_no);
            $('#blood-type').html(blood_type);
            $('#contact-no').html(contact_no);
            $('#home-address').html(home_address);
            $('#city-address').html(city_address);
            $('#email').html(email);
            $('#contact-person').html(contact_person);
            $('#contact-person-addr').html(contact_person_addr);
            $('#contact-person-no').html(contact_person_no);
            $('#healthcare-provider').html(healthcare_provider);
            $('#loa-request-type').html(loa_request_type);
            $('#loa-med-services').html(med_serv);
            $('#health-card-no').html(health_card_no);
            $('#requesting-company').html(requesting_company);
            $('#request-date').html(request_date);
            $('#chief-complaint').html(chief_complaint);
            $('#requesting-physician').html(requesting_physician);
            $('#attending-physician').html(at_physician);
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

    function viewImage(path) {
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