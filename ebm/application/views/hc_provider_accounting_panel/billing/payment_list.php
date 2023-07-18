<!-- Start of Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
        <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Payment List</h4>
            <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item">Healthcare Provider</li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Payment List
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
        <br>
        <div class="card bg-light">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="billedTable">
                    <thead style="background-color:#00538C">
                            <tr>
                                <td class="fw-bold" style="color: white">PAYMENT NUMBER</td>
                                <td class="fw-bold" style="color: white">ACCOUNT NUMBER</td>
                                <td class="fw-bold" style="color: white">ACCOUNT NAME</td>
                                <td class="fw-bold" style="color: white">CHECK NUMBER</td>
                                <td class="fw-bold" style="color: white">CHECK DATE</td>
                                <td class="fw-bold" style="color: white">BANK</td>
                                <td class="fw-bold" style="color: white">PAYMENT DETAILS</td>
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
                        url: `${baseUrl}healthcare-provider/billing-list/payment-history/fetch`,
                        type: "POST",
                        data: function(data) {
                            data.token     = '<?php echo $this->security->get_csrf_hash(); ?>';
                            data.filter    = '<?= $hc_id?>';
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

                // $('#hospital-filter').change(function(){
                //     closedTable.draw();
                // });
                
            });

            const viewPaymentInfo = (details_id,check_image) => {
                $.ajax({
                    type: 'GET',
                    url: `${baseUrl}healthcare-provider/bill/payment-list/view-payment-details/${details_id}`,
                    success: function(response){
                        console.log(response);
                        const res = JSON.parse(response);
                        const base_url = window.location.origin;
                        const {
                            status,
                            token,
                            payment_no,
                            hp_name,
                            added_on,
                            acc_number,
                            acc_name,
                            check_num,
                            check_date,
                            bank,
                            amount_paid,
                            billed_date,
                            covered_loa_no,
                        } = res;
            
                        $('#viewPaymentModal').modal('show');

                        $('#hospital_filtered').val(hp_name);
                        $('#start_date').val(added_on);
                        // $('#end_date').val(end_date);
                        $('#payment-num').val(payment_no);
                        $('#acc-number').val(acc_number);
                        $('#acc-name').val(acc_name);
                        $('#check-number').val(check_num);
                        $('#check-date').val(check_date);
                        $('#bank').val(bank);
                        $('#amount-paid').val(parseFloat(amount_paid).toFixed(2));
                        $('#textbox').val(covered_loa_no);
                        $('#c-billed-date').val(billed_date);
                        $('#supporting-docu').attr('src', check_image);
                    }
                });
            }

        //     function viewImage(path) {
        //     let item = [{
        //         src: path, // path to image
        //         title: 'Attached Check File' // If you skip it, there will display the original image name
        //     }];
        //     // define options (if needed)
        //     let options = {
        //         index: 0 // this option means you will start at first image
        //     };
        //     // Initialize the plugin
        //     let photoviewer = new PhotoViewer(item, options);
        // }

          
    </script>