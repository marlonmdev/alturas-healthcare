<!-- Start of Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
        <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title"><i class="mdi mdi-file-document-box"></i> Charging Monitoring</h4>
            <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item">Head Office Accounting</li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Unpaid Charge
                    </li>
                    </ol>
                </nav>
            </div>
        </div>
        </div>
    </div>
    <!-- End Bread crumb and right sidebar toggle -->
    <div class="container-fluid">
        <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
       <div class="row">
            <div class="col-lg-12 pb-1">
                <ul class="nav nav-tabs mb-4" role="tablist"> 
                    <li class="nav-item">
                        <a
                            class="nav-link active"
                            href="<?php echo base_url(); ?>head-office-accounting/charging/business-unit"
                            role="tab"
                            ><span class="hidden-sm-up"></span>
                            <span class="hidden-xs-down fs-5 font-bold">BU Charges</span></a
                        >
                    </li>
                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="<?php echo base_url(); ?>head-office-accounting/charging/business-unit/for-payment"
                            role="tab"
                            ><span class="hidden-sm-up"></span>
                            <span class="hidden-xs-down fs-5 font-bold">Receivables</span></a
                        >
                    </li>
                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="<?php echo base_url(); ?>head-office-accounting/charging/business-unit/paid"
                            role="tab"
                            ><span class="hidden-sm-up"></span>
                            <span class="hidden-xs-down fs-5 font-bold">Paid Charges</span></a
                        >
                    </li>
                </ul>
            </div>
            <div class="col-lg-5 ps-5 pb-2">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-dark text-white">
                        <i class="mdi mdi-filter"></i>
                        </span>
                    </div>
                    <select class="form-select fw-bold" name="charging-bu-filter" id="charging-bu-filter">
                        <option value="">Select Business Units...</option>
                        <?php
                            // Sort the business units alphabetically
                            $sorted_bu = array_column($bu, 'business_unit');
                            asort($sorted_bu);
                            
                            foreach($sorted_bu as $bu) :
                        ?>
                        <option value="<?php echo $bu; ?>"><?php echo $bu; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-lg-3">
                <button class="btn btn-danger rounded-pill btn-sm fs-6" type="button" id="print-btn" onclick="forCollection()" title="tag charges for receivables"><i class="mdi mdi-printer"></i> CONSOLIDATE</button>
            </div>
       </div>
        <br>
        <div class="card bg-light">
            <div class="card-body">
                <div class=" table-responsive">
                    <table class="table table-hover" id="chargeTable">
                        <thead style="background-color:#00538C">
                            <tr>
                                <td class="text-white">Healthcard No.</td>
                                <td class="text-white">Member</td>
                                <td class="text-white">Business Unit</td>
                                <td class="text-white">Company Charge</td>
                                <td class="text-white">Healthcare Advance</td>
                                <td class="text-white">Total Charge</td>
                                <td class="text-white">Status</td>
                                <td class="text-white">Action</td>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <td></td>
                            <td></td>
                            <td><span class="fw-bold fs-5">TOTAL</span></td>
                            <td><span class="fw-bold fs-5" id="total-company"></span></td>
                            <td><span class="fw-bold fs-5" id="total-advance"></span></td>
                            <td><span class="fw-bold fs-5" id="total-payable"></span></td>
                            <td></td>
                            <td></td>
                        </tfoot>
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
            let chargingTable = $('#chargeTable').DataTable({
                processing: true, //Feature control the processing indicator.
                serverSide: true, //Feature control DataTables' server-side processing mode.
                order: [], //Initial no order.

                // Load data for the table's content from an Ajax source
                ajax: {
                    url: `${baseUrl}head-office-accounting/charging/business-units/fetch`,
                    type: "POST",
                    data: function(data) {
                        data.token     = '<?php echo $this->security->get_csrf_hash(); ?>';
                        data.filter    = $('#charging-bu-filter').val();
                    },
                
                },

                //Set column definition initialisation properties.
                columnDefs: [{
                    "targets": [5], // 5th column / numbering column
                    "orderable": false, //set not orderable
                }, ],
                info: false,
                paging: false,
                filter: false,
                lengthChange: false,
                responsive: true,
                fixedHeader: true,
                });

            $('#charging-bu-filter').change(function(){
                chargingTable.draw();
            });

                      
        chargingTable.on('draw.dt', function() {
            let columnIndices = [3, 4, 5]; // Array of column indices to calculate sum
            let sums = [0, 0, 0]; // Array to store the sums for each column

            if ($('#chargeTable').DataTable().data().length > 0) {
                // The table is not empty
                chargingTable.rows().nodes().each(function(index, row) {
                let rowData = chargingTable.row(row).data();

                columnIndices.forEach(function(columnIdx, idx) {
                    let columnValue = rowData[columnIdx];
                    let pattern = /-?[\d,]+(\.\d+)?/g;
                    let matches = columnValue.match(pattern);

                    if (matches && matches.length > 0) {
                    let numberString = matches[0].replace(/,/g, '');
                    let floatValue = parseFloat(numberString);
                    sums[idx] += floatValue;
                    }
                });
                });
            }

            let sumColumn1 = sums[0];
            let sumColumn2 = sums[1];
            let sumColumn3 = sums[2];

            $('#total-company').html(sumColumn1.toLocaleString('PHP', { minimumFractionDigits: 2 }));
            $('#total-advance').html(sumColumn2.toLocaleString('PHP', { minimumFractionDigits: 2 }));
            $('#total-payable').html(sumColumn3.toLocaleString('PHP', { minimumFractionDigits: 2 }));
        });
            
        });

        const forCollection = () => {
            const bu_filter = document.querySelector('#charging-bu-filter').value;

            $.confirm({
                    title: '<strong>Confirmation!</strong>',
                    content: 'Are you sure? Please review before you proceed.',
                    type: 'blue',
                    buttons: {
                        confirm: {
                            text: 'Yes',
                            btnClass: 'btn-blue',
                            action: function(){

                                $.ajax({
                                    url: `${baseUrl}head-office-accounting/charging/tag-to-collect`,
                                    type: 'POST',
                                    data: {
                                        'token': '<?php echo $this->security->get_csrf_hash(); ?>',
                                        'bu_filter' : bu_filter,
                                    },
                                    dataType: 'json',
                                    success: function(response){
                                        const { 
                                            token,charging_no,status,message
                                        } = response;

                                        if(status == 'success'){
                                            swal({
                                                title: 'Success',
                                                text: message,
                                                timer: 3000,
                                                showConfirmButton: false,
                                                type: 'success'
                                            });
                                            printBUCharging(charging_no);
                                        }
                                        if(status == 'failed'){
                                            swal({
                                                title: 'Error',
                                                text: message,
                                                timer: 3000,
                                                showConfirmButton: true,
                                                type: 'error'
                                            });
                                        }
                                    }
                                });
                            },
                        },
                        cancel: {
                            btnClass: 'btn-dark',
                            action: function() {
                                // close dialog
                            }
                        },
                    }
                });
           
        }

        const printBUCharging = (charging_no) => {
            const bu_filters = document.querySelector('#charging-bu-filter').value;

            if(bu_filters != ''){
                bu_filter = bu_filters;
            }else{
                bu_filter = 'none';
            }

            var base_url = `${baseUrl}`;
            var win = window.open(base_url + "printBUCharge/pdfBUCharging/" + btoa(bu_filter) + "/" + btoa(charging_no), '_blank');
        }


    </script>