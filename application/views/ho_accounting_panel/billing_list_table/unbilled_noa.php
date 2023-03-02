<!-- Start of Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
        <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Billing List</h4>
            <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item">Head Office Accounting</li>
                <li class="breadcrumb-item active" aria-current="page">
                   Unbilled NOA
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
    <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
        <div class="row">
            <div class="col-lg-12">
                <ul class="nav nav-tabs mb-4" role="tablist"> 
                   
                    <li class="nav-item">
                        <a
                            class="nav-link "
                            href="<?php echo base_url(); ?>head-office-accounting/billing-list/billed"
                            role="tab"
                            ><span class="hidden-sm-up"></span>
                            <span class="hidden-xs-down fs-5 font-bold">Billed</span></a
                        >
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="<?php echo base_url(); ?>head-office-accounting/billing-list/closed"
                            role="tab"
                            ><span class="hidden-sm-up"></span>
                            <span class="hidden-xs-down fs-5 font-bold">Closed</span></a
                        >
                    </li>
                    
                    <div class="dropdown">
                        <li class="nav-item">
                            <button class="btn dropdown-toggle active" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="hidden-sm-up"></span>
                                <span class="hidden-xs-down fs-5 font-bold" style="color:#2359fc">Unbilled</span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item fw-bold" href="<?php echo base_url(); ?>head-office-accounting/billing-list/unbilled/loa">LOA</a></li>
                                <li><a class="dropdown-item fw-bold" href="<?php echo base_url(); ?>head-office-accounting/billing-list/unbilled/noa">NOA</a></li>
                            </ul>
                        </li>
                    </div>
                </ul>
            </div>
            <div class="col-lg-5 ps-5 pb-4">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-secondary text-white">
                        <i class="mdi mdi-filter"></i>
                        </span>
                    </div>
                    <select class="form-select fw-bold" name="hospital-filter" id="hospital-filter" oninput="enableDate()">
                            <option value="">Select Hospital</option>
                            <?php foreach($hc_provider as $option) : ?>
                            <option value="<?php echo $option['hp_id']; ?>"><?php echo $option['hp_name']; ?></option>
                            <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <?php include 'view_completed_noa_details.php'; ?>
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="unbilledNoaTable">
                            <thead>
                                <tr>
                                    <th class="fw-bold">NOA No.</th>
                                    <th class="fw-bold">Name</th>
                                    <th class="fw-bold">Hosptial Name</th>
                                    <th class="fw-bold">Admission Date</th>
                                    <th class="fw-bold">Request Date</th>
                                    <th class="fw-bold">Status</th>
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
    <style>
        .dropdown-item:hover {
            background-color: #5f86fa;
        }
    </style>

<script>
    const baseUrl = "<?php echo base_url(); ?>";
    $(document).ready(function() {

        let noaTable = $('#unbilledNoaTable').DataTable({
        processing: true, //Feature control the processing indicator.
        serverSide: true, //Feature control DataTables' server-side processing mode.
        order: [], //Initial no order.

        // Load data for the table's content from an Ajax source
        ajax: {
            url: `${baseUrl}head-office-accounting/billing-list/unbilled_noa/fetch`,
            type: "POST",
            // passing the token as data so that requests will be allowed
            data: function(data){
                data.token = '<?php echo $this->security->get_csrf_hash(); ?>',
                data.filter = $('#hospital-filter').val()
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

    function viewNoaInfo(noa_id) {
        $.ajax({
            url: `${baseUrl}head-office-accounting/billing-list/unbilled_noa/view/${noa_id}`,
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
                request_date,
                req_status,
                } = res;

                $("#viewNoaModal").modal("show");

                $('#noa-no').html(noa_no);
                $('#noa-status').html('<strong class="text-info">[' + req_status + ']</strong>');
                $('#approved-by').html(approved_by);
                $('#approved-on').html(approved_on);
                $('#member-mbl').html(member_mbl);
                $('#remaining-mbl').html(remaining_mbl);
                $('#full-name').html(`${first_name} ${middle_name} ${last_name} ${suffix}`);
                $('#date-of-birth').html(date_of_birth);
                $('#age').html(age);
                $('#hospital-name').html(hospital_name);
                $('#admission-date').html(admission_date);
                $('#chief-complaint').html(chief_complaint);
                $('#work-related').html(work_related);
                $('#request-date').html(request_date);
            }
        });
    }
</script>    