<!-- Start of Page Wrapper -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title ls-2">LOA</h4>
                <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item">Healthcare Coordinator</li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Match LOA  
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
            <div class="col-12 mb-4 mt-0">
                <div class="input-group">
                    <a href="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/completed" type="submit" class="btn btn-outline-dark" data-bs-toggle="tooltip" title="Click to Go Back">
                        <strong class="ls-2" style="vertical-align:middle">
                            <i class="mdi mdi-arrow-left-bold"></i> Go Back
                        </strong>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 border border-light">
                    <div class="row pt-3 pb-3">
                        <div class="col-lg-7">
                            <label>Patient's Name: </label>
                            <input name="patient-name" type="text" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="col-lg-7">
                            <label>Medical Services: </label>
                            <label name="med-services[]" readonly></label>
                        </div>
                        <div class="col-lg-3">
                            <label>Amount: </label>
                            <label name="amount[]" readonly></label>
                        </div>
                        <hr>
                    </div>
                    <div class="col-lg-3">
                        <label name="total-services"></label>
                    </div>
                    
                    <div class="row pt-4">
                        <div class="col-lg-7">
                            <label>Deductions: </label>
                            <label name="deductions[]" readonly></label>
                        </div>
                        <div class="col-lg-3">
                            <label class="pt-2" name="deduct-amount[]" readonly></label>
                        </div>
                        <hr>
                    </div>
                    <div class="col-lg-3">
                        <label name="total-deductions"></label>
                    </div>
                    <hr>
                    <label name="net-bill"></label>
                </div>
                <div class="col-lg-6 border">

                </div>
            </div>
            
                
            
    </div>
    <!-- End Container fluid  -->
</div>
<!-- End Wrapper -->

<script>

</script>
