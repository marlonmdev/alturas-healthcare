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
                   Closed
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
                            href="<?php echo base_url(); ?>head-office-accounting/billing-list/billed"
                            role="tab"
                            ><span class="hidden-sm-up"></span>
                            <span class="hidden-xs-down fs-5 font-bold">Billed2</span></a
                        >
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link active"
                            href="<?php echo base_url(); ?>head-office-accounting/billing-list/closed"
                            role="tab"
                            ><span class="hidden-sm-up"></span>
                            <span class="hidden-xs-down fs-5 font-bold">Closed2</span></a
                        >
                    </li>

                    <!-- <div class="dropdown">
                        <li class="nav-item">
                            <button class="btn dropdown-toggle active" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="hidden-sm-up"></span>
                                <span class="hidden-xs-down fs-5 font-bold " style="color:#2359fc">Unbilled</span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item fw-bold" href="<?php echo base_url(); ?>head-office-accounting/billing-list/unbilled/loa">LOA</a></li>
                                <li><a class="dropdown-item fw-bold" href="<?php echo base_url(); ?>head-office-accounting/billing-list/unbilled/noa">NOA</a></li>
                            </ul>
                        </li>
                    </div> -->
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
                            <?php foreach($options as $option) : ?>
                            <option value="<?php echo $option['hp_id']; ?>"><?php echo $option['hp_name']; ?></option>
                            <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="closedTable">
                            <thead>
                                <tr>
                                    <td class="fw-bold">Billing #</td>
                                    <td class="fw-bold">Patient Name</td>
                                    <td class="fw-bold">Request Type</td>
                                    <td class="fw-bold">Billed on</td>
                                    <td class="fw-bold">Company Charge</td>
                                    <td class="fw-bold">Check Date</td>
                                    <td class="fw-bold">Status</td>
                                    <td class="fw-bold">Action</td>
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
    <?php include 'view_employee_payment_details.php' ?>
    <style>
        .dropdown-item:hover {
            background-color: #5f86fa;
        }
    </style>

    <script>
        const baseUrl = "<?php echo base_url(); ?>";
        $(document).ready(function(){
            let closedTable = $('#closedTable').DataTable({
                processing: true, //Feature control the processing indicator.
                serverSide: true, //Feature control DataTables' server-side processing mode.
                order: [], //Initial no order.

                // Load data for the table's content from an Ajax source
                ajax: {
                    url: `${baseUrl}head-office-accounting/billing-list/closed/fetch`,
                    type: "POST",
                    data: function(data) {
                        data.token     = '<?php echo $this->security->get_csrf_hash(); ?>';
                        data.filter    = $('#hospital-filter').val();
                    },
                
                },

                //Set column definition initialisation properties.
                columnDefs: [{
                    "targets": [5], // 5th column / numbering column
                    "orderable": false, //set not orderable
                }, ],
                responsive: true,
                fixedHeader: true,
            });

            $('#hospital-filter').change(function(){
                closedTable.draw();
            });
        });

        const viewEmployeePaymentD = (billing_id) => {
            $.ajax({
                type: 'GET',
                url: `${baseUrl}head-office-accounting/billing-list/view-employee-payment/${billing_id}`,
                success: function(response){
                    const res = JSON.parse(response);
                    const base_url = window.location.origin;
                    const {
                        token,
                        billing_no,
                        hp_name,
                        fullname,
                        request_type,
                        billed_on,
                        company_charge,
                        payment_no,
                        check_date,
                        status
                    } = res;
                    
                    $('#paymentDetails').modal('show');

                    $('#payment-no').html(payment_no);
                    $('#status').html(status);
                    $('#billing-num').html(billing_no);
                    $('#hp-name').html(hp_name);
                    $('#full-name').html(fullname);
                    $('#req-type').html(request_type);
                    $('#billed-on').html(billed_on);
                    $('#company-charge').html(parseFloat(company_charge).toFixed(2));
                    $('#check-date').html(check_date);
                }
            });
        }
    </script>
           