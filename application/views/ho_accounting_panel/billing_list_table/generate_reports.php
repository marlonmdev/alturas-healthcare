<!-- Start of Page Wrapper -->
<div class="page-wrapper">
        <!-- Bread crumb and right sidebar toggle -->
        <div class="page-breadcrumb">
            <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title ls-2"> Reports</h4>
                <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item">Head Office Accounting</li>
                    <li class="breadcrumb-item active" aria-current="page">
                    Print Reports
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
                        <select class="form-select fw-bold" name="billed-hospital-filter" id="billed-hospital-filter" onchange="displayValue()">
                            <option value="">Select Hospital...</option>
                            <?php foreach($hc_provider as $hospital) : ?>
                            <option value="<?php echo $hospital['hp_id']; ?>"><?php echo $hospital['hp_name']; ?></option>
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
                                <!-- <th class="fw-bold">LOA/NOA No.</th> -->
                                <th class="fw-bold">Patient Name</th>
                                <th class="fw-bold">Business Unit</th>
                                <th class="fw-bold">Remaining MBL</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            
            </div>
        </div>
</div>