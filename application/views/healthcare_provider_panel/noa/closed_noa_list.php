<!-- Start of Page Wrapper -->
<div class="page-wrapper">
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title ls-2">NOA Requests</h4>
            <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Healthcare Provider</li>
                    <li class="breadcrumb-item active" aria-current="page">
                    Closed NOA
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
                    href="<?php echo base_url(); ?>healthcare-provider/noa-requests/pending"
                    role="tab"
                    ><span class="hidden-sm-up"></span>
                    <span class="hidden-xs-down fs-5 font-bold">Pending</span></a
                    >
                </li>
                <li class="nav-item">
                    <a
                    class="nav-link"
                    href="<?php echo base_url(); ?>healthcare-provider/noa-requests/approved"
                    role="tab"
                    ><span class="hidden-sm-up"></span>
                    <span class="hidden-xs-down fs-5 font-bold">Approved</span></a
                    >
                </li>
                <li class="nav-item">
                    <a
                    class="nav-link"
                    href="<?php echo base_url(); ?>healthcare-provider/noa-requests/disapproved"
                    role="tab"
                    ><span class="hidden-sm-up"></span>
                    <span class="hidden-xs-down fs-5 font-bold">Disapproved</span></a
                    >
                </li>
                    <li class="nav-item">
                    <a
                    class="nav-link active"
                    href="<?php echo base_url(); ?>healthcare-provider/noa-requests/closed"
                    role="tab"
                    ><span class="hidden-sm-up"></span>
                    <span class="hidden-xs-down fs-5 font-bold">Closed</span></a
                    >
                </li>
            </ul>

             <div class="card">
                <div class="card-body">
                    <div class="table-responsive">  
                        <?php include 'view_closed_noa_details.php'; ?>
                        <table id="closedNoaTable" class="table table-striped" style="width:100%">
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
<script>
    const baseUrl = "<?php echo base_url(); ?>";
    $(document).ready(function() {

        $('#closedNoaTable').DataTable({
        processing: true, //Feature control the processing indicator.
        serverSide: true, //Feature control DataTables' server-side processing mode.
        order: [], //Initial no order.

        // Load data for the table's content from an Ajax source
        ajax: {
            url: `${baseUrl}healthcare-provider/noa-requests/closed/fetch`,
            type: "POST",
            // passing the token as data so that requests will be allowed
            data: {
            'token': '<?php echo $this->security->get_csrf_hash(); ?>'
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

    });

    function viewNoaInfo(req_id) {
        $.ajax({
        url: `${baseUrl}healthcare-provider/noa-requests/view/${req_id}`,
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

            switch (req_status) {
                case 'Pending':
                    $('#noa-status').html('<strong class="text-warning">[' + req_status + ']</strong>');
                    break;
                case 'Approved':
                    $('#noa-status').html('<strong class="text-success">[' + req_status + ']</strong>');
                    break;
                case 'Disapproved':
                    $('#noa-status').html('<strong class="text-danger">[' + req_status + ']</strong>');
                    break;
                case 'Closed':
                    $('#noa-status').html('<strong class="text-info">[' + req_status + ']</strong>');
                    break;
                }
                $('#noa-no').html(noa_no);
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
