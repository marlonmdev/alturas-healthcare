
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
                        LOA for Completion 
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
                <form method="POST" action="<?php echo base_url(); ?>healthcare-coordinator/loa/requests-list/approved" id="search-form-1" class="needs-validation" novalidate>
                    <div class="input-group">
                        <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash(); ?>">
                        <input type="hidden" name="loa-id" value="<?php echo $hp_id ?>">
                        <input type="hidden" name="loa-id" value="<?php echo $loa_id ?>">
                        <button type="submit" class="btn btn-outline-dark" data-bs-toggle="tooltip" title="Click to Go Back">
                            <strong class="ls-2" style="vertical-align:middle">
                                <i class="mdi mdi-arrow-left-bold"></i> Go Back
                            </strong>
                        </button>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <label class="fw-bold">Member's Name : </label>
                    <input class="form-control text-danger fw-bold fs-5" type="text" name="member-name" id="member-name" value="<?php echo $full_name ?>" readonly>
                </div>
                <div class="col-lg-3">
                    <label class="fw-bold">LOA Number : </label>
                    <input class="form-control text-danger fw-bold fs-5" type="text" name="loa-num" id="loa-num" value="<?php echo $loa_no?>" readonly>
                </div>     
                <div class="col-lg-5">
                    <label class="fw-bold">Healthcare Provider : </label>
                    <input class="form-control text-danger fw-bold fs-5" type="text" name="loa-num" id="loa-num" value="<?php echo $hc_provider ?>" readonly>
                </div>
            </div>
            <hr>
            <div class="card pt-1 shadow">
                <div class="card-body">
                    <div class="row">

                        <?php 
                            /* Exploding the string into an array and then checking if the array
                            contains the value. */
                            $selectedOptions = explode(';', $med_services);
                            foreach ($cost_types as $cost_type) :
                                if (in_array($cost_type['ctype_id'], $selectedOptions)) :
                        ?>
                            <input type="hidden" name="ctype_id" value="<?php echo $cost_type['ctype_id']; ?>">
                            <div class="col-lg-3">
                                <label class="fw-bold">Medical Services : </label>
                                <input type="text" class="form-control fw-bold ls-1" name="ct-name[]" value="<?php echo $cost_type['item_description']; ?>" readonly>
                            </div>

                            <div class="col-lg-1">
                                <label class="fw-bold">Service Fee: </label>
                                <input type="text" class="ct-fee form-control fw-bold ls-1" name="ct-fee[]" value="<?php echo $cost_type['op_price']; ?>" min="0" required>
                            </div>
                        <?php 
                                endif;
                            endforeach;
                        ?>
                   
                        <div class="col-lg-1">
                            <label class="fw-bold">Quantity : </label>
                            <input class="form-control" name="quantity" id="quantity" type="number" min="1" value="1">
                        </div>
                        <div class="col-lg-2">
                            <label class="fw-bold">Status: </label>
                            <select class="form-control fw-bold" name="status" id="status">
                                <option valu="">Select...</option>
                                <option value="performed">Performed</option>
                                <option value="not yet">Not yet performed</option>
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="fw-bold">Date : </label>
                            <input class="form-control" name="date" id="date" type="date">
                        </div>
                        <div class="col-lg-3">
                            <label class="fw-bold">Physician : </label>
                            <input class="form-control" name="physician" id="physician">
                        </div>
                    </div>
                    <div class="offset-10 pt-4">
                        <button class="btn btn-success fw-bold fs-4" type="button" name="submit" id="submit"><i class="mdi mdi-near-me"></i> Submit</button>
                    </div>
                </div>

            </div>
    </div>
    <!-- End Container fluid  -->
    
</div>
<!-- End Wrapper -->
