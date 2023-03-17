<!-- Start of Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
        <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Payment History</h4>
            <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item">Head Office Accounting</li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Payment History
                    </li>
                    </ol>
                </nav>
            </div>
        </div>
        </div>
    </div><hr>
    <!-- End Bread crumb and right sidebar toggle -->
    <div class="container-fluid">
        <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
        <div class="col-lg-5 ps-5">
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
        <br>
        <div class="card bg-light">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="billedTable">
                        <thead>
                            <tr>
                                <td class="fw-bold">Payment Number</td>
                                <td class="fw-bold">Account Number</td>
                                <td class="fw-bold">Account Name</td>
                                <td class="fw-bold">Check Number</td>
                                <td class="fw-bold">Check Date</td>
                                <td class="fw-bold">Bank</td>
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
 <?php include 'view_payment_details.php' ?>
<script>
        const baseUrl = "<?php echo base_url(); ?>";
        $(document).ready(function(){
            let closedTable = $('#billedTable').DataTable({
                processing: true, //Feature control the processing indicator.
                serverSide: true, //Feature control DataTables' server-side processing mode.
                order: [], //Initial no order.

                // Load data for the table's content from an Ajax source
                ajax: {
                    url: `${baseUrl}head-office-accounting/billing-list/payment-history/fetch`,
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

        const viewPaymentInfo = (payment_id) => {
            $.ajax({
                type: 'GET',
                url: `${baseUrl}head-office-accounting/billing-list/view-payment-details/${payment_id}`,
                success: function(response){
                    const res = JSON.parse(response);
                    const base_url = window.location.origin;
                    const {
                        status,
                        token,
                        payment_no,
                        hp_name,
                        start_date,
                        end_date,
                        acc_number,
                        acc_name,
                        check_num,
                        check_date,
                        bank,
                        amount_paid,
                        covered_loa_no
                    } = res;
                    
                    $('#viewPaymentModal').modal('show');

                    $('#hospital_filtered').val(hp_name);
                    $('#start_date').val(start_date);
                    $('#end_date').val(end_date);
                    $('#payment-num').val(payment_no);
                    $('#acc-number').val(acc_number);
                    $('#acc-name').val(acc_name);
                    $('#check-number').val(check_num);
                    $('#check-date').val(check_date);
                    $('#bank').val(bank);
                    $('#amount-paid').val(parseFloat(amount_paid).toFixed(2));
                    $('#textbox').html(covered_loa_no);
                }
            });
        }

        function viewImage(path) {
        let item = [{
            src: path, // path to image
            title: 'Attached Check File' // If you skip it, there will display the original image name
        }];
        // define options (if needed)
        let options = {
            index: 0 // this option means you will start at first image
        };
        // Initialize the plugin
        let photoviewer = new PhotoViewer(item, options);
    }

          
    </script>