<!-- Start of Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
        <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title"><i class="mdi mdi-receipt pe-1">
                </i>Payment History</h4>
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
                                <td class="fw-bold">Payment Code</td>
                                <td class="fw-bold">Payment Date</td>
                                <td class="fw-bold">Hospital Bank Account</td>
                                <td class="fw-bold">Hospital Account Number</td>
                                <td class="fw-bold">Hospital Account Name</td>
                                <td class="fw-bold">Payment Type</td>
                                <td class="fw-bold">Status</td>
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