<!-- Start of Page Wrapper -->
<div class="page-wrapper">
        <!-- Bread crumb and right sidebar toggle -->
        <div class="page-breadcrumb">
            <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle fw-bold" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        Select Reports
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="<?php echo base_url();?>head-office-accounting/reports">Paid Bill</a></li>
                        <li><a class="dropdown-item" href="<?php echo base_url();?>head-office-accounting/reports/charging">BU Charging</a></li>
                        <li><a class="dropdown-item" href="#">Letter of Authorization</a></li>
                        <li><a class="dropdown-item" href="#">Notice of Admission</a></li>
                    </ul>
                </div>
                <div class="ms-auto text-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item">Head Office Accounting</li>
                        <li class="breadcrumb-item active" aria-current="page">
                       Cash Advance
                        </li>
                        </ol>
                    </nav>
                </div>
            </div>
            </div> 
        </div><hr>
           <!-- End Bread crumb and right sidebar toggle -->
        <!-- Start of Container fluid  -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 ps-5 pb-3 pt-1 pb-4">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-white fw-bold bg-info">
                            <i class="mdi mdi-filter"></i>
                            </span>
                        </div>
                        <select class="form-select fw-bold" name="b-units-filter" id="b-units-filter">
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
            </div>
            <div class="card shadow">  
                <div class="pt-4 ps-4 pe-4">
                    <table class="table table-sm" id="tableReports">
                        <thead class="border-secondary border-1 border-0 border-top border-bottom">
                            <tr>
                                <th class="fw-bold">Patient Name</th>
                                <th class="fw-bold">Business Unit</th>
                                <th class="fw-bold">Total Cash Advance</th>
                                <th class="fw-bold">Status</th>
                            </tr>
                        </thead>
                    </table>    
                </div>
            
            </div>
        </div>
</div>
<script>
 $(document).ready(function(){

    $("#start-date").flatpickr({
        dateFormat: 'Y-m-d',
    });
    $("#end-date").flatpickr({
        dateFormat: 'Y-m-d',
    });
 });
</script>