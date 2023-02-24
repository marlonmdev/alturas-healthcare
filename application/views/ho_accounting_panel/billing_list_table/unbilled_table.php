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
                    Unbilled
                </li>
                </ol>
            </nav>
            </div>
        </div>
        </div>
    </div>
        <hr>
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- Start of Container fluid  -->
    <div class="container-fluid">
        <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
        <div class="col-lg-4 pt-0 pb-2 ps-3">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text bg-info text-white">
                  <i class="mdi mdi-filter"></i>
                </span>
              </div>
              <select class="form-select" name="hospital-filter" id="hospital-filter">
                    <option value="">Select Hospital</option>
                    <?php foreach($options as $option) : ?>
                    <option value="<?php echo $option['hp_id']; ?>"><?php echo $option['hp_name']; ?></option>
                    <?php endforeach; ?>
              </select>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <ul class="nav nav-tabs mb-4" role="tablist"> 
                    <li class="nav-item">
                        <a
                            class="nav-link active"
                            href="<?php echo base_url(); ?>head-office-accounting/billing-list"
                            role="tab"
                            ><span class="hidden-sm-up"></span>
                            <span class="hidden-xs-down fs-5 font-bold">Unbilled</span></a
                        >
                    </li>
                    <li class="nav-item">
                        <a
                            class="nav-link"
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
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover" id="unbilledTable">
                                    <thead>
                                        <tr>
                                            <td class="fw-bold">Billing #</td>
                                            <td class="fw-bold">Patient Name</td>
                                            <td class="fw-bold">Request Type</td>
                                            <!-- <td class="fw-bold">Healthcare Provider</td> -->
                                            <td class="fw-bold">Billed on</td>
                                            <td class="fw-bold">Charge</td>
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
    <script>
         const baseUrl = "<?php echo base_url(); ?>";
         $(document).ready(function(){
            let userTable = $('#unbilledTable').DataTable({
            processing: true, //Feature control the processing indicator.
            serverSide: true, //Feature control DataTables' server-side processing mode.
            order: [], //Initial no order.

            // Load data for the table's content from an Ajax source
            ajax: {
                url: `${baseUrl}head-office-accounting/billing-list/unbilled/fetch`,
                type: "POST",
                data: function(data) {
                data.token = '<?php echo $this->security->get_csrf_hash(); ?>';
                data.filter = $('#hospital-filter').val();
                }
            },

            //Set column definition initialisation properties.
            columnDefs: [{
                "targets": [5], //4th, 5th, and 6th column / numbering column
                "orderable": false, //set not orderable
            }, ],
            responsive: true,
            fixedHeader: true,
            });

            $('#hospital-filter').change(function(){
                userTable.draw();
            });
         });
    </script>
           