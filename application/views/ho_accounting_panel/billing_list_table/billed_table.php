
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
                    Billed
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
                            href="<?php echo base_url(); ?>head-office-accounting/billing-list"
                            role="tab"
                            ><span class="hidden-sm-up"></span>
                            <span class="hidden-xs-down fs-5 font-bold">Unbilled</span></a
                        >
                    </li>
                    <li class="nav-item">
                        <a
                            class="nav-link active"
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
                </ul>
            </div>
            <div class="row mb-3 pt-2">
                <div class="col-lg-5">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-dark text-white">
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
                
                <div class="col-lg-6 offset-1">
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class="input-group-text bg-dark text-white ls-1 ms-2">From : </span>
                            </div>
                            <input type="date" class="form-control" name="start_date" id="start-date" oninput="validateDateRange()" placeholder="mm/dd/yyyy" disabled>

                            <div class="input-group-append">
                                <span class="input-group-text bg-dark text-white ls-1 ms-2">To : </span>
                            </div>
                            <input type="date" class="form-control" name="end_date" id="end-date" oninput="validateDateRange()" placeholder="mm/dd/yyyy" disabled>
                        
                        </div>
                        
                    </div>
                </div>
            </div><br>
           
                <div class="card shadow">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="billedTable">
                                <thead>
                                    <tr>
                                        <td class="fw-bold">Billing #</td>
                                        <td class="fw-bold">Patient Name</td>
                                        <td class="fw-bold">Request Type</td>
                                        <td class="fw-bold">Billed on</td>
                                        <td class="fw-bold">Charge</td>
                                        <td class="fw-bold">Action</td>
                                        <td class="fw-bold">Total Charge</td>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <?php include 'payment_details_modal.php'?>
                <div class="row col-lg-7 offset-5 pt-3 pb-3">
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text bg-dark text-white ls-1 fs-5">TOTAL PAYABLE: </span>
                        </div>
                        <input type="text" class="form-control fs-5 text-danger" id="total_charge" readonly>
                        
                        <div class="input-group-append">
                            <a href="javascript:void(0)" onclick="add_payment()" class="input-group-text bg-cyan text-white ms-2 px-3 fs-5 ls-1" id="add-payment-btn">Add Payment Details </a>
                        </div>
                    </div>
                </div>
                <hr class="pt-2">
            </div>
        </div> 
    </div>

    <script>
        const baseUrl = "<?php echo base_url(); ?>";
        $(document).ready(function(){

            let billingTable = $('#billedTable').DataTable({
                processing: true, //Feature control the processing indicator.
                serverSide: true, //Feature control DataTables' server-side processing mode.
                order: [], //Initial no order.

                // Load data for the table's content from an Ajax source
                ajax: {
                    url: `${baseUrl}head-office-accounting/billing-list/billed/fetch`,
                    type: "POST",
                    data: function(data) {
                        data.token     = '<?php echo $this->security->get_csrf_hash(); ?>';
                        data.filter    = $('#hospital-filter').val();
                        data.startDate = $('#start-date').val();
                        data.endDate   = $('#end-date').val();
                    },
                },



                // footerCallback: function (row, data, start, end, display) {
                //     var api = this.api();

                //     // Calculate the sum of values in column 4
                //     var column5Total = api.column(4).data().reduce(function (acc, val) {
                //         return acc + parseFloat(val);
                //     }, 0);

                //     // Update the footer value for column 4
                //     $('#total_charge').html(data.total);
                // },
               
                
                //Set column definition initialisation properties.
                columnDefs: [{
                    "targets": [5], // 5th column / numbering column
                    "orderable": false, //set not orderable
                }, ],
                responsive: true,
                fixedHeader: true,
            });

            $('#hospital-filter').change(function(){
                billingTable.draw();
            });

            $('#start-date').change(function(){
                billingTable.draw();
            });

            $('#end-date').change(function(){
                billingTable.draw();
            });


            // $("#start-date").pickadate({
            //     format: 'mm/dd/yyyy',
            //     today: 'Today',
            //     clear: 'Clear',
            //     close: 'Close',
            // });

            // $('#end-date').pickadate({
            //     format: 'mm/dd/yyyy',
            //     today: 'Today',
            //     clear: 'Clear',
            //     close: 'Close',
            // });

        });

        const enableDate = () => {
            const hp_filter = document.querySelector('#hospital-filter');
            const start_date = document.querySelector('#start-date');
            const end_date = document.querySelector('#end-date');

            if(hp_filter != ''){
                start_date.removeAttribute('disabled');
                end_date.removeAttribute('disabled');
            }else{
                start_date.setAttribute('disabled', true);
                start_date.value = '';
                end_date.setAttributte('disabled', true);
                end_date.value = '';
            }
        }

        const validateDateRange = () => {
            const startDateInput = document.querySelector('#start-date');
            const endDateInput = document.querySelector('#end-date');
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);



            if (startDateInput.value === '' || endDateInput.value === '') {
                return; // Don't do anything if either input is empty
            }

            if (endDate < startDate) {
                // alert('End date must be greater than or equal to the start date');
                swal({
                    title: 'Failed',
                    text: 'End date must be greater than or equal to the start date',
                    // timer: 4000,
                    showConfirmButton: true,
                    type: 'error'
                });
                endDateInput.value = '';
                return;
            }          
        }

        const add_payment = () => {
            $('#addPaymentModal').modal('show');
        }


    </script>
           