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

                    <div class="dropdown">
                        <li class="nav-item">
                            <button class="btn dropdown-toggle active" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="hidden-sm-up"></span>
                                <span class="hidden-xs-down fs-5 font-bold" style="color:#2359fc">Unbilled</span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item fw-bold" href="<?php echo base_url(); ?>head-office-accounting/billing-list/unbilled/loa">LOA</a></li>
                                <li><a class="dropdown-item fw-bold" href="<?php echo base_url(); ?>head-office-accounting/billing-list/unbilled/noa">NOA</a></li>
                            </ul>
                        </li>
                    </div>
                </ul>
            </div>
        </div>  
    </div>
    <style>
        .dropdown-item:hover {
            background-color: #5f86fa;
        }
    </style>
           