<!-- Start of Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title"><i class="mdi mdi-format-line-style"></i> Charging Details</h4>
                <div class="ms-auto text-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item">Head Office Accounting</li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Business Unit Charging
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
        <input type="hidden" id="details-emp-id" value="<?php echo $emp_id; ?>">
        <div class="col-2 pb-1 offset-10">
            <div class="input-group">
                <a href="<?php echo base_url(); ?>head-office-accounting/charging/business-unit" type="submit" class="btn btn-info" data-bs-toggle="tooltip" title="Click to Go Back">
                    <strong class="ls-2" style="vertical-align:middle">
                        <i class="mdi mdi-arrow-left-bold"></i> Go Back
                    </strong>
                </a>
            </div>
        </div><br>
        <div class="row gap-4 ps-4">
            <div class="col-3 border border shadow bg-light pt-2 ps-3 pe-2">
                <div class="d-flex flex-column align-items-center text-center">
                    <?php if($member['gender'] == 'Male' || $member['gender'] == 'male'): ?>
                      <img src="<?= base_url() . 'assets/images/male_avatar.svg' ?>" alt="Member" class="img-responsive" width="150" height="auto">
                    <?php endif; ?>

                    <?php if($member['gender'] == 'Female' || $member['gender'] == 'female'): ?>
                      <img src="<?= base_url() . 'assets/images/female_avatar.svg' ?>" alt="Member" class="img-responsive" width="150" height="auto">
                    <?php endif; ?>

                  <div class="mt-3">
                    <p class="mb-1 fs-4 text-info"><strong> <?= $member['first_name'] . ' ' . $member['middle_name'] . ' ' . $member['last_name'] . ' ' . $member['suffix'] ?></strong></p>
                    <p class="mb-1"><strong><?= $member['business_unit']; ?></strong></p>
                    <p class="mb-1"><strong><?= $member['dept_name']; ?></strong></p>
                    <p class="text-success mb-1"><strong><?= $member['position']; ?></strong></p>
                    <p class="mb-1"><strong><?= $member['emp_type']; ?></strong></em>
                    <p class="text-muted font-size-sm"><span class="badge rounded-pill bg-success"><strong><?= $member['current_status']; ?></strong></span></p>
                  </div>
                </div>
            </div>
           
            <div class="card bg-light col-8">
                <div class="card-body">
                    <div class=" table-responsive">
                        <table class="table table-hover" id="detailsTable">
                            <thead style="background-color:#17a1d4">
                                <tr>
                                    <td class="text-white">Billing No.</td>
                                    <td class="text-white">LOA/NOA No.</td>
                                    <td class="text-white">Company Charge</td>
                                    <td class="text-white">Healthcare Advance</td>
                                    <td class="text-white">Total Charge</td>
                                    <td class="text-white">Status</td>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td class="fw-bold">TOTAL</td>
                                    <td><span class="fw-bold fs-5" id="total-company"></span></td>
                                    <td><span class="fw-bold fs-5" id="total-advance"></span></td>
                                    <td><span class="fw-bold fs-5" id="total-payable"></span></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>
 <?php include 'view_payment_details.php' ?>
<script>
        const baseUrl = "<?php echo base_url(); ?>";
        $(document).ready(function(){
            let chargingTable = $('#detailsTable').DataTable({
                processing: true, //Feature control the processing indicator.
                serverSide: true, //Feature control DataTables' server-side processing mode.
                order: [], //Initial no order.

                // Load data for the table's content from an Ajax source
                ajax: {
                    url: `${baseUrl}head-office-accounting/charging/business-units/details/fetch`,
                    type: "POST",
                    data: function(data) {
                        data.token     = '<?php echo $this->security->get_csrf_hash(); ?>';
                        data.emp_id    = $('#details-emp-id').val();
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
                
        chargingTable.on('draw.dt', function() {
            let columnIndices = [2, 3, 4]; // Array of column indices to calculate sum
            let sums = [0, 0, 0]; // Array to store the sums for each column

            if ($('#detailsTable').DataTable().data().length > 0) {
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
    </script>